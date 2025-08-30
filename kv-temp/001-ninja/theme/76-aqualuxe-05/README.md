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
Use Appearance > Setup & Demo to import a complete demo (pages, menus, Woo categories/products, widgets) or to fully reset the site to a clean slate. Demo seed lives under `demo/`.

## License

GPL-2.0-or-later

## Modules

Toggle via Appearance > AquaLuxe Modules:

- wishlist: simple cookie-based wishlist with REST + shortcode
- services: Services + Booking CPTs and a basic booking form
- events: Events + Tickets CPTs (extensible)
- multicurrency: light cookie-based currency switch + Woo hooks
- multilingual: locale filter stub for integration with translators
- referrals: tracks ?ref codes, stores on Woo orders, admin report under Tools
- wholesale: role-based price multipliers (filter `aqlx_wholesale_multipliers`)
- subscriptions: badge on subscription products (requires Woo Subscriptions)
- tradeins: Trade-in request modal, CPT, and email handler
- b2b: hide prices for guests, Request Quote flow, CPT, and notice; Customizer controls
- menu-gating: show/hide menu items with classes/XFN (logged-in, logged-out, b2b)
- saved-filters: save/apply/delete product filter presets per user; shows a "Save filters" button on shop archives and a [aqlx_saved_filters] list in the sidebar

Notes and filters:
- B2B hide prices default can be adjusted via Customizer or `aqlx_b2b_hide_prices` filter.
- B2B roles recognized for `menu-gating` can be extended via `aqlx_b2b_roles`.
- REST endpoints under `/wp-json/aqualuxe/v1` include: `settings`, `dark-mode`, `wishlist`, `quick-view`, `referral`.

## Shop filters (PJAX)

Product archives use IDs `#aqlx-shop-filters` and `#aqlx-shop-grid` with JS to fetch and swap content for filters and pagination. Works with built-in Woo filter widgets and the price form in the sidebar.