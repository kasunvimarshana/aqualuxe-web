# AquaLuxe WordPress Theme

AquaLuxe is a premium, fully responsive WordPress + WooCommerce theme designed for luxury aquatic retail businesses. The theme combines elegant aquatic visuals with refined typography, smooth micro-interactions, and a mobile-first approach that emphasizes product rarity, quality, and exclusivity.

## Features

- **Fully Responsive Design**: Optimized for all devices and screen sizes
- **WooCommerce Integration**: Complete support for shop, product pages, cart, checkout, account dashboard, and more
- **Dual-State Architecture**: Seamless functionality with or without WooCommerce activation
- **Modern Design Patterns**: Elegant aquatic visuals and refined typography
- **Accessibility Compliance**: WCAG 2.1 AA compliant with keyboard navigation support
- **Performance Optimized**: Fast loading times with optimized assets
- **Dark Mode**: Built-in dark mode toggle with system preference detection
- **Multilingual Support**: Ready for translation with proper internationalization
- **Customization Options**: Extensive theme customizer with live preview
- **Developer Friendly**: Clean, modular code with proper documentation

## Requirements

- WordPress 5.6 or higher
- PHP 7.4 or higher
- WooCommerce 5.0 or higher (optional)
- Node.js 18.0 or higher (for development)
- npm 8.0 or higher (for development)

## Installation

### Standard Installation

1. Download the `aqualuxe.zip` file
2. Go to your WordPress admin panel and navigate to Appearance > Themes
3. Click "Add New" and then "Upload Theme"
4. Choose the `aqualuxe.zip` file and click "Install Now"
5. After installation, click "Activate" to activate the theme

### Manual Installation

1. Extract the `aqualuxe.zip` file
2. Upload the `aqualuxe` folder to your WordPress installation's `/wp-content/themes/` directory
3. Go to your WordPress admin panel and navigate to Appearance > Themes
4. Find "AquaLuxe" and click "Activate"

## Development Setup

### Prerequisites

- Node.js 18.0 or higher
- npm 8.0 or higher

### Setup Instructions

1. Clone the repository to your local machine
2. Navigate to the theme directory in your terminal
3. Install dependencies:

```bash
npm install
```

4. Start the development server:

```bash
npm run dev
```

### Build Process

To build the theme for production:

```bash
npm run build
```

Or use the included build script:

```bash
./build.sh
```

This will:
- Clean previous build files
- Compile and optimize SCSS to CSS
- Compile and optimize JavaScript
- Generate critical CSS
- Optimize images
- Create SVG sprites
- Generate service worker

### Available npm Scripts

- `npm run dev`: Start development mode
- `npm run watch`: Watch for file changes
- `npm run production`: Build for production
- `npm run build`: Full production build with critical CSS
- `npm run clean`: Clean build files
- `npm run lint`: Lint JavaScript files
- `npm run stylelint`: Lint SCSS files
- `npm run critical`: Generate critical CSS
- `npm run imagemin`: Optimize images
- `npm run svg-sprite`: Generate SVG sprite

## Theme Structure

```
aqualuxe/
├── assets/               # Compiled assets (CSS, JS, images, fonts)
│   ├── css/              # Compiled CSS files
│   ├── fonts/            # Font files
│   ├── images/           # Optimized images
│   ├── js/               # Compiled JavaScript files
│   ├── src/              # Source files
│   │   ├── fonts/        # Font source files
│   │   ├── images/       # Image source files
│   │   ├── js/           # JavaScript source files
│   │   └── scss/         # SCSS source files
│   └── vendor/           # Third-party assets
├── inc/                  # Theme PHP includes
│   ├── core/             # Core functionality
│   ├── customizer/       # Customizer settings
│   ├── helpers/          # Helper functions
│   ├── performance/      # Performance optimizations
│   └── woocommerce/      # WooCommerce integration
├── template-parts/       # Template partials
│   ├── content/          # Content templates
│   ├── footer/           # Footer templates
│   └── header/           # Header templates
├── woocommerce/          # WooCommerce template overrides
├── .gitignore            # Git ignore file
├── functions.php         # Theme functions
├── index.php             # Main template file
├── package.json          # npm package configuration
├── style.css             # Theme metadata
└── webpack.mix.js        # Laravel Mix configuration
```

## Customization

### Theme Customizer

AquaLuxe comes with an extensive theme customizer that allows you to change various aspects of the theme without writing any code:

1. Go to Appearance > Customize
2. Explore the available options:
   - General Settings
   - Header Options
   - Footer Options
   - Typography
   - Colors
   - Layout Options
   - Blog Settings
   - WooCommerce Settings
   - Performance Options
   - Advanced Settings

### Child Theme

For advanced customizations, it's recommended to create a child theme:

1. Create a new folder in `/wp-content/themes/` named `aqualuxe-child`
2. Create a `style.css` file with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com
Template: aqualuxe
Version: 1.0.0
*/
```

3. Create a `functions.php` file with the following content:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
```

4. Activate the child theme from Appearance > Themes

## Support

For support requests, please contact us at support@example.com or visit our [support forum](https://example.com/support).

## License

This theme is licensed under the GPL v2 or later. See the LICENSE file for details.

## Credits

- [Tailwind CSS](https://tailwindcss.com/)
- [Laravel Mix](https://laravel-mix.com/)
- [Swiper](https://swiperjs.com/)
- [Tippy.js](https://atomiks.github.io/tippyjs/)
- [Focus Visible](https://github.com/WICG/focus-visible)
- [Lazysizes](https://github.com/aFarkas/lazysizes)
- [Montserrat Font](https://fonts.google.com/specimen/Montserrat)
- [Playfair Display Font](https://fonts.google.com/specimen/Playfair+Display)