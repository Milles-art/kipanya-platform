# Kipanya Blade View Architecture

## Source views
- `resources/views/layouts/` — shared public layout
- `resources/views/components/` — reusable Blade components
- `resources/views/pages/public/` — public-facing pages
- `resources/views/account/` — authenticated user pages
- `resources/views/admin/` — Studio/admin pages

Never edit files under `storage/framework/views/`; those are compiled Blade caches.

After changing Blade templates locally, use `php artisan view:clear` if a stale compiled view is suspected.
