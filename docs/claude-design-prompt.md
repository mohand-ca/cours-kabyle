# Prompt Claude Design — Thamazight

Coller ce prompt tel quel dans Claude Design pour générer toutes les pages de l'application.

---

Design a complete SaaS web application for an online Kabyle language learning platform.

Kabyle (taqbaylit) is a Berber language spoken by millions in Algeria and the diaspora. The platform connects learners worldwide with native Kabyle-speaking teachers based in Algeria for one-on-one online lessons.

**Pick a cohesive, premium color palette on your own** — do not use generic blue or green. Think about what fits a cultural language platform: warmth, trust, modern. Choose 1 primary accent color, a neutral base, and a few semantic colors (success, warning, etc.).

---

## TYPOGRAPHY & STYLE

- Modern SaaS aesthetic — think Linear, Lemon Squeezy, Clerk
- Inter or a premium sans-serif font
- Strong visual hierarchy: bold headings, light secondary text
- Generous whitespace, subtle shadows, smooth transitions
- Cards with visible but soft borders, rounded-2xl corners throughout

---

## NAVIGATION — Top navbar (sticky)

- Logo (gradient icon square + app name)
- For guests: nav links (Features, How it works, Teach) + "Sign in" (ghost) + "Get started" (primary button)
- For learners: Dashboard · Find a teacher · My learners · Buy sessions + user avatar + name + logout
- For teachers: Dashboard · My profile · My availability + user avatar + name + logout
- Mobile: collapse to hamburger

---

## PAGE 1 — Marketing landing page (welcome)

- Hero section: large bold headline with gradient text on the key word, short tagline, 2 CTAs (primary: "Get started free", secondary: "I'm a teacher"), animated badge showing live availability, social proof avatar stack
- A visual element in the hero: either a floating app UI mockup (wireframe/skeleton style) or an abstract illustrative element — NOT a stock photo
- Stats bar: 4 key numbers (native teachers, lesson duration, location, cancellation policy)
- Features grid: 6 cards with icons — Verified teachers, Instant booking, Secure payment, Google Meet lessons, Family profiles, Free cancellation
- How it works: 3 steps with gradient icon badges and a horizontal connector line between them
- Teacher CTA section: gradient background card with grid texture overlay, headline, description, CTA button
- Footer: logo, nav links, copyright

---

## PAGE 2 — Login

- Centered card, max-w-sm
- App logo at top
- Title + subtitle
- Email + password fields
- Remember me checkbox
- Primary submit button (full width)
- Links: "No account? Sign up" and "I'm a teacher → create teacher account"

---

## PAGE 3 — Learner registration

- Same centered card layout
- Fields: Display name, First name + Last name (2 columns), Email, Password, Confirm password
- Primary submit button
- Link to login

---

## PAGE 4 — Teacher registration

- Same card layout
- Fields: Display name, Email, Password, Confirm password
- Info banner: "Your profile will be reviewed by our team within 48h before going live"
- Primary submit button
- Links to login and learner registration

---

## PAGE 5 — Learner dashboard

- Greeting heading (Hello, [Name]) + subtitle
- 4 stat cards in a row: Upcoming sessions (calendar icon), Sessions remaining (clock icon, accent color when > 0, with "Buy sessions →" link when = 0), Points (star icon), Learners (people icon) — each card has icon in tinted square, large number, small sublabel
- 5-column grid below: col-span-3 for upcoming sessions list, col-span-2 for learner profiles
- Session rows: date column (day number + abbreviated month in accent color) | vertical divider | time range + teacher name · learner name | "Confirmed" badge (colored dot + label) | ✕ cancel button that expands to a confirm/dismiss inline action
- Empty state for sessions: centered icon in soft square, descriptive text, link to find teacher
- Learner profile cards: gradient initials avatar, name, relationship label

---

## PAGE 6 — Find a teacher (catalog)

- Title + subtitle
- Filter bar: level dropdown + language dropdown
- Teacher cards (stacked list, not grid):
  - Gradient avatar (initials, large, rounded-xl)
  - Name + location/timezone
  - Availability badge: green dot + "X slots available" pill OR gray "No slots" pill
  - Short bio (2 lines truncated)
  - Language tags (gray pill) + level tags (colored by level: beginner/intermediate/advanced)
  - "View availability →" button aligned right
- When expanded: slot rows appear below the teacher card with date column + time + "Book" button
- When slot selected: learner picker appears inline (pill buttons per learner profile), then "Confirm booking" primary button + "Cancel" ghost

---

## PAGE 7 — Buy sessions (package catalog)

- Title + subtitle
- Banner if sessions remaining > 0: "You have X sessions remaining" (accent-colored)
- Package cards in a 3-column grid:
  - Package name
  - Large price in accent color
  - Price per session in gray
  - Session count
  - "Buy" full-width primary button
  - Hover: lift shadow + accent border

---

## PAGE 8 — Manage learners

- Title + "Add a learner" primary button (top right)
- When form open: card with fields: First name + Last name (2 col), Relationship dropdown (Myself/Child/Spouse/Other), Date of birth (optional), Notification email (optional, with hint text) — Save + Cancel buttons
- Learner list: each row has gradient initials avatar, name, relationship label, Edit link, Delete link (not shown for self)

---

## PAGE 9 — Teacher dashboard

- Greeting heading + subtitle
- Status banner (if not approved): amber for pending review, violet for incomplete profile — with icon, message, and CTA button if needed
- 3 stat cards: Upcoming lessons (calendar icon, count), Available slots (clock icon, count in green if > 0), Profile status (person icon, text label: Approved/Pending/Incomplete with matching color)
- 2 quick-access cards below: My profile (icon, title, description, chevron) + My availability (same) — hover: lift + accent border
- If upcoming booked sessions: list section below with same date-column row style as learner dashboard — blue "Booked" badge

---

## PAGE 10 — Teacher profile setup

- Title + status badge (Approved/Pending/Draft/Suspended) top right
- Each section in its own card:
  - Card 1: "About me" textarea (min 50 chars)
  - Card 2: "Teaching levels" — pill checkboxes (Beginner, Intermediate, Advanced) — selected = filled accent background
  - Card 3: "Spoken languages" — same pill checkbox style
  - Card 4: Google Meet link input
- Actions: "Save" (secondary/outline) + "Submit for review" (primary, only when draft and not yet submitted)

---

## PAGE 11 — Manage availability

- Title
- Warning banner if profile not approved (amber, with icon and link)
- Add slot form card: Date picker + Time picker + Duration dropdown (60/90/120 min) + "Add" primary button — all in one row
- Slot list below (in a card with overflow hidden):
  - Each row: date column (day bold accent, month xs gray) | vertical divider | time range | day of week label | status badge (green "Available" or blue "Booked") | ✕ delete button (only if available)
  - Empty state: centered icon + text

---

## SHARED COMPONENTS

- **Stat card**: white bg, soft border + ring, icon in tinted rounded square (top right), label (xs uppercase tracking-widest), large bold number, small sublabel
- **Session row**: date column (day bold accent, month xs gray), 1px vertical divider, flex-1 content, badge, action
- **Status badge**: small dot + label, color variants: green/amber/red/gray/blue
- **Primary button**: rounded-xl, font-bold, shadow with accent color, hover scale + darker shade
- **Ghost button**: transparent, border, hover accent border + text color
- **Input field**: rounded-xl border, focus ring in accent color (2px), subtle shadow-sm
- **Nav link**: pill shape, active = tinted background + accent text, inactive = gray text + hover gray bg

---

Generate each page as a separate HTML file using Tailwind CSS via CDN. Use realistic placeholder content. The overall vibe: premium, cultural, trustworthy — not sterile corporate, not playful startup. Something you'd pay $50/month for without hesitation.
