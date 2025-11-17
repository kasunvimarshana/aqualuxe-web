<?php
/**
 * Enqueue Scripts and Styles - Luxury Ornamental Fish Theme
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
        
        // Enqueue Google Fonts for luxury typography
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
            array(),
            null
        );
        
        // Enqueue child theme styles
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array('aqualuxe-google-fonts'),
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
        
        // Enqueue product components fixes (loaded last to override all other styles)
        wp_enqueue_style(
            'aqualuxe-product-fixes',
            get_stylesheet_directory_uri() . '/assets/css/product-components-fixes.css',
            array('aqualuxe-style', 'aqualuxe-woocommerce-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue Magnific Popup for lightbox
        wp_enqueue_script(
            'magnific-popup',
            'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',
            array('jquery'),
            '1.1.0',
            true
        );
        
        wp_enqueue_style(
            'magnific-popup',
            'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css',
            array(),
            '1.1.0'
        );
        
        // Enqueue Zoom for product images
        wp_enqueue_script(
            'zoom',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js',
            array('jquery'),
            '1.7.21',
            true
        );
        
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
                array('jquery', 'magnific-popup', 'zoom', 'wc-add-to-cart-variation'),
                AQUALUXE_VERSION,
                true
            );
            
            // Localize script for AJAX
            wp_localize_script('aqualuxe-woocommerce-js', 'aqualuxe_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_nonce'),
                'quick_view_enabled' => get_theme_mod('aqualuxe_quick_view', true) ? '1' : '0',
                'i18n' => array(
                    'added_to_cart_success' => __('Product added to cart successfully!', 'aqualuxe'),
                    'added_to_cart_error' => __('Could not add product to cart.', 'aqualuxe'),
                    'quick_view_error' => __('Error loading quick view.', 'aqualuxe')
                )
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
        // Enqueue Google Fonts for editor
        add_editor_style('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        add_editor_style(get_stylesheet_directory_uri() . '/assets/css/editor-style.css');
    }
}
add_action('admin_init', 'aqualuxe_enqueue_editor_styles');