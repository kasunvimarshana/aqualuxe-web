<?php
/**
 * Accessibility features for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add accessibility attributes to navigation
 */
function aqualuxe_nav_accessibility($items, $args) {
    if ($args->theme_location == 'primary') {
        // Add accessibility attributes to menu items
        $items = str_replace('<a', '<a aria-haspopup="true"', $items);
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_nav_accessibility', 10, 2);

/**
 * Add accessibility attributes to search form
 */
function aqualuxe_search_form_accessibility($form) {
    // Add accessibility attributes to search form
    $form = str_replace('<input type="search"', '<input type="search" aria-label="' . esc_attr__('Search for:', 'aqualuxe') . '"', $form);
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form_accessibility');

/**
 * Add accessibility attributes to product images
 */
function aqualuxe_product_image_accessibility($html, $attachment_id, $attachment) {
    // Add alt text if not present
    if (strpos($html, 'alt=') === false) {
        $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        if (!$alt) {
            $alt = get_the_title($attachment_id);
        }
        $html = str_replace('<img', '<img alt="' . esc_attr($alt) . '"', $html);
    }
    
    // Add loading attribute for lazy loading
    if (strpos($html, 'loading=') === false) {
        $html = str_replace('<img', '<img loading="lazy"', $html);
    }
    
    return $html;
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'aqualuxe_product_image_accessibility', 10, 3);

/**
 * Add accessibility attributes to form fields
 */
function aqualuxe_form_field_accessibility($field, $key, $args, $value) {
    // Add aria-describedby for description
    if (!empty($args['description'])) {
        $description_id = $args['id'] . '-description';
        $field = str_replace('<input', '<input aria-describedby="' . esc_attr($description_id) . '"', $field);
        $field = str_replace('</p>', '<p id="' . esc_attr($description_id) . '" class="description">' . esc_html($args['description']) . '</p></p>', $field);
    }
    
    return $field;
}
add_filter('woocommerce_form_field', 'aqualuxe_form_field_accessibility', 10, 4);

/**
 * Add skip link to content
 */
function aqualuxe_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__('Skip to content', 'aqualuxe') . '</a>';
}
add_action('wp_body_open', 'aqualuxe_skip_link');

/**
 * Add focus styles for keyboard navigation
 */
function aqualuxe_focus_styles() {
    echo '<style>
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 2px solid #0073aa;
            outline-offset: 2px;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #f1f1f1;
            color: #0073aa;
            padding: 8px;
            text-decoration: none;
            transition: top 0.3s ease;
        }
        
        .skip-link:focus {
            top: 6px;
            z-index: 100000;
        }
    </style>';
}
add_action('wp_head', 'aqualuxe_focus_styles');