<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Specification;
use App\Models\SpecificationGroup;
use Illuminate\Support\Collection;

class ProductComparisonService
{
    /**
     * So sánh 2 hoặc 3 sản phẩm theo thông số kỹ thuật và luật so sánh
     *
     * @param  array<int>  $productIds
     * @return array{
     *     products: array,
     *     core_specs: array,
     *     spec_groups: array
     * }
     */
    public function compare(array $productIds): array
    {
        $productIds = array_slice(array_unique(array_filter($productIds)), 0, 3);

        if (empty($productIds)) {
            return [
                'products' => [],
                'core_specs' => [],
                'spec_groups' => [],
                'root_category' => null,
                'allowed_category_ids' => [],
                'mismatched_removed' => false,
            ];
        }

        $products = Product::with([
            'brand',
            'category',
            'images',
            'variants',
            'productSpecifications.specification.group',
        ])
            ->whereIn('id', $productIds)
            ->get()
            ->sortBy(fn (Product $p) => array_search($p->id, $productIds, true))
            ->values();

        if ($products->isEmpty()) {
            return [
                'products' => [],
                'core_specs' => [],
                'spec_groups' => [],
                'root_category' => null,
                'allowed_category_ids' => [],
                'mismatched_removed' => false,
            ];
        }

        // RÀNG BUỘC DANH MỤC: Thiết bị đầu tiên là thiết bị chuẩn / neo (Anchor Device).
        // Toàn bộ các thiết bị so sánh phía sau bắt buộc phải thuộc cùng nhóm danh mục gốc với thiết bị thứ nhất.
        $firstProduct = $products->first();
        $rootCategory = $firstProduct->category?->getRoot();
        $allowedCategoryIds = $firstProduct->category?->getFamilyIds() ?? [$firstProduct->category_id];

        $validProducts = $products->filter(function (Product $p) use ($allowedCategoryIds) {
            return in_array($p->category_id, $allowedCategoryIds, true);
        })->values();

        $mismatchedRemoved = ($validProducts->count() < $products->count());
        $products = $validProducts;

        $coreSpecs = $this->buildCoreSpecsComparison($products);
        $specGroups = $this->buildDetailedSpecsComparison($products);

        return [
            'products' => $products->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand?->name,
                'category' => $p->category?->name,
                'category_id' => $p->category_id,
                'root_category_id' => $p->category?->getRoot()->id,
                'root_category_name' => $p->category?->getRoot()->name,
                'image_url' => $p->images->first()?->image_url ?? '/images/placeholder.svg',
                'base_price' => (float) $p->base_price,
                'sale_price' => $p->sale_price ? (float) $p->sale_price : null,
                'current_price' => $p->current_price,
                'is_on_sale' => $p->is_on_sale,
                'warranty_months' => $p->warranty_months,
            ])->values()->all(),
            'core_specs' => $coreSpecs,
            'spec_groups' => $specGroups,
            'root_category' => [
                'id' => $rootCategory?->id,
                'name' => $rootCategory?->name ?? 'Thiết bị công nghệ',
            ],
            'allowed_category_ids' => $allowedCategoryIds,
            'mismatched_removed' => $mismatchedRemoved,
        ];
    }

    /**
     * So sánh các thông số cốt lõi trong bảng products (Giá, Pin, Trọng lượng, Màn hình, Bảo hành)
     *
     * @param  Collection<int, Product>  $products
     */
    protected function buildCoreSpecsComparison(Collection $products): array
    {
        $specs = [
            [
                'code' => 'price',
                'name' => 'Giá bán thực tế',
                'unit' => 'đ',
                'rule' => 'lower_is_better',
                'values' => [],
            ],
            [
                'code' => 'weight',
                'name' => 'Trọng lượng máy',
                'unit' => 'g',
                'rule' => 'lower_is_better',
                'values' => [],
            ],
            [
                'code' => 'battery',
                'name' => 'Dung lượng pin',
                'unit' => 'mAh',
                'rule' => 'higher_is_better',
                'values' => [],
            ],
            [
                'code' => 'screen',
                'name' => 'Kích thước màn hình',
                'unit' => 'inch',
                'rule' => 'none',
                'values' => [],
            ],
            [
                'code' => 'warranty',
                'name' => 'Thời gian bảo hành',
                'unit' => 'tháng',
                'rule' => 'higher_is_better',
                'values' => [],
            ],
        ];

        // Lấy giá trị số của từng sản phẩm
        foreach ($specs as &$spec) {
            $numericValues = [];

            foreach ($products as $p) {
                $val = match ($spec['code']) {
                    'price' => $p->current_price,
                    'weight' => $p->weight_grams,
                    'battery' => $p->battery_mah,
                    'screen' => $p->screen_size ? (float) $p->screen_size : null,
                    'warranty' => $p->warranty_months,
                };

                $numericValues[$p->id] = $val !== null ? (float) $val : null;
            }

            $bestId = $this->findBestProductId($numericValues, $spec['rule']);

            foreach ($products as $p) {
                $raw = $numericValues[$p->id];
                $formatted = match ($spec['code']) {
                    'price' => $raw !== null ? number_format($raw, 0, ',', '.').' đ' : 'Chưa cập nhật',
                    'weight' => $raw !== null ? ($raw < 1000 ? "{$raw} g" : number_format($raw / 1000, 2).' kg') : 'Chưa rõ',
                    'battery' => $raw !== null ? number_format($raw, 0, ',', '.').' mAh'.($p->battery_wh ? " ({$p->battery_wh} Wh)" : '') : 'Chưa rõ',
                    'screen' => $raw !== null ? "{$raw} inch" : 'Chưa rõ',
                    'warranty' => $raw !== null ? "{$raw} tháng" : '12 tháng',
                };

                $spec['values'][$p->id] = [
                    'text' => $formatted,
                    'numeric' => $raw,
                    'is_best' => $bestId !== null && in_array($p->id, $bestId, true),
                ];
            }
        }

        return $specs;
    }

    /**
     * So sánh các thông số kỹ thuật chi tiết theo nhóm (CPU, RAM, GPU, Ổ cứng...)
     *
     * @param  Collection<int, Product>  $products
     */
    protected function buildDetailedSpecsComparison(Collection $products): array
    {
        // Thu thập tất cả specification_id xuất hiện trong các sản phẩm cần so sánh
        $specMap = [];
        foreach ($products as $product) {
            foreach ($product->productSpecifications as $ps) {
                if ($ps->specification) {
                    $specMap[$ps->specification_id] = $ps->specification;
                }
            }
        }

        if (empty($specMap)) {
            return [];
        }

        // Nhóm các thông số theo group
        $groupIds = array_unique(array_map(fn (Specification $s) => $s->group_id, $specMap));
        $groups = SpecificationGroup::whereIn('id', $groupIds)->orderBy('sort_order')->get();

        $result = [];

        foreach ($groups as $group) {
            $groupSpecs = [];
            $specsInGroup = array_filter($specMap, fn (Specification $s) => $s->group_id === $group->id);
            usort($specsInGroup, fn (Specification $a, Specification $b) => $a->sort_order <=> $b->sort_order);

            foreach ($specsInGroup as $spec) {
                $numericValues = [];
                $textValues = [];

                foreach ($products as $product) {
                    $ps = $product->productSpecifications->firstWhere('specification_id', $spec->id);
                    $numericValues[$product->id] = $ps?->value_numeric !== null ? (float) $ps->value_numeric : null;
                    $textValues[$product->id] = $ps?->value_text ?? '-';
                }

                $bestIds = $this->findBestProductId($numericValues, $spec->compare_rule);

                $values = [];
                foreach ($products as $product) {
                    $rawText = $textValues[$product->id];
                    $unit = $spec->unit ? " {$spec->unit}" : '';
                    if ($spec->unit && str_contains(strtolower((string) $rawText), strtolower((string) $spec->unit))) {
                        $unit = '';
                    }
                    $displayText = $rawText !== '-' ? "{$rawText}{$unit}" : '-';

                    $values[$product->id] = [
                        'text' => $displayText,
                        'numeric' => $numericValues[$product->id],
                        'is_best' => in_array($product->id, $bestIds, true) && $rawText !== '-',
                    ];
                }

                $groupSpecs[] = [
                    'id' => $spec->id,
                    'name' => $spec->name,
                    'unit' => $spec->unit,
                    'compare_rule' => $spec->compare_rule,
                    'values' => $values,
                ];
            }

            if (! empty($groupSpecs)) {
                $result[] = [
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'specs' => $groupSpecs,
                ];
            }
        }

        return $result;
    }

    /**
     * Xác định sản phẩm có thông số tốt nhất theo luật
     *
     * @param  array<int, float|null>  $values
     * @return array<int> Danh sách ID sản phẩm có thông số tốt nhất
     */
    protected function findBestProductId(array $values, string $rule): array
    {
        if ($rule === 'none') {
            return [];
        }

        $valid = array_filter($values, fn ($v) => $v !== null && $v > 0);

        if (count($valid) < 2) {
            return [];
        }

        if ($rule === 'higher_is_better') {
            $bestVal = max($valid);
        } elseif ($rule === 'lower_is_better') {
            $bestVal = min($valid);
        } else {
            return [];
        }

        // Tìm tất cả product_id đạt giá trị tốt nhất
        $bestIds = [];
        foreach ($valid as $pId => $val) {
            if (abs($val - $bestVal) < 0.0001) {
                $bestIds[] = $pId;
            }
        }

        // Nếu tất cả các máy đều bằng nhau thì không cần highlight
        if (count($bestIds) === count($valid)) {
            return [];
        }

        return $bestIds;
    }
}
