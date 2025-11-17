<?php
/**
 * WooCommerce integration for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 400,
        'single_image_width' => 800,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 2,
            'max_rows' => 8,
            'default_columns' => 4,
            'min_columns' => 2,
            'max_columns' => 5,
        ],
    ]);

    // Add support for WooCommerce product gallery features
    if (get_theme_mod('aqualuxe_product_zoom', true)) {
        add_theme_support('wc-product-gallery-zoom');
    }
    
    if (get_theme_mod('aqualuxe_product_lightbox', true)) {
        add_theme_support('wc-product-gallery-lightbox');
    }
    
    if (get_theme_mod('aqualuxe_product_slider', true)) {
        add_theme_support('wc-product-gallery-slider');
    }
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 */
function aqualuxe_woocommerce_scripts() {
    // Enqueue WooCommerce styles
    wp_enqueue_style('aqualuxe-woocommerce-style', AQUALUXE_URI . '/assets/css/woocommerce.css', [], AQUALUXE_VERSION);

    // Enqueue WooCommerce scripts
    wp_enqueue_script('aqualuxe-woocommerce-script', AQUALUXE_URI . '/assets/js/woocommerce.js', ['jquery'], AQUALUXE_VERSION, true);

    // Localize WooCommerce script
    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxeWoocommerce', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'isLoggedIn' => is_user_logged_in(),
        'cartUrl' => wc_get_cart_url(),
        'checkoutUrl' => wc_get_checkout_url(),
        'i18n' => [
            'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
            'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
            'addedToCart' => esc_html__('Added to Cart', 'aqualuxe'),
            'viewCart' => esc_html__('View Cart', 'aqualuxe'),
            'errorMessage' => esc_html__('Something went wrong. Please try again.', 'aqualuxe'),
        ],
    ]);
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 */
function aqualuxe_dequeue_woocommerce_styles() {
    // Dequeue the default WooCommerce stylesheet
    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
}
add_action('wp_enqueue_scripts', 'aqualuxe_dequeue_woocommerce_styles', 100);

/**
 * Related Products Args.
 *
 * @param array $args Related products args.
 * @return array
 */
function aqualuxe_related_products_args($args) {
    $defaults = [
        'posts_per_page' => 4,
        'columns' => 4,
    ];

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main container-fluid py-12">
    <?php
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
    </main><!-- #primary -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Add custom WooCommerce sidebar.
 */
function aqualuxe_woocommerce_sidebar() {
    // Get sidebar position
    $sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');

    // Check if sidebar is enabled
    if ('none' !== $sidebar_position && is_active_sidebar('sidebar-shop')) {
        ?>
        <aside id="secondary" class="widget-area">
            <?php dynamic_sidebar('sidebar-shop'); ?>
        </aside><!-- #secondary -->
        <?php
    }
}
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar');

/**
 * Modify WooCommerce breadcrumb arguments.
 *
 * @param array $args Breadcrumb args.
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_args($args) {
    $args = [
        'delimiter' => '<span class="breadcrumb-separator mx-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb py-4 text-sm" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '"><ol class="flex flex-wrap items-center">',
        'wrap_after' => '</ol></nav>',
        'before' => '<li class="breadcrumb-item">',
        'after' => '</li>',
        'home' => esc_html__('Home', 'aqualuxe'),
    ];

    return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_args');

/**
 * Modify WooCommerce pagination arguments.
 *
 * @param array $args Pagination args.
 * @return array
 */
function aqualuxe_woocommerce_pagination_args($args) {
    $args = [
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg><span class="screen-reader-text">' . esc_html__('Previous page', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
        'mid_size' => 2,
        'end_size' => 1,
    ];

    return $args;
}
add_filter('woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args');

/**
 * Modify WooCommerce product columns.
 *
 * @param int $columns Number of columns.
 * @return int
 */
function aqualuxe_woocommerce_loop_columns($columns) {
    $columns = get_theme_mod('aqualuxe_shop_columns', 3);
    return $columns;
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Modify WooCommerce products per page.
 *
 * @param int $products Number of products.
 * @return int
 */
function aqualuxe_woocommerce_products_per_page($products) {
    $products = 12;
    return $products;
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Add custom classes to product loop.
 *
 * @param array $classes Product classes.
 * @return array
 */
function aqualuxe_woocommerce_product_loop_classes($classes) {
    $classes[] = 'product-card';
    
    // Add product card style class
    $product_card_style = get_theme_mod('aqualuxe_product_card', 'default');
    $classes[] = 'product-card-' . $product_card_style;
    
    return $classes;
}
add_filter('woocommerce_post_class', 'aqualuxe_woocommerce_product_loop_classes', 10, 1);

/**
 * Add custom classes to product image.
 *
 * @param string $html Product image HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_image_classes($html) {
    $html = str_replace('class="attachment-woocommerce_thumbnail', 'class="attachment-woocommerce_thumbnail w-full h-auto transition-transform duration-500', $html);
    
    // Add product hover effect class
    $product_hover = get_theme_mod('aqualuxe_product_hover', 'zoom');
    if ('zoom' === $product_hover) {
        $html = str_replace('class="attachment-woocommerce_thumbnail', 'class="attachment-woocommerce_thumbnail hover:scale-105', $html);
    } elseif ('fade' === $product_hover) {
        $html = str_replace('class="attachment-woocommerce_thumbnail', 'class="attachment-woocommerce_thumbnail hover:opacity-80', $html);
    } elseif ('flip' === $product_hover) {
        $html = str_replace('class="attachment-woocommerce_thumbnail', 'class="attachment-woocommerce_thumbnail hover:rotate-y-180', $html);
    }
    
    return $html;
}
add_filter('woocommerce_product_get_image', 'aqualuxe_woocommerce_product_image_classes');

/**
 * Modify sale flash HTML.
 *
 * @param string $html Sale flash HTML.
 * @return string
 */
function aqualuxe_woocommerce_sale_flash($html) {
    $sale_badge_style = get_theme_mod('aqualuxe_sale_badge', 'circle');
    
    if ('circle' === $sale_badge_style) {
        $html = '<span class="onsale absolute top-4 left-4 bg-accent text-primary-dark text-xs font-bold px-3 py-3 rounded-full z-10">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    } elseif ('square' === $sale_badge_style) {
        $html = '<span class="onsale absolute top-4 left-4 bg-accent text-primary-dark text-xs font-bold px-3 py-3 z-10">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    } elseif ('ribbon' === $sale_badge_style) {
        $html = '<span class="onsale absolute top-4 left-0 bg-accent text-primary-dark text-xs font-bold px-4 py-1 z-10 before:absolute before:content-[\'\'] before:top-0 before:right-[-8px] before:border-l-[8px] before:border-l-accent before:border-b-[13px] before:border-b-transparent after:absolute after:content-[\'\'] after:bottom-0 after:right-[-8px] after:border-l-[8px] after:border-l-accent after:border-t-[13px] after:border-t-transparent">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    } elseif ('tag' === $sale_badge_style) {
        $html = '<span class="onsale absolute top-4 left-4 bg-accent text-primary-dark text-xs font-bold px-2 py-1 rounded-md z-10">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    return $html;
}
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash');

/**
 * Add quick view button to product loop.
 */
function aqualuxe_woocommerce_quick_view_button() {
    if (!get_theme_mod('aqualuxe_quick_view', true)) {
        return;
    }
    
    global $product;
    
    echo '<button class="quick-view-button text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    echo '</svg>';
    echo '</button>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Add wishlist button to product loop.
 */
function aqualuxe_woocommerce_wishlist_button() {
    if (!get_theme_mod('aqualuxe_wishlist', true)) {
        return;
    }
    
    global $product;
    
    echo '<button class="wishlist-button text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 ' . (aqualuxe_is_in_wishlist($product->get_id()) ? 'wishlist-active text-primary' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="' . (aqualuxe_is_in_wishlist($product->get_id()) ? 'currentColor' : 'none') . '" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    echo '</svg>';
    echo '</button>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * Add wishlist button to single product.
 */
function aqualuxe_woocommerce_single_wishlist_button() {
    if (!get_theme_mod('aqualuxe_wishlist', true)) {
        return;
    }
    
    global $product;
    
    echo '<div class="single-wishlist-button mt-4">';
    echo '<button class="wishlist-button flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 ' . (aqualuxe_is_in_wishlist($product->get_id()) ? 'wishlist-active text-primary' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="' . (aqualuxe_is_in_wishlist($product->get_id()) ? 'currentColor' : 'none') . '" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_single_wishlist_button', 35);

/**
 * Add product data tabs.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    // Add custom tab
    $tabs['custom_tab'] = [
        'title' => __('Care Instructions', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_custom_tab_content',
    ];
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

/**
 * Custom tab content.
 */
function aqualuxe_woocommerce_custom_tab_content() {
    global $product;
    
    // Get custom tab content from product meta
    $custom_tab_content = get_post_meta($product->get_id(), '_custom_tab_content', true);
    
    if (!empty($custom_tab_content)) {
        echo wp_kses_post($custom_tab_content);
    } else {
        // Default content
        echo '<h3>' . esc_html__('Care Instructions', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('Proper care is essential for the health and longevity of your aquatic pets and plants. Here are some general guidelines:', 'aqualuxe') . '</p>';
        echo '<ul>';
        echo '<li>' . esc_html__('Maintain proper water temperature and quality', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Feed appropriate amounts and types of food', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Perform regular water changes', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Monitor water parameters regularly', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Provide adequate lighting for plants', 'aqualuxe') . '</li>';
        echo '</ul>';
        echo '<p>' . esc_html__('For specific care instructions for this product, please contact our customer support team.', 'aqualuxe') . '</p>';
    }
}

/**
 * Add custom meta box for product custom tab content.
 */
function aqualuxe_woocommerce_add_custom_tab_meta_box() {
    add_meta_box(
        'aqualuxe_custom_tab',
        __('Care Instructions Tab Content', 'aqualuxe'),
        'aqualuxe_woocommerce_custom_tab_meta_box_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_custom_tab_meta_box');

/**
 * Custom tab meta box callback.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_custom_tab_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_custom_tab_nonce', 'aqualuxe_custom_tab_nonce');
    
    // Get current value
    $custom_tab_content = get_post_meta($post->ID, '_custom_tab_content', true);
    
    // Output field
    wp_editor($custom_tab_content, 'custom_tab_content', [
        'textarea_name' => 'custom_tab_content',
        'textarea_rows' => 10,
        'media_buttons' => true,
        'teeny' => true,
    ]);
    
    echo '<p class="description">' . esc_html__('Enter content for the Care Instructions tab. Leave empty to use default content.', 'aqualuxe') . '</p>';
}

/**
 * Save custom tab content.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_custom_tab_content($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_custom_tab_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_custom_tab_nonce'], 'aqualuxe_custom_tab_nonce')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save custom tab content
    if (isset($_POST['custom_tab_content'])) {
        update_post_meta($post_id, '_custom_tab_content', wp_kses_post($_POST['custom_tab_content']));
    }
}
add_action('save_post_product', 'aqualuxe_woocommerce_save_custom_tab_content');

/**
 * Add social sharing buttons to single product.
 */
function aqualuxe_woocommerce_social_sharing() {
    global $product;
    
    $product_url = urlencode(get_permalink());
    $product_title = urlencode(get_the_title());
    $product_image = urlencode(wp_get_attachment_url($product->get_image_id()));
    
    echo '<div class="social-sharing mt-6">';
    echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Share This Product', 'aqualuxe') . '</h3>';
    echo '<div class="flex space-x-2">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $product_url . '" target="_blank" rel="noopener noreferrer" class="social-share-link facebook bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . $product_url . '&text=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link twitter bg-blue-400 hover:bg-blue-500 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
    echo '</a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $product_url . '&title=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link linkedin bg-blue-700 hover:bg-blue-800 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
    echo '</a>';
    
    // Pinterest
    echo '<a href="https://pinterest.com/pin/create/button/?url=' . $product_url . '&media=' . $product_image . '&description=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link pinterest bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>';
    echo '</a>';
    
    // Email
    echo '<a href="mailto:?subject=' . $product_title . '&body=' . $product_url . '" class="social-share-link email bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_social_sharing', 50);

/**
 * Add product specifications to single product.
 */
function aqualuxe_woocommerce_product_specifications() {
    global $product;
    
    // Get product attributes
    $attributes = aqualuxe_get_product_attributes($product->get_id());
    
    if (!empty($attributes)) {
        echo '<div class="product-specifications mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">';
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Product Specifications', 'aqualuxe') . '</h3>';
        
        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        
        foreach ($attributes as $attribute) {
            echo '<div class="specification-item">';
            echo '<div class="specification-label font-bold">' . esc_html($attribute['name']) . '</div>';
            echo '<div class="specification-value">' . esc_html(implode(', ', $attribute['values'])) . '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 45);

/**
 * Add estimated delivery to single product.
 */
function aqualuxe_woocommerce_estimated_delivery() {
    global $product;
    
    // Get estimated delivery from product meta
    $estimated_delivery = get_post_meta($product->get_id(), '_estimated_delivery', true);
    
    if (empty($estimated_delivery)) {
        $estimated_delivery = '3-5 business days';
    }
    
    echo '<div class="estimated-delivery mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Estimated Delivery:', 'aqualuxe') . ' ' . esc_html($estimated_delivery) . '</span>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_estimated_delivery', 30);

/**
 * Add stock status with icon to single product.
 */
function aqualuxe_woocommerce_stock_status() {
    global $product;
    
    if ($product->is_in_stock()) {
        echo '<div class="stock-status mt-4 flex items-center text-sm text-green-600 dark:text-green-400">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
        echo '</svg>';
        echo '<span>' . esc_html__('In Stock', 'aqualuxe') . '</span>';
        echo '</div>';
    } else {
        echo '<div class="stock-status mt-4 flex items-center text-sm text-red-600 dark:text-red-400">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        echo '</svg>';
        echo '<span>' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_stock_status', 25);

/**
 * Add secure payment info to single product.
 */
function aqualuxe_woocommerce_secure_payment() {
    echo '<div class="secure-payment mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">';
    echo '<div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Secure Payment', 'aqualuxe') . '</span>';
    echo '</div>';
    
    echo '<div class="payment-methods flex items-center space-x-2">';
    echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/visa.svg') . '" alt="Visa" class="h-6">';
    echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/mastercard.svg') . '" alt="Mastercard" class="h-6">';
    echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/amex.svg') . '" alt="American Express" class="h-6">';
    echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/paypal.svg') . '" alt="PayPal" class="h-6">';
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_secure_payment', 55);

/**
 * Add recently viewed products.
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (!is_singular('product')) {
        return;
    }
    
    // Get current product ID
    $current_product_id = get_the_ID();
    
    // Get cookie
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : [];
    $viewed_products = array_filter(array_map('absint', $viewed_products));
    
    // Remove current product
    $viewed_products = array_diff($viewed_products, [$current_product_id]);
    
    // Show only if we have viewed products
    if (empty($viewed_products)) {
        return;
    }
    
    // Get products
    $args = [
        'post_type' => 'product',
        'post__in' => $viewed_products,
        'posts_per_page' => 4,
        'orderby' => 'post__in',
        'post_status' => 'publish',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<section class="recently-viewed-products mt-12">';
        echo '<h2 class="text-2xl font-bold mb-6">' . esc_html__('Recently Viewed Products', 'aqualuxe') . '</h2>';
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</section>';
        
        wp_reset_postdata();
    }
}
add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products');

/**
 * Track recently viewed products.
 */
function aqualuxe_woocommerce_track_product_view() {
    if (!is_singular('product')) {
        return;
    }
    
    global $post;
    
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = [];
    } else {
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }
    
    // Remove current product
    $viewed_products = array_diff($viewed_products, [$post->ID]);
    
    // Add current product to start of array
    array_unshift($viewed_products, $post->ID);
    
    // Limit to 15 products
    if (count($viewed_products) > 15) {
        $viewed_products = array_slice($viewed_products, 0, 15);
    }
    
    // Store in cookie
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_woocommerce_track_product_view', 20);

/**
 * Add product brand taxonomy.
 */
function aqualuxe_woocommerce_register_brand_taxonomy() {
    $labels = [
        'name' => __('Brands', 'aqualuxe'),
        'singular_name' => __('Brand', 'aqualuxe'),
        'search_items' => __('Search Brands', 'aqualuxe'),
        'all_items' => __('All Brands', 'aqualuxe'),
        'parent_item' => __('Parent Brand', 'aqualuxe'),
        'parent_item_colon' => __('Parent Brand:', 'aqualuxe'),
        'edit_item' => __('Edit Brand', 'aqualuxe'),
        'update_item' => __('Update Brand', 'aqualuxe'),
        'add_new_item' => __('Add New Brand', 'aqualuxe'),
        'new_item_name' => __('New Brand Name', 'aqualuxe'),
        'menu_name' => __('Brands', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'brand'],
    ];

    register_taxonomy('product_brand', ['product'], $args);
}
add_action('init', 'aqualuxe_woocommerce_register_brand_taxonomy');

/**
 * Add brand to product meta.
 */
function aqualuxe_woocommerce_product_brand() {
    global $product;
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        echo '<div class="product-brand mb-2">';
        echo '<span class="text-sm text-gray-600 dark:text-gray-400">' . esc_html__('Brand:', 'aqualuxe') . ' </span>';
        
        $brand_links = [];
        
        foreach ($brands as $brand) {
            $brand_links[] = '<a href="' . esc_url(get_term_link($brand)) . '" class="text-sm text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($brand->name) . '</a>';
        }
        
        echo implode(', ', $brand_links);
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 6);

/**
 * Add brand filter widget.
 */
function aqualuxe_woocommerce_brand_filter_widget() {
    class Aqualuxe_WC_Widget_Brand_Filter extends WC_Widget {
        /**
         * Constructor.
         */
        public function __construct() {
            $this->widget_cssclass = 'woocommerce widget_brand_filter';
            $this->widget_description = __('Display a list of product brands.', 'aqualuxe');
            $this->widget_id = 'aqualuxe_brand_filter';
            $this->widget_name = __('AquaLuxe Brand Filter', 'aqualuxe');
            $this->settings = [
                'title' => [
                    'type' => 'text',
                    'std' => __('Filter by Brand', 'aqualuxe'),
                    'label' => __('Title', 'aqualuxe'),
                ],
                'count' => [
                    'type' => 'checkbox',
                    'std' => 1,
                    'label' => __('Show product counts', 'aqualuxe'),
                ],
                'hierarchical' => [
                    'type' => 'checkbox',
                    'std' => 1,
                    'label' => __('Show hierarchy', 'aqualuxe'),
                ],
            ];
            
            parent::__construct();
        }
        
        /**
         * Output widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         */
        public function widget($args, $instance) {
            if (!is_shop() && !is_product_taxonomy()) {
                return;
            }
            
            $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
            $taxonomy = 'product_brand';
            $query_type = 'and';
            
            $get_terms_args = [
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
            ];
            
            $terms = get_terms($get_terms_args);
            
            if (0 === count($terms)) {
                return;
            }
            
            $this->widget_start($args, $instance);
            
            $found = $this->layered_nav_list($terms, $taxonomy, $query_type, $instance);
            
            $this->widget_end($args);
            
            if (!$found) {
                wp_reset_query();
                return;
            }
        }
        
        /**
         * Return the currently viewed taxonomy name.
         *
         * @return string
         */
        protected function get_current_taxonomy() {
            return is_tax() ? get_queried_object()->taxonomy : '';
        }
        
        /**
         * Return the currently viewed term ID.
         *
         * @return int
         */
        protected function get_current_term_id() {
            return is_tax() ? get_queried_object()->term_id : 0;
        }
        
        /**
         * Return the currently viewed term slug.
         *
         * @return string
         */
        protected function get_current_term_slug() {
            return is_tax() ? get_queried_object()->slug : '';
        }
        
        /**
         * Show list based layered nav.
         *
         * @param array $terms Terms.
         * @param string $taxonomy Taxonomy.
         * @param string $query_type Query type.
         * @param array $instance Widget instance.
         * @return bool Will nav display?
         */
        protected function layered_nav_list($terms, $taxonomy, $query_type, $instance) {
            // List display.
            echo '<ul class="woocommerce-widget-layered-nav-list">';
            
            $term_counts = $this->get_filtered_term_product_counts(wp_list_pluck($terms, 'term_id'), $taxonomy, $query_type);
            $found = false;
            $base_link = $this->get_current_page_url();
            
            foreach ($terms as $term) {
                $current_values = isset($_GET['filter_brand']) ? explode(',', wc_clean(wp_unslash($_GET['filter_brand']))) : [];
                $option_is_set = in_array($term->slug, $current_values, true);
                $count = isset($term_counts[$term->term_id]) ? $term_counts[$term->term_id] : 0;
                
                // Skip the term for the current archive.
                if ($this->get_current_term_id() === $term->term_id) {
                    continue;
                }
                
                // Only show options with count > 0.
                if (0 < $count) {
                    $found = true;
                }
                
                $filter_name = 'filter_brand';
                $current_filter = isset($_GET[$filter_name]) ? explode(',', wc_clean(wp_unslash($_GET[$filter_name]))) : [];
                $current_filter = array_map('sanitize_title', $current_filter);
                
                if (!in_array($term->slug, $current_filter, true)) {
                    $current_filter[] = $term->slug;
                }
                
                $link = remove_query_arg($filter_name, $base_link);
                
                // Add current filters to URL.
                foreach ($current_filter as $key => $value) {
                    // Exclude query arg for current term archive term.
                    if ($value === $this->get_current_term_slug()) {
                        unset($current_filter[$key]);
                    }
                }
                
                // Add current filters to URL.
                if (!empty($current_filter)) {
                    asort($current_filter);
                    $link = add_query_arg($filter_name, implode(',', $current_filter), $link);
                    
                    // Add Query type Arg to URL.
                    if ('or' === $query_type && !(1 === count($current_filter) && $option_is_set)) {
                        $link = add_query_arg('query_type_brand', 'or', $link);
                    }
                }
                
                if ($count > 0 || $option_is_set) {
                    $link = esc_url($link);
                    $term_html = '<a href="' . $link . '">' . esc_html($term->name) . '</a>';
                } else {
                    $link = false;
                    $term_html = '<span>' . esc_html($term->name) . '</span>';
                }
                
                if ($instance['count']) {
                    $term_html .= ' <span class="count">(' . absint($count) . ')</span>';
                }
                
                echo '<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term ' . ($option_is_set ? 'woocommerce-widget-layered-nav-list__item--chosen chosen' : '') . '">';
                echo wp_kses_post($term_html);
                echo '</li>';
            }
            
            echo '</ul>';
            
            return $found;
        }
        
        /**
         * Count products within certain terms, taking the main WP query into consideration.
         *
         * @param array $term_ids Term IDs.
         * @param string $taxonomy Taxonomy.
         * @param string $query_type Query Type.
         * @return array
         */
        protected function get_filtered_term_product_counts($term_ids, $taxonomy, $query_type) {
            global $wpdb;
            
            $tax_query = WC_Query::get_main_tax_query();
            $meta_query = WC_Query::get_main_meta_query();
            
            if ('or' === $query_type) {
                foreach ($tax_query as $key => $query) {
                    if (is_array($query) && $taxonomy === $query['taxonomy']) {
                        unset($tax_query[$key]);
                    }
                }
            }
            
            $meta_query = new WP_Meta_Query($meta_query);
            $tax_query = new WP_Tax_Query($tax_query);
            $meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
            $tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');
            
            // Generate query.
            $query = [];
            $query['select'] = "SELECT COUNT(DISTINCT {$wpdb->posts}.ID) as term_count, terms.term_id as term_count_id";
            $query['from'] = "FROM {$wpdb->posts}";
            $query['join'] = "
                INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
                INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING(term_taxonomy_id)
                INNER JOIN {$wpdb->terms} AS terms USING(term_id)
                " . $tax_query_sql['join'] . $meta_query_sql['join'];
            
            $query['where'] = "
                WHERE {$wpdb->posts}.post_type IN ('product')
                AND {$wpdb->posts}.post_status = 'publish'
                " . $tax_query_sql['where'] . $meta_query_sql['where'] . "
                AND terms.term_id IN (" . implode(',', array_map('absint', $term_ids)) . ")
            ";
            
            $query['group_by'] = 'GROUP BY terms.term_id';
            $query = apply_filters('woocommerce_get_filtered_term_product_counts_query', $query);
            $query = implode(' ', $query);
            
            // We have a query - let's see if cached results of this query already exist.
            $query_hash = md5($query);
            
            // Maybe store a transient of the count values.
            $cache = apply_filters('woocommerce_layered_nav_count_maybe_cache', true);
            if (true === $cache) {
                $cached_counts = (array) get_transient('wc_layered_nav_counts_' . sanitize_title($taxonomy));
            } else {
                $cached_counts = [];
            }
            
            if (!isset($cached_counts[$query_hash])) {
                $results = $wpdb->get_results($query, ARRAY_A); // WPCS: unprepared SQL ok.
                $counts = array_map('absint', wp_list_pluck($results, 'term_count', 'term_count_id'));
                
                if (true === $cache) {
                    $cached_counts[$query_hash] = $counts;
                    set_transient('wc_layered_nav_counts_' . sanitize_title($taxonomy), $cached_counts, DAY_IN_SECONDS);
                }
            }
            
            return array_map('absint', (array) $cached_counts[$query_hash]);
        }
        
        /**
         * Get current page URL for layered nav items.
         *
         * @return string
         */
        protected function get_current_page_url() {
            if (defined('SHOP_IS_ON_FRONT')) {
                $link = home_url();
            } elseif (is_shop()) {
                $link = get_permalink(wc_get_page_id('shop'));
            } elseif (is_product_category()) {
                $link = get_term_link(get_query_var('product_cat'), 'product_cat');
            } elseif (is_product_tag()) {
                $link = get_term_link(get_query_var('product_tag'), 'product_tag');
            } else {
                $queried_object = get_queried_object();
                $link = get_term_link($queried_object->slug, $queried_object->taxonomy);
            }
            
            // Min/Max.
            if (isset($_GET['min_price'])) {
                $link = add_query_arg('min_price', wc_clean(wp_unslash($_GET['min_price'])), $link);
            }
            
            if (isset($_GET['max_price'])) {
                $link = add_query_arg('max_price', wc_clean(wp_unslash($_GET['max_price'])), $link);
            }
            
            // Order by.
            if (isset($_GET['orderby'])) {
                $link = add_query_arg('orderby', wc_clean(wp_unslash($_GET['orderby'])), $link);
            }
            
            /**
             * Search Arg.
             * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
             */
            if (get_search_query()) {
                $link = add_query_arg('s', rawurlencode(htmlspecialchars_decode(get_search_query())), $link);
            }
            
            // Post Type Arg.
            if (isset($_GET['post_type'])) {
                $link = add_query_arg('post_type', wc_clean(wp_unslash($_GET['post_type'])), $link);
                
                // Prevent post type and page id when pretty permalinks are enabled.
                if (is_shop()) {
                    $link = remove_query_arg('page_id', $link);
                }
            }
            
            // Min Rating Arg.
            if (isset($_GET['rating_filter'])) {
                $link = add_query_arg('rating_filter', wc_clean(wp_unslash($_GET['rating_filter'])), $link);
            }
            
            // All current filters.
            if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
                foreach ($_chosen_attributes as $name => $data) {
                    $filter_name = sanitize_title(str_replace('pa_', 'filter_', $name));
                    if (!empty($data['terms'])) {
                        $link = add_query_arg($filter_name, implode(',', $data['terms']), $link);
                    }
                    if ('or' === $data['query_type']) {
                        $link = add_query_arg($filter_name . '_query_type', 'or', $link);
                    }
                }
            }
            
            return $link;
        }
    }
    
    register_widget('Aqualuxe_WC_Widget_Brand_Filter');
}
add_action('widgets_init', 'aqualuxe_woocommerce_brand_filter_widget');

/**
 * Add brand filter to product query.
 *
 * @param array $tax_query Tax query.
 * @param bool $main_query Main query.
 * @return array
 */
function aqualuxe_woocommerce_brand_filter_query($tax_query, $main_query) {
    if (!$main_query) {
        return $tax_query;
    }
    
    // Brand filter
    if (!empty($_GET['filter_brand'])) {
        $brands = array_filter(array_map('sanitize_title', explode(',', $_GET['filter_brand'])));
        
        if (!empty($brands)) {
            $tax_query[] = [
                'taxonomy' => 'product_brand',
                'field' => 'slug',
                'terms' => $brands,
                'operator' => 'IN',
            ];
        }
    }
    
    return $tax_query;
}
add_filter('woocommerce_product_query_tax_query', 'aqualuxe_woocommerce_brand_filter_query', 10, 2);

/**
 * Add AJAX add to cart functionality.
 */
function aqualuxe_woocommerce_ajax_add_to_cart() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error([
            'message' => __('Security check failed.', 'aqualuxe'),
        ]);
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error([
            'message' => __('No product selected.', 'aqualuxe'),
        ]);
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? $_POST['variation'] : [];
    
    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    
    if ($added) {
        $data = [
            'message' => __('Product added to cart.', 'aqualuxe'),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ];
        
        // Get mini cart HTML
        ob_start();
        woocommerce_mini_cart();
        $data['mini_cart'] = ob_get_clean();
        
        wp_send_json_success($data);
    } else {
        wp_send_json_error([
            'message' => __('Failed to add product to cart.', 'aqualuxe'),
        ]);
    }
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');

/**
 * Add AJAX update wishlist functionality.
 */
function aqualuxe_woocommerce_ajax_update_wishlist() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error([
            'message' => __('Security check failed.', 'aqualuxe'),
        ]);
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error([
            'message' => __('Please log in to add items to your wishlist.', 'aqualuxe'),
        ]);
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error([
            'message' => __('No product selected.', 'aqualuxe'),
        ]);
    }
    
    $product_id = absint($_POST['product_id']);
    $action = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : 'toggle';
    
    if ('add' === $action) {
        $result = aqualuxe_add_to_wishlist($product_id);
        $message = __('Product added to wishlist.', 'aqualuxe');
    } elseif ('remove' === $action) {
        $result = aqualuxe_remove_from_wishlist($product_id);
        $message = __('Product removed from wishlist.', 'aqualuxe');
    } else {
        // Toggle
        if (aqualuxe_is_in_wishlist($product_id)) {
            $result = aqualuxe_remove_from_wishlist($product_id);
            $message = __('Product removed from wishlist.', 'aqualuxe');
            $status = 'removed';
        } else {
            $result = aqualuxe_add_to_wishlist($product_id);
            $message = __('Product added to wishlist.', 'aqualuxe');
            $status = 'added';
        }
    }
    
    if ($result) {
        wp_send_json_success([
            'message' => $message,
            'count' => aqualuxe_get_wishlist_count(),
            'status' => isset($status) ? $status : $action,
        ]);
    } else {
        wp_send_json_error([
            'message' => __('Failed to update wishlist.', 'aqualuxe'),
        ]);
    }
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_ajax_update_wishlist', 'aqualuxe_woocommerce_ajax_update_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_ajax_update_wishlist', 'aqualuxe_woocommerce_ajax_update_wishlist');

/**
 * Add AJAX quick view functionality.
 */
function aqualuxe_woocommerce_ajax_quick_view() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error([
            'message' => __('Security check failed.', 'aqualuxe'),
        ]);
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error([
            'message' => __('No product selected.', 'aqualuxe'),
        ]);
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error([
            'message' => __('Product not found.', 'aqualuxe'),
        ]);
    }
    
    // Get quick view HTML
    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="quick-view-images">
                <?php
                if ($product->get_image_id()) {
                    echo wp_get_attachment_image($product->get_image_id(), 'woocommerce_single', false, [
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                } else {
                    echo wc_placeholder_img([
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                }
                ?>
            </div>
            <div class="quick-view-summary">
                <h2 class="product-title text-2xl font-bold mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                
                <?php if ($product->get_rating_count() > 0) : ?>
                <div class="product-rating flex items-center mb-3">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">(<?php echo $product->get_rating_count(); ?>)</span>
                </div>
                <?php endif; ?>
                
                <div class="product-price text-xl font-bold text-primary mb-4">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <div class="product-description mb-4">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
                
                <?php if ($product->is_in_stock()) : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-green-600 dark:text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                </div>
                <?php else : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-red-600 dark:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                    <div class="quantity mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                        <div class="flex">
                            <button type="button" class="quantity-button minus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-l">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" class="w-16 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <button type="button" class="quantity-button plus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-r">+</button>
                        </div>
                    </div>
                    
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Add to Cart', 'aqualuxe'); ?></button>
                </form>
                <?php elseif ($product->is_type('variable')) : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Select Options', 'aqualuxe'); ?></a>
                <?php else : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                <?php endif; ?>
                
                <div class="quick-view-actions flex justify-between mt-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300"><?php esc_html_e('View Details', 'aqualuxe'); ?></a>
                    
                    <?php if (get_theme_mod('aqualuxe_wishlist', true)) : ?>
                    <button class="wishlist-button flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 <?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'wishlist-active text-primary' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="<?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    
    wp_send_json_success([
        'html' => $html,
    ]);
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_ajax_quick_view', 'aqualuxe_woocommerce_ajax_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_ajax_quick_view', 'aqualuxe_woocommerce_ajax_quick_view');

/**
 * Add REST API endpoint for quick view.
 */
function aqualuxe_rest_get_product($request) {
    $product_id = $request->get_param('id');
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return new WP_Error('product_not_found', __('Product not found.', 'aqualuxe'), ['status' => 404]);
    }
    
    // Get quick view HTML
    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="quick-view-images">
                <?php
                if ($product->get_image_id()) {
                    echo wp_get_attachment_image($product->get_image_id(), 'woocommerce_single', false, [
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                } else {
                    echo wc_placeholder_img([
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                }
                ?>
            </div>
            <div class="quick-view-summary">
                <h2 class="product-title text-2xl font-bold mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                
                <?php if ($product->get_rating_count() > 0) : ?>
                <div class="product-rating flex items-center mb-3">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">(<?php echo $product->get_rating_count(); ?>)</span>
                </div>
                <?php endif; ?>
                
                <div class="product-price text-xl font-bold text-primary mb-4">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <div class="product-description mb-4">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
                
                <?php if ($product->is_in_stock()) : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-green-600 dark:text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                </div>
                <?php else : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-red-600 dark:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                    <div class="quantity mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                        <div class="flex">
                            <button type="button" class="quantity-button minus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-l">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" class="w-16 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <button type="button" class="quantity-button plus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-r">+</button>
                        </div>
                    </div>
                    
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Add to Cart', 'aqualuxe'); ?></button>
                </form>
                <?php elseif ($product->is_type('variable')) : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Select Options', 'aqualuxe'); ?></a>
                <?php else : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                <?php endif; ?>
                
                <div class="quick-view-actions flex justify-between mt-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300"><?php esc_html_e('View Details', 'aqualuxe'); ?></a>
                    
                    <?php if (get_theme_mod('aqualuxe_wishlist', true)) : ?>
                    <button class="wishlist-button flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 <?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'wishlist-active text-primary' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="<?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    
    return [
        'html' => $html,
    ];
}

/**
 * Add REST API endpoint for wishlist.
 */
function aqualuxe_rest_update_wishlist($request) {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', __('Please log in to add items to your wishlist.', 'aqualuxe'), ['status' => 401]);
    }
    
    $params = $request->get_json_params();
    
    // Check product ID
    if (!isset($params['product_id']) || empty($params['product_id'])) {
        return new WP_Error('no_product', __('No product selected.', 'aqualuxe'), ['status' => 400]);
    }
    
    $product_id = absint($params['product_id']);
    $action = isset($params['action']) ? sanitize_text_field($params['action']) : 'toggle';
    
    if ('add' === $action) {
        $result = aqualuxe_add_to_wishlist($product_id);
        $message = __('Product added to wishlist.', 'aqualuxe');
    } elseif ('remove' === $action) {
        $result = aqualuxe_remove_from_wishlist($product_id);
        $message = __('Product removed from wishlist.', 'aqualuxe');
    } else {
        // Toggle
        if (aqualuxe_is_in_wishlist($product_id)) {
            $result = aqualuxe_remove_from_wishlist($product_id);
            $message = __('Product removed from wishlist.', 'aqualuxe');
            $status = 'removed';
        } else {
            $result = aqualuxe_add_to_wishlist($product_id);
            $message = __('Product added to wishlist.', 'aqualuxe');
            $status = 'added';
        }
    }
    
    if ($result) {
        return [
            'success' => true,
            'message' => $message,
            'count' => aqualuxe_get_wishlist_count(),
            'status' => isset($status) ? $status : $action,
        ];
    } else {
        return new WP_Error('update_failed', __('Failed to update wishlist.', 'aqualuxe'), ['status' => 500]);
    }
}

/**
 * Add REST API endpoint for newsletter.
 */
function aqualuxe_rest_newsletter_subscribe($request) {
    $params = $request->get_json_params();
    
    // Check email
    if (!isset($params['email']) || empty($params['email'])) {
        return new WP_Error('no_email', __('No email provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $email = sanitize_email($params['email']);
    
    if (!is_email($email)) {
        return new WP_Error('invalid_email', __('Invalid email address.', 'aqualuxe'), ['status' => 400]);
    }
    
    // Store email in database
    $subscribers = get_option('aqualuxe_newsletter_subscribers', []);
    
    if (in_array($email, $subscribers)) {
        return [
            'success' => false,
            'message' => __('This email is already subscribed.', 'aqualuxe'),
        ];
    }
    
    $subscribers[] = $email;
    update_option('aqualuxe_newsletter_subscribers', $subscribers);
    
    // Send notification email to admin
    $admin_email = get_option('admin_email');
    $subject = sprintf(__('New Newsletter Subscription on %s', 'aqualuxe'), get_bloginfo('name'));
    $message = sprintf(__('A new user has subscribed to your newsletter with the email: %s', 'aqualuxe'), $email);
    
    wp_mail($admin_email, $subject, $message);
    
    return [
        'success' => true,
        'message' => __('Thank you for subscribing to our newsletter!', 'aqualuxe'),
    ];
}

/**
 * Add wishlist page.
 */
function aqualuxe_woocommerce_add_wishlist_page() {
    if (!get_page_by_path('wishlist')) {
        $page_id = wp_insert_post([
            'post_title' => __('Wishlist', 'aqualuxe'),
            'post_content' => '[aqualuxe_wishlist]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'wishlist',
        ]);
        
        if ($page_id) {
            update_option('aqualuxe_wishlist_page_id', $page_id);
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_woocommerce_add_wishlist_page');

/**
 * Add wishlist shortcode.
 */
function aqualuxe_woocommerce_wishlist_shortcode() {
    ob_start();
    
    if (!is_user_logged_in()) {
        echo '<div class="woocommerce-info">';
        echo esc_html__('Please log in to view your wishlist.', 'aqualuxe');
        echo ' <a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">' . esc_html__('Log in', 'aqualuxe') . '</a>';
        echo '</div>';
        return ob_get_clean();
    }
    
    $wishlist = aqualuxe_get_wishlist_items();
    
    if (empty($wishlist)) {
        echo '<div class="woocommerce-info">';
        echo esc_html__('Your wishlist is empty.', 'aqualuxe');
        echo ' <a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Browse products', 'aqualuxe') . '</a>';
        echo '</div>';
        return ob_get_clean();
    }
    
    echo '<div class="wishlist-items">';
    echo '<table class="shop_table shop_table_responsive cart wishlist-table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="product-remove">&nbsp;</th>';
    echo '<th class="product-thumbnail">&nbsp;</th>';
    echo '<th class="product-name">' . esc_html__('Product', 'aqualuxe') . '</th>';
    echo '<th class="product-price">' . esc_html__('Price', 'aqualuxe') . '</th>';
    echo '<th class="product-stock-status">' . esc_html__('Stock Status', 'aqualuxe') . '</th>';
    echo '<th class="product-add-to-cart">&nbsp;</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    foreach ($wishlist as $product_id) {
        $product = wc_get_product($product_id);
        
        if (!$product) {
            continue;
        }
        
        echo '<tr>';
        
        // Remove button
        echo '<td class="product-remove">';
        echo '<a href="#" class="remove wishlist-remove" data-product-id="' . esc_attr($product_id) . '">&times;</a>';
        echo '</td>';
        
        // Thumbnail
        echo '<td class="product-thumbnail">';
        echo '<a href="' . esc_url($product->get_permalink()) . '">';
        echo $product->get_image('woocommerce_thumbnail');
        echo '</a>';
        echo '</td>';
        
        // Name
        echo '<td class="product-name">';
        echo '<a href="' . esc_url($product->get_permalink()) . '">' . esc_html($product->get_name()) . '</a>';
        echo '</td>';
        
        // Price
        echo '<td class="product-price">';
        echo $product->get_price_html();
        echo '</td>';
        
        // Stock status
        echo '<td class="product-stock-status">';
        if ($product->is_in_stock()) {
            echo '<span class="in-stock text-green-600 dark:text-green-400">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
        } else {
            echo '<span class="out-of-stock text-red-600 dark:text-red-400">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }
        echo '</td>';
        
        // Add to cart
        echo '<td class="product-add-to-cart">';
        if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
            echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="button add_to_cart_button ajax_add_to_cart" data-product_id="' . esc_attr($product_id) . '">' . esc_html__('Add to Cart', 'aqualuxe') . '</a>';
        } elseif ($product->is_type('variable')) {
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('Select Options', 'aqualuxe') . '</a>';
        } else {
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('View Product', 'aqualuxe') . '</a>';
        }
        echo '</td>';
        
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_shortcode');

/**
 * Add newsletter shortcode.
 */
function aqualuxe_woocommerce_newsletter_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Subscribe to Our Newsletter', 'aqualuxe'),
        'description' => __('Get the latest updates, news and special offers delivered directly to your inbox.', 'aqualuxe'),
    ], $atts);
    
    ob_start();
    ?>
    <div class="newsletter-form p-6 bg-primary-light dark:bg-primary-dark rounded-lg text-center">
        <h3 class="text-2xl font-bold mb-2"><?php echo esc_html($atts['title']); ?></h3>
        <p class="mb-4"><?php echo esc_html($atts['description']); ?></p>
        
        <form id="newsletter-form" class="flex flex-wrap max-w-md mx-auto">
            <div class="w-full md:w-2/3 mb-2 md:mb-0 md:pr-2">
                <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required class="w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            </div>
            <div class="w-full md:w-1/3">
                <button type="submit" class="w-full btn btn-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
            </div>
            <div class="w-full mt-2 response-message"></div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('aqualuxe_newsletter', 'aqualuxe_woocommerce_newsletter_shortcode');

/**
 * Add featured products shortcode.
 */
function aqualuxe_woocommerce_featured_products_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Featured Products', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
    ], $atts);
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'tax_query' => [
            [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ],
        ],
    ];
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        echo '<div class="featured-products">';
        
        if ($atts['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($atts['title']) . '</h2>';
        }
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_featured_products', 'aqualuxe_woocommerce_featured_products_shortcode');

/**
 * Add sale products shortcode.
 */
function aqualuxe_woocommerce_sale_products_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Sale Products', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
    ], $atts);
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => '_sale_price',
                'value' => '',
                'compare' => '!=',
            ],
            [
                'key' => '_min_variation_sale_price',
                'value' => '',
                'compare' => '!=',
            ],
        ],
    ];
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        echo '<div class="sale-products">';
        
        if ($atts['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($atts['title']) . '</h2>';
        }
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_sale_products', 'aqualuxe_woocommerce_sale_products_shortcode');

/**
 * Add best selling products shortcode.
 */
function aqualuxe_woocommerce_best_selling_products_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Best Selling Products', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
    ], $atts);
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ];
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        echo '<div class="best-selling-products">';
        
        if ($atts['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($atts['title']) . '</h2>';
        }
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_best_selling_products', 'aqualuxe_woocommerce_best_selling_products_shortcode');

/**
 * Add product categories shortcode.
 */
function aqualuxe_woocommerce_product_categories_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Product Categories', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
        'hide_empty' => true,
    ], $atts);
    
    $args = [
        'taxonomy' => 'product_cat',
        'hide_empty' => $atts['hide_empty'],
        'number' => $atts['limit'],
        'orderby' => 'name',
        'order' => 'ASC',
    ];
    
    $categories = get_terms($args);
    
    ob_start();
    
    if (!empty($categories) && !is_wp_error($categories)) {
        echo '<div class="product-categories">';
        
        if ($atts['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($atts['title']) . '</h2>';
        }
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        foreach ($categories as $category) {
            $category_thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $category_image = $category_thumbnail_id ? wp_get_attachment_image_src($category_thumbnail_id, 'woocommerce_thumbnail') : '';
            
            echo '<div class="product-category card">';
            echo '<a href="' . esc_url(get_term_link($category)) . '" class="block">';
            
            if ($category_image) {
                echo '<img src="' . esc_url($category_image[0]) . '" alt="' . esc_attr($category->name) . '" class="w-full h-auto rounded-t-lg">';
            } else {
                echo wc_placeholder_img([
                    'class' => 'w-full h-auto rounded-t-lg',
                    'alt' => $category->name,
                ]);
            }
            
            echo '<div class="p-4">';
            echo '<h3 class="text-lg font-bold mb-2">' . esc_html($category->name) . '</h3>';
            
            if ($category->count > 0) {
                echo '<span class="text-sm text-gray-600 dark:text-gray-400">' . sprintf(_n('%s product', '%s products', $category->count, 'aqualuxe'), $category->count) . '</span>';
            }
            
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_product_categories', 'aqualuxe_woocommerce_product_categories_shortcode');

/**
 * Add product brands shortcode.
 */
function aqualuxe_woocommerce_product_brands_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Product Brands', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
        'hide_empty' => true,
    ], $atts);
    
    $args = [
        'taxonomy' => 'product_brand',
        'hide_empty' => $atts['hide_empty'],
        'number' => $atts['limit'],
        'orderby' => 'name',
        'order' => 'ASC',
    ];
    
    $brands = get_terms($args);
    
    ob_start();
    
    if (!empty($brands) && !is_wp_error($brands)) {
        echo '<div class="product-brands">';
        
        if ($atts['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($atts['title']) . '</h2>';
        }
        
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        foreach ($brands as $brand) {
            $brand_thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
            $brand_image = $brand_thumbnail_id ? wp_get_attachment_image_src($brand_thumbnail_id, 'woocommerce_thumbnail') : '';
            
            echo '<div class="product-brand card">';
            echo '<a href="' . esc_url(get_term_link($brand)) . '" class="block">';
            
            if ($brand_image) {
                echo '<img src="' . esc_url($brand_image[0]) . '" alt="' . esc_attr($brand->name) . '" class="w-full h-auto rounded-t-lg">';
            } else {
                echo wc_placeholder_img([
                    'class' => 'w-full h-auto rounded-t-lg',
                    'alt' => $brand->name,
                ]);
            }
            
            echo '<div class="p-4">';
            echo '<h3 class="text-lg font-bold mb-2">' . esc_html($brand->name) . '</h3>';
            
            if ($brand->count > 0) {
                echo '<span class="text-sm text-gray-600 dark:text-gray-400">' . sprintf(_n('%s product', '%s products', $brand->count, 'aqualuxe'), $brand->count) . '</span>';
            }
            
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_product_brands', 'aqualuxe_woocommerce_product_brands_shortcode');

/**
 * Add product filter shortcode.
 */
function aqualuxe_woocommerce_product_filter_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Filter Products', 'aqualuxe'),
    ], $atts);
    
    ob_start();
    
    echo '<div class="product-filters p-6 bg-white dark:bg-dark-card rounded-lg shadow-soft dark:shadow-none">';
    
    if ($atts['title']) {
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html($atts['title']) . '</h3>';
    }
    
    echo '<form id="product-filters" method="get" action="' . esc_url(wc_get_page_permalink('shop')) . '">';
    
    // Keep query string parameters
    foreach ($_GET as $key => $value) {
        if (!in_array($key, ['min_price', 'max_price', 'product_cat', 'product_tag', 'filter_color', 'filter_size', 'orderby'])) {
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        }
    }
    
    // Price filter
    echo '<div class="filter-section mb-6">';
    echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Price Range', 'aqualuxe') . '</h4>';
    
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '';
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '';
    
    echo '<div class="flex space-x-2">';
    echo '<div class="w-1/2">';
    echo '<label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Min', 'aqualuxe') . '</label>';
    echo '<input type="number" id="min_price" name="min_price" value="' . esc_attr($min_price) . '" min="0" step="1" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    echo '</div>';
    
    echo '<div class="w-1/2">';
    echo '<label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Max', 'aqualuxe') . '</label>';
    echo '<input type="number" id="max_price" name="max_price" value="' . esc_attr($max_price) . '" min="0" step="1" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // Categories filter
    $product_categories = aqualuxe_get_product_categories();
    
    if (!empty($product_categories)) {
        echo '<div class="filter-section mb-6">';
        echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Categories', 'aqualuxe') . '</h4>';
        
        $current_cat = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
        
        echo '<div class="space-y-2">';
        
        foreach ($product_categories as $category) {
            echo '<div class="flex items-center">';
            echo '<input type="checkbox" id="cat-' . esc_attr($category->slug) . '" name="product_cat[]" value="' . esc_attr($category->slug) . '" ' . (in_array($category->slug, explode(',', $current_cat)) ? 'checked' : '') . ' class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">';
            echo '<label for="cat-' . esc_attr($category->slug) . '" class="ml-2 text-sm text-gray-700 dark:text-gray-300">' . esc_html($category->name) . ' <span class="text-gray-500 dark:text-gray-500">(' . $category->count . ')</span></label>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    // Attributes filter (color, size, etc.)
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    if (!empty($attribute_taxonomies)) {
        foreach ($attribute_taxonomies as $attribute) {
            $taxonomy = 'pa_' . $attribute->attribute_name;
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
            ]);
            
            if (!empty($terms) && !is_wp_error($terms)) {
                $filter_name = 'filter_' . $attribute->attribute_name;
                $current_filter = isset($_GET[$filter_name]) ? sanitize_text_field($_GET[$filter_name]) : '';
                $current_filters = explode(',', $current_filter);
                
                echo '<div class="filter-section mb-6">';
                echo '<h4 class="text-lg font-bold mb-3">' . esc_html(ucfirst($attribute->attribute_label)) . '</h4>';
                
                echo '<div class="space-y-2">';
                
                foreach ($terms as $term) {
                    echo '<div class="flex items-center">';
                    echo '<input type="checkbox" id="' . esc_attr($filter_name . '-' . $term->slug) . '" name="' . esc_attr($filter_name) . '[]" value="' . esc_attr($term->slug) . '" ' . (in_array($term->slug, $current_filters) ? 'checked' : '') . ' class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">';
                    echo '<label for="' . esc_attr($filter_name . '-' . $term->slug) . '" class="ml-2 text-sm text-gray-700 dark:text-gray-300">' . esc_html($term->name) . ' <span class="text-gray-500 dark:text-gray-500">(' . $term->count . ')</span></label>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
        }
    }
    
    // Sort by
    echo '<div class="filter-section">';
    echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Sort By', 'aqualuxe') . '</h4>';
    
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
    
    $orderby_options = [
        'menu_order' => __('Default sorting', 'aqualuxe'),
        'popularity' => __('Sort by popularity', 'aqualuxe'),
        'rating' => __('Sort by average rating', 'aqualuxe'),
        'date' => __('Sort by latest', 'aqualuxe'),
        'price' => __('Sort by price: low to high', 'aqualuxe'),
        'price-desc' => __('Sort by price: high to low', 'aqualuxe'),
    ];
    
    echo '<select name="orderby" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    
    foreach ($orderby_options as $id => $name) {
        echo '<option value="' . esc_attr($id) . '" ' . selected($current_orderby, $id, false) . '>' . esc_html($name) . '</option>';
    }
    
    echo '</select>';
    echo '</div>';
    
    echo '<div class="filter-actions mt-6">';
    echo '<button type="submit" class="btn btn-primary w-full">' . esc_html__('Apply Filters', 'aqualuxe') . '</button>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_product_filter', 'aqualuxe_woocommerce_product_filter_shortcode');