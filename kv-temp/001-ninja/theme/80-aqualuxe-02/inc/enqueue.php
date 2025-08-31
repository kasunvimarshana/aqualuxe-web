<?php
/** Enqueue compiled assets via mix-manifest */

add_action('wp_enqueue_scripts', function () {
    $manifest_file = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $map = [];
    if (file_exists($manifest_file)) {
        $map = json_decode(file_get_contents($manifest_file), true) ?: [];
    }

    // Helper to fetch and sanitize a manifest path
    $get = function(string $key) use ($map) {
        if (!empty($map[$key])) return preg_replace('#/{2,}#', '/', $map[$key]);
        // attempt fuzzy lookup by filename
        foreach ($map as $k => $v) {
            if (str_ends_with($k, basename($key))) {
                return preg_replace('#/{2,}#', '/', $v);
            }
        }
        return $key; // fallback
    };

    // Styles
    $css = $get('/css/theme.css');
    wp_enqueue_style('aqualuxe', AQUALUXE_URI . 'assets/dist' . $css, [], null);

    // Scripts order: manifest -> vendor -> theme
    $manifest_js = $get('/js/manifest.js');
    if ($manifest_js && $manifest_js !== '/js/manifest.js') {
        wp_enqueue_script('aqualuxe-manifest', AQUALUXE_URI . 'assets/dist' . $manifest_js, [], null, true);
    }
    $vendor_js = $get('/js/vendor.js');
    if ($vendor_js && $vendor_js !== '/js/vendor.js') {
        wp_enqueue_script('aqualuxe-vendor', AQUALUXE_URI . 'assets/dist' . $vendor_js, ['aqualuxe-manifest'], null, true);
    }
    $theme_js = $get('/js/theme.js');
    $deps = [];
    if (wp_script_is('aqualuxe-manifest', 'registered') || wp_script_is('aqualuxe-manifest', 'enqueued')) $deps[] = 'aqualuxe-manifest';
    if (wp_script_is('aqualuxe-vendor', 'registered') || wp_script_is('aqualuxe-vendor', 'enqueued')) $deps[] = 'aqualuxe-vendor';
    wp_enqueue_script('aqualuxe', AQUALUXE_URI . 'assets/dist' . $theme_js, $deps, null, true);

    // Dark mode preference + i18n vars
    wp_localize_script('aqualuxe', 'AquaLuxe', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe'),
    ]);
});
