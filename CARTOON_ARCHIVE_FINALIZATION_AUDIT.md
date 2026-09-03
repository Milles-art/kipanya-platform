# Cartoon Archive — Deep Finalization Audit

## Public client
- Archive landing no longer contains the large editorial hero/featured block; content starts with Daily Cartoon.
- Daily stories use real published artwork and an Instagram-style portrait viewer with progress, auto-advance, arrows, keyboard navigation, swipe navigation, close, detail and Wear actions.
- Categories and collections use configured media first, with artwork fallback.
- Search supports title, description, caption, category and Daily filtering.
- Published-only guards remain on public detail/category/collection/daily flows.
- Favorite cards use the authenticated user's loaded favorite IDs instead of one favorite query per card.
- Unused duplicate legacy public/account Blade files were removed.

## Client authentication
- Web registration is phone + OTP and signs the user into the `web` guard.
- Web login is phone + OTP and signs the user into the `web` guard.
- Local development exposes the generated OTP only when `AUTH_LOG_OTP_CODES=true`.
- Production logs no longer include the OTP message when local OTP logging is disabled.
- Logout invalidates the browser session and regenerates the CSRF token.

## Cartoon → Wear bridge
- The selected cartoon is a separate artwork layer; the base shirt asset contains no cartoon artwork.
- Shirt color changes the actual shirt image asset.
- Front Center and Front Pocket are the supported placements in this bridge.
- Artwork can be removed/re-added without changing the selected Cartoon source.
- The user can return to the archive to choose another Cartoon.
- Wear design persistence stores `artwork_enabled` inside the configuration JSON.
- Obsolete shirt tint variables and the old screenshot-derived `kipanya-black-shirt.png` reference are removed from the public client.

## Separation
- Cartoon public pages contain no episode/video/watch UI.
- Episode/YouTube domain code is retained only for the future Kipanya TV workflow and is not linked from the Cartoon Studio navigation.

## Known development-only limitation
- `LogSmsGateway` is intentionally a development transport. A real SMS provider must replace it for production OTP delivery.


## Deep finalization pass v8

- Client web OTP is available to feature tests and local development only; production does not expose codes.
- Public Cartoon routes now require real artwork; published records without artwork are not exposed.
- Admin publishing/featuring requires artwork, and removing artwork from a published Cartoon moves it to Draft.
- T-shirt assets are clean blank-shirt PNGs with transparent backgrounds; artwork is a separate layer.
- T-shirt artwork can be removed/restored without losing the selected Cartoon.
- Shirt color selection swaps real color assets; home rail selections are carried into the full designer.
- Daily story viewer was hardened into an Instagram-like portrait interaction with auto-advance, progress, keyboard navigation, tap navigation, and swipe/pointer navigation.
- Removed the unused legacy V3 Cartoon Archive CSS block.
- The large editorial Cartoon Archive hero/intro is absent from the current public Cartoon Blade.
