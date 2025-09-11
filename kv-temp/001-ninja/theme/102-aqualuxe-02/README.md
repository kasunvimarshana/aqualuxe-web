# AquaLuxe WordPress Theme

**Version:** 1.0.0  
**Author:** AquaLuxe Development Team  
**License:** GPL-2.0-or-later  
**Requires:** WordPress 5.0+, PHP 8.1+  

*"Bringing elegance to aquatic life – globally"*

## Overview

AquaLuxe is a premium WordPress theme designed for aquatic businesses, featuring a modern, mobile-first design with comprehensive e-commerce integration. Built with enterprise-grade architecture, it supports multi-tenancy, multiple vendors, languages, and currencies.

## Key Features

### 🏗️ **Architecture**
- **Modular Design**: Clean separation of concerns with PSR-4 autoloading
- **SOLID Principles**: Maintainable, extensible, and testable code
- **Dual-State**: Works seamlessly with or without WooCommerce
- **Performance Optimized**: Lazy loading, minified assets, caching support

### 🎨 **Design & UX**
- **Mobile-First**: Responsive design with Tailwind CSS
- **Dark Mode**: Persistent user preference with smooth transitions
- **Accessibility**: WCAG 2.1 AA compliant with ARIA support
- **Aquatic Theme**: Elegant design reflecting luxury aquatic aesthetics

### 🌍 **Multi-Everything**
- **Multilingual**: Built-in translation support with language switcher
- **Multicurrency**: Ready for international e-commerce
- **Multitenant**: Scalable architecture for large deployments
- **Multivendor**: Marketplace-ready functionality

### 🛒 **E-commerce Ready**
- **WooCommerce Integration**: Full shop, cart, checkout support
- **Product Types**: Physical, digital, variable, grouped products
- **Advanced Features**: Quick view, filtering, wishlist, reviews
- **International**: Optimized shipping and checkout flows

### 🔧 **Developer Friendly**
- **Modern Tooling**: Webpack, SCSS, PostCSS, Tailwind CSS
- **Hooks & Filters**: Extensive customization options
- **Documentation**: Comprehensive developer and user guides
- **Testing**: Unit and e2e test support

## Installation

### Quick Setup

1. **Download** the theme files
2. **Upload** to `/wp-content/themes/aqualuxe/`
3. **Install dependencies** (see Build Process below)
4. **Activate** the theme in WordPress admin
5. **Import demo content** via Appearance > Demo Importer

### Requirements

- **WordPress:** 6.0+ (recommended 6.7+)
- **PHP:** 8.1+ with extensions: `mbstring`, `curl`, `gd`, `zip`
- **MySQL:** 8.0+ or MariaDB 10.5+
- **Memory:** 512MB+ recommended
- **Node.js:** 16.0+ (for development)
- **NPM:** 8.0+ (for development)

## Build Process

### Development Setup

```bash
# Navigate to theme directory
cd wp-content/themes/aqualuxe

# Install dependencies
npm install

# Development build (with source maps)
npm run dev

# Watch for changes
npm run watch

# Production build (minified)
npm run build
```

### Asset Structure

```
assets/
├── src/          # Source files
│   ├── scss/     # Sass stylesheets
│   ├── js/       # JavaScript modules
│   ├── images/   # Source images
│   └── fonts/    # Font files
└── dist/         # Compiled assets (auto-generated)
    ├── css/      # Minified CSS
    ├── js/       # Minified JavaScript
    ├── images/   # Optimized images
    └── fonts/    # Web fonts
```

## Configuration

### Theme Options

Access via **Appearance > Customize > AquaLuxe Options**:

- **Colors**: Primary, secondary, accent color schemes
- **Typography**: Font families, sizes, line heights
- **Layout**: Container width, sidebar position, header layout
- **Dark Mode**: Default mode, toggle position, auto scheduling
- **Multilingual**: Language preferences, RTL support
- **Performance**: Critical CSS, DNS prefetch domains

### Module System

Enable/disable features in `inc/core/class-bootstrap.php`:

```php
'modules' => array(
    'assets'        => true,  // Asset management
    'security'      => true,  // Security hardening
    'performance'   => true,  // Performance optimization
    'seo'           => true,  // SEO features
    'multilingual'  => true,  // Translation support
    'dark_mode'     => true,  // Dark/light mode toggle
    'woocommerce'   => true,  // E-commerce integration
    'demo_importer' => true,  // Demo content importer
),
```

## Customization

### Child Theme Support

Create a child theme for safe customizations:

```php
<?php
// functions.php in child theme
add_action( 'wp_enqueue_scripts', 'child_theme_styles' );

function child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
}
```

### Hooks & Filters

Extensive customization via WordPress hooks:

```php
// Modify theme configuration
add_filter( 'aqualuxe_theme_config', 'custom_theme_config' );

// Add custom modules
add_action( 'aqualuxe_modules_loaded', 'register_custom_modules' );

// Customize supported languages
add_filter( 'aqualuxe_supported_languages', 'custom_languages' );

// Modify CSP directives
add_filter( 'aqualuxe_csp_directives', 'custom_csp_directives' );
```

### Custom Post Types

Easily add custom content types:

```php
// Register custom post type
add_action( 'init', 'register_aquarium_projects' );

function register_aquarium_projects() {
    register_post_type( 'project', array(
        'labels' => array(
            'name' => 'Aquarium Projects',
            'singular_name' => 'Project',
        ),
        'public' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest' => true,
    ));
}
```

## WooCommerce Integration

### Supported Features

- ✅ **Product Types**: Simple, variable, grouped, external
- ✅ **Shop Features**: Grid/list views, sorting, filtering
- ✅ **Cart & Checkout**: Optimized mobile experience
- ✅ **Account Dashboard**: Order history, downloads, addresses
- ✅ **Payment Gateways**: All major gateways supported
- ✅ **Shipping**: Multiple methods, zones, classes

### Template Overrides

Custom WooCommerce templates in `/woocommerce/` directory:

```
woocommerce/
├── archive-product.php     # Shop page
├── single-product.php      # Product details
├── cart/
│   └── cart.php           # Shopping cart
├── checkout/
│   └── form-checkout.php  # Checkout form
└── myaccount/
    └── dashboard.php      # Account dashboard
```

### Customization Examples

```php
// Modify products per page
add_filter( 'loop_shop_per_page', function() { return 12; } );

// Add custom product tabs
add_filter( 'woocommerce_product_tabs', 'add_custom_product_tab' );

// Customize cart/checkout fields
add_filter( 'woocommerce_checkout_fields', 'customize_checkout_fields' );
```

## Performance Optimization

### Built-in Optimizations

- **Asset Minification**: CSS and JS automatically minified
- **Image Optimization**: Lazy loading, WebP support
- **Caching**: Browser caching headers, object cache support
- **Database**: Optimized queries, transient caching
- **CDN Ready**: Asset URLs easily configurable

### Performance Checklist

- ✅ Use a caching plugin (WP Rocket, W3 Total Cache)
- ✅ Enable object caching (Redis, Memcached)
- ✅ Optimize images (WebP, proper sizing)
- ✅ Use a CDN for static assets
- ✅ Enable GZIP compression
- ✅ Monitor with performance tools

### Lighthouse Targets

- **Performance**: ≥90 (mobile), ≥95 (desktop)
- **Accessibility**: ≥95
- **Best Practices**: ≥90
- **SEO**: ≥95

## Security Features

### Built-in Security

- **Input Sanitization**: All user inputs properly sanitized
- **Output Escaping**: XSS prevention on all outputs
- **Nonce Verification**: CSRF protection on forms
- **Capability Checks**: Proper permission validation
- **Security Headers**: CSP, HSTS, X-Frame-Options

### Security Checklist

- ✅ Keep WordPress, themes, and plugins updated
- ✅ Use strong passwords and 2FA
- ✅ Limit login attempts
- ✅ Hide WordPress version information
- ✅ Regular security scans
- ✅ Backup regularly

## SEO Features

### Built-in SEO

- **Schema Markup**: Article, Organization, WebSite schemas
- **Open Graph**: Facebook and social media optimization
- **Twitter Cards**: Enhanced Twitter sharing
- **Meta Tags**: Automatic title, description generation
- **Sitemaps**: Enhanced WordPress sitemaps
- **Breadcrumbs**: Structured navigation support

### SEO Best Practices

- ✅ Use semantic HTML structure
- ✅ Optimize images with alt text
- ✅ Write descriptive meta descriptions
- ✅ Use heading hierarchy (H1-H6)
- ✅ Implement internal linking
- ✅ Monitor with Google Search Console

## Accessibility (WCAG 2.1 AA)

### Compliance Features

- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Readers**: Proper ARIA labels and landmarks
- **Color Contrast**: Meets AA contrast ratios
- **Focus Management**: Visible focus indicators
- **Alternative Text**: Image accessibility
- **Skip Links**: Content navigation shortcuts

### Accessibility Testing

Use these tools to verify compliance:

- **axe DevTools**: Browser extension for automated testing
- **WAVE**: Web accessibility evaluation tool
- **Lighthouse**: Built-in accessibility audit
- **Screen Readers**: Test with NVDA, JAWS, VoiceOver

## Troubleshooting

### Common Issues

**Build Errors**
```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

**PHP Errors**
- Check PHP version (8.1+ required)
- Increase memory limit (512MB+)
- Enable required extensions

**Performance Issues**
- Enable caching plugins
- Optimize database
- Check for plugin conflicts
- Monitor server resources

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

## Support & Documentation

### Resources

- **Theme Documentation**: `/docs/` directory
- **Developer Guide**: `/docs/developer-guide.md`
- **User Manual**: `/docs/user-guide.md`
- **Changelog**: `CHANGELOG.md`
- **License**: `LICENSE`

### Getting Help

1. **Check Documentation**: Review theme docs first
2. **Search Issues**: Look for similar problems
3. **WordPress Forums**: Community support
4. **Theme Support**: Premium support available

## Contributing

### Development Workflow

1. **Fork** the repository
2. **Create** a feature branch
3. **Make** your changes
4. **Test** thoroughly
5. **Submit** a pull request

### Coding Standards

- **PHP**: WordPress Coding Standards, PSR-12
- **JavaScript**: WordPress JavaScript Standards
- **CSS**: WordPress CSS Standards
- **Commits**: Conventional commit messages

### Testing

```bash
# Run PHP tests
composer test

# Run JavaScript tests
npm test

# Run linting
npm run lint

# Run accessibility tests
npm run test:a11y
```

## License

This theme is licensed under the GNU General Public License v2.0 or later.

You are free to:
- ✅ Use for personal and commercial projects
- ✅ Modify and customize
- ✅ Distribute and sell

Requirements:
- 📋 Include original license
- 📋 Share modifications under same license
- 📋 Provide source code access

## Changelog

### v1.0.0 - Initial Release
- 🎉 Complete theme implementation
- ✨ Modular architecture with SOLID principles
- 🎨 Mobile-first responsive design
- 🌍 Multilingual and multicurrency support
- 🛒 Full WooCommerce integration
- ⚡ Performance optimizations
- 🔒 Security hardening
- ♿ WCAG 2.1 AA compliance
- 📱 Dark mode support
- 🎯 SEO optimization

---

**AquaLuxe Theme** - *Bringing elegance to aquatic life – globally*