# AquaLuxe WordPress Theme

![AquaLuxe Theme](screenshot.png)

AquaLuxe is a modern, accessible WordPress theme designed for aquarium-related businesses, marine life enthusiasts, and underwater photography portfolios.

## Features

- **Responsive Design**: Optimized for all devices from mobile to desktop
- **Accessibility**: WCAG 2.1 AA compliant with keyboard navigation and screen reader support
- **Performance**: Optimized for Core Web Vitals with fast loading times
- **WooCommerce Integration**: Custom styling and enhanced functionality for e-commerce
- **Dark Mode**: Built-in dark mode with automatic detection of system preferences
- **Customization**: Extensive customizer options for colors, typography, and layout
- **Block Editor Support**: Custom blocks and styling for the WordPress block editor
- **SEO Friendly**: Optimized markup and schema for better search engine visibility

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Installation

1. Upload the `aqualuxe` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Configure theme settings through the Customizer

## Documentation

Comprehensive documentation is available in the `docs` folder:

- [User Documentation](docs/user-documentation.md): A guide for end users on how to use the theme
- [Developer Documentation](docs/developer-documentation.md): Technical details for developers who want to customize or extend the theme
- [Hooks and Filters Reference](docs/hooks-and-filters.md): A list of all available hooks and filters
- [Testing Plan](docs/testing-plan.md): A comprehensive testing plan for the theme
- [Release Notes](docs/release-notes.md): Details about each version release

## Development

### Setup

1. Clone the repository
2. Run `npm install` to install dependencies
3. Run `composer install` to install PHP dependencies

### Available Commands

- `npm run dev`: Compile assets for development
- `npm run watch`: Compile assets and watch for changes
- `npm run hot`: Compile assets with hot module replacement
- `npm run prod`: Compile and optimize assets for production
- `npm run lint:js`: Lint JavaScript files
- `npm run lint:css`: Lint CSS/SCSS files
- `npm run test`: Run JavaScript tests
- `npm run test:a11y`: Run accessibility tests
- `npm run test:performance`: Run performance tests
- `npm run critical`: Generate critical CSS
- `npm run svg`: Generate SVG sprite
- `npm run imagemin`: Optimize images

### Build Process

The build process is configured in `webpack.mix.js`. It uses Laravel Mix (a wrapper around webpack) to compile and optimize assets.

### Coding Standards

This theme follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) for PHP, JavaScript, and CSS.

## Customization

### Child Theme

The recommended way to customize the theme is by creating a child theme:

1. Create a new folder in the themes directory: `aqualuxe-child`
2. Create a `style.css` file with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe
Version: 1.0.0
*/
```

3. Create a `functions.php` file to enqueue the parent and child theme styles:

```php
<?php
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
```

### Hooks and Filters

The theme provides numerous hooks and filters to modify its behavior without editing core files. See the [Hooks and Filters Reference](docs/hooks-and-filters.md) for details.

## Support

For support, please visit our support forum at https://example.com/support or contact us at support@example.com.

## License

This theme is licensed under the [GNU General Public License v2 or later](LICENSE).

## Credits

- Design and Development: NinjaTech AI Team
- Photography: Unsplash and Pexels (CC0 license)
- Icons: FontAwesome
- Slider: Swiper.js
- Testing: BrowserStack

## Changelog

See the [Release Notes](docs/release-notes.md) for a detailed changelog.