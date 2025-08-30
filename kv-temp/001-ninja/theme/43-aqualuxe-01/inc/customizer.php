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
function aqualuxe_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => __( 'Theme Options for AquaLuxe', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // General Settings Section
    $wp_customize->add_section(
        'aqualuxe_general_settings',
        array(
            'title'       => __( 'General Settings', 'aqualuxe' ),
            'description' => __( 'General theme settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Layout Settings
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __( 'Container Width', 'aqualuxe' ),
            'description' => __( 'Set the maximum width of the content container', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'text',
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'       => __( 'Blog Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for blog pages', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                'full-width'    => __( 'Full Width', 'aqualuxe' ),
            ),
        )
    );

    // Single Post Layout
    $wp_customize->add_setting(
        'aqualuxe_post_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_layout',
        array(
            'label'       => __( 'Single Post Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for single posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                'full-width'    => __( 'Full Width', 'aqualuxe' ),
            ),
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'no-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_layout',
        array(
            'label'       => __( 'Page Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for pages', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                'full-width'    => __( 'Full Width', 'aqualuxe' ),
            ),
        )
    );

    // Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_show_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_breadcrumbs',
        array(
            'label'       => __( 'Show Breadcrumbs', 'aqualuxe' ),
            'description' => __( 'Display breadcrumbs on pages and posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'checkbox',
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top',
        array(
            'label'       => __( 'Back to Top Button', 'aqualuxe' ),
            'description' => __( 'Display a back to top button', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_settings',
            'type'        => 'checkbox',
        )
    );

    // Header Settings Section
    $wp_customize->add_section(
        'aqualuxe_header_settings',
        array(
            'title'       => __( 'Header Settings', 'aqualuxe' ),
            'description' => __( 'Customize the header section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'       => __( 'Header Layout', 'aqualuxe' ),
            'description' => __( 'Choose the header layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'select',
            'choices'     => array(
                'default'      => __( 'Default', 'aqualuxe' ),
                'centered'     => __( 'Centered', 'aqualuxe' ),
                'transparent'  => __( 'Transparent', 'aqualuxe' ),
                'sticky'       => __( 'Sticky', 'aqualuxe' ),
                'minimal'      => __( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Announcement Bar
    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_enable',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_enable',
        array(
            'label'       => __( 'Enable Announcement Bar', 'aqualuxe' ),
            'description' => __( 'Display an announcement bar at the top of the site', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_text',
        array(
            'default'           => __( 'Free shipping on orders over $100 | International shipping available', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_text',
        array(
            'label'       => __( 'Announcement Text', 'aqualuxe' ),
            'description' => __( 'Text to display in the announcement bar', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_announcement_bar_link',
        array(
            'label'       => __( 'Announcement Link', 'aqualuxe' ),
            'description' => __( 'Link for the announcement bar (optional)', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_bg',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_announcement_bar_bg',
            array(
                'label'       => __( 'Background Color', 'aqualuxe' ),
                'description' => __( 'Background color for the announcement bar', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_settings',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_announcement_bar_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_announcement_bar_color',
            array(
                'label'       => __( 'Text Color', 'aqualuxe' ),
                'description' => __( 'Text color for the announcement bar', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_settings',
            )
        )
    );

    // Search Toggle
    $wp_customize->add_setting(
        'aqualuxe_search_enable',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_search_enable',
        array(
            'label'       => __( 'Enable Search', 'aqualuxe' ),
            'description' => __( 'Display search icon in the header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        )
    );

    // Search Products Only (if WooCommerce is active)
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_customize->add_setting(
            'aqualuxe_search_products_only',
            array(
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_search_products_only',
            array(
                'label'       => __( 'Search Products Only', 'aqualuxe' ),
                'description' => __( 'Limit search results to products only', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_settings',
                'type'        => 'checkbox',
            )
        );
    }

    // Footer Settings Section
    $wp_customize->add_section(
        'aqualuxe_footer_settings',
        array(
            'title'       => __( 'Footer Settings', 'aqualuxe' ),
            'description' => __( 'Customize the footer section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'       => __( 'Footer Layout', 'aqualuxe' ),
            'description' => __( 'Choose the footer layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_settings',
            'type'        => 'select',
            'choices'     => array(
                'default'      => __( 'Default (4 Columns)', 'aqualuxe' ),
                'three-column' => __( '3 Columns', 'aqualuxe' ),
                'two-column'   => __( '2 Columns', 'aqualuxe' ),
                'one-column'   => __( '1 Column', 'aqualuxe' ),
                'minimal'      => __( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. ' . __( 'All rights reserved.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'       => __( 'Copyright Text', 'aqualuxe' ),
            'description' => __( 'Text to display in the footer copyright area', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_settings',
            'type'        => 'textarea',
        )
    );

    // Footer Background Color
    $wp_customize->add_setting(
        'aqualuxe_footer_bg',
        array(
            'default'           => '#111111',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_bg',
            array(
                'label'       => __( 'Footer Background Color', 'aqualuxe' ),
                'description' => __( 'Background color for the footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_settings',
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_color',
            array(
                'label'       => __( 'Footer Text Color', 'aqualuxe' ),
                'description' => __( 'Text color for the footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_settings',
            )
        )
    );

    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title'       => __( 'Social Media', 'aqualuxe' ),
            'description' => __( 'Add your social media profiles', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_social_facebook',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_facebook',
        array(
            'label'       => __( 'Facebook', 'aqualuxe' ),
            'description' => __( 'Enter your Facebook profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_social_twitter',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_twitter',
        array(
            'label'       => __( 'Twitter', 'aqualuxe' ),
            'description' => __( 'Enter your Twitter profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_social_instagram',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_instagram',
        array(
            'label'       => __( 'Instagram', 'aqualuxe' ),
            'description' => __( 'Enter your Instagram profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_social_linkedin',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_linkedin',
        array(
            'label'       => __( 'LinkedIn', 'aqualuxe' ),
            'description' => __( 'Enter your LinkedIn profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_social_youtube',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_youtube',
        array(
            'label'       => __( 'YouTube', 'aqualuxe' ),
            'description' => __( 'Enter your YouTube channel URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_social_pinterest',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_pinterest',
        array(
            'label'       => __( 'Pinterest', 'aqualuxe' ),
            'description' => __( 'Enter your Pinterest profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // Blog Settings Section
    $wp_customize->add_section(
        'aqualuxe_blog_settings',
        array(
            'title'       => __( 'Blog Settings', 'aqualuxe' ),
            'description' => __( 'Customize the blog section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Blog Style
    $wp_customize->add_setting(
        'aqualuxe_blog_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_style',
        array(
            'label'       => __( 'Blog Style', 'aqualuxe' ),
            'description' => __( 'Choose the blog listing style', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'select',
            'choices'     => array(
                'default'  => __( 'Default', 'aqualuxe' ),
                'grid'     => __( 'Grid', 'aqualuxe' ),
                'masonry'  => __( 'Masonry', 'aqualuxe' ),
                'list'     => __( 'List', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Show Featured Image
    $wp_customize->add_setting(
        'aqualuxe_blog_show_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_show_featured_image',
        array(
            'label'       => __( 'Show Featured Image', 'aqualuxe' ),
            'description' => __( 'Display featured image on blog posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'checkbox',
        )
    );

    // Show Post Meta
    $wp_customize->add_setting(
        'aqualuxe_blog_show_meta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_show_meta',
        array(
            'label'       => __( 'Show Post Meta', 'aqualuxe' ),
            'description' => __( 'Display post meta information (date, author, etc.)', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'checkbox',
        )
    );

    // Show Author Bio
    $wp_customize->add_setting(
        'aqualuxe_show_author_bio',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_author_bio',
        array(
            'label'       => __( 'Show Author Bio', 'aqualuxe' ),
            'description' => __( 'Display author bio on single posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'checkbox',
        )
    );

    // Show Related Posts
    $wp_customize->add_setting(
        'aqualuxe_show_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_related_posts',
        array(
            'label'       => __( 'Show Related Posts', 'aqualuxe' ),
            'description' => __( 'Display related posts on single posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'checkbox',
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => 20,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'       => __( 'Excerpt Length', 'aqualuxe' ),
            'description' => __( 'Number of words in post excerpts', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 1,
            ),
        )
    );

    // WooCommerce Settings Section (if WooCommerce is active)
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_settings',
            array(
                'title'       => __( 'WooCommerce Settings', 'aqualuxe' ),
                'description' => __( 'Customize the WooCommerce section', 'aqualuxe' ),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 60,
            )
        );

        // Shop Layout
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            array(
                'default'           => 'left-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            array(
                'label'       => __( 'Shop Layout', 'aqualuxe' ),
                'description' => __( 'Choose the layout for shop pages', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'select',
                'choices'     => array(
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ),
            )
        );

        // Product Layout
        $wp_customize->add_setting(
            'aqualuxe_product_layout',
            array(
                'default'           => 'no-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_layout',
            array(
                'label'       => __( 'Product Layout', 'aqualuxe' ),
                'description' => __( 'Choose the layout for product pages', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'select',
                'choices'     => array(
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ),
            )
        );

        // Products Per Row
        $wp_customize->add_setting(
            'aqualuxe_products_per_row',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_row',
            array(
                'label'       => __( 'Products Per Row', 'aqualuxe' ),
                'description' => __( 'Number of products to display per row', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 2,
                    'max'  => 6,
                    'step' => 1,
                ),
            )
        );

        // Products Per Page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default'           => 12,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label'       => __( 'Products Per Page', 'aqualuxe' ),
                'description' => __( 'Number of products to display per page', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 4,
                    'max'  => 48,
                    'step' => 4,
                ),
            )
        );

        // Related Products
        $wp_customize->add_setting(
            'aqualuxe_related_products',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products',
            array(
                'label'       => __( 'Show Related Products', 'aqualuxe' ),
                'description' => __( 'Display related products on product pages', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'checkbox',
            )
        );

        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_quick_view',
            array(
                'label'       => __( 'Enable Quick View', 'aqualuxe' ),
                'description' => __( 'Display quick view button on product listings', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'checkbox',
            )
        );

        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_wishlist',
            array(
                'label'       => __( 'Enable Wishlist', 'aqualuxe' ),
                'description' => __( 'Display wishlist functionality', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'checkbox',
            )
        );

        // Ajax Add to Cart
        $wp_customize->add_setting(
            'aqualuxe_ajax_add_to_cart',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_ajax_add_to_cart',
            array(
                'label'       => __( 'Enable Ajax Add to Cart', 'aqualuxe' ),
                'description' => __( 'Add products to cart without page reload', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_settings',
                'type'        => 'checkbox',
            )
        );
    }

    // Typography Settings Section
    $wp_customize->add_section(
        'aqualuxe_typography_settings',
        array(
            'title'       => __( 'Typography Settings', 'aqualuxe' ),
            'description' => __( 'Customize the typography', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 70,
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default'           => 'system',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label'       => __( 'Body Font Family', 'aqualuxe' ),
            'description' => __( 'Select the font family for body text', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography_settings',
            'type'        => 'select',
            'choices'     => array(
                'system'      => __( 'System Font', 'aqualuxe' ),
                'montserrat'  => __( 'Montserrat', 'aqualuxe' ),
                'roboto'      => __( 'Roboto', 'aqualuxe' ),
                'open-sans'   => __( 'Open Sans', 'aqualuxe' ),
                'lato'        => __( 'Lato', 'aqualuxe' ),
                'raleway'     => __( 'Raleway', 'aqualuxe' ),
            ),
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default'           => 'playfair',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label'       => __( 'Heading Font Family', 'aqualuxe' ),
            'description' => __( 'Select the font family for headings', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography_settings',
            'type'        => 'select',
            'choices'     => array(
                'system'      => __( 'System Font', 'aqualuxe' ),
                'playfair'    => __( 'Playfair Display', 'aqualuxe' ),
                'montserrat'  => __( 'Montserrat', 'aqualuxe' ),
                'roboto'      => __( 'Roboto', 'aqualuxe' ),
                'open-sans'   => __( 'Open Sans', 'aqualuxe' ),
                'lato'        => __( 'Lato', 'aqualuxe' ),
                'raleway'     => __( 'Raleway', 'aqualuxe' ),
            ),
        )
    );

    // Body Font Size
    $wp_customize->add_setting(
        'aqualuxe_body_font_size',
        array(
            'default'           => '16px',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_size',
        array(
            'label'       => __( 'Body Font Size', 'aqualuxe' ),
            'description' => __( 'Font size for body text', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography_settings',
            'type'        => 'text',
        )
    );

    // Line Height
    $wp_customize->add_setting(
        'aqualuxe_line_height',
        array(
            'default'           => '1.6',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_line_height',
        array(
            'label'       => __( 'Line Height', 'aqualuxe' ),
            'description' => __( 'Line height for body text', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography_settings',
            'type'        => 'text',
        )
    );

    // Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors_settings',
        array(
            'title'       => __( 'Colors Settings', 'aqualuxe' ),
            'description' => __( 'Customize the colors', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __( 'Primary Color', 'aqualuxe' ),
                'description' => __( 'Primary color for buttons, links, etc.', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_settings',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#005177',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __( 'Secondary Color', 'aqualuxe' ),
                'description' => __( 'Secondary color for accents, etc.', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_settings',
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'       => __( 'Text Color', 'aqualuxe' ),
                'description' => __( 'Main text color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_settings',
            )
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'aqualuxe_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color',
            array(
                'label'       => __( 'Background Color', 'aqualuxe' ),
                'description' => __( 'Main background color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_settings',
            )
        )
    );

    // Dark Mode Settings Section
    $wp_customize->add_section(
        'aqualuxe_dark_mode_settings',
        array(
            'title'       => __( 'Dark Mode Settings', 'aqualuxe' ),
            'description' => __( 'Customize the dark mode', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 90,
        )
    );

    // Enable Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_enable',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_enable',
        array(
            'label'       => __( 'Enable Dark Mode', 'aqualuxe' ),
            'description' => __( 'Allow users to switch to dark mode', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_settings',
            'type'        => 'checkbox',
        )
    );

    // Default Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_default',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_default',
        array(
            'label'       => __( 'Default to Dark Mode', 'aqualuxe' ),
            'description' => __( 'Use dark mode as the default theme', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_settings',
            'type'        => 'checkbox',
        )
    );

    // Auto Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_auto',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_auto',
        array(
            'label'       => __( 'Auto Dark Mode', 'aqualuxe' ),
            'description' => __( 'Automatically switch to dark mode based on user system preferences', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_settings',
            'type'        => 'checkbox',
        )
    );

    // Save in Cookies
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_cookies',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_cookies',
        array(
            'label'       => __( 'Save in Cookies', 'aqualuxe' ),
            'description' => __( 'Save user dark mode preference in cookies', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_settings',
            'type'        => 'checkbox',
        )
    );

    // Dark Mode Background Color
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_bg',
        array(
            'default'           => '#111111',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_bg',
            array(
                'label'       => __( 'Dark Mode Background Color', 'aqualuxe' ),
                'description' => __( 'Background color for dark mode', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_settings',
            )
        )
    );

    // Dark Mode Text Color
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_text',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_text',
            array(
                'label'       => __( 'Dark Mode Text Color', 'aqualuxe' ),
                'description' => __( 'Text color for dark mode', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_settings',
            )
        )
    );

    // Homepage Settings Section
    $wp_customize->add_section(
        'aqualuxe_homepage_settings',
        array(
            'title'       => __( 'Homepage Settings', 'aqualuxe' ),
            'description' => __( 'Customize the homepage sections', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 100,
        )
    );

    // Hero Section
    $wp_customize->add_setting(
        'aqualuxe_hero_title',
        array(
            'default'           => __( 'Bringing Elegance to Aquatic Life – Globally', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_hero_title',
        array(
            'label'       => __( 'Hero Title', 'aqualuxe' ),
            'description' => __( 'Title for the hero section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_hero_description',
        array(
            'default'           => __( 'Premium ornamental fish, aquatic plants, and custom aquarium solutions for enthusiasts and professionals.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_hero_description',
        array(
            'label'       => __( 'Hero Description', 'aqualuxe' ),
            'description' => __( 'Description for the hero section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_hero_button_text',
        array(
            'default'           => __( 'Shop Now', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_hero_button_text',
        array(
            'label'       => __( 'Hero Button Text', 'aqualuxe' ),
            'description' => __( 'Text for the hero button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_hero_button_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_hero_button_url',
        array(
            'label'       => __( 'Hero Button URL', 'aqualuxe' ),
            'description' => __( 'URL for the hero button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_hero_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_hero_image',
            array(
                'label'       => __( 'Hero Background Image', 'aqualuxe' ),
                'description' => __( 'Background image for the hero section', 'aqualuxe' ),
                'section'     => 'aqualuxe_homepage_settings',
            )
        )
    );

    // Featured Products Section
    $wp_customize->add_setting(
        'aqualuxe_featured_products_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_products_show',
        array(
            'label'       => __( 'Show Featured Products Section', 'aqualuxe' ),
            'description' => __( 'Display the featured products section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_products_title',
        array(
            'default'           => __( 'Featured Products', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_products_title',
        array(
            'label'       => __( 'Featured Products Title', 'aqualuxe' ),
            'description' => __( 'Title for the featured products section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_products_description',
        array(
            'default'           => __( 'Discover our premium selection of rare and exotic aquatic species and supplies.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_products_description',
        array(
            'label'       => __( 'Featured Products Description', 'aqualuxe' ),
            'description' => __( 'Description for the featured products section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_products_count',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_products_count',
        array(
            'label'       => __( 'Number of Products', 'aqualuxe' ),
            'description' => __( 'Number of products to display in the featured products section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_products_type',
        array(
            'default'           => 'featured',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_products_type',
        array(
            'label'       => __( 'Products Type', 'aqualuxe' ),
            'description' => __( 'Type of products to display in the featured products section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'select',
            'choices'     => array(
                'featured'     => __( 'Featured Products', 'aqualuxe' ),
                'sale'         => __( 'Sale Products', 'aqualuxe' ),
                'best_selling' => __( 'Best Selling Products', 'aqualuxe' ),
                'new'          => __( 'New Products', 'aqualuxe' ),
            ),
        )
    );

    // About Section
    $wp_customize->add_setting(
        'aqualuxe_about_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_show',
        array(
            'label'       => __( 'Show About Section', 'aqualuxe' ),
            'description' => __( 'Display the about section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_title',
        array(
            'default'           => __( 'About AquaLuxe', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_title',
        array(
            'label'       => __( 'About Title', 'aqualuxe' ),
            'description' => __( 'Title for the about section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_description',
        array(
            'default'           => __( 'Bringing elegance to aquatic life – globally. We are passionate about providing the highest quality ornamental fish, aquatic plants, and custom aquarium solutions for enthusiasts and professionals.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_description',
        array(
            'label'       => __( 'About Description', 'aqualuxe' ),
            'description' => __( 'Description for the about section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_content',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_content',
        array(
            'label'       => __( 'About Content', 'aqualuxe' ),
            'description' => __( 'Content for the about section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_about_image',
            array(
                'label'       => __( 'About Image', 'aqualuxe' ),
                'description' => __( 'Image for the about section', 'aqualuxe' ),
                'section'     => 'aqualuxe_homepage_settings',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_button_text',
        array(
            'default'           => __( 'Learn More', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_button_text',
        array(
            'label'       => __( 'About Button Text', 'aqualuxe' ),
            'description' => __( 'Text for the about button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_about_button_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_about_button_url',
        array(
            'label'       => __( 'About Button URL', 'aqualuxe' ),
            'description' => __( 'URL for the about button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'url',
        )
    );

    // Services Section
    $wp_customize->add_setting(
        'aqualuxe_services_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_services_show',
        array(
            'label'       => __( 'Show Services Section', 'aqualuxe' ),
            'description' => __( 'Display the services section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_services_title',
        array(
            'default'           => __( 'Our Services', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_services_title',
        array(
            'label'       => __( 'Services Title', 'aqualuxe' ),
            'description' => __( 'Title for the services section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_services_description',
        array(
            'default'           => __( 'We offer a comprehensive range of professional aquatic services to meet all your needs.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_services_description',
        array(
            'label'       => __( 'Services Description', 'aqualuxe' ),
            'description' => __( 'Description for the services section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_services_button_text',
        array(
            'default'           => __( 'View All Services', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_services_button_text',
        array(
            'label'       => __( 'Services Button Text', 'aqualuxe' ),
            'description' => __( 'Text for the services button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_services_button_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_services_button_url',
        array(
            'label'       => __( 'Services Button URL', 'aqualuxe' ),
            'description' => __( 'URL for the services button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'url',
        )
    );

    // Testimonials Section
    $wp_customize->add_setting(
        'aqualuxe_testimonials_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_testimonials_show',
        array(
            'label'       => __( 'Show Testimonials Section', 'aqualuxe' ),
            'description' => __( 'Display the testimonials section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_testimonials_title',
        array(
            'default'           => __( 'What Our Customers Say', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_testimonials_title',
        array(
            'label'       => __( 'Testimonials Title', 'aqualuxe' ),
            'description' => __( 'Title for the testimonials section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_testimonials_description',
        array(
            'default'           => __( 'Read what our satisfied customers have to say about their experience with AquaLuxe.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_testimonials_description',
        array(
            'label'       => __( 'Testimonials Description', 'aqualuxe' ),
            'description' => __( 'Description for the testimonials section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    // Latest Posts Section
    $wp_customize->add_setting(
        'aqualuxe_latest_posts_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_show',
        array(
            'label'       => __( 'Show Latest Posts Section', 'aqualuxe' ),
            'description' => __( 'Display the latest posts section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_latest_posts_title',
        array(
            'default'           => __( 'Latest From Our Blog', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_title',
        array(
            'label'       => __( 'Latest Posts Title', 'aqualuxe' ),
            'description' => __( 'Title for the latest posts section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_latest_posts_description',
        array(
            'default'           => __( 'Stay updated with the latest aquarium care guides, aquascaping tips, and industry news.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_description',
        array(
            'label'       => __( 'Latest Posts Description', 'aqualuxe' ),
            'description' => __( 'Description for the latest posts section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_latest_posts_count',
        array(
            'default'           => 3,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_count',
        array(
            'label'       => __( 'Number of Posts', 'aqualuxe' ),
            'description' => __( 'Number of posts to display in the latest posts section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_latest_posts_button_text',
        array(
            'default'           => __( 'View All Posts', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_button_text',
        array(
            'label'       => __( 'Latest Posts Button Text', 'aqualuxe' ),
            'description' => __( 'Text for the latest posts button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_latest_posts_button_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_latest_posts_button_url',
        array(
            'label'       => __( 'Latest Posts Button URL', 'aqualuxe' ),
            'description' => __( 'URL for the latest posts button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'url',
        )
    );

    // Newsletter Section
    $wp_customize->add_setting(
        'aqualuxe_newsletter_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_show',
        array(
            'label'       => __( 'Show Newsletter Section', 'aqualuxe' ),
            'description' => __( 'Display the newsletter section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_title',
        array(
            'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_title',
        array(
            'label'       => __( 'Newsletter Title', 'aqualuxe' ),
            'description' => __( 'Title for the newsletter section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_description',
        array(
            'default'           => __( 'Stay updated with the latest news, rare species arrivals, and exclusive offers.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_description',
        array(
            'label'       => __( 'Newsletter Description', 'aqualuxe' ),
            'description' => __( 'Description for the newsletter section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_button_text',
        array(
            'default'           => __( 'Subscribe', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_button_text',
        array(
            'label'       => __( 'Newsletter Button Text', 'aqualuxe' ),
            'description' => __( 'Text for the newsletter button', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_background',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_newsletter_background',
            array(
                'label'       => __( 'Newsletter Background Image', 'aqualuxe' ),
                'description' => __( 'Background image for the newsletter section', 'aqualuxe' ),
                'section'     => 'aqualuxe_homepage_settings',
            )
        )
    );

    // Partners Section
    $wp_customize->add_setting(
        'aqualuxe_partners_show',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_partners_show',
        array(
            'label'       => __( 'Show Partners Section', 'aqualuxe' ),
            'description' => __( 'Display the partners section on the homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_partners_title',
        array(
            'default'           => __( 'Our Partners', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_partners_title',
        array(
            'label'       => __( 'Partners Title', 'aqualuxe' ),
            'description' => __( 'Title for the partners section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_partners_description',
        array(
            'default'           => __( 'We work with the best brands in the aquatic industry to provide you with top-quality products.', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_partners_description',
        array(
            'label'       => __( 'Partners Description', 'aqualuxe' ),
            'description' => __( 'Description for the partners section', 'aqualuxe' ),
            'section'     => 'aqualuxe_homepage_settings',
            'type'        => 'textarea',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize select field.
 *
 * @param string $input The input to sanitize.
 * @param object $setting The setting object.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox field.
 *
 * @param bool $checked The input to sanitize.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Generate inline CSS for customizer options.
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#005177' );
    $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
    $background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
    $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'system' );
    $heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'playfair' );
    $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16px' );
    $line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
    $container_width = get_theme_mod( 'aqualuxe_container_width', '1200px' );
    $dark_mode_bg = get_theme_mod( 'aqualuxe_dark_mode_bg', '#111111' );
    $dark_mode_text = get_theme_mod( 'aqualuxe_dark_mode_text', '#ffffff' );
    $footer_bg = get_theme_mod( 'aqualuxe_footer_bg', '#111111' );
    $footer_color = get_theme_mod( 'aqualuxe_footer_color', '#ffffff' );

    // Font family variables
    $font_families = array(
        'system'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'playfair'  => '"Playfair Display", serif',
        'montserrat' => '"Montserrat", sans-serif',
        'roboto'    => '"Roboto", sans-serif',
        'open-sans' => '"Open Sans", sans-serif',
        'lato'      => '"Lato", sans-serif',
        'raleway'   => '"Raleway", sans-serif',
    );

    $body_font = isset( $font_families[ $body_font_family ] ) ? $font_families[ $body_font_family ] : $font_families['system'];
    $heading_font = isset( $font_families[ $heading_font_family ] ) ? $font_families[ $heading_font_family ] : $font_families['playfair'];

    $css = "
        :root {
            --primary-color: {$primary_color};
            --primary-color-dark: {$secondary_color};
            --text-color: {$text_color};
            --background-color: {$background_color};
            --body-font: {$body_font};
            --heading-font: {$heading_font};
            --container-width: {$container_width};
            --dark-mode-bg: {$dark_mode_bg};
            --dark-mode-text: {$dark_mode_text};
            --footer-bg: {$footer_bg};
            --footer-color: {$footer_color};
        }

        body {
            font-family: var(--body-font);
            font-size: {$body_font_size};
            line-height: {$line_height};
            color: var(--text-color);
            background-color: var(--background-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }

        .container {
            max-width: var(--container-width);
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .hover\\:bg-primary-dark:hover {
            background-color: var(--primary-color-dark) !important;
        }

        .hover\\:text-primary:hover {
            color: var(--primary-color) !important;
        }

        .dark {
            --text-color: var(--dark-mode-text);
            --background-color: var(--dark-mode-bg);
        }

        .site-footer {
            background-color: var(--footer-bg);
            color: var(--footer-color);
        }
    ";

    // Add the CSS inline
    wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css', 20 );