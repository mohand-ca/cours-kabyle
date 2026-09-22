# Roadmap

## MVP — Lancer et valider le marché

Objectif : avoir les premiers cours payants en ligne le plus vite possible.
Périmètre volontairement réduit — livrer vite, apprendre du marché.

### Phase 1 — Authentification et rôles ✅
- [x] Inscription / connexion (email + mot de passe)
- [x] Vérification email
- [x] Rôles : admin, teacher, learner
- [x] Un utilisateur peut ajouter des apprenants (lui-même, ses enfants, son conjoint) — via `learners.relationship`

### Phase 2 — Espace enseignant ✅
- [x] Inscription dédiée `/become-a-teacher`
- [x] Complétion du profil (bio, niveaux, langues, lien Google Meet)
- [x] Soumission pour validation admin
- [x] Calendrier de disponibilités (créneaux manuels, stockés en UTC)
- [x] Validation / suspension via Filament admin

### Phase 3 — Design ✅
- [x] Projet Claude Design créé (privé, account Samir)
- [x] 11 composants HTML : foundations, components, learner, public
- [x] Palette violet/indigo définie, hero dark `#1e1b4b`, fond blanc app
- [x] Typographie Inter, gradients, système de badges et cards

### Phase 4 — Espace apprenant + réservations ✅
- [x] Dashboard apprenant (séances à venir, apprenants, sessions restantes)
- [x] Gestion des apprenants (CRUD, relation self/child/spouse/other)
- [x] Catalogue enseignants approuvés avec filtres niveau/langue
- [x] `LessonSession` : lié à un créneau, un purchase, un apprenant
- [x] Flow réservation inline (sélection créneau → choix apprenant → confirmation)
- [x] DB transaction + `lockForUpdate` (anti double-booking)
- [x] Annulation → libère le créneau + re-crédite le purchase

### Phase 5 — Paiements Stripe ✅
- [x] `SessionPackage` (table packages) : nom, sessions_count, price_cents, stripe_price_id
- [x] `Purchase` : sessions_total, sessions_remaining, stripe_session_id, status
- [x] Page packages (`/packages`) avec prix et bouton achat
- [x] Stripe Checkout one-time via `$user->checkout()`
- [x] Webhook `checkout.session.completed` → `StripeEventListener` → crée `Purchase` (idempotent)
- [x] Booking exige un `Purchase` actif avec `sessions_remaining > 0`
- [x] Décrément `sessions_remaining` à la réservation, re-crédit à l'annulation
- [x] CSRF exclu pour `stripe/*`

### Phase 6 — Emails ← PROCHAINE ÉTAPE
- [ ] Email de confirmation réservation : apprenant (+ `notification_email` si défini) + enseignant
- [ ] Rappel automatique 24h avant la séance (job planifié)
- [ ] Email de bienvenue enseignant après approbation admin
- [ ] Choix provider : Mailgun ou Resend (à décider — Resend recommandé pour sa DX)

### Phase 7 — Admin Filament complet
- [ ] Filament : gestion des paquets (créer/modifier prix + stripe_price_id)
- [ ] Filament : vue des bookings (LessonSessions) avec filtres
- [ ] Filament : vue des purchases avec statut
- [ ] Filament : statistiques basiques (revenus, séances, inscriptions)
- [ ] Filament : gestion des utilisateurs (tous rôles)

### Phase 8 — Site vitrine
- [ ] Page d'accueil (hero, problème, solution, comment ça marche, tarifs)
- [ ] Page "Devenir enseignant"
- [ ] Page FAQ
- [ ] Page politique de confidentialité + mentions légales (RGPD / PIPEDA)
- [ ] Remplace `welcome.blade.php`

### Phase 9 — Intégration design + déploiement
- [ ] Appliquer le design Claude Design sur toutes les vues (auth, enseignant, apprenant)
- [ ] Configurer Stripe en prod (clés + `php artisan cashier:webhook`)
- [ ] Configurer MySQL 8 sur Laravel Cloud (EU)
- [ ] Variables d'env prod : `APP_URL`, `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
- [ ] `PackageSeeder` avec les vrais `stripe_price_id` du dashboard Stripe

---

## V2 — Améliorer la rétention et la croissance
*Priorités définies après validation du MVP avec les premiers utilisateurs.*

- Système de notation bidirectionnel prof / apprenant
- Points et récompenses (`PointTransaction`, `Reward`, objectifs définis par le parent)
- Messagerie interne pour éviter le bypass de la plateforme
- Programme de parrainage
- Cartes cadeaux
- Génération automatique de liens Google Meet
- Disponibilités récurrentes (génération depuis `availability_patterns`)
- Suivi pédagogique par apprenant
- Politique d'annulation >24h re-crédit / <24h décompté

---

## V3 — Expansion
*Déclenché une fois la plateforme stable et rentable.*

- Offre B2B pour les associations de la diaspora
- Bibliothèque de ressources pédagogiques
- Parcours structurés par niveau
- Extension vers d'autres variantes amazigh

---

## Jalons cibles (révisés)

| Jalon | Objectif |
|---|---|
| Semaine 1–2 | Documentation + installation ✅ |
| Semaine 3–4 | Phase 1 Auth + Phase 2 Enseignant ✅ |
| Semaine 5 | Phase 3 Design + Phase 4 Espace apprenant ✅ |
| Semaine 6 | Phase 5 Paiements Stripe ✅ |
| Semaine 7 | Phase 6 Emails |
| Semaine 8 | Phase 7 Admin Filament complet |
| Semaine 9 | Phase 8 Site vitrine |
| Semaine 10 | Phase 9 Intégration design + déploiement Cloud |
| Semaine 11 | Tests internes + corrections |
| Semaine 12 | Lancement beta fermé (5–10 profs, 20–30 familles) |
| Mois 4 | Lancement public + campagne marketing associé |
| Mois 6 | Bilan MVP → priorisation V2 |
