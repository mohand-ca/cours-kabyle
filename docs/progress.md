# Progress — État du projet

> Ce fichier est mis à jour à chaque session. Il sert de point d'entrée pour reprendre le travail sans perdre le contexte.

---

## Statut général
**Phase : MVP — Phase 2 terminée (Espace enseignant)**
34/34 tests passent.

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
  - `learners.relationship` : self / child / spouse / other (pas de table `guardians`)
  - `learners.notification_email` : email additionnel optionnel par apprenant
- **Disponibilités** : deux tables — `availability_patterns` (règle récurrente, V2) + `availability_slots` (créneaux réels, création manuelle pour le MVP)
- **Paiements** : Stripe en USD via Laravel Cashier — checkout one-time par paquet (pas de subscription)
- **Google Meet** : lien statique du prof copié dans chaque booking
- **`LessonSession`** (pas `Session`) pour éviter le conflit avec la façade `Session::` de Laravel
- **`Review` model → PAS dans le MVP** (reporté en V2)
- **Code en anglais** : variables, méthodes, colonnes, routes, clés de config — tout en anglais
- **Traduction dès le début** : chaque chaîne visible dans une vue passe par `__('clé')` — fichiers dans `lang/fr/` et `lang/en/`

### Installation
- Laravel 13.32.0 ✅
- Livewire 4.4.6 ✅
- Filament 5.8.4 + panel admin initialisé ✅
- Spatie Permission 8.3.0 ✅
- Laravel Cashier 16.8.0 ✅
- Spatie MediaLibrary 11.23.8 ✅
- DomPDF 3.1.2 ✅
- Spatie ActivityLog 4.12.3 ✅
- Laravel Boost 2.9.1 ✅

---

### Phase 1 — Auth + Rôles ✅

**Migrations :**
- `add_role_timezone_to_users_table` — colonnes `role` (enum) + `timezone` sur `users` ✅
- `create_learners_table` — `user_id`, `first_name`, `last_name`, `date_of_birth`, `relationship`, `notification_email`, `points` ✅

**Models :**
- `User` — traits `HasRoles` + `Billable` + interfaces `FilamentUser` + `MustVerifyEmail` ✅
- `Learner` — relation `user()`, méthode `notificationAddresses()`, factory avec états `self()` / `child()` ✅

**Auth :**
- `Livewire\Auth\Register` + `RegisterForm` — inscription → crée User + Learner(self) + rôle Spatie ✅
- `Livewire\Auth\Login` — connexion → redirection admin `/admin`, teacher `teacher.dashboard`, learner `/dashboard` ✅
- Email de vérification (Laravel `Registered` event) — après vérification, teacher → `teacher.dashboard` ✅
- Layout auth violet/indigo ✅

**Filament :**
- Panel admin couleur Violet ✅
- `canAccessPanel()` → `hasRole('admin') && hasVerifiedEmail()` ✅

**Routes :** `/register`, `/login`, `/dashboard` (role:learner), `/logout`, email verify ✅

**Seeders :** `RoleSeeder` — rôles `admin` / `teacher` / `learner` + compte `admin@thamazight.com` ✅

**Tests : 19/19** ✅
- `RegisterTest` (6 tests), `LoginTest` (4 tests), `AdminPanelAccessTest` (5 tests) + 4 Phase 2

---

### Phase 2 — Espace enseignant ✅

**Migrations :**
- `create_teacher_profiles_table` — `user_id` unique FK, `bio`, `levels` JSON, `languages` JSON, `meet_link`, `status` enum (pending/approved/suspended), `submitted_at` ✅
- `create_availability_patterns_table` — `teacher_profile_id`, `day_of_week`, `start_time`, `end_time`, `is_active` ✅
- `create_availability_slots_table` — `teacher_profile_id`, `availability_pattern_id` nullable, `starts_at`, `ends_at`, `status` enum, index composite ✅

**Models :**
- `TeacherProfile` — méthodes `isPending()`, `isApproved()`, `isSuspended()`, `submit()`, `approve()`, `suspend()` ; factory avec états `approved()`, `submitted()`, `suspended()` ✅
- `AvailabilityPattern` — factory + méthode `dayName()` ✅
- `AvailabilitySlot` — scope `scopeAvailable()`, méthodes `isAvailable()`, `isBooked()` ; factory avec états `booked()`, `cancelled()` ✅

**Auth enseignant :**
- `Livewire\Auth\TeacherRegister` + `TeacherRegisterForm` — inscription séparée `/become-a-teacher`, crée User(role=teacher) + TeacherProfile vierge ✅

**Livewire enseignant :**
- `Teacher\Dashboard` — statut profil + créneaux réservés à venir ✅
- `Teacher\ProfileSetup` — bio, niveaux, langues, Meet link, soumission pour validation ✅
- `Teacher\AvailabilityCalendar` — ajout de créneaux (vérifie approbation + chevauchement, stocke en UTC), annulation ✅

**Filament admin :**
- `TeacherProfileResource` — liste, édition, actions approve/suspend, badge statut coloré ✅

**Routes enseignant :**
- Groupe `prefix('teacher')`, middleware `role:teacher` : `teacher.dashboard`, `teacher.profile`, `teacher.availability` ✅

**Tests : 15/15** ✅
- `TeacherProfileTest` (7 tests), `AvailabilitySlotTest` (7 tests) + 1 repris en Phase 1

---

### Standards et conventions ✅

- **i18n** : toutes les chaînes passent par `__()` — `lang/fr/auth.php`, `lang/fr/teacher.php`, `lang/en/auth.php`, `lang/en/teacher.php` ✅
- **`APP_LOCALE=fr`** dans `.env` ✅
- **Règles AI** enregistrées dans `.ai/rules/` : code en anglais, traductions obligatoires ✅

---

## Ce qui reste à faire (MVP)

- [ ] **Phase 3** — Design avec Claude Design : maquettes écrans apprenant (dashboard, catalogue, sélection enseignant/créneau, confirmation booking) + système de design
- [ ] **Phase 4** — Catalogue + paiement Stripe : `Package`, `Purchase`, Cashier one-time checkout, webhook `checkout.session.completed`
- [ ] **Phase 5** — Réservation : `LessonSession`/`Booking`, emails confirmation + rappels (24h / 1h), politique annulation
- [ ] **Phase 6** — Points + récompenses : `PointTransaction`, `Reward`, dashboard apprenant
- [ ] **Phase 7** — Admin Filament complet + site vitrine + intégration design sur toutes les vues
- [ ] Configurer MySQL en production (Laravel Cloud)

---

## Contexte projet

- **Samir** : développeur web senior, Kabyle, basé au Canada, travaille pour GlobalLingua
- **Associé** : responsable marketing, a reçu `pitch.md`
- **Enseignant coordinateur** : déjà identifié en Algérie, gère la qualité pédagogique
- **Marché** : diaspora kabyle/amazigh — France, Canada, USA, monde entier
- **Nom de la plateforme** : pas encore décidé
- **Script Tamazight** : Latin

---

## Dernière session — 21 septembre 2026

- **Phase 2 complétée** : espace enseignant (TeacherProfile, disponibilités, Filament admin) — 34 tests passent
- **Bug corrigé** : `TeacherRegister` utilisait `RegisterForm` (qui requiert firstName/lastName), remplacé par `TeacherRegisterForm` dédié
- **Bug corrigé** : route `/dashboard` non protégée par rôle — ajout de `role:learner`
- **i18n mis en place** : toutes les chaînes hardcodées migrées vers `lang/fr/` et `lang/en/`, règles AI enregistrées
- Prochaine étape : **Phase 3** — Catalogue de paquets + paiement Stripe
