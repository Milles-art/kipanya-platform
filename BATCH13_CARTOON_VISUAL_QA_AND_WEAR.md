# Batch 13 — Cartoon Visual QA + T-shirt bridge

## Included
- 19 supplied Cartoon Archive artworks copied into `public/assets/cartoon/demo/`.
- `CartoonDemoSeeder` loads the artwork into the existing Cartoon/Category/Collection data model.
- Cartoon client artwork presentation now preserves natural composition instead of forcing every image into a 16:9 crop.
- Cartoon Studio library/detail/recent artwork previews use the same artwork-first treatment.
- Cartoon watch/detail now uses the Cartoon client layout and includes a real `Make a T-shirt` entry point.
- New T-shirt designer at `/wear/from-cartoon/{cartoon:slug}` with live preview controls for color, size, fit and placement.
- T-shirt selections are persisted in session as a bridge until the full Kipanya Wear product/order system is implemented.

## Load the supplied artwork locally
```bash
php artisan db:seed
```

The seeder is idempotent for the demo records and uses the supplied local assets.

## Next Wear phase
The current designer is intentionally a bridge, not a fake checkout. The next Wear implementation should replace the session design with real product/variant/cart/order models and connect the saved design to a Wear product.
