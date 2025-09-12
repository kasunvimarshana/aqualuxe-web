"# AquaLuxe WordPress Theme

**Bringing elegance to aquatic life – globally.**

A premium, modular WordPress theme designed for luxury aquatic retail businesses. Features multitenant, multivendor, multilingual, multicurrency support with dual-state architecture that works seamlessly with or without WooCommerce.

## 🌟 Features

### 🏗️ Architecture
- **Modular Design**: Clean separation of core framework and feature modules
- **Dual-State Architecture**: Works with or without WooCommerce (graceful fallbacks)
- **SOLID Principles**: Single Responsibility, Open-Closed, Liskov Substitution, Interface Segregation, Dependency Inversion
- **DRY & KISS**: Don't Repeat Yourself, Keep It Simple Stupid
- **Performance Optimized**: Lazy loading, caching, minification, tree-shaking

### 🛒 E-Commerce Ready
- **Full WooCommerce Integration**: Product types (physical/digital/variable/grouped)
- **Quick View Modals**: Product preview without page reload
- **Advanced Filtering**: Search and filter products by multiple criteria
- **Wishlist Functionality**: Save products for later
- **Multicurrency Ready**: International shipping and checkout optimization
- **Wholesale Pricing**: B2B functionality with tiered pricing

### 🌍 Global & Multilingual
- **Multilingual Support**: WPML and Polylang integration
- **RTL Language Support**: Right-to-left language compatibility
- **Currency Support**: Multiple currency display and conversion
- **International Shipping**: Optimized for global commerce

### 🎨 Design & UX
- **Mobile-First Design**: Fully responsive across all devices
- **Dark Mode Support**: User preference with persistent storage
- **Accessibility Ready**: WCAG 2.1 AA compliance
- **Tailwind CSS**: Utility-first CSS framework for rapid development
- **Micro-Interactions**: Smooth animations and transitions

### 📱 Performance & SEO
- **Performance Optimized**: Lighthouse score target ≥ 70
- **SEO Optimized**: Schema.org markup, meta tags, sitemaps
- **Critical CSS Inlining**: Above-the-fold optimization
- **Image Optimization**: Lazy loading and responsive images
- **Security Hardened**: Input sanitization, CSRF protection, secure coding

## 🧩 Modules

### Core Modules
- **Multilingual**: WPML/Polylang integration with language switcher
- **Dark Mode**: Persistent user preference toggle
- **WooCommerce**: Complete e-commerce integration with fallbacks
- **Demo Importer**: Robust content importer with progress tracking

### Business Modules
- **Services**: Service booking and management system
- **Events**: Event calendar with ticketing and registration
- **Subscriptions**: Membership and newsletter management
- **Wholesale**: B2B pricing and account management
- **Auctions**: Product bidding and auction functionality
- **Franchise**: Partner portal and licensing inquiries

### Advanced Modules
- **Multivendor**: Multi-seller marketplace functionality
- **Sustainability**: Environmental initiatives and reporting
- **Affiliates**: Referral program management

## 🛠️ Installation

### Requirements
- WordPress 6.0+
- PHP 8.0+
- MySQL 5.7+
- WooCommerce 7.0+ (optional but recommended)

### Quick Install
1. Download the theme package
2. Upload to `/wp-content/themes/aqualuxe/`
3. Activate the theme in WordPress admin
4. Run `npm install && npm run production` for asset compilation
5. Import demo content via **Appearance > Demo Importer**

### Development Setup
```bash
# Clone the repository
git clone https://github.com/kasunvimarshana/aqualuxe.git

# Install dependencies
npm install

# Development build (with source maps)
npm run dev

# Production build (minified)
npm run production

# Watch for changes
npm run watch
```

## 📁 File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source files
│   │   ├── js/                # JavaScript files
│   │   ├── scss/              # SCSS stylesheets
│   │   ├── images/            # Source images
│   │   └── fonts/             # Web fonts
│   └── dist/                  # Compiled assets
├── core/                      # Core framework files
│   ├── class-asset-manager.php
│   ├── class-customizer.php
│   ├── class-performance.php
│   ├── class-security.php
│   ├── class-seo.php
│   ├── class-template-loader.php
│   └── class-theme-setup.php
├── modules/                   # Feature modules
│   ├── multilingual/
│   ├── dark-mode/
│   ├── woocommerce/
│   ├── demo-importer/
│   ├── services/
│   ├── events/
│   ├── subscriptions/
│   ├── wholesale/
│   └── [other-modules]/
├── templates/                 # Page templates
│   ├── page-about.php
│   ├── page-contact.php
│   └── [other-templates]/
├── woocommerce/              # WooCommerce overrides
├── languages/                # Translation files
├── functions.php             # Main theme functions
├── style.css                 # Theme information
├── webpack.mix.js            # Asset compilation
├── tailwind.config.js        # Tailwind configuration
└── package.json              # Dependencies
```

## ⚙️ Configuration

### Customizer Options
Access via **Appearance > Customize**:
- **Site Identity**: Logo, colors, typography
- **Layout**: Container width, sidebar position
- **Header Options**: Sticky header, transparency
- **Footer Options**: Description, copyright
- **Contact Information**: Phone, email, address, hours
- **Social Media**: Social network URLs
- **WooCommerce**: Shop layout, products per page

### Module Configuration
Each module can be toggled and configured independently:
- Navigate to module-specific admin pages
- Use shortcodes for flexible content placement
- Configure API integrations and third-party services

## 🔧 Development

### Adding New Modules
1. Create module directory: `modules/my-module/`
2. Create main class: `class-my-module-module.php`
3. Follow naming convention: `AquaLuxe_My_Module_Module`
4. Register in `functions.php` module loader

### Customization
- **Child Theme Recommended**: Create child theme for customizations
- **Hooks & Filters**: Extensive action and filter hooks available
- **Template Overrides**: Override templates in child theme
- **SCSS Variables**: Customize colors and spacing in `tailwind.config.js`

### Asset Management
- All source files in `assets/src/`
- Compiled files in `assets/dist/`
- Never enqueue raw source files
- Use `aqualuxe_asset()` helper for cache-busted URLs

## 📖 Usage Examples

### Shortcodes

```php
// Display services
[aqualuxe_services limit="6" columns="3" show_booking="true"]

// Events calendar
[aqualuxe_events_calendar month="12" year="2024"]

// Subscription plans
[aqualuxe_subscription_plans columns="3" featured="true"]

// Newsletter signup
[aqualuxe_newsletter_signup title="Stay Updated" style="minimal"]

// Wholesale benefits
[aqualuxe_wholesale_benefits columns="3"]
```

### Template Usage

```php
// Check if WooCommerce is active
if (aqualuxe_is_woocommerce_active()) {
    // WooCommerce functionality
}

// Get theme option
$logo_height = aqualuxe_get_option('logo_height', 60);

// Load template part
aqualuxe_get_template_part('components/hero', null, $args);

// Get asset URL with cache busting
wp_enqueue_style('custom', aqualuxe_asset('css/custom.css'));
```

## 🧪 Testing

### Browser Testing
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

### Performance Testing
- Lighthouse audits (target: 70+ mobile)
- GTmetrix analysis
- WebPageTest.org validation

### Accessibility Testing
- WAVE Web Accessibility Evaluator
- axe DevTools
- Keyboard navigation testing
- Screen reader compatibility

## 🚀 Deployment

### Production Checklist
- [ ] Run `npm run production`
- [ ] Test on staging environment
- [ ] Verify asset loading
- [ ] Check for PHP errors
- [ ] Validate markup
- [ ] Test performance
- [ ] Security scan

### Performance Optimization
- Enable object caching
- Configure CDN
- Optimize database
- Enable gzip compression
- Set proper cache headers

## 🆘 Support

### Documentation
- [Theme Documentation](docs/)
- [Developer Guide](docs/developer-guide.md)
- [API Reference](docs/api-reference.md)

### Community
- [GitHub Issues](https://github.com/kasunvimarshana/aqualuxe/issues)
- [Support Forum](https://example.com/support)
- [Knowledge Base](https://example.com/kb)

## 📄 License

This theme is licensed under the GNU General Public License v2.0 or later.
See [LICENSE](LICENSE) for full license text.

## 🏆 Credits

### Dependencies
- **WordPress**: Content management system
- **WooCommerce**: E-commerce platform
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Swiper**: Modern touch slider
- **Laravel Mix**: Asset compilation

### Assets
- Icons: Font Awesome
- Images: Unsplash (properly attributed)
- Fonts: Google Fonts

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Coding Standards
- Follow WordPress Coding Standards
- Use PSR-4 autoloading
- Write comprehensive comments
- Include unit tests for new features

---

**AquaLuxe** - Premium WordPress theme for luxury aquatic retail
© 2024 AquaLuxe Team" 
