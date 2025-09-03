# Aqualuxe Theme

A modular, scalable WordPress theme skeleton with service providers, DI container, asset pipeline, and best practices.

## Highlights
- Modular providers (assets, security, REST, SEO, a11y, performance, content types)
- Webpack, SCSS, Babel
- Progressive enhancement; works without JS
- Accessibility-minded, SEO-ready structure
- Extensible via hooks and filters

## Structure
- inc/Support: Container (tiny DI)
- inc/Providers: Service providers modules
- assets/src: JS/SCSS sources -> assets/dist built files
- templates/parts: Template partials

## Getting started
1. Install Node dependencies
2. Build assets
3. Activate the theme in WordPress

## Scripts
- npm run dev
- npm run build

## Notes
- Add more providers for roles/permissions, multitenancy, vendors, multilingual, etc.
- Follow WordPress Coding Standards (WPCS).
