# AquaLuxe Theme Developer Guide

## Table of Contents

1.  [Introduction](#introduction)
2.  [Theme Architecture](#theme-architecture)
    - [File Structure](#file-structure)
    - [Modular Design](#modular-design)
3.  [Development Environment](#development-environment)
    - [Prerequisites](#prerequisites)
    - [Installation](#installation)
4.  [Frontend Asset Compilation](#frontend-asset-compilation)
    - [Available Scripts](#available-scripts)
5.  [Coding Standards](#coding-standards)
6.  [Customization and Extension](#customization-and-extension)
    - [Creating a New Module](#creating-a-new-module)
    - [Adding Custom Post Types](#adding-custom-post-types)
    - [Overriding Templates](#overriding-templates)
7.  [Hooks and Filters](#hooks-and-filters)
8.  [Deployment](#deployment)

---

## 1. Introduction

This guide is for developers who want to customize, extend, or contribute to the AquaLuxe theme. It provides an overview of the theme's architecture, development workflow, and best practices.

## 2. Theme Architecture

AquaLuxe is built with a modular and maintainable architecture, following WordPress best practices and modern development standards.

### File Structure

The theme follows the standard WordPress file structure, with some key additions for our development workflow:

```
aqualuxe/
├── assets/
│   ├── src/
│   │   ├── js/
│   │   └── scss/
│   └── dist/
├── docs/
├── inc/
│   ├── core/
│   ├── cpt/
│   ├── taxonomies/
│   └── lib/
├── languages/
├── modules/
│   ├── dark_mode/
│   └── ...
├── template-parts/
├── woocommerce/
├── functions.php
├── style.css
├── package.json
└── webpack.mix.js
```

-   `assets/src`: Contains raw, uncompiled JavaScript (ES6+) and SCSS files.
-   `assets/dist`: Contains compiled and minified assets. This directory is generated automatically.
-   `inc`: Holds the core theme logic, including Custom Post Type registrations, helper functions, and third-party libraries.
-   `modules`: Contains self-contained feature modules that can be enabled, disabled, or modified independently.
-   `webpack.mix.js`: Configuration file for Laravel Mix (a wrapper for Webpack).

### Modular Design

The theme's functionality is broken down into modules located in the `/modules` directory. Each module is self-contained and typically includes:
- A primary PHP file (`module-name.php`) to enqueue assets and handle server-side logic.
- A CSS/SCSS file for styling.
- A JavaScript file for client-side interactivity.

Modules are automatically loaded by the `aqualuxe_autoload_modules()` function in `inc/core/core.php`.

## 3. Development Environment

### Prerequisites

-   Node.js and npm
-   A local WordPress environment (e.g., Local, Docker, XAMPP)

### Installation

1.  Clone the theme repository into your `wp-content/themes/` directory.
2.  Navigate to the theme's root directory in your terminal: `cd path/to/wp-content/themes/aqualuxe`
3.  Install the required npm packages: `npm install`

## 4. Frontend Asset Compilation

We use Laravel Mix to compile and manage our frontend assets.

### Available Scripts

-   `npm run dev`: Compiles assets for development (unminified, with sourcemaps).
-   `npm run watch`: Compiles assets and watches for changes, automatically recompiling when a file is saved.
-   `npm run prod`: Compiles and minifies assets for production.

## 5. Coding Standards

-   **PHP**: Follow the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
-   **JavaScript**: Use modern ES6+ syntax.
-   **CSS**: Follow a BEM-like naming convention for custom classes.

## 6. Customization and Extension

### Creating a New Module

1.  Create a new directory in `/modules`, e.g., `my_new_module`.
2.  Inside this directory, create your main PHP file: `my_new_module.php`.
3.  In this PHP file, enqueue any necessary assets and add your hooks.
4.  The module will be loaded automatically. To add assets to the main compiled files, import them in `assets/src/js/app.js` or `assets/src/scss/app.scss`.

### Adding Custom Post Types

1.  Create a new file in `/inc/cpt/`, e.g., `book.php`.
2.  In this file, define and register your CPT using `register_post_type()`.
3.  The CPT file is loaded automatically via `inc/cpt.php`.

### Overriding Templates

AquaLuxe uses a standard template hierarchy. To override a template file (e.g., `single.php`), it is recommended to use a child theme.

## 7. Hooks and Filters

The theme uses a variety of action and filter hooks for extensibility. Search the codebase for `do_action()` and `apply_filters()` to find available hooks.

## 8. Deployment

1.  Run `npm run prod` to build and minify all assets for production.
2.  When deploying, you only need to upload the theme files. The `node_modules` directory and source asset files (`assets/src`) are not required on the production server.
3.  Ensure your `.gitignore` file is set up to exclude `node_modules` and other development-specific files.
