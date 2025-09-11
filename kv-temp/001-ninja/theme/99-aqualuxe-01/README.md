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

## Production Environment Checklist

Before deploying the AquaLuxe theme to a production environment, it is crucial to ensure your server and WordPress configuration are secure and optimized. Please review the following checklist, which addresses items noted in the WooCommerce System Status Report.

### 1. WordPress Configuration (`wp-config.php`)

- **Disable Debug Mode**: Ensure that `WP_DEBUG` and `WP_DEBUG_DISPLAY` are set to `false` in your `wp-config.php` file. Displaying errors on a live site is a security risk.

  ```php
  // in wp-config.php
  define( 'WP_DEBUG', false );
  define( 'WP_DEBUG_DISPLAY', false );
  ```

### 2. Server Configuration

- **Enable HTTPS/SSL**: Your store is not using HTTPS. An SSL certificate is essential for security, customer trust, and SEO. Contact your hosting provider to have an SSL certificate installed and configured for your domain. Once installed, update your WordPress Address (URL) and Site Address (URL) in `Settings > General` to use `https://`.

- **Install SoapClient**: The `SoapClient` class is not available on your server. Some WooCommerce extensions and payment gateways require this. Please contact your hosting provider to have the PHP SOAP extension installed and enabled.

- **Verify Cron Jobs**: The report indicates that the daily cron may not be scheduled. WordPress cron relies on site visits to trigger scheduled tasks. For low-traffic sites, this can be unreliable. Consider setting up a server-level cron job to trigger `wp-cron.php` for improved reliability.

### 3. WooCommerce Setup

- **Create Core Pages**: If the WooCommerce pages (Shop, Cart, etc.) are missing, navigate to the **AquaLuxe Setup** page in your WordPress admin dashboard and use the provided tool to create them.

- **Clear Template Cache**: If the WooCommerce System Status report shows outdated templates after a theme update, navigate to `WooCommerce > Status > System status` and click the **"Clear system status theme info cache"** button.

### 4. Performance

- **Caching**: Install and configure a caching plugin (e.g., W3 Total Cache, WP Super Cache) to significantly improve site speed.

- **Backups**: Ensure you have a reliable, automated backup solution in place before going live.

## License

AquaLuxe is licensed under the GNU General Public License v2 or later.
