<?php

use App\Models\Enums\Plan;

return [
    'trial_days' => 14,

    'plans' => [
        env('STRIPE_PLANS_SCREEN', 'price_1Kt5khCZD4b7c60wovG6PQbR') => strtoupper(Plan::screen()),
        env('STRIPE_PLANS_ASSESS', 'price_1Kt5m6CZD4b7c60wWcpb1QUi') => strtoupper(Plan::assess()),
        env('STRIPE_PLANS_DISCOVER', 'price_1Kt5mRCZD4b7c60wMciItM6r') => strtoupper(Plan::discover()),
        env('STRIPE_PLANS_PLAN', 'price_1Kt5moCZD4b7c60wYZNB4FGX') => strtoupper(Plan::plan()),
    ],

    'priority' => [
        Plan::screen(),
        Plan::assess(),
        Plan::discover(),
        Plan::plan(),
    ],

    'cancelation_reasons' => [
        'Too expensive',
        'Lacks features',
        'Not what I expected',
    ],

    'stripe_key' => env('STRIPE_KEY'),
    'stripe_secret' => env('STRIPE_SECRET'),
];
