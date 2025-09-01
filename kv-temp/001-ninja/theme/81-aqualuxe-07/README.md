# AquaLuxe Theme

Modular, dual-state (with/without WooCommerce) WordPress theme with Tailwind, Three.js hero, and demo importer.

## Key Features
- Core vs Modules architecture (toggle in `modules/modules.json`)
- Assets built via `webpack.mix.js` to `assets/dist` with cache-busting
- Tailwind CSS (no CDN), GSAP, Three.js, D3 bundled locally
- Customizer: color, font family, container width, dark mode default
- CPTs: Services, Events with taxonomies and meta
- WooCommerce graceful support (styles handled by theme)
- Demo Importer: pages, services, events, basic products
- Optional modules: currency switcher, vendors taxonomy, bookings (requests), memberships, auctions listing, franchise inquiries, affiliate referrals, wishlist, quick-view, filtering

## Install
1. Activate the theme in WP Admin.
2. In theme folder, run Node 20+: `npm ci` then `npm run build`.
3. Visit AquaLuxe > Importer to seed demo content.
4. Toggle modules in `modules/modules.json` (true/false). No fatal errors if WooCommerce is inactive.

### Importer (advanced)
- Select entities (Pages, Posts, Services, Events, Products, Media, Menus, Roles/Users).
- Set volumes and options: Random Seed, Locale (placeholder), Conflict Policy (Skip/Overwrite), Variations.
- Real-time progress and logging; logs saved under uploads/aqualuxe-importer/logs.
- Export demo-tagged content to JSON (uploads/aqualuxe-importer/exports).
- Selective Flush deletes only demo-tagged items by default, plus menus/roles/settings when chosen.
- Optional scheduling will periodically re-initialize the demo (hourly/daily/twicedaily).

Notes:
- Works with or without WooCommerce (products steps are skipped if Woo is disabled).
- Import is idempotent: re-runs won’t duplicate pages/products/media; variations are deduped by attributes.
- Media is license-safe and includes attribution meta (Pixabay) and a source hash for dedupe.

## Development
- `npm run dev` for unminified build, `npm run watch` for HMR-like rebuilds.
 - `npm run test` (after `composer install`) for PHP unit tests.

## Modules
Enable/disable in `modules/modules.json`. Each contains a `bootstrap.php` that registers hooks.

Included modules:
- dark-mode, multilingual (basic), wishlist (REST+local), quick-view (REST), filtering (shop), events (archive override)
- currency (cookie-based currency override), vendors taxonomy, bookings (request form+CPT), subscriptions (membership CPT), auctions (CPT), franchise (inquiry form+CPT), affiliate (ref param cookie)

## Security & Performance
- Escaping and sanitization on inputs
- No remote CDNs
- Cache-busted assets from `mix-manifest.json`

## Accessibility
- Quick View modal returns focus to trigger and supports Escape to close.
- Wishlist buttons expose pressed state via `aria-pressed` and initialize on load.
- Hero honors `prefers-reduced-motion` and pauses when the tab is not visible.

## Editor
- Includes `theme.json` for editor settings, align-wide support, and editor styles.
- Block Patterns (under `patterns/`): Immersive Hero, Product Grid, CTA Strip.
