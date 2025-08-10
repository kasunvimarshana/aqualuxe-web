# AquaLuxe Child Theme Development Guide

## Introduction

A child theme is the safest and most efficient way to customize your AquaLuxe WordPress theme. Child themes allow you to modify the appearance and functionality of the parent theme without directly editing its files. This ensures that your customizations won't be lost when the parent theme is updated.

This guide will walk you through creating and customizing an AquaLuxe child theme.

## What is a Child Theme?

A child theme is a theme that inherits the functionality and styling of another theme, called the parent theme. The child theme can override specific files and functions of the parent theme, allowing for customization without modifying the parent theme's files.

## Benefits of Using a Child Theme

- **Update Safety**: Your customizations won't be overwritten when the parent theme is updated
- **Reversibility**: You can easily revert to the parent theme if needed
- **Organization**: Keep your custom code separate from the parent theme's code
- **Learning**: Great way to learn theme development without starting from scratch

## Creating an AquaLuxe Child Theme

### Step 1: Create the Child Theme Directory

1. Navigate to your WordPress installation's themes directory: `/wp-content/themes/`
2. Create a new directory for your child theme: `aqualuxe-child`

### Step 2: Create the Style.css File

Create a `style.css` file in your child theme directory with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxetheme.com/
Description: Child theme for the AquaLuxe theme
Author: Your Name
Author URI: https://yourwebsite.com/
Template: aqualuxe-theme
Version: 1.0.0
Text Domain: aqualuxe-child
*/

/* Import parent theme styles */
@import url("../aqualuxe-theme/style.css");

/* Add your custom CSS below this line */
```

The most important line is `Template: aqualuxe-theme`, which tells WordPress that this is a child theme of AquaLuxe.

### Step 3: Create the Functions.php File

Create a `functions.php` file in your child theme directory:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent style
    wp_enqueue_style('aqualuxe-parent-style', 
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('aqualuxe-theme')->get('Version')
    );
    
    // Enqueue child style
    wp_enqueue_style('aqualuxe-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('aqualuxe-parent-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions below this line
```

This function properly enqueues both the parent and child theme stylesheets.

### Step 4: Create a Screenshot (Optional)

Create a `screenshot.png` file for your child theme. The recommended size is 1200×900 pixels.

### Step 5: Activate the Child Theme

1. Log in to your WordPress admin panel
2. Go to Appearance > Themes
3. Find your AquaLuxe Child theme and click "Activate"

## Customizing Your Child Theme

### Overriding Template Files

To override a template file from the parent theme:

1. Locate the file you want to override in the parent theme directory
2. Copy the file to your child theme directory, maintaining the same directory structure
3. Modify the copied file as needed

For example, to override the `header.php` file:

1. Copy `/wp-content/themes/aqualuxe-theme/header.php`
2. Paste it to `/wp-content/themes/aqualuxe-child/header.php`
3. Edit the child theme version of the file

### Overriding Template Parts

AquaLuxe uses template parts for modular components. To override a template part:

1. Create a `templates` directory in your child theme if it doesn't exist
2. Create a `parts` subdirectory inside the `templates` directory
3. Copy the template part file from the parent theme to this directory
4. Modify as needed

For example, to override the hero section:

1. Copy `/wp-content/themes/aqualuxe-theme/templates/parts/hero.php`
2. Paste it to `/wp-content/themes/aqualuxe-child/templates/parts/hero.php`
3. Edit the child theme version

### Adding Custom CSS

The simplest way to add custom CSS is by editing your child theme's `style.css` file. Add your custom styles after the `@import` statement.

For more organized CSS, you can:

1. Create a `css` directory in your child theme
2. Create CSS files for specific components (e.g., `header.css`, `footer.css`)
3. Enqueue these files in your `functions.php`:

```php
function aqualuxe_child_enqueue_custom_css() {
    wp_enqueue_style('aqualuxe-child-header', 
        get_stylesheet_directory_uri() . '/css/header.css',
        array('aqualuxe-child-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_custom_css', 20);
```

### Adding Custom JavaScript

To add custom JavaScript:

1. Create a `js` directory in your child theme
2. Create your JavaScript files
3. Enqueue them in your `functions.php`:

```php
function aqualuxe_child_enqueue_scripts() {
    wp_enqueue_script('aqualuxe-child-custom', 
        get_stylesheet_directory_uri() . '/js/custom.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_scripts');
```

### Modifying Theme Functions

To modify or extend the parent theme's functionality:

1. Use WordPress action and filter hooks in your child theme's `functions.php`
2. Avoid directly overriding parent theme functions unless necessary

Example of using a filter to modify the excerpt length:

```php
function aqualuxe_child_custom_excerpt_length($length) {
    return 30; // Change excerpt length to 30 words
}
add_filter('excerpt_length', 'aqualuxe_child_custom_excerpt_length');
```

## Advanced Customization

### Creating Custom Page Templates

To create a custom page template:

1. Create a file in your child theme directory with a name like `template-custom.php`
2. Add the template information at the top:

```php
<?php
/**
 * Template Name: Custom Template
 * Description: A custom page template for specific layouts
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Your custom template code here -->
</main>

<?php
get_footer();
```

3. The template will now be available in the Page Attributes section when editing a page

### Customizing WooCommerce Templates

To override WooCommerce templates:

1. Create a `woocommerce` directory in your child theme
2. Copy the template files from `/wp-content/plugins/woocommerce/templates/` or `/wp-content/themes/aqualuxe-theme/woocommerce/`
3. Maintain the same directory structure within your child theme's `woocommerce` directory
4. Modify the templates as needed

### Adding Custom Shortcodes

You can create custom shortcodes in your child theme's `functions.php`:

```php
function aqualuxe_child_custom_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'color' => 'blue',
    ), $atts);
    
    return '<div class="custom-element" style="color:' . esc_attr($atts['color']) . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('custom_element', 'aqualuxe_child_custom_shortcode');
```

Usage: `[custom_element color="red"]Content here[/custom_element]`

### Adding Custom Widget Areas

To add a new widget area:

```php
function aqualuxe_child_widgets_init() {
    register_sidebar(array(
        'name'          => __('Custom Sidebar', 'aqualuxe-child'),
        'id'            => 'custom-sidebar',
        'description'   => __('Add widgets here to appear in your custom sidebar.', 'aqualuxe-child'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'aqualuxe_child_widgets_init');
```

## Best Practices

### Code Organization

- Keep your child theme organized with clear directory structure
- Use comments to document your code
- Follow WordPress coding standards

### Performance Considerations

- Minimize the number of CSS and JavaScript files
- Optimize images before adding them to your theme
- Use WordPress built-in functions when possible
- Consider using a build process for production (e.g., minification)

### Security Best Practices

- Validate and sanitize all user inputs
- Escape output when displaying data
- Use WordPress nonces for form submissions
- Keep your child theme updated

### Internationalization

Make your child theme translation-ready:

1. Use translation functions for all text strings:
   - `__()` for simple strings
   - `_e()` to echo strings
   - `esc_html__()` for escaped strings
   - `esc_html_e()` to echo escaped strings

2. Include the text domain in all translations:

```php
$title = esc_html__('My Custom Title', 'aqualuxe-child');
```

## Troubleshooting Common Issues

### Styles Not Loading

- Check that the parent theme name in `style.css` matches exactly: `Template: aqualuxe-theme`
- Verify that your `functions.php` is properly enqueuing styles
- Check for PHP errors in your browser console or error logs

### Template Files Not Overriding

- Ensure the file path and name exactly match the parent theme's structure
- Check file permissions (should be readable)
- Clear any caching plugins

### JavaScript Errors

- Check the browser console for errors
- Verify that dependencies are properly listed in your enqueue function
- Test disabling other plugins to check for conflicts

## Resources

- [WordPress Child Theme Documentation](https://developer.wordpress.org/themes/advanced-topics/child-themes/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [AquaLuxe Theme Documentation](https://aqualuxetheme.com/documentation/)
- [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)

## Conclusion

Creating a child theme for AquaLuxe allows you to customize the theme while maintaining the ability to update the parent theme. By following this guide, you can create a professional, customized website that meets your specific needs while building on the solid foundation that AquaLuxe provides.

Remember that the AquaLuxe support team is always available to help if you encounter any issues with your child theme development. Happy customizing!