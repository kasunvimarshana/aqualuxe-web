<?php
/**
 * Social Media Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add social media settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_social_media($wp_customize) {
    // Add Social Media section
    $wp_customize->add_section('aqualuxe_social_media', array(
        'title' => esc_html__('Social Media', 'aqualuxe'),
        'priority' => 110,
    ));

    // Social Media Profiles
    $wp_customize->add_setting('aqualuxe_social_media_profiles_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_social_media_profiles_heading', array(
        'label' => esc_html__('Social Media Profiles', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 10,
    )));

    // Facebook URL
    $wp_customize->add_setting('aqualuxe_facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_facebook_url', array(
        'label' => esc_html__('Facebook URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://facebook.com/yourusername',
        ),
        'priority' => 20,
    ));

    // Twitter URL
    $wp_customize->add_setting('aqualuxe_twitter_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_twitter_url', array(
        'label' => esc_html__('Twitter URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://twitter.com/yourusername',
        ),
        'priority' => 30,
    ));

    // Instagram URL
    $wp_customize->add_setting('aqualuxe_instagram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_instagram_url', array(
        'label' => esc_html__('Instagram URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://instagram.com/yourusername',
        ),
        'priority' => 40,
    ));

    // LinkedIn URL
    $wp_customize->add_setting('aqualuxe_linkedin_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_linkedin_url', array(
        'label' => esc_html__('LinkedIn URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://linkedin.com/in/yourusername',
        ),
        'priority' => 50,
    ));

    // YouTube URL
    $wp_customize->add_setting('aqualuxe_youtube_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_youtube_url', array(
        'label' => esc_html__('YouTube URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://youtube.com/c/yourusername',
        ),
        'priority' => 60,
    ));

    // Pinterest URL
    $wp_customize->add_setting('aqualuxe_pinterest_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_pinterest_url', array(
        'label' => esc_html__('Pinterest URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://pinterest.com/yourusername',
        ),
        'priority' => 70,
    ));

    // TikTok URL
    $wp_customize->add_setting('aqualuxe_tiktok_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_tiktok_url', array(
        'label' => esc_html__('TikTok URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://tiktok.com/@yourusername',
        ),
        'priority' => 80,
    ));

    // Snapchat URL
    $wp_customize->add_setting('aqualuxe_snapchat_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_snapchat_url', array(
        'label' => esc_html__('Snapchat URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://snapchat.com/add/yourusername',
        ),
        'priority' => 90,
    ));

    // WhatsApp URL
    $wp_customize->add_setting('aqualuxe_whatsapp_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_whatsapp_url', array(
        'label' => esc_html__('WhatsApp URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://wa.me/yournumber',
        ),
        'priority' => 100,
    ));

    // Telegram URL
    $wp_customize->add_setting('aqualuxe_telegram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_telegram_url', array(
        'label' => esc_html__('Telegram URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'url',
        'input_attrs' => array(
            'placeholder' => 'https://t.me/yourusername',
        ),
        'priority' => 110,
    ));

    // Social Icons Display
    $wp_customize->add_setting('aqualuxe_social_icons_display_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_social_icons_display_heading', array(
        'label' => esc_html__('Social Icons Display', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 120,
    )));

    // Show in Header
    $wp_customize->add_setting('aqualuxe_show_social_header', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_social_header', array(
        'label' => esc_html__('Show in Header', 'aqualuxe'),
        'description' => esc_html__('Display social icons in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 130,
    )));

    // Show in Footer
    $wp_customize->add_setting('aqualuxe_show_social_footer', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_social_footer', array(
        'label' => esc_html__('Show in Footer', 'aqualuxe'),
        'description' => esc_html__('Display social icons in the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 140,
    )));

    // Show in Mobile Menu
    $wp_customize->add_setting('aqualuxe_show_social_mobile', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_social_mobile', array(
        'label' => esc_html__('Show in Mobile Menu', 'aqualuxe'),
        'description' => esc_html__('Display social icons in the mobile menu.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 150,
    )));

    // Social Icons Order
    $wp_customize->add_setting('aqualuxe_social_icons_order', array(
        'default' => array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'pinterest'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Sortable($wp_customize, 'aqualuxe_social_icons_order', array(
        'label' => esc_html__('Social Icons Order', 'aqualuxe'),
        'description' => esc_html__('Drag and drop to reorder social icons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 160,
        'choices' => array(
            'facebook' => esc_html__('Facebook', 'aqualuxe'),
            'twitter' => esc_html__('Twitter', 'aqualuxe'),
            'instagram' => esc_html__('Instagram', 'aqualuxe'),
            'linkedin' => esc_html__('LinkedIn', 'aqualuxe'),
            'youtube' => esc_html__('YouTube', 'aqualuxe'),
            'pinterest' => esc_html__('Pinterest', 'aqualuxe'),
            'tiktok' => esc_html__('TikTok', 'aqualuxe'),
            'snapchat' => esc_html__('Snapchat', 'aqualuxe'),
            'whatsapp' => esc_html__('WhatsApp', 'aqualuxe'),
            'telegram' => esc_html__('Telegram', 'aqualuxe'),
        ),
    )));

    // Social Icons Style
    $wp_customize->add_setting('aqualuxe_social_icons_style', array(
        'default' => 'filled-circle',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_social_icons_style', array(
        'label' => esc_html__('Social Icons Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for social icons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'select',
        'choices' => array(
            'filled-circle' => esc_html__('Filled Circle', 'aqualuxe'),
            'outlined-circle' => esc_html__('Outlined Circle', 'aqualuxe'),
            'filled-square' => esc_html__('Filled Square', 'aqualuxe'),
            'outlined-square' => esc_html__('Outlined Square', 'aqualuxe'),
            'plain' => esc_html__('Plain', 'aqualuxe'),
        ),
        'priority' => 170,
    ));

    // Social Icons Size
    $wp_customize->add_setting('aqualuxe_social_icons_size', array(
        'default' => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_social_icons_size', array(
        'label' => esc_html__('Social Icons Size', 'aqualuxe'),
        'description' => esc_html__('Select the size for social icons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'select',
        'choices' => array(
            'small' => esc_html__('Small', 'aqualuxe'),
            'medium' => esc_html__('Medium', 'aqualuxe'),
            'large' => esc_html__('Large', 'aqualuxe'),
        ),
        'priority' => 180,
    ));

    // Social Icons Color
    $wp_customize->add_setting('aqualuxe_social_icons_color_type', array(
        'default' => 'brand',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_social_icons_color_type', array(
        'label' => esc_html__('Social Icons Color', 'aqualuxe'),
        'description' => esc_html__('Select how to color the social icons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'select',
        'choices' => array(
            'brand' => esc_html__('Brand Colors', 'aqualuxe'),
            'custom' => esc_html__('Custom Color', 'aqualuxe'),
            'monochrome' => esc_html__('Monochrome', 'aqualuxe'),
        ),
        'priority' => 190,
    ));

    // Custom Social Icons Color
    $wp_customize->add_setting('aqualuxe_social_icons_custom_color', array(
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_social_icons_custom_color', array(
        'label' => esc_html__('Custom Icon Color', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 200,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_social_icons_color_type', 'brand') === 'custom';
        },
    )));

    // Social Sharing
    $wp_customize->add_setting('aqualuxe_social_sharing_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_social_sharing_heading', array(
        'label' => esc_html__('Social Sharing', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 210,
    )));

    // Enable Social Sharing
    $wp_customize->add_setting('aqualuxe_enable_social_sharing', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_social_sharing', array(
        'label' => esc_html__('Enable Social Sharing', 'aqualuxe'),
        'description' => esc_html__('Display social sharing buttons on posts and products.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 220,
    )));

    // Social Sharing Position
    $wp_customize->add_setting('aqualuxe_social_sharing_position', array(
        'default' => 'after-content',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_social_sharing_position', array(
        'label' => esc_html__('Sharing Buttons Position', 'aqualuxe'),
        'description' => esc_html__('Select where to display social sharing buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'select',
        'choices' => array(
            'before-content' => esc_html__('Before Content', 'aqualuxe'),
            'after-content' => esc_html__('After Content', 'aqualuxe'),
            'both' => esc_html__('Before and After Content', 'aqualuxe'),
            'floating' => esc_html__('Floating Sidebar', 'aqualuxe'),
        ),
        'priority' => 230,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));

    // Social Sharing Networks
    $wp_customize->add_setting('aqualuxe_social_sharing_networks', array(
        'default' => array('facebook', 'twitter', 'pinterest', 'linkedin', 'email'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Sortable($wp_customize, 'aqualuxe_social_sharing_networks', array(
        'label' => esc_html__('Sharing Networks', 'aqualuxe'),
        'description' => esc_html__('Select and arrange the social networks for sharing.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 240,
        'choices' => array(
            'facebook' => esc_html__('Facebook', 'aqualuxe'),
            'twitter' => esc_html__('Twitter', 'aqualuxe'),
            'pinterest' => esc_html__('Pinterest', 'aqualuxe'),
            'linkedin' => esc_html__('LinkedIn', 'aqualuxe'),
            'reddit' => esc_html__('Reddit', 'aqualuxe'),
            'tumblr' => esc_html__('Tumblr', 'aqualuxe'),
            'whatsapp' => esc_html__('WhatsApp', 'aqualuxe'),
            'telegram' => esc_html__('Telegram', 'aqualuxe'),
            'email' => esc_html__('Email', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));

    // Show Share Count
    $wp_customize->add_setting('aqualuxe_show_share_count', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_share_count', array(
        'label' => esc_html__('Show Share Count', 'aqualuxe'),
        'description' => esc_html__('Display the number of shares for each network.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 250,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));

    // Social Sharing Style
    $wp_customize->add_setting('aqualuxe_social_sharing_style', array(
        'default' => 'buttons',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_social_sharing_style', array(
        'label' => esc_html__('Sharing Buttons Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for social sharing buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'select',
        'choices' => array(
            'buttons' => esc_html__('Buttons', 'aqualuxe'),
            'icons' => esc_html__('Icons Only', 'aqualuxe'),
            'minimal' => esc_html__('Minimal', 'aqualuxe'),
        ),
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));

    // Show on Posts
    $wp_customize->add_setting('aqualuxe_show_sharing_on_posts', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_sharing_on_posts', array(
        'label' => esc_html__('Show on Posts', 'aqualuxe'),
        'description' => esc_html__('Display social sharing buttons on blog posts.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 270,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));

    // Show on Pages
    $wp_customize->add_setting('aqualuxe_show_sharing_on_pages', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_sharing_on_pages', array(
        'label' => esc_html__('Show on Pages', 'aqualuxe'),
        'description' => esc_html__('Display social sharing buttons on pages.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 280,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));

    // Show on Products
    $wp_customize->add_setting('aqualuxe_show_sharing_on_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_sharing_on_products', array(
        'label' => esc_html__('Show on Products', 'aqualuxe'),
        'description' => esc_html__('Display social sharing buttons on products.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'priority' => 290,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true) && class_exists('WooCommerce');
        },
    )));

    // Social Sharing Title
    $wp_customize->add_setting('aqualuxe_social_sharing_title', array(
        'default' => esc_html__('Share this:', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_social_sharing_title', array(
        'label' => esc_html__('Sharing Title', 'aqualuxe'),
        'description' => esc_html__('Text to display before sharing buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_social_media',
        'type' => 'text',
        'priority' => 300,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_social_media');