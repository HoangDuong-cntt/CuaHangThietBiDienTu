<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductComparisonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductComparisonController extends Controller
{
    public function __construct(
        protected ProductComparisonService $comparisonService
    ) {}

    /**
     * Giao diện trang so sánh đối chiếu sản phẩm
     */
    public function index(Request $request): View
    {
        if ($request->has('ids')) {
            $idsParam = (string) $request->query('ids', '');
            $productIds = array_filter(array_map('intval', explode(',', $idsParam)));
        } else {
            $productIds = [1, 2]; // Mặc định so sánh 2 mẫu Laptop cùng loại hot nhất
        }

        $data = $this->comparisonService->compare($productIds);

        return view('client.compare.index', [
            'products' => $data['products'] ?? [],
            'coreSpecs' => $data['core_specs'] ?? [],
            'specGroups' => $data['spec_groups'] ?? [],
            'rootCategory' => $data['root_category'] ?? null,
            'allowedCategoryIds' => $data['allowed_category_ids'] ?? [],
            'mismatchedRemoved' => $data['mismatched_removed'] ?? false,
            'currentIds' => implode(',', array_column($data['products'] ?? [], 'id')),
        ]);
    }

    /**
     * API JSON so sánh sản phẩm (Dành cho AJAX modal hoặc app)
     */
    public function apiCompare(Request $request): JsonResponse
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_map('intval', explode(',', $ids));
        }

        $data = $this->comparisonService->compare((array) $ids);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * API tìm kiếm nhanh sản phẩm để thêm vào bảng so sánh (Ràng buộc cùng danh mục)
     */
    public function searchForCompare(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));
        $baseId = $request->query('base_id');
        $categoryId = $request->query('category_id');

        $query = Product::with(['brand', 'images', 'category'])
            ->where('is_active', true);

        // Lọc nghiêm ngặt: chỉ cho phép các sản phẩm thuộc cùng họ danh mục gốc với thiết bị chuẩn
        if (! empty($baseId)) {
            $baseProduct = Product::with('category')->find($baseId);
            if ($baseProduct?->category) {
                $allowedIds = $baseProduct->category->getFamilyIds();
                $query->whereIn('category_id', $allowedIds);
            }
        } elseif (! empty($categoryId)) {
            $category = Category::find($categoryId);
            if ($category) {
                $allowedIds = $category->getFamilyIds();
                $query->whereIn('category_id', $allowedIds);
            }
        }

        if (strlen($keyword) >= 1) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku_master', 'like', "%{$keyword}%");
            });
        }

        $results = $query->limit(8)->get()->map(fn (Product $p) => [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku_master,
            'brand' => $p->brand?->name,
            'category' => $p->category?->name,
            'category_id' => $p->category_id,
            'root_category_id' => $p->category?->getRoot()->id,
            'root_category_name' => $p->category?->getRoot()->name,
            'price' => number_format($p->current_price, 0, ',', '.').' đ',
            'image_url' => $p->images->first()?->image_url ?? '/images/placeholder.svg',
        ]);

        return response()->json(['results' => $results]);
    }
}
