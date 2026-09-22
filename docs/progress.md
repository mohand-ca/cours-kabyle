# Progress — État du projet

> Ce fichier est mis à jour à chaque session. Il sert de point d'entrée pour reprendre le travail sans perdre le contexte.

---

## Statut général
**Phase : MVP — Phase 5 terminée (Paiements Stripe)**
68/68 tests passent.

---

## Ce qui est fait

### Documentation
- `docs/pitch.md` — document non-technique pour l'associé marketing ✅
- `docs/stack.md` — stack technique avec versions installées ✅
- `docs/architecture.md` — rôles, flux utilisateurs, modules, règles métier ✅
- `docs/roadmap.md` — MVP + directions V2/V3 ✅
- `docs/design.md` — inspiration visuelle, direction design, pages à designer ✅
- `docs/progress.md` — ce fichier ✅

### Décisions de conception validées
- **Rôles** : `admin` / `teacher` / `learner` (pas de rôle `parent`)
- **Modèle compte/apprenant** : table `users` (le compte) + table `learners` (les profils)
  - `learners.relationship` : self / child / spouse / other
  - `learners.notification_email` : email additionnel optionnel par apprenant
- **Disponibilités** : `availability_patterns` (règle récurrente, V2) + `availability_slots` (créneaux réels, manuel pour MVP)
- **Paiements** : Stripe USD via Cashier — checkout one-time par paquet (pas de subscription)
- **Séance** : `LessonSession` (pas `Session` — conflit avec la façade Laravel)
- **Annulation** : toujours re-crédit pour le MVP (politique >24h en V2)
- **`Review` model → PAS dans le MVP** (reporté en V2)
- **Code en anglais** : variables, méthodes, colonnes, routes — tout en anglais
- **Traduction dès le début** : toutes les chaînes via `__('clé')` — `lang/fr/` + `lang/en/`

### Installation (packages)
- Laravel 13, Livewire 4, Filament 5, Spatie Permission 8, Laravel Cashier 16 ✅
- Spatie MediaLibrary 11, DomPDF 3, Spatie ActivityLog 4, Laravel Boost 2 ✅

---

### Phase 1 — Auth + Rôles ✅ (19 tests)

- `users` table : colonne `role` (enum) + `timezone`
- `learners` table : `user_id`, `first_name`, `last_name`, `date_of_birth` (raw string, pas de cast Carbon), `relationship`, `notification_email`, `points`
- `User` : `HasRoles` + `Billable` + `FilamentUser` + `MustVerifyEmail`
- `Learner` : relation `user()`, `notificationAddresses()`, factory avec états `self()` / `child()`
- Livewire : `Register`, `Login`, `TeacherRegister` + Form objects
- Login redirige vers `learner.dashboard` (pas `dashboard`)
- `RoleSeeder` : rôles + `admin@thamazight.com` / `password`
- Panel Filament violet, accès admin + email vérifié

---

### Phase 2 — Espace enseignant ✅ (15 tests)

- `teacher_profiles` : `bio`, `levels` JSON, `languages` JSON, `meet_link`, `status` enum, `submitted_at`
- `availability_patterns` : `day_of_week`, `start_time`, `end_time` UTC, `is_active`
- `availability_slots` : `starts_at`, `ends_at` UTC, `status` (available/booked/cancelled)
- `AvailabilitySlot::book()` → met status à 'booked' ; `scopeAvailable()` → future + available
- Livewire : `Teacher\Dashboard`, `Teacher\ProfileSetup`, `Teacher\AvailabilityCalendar`
- Filament : `TeacherProfileResource` avec actions approve/suspend
- Routes enseignant sous `prefix('teacher')` + `role:teacher`

---

### Phase 3 — Design ✅ (Claude Design)

- Projet Claude Design créé sur claude.ai (privé, account Samir)
- 11 composants HTML générés : foundations/colors, foundations/typography, components/buttons, components/forms, components/badges, components/cards, learner/teacher-card, learner/session-card, learner/dashboard, public/hero, public/how-it-works
- Palette : violet-600 primaire, indigo-500 accent, fond blanc app / fond `#1e1b4b` vitrine
- Typographie : Inter, gradient headline violet-400→indigo-300
- Inspiration : SavvyCal (layout bold dark hero, minimaliste) — couleurs violet/indigo propres au projet

---

### Phase 4 — Espace apprenant + réservations ✅ (25 tests)

**Migrations :**
- `create_lesson_sessions_table` : `availability_slot_id` unique (anti double-booking), `learner_id`, `teacher_profile_id`, `purchase_id` nullable, `status` enum (confirmed/cancelled)

**Models :**
- `LessonSession` : `scopeUpcoming()`, `isConfirmed()`, `cancel()` (met cancelled + libère slot)
- `AvailabilitySlot` : `lessonSession()` HasOne, `book()` helper
- `Learner` : `lessonSessions()` HasMany

**Livewire apprenant :**
- `Learner\Dashboard` : séances à venir via `scopeUpcoming`, annulation avec re-crédit, `sessionsRemaining`
- `Learner\ManageLearners` : CRUD apprenants (protégé par `user_id`), pas de suppression du profil `self`
- `Learner\TeacherCatalog` : filtres niveau/langue, expansion créneaux par prof, flow réservation inline

**Flow réservation :**
1. Ouvrir créneaux du prof (`toggleSlots`)
2. Cliquer "Réserver" sur un créneau (`selectSlot`)
3. Choisir un apprenant
4. Confirmer (`book`) → vérifie purchase actif, DB transaction + `lockForUpdate`, décrément `sessions_remaining`

**Routes :** `/dashboard`, `/learners`, `/teachers` sous `role:learner`

**Traductions :** `lang/en/learner.php` + `lang/fr/learner.php` — sections `dashboard`, `learners`, `booking`, `catalog`

**Tests :** `LearnerDashboardTest` (11), `TeacherCatalogTest` (6), `BookingTest` (8)

---

### Phase 5 — Paiements Stripe ✅ (9 tests)

**Migrations Cashier publiées :**
- `create_customer_columns` : `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at` sur `users`
- `create_subscriptions_table` + `subscription_items` (Cashier standard)

**Migrations custom :**
- `create_packages_table` : `name`, `sessions_count`, `price_cents`, `stripe_price_id`, `is_active`
- `create_purchases_table` : `user_id`, `package_id`, `stripe_session_id` unique, `sessions_total`, `sessions_remaining`, `status` enum
- `add_purchase_foreign_to_lesson_sessions` : FK `purchase_id` → `purchases.id` nullOnDelete

**Models :**
- `SessionPackage` (table: `packages`) : `priceInDollars()`, `scopeActive()`
- `Purchase` : `hasSessionsRemaining()`, `isCompleted()`
- `User` : `purchases()` HasMany, `sessionsRemaining()` (sum sessions_remaining des purchases completed)

**Flow paiement :**
1. `/packages` → `Learner\PackageCatalog` — liste les packages actifs
2. Clic "Acheter" → `GET /checkout/{package}` → `CheckoutController::create()` → `$user->checkout(...)` avec metadata `user_id` + `package_id`
3. Stripe Checkout → succès → `/checkout/success` → redirect dashboard avec flash
4. Webhook `checkout.session.completed` → `StripeEventListener` → `Purchase::firstOrCreate(...)` (idempotent)

**Listener :** `StripeEventListener` écoute `WebhookReceived` — enregistré dans `AppServiceProvider`

**CSRF :** `stripe/*` exclu dans `bootstrap/app.php`

**Annulation session :** re-crédite `sessions_remaining` sur le `purchase_id` lié

**⚠️ À configurer en prod :**
```
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...
```
- Créer les 3 paquets dans le Stripe Dashboard, mettre les `stripe_price_id` via `PackageSeeder`
- Enregistrer le webhook : `php artisan cashier:webhook`

**Tests :** `PurchaseTest` (9) — webhook, idempotence, booking nécessite un purchase, décrément, re-crédit

---

### Standards et conventions ✅

- **i18n** : toutes les chaînes via `__()` — `lang/fr/auth.php`, `lang/fr/teacher.php`, `lang/fr/learner.php` ✅
- **`APP_LOCALE=fr`** dans `.env` ✅
- **Règles AI** dans `.ai/rules/` : code en anglais, traductions obligatoires ✅
- **Pint** : lancé après chaque phase — `vendor/bin/pint --dirty --format agent`

---

## Pièges techniques à retenir

| Problème | Solution |
|---|---|
| `$slots` en Blade Livewire | Nom réservé par Livewire pour les named slots → utiliser `$availableSlots` |
| `LearnerForm::fill()` | Conflit avec `Form::fill()` de la base Livewire → renommer en `populate()` |
| `assertSessionHas` en Livewire 4 | Ne fonctionne pas → utiliser `assertDatabaseHas` / `assertDatabaseMissing` |
| `expectException` dans Livewire | Ne capture pas les exceptions de composant → asserter l'état DB |
| `date_of_birth` | Pas de cast Carbon sur `Learner` → raw string, pas de `->format()` |
| Nom de la route dashboard | `learner.dashboard` (pas `dashboard`) |
| Ordre des routes checkout | `/checkout/success` avant `/checkout/{package}` pour éviter le conflit de param |
| `make:livewire` Livewire 4 | Peut placer le fichier dans `views/components/` → écrire les fichiers manuellement si nécessaire |

---

## Ce qui reste à faire (MVP)

- [ ] **Phase 6** — Emails : confirmation réservation (apprenant + prof), rappel 24h avant, email bienvenue enseignant approuvé
- [ ] **Phase 7** — Admin Filament complet : gestion paquets, vue bookings/purchases, stats revenus/séances
- [ ] **Phase 8** — Site vitrine : homepage, "Devenir enseignant", FAQ, pricing, RGPD/PIPEDA
- [ ] **Phase 9** — Intégration design sur toutes les vues (auth + enseignant + apprenant)
- [ ] Configurer Stripe (clés + webhook) en production
- [ ] Déployer sur Laravel Cloud (EU)

---

## Contexte projet

- **Samir** : développeur web senior, Kabyle, basé au Canada, travaille pour GlobalLingua
- **Associé** : responsable marketing, a reçu `pitch.md`
- **Enseignant coordinateur** : déjà identifié en Algérie
- **Marché** : diaspora kabyle/amazigh — France, Canada, USA, monde entier
- **Nom de la plateforme** : pas encore décidé
- **Script Tamazight** : Latin

---

## Dernière session — 22 septembre 2026

- **Phase 3 complétée** : design system Claude Design (11 composants), palette violet/indigo, hero dark `#1e1b4b`
- **Phase 4 complétée** : espace apprenant (Dashboard, ManageLearners, TeacherCatalog), réservations (`LessonSession`, flow inline, annulation) — 34 tests passaient
- **Phase 5 complétée** : paiements Stripe (`SessionPackage`, `Purchase`, Cashier Checkout, webhook listener, re-crédit annulation) — 68 tests passent
- **Prochain** : Phase 6 — Emails (confirmation, rappels, bienvenue enseignant)
