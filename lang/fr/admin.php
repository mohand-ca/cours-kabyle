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
    'stats' => [
        'learners' => 'Apprenants',
        'teachers_approved' => 'Enseignants approuvés',
        'revenue' => 'Revenus',
        'sessions_booked' => 'Séances réservées',
        'new_this_month' => ':count nouveaux ce mois',
        'pending_count' => ':count en attente de validation',
        'purchases_count' => ':count achats complétés',
        'sessions_sold' => ':count séances vendues au total',
        'latest_bookings' => 'Dernières réservations',
        'columns' => [
            'learner' => 'Apprenant',
            'teacher' => 'Enseignant',
            'date' => 'Date du cours',
            'status' => 'Statut',
            'booked_at' => 'Réservé le',
        ],
    ],
];
