# AquaLuxe Theme

Modular, WooCommerce-ready theme with dual-state architecture (works with/without WooCommerce). Built with Tailwind + Laravel Mix. No external CDNs.

## Structure
- assets/src: raw JS/CSS assets
- assets/dist: built assets (hashed) via mix-manifest.json
- inc: core PHP (hooks, enqueue, customizer, security, demo importer)
	- seo.php: Open Graph + JSON-LD
- modules: opt-in features (services, events, multilingual, wishlist)
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

## Importer
Admin: Appearance > AquaLuxe Importer
- Full reset (flush) option
- Volume and locale options
- Progress bar with stepwise import
 - Export demo JSON

## License
GPL-2.0-or-later
