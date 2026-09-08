# Kipanya public pages redesign

This handoff updates the customer-facing Cartoon Archive and Kipanya Wear pages.

## Main files changed

- `resources/views/layouts/cartoon.blade.php`
  - Enables the new public stylesheet, dark editorial archive shell, shared glass navbar, and archive footer.
- `resources/views/layouts/wear.blade.php`
  - Replaces the old dashboard/sidebar shell with the shared responsive Kipanya navbar and storefront footer.
- `resources/views/pages/public/wear.blade.php`
  - Rebuilds the Wear landing page with a real featured product hero, category rail, promo cards, product sections, and trust row.
- `resources/views/pages/public/cartoon.blade.php`
  - Uses the new Kipanya social-inspired archive profile, daily story rail, feed layout, theme sidebar, and collection rail.
- `resources/views/components/cartoon/feed-post.blade.php`
  - Reusable cartoon feed post with creator metadata, artwork, save/share actions, and story link.
- `resources/views/components/cartoon/share-button.blade.php`
  - Supports sharing a specific cartoon URL from feed posts while retaining the existing detail-page behavior.
- `resources/css/public-redesign.css`
  - Isolated visual system for the archive and storefront: responsive layout, profile/feed cards, story rail, hover states, loading-friendly transitions, hero motion, mobile breakpoints, and reduced-motion support.

The existing routes, controllers, forms, product cards, cart actions, wishlist behavior, and cartoon story viewer are preserved. Copy the `resources` changes into the Laravel application and run the normal Vite build for production assets.