<?php
/**
 * AquaLuxe Theme Customizer - Layout Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add layout settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_settings($wp_customize) {
    // Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout_section',
        array(
            'title'       => __('Layout Settings', 'aqualuxe'),
            'description' => __('Configure the layout settings for your site.', 'aqualuxe'),
            'priority'    => 40,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_options[container_width]',
        array(
            'default'           => '1280px',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[container_width]',
        array(
            'label'       => __('Container Width', 'aqualuxe'),
            'description' => __('Set the maximum width of the main container (e.g., 1280px, 1440px).', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'text',
            'input_attrs' => array(
                'placeholder' => '1280px',
            ),
        )
    );

    // Content Width
    $wp_customize->add_setting(
        'aqualuxe_options[content_width]',
        array(
            'default'           => '800',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[content_width]',
        array(
            'label'       => __('Content Width', 'aqualuxe'),
            'description' => __('Width of the main content area in pixels.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 600,
                'max'  => 1200,
                'step' => 10,
            ),
        )
    );

    // Sidebar Width
    $wp_customize->add_setting(
        'aqualuxe_options[sidebar_width]',
        array(
            'default'           => '300',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[sidebar_width]',
        array(
            'label'       => __('Sidebar Width', 'aqualuxe'),
            'description' => __('Width of the sidebar in pixels.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 200,
                'max'  => 500,
                'step' => 10,
            ),
        )
    );

    // Content Padding
    $wp_customize->add_setting(
        'aqualuxe_options[content_padding]',
        array(
            'default'           => '40',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[content_padding]',
        array(
            'label'       => __('Content Padding', 'aqualuxe'),
            'description' => __('Padding around content areas in pixels.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ),
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_options[page_layout]',
        array(
            'default'           => 'right-sidebar',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[page_layout]',
        array(
            'label'       => __('Default Page Layout', 'aqualuxe'),
            'description' => __('Choose the default layout for pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Post Layout
    $wp_customize->add_setting(
        'aqualuxe_options[post_layout]',
        array(
            'default'           => 'right-sidebar',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[post_layout]',
        array(
            'label'       => __('Default Post Layout', 'aqualuxe'),
            'description' => __('Choose the default layout for single posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Archive Layout
    $wp_customize->add_setting(
        'aqualuxe_options[archive_layout]',
        array(
            'default'           => 'right-sidebar',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[archive_layout]',
        array(
            'label'       => __('Archive Layout', 'aqualuxe'),
            'description' => __('Choose the layout for archive pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Archive Display Style
    $wp_customize->add_setting(
        'aqualuxe_options[archive_display]',
        array(
            'default'           => 'grid',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[archive_display]',
        array(
            'label'       => __('Archive Display Style', 'aqualuxe'),
            'description' => __('Choose how to display posts on archive pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'grid'    => __('Grid', 'aqualuxe'),
                'list'    => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
                'classic' => __('Classic', 'aqualuxe'),
            ),
        )
    );

    // Grid Columns
    $wp_customize->add_setting(
        'aqualuxe_options[grid_columns]',
        array(
            'default'           => '3',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[grid_columns]',
        array(
            'label'       => __('Grid Columns', 'aqualuxe'),
            'description' => __('Number of columns for grid and masonry layouts.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                $archive_display = isset($options['archive_display']) ? $options['archive_display'] : 'grid';
                return ($archive_display === 'grid' || $archive_display === 'masonry');
            },
        )
    );

    // Section Spacing
    $wp_customize->add_setting(
        'aqualuxe_options[section_spacing]',
        array(
            'default'           => 'medium',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[section_spacing]',
        array(
            'label'       => __('Section Spacing', 'aqualuxe'),
            'description' => __('Control the spacing between sections.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'small'  => __('Small', 'aqualuxe'),
                'medium' => __('Medium', 'aqualuxe'),
                'large'  => __('Large', 'aqualuxe'),
            ),
        )
    );

    // Box Shadow
    $wp_customize->add_setting(
        'aqualuxe_options[enable_box_shadow]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_box_shadow]',
        array(
            'label'       => __('Enable Box Shadows', 'aqualuxe'),
            'description' => __('Add subtle box shadows to cards and content boxes.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'checkbox',
        )
    );

    // Border Radius
    $wp_customize->add_setting(
        'aqualuxe_options[border_radius]',
        array(
            'default'           => 'medium',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[border_radius]',
        array(
            'label'       => __('Border Radius', 'aqualuxe'),
            'description' => __('Control the roundness of elements.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'none'   => __('None', 'aqualuxe'),
                'small'  => __('Small', 'aqualuxe'),
                'medium' => __('Medium', 'aqualuxe'),
                'large'  => __('Large', 'aqualuxe'),
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_layout_settings');