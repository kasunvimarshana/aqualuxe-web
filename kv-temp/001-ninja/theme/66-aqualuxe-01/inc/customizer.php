<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            [
                'selector' => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            ]
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            [
                'selector' => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            ]
        );
    }

    // Add theme options panel
    $wp_customize->add_panel('aqualuxe_theme_options', [
        'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority' => 130,
    ]);

    // Add header section
    $wp_customize->add_section('aqualuxe_header_options', [
        'title' => __('Header Options', 'aqualuxe'),
        'description' => __('Customize the header section', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ]);

    // Header layout
    $wp_customize->add_setting('aqualuxe_header_layout', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_header_layout', [
        'label' => __('Header Layout', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'split' => __('Split', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ],
    ]);

    // Sticky header
    $wp_customize->add_setting('aqualuxe_sticky_header', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_sticky_header', [
        'label' => __('Enable Sticky Header', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'checkbox',
    ]);

    // Transparent header
    $wp_customize->add_setting('aqualuxe_transparent_header', [
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_transparent_header', [
        'label' => __('Enable Transparent Header on Homepage', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'checkbox',
    ]);

    // Header top bar
    $wp_customize->add_setting('aqualuxe_header_top_bar', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_header_top_bar', [
        'label' => __('Enable Header Top Bar', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'checkbox',
    ]);

    // Header top bar content
    $wp_customize->add_setting('aqualuxe_header_top_bar_content', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_header_top_bar_content', [
        'label' => __('Header Top Bar Content', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'textarea',
        'active_callback' => function () {
            return get_theme_mod('aqualuxe_header_top_bar', true);
        },
    ]);

    // Add footer section
    $wp_customize->add_section('aqualuxe_footer_options', [
        'title' => __('Footer Options', 'aqualuxe'),
        'description' => __('Customize the footer section', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ]);

    // Footer layout
    $wp_customize->add_setting('aqualuxe_footer_layout', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_footer_layout', [
        'label' => __('Footer Layout', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
            'expanded' => __('Expanded', 'aqualuxe'),
        ],
    ]);

    // Footer widgets
    $wp_customize->add_setting('aqualuxe_footer_widgets', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_footer_widgets', [
        'label' => __('Enable Footer Widgets', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'checkbox',
    ]);

    // Footer widgets columns
    $wp_customize->add_setting('aqualuxe_footer_widgets_columns', [
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_footer_widgets_columns', [
        'label' => __('Footer Widgets Columns', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'select',
        'choices' => [
            '1' => __('1 Column', 'aqualuxe'),
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ],
        'active_callback' => function () {
            return get_theme_mod('aqualuxe_footer_widgets', true);
        },
    ]);

    // Copyright text
    $wp_customize->add_setting('aqualuxe_copyright_text', [
        'default' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_copyright_text', [
        'label' => __('Copyright Text', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'textarea',
    ]);

    // Add colors section
    $wp_customize->add_section('aqualuxe_colors', [
        'title' => __('Colors', 'aqualuxe'),
        'description' => __('Customize the theme colors', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ]);

    // Primary color
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Secondary color
    $wp_customize->add_setting('aqualuxe_secondary_color', [
        'default' => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
        'label' => __('Secondary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Accent color
    $wp_customize->add_setting('aqualuxe_accent_color', [
        'default' => '#00c1b6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
        'label' => __('Accent Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Text color
    $wp_customize->add_setting('aqualuxe_text_color', [
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', [
        'label' => __('Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Heading color
    $wp_customize->add_setting('aqualuxe_heading_color', [
        'default' => '#222222',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', [
        'label' => __('Heading Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Background color
    $wp_customize->add_setting('aqualuxe_background_color', [
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', [
        'label' => __('Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Add typography section
    $wp_customize->add_section('aqualuxe_typography', [
        'title' => __('Typography', 'aqualuxe'),
        'description' => __('Customize the theme typography', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ]);

    // Body font
    $wp_customize->add_setting('aqualuxe_body_font', [
        'default' => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_body_font', [
        'label' => __('Body Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Source Sans Pro' => 'Source Sans Pro',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'Ubuntu' => 'Ubuntu',
        ],
    ]);

    // Heading font
    $wp_customize->add_setting('aqualuxe_heading_font', [
        'default' => 'Montserrat',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_heading_font', [
        'label' => __('Heading Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Montserrat' => 'Montserrat',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Inter' => 'Inter',
            'Poppins' => 'Poppins',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Raleway' => 'Raleway',
            'Ubuntu' => 'Ubuntu',
        ],
    ]);

    // Body font size
    $wp_customize->add_setting('aqualuxe_body_font_size', [
        'default' => '16',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_body_font_size', [
        'label' => __('Body Font Size (px)', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => [
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ],
    ]);

    // Line height
    $wp_customize->add_setting('aqualuxe_line_height', [
        'default' => '1.6',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_line_height', [
        'label' => __('Line Height', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 2,
            'step' => 0.1,
        ],
    ]);

    // Add layout section
    $wp_customize->add_section('aqualuxe_layout', [
        'title' => __('Layout', 'aqualuxe'),
        'description' => __('Customize the theme layout', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ]);

    // Container width
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1200',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Width (px)', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => [
            'min' => 960,
            'max' => 1600,
            'step' => 10,
        ],
    ]);

    // Sidebar position
    $wp_customize->add_setting('aqualuxe_sidebar_position', [
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_sidebar_position', [
        'label' => __('Sidebar Position', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            'right' => __('Right', 'aqualuxe'),
            'left' => __('Left', 'aqualuxe'),
            'none' => __('No Sidebar', 'aqualuxe'),
        ],
    ]);

    // Add blog section
    $wp_customize->add_section('aqualuxe_blog', [
        'title' => __('Blog', 'aqualuxe'),
        'description' => __('Customize the blog settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 60,
    ]);

    // Blog layout
    $wp_customize->add_setting('aqualuxe_blog_layout', [
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_blog_layout', [
        'label' => __('Blog Layout', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => [
            'grid' => __('Grid', 'aqualuxe'),
            'list' => __('List', 'aqualuxe'),
            'standard' => __('Standard', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ],
    ]);

    // Blog columns
    $wp_customize->add_setting('aqualuxe_blog_columns', [
        'default' => '3',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_blog_columns', [
        'label' => __('Blog Columns', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => [
            '1' => __('1 Column', 'aqualuxe'),
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ],
        'active_callback' => function () {
            return in_array(get_theme_mod('aqualuxe_blog_layout', 'grid'), ['grid', 'masonry']);
        },
    ]);

    // Excerpt length
    $wp_customize->add_setting('aqualuxe_excerpt_length', [
        'default' => '30',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_excerpt_length', [
        'label' => __('Excerpt Length', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => [
            'min' => 10,
            'max' => 100,
            'step' => 5,
        ],
    ]);

    // Show featured image
    $wp_customize->add_setting('aqualuxe_show_featured_image', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_show_featured_image', [
        'label' => __('Show Featured Image', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ]);

    // Show post meta
    $wp_customize->add_setting('aqualuxe_show_post_meta', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_show_post_meta', [
        'label' => __('Show Post Meta', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ]);

    // Show author bio
    $wp_customize->add_setting('aqualuxe_show_author_bio', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_show_author_bio', [
        'label' => __('Show Author Bio', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ]);

    // Show related posts
    $wp_customize->add_setting('aqualuxe_show_related_posts', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_show_related_posts', [
        'label' => __('Show Related Posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ]);

    // Add social media section
    $wp_customize->add_section('aqualuxe_social', [
        'title' => __('Social Media', 'aqualuxe'),
        'description' => __('Add your social media links', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 70,
    ]);

    // Facebook
    $wp_customize->add_setting('aqualuxe_social_facebook', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_facebook', [
        'label' => __('Facebook', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // Twitter
    $wp_customize->add_setting('aqualuxe_social_twitter', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_twitter', [
        'label' => __('Twitter', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // Instagram
    $wp_customize->add_setting('aqualuxe_social_instagram', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_instagram', [
        'label' => __('Instagram', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // LinkedIn
    $wp_customize->add_setting('aqualuxe_social_linkedin', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_linkedin', [
        'label' => __('LinkedIn', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // YouTube
    $wp_customize->add_setting('aqualuxe_social_youtube', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_youtube', [
        'label' => __('YouTube', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // Pinterest
    $wp_customize->add_setting('aqualuxe_social_pinterest', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_social_pinterest', [
        'label' => __('Pinterest', 'aqualuxe'),
        'section' => 'aqualuxe_social',
        'type' => 'url',
    ]);

    // Add contact information section
    $wp_customize->add_section('aqualuxe_contact', [
        'title' => __('Contact Information', 'aqualuxe'),
        'description' => __('Add your contact information', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 80,
    ]);

    // Phone
    $wp_customize->add_setting('aqualuxe_contact_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_contact_phone', [
        'label' => __('Phone', 'aqualuxe'),
        'section' => 'aqualuxe_contact',
        'type' => 'text',
    ]);

    // Email
    $wp_customize->add_setting('aqualuxe_contact_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_contact_email', [
        'label' => __('Email', 'aqualuxe'),
        'section' => 'aqualuxe_contact',
        'type' => 'email',
    ]);

    // Address
    $wp_customize->add_setting('aqualuxe_contact_address', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_contact_address', [
        'label' => __('Address', 'aqualuxe'),
        'section' => 'aqualuxe_contact',
        'type' => 'text',
    ]);

    // Business hours
    $wp_customize->add_setting('aqualuxe_contact_hours', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_contact_hours', [
        'label' => __('Business Hours', 'aqualuxe'),
        'section' => 'aqualuxe_contact',
        'type' => 'text',
    ]);

    // Google Maps API Key
    $wp_customize->add_setting('aqualuxe_google_maps_api_key', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_google_maps_api_key', [
        'label' => __('Google Maps API Key', 'aqualuxe'),
        'section' => 'aqualuxe_contact',
        'type' => 'text',
    ]);

    // Add newsletter section
    $wp_customize->add_section('aqualuxe_newsletter', [
        'title' => __('Newsletter', 'aqualuxe'),
        'description' => __('Configure newsletter settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 90,
    ]);

    // Newsletter shortcode
    $wp_customize->add_setting('aqualuxe_newsletter_shortcode', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_newsletter_shortcode', [
        'label' => __('Newsletter Shortcode', 'aqualuxe'),
        'description' => __('Enter the shortcode for your newsletter form (e.g., from Mailchimp, Contact Form 7, etc.)', 'aqualuxe'),
        'section' => 'aqualuxe_newsletter',
        'type' => 'text',
    ]);

    // Show newsletter in footer
    $wp_customize->add_setting('aqualuxe_show_newsletter_footer', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_show_newsletter_footer', [
        'label' => __('Show Newsletter in Footer', 'aqualuxe'),
        'section' => 'aqualuxe_newsletter',
        'type' => 'checkbox',
    ]);

    // Add performance section
    $wp_customize->add_section('aqualuxe_performance', [
        'title' => __('Performance', 'aqualuxe'),
        'description' => __('Configure performance settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 100,
    ]);

    // Enable lazy loading
    $wp_customize->add_setting('aqualuxe_lazy_loading', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_lazy_loading', [
        'label' => __('Enable Lazy Loading for Images', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type' => 'checkbox',
    ]);

    // Enable minification
    $wp_customize->add_setting('aqualuxe_minification', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_minification', [
        'label' => __('Enable CSS/JS Minification', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type' => 'checkbox',
    ]);

    // Add modules section
    $wp_customize->add_section('aqualuxe_modules', [
        'title' => __('Modules', 'aqualuxe'),
        'description' => __('Enable or disable theme modules', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 110,
    ]);

    // Multilingual module
    $wp_customize->add_setting('aqualuxe_module_multilingual', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_multilingual', [
        'label' => __('Enable Multilingual Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Dark mode module
    $wp_customize->add_setting('aqualuxe_module_dark_mode', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_dark_mode', [
        'label' => __('Enable Dark Mode Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Dark mode default
    $wp_customize->add_setting('aqualuxe_dark_mode_default', [
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_dark_mode_default', [
        'label' => __('Enable Dark Mode by Default', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
        'active_callback' => function () {
            return get_theme_mod('aqualuxe_module_dark_mode', true);
        },
    ]);

    // Bookings module
    $wp_customize->add_setting('aqualuxe_module_bookings', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_bookings', [
        'label' => __('Enable Bookings Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Events module
    $wp_customize->add_setting('aqualuxe_module_events', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_events', [
        'label' => __('Enable Events Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Wholesale module
    $wp_customize->add_setting('aqualuxe_module_wholesale', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_wholesale', [
        'label' => __('Enable Wholesale Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Auctions module
    $wp_customize->add_setting('aqualuxe_module_auctions', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_auctions', [
        'label' => __('Enable Auctions Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);

    // Affiliate module
    $wp_customize->add_setting('aqualuxe_module_affiliate', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('aqualuxe_module_affiliate', [
        'label' => __('Enable Affiliate Module', 'aqualuxe'),
        'section' => 'aqualuxe_modules',
        'type' => 'checkbox',
    ]);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_DIST_URI . 'js/customizer.js', ['customize-preview'], AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (bool) $checked;
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize float.
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Generate CSS from customizer settings.
 *
 * @return string
 */
function aqualuxe_get_customizer_css() {
    $css = '';

    // Get customizer settings
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00a0d2');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#00c1b6');
    $text_color = get_theme_mod('aqualuxe_text_color', '#333333');
    $heading_color = get_theme_mod('aqualuxe_heading_color', '#222222');
    $background_color = get_theme_mod('aqualuxe_background_color', '#ffffff');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font_size = get_theme_mod('aqualuxe_body_font_size', '16');
    $line_height = get_theme_mod('aqualuxe_line_height', '1.6');
    $container_width = get_theme_mod('aqualuxe_container_width', '1200');

    // Generate CSS
    $css .= ':root {
        --aqualuxe-primary-color: ' . $primary_color . ';
        --aqualuxe-secondary-color: ' . $secondary_color . ';
        --aqualuxe-accent-color: ' . $accent_color . ';
        --aqualuxe-text-color: ' . $text_color . ';
        --aqualuxe-heading-color: ' . $heading_color . ';
        --aqualuxe-background-color: ' . $background_color . ';
        --aqualuxe-body-font: "' . $body_font . '", sans-serif;
        --aqualuxe-heading-font: "' . $heading_font . '", sans-serif;
        --aqualuxe-body-font-size: ' . $body_font_size . 'px;
        --aqualuxe-line-height: ' . $line_height . ';
        --aqualuxe-container-width: ' . $container_width . 'px;
    }';

    $css .= 'body {
        font-family: var(--aqualuxe-body-font);
        font-size: var(--aqualuxe-body-font-size);
        line-height: var(--aqualuxe-line-height);
        color: var(--aqualuxe-text-color);
        background-color: var(--aqualuxe-background-color);
    }';

    $css .= 'h1, h2, h3, h4, h5, h6 {
        font-family: var(--aqualuxe-heading-font);
        color: var(--aqualuxe-heading-color);
    }';

    $css .= 'a {
        color: var(--aqualuxe-primary-color);
    }';

    $css .= 'a:hover {
        color: var(--aqualuxe-secondary-color);
    }';

    $css .= '.container {
        max-width: var(--aqualuxe-container-width);
    }';

    $css .= '.btn-primary, .button, button[type="submit"], input[type="submit"] {
        background-color: var(--aqualuxe-primary-color);
        border-color: var(--aqualuxe-primary-color);
    }';

    $css .= '.btn-primary:hover, .button:hover, button[type="submit"]:hover, input[type="submit"]:hover {
        background-color: var(--aqualuxe-secondary-color);
        border-color: var(--aqualuxe-secondary-color);
    }';

    $css .= '.btn-secondary {
        background-color: var(--aqualuxe-secondary-color);
        border-color: var(--aqualuxe-secondary-color);
    }';

    $css .= '.btn-secondary:hover {
        background-color: var(--aqualuxe-primary-color);
        border-color: var(--aqualuxe-primary-color);
    }';

    $css .= '.btn-accent {
        background-color: var(--aqualuxe-accent-color);
        border-color: var(--aqualuxe-accent-color);
    }';

    $css .= '.btn-accent:hover {
        background-color: var(--aqualuxe-secondary-color);
        border-color: var(--aqualuxe-secondary-color);
    }';

    // Header styles
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $sticky_header = get_theme_mod('aqualuxe_sticky_header', true);
    $transparent_header = get_theme_mod('aqualuxe_transparent_header', false);

    if ($sticky_header) {
        $css .= '.site-header.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            animation: sticky-header 0.3s ease;
        }';

        $css .= '@keyframes sticky-header {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }';
    }

    if ($transparent_header) {
        $css .= '.home .site-header.transparent {
            background-color: transparent;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }';

        $css .= '.home .site-header.transparent .site-title a,
                .home .site-header.transparent .site-description,
                .home .site-header.transparent .primary-menu > li > a {
            color: #fff;
        }';
    }

    // Footer styles
    $footer_layout = get_theme_mod('aqualuxe_footer_layout', 'default');
    $footer_widgets = get_theme_mod('aqualuxe_footer_widgets', true);
    $footer_widgets_columns = get_theme_mod('aqualuxe_footer_widgets_columns', '4');

    if ($footer_widgets) {
        $css .= '.footer-widgets .widget-column {
            flex: 0 0 calc(100% / ' . $footer_widgets_columns . ');
        }';
    }

    // Blog styles
    $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
    $blog_columns = get_theme_mod('aqualuxe_blog_columns', '3');

    if ($blog_layout === 'grid' || $blog_layout === 'masonry') {
        $css .= '.blog-grid .post,
                .blog-masonry .post {
            flex: 0 0 calc(100% / ' . $blog_columns . ' - 30px);
            margin: 15px;
        }';
    }

    // Sidebar styles
    $sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');

    if ($sidebar_position === 'left') {
        $css .= '.content-area {
            order: 2;
        }';

        $css .= '.widget-area {
            order: 1;
        }';
    } elseif ($sidebar_position === 'none') {
        $css .= '.content-area {
            flex: 0 0 100%;
            max-width: 100%;
        }';

        $css .= '.widget-area {
            display: none;
        }';
    }

    return $css;
}

/**
 * Enqueue customizer CSS.
 */
function aqualuxe_enqueue_customizer_css() {
    $css = aqualuxe_get_customizer_css();
    wp_add_inline_style('aqualuxe-style', $css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_customizer_css', 20);

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_enqueue_google_fonts() {
    $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');

    $fonts = [];

    if ($body_font) {
        $fonts[] = $body_font . ':400,500,600,700';
    }

    if ($heading_font && $heading_font !== $body_font) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }

    if (!empty($fonts)) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map('urlencode', $fonts)) . '&display=swap';
        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, [], AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts');

/**
 * Update active modules based on customizer settings.
 */
function aqualuxe_update_active_modules() {
    $active_modules = [];

    // Get module settings from customizer
    $multilingual = get_theme_mod('aqualuxe_module_multilingual', true);
    $dark_mode = get_theme_mod('aqualuxe_module_dark_mode', true);
    $bookings = get_theme_mod('aqualuxe_module_bookings', true);
    $events = get_theme_mod('aqualuxe_module_events', true);
    $wholesale = get_theme_mod('aqualuxe_module_wholesale', true);
    $auctions = get_theme_mod('aqualuxe_module_auctions', true);
    $affiliate = get_theme_mod('aqualuxe_module_affiliate', true);

    // Set active modules
    if ($multilingual) {
        $active_modules['multilingual'] = true;
    }

    if ($dark_mode) {
        $active_modules['dark-mode'] = true;
    }

    if ($bookings) {
        $active_modules['bookings'] = true;
    }

    if ($events) {
        $active_modules['events'] = true;
    }

    if ($wholesale) {
        $active_modules['wholesale'] = true;
    }

    if ($auctions) {
        $active_modules['auctions'] = true;
    }

    if ($affiliate) {
        $active_modules['affiliate'] = true;
    }

    // Update active modules option
    update_option('aqualuxe_active_modules', $active_modules);
}
add_action('customize_save_after', 'aqualuxe_update_active_modules');