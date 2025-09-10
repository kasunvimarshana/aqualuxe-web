# AquaLuxe Theme

A modular, WooCommerce-ready WordPress theme with a dual-state architecture. Assets are bundled with Laravel Mix + Tailwind. No external CDNs.

## Features

- Modular core vs modules (dark mode, multilingual stub, importer)
- Custom post types: Service, Event, Testimonial
- Theme Customizer: color + typography
- WooCommerce dual-state compatibility
- Progressive enhancement, accessibility-minded

## Structure

- assets/src: JS + SCSS (Tailwind)
- assets/dist: built assets + mix-manifest.json
- inc/: core classes, admin, integrations, helpers
- modules/: independent features
- templates: use standard WordPress hierarchy

## Setup

1. In this theme folder:
   - npm install
   - npm run build (or npm run watch)
2. In WordPress Admin:
   - Activate “AquaLuxe” theme.

## Development

- Never enqueue raw files. PHP reads hashed filenames from mix-manifest.json.
- Add modules under modules/<feature>/Module.php and enable via the `aqualuxe/modules` filter.

## Importer

Tools > AquaLuxe > Importer. Supports a minimal demo import (pages, posts, products if WooCommerce active) and optional reset of demo items.

## License

GPL-2.0-or-later
