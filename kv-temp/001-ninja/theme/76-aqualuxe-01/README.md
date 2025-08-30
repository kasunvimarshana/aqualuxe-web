# AquaLuxe Theme

Modular, WooCommerce-ready theme with dual-state architecture. Built with Tailwind and Laravel Mix.

## Quick start

- Node.js 18+
- From theme folder:

```
npm install
npm run build
```

Activate the theme in WordPress admin. No external CDNs. Assets compiled into `assets/dist` and cache-busted via `mix-manifest.json`.

## Development

- `npm run dev` for a dev build with sourcemaps
- `npm run watch` to watch files

## Structure

- assets/src: JS and SCSS sources
- assets/dist: Compiled assets
- inc/: PHP includes and classes
- modules/: Optional feature modules
- templates/: Theme parts and page templates
- woocommerce/: Woo templates overrides

## Customizer

Colors, typography, layout, and dark mode default.

## Dark mode

Toggle in header; persists via REST and cookie.

## WooCommerce

Theme provides base styles and layout. Degrades gracefully if WooCommerce is not active.

## Demo content

See `demo/` folder. Import via WordPress importer.

## License

GPL-2.0-or-later