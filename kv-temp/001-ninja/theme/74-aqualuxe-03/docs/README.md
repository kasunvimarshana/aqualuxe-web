# AquaLuxe Theme

Luxury aquatic WordPress theme with modular architecture and WooCommerce dual-state support.

## Features
- Modular core vs modules (toggle via `aqualuxe_modules_enabled` filter)
- Works with or without WooCommerce
- TailwindCSS via npm (no CDNs), Mix build, hashed assets, cache-busting
- Multilingual switcher (pluginless, lightweight) and dark mode
- CPTs: Services, Events, Memberships, Auctions, Initiatives
- Wholesale role pricing, Affiliate referral tracking
- Bookings & Contact forms (nonce-protected), Franchise inquiries
- Multi-currency display conversion and header switcher
- Shop filters UI (category, min/max price) via shortcode `[aqualuxe_filters]`
- Accessibility: semantic HTML, ARIA, lazy loading
- SEO: Open Graph, schema basics

## Quick Start
1. Copy the theme folder to `wp-content/themes/aqualuxe`.
2. In a terminal, install deps and build assets:
```cmd
npm ci
npm run build
```
3. Activate the theme in WordPress.
4. Optional: Install WooCommerce. The shop UI appears automatically.

## Shortcodes
- `[aqualuxe_home]` – Featured products section
- `[aqualuxe_services]` – Grid of services CPT
- `[aqualuxe_contact]` – Contact form (+ optional map via Customizer URL)
- `[aqualuxe_booking]` – Booking request form
- `[aqualuxe_filters]` – Shop filters (category + price)

## Modules
Each module lives under `modules/<name>` and self-registers via hooks.
Toggle modules using the filter:
```php
add_filter('aqualuxe_modules_enabled', function($mods){
  $mods['auctions'] = false; // disable auctions
  return $mods;
});
```

## Modules included
- darkmode, multilingual, multicurrency, services, events, bookings, subscriptions, wholesale, auctions, tradeins, affiliates, sustainability, franchise, vendors, contact, filters.

## Demo Content
- Import via Appearance → AquaLuxe Setup or `demo-content/aqualuxe-demo.xml`.

## Assign homepage and menus manually
- Settings → Reading → Your homepage displays: A static page → select Home.
- Appearance → Menus → create "Primary" and assign to Primary location.

## Testing
- JS: `vitest` tests under `tests/js`
- PHP: bootstrap placeholder provided in `tests/php`. Integrate with WP test suite for deeper coverage.

## CI
- GitHub Actions workflow runs lint and build.

## Security
- Nonces and sanitization in AJAX and forms
- Escaping all output via WordPress APIs

## Packaging for marketplaces
- Build assets and include `assets/dist/` in your zip.
- Ensure `style.css`, `functions.php`, templates, and `screenshot.png` are at theme root.
- Validate with Theme Check plugin before submission.

## License
GPL-3.0-or-later

On Windows, scripts use cross-env; run from theme folder:
```cmd
npm ci
npm run build
```
