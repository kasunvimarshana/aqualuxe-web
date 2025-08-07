<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_enqueue_scripts')) {
    /**
     * Enqueue scripts and styles
     *
     * @since 1.0.0
     */
    function aqualuxe_enqueue_scripts() {
        // Dequeue parent theme styles
        wp_dequeue_style('storefront-style');
        wp_dequeue_style('storefront-icons');
        
        // Enqueue child theme styles
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue custom styles
        wp_enqueue_style(
            'aqualuxe-custom-style',
            get_stylesheet_directory_uri() . '/assets/css/custom.css',
            array('aqualuxe-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce specific styles
        if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            wp_enqueue_style(
                'aqualuxe-woocommerce-style',
                get_stylesheet_directory_uri() . '/assets/css/woocommerce.css',
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue custom scripts
        wp_enqueue_script(
            'aqualuxe-custom-js',
            get_stylesheet_directory_uri() . '/assets/js/custom.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue WooCommerce specific scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce-js',
                get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
                array('jquery', 'aqualuxe-custom-js'),
                AQUALUXE_VERSION,
                true
            );
            
            // Localize script for AJAX
            wp_localize_script('aqualuxe-woocommerce-js', 'aqualuxe_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_nonce'),
            ));
        }
        
        // Enqueue comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}

if (!function_exists('aqualuxe_enqueue_editor_styles')) {
    /**
     * Enqueue editor styles
     *
     * @since 1.0.0
     */
    function aqualuxe_enqueue_editor_styles() {
        add_editor_style(get_stylesheet_directory_uri() . '/assets/css/editor-style.css');
    }
}
add_action('admin_init', 'aqualuxe_enqueue_editor_styles');