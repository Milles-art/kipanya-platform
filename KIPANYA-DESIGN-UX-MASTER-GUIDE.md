# KIPANYA — Design & UX Master Guide

**Status:** Established — Source of Truth for Public Redesign
**Scope:** Public/non-admin experience only
**Implementation target:** Laravel Blade
**Admin policy:** READ-ONLY / DO NOT MODIFY

---

## 0. NON-NEGOTIABLE PROJECT BOUNDARY

Kipanya is a Laravel product with two distinct surfaces:

- **Public product:** the experience users see and use.
- **Admin/Studio:** internal management experience.

### Absolute rule

> **No redesign work may modify the admin surface.**

Protected area:

`resources/views/admin/**`

Also treat admin controllers, admin routes, admin middleware, admin-specific assets and admin business logic as protected unless a separate explicit task authorizes backend changes.

Never edit:

- `resources/views/admin/**`
- `Admin*Controller` classes
- `EnsureAdminWebAccess`
- admin authentication/authorization flows
- admin routes under `/admin`
- admin-specific data operations

`storage/framework/views/**` is compiled output and must never be edited manually.

Everything else may be redesigned when required, while preserving application functionality.

---

# 1. PRODUCT UNDERSTANDING

Kipanya is not merely a landing page. The current public application includes multiple connected experiences:

- Home
- Discovery/library
- Cartoon detail
- Categories
- Collections
- Episodes/watch experience
- Favorites
- User account/profile
- OTP login/register
- Kipanya Wear
- Wear product detail
- T-shirt/design experience
- Cart
- Checkout
- Order confirmation/order viewing

The redesign must make these feel like **one product**, not unrelated pages.

### Core product loop

`Discover → Understand → Watch/Engage → Save/Return → Explore More`

### Commerce loop

`Discover Wear → Product → Choose/customize → Cart → Checkout → Confirmation → Return`

These loops should coexist without making the product feel like two separate websites.

---

# 2. DESIGN NORTH STAR

Kipanya should feel:

- Distinctive
- Contemporary
- Confident
- Human
- Editorial
- Entertaining
- Playful where appropriate
- Commercially credible
- Fast and easy to understand
- Designed rather than generated

The visual result must feel like a **real product with a deliberate design team behind it**.

Do not optimize for visual novelty at the expense of usability.

### North-star question

> If the logo and project name disappeared, would the interface still feel intentionally designed rather than like an AI template?

If the answer is no, redesign it.

---

# 3. REFERENCE METHOD

We use references to study **principles**, never to clone brands.

### Entertainment/product references

Study products such as:

- Netflix — content discovery, continuation, hierarchy
- Crunchyroll — episode/library organization
- YouTube — search, recommendations, content density
- Disney+ — entertainment presentation and visual storytelling

### Commerce references

Study:

- Nike — product storytelling and merchandising
- Apple — product detail clarity
- Shopify — commerce interaction patterns
- ASOS — filtering, browsing and product discovery

### Design references

Use high-quality work from:

- Awwwards
- CSS Design Awards
- Behance
- Dribbble
- Mobbin
- Godly
- Land-book

### Critical reference rule

A reference may inform a **specific pattern** only.

Bad:

> Make Kipanya look like Netflix.

Good:

> Study how Netflix establishes content hierarchy and continuation, then adapt the principle to Kipanya's own brand language.

Never copy another company's branding, copy, layout wholesale, or distinctive visual identity.

---

# 4. UX PRINCIPLES

## 4.1 One primary purpose per screen

Every page must have a clear reason for existing.

The user should understand within seconds:

1. Where they are.
2. What they can do.
3. What the primary action is.

## 4.2 Recognition over memory

Users should not have to remember where something is or what an icon means.

Use visible labels when meaning could be ambiguous.

## 4.3 Progressive disclosure

Show what users need first. Reveal secondary detail when useful.

## 4.4 Reduce friction

Remove unnecessary steps, fields, clicks, decisions and interruptions.

## 4.5 Strong feedback

Every meaningful action should communicate its result.

Examples:

- Favorite added → immediate visual confirmation.
- Cart item added → clear confirmation and cart state update.
- Form submitted → progress/result state.
- Error → actionable explanation.

## 4.6 Preserve user control

Avoid surprising redirects, destructive actions without confirmation, and interactions that trap users.

## 4.7 Design the complete state

Every important component should consider:

- Default
- Hover
- Focus
- Active
- Disabled
- Loading
- Empty
- Error
- Success

## 4.8 Content before decoration

Visual effects never outrank the actual content or action.

---

# 5. INFORMATION ARCHITECTURE

The public experience should have a clear mental model.

Recommended top-level mental model:

- **Home** — what is happening now
- **Discover** — find content
- **Cartoons** — browse/watch content
- **Collections/Categories** — browse by context
- **Wear** — shop
- **Account** — personal space

The navigation may be visually reinvented, but these concepts must remain easy to discover.

### Navigation rules

- Current location must be obvious.
- Primary navigation should remain stable.
- Mobile navigation must prioritize the most frequent actions.
- Search should be easy to find.
- Account state should be obvious.
- Cart state should be visible when relevant.
- Do not overload the navbar with every possible destination.

---

# 6. VISUAL DESIGN LANGUAGE

## 6.1 Layout

Use deliberate composition rather than a collection of centered cards.

Prefer:

- Strong alignment
- Editorial rhythm
- Intentional asymmetry when useful
- Clear content widths
- Varied but controlled section compositions
- Meaningful whitespace

Avoid:

- Everything centered
- Endless identical card grids
- Arbitrary floating elements
- Sections added only to make pages longer

## 6.2 Shape language

Use a consistent approach to:

- Corner radius
- Borders
- Image crops
- Buttons
- Inputs
- Cards

Not every element needs the same radius.

Shape should communicate hierarchy.

## 6.3 Elevation

Use shadows/elevation sparingly.

Not every surface should float.

Prefer hierarchy through:

- Contrast
- Spacing
- Scale
- Typography
- Borders
- Position

## 6.4 Color

Create semantic color tokens before styling pages.

Minimum semantic system:

- Background
- Surface
- Elevated surface
- Primary text
- Secondary text
- Muted text
- Border
- Brand/primary
- Brand hover/active
- Success
- Warning
- Danger
- Informational

Color must serve hierarchy and meaning.

Avoid random gradients and multi-accent palettes.

## 6.5 Typography

Typography is a primary structural tool.

Define:

- Display heading
- H1
- H2
- H3
- Body large
- Body
- Body small
- Label
- Caption/meta
- Button text

Use weight and scale deliberately. Do not create hierarchy only by making everything huge.

---

# 7. ANTI-AI DESIGN RULES

These are explicit quality controls.

Do NOT default to:

- Purple/blue SaaS gradients
- Giant gradient hero sections
- Excessive glassmorphism
- Excessive `rounded-2xl` cards
- Shadow on every component
- Random blobs
- Decorative lines with no purpose
- Huge headings on every page
- Icon inside every button
- Generic stock-style testimonials
- Repetitive 3-column sections
- Fake statistics
- Generic marketing copy
- Excessive floating elements
- Excessive animations
- Animation for animation's sake
- Identical cards repeated across every section
- Artificially symmetrical layouts
- Empty space used without compositional intent

### AI smell test

If a section could be pasted into 1,000 unrelated SaaS websites without changing its meaning, it probably does not belong in Kipanya.

---

# 8. COMPONENT SYSTEM

Build reusable Blade components/partials where repetition exists.

Core component families should include:

### Global

- Public shell/layout
- Navigation
- Mobile navigation
- Footer
- Section heading
- Button
- Icon button
- Badge/tag
- Alert/toast
- Modal/drawer
- Breadcrumbs
- Pagination

### Content

- Cartoon card
- Featured content block
- Collection card
- Category card
- Episode item
- Continue-watching item
- Favorite action
- Share action
- Search result
- Filter control

### Commerce

- Wear product card
- Product gallery
- Price
- Variant selector
- Quantity control
- Cart item
- Cart summary
- Checkout field group
- Order summary
- Confirmation state

### Account

- Profile summary
- Account navigation
- Favorite collection
- Authentication form
- OTP input
- Validation/error message

Components must be reusable because they represent **design decisions**, not merely because extraction is technically possible.

---

# 9. HOMEPAGE UX

The homepage is the most important public presentation screen.

It should answer quickly:

- What is Kipanya?
- What is worth seeing now?
- What can I do next?

The homepage should prioritize actual product value over generic marketing.

Possible hierarchy:

1. Strong brand/product introduction
2. Featured or current content
3. Continue/discover pathways where relevant
4. Categories/collections
5. Wear connection
6. Supporting brand/story content only where useful
7. Footer/navigation closure

Do not force every feature onto the homepage.

---

# 10. DISCOVERY UX

Discovery should feel alive without becoming noisy.

Users should be able to:

- Browse
- Search
- Filter where useful
- Understand categories
- Recognize new/featured/trending content
- Open content quickly

Search should support recovery:

`Search → Results → No results → Helpful alternatives`

Avoid overwhelming users with too many filters before they need them.

---

# 11. CARTOON DETAIL UX

A cartoon detail page should answer:

- What is this?
- Why should I watch it?
- How do I start?
- What episodes are available?
- Can I save it?
- What else is related?

Primary action should be obvious.

Secondary actions should include relevant behavior such as favorite/share.

Episode lists must be scannable and should not bury the primary watch action.

---

# 12. WATCH EXPERIENCE

The watch experience is a focused environment.

Priorities:

1. Video/content
2. Episode context
3. Playback/navigation
4. Continue/next action
5. Supporting metadata
6. Related discovery

Do not clutter the viewing experience with unnecessary UI.

After an episode, users should have a clear next step where appropriate:

- Next episode
- Back to series
- Related content
- Favorite/save

---

# 13. FAVORITES & ACCOUNT

Account pages should feel like a personal space, not an admin dashboard.

Priorities:

- Identity
- Personal content
- Favorites
- Profile/settings
- Relevant activity

Avoid copying the admin visual language into the user account area.

---

# 14. AUTHENTICATION UX

OTP-based login/register should feel simple and trustworthy.

The interface must clearly communicate:

- What information is requested
- Why it is needed
- Where the OTP is being sent
- Whether the code was accepted
- Whether the code is invalid/expired
- How to retry
- How to recover

Do not make authentication visually intimidating.

---

# 15. KIPANYA WEAR UX

Wear should feel like a natural extension of Kipanya.

It must still feel commercially credible.

### Product discovery

Users should quickly understand:

- Product
- Price
- Visual
- Availability/variant information
- Main action

### Product detail

Prioritize:

- Product imagery
- Product name
- Price
- Variants
- Quantity
- Primary purchase action
- Important details

### T-shirt/design experience

The design/customization interface should prioritize the actual creative task.

Controls must be discoverable and understandable without visual clutter.

---

# 16. CART & CHECKOUT UX

Checkout should be one of the least decorative pages.

Optimize for completion.

Users should always understand:

- What they are buying
- Quantity
- Price
- Total
- Required customer information
- What happens next

Prevent avoidable errors before submission.

After success, provide a clear confirmation state and useful next actions.

---

# 17. STATES

No major screen is complete until important states are considered.

### Loading

Use skeletons/placeholders when they improve perceived continuity.

### Empty

Explain what is empty and what the user can do next.

### Error

State:

- What happened
- What the user can do
- Retry/recovery action

### Success

Confirm the action and show the next useful step.

### Offline/slow network

Where relevant, avoid making the interface appear frozen.

---

# 18. MOTION

Motion must have purpose.

Use motion for:

- State change
- Spatial continuity
- Feedback
- Focus
- Discovery

Avoid:

- Constant looping animation
- Long entrance animations
- Excessive parallax
- Animating every card
- Motion that delays user action

Motion should generally feel:

**quick, subtle, controlled, intentional.**

Respect reduced-motion preferences.

---

# 19. RESPONSIVE & MOBILE UX

Mobile is a first-class product surface.

Do not simply shrink desktop.

Explicitly redesign:

- Navigation
- Search
- Card density
- Filters
- Content hierarchy
- Video controls
- Forms
- Checkout
- Account navigation

Touch targets must be comfortable.

Important actions should remain reachable with one hand where practical.

---

# 20. ACCESSIBILITY

Every public page should aim for:

- Strong contrast
- Visible keyboard focus
- Semantic HTML
- Logical heading structure
- Proper labels
- Accessible form errors
- Meaningful button/link text
- Touch-friendly controls
- Reduced-motion support
- Images with appropriate alternative text

Accessibility is part of design quality, not a final patch.

---

# 21. PERFORMANCE

Visual quality must not create a slow product.

Prefer:

- Existing build pipeline
- Optimized assets
- Correct image sizing
- Lazy loading where appropriate
- Minimal unnecessary JavaScript
- CSS over JS for simple visual effects
- Reusable components

Do not introduce a heavy frontend framework simply for visual effects if Blade + existing assets can deliver the experience.

---

# 22. BLADE IMPLEMENTATION RULES

Final public UI must be implemented as Laravel Blade.

Use:

- `.blade.php`
- Existing Laravel routes
- Existing controller data
- Blade layouts
- Blade components/partials
- Existing Vite/build pipeline

Do not replace working backend functionality with fake static data.

Do not break route names, forms, validation, authentication or commerce behavior merely to achieve a visual result.

The UI may be radically different while the application's functional contract remains intact.

---

# 23. PAGE DESIGN CONTRACT

Before implementing each major public page, define:

### Purpose
Why does this page exist?

### User
Who is using it?

### Goal
What does the user want to accomplish?

### Primary action
What is the single most important action?

### Secondary actions
What else is useful?

### Information hierarchy
What must be seen first, second and third?

### Components
Which existing/reusable components are appropriate?

### States
Loading, empty, error, success, disabled, etc.

### Mobile behavior
What changes on small screens?

### Accessibility
What must be accessible?

### Quality test
Would a real user understand the page without explanation?

---

# 24. DESIGN REVIEW QUESTIONS

Before approving a page, ask:

1. Is the purpose obvious?
2. Is the primary action obvious?
3. Is the hierarchy strong?
4. Is the content easy to scan?
5. Does the page feel like Kipanya?
6. Does it feel related to the rest of the product?
7. Does it avoid generic AI patterns?
8. Is the spacing intentional?
9. Are components consistent without becoming repetitive?
10. Are all important states handled?
11. Does it work on mobile?
12. Does it remain fast?
13. Is it accessible?
14. Did we preserve existing functionality?
15. Did we accidentally touch admin code?

If a page fails several of these, it is not finished.

---

# 25. IMPLEMENTATION ORDER

Do not redesign randomly page by page.

Recommended order:

1. Public design tokens/foundation
2. Public shell/navigation/footer
3. Homepage
4. Discovery/library
5. Cartoon detail
6. Watch experience
7. Collections/categories
8. Favorites/account
9. Authentication
10. Wear storefront
11. Wear product detail
12. T-shirt/design experience
13. Cart
14. Checkout
15. Order confirmation
16. Global states/errors
17. Responsive refinement
18. Accessibility pass
19. Performance pass
20. Presentation polish

This order follows the user's journey and establishes reusable patterns early.

---

# 26. TOMORROW'S PRESENTATION PRIORITY

Because the product must be presentation-ready, prioritize the path most likely to be demonstrated:

`Home → Discover → Cartoon → Watch → Account/Favorite → Wear → Product → Cart/Checkout`

The goal is not to have every obscure page perfect before the core experience looks exceptional.

The presentation path must be:

- Visually polished
- Fully functional
- Consistent
- Fast
- Responsive
- Easy to explain

---

# 27. FINAL QUALITY BAR

The finished Kipanya public experience should feel:

> **Designed, not decorated.**
>
> **Product-led, not template-led.**
>
> **Distinctive, not derivative.**
>
> **Usable, not merely impressive.**
>
> **Human, not AI-generated.**

This document is the design and UX source of truth for the redesign.

If a later design decision conflicts with this guide, stop and resolve the conflict before implementation rather than silently changing direction.

---

## Protected implementation boundary

**PUBLIC:** may be redesigned substantially.

**ADMIN:** must remain untouched.

**FINAL OUTPUT:** working Laravel Blade implementation.
