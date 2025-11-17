# AquaLuxe WordPress Theme - Developer Guide

## Overview

AquaLuxe is a modern, modular WordPress theme designed for luxury aquatic retail businesses. It follows enterprise-grade architecture patterns with SOLID principles, comprehensive security measures, and performance optimizations.

## Architecture

### Core Principles
- **SOLID**: Single Responsibility, Open-Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **DRY**: Don't Repeat Yourself
- **KISS**: Keep It Simple, Stupid
- **YAGNI**: You Aren't Gonna Need It
- **Separation of Concerns**: Clear boundaries between functionality

### Directory Structure

```
aqualuxe/
├── assets/                    # Asset management
│   ├── src/                   # Source files (SCSS, JS, images, fonts)
│   │   ├── js/
│   │   │   ├── modules/       # Feature-specific JS modules
│   │   │   ├── admin/         # Admin-only scripts
│   │   │   └── *.js          # Core application scripts
│   │   ├── scss/
│   │   │   ├── base/          # Base styles (reset, typography)
│   │   │   ├── components/    # Reusable components
│   │   │   ├── layout/        # Layout-specific styles
│   │   │   ├── theme/         # Theme-specific styles
│   │   │   └── utilities/     # Utility classes
│   │   ├── images/           # Source images
│   │   └── fonts/            # Font files
│   └── dist/                 # Compiled, optimized assets
├── core/                     # Core theme functionality
│   ├── classes/              # PSR-4 autoloaded classes
│   │   └── Core/             # Core namespace classes
│   └── functions/            # Core theme functions
├── inc/                      # Theme includes
│   ├── admin/                # Admin interface components
│   ├── customizer/           # Theme customizer
│   ├── demo-importer/        # Demo content importer
│   └── *.php                # Configuration files
├── modules/                  # Feature modules (self-contained)
│   ├── dark-mode/            # Dark mode functionality
│   ├── multilingual/         # Multi-language support
│   ├── services/             # Service management
│   ├── bookings/             # Booking system
│   ├── events/               # Event management
│   ├── wholesale/            # Wholesale functionality
│   ├── franchise/            # Franchise management
│   └── auctions/             # Auction system
├── template-parts/           # Reusable template components
│   ├── content/              # Content templates
│   ├── header/               # Header components
│   └── navigation/           # Navigation components
├── woocommerce/              # WooCommerce template overrides
├── languages/                # Translation files
└── docs/                     # Documentation
```

## Getting Started

### Prerequisites
- Node.js 16+ and npm 8+
- PHP 7.4+
- WordPress 5.0+
- Composer (optional, for PHP development tools)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe.git
   cd aqualuxe
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Install PHP dependencies (optional):**
   ```bash
   composer install
   ```

4. **Build assets:**
   ```bash
   npm run build
   ```

### Development Workflow

#### Asset Development
```bash
# Development build with source maps
npm run development

# Watch for changes
npm run watch

# Hot reloading
npm run hot

# Production build
npm run production
```

#### Code Quality
```bash
# Run all linting
npm run lint

# Auto-fix linting issues
npm run lint:fix

# Format code
npm run format

# Full validation (lint + test + build)
npm run validate
```

#### Testing
```bash
# Run JavaScript tests
npm run test

# Watch tests
npm run test:watch

# Run E2E tests
npm run test:e2e
```

## Modular Architecture

### Creating a New Module

1. **Create module directory:**
   ```bash
   mkdir modules/my-feature
   cd modules/my-feature
   ```

2. **Create module.php:**
   ```php
   <?php
   /**
    * My Feature Module
    */
   
   class AquaLuxe_My_Feature_Module {
       public function __construct() {
           add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
           add_action('init', array($this, 'init'));
       }
   
       public function init() {
           // Module initialization
       }
   
       public function enqueue_scripts() {
           if ($this->should_load()) {
               wp_enqueue_script(
                   'aqualuxe-my-feature',
                   AQUALUXE_ASSETS_URI . '/js/modules/my-feature.js',
                   array('jquery'),
                   AQUALUXE_VERSION,
                   true
               );
           }
       }
   
       private function should_load() {
           // Conditional loading logic
           return true;
       }
   }
   
   // Initialize module
   new AquaLuxe_My_Feature_Module();
   ```

3. **Create JavaScript module:**
   ```javascript
   // assets/src/js/modules/my-feature.js
   (function($) {
       'use strict';
   
       const MyFeature = {
           init() {
               // Feature initialization
           }
       };
   
       $(document).ready(() => {
           MyFeature.init();
       });
   
   })(jQuery);
   ```

4. **Add to webpack.mix.js:**
   ```javascript
   mix.js('assets/src/js/modules/my-feature.js', 'assets/dist/js/modules');
   ```

### Module Configuration

Modules support toggle-able configuration:

```php
// In module constructor
if (!get_theme_mod('aqualuxe_enable_my_feature', true)) {
    return; // Don't initialize if disabled
}
```

## Security Features

### Input Sanitization
```php
// Use type-specific sanitization
$email = sanitize_email($_POST['email']);
$url = esc_url_raw($_POST['url']);
$text = sanitize_text_field($_POST['text']);
$html = wp_kses_post($_POST['content']);
```

### CSRF Protection
```php
// Generate nonce
wp_nonce_field('my_action', 'my_nonce');

// Verify nonce
if (!wp_verify_nonce($_POST['my_nonce'], 'my_action')) {
    wp_die('Security check failed');
}
```

### Rate Limiting
```php
// Check rate limit for user
if (!aqualuxe_check_rate_limit()) {
    wp_send_json_error('Too many requests', 429);
}
```

### Secure AJAX Handlers
```php
// Register secure AJAX handler
aqualuxe_secure_ajax_handler('my_action', 'my_callback_function', true, true);

function my_callback_function() {
    // CSRF and rate limiting automatically handled
    $data = aqualuxe_sanitize_ajax_data($_POST);
    
    // Process request
    wp_send_json_success($response_data);
}
```

## Performance Optimization

### Asset Management
- **Manifest-based versioning** for cache busting
- **Critical CSS inlining** for above-the-fold content
- **Lazy loading** for images and non-critical assets
- **Tree shaking** for JavaScript optimization
- **Minification** and compression in production

### Caching Strategy
```php
// Use transients for expensive operations
$data = get_transient('expensive_operation');
if (false === $data) {
    $data = perform_expensive_operation();
    set_transient('expensive_operation', $data, HOUR_IN_SECONDS);
}
```

### Database Optimization
```php
// Use efficient queries
$posts = get_posts(array(
    'post_type' => 'product',
    'posts_per_page' => 10,
    'meta_query' => array(
        array(
            'key' => 'featured',
            'value' => '1',
            'compare' => '='
        )
    ),
    'no_found_rows' => true, // Skip pagination count
    'update_post_meta_cache' => false, // Skip meta cache if not needed
    'update_post_term_cache' => false // Skip term cache if not needed
));
```

## WooCommerce Integration

### Graceful Fallbacks
The theme works with or without WooCommerce:

```php
// Check WooCommerce availability
if (class_exists('WooCommerce')) {
    // WooCommerce-specific functionality
    $cart_url = wc_get_cart_url();
} else {
    // Fallback functionality
    $cart_url = '#';
}
```

### Template Overrides
Custom WooCommerce templates in `/woocommerce/` directory:
- `single-product.php` - Product page template
- `archive-product.php` - Shop page template
- `cart/cart.php` - Cart page template
- `checkout/form-checkout.php` - Checkout form

### Product Types Support
- Physical products
- Digital products
- Variable products
- Grouped products
- Subscription products (with extensions)

## Customizer Integration

### Adding Custom Controls
```php
// In customizer.php
$wp_customize->add_setting('aqualuxe_my_setting', array(
    'default' => 'default_value',
    'sanitize_callback' => 'sanitize_text_field',
    'transport' => 'refresh'
));

$wp_customize->add_control('aqualuxe_my_setting', array(
    'label' => __('My Setting', 'aqualuxe'),
    'section' => 'aqualuxe_general',
    'type' => 'text'
));
```

### Live Preview
```javascript
// In customizer-preview.js
wp.customize('aqualuxe_my_setting', function(value) {
    value.bind(function(newval) {
        $('.my-element').text(newval);
    });
});
```

## Internationalization

### Text Domains
All translatable strings use the `aqualuxe` text domain:

```php
__('Text to translate', 'aqualuxe');
_e('Text to echo', 'aqualuxe');
esc_html__('Text to escape and translate', 'aqualuxe');
```

### Language Files
- `languages/aqualuxe.pot` - Template file
- `languages/aqualuxe-{locale}.po` - Translation files
- `languages/aqualuxe-{locale}.mo` - Compiled translations

### RTL Support
The theme includes RTL (Right-to-Left) support:
- Automatic direction detection
- RTL-specific styles in SCSS
- Proper text alignment

## Testing

### Unit Tests
```bash
# Run PHP unit tests
composer test

# Run JavaScript unit tests
npm run test
```

### E2E Tests
```bash
# Run Playwright tests
npm run test:e2e
```

### Performance Testing
```bash
# Analyze bundle size
npm run analyze

# Run Lighthouse audit
npm run lighthouse
```

## Deployment

### Production Checklist
- [ ] Run `npm run production` for optimized assets
- [ ] Verify all tests pass
- [ ] Check security measures are enabled
- [ ] Test with and without WooCommerce
- [ ] Verify accessibility compliance
- [ ] Test on various devices and browsers

### Environment Variables
```bash
# .env file (not committed)
WP_DEBUG=false
WP_DEBUG_LOG=false
SCRIPT_DEBUG=false
```

### Build Optimization
The production build includes:
- JavaScript minification and compression
- CSS minification and autoprefixing
- Image optimization
- Font subsetting
- Cache busting via manifest
- Tree shaking for unused code

## Troubleshooting

### Common Issues

**Build fails:**
```bash
# Clear cache and reinstall
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Assets not loading:**
- Check file permissions
- Verify manifest.json exists
- Ensure WordPress has write access to assets/dist/

**JavaScript errors:**
- Check browser console
- Verify jQuery is loaded
- Check for conflicts with other plugins

**Performance issues:**
- Enable caching plugins
- Optimize images
- Use CDN for static assets
- Enable gzip compression

### Debug Mode
Enable debug mode for development:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);
```

## Contributing

### Code Standards
- Follow WordPress Coding Standards
- Use PSR-4 autoloading for PHP classes
- Use ESLint and Prettier for JavaScript
- Write comprehensive tests
- Document all functions and classes

### Pull Request Process
1. Fork the repository
2. Create a feature branch
3. Write tests for new functionality
4. Ensure all tests pass
5. Submit pull request with description

## Support

### Resources
- [WordPress Developer Documentation](https://developer.wordpress.org/)
- [WooCommerce Developer Documentation](https://woocommerce.github.io/code-reference/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Laravel Mix Documentation](https://laravel-mix.com/docs)

### Getting Help
- Check the documentation first
- Search existing issues on GitHub
- Create detailed bug reports
- Join the community discussions

---

**Version:** 1.0.0  
**Last Updated:** $(date +%Y-%m-%d)  
**Maintained By:** AquaLuxe Development Team