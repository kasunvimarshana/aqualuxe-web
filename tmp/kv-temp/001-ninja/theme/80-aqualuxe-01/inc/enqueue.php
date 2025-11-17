<?php
/** Enqueue compiled assets via mix-manifest */

add_action('wp_enqueue_scripts', function () {
    $manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $css = '/css/theme.css';
    $js = '/js/theme.js';

    if (file_exists($manifest)) {
        $map = json_decode(file_get_contents($manifest), true);
        if (isset($map['/css/theme.css'])) { $css = $map['/css/theme.css']; }
        if (isset($map['/js/theme.js'])) { $js = $map['/js/theme.js']; }
    }

    wp_enqueue_style('aqualuxe', AQUALUXE_URI . 'assets/dist' . $css, [], null);
    wp_enqueue_script('aqualuxe', AQUALUXE_URI . 'assets/dist' . $js, [], null, true);

    // Dark mode preference + i18n vars
    wp_localize_script('aqualuxe', 'AquaLuxe', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe'),
    ]);
});
