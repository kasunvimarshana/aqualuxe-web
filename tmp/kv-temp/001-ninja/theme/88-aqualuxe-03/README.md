# AquaLuxe Theme

Modular, multitenant-ready, WooCommerce-friendly WordPress theme with SOLID/DRY/KISS architecture.

## Features
- Dual-state: works with or without WooCommerce
- Modular: `modules/*` self-contained features
- Modern pipeline: Tailwind + Laravel Mix, hashed assets via mix-manifest.json
- Accessibility: semantic HTML, ARIA where needed, skip links
- Security: nonces, headers, sanitization patterns

## Structure
- core in `inc/Core/*`
- modules in `modules/*`
- assets: sources in `assets/src`, outputs in `assets/dist`
- templates: root PHP templates and `template-parts` if needed

## Setup
1. From this theme directory:
```cmd
npm install
npm run build
```
2. Activate theme in WordPress admin.

## Development
- `npm run watch` for hot rebuilds

## Lint/Test
- ESLint placeholder: `npm run lint`

## Importer
- Admin menu: AquaLuxe → Demo Importer (AJAX endpoints wired; extend in inc/Core/DemoImporter.php)

## License
GPL-2.0-or-later