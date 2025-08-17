<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

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
            array(
                'selector' => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector' => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title' => esc_html__('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => esc_html__('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority' => 130,
        )
    );

    // Add Header Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title' => esc_html__('Header Options', 'aqualuxe'),
            'description' => esc_html__('Customize your header settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 10,
        )
    );

    // Header Style
    $wp_customize->add_setting(
        'aqualuxe_header_style',
        array(
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_style',
        array(
            'label' => esc_html__('Header Style', 'aqualuxe'),
            'section' => 'aqualuxe_header_options',
            'type' => 'select',
            'choices' => array(
                'default' => esc_html__('Default', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'transparent' => esc_html__('Transparent', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
            ),
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_sticky_header_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header_enable',
        array(
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_options',
            'type' => 'checkbox',
        )
    );

    // Announcement Bar
    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_enable',
        array(
            'default' => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_enable',
        array(
            'label' => esc_html__('Enable Announcement Bar', 'aqualuxe'),
            'section' => 'aqualuxe_header_options',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_text',
        array(
            'default' => esc_html__('Free shipping on all orders over $100', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_text',
        array(
            'label' => esc_html__('Announcement Text', 'aqualuxe'),
            'section' => 'aqualuxe_header_options',
            'type' => 'text',
            'active_callback' => 'aqualuxe_announcement_bar_active',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_link',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_link',
        array(
            'label' => esc_html__('Announcement Link', 'aqualuxe'),
            'section' => 'aqualuxe_header_options',
            'type' => 'url',
            'active_callback' => 'aqualuxe_announcement_bar_active',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_bg_color',
        array(
            'default' => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_announcement_bar_bg_color',
            array(
                'label' => esc_html__('Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_header_options',
                'active_callback' => 'aqualuxe_announcement_bar_active',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_text_color',
        array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_announcement_bar_text_color',
            array(
                'label' => esc_html__('Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_header_options',
                'active_callback' => 'aqualuxe_announcement_bar_active',
            )
        )
    );

    // Add Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title' => esc_html__('Footer Options', 'aqualuxe'),
            'description' => esc_html__('Customize your footer settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 20,
        )
    );

    // Footer Style
    $wp_customize->add_setting(
        'aqualuxe_footer_style',
        array(
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_style',
        array(
            'label' => esc_html__('Footer Style', 'aqualuxe'),
            'section' => 'aqualuxe_footer_options',
            'type' => 'select',
            'choices' => array(
                'default' => esc_html__('Default', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
                'expanded' => esc_html__('Expanded', 'aqualuxe'),
            ),
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default' => sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
                date('Y'),
                get_bloginfo('name')
            ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label' => esc_html__('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer_options',
            'type' => 'textarea',
        )
    );

    // Footer Background Color
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_color',
        array(
            'default' => '#0a1128',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_bg_color',
            array(
                'label' => esc_html__('Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer_options',
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_text_color',
        array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_text_color',
            array(
                'label' => esc_html__('Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer_options',
            )
        )
    );

    // Add Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout_options',
        array(
            'title' => esc_html__('Layout Options', 'aqualuxe'),
            'description' => esc_html__('Customize your layout settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 30,
        )
    );

    // Layout Style
    $wp_customize->add_setting(
        'aqualuxe_layout',
        array(
            'default' => 'wide',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_layout',
        array(
            'label' => esc_html__('Layout Style', 'aqualuxe'),
            'section' => 'aqualuxe_layout_options',
            'type' => 'select',
            'choices' => array(
                'wide' => esc_html__('Wide', 'aqualuxe'),
                'boxed' => esc_html__('Boxed', 'aqualuxe'),
                'full-width' => esc_html__('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Sidebar Enable
    $wp_customize->add_setting(
        'aqualuxe_sidebar_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_enable',
        array(
            'label' => esc_html__('Enable Sidebar', 'aqualuxe'),
            'section' => 'aqualuxe_layout_options',
            'type' => 'checkbox',
        )
    );

    // Sidebar Position
    $wp_customize->add_setting(
        'aqualuxe_sidebar_position',
        array(
            'default' => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_position',
        array(
            'label' => esc_html__('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout_options',
            'type' => 'select',
            'choices' => array(
                'left' => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
            ),
            'active_callback' => 'aqualuxe_sidebar_active',
        )
    );

    // Add Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors_options',
        array(
            'title' => esc_html__('Colors Options', 'aqualuxe'),
            'description' => esc_html__('Customize your color settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 40,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default' => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label' => esc_html__('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors_options',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default' => '#00b4d8',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label' => esc_html__('Secondary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors_options',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default' => '#90e0ef',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label' => esc_html__('Accent Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors_options',
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label' => esc_html__('Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors_options',
            )
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'aqualuxe_background_color',
        array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color',
            array(
                'label' => esc_html__('Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors_options',
            )
        )
    );

    // Add Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography_options',
        array(
            'title' => esc_html__('Typography Options', 'aqualuxe'),
            'description' => esc_html__('Customize your typography settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 50,
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default' => 'Roboto, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label' => esc_html__('Body Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography_options',
            'type' => 'select',
            'choices' => array(
                'Roboto, sans-serif' => esc_html__('Roboto', 'aqualuxe'),
                'Open Sans, sans-serif' => esc_html__('Open Sans', 'aqualuxe'),
                'Lato, sans-serif' => esc_html__('Lato', 'aqualuxe'),
                'Montserrat, sans-serif' => esc_html__('Montserrat', 'aqualuxe'),
                'Raleway, sans-serif' => esc_html__('Raleway', 'aqualuxe'),
                'Poppins, sans-serif' => esc_html__('Poppins', 'aqualuxe'),
            ),
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default' => 'Montserrat, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label' => esc_html__('Heading Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography_options',
            'type' => 'select',
            'choices' => array(
                'Montserrat, sans-serif' => esc_html__('Montserrat', 'aqualuxe'),
                'Roboto, sans-serif' => esc_html__('Roboto', 'aqualuxe'),
                'Open Sans, sans-serif' => esc_html__('Open Sans', 'aqualuxe'),
                'Lato, sans-serif' => esc_html__('Lato', 'aqualuxe'),
                'Raleway, sans-serif' => esc_html__('Raleway', 'aqualuxe'),
                'Poppins, sans-serif' => esc_html__('Poppins', 'aqualuxe'),
                'Playfair Display, serif' => esc_html__('Playfair Display', 'aqualuxe'),
            ),
        )
    );

    // Body Font Size
    $wp_customize->add_setting(
        'aqualuxe_body_font_size',
        array(
            'default' => '16',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_size',
        array(
            'label' => esc_html__('Body Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography_options',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ),
        )
    );

    // Heading Font Weight
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default' => '600',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label' => esc_html__('Heading Font Weight', 'aqualuxe'),
            'section' => 'aqualuxe_typography_options',
            'type' => 'select',
            'choices' => array(
                '400' => esc_html__('Regular (400)', 'aqualuxe'),
                '500' => esc_html__('Medium (500)', 'aqualuxe'),
                '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
                '700' => esc_html__('Bold (700)', 'aqualuxe'),
            ),
        )
    );

    // Add Blog Section
    $wp_customize->add_section(
        'aqualuxe_blog_options',
        array(
            'title' => esc_html__('Blog Options', 'aqualuxe'),
            'description' => esc_html__('Customize your blog settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 60,
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default' => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label' => esc_html__('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'select',
            'choices' => array(
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
            ),
        )
    );

    // Blog Columns
    $wp_customize->add_setting(
        'aqualuxe_blog_columns',
        array(
            'default' => '3',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_columns',
        array(
            'label' => esc_html__('Blog Columns', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'select',
            'choices' => array(
                '2' => esc_html__('2 Columns', 'aqualuxe'),
                '3' => esc_html__('3 Columns', 'aqualuxe'),
                '4' => esc_html__('4 Columns', 'aqualuxe'),
            ),
            'active_callback' => 'aqualuxe_blog_grid_active',
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default' => '25',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label' => esc_html__('Excerpt Length', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 10,
                'max' => 100,
                'step' => 5,
            ),
        )
    );

    // Read More Text
    $wp_customize->add_setting(
        'aqualuxe_read_more_text',
        array(
            'default' => esc_html__('Read More', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_read_more_text',
        array(
            'label' => esc_html__('Read More Text', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'text',
        )
    );

    // Post Meta
    $wp_customize->add_setting(
        'aqualuxe_post_meta_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta_enable',
        array(
            'label' => esc_html__('Enable Post Meta', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'checkbox',
        )
    );

    // Post Meta Date
    $wp_customize->add_setting(
        'aqualuxe_post_meta_date',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta_date',
        array(
            'label' => esc_html__('Show Date', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'checkbox',
            'active_callback' => 'aqualuxe_post_meta_active',
        )
    );

    // Post Meta Author
    $wp_customize->add_setting(
        'aqualuxe_post_meta_author',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta_author',
        array(
            'label' => esc_html__('Show Author', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'checkbox',
            'active_callback' => 'aqualuxe_post_meta_active',
        )
    );

    // Post Meta Categories
    $wp_customize->add_setting(
        'aqualuxe_post_meta_categories',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta_categories',
        array(
            'label' => esc_html__('Show Categories', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'checkbox',
            'active_callback' => 'aqualuxe_post_meta_active',
        )
    );

    // Post Meta Comments
    $wp_customize->add_setting(
        'aqualuxe_post_meta_comments',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta_comments',
        array(
            'label' => esc_html__('Show Comments', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'checkbox',
            'active_callback' => 'aqualuxe_post_meta_active',
        )
    );

    // Pagination Type
    $wp_customize->add_setting(
        'aqualuxe_pagination_type',
        array(
            'default' => 'numbered',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pagination_type',
        array(
            'label' => esc_html__('Pagination Type', 'aqualuxe'),
            'section' => 'aqualuxe_blog_options',
            'type' => 'select',
            'choices' => array(
                'numbered' => esc_html__('Numbered', 'aqualuxe'),
                'older_newer' => esc_html__('Older / Newer', 'aqualuxe'),
            ),
        )
    );

    // Add Single Post Section
    $wp_customize->add_section(
        'aqualuxe_single_post_options',
        array(
            'title' => esc_html__('Single Post Options', 'aqualuxe'),
            'description' => esc_html__('Customize your single post settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 70,
        )
    );

    // Featured Image
    $wp_customize->add_setting(
        'aqualuxe_single_featured_image',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_single_featured_image',
        array(
            'label' => esc_html__('Show Featured Image', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Post Tags
    $wp_customize->add_setting(
        'aqualuxe_post_tags_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_tags_enable',
        array(
            'label' => esc_html__('Show Post Tags', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Author Box
    $wp_customize->add_setting(
        'aqualuxe_author_box_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_author_box_enable',
        array(
            'label' => esc_html__('Show Author Box', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Related Posts
    $wp_customize->add_setting(
        'aqualuxe_related_posts_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts_enable',
        array(
            'label' => esc_html__('Show Related Posts', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Share Buttons
    $wp_customize->add_setting(
        'aqualuxe_share_buttons_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_share_buttons_enable',
        array(
            'label' => esc_html__('Show Share Buttons', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Post Navigation
    $wp_customize->add_setting(
        'aqualuxe_post_navigation_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_navigation_enable',
        array(
            'label' => esc_html__('Show Post Navigation', 'aqualuxe'),
            'section' => 'aqualuxe_single_post_options',
            'type' => 'checkbox',
        )
    );

    // Add Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_options',
        array(
            'title' => esc_html__('Social Media Options', 'aqualuxe'),
            'description' => esc_html__('Customize your social media settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 80,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_social_facebook',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_facebook',
        array(
            'label' => esc_html__('Facebook URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_social_twitter',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_twitter',
        array(
            'label' => esc_html__('Twitter URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_social_instagram',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_instagram',
        array(
            'label' => esc_html__('Instagram URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_social_linkedin',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_linkedin',
        array(
            'label' => esc_html__('LinkedIn URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_social_youtube',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_youtube',
        array(
            'label' => esc_html__('YouTube URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_social_pinterest',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_pinterest',
        array(
            'label' => esc_html__('Pinterest URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_options',
            'type' => 'url',
        )
    );

    // Add Additional Features Section
    $wp_customize->add_section(
        'aqualuxe_additional_options',
        array(
            'title' => esc_html__('Additional Features', 'aqualuxe'),
            'description' => esc_html__('Customize additional features', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 90,
        )
    );

    // Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_breadcrumbs_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_breadcrumbs_enable',
        array(
            'label' => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'checkbox',
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_back_to_top_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top_enable',
        array(
            'label' => esc_html__('Enable Back to Top Button', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'checkbox',
        )
    );

    // Cookie Notice
    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_enable',
        array(
            'label' => esc_html__('Enable Cookie Notice', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_text',
        array(
            'default' => esc_html__('We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.', 'aqualuxe'),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_text',
        array(
            'label' => esc_html__('Cookie Notice Text', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'textarea',
            'active_callback' => 'aqualuxe_cookie_notice_active',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_button_text',
        array(
            'default' => esc_html__('Accept', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_button_text',
        array(
            'label' => esc_html__('Cookie Notice Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'text',
            'active_callback' => 'aqualuxe_cookie_notice_active',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_privacy_link',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_privacy_link',
        array(
            'label' => esc_html__('Privacy Policy Link', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'url',
            'active_callback' => 'aqualuxe_cookie_notice_active',
        )
    );

    // Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_enable',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_enable',
        array(
            'label' => esc_html__('Enable Dark Mode Toggle', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_default',
        array(
            'default' => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_default',
        array(
            'label' => esc_html__('Dark Mode by Default', 'aqualuxe'),
            'section' => 'aqualuxe_additional_options',
            'type' => 'checkbox',
            'active_callback' => 'aqualuxe_dark_mode_active',
        )
    );

    // Logo for Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_logo_dark',
        array(
            'default' => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_logo_dark',
            array(
                'label' => esc_html__('Logo for Dark Mode', 'aqualuxe'),
                'section' => 'title_tagline',
                'mime_type' => 'image',
                'active_callback' => 'aqualuxe_dark_mode_active',
            )
        )
    );

    // Add WooCommerce Section (only if WooCommerce is active)
    if (aqualuxe_is_woocommerce_active()) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_options',
            array(
                'title' => esc_html__('WooCommerce Options', 'aqualuxe'),
                'description' => esc_html__('Customize your WooCommerce settings', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 100,
            )
        );

        // Shop Sidebar
        $wp_customize->add_setting(
            'aqualuxe_shop_sidebar_enable',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_sidebar_enable',
            array(
                'label' => esc_html__('Enable Shop Sidebar', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Shop Sidebar Position
        $wp_customize->add_setting(
            'aqualuxe_shop_sidebar_position',
            array(
                'default' => 'left',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_sidebar_position',
            array(
                'label' => esc_html__('Shop Sidebar Position', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'select',
                'choices' => array(
                    'left' => esc_html__('Left', 'aqualuxe'),
                    'right' => esc_html__('Right', 'aqualuxe'),
                ),
                'active_callback' => 'aqualuxe_shop_sidebar_active',
            )
        );

        // Products Per Page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default' => '12',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label' => esc_html__('Products Per Page', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 4,
                    'max' => 48,
                    'step' => 4,
                ),
            )
        );

        // Product Columns
        $wp_customize->add_setting(
            'aqualuxe_product_columns',
            array(
                'default' => '4',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_columns',
            array(
                'label' => esc_html__('Product Columns', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'select',
                'choices' => array(
                    '2' => esc_html__('2 Columns', 'aqualuxe'),
                    '3' => esc_html__('3 Columns', 'aqualuxe'),
                    '4' => esc_html__('4 Columns', 'aqualuxe'),
                    '5' => esc_html__('5 Columns', 'aqualuxe'),
                ),
            )
        );

        // Related Products
        $wp_customize->add_setting(
            'aqualuxe_related_products_enable',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_enable',
            array(
                'label' => esc_html__('Enable Related Products', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Related Products Count
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            array(
                'default' => '4',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            array(
                'label' => esc_html__('Related Products Count', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 2,
                    'max' => 8,
                    'step' => 1,
                ),
                'active_callback' => 'aqualuxe_related_products_active',
            )
        );

        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_quick_view_enable',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_quick_view_enable',
            array(
                'label' => esc_html__('Enable Quick View', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_wishlist_enable',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_wishlist_enable',
            array(
                'label' => esc_html__('Enable Wishlist', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Product Gallery Zoom
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_zoom',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_gallery_zoom',
            array(
                'label' => esc_html__('Enable Product Gallery Zoom', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Product Gallery Lightbox
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_lightbox',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_gallery_lightbox',
            array(
                'label' => esc_html__('Enable Product Gallery Lightbox', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Product Gallery Slider
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_slider',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_gallery_slider',
            array(
                'label' => esc_html__('Enable Product Gallery Slider', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Sticky Add to Cart
        $wp_customize->add_setting(
            'aqualuxe_sticky_add_to_cart',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_sticky_add_to_cart',
            array(
                'label' => esc_html__('Enable Sticky Add to Cart', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );

        // Ajax Add to Cart
        $wp_customize->add_setting(
            'aqualuxe_ajax_add_to_cart',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_ajax_add_to_cart',
            array(
                'label' => esc_html__('Enable Ajax Add to Cart', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_options',
                'type' => 'checkbox',
            )
        );
    }
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
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Check if announcement bar is active.
 *
 * @return bool
 */
function aqualuxe_announcement_bar_active() {
    return get_theme_mod('aqualuxe_announcement_bar_enable', false);
}

/**
 * Check if sidebar is active.
 *
 * @return bool
 */
function aqualuxe_sidebar_active() {
    return get_theme_mod('aqualuxe_sidebar_enable', true);
}

/**
 * Check if shop sidebar is active.
 *
 * @return bool
 */
function aqualuxe_shop_sidebar_active() {
    return get_theme_mod('aqualuxe_shop_sidebar_enable', true);
}

/**
 * Check if blog grid layout is active.
 *
 * @return bool
 */
function aqualuxe_blog_grid_active() {
    return get_theme_mod('aqualuxe_blog_layout', 'grid') === 'grid' || get_theme_mod('aqualuxe_blog_layout', 'grid') === 'masonry';
}

/**
 * Check if post meta is active.
 *
 * @return bool
 */
function aqualuxe_post_meta_active() {
    return get_theme_mod('aqualuxe_post_meta_enable', true);
}

/**
 * Check if cookie notice is active.
 *
 * @return bool
 */
function aqualuxe_cookie_notice_active() {
    return get_theme_mod('aqualuxe_cookie_notice_enable', true);
}

/**
 * Check if dark mode is active.
 *
 * @return bool
 */
function aqualuxe_dark_mode_active() {
    return get_theme_mod('aqualuxe_dark_mode_enable', true);
}

/**
 * Check if related products are active.
 *
 * @return bool
 */
function aqualuxe_related_products_active() {
    return get_theme_mod('aqualuxe_related_products_enable', true);
}

/**
 * Generate CSS from customizer settings.
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077b6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00b4d8');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#90e0ef');
    $text_color = get_theme_mod('aqualuxe_text_color', '#333333');
    $background_color = get_theme_mod('aqualuxe_background_color', '#ffffff');
    $footer_bg_color = get_theme_mod('aqualuxe_footer_bg_color', '#0a1128');
    $footer_text_color = get_theme_mod('aqualuxe_footer_text_color', '#ffffff');
    $announcement_bar_bg_color = get_theme_mod('aqualuxe_announcement_bar_bg_color', '#0077b6');
    $announcement_bar_text_color = get_theme_mod('aqualuxe_announcement_bar_text_color', '#ffffff');
    $body_font_family = get_theme_mod('aqualuxe_body_font_family', 'Roboto, sans-serif');
    $heading_font_family = get_theme_mod('aqualuxe_heading_font_family', 'Montserrat, sans-serif');
    $body_font_size = get_theme_mod('aqualuxe_body_font_size', '16');
    $heading_font_weight = get_theme_mod('aqualuxe_heading_font_weight', '600');

    $css = '
        :root {
            --primary-color: ' . esc_attr($primary_color) . ';
            --primary-color-dark: ' . esc_attr(aqualuxe_adjust_brightness($primary_color, -20)) . ';
            --primary-color-light: ' . esc_attr(aqualuxe_adjust_brightness($primary_color, 20)) . ';
            --secondary-color: ' . esc_attr($secondary_color) . ';
            --secondary-color-dark: ' . esc_attr(aqualuxe_adjust_brightness($secondary_color, -20)) . ';
            --secondary-color-light: ' . esc_attr(aqualuxe_adjust_brightness($secondary_color, 20)) . ';
            --accent-color: ' . esc_attr($accent_color) . ';
            --text-color: ' . esc_attr($text_color) . ';
            --background-color: ' . esc_attr($background_color) . ';
            --footer-bg-color: ' . esc_attr($footer_bg_color) . ';
            --footer-text-color: ' . esc_attr($footer_text_color) . ';
            --body-font-family: ' . esc_attr($body_font_family) . ';
            --heading-font-family: ' . esc_attr($heading_font_family) . ';
            --body-font-size: ' . esc_attr($body_font_size) . 'px;
            --heading-font-weight: ' . esc_attr($heading_font_weight) . ';
        }

        body {
            font-family: var(--body-font-family);
            font-size: var(--body-font-size);
            color: var(--text-color);
            background-color: var(--background-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font-family);
            font-weight: var(--heading-font-weight);
        }

        a {
            color: var(--primary-color);
        }

        a:hover {
            color: var(--primary-color-dark);
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-secondary {
            background-color: var(--secondary-color) !important;
        }

        .bg-accent {
            background-color: var(--accent-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-secondary {
            color: var(--secondary-color) !important;
        }

        .text-accent {
            color: var(--accent-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-color-dark);
            border-color: var(--primary-color-dark);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-color-dark);
            border-color: var(--secondary-color-dark);
        }

        .site-footer {
            background-color: var(--footer-bg-color);
            color: var(--footer-text-color);
        }

        .site-footer a {
            color: var(--footer-text-color);
        }

        .site-footer a:hover {
            color: var(--accent-color);
        }

        .announcement-bar {
            background-color: ' . esc_attr($announcement_bar_bg_color) . ';
            color: ' . esc_attr($announcement_bar_text_color) . ';
        }

        .announcement-bar a {
            color: ' . esc_attr($announcement_bar_text_color) . ';
        }

        /* Dark Mode Styles */
        .dark-mode-active {
            --text-color: #f8f9fa;
            --background-color: #121212;
        }

        .dark-mode-active .card,
        .dark-mode-active .site-header,
        .dark-mode-active .site-content {
            background-color: #1e1e1e;
        }

        .dark-mode-active .border,
        .dark-mode-active .border-top,
        .dark-mode-active .border-right,
        .dark-mode-active .border-bottom,
        .dark-mode-active .border-left {
            border-color: #333333 !important;
        }

        /* WooCommerce Styles */
        .woocommerce #respond input#submit.alt,
        .woocommerce a.button.alt,
        .woocommerce button.button.alt,
        .woocommerce input.button.alt {
            background-color: var(--primary-color);
        }

        .woocommerce #respond input#submit.alt:hover,
        .woocommerce a.button.alt:hover,
        .woocommerce button.button.alt:hover,
        .woocommerce input.button.alt:hover {
            background-color: var(--primary-color-dark);
        }

        .woocommerce span.onsale {
            background-color: var(--secondary-color);
        }

        .woocommerce ul.products li.product .price,
        .woocommerce div.product p.price,
        .woocommerce div.product span.price {
            color: var(--primary-color);
        }

        .woocommerce-info,
        .woocommerce-message {
            border-top-color: var(--primary-color);
        }

        .woocommerce-info::before,
        .woocommerce-message::before {
            color: var(--primary-color);
        }
    ';

    return $css;
}

/**
 * Output the customizer CSS.
 */
function aqualuxe_output_customizer_css() {
    echo '<style type="text/css">' . aqualuxe_customizer_css() . '</style>';
}
add_action('wp_head', 'aqualuxe_output_customizer_css');

/**
 * Adjust brightness of a color.
 *
 * @param string $hex Hex color code.
 * @param int $steps Steps to adjust brightness (positive for lighter, negative for darker).
 * @return string
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');

    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to hex
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}