<?php
/** Product filtering module */
if (!defined('ABSPATH')) { exit; }

// Shortcode renders a basic filter form (category)
add_shortcode('ax_product_filters', function(){
    if (!class_exists('WooCommerce')) return '';
    $terms = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]);
    $opts = '<option value="">' . esc_html__('All Categories','aqualuxe') . '</option>';
    foreach ($terms as $t) { $sel = selected(get_query_var('product_cat'), $t->slug, false); $opts .= '<option value="' . esc_attr($t->slug) . '" ' . $sel . '>' . esc_html($t->name) . '</option>'; }
    $action = esc_url( get_post_type_archive_link('product') );
    return '<form method="get" action="'.$action.'" class="flex gap-2 items-end"><label class="grid"><span class="text-sm">' . esc_html__('Category','aqualuxe') . '</span><select name="product_cat" class="border p-2">'.$opts.'</select></label><button class="ax-btn">' . esc_html__('Filter','aqualuxe') . '</button></form>';
});
