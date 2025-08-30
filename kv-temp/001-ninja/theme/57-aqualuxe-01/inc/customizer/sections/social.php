<?php
/**
 * Social Media Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add social media settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_social($wp_customize) {
    // Add Social Media section
    $wp_customize->add_section(
        'aqualuxe_social',
        array(
            'title'    => esc_html__('Social Media', 'aqualuxe'),
            'priority' => 90,
        )
    );

    // Facebook URL
    $wp_customize->add_setting(
        'aqualuxe_facebook_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_url',
        array(
            'label'   => esc_html__('Facebook URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // Twitter URL
    $wp_customize->add_setting(
        'aqualuxe_twitter_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_url',
        array(
            'label'   => esc_html__('Twitter URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // Instagram URL
    $wp_customize->add_setting(
        'aqualuxe_instagram_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram_url',
        array(
            'label'   => esc_html__('Instagram URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // LinkedIn URL
    $wp_customize->add_setting(
        'aqualuxe_linkedin_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin_url',
        array(
            'label'   => esc_html__('LinkedIn URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // YouTube URL
    $wp_customize->add_setting(
        'aqualuxe_youtube_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube_url',
        array(
            'label'   => esc_html__('YouTube URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // Pinterest URL
    $wp_customize->add_setting(
        'aqualuxe_pinterest_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pinterest_url',
        array(
            'label'   => esc_html__('Pinterest URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // TikTok URL
    $wp_customize->add_setting(
        'aqualuxe_tiktok_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_tiktok_url',
        array(
            'label'   => esc_html__('TikTok URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // WhatsApp URL
    $wp_customize->add_setting(
        'aqualuxe_whatsapp_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_whatsapp_url',
        array(
            'label'   => esc_html__('WhatsApp URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // Telegram URL
    $wp_customize->add_setting(
        'aqualuxe_telegram_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_telegram_url',
        array(
            'label'   => esc_html__('Telegram URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        )
    );

    // Social Icons Display
    $wp_customize->add_setting(
        'aqualuxe_show_social_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_social_header',
        array(
            'label'   => esc_html__('Show Social Icons in Header', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_social_footer',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_social_footer',
        array(
            'label'   => esc_html__('Show Social Icons in Footer', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_social_mobile',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_social_mobile',
        array(
            'label'   => esc_html__('Show Social Icons in Mobile Menu', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'checkbox',
        )
    );

    // Social Icons Style
    $wp_customize->add_setting(
        'aqualuxe_social_icons_style',
        array(
            'default'           => 'filled',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icons_style',
        array(
            'label'   => esc_html__('Social Icons Style', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'filled'    => esc_html__('Filled', 'aqualuxe'),
                'outlined'  => esc_html__('Outlined', 'aqualuxe'),
                'minimal'   => esc_html__('Minimal', 'aqualuxe'),
                'rounded'   => esc_html__('Rounded', 'aqualuxe'),
                'circle'    => esc_html__('Circle', 'aqualuxe'),
                'square'    => esc_html__('Square', 'aqualuxe'),
            ),
        )
    );

    // Social Icons Size
    $wp_customize->add_setting(
        'aqualuxe_social_icons_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icons_size',
        array(
            'label'   => esc_html__('Social Icons Size', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'small'     => esc_html__('Small', 'aqualuxe'),
                'medium'    => esc_html__('Medium', 'aqualuxe'),
                'large'     => esc_html__('Large', 'aqualuxe'),
            ),
        )
    );

    // Social Icons Color
    $wp_customize->add_setting(
        'aqualuxe_social_icons_color_type',
        array(
            'default'           => 'brand',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icons_color_type',
        array(
            'label'   => esc_html__('Social Icons Color', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'brand'     => esc_html__('Brand Colors', 'aqualuxe'),
                'custom'    => esc_html__('Custom Color', 'aqualuxe'),
                'theme'     => esc_html__('Theme Color', 'aqualuxe'),
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_social_icons_custom_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_social_icons_custom_color',
            array(
                'label'   => esc_html__('Custom Social Icons Color', 'aqualuxe'),
                'section' => 'aqualuxe_social',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_social_icons_color_type', 'brand') === 'custom';
                },
            )
        )
    );

    // Social Share Buttons
    $wp_customize->add_setting(
        'aqualuxe_enable_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_social_sharing',
        array(
            'label'   => esc_html__('Enable Social Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'checkbox',
        )
    );

    // Social Share Networks
    $social_networks = array(
        'facebook'  => esc_html__('Facebook', 'aqualuxe'),
        'twitter'   => esc_html__('Twitter', 'aqualuxe'),
        'linkedin'  => esc_html__('LinkedIn', 'aqualuxe'),
        'pinterest' => esc_html__('Pinterest', 'aqualuxe'),
        'email'     => esc_html__('Email', 'aqualuxe'),
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting(
            'aqualuxe_enable_' . $network . '_sharing',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_' . $network . '_sharing',
            array(
                'label'   => sprintf(esc_html__('Enable %s Sharing', 'aqualuxe'), $label),
                'section' => 'aqualuxe_social',
                'type'    => 'checkbox',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_enable_social_sharing', true);
                },
            )
        );
    }

    // Social Share Position
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_position',
        array(
            'default'           => 'after_content',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_position',
        array(
            'label'   => esc_html__('Social Sharing Position', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'before_content' => esc_html__('Before Content', 'aqualuxe'),
                'after_content'  => esc_html__('After Content', 'aqualuxe'),
                'both'           => esc_html__('Before and After Content', 'aqualuxe'),
                'floating'       => esc_html__('Floating Sidebar', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    // Social Share Style
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_style',
        array(
            'default'           => 'icon_text',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_style',
        array(
            'label'   => esc_html__('Social Sharing Style', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'icon_only' => esc_html__('Icon Only', 'aqualuxe'),
                'text_only' => esc_html__('Text Only', 'aqualuxe'),
                'icon_text' => esc_html__('Icon and Text', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    // Social Share Button Shape
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_shape',
        array(
            'default'           => 'rounded',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_shape',
        array(
            'label'   => esc_html__('Social Sharing Button Shape', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'select',
            'choices' => array(
                'square'  => esc_html__('Square', 'aqualuxe'),
                'rounded' => esc_html__('Rounded', 'aqualuxe'),
                'pill'    => esc_html__('Pill', 'aqualuxe'),
                'circle'  => esc_html__('Circle', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true) && 
                       get_theme_mod('aqualuxe_social_sharing_style', 'icon_text') !== 'text_only';
            },
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_social');