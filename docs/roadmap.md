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

### Phase 3 — Design (Claude Design)
Créer les maquettes des écrans apprenant avant de coder les phases suivantes.
Les écrans auth et enseignant (Phases 1–2) sont fonctionnels mais non designés — ils seront alignés lors de l'intégration.

- [ ] Dashboard apprenant (crédits restants, prochain cours, liste des apprenants du compte)
- [ ] Page catalogue / choix de paquet (5, 10, 20 séances + essai)
- [ ] Page sélection enseignant + créneau disponible
- [ ] Page confirmation de réservation
- [ ] Design système : composants réutilisables, typographie, couleurs (base : violet/indigo, inspiration SavvyCal)
- [ ] *(Vitrine — à la fin de cette phase ou en Phase 7)* : homepage, "Devenir enseignant", FAQ, pricing

### Phase 4 — Catalogue et paiement
- [ ] `Package` : paquet de séances (stripe_price_id, sessions_count, price_usd)
- [ ] `Purchase` : achat lié à un apprenant (sessions_total, sessions_remaining, stripe_session_id)
- [ ] Page publique des paquets (5, 10, 20 séances + séance d'essai)
- [ ] Paiement Stripe via Cashier one-time checkout
- [ ] Webhook `checkout.session.completed` → création du Purchase
- [ ] Historique des achats (dashboard apprenant)

### Phase 5 — Réservation
- [ ] `LessonSession` / `Booking` : lié à un créneau, un Purchase, un apprenant
- [ ] Recherche et sélection d'un enseignant
- [ ] Sélection d'un créneau disponible
- [ ] Confirmation de booking (email apprenant + prof — dual email si `notification_email` défini)
- [ ] Rappel automatique 24h avant et 1h avant la séance
- [ ] Politique d'annulation (>24h = re-crédit, <24h = décompté)
- [ ] Suivi des séances enseignant (à venir, passées)
- [ ] Marquer une séance comme complétée / no-show

### Phase 6 — Points et récompenses
- [ ] `PointTransaction` : attribution de points à chaque séance complétée
- [ ] `Reward` : objectif défini par le parent
- [ ] Tableau de bord apprenant (points, progression vers l'objectif)
- [ ] Notification parent quand l'objectif est atteint

### Phase 7 — Admin complet + site vitrine
- [ ] Filament : gestion des utilisateurs (tous rôles)
- [ ] Filament : gestion des paquets (prix, nombre de séances)
- [ ] Filament : vue des bookings et purchases
- [ ] Filament : statistiques basiques (revenus, séances, inscriptions)
- [ ] Page d'accueil (hero, problème, solution, comment ça marche, tarifs)
- [ ] Page "Devenir enseignant"
- [ ] Page FAQ
- [ ] Page politique de confidentialité + mentions légales (RGPD / PIPEDA)
- [ ] Intégration design sur toutes les vues Livewire (auth + enseignant + apprenant)

---

## V2 — Améliorer la rétention et la croissance
*Priorités définies après validation du MVP avec les premiers utilisateurs.*

- Système de notation bidirectionnel prof / apprenant
- Messagerie interne pour éviter le bypass de la plateforme
- Programme de parrainage
- Cartes cadeaux
- Génération automatique de liens Google Meet
- Disponibilités récurrentes (génération depuis `availability_patterns`)
- Suivi pédagogique par apprenant

---

## V3 — Expansion
*Déclenché une fois la plateforme stable et rentable.*

- Offre B2B pour les associations de la diaspora
- Bibliothèque de ressources pédagogiques
- Parcours structurés par niveau
- Extension vers d'autres variantes amazigh

---

## Jalons cibles

| Jalon | Objectif |
|---|---|
| Semaine 1–2 | Documentation + installation ✅ |
| Semaine 3–4 | Phase 1 Auth + Phase 2 Enseignant ✅ |
| Semaine 5 | Phase 3 — Design (Claude Design) |
| Semaine 6–7 | Phase 4 — Catalogue + Paiement Stripe |
| Semaine 8–9 | Phase 5 — Réservation + emails + rappels |
| Semaine 10 | Phase 6 — Points + récompenses |
| Semaine 11–12 | Phase 7 — Admin complet + site vitrine + intégration design |
| Semaine 13 | Tests internes + corrections |
| Semaine 14 | Lancement beta fermé (5–10 profs, 20–30 familles) |
| Mois 4 | Lancement public + campagne marketing associé |
| Mois 6 | Bilan MVP → priorisation V2 |
