# AquaLuxe WordPress Theme

A modern, modular, and production-ready WordPress theme built for WooCommerce and the block editor.

## Features

*   **Modular Architecture:** Core functionality is separated from feature modules, making the theme extensible and maintainable.
*   **Asset Pipeline:** Uses Laravel Mix (Webpack) for compiling assets. All raw assets are in `assets/src` and compiled to `assets/dist`.
*   **Tailwind CSS:** A utility-first CSS framework for rapid UI development.
*   **Block Editor Support:** Full support for the Gutenberg block editor.
*   **WooCommerce Integration:** Deep integration with WooCommerce, including custom templates and styles.
*   **Demo Importer:** A comprehensive tool to import demo content, widgets, and customizer settings.
*   **Mega Menu:** A powerful and flexible mega menu system.
*   **Slide-in Panel:** An off-canvas panel for additional navigation or content.
*   **Advanced Search:** An AJAX-powered search modal for real-time results.
*   **Social Media Integration:** A customizer-based system for managing and displaying social media links.
*   **Dark Mode:** A toggleable dark mode for a better user experience.

## Installation

1.  Clone the repository into your `wp-content/themes` directory.
2.  Run `npm install` to install the dependencies.
3.  Run `npm run dev` to compile the assets.
4.  Activate the theme in your WordPress admin panel.

## Development

*   `npm run dev`: Compile assets for development.
*   `npm run watch`: Watch for changes and recompile assets automatically.
*   `npm run prod`: Compile assets for production (minified).

## Modules

The theme is built with a modular approach. Each feature is a self-contained module located in the `modules` directory.

*   **`core`:** The core theme setup and initialization.
*   **`advanced-search`:** AJAX-powered search modal.
*   **`custom-post-types`:** Example custom post type registration.
*   **`dark-mode`:** Dark mode toggle and functionality.
*   **`demo-importer`:** One-click demo content import.
*   **`mega-menu`:** Mega menu functionality.
*   **`slide-in-panel`:** Off-canvas slide-in panel.
*   **`social-media`:** Social media link management and display.
