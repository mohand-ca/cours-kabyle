# Design — Inspiration et direction visuelle

## Référence principale

**SavvyCal** — https://savvycal.com

Site de référence pour le design du site public et de l'application.

### Ce qu'on aime
- Design épuré et minimaliste, beaucoup d'espace blanc
- Palette de couleurs violet/indigo — moderne et professionnelle
- UX de réservation claire et intuitive (très pertinent pour notre système de créneaux)
- Typographie propre et lisible
- Cohérence entre le site public et l'application

---

## Direction visuelle

### Couleurs
- **Principale** : violet/indigo (inspiré SavvyCal)
- **Fond** : blanc
- **Texte** : quasi-noir
- **Accents** : dégradés subtils violet → bleu

### Style général
- Minimaliste, aéré
- Professionnel et rassurant (parents qui inscrivent leurs enfants)
- Moderne sans être froid

---

## Pages à designer (avant implémentation)

### Site public
- [ ] Page d'accueil (hero, problème, solution, comment ça marche, tarifs)
- [ ] Page profil enseignant (public)
- [ ] Page "Devenir enseignant"
- [ ] Page tarifs / paquets
- [ ] Page FAQ

### Application
- [ ] Dashboard apprenant (points, séances à venir)
- [ ] Dashboard parent (suivi enfants)
- [ ] Page recherche / sélection d'un enseignant
- [ ] Calendrier de réservation
- [ ] Profil enseignant (connecté)

---

## Architecture front-end par interface

| Interface | Technologie | Raison |
|---|---|---|
| Admin (gestion plateforme) | Filament panel | Conçu pour les tables, formulaires, actions admin |
| Espace enseignant | Livewire custom | Contrôle total, cohérent avec l'auth |
| Espace membre / apprenant | Livewire custom | Consumer-facing — doit correspondre au design Claude Design |
| Site vitrine | Blade + Livewire custom | Pages marketing, SEO, plein contrôle |

**Règle** : Filament est réservé à l'admin. Tout ce que voit l'utilisateur final (enseignant ou apprenant) est du Livewire custom avec le design issu de Phase 3.

Un panel Filament pour les membres est à éviter : son UX "back-office" entre en conflit avec un design consumer (marketplace familial). Personnaliser Filament pour ressembler à une vraie appli grand public demande autant de travail que du Livewire custom, avec plus de contraintes.

---

## Outil de design

**Claude Design** — à utiliser en Phase 3 avant de coder les interfaces apprenant.

Inputs à fournir à Claude Design :
- Ce fichier (`design.md`) pour la direction visuelle
- `docs/pitch.md` pour le ton et le positionnement produit
- La liste des écrans Phase 3 (voir roadmap)
