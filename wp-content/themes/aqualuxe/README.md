# Aqualuxe Theme

Modular, SOLID-inspired WordPress theme scaffold: multi-*, accessible, SEO friendly, secure, and extendable.

## Highlights
- SOLID architecture with service providers and clear separation of concerns
- Progressive enhancement: REST + admin-ajax fallback
- Example CPT: Listing + taxonomy
- Performance: deferred JS, filemtime cache busting, autoprefixer
- Security: nonces, disabled xmlrpc, header hardening
- Accessibility: skip links, focus styles, semantic roles
- SEO: description meta + JSON-LD website schema
- Tooling: Webpack + PostCSS, PHPCS config, Composer autoload

## Structure
- `src/` core, providers, modules, http (REST/AJAX)
- `assets/src/` sources compiled to `assets/dist/`
- `templates/` contains form and parts

## Setup
1. Install Node deps and build assets:
   - Windows CMD
     - npm install
     - npm run build
2. (Optional) Install Composer autoload for IDE support:
   - composer dump-autoload
3. Activate the theme in WordPress admin.

## Extending
- Add new modules as providers and register them in `functions.php`.
- Use hooks/filters for customization; avoid editing core theme files.

## Testing
- Add pure-PHP unit tests under `tests/` (avoid WP runtime) and run via PHPUnit.
