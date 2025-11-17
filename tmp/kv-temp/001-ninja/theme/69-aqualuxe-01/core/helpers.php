<?php
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}
function aqualuxe_get_asset($path) {
    $manifest = get_template_directory() . '/mix-manifest.json';
    if (file_exists($manifest)) {
        $map = json_decode(file_get_contents($manifest), true);
        if (isset($map["/assets/dist/{$path}"])) {
            return get_template_directory_uri() . $map["/assets/dist/{$path}"];
        }
    }
    return get_template_directory_uri() . "/assets/dist/{$path}";
}
