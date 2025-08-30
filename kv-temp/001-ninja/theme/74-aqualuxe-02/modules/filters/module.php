<?php
namespace AquaLuxe\Modules\Filters;

\add_shortcode('aqualuxe_filters', function(){
    if (!\class_exists('WooCommerce')) return '';
    $cats = \get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
    \ob_start();
    echo '<form method="get" class="flex flex-wrap gap-3 items-end mb-6">';
    echo '<div><label class="text-sm">'.\esc_html__('Category','aqualuxe').'<select name="product_cat" class="border rounded px-2 py-1"><option value="">'.\esc_html__('All','aqualuxe').'</option>';
    $sel = \sanitize_text_field($_GET['product_cat'] ?? '');
    foreach ($cats as $c) { printf('<option value="%1$s" %3$s>%2$s</option>', \esc_attr($c->slug), \esc_html($c->name), selected($sel, $c->slug, false)); }
    echo '</select></label></div>';
    echo '<div><label class="text-sm">'.\esc_html__('Min Price','aqualuxe').'<input class="border rounded px-2 py-1 w-28" type="number" name="min_price" value="'.\esc_attr($_GET['min_price'] ?? '').'" /></label></div>';
    echo '<div><label class="text-sm">'.\esc_html__('Max Price','aqualuxe').'<input class="border rounded px-2 py-1 w-28" type="number" name="max_price" value="'.\esc_attr($_GET['max_price'] ?? '').'" /></label></div>';
    echo '<button class="btn-primary">'.\esc_html__('Filter','aqualuxe').'</button>';
    echo '</form>';
    return \ob_get_clean();
});
