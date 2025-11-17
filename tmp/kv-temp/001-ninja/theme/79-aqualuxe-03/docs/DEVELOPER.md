# Developer Guide

- PHP 8+, WP 6.6, WooCommerce (optional).
- Assets: edit `assets/src`, run `npm run build` to output `assets/dist` with cache-busting.
- Modules live in `modules/<feature>/init.php`. Toggle in WP Admin > Appearance > AquaLuxe.
- Customizer options in `inc/customizer.php`.
- Shortcodes in `inc/shortcodes.php`.
- Importer UI under Tools > AquaLuxe Importer; CLI: `wp aqlx import --flush`.

## Local dev with hot reload

The theme uses Laravel Mix with optional BrowserSync. Production builds are unaffected.

- One-time: `npm install`
- Build once: `npm run build`
- Watch: `npm run watch`
- Watch with BrowserSync proxy (Windows cmd.exe):

  1) Set your local site URL as an environment variable for the current session:

	  set BROWSERSYNC_PROXY=http://aqualuxe.local

  2) Start watch with proxy:

	  npm run watch

  BrowserSync will proxy your site on http://localhost:3000 and live-reload on asset/PHP changes.

Tip: If you prefer a separate script name, you can use `npm run watch:proxy` after setting `BROWSERSYNC_PROXY`.

## Coding notes

- Dual-state: Always guard WooCommerce calls using `function_exists`, `class_exists`, or `call_user_func` for optional integration.
- Namespaces: Theme code is namespaced; call global WP/WC functions with guards to avoid static analyzers errors.
- Security: Escape output and sanitize input; use nonces for AJAX.
- Performance: Assets are versioned via `mix-manifest.json`; enqueued with the theme `mix()` helper.
