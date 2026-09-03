# Cartoon Archive Finalization Audit — v9

## Scope

Client-facing Blade views were audited and aligned to the current dark/glass Cartoon design system. The approved ecosystem homepage and the separate admin Studio design were left intact.

## Client Blade alignment

All public Cartoon views and client account views now use the current client surface:

- `pages/public/cartoon.blade.php`
- `pages/public/discover.blade.php`
- `pages/public/category.blade.php`
- `pages/public/collection.blade.php`
- `pages/public/collections.blade.php`
- `pages/public/detail.blade.php`
- `pages/public/favorites.blade.php`
- `pages/public/tshirt.blade.php`
- `account/login.blade.php`
- `account/register.blade.php`
- `account/index.blade.php`

The legacy `k-*` public/account presentation was removed from client views. Admin authentication remains on the admin/control surface intentionally.

## Wear bridge

- Blank shirt assets remain the only shirt base layer.
- Cartoon artwork is a separate overlay.
- Shirt color changes the actual shirt asset.
- Duplicate selected-colour caption was removed.
- Shirt preview is reduced in scale so the product does not dominate the stage.
- Front Center and Front Pocket are the only exposed placements until a real back product view exists.
- Artwork can be removed and restored without changing the selected Cartoon.
- Wear state is persisted on every interaction through a debounced state endpoint.
- Guest state is stored in session and localStorage fallback.
- Authenticated state is stored in the `wear_designs` record as well as session.
- State is scoped per Cartoon to prevent one Cartoon's configuration leaking into another.

## Daily Cartoon stories

The story viewer remains a real interaction using published Daily Cartoon records and supports:

- story selection
- progress bars
- timed progression
- previous/next
- pointer swipe
- keyboard navigation
- close
- Cartoon detail link
- Wear link

## Data integrity

Public Cartoon discovery continues to require published status plus available artwork. Homepage public data was also tightened so published records without artwork do not become broken visual cards.

## Verification performed in this environment

- PHP syntax scan: passed across app/routes/database/tests.
- JavaScript syntax check: passed.
- Blade script-tag balance: passed across all Blade views.
- Client views using legacy `layouts.app`: 0.
- Old shirt image reference: 0.
- Old SVG/CSS shirt selectors in views: 0.
- Duplicate selected-colour caption: 0.

Full PHPUnit/Vite build must still be run on the developer machine because this working copy intentionally has no `vendor` or `node_modules`.
