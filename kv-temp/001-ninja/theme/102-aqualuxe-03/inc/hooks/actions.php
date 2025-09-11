<?php
/**
 * Action Hooks
 *
 * Custom action hooks for this theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Google Fonts
 */
function aqualuxe_fonts() {
    $fonts_url = aqualuxe_fonts_url();
    if ($fonts_url) {
        wp_enqueue_style('aqualuxe-fonts', $fonts_url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_fonts');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add class for single posts/pages
    if (is_singular()) {
        $classes[] = 'singular';
    }
    
    // Add class for WooCommerce pages
    if (aqualuxe_is_woocommerce_page()) {
        $classes[] = 'woocommerce-page';
    }
    
    // Add class for mobile devices
    if (aqualuxe_is_mobile()) {
        $classes[] = 'mobile-device';
    }
    
    // Add class if sidebar is active
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for dark mode support
    $classes[] = 'dark-mode-support';
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add custom post classes
 */
function aqualuxe_post_classes($classes, $class, $post_id) {
    // Add reading time class
    $reading_time = aqualuxe_get_reading_time($post_id);
    $classes[] = 'reading-time-' . $reading_time;
    
    // Add featured image class
    if (has_post_thumbnail($post_id)) {
        $classes[] = 'has-featured-image';
    }
    
    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes', 10, 3);

/**
 * Add custom classes to navigation menu items
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args) {
    if (isset($args->theme_location)) {
        switch ($args->theme_location) {
            case 'primary':
                $classes[] = 'nav-link';
                break;
            case 'footer':
                $classes[] = 'footer-nav-link';
                break;
            case 'mobile':
                $classes[] = 'mobile-nav-link';
                break;
        }
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3);

/**
 * Add viewport meta tag for mobile devices
 */
function aqualuxe_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
}
add_action('wp_head', 'aqualuxe_viewport_meta', 1);

/**
 * Add theme color meta tag
 */
function aqualuxe_theme_color_meta() {
    $primary_color = aqualuxe_get_theme_option('primary_color', '#14b8a6');
    echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
}
add_action('wp_head', 'aqualuxe_theme_color_meta');

/**
 * Add preconnect links for performance
 */
function aqualuxe_preconnect_links() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'aqualuxe_preconnect_links', 1);

/**
 * Add custom CSS variables to head
 */
function aqualuxe_css_variables() {
    $colors = aqualuxe_get_theme_colors();
    
    $css = ':root {';
    foreach ($colors as $name => $color) {
        $css .= '--color-' . $name . ': ' . $color . ';';
        
        // Add RGB values for alpha transparency
        $rgb = aqualuxe_hex_to_rgb($color);
        $css .= '--color-' . $name . '-rgb: ' . implode(', ', $rgb) . ';';
    }
    $css .= '}';
    
    if (!empty($css)) {
        echo '<style id="aqualuxe-custom-colors">' . aqualuxe_minify_css($css) . '</style>' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_css_variables');

/**
 * Add structured data to head
 */
function aqualuxe_structured_data() {
    if (is_singular('post')) {
        // Article structured data
        $post_id = get_the_ID();
        $author = get_the_author_meta('display_name');
        $published_date = get_the_date('c');
        $modified_date = get_the_modified_date('c');
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'author' => array(
                '@type' => 'Person',
                'name' => $author
            ),
            'datePublished' => $published_date,
            'dateModified' => $modified_date,
        );
        
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'full');
            $schema['image'] = $thumbnail_url;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_structured_data');

/**
 * Customize excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero-large' => __('Hero Large', 'aqualuxe'),
        'aqualuxe-hero-medium' => __('Hero Medium', 'aqualuxe'),
        'aqualuxe-featured-large' => __('Featured Large', 'aqualuxe'),
        'aqualuxe-featured-medium' => __('Featured Medium', 'aqualuxe'),
        'aqualuxe-thumbnail-large' => __('Thumbnail Large', 'aqualuxe'),
        'aqualuxe-gallery' => __('Gallery', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Modify tag cloud widget settings
 */
function aqualuxe_widget_tag_cloud_args($args) {
    $args['number'] = 15;
    $args['largest'] = 1.5;
    $args['smallest'] = 0.875;
    $args['unit'] = 'rem';
    
    return $args;
}
add_filter('widget_tag_cloud_args', 'aqualuxe_widget_tag_cloud_args');

/**
 * Add custom classes to comment form
 */
function aqualuxe_comment_form_defaults($defaults) {
    $defaults['class_form'] = 'comment-form space-y-4';
    $defaults['class_submit'] = 'btn-primary';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Customize comment form fields
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    
    $fields['author'] = '<div class="comment-form-author">
        <label for="author" class="form-label">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>
        <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" class="form-input" required>
    </div>';
    
    $fields['email'] = '<div class="comment-form-email">
        <label for="email" class="form-label">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>
        <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" class="form-input" required>
    </div>';
    
    $fields['url'] = '<div class="comment-form-url">
        <label for="url" class="form-label">' . __('Website', 'aqualuxe') . '</label>
        <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" class="form-input">
    </div>';
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Remove WordPress version from head for security
 */
remove_action('wp_head', 'wp_generator');

/**
 * Remove unnecessary emoji scripts
 */
function aqualuxe_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'aqualuxe_disable_emojis');