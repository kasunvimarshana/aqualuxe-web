<?php

/**
 * AquaLuxe Asset Enqueue
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Enqueue
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets()
    {
        $version = wp_get_theme()->get('Version');

        // Styles
        wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), [], $version);
        // wp_enqueue_style('aqualuxe-main', get_template_directory_uri() . '/assets/css/main.css', [], $version);
        // minifying inline CSS before enqueue
        $main_css = file_get_contents(get_template_directory() . '/assets/css/main.css');
        wp_add_inline_style('aqualuxe-main', AquaLuxe_Minify::minify_css($main_css));


        wp_enqueue_style('aqualuxe-responsive', get_template_directory_uri() . '/assets/css/responsive.css', [], $version);

        if (class_exists('WooCommerce')) {
            wp_enqueue_style('aqualuxe-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', [], $version);
        }

        // Scripts
        wp_enqueue_script('aqualuxe-theme', get_template_directory_uri() . '/assets/js/theme.js', ['jquery'], $version, true);

        if (class_exists('WooCommerce')) {
            wp_enqueue_script('aqualuxe-ajax-cart', get_template_directory_uri() . '/assets/js/ajax-cart.js', ['jquery'], $version, true);
            wp_enqueue_script('aqualuxe-quick-view', get_template_directory_uri() . '/assets/js/quick-view.js', ['jquery'], $version, true);

            wp_localize_script('aqualuxe-quick-view', 'aqualuxe_quickview', [
                'ajax_url' => admin_url('admin-ajax.php')
            ]);
        }
    }
}
