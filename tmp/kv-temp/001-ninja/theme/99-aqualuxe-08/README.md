# AquaLuxe WordPress Theme

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![WordPress](https://img.shields.io/badge/WordPress-6.7%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-8.0%2B-purple.svg)](https://woocommerce.com/)

**Bringing elegance to aquatic life – globally.**

AquaLuxe is a next-generation WordPress theme engineered with a modular, mobile-first, multi-tenant, multivendor, multilingual, multicurrency, and multi-theme architecture. It provides a fully responsive, fully dynamic, extendable, customizable, and SEO-optimized design, built for both performance and scalability.

## 🌟 Key Features

### 🏗️ Architecture
- **Modular Design**: Clean separation between core functionality and feature modules
- **SOLID Principles**: Object-oriented design following SOLID/DRY/KISS principles
- **Multi-Tenant**: Support for multiple vendors and tenants
- **Dual-State**: Works seamlessly with or without WooCommerce

### 🌍 Global Ready
- **Multilingual**: Support for 10+ languages with automatic detection
- **Multicurrency**: 10+ major currencies with real-time conversion
- **RTL Support**: Full right-to-left language support
- **Geolocation**: Automatic language and currency detection

### 🛍️ E-Commerce
- **WooCommerce Integration**: Complete shop functionality
- **Product Types**: Physical, digital, variable, grouped products
- **Advanced Features**: Quick view, wishlist, advanced filtering
- **Payment Ready**: Multiple payment gateway support

### 🎨 Design & UX
- **Mobile-First**: Responsive design for all devices
- **Dark Mode**: Built-in dark/light mode toggle
- **Accessibility**: WCAG 2.1 AA compliant
- **Performance**: Optimized for speed and SEO

### 🔧 Developer Features
- **Asset Pipeline**: Webpack + Laravel Mix for asset compilation
- **Modern Stack**: Tailwind CSS, ES6+ JavaScript
- **Theme Customizer**: Extensive customization options
- **Demo Importer**: One-click demo content installation

## 📋 Requirements

- **PHP**: 8.1 or higher
- **WordPress**: 6.7 or higher
- **WooCommerce**: 8.0 or higher (optional)
- **Node.js**: 16+ (for development)
- **Memory**: 512MB+ recommended

## 🚀 Quick Start

### Installation

1. **Download the theme**
   ```bash
   # Via Git
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   
   # Or download ZIP from GitHub
   ```

2. **Install WordPress theme**
   - Upload the `wp-content/themes/aqualuxe` folder to your WordPress themes directory
   - Activate the theme in WordPress admin

3. **Install dependencies** (for development)
   ```bash
   cd wp-content/themes/aqualuxe
   npm install
   ```

4. **Build assets**
   ```bash
   # Development build
   npm run dev
   
   # Production build
   npm run production
   ```

### Demo Content

1. Go to **Appearance > Demo Importer** in WordPress admin
2. Select a demo variant (Default, Minimal, or Wholesale)
3. Choose import options
4. Click "Import Selected Demo"

## 🏗️ Theme Structure

```
aqualuxe/
├── assets/                     # Compiled assets
│   ├── css/                   # Compiled CSS
│   ├── js/                    # Compiled JavaScript
│   ├── fonts/                 # Web fonts
│   └── images/                # Optimized images
├── assets/src/                # Source assets
│   ├── sass/                  # Sass stylesheets
│   ├── js/                    # JavaScript modules
│   ├── images/                # Raw images
│   └── fonts/                 # Source fonts
├── inc/                       # PHP includes
│   └── core/                  # Core functionality
├── modules/                   # Feature modules
│   ├── dark-mode/            # Dark mode functionality
│   ├── multilingual/         # Language switching
│   ├── multicurrency/        # Currency switching
│   └── demo-importer/        # Demo content importer
├── templates/                 # Template parts
├── woocommerce/              # WooCommerce overrides
├── languages/                # Translation files
├── docs/                     # Documentation
├── functions.php             # Main theme functions
├── style.css                 # Theme stylesheet header
├── package.json              # NPM dependencies
├── webpack.mix.js            # Asset compilation config
└── tailwind.config.js        # Tailwind configuration
```

## ⚙️ Configuration

### Theme Customizer

Access **Appearance > Customize** to configure:

- **Colors**: Primary, secondary, and accent colors
- **Typography**: Font families and sizes
- **Header**: Layout and styling options
- **Footer**: Widget areas and copyright
- **Layout**: Container width and sidebar position
- **WooCommerce**: Products per page and layout

### Modules

Enable/disable features in `functions.php`:

```php
// Default enabled modules
$default_modules = array(
    'dark-mode',
    'multilingual',
    'multicurrency',
    'demo-importer',
    'wishlist',
    'quick-view',
    'advanced-filters',
);
```

## 🛠️ Development

### Asset Compilation

```bash
# Development mode with watch
npm run watch

# Development build
npm run dev

# Production build
npm run production

# Clean build files
npm run clean
```

### Code Standards

- **PHP**: WordPress Coding Standards
- **JavaScript**: WordPress JavaScript Standards
- **CSS**: WordPress CSS Standards + Tailwind
- **Linting**: ESLint, Stylelint

### Testing

```bash
# Run linters
npm run lint

# Run tests (when implemented)
npm run test
```

## 🎨 Customization

### Child Themes

Create a child theme for customizations:

```php
<?php
// wp-content/themes/aqualuxe-child/functions.php

add_action('wp_enqueue_scripts', 'aqualuxe_child_styles');

function aqualuxe_child_styles() {
    wp_enqueue_style('aqualuxe-child', 
        get_stylesheet_directory_uri() . '/style.css',
        array('aqualuxe-style'),
        wp_get_theme()->get('Version')
    );
}
```

### Custom Modules

Create custom modules in the `modules/` directory:

```php
<?php
// modules/my-feature/bootstrap.php

class AquaLuxe_My_Feature {
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Your feature logic here
    }
}

new AquaLuxe_My_Feature();
```

## 🔧 Hooks & Filters

### Theme Hooks

```php
// Modify enabled modules
add_filter('aqualuxe_enabled_modules', 'my_enabled_modules');

// Customize theme colors
add_filter('aqualuxe_color_palette', 'my_color_palette');

// Add custom CSS variables
add_action('aqualuxe_css_variables', 'my_css_variables');
```

### WooCommerce Integration

```php
// Customize products per row
add_filter('loop_shop_columns', function() {
    return get_theme_mod('woocommerce_products_per_row', 4);
});

// Modify product gallery
add_action('woocommerce_before_single_product_summary', 'my_product_gallery', 25);
```

## 🌐 Internationalization

### Supported Languages

- English (en_US)
- Spanish (es_ES)
- French (fr_FR)
- German (de_DE)
- Italian (it_IT)
- Portuguese (pt_PT)
- Russian (ru_RU)
- Chinese (zh_CN)
- Japanese (ja)
- Arabic (ar)

### Translation

```bash
# Generate POT file
wp i18n make-pot . languages/aqualuxe.pot

# Update PO files
wp i18n update-po languages/aqualuxe.pot languages/

# Generate MO files
wp i18n make-mo languages/
```

## 💱 Currency Support

### Supported Currencies

- USD (US Dollar)
- EUR (Euro)
- GBP (British Pound)
- CAD (Canadian Dollar)
- AUD (Australian Dollar)
- JPY (Japanese Yen)
- CNY (Chinese Yuan)
- INR (Indian Rupee)
- BRL (Brazilian Real)
- RUB (Russian Ruble)

## 🎯 Performance

### Optimization Features

- **Lazy Loading**: Images and content
- **Code Splitting**: Separate bundles for different features
- **Asset Minification**: CSS and JavaScript compression
- **Cache Busting**: Automatic asset versioning
- **Critical CSS**: Above-the-fold optimization

### Performance Tips

1. Use a caching plugin (WP Rocket, W3 Total Cache)
2. Optimize images (WebP format recommended)
3. Use a CDN for global content delivery
4. Enable GZIP compression
5. Minimize plugin usage

## 🔒 Security

### Security Features

- **Input Sanitization**: All user inputs sanitized
- **Output Escaping**: Secure output rendering
- **Nonce Verification**: CSRF protection
- **Capability Checks**: Permission validation
- **Secure Coding**: WordPress best practices

## 📚 Documentation

- [User Guide](docs/user-guide.md)
- [Developer Guide](docs/developer-guide.md)
- [Customization Guide](docs/customization.md)
- [WooCommerce Integration](docs/woocommerce.md)
- [Troubleshooting](docs/troubleshooting.md)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## 📄 License

This theme is licensed under the GPL v2 or later.

```
AquaLuxe WordPress Theme
Copyright (C) 2024 Kasun Vimarshana

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 🆘 Support

- **Documentation**: Check the docs folder
- **Issues**: GitHub Issues
- **Community**: WordPress.org forums
- **Professional Support**: Contact the developer

## 🎉 Credits

- **Developer**: [Kasun Vimarshana](https://github.com/kasunvimarshana)
- **Framework**: WordPress
- **E-Commerce**: WooCommerce
- **CSS Framework**: Tailwind CSS
- **Build Tool**: Laravel Mix
- **Icons**: Heroicons
- **Fonts**: Google Fonts

---

**Made with ❤️ for the aquatic community**