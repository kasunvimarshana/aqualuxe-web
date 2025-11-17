<?php
/** Enqueue compiled assets via mix-manifest */

add_action('wp_enqueue_scripts', function () {
    // Reuse helpers for manifest resolution
    if (!function_exists('aqualuxe_manifest_map')) return;
    $map = aqualuxe_manifest_map();
    $get = function(string $key) use ($map) { return aqualuxe_manifest_get($key, $map); };

    // Styles
    $css = $get('/css/theme.css');
    wp_enqueue_style('aqualuxe', AQUALUXE_URI . 'assets/dist' . $css, [], null);

    // Scripts order: manifest -> vendor -> theme
    $manifest_js = $get('/js/manifest.js');
    if ($manifest_js && $manifest_js !== '/js/manifest.js') {
        wp_enqueue_script('aqualuxe-manifest', AQUALUXE_URI . 'assets/dist' . $manifest_js, [], null, true);
    }
    $vendor_js = $get('/js/vendor.js');
    // If vendor path equals default and file missing, try nested /js/js/vendor.js
    $v_path = $vendor_js;
    $v_check = $vendor_js;
    $qpos = strpos($v_check, '?'); if ($qpos !== false) $v_check = substr($v_check, 0, $qpos);
    if ($v_check === '/js/vendor.js' && !file_exists(AQUALUXE_PATH . 'assets/dist' . $v_check)) {
        if (file_exists(AQUALUXE_PATH . 'assets/dist/js/js/vendor.js')) {
            $v_path = '/js/js/vendor.js' . (isset($qpos) && $qpos !== false ? substr($vendor_js, $qpos) : '');
        }
    }
    if ($v_path && $v_path !== '/js/vendor.js') {
        wp_enqueue_script('aqualuxe-vendor', AQUALUXE_URI . 'assets/dist' . $v_path, ['aqualuxe-manifest'], null, true);
    }
    $theme_js = $get('/js/theme.js');
    $deps = [];
    if (wp_script_is('aqualuxe-manifest', 'registered') || wp_script_is('aqualuxe-manifest', 'enqueued')) $deps[] = 'aqualuxe-manifest';
    if (wp_script_is('aqualuxe-vendor', 'registered') || wp_script_is('aqualuxe-vendor', 'enqueued')) $deps[] = 'aqualuxe-vendor';
    wp_enqueue_script('aqualuxe', AQUALUXE_URI . 'assets/dist' . $theme_js, $deps, null, true);

    // Defer scripts for better paint by default (can be disabled via filter)
    $perf = get_option('aqlx_perf', []);
    $defer_default = !isset($perf['defer_scripts']) || !empty($perf['defer_scripts']);
    $defer = apply_filters('aqualuxe/scripts_defer', $defer_default);
    if ($defer) {
        if (wp_script_is('aqualuxe-manifest', 'enqueued')) wp_script_add_data('aqualuxe-manifest', 'defer', true);
        if (wp_script_is('aqualuxe-vendor', 'enqueued')) wp_script_add_data('aqualuxe-vendor', 'defer', true);
        if (wp_script_is('aqualuxe', 'enqueued')) wp_script_add_data('aqualuxe', 'defer', true);
    }

    // Dark mode preference + i18n vars
    wp_localize_script('aqualuxe', 'AquaLuxe', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe'),
    ]);
});
