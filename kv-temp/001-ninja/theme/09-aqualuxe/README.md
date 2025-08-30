# AquaLuxe WordPress Theme

A premium WordPress theme for AquaLuxe - Premium Ornamental Aquatic Solutions. Built for local and international markets with full WooCommerce integration.

![AquaLuxe Theme](screenshot.png)

## Description

AquaLuxe is a premium, fully responsive WordPress theme built specifically for ornamental fish farming businesses targeting local and international markets. The theme features full WooCommerce integration, custom post types for services and events, and a modern, elegant design that reflects the brand identity of *"Bringing elegance to aquatic life – globally."*

## Features

- **Fully Responsive Design**: Looks great on all devices, from mobile phones to desktop computers
- **WooCommerce Integration**: Complete e-commerce functionality for selling products online
- **Custom Post Types**: Services and Events post types for showcasing business offerings
- **Tailwind CSS**: Modern utility-first CSS framework for rapid UI development
- **Dark Mode**: Toggle between light and dark modes with persistent user preference
- **Multilingual Ready**: Fully translatable with .pot files included
- **SEO Optimized**: Built with best practices for search engine optimization
- **Accessibility Compliant**: ARIA attributes and semantic HTML for better accessibility
- **Custom Templates**: Multiple page templates for different content types
- **Theme Customizer**: Extensive options for customizing colors, typography, and layouts
- **Mega Menu Support**: Advanced navigation options for complex site structures
- **Social Media Integration**: Easy setup for social media profiles and sharing
- **Performance Optimized**: Fast loading times with optimized assets
- **Well Documented**: Comprehensive documentation for easy setup and customization

## Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- WooCommerce 6.0 or higher (for e-commerce functionality)

## Installation

1. Upload the `aqualuxe-theme` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Configure theme settings via the WordPress Customizer
4. Install and activate recommended plugins when prompted

## Recommended Plugins

- WooCommerce (for e-commerce functionality)
- Contact Form 7 (for contact forms)
- Yoast SEO (for search engine optimization)
- WP Rocket (for performance optimization)

## Theme Structure

```
aqualuxe-theme/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
├── inc/
│   ├── customizer/
│   ├── widgets/
│   ├── helpers/
│   └── admin/
├── templates/
│   ├── parts/
│   └── blocks/
├── woocommerce/
│   ├── single-product/
│   ├── archive-product/
│   ├── cart/
│   ├── checkout/
│   └── myaccount/
├── languages/
├── functions.php
├── index.php
├── style.css
└── README.md
```

## Customization

### Theme Customizer

The AquaLuxe theme includes extensive customization options through the WordPress Customizer:

1. Go to Appearance > Customize
2. Explore the following sections:
   - Colors
   - Typography
   - Layout
   - Header
   - Footer
   - WooCommerce
   - Social Media

### Custom Templates

The theme includes several custom page templates:

- **Homepage**: A dynamic homepage with multiple sections
- **About**: Template for company information and team members
- **Services**: Template for displaying services in a grid layout
- **Events**: Template for upcoming and past events
- **Contact**: Template with contact form and Google Maps integration
- **FAQ**: Template for frequently asked questions

### Custom Post Types

The theme includes two custom post types:

1. **Services**: For showcasing business services with details like price, duration, and booking options
2. **Events**: For promoting upcoming events with dates, locations, and registration links

## Development

### Building Assets

The theme uses Laravel Mix for asset compilation. To build assets:

1. Install dependencies:
   ```
   npm install
   ```

2. Development build:
   ```
   npm run dev
   ```

3. Production build:
   ```
   npm run prod
   ```

4. Watch for changes:
   ```
   npm run watch
   ```

### Tailwind CSS

The theme uses Tailwind CSS for styling. The configuration file is located at `tailwind.config.js`.

## Support

For theme support, please contact:

- Email: support@aqualuxe.com
- Website: https://aqualuxe.com/support

## License

This theme is licensed under the GNU General Public License v2 or later.

## Credits

- Tailwind CSS: https://tailwindcss.com/
- Alpine.js: https://alpinejs.dev/
- Font Awesome: https://fontawesome.com/
- Laravel Mix: https://laravel-mix.com/

## Changelog

### 1.0.0
- Initial release