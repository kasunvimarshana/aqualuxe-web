# AquaLuxe WordPress Theme Developer Documentation

This document provides technical information for developers who want to customize or extend the AquaLuxe WordPress theme.

## Theme Structure

```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── accessibility.css
│   │   ├── custom.css
│   │   ├── dark-mode.css
│   │   └── tailwind.css
│   ├── js/
│   │   ├── custom.js
│   │   ├── dark-mode.js
│   │   ├── focus-management.js
│   │   ├── keyboard-navigation.js
│   │   └── navigation.js
│   └── images/
├── docs/
│   ├── README.md
│   ├── INSTALLATION.md
│   ├── DEVELOPER.md
│   ├── CUSTOMIZATION.md
│   └── CHANGELOG.md
├── inc/
│   ├── core/
│   │   ├── accessibility.php
│   │   ├── dark-mode.php
│   │   ├── hooks.php
│   │   ├── multilingual.php
│   │   ├── pagination.php
│   │   ├── performance.php
│   │   ├── post-types.php
│   │   ├── seo.php
│   │   ├── taxonomies.php
│   │   ├── template-functions.php
│   │   ├── template-tags.php
│   │   └── walker-nav-menu.php
│   ├── customizer/
│   │   └── customizer.php
│   ├── helpers/
│   │   ├── markup.php
│   │   └── sanitize.php
│   ├── widgets/
│   │   ├── recent-posts.php
│   │   └── social-links.php
│   └── woocommerce/
│       ├── international-shipping.php
│       ├── multi-currency.php
│       ├── quick-view.php
│       ├── template-functions.php
│       ├── template-hooks.php
│       ├── wishlist.php
│       └── woocommerce.php
├── languages/
├── template-parts/
│   ├── about/
│   ├── blog/
│   ├── contact/
│   ├── content-none.php
│   ├── content-page.php
│   ├── content-search.php
│   ├── content.php
│   ├── faq/
│   ├── home/
│   └── services/
├── woocommerce/
│   ├── archive-product.php
│   ├── cart/
│   ├── checkout/
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── global/
│   ├── loop/
│   ├── quick-view-content.php
│   ├── quick-view-modal.php
│   ├── single-product/
│   ├── single-product.php
│   └── wishlist.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── searchform.php
├── sidebar.php
├── single.php
└── style.css
```

## Build System

AquaLuxe uses Tailwind CSS for styling. To compile the CSS:

1. Install Node.js and npm
2. Navigate to the theme directory
3. Run `npm install` to install dependencies
4. Run `npm run dev` for development build
5. Run `npm run build` for production build

## Hooks and Filters

AquaLuxe provides numerous hooks and filters to customize its functionality without modifying core files.

### Action Hooks

#### Header Hooks
- `aqualuxe_before_header`: Fires before the header
- `aqualuxe_after_header`: Fires after the header
- `aqualuxe_header_content`: Fires inside the header container

#### Footer Hooks
- `aqualuxe_before_footer`: Fires before the footer
- `aqualuxe_after_footer`: Fires after the footer
- `aqualuxe_footer_content`: Fires inside the footer container

#### Content Hooks
- `aqualuxe_before_content`: Fires before the main content
- `aqualuxe_after_content`: Fires after the main content
- `aqualuxe_before_post_content`: Fires before post content
- `aqualuxe_after_post_content`: Fires after post content
- `aqualuxe_before_page_content`: Fires before page content
- `aqualuxe_after_page_content`: Fires after page content

#### WooCommerce Hooks
- `aqualuxe_before_shop_loop`: Fires before the shop loop
- `aqualuxe_after_shop_loop`: Fires after the shop loop
- `aqualuxe_before_product_content`: Fires before product content
- `aqualuxe_after_product_content`: Fires after product content
- `aqualuxe_before_add_to_cart`: Fires before add to cart button
- `aqualuxe_after_add_to_cart`: Fires after add to cart button
- `aqualuxe_before_product_tabs`: Fires before product tabs
- `aqualuxe_after_product_tabs`: Fires after product tabs

### Filter Hooks

#### General Filters
- `aqualuxe_body_classes`: Filter body classes
- `aqualuxe_post_classes`: Filter post classes
- `aqualuxe_excerpt_length`: Filter excerpt length
- `aqualuxe_excerpt_more`: Filter excerpt more text
- `aqualuxe_comment_form_args`: Filter comment form arguments
- `aqualuxe_pagination_args`: Filter pagination arguments

#### WooCommerce Filters
- `aqualuxe_product_loop_columns`: Filter product loop columns
- `aqualuxe_related_products_args`: Filter related products arguments
- `aqualuxe_upsell_products_args`: Filter upsell products arguments
- `aqualuxe_cross_sell_products_args`: Filter cross-sell products arguments
- `aqualuxe_product_tabs`: Filter product tabs

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some additions:

- `front-page.php`: Used for the homepage
- `page-{slug}.php`: Used for specific pages (e.g., `page-about.php` for the About page)
- `archive-{post_type}.php`: Used for custom post type archives
- `single-{post_type}.php`: Used for single custom post type posts
- `taxonomy-{taxonomy}.php`: Used for custom taxonomy archives
- `woocommerce.php`: Used as a fallback for WooCommerce pages

## Custom Post Types

AquaLuxe includes the following custom post types:

### Services
- Post Type: `service`
- Supports: title, editor, thumbnail, excerpt
- Taxonomies: service_category
- Rewrite: `services/%service_category%/%service%`

### Testimonials
- Post Type: `testimonial`
- Supports: title, editor, thumbnail
- Taxonomies: none
- Rewrite: `testimonials/%testimonial%`

### Team Members
- Post Type: `team_member`
- Supports: title, editor, thumbnail, excerpt
- Taxonomies: team_department
- Rewrite: `team/%team_department%/%team_member%`

## Custom Taxonomies

AquaLuxe includes the following custom taxonomies:

### Service Categories
- Taxonomy: `service_category`
- Post Types: `service`
- Hierarchical: true
- Rewrite: `service-category/%service_category%`

### Team Departments
- Taxonomy: `team_department`
- Post Types: `team_member`
- Hierarchical: true
- Rewrite: `team-department/%team_department%`

## Customizer Options

AquaLuxe provides extensive customization options through the WordPress Customizer:

### Site Identity
- Logo
- Site Title
- Tagline
- Site Icon

### Colors
- Primary Color
- Secondary Color
- Background Color
- Text Color
- Link Color
- Button Color

### Typography
- Heading Font
- Body Font
- Font Sizes
- Line Heights

### Header
- Header Layout
- Header Background
- Header Text Color
- Sticky Header
- Transparent Header

### Footer
- Footer Layout
- Footer Background
- Footer Text Color
- Footer Widgets
- Copyright Text

### Blog
- Blog Layout
- Featured Image Size
- Excerpt Length
- Read More Text
- Post Meta Display

### WooCommerce
- Shop Layout
- Product Grid Columns
- Related Products Count
- Upsell Products Count
- Cross-Sell Products Count
- Quick View
- Wishlist
- Multi-Currency

### Dark Mode
- Dark Mode Toggle
- Dark Mode Colors
- Auto Dark Mode

### Multilingual
- Language Switcher
- RTL Support

## Adding Custom Functionality

### Adding a Custom Post Type

```php
function my_custom_post_type() {
    $args = array(
        'labels' => array(
            'name' => __( 'Projects', 'aqualuxe' ),
            'singular_name' => __( 'Project', 'aqualuxe' ),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite' => array( 'slug' => 'projects' ),
    );
    register_post_type( 'project', $args );
}
add_action( 'init', 'my_custom_post_type' );
```

### Adding a Custom Taxonomy

```php
function my_custom_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => __( 'Project Categories', 'aqualuxe' ),
            'singular_name' => __( 'Project Category', 'aqualuxe' ),
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array( 'slug' => 'project-category' ),
    );
    register_taxonomy( 'project_category', 'project', $args );
}
add_action( 'init', 'my_custom_taxonomy' );
```

### Adding a Custom Widget Area

```php
function my_custom_widget_area() {
    register_sidebar( array(
        'name' => __( 'Custom Sidebar', 'aqualuxe' ),
        'id' => 'custom-sidebar',
        'description' => __( 'Add widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ) );
}
add_action( 'widgets_init', 'my_custom_widget_area' );
```

### Adding a Custom Customizer Section

```php
function my_custom_customizer_section( $wp_customize ) {
    $wp_customize->add_section( 'my_custom_section', array(
        'title' => __( 'Custom Section', 'aqualuxe' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'my_custom_setting', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'my_custom_setting', array(
        'label' => __( 'Custom Setting', 'aqualuxe' ),
        'section' => 'my_custom_section',
        'type' => 'text',
    ) );
}
add_action( 'customize_register', 'my_custom_customizer_section' );
```

### Adding a Custom Template

1. Create a new file in the theme directory, e.g., `template-custom.php`
2. Add the template header:

```php
<?php
/**
 * Template Name: Custom Template
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'page' );
        endwhile;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
```

## Child Theme Development

To create a child theme for AquaLuxe:

1. Create a new folder in the `wp-content/themes` directory, e.g., `aqualuxe-child`
2. Create a `style.css` file with the following header:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.example.com/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com/
Template: aqualuxe
Version: 1.0.0
Text Domain: aqualuxe-child
*/
```

3. Create a `functions.php` file:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @package AquaLuxe_Child
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'aqualuxe-child-style', get_stylesheet_uri(), array( 'aqualuxe-style' ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );

// Add your custom functions below this line
```

4. Activate the child theme in the WordPress admin

## Performance Optimization

AquaLuxe includes several performance optimization features:

- Lazy loading for images and iframes
- CSS and JavaScript minification
- Image optimization with WebP support
- Browser caching strategies
- Resource preloading and prefetching

To further optimize performance:

1. Use a caching plugin like WP Rocket
2. Use a CDN for static assets
3. Optimize database queries
4. Use a quality hosting provider

## Accessibility

AquaLuxe is built with accessibility in mind:

- ARIA landmarks and roles
- Keyboard navigation support
- Focus management
- Screen reader text for icons
- Skip links
- Sufficient color contrast
- Semantic HTML5 structure

When customizing the theme, ensure that your changes maintain these accessibility features.

## Support

For developer support, please contact our developer support team:

- Email: dev-support@aqualuxe.example.com
- Developer Forum: https://aqualuxe.example.com/dev-support
- GitHub: https://github.com/aqualuxe/aqualuxe