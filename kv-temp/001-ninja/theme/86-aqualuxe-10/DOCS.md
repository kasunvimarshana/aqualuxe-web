# AquaLuxe Theme – Developer & User Guide

This theme is production-ready, modular, and dual‑state (works with and without WooCommerce). It follows SOLID/DRY/KISS and WordPress coding standards.

## Quick Start
- Requirements: WordPress >= 6.0, PHP >= 8.0, Node 18 LTS
- Activate the theme in WP Admin.
- Build assets locally (no CDNs):

Windows (cmd):

```
cd wp-content\themes\aqualuxe
npm install
npm run build
```

- For development:
```
npm run watch
```

## Structure
- assets/src: raw sources (JS/CSS). Built to assets/dist with hashed filenames via mix‑manifest.json
- inc/: core framework (autoloaded) – Theme bootstrap, Customizer, CPTs, REST, Assets
- modules/: feature modules (self‑contained; each can be enabled/disabled)
- templates/: partials (e.g., quick view)
- woocommerce/: overrides (kept minimal and accessible)

## Modules
Each module is a folder under `modules/<slug>` with `module.php` and optional `module.json`.
- Toggle modules: WP Admin → Appearance → AquaLuxe Modules
- Registry auto‑discovers modules; unmet requirements are surfaced as admin notices.

Included highlights:
- dark-mode: persisted preference (localStorage) + class‑based Tailwind dark mode.
- multilingual: compatibility with Polylang/WPML + shortcode switcher.
- multicurrency: price formatting and simple live conversion (filterable rates); graceful fallback.
- wishlist: cookie‑based, Woo‑agnostic.
- bookings, classifieds, auctions, subscriptions, affiliates, sitemap, seo, performance, roles, vendors, tenancy, wc-filters.

## Demo Importer
WP Admin → Appearance → AquaLuxe Setup.
- Start, Preview, Pause/Resume, Cancel (rollback), Export JSON, Flush All.
- Stepwise engine with progress, JSONL audit logs under uploads/aqualuxe‑import‑logs.
- Dual‑state: products are skipped when WooCommerce is inactive.

## Accessibility & SEO
- Skip link, ARIA roles, aria‑live updates for modals, focus trapping.
- Schema.org Organization JSON‑LD, Open Graph basics, semantic HTML.

## Performance & Security
- Defer main script; lazy images; WC styles dequeued; small, local JS.
- Security headers sent (see performance module). Nonces and sanitization helpers in `inc/security.php`.

## Customizer
- Colors (primary), Typography (font stack), Layout (container width).

## REST API
- `aqualuxe/v1/status`, `aqualuxe/v1/quickview/<id>` (dual‑state), importer endpoints.

## Theming & Assets
- Tailwind CSS (local via npm), dark mode via class.
- Do not enqueue raw files; everything is versioned via mix‑manifest.

## Dual‑State WooCommerce Integration
- If WooCommerce is present, theme adds gallery features, shop loop quick‑view, and importer seeds sample catalog.
- If absent, theme falls back to posts and generic quick‑view.

## Multitenancy
- Tenancy module provides host→tenant key mapping filters; example option overrides (e.g., blogname) per tenant.

## Testing & CI
- CI workflow builds and PHP‑lints the theme on push/PR to `dev`/`main`.
- For a manual smoke test: visit Home, open Quick View on products (if Woo active), toggle dark mode, run importer preview/start.

## Extending
- Add new feature: create `modules/feature-x/module.php` (+ optional module.json). Hook into `init`, `wp_head`, etc. Keep side effects isolated.
- Add assets: import through `assets/src/js/app.js` and `assets/src/css/app.css`, then `npm run build`.

## License
GPL‑3.0‑or‑later. Use free/copyright‑free media only in demo/importer (placeholder SVGs are generated locally).
