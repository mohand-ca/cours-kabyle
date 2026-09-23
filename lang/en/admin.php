<?php

return [
    'users' => [
        'navigation_label' => 'Users',
        'model_label' => 'User',
        'plural_model_label' => 'Users',
        'columns' => [
            'name' => 'Name',
            'email' => 'Email',
            'role' => 'Role',
            'verified' => 'Verified',
            'timezone' => 'Timezone',
            'created_at' => 'Registered',
        ],
        'roles' => [
            'admin' => 'Admin',
            'teacher' => 'Teacher',
            'learner' => 'Learner',
        ],
        'actions' => [
            'verify_email' => 'Mark as verified',
        ],
        'filters' => [
            'role' => 'Role',
        ],
    ],
    'purchases' => [
        'navigation_label' => 'Purchases',
        'model_label' => 'Purchase',
        'plural_model_label' => 'Purchases',
        'columns' => [
            'user' => 'User',
            'package' => 'Package',
            'sessions_total' => 'Sessions bought',
            'sessions_remaining' => 'Sessions remaining',
            'status' => 'Status',
            'stripe_session_id' => 'Stripe session',
            'created_at' => 'Date',
        ],
        'statuses' => [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'refunded' => 'Refunded',
        ],
        'filters' => [
            'status' => 'Status',
        ],
    ],
];
