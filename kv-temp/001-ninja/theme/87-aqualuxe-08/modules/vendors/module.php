<?php
// Vendors module: detect multivendor plugins and expose an adapter layer.

add_action('plugins_loaded', function(){
    $adapter = null;
    if (class_exists('WeDevs_Dokan')) { $adapter = 'dokan'; }
    elseif (defined('WCFMmp_VERSION')) { $adapter = 'wcfm'; }
    elseif (class_exists('WC_Vendors')) { $adapter = 'wcv'; }
    $adapter = apply_filters('aqualuxe/vendors/adapter', $adapter);
    if (!$adapter) { return; }
    // Map a generic capability for vendor dashboard access
    add_filter('map_meta_cap', function($caps, $cap){
        if ($cap === 'aqlx_vendor_dashboard') {
            $caps = ['read']; // allow logged-in users with read
        }
        return $caps;
    }, 10, 2);

    // Provide a unified shortcode to vendor dashboard link
    $adapterForShortcode = $adapter;
    add_shortcode('aqualuxe_vendor_dashboard', function() use ($adapterForShortcode){
        $url = '#';
        if ($adapterForShortcode === 'dokan' && function_exists('dokan_get_page_url')) { $url = call_user_func('dokan_get_page_url', 'dashboard', 'dokan'); }
        if ($adapterForShortcode === 'wcfm' && function_exists('wcfm_get_page_id')) { $url = get_permalink(call_user_func('wcfm_get_page_id', 'wcfm-membership')); }
        if ($adapterForShortcode === 'wcv') { $url = site_url('/vendor_dashboard'); }
        return '<a class="button" href="' . esc_url($url) . '">' . esc_html__('Vendor Dashboard', 'aqualuxe') . '</a>';
    });
});

