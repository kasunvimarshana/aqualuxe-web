<?php
/**
 * AquaLuxe Newsletter Functions
 *
 * Functions for newsletter subscription features
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add newsletter options to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_newsletter_customizer_options( $wp_customize ) {
    // Add Newsletter section
    $wp_customize->add_section( 'aqualuxe_newsletter', array(
        'title'    => __( 'Newsletter', 'aqualuxe' ),
        'priority' => 95,
    ) );

    // Enable Newsletter
    $wp_customize->add_setting( 'aqualuxe_newsletter_enable', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_enable', array(
        'label'    => __( 'Enable Newsletter Section', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'checkbox',
    ) );

    // Newsletter Title
    $wp_customize->add_setting( 'aqualuxe_newsletter_title', array(
        'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_title', array(
        'label'    => __( 'Newsletter Title', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'text',
    ) );

    // Newsletter Description
    $wp_customize->add_setting( 'aqualuxe_newsletter_description', array(
        'default'           => __( 'Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_description', array(
        'label'    => __( 'Newsletter Description', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'textarea',
    ) );

    // Newsletter Form Action
    $wp_customize->add_setting( 'aqualuxe_newsletter_form_action', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_form_action', array(
        'label'       => __( 'Form Action URL', 'aqualuxe' ),
        'description' => __( 'Enter the form action URL for your newsletter service (Mailchimp, ConvertKit, etc.)', 'aqualuxe' ),
        'section'     => 'aqualuxe_newsletter',
        'type'        => 'url',
    ) );

    // Newsletter Service
    $wp_customize->add_setting( 'aqualuxe_newsletter_service', array(
        'default'           => 'mailchimp',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_service', array(
        'label'    => __( 'Newsletter Service', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'select',
        'choices'  => array(
            'mailchimp'   => __( 'Mailchimp', 'aqualuxe' ),
            'convertkit'  => __( 'ConvertKit', 'aqualuxe' ),
            'aweber'      => __( 'AWeber', 'aqualuxe' ),
            'custom'      => __( 'Custom', 'aqualuxe' ),
        ),
    ) );

    // Newsletter Display Locations
    $wp_customize->add_setting( 'aqualuxe_newsletter_display_footer', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_display_footer', array(
        'label'    => __( 'Display in Footer', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'checkbox',
    ) );

    $wp_customize->add_setting( 'aqualuxe_newsletter_display_popup', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_display_popup', array(
        'label'    => __( 'Display as Popup', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'checkbox',
    ) );

    // Popup Delay
    $wp_customize->add_setting( 'aqualuxe_newsletter_popup_delay', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_popup_delay', array(
        'label'       => __( 'Popup Delay (seconds)', 'aqualuxe' ),
        'description' => __( 'How long to wait before showing the popup', 'aqualuxe' ),
        'section'     => 'aqualuxe_newsletter',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 60,
            'step' => 1,
        ),
    ) );

    // Popup Frequency
    $wp_customize->add_setting( 'aqualuxe_newsletter_popup_frequency', array(
        'default'           => 7,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_popup_frequency', array(
        'label'       => __( 'Popup Frequency (days)', 'aqualuxe' ),
        'description' => __( 'How often to show the popup to the same visitor', 'aqualuxe' ),
        'section'     => 'aqualuxe_newsletter',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 365,
            'step' => 1,
        ),
    ) );

    // Background Image
    $wp_customize->add_setting( 'aqualuxe_newsletter_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_newsletter_bg_image', array(
        'label'    => __( 'Background Image', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
    ) ) );

    // Background Color
    $wp_customize->add_setting( 'aqualuxe_newsletter_bg_color', array(
        'default'           => '#f0f8ff', // Light blue color
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_newsletter_bg_color', array(
        'label'    => __( 'Background Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
    ) ) );

    // Text Color
    $wp_customize->add_setting( 'aqualuxe_newsletter_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_newsletter_text_color', array(
        'label'    => __( 'Text Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
    ) ) );

    // Button Color
    $wp_customize->add_setting( 'aqualuxe_newsletter_button_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_newsletter_button_color', array(
        'label'    => __( 'Button Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
    ) ) );

    // Button Text Color
    $wp_customize->add_setting( 'aqualuxe_newsletter_button_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_newsletter_button_text_color', array(
        'label'    => __( 'Button Text Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
    ) ) );

    // GDPR Compliance
    $wp_customize->add_setting( 'aqualuxe_newsletter_gdpr_enable', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_gdpr_enable', array(
        'label'    => __( 'Enable GDPR Compliance', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'checkbox',
    ) );

    // GDPR Text
    $wp_customize->add_setting( 'aqualuxe_newsletter_gdpr_text', array(
        'default'           => __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing communications from us.', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_newsletter_gdpr_text', array(
        'label'    => __( 'GDPR Consent Text', 'aqualuxe' ),
        'section'  => 'aqualuxe_newsletter',
        'type'     => 'textarea',
    ) );
}
add_action( 'customize_register', 'aqualuxe_newsletter_customizer_options' );

/**
 * Sanitize checkbox.
 *
 * @param bool $input Input value.
 * @return bool Sanitized value.
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input Input value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Display newsletter form.
 *
 * @param string $location Location of the form (footer or popup).
 */
function aqualuxe_display_newsletter_form( $location = 'footer' ) {
    // Check if newsletter is enabled
    if ( ! get_theme_mod( 'aqualuxe_newsletter_enable', true ) ) {
        return;
    }

    // Check if newsletter should be displayed in this location
    $display_setting = 'aqualuxe_newsletter_display_' . $location;
    if ( ! get_theme_mod( $display_setting, true ) ) {
        return;
    }

    // Get newsletter settings
    $title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
    $description = get_theme_mod( 'aqualuxe_newsletter_description', __( 'Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe' ) );
    $form_action = get_theme_mod( 'aqualuxe_newsletter_form_action', '' );
    $service = get_theme_mod( 'aqualuxe_newsletter_service', 'mailchimp' );
    $bg_image = get_theme_mod( 'aqualuxe_newsletter_bg_image', '' );
    $bg_color = get_theme_mod( 'aqualuxe_newsletter_bg_color', '#f0f8ff' );
    $text_color = get_theme_mod( 'aqualuxe_newsletter_text_color', '#333333' );
    $button_color = get_theme_mod( 'aqualuxe_newsletter_button_color', '#0073aa' );
    $button_text_color = get_theme_mod( 'aqualuxe_newsletter_button_text_color', '#ffffff' );
    $gdpr_enable = get_theme_mod( 'aqualuxe_newsletter_gdpr_enable', true );
    $gdpr_text = get_theme_mod( 'aqualuxe_newsletter_gdpr_text', __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing communications from us.', 'aqualuxe' ) );

    // Generate inline styles
    $container_style = '';
    if ( $bg_image ) {
        $container_style .= 'background-image: url(' . esc_url( $bg_image ) . '); background-size: cover; background-position: center;';
    } else {
        $container_style .= 'background-color: ' . esc_attr( $bg_color ) . ';';
    }
    $container_style .= ' color: ' . esc_attr( $text_color ) . ';';

    $button_style = 'background-color: ' . esc_attr( $button_color ) . '; color: ' . esc_attr( $button_text_color ) . ';';

    // Set form attributes based on newsletter service
    $form_attributes = '';
    $hidden_fields = '';
    $email_field_name = 'email';

    switch ( $service ) {
        case 'mailchimp':
            if ( empty( $form_action ) ) {
                $form_action = '#';
            }
            $form_attributes = ' method="post" target="_blank"';
            $email_field_name = 'EMAIL';
            $hidden_fields = '<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_' . md5( $form_action ) . '_' . md5( time() ) . '" tabindex="-1" value=""></div>';
            break;

        case 'convertkit':
            if ( empty( $form_action ) ) {
                $form_action = '#';
            }
            $form_attributes = ' method="post" target="_blank"';
            $email_field_name = 'email_address';
            break;

        case 'aweber':
            if ( empty( $form_action ) ) {
                $form_action = '#';
            }
            $form_attributes = ' method="post" target="_blank"';
            $email_field_name = 'email';
            break;

        case 'custom':
        default:
            if ( empty( $form_action ) ) {
                $form_action = '#';
            }
            $form_attributes = ' method="post"';
            break;
    }

    // Start output
    ?>
    <div class="aqualuxe-newsletter aqualuxe-newsletter-<?php echo esc_attr( $location ); ?>" style="<?php echo esc_attr( $container_style ); ?>">
        <div class="aqualuxe-newsletter-container">
            <?php if ( $title ) : ?>
                <h3 class="aqualuxe-newsletter-title"><?php echo esc_html( $title ); ?></h3>
            <?php endif; ?>

            <?php if ( $description ) : ?>
                <div class="aqualuxe-newsletter-description"><?php echo esc_html( $description ); ?></div>
            <?php endif; ?>

            <form action="<?php echo esc_url( $form_action ); ?>"<?php echo $form_attributes; ?> class="aqualuxe-newsletter-form">
                <div class="aqualuxe-newsletter-form-fields">
                    <div class="aqualuxe-newsletter-form-field">
                        <label for="aqualuxe-newsletter-email-<?php echo esc_attr( $location ); ?>" class="screen-reader-text"><?php esc_html_e( 'Email Address', 'aqualuxe' ); ?></label>
                        <input type="email" name="<?php echo esc_attr( $email_field_name ); ?>" id="aqualuxe-newsletter-email-<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                    </div>
                    <div class="aqualuxe-newsletter-form-submit">
                        <button type="submit" style="<?php echo esc_attr( $button_style ); ?>"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                    </div>
                </div>

                <?php if ( $gdpr_enable && $gdpr_text ) : ?>
                    <div class="aqualuxe-newsletter-gdpr">
                        <label>
                            <input type="checkbox" name="gdpr_consent" required>
                            <span><?php echo esc_html( $gdpr_text ); ?></span>
                        </label>
                    </div>
                <?php endif; ?>

                <?php echo $hidden_fields; ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Add newsletter form to footer.
 */
function aqualuxe_add_newsletter_to_footer() {
    aqualuxe_display_newsletter_form( 'footer' );
}
add_action( 'aqualuxe_before_footer', 'aqualuxe_add_newsletter_to_footer' );

/**
 * Add newsletter popup.
 */
function aqualuxe_add_newsletter_popup() {
    // Check if newsletter popup is enabled
    if ( ! get_theme_mod( 'aqualuxe_newsletter_display_popup', false ) ) {
        return;
    }

    // Get popup settings
    $popup_delay = get_theme_mod( 'aqualuxe_newsletter_popup_delay', 5 );
    $popup_frequency = get_theme_mod( 'aqualuxe_newsletter_popup_frequency', 7 );

    // Output popup HTML
    ?>
    <div id="aqualuxe-newsletter-popup" class="aqualuxe-newsletter-popup" style="display: none;">
        <div class="aqualuxe-newsletter-popup-overlay"></div>
        <div class="aqualuxe-newsletter-popup-content">
            <button type="button" class="aqualuxe-newsletter-popup-close" aria-label="<?php esc_attr_e( 'Close', 'aqualuxe' ); ?>">×</button>
            <?php aqualuxe_display_newsletter_form( 'popup' ); ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has closed the popup before
        var popupClosed = localStorage.getItem('aqualuxe_newsletter_popup_closed');
        var lastShown = localStorage.getItem('aqualuxe_newsletter_popup_shown');
        var now = new Date().getTime();
        var popupFrequency = <?php echo esc_js( $popup_frequency ); ?> * 24 * 60 * 60 * 1000; // Convert days to milliseconds

        // Show popup if it hasn't been closed or if the frequency period has passed
        if (!popupClosed || (lastShown && now - lastShown > popupFrequency)) {
            setTimeout(function() {
                document.getElementById('aqualuxe-newsletter-popup').style.display = 'block';
                document.body.classList.add('aqualuxe-newsletter-popup-active');
                localStorage.setItem('aqualuxe_newsletter_popup_shown', now);
            }, <?php echo esc_js( $popup_delay * 1000 ); ?>);
        }

        // Close popup when clicking the close button
        document.querySelector('.aqualuxe-newsletter-popup-close').addEventListener('click', function() {
            document.getElementById('aqualuxe-newsletter-popup').style.display = 'none';
            document.body.classList.remove('aqualuxe-newsletter-popup-active');
            localStorage.setItem('aqualuxe_newsletter_popup_closed', 'true');
        });

        // Close popup when clicking outside the popup content
        document.querySelector('.aqualuxe-newsletter-popup-overlay').addEventListener('click', function() {
            document.getElementById('aqualuxe-newsletter-popup').style.display = 'none';
            document.body.classList.remove('aqualuxe-newsletter-popup-active');
            localStorage.setItem('aqualuxe_newsletter_popup_closed', 'true');
        });

        // Reset popup closed status when form is submitted
        document.querySelector('.aqualuxe-newsletter-popup .aqualuxe-newsletter-form').addEventListener('submit', function() {
            localStorage.setItem('aqualuxe_newsletter_popup_closed', 'true');
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_add_newsletter_popup' );

/**
 * Add newsletter styles.
 */
function aqualuxe_add_newsletter_styles() {
    // Only add styles if newsletter is enabled
    if ( ! get_theme_mod( 'aqualuxe_newsletter_enable', true ) ) {
        return;
    }

    // Get color settings
    $bg_color = get_theme_mod( 'aqualuxe_newsletter_bg_color', '#f0f8ff' );
    $text_color = get_theme_mod( 'aqualuxe_newsletter_text_color', '#333333' );
    $button_color = get_theme_mod( 'aqualuxe_newsletter_button_color', '#0073aa' );
    $button_text_color = get_theme_mod( 'aqualuxe_newsletter_button_text_color', '#ffffff' );
    ?>
    <style>
        /* Newsletter Styles */
        .aqualuxe-newsletter {
            padding: 3rem 1.5rem;
            text-align: center;
            margin: 2rem 0;
        }

        .aqualuxe-newsletter-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .aqualuxe-newsletter-title {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }

        .aqualuxe-newsletter-description {
            margin-bottom: 1.5rem;
        }

        .aqualuxe-newsletter-form-fields {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .aqualuxe-newsletter-form-field {
            flex: 1;
            min-width: 200px;
        }

        .aqualuxe-newsletter-form-field input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            font-size: 1rem;
        }

        .aqualuxe-newsletter-form-submit button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .aqualuxe-newsletter-form-submit button:hover {
            opacity: 0.9;
        }

        .aqualuxe-newsletter-gdpr {
            font-size: 0.875rem;
            margin-top: 1rem;
            text-align: left;
        }

        .aqualuxe-newsletter-gdpr input {
            margin-right: 0.5rem;
        }

        /* Newsletter Popup Styles */
        .aqualuxe-newsletter-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .aqualuxe-newsletter-popup-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .aqualuxe-newsletter-popup-content {
            position: relative;
            max-width: 500px;
            width: 90%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1;
            overflow: hidden;
        }

        .aqualuxe-newsletter-popup .aqualuxe-newsletter {
            margin: 0;
            padding: 2rem;
        }

        .aqualuxe-newsletter-popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background-color: rgba(0, 0, 0, 0.2);
            color: #ffffff;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .aqualuxe-newsletter-popup-close:hover {
            background-color: rgba(0, 0, 0, 0.4);
        }

        body.aqualuxe-newsletter-popup-active {
            overflow: hidden;
        }

        /* Dark Mode Styles */
        .dark-mode .aqualuxe-newsletter-form-field input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .dark-mode .aqualuxe-newsletter-popup-content {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .aqualuxe-newsletter-form-fields {
                flex-direction: column;
            }

            .aqualuxe-newsletter-form-submit {
                width: 100%;
            }

            .aqualuxe-newsletter-form-submit button {
                width: 100%;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_add_newsletter_styles' );

/**
 * Process newsletter form submission via AJAX.
 */
function aqualuxe_process_newsletter_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_newsletter_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }

    // Check email
    if ( ! isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'aqualuxe' ) ) );
    }

    $email = sanitize_email( $_POST['email'] );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'aqualuxe' ) ) );
    }

    // Check GDPR consent
    if ( get_theme_mod( 'aqualuxe_newsletter_gdpr_enable', true ) ) {
        if ( ! isset( $_POST['gdpr_consent'] ) || $_POST['gdpr_consent'] !== 'true' ) {
            wp_send_json_error( array( 'message' => __( 'Please agree to the privacy policy.', 'aqualuxe' ) ) );
        }
    }

    // Get newsletter service
    $service = get_theme_mod( 'aqualuxe_newsletter_service', 'mailchimp' );
    $form_action = get_theme_mod( 'aqualuxe_newsletter_form_action', '' );

    // Process based on service
    switch ( $service ) {
        case 'mailchimp':
            // For Mailchimp, we'll just return success as the form will be submitted directly to Mailchimp
            wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'aqualuxe' ) ) );
            break;

        case 'convertkit':
        case 'aweber':
            // For these services, we'll just return success as the form will be submitted directly to the service
            wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'aqualuxe' ) ) );
            break;

        case 'custom':
        default:
            // For custom implementation, you can add your own code here
            // For now, we'll just return success
            wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'aqualuxe' ) ) );
            break;
    }
}
add_action( 'wp_ajax_aqualuxe_process_newsletter', 'aqualuxe_process_newsletter_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_process_newsletter', 'aqualuxe_process_newsletter_ajax' );

/**
 * Add newsletter AJAX script.
 */
function aqualuxe_add_newsletter_ajax_script() {
    // Only add script if newsletter is enabled and using custom service
    if ( ! get_theme_mod( 'aqualuxe_newsletter_enable', true ) || get_theme_mod( 'aqualuxe_newsletter_service', 'mailchimp' ) !== 'custom' ) {
        return;
    }

    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('.aqualuxe-newsletter-form');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Only intercept custom forms
                if (form.getAttribute('action') === '#') {
                    e.preventDefault();
                    
                    var email = form.querySelector('input[type="email"]').value;
                    var gdprConsent = form.querySelector('input[name="gdpr_consent"]');
                    var gdprConsentValue = gdprConsent ? gdprConsent.checked : true;
                    
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            var response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Show success message
                                var successMessage = document.createElement('div');
                                successMessage.className = 'aqualuxe-newsletter-success';
                                successMessage.textContent = response.data.message;
                                form.innerHTML = '';
                                form.appendChild(successMessage);
                            } else {
                                // Show error message
                                var errorMessage = document.createElement('div');
                                errorMessage.className = 'aqualuxe-newsletter-error';
                                errorMessage.textContent = response.data.message;
                                form.prepend(errorMessage);
                            }
                        }
                    };
                    
                    xhr.send('action=aqualuxe_process_newsletter&nonce=<?php echo wp_create_nonce( 'aqualuxe_newsletter_nonce' ); ?>&email=' + encodeURIComponent(email) + '&gdpr_consent=' + gdprConsentValue);
                }
            });
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_add_newsletter_ajax_script' );