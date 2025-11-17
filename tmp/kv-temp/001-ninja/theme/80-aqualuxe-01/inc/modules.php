<?php
/** Module loader with toggleable config */

function aqualuxe_get_modules_config(): array {
    $default = [
        'multilingual' => true,
        'events' => true,
        'services' => true,
        'wishlist' => true,
    ];
    $file = AQUALUXE_PATH . 'modules.json';
    if (file_exists($file)) {
        $cfg = json_decode(file_get_contents($file), true);
        if (is_array($cfg)) $default = array_merge($default, $cfg);
    }
    return apply_filters('aqualuxe/modules_enabled', $default);
}

add_action('after_setup_theme', function(){
    $enabled = aqualuxe_get_modules_config();
    foreach ($enabled as $slug => $on) {
        if (!$on) continue;
        $bootstrap = AQUALUXE_PATH . 'modules/' . sanitize_key($slug) . '/module.php';
        if (file_exists($bootstrap)) require_once $bootstrap;
    }
});
