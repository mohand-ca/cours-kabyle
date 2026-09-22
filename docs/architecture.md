# Architecture de l'application

## Rôles utilisateurs

| Rôle | Description |
|---|---|
| `admin` | Gestion complète via Filament (profs, apprenants, paiements, contenu) |
| `teacher` | Crée son profil, ses disponibilités, conduit les séances |
| `learner` | Tout apprenant : adulte pour lui-même, parent gérant ses enfants, ou les deux |

Le rôle `parent` n'existe pas — c'est une **relation**, pas un rôle.
Un compte `learner` peut avoir plusieurs profils apprenants dans la table `learners`, liés par `learners.relationship` (self / child / spouse / other). Pas de table `guardians`.

Cas d'usage couverts :
- **Adulte qui apprend seul** → un compte `learner`, un profil apprenant avec `relationship = self`
- **Parent qui inscrit son enfant** → un compte `learner`, crée des profils enfants avec `relationship = child`
- **Parent qui apprend aussi** → un compte `learner`, un profil `self` + des profils `child`

---

## Flux utilisateurs

### Flux 1 — Inscription et onboarding d'un enseignant
```
/become-a-teacher → Création compte (role=teacher) + TeacherProfile vierge
→ Email de vérification
→ Complétion du profil (bio, niveaux, langues, lien Meet)
→ Soumission pour validation (status=pending)
→ Admin approuve via Filament → status=approved
→ Publication des disponibilités (créneaux manuels)
→ Visible dans la recherche
```

### Flux 2a — Adulte qui apprend pour lui-même
```
Inscription (learner) → Learner(relationship=self) créé automatiquement
→ Vérification email
→ Séance d'essai (optionnel, 10 USD)
→ Achat d'un paquet (Stripe Checkout one-time)
→ Webhook checkout.session.completed → Purchase créée (sessions_remaining = N)
→ Choix d'un enseignant → Sélection d'un créneau disponible
→ Booking confirmée → Email confirmation (apprenant + prof)
→ Rappel automatique 24h avant + 1h avant
→ Séance (Google Meet — lien statique du prof)
→ Prof marque la séance "completed" → Points attribués
→ Apprenant reçoit un résumé de séance
```

### Flux 2b — Parent qui inscrit son enfant
```
Inscription (learner, relationship=self) → Création d'un profil enfant (relationship=child)
→ Achat d'un paquet pour l'enfant (Stripe Checkout)
→ Webhook → Purchase créée pour l'enfant
→ Choix d'un enseignant → Sélection d'un créneau
→ Booking confirmée → Email confirmation (parent + prof)
  → Si learner.notification_email défini, envoi sur les deux adresses
→ Rappel automatique 24h avant + 1h avant
→ Séance (Google Meet)
→ Prof marque la séance "completed" → Points attribués à l'enfant
→ Parent reçoit un résumé + progression de l'enfant
```

### Flux 3 — Système de points et récompenses
```
Parent fixe un objectif (ex : 100 points = sortie)
→ Chaque séance complétée = X points attribués à l'enfant
→ Tableau de bord affiche la progression vers l'objectif
→ Objectif atteint → notification parent
→ Parent valide la récompense → status "achieved"
→ Nouveau cycle possible
```

### Flux 4 — Annulation
```
Annulation > 24h avant → séance re-créditée sur le Purchase
Annulation < 24h avant → séance décomptée (sans remboursement)
No-show enseignant → séance re-créditée + signalement admin
```

---

## Structure de l'application

```
app/
├── Models/
│   ├── User.php                  ← HasRoles + Billable + FilamentUser + MustVerifyEmail
│   ├── Learner.php               ← relationship enum (self/child/spouse/other)
│   ├── TeacherProfile.php        ← status enum (pending/approved/suspended)
│   ├── AvailabilityPattern.php   ← règle récurrente (V2 — génération automatique)
│   ├── AvailabilitySlot.php      ← créneau réel, stocké en UTC
│   ├── Package.php               ← paquet de séances (stripe_price_id, sessions_count)
│   ├── Purchase.php              ← achat (sessions_total, sessions_remaining)
│   ├── LessonSession.php         ← séance planifiée (NB: pas Session.php — conflit façade)
│   ├── PointTransaction.php
│   └── Reward.php
│
├── Livewire/
│   ├── Auth/
│   │   ├── Register.php          ← inscription learner
│   │   ├── TeacherRegister.php   ← inscription teacher
│   │   └── Login.php             ← redirection selon rôle
│   ├── Forms/
│   │   ├── RegisterForm.php      ← name, firstName, lastName, email, password
│   │   └── TeacherRegisterForm.php ← name, email, password (sans firstName/lastName)
│   ├── Teacher/
│   │   ├── Dashboard.php
│   │   ├── ProfileSetup.php
│   │   └── AvailabilityCalendar.php
│   └── Learner/
│       ├── Dashboard.php
│       └── BookingFlow.php
│
├── Filament/Resources/
│   ├── TeacherProfiles/          ← ✅ liste + édition + approve/suspend
│   ├── UserResource/             ← à venir (Phase 5)
│   ├── PackageResource/          ← à venir (Phase 3)
│   ├── PurchaseResource/         ← à venir (Phase 3)
│   └── BookingResource/          ← à venir (Phase 4)
│
├── Jobs/
│   ├── SendBookingConfirmation.php
│   ├── SendSessionReminder.php   ← 24h et 1h avant
│   └── ProcessCancellation.php
│
└── Notifications/
    ├── BookingConfirmed.php
    ├── SessionReminder.php
    └── SessionCompleted.php

lang/
├── fr/
│   ├── auth.php                  ← login, register, teacher_register
│   └── teacher.php               ← profile, dashboard, availability, filament
└── en/
    ├── auth.php
    └── teacher.php
```

---

## Règles métier importantes

- Un créneau réservé n'est plus visible aux autres apprenants
- Une séance ne peut être réservée que si le Purchase a `sessions_remaining > 0`
- Les points sont attribués **uniquement** quand le prof marque la séance `completed`
- Un enseignant ne peut pas s'approuver lui-même (admin uniquement)
- Les paiements Stripe sont confirmés via webhook — jamais sur le retour de redirection
- Les créneaux sont toujours stockés en **UTC**, convertis à l'affichage selon `user.timezone`
- La confirmation de booking est envoyée à `user.email` ET `learner.notification_email` si renseigné

## Conventions de développement

- **Code en anglais** : variables, méthodes, colonnes DB, routes, noms de jobs — tout en anglais
- **Traductions dès le début** : toute chaîne visible passe par `__('clé')` — jamais de label hardcodé dans les vues
- **Composants Livewire class-based** : `app/Livewire/` (PHP) + `resources/views/livewire/` (Blade), pas de Volt
- **Form Objects** pour les formulaires d'auth : `app/Livewire/Forms/`
- **Tests PHPUnit feature** pour chaque module — `withoutVite()` dans `setUp()` si la vue utilise `@vite`
