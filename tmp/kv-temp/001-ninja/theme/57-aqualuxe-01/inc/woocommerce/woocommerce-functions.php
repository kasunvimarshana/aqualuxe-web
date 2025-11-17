<?php
/**
 * WooCommerce functions
 *
 * @package AquaLuxe
 */

/**
 * Get wishlist URL
 *
 * @return string Wishlist URL
 */
function aqualuxe_get_wishlist_url() {
    // Check if YITH WooCommerce Wishlist is active
    if (function_exists('YITH_WCWL')) {
        return YITH_WCWL()->get_wishlist_url();
    }
    
    // Check if WooCommerce Wishlist plugin is active
    if (function_exists('tinv_url_wishlist_default')) {
        return tinv_url_wishlist_default();
    }
    
    // Default wishlist page
    $wishlist_page_id = get_option('aqualuxe_wishlist_page_id');
    if ($wishlist_page_id) {
        return get_permalink($wishlist_page_id);
    }
    
    return '#';
}

/**
 * Get wishlist count
 *
 * @return int Wishlist count
 */
function aqualuxe_get_wishlist_count() {
    // Check if YITH WooCommerce Wishlist is active
    if (function_exists('YITH_WCWL')) {
        return YITH_WCWL()->count_products();
    }
    
    // Check if WooCommerce Wishlist plugin is active
    if (function_exists('tinv_wishlist_count')) {
        return tinv_wishlist_count();
    }
    
    // Default wishlist count
    $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
    if ($wishlist) {
        return count($wishlist);
    }
    
    return 0;
}

/**
 * Add to wishlist button
 */
function aqualuxe_add_to_wishlist_button() {
    global $product;
    
    // Check if product exists
    if (!$product) {
        return;
    }
    
    // Check if YITH WooCommerce Wishlist is active
    if (function_exists('YITH_WCWL')) {
        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
        return;
    }
    
    // Check if WooCommerce Wishlist plugin is active
    if (function_exists('tinv_wishlist_add_to_cart_button')) {
        echo tinv_wishlist_add_to_cart_button();
        return;
    }
    
    // Default wishlist button
    $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
    $in_wishlist = $wishlist && in_array($product->get_id(), $wishlist);
    
    echo '<a href="#" class="aqualuxe-wishlist-button' . ($in_wishlist ? ' added' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Add to wishlist', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0H24V24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>';
    echo '</a>';
}

/**
 * Get compare URL
 *
 * @return string Compare URL
 */
function aqualuxe_get_compare_url() {
    // Check if YITH WooCommerce Compare is active
    if (function_exists('yith_woocompare_get_page_url')) {
        return yith_woocompare_get_page_url();
    }
    
    // Default compare page
    $compare_page_id = get_option('aqualuxe_compare_page_id');
    if ($compare_page_id) {
        return get_permalink($compare_page_id);
    }
    
    return '#';
}

/**
 * Get compare count
 *
 * @return int Compare count
 */
function aqualuxe_get_compare_count() {
    // Check if YITH WooCommerce Compare is active
    if (function_exists('yith_woocompare_count_products')) {
        return yith_woocompare_count_products();
    }
    
    // Default compare count
    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    return count($compare);
}

/**
 * Add to compare button
 */
function aqualuxe_add_to_compare_button() {
    global $product;
    
    // Check if product exists
    if (!$product) {
        return;
    }
    
    // Check if YITH WooCommerce Compare is active
    if (function_exists('yith_woocompare_add_product_url')) {
        echo '<a href="' . esc_url(yith_woocompare_add_product_url($product->get_id())) . '" class="compare" data-product_id="' . esc_attr($product->get_id()) . '">';
        echo '<span class="screen-reader-text">' . esc_html__('Compare', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>';
        echo '</a>';
        return;
    }
    
    // Default compare button
    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    $in_compare = in_array($product->get_id(), $compare);
    
    echo '<a href="#" class="aqualuxe-compare-button' . ($in_compare ? ' added' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Compare', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>';
    echo '</a>';
}

/**
 * Quick view button
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    // Check if product exists
    if (!$product) {
        return;
    }
    
    // Check if YITH WooCommerce Quick View is active
    if (function_exists('yith_wcqv_init')) {
        echo '<a href="#" class="button yith-wcqv-button" data-product_id="' . esc_attr($product->get_id()) . '">';
        echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>';
        echo '</a>';
        return;
    }
    
    // Default quick view button
    echo '<a href="#" class="aqualuxe-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>';
    echo '</a>';
}

/**
 * Quick view modal
 */
function aqualuxe_quick_view_modal() {
    if (!get_theme_mod('aqualuxe_quick_view', true)) {
        return;
    }
    
    // Check if YITH WooCommerce Quick View is active
    if (function_exists('yith_wcqv_init')) {
        return;
    }
    
    // Default quick view modal
    ?>
    <div id="aqualuxe-quick-view-modal" class="aqualuxe-quick-view-modal" style="display: none;">
        <div class="aqualuxe-quick-view-modal-overlay"></div>
        <div class="aqualuxe-quick-view-modal-content">
            <button class="aqualuxe-quick-view-modal-close">
                <span class="screen-reader-text"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
            </button>
            <div class="aqualuxe-quick-view-modal-body"></div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_quick_view_modal');

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error();
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error();
    }
    
    ob_start();
    ?>
    <div class="aqualuxe-quick-view-product">
        <div class="aqualuxe-quick-view-product-image">
            <?php echo $product->get_image('medium'); ?>
        </div>
        <div class="aqualuxe-quick-view-product-summary">
            <h2 class="aqualuxe-quick-view-product-title"><?php echo $product->get_name(); ?></h2>
            <div class="aqualuxe-quick-view-product-price"><?php echo $product->get_price_html(); ?></div>
            <div class="aqualuxe-quick-view-product-rating">
                <?php if ($product->get_rating_count() > 0) : ?>
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <a href="<?php echo esc_url(get_permalink($product_id)) . '#reviews'; ?>" class="aqualuxe-quick-view-product-review-link">
                        <?php printf(_n('(%s review)', '(%s reviews)', $product->get_review_count(), 'aqualuxe'), $product->get_review_count()); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="aqualuxe-quick-view-product-description">
                <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
            </div>
            <div class="aqualuxe-quick-view-product-add-to-cart">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
            <div class="aqualuxe-quick-view-product-meta">
                <?php if ($product->get_sku()) : ?>
                    <span class="aqualuxe-quick-view-product-sku">
                        <?php esc_html_e('SKU:', 'aqualuxe'); ?> <?php echo $product->get_sku(); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (wc_get_product_category_list($product_id)) : ?>
                    <span class="aqualuxe-quick-view-product-categories">
                        <?php esc_html_e('Categories:', 'aqualuxe'); ?> <?php echo wc_get_product_category_list($product_id); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (wc_get_product_tag_list($product_id)) : ?>
                    <span class="aqualuxe-quick-view-product-tags">
                        <?php esc_html_e('Tags:', 'aqualuxe'); ?> <?php echo wc_get_product_tag_list($product_id); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="aqualuxe-quick-view-product-actions">
                <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="button aqualuxe-quick-view-product-view-details">
                    <?php esc_html_e('View Details', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    
    $output = ob_get_clean();
    wp_send_json_success($output);
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');

/**
 * Wishlist AJAX handler
 */
function aqualuxe_wishlist_ajax() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error();
    }
    
    $product_id = absint($_POST['product_id']);
    $user_id = get_current_user_id();
    
    if ($user_id === 0) {
        // Guest user, use cookies
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $action = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        // Update cookie
        setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/');
    } else {
        // Logged in user, use user meta
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        if (!$wishlist) {
            $wishlist = array();
        }
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $action = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        // Update user meta
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
    }
    
    wp_send_json_success(array(
        'action' => $action,
        'count' => count($wishlist),
    ));
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');

/**
 * Compare AJAX handler
 */
function aqualuxe_compare_ajax() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error();
    }
    
    $product_id = absint($_POST['product_id']);
    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    
    if (in_array($product_id, $compare)) {
        // Remove from compare
        $compare = array_diff($compare, array($product_id));
        $action = 'removed';
    } else {
        // Add to compare
        $compare[] = $product_id;
        $action = 'added';
    }
    
    // Update cookie
    setcookie('aqualuxe_compare', implode(',', $compare), time() + (86400 * 30), '/');
    
    wp_send_json_success(array(
        'action' => $action,
        'count' => count($compare),
    ));
}
add_action('wp_ajax_aqualuxe_compare', 'aqualuxe_compare_ajax');
add_action('wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_compare_ajax');

/**
 * Get product price HTML with discount percentage
 *
 * @param WC_Product $product Product object
 * @return string Price HTML
 */
function aqualuxe_get_product_price_html($product) {
    if (!$product) {
        return '';
    }
    
    $price_html = $product->get_price_html();
    
    // Add discount percentage for sale products
    if ($product->is_on_sale() && $product->get_regular_price()) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();
        
        if ($regular_price > 0) {
            $discount = round(100 - ($sale_price / $regular_price * 100));
            $price_html .= ' <span class="discount-percentage">-' . $discount . '%</span>';
        }
    }
    
    return $price_html;
}

/**
 * Get product stock status HTML
 *
 * @param WC_Product $product Product object
 * @return string Stock status HTML
 */
function aqualuxe_get_product_stock_status_html($product) {
    if (!$product) {
        return '';
    }
    
    $availability = $product->get_availability();
    $stock_status = $availability['class'] ?? '';
    $stock_html = '';
    
    switch ($stock_status) {
        case 'in-stock':
            $stock_html = '<span class="stock in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
            break;
        case 'out-of-stock':
            $stock_html = '<span class="stock out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
            break;
        case 'available-on-backorder':
            $stock_html = '<span class="stock backorder">' . esc_html__('Available on Backorder', 'aqualuxe') . '</span>';
            break;
    }
    
    return $stock_html;
}

/**
 * Get product rating HTML
 *
 * @param WC_Product $product Product object
 * @return string Rating HTML
 */
function aqualuxe_get_product_rating_html($product) {
    if (!$product || $product->get_rating_count() === 0) {
        return '';
    }
    
    $rating_html = wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
    $rating_html .= '<span class="rating-count">(' . $product->get_rating_count() . ')</span>';
    
    return $rating_html;
}

/**
 * Get product categories HTML
 *
 * @param WC_Product $product Product object
 * @return string Categories HTML
 */
function aqualuxe_get_product_categories_html($product) {
    if (!$product) {
        return '';
    }
    
    $categories = wc_get_product_category_list($product->get_id(), ', ', '<span class="product-categories">', '</span>');
    
    return $categories;
}

/**
 * Get product tags HTML
 *
 * @param WC_Product $product Product object
 * @return string Tags HTML
 */
function aqualuxe_get_product_tags_html($product) {
    if (!$product) {
        return '';
    }
    
    $tags = wc_get_product_tag_list($product->get_id(), ', ', '<span class="product-tags">', '</span>');
    
    return $tags;
}

/**
 * Get product SKU HTML
 *
 * @param WC_Product $product Product object
 * @return string SKU HTML
 */
function aqualuxe_get_product_sku_html($product) {
    if (!$product || !$product->get_sku()) {
        return '';
    }
    
    return '<span class="product-sku">' . esc_html__('SKU:', 'aqualuxe') . ' ' . $product->get_sku() . '</span>';
}

/**
 * Get product dimensions HTML
 *
 * @param WC_Product $product Product object
 * @return string Dimensions HTML
 */
function aqualuxe_get_product_dimensions_html($product) {
    if (!$product || !$product->has_dimensions()) {
        return '';
    }
    
    return '<span class="product-dimensions">' . esc_html__('Dimensions:', 'aqualuxe') . ' ' . wc_format_dimensions($product->get_dimensions(false)) . '</span>';
}

/**
 * Get product weight HTML
 *
 * @param WC_Product $product Product object
 * @return string Weight HTML
 */
function aqualuxe_get_product_weight_html($product) {
    if (!$product || !$product->has_weight()) {
        return '';
    }
    
    return '<span class="product-weight">' . esc_html__('Weight:', 'aqualuxe') . ' ' . $product->get_weight() . ' ' . get_option('woocommerce_weight_unit') . '</span>';
}

/**
 * Get product attributes HTML
 *
 * @param WC_Product $product Product object
 * @return string Attributes HTML
 */
function aqualuxe_get_product_attributes_html($product) {
    if (!$product) {
        return '';
    }
    
    $attributes = $product->get_attributes();
    
    if (empty($attributes)) {
        return '';
    }
    
    $attributes_html = '<div class="product-attributes">';
    
    foreach ($attributes as $attribute) {
        if ($attribute->get_visible()) {
            $attribute_name = wc_attribute_label($attribute->get_name());
            $attribute_values = array();
            
            if ($attribute->is_taxonomy()) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
            } else {
                $attribute_values = $attribute->get_options();
            }
            
            $attributes_html .= '<div class="product-attribute">';
            $attributes_html .= '<span class="attribute-name">' . $attribute_name . ':</span> ';
            $attributes_html .= '<span class="attribute-value">' . implode(', ', $attribute_values) . '</span>';
            $attributes_html .= '</div>';
        }
    }
    
    $attributes_html .= '</div>';
    
    return $attributes_html;
}

/**
 * Get product meta HTML
 *
 * @param WC_Product $product Product object
 * @return string Meta HTML
 */
function aqualuxe_get_product_meta_html($product) {
    if (!$product) {
        return '';
    }
    
    $meta_html = '';
    
    // SKU
    if (get_theme_mod('aqualuxe_product_sku', true) && $product->get_sku()) {
        $meta_html .= '<div class="product-meta-item product-sku">';
        $meta_html .= '<span class="meta-label">' . esc_html__('SKU:', 'aqualuxe') . '</span> ';
        $meta_html .= '<span class="meta-value">' . $product->get_sku() . '</span>';
        $meta_html .= '</div>';
    }
    
    // Categories
    if (get_theme_mod('aqualuxe_product_categories', true)) {
        $categories = wc_get_product_category_list($product->get_id());
        if ($categories) {
            $meta_html .= '<div class="product-meta-item product-categories">';
            $meta_html .= '<span class="meta-label">' . esc_html__('Categories:', 'aqualuxe') . '</span> ';
            $meta_html .= '<span class="meta-value">' . $categories . '</span>';
            $meta_html .= '</div>';
        }
    }
    
    // Tags
    if (get_theme_mod('aqualuxe_product_tags', true)) {
        $tags = wc_get_product_tag_list($product->get_id());
        if ($tags) {
            $meta_html .= '<div class="product-meta-item product-tags">';
            $meta_html .= '<span class="meta-label">' . esc_html__('Tags:', 'aqualuxe') . '</span> ';
            $meta_html .= '<span class="meta-value">' . $tags . '</span>';
            $meta_html .= '</div>';
        }
    }
    
    return $meta_html ? '<div class="product-meta">' . $meta_html . '</div>' : '';
}

/**
 * Get product sharing HTML
 *
 * @param WC_Product $product Product object
 * @return string Sharing HTML
 */
function aqualuxe_get_product_sharing_html($product) {
    if (!$product || !get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return '';
    }
    
    $product_url = get_permalink($product->get_id());
    $product_title = get_the_title($product->get_id());
    $product_image = wp_get_attachment_url($product->get_image_id());
    
    $sharing_html = '<div class="product-sharing">';
    $sharing_html .= '<span class="sharing-label">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    
    // Facebook
    if (get_theme_mod('aqualuxe_enable_facebook_sharing', true)) {
        $sharing_html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($product_url) . '" target="_blank" rel="noopener noreferrer" class="share-facebook">';
        $sharing_html .= '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
        $sharing_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
        $sharing_html .= '</a>';
    }
    
    // Twitter
    if (get_theme_mod('aqualuxe_enable_twitter_sharing', true)) {
        $sharing_html .= '<a href="https://twitter.com/intent/tweet?url=' . urlencode($product_url) . '&text=' . urlencode($product_title) . '" target="_blank" rel="noopener noreferrer" class="share-twitter">';
        $sharing_html .= '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
        $sharing_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
        $sharing_html .= '</a>';
    }
    
    // LinkedIn
    if (get_theme_mod('aqualuxe_enable_linkedin_sharing', true)) {
        $sharing_html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($product_url) . '&title=' . urlencode($product_title) . '" target="_blank" rel="noopener noreferrer" class="share-linkedin">';
        $sharing_html .= '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
        $sharing_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5"><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
        $sharing_html .= '</a>';
    }
    
    // Pinterest
    if (get_theme_mod('aqualuxe_enable_pinterest_sharing', true) && $product_image) {
        $sharing_html .= '<a href="https://pinterest.com/pin/create/button/?url=' . urlencode($product_url) . '&media=' . urlencode($product_image) . '&description=' . urlencode($product_title) . '" target="_blank" rel="noopener noreferrer" class="share-pinterest">';
        $sharing_html .= '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
        $sharing_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5"><path fill="currentColor" d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>';
        $sharing_html .= '</a>';
    }
    
    // Email
    if (get_theme_mod('aqualuxe_enable_email_sharing', true)) {
        $sharing_html .= '<a href="mailto:?subject=' . urlencode($product_title) . '&body=' . urlencode(esc_html__('Check out this product:', 'aqualuxe') . ' ' . $product_url) . '" class="share-email">';
        $sharing_html .= '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
        $sharing_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>';
        $sharing_html .= '</a>';
    }
    
    $sharing_html .= '</div>';
    
    return $sharing_html;
}

/**
 * Get product data tabs HTML
 *
 * @param WC_Product $product Product object
 * @return string Tabs HTML
 */
function aqualuxe_get_product_tabs_html($product) {
    if (!$product) {
        return '';
    }
    
    $tabs = apply_filters('woocommerce_product_tabs', array());
    
    if (empty($tabs)) {
        return '';
    }
    
    $tabs_style = get_theme_mod('aqualuxe_product_tabs_style', 'horizontal');
    $tabs_html = '';
    
    switch ($tabs_style) {
        case 'horizontal':
            $tabs_html .= '<div class="woocommerce-tabs wc-tabs-wrapper horizontal-tabs">';
            $tabs_html .= '<ul class="tabs wc-tabs" role="tablist">';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<li class="' . esc_attr($key) . '_tab" id="tab-title-' . esc_attr($key) . '" role="tab" aria-controls="tab-' . esc_attr($key) . '">';
                $tabs_html .= '<a href="#tab-' . esc_attr($key) . '">' . esc_html($tab['title']) . '</a>';
                $tabs_html .= '</li>';
            }
            
            $tabs_html .= '</ul>';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--' . esc_attr($key) . ' panel entry-content wc-tab" id="tab-' . esc_attr($key) . '" role="tabpanel" aria-labelledby="tab-title-' . esc_attr($key) . '">';
                
                if (isset($tab['callback'])) {
                    ob_start();
                    call_user_func($tab['callback'], $key, $tab);
                    $tabs_html .= ob_get_clean();
                }
                
                $tabs_html .= '</div>';
            }
            
            $tabs_html .= '</div>';
            break;
            
        case 'vertical':
            $tabs_html .= '<div class="woocommerce-tabs wc-tabs-wrapper vertical-tabs">';
            $tabs_html .= '<div class="tabs-container">';
            $tabs_html .= '<ul class="tabs wc-tabs" role="tablist">';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<li class="' . esc_attr($key) . '_tab" id="tab-title-' . esc_attr($key) . '" role="tab" aria-controls="tab-' . esc_attr($key) . '">';
                $tabs_html .= '<a href="#tab-' . esc_attr($key) . '">' . esc_html($tab['title']) . '</a>';
                $tabs_html .= '</li>';
            }
            
            $tabs_html .= '</ul>';
            
            $tabs_html .= '<div class="tabs-content">';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--' . esc_attr($key) . ' panel entry-content wc-tab" id="tab-' . esc_attr($key) . '" role="tabpanel" aria-labelledby="tab-title-' . esc_attr($key) . '">';
                
                if (isset($tab['callback'])) {
                    ob_start();
                    call_user_func($tab['callback'], $key, $tab);
                    $tabs_html .= ob_get_clean();
                }
                
                $tabs_html .= '</div>';
            }
            
            $tabs_html .= '</div>';
            $tabs_html .= '</div>';
            $tabs_html .= '</div>';
            break;
            
        case 'accordion':
            $tabs_html .= '<div class="woocommerce-tabs wc-tabs-wrapper accordion-tabs">';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<div class="accordion-tab">';
                $tabs_html .= '<div class="accordion-tab-title" id="tab-title-' . esc_attr($key) . '">';
                $tabs_html .= '<h3>' . esc_html($tab['title']) . '</h3>';
                $tabs_html .= '<span class="accordion-tab-icon"></span>';
                $tabs_html .= '</div>';
                
                $tabs_html .= '<div class="accordion-tab-content woocommerce-Tabs-panel woocommerce-Tabs-panel--' . esc_attr($key) . '" id="tab-' . esc_attr($key) . '">';
                
                if (isset($tab['callback'])) {
                    ob_start();
                    call_user_func($tab['callback'], $key, $tab);
                    $tabs_html .= ob_get_clean();
                }
                
                $tabs_html .= '</div>';
                $tabs_html .= '</div>';
            }
            
            $tabs_html .= '</div>';
            break;
            
        case 'sections':
            $tabs_html .= '<div class="woocommerce-tabs wc-tabs-wrapper section-tabs">';
            
            foreach ($tabs as $key => $tab) {
                $tabs_html .= '<div class="section-tab">';
                $tabs_html .= '<h3 class="section-tab-title">' . esc_html($tab['title']) . '</h3>';
                
                $tabs_html .= '<div class="section-tab-content woocommerce-Tabs-panel woocommerce-Tabs-panel--' . esc_attr($key) . '">';
                
                if (isset($tab['callback'])) {
                    ob_start();
                    call_user_func($tab['callback'], $key, $tab);
                    $tabs_html .= ob_get_clean();
                }
                
                $tabs_html .= '</div>';
                $tabs_html .= '</div>';
            }
            
            $tabs_html .= '</div>';
            break;
    }
    
    return $tabs_html;
}

/**
 * Get product reviews HTML
 *
 * @param WC_Product $product Product object
 * @return string Reviews HTML
 */
function aqualuxe_get_product_reviews_html($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    comments_template();
    return ob_get_clean();
}

/**
 * Get product related products HTML
 *
 * @param WC_Product $product Product object
 * @return string Related products HTML
 */
function aqualuxe_get_product_related_products_html($product) {
    if (!$product || !get_theme_mod('aqualuxe_related_products', true)) {
        return '';
    }
    
    ob_start();
    woocommerce_related_products(array(
        'posts_per_page' => get_theme_mod('aqualuxe_related_products_count', 4),
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
    ));
    return ob_get_clean();
}

/**
 * Get product upsells HTML
 *
 * @param WC_Product $product Product object
 * @return string Upsells HTML
 */
function aqualuxe_get_product_upsells_html($product) {
    if (!$product || !get_theme_mod('aqualuxe_upsells', true)) {
        return '';
    }
    
    ob_start();
    woocommerce_upsell_display();
    return ob_get_clean();
}

/**
 * Get product cross-sells HTML
 *
 * @param WC_Product $product Product object
 * @return string Cross-sells HTML
 */
function aqualuxe_get_product_cross_sells_html($product) {
    if (!$product || !get_theme_mod('aqualuxe_cross_sells', true)) {
        return '';
    }
    
    ob_start();
    woocommerce_cross_sell_display();
    return ob_get_clean();
}

/**
 * Get product recently viewed HTML
 *
 * @return string Recently viewed HTML
 */
function aqualuxe_get_product_recently_viewed_html() {
    if (!get_theme_mod('aqualuxe_recently_viewed_products', true)) {
        return '';
    }
    
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
    $viewed_products = array_filter(array_map('absint', $viewed_products));
    
    if (empty($viewed_products)) {
        return '';
    }
    
    $current_product_id = get_the_ID();
    $viewed_products = array_diff($viewed_products, array($current_product_id));
    
    if (empty($viewed_products)) {
        return '';
    }
    
    $count = get_theme_mod('aqualuxe_recently_viewed_products_count', 4);
    $viewed_products = array_slice($viewed_products, 0, $count);
    
    ob_start();
    
    woocommerce_related_products(array(
        'posts_per_page' => $count,
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
        'orderby'        => 'rand',
        'post__in'       => $viewed_products,
    ));
    
    return ob_get_clean();
}

/**
 * Get product gallery HTML
 *
 * @param WC_Product $product Product object
 * @return string Gallery HTML
 */
function aqualuxe_get_product_gallery_html($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_show_product_images();
    return ob_get_clean();
}

/**
 * Get product add to cart HTML
 *
 * @param WC_Product $product Product object
 * @return string Add to cart HTML
 */
function aqualuxe_get_product_add_to_cart_html($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_add_to_cart();
    return ob_get_clean();
}

/**
 * Get product summary HTML
 *
 * @param WC_Product $product Product object
 * @return string Summary HTML
 */
function aqualuxe_get_product_summary_html($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_excerpt();
    return ob_get_clean();
}

/**
 * Get product title HTML
 *
 * @param WC_Product $product Product object
 * @return string Title HTML
 */
function aqualuxe_get_product_title_html($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_title();
    return ob_get_clean();
}

/**
 * Get product price HTML
 *
 * @param WC_Product $product Product object
 * @return string Price HTML
 */
function aqualuxe_get_product_price_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_price();
    return ob_get_clean();
}

/**
 * Get product rating HTML
 *
 * @param WC_Product $product Product object
 * @return string Rating HTML
 */
function aqualuxe_get_product_rating_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_rating();
    return ob_get_clean();
}

/**
 * Get product meta HTML
 *
 * @param WC_Product $product Product object
 * @return string Meta HTML
 */
function aqualuxe_get_product_meta_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_meta();
    return ob_get_clean();
}

/**
 * Get product sharing HTML
 *
 * @param WC_Product $product Product object
 * @return string Sharing HTML
 */
function aqualuxe_get_product_sharing_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_template_single_sharing();
    return ob_get_clean();
}

/**
 * Get product data tabs HTML
 *
 * @param WC_Product $product Product object
 * @return string Tabs HTML
 */
function aqualuxe_get_product_tabs_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_output_product_data_tabs();
    return ob_get_clean();
}

/**
 * Get product related products HTML
 *
 * @param WC_Product $product Product object
 * @return string Related products HTML
 */
function aqualuxe_get_product_related_products_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_output_related_products();
    return ob_get_clean();
}

/**
 * Get product upsells HTML
 *
 * @param WC_Product $product Product object
 * @return string Upsells HTML
 */
function aqualuxe_get_product_upsells_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_upsell_display();
    return ob_get_clean();
}

/**
 * Get product cross-sells HTML
 *
 * @param WC_Product $product Product object
 * @return string Cross-sells HTML
 */
function aqualuxe_get_product_cross_sells_html_formatted($product) {
    if (!$product) {
        return '';
    }
    
    ob_start();
    woocommerce_cross_sell_display();
    return ob_get_clean();
}

/**
 * Get product recently viewed HTML
 *
 * @return string Recently viewed HTML
 */
function aqualuxe_get_product_recently_viewed_html_formatted() {
    ob_start();
    aqualuxe_recently_viewed_products();
    return ob_get_clean();
}