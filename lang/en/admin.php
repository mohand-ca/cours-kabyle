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
    'stats' => [
        'learners' => 'Learners',
        'teachers_approved' => 'Approved teachers',
        'revenue' => 'Revenue',
        'sessions_booked' => 'Confirmed bookings',
        'new_this_month' => ':count new this month',
        'pending_count' => ':count pending review',
        'purchases_count' => ':count completed purchases',
        'sessions_sold' => ':count sessions sold total',
        'latest_bookings' => 'Latest bookings',
        'columns' => [
            'learner' => 'Learner',
            'teacher' => 'Teacher',
            'date' => 'Lesson date',
            'status' => 'Status',
            'booked_at' => 'Booked at',
        ],
    ],
];
