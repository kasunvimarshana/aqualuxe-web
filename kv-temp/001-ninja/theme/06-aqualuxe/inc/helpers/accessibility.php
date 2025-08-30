<?php
/**
 * Accessibility functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add accessibility features to the theme
 */
function aqualuxe_accessibility_setup() {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return;
    }
    
    // Add screen reader text class
    add_action( 'wp_head', 'aqualuxe_add_screen_reader_text_style' );
    
    // Add skip link focus fix
    add_action( 'wp_footer', 'aqualuxe_skip_link_focus_fix', 999 );
    
    // Add ARIA attributes to navigation menus
    add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );
    add_filter( 'nav_menu_item_id', 'aqualuxe_nav_menu_item_id', 10, 4 );
    add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 4 );
    
    // Add ARIA attributes to comment form
    add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );
    
    // Add ARIA attributes to search form
    add_filter( 'get_search_form', 'aqualuxe_get_search_form' );
    
    // Add ARIA attributes to pagination
    add_filter( 'navigation_markup_template', 'aqualuxe_navigation_markup_template', 10, 2 );
    
    // Add ARIA attributes to widgets
    add_filter( 'dynamic_sidebar_params', 'aqualuxe_dynamic_sidebar_params' );
}
add_action( 'after_setup_theme', 'aqualuxe_accessibility_setup' );

/**
 * Add screen reader text style
 */
function aqualuxe_add_screen_reader_text_style() {
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
    </style>
    <?php
}

/**
 * Skip link focus fix
 */
function aqualuxe_skip_link_focus_fix() {
    ?>
    <script>
        /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
    </script>
    <?php
}

/**
 * Add ARIA attributes to navigation menu links
 *
 * @param array $atts Link attributes.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @param int $depth Menu depth.
 * @return array Modified link attributes.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
    // Add ARIA attributes to menu links
    if ( ! isset( $atts['aria-current'] ) && $item->current ) {
        $atts['aria-current'] = 'page';
    }
    
    // Add ARIA attributes to dropdown toggles
    if ( in_array( 'menu-item-has-children', $item->classes ) ) {
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }
    
    return $atts;
}

/**
 * Add ARIA attributes to navigation menu items
 *
 * @param string $id Menu item ID.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @param int $depth Menu depth.
 * @return string Modified menu item ID.
 */
function aqualuxe_nav_menu_item_id( $id, $item, $args, $depth ) {
    // Add unique ID to menu items
    if ( empty( $id ) ) {
        $id = 'menu-item-' . $item->ID;
    }
    
    return $id;
}

/**
 * Add ARIA attributes to navigation menu classes
 *
 * @param array $classes Menu item classes.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @param int $depth Menu depth.
 * @return array Modified menu item classes.
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args, $depth ) {
    // Add current class to current menu items
    if ( $item->current ) {
        $classes[] = 'current';
    }
    
    return $classes;
}

/**
 * Add ARIA attributes to comment form
 *
 * @param array $defaults Comment form defaults.
 * @return array Modified comment form defaults.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
    // Add ARIA attributes to comment form
    $defaults['comment_field'] = str_replace( 'textarea', 'textarea aria-required="true" aria-label="' . esc_attr__( 'Comment', 'aqualuxe' ) . '"', $defaults['comment_field'] );
    
    // Add ARIA attributes to comment form submit button
    $defaults['submit_button'] = str_replace( 'submit', 'submit aria-label="' . esc_attr__( 'Submit Comment', 'aqualuxe' ) . '"', $defaults['submit_button'] );
    
    return $defaults;
}

/**
 * Add ARIA attributes to search form
 *
 * @param string $form Search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_get_search_form( $form ) {
    // Add ARIA attributes to search form
    $form = str_replace( 'search-field', 'search-field aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '"', $form );
    $form = str_replace( 'search-submit', 'search-submit aria-label="' . esc_attr__( 'Search Submit', 'aqualuxe' ) . '"', $form );
    
    return $form;
}

/**
 * Add ARIA attributes to pagination
 *
 * @param string $template Navigation markup template.
 * @param string $class Navigation class.
 * @return string Modified navigation markup template.
 */
function aqualuxe_navigation_markup_template( $template, $class ) {
    // Add ARIA attributes to pagination
    $template = str_replace( '<nav class="navigation %1$s"', '<nav class="navigation %1$s" aria-label="' . esc_attr__( 'Posts Navigation', 'aqualuxe' ) . '"', $template );
    
    return $template;
}

/**
 * Add ARIA attributes to widgets
 *
 * @param array $params Widget parameters.
 * @return array Modified widget parameters.
 */
function aqualuxe_dynamic_sidebar_params( $params ) {
    // Add ARIA attributes to widgets
    $params[0]['before_widget'] = str_replace( 'class="widget', 'class="widget" role="complementary" aria-label="' . esc_attr( $params[0]['widget_name'] ) . '"', $params[0]['before_widget'] );
    
    return $params;
}

/**
 * Add ARIA attributes to dropdown menu toggles
 *
 * @param string $nav_menu Navigation menu HTML.
 * @param object $args Menu arguments.
 * @return string Modified navigation menu HTML.
 */
function aqualuxe_add_dropdown_toggle_button( $nav_menu, $args ) {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return $nav_menu;
    }
    
    // Add dropdown toggle button to menu items with children
    $nav_menu = preg_replace_callback(
        '/<li(.+?)class="(.+?)menu-item-has-children(.+?)"/i',
        function( $matches ) {
            return '<li' . $matches[1] . 'class="' . $matches[2] . 'menu-item-has-children' . $matches[3] . '" aria-haspopup="true" aria-expanded="false"';
        },
        $nav_menu
    );
    
    return $nav_menu;
}
add_filter( 'wp_nav_menu', 'aqualuxe_add_dropdown_toggle_button', 10, 2 );

/**
 * Add ARIA attributes to mobile menu toggle button
 *
 * @param string $toggle_button Mobile menu toggle button HTML.
 * @return string Modified mobile menu toggle button HTML.
 */
function aqualuxe_mobile_menu_toggle_button( $toggle_button ) {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return $toggle_button;
    }
    
    // Add ARIA attributes to mobile menu toggle button
    $toggle_button = str_replace( 'class="menu-toggle"', 'class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"', $toggle_button );
    
    return $toggle_button;
}
add_filter( 'aqualuxe_mobile_menu_toggle', 'aqualuxe_mobile_menu_toggle_button' );

/**
 * Add ARIA attributes to WooCommerce elements
 */
function aqualuxe_woocommerce_accessibility() {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return;
    }
    
    // Add ARIA attributes to WooCommerce quantity input
    add_filter( 'woocommerce_quantity_input_args', function( $args, $product ) {
        $args['inputmode'] = 'numeric';
        $args['pattern'] = '[0-9]*';
        $args['aria-label'] = esc_attr__( 'Product quantity', 'aqualuxe' );
        
        return $args;
    }, 10, 2 );
    
    // Add ARIA attributes to WooCommerce add to cart button
    add_filter( 'woocommerce_loop_add_to_cart_args', function( $args, $product ) {
        $args['attributes']['aria-label'] = sprintf( esc_attr__( 'Add %s to your cart', 'aqualuxe' ), $product->get_name() );
        
        return $args;
    }, 10, 2 );
    
    // Add ARIA attributes to WooCommerce product gallery
    add_filter( 'woocommerce_single_product_image_gallery_classes', function( $classes ) {
        $classes[] = 'aria-describedby="product-gallery-instructions"';
        
        return $classes;
    } );
    
    // Add ARIA attributes to WooCommerce product tabs
    add_filter( 'woocommerce_product_tabs', function( $tabs ) {
        foreach ( $tabs as $key => $tab ) {
            $tabs[ $key ]['aria_label'] = sprintf( esc_attr__( '%s tab', 'aqualuxe' ), $tab['title'] );
        }
        
        return $tabs;
    } );
}
add_action( 'woocommerce_init', 'aqualuxe_woocommerce_accessibility' );

/**
 * Add keyboard navigation for accessibility
 */
function aqualuxe_keyboard_navigation() {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return;
    }
    
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus styles for keyboard navigation
            document.body.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            document.body.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });
            
            // Accessible dropdown menu
            var dropdownToggles = document.querySelectorAll('.menu-item-has-children > a');
            
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        
                        var parent = this.parentNode;
                        var submenu = parent.querySelector('.sub-menu');
                        
                        if (submenu) {
                            var isExpanded = parent.getAttribute('aria-expanded') === 'true';
                            
                            parent.setAttribute('aria-expanded', !isExpanded);
                            submenu.classList.toggle('toggled');
                            
                            if (!isExpanded) {
                                var firstLink = submenu.querySelector('a');
                                
                                if (firstLink) {
                                    firstLink.focus();
                                }
                            }
                        }
                    }
                });
            });
            
            // Accessible mobile menu
            var menuToggle = document.querySelector('.menu-toggle');
            
            if (menuToggle) {
                menuToggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        
                        var isExpanded = this.getAttribute('aria-expanded') === 'true';
                        
                        this.setAttribute('aria-expanded', !isExpanded);
                        document.querySelector('.main-navigation').classList.toggle('toggled');
                        
                        if (!isExpanded) {
                            var firstLink = document.querySelector('.main-navigation .menu > li > a');
                            
                            if (firstLink) {
                                firstLink.focus();
                            }
                        }
                    }
                });
            }
            
            // Accessible search toggle
            var searchToggle = document.querySelector('.search-toggle');
            
            if (searchToggle) {
                searchToggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        
                        var headerSearch = this.closest('.header-search');
                        
                        if (headerSearch) {
                            headerSearch.classList.toggle('active');
                            
                            if (headerSearch.classList.contains('active')) {
                                var searchField = headerSearch.querySelector('.search-field');
                                
                                if (searchField) {
                                    searchField.focus();
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    <style>
        /* Keyboard navigation styles */
        .keyboard-navigation :focus {
            outline: 2px solid var(--color-primary) !important;
            outline-offset: 2px !important;
        }
    </style>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_keyboard_navigation', 20 );

/**
 * Add ARIA landmark roles to theme elements
 *
 * @param string $content Content.
 * @return string Modified content.
 */
function aqualuxe_add_aria_landmark_roles( $content ) {
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return $content;
    }
    
    // Add ARIA landmark roles to theme elements
    $content = str_replace( '<header id="masthead"', '<header id="masthead" role="banner"', $content );
    $content = str_replace( '<nav id="site-navigation"', '<nav id="site-navigation" role="navigation"', $content );
    $content = str_replace( '<main id="main"', '<main id="main" role="main"', $content );
    $content = str_replace( '<aside id="secondary"', '<aside id="secondary" role="complementary"', $content );
    $content = str_replace( '<footer id="colophon"', '<footer id="colophon" role="contentinfo"', $content );
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_aria_landmark_roles', 999 );
add_filter( 'get_header', 'aqualuxe_add_aria_landmark_roles', 999 );
add_filter( 'get_footer', 'aqualuxe_add_aria_landmark_roles', 999 );
add_filter( 'get_sidebar', 'aqualuxe_add_aria_landmark_roles', 999 );

/**
 * Add accessibility checker to admin bar
 */
function aqualuxe_accessibility_checker() {
    // Check if user is logged in and can manage options
    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Check if accessibility features are enabled
    if ( ! get_theme_mod( 'aqualuxe_accessibility_enabled', true ) ) {
        return;
    }
    
    // Add accessibility checker script
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add accessibility checker button to admin bar
            var adminBar = document.getElementById('wpadminbar');
            
            if (adminBar) {
                var accessibilityChecker = document.createElement('li');
                accessibilityChecker.id = 'accessibility-checker';
                accessibilityChecker.className = 'menupop';
                accessibilityChecker.innerHTML = '<a class="ab-item" href="#"><span class="ab-icon"></span><span class="ab-label"><?php esc_html_e( 'Accessibility', 'aqualuxe' ); ?></span></a><div class="ab-sub-wrapper"><ul class="ab-submenu"><li><a class="ab-item" href="#" id="run-accessibility-check"><?php esc_html_e( 'Run Accessibility Check', 'aqualuxe' ); ?></a></li></ul></div>';
                
                var adminBarList = adminBar.querySelector('#wp-admin-bar-root-default');
                
                if (adminBarList) {
                    adminBarList.appendChild(accessibilityChecker);
                }
                
                // Add accessibility checker functionality
                document.getElementById('run-accessibility-check').addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Simple accessibility checks
                    var accessibilityIssues = [];
                    
                    // Check for images without alt text
                    var images = document.querySelectorAll('img:not([alt])');
                    
                    if (images.length > 0) {
                        accessibilityIssues.push('Found ' + images.length + ' images without alt text');
                    }
                    
                    // Check for links without text
                    var emptyLinks = document.querySelectorAll('a:empty:not([aria-label]):not([aria-labelledby])');
                    
                    if (emptyLinks.length > 0) {
                        accessibilityIssues.push('Found ' + emptyLinks.length + ' links without text or ARIA labels');
                    }
                    
                    // Check for form fields without labels
                    var formFields = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([aria-label]):not([aria-labelledby]), select:not([aria-label]):not([aria-labelledby]), textarea:not([aria-label]):not([aria-labelledby])');
                    var fieldsWithoutLabels = [];
                    
                    formFields.forEach(function(field) {
                        var id = field.getAttribute('id');
                        
                        if (id) {
                            var label = document.querySelector('label[for="' + id + '"]');
                            
                            if (!label) {
                                fieldsWithoutLabels.push(field);
                            }
                        } else {
                            fieldsWithoutLabels.push(field);
                        }
                    });
                    
                    if (fieldsWithoutLabels.length > 0) {
                        accessibilityIssues.push('Found ' + fieldsWithoutLabels.length + ' form fields without labels or ARIA labels');
                    }
                    
                    // Check for color contrast (simplified)
                    var elements = document.querySelectorAll('*');
                    var contrastIssues = [];
                    
                    elements.forEach(function(element) {
                        var style = window.getComputedStyle(element);
                        var backgroundColor = style.backgroundColor;
                        var color = style.color;
                        
                        if (backgroundColor === 'rgba(0, 0, 0, 0)' || color === 'rgba(0, 0, 0, 0)') {
                            return;
                        }
                        
                        // Simple contrast check (not accurate but gives an idea)
                        var bgRgb = backgroundColor.match(/\d+/g);
                        var textRgb = color.match(/\d+/g);
                        
                        if (bgRgb && textRgb) {
                            var bgLuminance = (parseInt(bgRgb[0]) * 0.299 + parseInt(bgRgb[1]) * 0.587 + parseInt(bgRgb[2]) * 0.114) / 255;
                            var textLuminance = (parseInt(textRgb[0]) * 0.299 + parseInt(textRgb[1]) * 0.587 + parseInt(textRgb[2]) * 0.114) / 255;
                            var contrastRatio = Math.abs(bgLuminance - textLuminance);
                            
                            if (contrastRatio < 0.5) {
                                contrastIssues.push(element);
                            }
                        }
                    });
                    
                    if (contrastIssues.length > 0) {
                        accessibilityIssues.push('Found ' + contrastIssues.length + ' potential color contrast issues');
                    }
                    
                    // Display results
                    if (accessibilityIssues.length > 0) {
                        alert('Accessibility Issues Found:\n\n' + accessibilityIssues.join('\n'));
                    } else {
                        alert('No major accessibility issues found!');
                    }
                });
            }
        });
    </script>
    <style>
        #wpadminbar #wp-admin-bar-accessibility-checker .ab-icon:before {
            content: '\f227';
            top: 2px;
        }
    </style>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_accessibility_checker' );