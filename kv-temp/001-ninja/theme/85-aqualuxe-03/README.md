# Aqualuxe Theme

Modular, production-ready WordPress theme with a provider-based architecture, Laravel Mix + Tailwind asset pipeline, cache-busted assets via mix-manifest, and progressive enhancement.

## Highlights
- Service providers for clear modularity: assets, security, REST, SEO, a11y, performance, content types, roles, vendors, tenancy, multilingual, currency, customizer, admin, importer, Woo (conditional)
- Laravel Mix + Tailwind CSS; versioned assets from `mix-manifest.json` (no raw/CDN enqueues)
- Progressive enhancement and accessible, SEO-friendly defaults
- Demo Importer (Tools > Aqualuxe Demo)

## Structure
- `inc/Support` – DI container
- `inc/Providers` – service providers
- `inc/Importer` – demo importer engine
- `assets/src` – JS/SCSS/Tailwind sources → `assets/dist` (built)
- `config/modules.php` – enable/disable providers
- `woocommerce.php` – Woo wrapper template

## Setup
1) Install deps and build assets
```bat
cd /d c:\projects\PPKV\aqualuxe-web\wp-content\themes\aqualuxe
npm ci || npm install --no-audit --no-fund
npm run build
```
2) Activate theme in WordPress
3) Optional: Tools → Aqualuxe Demo → Import Demo

## Development
- `npm run dev` – build in dev mode
- `npm run build` – production build with versioning
- `npm run watch` – watch mode
- `npm run lint` – lint JS
- `npm test` – run JS tests (Jest)

## Modules toggle
Edit `config/modules.php` or filter programmatically:
```php
add_filter('aqualuxe_modules', function(array $modules){
	$modules[Aqualuxe\Providers\SEO_Service_Provider::class] = false; // disable SEO
	return $modules;
});
```

## Notes
- Assets are enqueued only when `assets/dist/mix-manifest.json` exists.
- When WooCommerce is active, the Woo provider registers theme support and the `woocommerce.php` wrapper is used.
- Static analysis may flag WordPress functions as undefined outside WP runtime; this is expected.
