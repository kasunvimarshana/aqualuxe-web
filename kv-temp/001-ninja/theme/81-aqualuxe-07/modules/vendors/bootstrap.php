<?php
/** Vendors module: basic vendor taxonomy for products and services (multivendor readiness) */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_taxonomy('vendor', ['product','service'], [
        'label' => __('Vendors','aqualuxe'),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'vendor']
    ]);
});

add_shortcode('ax_vendor_badge', function($atts){
    $atts = shortcode_atts(['id'=>get_the_ID()], $atts, 'ax_vendor_badge');
    $id = (int) $atts['id']; if (!$id) return '';
    $terms = get_the_terms($id, 'vendor'); if (is_wp_error($terms) || empty($terms)) return '';
    $names = array_map(function($t){ return esc_html($t->name); }, $terms);
    return '<span class="inline-flex items-center gap-1 text-xs bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">'.__('Vendor:','aqualuxe').' '.implode(', ', $names).'</span>';
});
