<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductFilterService
{
    /**
     * Lọc danh sách sản phẩm theo đa tiêu chí (Multi-facet filter)
     *
     * @param  array<string, mixed>  $filters
     */
    public function filter(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['brand', 'category', 'images', 'variants'])
            ->where('is_active', true);

        // 1. Lọc theo từ khóa (Keyword search)
        if (! empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku_master', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo danh mục (Category)
        if (! empty($filters['category'])) {
            $categorySlug = $filters['category'];
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                // Bao gồm cả danh mục con nếu là danh mục cha
                $categoryIds = Category::where('parent_id', $category->id)
                    ->pluck('id')
                    ->push($category->id);

                $query->whereIn('category_id', $categoryIds);
            }
        } elseif (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 3. Lọc theo thương hiệu (Brands - hỗ trợ chọn nhiều hãng)
        if (! empty($filters['brands'])) {
            $brandIds = is_array($filters['brands'])
                ? $filters['brands']
                : explode(',', (string) $filters['brands']);

            $query->whereIn('brand_id', array_filter($brandIds));
        }

        // 4. Lọc theo khoảng giá (Price Range)
        if (! empty($filters['min_price'])) {
            $minPrice = (float) $filters['min_price'];
            if ($minPrice > 0 && $minPrice < 1000) {
                $minPrice = $minPrice * 1000000;
            }
            $query->where(function (Builder $q) use ($minPrice) {
                $q->where(fn ($sub) => $sub->whereNotNull('sale_price')->where('sale_price', '>=', $minPrice))
                    ->orWhere(fn ($sub) => $sub->whereNull('sale_price')->where('base_price', '>=', $minPrice));
            });
        }

        if (! empty($filters['max_price'])) {
            $maxPrice = (float) $filters['max_price'];
            if ($maxPrice > 0 && $maxPrice < 1000) {
                $maxPrice = $maxPrice * 1000000;
            }
            $query->where(function (Builder $q) use ($maxPrice) {
                $q->where(fn ($sub) => $sub->whereNotNull('sale_price')->where('sale_price', '<=', $maxPrice))
                    ->orWhere(fn ($sub) => $sub->whereNull('sale_price')->where('base_price', '<=', $maxPrice));
            });
        }

        // 5. Lọc theo cân nặng (Weight ranges)
        if (! empty($filters['weight'])) {
            match ($filters['weight']) {
                'ultralight' => $query->where('weight_grams', '<', 1300), // Siêu nhẹ dưới 1.3kg
                'standard' => $query->whereBetween('weight_grams', [1300, 1800]), // Tiêu chuẩn 1.3 - 1.8kg
                'heavy' => $query->where('weight_grams', '>', 1800), // Máy trạm / Gaming > 1.8kg
                default => null,
            };
        }

        // 6. Lọc theo dung lượng Pin (Battery mAh)
        if (! empty($filters['battery'])) {
            match ($filters['battery']) {
                'low' => $query->where('battery_mah', '<', 4000),
                'medium' => $query->whereBetween('battery_mah', [4000, 5500]),
                'high' => $query->where('battery_mah', '>', 5500),
                default => null,
            };
        }

        // 7. Lọc theo kích thước màn hình (Screen size)
        if (! empty($filters['screen'])) {
            match ($filters['screen']) {
                'small' => $query->where('screen_size', '<', 14.0),
                'medium' => $query->whereBetween('screen_size', [14.0, 15.6]),
                'large' => $query->where('screen_size', '>', 15.6),
                default => null,
            };
        }

        // 8. Sắp xếp (Sorting)
        $sort = $filters['sort'] ?? 'newest';
        match ($sort) {
            'price_asc' => $query->orderByRaw('CASE WHEN sale_price IS NOT NULL AND sale_price > 0 THEN sale_price ELSE base_price END ASC'),
            'price_desc' => $query->orderByRaw('CASE WHEN sale_price IS NOT NULL AND sale_price > 0 THEN sale_price ELSE base_price END DESC'),
            'bestseller' => $query->orderByDesc('total_sold'),
            'weight_asc' => $query->whereNotNull('weight_grams')->orderBy('weight_grams', 'asc'),
            'battery_desc' => $query->whereNotNull('battery_mah')->orderBy('battery_mah', 'desc'),
            default => $query->orderByDesc('created_at'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Tìm kiếm gợi ý Autocomplete tốc độ cao
     *
     * @return array<int, array{id: int, name: string, slug: string, sku: string, price: string, sale_price: ?string, brand: ?string, image_url: string}>
     */
    public function autocomplete(string $keyword, int $limit = 6): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 1) {
            return [];
        }

        return Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku_master', 'like', "%{$keyword}%");
            })
            ->limit($limit)
            ->get()
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'sku' => $p->sku_master,
                'price' => number_format($p->base_price, 0, ',', '.').' đ',
                'sale_price' => $p->sale_price ? number_format($p->sale_price, 0, ',', '.').' đ' : null,
                'current_price_formatted' => number_format($p->current_price, 0, ',', '.').' đ',
                'brand' => $p->brand?->name,
                'image_url' => $p->images->first()?->image_url ?? '/images/placeholder.svg',
            ])
            ->all();
    }

    /**
     * Lấy danh sách sản phẩm theo tab hiển thị trang chủ (Nổi bật, Học tập, Năng lực, Thiết kế)
     *
     * @return array<int, array<string, mixed>>
     */
    public function getFeaturedTabsProducts(string $tab = 'featured', int $limit = 4): array
    {
        $query = Product::with(['brand', 'category', 'images', 'variants'])
            ->where('is_active', true);

        match ($tab) {
            'study', 'office' => $query->where(fn ($q) => $q->where('category_id', 2)->orWhere('category_id', 1)),
            'creative', 'design' => $query->where(fn ($q) => $q->where('category_id', 4)->orWhere('category_id', 1)),
            'entertainment', 'gaming' => $query->where(fn ($q) => $q->whereIn('category_id', [3, 9, 10])),
            default => $query->orderBy('total_sold', 'desc'),
        };

        $products = $query->take($limit)->get();
        if ($products->isEmpty()) {
            $products = Product::with(['brand', 'category', 'images', 'variants'])
                ->where('is_active', true)
                ->orderBy('total_sold', 'desc')
                ->take($limit)
                ->get();
        }

        return $products->map(fn (Product $p) => [
            'id' => $p->id,
            'default_variant_id' => $p->variants->first()?->id ?? $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'sku' => $p->sku_master,
            'brand' => $p->brand?->name,
            'category' => $p->category?->name,
            'base_price' => (float) $p->base_price,
            'sale_price' => $p->sale_price ? (float) $p->sale_price : null,
            'current_price' => $p->current_price,
            'price_formatted' => number_format($p->current_price, 0, ',', '.').'đ',
            'base_price_formatted' => number_format($p->base_price, 0, ',', '.').'đ',
            'is_on_sale' => $p->is_on_sale,
            'weight_grams' => $p->weight_grams,
            'battery_mah' => $p->battery_mah,
            'screen_size' => $p->screen_size,
            'image_url' => $p->images->first()?->image_url ?? '/images/placeholder.svg',
        ])->all();
    }

    /**
     * Lấy danh sách facets để hiển thị trên sidebar (Thương hiệu, Danh mục, Khoảng giá Min/Max)
     */
    public function getFilterFacets(): array
    {
        return [
            'categories' => Category::where('is_visible', true)
                ->whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->where('is_visible', true)->withCount('products')])
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
            'brands' => Brand::where('is_visible', true)
                ->withCount('products')
                ->orderBy('name')
                ->get(),
            'price_bounds' => [
                'min' => (float) Product::where('is_active', true)->min('sale_price') ?? 0,
                'max' => (float) Product::where('is_active', true)->max('base_price') ?? 100000000,
            ],
        ];
    }
}
