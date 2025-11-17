<?php
/**
 * AquaLuxe Accessibility Functions
 *
 * Functions for improving accessibility
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add screen reader text class
 * 
 * @param array $classes Array of classes.
 * @return array
 */
function aqualuxe_screen_reader_class( $classes ) {
    $classes[] = 'screen-reader-text';
    return $classes;
}

/**
 * Add skip link
 */
function aqualuxe_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
}
add_action( 'wp_body_open', 'aqualuxe_skip_link', 5 );

/**
 * Add ARIA landmark roles
 *
 * @param string $content The content.
 * @return string
 */
function aqualuxe_aria_landmark_roles( $content ) {
    return str_replace(
        array(
            '<header class="',
            '<nav class="',
            '<main class="',
            '<aside class="',
            '<footer class="',
        ),
        array(
            '<header role="banner" class="',
            '<nav role="navigation" class="',
            '<main role="main" class="',
            '<aside role="complementary" class="',
            '<footer role="contentinfo" class="',
        ),
        $content
    );
}
add_filter( 'wp_body_open', 'aqualuxe_aria_landmark_roles' );

/**
 * Add aria-current attribute to current menu items
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
    if ( isset( $item->current ) && $item->current ) {
        $atts['aria-current'] = 'page';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );

/**
 * Add aria-label to search form
 *
 * @param string $form The search form HTML.
 * @return string
 */
function aqualuxe_search_form_aria_label( $form ) {
    return str_replace( 'role="search"', 'role="search" aria-label="' . esc_attr__( 'Site search', 'aqualuxe' ) . '"', $form );
}
add_filter( 'get_search_form', 'aqualuxe_search_form_aria_label' );

/**
 * Add aria-required to required form fields
 */
function aqualuxe_comment_form_default_fields( $fields ) {
    if ( isset( $fields['author'] ) ) {
        $fields['author'] = str_replace(
            'required=\'required\'',
            'required=\'required\' aria-required=\'true\'',
            $fields['author']
        );
    }
    if ( isset( $fields['email'] ) ) {
        $fields['email'] = str_replace(
            'required=\'required\'',
            'required=\'required\' aria-required=\'true\'',
            $fields['email']
        );
    }
    if ( isset( $fields['url'] ) ) {
        $fields['url'] = str_replace(
            '<input ',
            '<input aria-required=\'false\' ',
            $fields['url']
        );
    }
    return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_default_fields' );

/**
 * Add aria-required to comment form textarea
 */
function aqualuxe_comment_form_defaults( $defaults ) {
    if ( isset( $defaults['comment_field'] ) ) {
        $defaults['comment_field'] = str_replace(
            'required=\'required\'',
            'required=\'required\' aria-required=\'true\'',
            $defaults['comment_field']
        );
    }
    return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Add aria-describedby to comment form
 */
function aqualuxe_comment_form_after_fields() {
    echo '<p id="comment-notes-description" class="screen-reader-text">' . esc_html__( 'Required fields are marked with an asterisk (*).', 'aqualuxe' ) . '</p>';
}
add_action( 'comment_form_after_fields', 'aqualuxe_comment_form_after_fields' );

/**
 * Add aria-describedby to the comment textarea
 */
function aqualuxe_comment_form_field_comment( $field ) {
    return str_replace(
        'class="comment-form-comment">',
        'class="comment-form-comment"><p id="comment-notes" class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'aqualuxe' ) . '</p>',
        $field
    );
}
add_filter( 'comment_form_field_comment', 'aqualuxe_comment_form_field_comment' );

/**
 * Add title attribute to read more links
 */
function aqualuxe_excerpt_more( $more ) {
    global $post;
    return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link" title="' . sprintf(
        /* translators: %s: Post title */
        esc_attr__( 'Continue reading %s', 'aqualuxe' ),
        the_title_attribute( array( 'echo' => false ) )
    ) . '">' . esc_html__( 'Read more', 'aqualuxe' ) . '<span class="screen-reader-text"> ' . sprintf(
        /* translators: %s: Post title */
        esc_html__( 'about %s', 'aqualuxe' ),
        get_the_title()
    ) . '</span></a>';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add screen reader text to post pagination
 */
function aqualuxe_posts_navigation_args( $args ) {
    $args['prev_text'] = '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span><span aria-hidden="true">&larr;</span>';
    $args['next_text'] = '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><span aria-hidden="true">&rarr;</span>';
    return $args;
}
add_filter( 'the_posts_pagination_args', 'aqualuxe_posts_navigation_args' );

/**
 * Add screen reader text to post navigation
 */
function aqualuxe_post_navigation_args( $args ) {
    $args['prev_text'] = '<span class="nav-title-icon-wrapper" aria-hidden="true">&larr;</span> <span class="screen-reader-text">' . esc_html__( 'Previous post:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>';
    $args['next_text'] = '<span class="nav-title">%title</span> <span class="screen-reader-text">' . esc_html__( 'Next post:', 'aqualuxe' ) . '</span> <span class="nav-title-icon-wrapper" aria-hidden="true">&rarr;</span>';
    return $args;
}
add_filter( 'the_post_navigation_args', 'aqualuxe_post_navigation_args' );

/**
 * Add screen reader text to social links
 */
function aqualuxe_social_links_icons( $social_links_output, $social_links ) {
    foreach ( $social_links as $network => $link ) {
        if ( ! empty( $link ) ) {
            $label = sprintf(
                /* translators: %s: Social network name */
                esc_html__( '%s link', 'aqualuxe' ),
                ucfirst( $network )
            );
            $social_links_output = str_replace(
                'class="social-link ' . $network . '"',
                'class="social-link ' . $network . '" aria-label="' . esc_attr( $label ) . '"',
                $social_links_output
            );
        }
    }
    return $social_links_output;
}
add_filter( 'aqualuxe_social_links_icons', 'aqualuxe_social_links_icons', 10, 2 );

/**
 * Add keyboard navigation for dropdown menus
 */
function aqualuxe_dropdown_keyboard_navigation() {
    if ( ! wp_script_is( 'aqualuxe-navigation', 'enqueued' ) ) {
        return;
    }
    ?>
    <script>
    (function() {
        var container, menu, links, i, len;

        container = document.getElementById('site-navigation');
        if (!container) {
            return;
        }

        menu = container.getElementsByTagName('ul')[0];
        if ('undefined' === typeof menu) {
            return;
        }

        menu.setAttribute('aria-expanded', 'false');
        if (-1 === menu.className.indexOf('nav-menu')) {
            menu.className += ' nav-menu';
        }

        // Get all the link elements within the menu.
        links = menu.getElementsByTagName('a');

        // Each time a menu link is focused or blurred, toggle focus.
        for (i = 0, len = links.length; i < len; i++) {
            links[i].addEventListener('focus', toggleFocus, true);
            links[i].addEventListener('blur', toggleFocus, true);
        }

        /**
         * Sets or removes .focus class on an element.
         */
        function toggleFocus() {
            var self = this;

            // Move up through the ancestors of the current link until we hit .nav-menu.
            while (-1 === self.className.indexOf('nav-menu')) {
                // On li elements toggle the class .focus.
                if ('li' === self.tagName.toLowerCase()) {
                    if (-1 !== self.className.indexOf('focus')) {
                        self.className = self.className.replace(' focus', '');
                    } else {
                        self.className += ' focus';
                    }
                }
                self = self.parentElement;
            }
        }

        /**
         * Toggles `focus` class to allow submenu access on tablets.
         */
        (function(container) {
            var touchStartFn, i,
                parentLink = container.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

            if ('ontouchstart' in window) {
                touchStartFn = function(e) {
                    var menuItem = this.parentNode, i;

                    if (!menuItem.classList.contains('focus')) {
                        e.preventDefault();
                        for (i = 0; i < menuItem.parentNode.children.length; ++i) {
                            if (menuItem === menuItem.parentNode.children[i]) {
                                continue;
                            }
                            menuItem.parentNode.children[i].classList.remove('focus');
                        }
                        menuItem.classList.add('focus');
                    } else {
                        menuItem.classList.remove('focus');
                    }
                };

                for (i = 0; i < parentLink.length; ++i) {
                    parentLink[i].addEventListener('touchstart', touchStartFn, false);
                }
            }
        }(container));
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_dropdown_keyboard_navigation' );

/**
 * Add ARIA attributes to WooCommerce elements
 */
function aqualuxe_woocommerce_accessibility() {
    // Only run if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Add ARIA label to product search
    add_filter( 'get_product_search_form', function( $form ) {
        return str_replace( 'class="woocommerce-product-search"', 'class="woocommerce-product-search" role="search" aria-label="' . esc_attr__( 'Product search', 'aqualuxe' ) . '"', $form );
    } );

    // Add ARIA label to quantity input
    add_filter( 'woocommerce_quantity_input_args', function( $args, $product ) {
        $args['label'] = esc_html__( 'Quantity', 'aqualuxe' );
        $args['input_value'] = $args['input_value'] ? $args['input_value'] : 1;
        
        return $args;
    }, 10, 2 );

    // Add ARIA label to add to cart button
    add_filter( 'woocommerce_loop_add_to_cart_args', function( $args, $product ) {
        $args['attributes']['aria-label'] = sprintf(
            /* translators: %s: Product title */
            esc_attr__( 'Add %s to your cart', 'aqualuxe' ),
            strip_tags( $product->get_name() )
        );
        
        return $args;
    }, 10, 2 );

    // Add ARIA label to product gallery thumbnails
    add_filter( 'woocommerce_single_product_image_thumbnail_html', function( $html, $attachment_id ) {
        $attachment = get_post( $attachment_id );
        if ( $attachment ) {
            $html = str_replace( '<img', '<img aria-label="' . esc_attr( $attachment->post_title ) . '"', $html );
        }
        
        return $html;
    }, 10, 2 );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_accessibility' );

/**
 * Add focus styles to theme
 */
function aqualuxe_accessibility_styles() {
    ?>
    <style>
        /* Focus styles */
        a:focus,
        button:focus,
        input:focus,
        textarea:focus,
        select:focus,
        [tabindex]:focus {
            outline: 2px solid #4a90e2;
            outline-offset: 2px;
        }

        /* Skip link styles */
        .skip-link {
            background-color: #f1f1f1;
            box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.2);
            color: #21759b;
            display: block;
            font-size: 14px;
            font-weight: 700;
            left: -9999em;
            outline: none;
            padding: 15px 23px 14px;
            text-decoration: none;
            text-transform: none;
            top: -9999em;
            z-index: 100000;
        }

        .skip-link:focus {
            clip: auto;
            height: auto;
            left: 6px;
            top: 7px;
            width: auto;
            z-index: 100000;
        }

        /* Screen reader text */
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
add_action( 'wp_head', 'aqualuxe_accessibility_styles' );

/**
 * Add customizer options for accessibility
 */
function aqualuxe_accessibility_customizer_options( $wp_customize ) {
    // Add accessibility section
    $wp_customize->add_section( 'aqualuxe_accessibility', array(
        'title'    => __( 'Accessibility', 'aqualuxe' ),
        'priority' => 90,
    ) );

    // High contrast mode
    $wp_customize->add_setting( 'aqualuxe_high_contrast_mode', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_high_contrast_mode', array(
        'label'    => __( 'Enable High Contrast Mode', 'aqualuxe' ),
        'description' => __( 'Increases contrast for better readability', 'aqualuxe' ),
        'section'  => 'aqualuxe_accessibility',
        'type'     => 'checkbox',
    ) );

    // Font size adjustment
    $wp_customize->add_setting( 'aqualuxe_font_size_adjustment', array(
        'default'           => 'normal',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_font_size_adjustment', array(
        'label'    => __( 'Font Size Adjustment', 'aqualuxe' ),
        'description' => __( 'Adjust base font size for better readability', 'aqualuxe' ),
        'section'  => 'aqualuxe_accessibility',
        'type'     => 'select',
        'choices'  => array(
            'small'  => __( 'Small', 'aqualuxe' ),
            'normal' => __( 'Normal', 'aqualuxe' ),
            'large'  => __( 'Large', 'aqualuxe' ),
            'x-large' => __( 'Extra Large', 'aqualuxe' ),
        ),
    ) );

    // Reduced motion
    $wp_customize->add_setting( 'aqualuxe_reduced_motion', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_reduced_motion', array(
        'label'    => __( 'Reduce Motion', 'aqualuxe' ),
        'description' => __( 'Reduces or eliminates animations and transitions', 'aqualuxe' ),
        'section'  => 'aqualuxe_accessibility',
        'type'     => 'checkbox',
    ) );

    // Focus indicator style
    $wp_customize->add_setting( 'aqualuxe_focus_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_focus_style', array(
        'label'    => __( 'Focus Indicator Style', 'aqualuxe' ),
        'description' => __( 'Style of the focus indicator when navigating with keyboard', 'aqualuxe' ),
        'section'  => 'aqualuxe_accessibility',
        'type'     => 'select',
        'choices'  => array(
            'default' => __( 'Default (Blue outline)', 'aqualuxe' ),
            'high-visibility' => __( 'High Visibility (Yellow outline)', 'aqualuxe' ),
            'subtle' => __( 'Subtle (Gray outline)', 'aqualuxe' ),
        ),
    ) );
}
add_action( 'customize_register', 'aqualuxe_accessibility_customizer_options' );

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Apply accessibility customizer options
 */
function aqualuxe_apply_accessibility_options() {
    $high_contrast = get_theme_mod( 'aqualuxe_high_contrast_mode', false );
    $font_size = get_theme_mod( 'aqualuxe_font_size_adjustment', 'normal' );
    $reduced_motion = get_theme_mod( 'aqualuxe_reduced_motion', false );
    $focus_style = get_theme_mod( 'aqualuxe_focus_style', 'default' );

    $css = '';

    // High contrast mode
    if ( $high_contrast ) {
        $css .= '
            body {
                color: #000 !important;
                background-color: #fff !important;
            }
            a, button, input[type="button"], input[type="submit"], .button {
                color: #000080 !important;
            }
            a:hover, a:focus, button:hover, button:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus, .button:hover, .button:focus {
                color: #0000ff !important;
                background-color: #ffff00 !important;
            }
            .site-header, .site-footer {
                background-color: #f8f8f8 !important;
                color: #000 !important;
            }
            .main-navigation ul li a {
                color: #000 !important;
            }
            .main-navigation ul li:hover > a, .main-navigation ul li.focus > a {
                color: #0000ff !important;
                background-color: #ffff00 !important;
            }
            .main-navigation ul ul {
                background-color: #fff !important;
                border: 1px solid #000 !important;
            }
        ';
    }

    // Font size adjustment
    switch ( $font_size ) {
        case 'small':
            $css .= 'html { font-size: 14px; }';
            break;
        case 'large':
            $css .= 'html { font-size: 18px; }';
            break;
        case 'x-large':
            $css .= 'html { font-size: 20px; }';
            break;
        default:
            $css .= 'html { font-size: 16px; }';
    }

    // Reduced motion
    if ( $reduced_motion ) {
        $css .= '
            * {
                transition: none !important;
                animation: none !important;
            }
        ';
    }

    // Focus style
    switch ( $focus_style ) {
        case 'high-visibility':
            $css .= '
                a:focus, button:focus, input:focus, textarea:focus, select:focus, [tabindex]:focus {
                    outline: 3px solid #ffff00 !important;
                    outline-offset: 3px !important;
                    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.8) !important;
                }
            ';
            break;
        case 'subtle':
            $css .= '
                a:focus, button:focus, input:focus, textarea:focus, select:focus, [tabindex]:focus {
                    outline: 2px solid #aaaaaa !important;
                    outline-offset: 2px !important;
                }
            ';
            break;
    }

    if ( ! empty( $css ) ) {
        echo '<style id="aqualuxe-accessibility-styles">' . $css . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_apply_accessibility_options' );