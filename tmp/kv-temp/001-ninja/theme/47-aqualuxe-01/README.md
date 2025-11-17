# AquaLuxe WordPress Theme

![AquaLuxe Theme](screenshot.jpg)

## Overview

AquaLuxe is a premium WordPress theme designed specifically for aquatic-related e-commerce websites. It features a dual-state architecture that works seamlessly with or without WooCommerce enabled. The theme supports multilingual, multi-currency, multivendor, and multitenant functionality.

## Features

- **Responsive Design**: Looks great on all devices from mobile to desktop
- **Dual-State Architecture**: Works with or without WooCommerce
- **WooCommerce Integration**: Full support for WooCommerce with custom templates
- **Dark Mode**: Built-in dark mode toggle
- **Customization Options**: Extensive theme customizer options
- **Multilingual Support**: Compatible with WPML and Polylang
- **Multi-Currency Support**: Sell in multiple currencies
- **Multivendor Support**: Create a marketplace with multiple sellers
- **Multitenant Architecture**: Support for multiple stores on a single installation
- **Performance Optimized**: Fast loading times and optimized code

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (optional but recommended)

## Installation

1. Upload the `aqualuxe-theme` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Follow the setup wizard to configure the theme
4. Install recommended plugins when prompted

For detailed installation instructions, please refer to the [Installation Instructions](docs/installation-instructions.md).

## Documentation

- [User Guide](docs/user-guide.md): Comprehensive guide for theme users
- [Developer Documentation](docs/developer-documentation.md): Technical documentation for developers
- [Installation Instructions](docs/installation-instructions.md): Step-by-step installation guide
- [Theme Customization Options](docs/theme-customization-options.md): Detailed information about customization options
- [Hooks and Filters](docs/hooks-and-filters.md): Reference for all available hooks and filters
- [Changelog](docs/changelog.md): Version history and changes

## Theme Structure

```
aqualuxe-theme/
├── assets/
│   ├── admin/
│   │   ├── css/
│   │   └── js/
│   ├── dist/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── src/
│       ├── css/
│       ├── js/
│       └── images/
├── docs/
├── inc/
│   ├── customizer.php
│   ├── enqueue.php
│   ├── helpers.php
│   ├── hooks.php
│   ├── multilingual.php
│   ├── multitenant.php
│   ├── multivendor.php
│   ├── setup.php
│   ├── template-functions.php
│   ├── template-tags.php
│   ├── widgets.php
│   └── woocommerce.php
├── languages/
├── templates/
│   ├── content-archive.php
│   ├── content-none.php
│   ├── content-page.php
│   ├── content-post.php
│   ├── content-search.php
│   ├── content-single.php
│   └── content.php
├── woocommerce/
│   ├── archive-product.php
│   ├── cart-drawer.php
│   ├── cart/
│   ├── checkout/
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── product-filters.php
│   ├── quick-view.php
│   ├── single-product.php
│   └── wishlist.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── sidebar.php
├── single.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

## Development

### Build Process

AquaLuxe uses Laravel Mix (webpack wrapper) for asset compilation:

1. Install dependencies:
   ```
   npm install
   ```

2. Development build with watch:
   ```
   npm run dev
   ```

3. Production build:
   ```
   npm run prod
   ```

### Customization

The recommended way to customize AquaLuxe is through a child theme:

1. Create a new directory: `aqualuxe-child`
2. Create a `style.css` file:
   ```css
   /*
   Theme Name: AquaLuxe Child
   Theme URI: https://example.com/aqualuxe-child/
   Description: Child theme for AquaLuxe
   Author: Your Name
   Author URI: https://example.com/
   Template: aqualuxe-theme
   Version: 1.0.0
   Text Domain: aqualuxe-child
   */
   ```
3. Create a `functions.php` file:
   ```php
   <?php
   /**
    * AquaLuxe Child Theme functions and definitions
    */
   
   // Enqueue parent and child theme styles
   function aqualuxe_child_enqueue_styles() {
       wp_enqueue_style(
           'aqualuxe-style',
           get_template_directory_uri() . '/style.css',
           array(),
           wp_get_theme('aqualuxe-theme')->get('Version')
       );
       
       wp_enqueue_style(
           'aqualuxe-child-style',
           get_stylesheet_uri(),
           array('aqualuxe-style'),
           wp_get_theme()->get('Version')
       );
   }
   add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
   
   // Add your custom functions below this line
   ```

## Support

- Documentation: [docs.aqualuxe.com](https://docs.aqualuxe.com)
- Support Portal: [support.aqualuxe.com](https://support.aqualuxe.com)
- Email Support: [support@aqualuxe.com](mailto:support@aqualuxe.com)
- Community Forum: [forum.aqualuxe.com](https://forum.aqualuxe.com)

## License

AquaLuxe is licensed under the [GPL v2 or later](LICENSE).

## Credits

- [Tailwind CSS](https://tailwindcss.com/)
- [Laravel Mix](https://laravel-mix.com/)
- [Font Awesome](https://fontawesome.com/)
- [Google Fonts](https://fonts.google.com/)
- [Unsplash](https://unsplash.com/) for demo images