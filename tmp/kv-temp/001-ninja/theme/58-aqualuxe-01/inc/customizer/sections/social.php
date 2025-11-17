<?php
/**
 * AquaLuxe Theme Customizer - Social Media Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add social media settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_settings($wp_customize) {
    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_section',
        array(
            'title'       => __('Social Media Settings', 'aqualuxe'),
            'description' => __('Configure social media profiles and sharing options.', 'aqualuxe'),
            'priority'    => 60,
        )
    );

    // Social Media Profiles Heading
    $wp_customize->add_setting(
        'aqualuxe_social_profiles_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_social_profiles_heading',
            array(
                'label'   => __('Social Media Profiles', 'aqualuxe'),
                'section' => 'aqualuxe_social_section',
            )
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_options[social_facebook]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_facebook]',
        array(
            'label'       => __('Facebook URL', 'aqualuxe'),
            'description' => __('Enter your Facebook profile or page URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://facebook.com/yourusername',
            ),
        )
    );

    // Twitter/X
    $wp_customize->add_setting(
        'aqualuxe_options[social_twitter]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_twitter]',
        array(
            'label'       => __('Twitter/X URL', 'aqualuxe'),
            'description' => __('Enter your Twitter/X profile URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://twitter.com/yourusername',
            ),
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_options[social_instagram]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_instagram]',
        array(
            'label'       => __('Instagram URL', 'aqualuxe'),
            'description' => __('Enter your Instagram profile URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://instagram.com/yourusername',
            ),
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_options[social_linkedin]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_linkedin]',
        array(
            'label'       => __('LinkedIn URL', 'aqualuxe'),
            'description' => __('Enter your LinkedIn profile or company URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://linkedin.com/in/yourusername',
            ),
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_options[social_youtube]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_youtube]',
        array(
            'label'       => __('YouTube URL', 'aqualuxe'),
            'description' => __('Enter your YouTube channel URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://youtube.com/c/yourchannel',
            ),
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_options[social_pinterest]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_pinterest]',
        array(
            'label'       => __('Pinterest URL', 'aqualuxe'),
            'description' => __('Enter your Pinterest profile URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://pinterest.com/yourusername',
            ),
        )
    );

    // TikTok
    $wp_customize->add_setting(
        'aqualuxe_options[social_tiktok]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_tiktok]',
        array(
            'label'       => __('TikTok URL', 'aqualuxe'),
            'description' => __('Enter your TikTok profile URL.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
            'input_attrs' => array(
                'placeholder' => 'https://tiktok.com/@yourusername',
            ),
        )
    );

    // Social Media Display Heading
    $wp_customize->add_setting(
        'aqualuxe_social_display_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_social_display_heading',
            array(
                'label'   => __('Social Media Display', 'aqualuxe'),
                'section' => 'aqualuxe_social_section',
            )
        )
    );

    // Show in Header
    $wp_customize->add_setting(
        'aqualuxe_options[social_show_header]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_show_header]',
        array(
            'label'       => __('Show in Header', 'aqualuxe'),
            'description' => __('Display social media icons in the header.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );

    // Show in Footer
    $wp_customize->add_setting(
        'aqualuxe_options[social_show_footer]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_show_footer]',
        array(
            'label'       => __('Show in Footer', 'aqualuxe'),
            'description' => __('Display social media icons in the footer.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );

    // Social Icon Style
    $wp_customize->add_setting(
        'aqualuxe_options[social_icon_style]',
        array(
            'default'           => 'filled',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_icon_style]',
        array(
            'label'       => __('Social Icon Style', 'aqualuxe'),
            'description' => __('Choose the style for social media icons.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'select',
            'choices'     => array(
                'filled'    => __('Filled', 'aqualuxe'),
                'outlined'  => __('Outlined', 'aqualuxe'),
                'branded'   => __('Branded Colors', 'aqualuxe'),
                'monochrome' => __('Monochrome', 'aqualuxe'),
            ),
        )
    );

    // Social Icon Size
    $wp_customize->add_setting(
        'aqualuxe_options[social_icon_size]',
        array(
            'default'           => 'medium',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_icon_size]',
        array(
            'label'       => __('Social Icon Size', 'aqualuxe'),
            'description' => __('Choose the size for social media icons.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'select',
            'choices'     => array(
                'small'  => __('Small', 'aqualuxe'),
                'medium' => __('Medium', 'aqualuxe'),
                'large'  => __('Large', 'aqualuxe'),
            ),
        )
    );

    // Social Sharing Heading
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_social_sharing_heading',
            array(
                'label'   => __('Social Sharing', 'aqualuxe'),
                'section' => 'aqualuxe_social_section',
            )
        )
    );

    // Enable Social Sharing
    $wp_customize->add_setting(
        'aqualuxe_options[enable_social_sharing]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_social_sharing]',
        array(
            'label'       => __('Enable Social Sharing', 'aqualuxe'),
            'description' => __('Display social sharing buttons on posts and pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );

    // Social Sharing Networks
    $wp_customize->add_setting(
        'aqualuxe_options[social_sharing_networks]',
        array(
            'default'           => array('facebook', 'twitter', 'linkedin', 'pinterest'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Multi_Checkbox_Control(
            $wp_customize,
            'aqualuxe_options[social_sharing_networks]',
            array(
                'label'       => __('Social Sharing Networks', 'aqualuxe'),
                'description' => __('Select which networks to include in social sharing.', 'aqualuxe'),
                'section'     => 'aqualuxe_social_section',
                'choices'     => array(
                    'facebook'  => __('Facebook', 'aqualuxe'),
                    'twitter'   => __('Twitter/X', 'aqualuxe'),
                    'linkedin'  => __('LinkedIn', 'aqualuxe'),
                    'pinterest' => __('Pinterest', 'aqualuxe'),
                    'reddit'    => __('Reddit', 'aqualuxe'),
                    'email'     => __('Email', 'aqualuxe'),
                    'whatsapp'  => __('WhatsApp', 'aqualuxe'),
                    'telegram'  => __('Telegram', 'aqualuxe'),
                ),
                'active_callback' => function() {
                    $options = get_option('aqualuxe_options', array());
                    return isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
                },
            )
        )
    );

    // Social Sharing Position
    $wp_customize->add_setting(
        'aqualuxe_options[social_sharing_position]',
        array(
            'default'           => 'after_content',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_sharing_position]',
        array(
            'label'       => __('Social Sharing Position', 'aqualuxe'),
            'description' => __('Choose where to display social sharing buttons.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'select',
            'choices'     => array(
                'before_content' => __('Before Content', 'aqualuxe'),
                'after_content'  => __('After Content', 'aqualuxe'),
                'both'           => __('Before and After Content', 'aqualuxe'),
                'floating'       => __('Floating Sidebar', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
            },
        )
    );

    // Social Sharing Style
    $wp_customize->add_setting(
        'aqualuxe_options[social_sharing_style]',
        array(
            'default'           => 'buttons',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[social_sharing_style]',
        array(
            'label'       => __('Social Sharing Style', 'aqualuxe'),
            'description' => __('Choose the style for social sharing buttons.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'select',
            'choices'     => array(
                'buttons'   => __('Buttons', 'aqualuxe'),
                'icons'     => __('Icons Only', 'aqualuxe'),
                'minimal'   => __('Minimal', 'aqualuxe'),
                'boxed'     => __('Boxed', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
            },
        )
    );

    // Show Share Count
    $wp_customize->add_setting(
        'aqualuxe_options[show_share_count]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_share_count]',
        array(
            'label'       => __('Show Share Count', 'aqualuxe'),
            'description' => __('Display the number of shares for each network.', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
            },
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_social_settings');

/**
 * Custom control for section headings
 */
if (!class_exists('AquaLuxe_Customize_Heading_Control')) {
    class AquaLuxe_Customize_Heading_Control extends WP_Customize_Control {
        public $type = 'heading';

        public function render_content() {
            if (!empty($this->label)) {
                echo '<h4 class="aqualuxe-customizer-heading">' . esc_html($this->label) . '</h4>';
            }
            if (!empty($this->description)) {
                echo '<span class="description customize-control-description">' . esc_html($this->description) . '</span>';
            }
            echo '<style>
                .aqualuxe-customizer-heading {
                    border-bottom: 1px solid #ddd;
                    font-size: 14px;
                    padding: 10px 0;
                    margin: 15px 0 5px;
                    color: #0073aa;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
            </style>';
        }
    }
}