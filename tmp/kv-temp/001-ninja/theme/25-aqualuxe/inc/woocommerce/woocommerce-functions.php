<?php
/**
 * WooCommerce functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
    // Declare WooCommerce support
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 6,
        ),
    ));
    
    // Enable gallery features
    if (get_theme_mod('aqualuxe_enable_product_zoom', true)) {
        add_theme_support('wc-product-gallery-zoom');
    }
    
    if (get_theme_mod('aqualuxe_enable_product_lightbox', true)) {
        add_theme_support('wc-product-gallery-lightbox');
    }
    
    if (get_theme_mod('aqualuxe_enable_product_slider', true)) {
        add_theme_support('wc-product-gallery-slider');
    }
}

/**
 * Locate WooCommerce template files in theme's woocommerce directory.
 *
 * @param string $template      Template file path.
 * @param string $template_name Template name.
 * @param string $template_path Template path.
 * @return string Template file path.
 */
function aqualuxe_woocommerce_locate_template($template, $template_name, $template_path) {
    $theme_template = trailingslashit(get_template_directory()) . 'woocommerce/' . $template_name;
    
    // Return theme template if it exists
    if (file_exists($theme_template)) {
        return $theme_template;
    }
    
    // Return original template otherwise
    return $template;
}

/**
 * Set number of products per page.
 *
 * @return int Number of products per page.
 */
function aqualuxe_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}

/**
 * Set number of product columns.
 *
 * @return int Number of product columns.
 */
function aqualuxe_product_columns() {
    return get_theme_mod('aqualuxe_product_columns', 4);
}

/**
 * Set related products arguments.
 *
 * @param array $args Related products arguments.
 * @return array Modified related products arguments.
 */
function aqualuxe_related_products_args($args) {
    $args['posts_per_page'] = get_theme_mod('aqualuxe_related_products_count', 4);
    $args['columns'] = get_theme_mod('aqualuxe_related_products_columns', 4);
    
    return $args;
}

/**
 * Set upsell products arguments.
 *
 * @param array $args Upsell products arguments.
 * @return array Modified upsell products arguments.
 */
function aqualuxe_upsell_products_args($args) {
    $args['posts_per_page'] = get_theme_mod('aqualuxe_related_products_count', 4);
    $args['columns'] = get_theme_mod('aqualuxe_related_products_columns', 4);
    
    return $args;
}

/**
 * Set cross-sell columns.
 *
 * @return int Number of cross-sell columns.
 */
function aqualuxe_cross_sell_columns() {
    return 2;
}

/**
 * Set cross-sell total.
 *
 * @return int Number of cross-sell products.
 */
function aqualuxe_cross_sell_total() {
    return 2;
}

/**
 * Modify product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array Modified product tabs.
 */
function aqualuxe_product_tabs($tabs) {
    // Add care instructions tab
    $tabs['care_instructions'] = array(
        'title'    => __('Care Instructions', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_care_instructions_tab_content',
    );
    
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 40,
        'callback' => 'aqualuxe_shipping_tab_content',
    );
    
    // Add FAQ tab
    $tabs['faq'] = array(
        'title'    => __('FAQ', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'aqualuxe_faq_tab_content',
    );
    
    return $tabs;
}

/**
 * Care instructions tab content.
 */
function aqualuxe_care_instructions_tab_content() {
    global $product;
    
    // Get care instructions from product meta
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (empty($care_instructions)) {
        // Default care instructions for aquatic products
        ?>
        <h3><?php esc_html_e('Care Instructions for Your Aquatic Life', 'aqualuxe'); ?></h3>
        <div class="care-instructions">
            <div class="care-section">
                <h4><?php esc_html_e('Water Parameters', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Maintain stable water temperature between 24-28°C (75-82°F)', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Keep pH levels between 6.8-7.5 depending on species', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Monitor ammonia, nitrite, and nitrate levels regularly', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Perform regular water changes (20-30% weekly)', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div class="care-section">
                <h4><?php esc_html_e('Feeding', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Feed small amounts 1-2 times daily', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Provide a varied diet appropriate for your species', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Remove uneaten food after 2-3 minutes', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div class="care-section">
                <h4><?php esc_html_e('Environment', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Provide appropriate hiding places and swimming space', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Maintain proper filtration suitable for your tank size', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Ensure adequate lighting (8-10 hours daily)', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div class="care-section">
                <h4><?php esc_html_e('Health Monitoring', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Observe fish daily for signs of stress or illness', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Quarantine new additions before introducing to main tank', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Contact our support team if you notice unusual behavior', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    } else {
        echo wp_kses_post($care_instructions);
    }
}

/**
 * Shipping tab content.
 */
function aqualuxe_shipping_tab_content() {
    global $product;
    
    // Get shipping info from product meta
    $shipping_info = get_post_meta($product->get_id(), '_shipping_info', true);
    
    if (empty($shipping_info)) {
        // Default shipping information
        ?>
        <h3><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></h3>
        <div class="shipping-info">
            <div class="shipping-section">
                <h4><?php esc_html_e('Live Arrival Guarantee', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('We guarantee that your aquatic life will arrive alive and healthy. In the rare event that your fish arrive dead or severely distressed, please take a photo within 2 hours of delivery and contact us immediately for a replacement or refund.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="shipping-section">
                <h4><?php esc_html_e('Shipping Process', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('All live fish are shipped via expedited shipping methods', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Fish are packaged in insulated containers with heat/cold packs as needed', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Shipping typically takes 1-2 days depending on your location', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('We ship Monday-Wednesday to avoid weekend delays', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div class="shipping-section">
                <h4><?php esc_html_e('International Shipping', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('We ship to select international destinations. International orders require special handling and may be subject to customs regulations and import fees. Please contact us before placing an international order.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="shipping-section">
                <h4><?php esc_html_e('Returns & Exchanges', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('Due to the nature of live aquatic products, we cannot accept returns. For equipment and supplies, unused items in original packaging may be returned within 30 days for a full refund (excluding shipping costs).', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    } else {
        echo wp_kses_post($shipping_info);
    }
}

/**
 * FAQ tab content.
 */
function aqualuxe_faq_tab_content() {
    global $product;
    
    // Get FAQ from product meta
    $faq = get_post_meta($product->get_id(), '_faq', true);
    
    if (empty($faq)) {
        // Default FAQ
        ?>
        <h3><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h3>
        <div class="product-faq">
            <div class="faq-item">
                <h4><?php esc_html_e('How large will this fish grow?', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('The adult size depends on the species, tank conditions, and diet. Please refer to the product description for specific information about this species.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php esc_html_e('What size tank is recommended?', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('We recommend following the minimum tank size guidelines in the product description. Generally, larger tanks provide more stable water conditions and better quality of life for your aquatic pets.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php esc_html_e('Is this species compatible with my existing fish?', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('Compatibility depends on many factors including temperament, water parameter requirements, and tank size. Please contact our support team with details about your current setup for personalized advice.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php esc_html_e('How do I acclimate my new fish?', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('We recommend a slow drip acclimation method over 1-2 hours to allow your new fish to adjust to your tank\'s water parameters. Detailed acclimation instructions are included with every shipment.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="faq-item">
                <h4><?php esc_html_e('What if my fish arrives sick or dead?', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('We offer a Live Arrival Guarantee. Please take photos within 2 hours of delivery and contact our support team immediately for assistance with replacement or refund.', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    } else {
        echo wp_kses_post($faq);
    }
}

/**
 * Modify checkout fields.
 *
 * @param array $fields Checkout fields.
 * @return array Modified checkout fields.
 */
function aqualuxe_checkout_fields($fields) {
    // Add phone field placeholder
    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['placeholder'] = __('Phone number', 'aqualuxe');
    }
    
    // Add email field placeholder
    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['placeholder'] = __('Email address', 'aqualuxe');
    }
    
    // Make company field optional
    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['required'] = false;
        $fields['billing']['billing_company']['placeholder'] = __('Company name (optional)', 'aqualuxe');
    }
    
    // Add order notes placeholder
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['placeholder'] = __('Notes about your order, e.g. special delivery instructions or care requirements', 'aqualuxe');
    }
    
    return $fields;
}

/**
 * Modify account menu items.
 *
 * @param array $items Account menu items.
 * @return array Modified account menu items.
 */
function aqualuxe_account_menu_items($items) {
    // Add wishlist item
    if (get_theme_mod('aqualuxe_enable_wishlist', true)) {
        $items['wishlist'] = __('Wishlist', 'aqualuxe');
    }
    
    // Add subscriptions item if WooCommerce Subscriptions is active
    if (class_exists('WC_Subscriptions')) {
        $items['subscriptions'] = __('Subscriptions', 'aqualuxe');
    }
    
    // Add downloads item if downloads are enabled
    if (wc_get_page_id('myaccount_downloads') !== -1) {
        $items['downloads'] = __('Downloads', 'aqualuxe');
    }
    
    // Reorder items
    $new_items = array();
    
    // Always keep dashboard first
    if (isset($items['dashboard'])) {
        $new_items['dashboard'] = $items['dashboard'];
        unset($items['dashboard']);
    }
    
    // Add orders next
    if (isset($items['orders'])) {
        $new_items['orders'] = $items['orders'];
        unset($items['orders']);
    }
    
    // Add subscriptions if available
    if (isset($items['subscriptions'])) {
        $new_items['subscriptions'] = $items['subscriptions'];
        unset($items['subscriptions']);
    }
    
    // Add wishlist if available
    if (isset($items['wishlist'])) {
        $new_items['wishlist'] = $items['wishlist'];
        unset($items['wishlist']);
    }
    
    // Add downloads if available
    if (isset($items['downloads'])) {
        $new_items['downloads'] = $items['downloads'];
        unset($items['downloads']);
    }
    
    // Add remaining items
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
    }
    
    return $new_items;
}

/**
 * Add quick view button to product loop.
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    if (!$product || !get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }
    
    echo '<div class="quick-view-wrapper">';
    echo '<button type="button" class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '</div>';
}

/**
 * Handle quick view AJAX request.
 */
function aqualuxe_quick_view_ajax() {
    if (!isset($_POST['product_id']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-quick-view-nonce')) {
        wp_send_json_error(__('Invalid request', 'aqualuxe'));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(__('Product not found', 'aqualuxe'));
    }
    
    ob_start();
    ?>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image('medium_large'); ?>
        </div>
        <div class="quick-view-details">
            <h2 class="product-title"><?php echo esc_html($product->get_name()); ?></h2>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
            <div class="rating">
                <?php if ($product->get_average_rating() > 0) : ?>
                    <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    <span class="rating-count">(<?php echo esc_html($product->get_review_count()); ?>)</span>
                <?php endif; ?>
            </div>
            <div class="description">
                <?php echo wp_kses_post($product->get_short_description()); ?>
            </div>
            <?php if ($product->is_in_stock()) : ?>
                <div class="stock in-stock"><?php esc_html_e('In stock', 'aqualuxe'); ?></div>
            <?php else : ?>
                <div class="stock out-of-stock"><?php esc_html_e('Out of stock', 'aqualuxe'); ?></div>
            <?php endif; ?>
            
            <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                    <div class="quantity">
                        <label for="quantity_<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                        <input type="number" id="quantity_<?php echo esc_attr($product->get_id()); ?>" class="input-text qty text" step="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" name="quantity" value="1" title="<?php esc_attr_e('Qty', 'aqualuxe'); ?>" size="4" inputmode="numeric" />
                    </div>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                </form>
            <?php elseif ($product->is_type('variable')) : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button view-product"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
            <?php endif; ?>
            
            <div class="product-meta">
                <?php if ($product->get_sku()) : ?>
                    <span class="sku-wrapper"><?php esc_html_e('SKU:', 'aqualuxe'); ?> <span class="sku"><?php echo esc_html($product->get_sku()); ?></span></span>
                <?php endif; ?>
                
                <?php if (get_theme_mod('aqualuxe_show_product_categories', true)) : ?>
                    <span class="posted-in"><?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="categories-label">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . '</span> ', ''); ?></span>
                <?php endif; ?>
            </div>
            
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-details-link"><?php esc_html_e('View Full Details', 'aqualuxe'); ?></a>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    
    wp_send_json_success($output);
}

/**
 * Add quick view modal to footer.
 */
function aqualuxe_quick_view_modal() {
    if (!get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }
    ?>
    <div id="quick-view-modal" class="quick-view-modal" style="display: none;">
        <div class="quick-view-modal-content">
            <span class="close-modal">&times;</span>
            <div class="quick-view-container"></div>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Quick View functionality
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                
                var productId = $(this).data('product-id');
                var modal = $('#quick-view-modal');
                var container = modal.find('.quick-view-container');
                
                // Show loading state
                container.html('<div class="loading"><?php esc_html_e('Loading...', 'aqualuxe'); ?></div>');
                modal.fadeIn();
                
                // Get product data via AJAX
                $.ajax({
                    url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: '<?php echo wp_create_nonce('aqualuxe-quick-view-nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            container.html(response.data);
                        } else {
                            container.html('<div class="error">' + response.data + '</div>');
                        }
                    },
                    error: function() {
                        container.html('<div class="error"><?php esc_html_e('Error loading product data', 'aqualuxe'); ?></div>');
                    }
                });
            });
            
            // Close modal on X click
            $(document).on('click', '.close-modal', function() {
                $('#quick-view-modal').fadeOut();
            });
            
            // Close modal on outside click
            $(document).on('click', '.quick-view-modal', function(e) {
                if ($(e.target).hasClass('quick-view-modal')) {
                    $(this).fadeOut();
                }
            });
            
            // Close modal on ESC key
            $(document).keyup(function(e) {
                if (e.key === "Escape") {
                    $('#quick-view-modal').fadeOut();
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Add wishlist button to product loop.
 */
function aqualuxe_wishlist_button() {
    global $product;
    
    if (!$product || !get_theme_mod('aqualuxe_enable_wishlist', true)) {
        return;
    }
    
    // Check if product is in wishlist
    $wishlist = aqualuxe_get_wishlist();
    $in_wishlist = in_array($product->get_id(), $wishlist);
    
    echo '<div class="wishlist-wrapper">';
    echo '<button type="button" class="button wishlist-button' . ($in_wishlist ? ' in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="wishlist-icon">' . ($in_wishlist ? '❤' : '♡') . '</span>';
    echo '<span class="wishlist-text">' . ($in_wishlist ? esc_html__('In Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe')) . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Get user's wishlist.
 *
 * @return array Array of product IDs in wishlist.
 */
function aqualuxe_get_wishlist() {
    if (is_user_logged_in()) {
        // Get wishlist from user meta
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
    } else {
        // Get wishlist from cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
    }
    
    return array_map('absint', $wishlist);
}

/**
 * Handle wishlist add AJAX request.
 */
function aqualuxe_wishlist_add_ajax() {
    if (!isset($_POST['product_id']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-wishlist-nonce')) {
        wp_send_json_error(__('Invalid request', 'aqualuxe'));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(__('Product not found', 'aqualuxe'));
    }
    
    if (is_user_logged_in()) {
        // Add to user meta
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        }
    } else {
        // Add to cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }
    }
    
    wp_send_json_success(array(
        'message' => sprintf(__('%s added to wishlist', 'aqualuxe'), $product->get_name()),
        'product_id' => $product_id,
    ));
}

/**
 * Handle wishlist remove AJAX request.
 */
function aqualuxe_wishlist_remove_ajax() {
    if (!isset($_POST['product_id']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-wishlist-nonce')) {
        wp_send_json_error(__('Invalid request', 'aqualuxe'));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(__('Product not found', 'aqualuxe'));
    }
    
    if (is_user_logged_in()) {
        // Remove from user meta
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (is_array($wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        }
    } else {
        // Remove from cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (is_array($wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }
    }
    
    wp_send_json_success(array(
        'message' => sprintf(__('%s removed from wishlist', 'aqualuxe'), $product->get_name()),
        'product_id' => $product_id,
    ));
}

/**
 * Add advanced filtering to shop page.
 */
function aqualuxe_advanced_filtering() {
    // Only show on shop and product archive pages
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    // Get all product categories
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
    
    // Get all product tags
    $product_tags = get_terms(array(
        'taxonomy' => 'product_tag',
        'hide_empty' => true,
    ));
    
    // Get min and max prices
    $min_price = floor(wc_get_min_price());
    $max_price = ceil(wc_get_max_price());
    
    // Get current filters
    $current_category = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
    $current_tag = isset($_GET['product_tag']) ? sanitize_text_field($_GET['product_tag']) : '';
    $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
    $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
    
    aqualuxe_before_product_filter();
    ?>
    <div class="advanced-filtering">
        <button class="filter-toggle"><?php esc_html_e('Filter Products', 'aqualuxe'); ?> <span class="filter-icon">+</span></button>
        
        <div class="filter-container">
            <form class="filter-form" method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                <div class="filter-row">
                    <?php if (!empty($product_categories)) : ?>
                        <div class="filter-column">
                            <h4><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                            <select name="product_cat" class="filter-select">
                                <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                                <?php foreach ($product_categories as $category) : ?>
                                    <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($current_category, $category->slug); ?>><?php echo esc_html($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($product_tags)) : ?>
                        <div class="filter-column">
                            <h4><?php esc_html_e('Tags', 'aqualuxe'); ?></h4>
                            <select name="product_tag" class="filter-select">
                                <option value=""><?php esc_html_e('All Tags', 'aqualuxe'); ?></option>
                                <?php foreach ($product_tags as $tag) : ?>
                                    <option value="<?php echo esc_attr($tag->slug); ?>" <?php selected($current_tag, $tag->slug); ?>><?php echo esc_html($tag->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="filter-column">
                        <h4><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                        <div class="price-range">
                            <input type="number" name="min_price" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_min_price); ?>" placeholder="<?php esc_attr_e('Min', 'aqualuxe'); ?>" />
                            <span class="price-separator">-</span>
                            <input type="number" name="max_price" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_max_price); ?>" placeholder="<?php esc_attr_e('Max', 'aqualuxe'); ?>" />
                        </div>
                    </div>
                    
                    <div class="filter-column">
                        <h4><?php esc_html_e('Sort By', 'aqualuxe'); ?></h4>
                        <select name="orderby" class="filter-select">
                            <option value="menu_order" <?php selected($current_orderby, 'menu_order'); ?>><?php esc_html_e('Default sorting', 'aqualuxe'); ?></option>
                            <option value="popularity" <?php selected($current_orderby, 'popularity'); ?>><?php esc_html_e('Sort by popularity', 'aqualuxe'); ?></option>
                            <option value="rating" <?php selected($current_orderby, 'rating'); ?>><?php esc_html_e('Sort by average rating', 'aqualuxe'); ?></option>
                            <option value="date" <?php selected($current_orderby, 'date'); ?>><?php esc_html_e('Sort by latest', 'aqualuxe'); ?></option>
                            <option value="price" <?php selected($current_orderby, 'price'); ?>><?php esc_html_e('Sort by price: low to high', 'aqualuxe'); ?></option>
                            <option value="price-desc" <?php selected($current_orderby, 'price-desc'); ?>><?php esc_html_e('Sort by price: high to low', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="button filter-button"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="reset-filters"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></a>
                </div>
                
                <?php
                // Keep query string parameters
                foreach ($_GET as $key => $value) {
                    if (!in_array($key, array('product_cat', 'product_tag', 'min_price', 'max_price', 'orderby'))) {
                        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
                    }
                }
                ?>
            </form>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Toggle filter container
            $('.filter-toggle').on('click', function() {
                $('.filter-container').slideToggle();
                $(this).find('.filter-icon').text(function(i, text) {
                    return text === '+' ? '-' : '+';
                });
            });
        });
    })(jQuery);
    </script>
    <?php
    aqualuxe_after_product_filter();
}

/**
 * Add currency switcher.
 */
function aqualuxe_currency_switcher() {
    // Only show if multi-currency is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multi_currency', true)) {
        return;
    }
    
    // Check if WooCommerce Multi-currency is active
    if (!class_exists('WCML_Multi_Currency')) {
        return;
    }
    
    global $woocommerce_wpml;
    
    if (!is_object($woocommerce_wpml) || !isset($woocommerce_wpml->multi_currency)) {
        return;
    }
    
    $currencies = $woocommerce_wpml->multi_currency->get_currencies();
    $current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
    
    if (empty($currencies)) {
        return;
    }
    
    aqualuxe_before_currency_switcher();
    ?>
    <div class="currency-switcher-wrapper">
        <div class="currency-switcher">
            <button class="currency-switcher-toggle" aria-expanded="false" aria-controls="currency-switcher-dropdown">
                <span class="currency-code"><?php echo esc_html($current_currency); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="currency-switcher-arrow" width="16" height="16">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="currency-switcher-dropdown" class="currency-switcher-dropdown" aria-hidden="true">
                <ul class="currency-list">
                    <?php foreach ($currencies as $code => $currency) : ?>
                        <li class="currency-item<?php echo $code === $current_currency ? ' active' : ''; ?>">
                            <a href="<?php echo esc_url(add_query_arg('currency', $code)); ?>" class="currency-link" <?php echo $code === $current_currency ? ' aria-current="true"' : ''; ?>>
                                <span class="currency-code"><?php echo esc_html($code); ?></span>
                                <span class="currency-name"><?php echo esc_html($currency['languages'][$woocommerce_wpml->multi_currency->get_client_language()]); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Currency switcher functionality
            $('.currency-switcher-toggle').on('click', function() {
                const dropdown = $(this).next('.currency-switcher-dropdown');
                const expanded = $(this).attr('aria-expanded') === 'true';
                
                $(this).attr('aria-expanded', !expanded);
                dropdown.attr('aria-hidden', expanded);
                
                if (!expanded) {
                    dropdown.addClass('active');
                } else {
                    dropdown.removeClass('active');
                }
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.currency-switcher').length) {
                    $('.currency-switcher-toggle').attr('aria-expanded', 'false');
                    $('.currency-switcher-dropdown').attr('aria-hidden', 'true').removeClass('active');
                }
            });
        });
    })(jQuery);
    </script>
    <?php
    aqualuxe_after_currency_switcher();
}

/**
 * Optimize address fields for international shipping.
 *
 * @param array $fields Address fields.
 * @return array Modified address fields.
 */
function aqualuxe_optimize_address_fields($fields) {
    // Make state field not required
    if (isset($fields['state'])) {
        $fields['state']['required'] = false;
    }
    
    // Add placeholder for address fields
    if (isset($fields['address_1'])) {
        $fields['address_1']['placeholder'] = __('Street address', 'aqualuxe');
    }
    
    if (isset($fields['address_2'])) {
        $fields['address_2']['placeholder'] = __('Apartment, suite, unit, etc. (optional)', 'aqualuxe');
    }
    
    if (isset($fields['city'])) {
        $fields['city']['placeholder'] = __('City', 'aqualuxe');
    }
    
    if (isset($fields['postcode'])) {
        $fields['postcode']['placeholder'] = __('Postal code / ZIP', 'aqualuxe');
    }
    
    return $fields;
}

/**
 * Add order tracking information.
 *
 * @param WC_Order $order Order object.
 */
function aqualuxe_order_tracking_info($order) {
    // Get tracking number and carrier
    $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
    $tracking_carrier = get_post_meta($order->get_id(), '_tracking_carrier', true);
    
    if (empty($tracking_number)) {
        return;
    }
    
    $tracking_url = '';
    
    // Generate tracking URL based on carrier
    switch ($tracking_carrier) {
        case 'usps':
            $tracking_url = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $tracking_number;
            break;
        case 'ups':
            $tracking_url = 'https://www.ups.com/track?tracknum=' . $tracking_number;
            break;
        case 'fedex':
            $tracking_url = 'https://www.fedex.com/apps/fedextrack/?tracknumbers=' . $tracking_number;
            break;
        case 'dhl':
            $tracking_url = 'https://www.dhl.com/en/express/tracking.html?AWB=' . $tracking_number;
            break;
    }
    
    ?>
    <div class="order-tracking-info">
        <h3><?php esc_html_e('Tracking Information', 'aqualuxe'); ?></h3>
        <p>
            <?php esc_html_e('Carrier:', 'aqualuxe'); ?> <strong><?php echo esc_html(ucfirst($tracking_carrier)); ?></strong><br>
            <?php esc_html_e('Tracking Number:', 'aqualuxe'); ?> <strong><?php echo esc_html($tracking_number); ?></strong>
        </p>
        <?php if (!empty($tracking_url)) : ?>
            <a href="<?php echo esc_url($tracking_url); ?>" target="_blank" class="button track-button"><?php esc_html_e('Track Package', 'aqualuxe'); ?></a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add product badges.
 */
function aqualuxe_product_badges() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $badges = array();
    
    // Sale badge
    if ($product->is_on_sale()) {
        $badges[] = array(
            'text' => __('Sale', 'aqualuxe'),
            'class' => 'sale-badge',
        );
    }
    
    // New badge (products less than 30 days old)
    $days_since_creation = (time() - strtotime($product->get_date_created())) / DAY_IN_SECONDS;
    if ($days_since_creation < 30) {
        $badges[] = array(
            'text' => __('New', 'aqualuxe'),
            'class' => 'new-badge',
        );
    }
    
    // Featured badge
    if ($product->is_featured()) {
        $badges[] = array(
            'text' => __('Featured', 'aqualuxe'),
            'class' => 'featured-badge',
        );
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        $badges[] = array(
            'text' => __('Out of Stock', 'aqualuxe'),
            'class' => 'out-of-stock-badge',
        );
    }
    
    // Limited stock badge
    if ($product->is_in_stock() && $product->get_stock_quantity() && $product->get_stock_quantity() < 5) {
        $badges[] = array(
            'text' => __('Low Stock', 'aqualuxe'),
            'class' => 'low-stock-badge',
        );
    }
    
    // Custom badges from product meta
    $custom_badge = get_post_meta($product->get_id(), '_custom_badge', true);
    if (!empty($custom_badge)) {
        $badges[] = array(
            'text' => $custom_badge,
            'class' => 'custom-badge',
        );
    }
    
    if (empty($badges)) {
        return;
    }
    
    echo '<div class="product-badges">';
    foreach ($badges as $badge) {
        echo '<span class="product-badge ' . esc_attr($badge['class']) . '">' . esc_html($badge['text']) . '</span>';
    }
    echo '</div>';
}

/**
 * Add size guide.
 */
function aqualuxe_size_guide() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Check if product has size attributes
    $has_size = false;
    $attributes = $product->get_attributes();
    
    foreach ($attributes as $attribute) {
        if ($attribute->get_name() === 'pa_size' || $attribute->get_name() === 'size') {
            $has_size = true;
            break;
        }
    }
    
    if (!$has_size) {
        return;
    }
    
    // Get size guide from product meta
    $size_guide = get_post_meta($product->get_id(), '_size_guide', true);
    
    if (empty($size_guide)) {
        // Use default size guide
        $size_guide = get_option('aqualuxe_default_size_guide', '');
    }
    
    if (empty($size_guide)) {
        return;
    }
    
    ?>
    <div class="size-guide-wrapper">
        <button type="button" class="size-guide-toggle"><?php esc_html_e('Size Guide', 'aqualuxe'); ?></button>
        
        <div class="size-guide-modal" style="display: none;">
            <div class="size-guide-modal-content">
                <span class="close-modal">&times;</span>
                <div class="size-guide-container">
                    <h3><?php esc_html_e('Size Guide', 'aqualuxe'); ?></h3>
                    <?php echo wp_kses_post($size_guide); ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Size guide functionality
            $('.size-guide-toggle').on('click', function() {
                $('.size-guide-modal').fadeIn();
            });
            
            // Close modal on X click
            $('.close-modal').on('click', function() {
                $('.size-guide-modal').fadeOut();
            });
            
            // Close modal on outside click
            $('.size-guide-modal').on('click', function(e) {
                if ($(e.target).hasClass('size-guide-modal')) {
                    $(this).fadeOut();
                }
            });
            
            // Close modal on ESC key
            $(document).keyup(function(e) {
                if (e.key === "Escape") {
                    $('.size-guide-modal').fadeOut();
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Add estimated delivery time.
 */
function aqualuxe_estimated_delivery() {
    global $product;
    
    if (!$product || !$product->is_in_stock()) {
        return;
    }
    
    // Get estimated delivery from product meta
    $estimated_delivery = get_post_meta($product->get_id(), '_estimated_delivery', true);
    
    if (empty($estimated_delivery)) {
        // Use default estimated delivery
        $estimated_delivery = get_option('aqualuxe_default_estimated_delivery', '3-5 business days');
    }
    
    ?>
    <div class="estimated-delivery">
        <span class="estimated-delivery-icon">🚚</span>
        <span class="estimated-delivery-text"><?php esc_html_e('Estimated Delivery:', 'aqualuxe'); ?> <strong><?php echo esc_html($estimated_delivery); ?></strong></span>
    </div>
    <?php
}

/**
 * Add product inquiry form.
 */
function aqualuxe_product_inquiry_form() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    ?>
    <div class="product-inquiry">
        <h3><?php esc_html_e('Have a Question?', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Fill out the form below and we\'ll get back to you as soon as possible.', 'aqualuxe'); ?></p>
        
        <form class="inquiry-form" method="post" action="#">
            <div class="form-row">
                <div class="form-column">
                    <label for="inquiry-name"><?php esc_html_e('Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="inquiry-name" name="inquiry-name" required>
                </div>
                <div class="form-column">
                    <label for="inquiry-email"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" id="inquiry-email" name="inquiry-email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-column">
                    <label for="inquiry-subject"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="inquiry-subject" name="inquiry-subject" value="<?php echo esc_attr(sprintf(__('Question about %s', 'aqualuxe'), $product->get_name())); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-column full-width">
                    <label for="inquiry-message"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <textarea id="inquiry-message" name="inquiry-message" rows="5" required></textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-column full-width">
                    <input type="hidden" name="product-id" value="<?php echo esc_attr($product->get_id()); ?>">
                    <input type="hidden" name="product-name" value="<?php echo esc_attr($product->get_name()); ?>">
                    <button type="submit" class="button inquiry-button"><?php esc_html_e('Send Inquiry', 'aqualuxe'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Add product social sharing.
 */
function aqualuxe_product_sharing() {
    global $product;
    
    if (!$product || !get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return;
    }
    
    $product_url = get_permalink();
    $product_title = $product->get_name();
    $product_image = wp_get_attachment_url($product->get_image_id());
    
    ?>
    <div class="social-sharing">
        <h4><?php esc_html_e('Share This Product', 'aqualuxe'); ?></h4>
        <ul class="sharing-links">
            <li>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($product_url); ?>" target="_blank" rel="noopener noreferrer" class="facebook-share">
                    <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr($product_title); ?>&url=<?php echo esc_url($product_url); ?>" target="_blank" rel="noopener noreferrer" class="twitter-share">
                    <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
            </li>
            <li>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url($product_url); ?>&media=<?php echo esc_url($product_image); ?>&description=<?php echo esc_attr($product_title); ?>" target="_blank" rel="noopener noreferrer" class="pinterest-share">
                    <span class="screen-reader-text"><?php esc_html_e('Pin on Pinterest', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                </a>
            </li>
            <li>
                <a href="mailto:?subject=<?php echo esc_attr($product_title); ?>&body=<?php echo esc_attr(sprintf(__('Check out this product: %s', 'aqualuxe'), $product_url)); ?>" class="email-share">
                    <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                </a>
            </li>
        </ul>
    </div>
    <?php
}

/**
 * Track product views for recently viewed products.
 */
function aqualuxe_track_product_view() {
    if (!is_singular('product')) {
        return;
    }
    
    global $post;
    
    if (empty($_COOKIE['aqualuxe_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = json_decode(stripslashes($_COOKIE['aqualuxe_recently_viewed']), true);
    }
    
    // Remove current product from the array
    $viewed_products = array_diff($viewed_products, array($post->ID));
    
    // Add current product to the beginning of the array
    array_unshift($viewed_products, $post->ID);
    
    // Keep only the last 15 products
    $viewed_products = array_slice($viewed_products, 0, 15);
    
    // Set cookie
    setcookie('aqualuxe_recently_viewed', json_encode($viewed_products), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
}

/**
 * Display recently viewed products.
 */
function aqualuxe_recently_viewed_products() {
    if (empty($_COOKIE['aqualuxe_recently_viewed'])) {
        return;
    }
    
    $viewed_products = json_decode(stripslashes($_COOKIE['aqualuxe_recently_viewed']), true);
    
    // Remove current product
    global $post;
    $viewed_products = array_diff($viewed_products, array($post->ID));
    
    if (empty($viewed_products)) {
        return;
    }
    
    // Limit to 4 products
    $viewed_products = array_slice($viewed_products, 0, 4);
    
    $args = array(
        'post_type' => 'product',
        'post__in' => $viewed_products,
        'orderby' => 'post__in',
        'posts_per_page' => 4,
    );
    
    $products = new WP_Query($args);
    
    if (!$products->have_posts()) {
        return;
    }
    
    ?>
    <section class="recently-viewed-products">
        <h2><?php esc_html_e('Recently Viewed Products', 'aqualuxe'); ?></h2>
        <div class="products columns-4">
            <?php
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            wp_reset_postdata();
            ?>
        </div>
    </section>
    <?php
}

/**
 * Add product video support.
 */
function aqualuxe_product_video() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get video URL from product meta
    $video_url = get_post_meta($product->get_id(), '_product_video_url', true);
    
    if (empty($video_url)) {
        return;
    }
    
    // Check if it's a YouTube or Vimeo URL
    $video_id = '';
    $video_type = '';
    
    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
        $video_type = 'youtube';
        
        if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
            $video_id = substr($video_url, strpos($video_url, 'v=') + 2);
            $video_id = strtok($video_id, '&');
        } elseif (strpos($video_url, 'youtu.be/') !== false) {
            $video_id = substr($video_url, strpos($video_url, 'youtu.be/') + 9);
        }
    } elseif (strpos($video_url, 'vimeo.com') !== false) {
        $video_type = 'vimeo';
        
        if (preg_match('/vimeo.com\/(\d+)/', $video_url, $matches)) {
            $video_id = $matches[1];
        }
    }
    
    if (empty($video_id)) {
        return;
    }
    
    // Get video thumbnail
    $video_thumbnail = '';
    
    if ($video_type === 'youtube') {
        $video_thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
    } elseif ($video_type === 'vimeo') {
        $vimeo_data = json_decode(file_get_contents('https://vimeo.com/api/v2/video/' . $video_id . '.json'));
        if (!empty($vimeo_data[0]->thumbnail_large)) {
            $video_thumbnail = $vimeo_data[0]->thumbnail_large;
        }
    }
    
    ?>
    <div class="product-video-thumbnail">
        <a href="<?php echo esc_url($video_url); ?>" class="product-video-link" data-fancybox>
            <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php esc_attr_e('Product Video', 'aqualuxe'); ?>" class="product-video-image">
            <span class="product-video-play">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48"><path d="M8 5v14l11-7z"/></svg>
            </span>
        </a>
    </div>
    <?php
}

/**
 * Add 360 degree product view.
 */
function aqualuxe_product_360_view() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get 360 view images from product meta
    $images = get_post_meta($product->get_id(), '_product_360_view', true);
    
    if (empty($images)) {
        return;
    }
    
    $images = explode(',', $images);
    
    if (count($images) < 8) {
        return;
    }
    
    // Get first image as thumbnail
    $thumbnail_id = $images[0];
    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'medium');
    
    if (empty($thumbnail_url)) {
        return;
    }
    
    ?>
    <div class="product-360-view-thumbnail">
        <a href="#product-360-view-modal" class="product-360-view-link">
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php esc_attr_e('360° View', 'aqualuxe'); ?>" class="product-360-view-image">
            <span class="product-360-view-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48"><path d="M12 7C6.48 7 2 9.24 2 12c0 2.24 2.94 4.13 7 4.77V20l4-4-4-4v2.73c-3.15-.56-5-1.9-5-2.73 0-1.06 3.04-3 8-3s8 1.94 8 3c0 .73-1.46 1.89-4 2.53v2.05c3.53-.77 6-2.53 6-4.58 0-2.76-4.48-5-10-5z"/></svg>
            </span>
        </a>
    </div>
    
    <div id="product-360-view-modal" class="product-360-view-modal" style="display: none;">
        <div class="product-360-view-modal-content">
            <span class="close-modal">&times;</span>
            <div class="product-360-view-container">
                <div class="product-360-view-title"><?php esc_html_e('360° View', 'aqualuxe'); ?></div>
                <div class="product-360-view"></div>
                <div class="product-360-view-controls">
                    <button class="product-360-view-prev">&lt;</button>
                    <button class="product-360-view-next">&gt;</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // 360 view functionality
            $('.product-360-view-link').on('click', function(e) {
                e.preventDefault();
                $('#product-360-view-modal').fadeIn();
                
                // Initialize 360 view if not already initialized
                if (!$('.product-360-view').hasClass('initialized')) {
                    var images = <?php echo json_encode(array_map(function($id) { return wp_get_attachment_image_url($id, 'large'); }, $images)); ?>;
                    var currentImage = 0;
                    var totalImages = images.length;
                    
                    // Preload images
                    var preloadedImages = [];
                    for (var i = 0; i < totalImages; i++) {
                        preloadedImages[i] = new Image();
                        preloadedImages[i].src = images[i];
                    }
                    
                    // Show first image
                    $('.product-360-view').html('<img src="' + images[0] + '" alt="360° View">');
                    
                    // Add event listeners
                    $('.product-360-view-prev').on('click', function() {
                        currentImage = (currentImage - 1 + totalImages) % totalImages;
                        $('.product-360-view img').attr('src', images[currentImage]);
                    });
                    
                    $('.product-360-view-next').on('click', function() {
                        currentImage = (currentImage + 1) % totalImages;
                        $('.product-360-view img').attr('src', images[currentImage]);
                    });
                    
                    // Add drag functionality
                    var isDragging = false;
                    var startX = 0;
                    
                    $('.product-360-view').on('mousedown touchstart', function(e) {
                        isDragging = true;
                        startX = e.pageX || e.originalEvent.touches[0].pageX;
                        return false;
                    });
                    
                    $(document).on('mousemove touchmove', function(e) {
                        if (!isDragging) return;
                        
                        var currentX = e.pageX || e.originalEvent.touches[0].pageX;
                        var diff = currentX - startX;
                        
                        if (Math.abs(diff) > 30) {
                            if (diff > 0) {
                                currentImage = (currentImage - 1 + totalImages) % totalImages;
                            } else {
                                currentImage = (currentImage + 1) % totalImages;
                            }
                            
                            $('.product-360-view img').attr('src', images[currentImage]);
                            startX = currentX;
                        }
                        
                        return false;
                    });
                    
                    $(document).on('mouseup touchend', function() {
                        isDragging = false;
                    });
                    
                    // Mark as initialized
                    $('.product-360-view').addClass('initialized');
                }
            });
            
            // Close modal on X click
            $('.close-modal').on('click', function() {
                $('#product-360-view-modal').fadeOut();
            });
            
            // Close modal on outside click
            $('.product-360-view-modal').on('click', function(e) {
                if ($(e.target).hasClass('product-360-view-modal')) {
                    $(this).fadeOut();
                }
            });
            
            // Close modal on ESC key
            $(document).keyup(function(e) {
                if (e.key === "Escape") {
                    $('#product-360-view-modal').fadeOut();
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Add product specifications table.
 */
function aqualuxe_product_specifications() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get specifications from product meta
    $specifications = get_post_meta($product->get_id(), '_product_specifications', true);
    
    if (empty($specifications)) {
        return;
    }
    
    $specifications = maybe_unserialize($specifications);
    
    if (!is_array($specifications) || empty($specifications)) {
        return;
    }
    
    ?>
    <div class="product-specifications">
        <h3><?php esc_html_e('Product Specifications', 'aqualuxe'); ?></h3>
        <table class="specifications-table">
            <tbody>
                <?php foreach ($specifications as $spec) : ?>
                    <?php if (!empty($spec['name']) && !empty($spec['value'])) : ?>
                        <tr>
                            <th><?php echo esc_html($spec['name']); ?></th>
                            <td><?php echo wp_kses_post($spec['value']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Add product care instructions.
 */
function aqualuxe_product_care_instructions() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get care instructions from product meta
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (empty($care_instructions)) {
        return;
    }
    
    ?>
    <div class="product-care-instructions">
        <h3><?php esc_html_e('Care Instructions', 'aqualuxe'); ?></h3>
        <div class="care-instructions-content">
            <?php echo wp_kses_post($care_instructions); ?>
        </div>
    </div>
    <?php
}

/**
 * Modify product review form arguments.
 *
 * @param array $args Review form arguments.
 * @return array Modified review form arguments.
 */
function aqualuxe_review_form_args($args) {
    $args['title_reply'] = __('Write a Review', 'aqualuxe');
    $args['title_reply_to'] = __('Reply to %s', 'aqualuxe');
    $args['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__('Your Rating', 'aqualuxe') . '<span class="required">*</span></label><select name="rating" id="rating" required>
        <option value="">' . esc_html__('Rate&hellip;', 'aqualuxe') . '</option>
        <option value="5">' . esc_html__('Perfect', 'aqualuxe') . '</option>
        <option value="4">' . esc_html__('Good', 'aqualuxe') . '</option>
        <option value="3">' . esc_html__('Average', 'aqualuxe') . '</option>
        <option value="2">' . esc_html__('Not that bad', 'aqualuxe') . '</option>
        <option value="1">' . esc_html__('Very poor', 'aqualuxe') . '</option>
    </select></div>
    <div class="comment-form-comment">
        <label for="comment">' . esc_html__('Your Review', 'aqualuxe') . '<span class="required">*</span></label>
        <textarea id="comment" name="comment" cols="45" rows="8" required></textarea>
    </div>';
    
    return $args;
}

/**
 * Add product comparison feature.
 */
function aqualuxe_compare_button() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Check if product is in comparison list
    $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? json_decode(stripslashes($_COOKIE['aqualuxe_compare_list']), true) : array();
    $in_compare = in_array($product->get_id(), $compare_list);
    
    echo '<div class="compare-wrapper">';
    echo '<button type="button" class="button compare-button' . ($in_compare ? ' in-compare' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="compare-icon">' . ($in_compare ? '✓' : '⟷') . '</span>';
    echo '<span class="compare-text">' . ($in_compare ? esc_html__('In Compare', 'aqualuxe') : esc_html__('Compare', 'aqualuxe')) . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Add bulk discount information.
 */
function aqualuxe_bulk_discount_info() {
    global $product;
    
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock()) {
        return;
    }
    
    // Get bulk discounts from product meta
    $bulk_discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
    
    if (empty($bulk_discounts)) {
        return;
    }
    
    $bulk_discounts = maybe_unserialize($bulk_discounts);
    
    if (!is_array($bulk_discounts) || empty($bulk_discounts)) {
        return;
    }
    
    // Sort discounts by quantity
    usort($bulk_discounts, function($a, $b) {
        return $a['quantity'] - $b['quantity'];
    });
    
    ?>
    <div class="bulk-discounts">
        <h4><?php esc_html_e('Bulk Discounts', 'aqualuxe'); ?></h4>
        <table class="bulk-discounts-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Discount', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bulk_discounts as $discount) : ?>
                    <tr>
                        <td><?php echo esc_html($discount['quantity']); ?>+</td>
                        <td><?php echo esc_html($discount['discount']); ?>%</td>
                        <td><?php echo wc_price($product->get_price() * (100 - $discount['discount']) / 100); ?> <?php esc_html_e('each', 'aqualuxe'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Add stock notification form.
 */
function aqualuxe_stock_notification_form() {
    global $product;
    
    if (!$product || $product->is_in_stock()) {
        return;
    }
    
    ?>
    <div class="stock-notification">
        <h4><?php esc_html_e('Get notified when this product is back in stock', 'aqualuxe'); ?></h4>
        <form class="stock-notification-form" method="post" action="#">
            <div class="form-row">
                <div class="form-column">
                    <label for="stock-notification-email"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" id="stock-notification-email" name="stock-notification-email" required>
                </div>
                <div class="form-column">
                    <input type="hidden" name="product-id" value="<?php echo esc_attr($product->get_id()); ?>">
                    <button type="submit" class="button stock-notification-button"><?php esc_html_e('Notify Me', 'aqualuxe'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Add product FAQ section.
 */
function aqualuxe_product_faq() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get FAQ from product meta
    $faq = get_post_meta($product->get_id(), '_faq', true);
    
    if (empty($faq)) {
        return;
    }
    
    ?>
    <div class="product-faq">
        <h3><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h3>
        <div class="faq-content">
            <?php echo wp_kses_post($faq); ?>
        </div>
    </div>
    <?php
}

/**
 * Add product warranty information.
 */
function aqualuxe_product_warranty_info() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get warranty info from product meta
    $warranty_info = get_post_meta($product->get_id(), '_warranty_info', true);
    
    if (empty($warranty_info)) {
        // Use default warranty info
        $warranty_info = get_option('aqualuxe_default_warranty_info', '');
    }
    
    if (empty($warranty_info)) {
        return;
    }
    
    ?>
    <div class="product-warranty">
        <h4><?php esc_html_e('Warranty Information', 'aqualuxe'); ?></h4>
        <div class="warranty-content">
            <?php echo wp_kses_post($warranty_info); ?>
        </div>
    </div>
    <?php
}

/**
 * Add shipping information.
 */
function aqualuxe_shipping_info() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get shipping info from product meta
    $shipping_info = get_post_meta($product->get_id(), '_shipping_info', true);
    
    if (empty($shipping_info)) {
        // Use default shipping info
        $shipping_info = get_option('aqualuxe_default_shipping_info', '');
    }
    
    if (empty($shipping_info)) {
        return;
    }
    
    ?>
    <div class="product-shipping">
        <h4><?php esc_html_e('Shipping Information', 'aqualuxe'); ?></h4>
        <div class="shipping-content">
            <?php echo wp_kses_post($shipping_info); ?>
        </div>
    </div>
    <?php
}

/**
 * Add return policy information.
 */
function aqualuxe_return_policy_info() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get return policy from product meta
    $return_policy = get_post_meta($product->get_id(), '_return_policy', true);
    
    if (empty($return_policy)) {
        // Use default return policy
        $return_policy = get_option('aqualuxe_default_return_policy', '');
    }
    
    if (empty($return_policy)) {
        return;
    }
    
    ?>
    <div class="product-return-policy">
        <h4><?php esc_html_e('Return Policy', 'aqualuxe'); ?></h4>
        <div class="return-policy-content">
            <?php echo wp_kses_post($return_policy); ?>
        </div>
    </div>
    <?php
}

/**
 * Add product bundles support.
 */
function aqualuxe_product_bundles() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get bundled products from product meta
    $bundled_products = get_post_meta($product->get_id(), '_bundled_products', true);
    
    if (empty($bundled_products)) {
        return;
    }
    
    $bundled_products = maybe_unserialize($bundled_products);
    
    if (!is_array($bundled_products) || empty($bundled_products)) {
        return;
    }
    
    ?>
    <div class="product-bundles">
        <h3><?php esc_html_e('Complete Your Setup', 'aqualuxe'); ?></h3>
        <div class="bundled-products">
            <?php
            foreach ($bundled_products as $bundled_product_id) {
                $bundled_product = wc_get_product($bundled_product_id);
                
                if (!$bundled_product) {
                    continue;
                }
                
                ?>
                <div class="bundled-product">
                    <div class="bundled-product-image">
                        <?php echo $bundled_product->get_image('thumbnail'); ?>
                    </div>
                    <div class="bundled-product-details">
                        <h4 class="bundled-product-title"><?php echo esc_html($bundled_product->get_name()); ?></h4>
                        <div class="bundled-product-price"><?php echo $bundled_product->get_price_html(); ?></div>
                        <div class="bundled-product-description"><?php echo wp_kses_post($bundled_product->get_short_description()); ?></div>
                        <a href="<?php echo esc_url($bundled_product->get_permalink()); ?>" class="button bundled-product-link"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Add product customization options.
 */
function aqualuxe_product_customization_options() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get customization options from product meta
    $customization_options = get_post_meta($product->get_id(), '_customization_options', true);
    
    if (empty($customization_options)) {
        return;
    }
    
    $customization_options = maybe_unserialize($customization_options);
    
    if (!is_array($customization_options) || empty($customization_options)) {
        return;
    }
    
    ?>
    <div class="product-customization">
        <h4><?php esc_html_e('Customization Options', 'aqualuxe'); ?></h4>
        <div class="customization-options">
            <?php
            foreach ($customization_options as $option) {
                if (empty($option['name']) || empty($option['type']) || empty($option['values'])) {
                    continue;
                }
                
                $option_id = sanitize_title($option['name']);
                
                ?>
                <div class="customization-option">
                    <label for="<?php echo esc_attr($option_id); ?>"><?php echo esc_html($option['name']); ?></label>
                    
                    <?php if ($option['type'] === 'select') : ?>
                        <select id="<?php echo esc_attr($option_id); ?>" name="customization[<?php echo esc_attr($option_id); ?>]">
                            <?php foreach ($option['values'] as $value) : ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($option['type'] === 'radio') : ?>
                        <div class="radio-options">
                            <?php foreach ($option['values'] as $index => $value) : ?>
                                <label>
                                    <input type="radio" name="customization[<?php echo esc_attr($option_id); ?>]" value="<?php echo esc_attr($value); ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                    <?php echo esc_html($value); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($option['type'] === 'checkbox') : ?>
                        <div class="checkbox-options">
                            <?php foreach ($option['values'] as $value) : ?>
                                <label>
                                    <input type="checkbox" name="customization[<?php echo esc_attr($option_id); ?>][]" value="<?php echo esc_attr($value); ?>">
                                    <?php echo esc_html($value); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($option['type'] === 'text') : ?>
                        <input type="text" id="<?php echo esc_attr($option_id); ?>" name="customization[<?php echo esc_attr($option_id); ?>]" placeholder="<?php echo esc_attr($option['values'][0]); ?>">
                    <?php elseif ($option['type'] === 'textarea') : ?>
                        <textarea id="<?php echo esc_attr($option_id); ?>" name="customization[<?php echo esc_attr($option_id); ?>]" placeholder="<?php echo esc_attr($option['values'][0]); ?>"></textarea>
                    <?php endif; ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Add gift wrapping option.
 */
function aqualuxe_gift_wrapping_option() {
    global $product;
    
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock()) {
        return;
    }
    
    // Check if gift wrapping is enabled for this product
    $gift_wrapping_enabled = get_post_meta($product->get_id(), '_gift_wrapping_enabled', true);
    
    if (empty($gift_wrapping_enabled)) {
        // Check if gift wrapping is enabled globally
        $gift_wrapping_enabled = get_option('aqualuxe_gift_wrapping_enabled', 'yes');
    }
    
    if ($gift_wrapping_enabled !== 'yes') {
        return;
    }
    
    // Get gift wrapping price
    $gift_wrapping_price = get_post_meta($product->get_id(), '_gift_wrapping_price', true);
    
    if (empty($gift_wrapping_price)) {
        // Use default gift wrapping price
        $gift_wrapping_price = get_option('aqualuxe_gift_wrapping_price', '5');
    }
    
    ?>
    <div class="gift-wrapping-option">
        <label for="gift-wrapping">
            <input type="checkbox" id="gift-wrapping" name="gift_wrapping" value="yes">
            <?php echo sprintf(__('Add gift wrapping (+%s)', 'aqualuxe'), wc_price($gift_wrapping_price)); ?>
        </label>
        <div class="gift-message-wrapper" style="display: none;">
            <label for="gift-message"><?php esc_html_e('Gift Message (optional)', 'aqualuxe'); ?></label>
            <textarea id="gift-message" name="gift_message" rows="3" placeholder="<?php esc_attr_e('Enter your gift message here', 'aqualuxe'); ?>"></textarea>
        </div>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Gift wrapping functionality
            $('#gift-wrapping').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.gift-message-wrapper').slideDown();
                } else {
                    $('.gift-message-wrapper').slideUp();
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Add cart progress bar.
 */
function aqualuxe_cart_progress_bar() {
    // Get free shipping threshold
    $free_shipping_threshold = get_option('aqualuxe_free_shipping_threshold', '100');
    
    if (empty($free_shipping_threshold)) {
        return;
    }
    
    // Get cart subtotal
    $cart_subtotal = WC()->cart->get_subtotal();
    
    // Calculate progress percentage
    $progress = min(100, ($cart_subtotal / $free_shipping_threshold) * 100);
    
    // Calculate remaining amount for free shipping
    $remaining = max(0, $free_shipping_threshold - $cart_subtotal);
    
    ?>
    <div class="cart-progress-bar">
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: <?php echo esc_attr($progress); ?>%"></div>
        </div>
        <?php if ($remaining > 0) : ?>
            <div class="progress-message">
                <?php echo sprintf(__('Add %s more to get FREE shipping!', 'aqualuxe'), wc_price($remaining)); ?>
            </div>
        <?php else : ?>
            <div class="progress-message progress-complete">
                <?php esc_html_e('Congratulations! You\'ve qualified for FREE shipping!', 'aqualuxe'); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add cart coupon description.
 */
function aqualuxe_cart_coupon_description() {
    ?>
    <div class="coupon-description">
        <?php esc_html_e('If you have a coupon code, please enter it below.', 'aqualuxe'); ?>
    </div>
    <?php
}

/**
 * Add enhanced cross-sells to cart.
 */
function aqualuxe_cart_cross_sells_enhanced() {
    // Check if cross-sells are enabled
    if (get_option('aqualuxe_enable_cross_sells', 'yes') !== 'yes') {
        return;
    }
    
    // Get cross-sells
    $cross_sells = WC()->cart->get_cross_sells();
    
    if (empty($cross_sells)) {
        return;
    }
    
    // Get cross-sell products
    $args = array(
        'post_type' => 'product',
        'post__in' => $cross_sells,
        'posts_per_page' => 4,
        'orderby' => 'rand',
    );
    
    $products = new WP_Query($args);
    
    if (!$products->have_posts()) {
        return;
    }
    
    ?>
    <div class="enhanced-cross-sells">
        <h2><?php esc_html_e('You May Also Like', 'aqualuxe'); ?></h2>
        <div class="cross-sells-products">
            <?php
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                ?>
                <div class="cross-sell-product">
                    <div class="cross-sell-product-image">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                            <?php echo $product->get_image('thumbnail'); ?>
                        </a>
                    </div>
                    <div class="cross-sell-product-details">
                        <h3 class="cross-sell-product-title">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="cross-sell-product-price"><?php echo $product->get_price_html(); ?></div>
                        <?php if ($product->is_in_stock()) : ?>
                            <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                                <?php if ($product->is_type('simple')) : ?>
                                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="button alt"><?php esc_html_e('Add to Cart', 'aqualuxe'); ?></button>
                                <?php else : ?>
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                                <?php endif; ?>
                            </form>
                        <?php else : ?>
                            <span class="out-of-stock"><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}

/**
 * Add checkout steps.
 */
function aqualuxe_checkout_steps() {
    ?>
    <div class="checkout-steps">
        <div class="checkout-step active">
            <span class="step-number">1</span>
            <span class="step-label"><?php esc_html_e('Customer Information', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-step">
            <span class="step-number">2</span>
            <span class="step-label"><?php esc_html_e('Shipping', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-step">
            <span class="step-number">3</span>
            <span class="step-label"><?php esc_html_e('Payment', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Add checkout trust badges.
 */
function aqualuxe_checkout_trust_badges() {
    ?>
    <div class="checkout-trust-badges">
        <h4><?php esc_html_e('Secure Checkout', 'aqualuxe'); ?></h4>
        <div class="trust-badges">
            <div class="trust-badge">
                <span class="trust-badge-icon">🔒</span>
                <span class="trust-badge-text"><?php esc_html_e('Secure Payment', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge">
                <span class="trust-badge-icon">✓</span>
                <span class="trust-badge-text"><?php esc_html_e('Verified by Visa', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge">
                <span class="trust-badge-icon">🛡️</span>
                <span class="trust-badge-text"><?php esc_html_e('Protected Purchase', 'aqualuxe'); ?></span>
            </div>
        </div>
        <div class="payment-icons">
            <img src="<?php echo esc_url(AQUALUXE_URI . 'assets/images/payment-icons.png'); ?>" alt="<?php esc_attr_e('Payment Methods', 'aqualuxe'); ?>">
        </div>
    </div>
    <?php
}

/**
 * Add order received enhancements.
 *
 * @param int $order_id Order ID.
 */
function aqualuxe_order_received_enhancements($order_id) {
    $order = wc_get_order($order_id);
    
    if (!$order) {
        return;
    }
    
    ?>
    <div class="order-received-enhancements">
        <div class="order-confirmation">
            <div class="confirmation-icon">✓</div>
            <h2><?php esc_html_e('Thank You for Your Order!', 'aqualuxe'); ?></h2>
            <p><?php esc_html_e('Your order has been received and is now being processed.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="order-details-summary">
            <div class="order-detail">
                <span class="detail-label"><?php esc_html_e('Order Number:', 'aqualuxe'); ?></span>
                <span class="detail-value"><?php echo esc_html($order->get_order_number()); ?></span>
            </div>
            <div class="order-detail">
                <span class="detail-label"><?php esc_html_e('Date:', 'aqualuxe'); ?></span>
                <span class="detail-value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
            </div>
            <div class="order-detail">
                <span class="detail-label"><?php esc_html_e('Total:', 'aqualuxe'); ?></span>
                <span class="detail-value"><?php echo $order->get_formatted_order_total(); ?></span>
            </div>
            <div class="order-detail">
                <span class="detail-label"><?php esc_html_e('Payment Method:', 'aqualuxe'); ?></span>
                <span class="detail-value"><?php echo esc_html($order->get_payment_method_title()); ?></span>
            </div>
        </div>
        
        <div class="order-next-steps">
            <h3><?php esc_html_e('What Happens Next?', 'aqualuxe'); ?></h3>
            <ol class="next-steps-list">
                <li><?php esc_html_e('We\'ll send you an order confirmation email with your order details.', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Our team will prepare your order for shipping.', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('You\'ll receive a shipping confirmation email with tracking information when your order ships.', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Your package will be delivered to your shipping address.', 'aqualuxe'); ?></li>
            </ol>
        </div>
        
        <div class="order-support">
            <h3><?php esc_html_e('Need Help?', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('If you have any questions about your order, please contact our customer support team.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="button"><?php esc_html_e('View Your Account', 'aqualuxe'); ?></a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="button"><?php esc_html_e('Contact Support', 'aqualuxe'); ?></a>
        </div>
    </div>
    <?php
}

/**
 * Add account dashboard enhancements.
 */
function aqualuxe_account_dashboard_enhancements() {
    $customer_orders = wc_get_orders(array(
        'customer' => get_current_user_id(),
        'limit' => 5,
    ));
    
    ?>
    <div class="account-dashboard-enhancements">
        <div class="dashboard-welcome">
            <h3><?php esc_html_e('Welcome to Your Account Dashboard', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Here you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.', 'aqualuxe'); ?></p>
        </div>
        
        <?php if (!empty($customer_orders)) : ?>
            <div class="dashboard-recent-orders">
                <h4><?php esc_html_e('Recent Orders', 'aqualuxe'); ?></h4>
                <table class="recent-orders-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Order', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customer_orders as $customer_order) : ?>
                            <tr>
                                <td><?php echo esc_html($customer_order->get_order_number()); ?></td>
                                <td><?php echo esc_html(wc_format_datetime($customer_order->get_date_created())); ?></td>
                                <td><?php echo esc_html(wc_get_order_status_name($customer_order->get_status())); ?></td>
                                <td><?php echo $customer_order->get_formatted_order_total(); ?></td>
                                <td>
                                    <a href="<?php echo esc_url($customer_order->get_view_order_url()); ?>" class="button"><?php esc_html_e('View', 'aqualuxe'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="button"><?php esc_html_e('View All Orders', 'aqualuxe'); ?></a>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-quick-links">
            <h4><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h4>
            <div class="quick-links">
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="quick-link">
                    <span class="quick-link-icon">📍</span>
                    <span class="quick-link-text"><?php esc_html_e('Manage Addresses', 'aqualuxe'); ?></span>
                </a>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="quick-link">
                    <span class="quick-link-icon">👤</span>
                    <span class="quick-link-text"><?php esc_html_e('Account Details', 'aqualuxe'); ?></span>
                </a>
                <?php if (get_theme_mod('aqualuxe_enable_wishlist', true)) : ?>
                    <a href="<?php echo esc_url(wc_get_endpoint_url('wishlist')); ?>" class="quick-link">
                        <span class="quick-link-icon">❤️</span>
                        <span class="quick-link-text"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
                    </a>
                <?php endif; ?>
                <a href="<?php echo esc_url(wc_get_endpoint_url('customer-logout', '', wc_get_page_permalink('myaccount'))); ?>" class="quick-link">
                    <span class="quick-link-icon">🚪</span>
                    <span class="quick-link-text"><?php esc_html_e('Logout', 'aqualuxe'); ?></span>
                </a>
            </div>
        </div>
    </div>
    <?php
}