# Kipanya Cartoon Archive — Approved UI Implementation

This build implements the approved Cartoon Archive experience in Laravel Blade while keeping the ecosystem homepage isolated.

## Public flow
- `/cartoon` — Cartoon Archive Home
- `/cartoon/search` — Search and filter
- `/categories/{slug}` — Category archive
- `/collections` — Public collection index
- `/collections/{slug}` — Collection archive
- `/cartoon/search?daily=1` — Daily Cartoon archive filter
- `/cartoon/{slug}` — Cartoon artwork detail
- `/account/login` — OTP login for existing clients
- `/account/register` — OTP registration for new clients
- `/account/favorites` — Authenticated favorites
- `/wear/from-cartoon/{slug}` — T-shirt designer using the selected Cartoon artwork

## Design rules
- Dark glossy/glass Kipanya visual language
- Teal/turquoise primary accent with restrained orange Wear accent
- Actual Cartoon artwork is always used
- Cartoon is artwork-only; no episode/video UI in the archive
- Homepage remains isolated and unchanged
- Cards are keyboard/click navigable, with favorite controls remaining independent

## Wear bridge
The selected Cartoon artwork is loaded directly into the live T-shirt preview. The preview uses clean local shirt assets for each configured color; the base shirt contains no embedded Cartoon artwork. The bridge currently supports Front Center and Front Pocket placement. Saved designs retain the Cartoon ID and design configuration (color, size, placement, scale, offsets, rotation) so the future commerce/order flow can reproduce the design.

## Client authentication
The browser client now has a complete passwordless flow: request login OTP, verify OTP into a Laravel `web` session, register a new client through registration OTP, and sign out. Login/registration OTP requests are rate-limited and the OTP service remains production-safe by only logging codes when `AUTH_LOG_OTP_CODES=true`.

## Cartoon / TV separation
The public Cartoon Archive exposes artwork, categories, collections, Daily Cartoon, favorites, search, and the Wear bridge only. Public Watch/episode routes and UI have been removed from the Cartoon client. The existing episode domain remains in the codebase for the future Kipanya TV component rather than being mixed into Cartoon.

## Verification
PHP source files and Blade conditional directives were lint-checked in the build environment. Run the full Laravel suite on the development machine:

```powershell
php artisan optimize:clear
php artisan test
php artisan db:seed
npm run build
```
