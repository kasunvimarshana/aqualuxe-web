<?php
/**
 * WooCommerce Template Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get product price HTML with custom formatting
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_price_html($product) {
    if (!$product) {
        return '';
    }

    $price_html = $product->get_price_html();
    
    if ($product->is_on_sale()) {
        $regular_price = wc_get_price_to_display($product, ['price' => $product->get_regular_price()]);
        $sale_price = wc_get_price_to_display($product, ['price' => $product->get_sale_price()]);
        
        if ($regular_price && $sale_price) {
            $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
            
            $price_html .= ' <span class="discount-percentage">-' . $percentage . '%</span>';
        }
    }
    
    return $price_html;
}

/**
 * Get product rating HTML with custom formatting
 *
 * @param WC_Product $product
 * @param bool $show_empty
 * @return string
 */
function aqualuxe_wc_get_rating_html($product, $show_empty = false) {
    if (!$product) {
        return '';
    }

    $rating = $product->get_average_rating();
    
    if ($rating > 0 || $show_empty) {
        $rating_html = '<div class="star-rating-wrapper">';
        $rating_html .= wc_get_rating_html($rating);
        
        if ($rating > 0) {
            $review_count = $product->get_review_count();
            $rating_html .= ' <span class="rating-count">(' . $review_count . ')</span>';
        }
        
        $rating_html .= '</div>';
        
        return $rating_html;
    }
    
    return '';
}

/**
 * Get product categories HTML with custom formatting
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_categories_html($product) {
    if (!$product) {
        return '';
    }

    $categories = wc_get_product_category_list($product->get_id(), ', ', '<span class="product-categories">', '</span>');
    
    return $categories;
}

/**
 * Get product tags HTML with custom formatting
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_tags_html($product) {
    if (!$product) {
        return '';
    }

    $tags = wc_get_product_tag_list($product->get_id(), ', ', '<span class="product-tags">', '</span>');
    
    return $tags;
}

/**
 * Get product badges HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_badges_html($product) {
    if (!$product) {
        return '';
    }

    $badges = [];
    
    // Sale badge
    if ($product->is_on_sale()) {
        $badges[] = '<span class="badge badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        $badges[] = '<span class="badge badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if ($product->is_featured()) {
        $badges[] = '<span class="badge badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    // New badge (products less than 30 days old)
    $days_since_creation = (time() - strtotime($product->get_date_created())) / DAY_IN_SECONDS;
    if ($days_since_creation < 30) {
        $badges[] = '<span class="badge badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Apply filters to allow adding custom badges
    $badges = apply_filters('aqualuxe_wc_product_badges', $badges, $product);
    
    if (!empty($badges)) {
        return '<div class="product-badges">' . implode('', $badges) . '</div>';
    }
    
    return '';
}

/**
 * Get product actions HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_actions_html($product) {
    if (!$product) {
        return '';
    }

    ob_start();
    
    // Add to cart button
    woocommerce_template_loop_add_to_cart();
    
    // Quick view button
    if (function_exists('aqualuxe_wc_quick_view_button')) {
        aqualuxe_wc_quick_view_button($product);
    }
    
    // Wishlist button
    if (function_exists('aqualuxe_wc_wishlist_button')) {
        aqualuxe_wc_wishlist_button($product);
    }
    
    // Compare button
    if (function_exists('aqualuxe_wc_compare_button')) {
        aqualuxe_wc_compare_button($product);
    }
    
    return ob_get_clean();
}

/**
 * Quick view button
 *
 * @param WC_Product $product
 */
function aqualuxe_wc_quick_view_button($product) {
    if (!$product) {
        return;
    }

    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if quick view is enabled
    if (!$module->get_option('quick_view', true)) {
        return;
    }
    
    echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Quick View', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '</a>';
}

/**
 * Wishlist button
 *
 * @param WC_Product $product
 */
function aqualuxe_wc_wishlist_button($product) {
    if (!$product) {
        return;
    }

    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if wishlist is enabled
    if (!$module->get_option('wishlist', true)) {
        return;
    }
    
    // Get wishlist
    $wishlist = $module->get_wishlist();
    
    // Check if product is in wishlist
    $in_wishlist = in_array($product->get_id(), $wishlist);
    
    echo '<a href="#" class="wishlist-button ' . ($in_wishlist ? 'in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Add to Wishlist', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '</a>';
}

/**
 * Compare button
 *
 * @param WC_Product $product
 */
function aqualuxe_wc_compare_button($product) {
    if (!$product) {
        return;
    }

    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if compare is enabled
    if (!$module->get_option('compare', true)) {
        return;
    }
    
    // Get compare
    $compare = $module->get_compare();
    
    // Check if product is in compare
    $in_compare = in_array($product->get_id(), $compare);
    
    echo '<a href="#" class="compare-button ' . ($in_compare ? 'in-compare' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Add to Compare', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Compare', 'aqualuxe') . '</span>';
    echo '</a>';
}

/**
 * Size guide button
 */
function aqualuxe_wc_size_guide_button() {
    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if size guide is enabled
    if (!$module->get_option('size_guide', true)) {
        return;
    }
    
    // Get product
    global $product;
    
    // Check if product is variable
    if (!$product || !$product->is_type('variable')) {
        return;
    }
    
    echo '<a href="#" class="size-guide-button" data-toggle="size-guide-modal" aria-label="' . esc_attr__('Size Guide', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2 5l7-3 6 3 6.303-2.701a.5.5 0 0 1 .697.46V19l-7 3-6-3-6.303 2.701a.5.5 0 0 1-.697-.46V5zm14 14.395l4-1.714V5.033l-4 1.714v12.648zm-2-.131V6.736l-4-2v12.528l4 2zm-6-2.011V4.605L4 6.319v12.648l4-1.714z"/></svg>';
    echo '<span>' . esc_html__('Size Guide', 'aqualuxe') . '</span>';
    echo '</a>';
}

/**
 * Get product stock status HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_stock_status_html($product) {
    if (!$product) {
        return '';
    }

    $availability = $product->get_availability();
    $stock_status = $availability['class'] ?? '';
    $stock_text = $availability['availability'] ?? '';
    
    if (!$stock_text) {
        if ($product->is_in_stock()) {
            $stock_text = __('In Stock', 'aqualuxe');
        } else {
            $stock_text = __('Out of Stock', 'aqualuxe');
        }
    }
    
    return '<div class="stock-status ' . esc_attr($stock_status) . '">' . esc_html($stock_text) . '</div>';
}

/**
 * Get product variation attributes HTML
 *
 * @param WC_Product_Variable $product
 * @return string
 */
function aqualuxe_wc_get_variation_attributes_html($product) {
    if (!$product || !$product->is_type('variable')) {
        return '';
    }

    $attributes = $product->get_variation_attributes();
    
    if (empty($attributes)) {
        return '';
    }
    
    $html = '<div class="variation-attributes">';
    
    foreach ($attributes as $attribute_name => $options) {
        $attribute_label = wc_attribute_label($attribute_name);
        
        $html .= '<div class="variation-attribute">';
        $html .= '<span class="attribute-label">' . esc_html($attribute_label) . ':</span>';
        $html .= '<div class="attribute-options">';
        
        foreach ($options as $option) {
            $term = get_term_by('slug', $option, $attribute_name);
            $option_name = $term ? $term->name : $option;
            
            $html .= '<span class="attribute-option">' . esc_html($option_name) . '</span>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get product short description HTML
 *
 * @param WC_Product $product
 * @param int $length
 * @return string
 */
function aqualuxe_wc_get_short_description_html($product, $length = 100) {
    if (!$product) {
        return '';
    }

    $short_description = $product->get_short_description();
    
    if (!$short_description) {
        return '';
    }
    
    if ($length > 0 && strlen(strip_tags($short_description)) > $length) {
        $short_description = wp_trim_words(strip_tags($short_description), $length / 5, '...');
    }
    
    return '<div class="product-short-description">' . wp_kses_post($short_description) . '</div>';
}

/**
 * Get product dimensions HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_dimensions_html($product) {
    if (!$product || !$product->has_dimensions()) {
        return '';
    }

    $dimensions = wc_format_dimensions($product->get_dimensions(false));
    
    return '<div class="product-dimensions">' . esc_html__('Dimensions:', 'aqualuxe') . ' ' . esc_html($dimensions) . '</div>';
}

/**
 * Get product weight HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_weight_html($product) {
    if (!$product || !$product->has_weight()) {
        return '';
    }

    $weight = wc_format_weight($product->get_weight());
    
    return '<div class="product-weight">' . esc_html__('Weight:', 'aqualuxe') . ' ' . esc_html($weight) . '</div>';
}

/**
 * Get product SKU HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_sku_html($product) {
    if (!$product || !$product->get_sku()) {
        return '';
    }

    return '<div class="product-sku">' . esc_html__('SKU:', 'aqualuxe') . ' ' . esc_html($product->get_sku()) . '</div>';
}

/**
 * Get product meta HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_meta_html($product) {
    if (!$product) {
        return '';
    }

    ob_start();
    
    echo '<div class="product-meta">';
    
    // SKU
    echo aqualuxe_wc_get_sku_html($product);
    
    // Categories
    echo aqualuxe_wc_get_categories_html($product);
    
    // Tags
    echo aqualuxe_wc_get_tags_html($product);
    
    echo '</div>';
    
    return ob_get_clean();
}

/**
 * Get product social sharing HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_social_sharing_html($product) {
    if (!$product) {
        return '';
    }

    $product_url = get_permalink($product->get_id());
    $product_title = $product->get_name();
    
    ob_start();
    ?>
    <div class="product-social-sharing">
        <span class="sharing-title"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($product_url); ?>" target="_blank" rel="noopener noreferrer" class="social-share facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url($product_url); ?>&text=<?php echo urlencode($product_title); ?>" target="_blank" rel="noopener noreferrer" class="social-share twitter" aria-label="<?php esc_attr_e('Share on Twitter', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>
        </a>
        <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url($product_url); ?>&media=<?php echo esc_url(get_the_post_thumbnail_url($product->get_id(), 'full')); ?>&description=<?php echo urlencode($product_title); ?>" target="_blank" rel="noopener noreferrer" class="social-share pinterest" aria-label="<?php esc_attr_e('Share on Pinterest', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>
        </a>
        <a href="mailto:?subject=<?php echo urlencode($product_title); ?>&body=<?php echo esc_url($product_url); ?>" class="social-share email" aria-label="<?php esc_attr_e('Share via Email', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z"/></svg>
        </a>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get product countdown HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_countdown_html($product) {
    if (!$product || !$product->is_on_sale()) {
        return '';
    }

    $sale_end_date = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
    
    if (!$sale_end_date) {
        return '';
    }
    
    $now = time();
    $sale_end = (int) $sale_end_date;
    
    if ($now > $sale_end) {
        return '';
    }
    
    $days = floor(($sale_end - $now) / (60 * 60 * 24));
    $hours = floor(($sale_end - $now - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($sale_end - $now - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    $seconds = $sale_end - $now - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60;
    
    ob_start();
    ?>
    <div class="product-countdown" data-end-date="<?php echo esc_attr($sale_end_date); ?>">
        <div class="countdown-title"><?php esc_html_e('Sale Ends In:', 'aqualuxe'); ?></div>
        <div class="countdown-timer">
            <div class="countdown-item">
                <span class="countdown-value days"><?php echo esc_html($days); ?></span>
                <span class="countdown-label"><?php esc_html_e('Days', 'aqualuxe'); ?></span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value hours"><?php echo esc_html($hours); ?></span>
                <span class="countdown-label"><?php esc_html_e('Hours', 'aqualuxe'); ?></span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value minutes"><?php echo esc_html($minutes); ?></span>
                <span class="countdown-label"><?php esc_html_e('Minutes', 'aqualuxe'); ?></span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value seconds"><?php echo esc_html($seconds); ?></span>
                <span class="countdown-label"><?php esc_html_e('Seconds', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get product quantity input HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_quantity_input_html($product) {
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock()) {
        return '';
    }

    ob_start();
    
    woocommerce_quantity_input([
        'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
        'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
    ]);
    
    return ob_get_clean();
}

/**
 * Get product add to cart HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_add_to_cart_html($product) {
    if (!$product) {
        return '';
    }

    ob_start();
    
    woocommerce_template_loop_add_to_cart();
    
    return ob_get_clean();
}

/**
 * Get product gallery HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_gallery_html($product) {
    if (!$product) {
        return '';
    }

    ob_start();
    
    woocommerce_show_product_images();
    
    return ob_get_clean();
}

/**
 * Get product tabs HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_tabs_html($product) {
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
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_related_products_html($product) {
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
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_upsells_html($product) {
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
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_cross_sells_html($product) {
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
 * @param array $viewed_products
 * @return string
 */
function aqualuxe_wc_get_recently_viewed_html($viewed_products) {
    if (empty($viewed_products)) {
        return '';
    }

    ob_start();
    
    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Get products per row
    $products_per_row = $module->get_option('products_per_row', 3);
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $products_per_row * 2,
        'post__in' => $viewed_products,
        'orderby' => 'post__in',
        'post_status' => 'publish',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) :
        ?>
        <section class="recently-viewed-products">
            <h2><?php esc_html_e('Recently Viewed Products', 'aqualuxe'); ?></h2>
            <div class="products columns-<?php echo esc_attr($products_per_row); ?>">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </div>
        </section>
        <?php
    endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * Get product wishlist HTML
 *
 * @return string
 */
function aqualuxe_wc_get_wishlist_html() {
    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if wishlist is enabled
    if (!$module->get_option('wishlist', true)) {
        return '';
    }
    
    // Get wishlist
    $wishlist = $module->get_wishlist();
    
    // Check if wishlist is empty
    if (empty($wishlist)) {
        return '<div class="wishlist-empty">' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</div>';
    }
    
    ob_start();
    
    // Get products per row
    $products_per_row = $module->get_option('products_per_row', 3);
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post__in' => $wishlist,
        'orderby' => 'post__in',
        'post_status' => 'publish',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) :
        ?>
        <div class="wishlist-products">
            <div class="products columns-<?php echo esc_attr($products_per_row); ?>">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * Get product compare HTML
 *
 * @return string
 */
function aqualuxe_wc_get_compare_html() {
    // Get module
    $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
    
    // Check if compare is enabled
    if (!$module->get_option('compare', true)) {
        return '';
    }
    
    // Get compare
    $compare = $module->get_compare();
    
    // Check if compare is empty
    if (empty($compare)) {
        return '<div class="compare-empty">' . esc_html__('Your compare list is empty.', 'aqualuxe') . '</div>';
    }
    
    ob_start();
    
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post__in' => $compare,
        'orderby' => 'post__in',
        'post_status' => 'publish',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) :
        ?>
        <div class="compare-products">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <?php $product = wc_get_product(get_the_ID()); ?>
                            <th>
                                <div class="compare-product-header">
                                    <a href="<?php the_permalink(); ?>" class="compare-product-image">
                                        <?php echo $product->get_image('thumbnail'); ?>
                                    </a>
                                    <h3 class="compare-product-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="compare-product-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    <div class="compare-product-actions">
                                        <?php woocommerce_template_loop_add_to_cart(); ?>
                                        <?php aqualuxe_wc_compare_button($product); ?>
                                    </div>
                                </div>
                            </th>
                        <?php endwhile; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="compare-row-rating">
                        <th><?php esc_html_e('Rating', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <td><?php echo wc_get_rating_html($product->get_average_rating()); ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="compare-row-description">
                        <th><?php esc_html_e('Description', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <td><?php echo wp_kses_post($product->get_short_description()); ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="compare-row-sku">
                        <th><?php esc_html_e('SKU', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <td><?php echo esc_html($product->get_sku() ? $product->get_sku() : '-'); ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="compare-row-stock">
                        <th><?php esc_html_e('Stock', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            $availability = $product->get_availability();
                            ?>
                            <td class="<?php echo esc_attr($availability['class']); ?>"><?php echo esc_html($availability['availability'] ? $availability['availability'] : ($product->is_in_stock() ? __('In Stock', 'aqualuxe') : __('Out of Stock', 'aqualuxe'))); ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="compare-row-weight">
                        <th><?php esc_html_e('Weight', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <td><?php echo esc_html($product->has_weight() ? wc_format_weight($product->get_weight()) : '-'); ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="compare-row-dimensions">
                        <th><?php esc_html_e('Dimensions', 'aqualuxe'); ?></th>
                        <?php
                        $products->rewind_posts();
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <td><?php echo esc_html($product->has_dimensions() ? wc_format_dimensions($product->get_dimensions(false)) : '-'); ?></td>
                        <?php endwhile; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * Get mini cart HTML
 *
 * @return string
 */
function aqualuxe_wc_get_mini_cart_html() {
    ob_start();
    
    woocommerce_mini_cart();
    
    return ob_get_clean();
}

/**
 * Get cart count HTML
 *
 * @return string
 */
function aqualuxe_wc_get_cart_count_html() {
    $cart_count = WC()->cart->get_cart_contents_count();
    
    return '<span class="cart-count">' . esc_html($cart_count) . '</span>';
}

/**
 * Get cart total HTML
 *
 * @return string
 */
function aqualuxe_wc_get_cart_total_html() {
    $cart_total = WC()->cart->get_cart_total();
    
    return '<span class="cart-total">' . $cart_total . '</span>';
}

/**
 * Get product attributes HTML
 *
 * @param WC_Product $product
 * @return string
 */
function aqualuxe_wc_get_attributes_html($product) {
    if (!$product) {
        return '';
    }

    $attributes = $product->get_attributes();
    
    if (empty($attributes)) {
        return '';
    }
    
    ob_start();
    
    echo '<div class="product-attributes">';
    
    foreach ($attributes as $attribute) {
        if ($attribute->get_visible()) {
            echo '<div class="product-attribute">';
            echo '<span class="attribute-label">' . wc_attribute_label($attribute->get_name()) . ':</span>';
            echo '<span class="attribute-value">';
            
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
                echo apply_filters('woocommerce_attribute', wptexturize(implode(', ', $values)), $attribute, $values);
            } else {
                $values = $attribute->get_options();
                echo apply_filters('woocommerce_attribute', wptexturize(implode(', ', $values)), $attribute, $values);
            }
            
            echo '</span>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    
    return ob_get_clean();
}