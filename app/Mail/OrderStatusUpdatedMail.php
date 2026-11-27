<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cập nhật đơn hàng '.$this->order->order_code.': '.$this->statusLabel(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.orders.status-update',
            with: ['statusLabel' => $this->statusLabel()],
        );
    }

    private function statusLabel(): string
    {
        return match ($this->order->status) {
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            default => 'Đã cập nhật',
        };
    }
}
