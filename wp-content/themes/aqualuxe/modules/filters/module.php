<?php
namespace AquaLuxe\Modules\Filters;

class Module {
    public static function init(): void {
        add_action('woocommerce_before_shop_loop', [__CLASS__, 'render_filters'], 5);
    }

    public static function render_filters(): void {
        if (!class_exists('WooCommerce')) return;
        echo '<form class="mb-4 flex flex-wrap gap-3" method="get">';
        echo '<input class="border rounded px-3 py-2" type="text" name="s" placeholder="' . esc_attr__('Search products', 'aqualuxe') . '" value="' . esc_attr(get_search_query()) . '" />';
        echo '<select class="border rounded px-3 py-2" name="min_price"><option value="">' . esc_html__('Min Price','aqualuxe') . '</option><option>10</option><option>50</option><option>100</option></select>';
        echo '<select class="border rounded px-3 py-2" name="max_price"><option value="">' . esc_html__('Max Price','aqualuxe') . '</option><option>100</option><option>250</option><option>500</option></select>';
        echo '<button class="btn-outline" type="submit">' . esc_html__('Filter','aqualuxe') . '</button>';
        echo '</form>';
    }
}
