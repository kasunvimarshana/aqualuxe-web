# AquaLuxe Theme

Bringing elegance to aquatic life – globally. A modular, WooCommerce-ready, dual-state theme.

## Quick start
- Activate theme in WordPress.
- In theme folder, run `npm install` then `npm run build`.
- Optional: Tools > AquaLuxe Importer to load demo content.

## Structure
- assets/src -> assets/dist via Mix + Tailwind
- inc/ -> core code (setup, enqueue, customizer, admin, shortcodes)
- modules/ -> feature modules (toggle via Theme page)
- templates/ -> page templates and partials
- woocommerce/ -> future overrides

## Security & Performance
- CSP-friendly, no external CDNs by default
- mix-manifest cache-busting
- lazy loading, semantic HTML, ARIA

## Build
- npm run dev / build / watch

## License
GPL-2.0-or-later
