# AquaLuxe Theme Documentation

This document provides instructions on how to install, set up, and develop the AquaLuxe theme.

## Table of Contents

1.  [Installation](#installation)
2.  [Setup](#setup)
3.  [Development](#development)
4.  [Theme Structure](#theme-structure)
5.  [Modules](#modules)
6.  [Custom Post Types](#custom-post-types)
7.  [WooCommerce](#woocommerce)
8.  [Customizer](#customizer)
9.  [Demo Content](#demo-content)

---

## Installation

1.  Download the theme zip file.
2.  In your WordPress admin, go to **Appearance > Themes > Add New**.
3.  Click **Upload Theme** and choose the zip file you downloaded.
4.  Click **Install Now**.
5.  Once installed, click **Activate**.

## Setup

After activating the theme, you will be prompted to install recommended plugins. It is highly recommended to install and activate them to use all the theme features.

You can import the demo content by going to **Appearance > Demo Importer**.

## Development

The theme uses `npm` for dependency management and `Laravel Mix` for asset compilation.

**Prerequisites:**
*   Node.js and npm

**Steps:**

1.  Navigate to the theme directory in your terminal: `cd wp-content/themes/aqualuxe`
2.  Install dependencies: `npm install`
3.  Start the development server: `npm run watch`
    *   This will watch for changes in your `assets/src` files and automatically recompile them.
4.  For production, run: `npm run prod`
    *   This will minify and optimize the assets.

## Theme Structure

The theme is organized into the following directories:

*   `assets/`: Contains all the theme assets.
    *   `src/`: Raw, uncompiled assets (SCSS, JS, images, fonts).
    *   `dist/`: Compiled and minified assets.
*   `inc/`: Contains all the theme's PHP functions.
    *   `core/`: Core theme functionalities.
    *   `cpt/`: Custom Post Type registrations.
    *   `taxonomies/`: Custom Taxonomy registrations.
    *   `customizer/`: Theme Customizer options.
    *   `woocommerce/`: WooCommerce integration functions.
    *   `helpers/`: Helper functions.
    *   `admin/`: Admin-related functionalities.
*   `languages/`: Contains the translation files.
*   `modules/`: Contains the self-contained feature modules.
*   `template-parts/`: Contains the template parts for the theme.
*   `woocommerce/`: Contains the WooCommerce template overrides.
*   `docs/`: Contains the theme documentation.
*   `tests/`: Contains the theme tests.
*   `demo_content/`: Contains the demo content files.

## Modules

The theme uses a modular architecture. Each feature is a self-contained module located in the `modules/` directory. Each module can be enabled/disabled by adding/removing it from the directory.

## Custom Post Types

The theme registers several custom post types. You can find the registration logic in the `inc/cpt/` directory.

## WooCommerce

The theme has full WooCommerce support. Template overrides are located in the `woocommerce/` directory.

## Customizer

The theme uses the Theme Customizer for various options. You can find the Customizer settings in the `inc/customizer/` directory.

## Demo Content

The theme includes a demo content importer. You can find the importer in **Appearance > Demo Importer**. The demo content files are located in the `demo_content/` directory.
