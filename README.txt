KIPANYA — BATCH 03 FINAL REPAIR 2

Root cause confirmed:
Sanctum token deletion works, but Laravel's AuthManager can retain the
RequestGuard instance between requests in a long-lived/test process. That
cached guard can retain the previously authenticated user even after the
database token has been revoked.

Fix:
AuthenticationController::logout() now:
1. Deletes the exact presented bearer token.
2. Calls Auth::forgetGuards() so subsequent requests perform fresh
   authentication.

The logout feature test also asserts that the token is actually absent from
personal_access_tokens before checking that the same token receives 401.

Apply:
Extract into:
C:\Users\Esrom Mussa\kipanya-platform

Then run:
    php artisan config:clear
    php artisan test

No migration is required.
Do not run migrate:fresh.
