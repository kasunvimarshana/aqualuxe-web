<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Assets {
    public static function boot(): void {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_frontend']);
        add_action('enqueue_block_editor_assets', [__CLASS__, 'enqueue_editor']);
    }

    public static function enqueue_frontend(): void {
        $theme = wp_get_theme();
        $css = aqualuxe_mix('css/app.css');
        $js  = aqualuxe_mix('js/app.js');
        wp_enqueue_style('aqualuxe-app', $css, [], $theme->get('Version'));
    // Load theme stylesheet last for safe, persistent overrides that won't be overwritten by builds.
    wp_enqueue_style('aqualuxe-overrides', get_stylesheet_uri(), ['aqualuxe-app'], $theme->get('Version'));
        wp_enqueue_script('aqualuxe-app', $js, ['jquery'], $theme->get('Version'), true);
        wp_localize_script('aqualuxe-app', 'AquaLuxe', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe-nonce'),
            'i18n'    => [
                'added_to_cart' => __('Added to cart', 'aqualuxe'),
            ],
        ]);
    }

    public static function enqueue_editor(): void {
        wp_enqueue_style('aqualuxe-editor', aqualuxe_mix('css/editor.css'), [], wp_get_theme()->get('Version'));
    }
}
