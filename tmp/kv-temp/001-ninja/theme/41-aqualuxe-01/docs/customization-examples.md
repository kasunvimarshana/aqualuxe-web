# AquaLuxe WordPress Theme - Customization Examples

## Table of Contents

1. [Introduction](#introduction)
2. [Adding Custom CSS](#adding-custom-css)
3. [Customizing the Header](#customizing-the-header)
4. [Customizing the Footer](#customizing-the-footer)
5. [Modifying the Shop Page](#modifying-the-shop-page)
6. [Customizing Product Pages](#customizing-product-pages)
7. [Creating a Custom Homepage](#creating-a-custom-homepage)
8. [Adding Custom Widgets](#adding-custom-widgets)
9. [Customizing Blog Layout](#customizing-blog-layout)
10. [Advanced Customizations](#advanced-customizations)

## Introduction

This document provides practical examples of how to customize the AquaLuxe WordPress theme to match your specific needs. These examples use WordPress child themes, custom CSS, and PHP hooks to modify the theme without altering the core files.

## Adding Custom CSS

### Creating a Child Theme

The recommended way to add custom CSS is through a child theme:

1. Create a new folder in your WordPress themes directory named `aqualuxe-child`
2. Create a `style.css` file with the following header:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe
Version: 1.0.0
*/

/* Import parent theme styles */
@import url("../aqualuxe/style.css");

/* Your custom CSS below */
```

3. Create a `functions.php` file with:

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

### Using the Customizer

For simpler CSS customizations, you can use the WordPress Customizer:

1. Go to **Appearance > Customize**
2. Select **Additional CSS**
3. Add your custom CSS code

Example of changing the primary color:

```css
:root {
    --aqualuxe-primary-color: #00a0d2;
}

.btn-primary, .woocommerce #respond input#submit.alt, 
.woocommerce a.button.alt, .woocommerce button.button.alt, 
.woocommerce input.button.alt {
    background-color: var(--aqualuxe-primary-color);
}
```

## Customizing the Header

### Adding a Top Bar

Add a top bar above the header with contact information:

```php
function add_top_bar() {
    ?>
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span><i class="fas fa-phone"></i> +1 (800) 123-4567</span>
                    <span><i class="fas fa-envelope"></i> info@example.com</span>
                </div>
                <div class="col-md-6 text-right">
                    <span><i class="fas fa-clock"></i> Mon-Fri: 9AM-6PM</span>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_before_header', 'add_top_bar');
```

Add the corresponding CSS to your child theme:

```css
.top-bar {
    background-color: #f8f9fa;
    padding: 5px 0;
    font-size: 14px;
}

.top-bar span {
    margin-right: 15px;
}

.top-bar i {
    margin-right: 5px;
    color: var(--aqualuxe-primary-color);
}
```

### Modifying the Logo Area

Change the logo size and alignment:

```css
.site-logo img {
    max-height: 80px;
    width: auto;
}

.site-branding {
    display: flex;
    align-items: center;
}
```

### Customizing the Navigation Menu

Change the menu style:

```css
.main-navigation ul li a {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 15px 20px;
}

.main-navigation ul li:hover > a {
    color: var(--aqualuxe-primary-color);
}
```

## Customizing the Footer

### Adding Custom Footer Widgets

Register a new footer widget area:

```php
function custom_footer_widget() {
    register_sidebar(array(
        'name'          => 'Footer Column 4',
        'id'            => 'footer-column-4',
        'description'   => 'Add widgets here to appear in your footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'custom_footer_widget');
```

### Adding a Custom Footer Copyright

Replace the default copyright text:

```php
function custom_footer_copyright() {
    ?>
    <div class="site-info">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.
                </div>
                <div class="col-md-6 text-right">
                    <div class="payment-icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_footer_bottom', 'custom_footer_copyright', 15);
```

Add the corresponding CSS:

```css
.payment-icons i {
    font-size: 24px;
    margin-left: 10px;
    color: #888;
}
```

## Modifying the Shop Page

### Changing Products Per Row

Modify the number of products per row:

```php
function custom_products_per_row() {
    return 4; // Change to 3, 4, or 5
}
add_filter('aqualuxe_shop_products_per_row', 'custom_products_per_row');
```

### Customizing the Product Filter

Add a custom filter to the product filter area:

```php
function add_custom_product_filter() {
    $featured = isset($_GET['featured']) ? $_GET['featured'] : '';
    ?>
    <div class="custom-filter">
        <h4>Show Featured</h4>
        <select class="filter-select" name="featured">
            <option value="">All Products</option>
            <option value="yes" <?php selected($featured, 'yes'); ?>>Featured Only</option>
        </select>
    </div>
    <?php
}
add_action('aqualuxe_after_product_filter', 'add_custom_product_filter');
```

## Customizing Product Pages

### Adding Custom Product Tabs

Add a new tab to the product page:

```php
function custom_product_tabs($tabs) {
    $tabs['shipping_tab'] = array(
        'title'    => 'Shipping Information',
        'priority' => 30,
        'callback' => 'shipping_tab_content'
    );
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'custom_product_tabs');

function shipping_tab_content() {
    echo '<h2>Shipping Information</h2>';
    echo '<p>We offer free shipping on all orders over $100. Standard shipping takes 3-5 business days.</p>';
    echo '<p>For international orders, please allow 7-14 business days for delivery.</p>';
}
```

### Adding Content After Add to Cart Button

Add trust badges after the add to cart button:

```php
function add_trust_badges() {
    ?>
    <div class="trust-badges">
        <div class="badge">
            <i class="fas fa-truck"></i>
            <span>Free Shipping Over $100</span>
        </div>
        <div class="badge">
            <i class="fas fa-undo"></i>
            <span>30-Day Returns</span>
        </div>
        <div class="badge">
            <i class="fas fa-lock"></i>
            <span>Secure Checkout</span>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_after_add_to_cart', 'add_trust_badges');
```

Add the corresponding CSS:

```css
.trust-badges {
    display: flex;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.trust-badges .badge {
    display: flex;
    align-items: center;
    margin-right: 20px;
    background: none;
    padding: 0;
}

.trust-badges .badge i {
    font-size: 18px;
    margin-right: 8px;
    color: var(--aqualuxe-primary-color);
}
```

## Creating a Custom Homepage

### Using the Page Builder

AquaLuxe is compatible with popular page builders like Elementor, Beaver Builder, and the WordPress Block Editor. To create a custom homepage:

1. Create a new page (e.g., "Home")
2. Use your preferred page builder to design the page
3. Go to **Settings > Reading**
4. Set "Your homepage displays" to "A static page"
5. Select your new page as the Homepage

### Adding a Custom Hero Section

If you prefer to code your own homepage template:

1. Create a file named `template-home.php` in your child theme:

```php
<?php
/**
 * Template Name: Custom Homepage
 */

get_header();
?>

<div class="custom-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Premium Aquatic Products for Your Luxury Lifestyle</h1>
                <p>Discover our collection of high-end aquariums, exotic fish, and premium accessories.</p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">Shop Now</a>
            </div>
            <div class="col-md-6">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-image.jpg" alt="Luxury Aquarium">
            </div>
        </div>
    </div>
</div>

<?php
// Rest of your homepage content
get_footer();
```

2. Create a new page, select "Custom Homepage" template, and set it as your homepage

## Adding Custom Widgets

### Creating a Featured Products Widget

Create a custom widget for featured products:

```php
class Featured_Products_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'featured_products_widget',
            'Featured Products',
            array('description' => 'Display featured products')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        
        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $number,
            'tax_query'           => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );
        
        $products = new WP_Query($query_args);
        
        if ($products->have_posts()) {
            echo '<ul class="featured-products-widget">';
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $product->get_image('thumbnail'); ?>
                        <span class="product-title"><?php the_title(); ?></span>
                    </a>
                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                </li>
                <?php
            }
            echo '</ul>';
        }
        
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Featured Products';
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">Number of products to show:</label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 4;
        return $instance;
    }
}

function register_featured_products_widget() {
    register_widget('Featured_Products_Widget');
}
add_action('widgets_init', 'register_featured_products_widget');
```

## Customizing Blog Layout

### Changing the Blog Layout

Modify the blog layout to a grid format:

```php
function custom_blog_layout($classes) {
    if (is_home() || is_archive()) {
        $classes[] = 'blog-grid';
    }
    return $classes;
}
add_filter('aqualuxe_body_classes', 'custom_blog_layout');
```

Add the corresponding CSS:

```css
.blog-grid .posts-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.blog-grid .post {
    margin-bottom: 0;
    border: 1px solid #eee;
    border-radius: 5px;
    overflow: hidden;
}

.blog-grid .post-thumbnail {
    margin-bottom: 0;
}

.blog-grid .entry-content-wrap {
    padding: 20px;
}

@media (max-width: 768px) {
    .blog-grid .posts-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .blog-grid .posts-container {
        grid-template-columns: 1fr;
    }
}
```

### Customizing the Post Meta

Modify the post meta information:

```php
function custom_post_meta() {
    ?>
    <div class="entry-meta">
        <span class="posted-on">
            <i class="far fa-calendar-alt"></i>
            <?php echo get_the_date(); ?>
        </span>
        <span class="byline">
            <i class="far fa-user"></i>
            <?php the_author_posts_link(); ?>
        </span>
        <?php if (has_category()) : ?>
        <span class="cat-links">
            <i class="far fa-folder-open"></i>
            <?php the_category(', '); ?>
        </span>
        <?php endif; ?>
    </div>
    <?php
}
remove_action('aqualuxe_post_meta', 'aqualuxe_post_meta', 10);
add_action('aqualuxe_post_meta', 'custom_post_meta', 10);
```

## Advanced Customizations

### Adding Custom Post Types

Create a custom post type for testimonials:

```php
function register_testimonial_post_type() {
    $labels = array(
        'name'               => 'Testimonials',
        'singular_name'      => 'Testimonial',
        'menu_name'          => 'Testimonials',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Testimonial',
        'edit_item'          => 'Edit Testimonial',
        'new_item'           => 'New Testimonial',
        'view_item'          => 'View Testimonial',
        'search_items'       => 'Search Testimonials',
        'not_found'          => 'No testimonials found',
        'not_found_in_trash' => 'No testimonials found in Trash',
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'testimonials'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon'           => 'dashicons-format-quote',
    );
    
    register_post_type('testimonial', $args);
}
add_action('init', 'register_testimonial_post_type');
```

### Adding Custom Shortcodes

Create a shortcode to display testimonials:

```php
function testimonials_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts);
    
    $query_args = array(
        'post_type'      => 'testimonial',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );
    
    $testimonials = new WP_Query($query_args);
    
    ob_start();
    
    if ($testimonials->have_posts()) {
        echo '<div class="testimonials-carousel">';
        while ($testimonials->have_posts()) {
            $testimonials->the_post();
            $client_name = get_post_meta(get_the_ID(), 'client_name', true);
            $client_position = get_post_meta(get_the_ID(), 'client_position', true);
            ?>
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <?php the_content(); ?>
                </div>
                <div class="testimonial-meta">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="testimonial-image">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="testimonial-info">
                        <h4><?php echo esc_html($client_name); ?></h4>
                        <?php if ($client_position) : ?>
                            <span><?php echo esc_html($client_position); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('testimonials', 'testimonials_shortcode');
```

### Customizing WooCommerce Templates

Create a custom template for the mini-cart:

1. Copy the mini-cart.php file from WooCommerce to your child theme's woocommerce/cart folder
2. Modify the file to match your design requirements

For example, to add a custom message to the mini-cart:

```php
<div class="widget_shopping_cart_content">
    <?php if (!WC()->cart->is_empty()) : ?>
        <div class="mini-cart-header">
            <h4>Your Cart</h4>
            <span class="item-count"><?php echo WC()->cart->get_cart_contents_count(); ?> items</span>
        </div>
        
        <ul class="woocommerce-mini-cart cart_list product_list_widget">
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                <?php
                $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                
                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                    <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
                        <?php
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                esc_attr__('Remove this item', 'woocommerce'),
                                esc_attr($product_id),
                                esc_attr($cart_item_key),
                                esc_attr($_product->get_sku())
                            ),
                            $cart_item_key
                        );
                        ?>
                        <?php if (empty($product_permalink)) : ?>
                            <?php echo $thumbnail . wp_kses_post($product_name); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url($product_permalink); ?>">
                                <?php echo $thumbnail . wp_kses_post($product_name); ?>
                            </a>
                        <?php endif; ?>
                        <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                        <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); ?>
                    </li>
                    <?php
                }
            ?>
            <?php endforeach; ?>
        </ul>
        
        <div class="mini-cart-message">
            <p>Free shipping on orders over $100!</p>
        </div>

        <p class="woocommerce-mini-cart__total total">
            <?php
            /**
             * Hook: woocommerce_widget_shopping_cart_total.
             *
             * @hooked woocommerce_widget_shopping_cart_subtotal - 10
             */
            do_action('woocommerce_widget_shopping_cart_total');
            ?>
        </p>

        <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

        <p class="woocommerce-mini-cart__buttons buttons">
            <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
        </p>

        <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

    <?php else : ?>
        <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>
    <?php endif; ?>
</div>
```

Add the corresponding CSS:

```css
.mini-cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.mini-cart-header h4 {
    margin: 0;
    font-size: 16px;
}

.mini-cart-message {
    background-color: #f8f9fa;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
    font-size: 14px;
    text-align: center;
}

.mini-cart-message p {
    margin: 0;
    color: var(--aqualuxe-primary-color);
}
```