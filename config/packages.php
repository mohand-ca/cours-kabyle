<?php

return [
    'starter' => [
        'key' => 'starter',
        'name' => 'Pack 5 séances',
        'sessions_count' => 5,
        'price_cents' => 4900,
        'stripe_price_id' => env('STRIPE_PRICE_STARTER'),
    ],
    'standard' => [
        'key' => 'standard',
        'name' => 'Pack 10 séances',
        'sessions_count' => 10,
        'price_cents' => 8900,
        'stripe_price_id' => env('STRIPE_PRICE_STANDARD'),
    ],
    'premium' => [
        'key' => 'premium',
        'name' => 'Pack 20 séances',
        'sessions_count' => 20,
        'price_cents' => 15900,
        'stripe_price_id' => env('STRIPE_PRICE_PREMIUM'),
    ],
];
