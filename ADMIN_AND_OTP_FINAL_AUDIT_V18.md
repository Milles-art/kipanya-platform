# Kipanya Admin + OTP Final Audit v18

## Decision
Admin is intentionally a separate operational UX from the public client UX, but now shares the Kipanya visual system: typography, surfaces, glass, accent treatment, icons, responsive behavior, and light/dark theme.

## Admin audit
- Control Center: unified operational shell.
- Cartoon Studio: migrated to the same operational shell with Cartoon-specific navigation.
- Admin login: dedicated admin authentication shell using the same system.
- Studio content views retain their existing content-specific components but inherit the unified shell.
- Episodes remain domain functionality for future Kipanya TV and are not promoted as Cartoon public navigation.

## OTP audit
- Local/testing OTP code is exposed in the session after a successful request.
- Client login and registration keep the development code visible until verification instead of using a one-request flash.
- Admin login now exposes the development code in the same way.
- Production still does not expose OTP codes unless `AUTH_LOG_OTP_CODES=true` is explicitly enabled.
- The current SMS binding remains `LogSmsGateway`; a real SMS provider must be bound for production delivery.

## Recommended production step
Configure a real SMS gateway and bind it to `SmsGateway` in `AppServiceProvider` (or a dedicated integration provider) before production.
