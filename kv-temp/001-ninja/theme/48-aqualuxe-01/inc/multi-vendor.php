<?php
/**
 * Multi-vendor support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Initialize multi-vendor support
 */
function aqualuxe_multi_vendor_init() {
    // Check if multi-vendor support is enabled
    if (!aqualuxe_get_option('enable_multivendor', true)) {
        return;
    }
    
    // Add vendor information to product pages
    add_action('woocommerce_single_product_summary', 'aqualuxe_vendor_info_product_page', 7);
    
    // Add vendor tab to product tabs
    add_filter('woocommerce_product_tabs', 'aqualuxe_vendor_product_tab');
    
    // Add vendor information to order details
    add_action('woocommerce_order_details_after_order_table', 'aqualuxe_vendor_info_order_details');
    
    // Add vendor store link to product meta
    add_action('woocommerce_product_meta_end', 'aqualuxe_vendor_store_link');
    
    // Add vendor filter to shop page
    add_action('woocommerce_before_shop_loop', 'aqualuxe_vendor_filter', 30);
    
    // Add vendor information to admin order items
    add_action('woocommerce_admin_order_item_headers', 'aqualuxe_admin_order_item_vendor_header');
    add_action('woocommerce_admin_order_item_values', 'aqualuxe_admin_order_item_vendor_value', 10, 3);
    
    // Register vendor dashboard shortcode
    add_shortcode('aqualuxe_vendor_dashboard', 'aqualuxe_vendor_dashboard_shortcode');
    
    // Add vendor registration fields
    add_action('woocommerce_register_form', 'aqualuxe_vendor_registration_fields');
    add_action('woocommerce_created_customer', 'aqualuxe_save_vendor_registration_fields');
    
    // Add vendor commission settings
    add_action('woocommerce_product_options_general_product_data', 'aqualuxe_vendor_commission_fields');
    add_action('woocommerce_process_product_meta', 'aqualuxe_save_vendor_commission_fields');
    
    // Add vendor settings to user profile
    add_action('show_user_profile', 'aqualuxe_vendor_profile_fields');
    add_action('edit_user_profile', 'aqualuxe_vendor_profile_fields');
    add_action('personal_options_update', 'aqualuxe_save_vendor_profile_fields');
    add_action('edit_user_profile_update', 'aqualuxe_save_vendor_profile_fields');
    
    // Add vendor capabilities
    add_action('init', 'aqualuxe_vendor_capabilities');
}
add_action('after_setup_theme', 'aqualuxe_multi_vendor_init');

/**
 * Check if user is a vendor
 *
 * @param int $user_id User ID
 * @return bool
 */
function aqualuxe_is_vendor($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    // Check for WC Marketplace
    if (function_exists('is_user_wcmp_vendor') && is_user_wcmp_vendor($user_id)) {
        return true;
    }
    
    // Check for Dokan
    if (function_exists('dokan_is_user_seller') && dokan_is_user_seller($user_id)) {
        return true;
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors') && WCV_Vendors::is_vendor($user_id)) {
        return true;
    }
    
    // Check for custom vendor role
    $user = get_userdata($user_id);
    if ($user && in_array('aqualuxe_vendor', (array) $user->roles)) {
        return true;
    }
    
    return false;
}

/**
 * Get vendor ID from product
 *
 * @param int $product_id Product ID
 * @return int|bool
 */
function aqualuxe_get_vendor_id_from_product($product_id) {
    if (!$product_id) {
        return false;
    }
    
    // Check for WC Marketplace
    if (function_exists('get_wcmp_product_vendors') && $vendor = get_wcmp_product_vendors($product_id)) {
        return $vendor->id;
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_vendor_by_product') && $vendor = dokan_get_vendor_by_product($product_id)) {
        return $vendor->id;
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
        return WCV_Vendors::get_vendor_from_product($product_id);
    }
    
    // Check for custom vendor
    $vendor_id = get_post_field('post_author', $product_id);
    if ($vendor_id && aqualuxe_is_vendor($vendor_id)) {
        return $vendor_id;
    }
    
    return false;
}

/**
 * Display vendor information on product page
 */
function aqualuxe_vendor_info_product_page() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $vendor_id = aqualuxe_get_vendor_id_from_product($product->get_id());
    
    if (!$vendor_id) {
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    
    if (empty($vendor_info['name'])) {
        return;
    }
    
    echo '<div class="vendor-info vendor-info-product-page">';
    
    // Vendor logo
    if (!empty($vendor_info['logo'])) {
        echo '<div class="vendor-logo">';
        echo '<a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">';
        echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
        echo '</a>';
        echo '</div>';
    }
    
    // Vendor name and rating
    echo '<div class="vendor-details">';
    echo '<h4 class="vendor-name">' . esc_html__('Sold by:', 'aqualuxe') . ' <a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">' . esc_html($vendor_info['name']) . '</a></h4>';
    
    // Vendor rating
    $vendor_rating = aqualuxe_get_vendor_rating($vendor_id);
    if ($vendor_rating) {
        echo '<div class="vendor-rating">';
        echo '<div class="star-rating" role="img" aria-label="' . sprintf(esc_attr__('Rated %s out of 5', 'aqualuxe'), $vendor_rating) . '">';
        echo '<span style="width:' . esc_attr(($vendor_rating / 5) * 100) . '%">';
        echo sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $vendor_rating);
        echo '</span>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Add vendor tab to product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_vendor_product_tab($tabs) {
    global $product;
    
    if (!$product) {
        return $tabs;
    }
    
    $vendor_id = aqualuxe_get_vendor_id_from_product($product->get_id());
    
    if (!$vendor_id) {
        return $tabs;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    
    if (empty($vendor_info['name'])) {
        return $tabs;
    }
    
    $tabs['vendor'] = [
        'title' => esc_html__('Vendor Information', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_vendor_tab_content',
    ];
    
    return $tabs;
}

/**
 * Display vendor tab content
 */
function aqualuxe_vendor_tab_content() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $vendor_id = aqualuxe_get_vendor_id_from_product($product->get_id());
    
    if (!$vendor_id) {
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    
    if (empty($vendor_info['name'])) {
        return;
    }
    
    echo '<div class="vendor-tab-content">';
    
    // Vendor header
    echo '<div class="vendor-header">';
    
    // Vendor logo
    if (!empty($vendor_info['logo'])) {
        echo '<div class="vendor-logo">';
        echo '<a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">';
        echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
        echo '</a>';
        echo '</div>';
    }
    
    // Vendor name and rating
    echo '<div class="vendor-details">';
    echo '<h3 class="vendor-name"><a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">' . esc_html($vendor_info['name']) . '</a></h3>';
    
    // Vendor rating
    $vendor_rating = aqualuxe_get_vendor_rating($vendor_id);
    if ($vendor_rating) {
        echo '<div class="vendor-rating">';
        echo '<div class="star-rating" role="img" aria-label="' . sprintf(esc_attr__('Rated %s out of 5', 'aqualuxe'), $vendor_rating) . '">';
        echo '<span style="width:' . esc_attr(($vendor_rating / 5) * 100) . '%">';
        echo sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $vendor_rating);
        echo '</span>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Vendor description
    if (!empty($vendor_info['description'])) {
        echo '<div class="vendor-description">';
        echo wpautop(wp_kses_post($vendor_info['description']));
        echo '</div>';
    }
    
    // Vendor contact information
    echo '<div class="vendor-contact-info">';
    echo '<h4>' . esc_html__('Contact Information', 'aqualuxe') . '</h4>';
    
    echo '<ul class="vendor-contact-list">';
    
    if (!empty($vendor_info['address'])) {
        echo '<li class="vendor-address"><i class="fas fa-map-marker-alt"></i> ' . esc_html($vendor_info['address']) . '</li>';
    }
    
    if (!empty($vendor_info['phone'])) {
        echo '<li class="vendor-phone"><i class="fas fa-phone"></i> <a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $vendor_info['phone'])) . '">' . esc_html($vendor_info['phone']) . '</a></li>';
    }
    
    if (!empty($vendor_info['email'])) {
        echo '<li class="vendor-email"><i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr($vendor_info['email']) . '">' . esc_html($vendor_info['email']) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
    
    // Vendor social links
    if (!empty($vendor_info['social'])) {
        echo '<div class="vendor-social-links">';
        echo '<h4>' . esc_html__('Follow Us', 'aqualuxe') . '</h4>';
        
        echo '<ul class="vendor-social-list">';
        
        foreach ($vendor_info['social'] as $network => $url) {
            if (empty($url)) {
                continue;
            }
            
            $icon_class = 'fab fa-' . str_replace('_', '-', $network);
            if ($network === 'website') {
                $icon_class = 'fas fa-globe';
            } elseif ($network === 'email') {
                $icon_class = 'fas fa-envelope';
            }
            
            echo '<li class="vendor-social-item vendor-social-' . esc_attr($network) . '">';
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
            echo '<i class="' . esc_attr($icon_class) . '"></i>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
    
    // Vendor products
    $vendor_products = aqualuxe_get_vendor_products($vendor_id, 4);
    
    if ($vendor_products->have_posts()) {
        echo '<div class="vendor-products">';
        echo '<h4>' . esc_html__('More Products from this Vendor', 'aqualuxe') . '</h4>';
        
        echo '<ul class="products vendor-product-list columns-4">';
        
        while ($vendor_products->have_posts()) {
            $vendor_products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</ul>';
        
        echo '<div class="vendor-store-link">';
        echo '<a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '" class="button">' . esc_html__('Visit Store', 'aqualuxe') . '</a>';
        echo '</div>';
        
        echo '</div>';
        
        wp_reset_postdata();
    }
    
    echo '</div>';
}

/**
 * Display vendor information on order details
 *
 * @param WC_Order $order Order object
 */
function aqualuxe_vendor_info_order_details($order) {
    if (!$order) {
        return;
    }
    
    $vendors = [];
    
    // Get vendors from order items
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $vendor_id = aqualuxe_get_vendor_id_from_product($product_id);
        
        if ($vendor_id && !isset($vendors[$vendor_id])) {
            $vendor_info = aqualuxe_get_vendor_info($vendor_id);
            
            if (!empty($vendor_info['name'])) {
                $vendors[$vendor_id] = $vendor_info;
            }
        }
    }
    
    if (empty($vendors)) {
        return;
    }
    
    echo '<section class="woocommerce-vendor-details">';
    echo '<h2 class="woocommerce-vendor-details-title">' . esc_html__('Vendor Information', 'aqualuxe') . '</h2>';
    
    foreach ($vendors as $vendor_id => $vendor_info) {
        echo '<div class="vendor-info vendor-info-order-details">';
        
        // Vendor logo
        if (!empty($vendor_info['logo'])) {
            echo '<div class="vendor-logo">';
            echo '<a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">';
            echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
            echo '</a>';
            echo '</div>';
        }
        
        // Vendor details
        echo '<div class="vendor-details">';
        echo '<h4 class="vendor-name"><a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">' . esc_html($vendor_info['name']) . '</a></h4>';
        
        echo '<ul class="vendor-contact-list">';
        
        if (!empty($vendor_info['email'])) {
            echo '<li class="vendor-email"><i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr($vendor_info['email']) . '">' . esc_html($vendor_info['email']) . '</a></li>';
        }
        
        if (!empty($vendor_info['phone'])) {
            echo '<li class="vendor-phone"><i class="fas fa-phone"></i> <a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $vendor_info['phone'])) . '">' . esc_html($vendor_info['phone']) . '</a></li>';
        }
        
        echo '</ul>';
        echo '</div>';
        
        echo '</div>';
    }
    
    echo '</section>';
}

/**
 * Display vendor store link in product meta
 */
function aqualuxe_vendor_store_link() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $vendor_id = aqualuxe_get_vendor_id_from_product($product->get_id());
    
    if (!$vendor_id) {
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    
    if (empty($vendor_info['name'])) {
        return;
    }
    
    echo '<span class="vendor-store-link">' . esc_html__('Vendor:', 'aqualuxe') . ' <a href="' . esc_url(aqualuxe_get_vendor_store_url($vendor_id)) . '">' . esc_html($vendor_info['name']) . '</a></span>';
}

/**
 * Display vendor filter on shop page
 */
function aqualuxe_vendor_filter() {
    // Check if vendor filter is enabled
    if (!aqualuxe_get_option('enable_vendor_filter', true)) {
        return;
    }
    
    // Get vendors
    $vendors = aqualuxe_get_vendors();
    
    if (empty($vendors)) {
        return;
    }
    
    $current_vendor = isset($_GET['vendor']) ? sanitize_text_field($_GET['vendor']) : '';
    
    echo '<div class="vendor-filter">';
    echo '<span class="vendor-filter-label">' . esc_html__('Filter by Vendor:', 'aqualuxe') . '</span>';
    
    echo '<select name="vendor-filter" class="vendor-filter-select">';
    echo '<option value="">' . esc_html__('All Vendors', 'aqualuxe') . '</option>';
    
    foreach ($vendors as $vendor_id => $vendor_name) {
        echo '<option value="' . esc_attr($vendor_id) . '" ' . selected($current_vendor, $vendor_id, false) . '>' . esc_html($vendor_name) . '</option>';
    }
    
    echo '</select>';
    echo '</div>';
    
    // Add JavaScript to handle filter change
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.vendor-filter-select').on('change', function() {
            var vendor = $(this).val();
            var url = new URL(window.location.href);
            
            if (vendor) {
                url.searchParams.set('vendor', vendor);
            } else {
                url.searchParams.delete('vendor');
            }
            
            window.location.href = url.toString();
        });
    });
    </script>
    <?php
}

/**
 * Add vendor header to admin order items
 */
function aqualuxe_admin_order_item_vendor_header() {
    echo '<th class="item-vendor">' . esc_html__('Vendor', 'aqualuxe') . '</th>';
}

/**
 * Add vendor value to admin order items
 *
 * @param WC_Order_Item_Product $item Order item
 * @param WC_Product $product Product object
 * @param int $item_id Order item ID
 */
function aqualuxe_admin_order_item_vendor_value($item, $product, $item_id) {
    if (!$product) {
        echo '<td class="item-vendor">&mdash;</td>';
        return;
    }
    
    $vendor_id = aqualuxe_get_vendor_id_from_product($product->get_id());
    
    if (!$vendor_id) {
        echo '<td class="item-vendor">&mdash;</td>';
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    
    if (empty($vendor_info['name'])) {
        echo '<td class="item-vendor">&mdash;</td>';
        return;
    }
    
    echo '<td class="item-vendor">';
    echo '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $vendor_id)) . '">' . esc_html($vendor_info['name']) . '</a>';
    echo '</td>';
}

/**
 * Vendor dashboard shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_vendor_dashboard_shortcode($atts) {
    $atts = shortcode_atts([
        'sections' => 'products,orders,reports,settings',
    ], $atts);
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return '<div class="vendor-dashboard-login">' .
            '<p>' . esc_html__('Please log in to access your vendor dashboard.', 'aqualuxe') . '</p>' .
            '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '" class="button">' . esc_html__('Log In', 'aqualuxe') . '</a>' .
            '</div>';
    }
    
    // Check if user is a vendor
    $user_id = get_current_user_id();
    
    if (!aqualuxe_is_vendor($user_id)) {
        return '<div class="vendor-dashboard-error">' .
            '<p>' . esc_html__('You do not have vendor privileges. Please contact the administrator.', 'aqualuxe') . '</p>' .
            '</div>';
    }
    
    // Get vendor information
    $vendor_info = aqualuxe_get_vendor_info($user_id);
    
    // Parse sections
    $sections = explode(',', $atts['sections']);
    $active_section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'products';
    
    ob_start();
    
    echo '<div class="vendor-dashboard">';
    
    // Vendor header
    echo '<div class="vendor-dashboard-header">';
    
    // Vendor logo
    if (!empty($vendor_info['logo'])) {
        echo '<div class="vendor-logo">';
        echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
        echo '</div>';
    }
    
    // Vendor details
    echo '<div class="vendor-details">';
    echo '<h2 class="vendor-name">' . esc_html($vendor_info['name']) . '</h2>';
    
    // Vendor rating
    $vendor_rating = aqualuxe_get_vendor_rating($user_id);
    if ($vendor_rating) {
        echo '<div class="vendor-rating">';
        echo '<div class="star-rating" role="img" aria-label="' . sprintf(esc_attr__('Rated %s out of 5', 'aqualuxe'), $vendor_rating) . '">';
        echo '<span style="width:' . esc_attr(($vendor_rating / 5) * 100) . '%">';
        echo sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $vendor_rating);
        echo '</span>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Vendor navigation
    echo '<div class="vendor-dashboard-navigation">';
    echo '<ul class="vendor-dashboard-tabs">';
    
    if (in_array('products', $sections)) {
        echo '<li class="vendor-dashboard-tab ' . ($active_section === 'products' ? 'active' : '') . '">';
        echo '<a href="' . esc_url(add_query_arg('section', 'products')) . '">' . esc_html__('Products', 'aqualuxe') . '</a>';
        echo '</li>';
    }
    
    if (in_array('orders', $sections)) {
        echo '<li class="vendor-dashboard-tab ' . ($active_section === 'orders' ? 'active' : '') . '">';
        echo '<a href="' . esc_url(add_query_arg('section', 'orders')) . '">' . esc_html__('Orders', 'aqualuxe') . '</a>';
        echo '</li>';
    }
    
    if (in_array('reports', $sections)) {
        echo '<li class="vendor-dashboard-tab ' . ($active_section === 'reports' ? 'active' : '') . '">';
        echo '<a href="' . esc_url(add_query_arg('section', 'reports')) . '">' . esc_html__('Reports', 'aqualuxe') . '</a>';
        echo '</li>';
    }
    
    if (in_array('settings', $sections)) {
        echo '<li class="vendor-dashboard-tab ' . ($active_section === 'settings' ? 'active' : '') . '">';
        echo '<a href="' . esc_url(add_query_arg('section', 'settings')) . '">' . esc_html__('Settings', 'aqualuxe') . '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
    
    // Vendor content
    echo '<div class="vendor-dashboard-content">';
    
    switch ($active_section) {
        case 'products':
            aqualuxe_vendor_dashboard_products($user_id);
            break;
        
        case 'orders':
            aqualuxe_vendor_dashboard_orders($user_id);
            break;
        
        case 'reports':
            aqualuxe_vendor_dashboard_reports($user_id);
            break;
        
        case 'settings':
            aqualuxe_vendor_dashboard_settings($user_id);
            break;
        
        default:
            aqualuxe_vendor_dashboard_products($user_id);
            break;
    }
    
    echo '</div>';
    
    echo '</div>';
    
    return ob_get_clean();
}

/**
 * Display vendor dashboard products section
 *
 * @param int $vendor_id Vendor ID
 */
function aqualuxe_vendor_dashboard_products($vendor_id) {
    // Get vendor products
    $products = aqualuxe_get_vendor_products($vendor_id, -1);
    
    echo '<div class="vendor-dashboard-section vendor-dashboard-products">';
    echo '<h3>' . esc_html__('Products', 'aqualuxe') . '</h3>';
    
    // Add new product button
    echo '<div class="vendor-dashboard-actions">';
    echo '<a href="' . esc_url(admin_url('post-new.php?post_type=product')) . '" class="button">' . esc_html__('Add New Product', 'aqualuxe') . '</a>';
    echo '</div>';
    
    if ($products->have_posts()) {
        echo '<table class="vendor-products-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__('Image', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Name', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('SKU', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Stock', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Price', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Categories', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Date', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Actions', 'aqualuxe') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            echo '<tr>';
            
            // Image
            echo '<td class="product-image">';
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                echo '</a>';
            } else {
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo wc_placeholder_img('thumbnail');
                echo '</a>';
            }
            echo '</td>';
            
            // Name
            echo '<td class="product-name">';
            echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
            echo '</td>';
            
            // SKU
            echo '<td class="product-sku">';
            echo $product->get_sku() ? esc_html($product->get_sku()) : '&mdash;';
            echo '</td>';
            
            // Stock
            echo '<td class="product-stock">';
            if ($product->is_in_stock()) {
                echo '<span class="in-stock">' . esc_html__('In stock', 'aqualuxe') . '</span>';
            } else {
                echo '<span class="out-of-stock">' . esc_html__('Out of stock', 'aqualuxe') . '</span>';
            }
            echo '</td>';
            
            // Price
            echo '<td class="product-price">';
            echo $product->get_price_html();
            echo '</td>';
            
            // Categories
            echo '<td class="product-categories">';
            echo wc_get_product_category_list($product->get_id(), ', ');
            echo '</td>';
            
            // Date
            echo '<td class="product-date">';
            echo get_the_date();
            echo '</td>';
            
            // Actions
            echo '<td class="product-actions">';
            echo '<a href="' . esc_url(admin_url('post.php?post=' . $product->get_id() . '&action=edit')) . '" class="button">' . esc_html__('Edit', 'aqualuxe') . '</a>';
            echo '<a href="' . esc_url(get_delete_post_link($product->get_id())) . '" class="button delete">' . esc_html__('Delete', 'aqualuxe') . '</a>';
            echo '</td>';
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No products found.', 'aqualuxe') . '</p>';
    }
    
    echo '</div>';
}

/**
 * Display vendor dashboard orders section
 *
 * @param int $vendor_id Vendor ID
 */
function aqualuxe_vendor_dashboard_orders($vendor_id) {
    // Get vendor orders
    $orders = aqualuxe_get_vendor_orders($vendor_id);
    
    echo '<div class="vendor-dashboard-section vendor-dashboard-orders">';
    echo '<h3>' . esc_html__('Orders', 'aqualuxe') . '</h3>';
    
    if (!empty($orders)) {
        echo '<table class="vendor-orders-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__('Order', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Date', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Status', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Total', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Actions', 'aqualuxe') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($orders as $order) {
            echo '<tr>';
            
            // Order number
            echo '<td class="order-number">';
            echo '<a href="' . esc_url(admin_url('post.php?post=' . $order->get_id() . '&action=edit')) . '">#' . esc_html($order->get_order_number()) . '</a>';
            echo '</td>';
            
            // Date
            echo '<td class="order-date">';
            echo esc_html(wc_format_datetime($order->get_date_created()));
            echo '</td>';
            
            // Status
            echo '<td class="order-status">';
            echo '<span class="order-status-' . esc_attr($order->get_status()) . '">' . esc_html(wc_get_order_status_name($order->get_status())) . '</span>';
            echo '</td>';
            
            // Total
            echo '<td class="order-total">';
            echo $order->get_formatted_order_total();
            echo '</td>';
            
            // Actions
            echo '<td class="order-actions">';
            echo '<a href="' . esc_url(admin_url('post.php?post=' . $order->get_id() . '&action=edit')) . '" class="button">' . esc_html__('View', 'aqualuxe') . '</a>';
            echo '</td>';
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>' . esc_html__('No orders found.', 'aqualuxe') . '</p>';
    }
    
    echo '</div>';
}

/**
 * Display vendor dashboard reports section
 *
 * @param int $vendor_id Vendor ID
 */
function aqualuxe_vendor_dashboard_reports($vendor_id) {
    // Get vendor reports data
    $reports = aqualuxe_get_vendor_reports($vendor_id);
    
    echo '<div class="vendor-dashboard-section vendor-dashboard-reports">';
    echo '<h3>' . esc_html__('Reports', 'aqualuxe') . '</h3>';
    
    // Summary cards
    echo '<div class="vendor-reports-summary">';
    
    // Total sales
    echo '<div class="vendor-report-card">';
    echo '<div class="vendor-report-card-header">';
    echo '<h4>' . esc_html__('Total Sales', 'aqualuxe') . '</h4>';
    echo '</div>';
    echo '<div class="vendor-report-card-body">';
    echo '<span class="vendor-report-value">' . wc_price($reports['total_sales']) . '</span>';
    echo '</div>';
    echo '</div>';
    
    // Total orders
    echo '<div class="vendor-report-card">';
    echo '<div class="vendor-report-card-header">';
    echo '<h4>' . esc_html__('Total Orders', 'aqualuxe') . '</h4>';
    echo '</div>';
    echo '<div class="vendor-report-card-body">';
    echo '<span class="vendor-report-value">' . esc_html($reports['total_orders']) . '</span>';
    echo '</div>';
    echo '</div>';
    
    // Total products
    echo '<div class="vendor-report-card">';
    echo '<div class="vendor-report-card-header">';
    echo '<h4>' . esc_html__('Total Products', 'aqualuxe') . '</h4>';
    echo '</div>';
    echo '<div class="vendor-report-card-body">';
    echo '<span class="vendor-report-value">' . esc_html($reports['total_products']) . '</span>';
    echo '</div>';
    echo '</div>';
    
    // Commission
    echo '<div class="vendor-report-card">';
    echo '<div class="vendor-report-card-header">';
    echo '<h4>' . esc_html__('Commission Earned', 'aqualuxe') . '</h4>';
    echo '</div>';
    echo '<div class="vendor-report-card-body">';
    echo '<span class="vendor-report-value">' . wc_price($reports['commission']) . '</span>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    
    // Top products
    if (!empty($reports['top_products'])) {
        echo '<div class="vendor-reports-top-products">';
        echo '<h4>' . esc_html__('Top Selling Products', 'aqualuxe') . '</h4>';
        
        echo '<table class="vendor-top-products-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__('Product', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Sales', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Revenue', 'aqualuxe') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($reports['top_products'] as $product) {
            echo '<tr>';
            
            // Product
            echo '<td class="product-name">';
            echo '<a href="' . esc_url(get_permalink($product['id'])) . '">' . esc_html($product['name']) . '</a>';
            echo '</td>';
            
            // Sales
            echo '<td class="product-sales">';
            echo esc_html($product['quantity']);
            echo '</td>';
            
            // Revenue
            echo '<td class="product-revenue">';
            echo wc_price($product['revenue']);
            echo '</td>';
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        
        echo '</div>';
    }
    
    // Monthly sales chart
    if (!empty($reports['monthly_sales'])) {
        echo '<div class="vendor-reports-monthly-sales">';
        echo '<h4>' . esc_html__('Monthly Sales', 'aqualuxe') . '</h4>';
        
        // Chart data
        $chart_labels = [];
        $chart_data = [];
        
        foreach ($reports['monthly_sales'] as $month => $sales) {
            $chart_labels[] = $month;
            $chart_data[] = $sales;
        }
        
        echo '<div class="vendor-chart-container">';
        echo '<canvas id="vendor-monthly-sales-chart"></canvas>';
        echo '</div>';
        
        // Chart script
        ?>
        <script>
        jQuery(document).ready(function($) {
            var ctx = document.getElementById('vendor-monthly-sales-chart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($chart_labels); ?>,
                    datasets: [{
                        label: '<?php echo esc_js(__('Sales', 'aqualuxe')); ?>',
                        data: <?php echo json_encode($chart_data); ?>,
                        backgroundColor: 'rgba(0, 119, 182, 0.7)',
                        borderColor: 'rgba(0, 119, 182, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
        </script>
        <?php
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display vendor dashboard settings section
 *
 * @param int $vendor_id Vendor ID
 */
function aqualuxe_vendor_dashboard_settings($vendor_id) {
    // Get vendor information
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    $user_info = get_userdata($vendor_id);
    
    echo '<div class="vendor-dashboard-section vendor-dashboard-settings">';
    echo '<h3>' . esc_html__('Settings', 'aqualuxe') . '</h3>';
    
    // Settings form
    echo '<form class="vendor-settings-form" method="post" enctype="multipart/form-data">';
    wp_nonce_field('aqualuxe_vendor_settings', 'aqualuxe_vendor_settings_nonce');
    
    // Store information
    echo '<div class="vendor-settings-section">';
    echo '<h4>' . esc_html__('Store Information', 'aqualuxe') . '</h4>';
    
    // Store name
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_name">' . esc_html__('Store Name', 'aqualuxe') . '</label>';
    echo '<input type="text" id="vendor_name" name="vendor_name" value="' . esc_attr($vendor_info['name']) . '" required>';
    echo '</div>';
    
    // Store description
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_description">' . esc_html__('Store Description', 'aqualuxe') . '</label>';
    echo '<textarea id="vendor_description" name="vendor_description" rows="5">' . esc_textarea($vendor_info['description']) . '</textarea>';
    echo '</div>';
    
    // Store logo
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_logo">' . esc_html__('Store Logo', 'aqualuxe') . '</label>';
    
    if (!empty($vendor_info['logo'])) {
        echo '<div class="vendor-logo-preview">';
        echo '<img src="' . esc_url($vendor_info['logo']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
        echo '</div>';
    }
    
    echo '<input type="file" id="vendor_logo" name="vendor_logo" accept="image/*">';
    echo '</div>';
    
    // Store banner
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_banner">' . esc_html__('Store Banner', 'aqualuxe') . '</label>';
    
    if (!empty($vendor_info['banner'])) {
        echo '<div class="vendor-banner-preview">';
        echo '<img src="' . esc_url($vendor_info['banner']) . '" alt="' . esc_attr($vendor_info['name']) . '">';
        echo '</div>';
    }
    
    echo '<input type="file" id="vendor_banner" name="vendor_banner" accept="image/*">';
    echo '</div>';
    
    echo '</div>';
    
    // Contact information
    echo '<div class="vendor-settings-section">';
    echo '<h4>' . esc_html__('Contact Information', 'aqualuxe') . '</h4>';
    
    // Email
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_email">' . esc_html__('Email', 'aqualuxe') . '</label>';
    echo '<input type="email" id="vendor_email" name="vendor_email" value="' . esc_attr($user_info->user_email) . '" required>';
    echo '</div>';
    
    // Phone
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_phone">' . esc_html__('Phone', 'aqualuxe') . '</label>';
    echo '<input type="tel" id="vendor_phone" name="vendor_phone" value="' . esc_attr($vendor_info['phone']) . '">';
    echo '</div>';
    
    // Address
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_address">' . esc_html__('Address', 'aqualuxe') . '</label>';
    echo '<textarea id="vendor_address" name="vendor_address" rows="3">' . esc_textarea($vendor_info['address']) . '</textarea>';
    echo '</div>';
    
    echo '</div>';
    
    // Social media
    echo '<div class="vendor-settings-section">';
    echo '<h4>' . esc_html__('Social Media', 'aqualuxe') . '</h4>';
    
    // Facebook
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_facebook">' . esc_html__('Facebook', 'aqualuxe') . '</label>';
    echo '<input type="url" id="vendor_facebook" name="vendor_facebook" value="' . esc_attr(isset($vendor_info['social']['facebook']) ? $vendor_info['social']['facebook'] : '') . '">';
    echo '</div>';
    
    // Twitter
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_twitter">' . esc_html__('Twitter', 'aqualuxe') . '</label>';
    echo '<input type="url" id="vendor_twitter" name="vendor_twitter" value="' . esc_attr(isset($vendor_info['social']['twitter']) ? $vendor_info['social']['twitter'] : '') . '">';
    echo '</div>';
    
    // Instagram
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_instagram">' . esc_html__('Instagram', 'aqualuxe') . '</label>';
    echo '<input type="url" id="vendor_instagram" name="vendor_instagram" value="' . esc_attr(isset($vendor_info['social']['instagram']) ? $vendor_info['social']['instagram'] : '') . '">';
    echo '</div>';
    
    // LinkedIn
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_linkedin">' . esc_html__('LinkedIn', 'aqualuxe') . '</label>';
    echo '<input type="url" id="vendor_linkedin" name="vendor_linkedin" value="' . esc_attr(isset($vendor_info['social']['linkedin']) ? $vendor_info['social']['linkedin'] : '') . '">';
    echo '</div>';
    
    // YouTube
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_youtube">' . esc_html__('YouTube', 'aqualuxe') . '</label>';
    echo '<input type="url" id="vendor_youtube" name="vendor_youtube" value="' . esc_attr(isset($vendor_info['social']['youtube']) ? $vendor_info['social']['youtube'] : '') . '">';
    echo '</div>';
    
    echo '</div>';
    
    // Payment information
    echo '<div class="vendor-settings-section">';
    echo '<h4>' . esc_html__('Payment Information', 'aqualuxe') . '</h4>';
    
    // Payment method
    echo '<div class="vendor-settings-field">';
    echo '<label for="vendor_payment_method">' . esc_html__('Payment Method', 'aqualuxe') . '</label>';
    echo '<select id="vendor_payment_method" name="vendor_payment_method">';
    echo '<option value="paypal" ' . selected(get_user_meta($vendor_id, '_vendor_payment_method', true), 'paypal', false) . '>' . esc_html__('PayPal', 'aqualuxe') . '</option>';
    echo '<option value="bank" ' . selected(get_user_meta($vendor_id, '_vendor_payment_method', true), 'bank', false) . '>' . esc_html__('Bank Transfer', 'aqualuxe') . '</option>';
    echo '</select>';
    echo '</div>';
    
    // PayPal email
    echo '<div class="vendor-settings-field vendor-payment-paypal">';
    echo '<label for="vendor_paypal_email">' . esc_html__('PayPal Email', 'aqualuxe') . '</label>';
    echo '<input type="email" id="vendor_paypal_email" name="vendor_paypal_email" value="' . esc_attr(get_user_meta($vendor_id, '_vendor_paypal_email', true)) . '">';
    echo '</div>';
    
    // Bank details
    echo '<div class="vendor-settings-field vendor-payment-bank">';
    echo '<label for="vendor_bank_details">' . esc_html__('Bank Details', 'aqualuxe') . '</label>';
    echo '<textarea id="vendor_bank_details" name="vendor_bank_details" rows="5">' . esc_textarea(get_user_meta($vendor_id, '_vendor_bank_details', true)) . '</textarea>';
    echo '</div>';
    
    echo '</div>';
    
    // Submit button
    echo '<div class="vendor-settings-submit">';
    echo '<button type="submit" class="button button-primary">' . esc_html__('Save Settings', 'aqualuxe') . '</button>';
    echo '</div>';
    
    echo '</form>';
    
    // JavaScript for payment method toggle
    ?>
    <script>
    jQuery(document).ready(function($) {
        function togglePaymentFields() {
            var method = $('#vendor_payment_method').val();
            
            if (method === 'paypal') {
                $('.vendor-payment-paypal').show();
                $('.vendor-payment-bank').hide();
            } else if (method === 'bank') {
                $('.vendor-payment-paypal').hide();
                $('.vendor-payment-bank').show();
            }
        }
        
        togglePaymentFields();
        
        $('#vendor_payment_method').on('change', function() {
            togglePaymentFields();
        });
    });
    </script>
    <?php
    
    echo '</div>';
    
    // Process form submission
    if (isset($_POST['aqualuxe_vendor_settings_nonce']) && wp_verify_nonce($_POST['aqualuxe_vendor_settings_nonce'], 'aqualuxe_vendor_settings')) {
        aqualuxe_process_vendor_settings($vendor_id);
    }
}

/**
 * Process vendor settings form
 *
 * @param int $vendor_id Vendor ID
 */
function aqualuxe_process_vendor_settings($vendor_id) {
    // Store information
    if (isset($_POST['vendor_name'])) {
        update_user_meta($vendor_id, '_vendor_name', sanitize_text_field($_POST['vendor_name']));
    }
    
    if (isset($_POST['vendor_description'])) {
        update_user_meta($vendor_id, '_vendor_description', wp_kses_post($_POST['vendor_description']));
    }
    
    // Store logo
    if (isset($_FILES['vendor_logo']) && !empty($_FILES['vendor_logo']['tmp_name'])) {
        $logo_id = media_handle_upload('vendor_logo', 0);
        
        if (!is_wp_error($logo_id)) {
            update_user_meta($vendor_id, '_vendor_logo', $logo_id);
        }
    }
    
    // Store banner
    if (isset($_FILES['vendor_banner']) && !empty($_FILES['vendor_banner']['tmp_name'])) {
        $banner_id = media_handle_upload('vendor_banner', 0);
        
        if (!is_wp_error($banner_id)) {
            update_user_meta($vendor_id, '_vendor_banner', $banner_id);
        }
    }
    
    // Contact information
    if (isset($_POST['vendor_email'])) {
        $user_id = wp_update_user([
            'ID' => $vendor_id,
            'user_email' => sanitize_email($_POST['vendor_email']),
        ]);
    }
    
    if (isset($_POST['vendor_phone'])) {
        update_user_meta($vendor_id, '_vendor_phone', sanitize_text_field($_POST['vendor_phone']));
    }
    
    if (isset($_POST['vendor_address'])) {
        update_user_meta($vendor_id, '_vendor_address', sanitize_textarea_field($_POST['vendor_address']));
    }
    
    // Social media
    $social_fields = [
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
    ];
    
    foreach ($social_fields as $field) {
        if (isset($_POST['vendor_' . $field])) {
            update_user_meta($vendor_id, '_vendor_' . $field, esc_url_raw($_POST['vendor_' . $field]));
        }
    }
    
    // Payment information
    if (isset($_POST['vendor_payment_method'])) {
        update_user_meta($vendor_id, '_vendor_payment_method', sanitize_text_field($_POST['vendor_payment_method']));
    }
    
    if (isset($_POST['vendor_paypal_email'])) {
        update_user_meta($vendor_id, '_vendor_paypal_email', sanitize_email($_POST['vendor_paypal_email']));
    }
    
    if (isset($_POST['vendor_bank_details'])) {
        update_user_meta($vendor_id, '_vendor_bank_details', sanitize_textarea_field($_POST['vendor_bank_details']));
    }
    
    // Redirect to prevent form resubmission
    wp_redirect(add_query_arg('section', 'settings'));
    exit;
}

/**
 * Add vendor registration fields
 */
function aqualuxe_vendor_registration_fields() {
    // Check if vendor registration is enabled
    if (!aqualuxe_get_option('enable_vendor_registration', true)) {
        return;
    }
    
    ?>
    <p class="form-row form-row-wide">
        <label for="vendor_registration">
            <input type="checkbox" id="vendor_registration" name="vendor_registration" value="1" />
            <?php esc_html_e('Register as a vendor', 'aqualuxe'); ?>
        </label>
    </p>
    
    <div class="vendor-registration-fields" style="display: none;">
        <p class="form-row form-row-wide">
            <label for="vendor_name"><?php esc_html_e('Store Name', 'aqualuxe'); ?> <span class="required">*</span></label>
            <input type="text" class="input-text" name="vendor_name" id="vendor_name" />
        </p>
        
        <p class="form-row form-row-wide">
            <label for="vendor_description"><?php esc_html_e('Store Description', 'aqualuxe'); ?></label>
            <textarea class="input-text" name="vendor_description" id="vendor_description" rows="5"></textarea>
        </p>
        
        <p class="form-row form-row-wide">
            <label for="vendor_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="tel" class="input-text" name="vendor_phone" id="vendor_phone" />
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('#vendor_registration').on('change', function() {
            if ($(this).is(':checked')) {
                $('.vendor-registration-fields').show();
            } else {
                $('.vendor-registration-fields').hide();
            }
        });
    });
    </script>
    <?php
}

/**
 * Save vendor registration fields
 *
 * @param int $customer_id Customer ID
 */
function aqualuxe_save_vendor_registration_fields($customer_id) {
    if (isset($_POST['vendor_registration']) && $_POST['vendor_registration'] === '1') {
        // Add vendor role
        $user = new WP_User($customer_id);
        $user->add_role('aqualuxe_vendor');
        
        // Save vendor information
        if (isset($_POST['vendor_name'])) {
            update_user_meta($customer_id, '_vendor_name', sanitize_text_field($_POST['vendor_name']));
        }
        
        if (isset($_POST['vendor_description'])) {
            update_user_meta($customer_id, '_vendor_description', wp_kses_post($_POST['vendor_description']));
        }
        
        if (isset($_POST['vendor_phone'])) {
            update_user_meta($customer_id, '_vendor_phone', sanitize_text_field($_POST['vendor_phone']));
        }
        
        // Set default commission rate
        $default_commission = aqualuxe_get_option('default_vendor_commission', 70);
        update_user_meta($customer_id, '_vendor_commission_rate', $default_commission);
        
        // Notify admin
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('New Vendor Registration: %s', 'aqualuxe'), $_POST['vendor_name']);
        $message = sprintf(__('A new vendor has registered on your site.

Vendor Name: %s
Email: %s
Phone: %s

Please review this vendor account.', 'aqualuxe'), $_POST['vendor_name'], $_POST['email'], $_POST['vendor_phone']);
        
        wp_mail($admin_email, $subject, $message);
    }
}

/**
 * Add vendor commission fields to product
 */
function aqualuxe_vendor_commission_fields() {
    // Check if current user is admin
    if (!current_user_can('manage_options')) {
        return;
    }
    
    global $post;
    $vendor_id = aqualuxe_get_vendor_id_from_product($post->ID);
    
    if (!$vendor_id) {
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($vendor_id);
    $vendor_commission = get_post_meta($post->ID, '_vendor_commission', true);
    $vendor_commission_rate = get_user_meta($vendor_id, '_vendor_commission_rate', true);
    
    if (!$vendor_commission_rate) {
        $vendor_commission_rate = aqualuxe_get_option('default_vendor_commission', 70);
    }
    
    echo '<div class="options_group">';
    echo '<p class="form-field">';
    echo '<label>' . esc_html__('Vendor', 'aqualuxe') . '</label>';
    echo '<span>' . esc_html($vendor_info['name']) . '</span>';
    echo '</p>';
    
    woocommerce_wp_text_input([
        'id' => '_vendor_commission',
        'label' => __('Vendor Commission (%)', 'aqualuxe'),
        'description' => __('Commission percentage for this product. Leave empty to use vendor default.', 'aqualuxe'),
        'desc_tip' => true,
        'type' => 'number',
        'custom_attributes' => [
            'min' => '0',
            'max' => '100',
            'step' => '0.01',
        ],
        'value' => $vendor_commission ? $vendor_commission : $vendor_commission_rate,
    ]);
    
    echo '</div>';
}

/**
 * Save vendor commission fields
 *
 * @param int $post_id Post ID
 */
function aqualuxe_save_vendor_commission_fields($post_id) {
    // Check if current user is admin
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['_vendor_commission'])) {
        update_post_meta($post_id, '_vendor_commission', sanitize_text_field($_POST['_vendor_commission']));
    }
}

/**
 * Add vendor profile fields
 *
 * @param WP_User $user User object
 */
function aqualuxe_vendor_profile_fields($user) {
    // Check if user is a vendor
    if (!aqualuxe_is_vendor($user->ID) && !current_user_can('manage_options')) {
        return;
    }
    
    $vendor_info = aqualuxe_get_vendor_info($user->ID);
    $vendor_commission_rate = get_user_meta($user->ID, '_vendor_commission_rate', true);
    
    if (!$vendor_commission_rate) {
        $vendor_commission_rate = aqualuxe_get_option('default_vendor_commission', 70);
    }
    
    ?>
    <h2><?php esc_html_e('Vendor Information', 'aqualuxe'); ?></h2>
    
    <table class="form-table">
        <tr>
            <th><label for="vendor_name"><?php esc_html_e('Store Name', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="vendor_name" id="vendor_name" value="<?php echo esc_attr($vendor_info['name']); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_description"><?php esc_html_e('Store Description', 'aqualuxe'); ?></label></th>
            <td>
                <textarea name="vendor_description" id="vendor_description" rows="5" class="regular-text"><?php echo esc_textarea($vendor_info['description']); ?></textarea>
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label></th>
            <td>
                <input type="tel" name="vendor_phone" id="vendor_phone" value="<?php echo esc_attr($vendor_info['phone']); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label></th>
            <td>
                <textarea name="vendor_address" id="vendor_address" rows="3" class="regular-text"><?php echo esc_textarea($vendor_info['address']); ?></textarea>
            </td>
        </tr>
        
        <?php if (current_user_can('manage_options')) : ?>
        <tr>
            <th><label for="vendor_commission_rate"><?php esc_html_e('Commission Rate (%)', 'aqualuxe'); ?></label></th>
            <td>
                <input type="number" name="vendor_commission_rate" id="vendor_commission_rate" value="<?php echo esc_attr($vendor_commission_rate); ?>" class="regular-text" min="0" max="100" step="0.01" />
                <p class="description"><?php esc_html_e('Percentage of the sale that goes to the vendor.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    
    <h3><?php esc_html_e('Social Media', 'aqualuxe'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th><label for="vendor_facebook"><?php esc_html_e('Facebook', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" name="vendor_facebook" id="vendor_facebook" value="<?php echo esc_attr(isset($vendor_info['social']['facebook']) ? $vendor_info['social']['facebook'] : ''); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_twitter"><?php esc_html_e('Twitter', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" name="vendor_twitter" id="vendor_twitter" value="<?php echo esc_attr(isset($vendor_info['social']['twitter']) ? $vendor_info['social']['twitter'] : ''); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_instagram"><?php esc_html_e('Instagram', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" name="vendor_instagram" id="vendor_instagram" value="<?php echo esc_attr(isset($vendor_info['social']['instagram']) ? $vendor_info['social']['instagram'] : ''); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_linkedin"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" name="vendor_linkedin" id="vendor_linkedin" value="<?php echo esc_attr(isset($vendor_info['social']['linkedin']) ? $vendor_info['social']['linkedin'] : ''); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr>
            <th><label for="vendor_youtube"><?php esc_html_e('YouTube', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" name="vendor_youtube" id="vendor_youtube" value="<?php echo esc_attr(isset($vendor_info['social']['youtube']) ? $vendor_info['social']['youtube'] : ''); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    
    <h3><?php esc_html_e('Payment Information', 'aqualuxe'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th><label for="vendor_payment_method"><?php esc_html_e('Payment Method', 'aqualuxe'); ?></label></th>
            <td>
                <select name="vendor_payment_method" id="vendor_payment_method">
                    <option value="paypal" <?php selected(get_user_meta($user->ID, '_vendor_payment_method', true), 'paypal'); ?>><?php esc_html_e('PayPal', 'aqualuxe'); ?></option>
                    <option value="bank" <?php selected(get_user_meta($user->ID, '_vendor_payment_method', true), 'bank'); ?>><?php esc_html_e('Bank Transfer', 'aqualuxe'); ?></option>
                </select>
            </td>
        </tr>
        
        <tr class="vendor-payment-paypal">
            <th><label for="vendor_paypal_email"><?php esc_html_e('PayPal Email', 'aqualuxe'); ?></label></th>
            <td>
                <input type="email" name="vendor_paypal_email" id="vendor_paypal_email" value="<?php echo esc_attr(get_user_meta($user->ID, '_vendor_paypal_email', true)); ?>" class="regular-text" />
            </td>
        </tr>
        
        <tr class="vendor-payment-bank">
            <th><label for="vendor_bank_details"><?php esc_html_e('Bank Details', 'aqualuxe'); ?></label></th>
            <td>
                <textarea name="vendor_bank_details" id="vendor_bank_details" rows="5" class="regular-text"><?php echo esc_textarea(get_user_meta($user->ID, '_vendor_bank_details', true)); ?></textarea>
            </td>
        </tr>
    </table>
    
    <script>
    jQuery(document).ready(function($) {
        function togglePaymentFields() {
            var method = $('#vendor_payment_method').val();
            
            if (method === 'paypal') {
                $('.vendor-payment-paypal').show();
                $('.vendor-payment-bank').hide();
            } else if (method === 'bank') {
                $('.vendor-payment-paypal').hide();
                $('.vendor-payment-bank').show();
            }
        }
        
        togglePaymentFields();
        
        $('#vendor_payment_method').on('change', function() {
            togglePaymentFields();
        });
    });
    </script>
    <?php
}

/**
 * Save vendor profile fields
 *
 * @param int $user_id User ID
 */
function aqualuxe_save_vendor_profile_fields($user_id) {
    // Check if current user can edit this user
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }
    
    // Store information
    if (isset($_POST['vendor_name'])) {
        update_user_meta($user_id, '_vendor_name', sanitize_text_field($_POST['vendor_name']));
    }
    
    if (isset($_POST['vendor_description'])) {
        update_user_meta($user_id, '_vendor_description', wp_kses_post($_POST['vendor_description']));
    }
    
    // Contact information
    if (isset($_POST['vendor_phone'])) {
        update_user_meta($user_id, '_vendor_phone', sanitize_text_field($_POST['vendor_phone']));
    }
    
    if (isset($_POST['vendor_address'])) {
        update_user_meta($user_id, '_vendor_address', sanitize_textarea_field($_POST['vendor_address']));
    }
    
    // Commission rate (admin only)
    if (current_user_can('manage_options') && isset($_POST['vendor_commission_rate'])) {
        update_user_meta($user_id, '_vendor_commission_rate', sanitize_text_field($_POST['vendor_commission_rate']));
    }
    
    // Social media
    $social_fields = [
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
    ];
    
    foreach ($social_fields as $field) {
        if (isset($_POST['vendor_' . $field])) {
            update_user_meta($user_id, '_vendor_' . $field, esc_url_raw($_POST['vendor_' . $field]));
        }
    }
    
    // Payment information
    if (isset($_POST['vendor_payment_method'])) {
        update_user_meta($user_id, '_vendor_payment_method', sanitize_text_field($_POST['vendor_payment_method']));
    }
    
    if (isset($_POST['vendor_paypal_email'])) {
        update_user_meta($user_id, '_vendor_paypal_email', sanitize_email($_POST['vendor_paypal_email']));
    }
    
    if (isset($_POST['vendor_bank_details'])) {
        update_user_meta($user_id, '_vendor_bank_details', sanitize_textarea_field($_POST['vendor_bank_details']));
    }
}

/**
 * Add vendor capabilities
 */
function aqualuxe_vendor_capabilities() {
    // Check if role exists
    if (get_role('aqualuxe_vendor')) {
        return;
    }
    
    // Add vendor role
    add_role('aqualuxe_vendor', __('Vendor', 'aqualuxe'), [
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
    ]);
    
    // Add product capabilities
    $vendor_role = get_role('aqualuxe_vendor');
    $vendor_role->add_cap('edit_product');
    $vendor_role->add_cap('read_product');
    $vendor_role->add_cap('delete_product');
    $vendor_role->add_cap('edit_products');
    $vendor_role->add_cap('edit_published_products');
    $vendor_role->add_cap('publish_products');
    $vendor_role->add_cap('read_private_products');
    $vendor_role->add_cap('delete_products');
    $vendor_role->add_cap('delete_published_products');
    $vendor_role->add_cap('manage_product_terms');
    $vendor_role->add_cap('assign_product_terms');
}

/**
 * Get vendor store URL
 *
 * @param int $vendor_id Vendor ID
 * @return string
 */
function aqualuxe_get_vendor_store_url($vendor_id) {
    // Check for WC Marketplace
    if (function_exists('wcmp_get_vendor') && $vendor = wcmp_get_vendor($vendor_id)) {
        return $vendor->permalink;
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_store_url') && dokan_get_store_url($vendor_id)) {
        return dokan_get_store_url($vendor_id);
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors') && method_exists('WCV_Vendors', 'get_vendor_shop_page')) {
        return WCV_Vendors::get_vendor_shop_page($vendor_id);
    }
    
    // Default to author page
    return get_author_posts_url($vendor_id);
}

/**
 * Get vendor rating
 *
 * @param int $vendor_id Vendor ID
 * @return float
 */
function aqualuxe_get_vendor_rating($vendor_id) {
    // Check for WC Marketplace
    if (function_exists('wcmp_get_vendor_review_count') && function_exists('wcmp_get_vendor_rating')) {
        $rating_count = wcmp_get_vendor_review_count($vendor_id);
        
        if ($rating_count > 0) {
            return wcmp_get_vendor_rating($vendor_id);
        }
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_seller_rating')) {
        $rating = dokan_get_seller_rating($vendor_id);
        
        if ($rating['count'] > 0) {
            return $rating['rating'];
        }
    }
    
    // Calculate rating from product reviews
    $products = aqualuxe_get_vendor_products($vendor_id, -1);
    $rating_sum = 0;
    $rating_count = 0;
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            if ($product->get_rating_count() > 0) {
                $rating_sum += $product->get_average_rating() * $product->get_rating_count();
                $rating_count += $product->get_rating_count();
            }
        }
        
        wp_reset_postdata();
    }
    
    if ($rating_count > 0) {
        return round($rating_sum / $rating_count, 2);
    }
    
    return 0;
}

/**
 * Get vendor products
 *
 * @param int $vendor_id Vendor ID
 * @param int $limit Number of products to get
 * @return WP_Query
 */
function aqualuxe_get_vendor_products($vendor_id, $limit = 10) {
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'author' => $vendor_id,
    ];
    
    // Check for WC Marketplace
    if (function_exists('get_wcmp_vendor_products') && $vendor = wcmp_get_vendor($vendor_id)) {
        $vendor_products = $vendor->get_products();
        
        if (!empty($vendor_products)) {
            $args['post__in'] = $vendor_products;
            $args['author'] = '';
        }
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_vendor_by_id') && $vendor = dokan_get_vendor_by_id($vendor_id)) {
        $vendor_products = $vendor->get_products();
        
        if (!empty($vendor_products)) {
            $args['post__in'] = $vendor_products;
            $args['author'] = '';
        }
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
        $args['author'] = $vendor_id;
    }
    
    return new WP_Query($args);
}

/**
 * Get vendor orders
 *
 * @param int $vendor_id Vendor ID
 * @param int $limit Number of orders to get
 * @return array
 */
function aqualuxe_get_vendor_orders($vendor_id, $limit = 10) {
    $vendor_orders = [];
    
    // Check for WC Marketplace
    if (function_exists('get_wcmp_vendor_orders') && $vendor = wcmp_get_vendor($vendor_id)) {
        return $vendor->get_orders($limit);
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_seller_orders') && function_exists('dokan_get_seller_orders_count')) {
        $args = [
            'seller_id' => $vendor_id,
            'posts_per_page' => $limit,
            'paged' => 1,
        ];
        
        $orders = dokan_get_seller_orders($vendor_id, $args);
        
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $vendor_orders[] = wc_get_order($order->order_id);
            }
        }
        
        return $vendor_orders;
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
        $args = [
            'post_type' => 'shop_order',
            'post_status' => array_keys(wc_get_order_statuses()),
            'posts_per_page' => $limit,
            'meta_query' => [
                [
                    'key' => '_vendor_id',
                    'value' => $vendor_id,
                    'compare' => '=',
                ],
            ],
        ];
        
        $orders = get_posts($args);
        
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $vendor_orders[] = wc_get_order($order->ID);
            }
        }
        
        return $vendor_orders;
    }
    
    // Default implementation
    $args = [
        'post_type' => 'shop_order',
        'post_status' => array_keys(wc_get_order_statuses()),
        'posts_per_page' => $limit,
    ];
    
    $orders = get_posts($args);
    
    if (!empty($orders)) {
        foreach ($orders as $order) {
            $wc_order = wc_get_order($order->ID);
            $has_vendor_products = false;
            
            foreach ($wc_order->get_items() as $item) {
                $product_id = $item->get_product_id();
                $product_vendor_id = aqualuxe_get_vendor_id_from_product($product_id);
                
                if ($product_vendor_id === $vendor_id) {
                    $has_vendor_products = true;
                    break;
                }
            }
            
            if ($has_vendor_products) {
                $vendor_orders[] = $wc_order;
            }
        }
    }
    
    return $vendor_orders;
}

/**
 * Get vendor reports
 *
 * @param int $vendor_id Vendor ID
 * @return array
 */
function aqualuxe_get_vendor_reports($vendor_id) {
    $reports = [
        'total_sales' => 0,
        'total_orders' => 0,
        'total_products' => 0,
        'commission' => 0,
        'top_products' => [],
        'monthly_sales' => [],
    ];
    
    // Get vendor products
    $products_query = aqualuxe_get_vendor_products($vendor_id, -1);
    $reports['total_products'] = $products_query->found_posts;
    
    // Get vendor orders
    $orders = aqualuxe_get_vendor_orders($vendor_id, -1);
    $reports['total_orders'] = count($orders);
    
    // Calculate sales and commission
    $product_sales = [];
    $monthly_sales = [];
    
    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $product_vendor_id = aqualuxe_get_vendor_id_from_product($product_id);
            
            if ($product_vendor_id === $vendor_id) {
                $product = wc_get_product($product_id);
                $product_total = $item->get_total();
                $reports['total_sales'] += $product_total;
                
                // Commission
                $commission_rate = get_post_meta($product_id, '_vendor_commission', true);
                
                if (!$commission_rate) {
                    $commission_rate = get_user_meta($vendor_id, '_vendor_commission_rate', true);
                    
                    if (!$commission_rate) {
                        $commission_rate = aqualuxe_get_option('default_vendor_commission', 70);
                    }
                }
                
                $commission = ($product_total * $commission_rate) / 100;
                $reports['commission'] += $commission;
                
                // Product sales
                if (!isset($product_sales[$product_id])) {
                    $product_sales[$product_id] = [
                        'id' => $product_id,
                        'name' => $product->get_name(),
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                
                $product_sales[$product_id]['quantity'] += $item->get_quantity();
                $product_sales[$product_id]['revenue'] += $product_total;
                
                // Monthly sales
                $month = $order->get_date_created()->format('F Y');
                
                if (!isset($monthly_sales[$month])) {
                    $monthly_sales[$month] = 0;
                }
                
                $monthly_sales[$month] += $product_total;
            }
        }
    }
    
    // Sort product sales by revenue
    usort($product_sales, function($a, $b) {
        return $b['revenue'] - $a['revenue'];
    });
    
    // Get top 5 products
    $reports['top_products'] = array_slice($product_sales, 0, 5);
    
    // Sort monthly sales by date
    ksort($monthly_sales);
    
    // Get last 12 months
    $reports['monthly_sales'] = array_slice($monthly_sales, -12, 12, true);
    
    return $reports;
}

/**
 * Get vendors
 *
 * @return array
 */
function aqualuxe_get_vendors() {
    $vendors = [];
    
    // Check for WC Marketplace
    if (function_exists('get_wcmp_vendors')) {
        $wcmp_vendors = get_wcmp_vendors();
        
        if (!empty($wcmp_vendors)) {
            foreach ($wcmp_vendors as $vendor) {
                $vendors[$vendor->id] = $vendor->page_title;
            }
        }
        
        return $vendors;
    }
    
    // Check for Dokan
    if (function_exists('dokan_get_sellers')) {
        $dokan_vendors = dokan_get_sellers();
        
        if (!empty($dokan_vendors['users'])) {
            foreach ($dokan_vendors['users'] as $vendor) {
                $vendors[$vendor->ID] = $vendor->display_name;
            }
        }
        
        return $vendors;
    }
    
    // Check for WC Vendors
    if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
        $vendor_ids = WCV_Vendors::get_vendors();
        
        if (!empty($vendor_ids)) {
            foreach ($vendor_ids as $vendor_id) {
                $vendors[$vendor_id] = WCV_Vendors::get_vendor_shop_name($vendor_id);
            }
        }
        
        return $vendors;
    }
    
    // Default implementation
    $vendor_role = 'aqualuxe_vendor';
    $args = [
        'role' => $vendor_role,
    ];
    
    $vendor_users = get_users($args);
    
    if (!empty($vendor_users)) {
        foreach ($vendor_users as $user) {
            $vendor_name = get_user_meta($user->ID, '_vendor_name', true);
            $vendors[$user->ID] = $vendor_name ? $vendor_name : $user->display_name;
        }
    }
    
    return $vendors;
}

/**
 * Add multi-vendor support to customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer object
 */
function aqualuxe_multi_vendor_customizer($wp_customize) {
    // Add multi-vendor section to customizer
    $wp_customize->add_section('aqualuxe_multi_vendor', [
        'title' => __('Multi-Vendor Settings', 'aqualuxe'),
        'priority' => 40,
    ]);
    
    // Enable multi-vendor support
    $wp_customize->add_setting('aqualuxe_options[enable_multivendor]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_multivendor', [
        'label' => __('Enable multi-vendor support', 'aqualuxe'),
        'section' => 'aqualuxe_multi_vendor',
        'settings' => 'aqualuxe_options[enable_multivendor]',
        'type' => 'checkbox',
    ]);
    
    // Enable vendor registration
    $wp_customize->add_setting('aqualuxe_options[enable_vendor_registration]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_vendor_registration', [
        'label' => __('Enable vendor registration', 'aqualuxe'),
        'section' => 'aqualuxe_multi_vendor',
        'settings' => 'aqualuxe_options[enable_vendor_registration]',
        'type' => 'checkbox',
    ]);
    
    // Enable vendor filter
    $wp_customize->add_setting('aqualuxe_options[enable_vendor_filter]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_vendor_filter', [
        'label' => __('Enable vendor filter on shop page', 'aqualuxe'),
        'section' => 'aqualuxe_multi_vendor',
        'settings' => 'aqualuxe_options[enable_vendor_filter]',
        'type' => 'checkbox',
    ]);
    
    // Default vendor commission
    $wp_customize->add_setting('aqualuxe_options[default_vendor_commission]', [
        'default' => 70,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_default_vendor_commission', [
        'label' => __('Default Vendor Commission (%)', 'aqualuxe'),
        'description' => __('Percentage of the sale that goes to the vendor.', 'aqualuxe'),
        'section' => 'aqualuxe_multi_vendor',
        'settings' => 'aqualuxe_options[default_vendor_commission]',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ],
    ]);
}
add_action('customize_register', 'aqualuxe_multi_vendor_customizer');