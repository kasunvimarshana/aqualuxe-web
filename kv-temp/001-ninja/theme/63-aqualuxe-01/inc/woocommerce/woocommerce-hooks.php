<?php
/**
 * WooCommerce hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Remove default WooCommerce wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom wrappers
 */
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before', 10);
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after', 10);

/**
 * Output WooCommerce wrapper before
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <div class="container">
        <div class="row">
            <div id="primary" class="<?php echo esc_attr(aqualuxe_get_content_column_classes()); ?>">
                <main id="main" class="site-main" role="main">
    <?php
}

/**
 * Output WooCommerce wrapper after
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php if (aqualuxe_has_sidebar()) : ?>
                <aside id="secondary" class="<?php echo esc_attr(aqualuxe_get_sidebar_column_classes()); ?>">
                    <?php get_sidebar(); ?>
                </aside><!-- #secondary -->
            <?php endif; ?>
        </div><!-- .row -->
    </div><!-- .container -->
    <?php
}

/**
 * Modify product loop columns
 */
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Modify products per page
 */
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Modify product gallery thumbnail columns
 */
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');

/**
 * Modify related products args
 */
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Modify breadcrumb defaults
 */
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');

/**
 * Add cart fragment for cart count update
 */
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

/**
 * Add cart fragment for header cart
 */
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_header_add_to_cart_fragment');

/**
 * Modify sale flash
 */
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

/**
 * Add 'View Cart' button after 'Add to Cart' success message
 */
add_filter('wc_add_to_cart_message_html', 'aqualuxe_woocommerce_add_to_cart_message_html');

/**
 * Add Quick View button to product loop
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Add Wishlist button to product loop
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * Add Compare button to product loop
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25);

/**
 * Add AJAX handlers for Quick View, Wishlist, and Compare
 */
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_ajax');
add_action('wp_ajax_aqualuxe_compare', 'aqualuxe_woocommerce_compare_ajax');
add_action('wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_woocommerce_compare_ajax');

/**
 * Add WooCommerce specific settings to the Theme Customizer
 */
add_action('customize_register', 'aqualuxe_woocommerce_customize_register');

/**
 * Modify shop page layout
 */
add_filter('aqualuxe_page_layout', 'aqualuxe_woocommerce_page_layout');

/**
 * Get WooCommerce page layout
 *
 * @param string $layout Current layout.
 * @return string
 */
function aqualuxe_woocommerce_page_layout($layout) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $layout = aqualuxe_get_option('aqualuxe_shop_layout', 'right-sidebar');
    } elseif (is_product()) {
        $layout = aqualuxe_get_option('aqualuxe_product_layout', 'no-sidebar');
    }
    
    return $layout;
}

/**
 * Add product badges
 */
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_badges', 10);

/**
 * Output product badges
 */
function aqualuxe_woocommerce_product_badges() {
    global $product;
    
    echo aqualuxe_get_product_badges($product);
}

/**
 * Add product countdown
 */
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_countdown', 15);

/**
 * Output product countdown
 */
function aqualuxe_woocommerce_product_countdown() {
    global $product;
    
    echo aqualuxe_get_product_countdown($product);
}

/**
 * Add product categories to single product
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_categories_single', 5);

/**
 * Output product categories on single product
 */
function aqualuxe_woocommerce_product_categories_single() {
    global $product;
    
    echo aqualuxe_get_product_categories($product);
}

/**
 * Add product sharing to single product
 */
add_action('woocommerce_share', 'aqualuxe_woocommerce_product_sharing');

/**
 * Output product sharing
 */
function aqualuxe_woocommerce_product_sharing() {
    global $product;
    
    if (aqualuxe_get_option('aqualuxe_enable_product_share', true)) {
        echo aqualuxe_get_product_sharing($product);
    }
}

/**
 * Add product actions to single product
 */
add_action('woocommerce_after_add_to_cart_button', 'aqualuxe_woocommerce_product_actions_single', 15);

/**
 * Output product actions on single product
 */
function aqualuxe_woocommerce_product_actions_single() {
    global $product;
    
    echo '<div class="product-actions-single">';
    
    if (aqualuxe_get_option('aqualuxe_enable_wishlist', true)) {
        echo aqualuxe_get_wishlist_button($product);
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_compare', true)) {
        echo aqualuxe_get_compare_button($product);
    }
    
    echo '</div>';
}

/**
 * Add product stock status to single product
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_status_single', 25);

/**
 * Output product stock status on single product
 */
function aqualuxe_woocommerce_product_stock_status_single() {
    global $product;
    
    echo aqualuxe_get_product_stock_status($product);
}

/**
 * Add product meta wrapper start to single product
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta_start', 39);

/**
 * Output product meta wrapper start
 */
function aqualuxe_woocommerce_product_meta_start() {
    echo '<div class="product-meta">';
}

/**
 * Add product meta wrapper end to single product
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta_end', 41);

/**
 * Output product meta wrapper end
 */
function aqualuxe_woocommerce_product_meta_end() {
    echo '</div>';
}

/**
 * Add shop filters
 */
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_filters', 15);

/**
 * Output shop filters
 */
function aqualuxe_woocommerce_shop_filters() {
    if (!aqualuxe_get_option('aqualuxe_enable_shop_filters', true)) {
        return;
    }
    
    ?>
    <div class="shop-filters">
        <?php do_action('aqualuxe_shop_filters'); ?>
    </div>
    <?php
}

/**
 * Add shop view switcher
 */
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_view_switcher', 20);

/**
 * Output shop view switcher
 */
function aqualuxe_woocommerce_shop_view_switcher() {
    if (!aqualuxe_get_option('aqualuxe_enable_shop_view_switcher', true)) {
        return;
    }
    
    $current_view = isset($_COOKIE['aqualuxe_shop_view']) ? $_COOKIE['aqualuxe_shop_view'] : 'grid';
    ?>
    <div class="shop-view-switcher">
        <button class="shop-view-button grid-view<?php echo $current_view === 'grid' ? ' active' : ''; ?>" data-view="grid">
            <i class="fas fa-th" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php esc_html_e('Grid View', 'aqualuxe'); ?></span>
        </button>
        <button class="shop-view-button list-view<?php echo $current_view === 'list' ? ' active' : ''; ?>" data-view="list">
            <i class="fas fa-list" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php esc_html_e('List View', 'aqualuxe'); ?></span>
        </button>
    </div>
    <?php
}

/**
 * Add product loop start wrapper
 */
add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_product_loop_start', 5);

/**
 * Output product loop start wrapper
 */
function aqualuxe_woocommerce_product_loop_start() {
    echo '<div class="product-inner">';
}

/**
 * Add product loop end wrapper
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_product_loop_end', 50);

/**
 * Output product loop end wrapper
 */
function aqualuxe_woocommerce_product_loop_end() {
    echo '</div>';
}

/**
 * Add product categories to product loop
 */
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_product_categories', 5);

/**
 * Output product categories in product loop
 */
function aqualuxe_woocommerce_product_categories() {
    global $product;
    
    echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories">', '</div>');
}

/**
 * Add product rating to product loop
 */
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_rating', 5);

/**
 * Output product rating in product loop
 */
function aqualuxe_woocommerce_product_rating() {
    global $product;
    
    if ($product->get_rating_count()) {
        echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
    }
}

/**
 * Add product gallery wrapper start
 */
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_gallery_wrapper_start', 5);

/**
 * Output product gallery wrapper start
 */
function aqualuxe_woocommerce_product_gallery_wrapper_start() {
    echo '<div class="product-gallery-wrapper">';
}

/**
 * Add product gallery wrapper end
 */
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_gallery_wrapper_end', 30);

/**
 * Output product gallery wrapper end
 */
function aqualuxe_woocommerce_product_gallery_wrapper_end() {
    echo '</div>';
}

/**
 * Add cart progress
 */
add_action('woocommerce_before_cart', 'aqualuxe_woocommerce_cart_progress', 10);

/**
 * Output cart progress
 */
function aqualuxe_woocommerce_cart_progress() {
    ?>
    <div class="cart-progress">
        <div class="cart-progress-steps">
            <div class="cart-progress-step current">
                <span class="cart-progress-step-number">1</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step">
                <span class="cart-progress-step-number">2</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step">
                <span class="cart-progress-step-number">3</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add checkout progress
 */
add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_progress', 10);

/**
 * Output checkout progress
 */
function aqualuxe_woocommerce_checkout_progress() {
    ?>
    <div class="cart-progress">
        <div class="cart-progress-steps">
            <div class="cart-progress-step completed">
                <span class="cart-progress-step-number">1</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step current">
                <span class="cart-progress-step-number">2</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step">
                <span class="cart-progress-step-number">3</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add account welcome message
 */
add_action('woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_welcome', 10);

/**
 * Output account welcome message
 */
function aqualuxe_woocommerce_account_welcome() {
    if (!is_user_logged_in()) {
        return;
    }
    
    $current_user = wp_get_current_user();
    ?>
    <div class="account-welcome">
        <h2 class="account-welcome-title">
            <?php
            /* translators: %s: user display name */
            printf(esc_html__('Welcome, %s', 'aqualuxe'), esc_html($current_user->display_name));
            ?>
        </h2>
    </div>
    <?php
}

/**
 * Add order received progress
 */
add_action('woocommerce_before_thankyou', 'aqualuxe_woocommerce_order_received_progress', 10);

/**
 * Output order received progress
 */
function aqualuxe_woocommerce_order_received_progress() {
    ?>
    <div class="cart-progress">
        <div class="cart-progress-steps">
            <div class="cart-progress-step completed">
                <span class="cart-progress-step-number">1</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step completed">
                <span class="cart-progress-step-number">2</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
            </div>
            <div class="cart-progress-step current">
                <span class="cart-progress-step-number">3</span>
                <span class="cart-progress-step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add product social share
 */
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_social_share', 15);

/**
 * Output product social share
 */
function aqualuxe_woocommerce_product_social_share() {
    global $product;
    
    if (aqualuxe_get_option('aqualuxe_enable_product_share', true)) {
        echo aqualuxe_get_product_sharing($product);
    }
}

/**
 * Add Quick View modal to footer
 */
add_action('wp_footer', 'aqualuxe_woocommerce_quick_view_modal');

/**
 * Output Quick View modal
 */
function aqualuxe_woocommerce_quick_view_modal() {
    if (!aqualuxe_get_option('aqualuxe_enable_quick_view', true)) {
        return;
    }
    
    ?>
    <div id="quick-view-modal" class="quick-view-modal" style="display: none;">
        <div class="quick-view-modal-content">
            <button class="quick-view-close">&times;</button>
            <div class="quick-view-content-wrapper">
                <div class="quick-view-loader">
                    <div class="spinner"></div>
                </div>
                <div class="quick-view-content"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add Compare modal to footer
 */
add_action('wp_footer', 'aqualuxe_woocommerce_compare_modal');

/**
 * Output Compare modal
 */
function aqualuxe_woocommerce_compare_modal() {
    if (!aqualuxe_get_option('aqualuxe_enable_compare', true)) {
        return;
    }
    
    ?>
    <div id="compare-modal" class="compare-modal" style="display: none;">
        <div class="compare-modal-content">
            <button class="compare-close">&times;</button>
            <div class="compare-content-wrapper">
                <div class="compare-loader">
                    <div class="spinner"></div>
                </div>
                <div class="compare-content">
                    <h2><?php esc_html_e('Compare Products', 'aqualuxe'); ?></h2>
                    <div class="compare-products"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add Compare button to footer
 */
add_action('wp_footer', 'aqualuxe_woocommerce_compare_button_footer');

/**
 * Output Compare button in footer
 */
function aqualuxe_woocommerce_compare_button_footer() {
    if (!aqualuxe_get_option('aqualuxe_enable_compare', true)) {
        return;
    }
    
    // Get compare list from cookie
    $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? json_decode(stripslashes($_COOKIE['aqualuxe_compare_list']), true) : array();
    
    if (!is_array($compare_list)) {
        $compare_list = array();
    }
    
    $count = count($compare_list);
    
    if ($count > 0) {
        ?>
        <div class="compare-button-footer">
            <button class="compare-open-button">
                <i class="fas fa-exchange-alt" aria-hidden="true"></i>
                <span class="compare-count"><?php echo esc_html($count); ?></span>
                <span class="compare-text"><?php esc_html_e('Compare', 'aqualuxe'); ?></span>
            </button>
        </div>
        <?php
    }
}

/**
 * Add AJAX handler for Compare products
 */
add_action('wp_ajax_aqualuxe_get_compare_products', 'aqualuxe_woocommerce_get_compare_products_ajax');
add_action('wp_ajax_nopriv_aqualuxe_get_compare_products', 'aqualuxe_woocommerce_get_compare_products_ajax');

/**
 * AJAX handler for Compare products
 */
function aqualuxe_woocommerce_get_compare_products_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_woocommerce_nonce')) {
        wp_send_json_error('Invalid request');
    }
    
    // Get compare list from cookie
    $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? json_decode(stripslashes($_COOKIE['aqualuxe_compare_list']), true) : array();
    
    if (!is_array($compare_list) || empty($compare_list)) {
        wp_send_json_error('No products to compare');
    }
    
    $products = array();
    
    foreach ($compare_list as $product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'image' => $product->get_image('thumbnail'),
                'price' => $product->get_price_html(),
                'rating' => $product->get_average_rating() ? wc_get_rating_html($product->get_average_rating()) : '',
                'description' => $product->get_short_description(),
                'url' => get_permalink($product->get_id()),
                'add_to_cart' => sprintf(
                    '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(implode(' ', array_filter(array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                    )))),
                    wc_implode_html_attributes(array(
                        'data-product_id' => $product->get_id(),
                        'data-product_sku' => $product->get_sku(),
                        'aria-label' => $product->add_to_cart_description(),
                        'rel' => 'nofollow',
                    )),
                    esc_html($product->add_to_cart_text())
                ),
                'attributes' => aqualuxe_woocommerce_get_product_attributes_for_compare($product),
            );
        }
    }
    
    ob_start();
    ?>
    <table class="compare-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                <?php foreach ($products as $product) : ?>
                    <th>
                        <div class="compare-product-header">
                            <div class="compare-product-image">
                                <?php echo $product['image']; ?>
                            </div>
                            <h3 class="compare-product-title">
                                <a href="<?php echo esc_url($product['url']); ?>"><?php echo esc_html($product['name']); ?></a>
                            </h3>
                            <div class="compare-product-price">
                                <?php echo $product['price']; ?>
                            </div>
                            <div class="compare-product-rating">
                                <?php echo $product['rating']; ?>
                            </div>
                            <div class="compare-product-description">
                                <?php echo wp_kses_post($product['description']); ?>
                            </div>
                            <div class="compare-product-actions">
                                <?php echo $product['add_to_cart']; ?>
                                <button class="button compare-remove" data-product-id="<?php echo esc_attr($product['id']); ?>"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                            </div>
                        </div>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products[0]['attributes'] as $attribute_name => $attribute_value) : ?>
                <tr>
                    <th><?php echo esc_html($attribute_name); ?></th>
                    <?php foreach ($products as $product) : ?>
                        <td><?php echo isset($product['attributes'][$attribute_name]) ? wp_kses_post($product['attributes'][$attribute_name]) : '-'; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    
    $output = ob_get_clean();
    
    wp_send_json_success($output);
}

/**
 * Get product attributes for compare
 *
 * @param WC_Product $product Product object.
 * @return array
 */
function aqualuxe_woocommerce_get_product_attributes_for_compare($product) {
    $attributes = array();
    
    // SKU
    $attributes[__('SKU', 'aqualuxe')] = $product->get_sku() ? $product->get_sku() : '-';
    
    // Stock
    $attributes[__('Stock', 'aqualuxe')] = $product->is_in_stock() ? __('In Stock', 'aqualuxe') : __('Out of Stock', 'aqualuxe');
    
    // Weight
    $attributes[__('Weight', 'aqualuxe')] = $product->get_weight() ? wc_format_weight($product->get_weight()) : '-';
    
    // Dimensions
    $dimensions = array();
    
    if ($product->get_length()) {
        $dimensions[] = wc_format_localized_decimal($product->get_length()) . ' ' . get_option('woocommerce_dimension_unit');
    }
    
    if ($product->get_width()) {
        $dimensions[] = wc_format_localized_decimal($product->get_width()) . ' ' . get_option('woocommerce_dimension_unit');
    }
    
    if ($product->get_height()) {
        $dimensions[] = wc_format_localized_decimal($product->get_height()) . ' ' . get_option('woocommerce_dimension_unit');
    }
    
    $attributes[__('Dimensions', 'aqualuxe')] = !empty($dimensions) ? implode(' x ', $dimensions) : '-';
    
    // Product attributes
    $product_attributes = $product->get_attributes();
    
    if (!empty($product_attributes)) {
        foreach ($product_attributes as $attribute) {
            if ($attribute->get_visible()) {
                $attribute_name = wc_attribute_label($attribute->get_name());
                
                if ($attribute->is_taxonomy()) {
                    $attribute_values = array();
                    $attribute_taxonomy = $attribute->get_taxonomy_object();
                    $attribute_terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));
                    
                    foreach ($attribute_terms as $attribute_term) {
                        $attribute_values[] = $attribute_term->name;
                    }
                    
                    $attributes[$attribute_name] = implode(', ', $attribute_values);
                } else {
                    $attributes[$attribute_name] = implode(', ', $attribute->get_options());
                }
            }
        }
    }
    
    return $attributes;
}

/**
 * Add AJAX handler for Wishlist products
 */
add_action('wp_ajax_aqualuxe_get_wishlist_products', 'aqualuxe_woocommerce_get_wishlist_products_ajax');
add_action('wp_ajax_nopriv_aqualuxe_get_wishlist_products', 'aqualuxe_woocommerce_get_wishlist_products_ajax');

/**
 * AJAX handler for Wishlist products
 */
function aqualuxe_woocommerce_get_wishlist_products_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_woocommerce_nonce')) {
        wp_send_json_error('Invalid request');
    }
    
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }
    
    $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
    
    if (!$wishlist || !is_array($wishlist) || empty($wishlist)) {
        wp_send_json_error('No products in wishlist');
    }
    
    $products = array();
    
    foreach ($wishlist as $product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'image' => $product->get_image('thumbnail'),
                'price' => $product->get_price_html(),
                'url' => get_permalink($product->get_id()),
                'add_to_cart' => sprintf(
                    '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(implode(' ', array_filter(array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                    )))),
                    wc_implode_html_attributes(array(
                        'data-product_id' => $product->get_id(),
                        'data-product_sku' => $product->get_sku(),
                        'aria-label' => $product->add_to_cart_description(),
                        'rel' => 'nofollow',
                    )),
                    esc_html($product->add_to_cart_text())
                ),
            );
        }
    }
    
    ob_start();
    ?>
    <table class="wishlist-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td>
                        <div class="wishlist-product">
                            <div class="wishlist-product-image">
                                <?php echo $product['image']; ?>
                            </div>
                            <div class="wishlist-product-info">
                                <h3 class="wishlist-product-title">
                                    <a href="<?php echo esc_url($product['url']); ?>"><?php echo esc_html($product['name']); ?></a>
                                </h3>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wishlist-product-price">
                            <?php echo $product['price']; ?>
                        </div>
                    </td>
                    <td>
                        <div class="wishlist-product-actions">
                            <?php echo $product['add_to_cart']; ?>
                            <button class="button wishlist-remove" data-product-id="<?php echo esc_attr($product['id']); ?>"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    
    $output = ob_get_clean();
    
    wp_send_json_success($output);
}

/**
 * Add Wishlist page
 */
add_action('init', 'aqualuxe_woocommerce_add_wishlist_endpoint');

/**
 * Add Wishlist endpoint
 */
function aqualuxe_woocommerce_add_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}

/**
 * Add Wishlist to My Account menu
 *
 * @param array $items Menu items.
 * @return array
 */
function aqualuxe_woocommerce_add_wishlist_to_account_menu($items) {
    // Remove the logout menu item
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);
    
    // Add the wishlist menu item
    $items['wishlist'] = __('Wishlist', 'aqualuxe');
    
    // Add the logout menu item back to the end
    $items['customer-logout'] = $logout;
    
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_add_wishlist_to_account_menu');

/**
 * Add Wishlist content
 */
function aqualuxe_woocommerce_wishlist_content() {
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        return;
    }
    
    $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
    
    if (!$wishlist || !is_array($wishlist) || empty($wishlist)) {
        echo '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
        return;
    }
    
    $products = array();
    
    foreach ($wishlist as $product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = $product;
        }
    }
    
    if (empty($products)) {
        echo '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
        return;
    }
    
    ?>
    <table class="wishlist-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td>
                        <div class="wishlist-product">
                            <div class="wishlist-product-image">
                                <?php echo $product->get_image('thumbnail'); ?>
                            </div>
                            <div class="wishlist-product-info">
                                <h3 class="wishlist-product-title">
                                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"><?php echo esc_html($product->get_name()); ?></a>
                                </h3>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wishlist-product-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                    </td>
                    <td>
                        <div class="wishlist-product-actions">
                            <?php
                            echo sprintf(
                                '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                                esc_url($product->add_to_cart_url()),
                                esc_attr(implode(' ', array_filter(array(
                                    'button',
                                    'product_type_' . $product->get_type(),
                                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                                )))),
                                wc_implode_html_attributes(array(
                                    'data-product_id' => $product->get_id(),
                                    'data-product_sku' => $product->get_sku(),
                                    'aria-label' => $product->add_to_cart_description(),
                                    'rel' => 'nofollow',
                                )),
                                esc_html($product->add_to_cart_text())
                            );
                            ?>
                            <button class="button wishlist-remove" data-product-id="<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_woocommerce_wishlist_content');

/**
 * Add Compare page
 */
add_action('init', 'aqualuxe_woocommerce_add_compare_endpoint');

/**
 * Add Compare endpoint
 */
function aqualuxe_woocommerce_add_compare_endpoint() {
    add_rewrite_endpoint('compare', EP_ROOT | EP_PAGES);
}

/**
 * Add Compare to My Account menu
 *
 * @param array $items Menu items.
 * @return array
 */
function aqualuxe_woocommerce_add_compare_to_account_menu($items) {
    // Remove the logout menu item
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);
    
    // Add the compare menu item
    $items['compare'] = __('Compare', 'aqualuxe');
    
    // Add the logout menu item back to the end
    $items['customer-logout'] = $logout;
    
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_add_compare_to_account_menu');

/**
 * Add Compare content
 */
function aqualuxe_woocommerce_compare_content() {
    // Get compare list from cookie
    $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? json_decode(stripslashes($_COOKIE['aqualuxe_compare_list']), true) : array();
    
    if (!is_array($compare_list) || empty($compare_list)) {
        echo '<p>' . esc_html__('Your compare list is empty.', 'aqualuxe') . '</p>';
        return;
    }
    
    $products = array();
    
    foreach ($compare_list as $product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'image' => $product->get_image('thumbnail'),
                'price' => $product->get_price_html(),
                'rating' => $product->get_average_rating() ? wc_get_rating_html($product->get_average_rating()) : '',
                'description' => $product->get_short_description(),
                'url' => get_permalink($product->get_id()),
                'add_to_cart' => sprintf(
                    '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(implode(' ', array_filter(array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                    )))),
                    wc_implode_html_attributes(array(
                        'data-product_id' => $product->get_id(),
                        'data-product_sku' => $product->get_sku(),
                        'aria-label' => $product->add_to_cart_description(),
                        'rel' => 'nofollow',
                    )),
                    esc_html($product->add_to_cart_text())
                ),
                'attributes' => aqualuxe_woocommerce_get_product_attributes_for_compare($product),
            );
        }
    }
    
    if (empty($products)) {
        echo '<p>' . esc_html__('Your compare list is empty.', 'aqualuxe') . '</p>';
        return;
    }
    
    ?>
    <table class="compare-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                <?php foreach ($products as $product) : ?>
                    <th>
                        <div class="compare-product-header">
                            <div class="compare-product-image">
                                <?php echo $product['image']; ?>
                            </div>
                            <h3 class="compare-product-title">
                                <a href="<?php echo esc_url($product['url']); ?>"><?php echo esc_html($product['name']); ?></a>
                            </h3>
                            <div class="compare-product-price">
                                <?php echo $product['price']; ?>
                            </div>
                            <div class="compare-product-rating">
                                <?php echo $product['rating']; ?>
                            </div>
                            <div class="compare-product-description">
                                <?php echo wp_kses_post($product['description']); ?>
                            </div>
                            <div class="compare-product-actions">
                                <?php echo $product['add_to_cart']; ?>
                                <button class="button compare-remove" data-product-id="<?php echo esc_attr($product['id']); ?>"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                            </div>
                        </div>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products[0]['attributes'] as $attribute_name => $attribute_value) : ?>
                <tr>
                    <th><?php echo esc_html($attribute_name); ?></th>
                    <?php foreach ($products as $product) : ?>
                        <td><?php echo isset($product['attributes'][$attribute_name]) ? wp_kses_post($product['attributes'][$attribute_name]) : '-'; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
add_action('woocommerce_account_compare_endpoint', 'aqualuxe_woocommerce_compare_content');

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_woocommerce_flush_rewrite_rules() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('compare', EP_ROOT | EP_PAGES);
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_woocommerce_flush_rewrite_rules');