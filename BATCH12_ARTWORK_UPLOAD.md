# Batch 12 — Cartoon Artwork Upload

Implemented real cartoon artwork uploads in the Admin Studio.

## What changed
- Added `cartoons.thumbnail_path` for first-party stored artwork.
- Added `CartoonMediaService` using Laravel's `public` filesystem disk.
- Added secure image validation: JPEG/JPG, PNG, WebP, max 8 MB.
- Added generated UUID filenames under `cartoons/{id}/thumbnail/`.
- Added artwork replacement and removal.
- Added live browser preview before submitting.
- Kept `thumbnail_url` as a legacy external-image fallback.
- Public/admin artwork rendering now prefers uploaded artwork automatically.
- Added feature tests for upload, replacement, and removal.
- Added the admin thumbnail-removal route.

## Local setup
Run once after pulling the batch:

```bash
php artisan migrate
php artisan storage:link
```

Then open **Studio → Content → Create cartoon** or edit an existing cartoon and use **Upload artwork**.

The actual image files are intentionally not included in the code ZIP. They are uploaded by the admin through the application and stored under Laravel's configured filesystem disk.
