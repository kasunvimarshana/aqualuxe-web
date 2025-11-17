# AquaLuxe WordPress Theme - Customization Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Customizer Options](#customizer-options)
3. [CSS Customization](#css-customization)
4. [Template Customization](#template-customization)
5. [Hooks & Filters](#hooks-filters)
6. [Child Theme Development](#child-theme-development)
7. [Advanced Customization](#advanced-customization)

## Introduction <a name="introduction"></a>

This guide provides detailed information on how to customize the AquaLuxe WordPress theme to match your brand and requirements. AquaLuxe offers multiple customization options, from simple color and typography changes to advanced template modifications.

## Customizer Options <a name="customizer-options"></a>

The WordPress Customizer is the primary tool for making visual changes to your AquaLuxe theme.

### Accessing the Customizer
1. Log in to your WordPress admin panel
2. Navigate to Appearance > Customize
3. The Customizer will open with a live preview of your site

### Available Customization Sections

#### Site Identity
- **Logo**: Upload your brand logo (recommended size: 250×100px)
- **Site Title**: Set your website name
- **Tagline**: Add a short description
- **Site Icon**: Upload a favicon (must be at least 512×512px)

#### Colors
- **Primary Color**: Main brand color used for buttons, links, and accents
- **Secondary Color**: Supporting color used for hover states and secondary elements
- **Accent Color**: Used for highlighting important elements
- **Background Color**: Site background color
- **Text Color**: Main text color
- **Dark Mode Colors**: Separate color settings for dark mode

Example:
1. Go to Appearance > Customize > Colors
2. Click on "Primary Color"
3. Use the color picker to select your brand color
4. See the live preview update
5. Click "Publish" to save changes

#### Typography
- **Heading Font**: Font family for all headings (H1-H6)
- **Body Font**: Font family for body text
- **Font Sizes**: Control the size of different text elements
- **Line Heights**: Adjust line spacing
- **Font Weights**: Set the weight (boldness) of text

Example:
1. Go to Appearance > Customize > Typography
2. Click on "Heading Font"
3. Choose from the available Google Fonts
4. Adjust size, weight, and line height
5. Click "Publish" to save changes

#### Header Options
- **Header Layout**: Choose from multiple header layouts
  - Standard: Logo left, menu right
  - Centered: Logo centered, menu below
  - Minimal: Simplified header with essential elements
  - Expanded: Full-width header with additional elements
- **Sticky Header**: Enable/disable sticky header on scroll
- **Header Elements**: Show/hide elements like search, cart, account
- **Header Colors**: Customize header background and text colors
- **Transparent Header**: Enable on specific pages with custom colors

Example:
1. Go to Appearance > Customize > Header Options
2. Select "Header Layout"
3. Choose your preferred layout
4. Configure additional options
5. Click "Publish" to save changes

#### Footer Options
- **Footer Layout**: Choose from multiple footer layouts
- **Footer Widgets**: Configure number of widget columns
- **Footer Elements**: Show/hide elements like logo, menu, social icons
- **Footer Colors**: Customize footer background and text colors
- **Copyright Text**: Edit the copyright text in the footer

#### Layout Options
- **Container Width**: Set the maximum width of the content area
- **Sidebar Position**: Choose sidebar location (left, right, none)
- **Content Layout**: Adjust padding and spacing
- **Responsive Breakpoints**: Fine-tune mobile and tablet layouts

#### Blog Options
- **Blog Layout**: Choose from multiple blog layouts
  - Standard: Featured image above content
  - Grid: Multiple columns in a grid
  - List: Compact list view
  - Masonry: Pinterest-style layout
- **Post Meta**: Show/hide author, date, categories, comments
- **Featured Image**: Configure size and position
- **Excerpt Length**: Set the number of words in excerpts
- **Read More Text**: Customize the "Read More" link text

#### WooCommerce Options
- **Shop Layout**: Configure product grid layout
- **Product Page Layout**: Customize single product pages
- **Cart & Checkout**: Adjust cart and checkout layouts
- **Product Elements**: Show/hide price, rating, categories, etc.
- **Product Hover**: Choose hover effect for product images
- **Quick View**: Configure quick view popup
- **Add to Cart Behavior**: Adjust add to cart button behavior

#### Performance Options
- **Lazy Loading**: Enable/disable and configure lazy loading
- **Image Optimization**: Configure WebP and responsive images
- **Asset Loading**: Control CSS and JavaScript optimization
- **Preloading**: Configure resource preloading
- **Critical CSS**: Enable/disable critical CSS extraction

#### SEO Options
- **Schema Markup**: Configure structured data
- **Meta Tags**: Set default meta tags
- **Social Sharing**: Configure Open Graph and Twitter Cards
- **Breadcrumbs**: Enable/disable and configure breadcrumbs
- **XML Sitemap**: Configure sitemap settings

## CSS Customization <a name="css-customization"></a>

AquaLuxe provides multiple ways to add custom CSS:

### Using the Customizer
1. Go to Appearance > Customize
2. Click on "Additional CSS"
3. Add your custom CSS code
4. See the live preview update
5. Click "Publish" to save changes

Example:
```css
/* Change the primary button background color */
.button.button-primary {
    background-color: #ff6b6b;
}

/* Adjust the font size for mobile devices */
@media (max-width: 768px) {
    body {
        font-size: 16px;
    }
}
```

### Using a Child Theme
For more extensive CSS customization, create a child theme:

1. Create a new folder in wp-content/themes/ named "aqualuxe-child"
2. Create a style.css file with the following header:
```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxetheme.com
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com
Template: aqualuxe-theme
Version: 1.0.0
*/

/* Import parent theme styles */
@import url("../aqualuxe-theme/style.css");

/* Add your custom CSS below */
```

3. Create a functions.php file:
```php
<?php
// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-parent-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
```

4. Activate the child theme in Appearance > Themes

### Using CSS Variables
AquaLuxe uses CSS variables for consistent styling. You can override these variables:

```css
:root {
    /* Colors */
    --color-primary: #0073aa;
    --color-secondary: #005177;
    --color-accent: #00a0d2;
    --color-dark: #111111;
    --color-light: #f8f9fa;
    
    /* Typography */
    --font-primary: 'Montserrat', sans-serif;
    --font-secondary: 'Playfair Display', serif;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 2rem;
    --spacing-xl: 4rem;
    
    /* Other */
    --border-radius: 4px;
    --box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
```

## Template Customization <a name="template-customization"></a>

### Template Hierarchy
AquaLuxe follows the WordPress template hierarchy. To customize templates:

1. Create a child theme (as described above)
2. Copy the template file from the parent theme to the child theme
3. Modify the file in the child theme

Example: Customizing the single product template
1. Copy `/aqualuxe-theme/woocommerce/single-product.php` to `/aqualuxe-child/woocommerce/single-product.php`
2. Edit the file in your child theme
3. WordPress will now use your customized version

### Template Parts
For smaller template sections, use template parts:

1. Create a `template-parts` folder in your child theme
2. Copy the template part from the parent theme
3. Modify as needed

Example: Customizing the product card
1. Copy `/aqualuxe-theme/template-parts/content/content-product.php` to `/aqualuxe-child/template-parts/content/content-product.php`
2. Edit the file in your child theme

### Creating Custom Page Templates
1. Create a new PHP file in your child theme
2. Add the template header:
```php
<?php
/**
 * Template Name: My Custom Template
 * Template Post Type: page
 */

get_header();
?>

<!-- Your custom template code here -->

<?php
get_footer();
```

3. The template will now be available in the page editor under "Template"

## Hooks & Filters <a name="hooks-filters"></a>

AquaLuxe provides numerous hooks and filters for customization without editing template files.

### Action Hooks
Add content or functionality at specific points:

```php
// Add content before the product summary
function my_custom_product_content() {
    echo '<div class="custom-product-banner">Special Offer!</div>';
}
add_action('aqualuxe_before_product_summary', 'my_custom_product_content');

// Add content after the header
function my_custom_header_content() {
    echo '<div class="announcement-bar">Free shipping on orders over $50!</div>';
}
add_action('aqualuxe_after_header', 'my_custom_header_content');
```

### Common Action Hooks
- `aqualuxe_before_header` - Before the header
- `aqualuxe_after_header` - After the header
- `aqualuxe_before_footer` - Before the footer
- `aqualuxe_after_footer` - After the footer
- `aqualuxe_before_main_content` - Before the main content
- `aqualuxe_after_main_content` - After the main content
- `aqualuxe_before_sidebar` - Before the sidebar
- `aqualuxe_after_sidebar` - After the sidebar
- `aqualuxe_before_single_product` - Before single product content
- `aqualuxe_after_single_product` - After single product content
- `aqualuxe_before_product_summary` - Before product summary
- `aqualuxe_after_product_summary` - After product summary

### Filter Hooks
Modify data or settings:

```php
// Modify the related products count
function my_custom_related_products_count($count) {
    return 3; // Show only 3 related products
}
add_filter('aqualuxe_related_products_count', 'my_custom_related_products_count');

// Add custom classes to the header
function my_custom_header_classes($classes) {
    $classes[] = 'my-custom-header';
    return $classes;
}
add_filter('aqualuxe_header_classes', 'my_custom_header_classes');
```

### Common Filter Hooks
- `aqualuxe_header_classes` - Header CSS classes
- `aqualuxe_footer_classes` - Footer CSS classes
- `aqualuxe_sidebar_classes` - Sidebar CSS classes
- `aqualuxe_product_classes` - Product CSS classes
- `aqualuxe_related_products` - Related products array
- `aqualuxe_related_products_count` - Number of related products
- `aqualuxe_checkout_fields` - Checkout fields array
- `aqualuxe_breadcrumb_args` - Breadcrumb arguments
- `aqualuxe_schema_data` - Schema.org structured data

## Child Theme Development <a name="child-theme-development"></a>

For extensive customization, a child theme is recommended:

### Basic Child Theme Structure
```
aqualuxe-child/
├── css/
│   └── custom.css
├── js/
│   └── custom.js
├── template-parts/
│   └── (custom template parts)
├── functions.php
├── screenshot.png
└── style.css
```

### functions.php
```php
<?php
/**
 * AquaLuxe Child Theme Functions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-parent-style'));
    
    // Add custom JavaScript
    wp_enqueue_script('aqualuxe-child-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add custom functionality
function aqualuxe_child_setup() {
    // Your custom functionality here
}
add_action('after_setup_theme', 'aqualuxe_child_setup');

// Override parent theme functions
function aqualuxe_related_products_count($count) {
    return 4; // Show 4 related products instead of default
}
add_filter('aqualuxe_related_products_count', 'aqualuxe_related_products_count');
```

### Overriding Template Functions
To override a function from the parent theme:

1. Check if the function exists
2. Create your own version with the same name

```php
// Remove the original function
function remove_parent_functions() {
    remove_action('aqualuxe_before_footer', 'aqualuxe_footer_widgets', 10);
}
add_action('wp_loaded', 'remove_parent_functions');

// Add your custom version
function aqualuxe_footer_widgets() {
    // Your custom footer widgets code
}
add_action('aqualuxe_before_footer', 'aqualuxe_footer_widgets', 10);
```

## Advanced Customization <a name="advanced-customization"></a>

### Customizing WooCommerce Templates
AquaLuxe includes custom WooCommerce templates. To customize:

1. Create a `woocommerce` folder in your child theme
2. Copy the template file from the parent theme
3. Modify as needed

Example: Customizing the product loop
1. Copy `/aqualuxe-theme/woocommerce/content-product.php` to `/aqualuxe-child/woocommerce/content-product.php`
2. Edit the file in your child theme

### Adding Custom Shortcodes
```php
function my_custom_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => 'Default Title',
        'color' => 'blue',
    ), $atts);
    
    $output = '<div class="custom-box" style="color: ' . esc_attr($atts['color']) . '">';
    $output .= '<h3>' . esc_html($atts['title']) . '</h3>';
    $output .= '<div class="content">' . do_shortcode($content) . '</div>';
    $output .= '</div>';
    
    return $output;
}
add_shortcode('custom_box', 'my_custom_shortcode');
```

Usage:
```
[custom_box title="My Box" color="red"]
This is the content of my custom box.
[/custom_box]
```

### Adding Custom Widgets
```php
class My_Custom_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'my_custom_widget',
            __('My Custom Widget', 'aqualuxe-child'),
            array('description' => __('A custom widget for AquaLuxe', 'aqualuxe-child'))
        );
    }
    
    public function widget($args, $instance) {
        // Widget output
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo '<div class="custom-widget-content">' . esc_html($instance['content']) . '</div>';
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        // Widget admin form
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $content = !empty($instance['content']) ? $instance['content'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe-child'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php esc_html_e('Content:', 'aqualuxe-child'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>"><?php echo esc_textarea($content); ?></textarea>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        // Save widget options
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['content'] = (!empty($new_instance['content'])) ? sanitize_textarea_field($new_instance['content']) : '';
        return $instance;
    }
}

// Register the widget
function register_my_custom_widget() {
    register_widget('My_Custom_Widget');
}
add_action('widgets_init', 'register_my_custom_widget');
```

### Creating Custom Blocks
For Gutenberg blocks, use the AquaLuxe block framework:

1. Create a new JS file in your child theme:
```js
// custom-block.js
const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls, ColorPalette } = wp.blockEditor;
const { PanelBody } = wp.components;

registerBlockType('aqualuxe-child/custom-block', {
    title: 'Custom Block',
    icon: 'star',
    category: 'aqualuxe',
    attributes: {
        content: {
            type: 'string',
            default: 'Hello World'
        },
        color: {
            type: 'string',
            default: '#000000'
        }
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        
        return [
            <InspectorControls>
                <PanelBody title="Block Settings">
                    <p>Select a color:</p>
                    <ColorPalette
                        value={attributes.color}
                        onChange={(color) => setAttributes({ color })}
                    />
                </PanelBody>
            </InspectorControls>,
            <div style={{ color: attributes.color }}>
                <RichText
                    tagName="p"
                    value={attributes.content}
                    onChange={(content) => setAttributes({ content })}
                />
            </div>
        ];
    },
    save: function(props) {
        const { attributes } = props;
        
        return (
            <div style={{ color: attributes.color }}>
                <RichText.Content
                    tagName="p"
                    value={attributes.content}
                />
            </div>
        );
    }
});
```

2. Enqueue the script in functions.php:
```php
function aqualuxe_child_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'aqualuxe-child-blocks',
        get_stylesheet_directory_uri() . '/js/custom-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(get_stylesheet_directory() . '/js/custom-block.js')
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_child_enqueue_block_editor_assets');
```

### Customizing the Theme Options Panel
AquaLuxe uses the WordPress Customizer API. To add custom options:

```php
function aqualuxe_child_customize_register($wp_customize) {
    // Add a new section
    $wp_customize->add_section('aqualuxe_child_options', array(
        'title'    => __('Child Theme Options', 'aqualuxe-child'),
        'priority' => 130,
    ));
    
    // Add a setting
    $wp_customize->add_setting('aqualuxe_child_custom_option', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add a control
    $wp_customize->add_control('aqualuxe_child_custom_option', array(
        'label'    => __('Custom Option', 'aqualuxe-child'),
        'section'  => 'aqualuxe_child_options',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'aqualuxe_child_customize_register');
```

To use the custom option in your theme:
```php
$custom_option = get_theme_mod('aqualuxe_child_custom_option', 'default');
echo esc_html($custom_option);
```