# AquaLuxe WordPress Theme - Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Development Setup](#development-setup)
4. [Theme Hooks](#theme-hooks)
5. [Template Hierarchy](#template-hierarchy)
6. [Custom Templates](#custom-templates)
7. [Theme Functions](#theme-functions)
8. [Customizer API](#customizer-api)
9. [WooCommerce Integration](#woocommerce-integration)
10. [Testing Tools](#testing-tools)
11. [Multilingual Support](#multilingual-support)
12. [Advanced Features](#advanced-features)
13. [Contributing](#contributing)

## Introduction

AquaLuxe is a premium WordPress theme built for modern e-commerce websites. This developer documentation provides comprehensive information about the theme's architecture, features, and customization options.

### Theme Features

- Fully responsive design optimized for all devices
- WooCommerce integration with custom templates and features
- Advanced customizer options for easy customization
- Built with Tailwind CSS for modern styling
- Dark mode toggle with user preference persistence
- Multilingual support with WPML and Polylang compatibility
- Schema.org markup for improved SEO
- Comprehensive testing tools for quality assurance
- Multiple page templates for different use cases
- Performance optimized for fast loading times

## Theme Structure

The AquaLuxe theme follows a modular structure to keep code organized and maintainable:

```
aqualuxe/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
├── docs/
│   ├── developer-guide.md
│   └── user-guide.md
├── inc/
│   ├── classes/
│   ├── customizer/
│   ├── helpers/
│   ├── testing/
│   └── woocommerce/
├── template-parts/
│   ├── content/
│   ├── footer/
│   ├── header/
│   └── navigation/
├── templates/
│   ├── template-homepage.php
│   ├── template-about.php
│   └── ...
├── woocommerce/
│   ├── cart/
│   ├── checkout/
│   ├── myaccount/
│   └── ...
├── functions.php
├── index.php
├── header.php
├── footer.php
├── sidebar.php
├── style.css
└── ...
```

### Key Directories

- **assets/**: Contains all static assets like CSS, JavaScript, images, and fonts
- **docs/**: Documentation files
- **inc/**: PHP includes for theme functionality
- **template-parts/**: Reusable template parts organized by section
- **templates/**: Custom page templates
- **woocommerce/**: WooCommerce template overrides

## Development Setup

### Requirements

- WordPress 5.8+
- PHP 7.4+
- Node.js 14+
- npm 6+
- WooCommerce 6.0+ (for e-commerce functionality)

### Installation for Development

1. Clone the repository to your WordPress themes directory:
   ```bash
   git clone https://github.com/yourusername/aqualuxe.git wp-content/themes/aqualuxe
   ```

2. Navigate to the theme directory:
   ```bash
   cd wp-content/themes/aqualuxe
   ```

3. Install npm dependencies:
   ```bash
   npm install
   ```

4. Compile assets:
   ```bash
   npm run dev
   ```

5. For production builds:
   ```bash
   npm run build
   ```

### Development Commands

- `npm run dev`: Compiles assets for development
- `npm run watch`: Watches for changes and recompiles
- `npm run hot`: Hot module replacement for development
- `npm run build`: Compiles and optimizes assets for production
- `npm run lint`: Lints JavaScript and CSS files

## Theme Hooks

AquaLuxe uses a comprehensive hook system to allow for easy customization without modifying core theme files. The hooks are defined in `inc/hooks.php`.

### Main Template Hooks

```php
// Header hooks
do_action('aqualuxe_before_header');
do_action('aqualuxe_header');
do_action('aqualuxe_after_header');

// Content hooks
do_action('aqualuxe_before_main_content');
do_action('aqualuxe_main_content');
do_action('aqualuxe_after_main_content');

// Footer hooks
do_action('aqualuxe_before_footer');
do_action('aqualuxe_footer');
do_action('aqualuxe_after_footer');
```

### Example Usage

```php
// Add custom content to header
add_action('aqualuxe_after_header', 'my_custom_header_content');
function my_custom_header_content() {
    echo '<div class="custom-header-content">Custom content here</div>';
}
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some custom templates:

- `index.php`: Main template file
- `singular.php`: Used for single posts and pages
- `archive.php`: Used for archive pages
- `search.php`: Used for search results
- `404.php`: Used for 404 error pages

Custom templates are located in the `templates/` directory and can be selected in the WordPress page editor.

## Custom Templates

AquaLuxe includes several custom page templates:

- `template-homepage.php`: Homepage template with sections for featured products, categories, and promotions
- `template-about.php`: About page template with team members and company information sections
- `template-contact.php`: Contact page template with contact form and map
- `template-faq.php`: FAQ page template with accordion sections
- `template-services.php`: Services page template with service listings
- `template-privacy-policy.php`: Privacy policy page template
- `template-terms.php`: Terms and conditions page template
- `template-cookie-policy.php`: Cookie policy page template
- `template-gdpr.php`: GDPR compliance page template
- `template-shipping-returns.php`: Shipping and returns policy page template

## Theme Functions

### Helper Functions

AquaLuxe provides several helper functions to simplify theme development:

```php
// Get theme option with default fallback
aqualuxe_get_option($option_name, $default = '');

// Get header layout
aqualuxe_get_header_layout();

// Get footer layout
aqualuxe_get_footer_layout();

// Check if sidebar should be displayed
aqualuxe_has_sidebar();

// Get breadcrumbs
aqualuxe_breadcrumbs();

// Get social media links
aqualuxe_social_links();
```

### Template Functions

Template functions are located in `inc/template-functions.php` and provide functionality for templates:

```php
// Display post meta (date, author, categories)
aqualuxe_post_meta();

// Display post thumbnail with size options
aqualuxe_post_thumbnail($size = 'large');

// Display pagination
aqualuxe_pagination();

// Display related posts
aqualuxe_related_posts($post_id, $count = 3);
```

## Customizer API

The theme uses the WordPress Customizer API for theme options. The customizer settings are defined in `inc/customizer/customizer.php`.

### Customizer Sections

- **General Settings**: Basic theme settings
- **Header Settings**: Header layout and options
- **Footer Settings**: Footer layout and options
- **Typography**: Font family and size options
- **Colors**: Color scheme options
- **Blog Settings**: Blog layout and display options
- **WooCommerce Settings**: E-commerce specific options
- **Social Media**: Social media profile links

### Adding Custom Customizer Settings

```php
add_action('customize_register', 'my_custom_customizer_settings');
function my_custom_customizer_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('my_custom_section', array(
        'title' => __('My Custom Settings', 'aqualuxe'),
        'priority' => 120,
    ));
    
    // Add setting
    $wp_customize->add_setting('my_custom_setting', array(
        'default' => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add control
    $wp_customize->add_control('my_custom_setting', array(
        'label' => __('My Custom Setting', 'aqualuxe'),
        'section' => 'my_custom_section',
        'type' => 'text',
    ));
}
```

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and includes custom templates and functionality.

### WooCommerce Templates

The theme overrides several WooCommerce templates to match the theme design:

- `content-product.php`: Product loop item template
- `content-single-product.php`: Single product template
- `archive-product.php`: Product archive template
- `cart/cart.php`: Cart page template
- `checkout/form-checkout.php`: Checkout form template
- `myaccount/dashboard.php`: Account dashboard template

### WooCommerce Features

- **Quick View**: Product quick view modal
- **Wishlist**: Product wishlist functionality
- **Advanced Filtering**: AJAX product filtering
- **Multi-Currency Support**: Support for multiple currencies

### Adding Custom WooCommerce Functionality

```php
// Add custom tab to single product page
add_filter('woocommerce_product_tabs', 'my_custom_product_tab');
function my_custom_product_tab($tabs) {
    $tabs['custom_tab'] = array(
        'title' => __('Custom Tab', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'my_custom_product_tab_content'
    );
    return $tabs;
}

function my_custom_product_tab_content() {
    echo '<h2>Custom Tab Content</h2>';
    echo '<p>This is custom tab content.</p>';
}
```

## Testing Tools

AquaLuxe includes comprehensive testing tools to ensure theme quality and compatibility.

### Available Testing Tools

- **WooCommerce Test**: Tests WooCommerce integration in active and inactive states
- **Responsive Test**: Tests theme appearance on different device sizes
- **Performance Test**: Tests and optimizes theme performance
- **Accessibility Test**: Tests theme accessibility compliance
- **SEO Test**: Tests and optimizes theme SEO

### Using Testing Tools

1. Navigate to the WordPress admin area
2. Go to "AquaLuxe Testing" in the admin menu
3. Use the testing dashboard to run tests and view results
4. Individual test pages are available under the "Tools" menu

## Multilingual Support

AquaLuxe is compatible with popular translation plugins:

- **WPML**: Full compatibility with WPML plugin
- **Polylang**: Full compatibility with Polylang plugin
- **Translation Ready**: Includes .pot file for translations

### Translation Files

- `languages/aqualuxe.pot`: Template file for translations
- `languages/aqualuxe-{locale}.po`: Translation file for specific locale
- `languages/aqualuxe-{locale}.mo`: Compiled translation file

## Advanced Features

### Dark Mode

AquaLuxe includes a dark mode toggle with user preference persistence:

```php
// Check if dark mode is enabled
if (aqualuxe_is_dark_mode()) {
    // Dark mode specific code
}

// Toggle dark mode
<button id="dark-mode-toggle" class="dark-mode-toggle">
    <?php aqualuxe_dark_mode_toggle_icon(); ?>
</button>
```

### Schema.org Markup

The theme includes schema.org markup for better SEO:

- Article schema for blog posts
- Product schema for WooCommerce products
- BreadcrumbList schema for breadcrumbs
- WebPage schema for pages

### Open Graph Metadata

Open Graph tags are included for better social media sharing:

```php
<meta property="og:title" content="<?php echo esc_attr(aqualuxe_get_og_title()); ?>">
<meta property="og:description" content="<?php echo esc_attr(aqualuxe_get_og_description()); ?>">
<meta property="og:url" content="<?php echo esc_url(aqualuxe_get_current_url()); ?>">
<meta property="og:type" content="<?php echo esc_attr(aqualuxe_get_og_type()); ?>">
<meta property="og:image" content="<?php echo esc_url(aqualuxe_get_og_image()); ?>">
```

## Contributing

We welcome contributions to the AquaLuxe theme. Please follow these guidelines:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Make your changes
4. Run tests: `npm run test`
5. Commit your changes: `git commit -m 'Add feature'`
6. Push to the branch: `git push origin feature-name`
7. Submit a pull request

### Coding Standards

- Follow WordPress coding standards
- Use meaningful variable and function names
- Add comments for complex code sections
- Write unit tests for new features

## Support

For developer support, please contact:

- Email: support@aqualuxetheme.com
- GitHub Issues: https://github.com/yourusername/aqualuxe/issues