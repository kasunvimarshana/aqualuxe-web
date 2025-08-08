# AquaLuxe Theme Documentation

## Overview

AquaLuxe is a premium WooCommerce child theme designed specifically for ornamental fish businesses. Built on the robust Storefront parent theme, it provides a beautiful, responsive, and fully customizable e-commerce solution.

## Features

### Design & Layout
- Fully responsive design that works on all devices
- Clean, modern aesthetic with aquatic-inspired elements
- Custom color schemes and typography options
- Sticky header for improved navigation
- Grid-based product layouts

### WooCommerce Integration
- Complete compatibility with all WooCommerce features
- Support for variable, grouped, and external products
- AJAX add to cart functionality
- Product quick view feature
- Custom product gallery display
- Enhanced cart and checkout experience

### Performance & SEO
- Optimized for fast loading times
- Semantic HTML5 markup
- Schema.org structured data
- Open Graph meta tags
- Mobile-first indexing ready
- Lazy loading for images

### Accessibility
- WCAG 2.0 compliant
- ARIA attributes for screen readers
- Keyboard navigation support
- Proper heading structure
- Color contrast compliance

### Customization
- Extensive options in the WordPress Customizer
- Custom widgets for enhanced functionality
- Template override system
- Child theme ready for further customization

## Installation

See [INSTALL.md](INSTALL.md) for detailed installation instructions.

## Customization Options

### Customizer Settings

Access the customizer by going to "Appearance > Customize" in your WordPress admin.

#### Site Identity
- Logo upload
- Site title and tagline
- Site icon

#### Colors
- Primary color
- Secondary color
- Background color
- Text color

#### Typography
- Body font family
- Heading font family
- Font sizes

#### Header Options
- Sticky header enable/disable
- Header background color

#### WooCommerce Options
- Product grid settings
- Button styles
- Sale badge customization

### Child Theme Modifications

As a child theme, AquaLuxe can be easily customized:

1. Add custom CSS to `style.css`
2. Add custom functions to `functions.php`
3. Override template files by copying them from the parent theme

## Template Structure

```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── custom.css
│   │   ├── woocommerce.css
│   │   └── editor-style.css
│   └── js/
│       ├── custom.js
│       ├── woocommerce.js
│       └── customizer.js
├── inc/
│   ├── theme-setup.php
│   ├── enqueue-scripts.php
│   ├── customizer.php
│   ├── woocommerce.php
│   ├── seo-optimizations.php
│   ├── accessibility.php
│   └── demo-content.php
├── template-parts/
│   ├── content.php
│   ├── content-page.php
│   ├── content-search.php
│   └── content-none.php
├── woocommerce/
│   ├── archive-product.php
│   ├── single-product.php
│   ├── cart/
│   │   └── cart.php
│   ├── checkout/
│   │   └── form-checkout.php
│   ├── myaccount/
│   │   └── my-account.php
│   └── content-quick-view.php
├── functions.php
├── style.css
├── index.php
├── header.php
├── footer.php
├── sidebar.php
├── page.php
├── single.php
├── archive.php
├── search.php
├── 404.php
├── screenshot.png
├── README.md
├── CHANGELOG.md
├── LICENSE
├── INSTALL.md
└── DOCUMENTATION.md
```

## Functions & Hooks

### Theme Functions

The theme includes several custom functions:

- `aqualuxe_theme_setup()` - Initializes theme features
- `aqualuxe_enqueue_scripts()` - Enqueues CSS and JavaScript
- `aqualuxe_customize_register()` - Registers customizer settings
- `aqualuxe_sanitize_checkbox()` - Sanitizes checkbox inputs

### WooCommerce Functions

- `aqualuxe_woocommerce_setup()` - Initializes WooCommerce support
- `aqualuxe_ajax_add_to_cart()` - Handles AJAX add to cart
- `aqualuxe_quick_view()` - Handles product quick view AJAX requests

### Hooks & Filters

The theme provides several hooks for customization:

- `aqualuxe_before_header` - Before header content
- `aqualuxe_after_header` - After header content
- `aqualuxe_before_footer` - Before footer content
- `aqualuxe_after_footer` - After footer content

## Performance Optimizations

### Asset Minification
All CSS and JavaScript files are minified for optimal performance.

### Lazy Loading
Images are lazy loaded to improve initial page load times.

### Caching
The theme is optimized for compatibility with WordPress caching plugins.

## SEO Features

### Schema Markup
- Product schema for WooCommerce products
- Organization schema for the business
- Breadcrumb schema for navigation

### Open Graph
- Proper meta tags for social sharing
- Custom images for social media previews

### Structured Data
- JSON-LD structured data implementation
- Rich snippets for search engines

## Accessibility Features

### ARIA Attributes
- Proper landmark roles
- Descriptive labels for interactive elements
- Live regions for dynamic content

### Keyboard Navigation
- Full keyboard operability
- Visible focus indicators
- Skip links for screen readers

### Semantic HTML
- Proper heading hierarchy
- Meaningful element selection
- Logical content structure

## Demo Content

The theme includes a one-click demo content importer:

1. Go to "Appearance > Import Demo Content"
2. Click "Import Demo Content"
3. Wait for the import process to complete

This will import:
- Sample pages (Home, About, Contact)
- Sample products
- Menu structure
- Widget settings

## Support

For support, please refer to the following resources:

1. [Documentation](DOCUMENTATION.md)
2. [Installation Guide](INSTALL.md)
3. [Changelog](CHANGELOG.md)
4. Theme repository issues

## Troubleshooting

### Common Issues

1. **Theme not activating**: Ensure Storefront is installed and activated first
2. **Missing styles**: Check that all files were uploaded correctly
3. **WooCommerce compatibility**: Ensure you're using compatible versions

### Debugging Tips

1. Check for PHP errors in your server logs
2. Verify file permissions (folders should be 755, files 644)
3. Ensure all required plugins are installed and activated

## Updates

To update the theme:

1. Download the latest version
2. Backup your current theme files
3. Replace the theme files via FTP or your hosting file manager
4. Check for any breaking changes in the changelog

**Note**: Always backup your site before performing updates.

## Contributing

We welcome contributions to the AquaLuxe theme:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a pull request

Please ensure your code follows WordPress coding standards and includes proper documentation.