<?php
/**
 * AquaLuxe WooCommerce Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display the product badges
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_badges($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-badges">';
    
    // Sale badge
    if ($product->is_on_sale()) {
        echo '<span class="badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    // New badge (products published within the last 30 days)
    $days_as_new = 30;
    $post_date = get_the_time('U');
    $current_date = current_time('timestamp');
    $seconds_in_day = 86400;
    
    if (($current_date - $post_date) < ($days_as_new * $seconds_in_day)) {
        echo '<span class="badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if ($product->is_featured()) {
        echo '<span class="badge featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    echo '</div>';
}

/**
 * Display the product stock status
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_stock_status($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->is_in_stock()) {
        echo '<div class="stock in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</div>';
    } else {
        echo '<div class="stock out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</div>';
    }
}

/**
 * Display the product share buttons
 */
function aqualuxe_woocommerce_product_share() {
    $product_url = urlencode(get_permalink());
    $product_title = urlencode(get_the_title());
    $product_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    
    ?>
    <div class="product-share">
        <span class="share-title"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
        <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $product_url; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a class="share-twitter" href="https://twitter.com/intent/tweet?url=<?php echo $product_url; ?>&text=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-twitter"></i>
        </a>
        <a class="share-pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo $product_url; ?>&media=<?php echo $product_thumbnail; ?>&description=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-pinterest-p"></i>
        </a>
        <a class="share-email" href="mailto:?subject=<?php echo $product_title; ?>&body=<?php echo $product_url; ?>">
            <i class="fas fa-envelope"></i>
        </a>
    </div>
    <?php
}

/**
 * Display the quick view button
 * 
 * @param int $product_id Product ID.
 */
function aqualuxe_woocommerce_quick_view_button($product_id = null) {
    if (!$product_id) {
        global $product;
        $product_id = $product->get_id();
    }
    
    if (aqualuxe_get_option('show_quick_view', true)) {
        echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
    }
}

/**
 * Display the wishlist button
 * 
 * @param int $product_id Product ID.
 */
function aqualuxe_woocommerce_wishlist_button($product_id = null) {
    if (!$product_id) {
        global $product;
        $product_id = $product->get_id();
    }
    
    if (aqualuxe_get_option('show_wishlist', true)) {
        // Check if YITH WooCommerce Wishlist is active
        if (defined('YITH_WCWL') && YITH_WCWL) {
            echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product_id . '"]');
        } else {
            // Fallback wishlist button
            echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
        }
    }
}

/**
 * Display the recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (!aqualuxe_get_option('show_recently_viewed', true)) {
        return;
    }
    
    // Get recently viewed products
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
    $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
    
    if (empty($viewed_products)) {
        return;
    }
    
    // Remove current product
    $current_product_id = get_the_ID();
    $viewed_products = array_diff($viewed_products, array($current_product_id));
    
    if (empty($viewed_products)) {
        return;
    }
    
    // Limit to 4 products
    $viewed_products = array_slice($viewed_products, 0, 4);
    
    // Get products
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
        'posts_per_page' => 4,
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        ?>
        <section class="recently-viewed-products">
            <h2><?php esc_html_e('Recently Viewed', 'aqualuxe'); ?></h2>
            <ul class="products columns-4">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </ul>
        </section>
        <?php
    }
    
    wp_reset_postdata();
}

/**
 * Display the cart progress
 */
function aqualuxe_woocommerce_cart_progress() {
    ?>
    <div class="cart-progress">
        <div class="cart-progress-step current">
            <span class="step-number">1</span>
            <span class="step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
        </div>
        <div class="cart-progress-step">
            <span class="step-number">2</span>
            <span class="step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
        </div>
        <div class="cart-progress-step">
            <span class="step-number">3</span>
            <span class="step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Display the checkout progress
 */
function aqualuxe_woocommerce_checkout_progress() {
    ?>
    <div class="checkout-progress">
        <div class="checkout-progress-step completed">
            <span class="step-number">1</span>
            <span class="step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-progress-step current">
            <span class="step-number">2</span>
            <span class="step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-progress-step">
            <span class="step-number">3</span>
            <span class="step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Display the shop filter toggle
 */
function aqualuxe_woocommerce_shop_filter_toggle() {
    ?>
    <div class="shop-filter-toggle">
        <button class="filter-toggle-button">
            <i class="fas fa-filter"></i>
            <span><?php esc_html_e('Filter', 'aqualuxe'); ?></span>
        </button>
    </div>
    <?php
}

/**
 * Display the shop active filters
 */
function aqualuxe_woocommerce_shop_active_filters() {
    the_widget('WC_Widget_Layered_Nav_Filters');
}

/**
 * Display the header cart
 */
function aqualuxe_woocommerce_header_cart() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_cart', true)) {
        return;
    }
    
    ?>
    <div class="header-cart">
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
        </a>
        <div class="cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the header account
 */
function aqualuxe_woocommerce_header_account() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_account', true)) {
        return;
    }
    
    ?>
    <div class="header-account">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" title="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
            <i class="fas fa-user"></i>
        </a>
        <?php if (!is_user_logged_in()) : ?>
            <div class="account-dropdown">
                <div class="account-dropdown-inner">
                    <h3><?php esc_html_e('Login', 'aqualuxe'); ?></h3>
                    <?php woocommerce_login_form(array('redirect' => wc_get_page_permalink('myaccount'))); ?>
                    <p class="register-link">
                        <?php esc_html_e('Don\'t have an account?', 'aqualuxe'); ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('Register', 'aqualuxe'); ?></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the header wishlist
 */
function aqualuxe_woocommerce_header_wishlist() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_wishlist', true)) {
        return;
    }
    
    // Check if YITH WooCommerce Wishlist is active
    if (defined('YITH_WCWL') && YITH_WCWL) {
        $wishlist_url = YITH_WCWL()->get_wishlist_url();
        $count = yith_wcwl_count_all_products();
    } else {
        // Fallback to account page
        $wishlist_url = wc_get_account_endpoint_url('dashboard');
        $count = 0;
    }
    
    ?>
    <div class="header-wishlist">
        <a href="<?php echo esc_url($wishlist_url); ?>" title="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
            <i class="fas fa-heart"></i>
            <span class="wishlist-count"><?php echo esc_html($count); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Display the header search
 */
function aqualuxe_woocommerce_header_search() {
    if (!aqualuxe_get_option('header_search', true)) {
        return;
    }
    
    ?>
    <div class="header-search">
        <button class="search-toggle" aria-expanded="false">
            <i class="fas fa-search"></i>
        </button>
        <div class="search-dropdown">
            <?php get_product_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display the product video
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta($product->get_id(), '_video_url', true);
    
    if (!$video_url) {
        return;
    }
    
    $video_thumbnail = get_post_meta($product->get_id(), '_video_thumbnail', true);
    $video_thumbnail_url = $video_thumbnail ? wp_get_attachment_image_url($video_thumbnail, 'full') : wc_placeholder_img_src();
    
    ?>
    <div class="product-video">
        <a href="<?php echo esc_url($video_url); ?>" class="product-video-link" data-fancybox>
            <img src="<?php echo esc_url($video_thumbnail_url); ?>" alt="<?php esc_attr_e('Product Video', 'aqualuxe'); ?>" />
            <span class="video-play-button">
                <i class="fas fa-play"></i>
            </span>
        </a>
    </div>
    <?php
}

/**
 * Display the product size chart
 */
function aqualuxe_woocommerce_product_size_chart() {
    global $product;
    
    $size_chart = get_post_meta($product->get_id(), '_size_chart', true);
    
    if (!$size_chart) {
        return;
    }
    
    $size_chart_url = wp_get_attachment_image_url($size_chart, 'full');
    
    ?>
    <div class="product-size-chart">
        <a href="<?php echo esc_url($size_chart_url); ?>" class="size-chart-link" data-fancybox>
            <?php esc_html_e('Size Chart', 'aqualuxe'); ?>
        </a>
    </div>
    <?php
}

/**
 * Display the product custom tab content
 */
function aqualuxe_woocommerce_product_custom_tab_content() {
    global $product;
    
    $custom_tab_content = get_post_meta($product->get_id(), '_custom_tab_content', true);
    
    if ($custom_tab_content) {
        echo wpautop(wp_kses_post($custom_tab_content));
    }
}

/**
 * Display the product shipping tab content
 */
function aqualuxe_woocommerce_product_shipping_tab_content() {
    global $product;
    
    $shipping_content = get_post_meta($product->get_id(), '_shipping_content', true);
    
    if ($shipping_content) {
        echo wpautop(wp_kses_post($shipping_content));
    } else {
        echo wpautop(wp_kses_post(get_option('aqualuxe_default_shipping_content', '')));
    }
}

/**
 * Display the product care tab content
 */
function aqualuxe_woocommerce_product_care_tab_content() {
    global $product;
    
    $care_content = get_post_meta($product->get_id(), '_care_content', true);
    
    if ($care_content) {
        echo wpautop(wp_kses_post($care_content));
    } else {
        echo wpautop(wp_kses_post(get_option('aqualuxe_default_care_content', '')));
    }
}

/**
 * Display the product variation image
 * 
 * @param string $image   Image HTML.
 * @param int    $post_id Post ID.
 * @return string
 */
function aqualuxe_woocommerce_product_variation_image($image, $post_id) {
    $variation_image = get_post_meta($post_id, '_variation_image', true);
    
    if ($variation_image) {
        $image = wp_get_attachment_image($variation_image, 'woocommerce_thumbnail');
    }
    
    return $image;
}
add_filter('woocommerce_product_variation_get_image', 'aqualuxe_woocommerce_product_variation_image', 10, 2);

/**
 * Display the product category image
 * 
 * @param int $category_id Category ID.
 */
function aqualuxe_woocommerce_product_category_image($category_id) {
    $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
    
    if ($thumbnail_id) {
        echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail');
    } else {
        echo wc_placeholder_img();
    }
}

/**
 * Display the product category banner
 * 
 * @param int $category_id Category ID.
 */
function aqualuxe_woocommerce_product_category_banner($category_id) {
    $banner_image_id = get_term_meta($category_id, 'banner_image_id', true);
    
    if ($banner_image_id) {
        echo wp_get_attachment_image($banner_image_id, 'full');
    }
}

/**
 * Display the product category icon
 * 
 * @param int $category_id Category ID.
 */
function aqualuxe_woocommerce_product_category_icon($category_id) {
    $icon = get_term_meta($category_id, 'icon', true);
    
    if ($icon) {
        echo '<i class="' . esc_attr($icon) . '"></i>';
    }
}

/**
 * Display the product tag icon
 * 
 * @param int $tag_id Tag ID.
 */
function aqualuxe_woocommerce_product_tag_icon($tag_id) {
    $icon = get_term_meta($tag_id, 'icon', true);
    
    if ($icon) {
        echo '<i class="' . esc_attr($icon) . '"></i>';
    }
}

/**
 * Display the product attribute icon
 * 
 * @param int $attribute_id Attribute ID.
 */
function aqualuxe_woocommerce_product_attribute_icon($attribute_id) {
    $icon = get_option('aqualuxe_attribute_icon_' . $attribute_id);
    
    if ($icon) {
        echo '<i class="' . esc_attr($icon) . '"></i>';
    }
}

/**
 * Display the product featured badge
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_featured_badge($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->is_featured()) {
        echo '<span class="badge featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
}

/**
 * Display the product sale badge
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_sale_badge($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->is_on_sale()) {
        echo '<span class="badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
}

/**
 * Display the product new badge
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_new_badge($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    $days_as_new = 30;
    $post_date = get_the_time('U');
    $current_date = current_time('timestamp');
    $seconds_in_day = 86400;
    
    if (($current_date - $post_date) < ($days_as_new * $seconds_in_day)) {
        echo '<span class="badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
}

/**
 * Display the product out of stock badge
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_out_of_stock_badge($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if (!$product->is_in_stock()) {
        echo '<span class="badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
}

/**
 * Display the product price
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_price($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo '<div class="price">' . $product->get_price_html() . '</div>';
}

/**
 * Display the product rating
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_rating($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->get_rating_count() > 0) {
        echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
    }
}

/**
 * Display the product add to cart button
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_add_to_cart_button($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo '<div class="add-to-cart">';
    woocommerce_template_loop_add_to_cart();
    echo '</div>';
}

/**
 * Display the product categories
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_categories($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories">', '</div>');
}

/**
 * Display the product tags
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_tags($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo wc_get_product_tag_list($product->get_id(), ', ', '<div class="product-tags">', '</div>');
}

/**
 * Display the product short description
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_short_description($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->get_short_description()) {
        echo '<div class="product-short-description">' . wp_kses_post($product->get_short_description()) . '</div>';
    }
}

/**
 * Display the product dimensions
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_dimensions($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->has_dimensions()) {
        echo '<div class="product-dimensions">';
        echo '<span class="dimensions-label">' . esc_html__('Dimensions:', 'aqualuxe') . '</span> ';
        echo esc_html($product->get_dimensions());
        echo '</div>';
    }
}

/**
 * Display the product weight
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_weight($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->has_weight()) {
        echo '<div class="product-weight">';
        echo '<span class="weight-label">' . esc_html__('Weight:', 'aqualuxe') . '</span> ';
        echo esc_html($product->get_weight()) . ' ' . esc_html(get_option('woocommerce_weight_unit'));
        echo '</div>';
    }
}

/**
 * Display the product SKU
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_sku($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->get_sku()) {
        echo '<div class="product-sku">';
        echo '<span class="sku-label">' . esc_html__('SKU:', 'aqualuxe') . '</span> ';
        echo esc_html($product->get_sku());
        echo '</div>';
    }
}

/**
 * Display the product attributes
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_attributes($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    $attributes = $product->get_attributes();
    
    if (!$attributes) {
        return;
    }
    
    echo '<div class="product-attributes">';
    
    foreach ($attributes as $attribute) {
        if ($attribute->get_visible()) {
            echo '<div class="product-attribute">';
            echo '<span class="attribute-label">' . esc_html($attribute->get_name()) . ':</span> ';
            
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                echo esc_html(implode(', ', $values));
            } else {
                echo esc_html(implode(', ', $attribute->get_options()));
            }
            
            echo '</div>';
        }
    }
    
    echo '</div>';
}

/**
 * Display the product meta
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_meta($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-meta">';
    
    // SKU
    aqualuxe_woocommerce_product_sku($product);
    
    // Categories
    aqualuxe_woocommerce_product_categories($product);
    
    // Tags
    aqualuxe_woocommerce_product_tags($product);
    
    echo '</div>';
}

/**
 * Display the product tabs
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_tabs($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    woocommerce_output_product_data_tabs();
}

/**
 * Display the product related products
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_related_products($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if (aqualuxe_get_option('show_related_products', true)) {
        woocommerce_output_related_products();
    }
}

/**
 * Display the product upsells
 * 
 * @param object $product Product object.
 */
function aqualuxe_woocommerce_product_upsells($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if (aqualuxe_get_option('show_upsells', true)) {
        woocommerce_upsell_display();
    }
}

/**
 * Display the cart cross-sells
 */
function aqualuxe_woocommerce_cart_cross_sells() {
    woocommerce_cross_sell_display();
}

/**
 * Display the cart totals
 */
function aqualuxe_woocommerce_cart_totals() {
    woocommerce_cart_totals();
}

/**
 * Display the checkout coupon form
 */
function aqualuxe_woocommerce_checkout_coupon_form() {
    woocommerce_checkout_coupon_form();
}

/**
 * Display the checkout login form
 */
function aqualuxe_woocommerce_checkout_login_form() {
    woocommerce_checkout_login_form();
}

/**
 * Display the checkout billing form
 */
function aqualuxe_woocommerce_checkout_billing_form() {
    woocommerce_checkout_billing_form();
}

/**
 * Display the checkout shipping form
 */
function aqualuxe_woocommerce_checkout_shipping_form() {
    woocommerce_checkout_shipping_form();
}

/**
 * Display the checkout payment
 */
function aqualuxe_woocommerce_checkout_payment() {
    woocommerce_checkout_payment();
}

/**
 * Display the checkout order review
 */
function aqualuxe_woocommerce_checkout_order_review() {
    woocommerce_order_review();
}

/**
 * Display the account navigation
 */
function aqualuxe_woocommerce_account_navigation() {
    woocommerce_account_navigation();
}

/**
 * Display the account content
 */
function aqualuxe_woocommerce_account_content() {
    woocommerce_account_content();
}

/**
 * Display the account dashboard
 */
function aqualuxe_woocommerce_account_dashboard() {
    woocommerce_account_dashboard();
}

/**
 * Display the account orders
 */
function aqualuxe_woocommerce_account_orders() {
    woocommerce_account_orders();
}

/**
 * Display the account downloads
 */
function aqualuxe_woocommerce_account_downloads() {
    woocommerce_account_downloads();
}

/**
 * Display the account edit address
 */
function aqualuxe_woocommerce_account_edit_address() {
    woocommerce_account_edit_address();
}

/**
 * Display the account payment methods
 */
function aqualuxe_woocommerce_account_payment_methods() {
    woocommerce_account_payment_methods();
}

/**
 * Display the account edit account
 */
function aqualuxe_woocommerce_account_edit_account() {
    woocommerce_account_edit_account();
}

/**
 * Display the account view order
 */
function aqualuxe_woocommerce_account_view_order() {
    woocommerce_account_view_order();
}

/**
 * Display the account wishlist
 */
function aqualuxe_woocommerce_account_wishlist() {
    // Check if YITH WooCommerce Wishlist is active
    if (defined('YITH_WCWL') && YITH_WCWL) {
        echo do_shortcode('[yith_wcwl_wishlist]');
    } else {
        // Fallback wishlist
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        $wishlist = $wishlist ? $wishlist : array();
        
        if (empty($wishlist)) {
            echo '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
            return;
        }
        
        // Get products
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'post__in'       => $wishlist,
            'orderby'        => 'post__in',
            'posts_per_page' => -1,
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            ?>
            <table class="shop_table shop_table_responsive wishlist_table">
                <thead>
                    <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <th class="product-price"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                        <th class="product-stock-status"><?php esc_html_e('Stock Status', 'aqualuxe'); ?></th>
                        <th class="product-add-to-cart">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($products->have_posts()) : $products->the_post(); ?>
                        <?php
                        $product = wc_get_product(get_the_ID());
                        ?>
                        <tr>
                            <td class="product-remove">
                                <a href="#" class="remove remove-from-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>">&times;</a>
                            </td>
                            <td class="product-thumbnail">
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                    <?php echo $product->get_image(); ?>
                                </a>
                            </td>
                            <td class="product-name">
                                <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                            </td>
                            <td class="product-price">
                                <?php echo $product->get_price_html(); ?>
                            </td>
                            <td class="product-stock-status">
                                <?php if ($product->is_in_stock()) : ?>
                                    <span class="in-stock"><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="out-of-stock"><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="product-add-to-cart">
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
        }
        
        wp_reset_postdata();
    }
}