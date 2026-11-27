<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['shipping', 'cancelled'],
        'shipping' => ['delivered'],
        'delivered' => ['completed'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function index(Request $request): View
    {
        $this->authorizeStaff($request);

        $validated = $request->validate([
            'status' => ['nullable', 'in:pending,confirmed,shipping,delivered,completed,cancelled'],
        ]);

        $orders = Order::query()
            ->with('user')
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', ['orders' => $orders]);
    }

    public function show(Request $request, string $orderCode): View
    {
        $this->authorizeStaff($request);

        $order = Order::query()
            ->with(['user', 'items.variant.product', 'statusHistories.changedBy', 'paymentTransactions'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('admin.orders.show', [
            'order' => $order,
            'nextStatuses' => self::TRANSITIONS[$order->status] ?? [],
        ]);
    }

    public function invoice(Request $request, string $orderCode): Response
    {
        $this->authorizeStaff($request);

        $order = Order::query()
            ->with(['user', 'items', 'statusHistories'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return Pdf::loadView('pdf.invoice', ['order' => $order])
            ->setPaper('a4')
            ->download('invoice-'.$order->order_code.'.pdf');
    }

    public function updateStatus(Request $request, string $orderCode): RedirectResponse
    {
        $this->authorizeStaff($request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipping,delivered,completed,cancelled'],
            'cancel_reason' => ['required_if:status,cancelled', 'nullable', 'string', 'max:255'],
        ]);

        $order = DB::transaction(function () use ($request, $orderCode, $validated): Order {
            $order = Order::query()
                ->where('order_code', $orderCode)
                ->lockForUpdate()
                ->firstOrFail();
            $nextStatus = $validated['status'];
            $allowedTransitions = self::TRANSITIONS[$order->status] ?? [];

            if (! in_array($nextStatus, $allowedTransitions, true)) {
                throw ValidationException::withMessages([
                    'status' => 'Không thể chuyển đơn hàng sang trạng thái đã chọn.',
                ]);
            }

            if ($nextStatus === 'cancelled' && $order->payment_status === 'paid') {
                throw ValidationException::withMessages([
                    'status' => 'Đơn đã thanh toán cần hoàn tiền trước khi hủy.',
                ]);
            }

            if (
                $order->payment_method === 'vnpay'
                && $order->payment_status !== 'paid'
                && in_array($nextStatus, ['confirmed', 'shipping', 'delivered', 'completed'], true)
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Chỉ xử lý giao đơn VNPay sau khi thanh toán được xác nhận.',
                ]);
            }

            if ($nextStatus === 'cancelled') {
                $items = $order->items()->orderBy('product_variant_id')->get();

                foreach ($items as $item) {
                    ProductVariant::query()
                        ->whereKey($item->product_variant_id)
                        ->lockForUpdate()
                        ->firstOrFail()
                        ->increment('stock_quantity', $item->quantity);
                }

                $order->paymentTransactions()
                    ->where('status', 'pending')
                    ->update(['status' => 'expired']);
                $order->cancel_reason = $validated['cancel_reason'];
            }

            $order->status = $nextStatus;
            if ($nextStatus === 'delivered') {
                $deliveredAt = now();
                $order->delivered_at = $deliveredAt;

                foreach ($order->items()->with('variant.product')->get() as $item) {
                    $item->update([
                        'warranty_expires_at' => $deliveredAt
                            ->copy()
                            ->addMonths($item->variant->product->warranty_months)
                            ->toDateString(),
                    ]);
                }

                if ($order->payment_method === 'cod') {
                    $order->payment_status = 'paid';
                }
            }

            $order->save();

            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'status' => $nextStatus,
                'description' => $nextStatus === 'cancelled'
                    ? $validated['cancel_reason']
                    : 'Trạng thái đơn hàng được cập nhật.',
                'changed_by' => $request->user()->getKey(),
            ]);

            return $order;
        }, attempts: 3);

        if (in_array($order->status, ['confirmed', 'cancelled'], true)) {
            Mail::to($order->user->email)->queue(new OrderStatusUpdatedMail($order));
        }

        return redirect()
            ->route('admin.orders.show', $orderCode)
            ->with('status', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function lowStock(Request $request): View
    {
        $this->authorizeStaff($request);

        $variants = ProductVariant::query()
            ->with('product')
            ->where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->paginate(20);

        return view('admin.inventory.low-stock', ['variants' => $variants]);
    }

    private function authorizeStaff(Request $request): void
    {
        abort_unless(
            in_array($request->user()->role, ['admin', 'staff'], true),
            403,
        );
    }
}
