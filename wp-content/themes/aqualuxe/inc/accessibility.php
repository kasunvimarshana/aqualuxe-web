<?php
/**
 * Accessibility Enhancements - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_accessibility_setup')) {
    /**
     * Set up accessibility enhancements.
     *
     * @since 1.0.0
     */
    function aqualuxe_accessibility_setup() {
        // Add theme support for accessibility features
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
    }
}
add_action('after_setup_theme', 'aqualuxe_accessibility_setup');

if (!function_exists('aqualuxe_add_skip_links')) {
    /**
     * Add skip links to the site.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_skip_links() {
        ?>
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
        <a class="skip-link screen-reader-text" href="#main-navigation"><?php esc_html_e('Skip to navigation', 'aqualuxe'); ?></a>
        <?php
    }
}
add_action('wp_body_open', 'aqualuxe_add_skip_links');

if (!function_exists('aqualuxe_add_accessibility_attributes')) {
    /**
     * Add accessibility attributes to navigation menus.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_accessibility_attributes($items, $args) {
        if ($args->theme_location == 'primary') {
            $items = str_replace('<a', '<a aria-haspopup="true"', $items);
        }
        return $items;
    }
}
add_filter('wp_nav_menu_items', 'aqualuxe_add_accessibility_attributes', 10, 2);

if (!function_exists('aqualuxe_add_search_form_accessibility')) {
    /**
     * Add accessibility attributes to search form.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_search_form_accessibility($form) {
        $form = str_replace('<form', '<form role="search"', $form);
        $form = str_replace('<input type="search"', '<input type="search" aria-label="' . esc_attr__('Search', 'aqualuxe') . '"', $form);
        $form = str_replace('<input type="submit"', '<input type="submit" aria-label="' . esc_attr__('Submit search', 'aqualuxe') . '"', $form);
        return $form;
    }
}
add_filter('get_search_form', 'aqualuxe_add_search_form_accessibility');

if (!function_exists('aqualuxe_add_widget_accessibility')) {
    /**
     * Add accessibility attributes to widgets.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_widget_accessibility($params) {
        if ($params[0]['before_widget']) {
            $params[0]['before_widget'] = str_replace('<section', '<section role="complementary"', $params[0]['before_widget']);
        }
        return $params;
    }
}
add_filter('dynamic_sidebar_params', 'aqualuxe_add_widget_accessibility');

if (!function_exists('aqualuxe_add_image_accessibility')) {
    /**
     * Add accessibility attributes to images.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_image_accessibility($content) {
        // Add role and aria-hidden to decorative images
        $content = str_replace('src="', 'role="img" aria-hidden="true" src="', $content);
        
        // Add alt attributes to images without them
        $content = preg_replace('/<img(?![^>]*alt=)([^>]*?)>/i', '<img alt=""$1>', $content);
        
        return $content;
    }
}
add_filter('the_content', 'aqualuxe_add_image_accessibility');

if (!function_exists('aqualuxe_add_table_accessibility')) {
    /**
     * Add accessibility attributes to tables.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_table_accessibility($content) {
        // Add role and scope to tables
        $content = str_replace('<table', '<table role="table"', $content);
        $content = str_replace('<th', '<th scope="col"', $content);
        
        return $content;
    }
}
add_filter('the_content', 'aqualuxe_add_table_accessibility');

if (!function_exists('aqualuxe_add_form_accessibility')) {
    /**
     * Add accessibility attributes to forms.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_form_accessibility($content) {
        // Add required attribute to required fields
        $content = str_replace('aria-required="true"', 'required aria-required="true"', $content);
        
        // Add aria-describedby to form fields with descriptions
        $content = preg_replace('/<label for="([^"]+)">([^<]+)<\/label>\s*<([^>]+)>/', '<label for="$1">$2</label><$3 aria-describedby="$1-desc">', $content);
        
        return $content;
    }
}
add_filter('the_content', 'aqualuxe_add_form_accessibility');

if (!function_exists('aqualuxe_add_focus_styles')) {
    /**
     * Add focus styles for keyboard navigation.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_focus_styles() {
        ?>
        <style>
        /* Focus styles for keyboard navigation */
        a:focus,
        button:focus,
        input:focus,
        textarea:focus,
        select:focus {
            outline: 2px solid #00a896;
            outline-offset: 2px;
        }
        
        /* Focus styles for skip links */
        .skip-link:focus {
            position: absolute;
            top: 0;
            left: 0;
            width: auto;
            height: auto;
            padding: 10px 15px;
            background: #000;
            color: #fff;
            text-decoration: none;
            z-index: 99999;
        }
        
        /* Focus styles for navigation */
        .main-navigation a:focus {
            background-color: rgba(0, 168, 150, 0.1);
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'aqualuxe_add_focus_styles');

if (!function_exists('aqualuxe_add_landmarks')) {
    /**
     * Add ARIA landmarks to the site.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_landmarks() {
        // Add role to header
        add_filter('aqualuxe_header_role', function() {
            return 'banner';
        });
        
        // Add role to navigation
        add_filter('aqualuxe_navigation_role', function() {
            return 'navigation';
        });
        
        // Add role to main content
        add_filter('aqualuxe_main_role', function() {
            return 'main';
        });
        
        // Add role to footer
        add_filter('aqualuxe_footer_role', function() {
            return 'contentinfo';
        });
    }
}
add_action('init', 'aqualuxe_add_landmarks');

if (!function_exists('aqualuxe_add_contrast_checker')) {
    /**
     * Add contrast checker for text and background colors.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_contrast_checker() {
        // This function would typically be implemented with JavaScript
        // to check color contrast ratios in real-time
        ?>
        <script>
        (function() {
            // Function to calculate luminance
            function getLuminance(r, g, b) {
                var a = [r, g, b].map(function(v) {
                    v /= 255;
                    return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
                });
                return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
            }
            
            // Function to calculate contrast ratio
            function getContrastRatio(l1, l2) {
                return (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);
            }
            
            // Check contrast for text elements
            function checkContrast() {
                var elements = document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, a, li, td, th, label, input, textarea, select, button');
                for (var i = 0; i < elements.length; i++) {
                    var element = elements[i];
                    var style = window.getComputedStyle(element);
                    var color = style.color;
                    var backgroundColor = style.backgroundColor;
                    
                    // Parse RGB values
                    var colorMatch = color.match(/rgb\((\d+), (\d+), (\d+)\)/);
                    var bgMatch = backgroundColor.match(/rgb\((\d+), (\d+), (\d+)\)/);
                    
                    if (colorMatch && bgMatch) {
                        var r1 = parseInt(colorMatch[1]);
                        var g1 = parseInt(colorMatch[2]);
                        var b1 = parseInt(colorMatch[3]);
                        
                        var r2 = parseInt(bgMatch[1]);
                        var g2 = parseInt(bgMatch[2]);
                        var b2 = parseInt(bgMatch[3]);
                        
                        var l1 = getLuminance(r1, g1, b1);
                        var l2 = getLuminance(r2, g2, b2);
                        
                        var ratio = getContrastRatio(l1, l2);
                        
                        // Check if contrast ratio meets WCAG AA standards (4.5:1 for normal text, 3:1 for large text)
                        var fontSize = parseFloat(style.fontSize);
                        var isLargeText = fontSize >= 18 || (fontSize >= 14 && style.fontWeight >= 700);
                        var requiredRatio = isLargeText ? 3 : 4.5;
                        
                        if (ratio < requiredRatio) {
                            console.warn('Low contrast detected for element:', element);
                        }
                    }
                }
            }
            
            // Run contrast check on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkContrast);
            } else {
                checkContrast();
            }
        })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_add_contrast_checker');