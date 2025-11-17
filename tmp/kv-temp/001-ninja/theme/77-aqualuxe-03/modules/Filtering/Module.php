<?php
namespace AquaLuxe\Modules\Filtering;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        if (!function_exists('is_shop')) return;
        add_action('woocommerce_before_main_content', [__CLASS__, 'filters'], 5);
    }

    public static function filters(): void {
        echo '<form method="get" class="aqlx-filters mb-6 grid grid-cols-2 md:grid-cols-4 gap-3">';
        echo '<input type="text" name="s" placeholder="' . esc_attr__('Search', 'aqualuxe') . '" class="border rounded px-3 py-2" value="' . esc_attr(get_search_query()) . '" />';
        echo '<select name="min_price" class="border rounded px-2 py-2"><option value="">' . esc_html__('Min Price','aqualuxe') . '</option>';
        foreach ([10,50,100,250,500] as $p) echo '<option ' . selected(isset($_GET['min_price']) && (int)$_GET['min_price']===$p, true, false) . ' value="' . (int)$p . '">' . (int)$p . '</option>';
        echo '</select>';
        echo '<select name="max_price" class="border rounded px-2 py-2"><option value="">' . esc_html__('Max Price','aqualuxe') . '</option>';
        foreach ([50,100,250,500,1000] as $p) echo '<option ' . selected(isset($_GET['max_price']) && (int)$_GET['max_price']===$p, true, false) . ' value="' . (int)$p . '">' . (int)$p . '</option>';
        echo '</select>';
        echo '<button class="px-4 py-2 bg-sky-600 text-white rounded">' . esc_html__('Filter','aqualuxe') . '</button>';
        echo '</form>';
    }
}
