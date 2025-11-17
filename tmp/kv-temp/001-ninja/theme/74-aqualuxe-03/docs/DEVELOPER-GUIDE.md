# AquaLuxe Developer Guide

## Architecture
- Core classes in `inc/` keep foundation concerns isolated (assets, theme, modules, customizer, admin, shortcodes, Woo integration).
- Features live in `modules/<feature>/module.php` and hook themselves.
- Templates under `templates/` and WooCommerce overrides under `woocommerce/`.

## Assets Pipeline
- Source in `assets/src`, built to `assets/dist` with hashed filenames via Mix + versionHash.
- TailwindCSS configured in `tailwind.config.cjs` and `postcss.config.cjs`.
- Never enqueue raw assets; use `AquaLuxe\mix('/css/app.css')` and `mix('/js/app.js')` outputs.

## Add a Module
1. Create folder `modules/feature-x/module.php`.
2. Hook into WordPress as needed.
3. Register module path in `inc/class-modules.php` map.

## Hooks
- `aqualuxe/header/actions` allows injecting controls into the header actions area.
- `aqualuxe_modules_enabled` filter toggles module activation.
- `aqualuxe_currency_rates` and `aqualuxe_currencies` to manage multi-currency.

## Security
- Use nonces for forms and AJAX.
- Sanitize input, escape output. Prefer core helpers.

## Testing
- JS tests via Vitest under `tests/js`.
- PHP tests placeholder provided; integrate WP test suite for full coverage.

## Coding Standards
- PHP 8+, WordPress coding standards recommended.
- ESLint and Stylelint configs included.
