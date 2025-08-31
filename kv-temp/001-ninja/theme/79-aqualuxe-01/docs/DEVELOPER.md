# Developer Guide

- PHP 8+, WP 6.6, WooCommerce (optional).
- Assets: edit `assets/src`, run `npm run build` to output `assets/dist` with cache-busting.
- Modules live in `modules/<feature>/init.php`. Toggle in WP Admin > Appearance > AquaLuxe.
- Customizer options in `inc/customizer.php`.
- Shortcodes in `inc/shortcodes.php`.
- Importer UI under Tools > AquaLuxe Importer; CLI: `wp aqlx import --flush`.
