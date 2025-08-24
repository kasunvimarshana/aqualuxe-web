
# AquaLuxe Portfolio Module

This module provides a custom post type (CPT) for Portfolio/Gallery items, with:
- Custom taxonomies (Categories, Tags)
- Front-end templates for archive and single items
- Gallery meta box for multiple images per item
- Masonry grid and lightbox support (JS/CSS)
- Admin UI for managing gallery images

## Features
- `aqualuxe_portfolio` CPT
- `portfolio_category` and `portfolio_tag` taxonomies
- Gallery meta box with WordPress media uploader
- Front-end templates: `archive-portfolio.php`, `single-portfolio.php`
- JS/CSS for grid, lightbox, and admin gallery UI

## Usage
- Add/edit Portfolio items in WP Admin
- Use the Gallery meta box to add multiple images
- Front-end displays grid (archive) and gallery/lightbox (single)

## Developer Notes
- All assets are loaded conditionally
- Lint errors in static analysis are expected (WordPress functions)
- Extend as needed for custom layouts or features
