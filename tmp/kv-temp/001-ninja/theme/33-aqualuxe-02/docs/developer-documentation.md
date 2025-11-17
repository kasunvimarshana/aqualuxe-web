# AquaLuxe WordPress Theme - Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Build Process](#build-process)
4. [Theme Customization](#theme-customization)
5. [Hooks and Filters](#hooks-and-filters)
6. [Template Hierarchy](#template-hierarchy)
7. [Function Reference](#function-reference)
8. [Accessibility Features](#accessibility-features)
9. [WooCommerce Integration](#woocommerce-integration)
10. [Performance Optimization](#performance-optimization)
11. [Contributing](#contributing)

## Introduction

AquaLuxe is a modern, accessible WordPress theme designed for aquarium-related businesses, marine life enthusiasts, and underwater photography portfolios. This documentation provides technical details for developers who want to customize or extend the theme.

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (if using e-commerce features)

### Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Theme Structure

The AquaLuxe theme follows a modular structure with clear separation of concerns:

```
aqualuxe/
├── assets/
│   ├── css/           # Compiled CSS files
│   ├── fonts/         # Font files
│   ├── images/        # Image assets
│   ├── js/            # Compiled JavaScript files
│   └── src/           # Source files
│       ├── fonts/     # Font source files
│       ├── images/    # Image source files
│       ├── js/        # JavaScript source files
│       ├── scss/      # SCSS source files
│       └── vendor/    # Third-party libraries
├── docs/              # Documentation files
├── inc/               # Theme PHP includes
│   ├── core/          # Core theme functionality
│   ├── customizer/    # Customizer settings
│   ├── helpers/       # Helper functions
│   └── woocommerce/   # WooCommerce integration
├── languages/         # Translation files
├── template-parts/    # Template partials
│   ├── content/       # Content templates
│   ├── footer/        # Footer templates
│   ├── header/        # Header templates
│   └── navigation/    # Navigation templates
└── woocommerce/       # WooCommerce template overrides
```

## Build Process

AquaLuxe uses Laravel Mix (webpack wrapper) for asset compilation and optimization.

### Setup

1. Install Node.js and npm
2. Navigate to the theme directory
3. Run `npm install` to install dependencies

### Available Commands

- `npm run dev`: Compile assets for development
- `npm run watch`: Compile assets and watch for changes
- `npm run hot`: Compile assets with hot module replacement
- `npm run prod`: Compile and optimize assets for production
- `npm run critical`: Generate critical CSS
- `npm run svg`: Generate SVG sprite
- `npm run imagemin`: Optimize images

### Configuration

The build process is configured in `webpack.mix.js`. Key features include:

- SCSS compilation with autoprefixer
- JavaScript bundling and minification
- SVG sprite generation
- Critical CSS generation
- Image optimization
- Cache busting

## Theme Customization

### Customizer Options

AquaLuxe provides extensive customization options through the WordPress Customizer:

- **Site Identity**: Logo, site title, tagline, favicon
- **Colors**: Primary, secondary, accent, background, text
- **Typography**: Font families, sizes, weights
- **Layout**: Container width, sidebar position, content layout
- **Header**: Header style, sticky header, transparent header
- **Footer**: Footer columns, widgets, copyright text
- **Blog**: Post layout, featured image, meta information
- **WooCommerce**: Shop layout, product cards, cart, checkout

### Adding Custom Customizer Settings

To add custom Customizer settings, use the `aqualuxe_customize_register` hook:

```php
function my_custom_customizer_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('my_custom_section', array(
        'title'    => __('My Custom Section', 'aqualuxe'),
        'priority' => 130,
    ));

    // Add setting
    $wp_customize->add_setting('my_custom_setting', array(
        'default'           => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add control
    $wp_customize->add_control('my_custom_setting', array(
        'label'    => __('My Custom Setting', 'aqualuxe'),
        'section'  => 'my_custom_section',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'my_custom_customizer_settings');
```

## Hooks and Filters

AquaLuxe provides a variety of hooks and filters to modify theme behavior without editing core files.

### Action Hooks

#### Header Hooks
- `aqualuxe_before_header`: Fires before the header
- `aqualuxe_after_header`: Fires after the header
- `aqualuxe_header_content`: Fires inside the header container

#### Content Hooks
- `aqualuxe_before_content`: Fires before the main content
- `aqualuxe_after_content`: Fires after the main content
- `aqualuxe_before_page_content`: Fires before page content
- `aqualuxe_after_page_content`: Fires after page content
- `aqualuxe_before_post_content`: Fires before post content
- `aqualuxe_after_post_content`: Fires after post content

#### Footer Hooks
- `aqualuxe_before_footer`: Fires before the footer
- `aqualuxe_after_footer`: Fires after the footer
- `aqualuxe_footer_content`: Fires inside the footer container

#### Example Usage
```php
function my_custom_header_content() {
    echo '<div class="announcement">Special offer this week!</div>';
}
add_action('aqualuxe_header_content', 'my_custom_header_content');
```

### Filters

- `aqualuxe_body_classes`: Filter body classes
- `aqualuxe_post_classes`: Filter post classes
- `aqualuxe_excerpt_length`: Filter excerpt length
- `aqualuxe_excerpt_more`: Filter excerpt "more" text
- `aqualuxe_comment_form_args`: Filter comment form arguments
- `aqualuxe_related_posts_args`: Filter related posts query arguments
- `aqualuxe_social_sharing_networks`: Filter social sharing networks

#### Example Usage
```php
function my_custom_excerpt_length($length) {
    return 30; // Change excerpt length to 30 words
}
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length');
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some additional templates:

- `front-page.php`: Front page template
- `home.php`: Blog posts index
- `single.php`: Single post
- `page.php`: Single page
- `archive.php`: Archive page
- `search.php`: Search results
- `404.php`: 404 page
- `singular.php`: Fallback for single posts and pages

### Custom Templates

- `template-full-width.php`: Full-width page template
- `template-no-sidebar.php`: Page template without sidebar
- `template-contact.php`: Contact page template
- `template-about.php`: About page template
- `template-portfolio.php`: Portfolio page template

### Template Parts

Template parts are reusable components stored in the `template-parts` directory:

- `template-parts/content/content.php`: Default content template
- `template-parts/content/content-page.php`: Page content template
- `template-parts/content/content-single.php`: Single post content template
- `template-parts/content/content-none.php`: No content found template
- `template-parts/header/header.php`: Header template
- `template-parts/footer/footer.php`: Footer template
- `template-parts/navigation/navigation-main.php`: Main navigation template

## Function Reference

### Core Functions

- `aqualuxe_setup()`: Sets up theme defaults and registers support for various WordPress features
- `aqualuxe_scripts()`: Enqueues scripts and styles
- `aqualuxe_widgets_init()`: Registers widget areas
- `aqualuxe_content_width()`: Sets the content width

### Helper Functions

- `aqualuxe_get_theme_option($option, $default = '')`: Get theme option from Customizer
- `aqualuxe_get_image_url($attachment_id, $size = 'full')`: Get image URL by ID
- `aqualuxe_get_svg_icon($icon, $args = array())`: Get SVG icon
- `aqualuxe_get_post_views($post_id)`: Get post views count
- `aqualuxe_get_post_reading_time($post_id)`: Get post reading time
- `aqualuxe_get_related_posts($post_id, $number = 3, $args = array())`: Get related posts
- `aqualuxe_get_social_sharing($post_id)`: Get social sharing buttons
- `aqualuxe_get_social_media_links()`: Get social media links
- `aqualuxe_get_contact_info()`: Get contact information
- `aqualuxe_get_copyright_text()`: Get copyright text

### Accessibility Functions

- `aqualuxe_menu_aria_attributes($atts, $item, $args, $depth)`: Add ARIA attributes to menu items
- `aqualuxe_social_icon_accessibility($html, $network)`: Add screen reader text to social icons
- `aqualuxe_pagination_accessibility($template)`: Add ARIA attributes to pagination links
- `aqualuxe_comment_form_accessibility($args)`: Add ARIA attributes to comment form
- `aqualuxe_search_form_accessibility($form)`: Add ARIA attributes to search form
- `aqualuxe_icon_accessibility($icon, $icon_name, $label = '')`: Add screen reader text to icons
- `aqualuxe_get_dark_mode_class()`: Get dark mode class for HTML tag
- `aqualuxe_dark_mode_toggle()`: Add dark mode toggle button
- `aqualuxe_back_to_top()`: Add back to top button

## Accessibility Features

AquaLuxe is built with accessibility in mind, following WCAG 2.1 AA guidelines:

### Keyboard Navigation

- Skip links for main content areas
- Focus trapping for modals
- Keyboard navigation for dropdown menus
- Tab order follows logical flow
- Visible focus styles

### Screen Reader Support

- ARIA attributes for interactive elements
- Screen reader text for icons and visual elements
- Proper heading hierarchy
- Proper alt text for images
- Announcements for dynamic content

### Color Contrast

- All text meets WCAG AA contrast requirements
- High contrast mode option
- Dark mode with proper contrast

### Forms

- Proper labels for form fields
- Error messages with screen reader announcements
- Required field indicators
- Form validation with screen reader announcements

### Interactive Elements

- ARIA attributes for modals, accordions, tabs
- Focus management for dynamic content
- Keyboard shortcuts for modal dismissal
- Accessible tooltips

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides custom styling and functionality:

### Template Overrides

The theme includes custom WooCommerce templates in the `woocommerce` directory:

- `woocommerce/single-product.php`: Single product template
- `woocommerce/archive-product.php`: Product archive template
- `woocommerce/content-product.php`: Product content template
- `woocommerce/cart/cart.php`: Cart template
- `woocommerce/checkout/form-checkout.php`: Checkout form template

### Custom Functionality

- AJAX cart updates
- Quick view modal
- Product image zoom
- Product gallery slider
- Product filtering
- Wishlist integration
- Product comparison

### Adding Custom WooCommerce Features

To add custom WooCommerce features, use the WooCommerce hooks:

```php
function my_custom_product_tab($tabs) {
    $tabs['custom_tab'] = array(
        'title'    => __('Custom Tab', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'my_custom_product_tab_content',
    );
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'my_custom_product_tab');

function my_custom_product_tab_content() {
    echo '<p>Custom tab content here.</p>';
}
```

## Performance Optimization

AquaLuxe includes several performance optimizations:

### Critical CSS

Critical CSS is generated for above-the-fold content to improve page load speed. The critical CSS is inlined in the `<head>` section of the page.

To regenerate critical CSS:

```bash
npm run critical
```

### Lazy Loading

Images and iframes are lazy loaded to improve page load speed. The theme uses the native `loading="lazy"` attribute for images and a custom JavaScript implementation for other elements.

### Asset Optimization

Assets are optimized for production:

- CSS is minified and autoprefixed
- JavaScript is bundled and minified
- Images are optimized
- SVGs are compiled into a sprite
- Fonts are subset and optimized

### Caching

The theme includes cache busting for assets to ensure users always get the latest version:

```php
wp_enqueue_style('aqualuxe-style', AQUALUXE_ASSETS_URI . 'css/main.css', array(), AQUALUXE_VERSION);
```

## Contributing

We welcome contributions to AquaLuxe! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Make your changes
4. Run tests: `npm run test`
5. Commit your changes: `git commit -m 'Add my feature'`
6. Push to the branch: `git push origin feature/my-feature`
7. Submit a pull request

### Coding Standards

AquaLuxe follows the WordPress coding standards:

- PHP: [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- CSS: [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- JavaScript: [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- HTML: [WordPress HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/)

### Testing

Before submitting a pull request, please test your changes:

- Test on multiple browsers (Chrome, Firefox, Safari, Edge)
- Test on multiple devices (desktop, tablet, mobile)
- Test with different WordPress configurations
- Test with different plugins activated
- Test with different content types