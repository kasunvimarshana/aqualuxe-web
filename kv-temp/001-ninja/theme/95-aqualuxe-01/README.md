# AquaLuxe Theme

A modular, multitenant, multivendor, classified, multi-theme, multilingual, multicurrency, mobile-first, fully responsive, dynamic, reusable, extendable, customizable, multi-state architecture, and maintainable WordPress theme.

## Description

This theme is built with a modular architecture to facilitate easy addition or removal of features without breaking the application. It follows WordPress best practices and modern development workflows.

## Features

- **Modular Architecture**: Core vs. Modules.
- **Modern Frontend Workflow**: Tailwind CSS and Laravel Mix for asset bundling.
- **WooCommerce Ready**: Dual-state architecture for seamless integration.
- **Customizer Options**: Easy customization of logo, colors, etc.
- **Responsive Design**: Mobile-first approach.
- **SEO Optimized**: Best practices for search engine visibility.
- **Accessible**: WCAG 2.1 AA compliance in mind.

## Development

### Prerequisites

- Node.js & npm
- Composer

### Setup

1. Clone the repository.
2. Navigate to the theme directory: `cd wp-content/themes/aqualuxe`
3. Install PHP dependencies: `composer install`
4. Install NPM dependencies: `npm install`
5. Run the development server: `npm run dev` or `npm run watch`
6. For production builds: `npm run prod`

## File Structure

```
/aqualuxe
|-- /assets           # Compiled assets (dist) and source files (src)
|-- /docs             # Documentation
|-- /inc              # Theme backend functionality
|   |-- /core         # Core framework classes
|   |-- /customizer   # Theme customizer options
|   |-- /woocommerce  # WooCommerce specific functions
|   |-- setup.php     # Theme setup
|   |-- enqueue.php   # Asset enqueueing
|   `-- ...
|-- /languages        # Translation files
|-- /modules          # Feature modules (e.g., dark-mode, subscriptions)
|-- /templates        # Template parts (partials, layouts)
|-- /vendor           # Composer dependencies
|-- /woocommerce      # WooCommerce template overrides
|-- functions.php     # Main theme file
|-- style.css         # Theme information header
|-- index.php         # Main template file
|-- package.json      # NPM dependencies
|-- webpack.mix.js    # Webpack configuration
`-- README.md         # This file
```

## License

AquaLuxe is licensed under the GNU General Public License v2 or later.
