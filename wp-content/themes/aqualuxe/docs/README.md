# AquaLuxe Theme Documentation

Welcome to the AquaLuxe WordPress theme! This guide will help you set up, customize, and extend your site using AquaLuxe's modular architecture.

## Table of Contents
- [Installation](#installation)
- [Theme Options](#theme-options)
- [Modules](#modules)
- [WooCommerce Integration](#woocommerce-integration)
- [Demo Content Import](#demo-content-import)
- [Customization](#customization)
- [Developer Guide](#developer-guide)

---

## Installation
1. Copy the theme folder to `wp-content/themes/aqualuxe`.
2. Activate the theme in the WordPress admin.
3. Run `npm install` and `npm run build` in the theme directory to compile assets.

## Theme Options
- Use the WordPress Customizer (`Appearance > Customize`) to set your logo, primary color, and footer copyright.

## Modules
AquaLuxe features modular architecture. Each feature (e.g., Wholesale, Classifieds, Trade-Ins) is a self-contained module in `modules/`.
- Enable/disable modules via their `module.json` files.
- Each module has its own templates, assets, and service class.

## WooCommerce Integration
- WooCommerce templates are overridden in the `woocommerce/` directory.
- Styles are managed in `assets/src/woocommerce.css` and compiled to `assets/dist/woocommerce.css`.

## Demo Content Import
- Use the "AquaLuxe Demo Import" page in the admin to import demo posts, pages, menus, widgets, and theme options.
- Replace `modules/demo-importer/data/demo-content.xml` with your own WXR export for custom demo data.

## Customization
- All CSS is written in Tailwind and compiled via PostCSS.
- Edit assets in `assets/src/` and run `npm run build` to update.
- Use the Customizer for basic branding and colors.

## Developer Guide
- Modules follow SOLID principles and are registered via the service container in `inc/core/container.php`.
- Extend modules by adding new templates, assets, or service logic.
- For advanced customization, create child themes or custom modules.

---

For more details, see the individual module documentation in the `docs/` folder.
