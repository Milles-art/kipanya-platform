# KIPANYA PUBLIC REDESIGN — IMPLEMENTATION SKILL

## Mission

Rebuild the Kipanya public-facing experience into a premium, distinctive, production-quality Laravel Blade product while preserving the existing backend contracts and leaving the entire Admin system untouched.

This skill is an implementation contract. It is not a suggestion list. Apply it before, during, and after every public redesign change.

## 1. NON-NEGOTIABLE BOUNDARY

### Admin is READ-ONLY

Never modify, rename, delete, refactor, restyle, or "clean up" anything whose purpose is administrative, including:

- `resources/views/admin/**`
- `app/Http/Controllers/Web/Admin/**`
- `app/Http/Controllers/Api/V1/Admin/**`
- admin routes
- admin middleware
- admin authentication/authorization
- admin policies/permissions/roles
- admin CRUD/business logic
- admin-specific assets
- admin-specific CSS/JS
- admin database logic

Before editing a shared file, determine whether Admin depends on it. If it does, create/use a public-specific implementation instead of breaking Admin.

**Priority rule:** protecting Admin is more important than any visual improvement.

### Public may be rebuilt

The public Blade presentation layer may be replaced substantially or from scratch. This includes public pages, public layouts, public components, public CSS/JS, and public interaction patterns when necessary.

Do not confuse a visual rebuild with a backend rewrite.

## 2. SOURCE OF TRUTH ORDER

When making decisions, use this order:

1. Existing application functionality and backend data contracts.
2. This skill.
3. Kipanya product/brand understanding and the approved UX master guide.
4. Current project assets and real content.
5. Current web/product UX best practices and references.
6. Existing visual implementation only as evidence of functionality — never as the visual design source of truth.

The old UI is NOT the design reference. Its routes, data, actions, and business rules are the functional reference.

## 3. REQUIRED PRE-CODE AUDIT

Before changing public code:

- inspect the complete public Blade architecture;
- inspect `routes/web.php` and public controllers;
- inspect public models/data passed to views;
- inspect public layouts/components;
- inspect CSS, Tailwind, Vite, and JS architecture;
- inspect real image/assets and how URLs are resolved;
- inspect Cartoon flows;
- inspect Wear storefront/product/cart/checkout flows;
- inspect Account/Auth flows;
- identify shared files used by Admin;
- identify reusable patterns worth preserving;
- identify dead/orphaned public UI and incorrect UX concepts;
- establish the exact backend contract for every page before redesigning it.

Do not invent fields, routes, actions, API endpoints, payment methods, product variants, review systems, or content that the backend does not actually support.

## 4. PRODUCT MODEL

Kipanya is one ecosystem with different public verticals:

### Cartoon

Editorial/content-first experience for picture-based cartoons and caricatures.

Primary structure:

- Today's Cartoon / daily story-style strip
- Featured Cartoon slider
- Latest Cartoons
- Categories
- Collections
- Explore/Search
- Archive/date discovery where supported
- Cartoon Detail
- Favorites
- Related cartoons

Important: cartoons are picture-based. Do NOT design the public Cartoon experience around episodes, seasons, video players, streaming, or "next episode" behavior. Existing episode backend structures may remain untouched, but they must not drive the public Cartoon UX unless the actual product contract changes later.

### Wear

Separate premium fashion/e-commerce experience sharing Kipanya brand DNA but NOT the same UI composition as Cartoon.

Primary structure:

- Storefront
- Catalogue
- Search/filter/sort
- Product detail
- Variant selection supported by real data
- Wishlist where supported
- T-shirt/custom design where supported
- Cart
- Checkout
- Order confirmation/history/status where supported

Cartoon and Wear should feel related, not cloned.

### Account

Modern public account experience for authentication, profile, favorites/wishlist, orders, settings, and other actually-supported account features.

## 5. APPROVED VISUAL DNA

### Overall direction

Hybrid: entertainment personality + premium editorial + modern digital-product UX + sophisticated commerce.

### Color philosophy

Dynamic Contrast. Content/entertainment may use immersive or darker moments; editorial/discovery can use lighter surfaces; commerce can use cleaner premium surfaces. Maintain one Kipanya identity without forcing one color treatment everywhere.

### Typography

Editorial Pairing: a distinctive display/editorial face for major moments plus a highly readable UI/body face.

Typography must have an intentional hierarchy, not random large headings.

### Shape language

Controlled Mixed Geometry. Do not round every element. Use geometry according to function and context.

### Visual content language

Content-first + Editorial Storytelling. Artwork and products are the stars. Avoid repetitive generic card grids when editorial composition can communicate better.

### Motion

Contextual Motion. Motion must communicate, guide, confirm, reveal, or delight. Never add animation merely because a CSS animation is available.

Respect `prefers-reduced-motion`.

### Navigation

Layered Navigation. Keep the global public navigation stable; add contextual controls only when useful. Do not create competing headers or duplicate navigation systems.

### Icons

Use one coherent icon family with consistent stroke/optical sizing. Use icons only when they improve comprehension. No emoji or random Unicode symbols as UI icons.

## 6. ANTI-GENERIC / ANTI-AI RULES

Do NOT default to:

- generic SaaS landing-page templates;
- giant gradient hero text;
- excessive glassmorphism;
- floating blobs everywhere;
- every element inside a rounded card;
- huge headings with little content hierarchy;
- endless 3-column card grids;
- random decorative shapes with no purpose;
- fake testimonials or invented social proof;
- invented statistics;
- fake product reviews;
- generic copy that could belong to any company;
- excessive icon decoration;
- unnecessary carousels;
- animation for decoration only;
- cloned Shopify-style Wear UI;
- cloned streaming/video UI for Cartoon;
- redesigning each page with unrelated aesthetics.

The result must feel authored for Kipanya, not generated from a template.

## 7. PAGE DESIGN METHOD

For every page:

1. Identify the user's primary goal.
2. Identify the real data available.
3. Identify the actions available.
4. Identify states: loading, empty, error, success, unavailable, authenticated/guest.
5. Throw away the old visual composition when appropriate.
6. Create a new information hierarchy.
7. Build the page using the public design system.
8. Connect every interaction to the existing Laravel functionality.
9. Check desktop and mobile independently.
10. Verify accessibility and performance.

Never stop at "it looks nicer."

## 8. CARTOON UX RULES

The Cartoon homepage should strongly prioritize:

1. Today's Cartoon — small rounded Instagram Stories-style daily items at the top when daily data exists.
2. Featured Cartoon — large editorial slider/hero using real featured content.
3. Latest Cartoons — varied, content-first presentation rather than a monotonous grid.
4. Categories.
5. Featured Collections.

Cartoon detail should prioritize:

- artwork;
- title;
- date;
- caption/description when available;
- category;
- collection context;
- favorite/share;
- related cartoons.

A subtle "Make this a T-shirt" bridge may exist where the backend supports it, but Wear must not dominate the Cartoon experience.

## 9. WEAR UX RULES

Wear is commerce-first and should feel premium/editorial.

Storefront/catalogue:

- product imagery first;
- clear product name and price;
- useful category/filter/sort/search controls;
- wishlist/favorite controls where supported;
- strong mobile usability;
- editorial merchandising where real assets support it.

Product detail:

- large product imagery/gallery;
- name and price;
- real variant choices;
- availability;
- size/color controls only when supported by actual variants;
- useful product information;
- shipping/returns information only when supported by actual policy/data;
- clear Add to Cart action;
- mobile-friendly purchase controls.

Do not invent a 3D configurator or variant system that the backend does not have.

Cart/checkout:

- remove friction;
- make totals and actions obvious;
- preserve real validation and server-side behavior;
- show useful feedback for success/error states;
- never fake payment completion.

## 10. ACCOUNT UX RULES

Rebuild the public account visual experience independently from Admin.

Use clear information architecture for:

- login;
- registration;
- profile;
- favorites/wishlist;
- orders;
- settings;
- supported account actions.

Never alter Admin authentication while redesigning public authentication.

## 11. DESIGN SYSTEM

Create/reuse public-specific tokens and components for:

- typography;
- semantic colors;
- spacing;
- containers;
- grids;
- borders;
- radii;
- shadows/elevation;
- buttons;
- links;
- inputs;
- selects;
- search;
- cards;
- content cards;
- product cards;
- badges;
- tabs;
- pagination;
- breadcrumbs;
- modals/drawers;
- alerts/toasts;
- empty states;
- error states;
- loading/skeleton states;
- navigation.

Use shared public components where they genuinely improve consistency, but keep Cartoon and Wear expression distinct where required.

## 12. RESPONSIVE / MOBILE RULE

Mobile is a separately designed composition, not a squeezed desktop.

Explicitly evaluate:

- header/navigation;
- homepage;
- Cartoon stories and featured slider;
- archive/search/filter controls;
- collections;
- Wear catalogue;
- product detail;
- cart;
- checkout;
- account;
- T-shirt designer.

Touch targets must be comfortable. Avoid horizontal overflow. Preserve content hierarchy. Consider sticky purchase controls where they materially improve commerce usability.

## 13. ACCESSIBILITY

Use:

- semantic HTML;
- keyboard navigation;
- visible focus states;
- adequate contrast;
- correct form labels;
- ARIA only where appropriate;
- correct button/link semantics;
- meaningful image alt text;
- accessible dialogs/drawers;
- reduced-motion support.

Do not sacrifice accessibility for visual effects.

## 14. PERFORMANCE

Keep the public experience fast.

Prefer:

- existing Vite/Tailwind tooling;
- minimal JavaScript;
- reusable CSS/components;
- lazy loading where appropriate;
- optimized image presentation;
- efficient DOM structures.

Avoid unnecessary dependencies, duplicated CSS, giant bundles, oversized assets, and animation-heavy effects.

## 15. BACKEND PRESERVATION

The default implementation rule is:

**Change presentation, not business logic.**

Preserve:

- routes;
- controllers;
- models;
- migrations;
- authentication behavior;
- favorites;
- search/filter behavior;
- categories;
- collections;
- Wear products/variants;
- wishlist behavior;
- cart behavior;
- checkout behavior;
- orders;
- T-shirt designer behavior;
- account functionality.

If a public UX requirement cannot be implemented with the current contract, stop and inspect the backend before inventing a workaround. Only modify backend code when genuinely necessary and explicitly within public scope.

## 16. FILE SAFETY PROTOCOL

Before editing/deleting any file:

1. Determine whether it is public, shared, or admin-only.
2. Search references/usages.
3. If shared with Admin, do not make a breaking change.
4. Prefer public-specific components.
5. Keep a clear record of touched files.

Deleting/replacing authorized public Blades is allowed. Deleting backend/admin files is not part of this redesign.

## 17. VALIDATION GATE

After meaningful implementation stages:

- run available Laravel checks;
- run `php artisan route:list` when environment permits;
- run `npm run build`;
- inspect generated/rendered public HTML where possible;
- test all affected routes;
- test desktop and mobile;
- test forms and validation;
- test search/filter;
- test favorites/wishlist;
- test Cartoon detail/collections/categories;
- test Wear product/cart/checkout/order flow;
- test account/auth flows;
- test T-shirt designer;
- check console errors;
- check broken images/links;
- check focus/keyboard behavior;
- check responsive overflow;
- verify Admin files/routes remain untouched.

If a regression appears, fix it before moving forward.

## 18. CHANGE DISCIPLINE

Do not perform unrelated refactors during a redesign step.

However, when a public implementation has an obvious UX defect discovered during the work, fix it if the fix stays within the public scope and does not risk Admin/backend behavior.

Do not ask for approval on every small professional design decision. Make strong decisions from the established rules and continue.

## 19. FINAL QUALITY BAR

The finished public Kipanya product must be:

- unmistakably Kipanya;
- substantially different from the old composition;
- premium without being generic;
- editorial where content demands it;
- commerce-focused where shopping demands it;
- responsive by design;
- accessible;
- performant;
- functional with real backend data;
- coherent across all public pages;
- free of fake functionality/content;
- free of unnecessary visual noise;
- safe for the untouched Admin system.

Final test:

> If the interface could be renamed to another brand without changing anything except the logo, it is not distinctive enough.

> If a visual improvement requires breaking existing backend behavior, redesign the presentation instead.

> If a file might belong to Admin, verify before touching it.

## FINAL SELF-CHECK — MANDATORY BEFORE DECLARING ANYTHING FINISHED

Before declaring a page, component, feature, fix, or redesign task complete, perform a mandatory self-audit against this entire skill and the Kipanya Design & UX Master Guide.

### 1. Skill compliance check
- Re-read the relevant rules in this skill before considering the work finished.
- Verify that the implementation actually follows the visual, UX, architecture, accessibility, performance, responsive, and functionality rules defined here.
- Do not assume compliance because the code looks good.
- If a rule was missed, fix it before declaring the work complete.

### 2. Audit every fix made
Whenever you fix something:
- Inspect the surrounding implementation again after the fix.
- Check whether the fix introduced a regression elsewhere.
- Check related pages/components that depend on the changed code.
- Check desktop and mobile behavior where applicable.
- Check states and interactions affected by the fix.
- Confirm the fix solves the root problem rather than merely hiding the symptom.

### 3. No unfinished implementation
Do not declare something complete if it contains:
- placeholder UI that should be production UI
- fake functionality
- dead buttons or links
- broken states
- missing responsive behavior
- inconsistent components
- console/runtime errors caused by the implementation
- visual regressions discovered during review
- accessibility regressions
- unnecessary duplicated code

### 4. Final quality gate
Before finishing a task, ask internally:
> "Did I actually follow what I wrote in this skill, and did I audit the work after making my fixes?"

If the answer is not clearly YES, continue working.

### 5. Fix → re-audit → re-test loop
The required completion cycle is:

**IMPLEMENT → INSPECT → AUDIT AGAINST SKILL → FIND PROBLEMS → FIX → RE-AUDIT → TEST → ONLY THEN DECLARE COMPLETE.**

Never stop immediately after the first successful-looking implementation or after a single fix.

### 6. Final public/admin boundary check
Before finishing any public task:
- Confirm no admin files were changed.
- Confirm no admin routes, controllers, middleware, authentication, APIs, assets, or business logic were altered.
- If a shared file was touched, confirm its admin behavior remains unaffected.

This final self-audit is mandatory for every implementation cycle, not optional.

## 15. MANDATORY PRE-FINISH SELF-AUDIT

Before declaring any public feature, page, component, fix, or redesign complete, stop and audit the work against this entire skill. Do not treat implementation as finished merely because it renders or passes one test.

For every meaningful change, follow this mandatory loop:

**IMPLEMENT → INSPECT → AUDIT AGAINST THIS SKILL → FIND GAPS/REGRESSIONS → FIX → RE-AUDIT → TEST → ONLY THEN DECLARE COMPLETE.**

The audit must explicitly verify:

- The change follows the visual/UX rules in this skill.
- The change preserves the intended backend contract and existing public functionality.
- The change does not accidentally affect Admin, including shared dependencies.
- Responsive behavior is intentionally correct on desktop and mobile.
- Interactive states, loading/empty/error/success states are handled where relevant.
- Accessibility, keyboard/focus behavior, semantics, labels, contrast, and reduced motion are considered.
- Performance and unnecessary dependencies/DOM/CSS/JavaScript have been checked.
- No placeholder, fake, dead, duplicate, or invented functionality has slipped into the implementation.
- The page/component still belongs to the Kipanya ecosystem without becoming visually generic.

### FIX-THEN-AUDIT RULE

If the audit discovers something that needs fixing, the work is **NOT COMPLETE**. Make the fix, then run the audit again from the beginning. Never assume a fix is safe without re-checking its surrounding effects.

If multiple problems are found, continue the fix → re-audit cycle until the applicable requirements pass.

### FINAL DECLARATION RULE

Only say a task is complete after the implementation and the post-fix audit both pass. If an environment limitation prevents validation, state the limitation clearly instead of claiming completion.
