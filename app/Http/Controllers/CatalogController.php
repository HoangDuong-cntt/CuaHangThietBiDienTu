<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RecentlyViewed;
use App\Models\Wishlist;
use App\Services\ProductFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        protected ProductFilterService $filterService
    ) {}

    /**
     * Trang chủ cửa hàng (Home)
     */
    public function home(): View
    {
        $featuredProducts = $this->filterService->getFeaturedTabsProducts('featured', 4);
        $facets = $this->filterService->getFilterFacets();

        $categoryCards = [
            [
                'name' => 'Máy tính xách tay',
                'slug' => 'laptop',
                'icon' => 'fa-solid fa-laptop',
                'count' => 125,
            ],
            [
                'name' => 'Điện thoại',
                'slug' => 'dien-thoai',
                'icon' => 'fa-solid fa-mobile-screen-button',
                'count' => 36,
            ],
            [
                'name' => 'Máy tính bảng',
                'slug' => 'may-tinh-bang',
                'icon' => 'fa-solid fa-tablet-screen-button',
                'count' => 17,
            ],
            [
                'name' => 'Màn hình & Trạm',
                'slug' => 'laptop-do-hoa',
                'icon' => 'fa-solid fa-desktop',
                'count' => 28,
            ],
            [
                'name' => 'Âm thanh',
                'slug' => 'am-thanh',
                'icon' => 'fa-solid fa-headphones',
                'count' => 37,
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'icon' => 'fa-solid fa-keyboard',
                'count' => 35,
            ],
        ];

        return view('client.home', [
            'featuredProducts' => $featuredProducts,
            'categoryCards' => $categoryCards,
            'categories' => $facets['categories'],
        ]);
    }

    /**
     * API tải sản phẩm theo tab trên trang chủ (Nổi bật, Học tập, Năng lực, Thiết kế)
     */
    public function apiFeaturedTabs(Request $request): JsonResponse
    {
        $tab = (string) $request->query('tab', 'featured');
        $products = $this->filterService->getFeaturedTabsProducts($tab, 4);

        return response()->json([
            'success' => true,
            'tab' => $tab,
            'products' => $products,
        ]);
    }

    /**
     * Trang Cửa Hàng / Danh Mục Sản Phẩm (Shop/Catalog)
     */
    public function index(Request $request): View
    {
        // 1. RÀNG BUỘC BẢO MẬT PHÂN TRANG (page phải là số nguyên dương >= 1)
        if ($request->has('page')) {
            $rawPage = (string) $request->query('page');
            if (! ctype_digit($rawPage) || (int) $rawPage < 1) {
                abort(404, 'Tham số phân trang không hợp lệ: Số trang phải là số nguyên dương lớn hơn hoặc bằng 1.');
            }
        }

        // 2. RÀNG BUỘC BẢO MẬT DANH MỤC (Phải hợp lệ và tồn tại trong CSDL)
        if ($request->filled('category')) {
            $catSlug = (string) $request->query('category');
            if (! preg_match('/^[a-zA-Z0-9\-_]+$/', $catSlug)) {
                abort(400, 'Tham số danh mục chứa ký tự không hợp lệ.');
            }

            $categoryExists = Category::where('slug', $catSlug)->exists();
            if (! $categoryExists) {
                abort(404, 'Danh mục thiết bị "'.e($catSlug).'" không tồn tại trong hệ thống.');
            }
        }

        // 3. RÀNG BUỘC BẢO MẬT KHOẢNG GIÁ (Không được âm và min <= max)
        if ($request->filled('min_price')) {
            $minPrice = $request->query('min_price');
            if (! is_numeric($minPrice) || (float) $minPrice < 0) {
                abort(400, 'Giá tối thiểu không hợp lệ (phải là số >= 0).');
            }
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->query('max_price');
            if (! is_numeric($maxPrice) || (float) $maxPrice < 0) {
                abort(400, 'Giá tối đa không hợp lệ (phải là số >= 0).');
            }
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $normMin = (float) $request->query('min_price');
            $normMax = (float) $request->query('max_price');
            $normMin = $normMin < 1000 ? $normMin * 1000000 : $normMin;
            $normMax = $normMax < 1000 ? $normMax * 1000000 : $normMax;
            if ($normMin > $normMax) {
                abort(400, 'Khoảng giá không hợp lệ: Giá tối thiểu không được lớn hơn giá tối đa.');
            }
        }

        // 4. RÀNG BUỘC TIÊU CHÍ SẮP XẾP (Whitelist)
        if ($request->filled('sort')) {
            $allowedSorts = ['newest', 'price_asc', 'price_desc', 'bestseller', 'weight_asc', 'battery_desc'];
            if (! in_array($request->query('sort'), $allowedSorts, true)) {
                abort(400, 'Tiêu chí sắp xếp không hợp lệ.');
            }
        }

        // 5. RÀNG BUỘC TỪ KHÓA TÌM KIẾM (Độ dài & XSS)
        if ($request->filled('q')) {
            $rawQ = (string) $request->query('q');
            if (mb_strlen($rawQ) > 100) {
                abort(400, 'Từ khóa tìm kiếm quá dài (tối đa 100 ký tự).');
            }
        }

        $filters = $request->only([
            'q', 'category', 'category_id', 'brands',
            'min_price', 'max_price', 'weight', 'battery', 'screen', 'sort',
        ]);

        $products = $this->filterService->filter($filters, 12);

        // 6. RÀNG BUỘC SỐ TRANG VƯỢT QUÁ TỔNG SỐ TRANG HIỆN CÓ
        if ($request->has('page')) {
            $requestedPage = (int) $request->query('page');
            if ($products->total() > 0 && $requestedPage > $products->lastPage()) {
                abort(404, 'Trang không tồn tại. Danh mục này chỉ có tối đa '.$products->lastPage().' trang.');
            }
        }

        $facets = $this->filterService->getFilterFacets();

        return view('client.shop.index', [
            'products' => $products,
            'categories' => $facets['categories'],
            'brands' => $facets['brands'],
            'priceBounds' => $facets['price_bounds'],
            'currentFilters' => $filters,
        ]);
    }

    /**
     * API Tìm kiếm Autocomplete nhanh
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $keyword = (string) $request->query('q', '');
        $results = $this->filterService->autocomplete($keyword, 6);

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    /**
     * API Lọc sản phẩm trả về JSON (Dành cho AJAX filter)
     */
    public function filterJson(Request $request): JsonResponse
    {
        if ($request->has('page')) {
            $rawPage = (string) $request->query('page');
            if (! ctype_digit($rawPage) || (int) $rawPage < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tham số phân trang không hợp lệ (page phải là số nguyên dương >= 1).',
                ], 400);
            }
        }

        $filters = $request->only([
            'q', 'category', 'category_id', 'brands',
            'min_price', 'max_price', 'weight', 'battery', 'screen', 'sort',
        ]);

        $products = $this->filterService->filter($filters, 12);

        return response()->json([
            'success' => true,
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'items' => $products->items(),
        ]);
    }

    /**
     * API Bật / Tắt yêu thích sản phẩm (Wishlist)
     */
    public function toggleWishlist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $productId = $validated['product_id'];
        $userId = $request->user()?->id;

        if ($userId) {
            $existing = Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->delete();

                return response()->json([
                    'success' => true,
                    'is_favorited' => false,
                    'message' => 'Đã xóa khỏi danh sách yêu thích.',
                ]);
            }

            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'is_favorited' => true,
                'message' => 'Đã thêm vào danh sách yêu thích.',
            ]);
        }

        // Lưu tạm vào Session cho khách vãng lai
        $sessionWishlist = $request->hasSession() ? $request->session()->get('guest_wishlist', []) : [];
        $index = array_search($productId, $sessionWishlist, true);

        if ($index !== false) {
            unset($sessionWishlist[$index]);
            $isFavorited = false;
        } else {
            $sessionWishlist[] = $productId;
            $isFavorited = true;
        }

        if ($request->hasSession()) {
            $request->session()->put('guest_wishlist', array_values($sessionWishlist));
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'count' => count($sessionWishlist),
            'message' => $isFavorited ? 'Đã thêm vào yêu thích.' : 'Đã xóa khỏi yêu thích.',
        ]);
    }

    /**
     * API Lấy danh sách sản phẩm yêu thích (Wishlist)
     */
    public function getWishlist(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;

        if ($userId) {
            $productIds = Wishlist::where('user_id', $userId)
                ->pluck('product_id')
                ->toArray();
        } else {
            $productIds = $request->hasSession() ? $request->session()->get('guest_wishlist', []) : [];
        }

        if (empty($productIds)) {
            return response()->json([
                'success' => true,
                'items' => [],
                'count' => 0,
            ]);
        }

        $items = Product::with(['brand', 'images', 'category'])
            ->whereIn('id', $productIds)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand?->name,
                'category' => $p->category?->name,
                'root_category_id' => $p->category?->getRoot()->id,
                'root_category_name' => $p->category?->getRoot()->name,
                'price' => number_format($p->current_price, 0, ',', '.').' đ',
                'image_url' => $p->images->first()?->image_url ?? '/images/placeholder.svg',
            ])->values();

        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => count($items),
        ]);
    }

    /**
     * API Lưu vết sản phẩm vừa xem (Recently Viewed)
     */
    public function recordRecentlyViewed(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'session_id' => ['nullable', 'string'],
        ]);

        $productId = $validated['product_id'];
        $sessionId = $validated['session_id'] ?? $request->session()->getId() ?? (string) Str::uuid();
        $userId = $request->user()?->id;

        RecentlyViewed::updateOrCreate(
            [
                'product_id' => $productId,
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * API Lấy danh sách sản phẩm vừa xem gần đây
     */
    public function getRecentlyViewed(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'guest';

        $query = RecentlyViewed::with(['product.brand', 'product.images', 'product.category'])
            ->orderByDesc('viewed_at')
            ->limit(8);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $items = $query->get()->map(fn ($rv) => [
            'id' => $rv->product?->id,
            'name' => $rv->product?->name,
            'slug' => $rv->product?->slug,
            'brand' => $rv->product?->brand?->name,
            'category' => $rv->product?->category?->name,
            'root_category_id' => $rv->product?->category?->getRoot()->id,
            'root_category_name' => $rv->product?->category?->getRoot()->name,
            'price' => $rv->product ? number_format($rv->product->current_price, 0, ',', '.').' đ' : '',
            'image_url' => $rv->product?->images->first()?->image_url ?? '/images/placeholder.svg',
        ])->filter(fn ($item) => ! empty($item['id']))->values();

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }
}
