# Developer Guide

This guide provides information for developers who want to customize or extend the AquaLuxe theme.

## File Structure

The theme follows a modular structure:

*   `assets/`: Contains all the front-end assets.
    *   `src/`: Raw, uncompiled assets (Sass, JS).
    *   `dist/`: Compiled and minified assets.
*   `core/`: Core theme functionality (setup, scripts, helpers, CPTs).
*   `docs/`: Theme documentation.
*   `inc/`: Miscellaneous theme files (customizer, etc.).
*   `languages/`: Translation files.
*   `modules/`: Self-contained feature modules (dark mode, subscriptions, etc.).
*   `templates/`: Page templates and partials.
*   `woocommerce/`: WooCommerce template overrides.

## Development Workflow

The theme uses Laravel Mix for asset compilation. You will need Node.js and npm installed on your local machine.

1.  **Install Dependencies:**
    *   Navigate to the theme directory in your terminal: `cd /path/to/wp-content/themes/aqualuxe`
    *   Run `npm install` to install the required packages.

2.  **Development:**
    *   Run `npm run watch` to automatically compile assets as you make changes to the files in `assets/src/`.

3.  **Production Build:**
    *   Run `npm run prod` to create a minified, production-ready build of the assets.

## Creating a New Module

To add a new feature, it is recommended to create a new module in the `modules/` directory.

1.  Create a new directory: `modules/my-new-feature/`
2.  Create the main PHP file: `modules/my-new-feature/my-new-feature.php`
3.  Add your module's logic to this file. It will be automatically loaded by `modules/loader.php`.
4.  If your module has assets, create an `assets/` directory within your module and enqueue them from your main module file.

## Hooks and Filters

The theme uses WordPress hooks and filters extensively to allow for customization. Refer to the inline comments in the code for details on available hooks.

## Coding Standards

The theme follows the WordPress Coding Standards for PHP, HTML, CSS, and JavaScript. Please adhere to these standards when contributing to the theme.
