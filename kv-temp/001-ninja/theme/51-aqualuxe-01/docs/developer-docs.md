# AquaLuxe Theme Developer Documentation

This documentation is intended for developers who want to customize or extend the AquaLuxe theme. It provides an overview of the theme's architecture, hooks, filters, and customization options.

## Table of Contents

1. [Theme Architecture](#theme-architecture)
2. [File Structure](#file-structure)
3. [Theme Class](#theme-class)
4. [Hooks and Filters](#hooks-and-filters)
5. [Template Hierarchy](#template-hierarchy)
6. [Asset Management](#asset-management)
7. [WooCommerce Integration](#woocommerce-integration)
8. [Dark Mode Implementation](#dark-mode-implementation)
9. [Multilingual Support](#multilingual-support)
10. [Multi-currency Support](#multi-currency-support)
11. [Multivendor Support](#multivendor-support)
12. [Multitenant Architecture](#multitenant-architecture)
13. [Creating a Child Theme](#creating-a-child-theme)
14. [Extending the Theme](#extending-the-theme)

## Theme Architecture

AquaLuxe follows a modern, object-oriented architecture with a focus on modularity, extensibility, and performance. The theme is built using the singleton pattern for the main theme class and uses a component-based approach for different features.

### Key Principles

- **Modularity**: Each feature is encapsulated in its own module
- **Extensibility**: Extensive use of hooks and filters for easy customization
- **Performance**: Optimized code and assets for fast loading
- **Maintainability**: Clean, well-documented code following WordPress coding standards
- **Compatibility**: Works with or without WooCommerce

## File Structure

```
aqualuxe-theme/
├── assets/
│   ├── dist/            # Compiled assets
│   │   ├── css/
│   │   ├── js/
│   │   ├── fonts/
│   │   └── images/
│   └── src/             # Source assets
│       ├── css/
│       ├── js/
│       ├── scss/
│       ├── fonts/
│       └── images/
├── docs/                # Documentation
├── inc/                 # Theme includes
│   ├── customizer.php
│   ├── dark-mode.php
│   ├── demo-importer.php
│   ├── helpers.php
│   ├── multi-currency.php
│   ├── multilingual.php
│   ├── multitenant.php
│   ├── template-functions.php
│   ├── template-hooks.php
│   ├── template-tags.php
│   └── woocommerce/     # WooCommerce specific files
│       ├── woocommerce.php
│       ├── woocommerce-template-hooks.php
│       ├── woocommerce-template-functions.php
│       ├── class-aqualuxe-woocommerce.php
│       ├── wishlist.php
│       ├── quick-view.php
│       └── advanced-filters.php
├── languages/           # Translation files
├── template-parts/      # Template parts
│   ├── content-none.php
│   ├── content-page.php
│   ├── content-search.php
│   ├── content-single.php
│   └── content.php
├── templates/           # Page templates
│   ├── template-about.php
│   ├── template-contact.php
│   ├── template-faq.php
│   ├── template-homepage.php
│   └── template-services.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── README.md
├── screenshot.png
├── search.php
├── sidebar-shop.php
├── sidebar.php
├── single.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

## Theme Class

The main theme class `AquaLuxe_Theme` is implemented as a singleton and is responsible for initializing all theme functionality. It's defined in `functions.php`.

```php
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Is WooCommerce active
     *
     * @var bool
     */
    public $is_woocommerce_active = false;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Theme
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if WooCommerce is active
        $this->is_woocommerce_active = class_exists('WooCommerce');

        // Load theme files
        $this->load_dependencies();

        // Setup theme
        add_action('after_setup_theme', array($this, 'setup'));

        // Register assets
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));

        // Register widget areas
        add_action('widgets_init', array($this, 'register_sidebars'));

        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));
    }

    // Other methods...
}
```

To access the theme instance:

```php
$theme = AquaLuxe_Theme::instance();
```

## Hooks and Filters

AquaLuxe provides numerous hooks and filters for customization. Here are the main ones:

### Action Hooks

#### Header Hooks

- `aqualuxe_before_header`: Fires before the header
- `aqualuxe_header_start`: Fires at the start of the header
- `aqualuxe_header_content`: Fires in the middle of the header
- `aqualuxe_header_end`: Fires at the end of the header
- `aqualuxe_after_header`: Fires after the header

#### Footer Hooks

- `aqualuxe_before_footer`: Fires before the footer
- `aqualuxe_footer_start`: Fires at the start of the footer
- `aqualuxe_footer_widgets`: Fires in the footer widgets area
- `aqualuxe_footer_content`: Fires in the middle of the footer
- `aqualuxe_footer_end`: Fires at the end of the footer
- `aqualuxe_after_footer`: Fires after the footer

#### Content Hooks

- `aqualuxe_before_content`: Fires before the main content
- `aqualuxe_content_start`: Fires at the start of the main content
- `aqualuxe_content_end`: Fires at the end of the main content
- `aqualuxe_after_content`: Fires after the main content

#### Post Hooks

- `aqualuxe_before_post`: Fires before each post
- `aqualuxe_post_start`: Fires at the start of each post
- `aqualuxe_post_header`: Fires in the post header
- `aqualuxe_post_content`: Fires in the post content
- `aqualuxe_post_footer`: Fires in the post footer
- `aqualuxe_post_end`: Fires at the end of each post
- `aqualuxe_after_post`: Fires after each post

#### Page Hooks

- `aqualuxe_before_page`: Fires before each page
- `aqualuxe_page_start`: Fires at the start of each page
- `aqualuxe_page_header`: Fires in the page header
- `aqualuxe_page_content`: Fires in the page content
- `aqualuxe_page_footer`: Fires in the page footer
- `aqualuxe_page_end`: Fires at the end of each page
- `aqualuxe_after_page`: Fires after each page

#### Sidebar Hooks

- `aqualuxe_before_sidebar`: Fires before the sidebar
- `aqualuxe_sidebar_start`: Fires at the start of the sidebar
- `aqualuxe_sidebar_end`: Fires at the end of the sidebar
- `aqualuxe_after_sidebar`: Fires after the sidebar

#### WooCommerce Hooks

- `aqualuxe_before_shop`: Fires before the shop content
- `aqualuxe_shop_start`: Fires at the start of the shop content
- `aqualuxe_shop_end`: Fires at the end of the shop content
- `aqualuxe_after_shop`: Fires after the shop content
- `aqualuxe_before_product`: Fires before each product
- `aqualuxe_product_start`: Fires at the start of each product
- `aqualuxe_product_end`: Fires at the end of each product
- `aqualuxe_after_product`: Fires after each product

### Filter Hooks

- `aqualuxe_body_classes`: Filter the body classes
- `aqualuxe_post_classes`: Filter the post classes
- `aqualuxe_comment_form_args`: Filter the comment form arguments
- `aqualuxe_excerpt_length`: Filter the excerpt length
- `aqualuxe_excerpt_more`: Filter the excerpt more string
- `aqualuxe_content_width`: Filter the content width
- `aqualuxe_sidebar_position`: Filter the sidebar position
- `aqualuxe_page_layout`: Filter the page layout
- `aqualuxe_post_layout`: Filter the post layout
- `aqualuxe_shop_layout`: Filter the shop layout
- `aqualuxe_product_layout`: Filter the product layout

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some enhancements:

- `index.php`: Main template file
- `front-page.php`: Front page template
- `home.php`: Home page template
- `single.php`: Single post template
- `page.php`: Page template
- `archive.php`: Archive template
- `search.php`: Search results template
- `404.php`: 404 page template

### Custom Templates

AquaLuxe includes several custom page templates:

- `templates/template-homepage.php`: Homepage template
- `templates/template-about.php`: About page template
- `templates/template-contact.php`: Contact page template
- `templates/template-faq.php`: FAQ page template
- `templates/template-services.php`: Services page template

### Template Parts

Template parts are reusable components that can be included in multiple templates:

- `template-parts/content.php`: Default content template
- `template-parts/content-none.php`: No content found template
- `template-parts/content-page.php`: Page content template
- `template-parts/content-search.php`: Search results content template
- `template-parts/content-single.php`: Single post content template

## Asset Management

AquaLuxe uses Laravel Mix (webpack wrapper) for asset compilation. The configuration is in `webpack.mix.js`.

### CSS Architecture

The CSS is organized using the SCSS preprocessor with a modular architecture:

- `abstracts/`: Variables, mixins, functions, and breakpoints
- `base/`: Reset, typography, and animations
- `components/`: Buttons, forms, cards, etc.
- `layout/`: Header, footer, grid, etc.
- `pages/`: Page-specific styles
- `utilities/`: Utility classes

### JavaScript Architecture

The JavaScript is organized into modules:

- `main.js`: Main JavaScript file
- `dark-mode.js`: Dark mode functionality
- `woocommerce.js`: WooCommerce specific functionality
- `navigation.js`: Navigation functionality

### Enqueuing Assets

Assets are enqueued in the `register_assets` method of the `AquaLuxe_Theme` class:

```php
public function register_assets() {
    // Get the mix manifest file
    $mix_manifest = $this->get_mix_manifest();

    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-styles', 
        AQUALUXE_ASSETS_URI . 'css/main' . $this->get_asset_version('css/main.css', $mix_manifest) . '.css', 
        array(), 
        AQUALUXE_VERSION
    );

    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-scripts', 
        AQUALUXE_ASSETS_URI . 'js/main' . $this->get_asset_version('js/main.js', $mix_manifest) . '.js', 
        array('jquery'), 
        AQUALUXE_VERSION, 
        true
    );

    // Add dark mode script
    wp_enqueue_script(
        'aqualuxe-dark-mode', 
        AQUALUXE_ASSETS_URI . 'js/dark-mode' . $this->get_asset_version('js/dark-mode.js', $mix_manifest) . '.js', 
        array('jquery'), 
        AQUALUXE_VERSION, 
        true
    );

    // Localize script for AJAX and translations
    wp_localize_script('aqualuxe-scripts', 'aqualuxeSettings', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-nonce'),
        'isWooCommerceActive' => $this->is_woocommerce_active,
        'themeUri' => AQUALUXE_URI,
        'i18n' => array(
            'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
            'viewCart' => esc_html__('View cart', 'aqualuxe'),
            'addToWishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
            'removeFromWishlist' => esc_html__('Remove from wishlist', 'aqualuxe'),
            'quickView' => esc_html__('Quick view', 'aqualuxe'),
            'loadMore' => esc_html__('Load more', 'aqualuxe'),
            'noMoreProducts' => esc_html__('No more products to load', 'aqualuxe'),
            'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
            'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
        ),
    ));

    // Add comment reply script on single posts
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Add WooCommerce specific scripts if active
    if ($this->is_woocommerce_active) {
        wp_enqueue_script(
            'aqualuxe-woocommerce', 
            AQUALUXE_ASSETS_URI . 'js/woocommerce' . $this->get_asset_version('js/woocommerce.js', $mix_manifest) . '.js', 
            array('jquery'), 
            AQUALUXE_VERSION, 
            true
        );
    }
}
```

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides extensive customization options. The WooCommerce integration is handled in the `inc/woocommerce` directory.

### WooCommerce Support

WooCommerce support is added in the `setup_woocommerce_support` method of the `AquaLuxe_Theme` class:

```php
private function setup_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
```

### WooCommerce Template Overrides

AquaLuxe overrides WooCommerce templates to provide a custom look and feel. The overrides are handled in the `inc/woocommerce/woocommerce-template-hooks.php` and `inc/woocommerce/woocommerce-template-functions.php` files.

### Custom WooCommerce Features

AquaLuxe adds several custom WooCommerce features:

- **Quick View**: Allows customers to view product details without leaving the page
- **Wishlist**: Allows customers to add products to their wishlist
- **AJAX Add to Cart**: Allows customers to add products to cart without page reload
- **Advanced Filters**: Provides advanced filtering options for products
- **Custom Tabs**: Adds Care Guide and Shipping tabs to product pages

## Dark Mode Implementation

AquaLuxe includes a dark mode feature that users can toggle. The dark mode functionality is implemented in the `inc/dark-mode.php` file and the `assets/src/js/dark-mode.js` file.

### Dark Mode Toggle

The dark mode toggle is added to the header and can be customized in the Customizer.

### Dark Mode Detection

AquaLuxe detects the user's system preference for dark mode and applies it automatically if the user hasn't set a preference.

### Dark Mode Persistence

The user's dark mode preference is saved in localStorage and a cookie for server-side detection.

## Multilingual Support

AquaLuxe is fully compatible with popular multilingual plugins like Polylang and WPML. The multilingual support is implemented in the `inc/multilingual.php` file.

### Language Switcher

AquaLuxe adds a language switcher to the header that can be customized in the Customizer.

### Translation Ready

All strings in AquaLuxe are translation-ready and can be translated using standard WordPress translation tools.

## Multi-currency Support

AquaLuxe supports multiple currencies for international sales. The multi-currency support is implemented in the `inc/multi-currency.php` file.

### Currency Switcher

AquaLuxe adds a currency switcher to the header that can be customized in the Customizer.

### Price Formatting

AquaLuxe formats prices according to the selected currency and locale.

## Multivendor Support

AquaLuxe is compatible with popular multivendor plugins like Dokan and WC Vendors. The multivendor support is implemented in the `inc/multivendor.php` file.

### Vendor Dashboard

AquaLuxe enhances the vendor dashboard with a custom design and additional features.

### Vendor Stores

AquaLuxe provides custom templates for vendor stores with a consistent design.

## Multitenant Architecture

AquaLuxe supports WordPress multisite installations with a multitenant architecture. The multitenant support is implemented in the `inc/multitenant.php` file.

### Network Settings

AquaLuxe adds network settings for multisite installations.

### Per-Site Customization

Each site in a multisite installation can have its own customization options.

## Creating a Child Theme

For extensive customizations, it's recommended to create a child theme:

1. Create a new folder named `aqualuxe-child` in your `wp-content/themes` directory
2. Create a `style.css` file in the child theme folder with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe-theme
Version: 1.0.0
*/

/* Add your custom CSS here */
```

3. Create a `functions.php` file in the child theme folder with the following content:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions here
```

4. Activate the child theme in **Appearance > Themes**

## Extending the Theme

AquaLuxe can be extended in several ways:

### Adding Custom Templates

You can add custom templates to your child theme:

1. Create a new file in your child theme folder, e.g., `template-custom.php`
2. Add the template header:

```php
<?php
/**
 * Template Name: Custom Template
 * Template Post Type: post, page
 *
 * @package AquaLuxe Child
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'page');
        endwhile;
        ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();
```

### Adding Custom Hooks

You can add custom hooks to your child theme:

```php
function aqualuxe_child_custom_hook() {
    // Your code here
}
add_action('aqualuxe_after_header', 'aqualuxe_child_custom_hook');
```

### Adding Custom Widgets

You can add custom widgets to your child theme:

```php
class AquaLuxe_Child_Custom_Widget extends WP_Widget {
    // Widget implementation
}

function aqualuxe_child_register_widgets() {
    register_widget('AquaLuxe_Child_Custom_Widget');
}
add_action('widgets_init', 'aqualuxe_child_register_widgets');
```

### Adding Custom Shortcodes

You can add custom shortcodes to your child theme:

```php
function aqualuxe_child_custom_shortcode($atts, $content = null) {
    // Shortcode implementation
    return $output;
}
add_shortcode('custom_shortcode', 'aqualuxe_child_custom_shortcode');
```

### Adding Custom Blocks

You can add custom blocks to your child theme using the WordPress Block API.