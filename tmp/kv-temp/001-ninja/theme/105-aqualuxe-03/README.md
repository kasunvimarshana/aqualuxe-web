# AquaLuxe WordPress Theme

**Version:** 1.0.0  
**Tagline:** Bringing elegance to aquatic life – globally  
**License:** GPL v2 or later  

## Description

AquaLuxe is a comprehensive, modular WordPress theme designed specifically for luxury aquatic retail businesses. Built with modern web standards and best practices, it provides a complete solution for aquarium stores, aquatic service providers, and marine life enthusiasts.

## Features

### 🏗️ **Modular Architecture**
- **Clean separation of concerns** with core/ and modules/ directories
- **Loosely coupled modules** that can be enabled/disabled independently
- **SOLID principles** implementation throughout the codebase
- **Dependency injection** and proper abstraction layers

### 🌐 **Multilingual & Multicurrency Support**
- Built-in multilingual functionality with 7+ languages
- Automatic language detection from browser preferences
- Persistent user language preferences
- RTL language support
- Multicurrency readiness for international commerce

### 🛒 **WooCommerce Integration**
- **Dual-state architecture** - works with or without WooCommerce
- Graceful fallbacks when WooCommerce is inactive
- Support for all product types (simple, variable, grouped, digital)
- Advanced product filtering and search
- Wishlist functionality
- Quick view modals
- Optimized checkout and cart processes

### 🎨 **Design & User Experience**
- **Mobile-first responsive design**
- **Dark/Light mode toggle** with persistent preferences
- **Accessibility compliant** (WCAG 2.1 AA)
- **Semantic HTML5** markup
- **Progressive enhancement** - fully functional without JavaScript
- **Micro-interactions** and smooth animations

### 📦 **Content Management**
- **Custom Post Types**: Services, Events, Bookings, Auctions, Trade-ins, Testimonials, Team Members
- **Custom Taxonomies**: Service categories, Event types, Auction categories, Geographic regions
- **Advanced Custom Fields** integration
- **Demo Content Importer** with ACID transactions
- **Comprehensive Admin Dashboard**

### 🏢 **Professional Services**
- Aquarium design and consultation
- Installation and maintenance services
- Equipment sales and support
- Water quality testing and analysis
- Custom aquascaping services

### 💼 **Business Modules**

#### **Wholesale/B2B System**
- Wholesale account management
- Bulk pricing and quote requests
- B2B user roles and permissions
- Trade account applications
- Volume discount tiers

#### **Franchise/Licensing Portal**
- Franchise inquiry system
- Partner portal with resources
- Performance tracking and reporting
- Resource management for partners
- Territory management

#### **Auctions & Trade-ins**
- Live auction bidding system
- Automated auction endings
- Trade-in valuation requests
- Premium and rare item auctions
- Bid history and notifications

#### **Subscriptions & Memberships**
- Recurring service subscriptions
- Membership tier management
- Premium content access
- Member-only pricing
- Automatic renewals

#### **Affiliate & Referrals**
- Affiliate program management
- Referral tracking and rewards
- Commission calculations
- Partner recruitment tools
- Performance analytics

#### **R&D & Sustainability**
- Research project showcase
- Sustainability initiatives
- Environmental impact tracking
- Innovation documentation
- Green certification programs

### 🔒 **Security & Performance**
- **Enhanced security** with rate limiting, CSRF protection, and input validation
- **Performance optimized** with lazy loading, caching, and asset minification
- **SEO optimized** with schema markup and semantic structure
- **Cross-browser compatibility** with modern browsers
- **Progressive Web App** ready with service workers

### 🌟 **Advanced Features**
- **Demo content importer** with rollback capabilities
- **Multi-tenant architecture** for franchise operations
- **API integrations** for third-party services
- **Automated backup and restore**
- **Real-time notifications**
- **Advanced analytics and reporting**

## Installation

### Requirements
- WordPress 5.9 or higher
- PHP 7.4 or higher
- Node.js 16+ (for development)
- NPM 8+ (for development)

### Quick Installation

1. **Download the theme** from the repository or marketplace
2. **Upload to WordPress** via Appearance > Themes > Add New > Upload Theme
3. **Activate the theme** 
4. **Install dependencies** (if developing):
   ```bash
   npm install
   composer install
   ```
5. **Build assets** (if developing):
   ```bash
   npm run build
   ```

### Development Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe.git
   cd aqualuxe
   ```

2. **Install dependencies**:
   ```bash
   npm install
   composer install
   ```

3. **Start development server**:
   ```bash
   npm run dev
   ```

4. **Watch for changes**:
   ```bash
   npm run watch
   ```

## Configuration

### Theme Customizer
Access through **Appearance > Customize** to configure:
- Site identity and logo
- Color schemes and typography
- Layout options
- Header and footer settings
- WooCommerce integration
- Module enablement

### Demo Content
Import demo content via **Appearance > Demo Importer**:
- Full demo with all content types
- Selective import options
- Rollback capabilities
- Progress tracking
- Conflict resolution

### Module Management
Configure modules through **AquaLuxe > Module Settings**:
- Enable/disable specific modules
- Configure module-specific settings
- Manage dependencies
- Monitor performance impact

## Business Model Integration

AquaLuxe supports diverse revenue streams:

### **Primary Revenue Streams**
- **Retail Sales**: Products, equipment, and supplies
- **Wholesale Operations**: B2B sales and distribution
- **Professional Services**: Design, installation, maintenance
- **Franchise Operations**: Licensing and territory management

### **Secondary Revenue Streams**
- **Auctions & Trade-ins**: Premium and rare item sales
- **Subscriptions**: Ongoing services and memberships
- **Affiliate Programs**: Partner and referral commissions
- **Educational Services**: Training and certification programs

### **Supported Business Models**
- **B2C (Business-to-Consumer)**: Direct retail sales
- **B2B (Business-to-Business)**: Wholesale and trade accounts
- **Franchise**: Multi-location business operations
- **Subscription**: Recurring service and product delivery
- **Marketplace**: Multi-vendor platform capabilities

## Usage Examples

### Creating a Service
```php
// Register a new aquatic service
$service_id = wp_insert_post(array(
    'post_title'   => 'Premium Aquarium Maintenance',
    'post_content' => 'Complete maintenance service for luxury aquariums...',
    'post_type'    => 'aqualuxe_service',
    'post_status'  => 'publish',
    'meta_input'   => array(
        '_service_price' => '299.99',
        '_service_duration' => '2 hours',
        '_service_frequency' => 'monthly'
    )
));
```

### Displaying Services
```php
// Show services with custom query
$services = new WP_Query(array(
    'post_type' => 'aqualuxe_service',
    'posts_per_page' => 6,
    'meta_key' => '_service_featured',
    'meta_value' => 'yes'
));

if ($services->have_posts()) {
    while ($services->have_posts()) {
        $services->the_post();
        // Display service content
    }
}
```

### Shortcodes
```html
<!-- Display auction listings -->
[auction_listing limit="8" status="active" category="rare-fish"]

<!-- Show franchise application form -->
[franchise_application_form title="Join Our Network"]

<!-- Display wholesale quote form -->
[wholesale_quote_form]

<!-- Show trade-in form -->
[trade_form title="Trade Your Equipment"]
```

## API Documentation

### REST API Endpoints

#### Services API
```
GET /wp-json/aqualuxe/v1/services
POST /wp-json/aqualuxe/v1/services
GET /wp-json/aqualuxe/v1/services/{id}
PUT /wp-json/aqualuxe/v1/services/{id}
DELETE /wp-json/aqualuxe/v1/services/{id}
```

#### Auctions API
```
GET /wp-json/aqualuxe/v1/auctions
POST /wp-json/aqualuxe/v1/auctions/{id}/bid
GET /wp-json/aqualuxe/v1/auctions/{id}/bids
```

#### Wholesale API
```
POST /wp-json/aqualuxe/v1/wholesale/application
POST /wp-json/aqualuxe/v1/wholesale/quote
GET /wp-json/aqualuxe/v1/wholesale/pricing/{product_id}
```

### Hooks & Filters

#### Action Hooks
```php
// Before service display
do_action('aqualuxe_before_service_display', $service_id);

// After auction bid placed
do_action('aqualuxe_auction_bid_placed', $auction_id, $bid_id, $user_id);

// Before wholesale application processing
do_action('aqualuxe_before_wholesale_application', $application_data);
```

#### Filter Hooks
```php
// Modify service pricing
$price = apply_filters('aqualuxe_service_price', $price, $service_id);

// Customize auction bid increment
$increment = apply_filters('aqualuxe_auction_bid_increment', $increment, $auction_id);

// Filter wholesale discount
$discount = apply_filters('aqualuxe_wholesale_discount', $discount, $user_id);
```

## Customization

### Child Theme
Create a child theme for customizations:

```php
// style.css
/*
Theme Name: AquaLuxe Child
Template: aqualuxe
Version: 1.0.0
*/

@import url("../aqualuxe/style.css");

/* Your custom styles here */
```

### Custom Templates
Override templates by copying to your child theme:
- `single-aqualuxe-service.php` - Service single page
- `archive-aqualuxe-auction.php` - Auction archive
- `taxonomy-service-category.php` - Service category archive

### Custom Modules
Create custom modules following the existing pattern:

```php
// modules/custom-module/module.php
class AquaLuxe_Custom_Module {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Your module initialization
    }
}

AquaLuxe_Custom_Module::get_instance();
```

## Performance Optimization

### Caching
- **Object caching** for database queries
- **Page caching** for static content
- **Asset caching** with versioning
- **CDN integration** ready

### Asset Optimization
- **Minified CSS/JS** in production
- **Tree shaking** for unused code
- **Image optimization** with lazy loading
- **WebP support** for modern browsers

### Database Optimization
- **Efficient queries** with proper indexing
- **Batch processing** for large operations
- **Transient caching** for expensive operations
- **Query optimization** hooks

## Security Features

### Input Validation
- **Comprehensive sanitization** of all inputs
- **CSRF protection** with tokens
- **Rate limiting** for login attempts
- **XSS prevention** with output escaping

### File Security
- **Upload restrictions** by file type
- **Malware scanning** for uploads
- **Directory traversal** prevention
- **Execution prevention** in uploads

### Authentication Security
- **Failed login tracking**
- **IP-based restrictions**
- **Session management**
- **Two-factor authentication** ready

## Testing

### Unit Tests
```bash
# Run PHP unit tests
composer test

# Run JavaScript tests
npm test
```

### End-to-End Tests
```bash
# Run Playwright tests
npm run test:e2e
```

### Performance Tests
```bash
# Run performance benchmarks
npm run test:performance
```

## Contributing

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

### Coding Standards
- **WordPress Coding Standards** for PHP
- **ESLint configuration** for JavaScript
- **Stylelint configuration** for CSS
- **PSR-4 autoloading** for classes

### Code Quality
```bash
# Lint PHP code
composer run phpcs

# Lint JavaScript
npm run lint:js

# Lint CSS
npm run lint:css

# Fix code style
npm run lint:fix
```

## Support

### Documentation
- **User Guide**: Comprehensive setup and usage instructions
- **Developer Guide**: API documentation and customization examples
- **Video Tutorials**: Step-by-step setup and configuration
- **FAQ**: Common questions and troubleshooting

### Community
- **GitHub Issues**: Bug reports and feature requests
- **Discussion Forums**: Community support and discussions
- **Slack Channel**: Real-time developer support
- **Newsletter**: Updates and announcements

### Professional Support
- **Priority Support**: Direct access to development team
- **Custom Development**: Tailored features and integrations
- **Training Sessions**: On-site or remote team training
- **Maintenance Plans**: Ongoing updates and support

## Changelog

### Version 1.0.0 (Current)
- ✅ **Initial Release** with full feature set
- ✅ **Modular Architecture** with 12+ business modules
- ✅ **WooCommerce Integration** with dual-state architecture
- ✅ **Advanced Security** with rate limiting and CSRF protection
- ✅ **Demo Content Importer** with rollback capabilities
- ✅ **Comprehensive Documentation** with examples
- ✅ **Performance Optimization** with lazy loading and caching
- ✅ **Accessibility Compliance** (WCAG 2.1 AA)
- ✅ **Multi-language Support** with 7+ languages
- ✅ **Professional Service Management**
- ✅ **Auction and Trade-in System**
- ✅ **Wholesale/B2B Functionality**
- ✅ **Franchise Portal**
- ✅ **Subscription Management**
- ✅ **Affiliate Program**
- ✅ **R&D and Sustainability Tracking**

## License

This theme is licensed under the GPL v2 or later.

```
Copyright (C) 2024 AquaLuxe Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Credits

### Third-Party Libraries
- **Laravel Mix** - Asset compilation
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **GSAP** - Animation library
- **Swiper** - Touch slider
- **Lazysizes** - Lazy loading library

### Images and Assets
- All demo images are sourced from copyright-free providers
- Icons from Dashicons and custom icon set
- Fonts from Google Fonts with proper licensing

---

**AquaLuxe Theme** - Bringing elegance to aquatic life, globally. 🐠✨
- Service booking and scheduling system
- Inquiry forms with AJAX submissions
- Service categories and tagging
- Pricing and duration management
- Location and availability tracking

### 🚀 **Performance & SEO**
- **Asset optimization** with Webpack and Laravel Mix
- **Lazy loading** for images and content
- **Critical CSS inlining**
- **Schema.org markup** for rich snippets
- **Open Graph** and Twitter Card support
- **XML Sitemap** generation
- **Minified and cached assets**

### 🔒 **Security & Compliance**
- **Input sanitization** and output escaping
- **CSRF protection** with nonces
- **XSS and SQL injection prevention**
- **Secure coding practices**
- **Privacy-compliant** cookie handling

### 🛠️ **Development Features**
- **Modern build tools** (Webpack, Babel, Sass, Tailwind CSS)
- **Automated testing** setup (Jest, PHPUnit)
- **Code linting** (ESLint, Stylelint, PHPCS)
- **Version control** integration
- **CI/CD pipeline** configuration

## Installation

### Requirements
- **WordPress:** 5.0+
- **PHP:** 7.4+
- **Node.js:** 16.0+
- **NPM:** 8.0+

### Quick Start

1. **Download and Install**
   ```bash
   # Download the theme
   git clone https://github.com/kasunvimarshana/aqualuxe.git
   
   # Navigate to theme directory
   cd aqualuxe
   
   # Install dependencies
   npm install
   
   # Build assets
   npm run build
   ```

2. **Activate Theme**
   - Upload the theme to your `/wp-content/themes/` directory
   - Activate "AquaLuxe" from WordPress Admin → Appearance → Themes

3. **Import Demo Content**
   - Go to Appearance → Demo Importer
   - Select "Full Import" or choose selective options
   - Click "Start Import" and wait for completion

4. **Configure Settings**
   - Customize your site via Appearance → Customize
   - Set up menus in Appearance → Menus
   - Configure widgets in Appearance → Widgets

## File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source assets
│   │   ├── js/                # JavaScript files
│   │   │   ├── modules/       # Module-specific JS
│   │   │   └── admin/         # Admin interface JS
│   │   └── scss/              # Sass stylesheets
│   └── dist/                  # Compiled assets (auto-generated)
├── core/
│   ├── classes/               # Core PHP classes
│   │   ├── ThemeCore.php     # Main theme class
│   │   └── ModuleManager.php  # Module management
│   └── functions/             # Core functions
├── modules/                   # Feature modules
│   ├── multilingual/         # Multilingual support
│   ├── services/             # Professional services
│   ├── dark-mode/            # Dark/light mode toggle
│   └── [other-modules]/      # Additional modules
├── templates/                 # Template files
│   ├── parts/                # Template parts
│   ├── pages/                # Page templates
│   └── woocommerce/          # WooCommerce overrides
├── inc/                      # Additional includes
│   ├── admin/                # Admin functionality
│   ├── customizer/           # Theme customizer
│   ├── demo-importer/        # Demo content importer
│   ├── config.php            # Theme configuration
│   ├── custom-post-types.php # Custom post types
│   └── custom-taxonomies.php # Custom taxonomies
├── languages/                # Translation files
├── docs/                     # Documentation
├── tests/                    # Test files
└── demo-content/            # Demo content files
```

## Modules

### Available Modules

| Module | Description | Status |
|--------|-------------|--------|
| **Multilingual** | Multi-language support with auto-detection | ✅ Active |
| **Dark Mode** | Toggle between light and dark themes | ✅ Active |
| **Services** | Professional service management | ✅ Active |
| **Subscriptions** | Recurring payment and memberships | 🚧 Planned |
| **Bookings** | Appointment and scheduling system | 🚧 Planned |
| **Events** | Event management and ticketing | 🚧 Planned |
| **Auctions** | Auction and trade-in functionality | 🚧 Planned |
| **Wholesale** | B2B pricing and features | 🚧 Planned |
| **Franchise** | Partner portal and management | 🚧 Planned |
| **Sustainability** | R&D and eco-initiatives | 🚧 Planned |
| **Affiliates** | Referral and affiliate system | 🚧 Planned |
| **Marketplace** | Multi-vendor platform | 🚧 Planned |

## Development

### Build Commands

```bash
# Development build with watching
npm run dev

# Production build (minified)
npm run build

# Watch files for changes
npm run watch

# Hot reload development server
npm run hot

# Lint code
npm run lint

# Run tests
npm run test

# Clean dist folder
npm run clean
```

### Coding Standards

- **PHP:** WordPress Coding Standards, PSR-4 autoloading
- **JavaScript:** ESLint with WordPress configuration
- **CSS/Sass:** Stylelint with standard configuration
- **File Naming:** snake_case for PHP, kebab-case for assets
- **Comments:** Comprehensive inline documentation

## License

This theme is licensed under the GPL v2 or later.

```
Copyright (C) 2024 AquaLuxe Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

**AquaLuxe** - Premium WordPress Theme for Aquatic Retail  
*Bringing elegance to aquatic life – globally*
