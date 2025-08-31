<?php
/** AquaLuxe Diagnostics (admin) */

if (!is_admin()) return;

add_action('admin_menu', function(){
    add_theme_page(__('AquaLuxe Diagnostics','aqualuxe'), __('AquaLuxe Diagnostics','aqualuxe'), 'manage_options', 'aqualuxe-diagnostics', 'aqlx_diag_page');
});

function aqlx_diag_page(){
    if (!current_user_can('manage_options')) return;
    $wpv = get_bloginfo('version');
    $phpv = PHP_VERSION;
    $theme_v = defined('AQUALUXE_VERSION') ? AQUALUXE_VERSION : 'n/a';
    $woo = class_exists('WooCommerce') ? 'yes' : 'no';
    $modules = function_exists('aqualuxe_get_modules_config') ? aqualuxe_get_modules_config() : [];

    // Read manifest (via helpers)
    $manifest = function_exists('aqualuxe_manifest_map') ? aqualuxe_manifest_map() : [];

    echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Diagnostics','aqualuxe') . '</h1>';
    if (!empty($_GET['aqlx_notice']) && $_GET['aqlx_notice'] === 'orphans-deleted') {
        $cnt = isset($_GET['count']) ? (int) $_GET['count'] : 0;
        echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(esc_html__('%d orphaned files deleted.','aqualuxe'), $cnt) . '</p></div>';
    }
    echo '<h2>' . esc_html__('Environment','aqualuxe') . '</h2>';
    echo '<table class="widefat striped"><tbody>';
    echo '<tr><td>WordPress</td><td>' . esc_html($wpv) . '</td></tr>';
    echo '<tr><td>PHP</td><td>' . esc_html($phpv) . '</td></tr>';
    echo '<tr><td>Theme version</td><td>' . esc_html($theme_v) . '</td></tr>';
    echo '<tr><td>WooCommerce active</td><td>' . esc_html($woo) . '</td></tr>';
    // Performance defer
    $perf = get_option('aqlx_perf', []);
    $defer_on = (!isset($perf['defer_scripts']) || !empty($perf['defer_scripts'])) ? 'yes' : 'no';
    $defer_on = apply_filters('aqualuxe/scripts_defer', $defer_on === 'yes') ? 'yes' : 'no';
    echo '<tr><td>Defer scripts</td><td>' . esc_html($defer_on) . '</td></tr>';
    // CSP preview (only default; actual header may be filtered elsewhere)
    $csp = function_exists('aqualuxe_default_csp') ? aqualuxe_default_csp() : '';
    $csp = apply_filters('aqualuxe/security_csp', $csp);
    echo '<tr><td>Content-Security-Policy</td><td><code>' . esc_html($csp) . '</code></td></tr>';
    echo '</tbody></table>';

    echo '<h2 style="margin-top:1em;">' . esc_html__('Modules','aqualuxe') . '</h2>';
    echo '<table class="widefat striped"><thead><tr><th>Slug</th><th>Enabled</th></tr></thead><tbody>';
    foreach ($modules as $slug=>$on){
        echo '<tr><td>' . esc_html($slug) . '</td><td>' . ($on ? 'yes' : 'no') . '</td></tr>';
    }
    echo '</tbody></table>';

    // Health checks
    $checks = [];
    // Manifest present
    $manifest = function_exists('aqualuxe_manifest_map') ? aqualuxe_manifest_map() : [];
    $checks['manifest_found'] = !empty($manifest);
    // CSS/JS resolve and exist
    $css = function_exists('aqualuxe_manifest_get') ? aqualuxe_manifest_get('/css/theme.css', $manifest) : '';
    $js = function_exists('aqualuxe_manifest_get') ? aqualuxe_manifest_get('/js/theme.js', $manifest) : '';
    $strip_q = function($p){ $qpos = strpos($p, '?'); return $qpos !== false ? substr($p, 0, $qpos) : $p; };
    $checks['css_exists'] = $css && file_exists(AQUALUXE_PATH . 'assets/dist' . $strip_q($css));
    $checks['js_exists'] = $js && file_exists(AQUALUXE_PATH . 'assets/dist' . $strip_q($js));
    // CSP non-empty
    $csp = function_exists('aqualuxe_default_csp') ? aqualuxe_default_csp() : '';
    $csp = apply_filters('aqualuxe/security_csp', $csp);
    $checks['csp_defined'] = is_string($csp) && $csp !== '';
    // Defer status
    $perf = get_option('aqlx_perf', []);
    $defer_default = !isset($perf['defer_scripts']) || !empty($perf['defer_scripts']);
    $checks['defer_enabled'] = (bool) apply_filters('aqualuxe/scripts_defer', $defer_default);

    echo '<h2 style="margin-top:1em;">' . esc_html__('Health checks','aqualuxe') . '</h2>';
    echo '<table class="widefat striped"><thead><tr><th>Check</th><th>Status</th></tr></thead><tbody>';
    $render = function($label, $ok){ echo '<tr><td>' . esc_html($label) . '</td><td>' . ($ok ? '<span style="color:#15803d">PASS</span>' : '<span style="color:#b91c1c">FAIL</span>') . '</td></tr>'; };
    $render(__('Manifest found','aqualuxe'), $checks['manifest_found']);
    $render(__('Theme CSS present','aqualuxe'), $checks['css_exists']);
    $render(__('Theme JS present','aqualuxe'), $checks['js_exists']);
    $render(__('CSP configured','aqualuxe'), $checks['csp_defined']);
    $render(__('Defer scripts active','aqualuxe'), $checks['defer_enabled']);
    echo '</tbody></table>';

    echo '<h2 style="margin-top:1em;">' . esc_html__('Assets (mix-manifest)','aqualuxe') . '</h2>';
    echo '<p><a class="button" href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqlx_diag_export'), 'aqlx_diag_export')) . '">' . esc_html__('Export diagnostics (JSON)','aqualuxe') . '</a></p>';
    if (empty($manifest)) {
        echo '<div class="notice notice-warning"><p>' . esc_html__('No manifest found or manifest is empty at assets/dist/mix-manifest.json. Run the build to generate hashed assets (npm run build).','aqualuxe') . '</p></div>';
    } else {
        echo '<table class="widefat striped"><thead><tr><th>Key</th><th>Value</th><th>Sanitized</th><th>Exists</th><th>URL</th></tr></thead><tbody>';
        foreach ($manifest as $k=>$v){
            $san = function_exists('aqualuxe_sanitize_asset_path') ? aqualuxe_sanitize_asset_path($v) : $v;
            $url = AQUALUXE_URI . 'assets/dist' . $san;
            $san_noq = (function($p){ $qpos = strpos($p, '?'); return $qpos !== false ? substr($p, 0, $qpos) : $p; })($san);
            $exists = file_exists(AQUALUXE_PATH . 'assets/dist' . $san_noq) ? 'yes' : 'no';
            echo '<tr><td><code>' . esc_html($k) . '</code></td><td><code>' . esc_html($v) . '</code></td><td><code>' . esc_html($san) . '</code></td><td>' . esc_html($exists) . '</td><td><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></td></tr>';
        }
        echo '</tbody></table>';
    }

    // Assets/dist listing
    $human = function($bytes){
        $units = ['B','KB','MB','GB']; $i = 0; $b = max(0, (float)$bytes);
        while ($b >= 1024 && $i < count($units)-1) { $b /= 1024; $i++; }
        return number_format_i18n($b, $i ? 1 : 0) . ' ' . $units[$i];
    };
    $roots = [
        AQUALUXE_PATH . 'assets/dist/css' => AQUALUXE_URI . 'assets/dist/css',
        AQUALUXE_PATH . 'assets/dist/js' => AQUALUXE_URI . 'assets/dist/js',
    ];
    echo '<h2 style="margin-top:1em;">' . esc_html__('Assets (dist listing)','aqualuxe') . '</h2>';
    echo '<table class="widefat striped"><thead><tr><th>File</th><th>Size</th><th>Modified</th><th>URL</th></tr></thead><tbody>';
    foreach ($roots as $disk=>$uri){
        if (!is_dir($disk)) continue;
        $files = @scandir($disk) ?: [];
        foreach ($files as $f){
            if ($f === '.' || $f === '..') continue;
            $path = $disk . '/' . $f;
            if (!is_file($path)) continue;
            $size = @filesize($path);
            $mtime = @filemtime($path);
            $url = $uri . '/' . rawurlencode($f);
            echo '<tr><td><code>' . esc_html(str_replace(AQUALUXE_PATH, '', $path)) . '</code></td><td>' . esc_html($human($size)) . '</td><td>' . esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $mtime)) . '</td><td><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></td></tr>';
        }
    }
    echo '</tbody></table>';

    // Orphaned files (present in dist but not referenced by manifest values)
    $manifest_vals = [];
    if (is_array($manifest)) {
        foreach ($manifest as $k=>$v) {
            $san = function_exists('aqualuxe_sanitize_asset_path') ? aqualuxe_sanitize_asset_path($v) : $v;
            $manifest_vals[] = 'assets/dist' . $san; // relative to theme root
        }
    }
    $dist_files = [];
    foreach (array_keys($roots) as $disk) {
        if (!is_dir($disk)) continue;
        $files = @scandir($disk) ?: [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $path = $disk . '/' . $f;
            if (!is_file($path)) continue;
            $dist_files[] = str_replace(AQUALUXE_PATH, '', $path);
        }
    }
    $orphans = array_values(array_diff($dist_files, $manifest_vals));
    echo '<h2 style="margin-top:1em;">' . esc_html__('Assets (orphans)','aqualuxe') . '</h2>';
    if (empty($orphans)) {
        echo '<p>' . esc_html__('No orphaned files detected.','aqualuxe') . '</p>';
    } else {
        echo '<table class="widefat striped"><thead><tr><th>File</th><th>Size</th><th>Modified</th></tr></thead><tbody>';
        foreach ($orphans as $rel) {
            $full = AQUALUXE_PATH . $rel;
            $size = @filesize($full);
            $mtime = @filemtime($full);
            echo '<tr><td><code>' . esc_html($rel) . '</code></td><td>' . esc_html($human($size)) . '</td><td>' . esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $mtime)) . '</td></tr>';
        }
        echo '</tbody></table>';
        echo '<p><a class="button button-secondary" href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqlx_delete_orphans'), 'aqlx_delete_orphans')) . '" onclick="return confirm(\'' . esc_js(__('Delete all orphaned files?', 'aqualuxe')) . '\')">' . esc_html__('Delete orphans','aqualuxe') . '</a></p>';
    }

    echo '</div>';
}

// Handle deleting orphaned dist assets
add_action('admin_post_aqlx_delete_orphans', function(){
    if (!current_user_can('manage_options')) wp_die(__('Forbidden','aqualuxe'));
    check_admin_referer('aqlx_delete_orphans');

    $deleted = 0;
    // Recompute orphans (same logic as page)
    $manifest = function_exists('aqualuxe_manifest_map') ? aqualuxe_manifest_map() : [];
    $manifest_vals = [];
    if (is_array($manifest)) {
        foreach ($manifest as $k=>$v) {
            $san = function_exists('aqualuxe_sanitize_asset_path') ? aqualuxe_sanitize_asset_path($v) : $v;
            $manifest_vals[] = 'assets/dist' . $san;
        }
    }
    $roots = [
        AQUALUXE_PATH . 'assets/dist/css',
        AQUALUXE_PATH . 'assets/dist/js',
    ];
    $dist_files = [];
    foreach ($roots as $disk) {
        if (!is_dir($disk)) continue;
        $files = @scandir($disk) ?: [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $path = $disk . '/' . $f;
            if (!is_file($path)) continue;
            $dist_files[] = str_replace(AQUALUXE_PATH, '', $path);
        }
    }
    $orphans = array_values(array_diff($dist_files, $manifest_vals));
    foreach ($orphans as $rel) {
        $full = AQUALUXE_PATH . $rel;
        // Safety: ensure we only delete within assets/dist/css or js
        if (strpos($full, AQUALUXE_PATH . 'assets/dist/css') === 0 || strpos($full, AQUALUXE_PATH . 'assets/dist/js') === 0) {
            if (is_file($full) && @unlink($full)) $deleted++;
        }
    }
    wp_safe_redirect(admin_url('themes.php?page=aqualuxe-diagnostics&aqlx_notice=orphans-deleted&count=' . (int)$deleted));
    exit;
});

// Admin-post handler to export a JSON snapshot of environment and manifest
add_action('admin_post_aqlx_diag_export', function(){
    if (!current_user_can('manage_options')) wp_die(__('Forbidden','aqualuxe'));
    check_admin_referer('aqlx_diag_export');
    $data = [
        'wp_version' => get_bloginfo('version'),
        'php_version' => PHP_VERSION,
        'theme_version' => defined('AQUALUXE_VERSION') ? AQUALUXE_VERSION : 'n/a',
        'woocommerce_active' => class_exists('WooCommerce'),
        'modules' => function_exists('aqualuxe_get_modules_config') ? aqualuxe_get_modules_config() : [],
        'manifest' => [],
    ];
    $manifest = function_exists('aqualuxe_manifest_map') ? aqualuxe_manifest_map() : [];
    if (is_array($manifest)) {
        foreach ($manifest as $k=>$v) {
            $san = function_exists('aqualuxe_sanitize_asset_path') ? aqualuxe_sanitize_asset_path($v) : $v;
            $san_noq = (function($p){ $qpos = strpos($p, '?'); return $qpos !== false ? substr($p, 0, $qpos) : $p; })($san);
            $data['manifest'][] = [
                'key' => $k,
                'value' => $v,
                'sanitized' => $san,
                'exists' => file_exists(AQUALUXE_PATH . 'assets/dist' . $san_noq),
                'url' => AQUALUXE_URI . 'assets/dist' . $san,
            ];
        }
    }
    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=aqualuxe-diagnostics.json');
    echo wp_json_encode($data, JSON_PRETTY_PRINT);
    exit;
});
