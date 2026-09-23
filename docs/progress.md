# Progress — État du projet

> Ce fichier est mis à jour à chaque session. Il sert de point d'entrée pour reprendre le travail sans perdre le contexte.

---

## Statut général
**Phase : MVP — Production Readiness (phases 6–7 partiellement couvertes)**
68/68 tests passent. App nommée **Azul**.

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
- **Paiements** : Stripe **CAD** via Cashier — checkout one-time par paquet (pas de subscription)
- **Paquets** : config-based via `config/packages.php` (comme Laravel Spark) — pas de table `packages` / seeder
- **Séance** : `LessonSession` (pas `Session` — conflit avec la façade Laravel)
- **Annulation** : toujours re-crédit pour le MVP (politique >24h en V2)
- **`Review` model → PAS dans le MVP** (reporté en V2)
- **Code en anglais** : variables, méthodes, colonnes, routes — tout en anglais
- **Traduction dès le début** : toutes les chaînes via `__('clé')` — `lang/fr/` + `lang/en/`
- **Nom de la plateforme** : **Azul** ✅

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
- `create_purchases_table` : `user_id`, `package_key` (string), `stripe_session_id` unique, `sessions_total`, `sessions_remaining`, `status` enum
- `add_purchase_foreign_to_lesson_sessions` : FK `purchase_id` → `purchases.id` nullOnDelete

**Paquets (config-based, pas de table DB) :**
- `config/packages.php` — source de vérité : `starter` (5 séances, 49$), `standard` (10 séances, 89$), `premium` (20 séances, 159$)
- Prix en CAD (`CASHIER_CURRENCY=CAD`)
- Price IDs Stripe dans `.env` : `STRIPE_PRICE_STARTER`, `STRIPE_PRICE_STANDARD`, `STRIPE_PRICE_PREMIUM`

**Models :**
- `Purchase` : `hasSessionsRemaining()`, `isCompleted()`, `packageConfig()` (lookup via config)
- `User` : `purchases()` HasMany, `sessionsRemaining()` (sum sessions_remaining des purchases completed)

**Flow paiement :**
1. `/packages` → `Learner\PackageCatalog` — liste les packages depuis `config('packages')`
2. Clic "Acheter" → `GET /checkout/{key}` → `CheckoutController::create()` → `$user->checkout(...)` avec metadata `user_id` + `package_key`
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
STRIPE_PRICE_STARTER=price_...
STRIPE_PRICE_STANDARD=price_...
STRIPE_PRICE_PREMIUM=price_...
```
- Créer les 3 paquets dans le Stripe Dashboard, copier les `price_id` dans `.env`
- Enregistrer le webhook : `php artisan cashier:webhook`

**Tests :** `PurchaseTest` (9) — webhook, idempotence, booking nécessite un purchase, décrément, re-crédit

---

### Phase 6 — Emails transactionnels ✅

- `BookingConfirmed` : confirmation envoyée à l'apprenant et au prof après réservation
- `LessonReminder` : rappel 24h avant la séance (planification via scheduler)
- `TeacherApproved` : email de bienvenue envoyé à l'enseignant après approbation dans Filament
- Vues Blade sous `resources/views/mail/`
- Switcher langue FR/EN dans la barre de navigation ✅

---

### Phase 7 — Admin Filament ✅

**Resources Filament :**
- `UserResource` : liste, filtre par rôle, badge rôle, icône email vérifié, fuseau horaire
- `PurchaseResource` : liste achats, nom du pack via `config('packages')`, sessions restantes en couleur
- `TeacherProfileResource` (existant) : actions approve/suspend

**Widgets dashboard admin :**
- `StatsOverview` : 4 stats avec sparklines 7 jours (apprenants, profs approuvés, revenus CAD, séances) — polling 60s
- `LatestBookings` : tableau des 10 dernières séances confirmées

**Traductions :** `lang/fr/admin.php` + `lang/en/admin.php`

---

### Production Readiness ✅ (session 23 sept 2026)

- **App renommée** : "Azul" (config, layouts, meta) ✅
- **Vérification email** : vue `auth/verify-email.blade.php` propre avec bannière succès + logout ✅
- **Mot de passe oublié** : `ForgotPassword` + `ResetPassword` Livewire, anti-énumération (toujours success) ✅
  - ⚠️ Piège : `reset()` conflict avec `Livewire\Component::reset()` → méthode renommée `resetPassword()`
- **Timezone enseignant** : champ select dans `TeacherRegisterForm` + validation `timezone:all` ✅
- **Pages d'erreur** : `404.blade.php` + `500.blade.php` standalone, palette amber, logo ⵣ ✅
- **Config packages** : `config/packages.php` remplace la table `packages` + `SessionPackage` model supprimé ✅
- **Stripe CAD** : `CASHIER_CURRENCY=CAD`, clés test configurées localement ✅
- **Tests** : `PasswordResetTest` (8), `TeacherProfileTest` mis à jour avec tests timezone ✅

---

### DemoSeeder ✅ (session 23 sept 2026)

- `database/seeders/DemoSeeder.php` — données de démonstration réalistes
- **5 enseignants** : 4 approuvés (Amina Oukaci, Ferhat Aït-Ali, Taziri Melloul, Massinissa Idir) + 1 en attente (Lynda Ath-Mansour), bios réalistes en français
- **50 apprenants** : noms diaspora kabyle/française, ~17 comptes avec un 2ème profil enfant
- **36 achats** (starter/standard/premium, statut completed)
- **75 créneaux** (32 passés/réservés, 43 futurs/disponibles)
- **32 séances** confirmées liées aux créneaux passés
- Tous les mots de passe : `password` — emails : `prenom.nom@demo.com`

---

### Standards et conventions ✅

- **i18n** : toutes les chaînes via `__()` — `lang/fr/auth.php`, `lang/fr/teacher.php`, `lang/fr/learner.php`, `lang/fr/admin.php` ✅
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
| `reset()` dans Livewire 4 | Conflict avec `Livewire\Component::reset()` → renommer la méthode (ex: `resetPassword()`) |
| Apostrophes en PHP string | Bios en français avec `'` dans single-quoted strings → utiliser des double quotes `"` |

---

## Ce qui reste à faire (MVP)

- [x] ~~**Phase 6** — Emails : confirmation réservation, rappel 24h avant, bienvenue enseignant approuvé~~
- [x] ~~**Phase 7** — Admin Filament : resources Users/Purchases, stats dashboard~~
- [ ] **Phase 8** — Site vitrine : homepage, "Devenir enseignant", FAQ, pricing, RGPD/PIPEDA
- [ ] **Phase 9** — Intégration design sur toutes les vues (auth + enseignant + apprenant)
- [ ] Configurer Stripe (clés + webhook) en production
- [ ] Déployer sur Laravel Cloud (EU)
- [ ] Configurer un provider email en prod (Resend, Mailgun, SES)

---

## Contexte projet

- **Samir** : développeur web senior, Kabyle, basé au Canada, travaille pour GlobalLingua
- **Associé** : responsable marketing, a reçu `pitch.md`
- **Enseignant coordinateur** : déjà identifié en Algérie
- **Marché** : diaspora kabyle/amazigh — France, Canada, USA, monde entier
- **Nom de la plateforme** : **Azul** ✅
- **Script Tamazight** : Latin

---

## Dernière session — 23 septembre 2026

- **Production readiness** : renommage Azul, vérif email, mot de passe oublié, timezone enseignant, pages 404/500, config packages CAD
- **Admin Filament** : UserResource, PurchaseResource, StatsOverview (sparklines), LatestBookings, traductions FR/EN
- **DemoSeeder** : 50 apprenants + 5 enseignants avec créneaux, achats et séances — `php artisan migrate:fresh --seed`
- **68/68 tests passent**
- **Prochain** : Phase 8 — Site vitrine public (homepage, pricing, FAQ)
