"# AquaLuxe WordPress Theme

**"Bringing elegance to aquatic life – globally"**

A premium, modular WordPress theme designed for aquatic businesses, featuring comprehensive WooCommerce integration, responsive design, and advanced customization capabilities.

## 🎯 Overview

AquaLuxe is a production-ready WordPress theme built with modern development practices, featuring:

- **Modular Architecture**: Clean separation of core functionality and feature modules
- **WooCommerce Ready**: Full e-commerce integration with graceful fallbacks
- **Mobile-First Design**: Responsive layouts optimized for all devices
- **Performance Optimized**: Asset compilation, lazy loading, and caching support
- **Accessibility Compliant**: WCAG 2.1 AA standards with ARIA roles
- **SEO Optimized**: Schema.org markup, Open Graph, and meta tag management
- **Developer Friendly**: Modern build tools, coding standards, and extensible architecture

## 🚀 Features

### Core Functionality
- **Dark Mode Support**: Persistent preferences with system detection
- **Custom Post Types**: Services, Events, Team, Testimonials, Portfolio, FAQ
- **Demo Content Importer**: Advanced importer with progress tracking and reset capabilities
- **Theme Customizer**: Comprehensive customization options for colors, typography, and layout
- **Multilingual Ready**: Translation support with text domain
- **Advanced Navigation**: Mobile-friendly menus with dropdown support

### WooCommerce Integration
- **Product Management**: Support for all product types (simple, variable, grouped, digital)
- **Quick View**: AJAX-powered product quick view modals
- **Wishlist Functionality**: User-specific wishlist management
- **Advanced Cart**: Dynamic cart updates and enhanced checkout experience
- **Custom Product Fields**: Aquarium-specific metadata and care instructions

### Design & UX
- **Aquatic Theme**: Elegant design reflecting the brand identity
- **Responsive Grid**: CSS Grid and Flexbox layouts
- **Smooth Animations**: CSS transitions and JavaScript-powered interactions
- **Typography System**: Carefully selected font pairings with Google Fonts integration
- **Color System**: Comprehensive color palette with dark mode variants

## 🛠 Installation

### Requirements
- WordPress 5.0 or higher
- PHP 7.4 or higher
- Node.js 14+ (for development)
- WooCommerce 5.0+ (optional, for e-commerce features)

### Quick Start

1. **Download and Install**
   ```bash
   # Upload theme files to /wp-content/themes/aqualuxe/
   # Or install via WordPress admin
   ```

2. **Activate Theme**
   - Go to Appearance > Themes in WordPress admin
   - Activate AquaLuxe theme

3. **Install Dependencies** (for development)
   ```bash
   cd /path/to/theme/
   npm install
   ```

4. **Build Assets**
   ```bash
   # Development
   npm run dev
   
   # Production
   npm run build
   
   # Watch for changes
   npm run watch
   ```

5. **Import Demo Content** (optional)
   - Go to Appearance > Demo Importer
   - Select content to import
   - Run the import process

## 📁 File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source files
│   │   ├── css/               # SCSS files
│   │   ├── js/                # JavaScript files
│   │   ├── images/            # Source images
│   │   └── fonts/             # Font files
│   └── dist/                  # Compiled assets
├── core/                      # Core theme classes
├── inc/                       # Theme functions and features
├── modules/                   # Feature modules
│   ├── dark-mode/            # Dark mode functionality
│   ├── demo-importer/        # Demo content importer
│   └── custom-post-types/    # Custom post types
├── templates/                 # Page templates
├── woocommerce/              # WooCommerce template overrides
├── languages/                # Translation files
├── *.php                     # Core template files
├── style.css                 # Theme header and fallback styles
├── package.json              # NPM dependencies
├── webpack.mix.js            # Build configuration
└── tailwind.config.js        # Tailwind CSS configuration
```

## 🎨 Customization

### Theme Customizer Options

Access via **Appearance > Customize**:

- **Colors**: Primary, secondary, and accent colors
- **Typography**: Font selection and sizing
- **Layout**: Container width and sidebar position
- **Dark Mode**: Enable/disable dark mode features
- **Hero Section**: Homepage hero content and imagery

### Custom Post Types

The theme includes several custom post types:

- **Services**: Business services with pricing and duration
- **Events**: Calendar events with dates and locations
- **Team**: Team member profiles with contact information
- **Testimonials**: Customer testimonials with ratings
- **Portfolio**: Project showcases with categories
- **FAQ**: Frequently asked questions with categories

### Modular Architecture

Features are organized as independent modules in `/modules/`:

```php
// Example: Adding a new module
class AquaLuxe_New_Feature {
    public function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Add your hooks here
    }
}
```

## 🔧 Development

### Build System

The theme uses Laravel Mix for asset compilation:

```javascript
// webpack.mix.js
mix.js('assets/src/js/app.js', 'js')
   .sass('assets/src/css/app.scss', 'css')
   .options({
       postCss: [
           require('tailwindcss'),
           require('autoprefixer'),
       ]
   });
```

### CSS Architecture

SCSS files are organized by purpose:

- `components/`: Reusable UI components
- `theme/`: Theme-specific styles
- `wordpress/`: WordPress-specific styles
- `woocommerce/`: WooCommerce styles

### JavaScript Structure

JavaScript is organized using modern ES6+ modules with Alpine.js for interactivity.

### Coding Standards

- **PHP**: WordPress Coding Standards
- **CSS**: BEM methodology with Tailwind CSS utilities
- **JavaScript**: ES6+ with async/await patterns
- **Comments**: Comprehensive inline documentation

## 🌐 WooCommerce Integration

### Product Features
- Custom product galleries with zoom and lightbox
- Advanced product filtering and search
- Related products and upsells
- Product variations and attributes
- Custom product fields for aquatic care information

### Shop Features
- Responsive product grids
- AJAX cart functionality
- Wishlist management
- Quick view modals
- Advanced checkout with custom fields

### Compatibility
- Works with or without WooCommerce
- Graceful fallbacks for missing features
- Compatible with popular WooCommerce extensions

## 🎯 SEO & Performance

### SEO Features
- Schema.org structured data
- Open Graph meta tags
- Optimized title tags and meta descriptions
- XML sitemap compatibility
- Breadcrumb navigation

### Performance Optimizations
- Lazy loading for images
- Minified and concatenated assets
- Critical CSS inlining
- Image optimization
- Caching-friendly structure

## ♿ Accessibility

The theme follows WCAG 2.1 AA guidelines:

- Semantic HTML5 structure
- ARIA labels and roles
- Keyboard navigation support
- High contrast colors
- Screen reader compatibility
- Focus management

## 🌍 Internationalization

- Translation-ready with proper text domains
- RTL language support
- Date and number formatting
- Currency support for international sales

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11 (graceful degradation)

## 🔒 Security

- Input sanitization and validation
- Nonce verification for forms
- CSRF protection
- XSS prevention
- SQL injection protection
- Secure coding practices

## 📞 Support & Documentation

### Getting Help
- Theme documentation: Available in the theme package
- Support forum: Contact theme developer
- Video tutorials: Step-by-step guides for common tasks

### Customization Services
Professional customization and development services available for:
- Custom feature development
- Design modifications
- Performance optimization
- Third-party integrations

## 📄 License

This theme is licensed under the GNU General Public License v2 or later.

## 🎉 Credits

- **Theme Development**: Kasun Vimarshana
- **Framework**: WordPress, WooCommerce
- **CSS Framework**: Tailwind CSS
- **Build Tools**: Laravel Mix, Webpack
- **JavaScript**: Alpine.js, Swiper.js
- **Icons**: Heroicons
- **Fonts**: Google Fonts

---

**AquaLuxe** - Bringing elegance to aquatic life, globally. 🐠✨" 
