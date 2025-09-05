# AquaLuxe Theme

Modular, dual-state (with/without WooCommerce) WordPress theme. Mobile-first, accessible, SEO/performance-focused. Assets compiled locally via npm + Laravel Mix (no CDNs). Modules can be toggled safely.

## Requirements
- WordPress >= 6.0, PHP >= 8.0
- Node.js >= 18 LTS (for assets)
- WooCommerce (optional)

## Structure
- assets/src: raw JS/CSS (Tailwind) sources
- assets/dist: built assets with hashed filenames and mix-manifest.json
- inc/: core PHP classes (autoloaded)
- modules/: self-contained features (dark-mode, multilingual, wishlist)
- templates/: theme partials/templates
- woocommerce/: WC template overrides (optional)

## Install & Build
1) Activate the theme in WordPress.
2) Build assets locally:
```
cd wp-content\themes\aqualuxe
npm install
npm run build
```
For development watch:
```
npm run watch
```

## Demo Importer
- WP Admin > Appearance > AquaLuxe Setup
- Optionally tick Reset to delete existing demo data.

### Advanced Importer (Step-wise)
- Choose entities (Pages, CPTs, Users, Roles, Products, WC Config, Media, Widgets, Options).
- Start runs step-by-step with a progress bar and logs; pause/resume/cancel supported.
- Preview shows a light estimate; Export creates a JSON snapshot of demo content.
- Schedule daily/twice-daily/hourly runs and view the next run time; audit logs are saved as JSONL in uploads/aqualuxe-import-logs/.

## Customizer
- Colors, Typography, Layout under Appearance > Customize.

## Modules
Filter to toggle modules in code (e.g. mu-plugin):
```
add_filter('aqualuxe/modules/config', function ($mods) {
  $mods['wishlist'] = false; // disable wishlist
  return $mods;
});
```

Admin UI: Appearance > AquaLuxe Modules toggles modules and validates requirements.

Included modules:
- dark-mode: adds dark theme support and toggle
- multilingual: integrates with Polylang/WPML if present
- multicurrency: price conversion and formatting (optional)
- wishlist: simple cookie-based wishlist + shortcode
- sitemap: basic /sitemap.xml
- seo: meta + JSON-LD organization schema
- roles: wholesale customer role (optional)
- vendors: adapter for multivendor plugins (optional)
- classifieds: listings CPT + grid shortcode (optional)
- tenancy: hostname-based option overrides (optional)
- wc-filters: small WooCommerce UX/perf tweaks

## Security & Performance
- Nonces and sanitization helpers: inc/security.php
- No raw assets enqueued; cache-busted dist only.
- WC styles dequeued; theme styles used.

## Lint/CI
- Basic CI provided in .github/workflows/ci.yml to run php -l and npm build.

## License
GPL-3.0-or-later.

## Changelog

### 1.0.1 – 2025-09-05
- Shortcode ownership: Language and currency switchers are now provided exclusively by their modules.
- Header only renders switchers when the corresponding module/shortcode is active.
- Multilingual module no longer requires Polylang/WPML; includes a locale badge fallback.
