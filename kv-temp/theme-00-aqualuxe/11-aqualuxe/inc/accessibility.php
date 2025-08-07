<?php
/**
 * Accessibility Enhancements
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add accessibility attributes to navigation menus
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4);
if (!function_exists('aqualuxe_nav_menu_link_attributes')) {
    /**
     * Add accessibility attributes to navigation menu links
     *
     * @since 1.0.0
     */
    function aqualuxe_nav_menu_link_attributes($atts, $item, $args, $depth) {
        // Add aria-current for current menu item
        if ($item->current) {
            $atts['aria-current'] = 'page';
        }
        
        // Add aria-haspopup for menu items with children
        if (in_array('menu-item-has-children', $item->classes)) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }
        
        return $atts;
    }
}

// Add accessibility attributes to search form
add_filter('get_search_form', 'aqualuxe_search_form_accessibility');
if (!function_exists('aqualuxe_search_form_accessibility')) {
    /**
     * Add accessibility attributes to search form
     *
     * @since 1.0.0
     */
    function aqualuxe_search_form_accessibility($form) {
        // Add aria-label to search form
        $form = str_replace(
            '<form role="search"',
            '<form role="search" aria-label="' . esc_attr__('Search Form', 'aqualuxe') . '"',
            $form
        );
        
        // Add aria-label to search input
        $form = str_replace(
            '<input type="search"',
            '<input type="search" aria-label="' . esc_attr__('Search', 'aqualuxe') . '"',
            $form
        );
        
        return $form;
    }
}

// Add accessibility attributes to WooCommerce product images
add_filter('woocommerce_single_product_image_thumbnail_html', 'aqualuxe_product_image_accessibility', 10, 2);
if (!function_exists('aqualuxe_product_image_accessibility')) {
    /**
     * Add accessibility attributes to WooCommerce product images
     *
     * @since 1.0.0
     */
    function aqualuxe_product_image_accessibility($html, $attachment_id) {
        // Get alt text
        $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        
        // If no alt text, use product title
        if (empty($alt)) {
            $alt = get_the_title();
        }
        
        // Add alt attribute
        $html = str_replace('<img', '<img alt="' . esc_attr($alt) . '"', $html);
        
        return $html;
    }
}

// Add accessibility attributes to comment form
add_filter('comment_form_defaults', 'aqualuxe_comment_form_accessibility');
if (!function_exists('aqualuxe_comment_form_accessibility')) {
    /**
     * Add accessibility attributes to comment form
     *
     * @since 1.0.0
     */
    function aqualuxe_comment_form_accessibility($defaults) {
        // Add aria-label to comment field
        $defaults['comment_field'] = str_replace(
            '<textarea',
            '<textarea aria-label="' . esc_attr__('Your Comment', 'aqualuxe') . '"',
            $defaults['comment_field']
        );
        
        // Add aria-label to author field
        if (isset($defaults['fields']['author'])) {
            $defaults['fields']['author'] = str_replace(
                '<input',
                '<input aria-label="' . esc_attr__('Name', 'aqualuxe') . '"',
                $defaults['fields']['author']
            );
        }
        
        // Add aria-label to email field
        if (isset($defaults['fields']['email'])) {
            $defaults['fields']['email'] = str_replace(
                '<input',
                '<input aria-label="' . esc_attr__('Email', 'aqualuxe') . '"',
                $defaults['fields']['email']
            );
        }
        
        // Add aria-label to url field
        if (isset($defaults['fields']['url'])) {
            $defaults['fields']['url'] = str_replace(
                '<input',
                '<input aria-label="' . esc_attr__('Website', 'aqualuxe') . '"',
                $defaults['fields']['url']
            );
        }
        
        return $defaults;
    }
}

// Add skip links for keyboard navigation
add_action('wp_body_open', 'aqualuxe_skip_links');
if (!function_exists('aqualuxe_skip_links')) {
    /**
     * Add skip links for keyboard navigation
     *
     * @since 1.0.0
     */
    function aqualuxe_skip_links() {
        ?>
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to primary content', 'aqualuxe'); ?></a>
        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <a class="skip-link screen-reader-text" href="#secondary"><?php esc_html_e('Skip to sidebar', 'aqualuxe'); ?></a>
        <?php endif;
    }
}

// Add accessibility CSS
add_action('wp_enqueue_scripts', 'aqualuxe_accessibility_styles');
if (!function_exists('aqualuxe_accessibility_styles')) {
    /**
     * Add accessibility CSS
     *
     * @since 1.0.0
     */
    function aqualuxe_accessibility_styles() {
        wp_add_inline_style('aqualuxe-style', '
            .screen-reader-text {
                border: 0;
                clip: rect(1px, 1px, 1px, 1px);
                clip-path: inset(50%);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute !important;
                width: 1px;
                word-wrap: normal !important;
            }
            
            .screen-reader-text:focus {
                background-color: #f1f1f1;
                border-radius: 3px;
                box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
                clip: auto !important;
                clip-path: none;
                color: #21759b;
                display: block;
                font-size: 14px;
                font-size: 0.875rem;
                font-weight: bold;
                height: auto;
                left: 5px;
                line-height: normal;
                padding: 15px 23px 14px;
                text-decoration: none;
                top: 5px;
                width: auto;
                z-index: 100000;
            }
        ');
    }
}

// Ensure proper heading structure
add_filter('the_content', 'aqualuxe_heading_structure');
if (!function_exists('aqualuxe_heading_structure')) {
    /**
     * Ensure proper heading structure in content
     *
     * @since 1.0.0
     */
    function aqualuxe_heading_structure($content) {
        // This is a placeholder for more complex heading structure adjustments
        // In a real implementation, you might want to adjust heading levels
        // based on the context of the page
        
        return $content;
    }
}