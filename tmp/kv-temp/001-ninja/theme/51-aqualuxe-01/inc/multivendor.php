<?php
/**
 * Multivendor support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multivendor support
 */
function aqualuxe_multivendor_init() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_wc_vendors_scripts');
        add_filter('body_class', 'aqualuxe_wc_vendors_body_class');
        add_action('aqualuxe_after_vendor_header', 'aqualuxe_wc_vendors_header');
        add_action('aqualuxe_vendor_dashboard_content', 'aqualuxe_wc_vendors_dashboard_content');
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_dokan_scripts');
        add_filter('body_class', 'aqualuxe_dokan_body_class');
        add_action('aqualuxe_after_vendor_header', 'aqualuxe_dokan_header');
        add_action('aqualuxe_vendor_dashboard_content', 'aqualuxe_dokan_dashboard_content');
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_wcfm_scripts');
        add_filter('body_class', 'aqualuxe_wcfm_body_class');
        add_action('aqualuxe_after_vendor_header', 'aqualuxe_wcfm_header');
        add_action('aqualuxe_vendor_dashboard_content', 'aqualuxe_wcfm_dashboard_content');
    }
}
add_action('after_setup_theme', 'aqualuxe_multivendor_init');

/**
 * Enqueue WC Vendors scripts
 */
function aqualuxe_wc_vendors_scripts() {
    wp_enqueue_style('aqualuxe-wc-vendors', AQUALUXE_ASSETS_URI . 'css/wc-vendors.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WC Vendors body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_wc_vendors_body_class($classes) {
    $classes[] = 'wc-vendors-active';
    
    if (function_exists('wcv_get_vendor_id') && wcv_get_vendor_id()) {
        $classes[] = 'wc-vendors-vendor';
    }
    
    return $classes;
}

/**
 * Display WC Vendors header
 */
function aqualuxe_wc_vendors_header() {
    if (!function_exists('wcv_get_vendor_id') || !wcv_get_vendor_id()) {
        return;
    }
    
    $vendor_id = wcv_get_vendor_id();
    $vendor_shop_name = wcv_get_vendor_shop_name($vendor_id);
    $vendor_shop_url = wcv_get_vendor_shop_page($vendor_id);
    $vendor_description = get_user_meta($vendor_id, 'pv_shop_description', true);
    
    ?>
    <div class="aqualuxe-vendor-header wc-vendors-vendor-header">
        <div class="container">
            <div class="aqualuxe-vendor-header-inner">
                <div class="aqualuxe-vendor-header-info">
                    <h1 class="aqualuxe-vendor-header-name"><?php echo esc_html($vendor_shop_name); ?></h1>
                    
                    <?php if (!empty($vendor_description)) : ?>
                        <div class="aqualuxe-vendor-header-description">
                            <?php echo wp_kses_post($vendor_description); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="aqualuxe-vendor-header-actions">
                    <a href="<?php echo esc_url($vendor_shop_url); ?>" class="button"><?php esc_html_e('Visit Store', 'aqualuxe'); ?></a>
                    
                    <?php if (function_exists('wcv_get_vendor_dashboard_url')) : ?>
                        <a href="<?php echo esc_url(wcv_get_vendor_dashboard_url()); ?>" class="button button-primary"><?php esc_html_e('Dashboard', 'aqualuxe'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display WC Vendors dashboard content
 */
function aqualuxe_wc_vendors_dashboard_content() {
    if (!function_exists('wcv_get_vendor_id') || !wcv_get_vendor_id()) {
        return;
    }
    
    if (function_exists('wcv_get_vendor_dashboard_template')) {
        wcv_get_vendor_dashboard_template();
    }
}

/**
 * Enqueue Dokan scripts
 */
function aqualuxe_dokan_scripts() {
    wp_enqueue_style('aqualuxe-dokan', AQUALUXE_ASSETS_URI . 'css/dokan.css', array(), AQUALUXE_VERSION);
}

/**
 * Add Dokan body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_dokan_body_class($classes) {
    $classes[] = 'dokan-active';
    
    if (function_exists('dokan_is_seller_dashboard') && dokan_is_seller_dashboard()) {
        $classes[] = 'dokan-dashboard';
    }
    
    if (function_exists('dokan_is_store_page') && dokan_is_store_page()) {
        $classes[] = 'dokan-store';
    }
    
    if (function_exists('dokan_is_seller_enabled') && dokan_is_seller_enabled(get_current_user_id())) {
        $classes[] = 'dokan-seller';
    }
    
    return $classes;
}

/**
 * Display Dokan header
 */
function aqualuxe_dokan_header() {
    if (!function_exists('dokan_is_store_page') || !dokan_is_store_page()) {
        return;
    }
    
    $store_user = dokan()->vendor->get(get_query_var('author'));
    $store_info = $store_user->get_shop_info();
    $store_name = $store_info['store_name'];
    $store_url = dokan_get_store_url($store_user->get_id());
    $store_description = $store_info['store_description'];
    
    ?>
    <div class="aqualuxe-vendor-header dokan-vendor-header">
        <div class="container">
            <div class="aqualuxe-vendor-header-inner">
                <div class="aqualuxe-vendor-header-info">
                    <h1 class="aqualuxe-vendor-header-name"><?php echo esc_html($store_name); ?></h1>
                    
                    <?php if (!empty($store_description)) : ?>
                        <div class="aqualuxe-vendor-header-description">
                            <?php echo wp_kses_post($store_description); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="aqualuxe-vendor-header-actions">
                    <a href="<?php echo esc_url($store_url); ?>" class="button"><?php esc_html_e('Visit Store', 'aqualuxe'); ?></a>
                    
                    <?php if (function_exists('dokan_get_dashboard_url') && dokan_is_user_seller(get_current_user_id())) : ?>
                        <a href="<?php echo esc_url(dokan_get_dashboard_url()); ?>" class="button button-primary"><?php esc_html_e('Dashboard', 'aqualuxe'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display Dokan dashboard content
 */
function aqualuxe_dokan_dashboard_content() {
    if (!function_exists('dokan_is_seller_dashboard') || !dokan_is_seller_dashboard()) {
        return;
    }
    
    if (function_exists('dokan_get_template_part')) {
        dokan_get_template_part('dashboard/dashboard');
    }
}

/**
 * Enqueue WCFM scripts
 */
function aqualuxe_wcfm_scripts() {
    wp_enqueue_style('aqualuxe-wcfm', AQUALUXE_ASSETS_URI . 'css/wcfm.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WCFM body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_wcfm_body_class($classes) {
    $classes[] = 'wcfm-active';
    
    if (function_exists('wcfm_is_store_page') && wcfm_is_store_page()) {
        $classes[] = 'wcfm-store';
    }
    
    if (function_exists('is_wcfm_page') && is_wcfm_page()) {
        $classes[] = 'wcfm-dashboard';
    }
    
    if (function_exists('wcfm_is_vendor') && wcfm_is_vendor()) {
        $classes[] = 'wcfm-vendor';
    }
    
    return $classes;
}

/**
 * Display WCFM header
 */
function aqualuxe_wcfm_header() {
    if (!function_exists('wcfm_is_store_page') || !wcfm_is_store_page()) {
        return;
    }
    
    $store_user = wcfmmp_get_store(get_query_var('author'));
    $store_info = $store_user->get_shop_info();
    $store_name = $store_info['store_name'];
    $store_url = wcfmmp_get_store_url($store_user->get_id());
    $store_description = $store_info['shop_description'];
    
    ?>
    <div class="aqualuxe-vendor-header wcfm-vendor-header">
        <div class="container">
            <div class="aqualuxe-vendor-header-inner">
                <div class="aqualuxe-vendor-header-info">
                    <h1 class="aqualuxe-vendor-header-name"><?php echo esc_html($store_name); ?></h1>
                    
                    <?php if (!empty($store_description)) : ?>
                        <div class="aqualuxe-vendor-header-description">
                            <?php echo wp_kses_post($store_description); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="aqualuxe-vendor-header-actions">
                    <a href="<?php echo esc_url($store_url); ?>" class="button"><?php esc_html_e('Visit Store', 'aqualuxe'); ?></a>
                    
                    <?php if (function_exists('get_wcfm_url') && wcfm_is_vendor()) : ?>
                        <a href="<?php echo esc_url(get_wcfm_url()); ?>" class="button button-primary"><?php esc_html_e('Dashboard', 'aqualuxe'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display WCFM dashboard content
 */
function aqualuxe_wcfm_dashboard_content() {
    if (!function_exists('is_wcfm_page') || !is_wcfm_page()) {
        return;
    }
    
    if (function_exists('wcfm_get_template')) {
        wcfm_get_template('dashboard/wcfm-view-dashboard.php');
    }
}

/**
 * Check if current user is a vendor
 *
 * @return bool
 */
function aqualuxe_is_vendor() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_get_vendor_id')) {
        return wcv_get_vendor_id() > 0;
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_is_user_seller')) {
        return dokan_is_user_seller(get_current_user_id());
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('wcfm_is_vendor')) {
        return wcfm_is_vendor();
    }
    
    return false;
}

/**
 * Get vendor ID
 *
 * @return int
 */
function aqualuxe_get_vendor_id() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return 0;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_get_vendor_id')) {
        return wcv_get_vendor_id();
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_is_user_seller')) {
        if (dokan_is_user_seller(get_current_user_id())) {
            return get_current_user_id();
        }
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('wcfm_get_vendor_id_by_user')) {
        return wcfm_get_vendor_id_by_user(get_current_user_id());
    }
    
    return 0;
}

/**
 * Get vendor data
 *
 * @param int $vendor_id
 * @return array
 */
function aqualuxe_get_vendor_data($vendor_id = 0) {
    if ($vendor_id === 0) {
        $vendor_id = aqualuxe_get_vendor_id();
    }
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return array();
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_get_vendor_shop_name')) {
        return array(
            'id' => $vendor_id,
            'name' => wcv_get_vendor_shop_name($vendor_id),
            'url' => wcv_get_vendor_shop_page($vendor_id),
            'description' => get_user_meta($vendor_id, 'pv_shop_description', true),
        );
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_get_store_info')) {
        $store_info = dokan_get_store_info($vendor_id);
        
        return array(
            'id' => $vendor_id,
            'name' => $store_info['store_name'],
            'url' => dokan_get_store_url($vendor_id),
            'description' => $store_info['store_description'],
        );
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('wcfm_get_vendor_store_info')) {
        $store_info = wcfm_get_vendor_store_info($vendor_id);
        
        return array(
            'id' => $vendor_id,
            'name' => $store_info['store_name'],
            'url' => wcfmmp_get_store_url($vendor_id),
            'description' => $store_info['shop_description'],
        );
    }
    
    return array();
}

/**
 * Get vendor products
 *
 * @param int $vendor_id
 * @param int $limit
 * @return array
 */
function aqualuxe_get_vendor_products($vendor_id = 0, $limit = 10) {
    if ($vendor_id === 0) {
        $vendor_id = aqualuxe_get_vendor_id();
    }
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return array();
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
    );
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors')) {
        $args['author'] = $vendor_id;
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan')) {
        $args['author'] = $vendor_id;
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM')) {
        $args['author'] = $vendor_id;
    }
    
    $products = new WP_Query($args);
    
    return $products->posts;
}

/**
 * Get vendor dashboard URL
 *
 * @return string
 */
function aqualuxe_get_vendor_dashboard_url() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_get_vendor_dashboard_url')) {
        return wcv_get_vendor_dashboard_url();
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_get_dashboard_url')) {
        return dokan_get_dashboard_url();
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('get_wcfm_url')) {
        return get_wcfm_url();
    }
    
    return '';
}

/**
 * Get vendor store URL
 *
 * @param int $vendor_id
 * @return string
 */
function aqualuxe_get_vendor_store_url($vendor_id = 0) {
    if ($vendor_id === 0) {
        $vendor_id = aqualuxe_get_vendor_id();
    }
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_get_vendor_shop_page')) {
        return wcv_get_vendor_shop_page($vendor_id);
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_get_store_url')) {
        return dokan_get_store_url($vendor_id);
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('wcfmmp_get_store_url')) {
        return wcfmmp_get_store_url($vendor_id);
    }
    
    return '';
}

/**
 * Check if current page is vendor dashboard
 *
 * @return bool
 */
function aqualuxe_is_vendor_dashboard() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_is_vendor_dashboard')) {
        return wcv_is_vendor_dashboard();
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_is_seller_dashboard')) {
        return dokan_is_seller_dashboard();
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('is_wcfm_page')) {
        return is_wcfm_page();
    }
    
    return false;
}

/**
 * Check if current page is vendor store
 *
 * @return bool
 */
function aqualuxe_is_vendor_store() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors') && function_exists('wcv_is_vendor_page')) {
        return wcv_is_vendor_page();
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan') && function_exists('dokan_is_store_page')) {
        return dokan_is_store_page();
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM') && function_exists('wcfm_is_store_page')) {
        return wcfm_is_store_page();
    }
    
    return false;
}

/**
 * Display vendor store header
 */
function aqualuxe_vendor_store_header() {
    if (!aqualuxe_is_vendor_store()) {
        return;
    }
    
    do_action('aqualuxe_before_vendor_header');
    
    // Display vendor header based on active plugin
    do_action('aqualuxe_after_vendor_header');
}

/**
 * Display vendor dashboard content
 */
function aqualuxe_vendor_dashboard_content() {
    if (!aqualuxe_is_vendor_dashboard()) {
        return;
    }
    
    do_action('aqualuxe_before_vendor_dashboard_content');
    
    // Display vendor dashboard content based on active plugin
    do_action('aqualuxe_vendor_dashboard_content');
    
    do_action('aqualuxe_after_vendor_dashboard_content');
}