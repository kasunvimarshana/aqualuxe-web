# AquaLuxe Theme

Modular, WooCommerce-ready theme with dual-state architecture (works with/without WooCommerce). Built with Tailwind + Laravel Mix. No external CDNs.

## Structure
- assets/src: raw JS/CSS assets
- assets/dist: built assets (hashed) via mix-manifest.json
- inc: core PHP (hooks, enqueue, customizer, security, demo importer)
	- seo.php: Open Graph + JSON-LD
- modules: opt-in features (services, events, multilingual, wishlist, currency, filters, wholesale, franchise, sustainability, subscriptions, bookings, auctions, affiliates)
- templates: template parts and views
- woocommerce: overrides and partials

## Development
1. Install dependencies
```
npm install
```
2. Build assets
```
npm run build
```
3. Dev/watch
```
npm run watch
```

Note: Assets are versioned via `mix-manifest.json` and enqueued through `inc/enqueue.php`. The theme gracefully handles unusual manifest keys and never falls back to raw src in production.

Helper API:
- `aqualuxe_sanitize_asset_path()` and `aqualuxe_manifest_get()` ensure paths from `mix-manifest.json` are normalized and resolved consistently across the theme (enqueue, diagnostics, and `aqualuxe_mix()`).

## Importer
Admin: Appearance > AquaLuxe Importer
- Full reset (flush) option
- Volume and locale options
- Selective entities (pages, products, posts, services, events)
- Progress bar with stepwise import
- Nightly re-initialization scheduling
- Export demo JSON

## Settings
Appearance > AquaLuxe Settings
- Toggle modules on/off (persisted in options)
- UI: product badges on cards, title-badge style

Customizer
- Colors, typography, layout
- Commerce: free shipping threshold (optional)

Newsletter
- Simple built-in form in the footer posts locally with nonce, honeypot, and minimal rate limiting, storing emails in the `aqlx_newsletters` option for later export.
 - Admin: Appearance > AquaLuxe Newsletter – view list, Export CSV, Clear.
 - Includes a required consent checkbox with privacy policy link; consent status is stored and shown in the admin list.

## Diagnostics
Appearance > AquaLuxe Diagnostics
- Environment snapshot (WP/PHP/Theme/Woo)
- Modules enabled map
- Assets table from `assets/dist/mix-manifest.json` (raw, sanitized, URL)

## License
GPL-2.0-or-later

## CI/CD
- CI (`.github/workflows/ci.yml`): PHP lint + Node build (Node 20, PHP 8.2)
- Theme release (`.github/workflows/theme-release.yml`): Packages theme zip with compiled assets, uploads artifact, and attaches to GitHub Release on tags
