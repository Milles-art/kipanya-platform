# Batch 13 — Cartoon UX V2

Implemented the approved Cartoon redesign direction using a controlled glassmorphism/editorial system.

## Admin Studio
- Redesigned overview dashboard.
- Redesigned cartoon library as visual content cards.
- Redesigned create/edit cartoon workflow.
- Added content readiness checklist.
- Added scheduled publishing status and calendar.
- Added default categories migration so the category selector is populated on fresh/update migrations.
- Added category creation UI from Studio.

## Public Cartoon Experience
- Redesigned global navigation.
- Cinematic Today's Cartoon hero.
- Editorial latest-cartoon grid.
- Collections presentation.
- Browse-by-category chips.
- Redesigned discovery/search.
- Redesigned category and collection pages.
- Redesigned cartoon detail/watch page while preserving YouTube episode playback.
- Artwork now uses resolved uploaded artwork throughout the public experience.

## Scheduled publishing
`scheduled` cartoons are automatically moved to `published` when their `published_at` time arrives.

Local development:
`php artisan schedule:work`

Production/shared hosting:
configure the Laravel scheduler to run every minute through the server cron system.

## Important
This batch does not add TV functionality. The work remains focused on the Cartoon Archive and its UX foundation.
