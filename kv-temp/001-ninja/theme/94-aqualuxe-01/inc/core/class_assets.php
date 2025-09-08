<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Assets {
    public static function init(): void {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_public']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin']);
    }

    public static function enqueue_public(): void {
        $css = asset_uri('css/app.css');
        $js  = asset_uri('js/app.js');

            \wp_enqueue_style('aqualuxe-app', $css, [], AQUALUXE_VERSION, 'all');
            if (function_exists('wp_style_add_data')) { \wp_style_add_data('aqualuxe-app', 'rtl', 'replace'); }

        \wp_enqueue_script('aqualuxe-app', $js, [], AQUALUXE_VERSION, true);
            \wp_localize_script('aqualuxe-app', 'AquaLuxe', [
                'ajaxUrl' => function_exists('admin_url') ? \admin_url('admin-ajax.php') : '/wp-admin/admin-ajax.php',
                'nonce' => function_exists('wp_create_nonce') ? \wp_create_nonce('aqualuxe_nonce') : '',
            'i18n' => [
                'loading' => __('Loading...', AQUALUXE_TEXT_DOMAIN),
            ],
        ]);
    }

    public static function enqueue_admin(): void {
        // Keep admin light; can add module-specific assets.
    }
}
