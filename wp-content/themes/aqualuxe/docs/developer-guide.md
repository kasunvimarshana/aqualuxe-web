# Developer Guide

This guide provides an overview of the AquaLuxe Pro theme's architecture and development practices.

## Theme Structure

The theme follows the standard WordPress theme structure, with some key additions for modularity and maintainability.

*   `/inc`: Contains the core PHP files, including classes for theme setup, assets, and customizer.
*   `/inc/core`: Core theme classes.
*   `/inc/modules`: Self-contained feature modules, such as dark mode, demo importer, and multivendor support.
*   `/assets`: Contains all frontend assets.
*   `/assets/src`: Raw, uncompiled assets (SCSS, JS).
*   `/assets/dist`: Compiled and minified assets.
*   `/template-parts`: Reusable template files.
*   `/woocommerce`: WooCommerce template overrides.

## Asset Compilation

Assets are managed using Laravel Mix. To work with assets, you need to have Node.js and npm installed.

*   `npm install`: Install dependencies.
*   `npm run dev`: Compile assets for development.
*   `npm run watch`: Compile assets on file changes.
*   `npm run prod`: Compile and minify assets for production.

## Hooks and Filters

The theme makes extensive use of hooks and filters for extensibility.

### Actions

*   `aqualuxe_before_header`
*   `aqualuxe_after_header`
*   `aqualuxe_before_footer`
*   `aqualuxe_after_footer`

### Filters

*   `aqualuxe_logo_url`
*   `aqualuxe_primary_color`

## Creating a New Module

To create a new module, follow these steps:

1.  Create a new directory in `/inc/modules`.
2.  Create a main PHP file for your module (e.g., `my-module.php`).
3.  Add your module's logic to the file.
4.  Include your module's main file in `/inc/core/class-aqualuxe-theme.php`.
5.  If your module has assets, add them to `/assets/src` and update `webpack.mix.js`.

By following these conventions, you can ensure that your customizations are maintainable and compatible with future theme updates.
