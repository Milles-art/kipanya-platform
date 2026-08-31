# Kipanya Platform — Batch 06

Batch 06 introduces the first production-oriented interface layer.

## Design direction
- Editorial entertainment aesthetic
- Typography-first hierarchy
- Restrained palette with a single accent
- No emoji UI
- No generic Unicode iconography
- Responsive, mobile-first layouts
- Shared visual language between public site and Studio
- Lightweight motion and deliberate states

## Public routes
- `/` — home
- `/discover` — published cartoon library
- `/watch/{slug}` — cartoon detail/player page

## Admin Studio
- `/admin/login` — phone + OTP login
- `/admin` — dashboard
- `/admin/content` — content library
- `/admin/categories` — category overview
- `/admin/collections` — collection overview

The admin web login uses the existing OTP service and the normal Laravel web
session guard. API Sanctum authentication remains unchanged.

## Apply
No migration is required for Batch 06.

Run:
    php artisan config:clear
    php artisan test
    npm run build

The UI is a foundation. CRUD forms/actions will be expanded in the next UI
batch after the visual system is validated.
