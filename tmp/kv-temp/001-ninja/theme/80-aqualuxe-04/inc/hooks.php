<?php
/** Custom theme hooks */

// Action/Filter registry for modules to hook into
class AquaLuxe_Hooks {
    public static function init() {
        add_action('aqualuxe/header', [__CLASS__, 'header']);
        add_action('aqualuxe/footer', [__CLASS__, 'footer']);
    // Ensure quick-view modal is present near the end of the page
    add_action('wp_footer', [__CLASS__, 'quick_view_modal'], 100);
    }

    public static function header() {
        get_template_part('templates/parts/header');
    }

    public static function footer() {
        get_template_part('templates/parts/footer');
    }

    public static function quick_view_modal() {
        // Lightweight static container used by theme JS for product Quick View
        get_template_part('templates/parts/quick-view-modal');
    }
}
AquaLuxe_Hooks::init();

// Convenience hook for modules to add content under product titles in loop
add_action('woocommerce_shop_loop_item_title', function(){
    do_action('aqualuxe/after_product_title');
}, 12);
