<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

/**
 * Resolve a versioned asset path from mix-manifest.json
 */
function mix(string $path): string
{
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    $asset_path = '/assets/dist' . $path;
    if (file_exists($manifest_path)) {
        $manifest = json_decode((string) file_get_contents($manifest_path), true);
        if (is_array($manifest) && isset($manifest[$path])) {
            return AQUALUXE_URI . 'assets/dist' . $manifest[$path];
        }
    }
    return AQUALUXE_URI . ltrim($asset_path, '/');
}

if (function_exists('add_action')) {
    add_action('wp_enqueue_scripts', static function (): void {
        // Main CSS & JS (built via webpack.mix)
        $css = mix('/css/app.css');
        $js  = mix('/js/app.js');

        wp_enqueue_style('aqualuxe-app', $css, [], AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-app', $js, ['wp-i18n'], AQUALUXE_VERSION, true);

        // Dark mode preference.
        wp_localize_script('aqualuxe-app', 'AQUALUXE_SETTINGS', [
            'darkMode' => isset($_COOKIE['aqlx_dm']) ? (bool) intval($_COOKIE['aqlx_dm']) : false,
            'ajaxUrl'  => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqlx_nonce'),
        ]);
    });
}

// Early inline script to apply dark mode to prevent FOUC.
if (function_exists('add_action')) {
    add_action('wp_head', static function (): void {
        echo '<script>(function(){try{var dm=document.cookie.match(/(?:^|; )aqlx_dm=([^;]+)/);if(dm&&dm[1]==="1"){document.documentElement.classList.add("dark");}}catch(e){}})();</script>';
    }, 0);
}
