<?php
/**
 * Footer Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add footer settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer($wp_customize) {
    // Add Footer section
    $wp_customize->add_section(
        'aqualuxe_footer',
        array(
            'title'    => esc_html__('Footer Settings', 'aqualuxe'),
            'priority' => 40,
        )
    );

    // Footer Style
    $wp_customize->add_setting(
        'aqualuxe_footer_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_style',
        array(
            'label'   => esc_html__('Footer Style', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'select',
            'choices' => array(
                'default'      => esc_html__('Default', 'aqualuxe'),
                'centered'     => esc_html__('Centered', 'aqualuxe'),
                'minimal'      => esc_html__('Minimal', 'aqualuxe'),
                'dark'         => esc_html__('Dark', 'aqualuxe'),
                'light'        => esc_html__('Light', 'aqualuxe'),
                'stacked'      => esc_html__('Stacked', 'aqualuxe'),
            ),
        )
    );

    // Footer Logo
    $wp_customize->add_setting(
        'aqualuxe_footer_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_footer_logo',
            array(
                'label'     => esc_html__('Footer Logo', 'aqualuxe'),
                'section'   => 'aqualuxe_footer',
                'mime_type' => 'image',
            )
        )
    );

    // Footer Widgets
    $wp_customize->add_setting(
        'aqualuxe_enable_footer_widgets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_footer_widgets',
        array(
            'label'   => esc_html__('Enable Footer Widgets', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    // Footer Widget Columns
    $wp_customize->add_setting(
        'aqualuxe_footer_widget_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_widget_columns',
        array(
            'label'   => esc_html__('Footer Widget Columns', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'select',
            'choices' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ),
        )
    );

    // Footer Background
    $wp_customize->add_setting(
        'aqualuxe_footer_background',
        array(
            'default'           => '#1e3a8a',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_background',
            array(
                'label'   => esc_html__('Footer Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_text_color',
            array(
                'label'   => esc_html__('Footer Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Footer Link Color
    $wp_customize->add_setting(
        'aqualuxe_footer_link_color',
        array(
            'default'           => '#bfdbfe',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_link_color',
            array(
                'label'   => esc_html__('Footer Link Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Footer Link Hover Color
    $wp_customize->add_setting(
        'aqualuxe_footer_link_hover_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_link_hover_color',
            array(
                'label'   => esc_html__('Footer Link Hover Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Footer Widget Title Color
    $wp_customize->add_setting(
        'aqualuxe_footer_widget_title_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_widget_title_color',
            array(
                'label'   => esc_html__('Footer Widget Title Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Footer Padding
    $wp_customize->add_setting(
        'aqualuxe_footer_padding_top',
        array(
            'default'           => '60',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_padding_top',
        array(
            'label'       => esc_html__('Footer Top Padding (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_footer_padding_bottom',
        array(
            'default'           => '60',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_padding_bottom',
        array(
            'label'       => esc_html__('Footer Bottom Padding (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Footer Background Image
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_footer_bg_image',
            array(
                'label'     => esc_html__('Footer Background Image', 'aqualuxe'),
                'section'   => 'aqualuxe_footer',
                'mime_type' => 'image',
            )
        )
    );

    // Footer Background Overlay
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_overlay',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_bg_overlay',
        array(
            'label'   => esc_html__('Enable Background Overlay', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_footer_bg_overlay_color',
        array(
            'default'           => 'rgba(0,0,0,0.5)',
            'sanitize_callback' => 'aqualuxe_sanitize_rgba_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_bg_overlay_color',
            array(
                'label'   => esc_html__('Background Overlay Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => sprintf(esc_html__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'   => esc_html__('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'textarea',
        )
    );

    // Bottom Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_bottom_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_bottom_bar',
        array(
            'label'   => esc_html__('Enable Bottom Bar', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    // Bottom Bar Background
    $wp_customize->add_setting(
        'aqualuxe_bottom_bar_background',
        array(
            'default'           => '#172554',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_bottom_bar_background',
            array(
                'label'   => esc_html__('Bottom Bar Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Bottom Bar Text Color
    $wp_customize->add_setting(
        'aqualuxe_bottom_bar_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_bottom_bar_text_color',
            array(
                'label'   => esc_html__('Bottom Bar Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );

    // Payment Icons
    $wp_customize->add_setting(
        'aqualuxe_enable_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_payment_icons',
        array(
            'label'   => esc_html__('Enable Payment Icons', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    // Payment Icon Options
    $payment_icons = array(
        'visa'       => esc_html__('Visa', 'aqualuxe'),
        'mastercard' => esc_html__('MasterCard', 'aqualuxe'),
        'amex'       => esc_html__('American Express', 'aqualuxe'),
        'discover'   => esc_html__('Discover', 'aqualuxe'),
        'paypal'     => esc_html__('PayPal', 'aqualuxe'),
        'apple_pay'  => esc_html__('Apple Pay', 'aqualuxe'),
        'google_pay' => esc_html__('Google Pay', 'aqualuxe'),
    );

    foreach ($payment_icons as $icon_id => $icon_name) {
        $wp_customize->add_setting(
            'aqualuxe_payment_icon_' . $icon_id,
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_payment_icon_' . $icon_id,
            array(
                'label'   => $icon_name,
                'section' => 'aqualuxe_footer',
                'type'    => 'checkbox',
            )
        );
    }

    // Footer Newsletter
    $wp_customize->add_setting(
        'aqualuxe_enable_footer_newsletter',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_footer_newsletter',
        array(
            'label'   => esc_html__('Enable Footer Newsletter', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_title',
        array(
            'default'           => esc_html__('Subscribe to our newsletter', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_title',
        array(
            'label'   => esc_html__('Newsletter Title', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_text',
        array(
            'default'           => esc_html__('Stay updated with our latest news and offers.', 'aqualuxe'),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_text',
        array(
            'label'   => esc_html__('Newsletter Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'aqualuxe_sanitize_shortcode',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_shortcode',
        array(
            'label'       => esc_html__('Newsletter Shortcode', 'aqualuxe'),
            'description' => esc_html__('Enter the shortcode for your newsletter form (e.g., from Mailchimp, Contact Form 7, etc.).', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'text',
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_enable_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_back_to_top',
        array(
            'label'   => esc_html__('Enable Back to Top Button', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_back_to_top_style',
        array(
            'default'           => 'circle',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top_style',
        array(
            'label'   => esc_html__('Back to Top Button Style', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'select',
            'choices' => array(
                'circle'    => esc_html__('Circle', 'aqualuxe'),
                'square'    => esc_html__('Square', 'aqualuxe'),
                'rounded'   => esc_html__('Rounded', 'aqualuxe'),
                'text'      => esc_html__('Text', 'aqualuxe'),
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_back_to_top_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top_position',
        array(
            'label'   => esc_html__('Back to Top Button Position', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'select',
            'choices' => array(
                'right' => esc_html__('Right', 'aqualuxe'),
                'left'  => esc_html__('Left', 'aqualuxe'),
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_back_to_top_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_back_to_top_color',
            array(
                'label'   => esc_html__('Back to Top Button Color', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
            )
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_footer');