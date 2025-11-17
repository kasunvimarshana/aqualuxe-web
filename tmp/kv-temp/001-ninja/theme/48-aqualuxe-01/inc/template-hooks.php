<?php
/**
 * Template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Header hooks
 */
add_action('aqualuxe_header', 'aqualuxe_page_loader', 5);
add_action('aqualuxe_header', 'aqualuxe_header_top', 10);
add_action('aqualuxe_header', 'aqualuxe_header_main', 20);
add_action('aqualuxe_header', 'aqualuxe_header_bottom', 30);

// Header top
add_action('aqualuxe_header_top', 'aqualuxe_header_top_bar', 10);

// Header main
add_action('aqualuxe_header_main', 'aqualuxe_site_branding', 10);
add_action('aqualuxe_header_main', 'aqualuxe_primary_navigation', 20);
add_action('aqualuxe_header_main', 'aqualuxe_header_actions', 30);

// Header bottom
add_action('aqualuxe_header_bottom', 'aqualuxe_header_bottom_bar', 10);

// Header components
add_action('aqualuxe_header_top_bar', 'aqualuxe_contact_info', 10);
add_action('aqualuxe_header_top_bar', 'aqualuxe_language_switcher', 20);
add_action('aqualuxe_header_top_bar', 'aqualuxe_currency_switcher', 30);
add_action('aqualuxe_header_top_bar', 'aqualuxe_social_links', 40);

add_action('aqualuxe_site_branding', 'aqualuxe_site_logo', 10);

add_action('aqualuxe_header_actions', 'aqualuxe_search_form', 10);
add_action('aqualuxe_header_actions', 'aqualuxe_dark_mode_toggle', 20);
add_action('aqualuxe_header_actions', 'aqualuxe_mini_cart', 30);
add_action('aqualuxe_header_actions', 'aqualuxe_mobile_menu_toggle', 40);

add_action('aqualuxe_header_bottom_bar', 'aqualuxe_breadcrumbs', 10);

/**
 * Footer hooks
 */
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);
add_action('aqualuxe_footer', 'aqualuxe_footer_bottom', 20);
add_action('aqualuxe_footer', 'aqualuxe_back_to_top', 30);

// Footer components
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widget_areas', 10);
add_action('aqualuxe_footer_widgets', 'aqualuxe_newsletter_form', 20);

add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_navigation', 10);
add_action('aqualuxe_footer_bottom', 'aqualuxe_copyright', 20);
add_action('aqualuxe_footer_bottom', 'aqualuxe_social_links', 30);

/**
 * Content hooks
 */
add_action('aqualuxe_content_top', 'aqualuxe_page_title', 10);

// Single post
add_action('aqualuxe_single_post_top', 'aqualuxe_post_thumbnail', 10);
add_action('aqualuxe_single_post_top', 'aqualuxe_post_meta', 20);

add_action('aqualuxe_single_post_bottom', 'aqualuxe_post_tags', 10);
add_action('aqualuxe_single_post_bottom', 'aqualuxe_social_sharing', 20);
add_action('aqualuxe_single_post_bottom', 'aqualuxe_author_box', 30);
add_action('aqualuxe_single_post_bottom', 'aqualuxe_post_navigation', 40);
add_action('aqualuxe_single_post_bottom', 'aqualuxe_related_posts', 50);

// Page
add_action('aqualuxe_page_top', 'aqualuxe_page_thumbnail', 10);

// Archive
add_action('aqualuxe_archive_top', 'aqualuxe_archive_description', 10);

add_action('aqualuxe_archive_bottom', 'aqualuxe_pagination', 10);

// Search
add_action('aqualuxe_search_top', 'aqualuxe_search_form_large', 10);

add_action('aqualuxe_search_bottom', 'aqualuxe_pagination', 10);

// 404
add_action('aqualuxe_404_content', 'aqualuxe_404_content', 10);

/**
 * WooCommerce hooks
 */
if (aqualuxe_is_woocommerce_active()) {
    // Remove default WooCommerce wrappers
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    
    // Add custom wrappers
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10);
    
    // Shop page
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_filters', 15);
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_view_switcher', 25);
    
    // Product loop
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    
    add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_link_open', 10);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_link_close', 5);
    
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_badges', 5);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_actions', 15);
    
    // Single product
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_badges', 5);
    
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_vendor', 6);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_share', 50);
    
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_additional_information', 15);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_related_products', 20);
    
    // Cart
    add_action('woocommerce_before_cart', 'aqualuxe_woocommerce_cart_progress', 10);
    
    // Checkout
    add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_progress', 10);
    
    // Account
    add_action('woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_welcome', 10);
}

/**
 * Header functions
 */
if (!function_exists('aqualuxe_header_top_bar')) {
    /**
     * Display header top bar
     */
    function aqualuxe_header_top_bar() {
        // Check if top bar is enabled
        if (!aqualuxe_get_option('enable_top_bar', true)) {
            return;
        }
        
        echo '<div class="header-top-bar">';
        echo '<div class="container">';
        echo '<div class="header-top-bar-inner">';
        
        do_action('aqualuxe_header_top_bar');
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_header_main')) {
    /**
     * Display header main
     */
    function aqualuxe_header_main() {
        echo '<div class="header-main">';
        echo '<div class="container">';
        echo '<div class="header-main-inner">';
        
        do_action('aqualuxe_header_main');
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_header_bottom_bar')) {
    /**
     * Display header bottom bar
     */
    function aqualuxe_header_bottom_bar() {
        // Check if bottom bar is enabled
        if (!aqualuxe_get_option('enable_bottom_bar', true)) {
            return;
        }
        
        echo '<div class="header-bottom-bar">';
        echo '<div class="container">';
        echo '<div class="header-bottom-bar-inner">';
        
        do_action('aqualuxe_header_bottom_bar');
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_site_branding')) {
    /**
     * Display site branding
     */
    function aqualuxe_site_branding() {
        echo '<div class="site-branding">';
        
        do_action('aqualuxe_site_branding');
        
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_header_actions')) {
    /**
     * Display header actions
     */
    function aqualuxe_header_actions() {
        echo '<div class="header-actions">';
        
        do_action('aqualuxe_header_actions');
        
        echo '</div>';
    }
}

/**
 * Footer functions
 */
if (!function_exists('aqualuxe_footer_widget_areas')) {
    /**
     * Display footer widget areas
     */
    function aqualuxe_footer_widget_areas() {
        // Check if footer widgets are active
        if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
            return;
        }
        
        echo '<div class="footer-widgets">';
        echo '<div class="container">';
        echo '<div class="footer-widgets-inner">';
        
        // Footer widget columns
        $columns = 0;
        for ($i = 1; $i <= 4; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                $columns++;
            }
        }
        
        $column_class = 'footer-widget-column';
        if ($columns > 0) {
            $column_class .= ' footer-widget-column-' . $columns;
        }
        
        // Display widgets
        for ($i = 1; $i <= 4; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                echo '<div class="' . esc_attr($column_class) . '">';
                dynamic_sidebar('footer-' . $i);
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_footer_bottom')) {
    /**
     * Display footer bottom
     */
    function aqualuxe_footer_bottom() {
        echo '<div class="footer-bottom">';
        echo '<div class="container">';
        echo '<div class="footer-bottom-inner">';
        
        do_action('aqualuxe_footer_bottom');
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Content functions
 */
if (!function_exists('aqualuxe_post_thumbnail')) {
    /**
     * Display post thumbnail
     */
    function aqualuxe_post_thumbnail() {
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            the_post_thumbnail('aqualuxe-featured');
            echo '</div>';
        }
    }
}

if (!function_exists('aqualuxe_page_thumbnail')) {
    /**
     * Display page thumbnail
     */
    function aqualuxe_page_thumbnail() {
        if (has_post_thumbnail()) {
            echo '<div class="page-thumbnail">';
            the_post_thumbnail('aqualuxe-featured');
            echo '</div>';
        }
    }
}

if (!function_exists('aqualuxe_archive_description')) {
    /**
     * Display archive description
     */
    function aqualuxe_archive_description() {
        $description = '';
        
        if (is_category()) {
            $description = category_description();
        } elseif (is_tag()) {
            $description = tag_description();
        } elseif (is_tax()) {
            $description = term_description();
        }
        
        if ($description) {
            echo '<div class="archive-description">' . wp_kses_post($description) . '</div>';
        }
    }
}

if (!function_exists('aqualuxe_search_form_large')) {
    /**
     * Display large search form
     */
    function aqualuxe_search_form_large() {
        echo '<div class="search-form-large">';
        get_search_form();
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_404_content')) {
    /**
     * Display 404 content
     */
    function aqualuxe_404_content() {
        echo '<div class="error-404-content">';
        echo '<h2>' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h2>';
        echo '<p>' . esc_html__('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe') . '</p>';
        get_search_form();
        echo '</div>';
    }
}

/**
 * WooCommerce functions
 */
if (aqualuxe_is_woocommerce_active()) {
    if (!function_exists('aqualuxe_woocommerce_wrapper_start')) {
        /**
         * Display WooCommerce wrapper start
         */
        function aqualuxe_woocommerce_wrapper_start() {
            echo '<div class="woocommerce-content">';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_wrapper_end')) {
        /**
         * Display WooCommerce wrapper end
         */
        function aqualuxe_woocommerce_wrapper_end() {
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_shop_filters')) {
        /**
         * Display shop filters
         */
        function aqualuxe_woocommerce_shop_filters() {
            // Check if filters are enabled
            if (!aqualuxe_get_option('enable_shop_filters', true)) {
                return;
            }
            
            echo '<div class="shop-filters">';
            
            // Filter button
            echo '<button class="shop-filter-toggle">';
            echo '<i class="fas fa-filter"></i>';
            echo '<span>' . esc_html__('Filter', 'aqualuxe') . '</span>';
            echo '</button>';
            
            // Filter content
            echo '<div class="shop-filter-content">';
            
            // Active filters
            if (function_exists('woocommerce_active_filters')) {
                echo '<div class="shop-active-filters">';
                woocommerce_active_filters();
                echo '</div>';
            }
            
            // Filter widgets
            if (is_active_sidebar('shop-filters')) {
                echo '<div class="shop-filter-widgets">';
                dynamic_sidebar('shop-filters');
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_shop_view_switcher')) {
        /**
         * Display shop view switcher
         */
        function aqualuxe_woocommerce_shop_view_switcher() {
            // Check if view switcher is enabled
            if (!aqualuxe_get_option('enable_view_switcher', true)) {
                return;
            }
            
            $current_view = isset($_COOKIE['aqualuxe_shop_view']) ? $_COOKIE['aqualuxe_shop_view'] : 'grid';
            
            echo '<div class="shop-view-switcher">';
            echo '<button class="shop-view-button shop-view-grid ' . ($current_view === 'grid' ? 'active' : '') . '" data-view="grid">';
            echo '<i class="fas fa-th"></i>';
            echo '</button>';
            echo '<button class="shop-view-button shop-view-list ' . ($current_view === 'list' ? 'active' : '') . '" data-view="list">';
            echo '<i class="fas fa-list"></i>';
            echo '</button>';
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_template_loop_product_link_open')) {
        /**
         * Display product link open
         */
        function aqualuxe_woocommerce_template_loop_product_link_open() {
            echo '<div class="product-inner">';
            echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link">';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_template_loop_product_link_close')) {
        /**
         * Display product link close
         */
        function aqualuxe_woocommerce_template_loop_product_link_close() {
            echo '</a>';
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_template_loop_product_badges')) {
        /**
         * Display product badges
         */
        function aqualuxe_woocommerce_template_loop_product_badges() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            echo '<div class="product-badges">';
            
            // Sale badge
            if ($product->is_on_sale()) {
                echo '<span class="product-badge product-badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
            }
            
            // New badge
            $new_days = aqualuxe_get_option('new_product_days', 30);
            $product_date = strtotime($product->get_date_created());
            $now = time();
            $days_diff = floor(($now - $product_date) / (60 * 60 * 24));
            
            if ($days_diff < $new_days) {
                echo '<span class="product-badge product-badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
            }
            
            // Featured badge
            if ($product->is_featured()) {
                echo '<span class="product-badge product-badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
            }
            
            // Out of stock badge
            if (!$product->is_in_stock()) {
                echo '<span class="product-badge product-badge-outofstock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
            }
            
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_template_loop_product_actions')) {
        /**
         * Display product actions
         */
        function aqualuxe_woocommerce_template_loop_product_actions() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            echo '<div class="product-actions">';
            
            // Quick view button
            if (aqualuxe_get_option('enable_quick_view', true)) {
                echo '<button class="product-action-button product-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">';
                echo '<i class="fas fa-eye"></i>';
                echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
                echo '</button>';
            }
            
            // Wishlist button
            if (aqualuxe_get_option('enable_wishlist', true)) {
                echo '<button class="product-action-button product-wishlist" data-product-id="' . esc_attr($product->get_id()) . '">';
                echo '<i class="fas fa-heart"></i>';
                echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
                echo '</button>';
            }
            
            // Compare button
            if (aqualuxe_get_option('enable_compare', true)) {
                echo '<button class="product-action-button product-compare" data-product-id="' . esc_attr($product->get_id()) . '">';
                echo '<i class="fas fa-exchange-alt"></i>';
                echo '<span class="screen-reader-text">' . esc_html__('Compare', 'aqualuxe') . '</span>';
                echo '</button>';
            }
            
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_product_badges')) {
        /**
         * Display product badges on single product
         */
        function aqualuxe_woocommerce_product_badges() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            echo '<div class="product-badges product-badges-single">';
            
            // Sale badge
            if ($product->is_on_sale()) {
                echo '<span class="product-badge product-badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
            }
            
            // New badge
            $new_days = aqualuxe_get_option('new_product_days', 30);
            $product_date = strtotime($product->get_date_created());
            $now = time();
            $days_diff = floor(($now - $product_date) / (60 * 60 * 24));
            
            if ($days_diff < $new_days) {
                echo '<span class="product-badge product-badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
            }
            
            // Featured badge
            if ($product->is_featured()) {
                echo '<span class="product-badge product-badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
            }
            
            // Out of stock badge
            if (!$product->is_in_stock()) {
                echo '<span class="product-badge product-badge-outofstock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
            }
            
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_product_vendor')) {
        /**
         * Display product vendor
         */
        function aqualuxe_woocommerce_product_vendor() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            // Check if multivendor is enabled
            if (!aqualuxe_get_option('enable_multivendor', true)) {
                return;
            }
            
            $vendor_id = 0;
            
            // WC Marketplace
            if (function_exists('wcmp_get_vendor') && function_exists('get_wcmp_product_vendors')) {
                $vendor = get_wcmp_product_vendors($product->get_id());
                if ($vendor) {
                    $vendor_id = $vendor->id;
                }
            }
            
            // Dokan
            if (function_exists('dokan_get_vendor_by_product') && $vendor = dokan_get_vendor_by_product($product->get_id())) {
                $vendor_id = $vendor->id;
            }
            
            // WC Vendors
            if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
                $vendor_id = WCV_Vendors::get_vendor_from_product($product->get_id());
            }
            
            if ($vendor_id) {
                $vendor_info = aqualuxe_get_vendor_info($vendor_id);
                
                echo '<div class="product-vendor">';
                echo '<span class="product-vendor-label">' . esc_html__('Sold by:', 'aqualuxe') . '</span>';
                echo '<a href="' . esc_url(get_author_posts_url($vendor_id)) . '" class="product-vendor-link">';
                
                if ($vendor_info['logo']) {
                    echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '" class="product-vendor-logo">';
                }
                
                echo '<span class="product-vendor-name">' . esc_html($vendor_info['name']) . '</span>';
                echo '</a>';
                echo '</div>';
            }
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_product_share')) {
        /**
         * Display product share
         */
        function aqualuxe_woocommerce_product_share() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            // Check if social sharing is enabled
            if (!aqualuxe_get_option('enable_product_sharing', true)) {
                return;
            }
            
            $product_url = urlencode(get_permalink($product->get_id()));
            $product_title = urlencode(get_the_title($product->get_id()));
            
            // Get enabled social networks
            $networks = array();
            
            if (aqualuxe_get_option('share_facebook', true)) {
                $networks['facebook'] = array(
                    'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url,
                    'icon' => 'fab fa-facebook-f',
                    'label' => __('Share on Facebook', 'aqualuxe'),
                );
            }
            
            if (aqualuxe_get_option('share_twitter', true)) {
                $networks['twitter'] = array(
                    'url' => 'https://twitter.com/intent/tweet?url=' . $product_url . '&text=' . $product_title,
                    'icon' => 'fab fa-twitter',
                    'label' => __('Share on Twitter', 'aqualuxe'),
                );
            }
            
            if (aqualuxe_get_option('share_linkedin', true)) {
                $networks['linkedin'] = array(
                    'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $product_url . '&title=' . $product_title,
                    'icon' => 'fab fa-linkedin-in',
                    'label' => __('Share on LinkedIn', 'aqualuxe'),
                );
            }
            
            if (aqualuxe_get_option('share_pinterest', true) && has_post_thumbnail($product->get_id())) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
                $networks['pinterest'] = array(
                    'url' => 'https://pinterest.com/pin/create/button/?url=' . $product_url . '&media=' . urlencode($image[0]) . '&description=' . $product_title,
                    'icon' => 'fab fa-pinterest-p',
                    'label' => __('Pin on Pinterest', 'aqualuxe'),
                );
            }
            
            if (aqualuxe_get_option('share_email', true)) {
                $networks['email'] = array(
                    'url' => 'mailto:?subject=' . $product_title . '&body=' . $product_url,
                    'icon' => 'fas fa-envelope',
                    'label' => __('Share via Email', 'aqualuxe'),
                );
            }
            
            if (!empty($networks)) {
                echo '<div class="product-share">';
                echo '<span class="product-share-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
                echo '<ul class="product-share-list">';
                
                foreach ($networks as $network => $data) {
                    echo '<li class="product-share-item product-share-' . esc_attr($network) . '">';
                    echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($data['label']) . '">';
                    echo '<i class="' . esc_attr($data['icon']) . '"></i>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_product_additional_information')) {
        /**
         * Display product additional information
         */
        function aqualuxe_woocommerce_product_additional_information() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            // Check if additional information is enabled
            if (!aqualuxe_get_option('enable_product_additional_info', true)) {
                return;
            }
            
            echo '<div class="product-additional-information">';
            
            // Shipping information
            if (aqualuxe_get_option('enable_shipping_info', true)) {
                $shipping_info = aqualuxe_get_option('shipping_info', '');
                
                if ($shipping_info) {
                    echo '<div class="product-shipping-info">';
                    echo '<h4>' . esc_html__('Shipping Information', 'aqualuxe') . '</h4>';
                    echo '<div class="product-shipping-info-content">' . wp_kses_post($shipping_info) . '</div>';
                    echo '</div>';
                }
            }
            
            // Returns information
            if (aqualuxe_get_option('enable_returns_info', true)) {
                $returns_info = aqualuxe_get_option('returns_info', '');
                
                if ($returns_info) {
                    echo '<div class="product-returns-info">';
                    echo '<h4>' . esc_html__('Returns & Exchanges', 'aqualuxe') . '</h4>';
                    echo '<div class="product-returns-info-content">' . wp_kses_post($returns_info) . '</div>';
                    echo '</div>';
                }
            }
            
            // Size guide
            if (aqualuxe_get_option('enable_size_guide', true)) {
                $size_guide = aqualuxe_get_option('size_guide', '');
                
                if ($size_guide) {
                    echo '<div class="product-size-guide">';
                    echo '<h4>' . esc_html__('Size Guide', 'aqualuxe') . '</h4>';
                    echo '<div class="product-size-guide-content">' . wp_kses_post($size_guide) . '</div>';
                    echo '</div>';
                }
            }
            
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_related_products')) {
        /**
         * Display related products
         */
        function aqualuxe_woocommerce_related_products() {
            global $product;
            
            if (!$product) {
                return;
            }
            
            // Check if related products are enabled
            if (!aqualuxe_get_option('enable_related_products', true)) {
                return;
            }
            
            $args = array(
                'posts_per_page' => 4,
                'columns' => 4,
                'orderby' => 'rand',
            );
            
            woocommerce_related_products($args);
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_cart_progress')) {
        /**
         * Display cart progress
         */
        function aqualuxe_woocommerce_cart_progress() {
            // Check if cart progress is enabled
            if (!aqualuxe_get_option('enable_cart_progress', true)) {
                return;
            }
            
            echo '<div class="cart-progress">';
            echo '<ul class="cart-progress-steps">';
            
            echo '<li class="cart-progress-step cart-progress-step-cart active">';
            echo '<span class="cart-progress-step-number">1</span>';
            echo '<span class="cart-progress-step-label">' . esc_html__('Shopping Cart', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '<li class="cart-progress-step cart-progress-step-checkout">';
            echo '<span class="cart-progress-step-number">2</span>';
            echo '<span class="cart-progress-step-label">' . esc_html__('Checkout', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '<li class="cart-progress-step cart-progress-step-complete">';
            echo '<span class="cart-progress-step-number">3</span>';
            echo '<span class="cart-progress-step-label">' . esc_html__('Order Complete', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '</ul>';
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_checkout_progress')) {
        /**
         * Display checkout progress
         */
        function aqualuxe_woocommerce_checkout_progress() {
            // Check if checkout progress is enabled
            if (!aqualuxe_get_option('enable_checkout_progress', true)) {
                return;
            }
            
            echo '<div class="checkout-progress">';
            echo '<ul class="checkout-progress-steps">';
            
            echo '<li class="checkout-progress-step checkout-progress-step-cart completed">';
            echo '<span class="checkout-progress-step-number">1</span>';
            echo '<span class="checkout-progress-step-label">' . esc_html__('Shopping Cart', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '<li class="checkout-progress-step checkout-progress-step-checkout active">';
            echo '<span class="checkout-progress-step-number">2</span>';
            echo '<span class="checkout-progress-step-label">' . esc_html__('Checkout', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '<li class="checkout-progress-step checkout-progress-step-complete">';
            echo '<span class="checkout-progress-step-number">3</span>';
            echo '<span class="checkout-progress-step-label">' . esc_html__('Order Complete', 'aqualuxe') . '</span>';
            echo '</li>';
            
            echo '</ul>';
            echo '</div>';
        }
    }
    
    if (!function_exists('aqualuxe_woocommerce_account_welcome')) {
        /**
         * Display account welcome message
         */
        function aqualuxe_woocommerce_account_welcome() {
            $current_user = wp_get_current_user();
            
            echo '<div class="account-welcome">';
            echo '<h2>' . sprintf(esc_html__('Welcome, %s', 'aqualuxe'), $current_user->display_name) . '</h2>';
            echo '<p>' . esc_html__('From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
    }
}