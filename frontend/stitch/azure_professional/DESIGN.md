```markdown
# Design System Document: High-End Service Editorial

## 1. Overview & Creative North Star: "The Digital Craftsman"
The objective of this design system is to transform a standard utility website into a premium, editorial-grade service platform. We are moving away from the "directory" aesthetic toward a "concierge" experience.

**Creative North Star: The Digital Craftsman**
This philosophy treats digital interface elements with the same precision and care a master technician brings to their trade. We replace rigid, boxy layouts with **intentional asymmetry, sophisticated tonal depth, and a high-contrast typography scale.** The goal is to instill immediate trust through "Invisible Luxury"—where the interface feels so balanced and quiet that the user's focus remains entirely on the quality of the professionals they are hiring.

---

## 2. Colors: Tonal Depth & The "No-Line" Rule
Our palette moves beyond simple blue; it uses a spectrum of deep teals and aquatic greys to create a sense of reliability and professional calm.

### Core Token Strategy
- **Primary (`#006781`):** Use for authoritative actions and key brand moments.
- **Surface & Background (`#f8f9ff`):** A cool-tinted white that prevents the clinical feeling of pure `#FFFFFF`.
- **Tertiary/Accents (`#705d00` / `#ccac00`):** Used sparingly for "Verified" badges or "Expert" highlights to provide a warm, gold-standard contrast to the cool primary tones.

### The "No-Line" Rule
**Prohibit 1px solid borders for sectioning.** To define boundaries between content blocks (e.g., Hero section vs. Services grid), use background shifts. 
- *Implementation:* Transition from `surface` to `surface-container-low`. The lack of a hard line creates an expensive, seamless feel.

### Surface Hierarchy & Nesting
Treat the UI as physical layers. Use the `surface-container` tiers to define "importance" through elevation:
- **Base Layer:** `surface`
- **Secondary Content:** `surface-container-low`
- **Interactive Cards:** `surface-container-highest` or `surface-container-lowest` (depending on the desired "pop").

### The Glass & Gradient Rule
- **Hero & Primary CTAs:** Use a subtle linear gradient from `primary` to `primary_container` (at 15-degree angles) to provide a soft, liquid texture.
- **Overlays:** For floating search bars or navigation, use Glassmorphism (semi-transparent `surface` color with a 20px `backdrop-blur`).

---

## 3. Typography: Editorial Authority
We utilize a dual-font system to balance Vietnamese heritage with modern technical precision.

- **Display & Headlines (Be Vietnam Pro):** This is our "Editorial" voice. Used for large headers (`display-lg` to `headline-sm`). Its open counters and geometric curves provide a modern, welcoming professional tone. Use `medium` (500) and `bold` (700) weights to create clear visual anchors.
- **Body & Labels (Inter):** The "Functional" voice. Inter’s high x-height makes it incredibly readable for service descriptions and technical details. 
- **The Contrast Ratio:** Maintain a significant scale jump between headlines and body. For example, a `headline-lg` (2rem) title should sit near `body-md` (0.875rem) supporting text to create a high-end, asymmetric layout.

---

## 4. Elevation & Depth: Atmospheric Layering
Standard shadows are strictly forbidden. We create depth through light and tone.

- **The Layering Principle:** Depth is achieved by stacking. Place a `surface-container-lowest` (Pure White) card on a `surface-container-low` (Pale Blue-Grey) background. The contrast in lightness creates a "Soft Lift" without a single shadow pixel.
- **Ambient Shadows:** For floating elements like "Book Now" buttons, use an **Extra-Diffused Shadow**:
  - `Blur: 24px | Spread: -4px | Color: rgba(0, 29, 53, 0.06)` (A tint of `on_surface`).
- **The Ghost Border Fallback:** If a container requires definition against a similar color, use a 1px border with `outline_variant` at **15% opacity**. This creates a suggestion of a border rather than a hard constraint.

---

## 5. Components: Precision Styling

### Buttons
- **Primary:** Gradient fill (`primary` to `primary_container`), `xl` (1.5rem) roundedness. No border.
- **Secondary:** Transparent background with a `Ghost Border` and `primary` text.
- **Interaction:** On hover, shift the gradient intensity rather than a simple color darken.

### Cards & Service Items
- **Forbid dividers.** Use `32px` vertical padding (`spacing scale`) to separate "Electrician" from "Plumber" items.
- **Hover State:** Instead of a shadow, change the background from `surface-container-lowest` to `surface-bright` and apply a `0.5rem` upward translation.

### Input Fields
- **Search & Forms:** Use `surface-container-low` as the field background. Labels (`label-md`) should be placed *above* the field, not inside as placeholders, to maintain high-end accessibility.
- **Focus State:** A 2px `primary` glow with a 4px `backdrop-blur`.

### Professional Badges (Chips)
- **Verified Status:** Use `tertiary_container` (Gold/Yellow) with `on_tertiary_container` text. Use `full` roundedness to create a soft, pill-shaped "Seal of Quality."

---

## 6. Do's and Don'ts

### Do:
- **Do** use whitespace as a functional tool. If you think there is enough margin, add 16px more.
- **Do** use `primary_fixed_dim` for subtle icons to keep them from competing with text.
- **Do** align large headlines to the left while keeping supporting body text in a narrower, focused column for an editorial, asymmetric look.

### Don't:
- **Don't** use 100% black text. Always use `on_surface` (`#001d35`) to maintain a softer, premium contrast.
- **Don't** use standard `0.5rem` roundedness for everything. Use `xl` (1.5rem) for main containers and `full` for interactive chips to create a friendly, modern interface.
- **Don't** use icons of varying stroke weights. All icons must be "Light" or "Regular" weight to match the Inter typography.

---

## 7. Spacing & Rhythm
This system relies on an **8px grid**, but for section-level spacing, we prioritize "Breathing Rooms":
- **Section Gaps:** 128px (Desktop) / 80px (Mobile).
- **Component Padding:** Always use `lg` (1rem) or `xl` (1.5rem) to ensure elements never feel "cramped" or "cheap."