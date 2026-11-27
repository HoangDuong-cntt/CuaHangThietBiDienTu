<?php

return [
    'shipping_methods' => [
        'standard' => [
            'label' => 'Tiêu chuẩn',
            'fee' => (int) env('STORE_SHIPPING_STANDARD_FEE', 30000),
        ],
        'express' => [
            'label' => 'Nhanh',
            'fee' => (int) env('STORE_SHIPPING_EXPRESS_FEE', 50000),
        ],
        'same_day' => [
            'label' => 'Hỏa tốc',
            'fee' => (int) env('STORE_SHIPPING_SAME_DAY_FEE', 80000),
        ],
    ],
];
