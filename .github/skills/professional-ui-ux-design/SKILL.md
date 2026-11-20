---
name: professional-ui-ux-design
description: Use when designing, reviewing, or changing PowerStat BD interfaces, responsive behavior, forms, navigation, or visual components.
---

# Professional UI/UX Design

Create purposeful interfaces for a Bangla-first electricity information portal,
not generic admin templates.

## Design process

1. Identify the user, task, page purpose, and important decision before changing
   layout or styling.
2. Read the current page, shared layout, components, and sibling pages. Reuse
   established patterns unless they create a specific usability problem.
3. Give each page a distinct hierarchy appropriate to its content. Shared
   components should improve consistency without making unrelated pages identical.
4. Use a restrained visual system: legible typography, consistent spacing,
   accessible contrast, a limited color palette, and purposeful emphasis.
5. Prefer readable lists, tables, and editorial layouts where they communicate
   operational information better than repeated cards.
6. Design every state: normal, validation error, empty result, success, and
   relevant unavailable or stale information.
7. Test responsive behavior with Bangla and English text at 360, 390, 768, 1024,
   and desktop widths when browser testing is available.

## PowerStat BD considerations

- Public pages currently use Tailwind CSS v4 and the admin pages use Bootstrap 5.
  Follow the page's existing framework; do not combine utility vocabularies or
  migrate frameworks as an incidental cleanup.
- Bangladesh is the product context: Bangla is the default, English is supported,
  and timestamps must be understandable in Asia/Dhaka.
- The portal reports administrator-published information. Visually distinguish
  estimates and record status; do not suggest live grid data or official approval.
- Keep essential account and language actions discoverable without hiding them
  behind unrelated navigation.
- Avoid overflow masking. Fix the content sizing or wrapping that causes overflow.

## Accessibility

- Use semantic landmarks and a logical heading order.
- Give every form field an associated label and every icon-only control an
  accessible name and keyboard-operable behavior.
- Keep visible focus indicators, adequate text contrast, and touch targets.
- Communicate statuses with text as well as color.
- Check dialog names, focus handling, expanded state, and keyboard dismissal.

Do not add animations, decorative gradients, oversized rounding, or shadows
without a specific usability purpose. Do not claim browser or accessibility
validation unless it was performed.
