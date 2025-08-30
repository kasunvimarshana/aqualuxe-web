<?php
/**
 * AquaLuxe Accessibility Functions
 *
 * Implements accessibility features for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add ARIA landmarks to theme elements
 */
function aqualuxe_add_aria_landmarks() {
    add_filter('aqualuxe_header_attrs', 'aqualuxe_header_aria');
    add_filter('aqualuxe_main_attrs', 'aqualuxe_main_aria');
    add_filter('aqualuxe_footer_attrs', 'aqualuxe_footer_aria');
    add_filter('aqualuxe_nav_attrs', 'aqualuxe_nav_aria');
    add_filter('aqualuxe_sidebar_attrs', 'aqualuxe_sidebar_aria');
}
add_action('after_setup_theme', 'aqualuxe_add_aria_landmarks');

/**
 * Add ARIA attributes to header
 */
function aqualuxe_header_aria($attrs) {
    $attrs['role'] = 'banner';
    $attrs['aria-label'] = esc_attr__('Site header', 'aqualuxe');
    return $attrs;
}

/**
 * Add ARIA attributes to main content
 */
function aqualuxe_main_aria($attrs) {
    $attrs['role'] = 'main';
    $attrs['id'] = 'main-content';
    $attrs['tabindex'] = '-1';
    return $attrs;
}

/**
 * Add ARIA attributes to footer
 */
function aqualuxe_footer_aria($attrs) {
    $attrs['role'] = 'contentinfo';
    $attrs['aria-label'] = esc_attr__('Site footer', 'aqualuxe');
    return $attrs;
}

/**
 * Add ARIA attributes to navigation
 */
function aqualuxe_nav_aria($attrs) {
    $attrs['role'] = 'navigation';
    $attrs['aria-label'] = !empty($attrs['aria-label']) ? $attrs['aria-label'] : esc_attr__('Main navigation', 'aqualuxe');
    return $attrs;
}

/**
 * Add ARIA attributes to sidebar
 */
function aqualuxe_sidebar_aria($attrs) {
    $attrs['role'] = 'complementary';
    $attrs['aria-label'] = !empty($attrs['aria-label']) ? $attrs['aria-label'] : esc_attr__('Sidebar', 'aqualuxe');
    return $attrs;
}

/**
 * Implement skip links for keyboard navigation
 */
function aqualuxe_skip_links() {
    echo '<a class="skip-link screen-reader-text" href="#main-content">' . esc_html__('Skip to content', 'aqualuxe') . '</a>';
}
add_action('aqualuxe_before_header', 'aqualuxe_skip_links', 1);

/**
 * Add keyboard navigation support for dropdown menus
 */
function aqualuxe_keyboard_navigation_js() {
    wp_enqueue_script(
        'aqualuxe-keyboard-navigation',
        get_template_directory_uri() . '/dist/js/keyboard-navigation.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_keyboard_navigation_js');

/**
 * Add screen reader text helper class
 */
function aqualuxe_screen_reader_styles() {
    ?>
    <style>
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
            font-weight: 700;
            height: auto;
            left: 5px;
            line-height: normal;
            padding: 15px 23px 14px;
            text-decoration: none;
            top: 5px;
            width: auto;
            z-index: 100000;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_screen_reader_styles', 1);

/**
 * Ensure proper heading hierarchy
 */
function aqualuxe_fix_heading_hierarchy($title, $id = null) {
    // Get the current template
    $template = get_page_template_slug();
    
    // Default heading level
    $heading_level = 'h1';
    
    // Adjust heading level based on context
    if (is_singular() && !is_front_page() && !is_home()) {
        // Single posts and pages get h1 for title
        $heading_level = 'h1';
    } elseif (is_archive() || is_search() || is_home()) {
        // Archive pages use h2 for post titles
        $heading_level = 'h2';
    } elseif (is_front_page()) {
        // Front page may have multiple h1s for sections
        $heading_level = 'h1';
    }
    
    // Allow filtering of heading level
    $heading_level = apply_filters('aqualuxe_heading_level', $heading_level, $template, $id);
    
    // Return formatted heading
    return sprintf('<%1$s class="entry-title">%2$s</%1$s>', $heading_level, $title);
}

/**
 * Add focus styles for interactive elements
 */
function aqualuxe_focus_styles() {
    ?>
    <style>
        a:focus,
        button:focus,
        input:focus,
        textarea:focus,
        select:focus,
        [tabindex]:focus {
            outline: 2px solid #4a90e2;
            outline-offset: 2px;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_focus_styles', 2);

/**
 * Add aria-current to current menu items
 */
function aqualuxe_nav_menu_aria_current($atts, $item) {
    if (isset($item->current) && $item->current) {
        $atts['aria-current'] = 'page';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_aria_current', 10, 2);

/**
 * Add form field labels and ARIA attributes
 */
function aqualuxe_comment_form_accessibility($fields) {
    // Add proper labeling to comment form fields
    $fields['author'] = str_replace('<input', '<input aria-required="true" ', $fields['author']);
    $fields['email'] = str_replace('<input', '<input aria-required="true" ', $fields['email']);
    $fields['url'] = str_replace('<input', '<input aria-required="false" ', $fields['url']);
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_accessibility');

/**
 * Add ARIA attributes to comment textarea
 */
function aqualuxe_comment_form_textarea_accessibility($field) {
    return str_replace('<textarea', '<textarea aria-required="true" ', $field);
}
add_filter('comment_form_field_comment', 'aqualuxe_comment_form_textarea_accessibility');