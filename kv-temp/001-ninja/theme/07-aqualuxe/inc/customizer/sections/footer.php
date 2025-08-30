<?php
/**
 * Footer Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add footer settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer($wp_customize) {
    // Add Footer section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title' => esc_html__('Footer Settings', 'aqualuxe'),
        'priority' => 40,
    ));

    // Footer Style
    $wp_customize->add_setting('aqualuxe_footer_style', array(
        'default' => 'default',
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_footer_style', array(
        'label' => esc_html__('Footer Style', 'aqualuxe'),
        'description' => esc_html__('Select the footer layout style.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 10,
        'choices' => array(
            'default' => array(
                'label' => esc_html__('Default', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/footer-default.png',
            ),
            'centered' => array(
                'label' => esc_html__('Centered', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/footer-centered.png',
            ),
            'minimal' => array(
                'label' => esc_html__('Minimal', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/footer-minimal.png',
            ),
            'dark' => array(
                'label' => esc_html__('Dark', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/footer-dark.png',
            ),
        ),
    )));

    // Footer Columns
    $wp_customize->add_setting('aqualuxe_footer_columns', array(
        'default' => 4,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_footer_columns', array(
        'label' => esc_html__('Footer Widget Columns', 'aqualuxe'),
        'description' => esc_html__('Select the number of widget columns in the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'select',
        'choices' => array(
            1 => esc_html__('1 Column', 'aqualuxe'),
            2 => esc_html__('2 Columns', 'aqualuxe'),
            3 => esc_html__('3 Columns', 'aqualuxe'),
            4 => esc_html__('4 Columns', 'aqualuxe'),
        ),
        'priority' => 20,
    ));

    // Footer Background Color
    $wp_customize->add_setting('aqualuxe_footer_bg_color', array(
        'default' => '#f3f4f6',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bg_color', array(
        'label' => esc_html__('Footer Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 30,
    )));

    // Footer Text Color
    $wp_customize->add_setting('aqualuxe_footer_text_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_text_color', array(
        'label' => esc_html__('Footer Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 40,
    )));

    // Footer Bottom Background Color
    $wp_customize->add_setting('aqualuxe_footer_bottom_bg_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bottom_bg_color', array(
        'label' => esc_html__('Footer Bottom Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 50,
    )));

    // Footer Bottom Text Color
    $wp_customize->add_setting('aqualuxe_footer_bottom_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bottom_text_color', array(
        'label' => esc_html__('Footer Bottom Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 60,
    )));

    // Footer Padding
    $wp_customize->add_setting('aqualuxe_footer_padding', array(
        'default' => json_encode(array(
            'top' => '60',
            'right' => '20',
            'bottom' => '60',
            'left' => '20',
            'unit' => 'px',
        )),
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Dimensions($wp_customize, 'aqualuxe_footer_padding', array(
        'label' => esc_html__('Footer Padding', 'aqualuxe'),
        'description' => esc_html__('Set the padding for the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 70,
    )));

    // Footer Logo
    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label' => esc_html__('Footer Logo', 'aqualuxe'),
        'description' => esc_html__('Upload a logo for the footer. If not set, the main logo will be used.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'mime_type' => 'image',
        'priority' => 80,
    )));

    // Footer Logo Size
    $wp_customize->add_setting('aqualuxe_footer_logo_size', array(
        'default' => 120,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_footer_logo_size', array(
        'label' => esc_html__('Footer Logo Size', 'aqualuxe'),
        'description' => esc_html__('Set the maximum width of the footer logo in pixels.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 90,
        'min' => 50,
        'max' => 300,
        'step' => 1,
        'unit' => 'px',
    )));

    // Show Social Icons
    $wp_customize->add_setting('aqualuxe_show_footer_social', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_footer_social', array(
        'label' => esc_html__('Show Social Icons', 'aqualuxe'),
        'description' => esc_html__('Display social media icons in the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 100,
    )));

    // Footer Copyright Text
    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . esc_html__('All rights reserved.', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label' => esc_html__('Copyright Text', 'aqualuxe'),
        'description' => esc_html__('Enter the copyright text for the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
        'priority' => 110,
    ));

    // Payment Icons
    $wp_customize->add_setting('aqualuxe_show_payment_icons', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_payment_icons', array(
        'label' => esc_html__('Show Payment Icons', 'aqualuxe'),
        'description' => esc_html__('Display payment method icons in the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 120,
        'active_callback' => function() {
            return class_exists('WooCommerce');
        },
    )));

    // Payment Icons
    $wp_customize->add_setting('aqualuxe_payment_icons', array(
        'default' => array('visa', 'mastercard', 'amex', 'paypal'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Sortable($wp_customize, 'aqualuxe_payment_icons', array(
        'label' => esc_html__('Payment Icons', 'aqualuxe'),
        'description' => esc_html__('Select and arrange the payment icons to display.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 130,
        'choices' => array(
            'visa' => esc_html__('Visa', 'aqualuxe'),
            'mastercard' => esc_html__('Mastercard', 'aqualuxe'),
            'amex' => esc_html__('American Express', 'aqualuxe'),
            'discover' => esc_html__('Discover', 'aqualuxe'),
            'paypal' => esc_html__('PayPal', 'aqualuxe'),
            'apple-pay' => esc_html__('Apple Pay', 'aqualuxe'),
            'google-pay' => esc_html__('Google Pay', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return class_exists('WooCommerce') && get_theme_mod('aqualuxe_show_payment_icons', true);
        },
    )));

    // Newsletter Form
    $wp_customize->add_setting('aqualuxe_show_footer_newsletter', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_footer_newsletter', array(
        'label' => esc_html__('Show Newsletter Form', 'aqualuxe'),
        'description' => esc_html__('Display a newsletter signup form in the footer.', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'priority' => 140,
    )));

    // Newsletter Title
    $wp_customize->add_setting('aqualuxe_footer_newsletter_title', array(
        'default' => esc_html__('Subscribe to our newsletter', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_footer_newsletter_title', array(
        'label' => esc_html__('Newsletter Title', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'text',
        'priority' => 150,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_newsletter', true);
        },
    ));

    // Newsletter Text
    $wp_customize->add_setting('aqualuxe_footer_newsletter_text', array(
        'default' => esc_html__('Stay updated with our latest news and special offers.', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_footer_newsletter_text', array(
        'label' => esc_html__('Newsletter Description', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'text',
        'priority' => 160,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_newsletter', true);
        },
    ));

    // Newsletter Form Shortcode
    $wp_customize->add_setting('aqualuxe_footer_newsletter_shortcode', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_footer_newsletter_shortcode', array(
        'label' => esc_html__('Newsletter Form Shortcode', 'aqualuxe'),
        'description' => esc_html__('Enter the shortcode for your newsletter form plugin (e.g., Mailchimp, Contact Form 7).', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'text',
        'priority' => 170,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_newsletter', true);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_footer');