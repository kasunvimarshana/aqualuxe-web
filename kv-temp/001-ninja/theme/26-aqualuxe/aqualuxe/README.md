# AquaLuxe WordPress Theme

AquaLuxe is a premium WordPress theme designed specifically for high-end ornamental fish farming businesses targeting both local and international markets. The theme combines elegance with functionality to create a luxurious e-commerce experience.

![AquaLuxe Theme](screenshot.png)

## Features

- **Elegant Design**: Clean, modern design with a focus on showcasing aquatic life
- **WooCommerce Integration**: Fully compatible with WooCommerce for seamless e-commerce functionality
- **Responsive Layout**: Mobile-first approach ensuring perfect display on all devices
- **Dark Mode**: Built-in dark mode toggle with persistent user preferences
- **Multilingual Support**: Ready for translation with language switcher functionality
- **Custom Headers**: Multiple header layout options (default, centered, transparent, minimal)
- **Advanced Product Features**: Quick view, wishlist, product comparison, and more
- **Performance Optimized**: Lazy loading, asset minification, and optimized code
- **Accessibility Compliant**: Built with accessibility in mind following WCAG guidelines
- **SEO Friendly**: Schema.org markup and optimized structure for better search engine visibility

## Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- WooCommerce 6.0 or higher (for e-commerce functionality)

## Installation

1. Upload the `aqualuxe` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Configure theme settings via Appearance > Customize

## Development

AquaLuxe is built with modern development tools and follows best practices:

- **Tailwind CSS**: For utility-first styling
- **Laravel Mix**: For asset compilation
- **ES6+ JavaScript**: Modern JavaScript with modular architecture
- **WordPress Coding Standards**: Following WordPress.org and WooCommerce coding standards
- **SOLID, DRY, and KISS principles**: Clean, maintainable code architecture

### Development Requirements

- Node.js 14.x or higher
- npm 7.x or higher

### Build Commands

```bash
# Install dependencies
npm install

# Development build
npm run dev

# Production build
npm run prod

# Watch for changes
npm run watch
```

## Theme Structure

```
aqualuxe/
├── assets/               # Compiled assets
│   ├── css/             # Compiled CSS files
│   ├── js/              # Compiled JavaScript files
│   └── images/          # Theme images
├── inc/                 # Theme functionality
│   ├── admin/           # Admin-specific functionality
│   ├── customizer/      # Theme customizer options
│   ├── helpers/         # Helper functions
│   └── widgets/         # Custom widgets
├── languages/           # Translation files
├── template-parts/      # Reusable template parts
│   ├── content/         # Content templates
│   └── header/          # Header templates
├── woocommerce/         # WooCommerce template overrides
├── functions.php        # Theme functions
├── style.css            # Theme information
└── [other template files]
```

## Customization

AquaLuxe comes with extensive customization options available through the WordPress Customizer:

1. Go to Appearance > Customize
2. Explore the various sections to customize:
   - Site Identity
   - Colors
   - Typography
   - Header Options
   - Footer Options
   - WooCommerce Settings
   - And more...

## Support

For theme support, please contact us at support@aqualuxe.com or visit our [support portal](https://aqualuxe.com/support).

## License

AquaLuxe WordPress Theme, Copyright (C) 2025 NinjaTech AI
AquaLuxe is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.