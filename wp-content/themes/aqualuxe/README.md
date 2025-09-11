# 🌊 AquaLuxe WordPress Theme

A next-generation WordPress theme engineered with a modular, mobile-first, multi-tenant, multivendor, multilingual, multicurrency, and multi-theme architecture. Built for performance, scalability, and elegance in aquatic commerce.

## 🚀 Features

### Core Architecture
- **Modular Design**: Self-contained modules with easy enable/disable functionality
- **SOLID Principles**: Clean, maintainable, and extensible code architecture
- **Mobile-First**: Responsive design that works perfectly on all devices
- **Multi-Tenant**: Support for multiple tenants and white-label solutions
- **Dark Mode**: Built-in dark mode with persistent user preference

### E-Commerce Ready
- **Dual-State Architecture**: Works seamlessly with or without WooCommerce
- **Multi-Currency**: International commerce support with currency switching
- **Multi-Vendor**: Marketplace functionality for multiple sellers
- **Product Management**: Advanced product types and catalog management

### Performance & Security
- **Asset Optimization**: Webpack-based build system with Tailwind CSS
- **Lazy Loading**: Optimized image and content loading
- **Security Hardening**: Built-in security features and best practices
- **Caching**: Advanced caching and performance optimization

### Developer Experience
- **Modern Stack**: ES6+, Tailwind CSS, PostCSS, Laravel Mix
- **Component System**: Reusable UI components and templates
- **Custom Post Types**: Fish species, services, events, team members
- **Demo Importer**: Comprehensive demo content with flush capabilities

## 📁 File Structure

```
aqualuxe/
├── assets/
│   ├── src/                      # Source assets
│   │   ├── css/                  # Stylesheets (Tailwind CSS)
│   │   ├── js/                   # JavaScript modules
│   │   ├── images/               # Image assets
│   │   ├── fonts/                # Custom fonts
│   │   └── icons/                # Icon assets
│   └── dist/                     # Compiled assets
├── core/                         # Core theme classes
│   ├── class-theme-setup.php     # Theme setup and configuration
│   ├── class-assets.php          # Asset management
│   ├── class-helpers.php         # Utility functions
│   ├── class-security.php        # Security features
│   ├── class-performance.php     # Performance optimizations
│   ├── class-post-types.php      # Custom post types
│   ├── class-taxonomies.php      # Custom taxonomies
│   └── class-module-loader.php   # Module management system
├── modules/                      # Feature modules
│   ├── dark-mode/               # Dark mode functionality
│   ├── demo-importer/           # Demo content importer
│   ├── multilingual/            # Multi-language support
│   ├── multicurrency/           # Multi-currency support
│   ├── multivendor/             # Multi-vendor marketplace
│   └── [other-modules]/         # Additional feature modules
├── templates/                    # Template files
├── woocommerce/                  # WooCommerce template overrides
├── languages/                    # Translation files
├── docs/                         # Documentation
├── functions.php                 # Main theme functions
├── style.css                     # Theme stylesheet header
├── index.php                     # Main template
├── header.php                    # Header template
├── footer.php                    # Footer template
├── single.php                    # Single post template
├── sidebar.php                   # Sidebar template
├── package.json                  # Node.js dependencies
├── webpack.mix.js                # Build configuration
└── tailwind.config.js            # Tailwind CSS configuration
```

## 🛠️ Installation

### Requirements
- PHP 8.1+
- WordPress 6.0+
- Node.js 16+
- MySQL 8.0+

### Quick Setup

1. **Download and Install**
   ```bash
   # Upload theme to WordPress themes directory
   wp-content/themes/aqualuxe/
   ```

2. **Install Dependencies**
   ```bash
   cd wp-content/themes/aqualuxe
   npm install
   ```

3. **Build Assets**
   ```bash
   # Development build
   npm run dev
   
   # Production build
   npm run production
   
   # Watch for changes
   npm run watch
   ```

4. **Activate Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Activate AquaLuxe theme

5. **Import Demo Content** (Optional)
   - Go to Appearance → Demo Importer
   - Select content types to import
   - Click "Import Demo Content"

## ⚙️ Configuration

### Theme Modules
Access module management via **Appearance → Theme Modules**

Available modules:
- 🌙 **Dark Mode**: Toggle between light and dark themes
- 📦 **Demo Importer**: Import/flush demo content
- 🌍 **Multilingual**: Multi-language support
- 💱 **Multi-Currency**: Currency switching
- 🏪 **Multi-Vendor**: Marketplace functionality
- 📅 **Events**: Event management and ticketing
- 📋 **Subscriptions**: Recurring payments
- 🏢 **Wholesale/B2B**: Business-to-business features

### Customizer Settings
Access via **Appearance → Customize**

- **Site Identity**: Logo, colors, typography
- **Dark Mode**: Theme preferences and colors  
- **Layout**: Container width, sidebar settings
- **Colors**: Primary, secondary, accent colors
- **Typography**: Font families and sizes

### WooCommerce Integration
The theme automatically detects WooCommerce and enables:
- Custom product layouts
- Advanced product galleries
- Quick view functionality
- Wishlist integration
- Enhanced checkout process

## 🎨 Design System

### Color Palette
```css
Primary:   #14b8a6 (Teal)
Secondary: #0f766e (Dark Teal)  
Accent:    #eec25a (Gold)
Neutral:   #64748b (Slate)
```

### Typography
- **Headings**: Playfair Display (Serif)
- **Body**: Inter (Sans-serif)
- **Code**: JetBrains Mono (Monospace)

### Components
- Responsive grid system
- Utility-first CSS with Tailwind
- Consistent spacing and typography scale
- Accessible color contrasts
- Smooth animations and transitions

## 🔧 Development

### Build Commands
```bash
# Development build with source maps
npm run dev

# Production build (minified, optimized)
npm run production

# Watch for changes during development
npm run watch

# Hot reload for faster development
npm run hot

# Clean build artifacts
npm run clean
```

### Code Standards
- **PHP**: WordPress Coding Standards, PSR-12
- **JavaScript**: ESLint with WordPress configuration
- **CSS**: Stylelint with standard configuration
- **Accessibility**: WCAG 2.1 AA compliance

### Testing
```bash
# Run JavaScript tests
npm run test

# Run PHP tests (if PHPUnit is configured)
composer test

# Lint code
npm run lint
```

## 🌐 Multi-Language Support

The theme is translation-ready with:
- POT file: `languages/aqualuxe.pot`
- Text domain: `aqualuxe`
- RTL language support
- Multilingual module for advanced features

## 📱 Responsive Design

Breakpoints:
- **xs**: 475px+
- **sm**: 640px+
- **md**: 768px+
- **lg**: 1024px+
- **xl**: 1280px+
- **2xl**: 1536px+
- **3xl**: 1600px+

## 🔒 Security Features

- Input sanitization and validation
- Output escaping
- Nonce verification
- CSRF protection
- Rate limiting
- Security headers
- File upload validation

## ⚡ Performance Optimizations

- Lazy loading for images and content
- Asset minification and compression
- Critical CSS inlining
- Database query optimization
- Caching integration
- CDN support
- Image optimization

## 🛡️ Browser Support

- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)
- iOS Safari (last 2 versions)
- Android Chrome (last 2 versions)

## 📚 Documentation

- **User Guide**: `docs/user-guide.md`
- **Developer Guide**: `docs/developer-guide.md`
- **API Reference**: `docs/api-reference.md`
- **Changelog**: `CHANGELOG.md`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Follow coding standards
4. Add tests for new features
5. Submit a pull request

## 📄 License

This theme is licensed under the GPL v2 or later.

## 🏷️ Version

**Current Version**: 1.0.0  
**WordPress Tested**: 6.4+  
**PHP Version**: 8.1+  
**Last Updated**: 2024

## 📞 Support

For support and documentation:
- **GitHub Issues**: Report bugs and feature requests
- **Documentation**: Check the docs folder
- **Community**: WordPress community forums

---

**AquaLuxe** - *Bringing elegance to aquatic life – globally* 🌊