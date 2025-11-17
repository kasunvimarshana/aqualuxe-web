# AquaLuxe WordPress Theme

**Bringing elegance to aquatic life – globally**

A modular, multitenant, multivendor WordPress theme designed for luxury aquatic retail with comprehensive e-commerce functionality and global reach.

## Features

### 🏗️ **Modular Architecture**
- **Clean Architecture**: Separated concerns with core/, modules/, assets/, inc/, templates/
- **SOLID Principles**: Single Responsibility, Open-Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **Modular Design**: Self-contained modules that can be independently enabled/disabled
- **Autoloading**: PSR-4 compliant autoloader for all theme classes

### 🛒 **WooCommerce Integration**
- **Dual-State Architecture**: Works seamlessly with or without WooCommerce
- **Product Types**: Full support for physical, digital, variable, and grouped products
- **Quick View**: AJAX-powered product quick view modals
- **Cart Integration**: Real-time cart updates and fragments
- **Graceful Fallbacks**: Consistent UX when WooCommerce is inactive

### 🌍 **Multilingual & Global Ready**
- **Language Switching**: Built-in language switcher with persistent preferences
- **RTL Support**: Automatic right-to-left layout for applicable languages
- **Plugin Integration**: Compatible with Polylang, WPML, and other i18n plugins
- **Browser Detection**: Automatic language detection from browser preferences

### 🎨 **Dark Mode Support**
- **Toggle Functionality**: User-controlled dark/light mode switching
- **Persistent Preferences**: Remembers user choice across sessions
- **System Integration**: Respects system-level dark mode preferences
- **Smooth Transitions**: Animated theme switching with CSS transitions

### 📱 **Responsive Design**
- **Mobile-First**: Optimized for mobile devices with progressive enhancement
- **Tailwind CSS**: Utility-first CSS framework for rapid development
- **Custom Components**: Reusable UI components with consistent styling
- **Cross-Browser**: Compatible with modern browsers and graceful degradation

### ⚡ **Performance Optimized**
- **Asset Management**: Webpack compilation with cache busting
- **Lazy Loading**: Images and content loaded on demand
- **Critical CSS**: Inline critical styles for faster first paint
- **Minification**: Compressed and optimized assets for production

### 🔒 **Security Hardened**
- **Input Sanitization**: All user inputs properly sanitized and escaped
- **CSRF Protection**: Nonce verification for all AJAX requests
- **XSS Prevention**: Secure output handling and content filtering
- **Secure Headers**: Security headers for enhanced protection

### ♿ **Accessibility Compliant**
- **WCAG 2.1 AA**: Meets accessibility standards
- **ARIA Labels**: Proper labeling for screen readers
- **Keyboard Navigation**: Full keyboard accessibility
- **Focus Management**: Logical tab order and focus indicators

## Theme Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source assets
│   │   ├── scss/              # Sass stylesheets
│   │   ├── js/                # JavaScript files
│   │   ├── images/            # Images
│   │   └── fonts/             # Fonts
│   └── dist/                  # Compiled assets
├── core/                      # Core theme classes
│   ├── abstracts/             # Abstract base classes
│   ├── interfaces/            # Interface definitions
│   ├── services/              # Service classes
│   └── Theme.php              # Main theme class
├── inc/                       # Theme components
│   ├── customizer/            # Customizer integration
│   ├── post-types/            # Custom post types
│   ├── taxonomies/            # Custom taxonomies
│   ├── widgets/               # Custom widgets
│   └── shortcodes/            # Custom shortcodes
├── modules/                   # Feature modules
│   ├── dark-mode/             # Dark mode functionality
│   ├── multilingual/          # Language support
│   ├── subscriptions/         # Subscription system
│   ├── bookings/              # Booking system
│   ├── events/                # Events management
│   ├── auctions/              # Auction system
│   ├── wholesale/             # Wholesale functionality
│   ├── franchise/             # Franchise management
│   ├── sustainability/        # Sustainability features
│   ├── affiliate/             # Affiliate system
│   ├── services/              # Service management
│   └── multivendor/           # Multi-vendor marketplace
├── templates/                 # Template files
│   ├── parts/                 # Template partials
│   ├── pages/                 # Page templates
│   ├── archive/               # Archive templates
│   └── single/                # Single post templates
├── woocommerce/               # WooCommerce template overrides
│   ├── single-product/        # Single product templates
│   ├── archive-product/       # Product archive templates
│   ├── cart/                  # Cart templates
│   ├── checkout/              # Checkout templates
│   └── account/               # Account templates
├── languages/                 # Translation files
├── docs/                      # Documentation
└── tests/                     # Test files
    ├── unit/                  # Unit tests
    └── integration/           # Integration tests
```

## Installation

### Requirements
- WordPress 6.0 or higher
- PHP 8.0 or higher
- Node.js 18.0 or higher
- npm 8.0 or higher

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web/wp-content/themes/aqualuxe
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Build assets**
   ```bash
   # Development build
   npm run dev
   
   # Production build
   npm run build
   
   # Watch for changes
   npm run watch
   ```

4. **Activate the theme**
   - Go to WordPress Admin → Appearance → Themes
   - Activate "AquaLuxe"

### Docker Setup

The project includes a complete Docker environment:

```bash
# Copy environment file
cp .env.example .env

# Start the development environment
docker-compose up -d

# Access the site
open http://localhost:8080
```

## Configuration

### Theme Customizer

Access comprehensive theme settings via **Appearance → Customize**:

- **Brand Identity**: Logo, tagline, and brand colors
- **Layout Options**: Header, footer, and sidebar configurations
- **Typography**: Font selections and sizing
- **Dark Mode**: Dark theme settings and preferences
- **Multilingual**: Language settings and switcher options
- **WooCommerce**: Shop layout and product display options

### Module Management

Enable/disable theme modules via **Appearance → Modules**:

- **Dark Mode**: User-controlled theme switching
- **Multilingual**: Language support and switching
- **Subscriptions**: Membership and subscription management
- **Bookings**: Appointment and booking system
- **Events**: Event management and ticketing
- **Auctions**: Auction and bidding functionality
- **Wholesale**: B2B pricing and ordering
- **Franchise**: Franchise management system
- **Sustainability**: Environmental tracking
- **Affiliate**: Referral and commission system
- **Services**: Service booking and management
- **Multivendor**: Multi-vendor marketplace

## Custom Post Types

The theme includes comprehensive custom post types:

### Services (`aqualuxe_service`)
Aquarium design, maintenance, consultation services
- **Fields**: Price, duration, featured status
- **Archive**: `/services`
- **Single**: `/services/service-name`

### Portfolio (`aqualuxe_portfolio`)
Showcase of completed projects
- **Fields**: Client, project URL, completion date
- **Archive**: `/portfolio`
- **Single**: `/portfolio/project-name`

### Testimonials (`aqualuxe_testimonial`)
Customer reviews and feedback
- **Fields**: Client info, rating, company details
- **Display**: Used in homepage and service pages

### Team (`aqualuxe_team`)
Team member profiles
- **Fields**: Position, contact info, social links
- **Archive**: `/team`
- **Single**: `/team/member-name`

### Events (`aqualuxe_event`)
Workshops, exhibitions, training sessions
- **Fields**: Date/time, location, pricing, attendee limits
- **Archive**: `/events`
- **Single**: `/events/event-name`

### Bookings (`aqualuxe_booking`)
Service appointment bookings
- **Fields**: Service, date/time, customer info, status
- **Admin-only**: Booking management interface

### Subscriptions (`aqualuxe_subscription`)
Membership and recurring services
- **Fields**: Plan details, billing info, status
- **Admin-only**: Subscription management

### Locations (`aqualuxe_location`)
Physical store/office locations
- **Fields**: Address, coordinates, hours, contact
- **Archive**: `/locations`
- **Single**: `/locations/location-name`

## WooCommerce Integration

### Product Categories
- **Rare Fish Species**: Exotic and premium fish
- **Aquatic Plants**: Live plants and decorations
- **Premium Equipment**: High-end aquarium equipment
- **Care Supplies**: Maintenance and care products

### Features
- **Quick View**: Product details in modal overlay
- **Wishlist**: Save products for later
- **Advanced Filtering**: By category, price, attributes
- **Multi-currency**: International currency support
- **Shipping**: Specialized aquatic shipping options

### Template Overrides
Custom WooCommerce templates in `/woocommerce/`:
- Enhanced product cards with hover effects
- Improved shop layout with filtering
- Streamlined checkout process
- Responsive account dashboard

## Development

### Build System

The theme uses Laravel Mix for asset compilation:

```javascript
// webpack.mix.js configuration
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .sass('assets/src/scss/app.scss', 'assets/dist/css')
   .options({
       processCssUrls: false,
       postCss: [tailwindcss('./tailwind.config.js')],
   });
```

### CSS Framework

Tailwind CSS provides utility-first styling:

```javascript
// tailwind.config.js
module.exports = {
  content: ['./**/*.php', './assets/src/js/**/*.js'],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: { /* Custom aqua theme colors */ },
        secondary: { /* Supporting colors */ },
      },
    },
  },
};
```

### JavaScript Architecture

Modern ES6+ JavaScript with Alpine.js for reactivity:

```javascript
// Theme class organization
class AquaLuxeTheme {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupNavigation();
        this.setupDarkMode();
        this.setupWooCommerce();
        // ... other features
    }
}
```

### PHP Architecture

Object-oriented PHP following PSR standards:

```php
// Service container pattern
class Theme {
    private $asset_manager;
    private $hook_manager;
    private $module_manager;
    private $woocommerce_integration;
    
    public function init(): void {
        $this->asset_manager->init();
        $this->hook_manager->init();
        $this->module_manager->init();
        $this->woocommerce_integration->init();
    }
}
```

## API Reference

### Helper Functions

```php
// Asset management
aqualuxe_asset($path); // Get versioned asset URL
aqualuxe_get_option($option, $default); // Get theme option
aqualuxe_is_woocommerce_active(); // Check WooCommerce status

// Template functions
aqualuxe_get_template_part($slug, $name, $args); // Load template with data
```

### Hooks and Filters

```php
// Custom hooks
do_action('aqualuxe_theme_initialized', $theme);
do_action('aqualuxe_module_loaded', $module_name, $instance);
do_action('aqualuxe_header_actions'); // For header customizations

// Filters
apply_filters('aqualuxe_modules_config', $modules);
apply_filters('aqualuxe_asset_url', $url, $path);
```

## Testing

### Running Tests

```bash
# Install test dependencies
composer install

# Run PHPUnit tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

### Test Structure

```
tests/
├── unit/                      # Unit tests
│   ├── Core/                  # Core class tests
│   ├── Services/              # Service tests
│   └── Modules/               # Module tests
├── integration/               # Integration tests
│   ├── WooCommerce/           # WooCommerce integration
│   └── Customizer/            # Customizer tests
└── fixtures/                  # Test data and fixtures
```

## Contributing

### Development Workflow

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/new-feature`
3. **Make** your changes following coding standards
4. **Test** your changes: `npm run test`
5. **Commit** your changes: `git commit -am 'Add new feature'`
6. **Push** to the branch: `git push origin feature/new-feature`
7. **Submit** a pull request

### Coding Standards

- **PHP**: Follow WordPress Coding Standards and PSR-12
- **JavaScript**: Use ESLint configuration provided
- **CSS**: Follow BEM methodology and Tailwind best practices
- **Documentation**: Update docs for any new features

### Module Development

Creating a new module:

```php
<?php
namespace AquaLuxe\Modules\NewModule;

use AquaLuxe\Core\Abstracts\AbstractModule;

class NewModule extends AbstractModule {
    protected $version = '1.0.0';
    
    public function init(): void {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->register_hooks();
    }
    
    protected function register_hooks(): void {
        // Register your hooks here
    }
}
```

## Deployment

### Production Build

```bash
# Install production dependencies
npm ci --production

# Build optimized assets
npm run production

# Optimize autoloader
composer install --no-dev --optimize-autoloader
```

### Server Requirements

- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.0+ with required extensions
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Memory**: 256MB minimum, 512MB recommended

### Performance Optimization

- Enable **object caching** (Redis/Memcached)
- Configure **CDN** for static assets
- Enable **Gzip compression**
- Set up **image optimization**
- Configure **browser caching**

## Support

### Documentation
- [Theme Documentation](docs/)
- [API Reference](docs/api.md)
- [Module Development](docs/modules.md)
- [Customization Guide](docs/customization.md)

### Community
- [GitHub Issues](https://github.com/kasunvimarshana/aqualuxe-web/issues)
- [GitHub Discussions](https://github.com/kasunvimarshana/aqualuxe-web/discussions)

## License

This theme is licensed under the [GPL v2 or later](LICENSE).

```
AquaLuxe WordPress Theme
Copyright (C) 2024 AquaLuxe Development Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## Credits

### Dependencies
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **GSAP**: Animation library
- **Swiper**: Modern slider library

### Assets
- Images from [Unsplash](https://unsplash.com) (free license)
- Icons from [Heroicons](https://heroicons.com) (MIT license)
- Fonts from [Google Fonts](https://fonts.google.com) (Open Font License)

---

**AquaLuxe** - Bringing elegance to aquatic life – globally 🌊