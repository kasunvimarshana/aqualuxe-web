<?php
/**
 * WooCommerce template hooks
 *
 * @package AquaLuxe
 */

/**
 * Remove default WooCommerce hooks
 */
function aqualuxe_remove_woocommerce_hooks() {
    // Remove breadcrumbs
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    
    // Remove result count and catalog ordering
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    
    // Remove default add to cart button
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    // Remove product link close
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    
    // Remove sidebar
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}
add_action('init', 'aqualuxe_remove_woocommerce_hooks');

/**
 * Add custom WooCommerce hooks
 */
function aqualuxe_add_woocommerce_hooks() {
    // Add breadcrumbs
    add_action('aqualuxe_content_before', 'woocommerce_breadcrumb', 10);
    
    // Add result count and catalog ordering
    add_action('woocommerce_before_shop_loop', 'aqualuxe_shop_header', 10);
    
    // Add product link close
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);
    
    // Add product details wrapper
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_details_wrapper_start', 20);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_details_wrapper_end', 5);
    
    // Add custom add to cart button
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_template_loop_add_to_cart', 10);
    
    // Add product actions wrapper
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_actions_wrapper_start', 9);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_actions_wrapper_end', 21);
    
    // Add product badges
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 10);
    
    // Add product rating
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    
    // Add product price
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
    
    // Add product excerpt
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_product_excerpt', 15);
    
    // Add product meta
    add_action('woocommerce_single_product_summary', 'aqualuxe_product_meta', 41);
    
    // Add product share
    add_action('woocommerce_single_product_summary', 'aqualuxe_product_share', 50);
    
    // Add product tabs wrapper
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_product_tabs_wrapper_start', 9);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_product_tabs_wrapper_end', 11);
    
    // Add related products wrapper
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_related_products_wrapper_start', 19);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_related_products_wrapper_end', 21);
    
    // Add upsells wrapper
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_upsells_wrapper_start', 14);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_upsells_wrapper_end', 16);
    
    // Add cross-sells wrapper
    add_action('woocommerce_cart_collaterals', 'aqualuxe_cross_sells_wrapper_start', 1);
    add_action('woocommerce_cart_collaterals', 'aqualuxe_cross_sells_wrapper_end', 3);
    
    // Add cart totals wrapper
    add_action('woocommerce_cart_collaterals', 'aqualuxe_cart_totals_wrapper_start', 9);
    add_action('woocommerce_cart_collaterals', 'aqualuxe_cart_totals_wrapper_end', 11);
    
    // Add checkout coupon wrapper
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_coupon_wrapper_start', 9);
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_coupon_wrapper_end', 11);
    
    // Add checkout login wrapper
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_login_wrapper_start', 4);
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_login_wrapper_end', 6);
    
    // Add checkout billing and shipping wrapper
    add_action('woocommerce_checkout_before_customer_details', 'aqualuxe_checkout_billing_shipping_wrapper_start', 10);
    add_action('woocommerce_checkout_after_customer_details', 'aqualuxe_checkout_billing_shipping_wrapper_end', 10);
    
    // Add checkout order review wrapper
    add_action('woocommerce_checkout_before_order_review_heading', 'aqualuxe_checkout_order_review_wrapper_start', 10);
    add_action('woocommerce_checkout_after_order_review', 'aqualuxe_checkout_order_review_wrapper_end', 10);
    
    // Add account navigation wrapper
    add_action('woocommerce_before_account_navigation', 'aqualuxe_account_navigation_wrapper_start', 10);
    add_action('woocommerce_after_account_navigation', 'aqualuxe_account_navigation_wrapper_end', 10);
    
    // Add account content wrapper
    add_action('woocommerce_account_content', 'aqualuxe_account_content_wrapper_start', 1);
    add_action('woocommerce_account_content', 'aqualuxe_account_content_wrapper_end', 999);
}
add_action('init', 'aqualuxe_add_woocommerce_hooks');

/**
 * Shop header
 */
function aqualuxe_shop_header() {
    ?>
    <div class="aqualuxe-shop-header">
        <div class="aqualuxe-shop-header-left">
            <?php woocommerce_result_count(); ?>
        </div>
        <div class="aqualuxe-shop-header-right">
            <?php woocommerce_catalog_ordering(); ?>
        </div>
    </div>
    <?php
}

/**
 * Product details wrapper start
 */
function aqualuxe_product_details_wrapper_start() {
    echo '<div class="aqualuxe-product-details">';
}

/**
 * Product details wrapper end
 */
function aqualuxe_product_details_wrapper_end() {
    echo '</div>';
}

/**
 * Product actions wrapper start
 */
function aqualuxe_product_actions_wrapper_start() {
    echo '<div class="aqualuxe-product-actions">';
}

/**
 * Product actions wrapper end
 */
function aqualuxe_product_actions_wrapper_end() {
    echo '</div>';
}

/**
 * Product badges
 */
function aqualuxe_product_badges() {
    global $product;
    
    // Sale badge
    if ($product->is_on_sale()) {
        echo '<span class="aqualuxe-badge aqualuxe-badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    // New badge
    $newness_days = 30;
    $created = strtotime($product->get_date_created());
    
    if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
        echo '<span class="aqualuxe-badge aqualuxe-badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if ($product->is_featured()) {
        echo '<span class="aqualuxe-badge aqualuxe-badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="aqualuxe-badge aqualuxe-badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
}

/**
 * Template loop product excerpt
 */
function aqualuxe_template_loop_product_excerpt() {
    global $product;
    
    // Check if excerpt should be displayed
    if (!get_theme_mod('aqualuxe_show_product_excerpt', true)) {
        return;
    }
    
    // Get excerpt
    $excerpt = $product->get_short_description();
    
    // Display excerpt
    if ($excerpt) {
        echo '<div class="aqualuxe-product-excerpt">' . wp_trim_words($excerpt, 15, '...') . '</div>';
    }
}

/**
 * Template loop add to cart
 */
function aqualuxe_template_loop_add_to_cart() {
    global $product;
    
    // Get add to cart button
    $button = woocommerce_get_product_availability();
    
    // Display add to cart button
    echo '<div class="aqualuxe-add-to-cart">';
    woocommerce_template_loop_add_to_cart();
    echo '</div>';
}

/**
 * Product meta
 */
function aqualuxe_product_meta() {
    global $product;
    
    // Get product categories
    $categories = get_the_terms($product->get_id(), 'product_cat');
    
    // Get product tags
    $tags = get_the_terms($product->get_id(), 'product_tag');
    
    // Display product meta
    echo '<div class="aqualuxe-product-meta">';
    
    // Display categories
    if ($categories) {
        echo '<div class="aqualuxe-product-categories">';
        echo '<span class="meta-label">' . esc_html__('Categories:', 'aqualuxe') . '</span>';
        echo '<span class="meta-value">';
        
        $category_links = array();
        
        foreach ($categories as $category) {
            $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
        }
        
        echo implode(', ', $category_links);
        
        echo '</span>';
        echo '</div>';
    }
    
    // Display tags
    if ($tags) {
        echo '<div class="aqualuxe-product-tags">';
        echo '<span class="meta-label">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        echo '<span class="meta-value">';
        
        $tag_links = array();
        
        foreach ($tags as $tag) {
            $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
        }
        
        echo implode(', ', $tag_links);
        
        echo '</span>';
        echo '</div>';
    }
    
    // Display SKU
    if ($product->get_sku()) {
        echo '<div class="aqualuxe-product-sku">';
        echo '<span class="meta-label">' . esc_html__('SKU:', 'aqualuxe') . '</span>';
        echo '<span class="meta-value">' . esc_html($product->get_sku()) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Product share
 */
function aqualuxe_product_share() {
    // Check if social sharing is enabled
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return;
    }
    
    // Get social sharing networks
    $networks = get_theme_mod('aqualuxe_social_sharing_networks', array('facebook', 'twitter', 'linkedin', 'pinterest'));
    
    // Get product URL
    $product_url = urlencode(get_permalink());
    
    // Get product title
    $product_title = urlencode(get_the_title());
    
    // Get product image
    $product_image = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
    
    // Display product share
    echo '<div class="aqualuxe-product-share">';
    echo '<span class="share-label">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    echo '<div class="share-buttons">';
    
    // Facebook
    if (in_array('facebook', $networks)) {
        echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $product_url . '" target="_blank" rel="noopener noreferrer" class="share-button facebook">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // Twitter
    if (in_array('twitter', $networks)) {
        echo '<a href="https://twitter.com/intent/tweet?url=' . $product_url . '&text=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button twitter">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // LinkedIn
    if (in_array('linkedin', $networks)) {
        echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $product_url . '&title=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button linkedin">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // Pinterest
    if (in_array('pinterest', $networks)) {
        echo '<a href="https://pinterest.com/pin/create/button/?url=' . $product_url . '&media=' . $product_image . '&description=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button pinterest">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // Reddit
    if (in_array('reddit', $networks)) {
        echo '<a href="https://www.reddit.com/submit?url=' . $product_url . '&title=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button reddit">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-reddit"><path d="M22 11.5c0-1.4-1.1-2.5-2.5-2.5-.6 0-1.1.2-1.5.5-1.4-.9-3.1-1.5-5-1.5l1-4.5 3.1.7c.3.7 1 1.2 1.8 1.2 1.1 0 2-.9 2-2s-.9-2-2-2c-.7 0-1.4.4-1.7 1l-3.5-.8c-.3-.1-.7.1-.8.4l-1.3 5.8c-2 0-3.9.6-5.3 1.5-.4-.3-.9-.5-1.5-.5-1.4 0-2.5 1.1-2.5 2.5 0 1 .6 1.9 1.4 2.3-.1.3-.1.6-.1.9 0 3.3 3.8 6 8.5 6s8.5-2.7 8.5-6c0-.3 0-.6-.1-.9.9-.4 1.5-1.3 1.5-2.3zm-14.5 1c0-.8.7-1.5 1.5-1.5s1.5.7 1.5 1.5-.7 1.5-1.5 1.5-1.5-.7-1.5-1.5zm8.5 4c-1.2 1.2-3.1 1.2-4.3 0-.2-.2-.2-.5 0-.7.2-.2.5-.2.7 0 .8.8 2.1.8 2.9 0 .2-.2.5-.2.7 0 .2.2.2.5 0 .7zm.5-2.5c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5 1.5.7 1.5 1.5-.7 1.5-1.5 1.5z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Reddit', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // Email
    if (in_array('email', $networks)) {
        echo '<a href="mailto:?subject=' . $product_title . '&body=' . $product_url . '" class="share-button email">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // WhatsApp
    if (in_array('whatsapp', $networks)) {
        echo '<a href="https://api.whatsapp.com/send?text=' . $product_title . ' ' . $product_url . '" target="_blank" rel="noopener noreferrer" class="share-button whatsapp">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-whatsapp"><path d="M17.498 14.382c-.301-.15-1.767-.867-2.04-.966-.273-.101-.473-.15-.673.15-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.465.13-.615.136-.135.301-.345.451-.523.146-.181.194-.301.297-.496.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.172-.015-.371-.015-.571-.015-.2 0-.523.074-.797.359-.273.3-1.045 1.02-1.045 2.475s1.07 2.865 1.219 3.075c.149.195 2.105 3.195 5.1 4.485.714.3 1.27.48 1.704.629.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345z"></path><path d="M20.52 3.449C12.831-3.984.106 1.407.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652c1.746.943 3.71 1.444 5.715 1.447h.006c9.6 0 16.16-9.443 14.464-17.946zm-4.465 13.26c-.421.745-1.002 1.35-1.731 1.761-1.159.643-2.576.816-3.96.527-1.319-.274-2.576-.866-3.711-1.714-2.266-1.714-3.815-4.232-4.368-7.076-.319-1.644.044-3.359 1.006-4.763.826-1.206 2.107-2.057 3.576-2.354 1.624-.328 3.263.213 4.129 1.446.245.349.391.762.427 1.188.073.842-.146 1.681-.64 2.353-.126.171-.429.146-.578.045-.307-.21-.578-.45-.825-.705-.095-.098-.128-.245-.095-.379.128-.507.073-1.035-.151-1.506-.465-.97-1.644-1.416-2.692-1.035-.7.253-1.279.757-1.677 1.427-.605 1.019-.639 2.27-.093 3.3.466.883 1.185 1.625 2.094 2.15.093.053.155.172.137.273-.064.348-.101.697-.112 1.046-.005.182-.159.328-.337.348-.474.053-.941-.602-1.155-.927-.878-1.336-1.151-2.983-.762-4.532.473-1.881 1.841-3.392 3.572-3.949 1.813-.58 3.87-.039 5.33 1.337.9.853 1.332 2.076 1.187 3.305-.057.476-.156.942-.285 1.394-.129.452-.343.881-.63 1.269-.215.29-.518.52-.851.666-.467.205-.995.271-1.512.183-.216-.036-.33-.276-.256-.484.09-.257.152-.52.184-.786.02-.167.149-.308.316-.341.356-.069.684-.241.935-.5.443-.454.648-1.09.621-1.728-.021-.5-.148-.982-.372-1.42-.69-1.336-2.232-2.085-3.772-1.84-1.267.203-2.339.997-2.9 2.13-.627 1.27-.555 2.812.199 4.048.195.319.401.629.635.913.202.246.336.543.386.858.082.513.125 1.031.127 1.55.001.355-.396.6-.73.46-.276-.116-.523-.283-.74-.488-.336-.317-.571-.724-.67-1.162-.103-.461-.121-.938-.051-1.401.058-.381.183-.744.37-1.069.057-.099.037-.225-.046-.302-.613-.566-1.198-1.17-1.621-1.877-1.132-1.896-1.048-4.316.218-6.132.783-1.12 1.927-1.913 3.205-2.352 1.27-.437 2.618-.516 3.925-.23 1.155.252 2.17.91 2.892 1.803.738.91 1.132 2.063 1.121 3.236-.005.52-.092 1.035-.257 1.53-.29.87-.724 1.656-1.271 2.333z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on WhatsApp', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    // Telegram
    if (in_array('telegram', $networks)) {
        echo '<a href="https://t.me/share/url?url=' . $product_url . '&text=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button telegram">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-telegram"><path d="M21.198 2.433a2.242 2.242 0 0 0-1.022.215l-16.5 7.5a2.25 2.25 0 0 0 .126 4.303l3.984 1.328 2.25 7.5a2.25 2.25 0 0 0 4.1.15l4.5-7.5a.75.75 0 0 0-1.3-.75l-4.5 7.5-2.25-7.5a2.25 2.25 0 0 0-1.374-1.47l-3.984-1.328 16.5-7.5c.649-.294.915-1.067.594-1.714a1.5 1.5 0 0 0-1.022-.734z"></path></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Telegram', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Product tabs wrapper start
 */
function aqualuxe_product_tabs_wrapper_start() {
    echo '<div class="aqualuxe-product-tabs-wrapper">';
}

/**
 * Product tabs wrapper end
 */
function aqualuxe_product_tabs_wrapper_end() {
    echo '</div>';
}

/**
 * Related products wrapper start
 */
function aqualuxe_related_products_wrapper_start() {
    echo '<div class="aqualuxe-related-products-wrapper">';
}

/**
 * Related products wrapper end
 */
function aqualuxe_related_products_wrapper_end() {
    echo '</div>';
}

/**
 * Upsells wrapper start
 */
function aqualuxe_upsells_wrapper_start() {
    echo '<div class="aqualuxe-upsells-wrapper">';
}

/**
 * Upsells wrapper end
 */
function aqualuxe_upsells_wrapper_end() {
    echo '</div>';
}

/**
 * Cross-sells wrapper start
 */
function aqualuxe_cross_sells_wrapper_start() {
    echo '<div class="aqualuxe-cross-sells-wrapper">';
}

/**
 * Cross-sells wrapper end
 */
function aqualuxe_cross_sells_wrapper_end() {
    echo '</div>';
}

/**
 * Cart totals wrapper start
 */
function aqualuxe_cart_totals_wrapper_start() {
    echo '<div class="aqualuxe-cart-totals-wrapper">';
}

/**
 * Cart totals wrapper end
 */
function aqualuxe_cart_totals_wrapper_end() {
    echo '</div>';
}

/**
 * Checkout coupon wrapper start
 */
function aqualuxe_checkout_coupon_wrapper_start() {
    echo '<div class="aqualuxe-checkout-coupon-wrapper">';
}

/**
 * Checkout coupon wrapper end
 */
function aqualuxe_checkout_coupon_wrapper_end() {
    echo '</div>';
}

/**
 * Checkout login wrapper start
 */
function aqualuxe_checkout_login_wrapper_start() {
    echo '<div class="aqualuxe-checkout-login-wrapper">';
}

/**
 * Checkout login wrapper end
 */
function aqualuxe_checkout_login_wrapper_end() {
    echo '</div>';
}

/**
 * Checkout billing and shipping wrapper start
 */
function aqualuxe_checkout_billing_shipping_wrapper_start() {
    echo '<div class="aqualuxe-checkout-billing-shipping-wrapper">';
}

/**
 * Checkout billing and shipping wrapper end
 */
function aqualuxe_checkout_billing_shipping_wrapper_end() {
    echo '</div>';
}

/**
 * Checkout order review wrapper start
 */
function aqualuxe_checkout_order_review_wrapper_start() {
    echo '<div class="aqualuxe-checkout-order-review-wrapper">';
}

/**
 * Checkout order review wrapper end
 */
function aqualuxe_checkout_order_review_wrapper_end() {
    echo '</div>';
}

/**
 * Account navigation wrapper start
 */
function aqualuxe_account_navigation_wrapper_start() {
    echo '<div class="aqualuxe-account-navigation-wrapper">';
}

/**
 * Account navigation wrapper end
 */
function aqualuxe_account_navigation_wrapper_end() {
    echo '</div>';
}

/**
 * Account content wrapper start
 */
function aqualuxe_account_content_wrapper_start() {
    echo '<div class="aqualuxe-account-content-wrapper">';
}

/**
 * Account content wrapper end
 */
function aqualuxe_account_content_wrapper_end() {
    echo '</div>';
}