<?php
/**
 * AquaLuxe Theme Customizer - Footer Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add footer settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer($wp_customize) {
    // Add Footer section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer', 'aqualuxe'),
        'priority' => 70,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Footer Layout
    $wp_customize->add_setting('aqualuxe_footer_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_layout', array(
        'label'       => __('Footer Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            'default'  => __('Default', 'aqualuxe'),
            'simple'   => __('Simple', 'aqualuxe'),
            'widgets'  => __('Widgets', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal'  => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Footer Columns
    $wp_customize->add_setting('aqualuxe_footer_columns', array(
        'default'           => 4,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_columns', array(
        'label'       => __('Footer Widget Columns', 'aqualuxe'),
        'description' => __('Number of columns for footer widgets.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 6,
            'step' => 1,
        ),
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_footer_layout', 'default');
            return $layout === 'default' || $layout === 'widgets';
        },
    ));
    
    // Footer Logo
    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer (optional).', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'mime_type'   => 'image',
    )));
    
    // Footer Logo Width
    $wp_customize->add_setting('aqualuxe_footer_logo_width', array(
        'default'           => 150,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_logo_width', array(
        'label'       => __('Footer Logo Width (px)', 'aqualuxe'),
        'description' => __('Set the width of the footer logo.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_footer_logo', '') !== '';
        },
    ));
    
    // Show Footer Widgets
    $wp_customize->add_setting('aqualuxe_show_footer_widgets', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_footer_widgets', array(
        'label'       => __('Show Footer Widgets', 'aqualuxe'),
        'description' => __('Display widget areas in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_footer_layout', 'default');
            return $layout === 'default' || $layout === 'widgets';
        },
    ));
    
    // Show Footer Bottom
    $wp_customize->add_setting('aqualuxe_show_footer_bottom', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_footer_bottom', array(
        'label'       => __('Show Footer Bottom', 'aqualuxe'),
        'description' => __('Display the bottom section of the footer with copyright and menu.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));
    
    // Copyright Text
    $wp_customize->add_setting('aqualuxe_copyright_text', array(
        'default'           => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_copyright_text', array(
        'label'       => __('Copyright Text', 'aqualuxe'),
        'description' => __('Enter the copyright text for the footer. Use {year} for the current year.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true);
        },
    ));
    
    // Show Social Icons
    $wp_customize->add_setting('aqualuxe_footer_show_social', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_show_social', array(
        'label'       => __('Show Social Icons', 'aqualuxe'),
        'description' => __('Display social icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true);
        },
    ));
    
    // Show Payment Icons
    $wp_customize->add_setting('aqualuxe_footer_show_payment', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_show_payment', array(
        'label'       => __('Show Payment Icons', 'aqualuxe'),
        'description' => __('Display payment method icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true) && aqualuxe_is_woocommerce_active();
        },
    ));
    
    // Payment Icons
    $wp_customize->add_setting('aqualuxe_payment_icons', array(
        'default'           => array('visa', 'mastercard', 'amex', 'paypal'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));
    
    $wp_customize->add_control(new AquaLuxe_Multi_Checkbox_Control($wp_customize, 'aqualuxe_payment_icons', array(
        'label'       => __('Payment Icons', 'aqualuxe'),
        'description' => __('Select which payment icons to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'choices'     => array(
            'visa'       => __('Visa', 'aqualuxe'),
            'mastercard' => __('Mastercard', 'aqualuxe'),
            'amex'       => __('American Express', 'aqualuxe'),
            'discover'   => __('Discover', 'aqualuxe'),
            'paypal'     => __('PayPal', 'aqualuxe'),
            'apple-pay'  => __('Apple Pay', 'aqualuxe'),
            'google-pay' => __('Google Pay', 'aqualuxe'),
            'stripe'     => __('Stripe', 'aqualuxe'),
            'bitcoin'    => __('Bitcoin', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_footer_show_payment', true) && 
                   get_theme_mod('aqualuxe_show_footer_bottom', true) && 
                   aqualuxe_is_woocommerce_active();
        },
    )));
    
    // Footer Background
    $wp_customize->add_setting('aqualuxe_footer_background', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_footer_background', array(
        'label'       => __('Footer Background Image', 'aqualuxe'),
        'description' => __('Upload a background image for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));
    
    // Footer Background Color
    $wp_customize->add_setting('aqualuxe_footer_background_color', array(
        'default'           => '#23282d',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_background_color', array(
        'label'       => __('Footer Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));
    
    // Footer Text Color
    $wp_customize->add_setting('aqualuxe_footer_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_text_color', array(
        'label'       => __('Footer Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));
    
    // Footer Link Color
    $wp_customize->add_setting('aqualuxe_footer_link_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_link_color', array(
        'label'       => __('Footer Link Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));
    
    // Footer Link Hover Color
    $wp_customize->add_setting('aqualuxe_footer_link_hover_color', array(
        'default'           => '#00a0d2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_link_hover_color', array(
        'label'       => __('Footer Link Hover Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));
    
    // Footer Bottom Background Color
    $wp_customize->add_setting('aqualuxe_footer_bottom_background_color', array(
        'default'           => '#121212',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bottom_background_color', array(
        'label'       => __('Footer Bottom Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true);
        },
    )));
    
    // Footer Bottom Text Color
    $wp_customize->add_setting('aqualuxe_footer_bottom_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bottom_text_color', array(
        'label'       => __('Footer Bottom Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true);
        },
    )));
    
    // Footer Padding
    $wp_customize->add_setting('aqualuxe_footer_padding', array(
        'default'           => 60,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_padding', array(
        'label'       => __('Footer Padding (px)', 'aqualuxe'),
        'description' => __('Set the top and bottom padding for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 200,
            'step' => 5,
        ),
    ));
    
    // Footer Bottom Padding
    $wp_customize->add_setting('aqualuxe_footer_bottom_padding', array(
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_bottom_padding', array(
        'label'       => __('Footer Bottom Padding (px)', 'aqualuxe'),
        'description' => __('Set the top and bottom padding for the footer bottom section.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_bottom', true);
        },
    ));
    
    // Footer Widget Title Style
    $wp_customize->add_setting('aqualuxe_footer_widget_title_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_footer_widget_title_style', array(
        'label'       => __('Footer Widget Title Style', 'aqualuxe'),
        'description' => __('Choose the style for footer widget titles.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            'default'   => __('Default', 'aqualuxe'),
            'underline' => __('Underline', 'aqualuxe'),
            'overline'  => __('Overline', 'aqualuxe'),
            'bordered'  => __('Bordered', 'aqualuxe'),
            'modern'    => __('Modern', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_footer_widgets', true);
        },
    ));
    
    // Back to Top Button
    $wp_customize->add_setting('aqualuxe_show_back_to_top', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_back_to_top', array(
        'label'       => __('Show Back to Top Button', 'aqualuxe'),
        'description' => __('Display a button to scroll back to the top of the page.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));
    
    // Back to Top Button Style
    $wp_customize->add_setting('aqualuxe_back_to_top_style', array(
        'default'           => 'circle',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_back_to_top_style', array(
        'label'       => __('Back to Top Button Style', 'aqualuxe'),
        'description' => __('Choose the style for the back to top button.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            'circle'  => __('Circle', 'aqualuxe'),
            'square'  => __('Square', 'aqualuxe'),
            'rounded' => __('Rounded', 'aqualuxe'),
            'text'    => __('Text', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_back_to_top', true);
        },
    ));
    
    // Back to Top Button Position
    $wp_customize->add_setting('aqualuxe_back_to_top_position', array(
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_back_to_top_position', array(
        'label'       => __('Back to Top Button Position', 'aqualuxe'),
        'description' => __('Choose the position for the back to top button.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'center' => __('Center', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_back_to_top', true);
        },
    ));
}

// Add the footer section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_footer');

/**
 * Sanitize multi-select values.
 *
 * @param array $input Multi-select values.
 * @return array Sanitized multi-select values.
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array('visa', 'mastercard', 'amex', 'discover', 'paypal', 'apple-pay', 'google-pay', 'stripe', 'bitcoin');
    
    return array_intersect($input, $valid_keys);
}

/**
 * Multi-checkbox control class.
 */
if (class_exists('WP_Customize_Control')) {
    class AquaLuxe_Multi_Checkbox_Control extends WP_Customize_Control {
        public $type = 'multi-checkbox';
        
        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            
            $values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
            ?>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
            
            <ul>
                <?php foreach ($this->choices as $value => $label) : ?>
                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $values)); ?> />
                            <?php echo esc_html($label); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $values)); ?>" />
            
            <script>
                jQuery(document).ready(function($) {
                    var control = $('#<?php echo esc_attr($this->id); ?>');
                    var checkboxes = control.find('input[type="checkbox"]');
                    var hidden = control.find('input[type="hidden"]');
                    
                    checkboxes.on('change', function() {
                        var values = [];
                        checkboxes.filter(':checked').each(function() {
                            values.push($(this).val());
                        });
                        hidden.val(values.join(',')).trigger('change');
                    });
                });
            </script>
            <?php
        }
    }
}

/**
 * Add footer CSS to the head.
 */
function aqualuxe_footer_css() {
    $footer_background_color = get_theme_mod('aqualuxe_footer_background_color', '#23282d');
    $footer_text_color = get_theme_mod('aqualuxe_footer_text_color', '#ffffff');
    $footer_link_color = get_theme_mod('aqualuxe_footer_link_color', '#ffffff');
    $footer_link_hover_color = get_theme_mod('aqualuxe_footer_link_hover_color', '#00a0d2');
    $footer_bottom_background_color = get_theme_mod('aqualuxe_footer_bottom_background_color', '#121212');
    $footer_bottom_text_color = get_theme_mod('aqualuxe_footer_bottom_text_color', '#ffffff');
    $footer_padding = get_theme_mod('aqualuxe_footer_padding', 60);
    $footer_bottom_padding = get_theme_mod('aqualuxe_footer_bottom_padding', 20);
    $footer_background = get_theme_mod('aqualuxe_footer_background', '');
    $footer_widget_title_style = get_theme_mod('aqualuxe_footer_widget_title_style', 'default');
    $footer_logo_width = get_theme_mod('aqualuxe_footer_logo_width', 150);
    $back_to_top_style = get_theme_mod('aqualuxe_back_to_top_style', 'circle');
    $back_to_top_position = get_theme_mod('aqualuxe_back_to_top_position', 'right');
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-footer-background-color: <?php echo esc_attr($footer_background_color); ?>;
            --aqualuxe-footer-text-color: <?php echo esc_attr($footer_text_color); ?>;
            --aqualuxe-footer-link-color: <?php echo esc_attr($footer_link_color); ?>;
            --aqualuxe-footer-link-hover-color: <?php echo esc_attr($footer_link_hover_color); ?>;
            --aqualuxe-footer-bottom-background-color: <?php echo esc_attr($footer_bottom_background_color); ?>;
            --aqualuxe-footer-bottom-text-color: <?php echo esc_attr($footer_bottom_text_color); ?>;
            --aqualuxe-footer-padding: <?php echo esc_attr($footer_padding); ?>px;
            --aqualuxe-footer-bottom-padding: <?php echo esc_attr($footer_bottom_padding); ?>px;
        }
        
        /* Footer */
        .site-footer {
            background-color: var(--aqualuxe-footer-background-color);
            color: var(--aqualuxe-footer-text-color);
            <?php if (!empty($footer_background)) : ?>
            background-image: url(<?php echo esc_url($footer_background); ?>);
            background-size: cover;
            background-position: center;
            <?php endif; ?>
        }
        
        .footer-widgets {
            padding: var(--aqualuxe-footer-padding) 0;
        }
        
        .site-footer a {
            color: var(--aqualuxe-footer-link-color);
        }
        
        .site-footer a:hover,
        .site-footer a:focus {
            color: var(--aqualuxe-footer-link-hover-color);
        }
        
        .footer-bottom {
            background-color: var(--aqualuxe-footer-bottom-background-color);
            color: var(--aqualuxe-footer-bottom-text-color);
            padding: var(--aqualuxe-footer-bottom-padding) 0;
        }
        
        .footer-logo {
            max-width: <?php echo esc_attr($footer_logo_width); ?>px;
        }
        
        /* Footer Widget Title Styles */
        <?php if ($footer_widget_title_style === 'underline') : ?>
        .footer-widgets .widget-title {
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-widgets .widget-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--aqualuxe-footer-link-color);
        }
        <?php elseif ($footer_widget_title_style === 'overline') : ?>
        .footer-widgets .widget-title {
            position: relative;
            padding-top: 10px;
        }
        
        .footer-widgets .widget-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--aqualuxe-footer-link-color);
        }
        <?php elseif ($footer_widget_title_style === 'bordered') : ?>
        .footer-widgets .widget-title {
            border-left: 3px solid var(--aqualuxe-footer-link-color);
            padding-left: 10px;
        }
        <?php elseif ($footer_widget_title_style === 'modern') : ?>
        .footer-widgets .widget-title {
            display: inline-block;
            padding: 5px 15px;
            background-color: var(--aqualuxe-footer-link-color);
            color: var(--aqualuxe-footer-background-color);
        }
        <?php endif; ?>
        
        /* Back to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            <?php echo $back_to_top_position === 'right' ? 'right: 20px;' : ($back_to_top_position === 'left' ? 'left: 20px;' : 'left: 50%; transform: translateX(-50%);'); ?>
            z-index: 999;
            display: none;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
            <?php if ($back_to_top_style === 'circle') : ?>
            border-radius: 50%;
            <?php elseif ($back_to_top_style === 'rounded') : ?>
            border-radius: 5px;
            <?php elseif ($back_to_top_style === 'text') : ?>
            width: auto;
            padding: 0 15px;
            border-radius: 20px;
            <?php endif; ?>
        }
        
        .scroll-to-top:hover {
            opacity: 1;
        }
        
        .scroll-to-top.show {
            display: block;
        }
        
        /* Footer Columns */
        .footer-widgets-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .footer-widget-area {
            padding: 0 15px;
            margin-bottom: 30px;
        }
        
        @media (min-width: 768px) {
            .footer-widgets-row.columns-1 .footer-widget-area {
                width: 100%;
            }
            
            .footer-widgets-row.columns-2 .footer-widget-area {
                width: 50%;
            }
            
            .footer-widgets-row.columns-3 .footer-widget-area {
                width: 33.333%;
            }
            
            .footer-widgets-row.columns-4 .footer-widget-area {
                width: 25%;
            }
            
            .footer-widgets-row.columns-5 .footer-widget-area {
                width: 20%;
            }
            
            .footer-widgets-row.columns-6 .footer-widget-area {
                width: 16.666%;
            }
        }
        
        @media (max-width: 767px) {
            .footer-widget-area {
                width: 100%;
            }
        }
        
        /* Footer Bottom Content */
        .footer-bottom-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-copyright {
            margin-bottom: 10px;
        }
        
        .footer-navigation ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .footer-navigation li {
            margin-right: 20px;
        }
        
        .footer-navigation li:last-child {
            margin-right: 0;
        }
        
        .footer-social {
            display: flex;
            align-items: center;
        }
        
        .footer-social a {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .footer-payment {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        
        .footer-payment img {
            height: 24px;
            margin-right: 10px;
        }
        
        @media (max-width: 767px) {
            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-navigation ul {
                justify-content: center;
                margin-bottom: 10px;
            }
            
            .footer-social {
                justify-content: center;
                margin-top: 10px;
            }
            
            .footer-payment {
                justify-content: center;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_footer_css');

/**
 * Process copyright text.
 *
 * @param string $text Copyright text.
 * @return string Processed copyright text.
 */
function aqualuxe_process_copyright_text($text) {
    $text = str_replace('{year}', date('Y'), $text);
    return do_shortcode($text);
}

/**
 * Display the footer based on the selected layout.
 */
function aqualuxe_display_footer_complete() {
    $footer_layout = get_theme_mod('aqualuxe_footer_layout', 'default');
    
    if ($footer_layout === 'default') {
        get_template_part('templates/partials/footer', 'default');
    } elseif ($footer_layout === 'simple') {
        get_template_part('templates/partials/footer', 'simple');
    } elseif ($footer_layout === 'widgets') {
        get_template_part('templates/partials/footer', 'widgets');
    } elseif ($footer_layout === 'centered') {
        get_template_part('templates/partials/footer', 'centered');
    } elseif ($footer_layout === 'minimal') {
        get_template_part('templates/partials/footer', 'minimal');
    } else {
        get_template_part('templates/partials/footer', 'default');
    }
    
    // Display back to top button
    if (get_theme_mod('aqualuxe_show_back_to_top', true)) {
        aqualuxe_back_to_top_button();
    }
}

/**
 * Display social icons.
 */
function aqualuxe_social_icons() {
    $social_networks = array(
        'facebook'  => array(
            'label' => __('Facebook', 'aqualuxe'),
            'icon'  => 'fab fa-facebook-f',
        ),
        'twitter'   => array(
            'label' => __('Twitter', 'aqualuxe'),
            'icon'  => 'fab fa-twitter',
        ),
        'instagram' => array(
            'label' => __('Instagram', 'aqualuxe'),
            'icon'  => 'fab fa-instagram',
        ),
        'linkedin'  => array(
            'label' => __('LinkedIn', 'aqualuxe'),
            'icon'  => 'fab fa-linkedin-in',
        ),
        'youtube'   => array(
            'label' => __('YouTube', 'aqualuxe'),
            'icon'  => 'fab fa-youtube',
        ),
        'pinterest' => array(
            'label' => __('Pinterest', 'aqualuxe'),
            'icon'  => 'fab fa-pinterest-p',
        ),
    );
    
    echo '<div class="social-icons">';
    
    foreach ($social_networks as $network => $data) {
        $url = get_theme_mod('aqualuxe_social_' . $network, '');
        
        if (!empty($url)) {
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="social-icon ' . esc_attr($network) . '" aria-label="' . esc_attr($data['label']) . '">';
            echo '<i class="' . esc_attr($data['icon']) . '"></i>';
            echo '</a>';
        }
    }
    
    echo '</div>';
}

/**
 * Display payment icons.
 */
function aqualuxe_payment_icons() {
    $payment_icons = get_theme_mod('aqualuxe_payment_icons', array('visa', 'mastercard', 'amex', 'paypal'));
    
    if (empty($payment_icons)) {
        return;
    }
    
    echo '<div class="payment-icons">';
    
    foreach ($payment_icons as $icon) {
        echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . 'images/payment/' . $icon . '.svg') . '" alt="' . esc_attr($icon) . '" class="payment-icon">';
    }
    
    echo '</div>';
}

/**
 * Display back to top button.
 */
function aqualuxe_back_to_top_button() {
    $style = get_theme_mod('aqualuxe_back_to_top_style', 'circle');
    
    echo '<a href="#" class="scroll-to-top" aria-label="' . esc_attr__('Scroll to top', 'aqualuxe') . '">';
    
    if ($style === 'text') {
        echo esc_html__('Top', 'aqualuxe');
    } else {
        echo '<i class="fas fa-chevron-up"></i>';
    }
    
    echo '</a>';
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_footer_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Footer background color
        wp.customize("aqualuxe_footer_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-background-color", to);
                $(".site-footer").css("background-color", to);
            });
        });
        
        // Footer text color
        wp.customize("aqualuxe_footer_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-text-color", to);
                $(".site-footer").css("color", to);
            });
        });
        
        // Footer link color
        wp.customize("aqualuxe_footer_link_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-link-color", to);
                $(".site-footer a").css("color", to);
            });
        });
        
        // Footer link hover color
        wp.customize("aqualuxe_footer_link_hover_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-link-hover-color", to);
                // Cannot preview hover state
            });
        });
        
        // Footer bottom background color
        wp.customize("aqualuxe_footer_bottom_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-bottom-background-color", to);
                $(".footer-bottom").css("background-color", to);
            });
        });
        
        // Footer bottom text color
        wp.customize("aqualuxe_footer_bottom_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-bottom-text-color", to);
                $(".footer-bottom").css("color", to);
            });
        });
        
        // Footer padding
        wp.customize("aqualuxe_footer_padding", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-padding", to + "px");
                $(".footer-widgets").css("padding-top", to + "px");
                $(".footer-widgets").css("padding-bottom", to + "px");
            });
        });
        
        // Footer bottom padding
        wp.customize("aqualuxe_footer_bottom_padding", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-bottom-padding", to + "px");
                $(".footer-bottom").css("padding-top", to + "px");
                $(".footer-bottom").css("padding-bottom", to + "px");
            });
        });
        
        // Footer logo width
        wp.customize("aqualuxe_footer_logo_width", function(value) {
            value.bind(function(to) {
                $(".footer-logo").css("max-width", to + "px");
            });
        });
        
        // Copyright text
        wp.customize("aqualuxe_copyright_text", function(value) {
            value.bind(function(to) {
                $(".footer-copyright").html(to);
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_footer_customize_preview_js', 20);