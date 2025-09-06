# AquaLuxe Theme

Production-ready, modular, dual-state (Woo/No-Woo) WordPress theme following SOLID/DRY/KISS, WordPress Coding Standards, and analyzer-safe namespaced PHP.

## Features
- Modular architecture (`inc/core` vs `modules`) with toggleable features.
- Dual-state Woo/No-Woo: graceful fallbacks when WooCommerce is absent.
- Tailwind + Mix asset pipeline; hashed dist files via `mix-manifest.json`.
- Accessibility (WCAG 2.1 AA), SEO (OG + schema), and security hardening.
- Multilingual, multicurrency, classifieds, wishlist, quick view, advanced filters, roles, marketplace scaffolding.

## Structure
- `inc/core/*`: helpers, logger, security, setup, assets, SEO, admin, forms, customizer, CPTs, taxonomies, shortcodes, REST, WooCompat.
- `modules/*`: self-contained features (dark_mode, multilingual, importer, wishlist, quick_view, advanced_filters, multicurrency, roles, marketplace, classifieds).
- `assets/src/*`: raw JS/CSS bundled to `assets/dist/*` (never enqueue raw sources).
- `templates/*`, root templates, and `woocommerce/*` overrides.

## Coding Standards
- WordPress Coding Standards + analyzer-safe function_exists/call_user_func in namespaced files.
- SOLID: each module has a single responsibility with clear hooks.
- DRY/KISS/YAGNI: minimal dependencies, small helpers, filtered configuration.

## Install
1) In `wp-content/themes/aqualuxe`:
   - npm install
   - npm run build
2) Activate “AquaLuxe” in WP Admin.

## Develop
- npm run dev (or watch)

## Toggle Modules
- Filter `aqualuxe_enabled_modules` or set option `aqualuxe_enabled_modules_option` to an array of module folder names.

## Importer
- Tools → AquaLuxe Importer: run import or flush demo content. Best-effort logging to PHP error log.

## Security
- Strict sanitization/escaping, nonces, admin capability checks, analyzer-safe guards.

## SEO/A11Y
- Schema via JSON-LD, semantic HTML, ARIA roles, keyboard-friendly UI.

## Build
- Laravel Mix v6, Tailwind v3, hashed assets, no external CDNs.

## License
- GPL-2.0-or-later
