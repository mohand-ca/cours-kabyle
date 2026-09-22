# Stack technique

## Vue d'ensemble

| Couche | Technologie | Version | Justification |
|---|---|---|---|
| Backend | Laravel | 13.32.0 | Maîtrisé, écosystème riche, adapté aux marketplaces |
| Frontend | Livewire (class-based) | 4.4.6 | Réactivité sans SPA — composants dans `app/Livewire/` |
| Bundler | Vite | 8 | Build frontend rapide, HMR |
| UI | Tailwind CSS | v4 | Productivité, cohérence, mobile-first natif |
| Admin | Filament | 5.8.4 | Panel admin uniquement — Livewire custom pour les interfaces utilisateur |
| Rôles | Spatie Permission | 8.3.0 | Gestion des rôles admin / teacher / learner |
| Paiements | Laravel Cashier (Stripe) | 16.8.0 | Checkout one-time par paquet, webhooks, factures |
| Fichiers | Spatie MediaLibrary | 11.23.8 | Avatars profs et apprenants, stockage S3 |
| PDF | DomPDF | 3.1.2 | Génération de factures PDF |
| Traçabilité | Spatie ActivityLog | 4.12.3 | Journal des actions importantes |
| Traductions | Laravel i18n natif | — | `lang/fr/` + `lang/en/` — aucun label hardcodé dans les vues |
| AI Dev | Laravel Boost | 2.9.1 | Guidelines et outils pour Claude Code |
| Base de données | MySQL | — | Stable, performant, supporté par Laravel Cloud |
| Hébergement | Laravel Cloud | — | Déploiement natif Laravel, scaling automatique |
| Emails | Mailgun (ou Resend) | — | Livraison fiable, logs, webhooks |
| Files d'attente | Laravel Queue + Redis | — | Emails asynchrones, rappels, tâches planifiées |
| Visioconférence | Google Meet | — | Gratuit, universel, aucune intégration complexe en MVP |

---

## Détail par composant

### Laravel
- Framework principal — routing, auth, ORM (Eloquent), queues, scheduler
- `php artisan` pour les migrations, seeders, et commandes personnalisées
- Multi-rôles via un système de rôles/permissions (Spatie Laravel Permission)

### Livewire
- Syntaxe **class-based uniquement** (pas Volt) — PHP dans `app/Livewire/`, vues dans `resources/views/livewire/`
- Form Objects dans `app/Livewire/Forms/` pour les formulaires d'auth
- Utilisé pour toutes les interfaces utilisateur : auth, espace enseignant, espace apprenant, site vitrine
- Évite de gérer un frontend séparé (pas de Vue/React) — équipe réduite, maintenance plus simple

### Filament
- **Admin uniquement** — pas utilisé pour les interfaces enseignant ou apprenant
- Panel admin : gestion des profs (validation, suspension), apprenants, paquets, réservations, paiements
- Génération rapide de CRUD sans code répétitif
- Tableaux de bord avec statistiques (revenus, séances, inscriptions)

### Stripe
- Gestion des paquets de séances (Stripe Products + Prices)
- Webhooks pour confirmer les paiements avant de créditer les séances
- Remboursements en crédit (pas en cash) en cas d'annulation
- Paiements en USD par défaut (conversion automatique pour EUR/CAD)

### Paiements vers les enseignants (Algérie)
- Hors Stripe (Stripe ne couvre pas l'Algérie)
- Solution : Wise ou Payoneer — virement mensuel manuel dans un premier temps
- À automatiser en v2 si le volume le justifie

### Visioconférence
- En MVP : l'enseignant génère un lien Google Meet et le colle dans son profil/disponibilité
- La plateforme affiche ce lien à l'heure de la séance — simple et fiable
- En v2 : génération automatique via Google Calendar API

### Google Meet — génération automatique (v2)
- API Google Calendar : créer un événement avec Meet inclus automatiquement
- Envoi d'invitation dans les agendas des deux parties
- Réduction des no-shows

---

## Architecture de déploiement (Laravel Cloud)

```
Internet
    │
    ▼
Laravel Cloud (Load Balancer)
    │
    ├── App Servers (Laravel)
    │       ├── Web (Nginx + PHP-FPM)
    │       └── Queue Workers (Laravel Horizon)
    │
    ├── MySQL (managed)
    ├── Redis (sessions, cache, queues)
    └── Storage (S3-compatible pour avatars, documents)
```

- **Région EU** pour la conformité RGPD (utilisateurs France/EU)
- **HTTPS** obligatoire partout
- **Backups automatiques** MySQL quotidiens (Laravel Cloud)

---

## Conformité et sécurité

- **RGPD** : consentement cookies, politique de confidentialité, droit à l'effacement
- **PIPEDA** (Canada) : protection des données personnelles
- Mots de passe hashés (bcrypt via Laravel)
- Tokens API signés (Sanctum)
- Rate limiting sur les endpoints sensibles (auth, paiement)
- Validation stricte côté serveur sur tous les formulaires
