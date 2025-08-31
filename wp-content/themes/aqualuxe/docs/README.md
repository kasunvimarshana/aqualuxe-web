# AquaLuxe Theme

A modular, WooCommerce-ready theme with dual-state operation (with/without WooCommerce), Tailwind CSS, and Laravel Mix.

## Highlights
- Core vs Modules architecture
- Tailwind via npm; no CDNs; hashed assets from `assets/dist` using `mix-manifest.json`
- Multilingual (simple locale switcher), Dark Mode, Demo Importer
- WooCommerce optional with graceful fallbacks

## Structure
- assets/src -> assets/dist
- inc/core, inc/helpers
- modules/* self-contained features
- templates/*, woocommerce/* overrides

## Setup
1. Activate the theme in WP Admin.
2. In a terminal at the theme root:
   - npm install
   - npm run build
3. Optional: Appearance > AquaLuxe Demo Import to scaffold pages.

## Development
- npm run dev for unminified assets
- Edit Tailwind in `assets/src/css/app.css`

## Security & Performance
- Sanitization/escaping throughout
- No raw assets enqueued; hashed filenames for cache busting

## License
GPL-2.0-or-later
