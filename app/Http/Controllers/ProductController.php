<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request): View
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'variants']);

        // Category filter
        if ($categorySlug = $request->query('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                // If it's a parent category, include children IDs as well
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Brand filter
        if ($brandSlug = $request->query('brand')) {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Search keyword
        if ($keyword = $request->query('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku_master', 'like', "%{$keyword}%");
            });
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('sale_price', 'asc'),
            'price_desc' => $query->orderBy('sale_price', 'desc'),
            'popular' => $query->orderBy('total_sold', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->paginate(16)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::where('is_visible', true)
            ->orderBy('name')
            ->get();

        $currentCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;
        $currentBrand = $brandSlug ? Brand::where('slug', $brandSlug)->first() : null;

        return view('products.index', compact(
            'products',
            'categories',
            'brands',
            'currentCategory',
            'currentBrand',
            'sort'
        ));
    }

    /**
     * Display the specified product detail page.
     */
    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'brand',
                'category',
                'variants' => fn ($q) => $q->where('is_active', true),
                'images' => fn ($q) => $q->orderBy('sort_order'),
                'specifications.specification.group',
            ])
            ->firstOrFail();

        // Group specifications by group name
        $specificationsByGroup = $product->specifications
            ->groupBy(function ($prodSpec) {
                return $prodSpec->specification?->group?->name ?? 'Thông số chung';
            });

        // Related products in the same category
        $relatedProducts = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['brand', 'images'])
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'specificationsByGroup', 'relatedProducts'));
    }
}
