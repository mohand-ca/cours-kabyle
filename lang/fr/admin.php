<?php

return [
    'users' => [
        'navigation_label' => 'Utilisateurs',
        'model_label' => 'Utilisateur',
        'plural_model_label' => 'Utilisateurs',
        'columns' => [
            'name' => 'Nom',
            'email' => 'Email',
            'role' => 'Rôle',
            'verified' => 'Vérifié',
            'timezone' => 'Fuseau horaire',
            'created_at' => 'Inscrit le',
        ],
        'roles' => [
            'admin' => 'Admin',
            'teacher' => 'Enseignant',
            'learner' => 'Apprenant',
        ],
        'actions' => [
            'verify_email' => 'Marquer comme vérifié',
        ],
        'filters' => [
            'role' => 'Rôle',
        ],
    ],
    'purchases' => [
        'navigation_label' => 'Achats',
        'model_label' => 'Achat',
        'plural_model_label' => 'Achats',
        'columns' => [
            'user' => 'Utilisateur',
            'package' => 'Pack',
            'sessions_total' => 'Séances achetées',
            'sessions_remaining' => 'Séances restantes',
            'status' => 'Statut',
            'stripe_session_id' => 'Session Stripe',
            'created_at' => 'Date',
        ],
        'statuses' => [
            'pending' => 'En attente',
            'completed' => 'Complété',
            'refunded' => 'Remboursé',
        ],
        'filters' => [
            'status' => 'Statut',
        ],
    ],
];
