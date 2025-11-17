<?php
namespace AquaLuxe;

class Assets {
    public static function enqueue(): void {
        $deps = [];
        \wp_enqueue_style('aqualuxe-app', mix('/css/app.css'), [], AQUALUXE_VERSION);
        \wp_enqueue_script('aqualuxe-app', mix('/js/app.js'), $deps, AQUALUXE_VERSION, true);
        if (is_wc_active()) {
            \wp_enqueue_script('aqualuxe-woo', mix('/js/woo.js'), ['jquery'], AQUALUXE_VERSION, true);
        }
    }
}
