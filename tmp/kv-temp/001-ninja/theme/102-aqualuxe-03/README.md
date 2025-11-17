# AquaLuxe WordPress Theme

**Bringing elegance to aquatic life – globally.**

A premium, modular WordPress theme designed for luxury aquatic commerce, featuring multi-tenant, multivendor marketplace capabilities, multilingual and multicurrency support, and comprehensive WooCommerce integration.

## Features

### 🏗️ **Architecture**
- **Modular Design**: Self-contained modules with clear separation of concerns
- **SOLID Principles**: Clean, maintainable, and extensible codebase
- **Dual-State Compatibility**: Works seamlessly with or without WooCommerce
- **Progressive Enhancement**: Fully functional with JavaScript disabled

### 🎨 **Design & UX**
- **Mobile-First**: Responsive design optimized for all devices
- **Dark Mode**: Persistent user preference with smooth transitions
- **Accessibility**: WCAG 2.1 AA compliant with comprehensive ARIA support
- **Modern Aesthetics**: Luxurious aquatic-themed design with refined typography

### 🛍️ **E-Commerce**
- **WooCommerce Integration**: Complete shop functionality with custom templates
- **Product Types**: Support for physical, digital, variable, and grouped products
- **Quick View**: Modal product previews with AJAX functionality
- **Wishlist**: Save favorite products for later
- **Advanced Filtering**: Search and filter products by multiple criteria

### 🌐 **Internationalization**
- **Multilingual Ready**: Full translation support with RTL language compatibility
- **Multicurrency**: Ready for international commerce
- **Regional Customization**: Localized content and formatting options

### ⚡ **Performance**
- **Modern Build System**: Webpack with Tailwind CSS and asset optimization
- **Lazy Loading**: Images and content loaded on demand
- **Cache Optimization**: Efficient asset bundling with cache busting
- **SEO Optimized**: Schema.org markup, Open Graph, and semantic HTML

### 🔧 **Developer Features**
- **Custom Post Types**: Services, Events, Testimonials, Team, Portfolio, FAQ, Partners
- **Theme Customizer**: Comprehensive customization options
- **Hook System**: Extensive action and filter hooks for customization
- **Demo Importer**: ACID-compliant demo content importer with progress tracking

## Installation

1. **Upload the theme**:
   ```bash
   wp-content/themes/aqualuxe/
   ```

2. **Install dependencies**:
   ```bash
   cd wp-content/themes/aqualuxe/
   npm install
   ```

3. **Build assets**:
   ```bash
   npm run production
   ```

4. **Activate the theme** in WordPress admin under Appearance > Themes

## Development

### Requirements
- **PHP**: 8.0+
- **WordPress**: 6.0+
- **Node.js**: 16+
- **WooCommerce**: 7.0+ (optional)

### Build Process

#### Development Build
```bash
npm run dev
```

#### Watch for Changes
```bash
npm run watch
```

#### Production Build
```bash
npm run production
```

### File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source files
│   │   ├── css/               # Tailwind CSS files
│   │   ├── js/                # JavaScript modules
│   │   ├── scss/              # SCSS files
│   │   ├── images/            # Source images
│   │   └── fonts/             # Font files
│   └── dist/                  # Compiled assets
├── core/                      # Core theme functionality
│   ├── Setup/                 # Theme setup classes
│   ├── Customizer/            # Theme customizer
│   ├── Admin/                 # Admin functionality
│   ├── Security/              # Security hardening
│   ├── SEO/                   # SEO optimization
│   └── Performance/           # Performance optimization
├── modules/                   # Feature modules
│   ├── dark-mode/             # Dark mode functionality
│   ├── multilingual/          # Multilingual support
│   ├── woocommerce/           # WooCommerce enhancements
│   ├── demo-importer/         # Demo content importer
│   └── [other-modules]/       # Additional modules
├── templates/                 # Page templates
├── template-parts/            # Template partials
├── woocommerce/              # WooCommerce template overrides
├── inc/                      # Helper functions and utilities
├── languages/                # Translation files
├── demo-content/             # Demo content files
└── docs/                     # Documentation
```

## Customization

### Theme Customizer

Access comprehensive customization options through **Appearance > Customize**:

- **Colors**: Primary, secondary, and accent color schemes
- **Typography**: Font sizes, weights, and family options
- **Layout**: Container width, sidebar position, and spacing
- **Header**: Logo, navigation, and styling options
- **Footer**: Widget columns, copyright text, and layout
- **WooCommerce**: Product display and functionality settings

### Custom Post Types

The theme includes several custom post types:

- **Services**: Business services with pricing and duration
- **Events**: Calendar events with date, time, and location
- **Testimonials**: Customer reviews with ratings
- **Team**: Staff members with positions and contact info
- **Portfolio**: Project showcases with client information
- **FAQ**: Frequently asked questions with categories
- **Partners**: Business partners and affiliates

### Hooks and Filters

Extensive hook system for developers:

```php
// Custom action hooks
do_action('aqualuxe_before_header');
do_action('aqualuxe_after_content');

// Custom filter hooks
apply_filters('aqualuxe_social_share_links', $links);
apply_filters('aqualuxe_theme_colors', $colors);
```

## Demo Content

Import demo content through **Appearance > Demo Importer**:

1. **Aquarium Retailer**: Complete e-commerce setup with products
2. **Fish Breeder**: Breeding operation with livestock focus
3. **Aquatic Services**: Service-based business template
4. **Marine Supplier**: Wholesale and distribution setup

### Demo Importer Features

- **ACID Transactions**: Reliable import with rollback capability
- **Progress Tracking**: Real-time import progress with detailed status
- **Selective Import**: Choose specific content types to import
- **Conflict Resolution**: Handle duplicate content intelligently
- **Reset Functionality**: Clean slate reset with complete data removal

## WooCommerce Integration

### Dual-State Architecture

The theme works seamlessly with or without WooCommerce:

**With WooCommerce Active**:
- Full e-commerce functionality
- Custom product templates
- Enhanced checkout process
- Advanced filtering and search

**Without WooCommerce**:
- Graceful fallbacks to standard content
- Service-based business functionality
- Portfolio and testimonial showcase
- Contact and inquiry forms

### Product Features

- **Quick View**: Modal product previews
- **Wishlist**: Save products for later
- **Product Gallery**: High-resolution image galleries
- **Variation Swatches**: Visual product options
- **Stock Indicators**: Real-time availability status

## Performance Optimization

### Build Optimization

- **Tree Shaking**: Remove unused CSS and JavaScript
- **Code Splitting**: Separate vendor and application code
- **Asset Compression**: Minified and optimized files
- **Cache Busting**: Versioned assets for optimal caching

### Runtime Optimization

- **Lazy Loading**: Images and content loaded on demand
- **Critical CSS**: Above-the-fold styles inlined
- **Resource Hints**: Preconnect and prefetch for faster loading
- **Optimized Queries**: Efficient database operations

## Security

### Input Sanitization
- All user inputs properly sanitized
- WordPress nonce verification
- Capability checks for admin functions
- SQL injection prevention

### Output Escaping
- All output properly escaped
- XSS attack prevention
- Safe HTML rendering
- Secure template includes

## SEO Features

### Schema.org Markup
- **Organization**: Business information
- **Product**: WooCommerce product data
- **Article**: Blog post structured data
- **LocalBusiness**: Location and contact information

### Open Graph & Twitter Cards
- **Social Sharing**: Optimized social media previews
- **Image Optimization**: Proper aspect ratios and sizes
- **Meta Descriptions**: Dynamic and customizable descriptions

## Accessibility (WCAG 2.1 AA)

### Keyboard Navigation
- Full keyboard accessibility
- Logical tab order
- Skip links for main content
- Focus indicators

### Screen Reader Support
- Semantic HTML structure
- ARIA labels and descriptions
- Alternative text for images
- Proper heading hierarchy

### Color and Contrast
- Sufficient color contrast ratios
- Color-independent information
- High contrast mode support
- Customizable color schemes

## Browser Support

- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile Browsers**: iOS Safari 14+, Chrome Mobile 90+
- **Graceful Degradation**: Basic functionality in older browsers

## License

This theme is licensed under the [GPL v2.0 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Support

For support and documentation:

- **Documentation**: [Theme Documentation](docs/)
- **GitHub Issues**: [Report Issues](https://github.com/kasunvimarshana/aqualuxe-web/issues)
- **Theme Updates**: Automatic updates through WordPress admin

## Credits

### Fonts
- **Inter**: [Google Fonts](https://fonts.google.com/specimen/Inter)
- **Playfair Display**: [Google Fonts](https://fonts.google.com/specimen/Playfair+Display)

### Images
- **Demo Images**: [Unsplash](https://unsplash.com) (freely usable images)
- **Icons**: Custom SVG icons and [Heroicons](https://heroicons.com)

### Libraries
- **Tailwind CSS**: [MIT License](https://github.com/tailwindlabs/tailwindcss/blob/master/LICENSE)
- **Laravel Mix**: [MIT License](https://github.com/laravel-mix/laravel-mix/blob/master/LICENSE)
- **GSAP**: [Standard License](https://greensock.com/standard-license/) (bundled locally)

---

**AquaLuxe Theme** - Bringing elegance to aquatic life, globally. 🐠✨