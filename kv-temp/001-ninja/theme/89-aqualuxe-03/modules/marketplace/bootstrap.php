<?php
/** Marketplace compatibility module (multivendor-ready) */
namespace AquaLuxe\Modules\Marketplace;
if (!defined('ABSPATH')) { exit; }

function is_multivendor_active(): bool {
    return \class_exists('WeDevs_Dokan') || \class_exists('WC_Vendors') || \defined('WCFM_VERSION');
}

// Body class to reflect marketplace state
\add_filter('body_class', function(array $classes){
    $classes[] = is_multivendor_active() ? 'alx-marketplace-active' : 'alx-marketplace-inactive';
    return $classes;
});

// Simple vendor dashboard shortcode fallback
\add_shortcode('alx_vendor_dashboard', function(){
    if (is_multivendor_active()) {
        return '<div class="alx-vendor-dashboard-link"><a class="button" href="'.esc_url( home_url('/dashboard') ).'">'.esc_html__('Go to Vendor Dashboard','aqualuxe').'</a></div>';
    }
    return '<div class="notice notice-info"><p>'.esc_html__('A multivendor plugin (e.g., Dokan) is not active. Install/activate one to enable vendor features.','aqualuxe').'</p></div>';
});

// Minimal compat: if Dokan is active, expose a filterable area for theme tweaks
if (\class_exists('WeDevs_Dokan')) {
    \add_action('dokan_dashboard_content_inside_before','__return_null');
}
