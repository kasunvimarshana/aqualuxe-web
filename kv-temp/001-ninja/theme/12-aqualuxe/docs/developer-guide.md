# AquaLuxe Developer Guide

This guide provides information for developers who want to customize or extend the AquaLuxe WordPress theme.

## Theme Architecture

AquaLuxe follows an object-oriented approach with a modular structure to make the code more maintainable and extensible.

### Core Files

- `functions.php`: The main theme file that initializes the theme and includes all necessary files.
- `class-aqualuxe-core.php`: The core class that sets up theme features, registers menus, sidebars, etc.
- `template-functions.php`: Contains template-related functions used throughout the theme.
- `template-hooks.php`: Contains action and filter hooks for theme customization.

### Directory Structure

- `assets/`: Contains compiled CSS, JavaScript, fonts, and images.
- `inc/`: Contains PHP files for theme functionality.
  - `customizer/`: Contains customizer-related files.
  - `widgets/`: Contains custom widget classes.
- `src/`: Contains source files for development.
  - `scss/`: Contains SCSS files organized by functionality.
  - `js/`: Contains JavaScript source files.
- `template-parts/`: Contains reusable template parts.
- `templates/`: Contains page templates.
- `woocommerce/`: Contains WooCommerce template overrides.

## Development Setup

### Prerequisites

- Node.js (v14 or higher)
- npm or yarn
- Gulp CLI

### Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/aqualuxe.git
```

2. Navigate to the theme directory:
```bash
cd aqualuxe
```

3. Install dependencies:
```bash
npm install
```

4. Run development server:
```bash
npm start
```

This will start the Gulp tasks for compiling SCSS, transpiling JavaScript, and watching for changes.

### Build for Production

To build assets for production:

```bash
npm run build
```

This will create minified CSS and JavaScript files in the `assets` directory.

## Customization

### Adding Custom Styles

To add custom styles, create a new SCSS file in the appropriate directory under `src/scss/` and import it in `src/scss/main.scss`.

For example, to add styles for a new component:

1. Create `src/scss/components/_my-component.scss`
2. Add your styles to this file
3. Import it in `src/scss/main.scss`:
```scss
@import 'components/my-component';
```

### Adding Custom JavaScript

To add custom JavaScript:

1. Create a new file in `src/js/`
2. Add your code to this file
3. Import it in `src/js/main.js` or add it to the Gulp configuration to be compiled separately

### Adding Custom Templates

To add a custom page template:

1. Create a new file in the `templates/` directory, e.g., `templates/template-custom.php`
2. Add the template header:
```php
<?php
/**
 * Template Name: My Custom Template
 *
 * @package AquaLuxe
 */
```
3. Add your template code

### Adding Custom Post Types

The theme includes a post type registration system. To add a new custom post type:

1. Open `inc/post-types.php`
2. Add your custom post type registration to the `register_post_types()` function:
```php
register_post_type('my_post_type', [
    'labels' => [
        'name' => __('My Post Type', 'aqualuxe'),
        // Add other labels
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail'],
    'menu_icon' => 'dashicons-admin-post',
    // Add other arguments
]);
```

### Adding Custom Taxonomies

To add a new taxonomy:

1. Open `inc/post-types.php`
2. Add your taxonomy registration to the `register_taxonomies()` function:
```php
register_taxonomy('my_taxonomy', 'my_post_type', [
    'labels' => [
        'name' => __('My Taxonomy', 'aqualuxe'),
        // Add other labels
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    // Add other arguments
]);
```

### Adding Theme Options

The theme uses the WordPress Customizer for theme options. To add new options:

1. Open `inc/customizer/class-aqualuxe-customizer.php`
2. Add your customizer section, settings, and controls:
```php
// Add section
$wp_customize->add_section('my_section', [
    'title' => __('My Section', 'aqualuxe'),
    'priority' => 30,
]);

// Add setting
$wp_customize->add_setting('my_setting', [
    'default' => 'default_value',
    'sanitize_callback' => 'sanitize_text_field',
]);

// Add control
$wp_customize->add_control('my_setting', [
    'label' => __('My Setting', 'aqualuxe'),
    'section' => 'my_section',
    'type' => 'text',
]);
```

## Hooks and Filters

AquaLuxe provides various hooks and filters to customize theme functionality without modifying core files.

### Action Hooks

- `aqualuxe_before_header`: Fires before the header content
- `aqualuxe_after_header`: Fires after the header content
- `aqualuxe_before_footer`: Fires before the footer content
- `aqualuxe_after_footer`: Fires after the footer content
- `aqualuxe_before_main_content`: Fires before the main content
- `aqualuxe_after_main_content`: Fires after the main content
- `aqualuxe_sidebar`: Fires to output the sidebar

Example usage:
```php
add_action('aqualuxe_before_header', 'my_custom_function');
function my_custom_function() {
    echo '<div class="announcement">Special offer today!</div>';
}
```

### Filter Hooks

- `aqualuxe_excerpt_length`: Filters the excerpt length
- `aqualuxe_excerpt_more`: Filters the excerpt "more" text
- `aqualuxe_comment_form_args`: Filters the comment form arguments
- `aqualuxe_related_posts_args`: Filters the related posts query arguments

Example usage:
```php
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length');
function my_custom_excerpt_length($length) {
    return 30; // Change excerpt length to 30 words
}
```

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and includes custom styling and functionality.

### WooCommerce Template Overrides

The theme includes template overrides for WooCommerce in the `woocommerce/` directory. To customize a WooCommerce template:

1. Identify the template you want to override in the WooCommerce plugin
2. Create the same file structure in the `woocommerce/` directory
3. Copy the template from WooCommerce and modify it as needed

### WooCommerce Hooks

The theme adds custom hooks for WooCommerce in `inc/woocommerce.php`. You can add or remove functionality using these hooks.

Example:
```php
// Remove the default WooCommerce sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Add a custom sidebar
add_action('woocommerce_sidebar', 'my_custom_sidebar', 10);
function my_custom_sidebar() {
    get_sidebar('shop');
}
```

## Performance Optimization

AquaLuxe is optimized for performance with the following features:

- Minified CSS and JavaScript
- Deferred loading of non-critical JavaScript
- Lazy loading of images
- Responsive images

To further optimize performance:

1. Use a caching plugin like WP Super Cache or W3 Total Cache
2. Use a CDN for assets
3. Optimize images before uploading
4. Use a quality hosting provider

## Troubleshooting

### Common Issues

1. **Styles not updating**: Clear your browser cache or try a hard refresh (Ctrl+F5)
2. **JavaScript errors**: Check the browser console for errors and ensure jQuery is loaded
3. **Plugin conflicts**: Disable plugins one by one to identify conflicts
4. **Customizer settings not saving**: Check for JavaScript errors in the console

### Debug Mode

To enable debug mode, add the following to your `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

This will log errors to `wp-content/debug.log` without displaying them on the site.

## Support

For additional support, please contact:

- Email: support@aqualuxe.com
- Website: https://aqualuxe.com/support