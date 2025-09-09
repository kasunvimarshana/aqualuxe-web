# AquaLuxe WordPress Theme

**"Bringing elegance to aquatic life – globally"**

A premium, modular WordPress theme designed for luxury aquatic solutions, fish farming, aquarium services, and aquatic lifestyle businesses.

## 🌟 Features

### ✨ Core Features
- **Modular Architecture**: Self-contained modules that can be independently toggled
- **Dual-State Design**: Works seamlessly with or without WooCommerce
- **Mobile-First**: Fully responsive across all devices and screen sizes
- **Dark Mode**: Persistent user preference with system detection
- **Performance Optimized**: Lazy loading, caching, minification, tree-shaking
- **Accessibility Ready**: WCAG 2.1 AA compliance
- **SEO Optimized**: Schema markup, meta tags, semantic HTML
- **Multilingual Ready**: Translation-ready with RTL support
- **Multi-Currency Support**: Ready for international commerce

### 🛍️ WooCommerce Integration
- Complete shop functionality
- Product types: Physical, Digital, Variable, Grouped
- Quick-view modals and advanced filtering
- Wishlist functionality
- Optimized checkout and cart
- Multi-currency readiness
- International shipping support

### 🎨 Design System
- **Tailwind CSS**: Utility-first CSS framework
- **Custom Color Palette**: Aquatic blues, luxury teals, elegant golds
- **Typography**: Inter, Poppins, Playfair Display fonts
- **Component Library**: Reusable UI components
- **Animation System**: Smooth transitions and micro-interactions

### 🔧 Developer Features
- **Modern Build Tools**: Webpack + Laravel Mix
- **Asset Pipeline**: SCSS compilation, JS bundling, image optimization
- **Modular JavaScript**: ES6 modules with event bus system
- **WordPress Standards**: Follows WordPress coding standards
- **PSR-4 Autoloading**: Organized class structure
- **Hook System**: Comprehensive action and filter hooks

### 📋 Business Features
- **Custom Post Types**: Services, Events, Team, Testimonials, Portfolio, FAQ, Partners, Auctions
- **Demo Content Importer**: Comprehensive importer with flush mechanism
- **Theme Customizer**: Logo, colors, typography, layout options
- **Social Integration**: Social media links and sharing
- **Contact Forms**: Built-in contact form functionality
- **SEO Tools**: Breadcrumbs, meta descriptions, Open Graph

## 🚀 Quick Start

### Requirements
- **WordPress**: 6.0 or higher
- **PHP**: 8.1 or higher
- **Node.js**: 18.0 or higher
- **npm**: 9.0 or higher

### Installation

1. **Download & Install**
   ```bash
   # Upload the theme files to wp-content/themes/aqualuxe/
   # Or install via WordPress admin
   ```

2. **Install Dependencies**
   ```bash
   cd wp-content/themes/aqualuxe/
   npm install
   ```

3. **Build Assets**
   ```bash
   # Development build
   npm run dev
   
   # Production build
   npm run build
   ```

4. **Activate Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Activate AquaLuxe theme

5. **Import Demo Content** (Optional)
   - Go to Appearance → Demo Import
   - Select content to import
   - Click "Import Demo Content"

## 📁 File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Raw assets
│   │   ├── scss/              # Sass stylesheets
│   │   ├── js/                # JavaScript modules
│   │   ├── images/            # Images
│   │   └── fonts/             # Fonts
│   └── dist/                  # Compiled assets
├── core/                      # Core theme functionality
├── modules/                   # Feature modules
│   ├── custom-post-types/     # Custom post types
│   ├── dark-mode/             # Dark mode functionality
│   └── demo-importer/         # Demo content importer
├── inc/                       # Theme includes
│   ├── setup/                 # Theme setup files
│   ├── functions/             # Helper functions
│   ├── features/              # Theme features
│   ├── components/            # Components
│   ├── admin/                 # Admin functionality
│   ├── woocommerce/           # WooCommerce integration
│   └── classes/               # PHP classes
├── templates/                 # Template files
│   ├── content/               # Content templates
│   ├── parts/                 # Template parts
│   └── pages/                 # Page templates
├── languages/                 # Translation files
├── docs/                      # Documentation
└── tests/                     # Theme tests
```

## 🎨 Customization

### Theme Customizer
Access comprehensive theme options via **Appearance → Customize**:

- **Site Identity**: Logo, colors, typography
- **Header Options**: Navigation, search, cart, account links
- **Footer Options**: Copyright text, social links
- **Colors**: Primary, secondary, accent colors
- **Dark Mode**: Enable/disable dark mode toggle

### Custom Colors
The theme uses CSS custom properties for dynamic theming:

```css
:root {
  --primary-color: #3b82f6;    /* Primary Blue */
  --secondary-color: #14b8a6;   /* Secondary Teal */
  --accent-color: #f59e0b;      /* Accent Gold */
}
```

### Modular System
Enable/disable features by filtering active modules:

```php
function my_active_modules( $modules ) {
    // Remove dark mode module
    unset( $modules['dark-mode'] );
    
    // Add custom module
    $modules['my-custom-feature'] = true;
    
    return $modules;
}
add_filter( 'aqualuxe_active_modules', 'my_active_modules' );
```

## 🛠️ Development

### Build Commands
```bash
# Development with watch
npm run watch

# Development build
npm run dev

# Production build
npm run build

# Lint JavaScript
npm run lint:js

# Lint CSS
npm run lint:css
```

### Creating Custom Modules
1. Create module directory: `modules/my-module/`
2. Add `module.php` file with module class
3. Add module to active modules filter
4. Include module-specific assets and templates

### Hooks & Filters

#### Action Hooks
```php
// Header hooks
do_action( 'aqualuxe_header_start' );
do_action( 'aqualuxe_header_actions' );
do_action( 'aqualuxe_header_end' );

// Content hooks
do_action( 'aqualuxe_before_main_content' );
do_action( 'aqualuxe_after_main_content' );

// Footer hooks
do_action( 'aqualuxe_footer_start' );
do_action( 'aqualuxe_footer_social' );
do_action( 'aqualuxe_footer_end' );
```

#### Filter Hooks
```php
// Modify excerpt length
add_filter( 'aqualuxe_excerpt_length', function() { return 30; } );

// Modify active modules
add_filter( 'aqualuxe_active_modules', 'my_modules_callback' );

// Modify breadcrumbs
add_filter( 'aqualuxe_breadcrumbs', 'my_breadcrumbs_callback' );
```

## 🌐 Multilingual Support

The theme is translation-ready with support for:
- **RTL Languages**: Full right-to-left support
- **Translation Files**: POT file included
- **Localization**: All strings are translatable
- **Context-Aware**: Proper translation contexts

### Creating Translations
1. Generate POT file: `wp i18n make-pot . languages/aqualuxe.pot`
2. Create language files (e.g., `aqualuxe-es_ES.po`)
3. Compile to binary: `msgfmt aqualuxe-es_ES.po -o aqualuxe-es_ES.mo`

## 🔐 Security

The theme implements comprehensive security measures:
- **Input Sanitization**: All user inputs are sanitized
- **Output Escaping**: All outputs are properly escaped
- **Nonce Verification**: CSRF protection on all forms
- **Capability Checks**: Proper permission checks
- **SQL Injection Prevention**: Prepared statements

## ⚡ Performance

### Optimization Features
- **Asset Minification**: CSS and JS minification
- **Image Optimization**: Automatic image compression
- **Lazy Loading**: Images and content lazy loading
- **Caching**: Built-in caching mechanisms
- **Tree Shaking**: Unused code elimination
- **Critical CSS**: Above-the-fold CSS optimization

### Performance Best Practices
- Use production build for live sites
- Enable caching plugins
- Optimize images before upload
- Use WebP format when possible
- Enable GZIP compression

## 🧪 Testing

### Browser Support
- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile, Samsung Internet
- **Accessibility**: Screen readers and keyboard navigation

### Testing Commands
```bash
# Run tests
npm test

# Run with coverage
npm run test:coverage

# Watch mode
npm run test:watch
```

## 📚 Documentation

- **User Guide**: Complete setup and usage instructions
- **Developer Guide**: Technical documentation and API reference
- **Customization Guide**: Theme modification examples
- **Troubleshooting**: Common issues and solutions

## 🤝 Support

### Getting Help
- **Documentation**: Check the complete documentation
- **GitHub Issues**: Report bugs and request features
- **Community Forum**: Get help from other users

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This theme is licensed under the **GPL v3 or later**.

### Credits
- **Tailwind CSS**: Utility-first CSS framework
- **Laravel Mix**: Asset compilation
- **Webpack**: Module bundler
- **Icons**: Custom SVG icons
- **Fonts**: Google Fonts (Inter, Poppins, Playfair Display)

---

**AquaLuxe** - Premium WordPress theme for aquatic businesses
*"Bringing elegance to aquatic life – globally"*