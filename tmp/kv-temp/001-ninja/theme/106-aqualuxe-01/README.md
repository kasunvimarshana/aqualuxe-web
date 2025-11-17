# AquaLuxe Theme (Prototype Scaffold)

This repository contains the initial modular architecture scaffold for the AquaLuxe WordPress theme. It is an incremental foundation (not yet production-complete) implementing:

-   Modular feature discovery via `modules/*/module.php`
-   PSR-inspired autoload (theme local) with `AquaLuxe` namespace
-   Dark mode toggle module
-   Custom Post Types: Services, Events, Auctions (basic)
-   Marketplace (vendor role stub), Subscriptions (stub), Importer (demo pages scaffold)
-   Tailwind + Laravel Mix build pipeline (hashed assets via `mix-manifest.json`)
-   Accessibility-conscious header/footer/templates
-   Customizer basics (accent color + dark mode toggle option)
-   Security helpers & asset resolver

## Directory Structure

```
wp-content/themes/aqualuxe/
  assets/
    src/js/*.js
    src/scss/*.scss
    dist/ (build output)
  inc/
    core/module_manager.php
    helpers/*.php
    customizer/customizer.php
  modules/
    dark_mode/module.php
    services/module.php
    events/module.php
    auctions/module.php
    marketplace/module.php
    subscriptions/module.php
    importer/module.php
  templates/
    header.php
    footer.php
    partials/*.php
  languages/
  tests/
  style.css
  functions.php
  package.json
  webpack.mix.js
```

## Build

Install Node dependencies then build assets:

```
npm install
npm run dev   # or npm run build
```

## Next Steps

This scaffold must be expanded to meet full specification (multilingual, multicurrency, booking, advanced importer with batching, WooCommerce overrides, testing, CI, documentation, etc.).

## License

GPL-2.0-or-later
