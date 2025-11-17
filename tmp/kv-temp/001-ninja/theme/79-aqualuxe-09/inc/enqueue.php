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

        // Front page enhancements JS (lazy modules for 3D/GSAP/D3)
        if (is_front_page()) {
            $enqueue_fp = false;
            $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode((string) file_get_contents($manifest_path), true);
                $enqueue_fp = is_array($manifest) && isset($manifest['/js/frontpage.js']);
            }
            if (!$enqueue_fp && file_exists(AQUALUXE_DIR . 'assets/dist/js/frontpage.js')) {
                $enqueue_fp = true;
            }
            if ($enqueue_fp) {
                $fp_js = mix('/js/frontpage.js');
                wp_enqueue_script('aqualuxe-frontpage', $fp_js, ['aqualuxe-app'], AQUALUXE_VERSION, true);
            }
        }
    });
}

// Early inline script to apply dark mode to prevent FOUC.
if (function_exists('add_action')) {
    add_action('wp_head', static function (): void {
        echo '<script>(function(){try{var dm=document.cookie.match(/(?:^|; )aqlx_dm=([^;]+)/);if(dm&&dm[1]==="1"){document.documentElement.classList.add("dark");}}catch(e){}})();</script>';
    }, 0);
}

// Block editor styles (editor.css)
if (function_exists('add_action')) {
    add_action('enqueue_block_editor_assets', static function (): void {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        $has_editor = false;
        if (file_exists($manifest_path)) {
            $manifest = json_decode((string) file_get_contents($manifest_path), true);
            $has_editor = is_array($manifest) && isset($manifest['/editor.css']);
        }
        if (!$has_editor && file_exists(AQUALUXE_DIR . 'assets/dist/editor.css')) {
            $has_editor = true;
        }
        if ($has_editor) {
            wp_enqueue_style('aqualuxe-editor', mix('/editor.css'), [], AQUALUXE_VERSION);
        }
    });
}
