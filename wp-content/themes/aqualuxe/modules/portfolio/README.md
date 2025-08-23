# AquaLuxe Portfolio Module

This module adds a custom post type for portfolio/gallery items, with categories, tags, image galleries, and a lightbox. It is fully modular and can be enabled/disabled independently.

## Features
- Custom post type: Portfolio
- Categories & tags
- Portfolio grid & single templates
- Image gallery with lightbox
- Masonry-ready grid
- Modular assets (JS/CSS)

## Usage
- Place images in the featured image or gallery fields.
- Use the Portfolio post type in the admin to add/edit items.
- Visit `/portfolio/` for the archive grid, or single items for detail view.

## Developer Notes
- All assets are in `assets/` and should be compiled to `assets/dist/` by the main theme build process.
- Templates are in `templates/` and can be overridden in the main theme if needed.
- The module is loaded via `inc/init.php`.
