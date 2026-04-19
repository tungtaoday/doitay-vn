# Design System Document: High-End Service Editorial

## 1. Overview & Creative North Star: "The Digital Craftsman"

The objective of this design system is to transform a standard utility website into a premium, editorial-grade service platform. We are moving away from the "directory" aesthetic toward a "concierge" experience.

**Creative North Star: The Digital Craftsman**

This philosophy treats digital interface elements with the same precision and care a master technician brings to their trade. We replace rigid, boxy layouts with **intentional asymmetry, sophisticated tonal depth, and a high-contrast typography scale.** The goal is to instill immediate trust through "Invisible Luxury"—where the interface feels so balanced and quiet that the user's focus remains entirely on the quality of the professionals they are hiring.

---

## 2. Colors: Tonal Depth & The "No-Line" Rule

Our palette uses a spectrum of sky blues and deep architectural navies to create a sense of reliability and professional calm.

### Core Token Strategy

- **Primary (#48BBE2):** A bright, professional sky blue used for authoritative actions and key brand moments.
- **Secondary/Surface (#102F4B):** A deep navy used for grounding elements, text, and non-chromatic backgrounds to provide a premium feel.
- **Tertiary/Accents (#FFD700):** A vibrant gold used sparingly for "Verified" badges or "Expert" highlights to provide a high-contrast, gold-standard accent.

### The "No-Line" Rule

**Prohibit 1px solid borders for sectioning.** To define boundaries between content blocks (e.g., Hero section vs. Services grid), use background shifts.

- *Implementation:* Transition from primary surface to a secondary container. The lack of a hard line creates an expensive, seamless feel.

### Surface Hierarchy & Nesting

Treat the UI as physical layers. Use the neutral and secondary tiers to define "importance" through elevation:

- **Base Layer:** Primary neutral surface.
- **Secondary Content:** Container low tiers.
- **Interactive Cards:** Container highest or lowest (depending on the desired "pop").

---

## 3. Typography: Editorial Authority

We utilize a dual-font system to balance heritage with modern technical precision.

- **Display & Headlines (Be Vietnam Pro):** This is our "Editorial" voice. Used for large headers. Its open counters and geometric curves provide a modern, welcoming professional tone. Use `medium` (500) and `bold` (700) weights to create clear visual anchors.
- **Body & Labels (Inter):** The "Functional" voice. Inter's high x-height makes it incredibly readable for service descriptions and technical details.
- **The Contrast Ratio:** Maintain a significant scale jump between headlines and body to create a high-end, asymmetric layout.

---

## 4. Elevation & Depth: Atmospheric Layering

Standard shadows are strictly forbidden. We create depth through light and tone.

- **The Layering Principle:** Depth is achieved by stacking. Use the contrast in lightness between surface levels to create a "Soft Lift" without a single shadow pixel.
- **Ambient Shadows:** For floating elements like "Book Now" buttons, use an **Extra-Diffused Shadow** tinted by the secondary navy color at very low opacity (6%).
- **The Ghost Border Fallback:** If a container requires definition against a similar color, use a 1px border with the outline variant at **15% opacity**.

---

## 5. Components: Precision Styling

### Buttons

- **Primary:** Solid fill using the Primary Blue (#48BBE2), utilizing moderate (level 2) roundedness.
- **Secondary:** Transparent background with a Ghost Border and secondary navy text.
- **Interaction:** On hover, apply a subtle tonal shift to the primary color.

### Cards & Service Items

- **Forbid dividers.** Use the standard spacing scale (level 2) to provide breathing room between service items.
- **Hover State:** Instead of a shadow, change the background color level and apply a minor upward translation.

### Input Fields

- **Search & Forms:** Use the neutral container as the field background. Labels should be placed *above* the field, not inside as placeholders, to maintain high-end accessibility.
- **Focus State:** A 2px primary blue glow.

### Professional Badges (Chips)

- **Verified Status:** Use the Tertiary Gold (#FFD700) for a "Seal of Quality." Use maximum roundedness to create a soft, pill-shaped aesthetic.

---

## 6. Do's and Don'ts

### Do:

- **Do** use whitespace as a functional tool. Moderate spacing (level 2) ensures clarity without feeling sparse.
- **Do** keep large headlines aligned to the left for an editorial look.
- **Do** use the Primary Blue for key interaction points to guide the user journey.

### Don't:

- **Don't** use 100% black text. Always use the Secondary Navy (#102F4B) to maintain a softer, premium contrast.
- **Don't** over-use the Tertiary Gold; it is an accent, not a primary UI color.
- **Don't** use sharp corners; adhere to the moderate roundedness (level 2) for a balanced professional feel.

---

## 7. Spacing & Rhythm

This system relies on a consistent grid based on the "Normal" spacing scale (level 2):

- **Section Gaps:** Maintain significant vertical margins to ensure the "Editorial" feel.
- **Component Padding:** Ensure elements never feel "cramped" by utilizing the standard spacing tokens across all containers.
