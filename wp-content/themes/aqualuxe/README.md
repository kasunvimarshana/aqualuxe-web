# AquaLuxe WordPress Theme

> **Bringing elegance to aquatic life – globally**

A luxury aquatic lifestyle WordPress theme featuring modular architecture, WooCommerce integration, and stunning underwater aesthetics. Built for performance, accessibility, and scalability.

## 🌊 Features

### Core Features
- **Modular Architecture**: Clean separation between core functionality and optional modules
- **WooCommerce Integration**: Dual-state architecture works with or without WooCommerce
- **Dark/Light Mode**: System-aware theme switching with user preference persistence
- **Multilingual Ready**: Full translation support with WPML compatibility
- **Multicurrency Support**: WooCommerce currency switcher integration
- **Mobile-First Design**: Fully responsive across all devices
- **Performance Optimized**: Lazy loading, critical CSS, asset optimization
- **SEO Enhanced**: Structured data, meta tags, XML sitemaps
- **Security Hardened**: CSRF protection, input sanitization, login attempt limiting
- **Accessibility Compliant**: WCAG 2.1 AA standards

### Design Features
- **Luxury Aquatic Aesthetics**: Ocean-inspired color palette and typography
- **Animated Elements**: Smooth water-like animations and transitions
- **Custom Components**: Carousels, modals, tooltips, and interactive elements
- **Flexible Layouts**: Multiple page templates and customizable sections
- **Typography**: Beautiful font pairings with Playfair Display and Inter

### Technical Features
- **Modern Build Process**: Laravel Mix + Webpack for asset compilation
- **Tailwind CSS**: Utility-first CSS framework with custom configurations
- **Custom Post Types**: Extensible content management
- **Widget Areas**: Multiple sidebar and footer widget zones
- **Customizer Integration**: Live preview theme options
- **Child Theme Support**: Safe customization workflow

## 🚀 Quick Start

### Requirements
- WordPress 6.0+
- PHP 8.0+
- Node.js 16+ (for development)
- Composer (for dependencies)

### Installation

1. **Download the theme:**
   ```bash
   git clone https://github.com/aqualuxe/aqualuxe-theme.git
   cd aqualuxe-theme
   ```

2. **Install dependencies:**
   ```bash
   npm install
   composer install
   ```

3. **Build assets:**
   ```bash
   # Development
   npm run dev
   
   # Production
   npm run build
   
   # Watch mode
   npm run watch
   ```

4. **Upload to WordPress:**
   - Copy the theme folder to `/wp-content/themes/`
   - Activate in WordPress Admin → Appearance → Themes

### First Setup

1. **Configure basic settings:**
   - Go to Appearance → Customize
   - Set up site logo and colors
   - Configure header and footer options

2. **Set up menus:**
   - Go to Appearance → Menus
   - Create Primary and Footer menus
   - Assign to theme locations

3. **Install recommended plugins:**
   - WooCommerce (for e-commerce features)
   - Contact Form 7 (for contact forms)
   - Yoast SEO (for enhanced SEO)

## 🏗 Architecture

### Directory Structure
```
aqualuxe/
├── assets/
│   ├── src/               # Source files
│   │   ├── js/           # JavaScript source
│   │   ├── scss/         # SCSS source
│   │   └── images/       # Image assets
│   └── dist/             # Compiled assets
├── inc/                  # Core theme classes
│   ├── class-asset-manager.php
│   ├── class-theme-setup.php
│   ├── class-customizer.php
│   ├── class-woocommerce-integration.php
│   ├── class-security.php
│   ├── class-performance.php
│   ├── class-seo.php
│   ├── class-helper-functions.php
│   └── class-template-functions.php
├── modules/              # Optional feature modules
│   ├── dark-mode/
│   ├── social-sharing/
│   ├── newsletter/
│   └── analytics/
├── template-parts/       # Reusable template components
├── languages/           # Translation files
├── functions.php        # Main theme controller
├── style.css           # Theme metadata
└── README.md
```

### Module System

The theme uses a modular architecture where features are organized as self-contained modules:

```php
// Loading a module
aqualuxe_load_module('dark-mode');

// Checking if module is active
if (aqualuxe_is_module_active('woocommerce-integration')) {
    // Module-specific code
}
```

### Core Classes

- **Asset_Manager**: Handles asset enqueuing and optimization
- **Theme_Setup**: WordPress theme feature registration
- **Customizer**: Theme customization options
- **WooCommerce_Integration**: E-commerce functionality
- **Security**: Security hardening measures
- **Performance**: Performance optimization
- **SEO**: Search engine optimization
- **Helper_Functions**: Utility functions
- **Template_Functions**: Template-specific functions

## 🎨 Customization

### Using the Customizer

Access via Appearance → Customize:

1. **Site Identity**: Logo, title, tagline
2. **Colors**: Primary, secondary, accent colors
3. **Typography**: Font selections and sizes
4. **Header**: Layout options and navigation
5. **Footer**: Widget areas and copyright
6. **Dark Mode**: Toggle and preferences
7. **WooCommerce**: Shop layout and colors

### Custom CSS

Add custom styles via:
- Appearance → Customize → Additional CSS
- Child theme's style.css
- Custom CSS module

### Hooks and Filters

The theme provides numerous hooks for customization:

```php
// Action hooks
do_action('aqualuxe_before_header');
do_action('aqualuxe_after_content');
do_action('aqualuxe_footer_widgets');

// Filter hooks
apply_filters('aqualuxe_nav_menu_args', $args);
apply_filters('aqualuxe_post_meta_fields', $fields);
apply_filters('aqualuxe_customizer_sections', $sections);
```

### Creating Child Themes

1. Create new directory: `/wp-content/themes/aqualuxe-child/`
2. Add `style.css`:
   ```css
   /*
   Theme Name: AquaLuxe Child
   Template: aqualuxe
   */
   
   @import url("../aqualuxe/style.css");
   
   /* Your custom styles here */
   ```
3. Add `functions.php`:
   ```php
   <?php
   function aqualuxe_child_enqueue_styles() {
       wp_enqueue_style('aqualuxe-child-style',
           get_stylesheet_directory_uri() . '/style.css',
           array('aqualuxe-style'),
           wp_get_theme()->get('Version')
       );
   }
   add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
   ```

## 🛠 Development

### Build System

The theme uses Laravel Mix for asset compilation:

```javascript
// webpack.mix.js
const mix = require('laravel-mix');

mix.js('assets/src/js/main.js', 'assets/dist/js')
   .sass('assets/src/scss/main.scss', 'assets/dist/css')
   .options({
       processCssUrls: false,
       postCss: [require('tailwindcss')]
   });
```

### Available Scripts

```bash
# Development build with source maps
npm run dev

# Production build with optimization
npm run build

# Watch for changes and rebuild
npm run watch

# Hot reload development server
npm run hot

# Lint JavaScript
npm run lint:js

# Lint SCSS
npm run lint:scss

# Run tests
npm run test
```

### Coding Standards

- **PHP**: WordPress Coding Standards
- **JavaScript**: ESLint with WordPress configuration
- **CSS**: Stylelint with standard configuration
- **Accessibility**: WCAG 2.1 AA compliance

### Testing

```bash
# PHP Unit tests
composer test

# JavaScript tests
npm test

# Accessibility testing
npm run test:a11y

# Performance testing
npm run test:perf
```

## 🔧 Configuration

### Environment Variables

Create `.env` file in theme root:

```env
# Development settings
WP_DEBUG=true
SCRIPT_DEBUG=true

# API Keys
GOOGLE_ANALYTICS_ID=UA-XXXXXXXX-X
MAILCHIMP_API_KEY=your-api-key

# Feature flags
ENABLE_DARK_MODE=true
ENABLE_ANIMATIONS=true
```

### Theme Options

Key theme options available via Customizer:

```php
// Color scheme
get_theme_mod('aqualuxe_primary_color', '#0ea5e9')
get_theme_mod('aqualuxe_secondary_color', '#06b6d4')

// Typography
get_theme_mod('aqualuxe_heading_font', 'Playfair Display')
get_theme_mod('aqualuxe_body_font', 'Inter')

// Layout
get_theme_mod('aqualuxe_container_width', '1280px')
get_theme_mod('aqualuxe_sidebar_position', 'right')

// Features
get_theme_mod('aqualuxe_dark_mode_enabled', true)
get_theme_mod('aqualuxe_animations_enabled', true)
```

## 🌐 Internationalization

### Translation Support

The theme is fully translatable:

1. **Text Domain**: `aqualuxe`
2. **POT File**: `/languages/aqualuxe.pot`
3. **Translation Function Usage**:
   ```php
   esc_html__('Text', 'aqualuxe')
   esc_html_e('Text', 'aqualuxe')
   esc_attr__('Text', 'aqualuxe')
   _n('Singular', 'Plural', $count, 'aqualuxe')
   ```

### Adding Translations

1. Generate POT file:
   ```bash
   wp i18n make-pot . languages/aqualuxe.pot
   ```

2. Create language files:
   - `languages/aqualuxe-es_ES.po`
   - `languages/aqualuxe-fr_FR.po`
   - etc.

3. Compile to MO files:
   ```bash
   wp i18n make-mo languages/
   ```

### RTL Support

The theme includes RTL language support:
- Automatic RTL detection
- RTL-specific stylesheets
- Proper text direction handling

## 🔌 Plugin Compatibility

### Recommended Plugins

- **WooCommerce**: Full e-commerce integration
- **Contact Form 7**: Contact form compatibility
- **Yoast SEO**: Enhanced SEO features
- **WPML**: Multilingual support
- **WP Rocket**: Caching optimization
- **Wordfence**: Security enhancement

### Plugin Integration

The theme provides integration hooks for popular plugins:

```php
// WooCommerce integration
if (aqualuxe_is_woocommerce_active()) {
    // WooCommerce-specific features
}

// Contact Form 7 styling
add_filter('wpcf7_form_class_attr', 'aqualuxe_cf7_form_class');

// Yoast SEO breadcrumbs
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<nav class="breadcrumbs">', '</nav>');
}
```

## 📊 Performance

### Optimization Features

- **Critical CSS**: Above-the-fold styles inlined
- **Lazy Loading**: Images and iframes loaded on demand
- **Asset Minification**: CSS and JS compressed for production
- **Cache Headers**: Proper browser caching configuration
- **Database Optimization**: Efficient queries and caching

### Performance Metrics

Target performance benchmarks:
- **Lighthouse Score**: 90+ across all metrics
- **Page Load Time**: < 3 seconds
- **First Contentful Paint**: < 1.5 seconds
- **Time to Interactive**: < 3.5 seconds

### Monitoring

Use these tools to monitor performance:
- Google PageSpeed Insights
- GTmetrix
- Pingdom
- WebPageTest

## 🔒 Security

### Security Features

- **Input Sanitization**: All user inputs properly sanitized
- **CSRF Protection**: Nonce verification for forms
- **SQL Injection Prevention**: Prepared statements used
- **XSS Protection**: Output properly escaped
- **File Upload Security**: Restricted file types and validation
- **Login Security**: Rate limiting and strong password enforcement

### Security Best Practices

```php
// Sanitize input
$clean_input = sanitize_text_field($_POST['input']);

// Validate nonce
if (!wp_verify_nonce($_POST['nonce'], 'action_name')) {
    wp_die('Security check failed');
}

// Escape output
echo esc_html($user_content);
echo esc_url($url);
echo esc_attr($attribute);
```

## 🐛 Troubleshooting

### Common Issues

**Build Process Fails:**
```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

**Styles Not Loading:**
- Check if assets are built: `npm run build`
- Verify file permissions
- Clear browser cache
- Check for PHP errors in wp-config.php

**JavaScript Errors:**
- Enable WordPress debug mode
- Check browser console for errors
- Verify jQuery is loaded
- Check for plugin conflicts

**Performance Issues:**
- Optimize images
- Enable caching
- Minify assets: `npm run build`
- Check database queries

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### Getting Help

1. **Documentation**: Check theme documentation
2. **Support Forum**: Visit support forums
3. **GitHub Issues**: Report bugs on GitHub
4. **Premium Support**: Available for premium users

## 📝 Changelog

### Version 1.0.0 (2024-01-XX)
- Initial release
- Modular architecture implementation
- WooCommerce integration
- Dark mode functionality
- Performance optimizations
- Security hardening
- Accessibility improvements
- SEO enhancements

### Development Roadmap

**Version 1.1.0 (Planned)**
- Advanced customizer options
- Additional modules
- Enhanced WooCommerce features
- More animation options

**Version 1.2.0 (Planned)**
- Block editor support
- Site editor compatibility
- Enhanced performance
- Additional integrations

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### Development Setup

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

### Code Standards

- Follow WordPress coding standards
- Add proper documentation
- Include unit tests
- Update README if needed

## 📄 License

This theme is licensed under the [GPL v3 or later](LICENSE).

```
AquaLuxe WordPress Theme
Copyright (C) 2024 AquaLuxe Team

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 📞 Support

- **Documentation**: [https://aqualuxetheme.com/docs](https://aqualuxetheme.com/docs)
- **Support Forum**: [https://aqualuxetheme.com/support](https://aqualuxetheme.com/support)
- **Email**: support@aqualuxetheme.com
- **GitHub**: [https://github.com/aqualuxe/aqualuxe-theme](https://github.com/aqualuxe/aqualuxe-theme)

---

**AquaLuxe Theme** - Bringing elegance to aquatic life – globally 🌊
