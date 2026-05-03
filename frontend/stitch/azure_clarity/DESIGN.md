# Design System Strategy: The Lucid Authority

## 1. Overview & Creative North Star
The North Star for this design system is **"The Lucid Authority."** 

We are moving away from the cluttered, "dashboard-heavy" aesthetics of traditional enterprise software. Instead, we embrace a high-end editorial approach that prioritizes cognitive ease and absolute clarity. By combining hyper-minimalism with high-accessibility, we create an interface that feels both prestigious and inclusive. 

The system breaks the "template" look by utilizing **intentional white space as a structural element** rather than a void. We lean into a single-column editorial flow that guides the eye with relentless focus. Through the use of bold, oversized typography and high-contrast tonal layering, we ensure that the most "low-tech" user feels empowered and respected by the interface.

---

## 2. Colors & Surface Philosophy
Our palette is anchored by **Deep Azure (`primary`: #00327d)** and **Stark White (`surface-container-lowest`: #ffffff)**. This high-contrast pairing isn't just an aesthetic choice; it is a functional requirement for maximum legibility.

### The "No-Line" Rule
To maintain a premium, modern feel, **1px solid borders are strictly prohibited for sectioning.** Boundaries must be defined solely through background color shifts. 
*   *Example:* A `surface-container-low` (#f3f4f5) section sitting on a `surface` (#f8f9fa) background creates a sophisticated, soft edge that feels organic rather than mechanical.

### Surface Hierarchy & Nesting
Treat the UI as a physical stack of fine paper. 
*   **Base Level:** `surface` (#f8f9fa)
*   **Secondary Content:** `surface-container-low` (#f3f4f5)
*   **Interactive/Elevated Cards:** `surface-container-lowest` (#ffffff)
*   **High-Priority Focus:** `surface-container-high` (#e7e8e9)

### The "Glass & Gradient" Rule
Flatness can sometimes feel "cheap." To inject "soul," use subtle gradients on primary CTAs (transitioning from `primary` #00327d to `primary-container` #0047ab). For floating navigation or modal overlays, apply **Glassmorphism**: use `surface` colors at 85% opacity with a `20px` backdrop-blur to allow the content underneath to bleed through, creating a sense of environmental depth.

---

## 3. Typography: The Editorial Voice
We use **Be Vietnam Pro** exclusively. To achieve the "Hyper-Minimalist" look, we rely on scale and weight rather than color to denote importance.

*   **Display & Headlines:** Use `display-lg` (3.5rem) and `headline-lg` (2rem) with **Bold (700) weights**. These serve as "anchors" for the user, clearly stating the purpose of the screen.
*   **Body Copy:** Our "Standard" is `body-lg` (1rem). Never go below `body-md` (0.875rem) for functional text to ensure high accessibility.
*   **Visual Hierarchy:** Use `on-surface` (#191c1d) for all primary text. Use `on-surface-variant` (#434653) only for secondary metadata. The contrast ratio must always exceed 7:1 for core content.

---

## 4. Elevation & Depth
In this system, depth is a tool for focus, not decoration.

*   **Tonal Layering:** Avoid shadows for standard cards. Instead, place a `surface-container-lowest` card against a `surface-container-low` background. The subtle 2% shift in brightness is enough to signal a change in context.
*   **Ambient Shadows:** If an element must float (e.g., a Floating Action Button), use a highly diffused shadow: `Box-shadow: 0 12px 40px rgba(0, 50, 125, 0.08);`. The shadow is tinted with our Azure `primary` color to keep the light source feeling natural.
*   **The "Ghost Border" Fallback:** If a container sits on an identical background color and requires definition, use the `outline-variant` (#c3c6d5) at **15% opacity**. Never use a 100% opaque border.

---

## 5. Components

### Buttons (Extra-Large Touch Targets)
All interactive targets must have a minimum height of **56px** to accommodate all motor abilities.
*   **Primary:** Solid `primary` fill with `on-primary` text. Use an **8px to 12px** corner radius (`md` to `lg`).
*   **Secondary:** `primary-fixed-dim` background with `on-primary-fixed` text. No border.
*   **Tertiary:** Bold `primary` text with no background, but with a 56px minimum hit area.

### Iconography-First Approach
Icons are not decorations; they are wayfinders. 
*   Use thick, 2pt stroke weights to match the bold typography.
*   Every icon must be accompanied by a `label-md` or `title-sm` text descriptor to ensure low-tech users are never guessing.

### Cards & Lists
*   **The Divider Forbiddance:** Horizontal lines are banned. To separate list items, use **Vertical White Space** (minimum 24px) or a alternating subtle background tint (`surface-container-lowest` vs `surface-container-low`).
*   **Single Column:** All cards must span the full width of the content container (max-width 800px) to maintain a singular focus and prevent "eye-scanning fatigue."

### Input Fields
*   Text inputs should utilize a "filled" style using `surface-container-high` with a thick bottom-accent of `primary` when focused.
*   Labels must be **Bold** and always visible (no disappearing placeholder text).

---

## 6. Do's and Don'ts

### Do
*   **Do** use `title-lg` for all button labels to ensure they are readable at a glance.
*   **Do** utilize "Overlapping Elements"—for example, letting a high-contrast icon partially overlap a container edge to create a signature, custom feel.
*   **Do** embrace asymmetry; pull your main headers to the far left while keeping CTA buttons slightly offset to break the "centered template" look.

### Don't
*   **Don't** use "Grey" for disabled states. Use `primary` at 10% opacity to maintain the brand’s color DNA even in inactive states.
*   **Don't** use small icons. Minimum icon size is 24x24px within a 48x48px touch container.
*   **Don't** use multi-column layouts for forms. One question per line; one task per screen.