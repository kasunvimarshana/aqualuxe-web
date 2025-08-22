# AquaLuxe WordPress Theme

A premium WordPress theme for luxury aquatic businesses. Modular, multitenant, multivendor, multilingual, multicurrency, mobile-first, fully responsive theme with a dual-state architecture that works with or without WooCommerce.

## Features

- **Modular Architecture**: Core framework with toggleable feature modules
- **Dual-State Architecture**: Works seamlessly with or without WooCommerce
- **Mobile-First Design**: Fully responsive across all devices
- **Multilingual Support**: Ready for translation with WPML compatibility
- **Multi-Currency**: Support for multiple currencies with WooCommerce
- **Dark Mode**: Toggle with persistent user preference
- **Customizer Options**: Logo, colors, typography, layout settings
- **WooCommerce Integration**: Full support for all product types
- **Performance Optimized**: Fast loading with optimized assets
- **SEO Friendly**: Semantic HTML5, schema.org markup, Open Graph
- **Accessibility**: ARIA attributes and keyboard navigation
- **Security Hardened**: Proper sanitization, escaping, and nonces

## Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Node.js 16.x or higher (for development)
- npm 8.x or higher (for development)

## Installation

### Manual Installation

1. Download the theme zip file
2. Go to WordPress Admin > Appearance > Themes > Add New
3. Click "Upload Theme" and select the downloaded zip file
4. Activate the theme after installation

### Development Installation

1. Clone the repository to your WordPress themes directory
   ```
   git clone https://github.com/yourusername/aqualuxe-theme.git wp-content/themes/aqualuxe-theme
   ```
2. Navigate to the theme directory
   ```
   cd wp-content/themes/aqualuxe-theme
   ```
3. Install dependencies
   ```
   npm install
   ```
4. Build assets
   ```
   npm run build
   ```
5. Activate the theme in WordPress Admin > Appearance > Themes

## Development

### Available Commands

- `npm run dev`: Build assets for development
- `npm run watch`: Watch for changes and rebuild assets
- `npm run prod`: Build assets for production (minified)
- `npm run lint`: Run ESLint on JavaScript files
- `npm run lint:fix`: Fix ESLint issues automatically

### File Structure

```
aqualuxe-theme/
├── assets/
│   ├── dist/           # Compiled assets (do not edit)
│   └── src/            # Source assets
│       ├── fonts/      # Font files
│       ├── images/     # Image files
│       ├── js/         # JavaScript files
│       └── scss/       # SCSS files
├── core/               # Core theme functionality
│   ├── classes/        # PHP classes
│   ├── functions/      # Core functions
│   └── hooks/          # Action and filter hooks
├── inc/                # Theme includes
│   ├── customizer/     # Customizer settings
│   ├── helpers/        # Helper functions
│   └── widgets/        # Custom widgets
├── languages/          # Translation files
├── modules/            # Feature modules
│   ├── dark-mode/      # Dark mode module
│   ├── multilingual/   # Multilingual support
│   └── ...             # Other modules
├── templates/          # Template files
│   └── parts/          # Template parts
├── woocommerce/        # WooCommerce templates
├── functions.php       # Theme functions
├── index.php           # Main template
├── style.css           # Theme information
├── package.json        # npm dependencies
└── webpack.mix.js      # Laravel Mix configuration
```

### Module System

Each module is self-contained with its own:
- PHP functionality
- JavaScript
- Styles
- Templates
- Configuration

To enable or disable a module, use the Theme Customizer or modify the theme options.

## Customization

### Theme Customizer

Navigate to WordPress Admin > Appearance > Customize to access theme options:

- **Site Identity**: Logo, site title, tagline
- **Colors**: Primary, secondary, accent colors
- **Typography**: Font families, sizes, weights
- **Layout Options**: Content width, sidebar position
- **Header Settings**: Header style, navigation options
- **Footer Settings**: Footer columns, content
- **WooCommerce**: Shop layout, product display options
- **Modules**: Enable/disable feature modules

### Adding Custom CSS

Add custom CSS through the WordPress Customizer:
1. Go to WordPress Admin > Appearance > Customize
2. Select "Additional CSS"
3. Add your custom styles

For more extensive customizations, create a child theme.

## WooCommerce Integration

AquaLuxe provides comprehensive WooCommerce support:

- Custom product templates
- Enhanced shop pages
- Optimized checkout process
- Responsive cart
- Account dashboard improvements

When WooCommerce is not active, the theme provides graceful fallbacks while maintaining design consistency.

## Translation

AquaLuxe is translation-ready:

1. Use Poedit or a similar tool to translate the .pot file in the languages directory
2. Save the translated .po and .mo files in the languages directory
3. Set your site language in WordPress Admin > Settings > General

## Support

For support inquiries, please contact:
- Email: support@aqualuxe.com
- Documentation: https://docs.aqualuxe.com

## License

This theme is licensed under the GNU General Public License v2 or later.
See the LICENSE file for details.

## Credits

- [WordPress](https://wordpress.org/)
- [WooCommerce](https://woocommerce.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Laravel Mix](https://laravel-mix.com/)