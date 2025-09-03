# Aqualuxe Theme

Modular, scalable WordPress theme scaffold with PSR-4 app structure, progressive enhancement, and modern asset pipeline.

## Highlights
- Modular architecture (`app/Modules/*`) with central loader.
- API endpoints (`app/API`) for REST and AJAX.
- Accessibility and SEO hooks.
- Custom post types for classifieds and multivendor use-cases.
- Webpack + Sass + PostCSS with cache-busting manifest.

## Getting Started
1. Install dependencies (inside theme folder):
   - npm install
2. Build assets:
   - npm run build (or `npm run dev` for watch)
3. Activate the theme in WordPress admin.

## Structure
- app/Core: loaders and core services
- app/Modules: feature modules (Assets, SEO, Security, Access, Content, Ajax)
- app/API: REST routes and AJAX controller
- assets/src: JS/SCSS sources
- assets/dist: built files and manifest.json

## Extensibility
- Use `aqlx/modules/enabled` filter to toggle modules.
- Follow SOLID and separation of concerns by creating self-contained modules.

## Testing
- Add PHPUnit/Brain Monkey for PHP unit tests and Playwright for e2e as needed.

## Notes
- Linter warnings in namespaced files for WP globals are expected; WordPress provides them at runtime.