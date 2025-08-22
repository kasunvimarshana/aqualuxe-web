<?php
/**
 * WooCommerce compatibility file
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 400,
        'single_image_width' => 800,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 1,
            'max_rows' => 8,
            'default_columns' => 3,
            'min_columns' => 1,
            'max_columns' => 6,
        ],
    ]);
    
    // Add theme support for product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Register WooCommerce sidebars
 *
 * @return void
 */
function aqualuxe_woocommerce_widgets_init() {
    register_sidebar([
        'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id' => 'sidebar-shop',
        'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
    
    register_sidebar([
        'name' => esc_html__('Product Sidebar', 'aqualuxe'),
        'id' => 'sidebar-product',
        'description' => esc_html__('Add widgets here to appear in your product sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
}
add_action('widgets_init', 'aqualuxe_woocommerce_widgets_init');

/**
 * WooCommerce specific scripts & stylesheets
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // WooCommerce styles are enqueued in the main theme class
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet
 *
 * @return void
 */
function aqualuxe_dequeue_woocommerce_styles() {
    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
}
add_action('wp_enqueue_scripts', 'aqualuxe_dequeue_woocommerce_styles', 100);

/**
 * Related Products Args
 *
 * @param array $args Related products args
 * @return array
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $defaults = [
        'posts_per_page' => 4,
        'columns' => 4,
    ];
    
    $args = wp_parse_args($defaults, $args);
    
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Product gallery thumbnail columns
 *
 * @return int
 */
function aqualuxe_woocommerce_thumbnail_columns() {
    return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');

/**
 * Products per page
 *
 * @return int
 */
function aqualuxe_woocommerce_products_per_page() {
    $settings = aqualuxe_get_theme_settings();
    return isset($settings['products_per_page']) ? $settings['products_per_page'] : 12;
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Product gallery thumbnail size
 *
 * @return array
 */
function aqualuxe_woocommerce_gallery_thumbnail_size() {
    return [
        'width' => 100,
        'height' => 100,
        'crop' => 1,
    ];
}
add_filter('woocommerce_get_image_size_gallery_thumbnail', 'aqualuxe_woocommerce_gallery_thumbnail_size');

/**
 * Default loop columns on product archives
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_columns() {
    $settings = aqualuxe_get_theme_settings();
    return isset($settings['shop_columns']) ? $settings['shop_columns'] : 3;
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Add 'woocommerce-active' class to the body tag
 *
 * @param array $classes CSS classes applied to the body tag
 * @return array
 */
function aqualuxe_woocommerce_active_body_class($classes) {
    $classes[] = 'woocommerce-active';
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');

/**
 * Cart Fragments
 *
 * Ensure cart contents update when products are added to the cart via AJAX
 *
 * @param array $fragments Fragments to refresh via AJAX
 * @return array
 */
function aqualuxe_woocommerce_cart_link_fragment($fragments) {
    ob_start();
    aqualuxe_woocommerce_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

/**
 * Cart Link
 *
 * Displayed a link to the cart including the number of items present and the cart total
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
        <?php
        $item_count_text = sprintf(
            /* translators: %d: number of items in cart */
            _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'aqualuxe'),
            WC()->cart->get_cart_contents_count()
        );
        ?>
        <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
        <span class="count"><?php echo esc_html($item_count_text); ?></span>
    </a>
    <?php
}

/**
 * Display Header Cart
 *
 * @return void
 */
function aqualuxe_woocommerce_header_cart() {
    if (is_cart()) {
        $class = 'current-menu-item';
    } else {
        $class = '';
    }
    ?>
    <ul id="site-header-cart" class="site-header-cart">
        <li class="<?php echo esc_attr($class); ?>">
            <?php aqualuxe_woocommerce_cart_link(); ?>
        </li>
        <li>
            <?php
            $instance = [
                'title' => '',
            ];
            
            the_widget('WC_Widget_Cart', $instance);
            ?>
        </li>
    </ul>
    <?php
}

/**
 * Remove default WooCommerce wrapper
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrapper
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

/**
 * Add custom WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart
 *
 * You can add the WooCommerce Mini Cart to header.php like so:
 *
 * <?php
 * if (function_exists('aqualuxe_woocommerce_header_cart')) {
 *     aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

/**
 * Add quick view button to product loops
 */
function aqualuxe_add_quick_view_button() {
    $settings = aqualuxe_get_theme_settings();
    
    if (!isset($settings['enable_quick_view']) || !$settings['enable_quick_view']) {
        return;
    }
    
    global $product;
    
    echo '<div class="aqualuxe-quick-view-button">';
    echo '<a href="#" class="button aqualuxe-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    if (!isset($_POST['product_id'])) {
        wp_die();
    }
    
    $product_id = absint($_POST['product_id']);
    
    // Set the main WP query for the product
    wp('p=' . $product_id . '&post_type=product');
    
    ob_start();
    
    // Load content template
    aqualuxe_get_woocommerce_template_part('quick-view', 'content');
    
    $output = ob_get_clean();
    
    wp_send_json_success($output);
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');

/**
 * Add wishlist button to product loops
 */
function aqualuxe_add_wishlist_button() {
    $settings = aqualuxe_get_theme_settings();
    
    if (!isset($settings['enable_wishlist']) || !$settings['enable_wishlist']) {
        return;
    }
    
    global $product;
    
    $in_wishlist = aqualuxe_is_product_in_wishlist($product->get_id());
    $class = $in_wishlist ? 'aqualuxe-wishlist-added' : '';
    $text = $in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe');
    
    echo '<div class="aqualuxe-wishlist-button">';
    echo '<a href="#" class="button aqualuxe-wishlist ' . esc_attr($class) . '" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html($text) . '</a>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 20);

/**
 * Check if product is in wishlist
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_in_wishlist($product_id) {
    $wishlist = aqualuxe_get_wishlist();
    
    return in_array($product_id, $wishlist);
}

/**
 * Get wishlist
 *
 * @return array
 */
function aqualuxe_get_wishlist() {
    $wishlist = [];
    
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!$wishlist) {
            $wishlist = [];
        }
    } else {
        if (isset($_COOKIE['aqualuxe_wishlist'])) {
            $wishlist = json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true);
        }
    }
    
    return $wishlist;
}

/**
 * Wishlist AJAX handler
 */
function aqualuxe_wishlist_ajax() {
    if (!isset($_POST['product_id'])) {
        wp_die();
    }
    
    $product_id = absint($_POST['product_id']);
    $wishlist = aqualuxe_get_wishlist();
    
    if (in_array($product_id, $wishlist)) {
        $wishlist = array_diff($wishlist, [$product_id]);
        $action = 'removed';
    } else {
        $wishlist[] = $product_id;
        $action = 'added';
    }
    
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
    } else {
        setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + (86400 * 30), '/');
    }
    
    wp_send_json_success([
        'action' => $action,
        'product_id' => $product_id,
    ]);
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');

/**
 * Add AJAX add to cart functionality
 */
function aqualuxe_ajax_add_to_cart_js() {
    $settings = aqualuxe_get_theme_settings();
    
    if (!isset($settings['enable_ajax_add_to_cart']) || !$settings['enable_ajax_add_to_cart']) {
        return;
    }
    
    wp_enqueue_script('wc-add-to-cart');
}
add_action('wp_enqueue_scripts', 'aqualuxe_ajax_add_to_cart_js');

/**
 * Add product filter widget area
 */
function aqualuxe_product_filter_widgets_init() {
    register_sidebar([
        'name' => esc_html__('Product Filters', 'aqualuxe'),
        'id' => 'product-filters',
        'description' => esc_html__('Add widgets here to appear in your product filter area.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
}
add_action('widgets_init', 'aqualuxe_product_filter_widgets_init');

/**
 * Display product filters
 */
function aqualuxe_product_filters() {
    if (!is_active_sidebar('product-filters')) {
        return;
    }
    ?>
    <div id="aqualuxe-product-filters" class="aqualuxe-product-filters">
        <button class="aqualuxe-filter-toggle">
            <?php esc_html_e('Filter Products', 'aqualuxe'); ?>
            <span class="aqualuxe-filter-toggle-icon"></span>
        </button>
        
        <div class="aqualuxe-filter-widgets">
            <?php dynamic_sidebar('product-filters'); ?>
        </div>
    </div>
    <?php
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filters', 15);

/**
 * Add custom product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    global $product;
    
    // Add custom tab
    $tabs['aqualuxe_custom_tab'] = [
        'title' => esc_html__('Care Instructions', 'aqualuxe'),
        'priority' => 25,
        'callback' => 'aqualuxe_woocommerce_custom_tab_content',
    ];
    
    // Add shipping tab
    $tabs['aqualuxe_shipping_tab'] = [
        'title' => esc_html__('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    ];
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

/**
 * Custom tab content
 */
function aqualuxe_woocommerce_custom_tab_content() {
    global $product;
    
    // Get custom tab content from product meta
    $custom_tab_content = get_post_meta($product->get_id(), '_aqualuxe_custom_tab_content', true);
    
    if ($custom_tab_content) {
        echo wp_kses_post(wpautop($custom_tab_content));
    } else {
        // Default content
        echo '<p>' . esc_html__('Care instructions for this product will be added soon.', 'aqualuxe') . '</p>';
    }
}

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    global $product;
    
    // Get shipping tab content from product meta
    $shipping_tab_content = get_post_meta($product->get_id(), '_aqualuxe_shipping_tab_content', true);
    
    if ($shipping_tab_content) {
        echo wp_kses_post(wpautop($shipping_tab_content));
    } else {
        // Default content
        echo '<p>' . esc_html__('Shipping and returns information will be added soon.', 'aqualuxe') . '</p>';
    }
}

/**
 * Add product meta boxes
 */
function aqualuxe_woocommerce_add_product_meta_boxes() {
    add_meta_box(
        'aqualuxe_product_tabs',
        esc_html__('AquaLuxe Product Tabs', 'aqualuxe'),
        'aqualuxe_woocommerce_product_tabs_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_product_meta_boxes');

/**
 * Product tabs meta box
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_woocommerce_product_tabs_meta_box($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_tabs_meta_box', 'aqualuxe_product_tabs_meta_box_nonce');
    
    // Get the saved values
    $custom_tab_content = get_post_meta($post->ID, '_aqualuxe_custom_tab_content', true);
    $shipping_tab_content = get_post_meta($post->ID, '_aqualuxe_shipping_tab_content', true);
    
    ?>
    <div class="aqualuxe-product-tabs-meta-box">
        <div class="aqualuxe-product-tab-field">
            <label for="aqualuxe_custom_tab_content">
                <?php esc_html_e('Care Instructions Tab Content', 'aqualuxe'); ?>
            </label>
            <textarea id="aqualuxe_custom_tab_content" name="aqualuxe_custom_tab_content" rows="5" class="widefat"><?php echo esc_textarea($custom_tab_content); ?></textarea>
        </div>
        
        <div class="aqualuxe-product-tab-field">
            <label for="aqualuxe_shipping_tab_content">
                <?php esc_html_e('Shipping & Returns Tab Content', 'aqualuxe'); ?>
            </label>
            <textarea id="aqualuxe_shipping_tab_content" name="aqualuxe_shipping_tab_content" rows="5" class="widefat"><?php echo esc_textarea($shipping_tab_content); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Save product meta boxes
 *
 * @param int $post_id Post ID
 * @return void
 */
function aqualuxe_woocommerce_save_product_meta_boxes($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_tabs_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_tabs_meta_box_nonce'], 'aqualuxe_product_tabs_meta_box')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save the custom tab content
    if (isset($_POST['aqualuxe_custom_tab_content'])) {
        update_post_meta(
            $post_id,
            '_aqualuxe_custom_tab_content',
            wp_kses_post($_POST['aqualuxe_custom_tab_content'])
        );
    }
    
    // Save the shipping tab content
    if (isset($_POST['aqualuxe_shipping_tab_content'])) {
        update_post_meta(
            $post_id,
            '_aqualuxe_shipping_tab_content',
            wp_kses_post($_POST['aqualuxe_shipping_tab_content'])
        );
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_meta_boxes');

/**
 * Add product video meta box
 */
function aqualuxe_woocommerce_add_product_video_meta_box() {
    add_meta_box(
        'aqualuxe_product_video',
        esc_html__('Product Video', 'aqualuxe'),
        'aqualuxe_woocommerce_product_video_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_product_video_meta_box');

/**
 * Product video meta box
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_woocommerce_product_video_meta_box($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_video_meta_box', 'aqualuxe_product_video_meta_box_nonce');
    
    // Get the saved value
    $product_video_url = get_post_meta($post->ID, '_aqualuxe_product_video_url', true);
    
    ?>
    <div class="aqualuxe-product-video-meta-box">
        <div class="aqualuxe-product-video-field">
            <label for="aqualuxe_product_video_url">
                <?php esc_html_e('Video URL (YouTube or Vimeo)', 'aqualuxe'); ?>
            </label>
            <input type="url" id="aqualuxe_product_video_url" name="aqualuxe_product_video_url" value="<?php echo esc_url($product_video_url); ?>" class="widefat">
        </div>
    </div>
    <?php
}

/**
 * Save product video meta box
 *
 * @param int $post_id Post ID
 * @return void
 */
function aqualuxe_woocommerce_save_product_video_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_video_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_video_meta_box_nonce'], 'aqualuxe_product_video_meta_box')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save the product video URL
    if (isset($_POST['aqualuxe_product_video_url'])) {
        update_post_meta(
            $post_id,
            '_aqualuxe_product_video_url',
            esc_url_raw($_POST['aqualuxe_product_video_url'])
        );
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_video_meta_box');

/**
 * Display product video
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $product_video_url = get_post_meta($product->get_id(), '_aqualuxe_product_video_url', true);
    
    if (!$product_video_url) {
        return;
    }
    
    echo '<div class="aqualuxe-product-video">';
    echo '<h2>' . esc_html__('Product Video', 'aqualuxe') . '</h2>';
    
    // Check if it's a YouTube URL
    if (strpos($product_video_url, 'youtube.com') !== false || strpos($product_video_url, 'youtu.be') !== false) {
        // Extract YouTube video ID
        if (strpos($product_video_url, 'youtube.com/watch?v=') !== false) {
            $video_id = substr($product_video_url, strpos($product_video_url, 'v=') + 2);
            $video_id = strtok($video_id, '&');
        } elseif (strpos($product_video_url, 'youtu.be/') !== false) {
            $video_id = substr($product_video_url, strpos($product_video_url, 'youtu.be/') + 9);
            $video_id = strtok($video_id, '?');
        }
        
        if (isset($video_id)) {
            echo '<div class="aqualuxe-video-wrapper">';
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
        }
    } elseif (strpos($product_video_url, 'vimeo.com') !== false) {
        // Extract Vimeo video ID
        $video_id = substr($product_video_url, strpos($product_video_url, 'vimeo.com/') + 10);
        $video_id = strtok($video_id, '?');
        
        echo '<div class="aqualuxe-video-wrapper">';
        echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" width="560" height="315" frameborder="0" allowfullscreen></iframe>';
        echo '</div>';
    } else {
        echo '<p>' . esc_html__('Invalid video URL. Please use YouTube or Vimeo.', 'aqualuxe') . '</p>';
    }
    
    echo '</div>';
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_video', 15);

/**
 * Add 360 degree view meta box
 */
function aqualuxe_woocommerce_add_product_360_meta_box() {
    add_meta_box(
        'aqualuxe_product_360',
        esc_html__('360° Product View', 'aqualuxe'),
        'aqualuxe_woocommerce_product_360_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_product_360_meta_box');

/**
 * Product 360 meta box
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_woocommerce_product_360_meta_box($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_360_meta_box', 'aqualuxe_product_360_meta_box_nonce');
    
    // Get the saved value
    $product_360_gallery = get_post_meta($post->ID, '_aqualuxe_product_360_gallery', true);
    
    if (!is_array($product_360_gallery)) {
        $product_360_gallery = [];
    }
    
    ?>
    <div class="aqualuxe-product-360-meta-box">
        <div class="aqualuxe-product-360-field">
            <label>
                <?php esc_html_e('360° View Gallery', 'aqualuxe'); ?>
            </label>
            
            <div class="aqualuxe-product-360-gallery">
                <?php
                if (!empty($product_360_gallery)) {
                    foreach ($product_360_gallery as $attachment_id) {
                        $image = wp_get_attachment_image($attachment_id, 'thumbnail');
                        
                        if ($image) {
                            echo '<div class="aqualuxe-product-360-image">';
                            echo $image;
                            echo '<input type="hidden" name="aqualuxe_product_360_gallery[]" value="' . esc_attr($attachment_id) . '">';
                            echo '<a href="#" class="aqualuxe-remove-360-image">' . esc_html__('Remove', 'aqualuxe') . '</a>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
            
            <button type="button" class="button aqualuxe-add-360-images">
                <?php esc_html_e('Add Images', 'aqualuxe'); ?>
            </button>
        </div>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Add images
            $('.aqualuxe-add-360-images').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var gallery = $('.aqualuxe-product-360-gallery');
                
                var frame = wp.media({
                    title: '<?php esc_html_e('Select 360° View Images', 'aqualuxe'); ?>',
                    button: {
                        text: '<?php esc_html_e('Add to Gallery', 'aqualuxe'); ?>'
                    },
                    multiple: true
                });
                
                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    
                    $.each(attachments, function(i, attachment) {
                        var html = '<div class="aqualuxe-product-360-image">';
                        html += '<img src="' + attachment.sizes.thumbnail.url + '" alt="">';
                        html += '<input type="hidden" name="aqualuxe_product_360_gallery[]" value="' + attachment.id + '">';
                        html += '<a href="#" class="aqualuxe-remove-360-image"><?php esc_html_e('Remove', 'aqualuxe'); ?></a>';
                        html += '</div>';
                        
                        gallery.append(html);
                    });
                });
                
                frame.open();
            });
            
            // Remove image
            $('.aqualuxe-product-360-gallery').on('click', '.aqualuxe-remove-360-image', function(e) {
                e.preventDefault();
                $(this).parent().remove();
            });
        });
    </script>
    
    <style>
        .aqualuxe-product-360-gallery {
            margin-bottom: 10px;
        }
        
        .aqualuxe-product-360-image {
            position: relative;
            display: inline-block;
            margin: 0 10px 10px 0;
        }
        
        .aqualuxe-product-360-image img {
            display: block;
            max-width: 60px;
            height: auto;
        }
        
        .aqualuxe-remove-360-image {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(255, 0, 0, 0.7);
            color: #fff;
            font-size: 10px;
            padding: 2px 4px;
            display: none;
        }
        
        .aqualuxe-product-360-image:hover .aqualuxe-remove-360-image {
            display: block;
        }
    </style>
    <?php
}

/**
 * Save product 360 meta box
 *
 * @param int $post_id Post ID
 * @return void
 */
function aqualuxe_woocommerce_save_product_360_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_360_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_360_meta_box_nonce'], 'aqualuxe_product_360_meta_box')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save the product 360 gallery
    if (isset($_POST['aqualuxe_product_360_gallery'])) {
        $gallery = array_map('absint', $_POST['aqualuxe_product_360_gallery']);
        update_post_meta($post_id, '_aqualuxe_product_360_gallery', $gallery);
    } else {
        delete_post_meta($post_id, '_aqualuxe_product_360_gallery');
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_360_meta_box');

/**
 * Display 360 degree view
 */
function aqualuxe_woocommerce_product_360_view() {
    global $product;
    
    $gallery = get_post_meta($product->get_id(), '_aqualuxe_product_360_gallery', true);
    
    if (!is_array($gallery) || empty($gallery)) {
        return;
    }
    
    // Enqueue 360 view script
    wp_enqueue_script('aqualuxe-360-view');
    
    echo '<div class="aqualuxe-product-360-view-wrapper">';
    echo '<button class="aqualuxe-360-view-button">' . esc_html__('View 360°', 'aqualuxe') . '</button>';
    
    echo '<div class="aqualuxe-product-360-view" style="display: none;">';
    echo '<div class="aqualuxe-360-view-close">&times;</div>';
    
    echo '<div class="aqualuxe-360-view-container" data-images="' . esc_attr(count($gallery)) . '">';
    
    foreach ($gallery as $attachment_id) {
        $image = wp_get_attachment_image_src($attachment_id, 'full');
        
        if ($image) {
            echo '<img src="' . esc_url($image[0]) . '" alt="" class="aqualuxe-360-view-image">';
        }
    }
    
    echo '</div>';
    
    echo '<div class="aqualuxe-360-view-controls">';
    echo '<button class="aqualuxe-360-view-prev">' . esc_html__('Previous', 'aqualuxe') . '</button>';
    echo '<button class="aqualuxe-360-view-next">' . esc_html__('Next', 'aqualuxe') . '</button>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_360_view', 20);

/**
 * Add multicurrency support
 */
function aqualuxe_woocommerce_multicurrency_init() {
    // Check if multicurrency module is active
    if (!aqualuxe_is_module_active('multicurrency')) {
        return;
    }
    
    // Get available currencies
    $currencies = aqualuxe_get_module_setting('multicurrency', 'currencies', []);
    
    if (empty($currencies)) {
        return;
    }
    
    // Get current currency
    $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : '';
    
    // If current currency is not valid, use default
    if (!isset($currencies[$current_currency])) {
        $current_currency = aqualuxe_get_module_setting('multicurrency', 'default_currency', 'USD');
    }
    
    // Set current currency
    add_filter('woocommerce_currency', function() use ($current_currency) {
        return $current_currency;
    });
    
    // Set currency symbol
    add_filter('woocommerce_currency_symbol', function($symbol, $currency) use ($currencies, $current_currency) {
        if (isset($currencies[$current_currency]['symbol'])) {
            return $currencies[$current_currency]['symbol'];
        }
        
        return $symbol;
    }, 10, 2);
    
    // Set currency rate
    add_filter('woocommerce_product_get_price', function($price, $product) use ($currencies, $current_currency) {
        if (isset($currencies[$current_currency]['rate'])) {
            $rate = floatval($currencies[$current_currency]['rate']);
            
            if ($rate > 0) {
                return $price * $rate;
            }
        }
        
        return $price;
    }, 10, 2);
    
    add_filter('woocommerce_product_get_regular_price', function($price, $product) use ($currencies, $current_currency) {
        if (isset($currencies[$current_currency]['rate'])) {
            $rate = floatval($currencies[$current_currency]['rate']);
            
            if ($rate > 0) {
                return $price * $rate;
            }
        }
        
        return $price;
    }, 10, 2);
    
    add_filter('woocommerce_product_get_sale_price', function($price, $product) use ($currencies, $current_currency) {
        if (isset($currencies[$current_currency]['rate'])) {
            $rate = floatval($currencies[$current_currency]['rate']);
            
            if ($rate > 0) {
                return $price * $rate;
            }
        }
        
        return $price;
    }, 10, 2);
    
    // Add currency switcher
    add_action('wp_footer', 'aqualuxe_woocommerce_currency_switcher');
}
add_action('init', 'aqualuxe_woocommerce_multicurrency_init');

/**
 * Display currency switcher
 */
function aqualuxe_woocommerce_currency_switcher() {
    // Check if multicurrency module is active
    if (!aqualuxe_is_module_active('multicurrency')) {
        return;
    }
    
    // Get available currencies
    $currencies = aqualuxe_get_module_setting('multicurrency', 'currencies', []);
    
    if (empty($currencies)) {
        return;
    }
    
    // Get current currency
    $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : '';
    
    // If current currency is not valid, use default
    if (!isset($currencies[$current_currency])) {
        $current_currency = aqualuxe_get_module_setting('multicurrency', 'default_currency', 'USD');
    }
    
    echo '<div class="aqualuxe-currency-switcher">';
    echo '<span class="aqualuxe-currency-switcher-label">' . esc_html__('Currency', 'aqualuxe') . '</span>';
    
    echo '<select class="aqualuxe-currency-select">';
    
    foreach ($currencies as $code => $currency) {
        $selected = $code === $current_currency ? ' selected' : '';
        echo '<option value="' . esc_attr($code) . '"' . $selected . '>' . esc_html($currency['name']) . ' (' . esc_html($currency['symbol']) . ')</option>';
    }
    
    echo '</select>';
    echo '</div>';
    
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('.aqualuxe-currency-select').on('change', function() {
                var currency = $(this).val();
                
                // Set cookie
                document.cookie = 'aqualuxe_currency=' + currency + '; path=/; max-age=86400';
                
                // Reload page
                location.reload();
            });
        });
    </script>
    <?php
}

/**
 * Add international shipping options
 */
function aqualuxe_woocommerce_international_shipping_init() {
    // Check if international shipping module is active
    if (!aqualuxe_is_module_active('international-shipping')) {
        return;
    }
    
    // Add shipping zones
    add_action('woocommerce_shipping_init', 'aqualuxe_woocommerce_shipping_init');
    
    // Add shipping methods
    add_filter('woocommerce_shipping_methods', 'aqualuxe_woocommerce_shipping_methods');
}
add_action('init', 'aqualuxe_woocommerce_international_shipping_init');

/**
 * Initialize shipping
 */
function aqualuxe_woocommerce_shipping_init() {
    // Include shipping class
    require_once AQUALUXE_INC_DIR . 'class-aqualuxe-international-shipping.php';
}

/**
 * Add shipping methods
 *
 * @param array $methods Shipping methods
 * @return array
 */
function aqualuxe_woocommerce_shipping_methods($methods) {
    $methods['aqualuxe_international_shipping'] = 'AquaLuxe_International_Shipping';
    
    return $methods;
}

/**
 * Add checkout fields for international shipping
 *
 * @param array $fields Checkout fields
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Check if international shipping module is active
    if (!aqualuxe_is_module_active('international-shipping')) {
        return $fields;
    }
    
    // Add customs declaration field
    $fields['shipping']['shipping_customs_declaration'] = [
        'label' => esc_html__('Customs Declaration', 'aqualuxe'),
        'placeholder' => esc_html__('e.g. Gift, Commercial Sample, etc.', 'aqualuxe'),
        'required' => false,
        'class' => ['form-row-wide'],
        'clear' => true,
        'priority' => 100,
    ];
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');

/**
 * Save checkout fields
 *
 * @param int $order_id Order ID
 */
function aqualuxe_woocommerce_checkout_update_order_meta($order_id) {
    // Check if international shipping module is active
    if (!aqualuxe_is_module_active('international-shipping')) {
        return;
    }
    
    if (isset($_POST['shipping_customs_declaration'])) {
        update_post_meta($order_id, '_shipping_customs_declaration', sanitize_text_field($_POST['shipping_customs_declaration']));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'aqualuxe_woocommerce_checkout_update_order_meta');

/**
 * Display checkout fields in admin
 *
 * @param WC_Order $order Order object
 */
function aqualuxe_woocommerce_admin_order_data_after_shipping_address($order) {
    // Check if international shipping module is active
    if (!aqualuxe_is_module_active('international-shipping')) {
        return;
    }
    
    $customs_declaration = get_post_meta($order->get_id(), '_shipping_customs_declaration', true);
    
    if ($customs_declaration) {
        echo '<p><strong>' . esc_html__('Customs Declaration', 'aqualuxe') . ':</strong> ' . esc_html($customs_declaration) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'aqualuxe_woocommerce_admin_order_data_after_shipping_address');