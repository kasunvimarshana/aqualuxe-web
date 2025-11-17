<?php
/** Custom theme hooks */

// Action/Filter registry for modules to hook into
class AquaLuxe_Hooks {
    public static function init() {
        add_action('aqualuxe/header', [__CLASS__, 'header']);
        add_action('aqualuxe/footer', [__CLASS__, 'footer']);
    }

    public static function header() {
        get_template_part('templates/parts/header');
    }

    public static function footer() {
        get_template_part('templates/parts/footer');
    }
}
AquaLuxe_Hooks::init();
