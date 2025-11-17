<?php
/**
 * Contact Form Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\ContactForm;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Contact Form Module class
 */
class ContactFormModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'contact-form';
        $this->name        = __( 'Contact Form', 'aqualuxe' );
        $this->description = __( 'Display a customizable contact form with various field options.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Get In Touch', 'aqualuxe' ),
            'subtitle'        => __( 'Contact Us', 'aqualuxe' ),
            'description'     => __( 'Have a question or want to work with us? Send us a message and we\'ll get back to you as soon as possible.', 'aqualuxe' ),
            'layout'          => 'default',
            'style'           => 'default',
            'background_type' => 'color',
            'background_color' => '#f8fafc',
            'text_color'      => '#1e293b',
            'accent_color'    => '#0ea5e9',
            'form_fields'     => [
                'name'        => [
                    'label'    => __( 'Your Name', 'aqualuxe' ),
                    'type'     => 'text',
                    'required' => true,
                    'enabled'  => true,
                ],
                'email'       => [
                    'label'    => __( 'Your Email', 'aqualuxe' ),
                    'type'     => 'email',
                    'required' => true,
                    'enabled'  => true,
                ],
                'phone'       => [
                    'label'    => __( 'Phone Number', 'aqualuxe' ),
                    'type'     => 'tel',
                    'required' => false,
                    'enabled'  => true,
                ],
                'subject'     => [
                    'label'    => __( 'Subject', 'aqualuxe' ),
                    'type'     => 'text',
                    'required' => true,
                    'enabled'  => true,
                ],
                'message'     => [
                    'label'    => __( 'Your Message', 'aqualuxe' ),
                    'type'     => 'textarea',
                    'required' => true,
                    'enabled'  => true,
                ],
                'service'     => [
                    'label'    => __( 'Service Interested In', 'aqualuxe' ),
                    'type'     => 'select',
                    'required' => false,
                    'enabled'  => true,
                    'options'  => [
                        ''               => __( 'Select a Service', 'aqualuxe' ),
                        'web-design'     => __( 'Web Design', 'aqualuxe' ),
                        'development'    => __( 'Development', 'aqualuxe' ),
                        'branding'       => __( 'Branding', 'aqualuxe' ),
                        'digital-marketing' => __( 'Digital Marketing', 'aqualuxe' ),
                        'other'          => __( 'Other', 'aqualuxe' ),
                    ],
                ],
                'budget'      => [
                    'label'    => __( 'Budget Range', 'aqualuxe' ),
                    'type'     => 'select',
                    'required' => false,
                    'enabled'  => false,
                    'options'  => [
                        ''               => __( 'Select Budget Range', 'aqualuxe' ),
                        'under-1000'     => __( 'Under $1,000', 'aqualuxe' ),
                        '1000-5000'      => __( '$1,000 - $5,000', 'aqualuxe' ),
                        '5000-10000'     => __( '$5,000 - $10,000', 'aqualuxe' ),
                        '10000-plus'     => __( '$10,000+', 'aqualuxe' ),
                    ],
                ],
                'newsletter'  => [
                    'label'    => __( 'Subscribe to Newsletter', 'aqualuxe' ),
                    'type'     => 'checkbox',
                    'required' => false,
                    'enabled'  => false,
                ],
                'privacy'     => [
                    'label'    => __( 'I agree to the privacy policy', 'aqualuxe' ),
                    'type'     => 'checkbox',
                    'required' => true,
                    'enabled'  => true,
                ],
            ],
            'submit_button_text' => __( 'Send Message', 'aqualuxe' ),
            'success_message'    => __( 'Thank you! Your message has been sent successfully.', 'aqualuxe' ),
            'error_message'      => __( 'Oops! There was a problem sending your message. Please try again.', 'aqualuxe' ),
            'recipient_email'    => '',
            'contact_info'       => [
                'show'    => true,
                'address' => __( '123 Street Name, City, Country', 'aqualuxe' ),
                'phone'   => __( '+1 (555) 123-4567', 'aqualuxe' ),
                'email'   => __( 'info@example.com', 'aqualuxe' ),
                'hours'   => __( 'Mon-Fri: 9:00 AM - 5:00 PM', 'aqualuxe' ),
            ],
            'social_links'       => [
                'show'      => true,
                'facebook'  => '#',
                'twitter'   => '#',
                'instagram' => '#',
                'linkedin'  => '#',
            ],
            'map'                => [
                'show'      => false,
                'latitude'  => '40.7128',
                'longitude' => '-74.0060',
                'zoom'      => 14,
            ],
            'animation'          => 'fade',
        ];
    }

    /**
     * Initialize the module
     *
     * @return void
     */
    public function initialize() {
        // Register hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_ajax_aqualuxe_contact_form', [ $this, 'process_form_submission' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_contact_form', [ $this, 'process_form_submission' ] );
        
        // Register customizer settings
        add_action( 'customize_register', [ $this, 'register_customizer_settings' ] );
    }

    /**
     * Enqueue module assets
     *
     * @return void
     */
    public function enqueue_assets() {
        // Enqueue only if module is active on the page
        if ( $this->is_active() ) {
            wp_enqueue_style( 'aqualuxe-contact-form', get_template_directory_uri() . '/assets/css/modules/contact-form.css', [], $this->version );
            wp_enqueue_script( 'aqualuxe-contact-form', get_template_directory_uri() . '/assets/js/modules/contact-form.js', [ 'jquery' ], $this->version, true );
            
            // Add localized script data
            wp_localize_script( 'aqualuxe-contact-form', 'aqualuxeContactForm', [
                'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
                'nonce'          => wp_create_nonce( 'aqualuxe_contact_form_nonce' ),
                'successMessage' => $this->get_setting( 'success_message' ),
                'errorMessage'   => $this->get_setting( 'error_message' ),
            ] );
            
            // Enqueue Google Maps if map is enabled
            if ( $this->get_setting( 'map.show', false ) ) {
                $api_key = get_theme_mod( 'aqualuxe_google_maps_api_key', '' );
                if ( ! empty( $api_key ) ) {
                    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ), [], null, true );
                }
            }
        }
    }

    /**
     * Process form submission via AJAX
     *
     * @return void
     */
    public function process_form_submission() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_contact_form_nonce' ) ) {
            wp_send_json_error( [
                'message' => __( 'Security check failed. Please refresh the page and try again.', 'aqualuxe' ),
            ] );
        }

        // Get form data
        $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
        $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
        $service = isset( $_POST['service'] ) ? sanitize_text_field( $_POST['service'] ) : '';
        $budget = isset( $_POST['budget'] ) ? sanitize_text_field( $_POST['budget'] ) : '';
        $newsletter = isset( $_POST['newsletter'] ) && $_POST['newsletter'] === 'on';
        $privacy = isset( $_POST['privacy'] ) && $_POST['privacy'] === 'on';

        // Validate required fields
        $errors = [];

        $form_fields = $this->get_setting( 'form_fields', [] );
        
        foreach ( $form_fields as $field_id => $field ) {
            if ( $field['enabled'] && $field['required'] ) {
                $field_value = isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : '';
                
                if ( empty( $field_value ) || ( $field['type'] === 'checkbox' && $field_value !== 'on' ) ) {
                    $errors[ $field_id ] = sprintf( __( '%s is required.', 'aqualuxe' ), $field['label'] );
                }
            }
        }

        // Validate email format
        if ( ! empty( $email ) && ! is_email( $email ) ) {
            $errors['email'] = __( 'Please enter a valid email address.', 'aqualuxe' );
        }

        // Return errors if any
        if ( ! empty( $errors ) ) {
            wp_send_json_error( [
                'message' => __( 'Please fix the errors and try again.', 'aqualuxe' ),
                'errors'  => $errors,
            ] );
        }

        // Get recipient email
        $recipient = $this->get_setting( 'recipient_email', '' );
        if ( empty( $recipient ) ) {
            $recipient = get_option( 'admin_email' );
        }

        // Build email content
        $email_subject = sprintf( __( 'New Contact Form Submission: %s', 'aqualuxe' ), $subject );
        
        $email_content = sprintf( __( 'Name: %s', 'aqualuxe' ), $name ) . "\r\n";
        $email_content .= sprintf( __( 'Email: %s', 'aqualuxe' ), $email ) . "\r\n";
        
        if ( ! empty( $phone ) ) {
            $email_content .= sprintf( __( 'Phone: %s', 'aqualuxe' ), $phone ) . "\r\n";
        }
        
        if ( ! empty( $service ) ) {
            $service_options = $form_fields['service']['options'];
            $service_label = isset( $service_options[ $service ] ) ? $service_options[ $service ] : $service;
            $email_content .= sprintf( __( 'Service: %s', 'aqualuxe' ), $service_label ) . "\r\n";
        }
        
        if ( ! empty( $budget ) ) {
            $budget_options = $form_fields['budget']['options'];
            $budget_label = isset( $budget_options[ $budget ] ) ? $budget_options[ $budget ] : $budget;
            $email_content .= sprintf( __( 'Budget: %s', 'aqualuxe' ), $budget_label ) . "\r\n";
        }
        
        $email_content .= sprintf( __( 'Newsletter: %s', 'aqualuxe' ), $newsletter ? __( 'Yes', 'aqualuxe' ) : __( 'No', 'aqualuxe' ) ) . "\r\n";
        $email_content .= sprintf( __( 'Message: %s', 'aqualuxe' ), $message ) . "\r\n";
        $email_content .= sprintf( __( 'Sent from: %s', 'aqualuxe' ), get_bloginfo( 'name' ) ) . "\r\n";

        // Set email headers
        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
        ];

        // Send email
        $sent = wp_mail( $recipient, $email_subject, $email_content, $headers );

        // Return response
        if ( $sent ) {
            wp_send_json_success( [
                'message' => $this->get_setting( 'success_message' ),
            ] );
        } else {
            wp_send_json_error( [
                'message' => $this->get_setting( 'error_message' ),
            ] );
        }
    }

    /**
     * Register customizer settings for the module
     *
     * @param WP_Customize_Manager $wp_customize The customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Contact Form Section
        $wp_customize->add_section( 'aqualuxe_contact_form', [
            'title'       => __( 'Contact Form', 'aqualuxe' ),
            'description' => __( 'Configure the contact form module settings.', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 60,
        ] );

        // Title
        $wp_customize->add_setting( 'aqualuxe_contact_form_title', [
            'default'           => $this->get_setting( 'title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_title', [
            'label'    => __( 'Title', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'text',
            'priority' => 10,
        ] );

        // Subtitle
        $wp_customize->add_setting( 'aqualuxe_contact_form_subtitle', [
            'default'           => $this->get_setting( 'subtitle' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_subtitle', [
            'label'    => __( 'Subtitle', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'text',
            'priority' => 20,
        ] );

        // Description
        $wp_customize->add_setting( 'aqualuxe_contact_form_description', [
            'default'           => $this->get_setting( 'description' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_description', [
            'label'    => __( 'Description', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'textarea',
            'priority' => 30,
        ] );

        // Layout
        $wp_customize->add_setting( 'aqualuxe_contact_form_layout', [
            'default'           => $this->get_setting( 'layout' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_layout', [
            'label'    => __( 'Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'select',
            'choices'  => [
                'default'    => __( 'Default', 'aqualuxe' ),
                'split'      => __( 'Split', 'aqualuxe' ),
                'centered'   => __( 'Centered', 'aqualuxe' ),
                'boxed'      => __( 'Boxed', 'aqualuxe' ),
                'full-width' => __( 'Full Width', 'aqualuxe' ),
            ],
            'priority' => 40,
        ] );

        // Style
        $wp_customize->add_setting( 'aqualuxe_contact_form_style', [
            'default'           => $this->get_setting( 'style' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_style', [
            'label'    => __( 'Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'select',
            'choices'  => [
                'default'  => __( 'Default', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
                'material' => __( 'Material', 'aqualuxe' ),
            ],
            'priority' => 50,
        ] );

        // Background Type
        $wp_customize->add_setting( 'aqualuxe_contact_form_background_type', [
            'default'           => $this->get_setting( 'background_type' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_background_type', [
            'label'    => __( 'Background Type', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'select',
            'choices'  => [
                'color'     => __( 'Color', 'aqualuxe' ),
                'gradient'  => __( 'Gradient', 'aqualuxe' ),
                'image'     => __( 'Image', 'aqualuxe' ),
                'none'      => __( 'None', 'aqualuxe' ),
            ],
            'priority' => 60,
        ] );

        // Background Color
        $wp_customize->add_setting( 'aqualuxe_contact_form_background_color', [
            'default'           => $this->get_setting( 'background_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
        ] );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_contact_form_background_color', [
            'label'    => __( 'Background Color', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'priority' => 70,
        ] ) );

        // Text Color
        $wp_customize->add_setting( 'aqualuxe_contact_form_text_color', [
            'default'           => $this->get_setting( 'text_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
        ] );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_contact_form_text_color', [
            'label'    => __( 'Text Color', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'priority' => 80,
        ] ) );

        // Accent Color
        $wp_customize->add_setting( 'aqualuxe_contact_form_accent_color', [
            'default'           => $this->get_setting( 'accent_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
        ] );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_contact_form_accent_color', [
            'label'    => __( 'Accent Color', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'priority' => 90,
        ] ) );

        // Submit Button Text
        $wp_customize->add_setting( 'aqualuxe_contact_form_submit_button_text', [
            'default'           => $this->get_setting( 'submit_button_text' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_submit_button_text', [
            'label'    => __( 'Submit Button Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'text',
            'priority' => 100,
        ] );

        // Success Message
        $wp_customize->add_setting( 'aqualuxe_contact_form_success_message', [
            'default'           => $this->get_setting( 'success_message' ),
            'sanitize_callback' => 'wp_kses_post',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_success_message', [
            'label'    => __( 'Success Message', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'textarea',
            'priority' => 110,
        ] );

        // Error Message
        $wp_customize->add_setting( 'aqualuxe_contact_form_error_message', [
            'default'           => $this->get_setting( 'error_message' ),
            'sanitize_callback' => 'wp_kses_post',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_error_message', [
            'label'    => __( 'Error Message', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'textarea',
            'priority' => 120,
        ] );

        // Recipient Email
        $wp_customize->add_setting( 'aqualuxe_contact_form_recipient_email', [
            'default'           => $this->get_setting( 'recipient_email' ),
            'sanitize_callback' => 'sanitize_email',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_recipient_email', [
            'label'       => __( 'Recipient Email', 'aqualuxe' ),
            'description' => __( 'Leave empty to use admin email.', 'aqualuxe' ),
            'section'     => 'aqualuxe_contact_form',
            'type'        => 'email',
            'priority'    => 130,
        ] );

        // Contact Info Show
        $wp_customize->add_setting( 'aqualuxe_contact_form_contact_info_show', [
            'default'           => $this->get_setting( 'contact_info.show' ),
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_contact_info_show', [
            'label'    => __( 'Show Contact Info', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'checkbox',
            'priority' => 140,
        ] );

        // Contact Info Fields
        $contact_info_fields = [
            'address' => __( 'Address', 'aqualuxe' ),
            'phone'   => __( 'Phone', 'aqualuxe' ),
            'email'   => __( 'Email', 'aqualuxe' ),
            'hours'   => __( 'Hours', 'aqualuxe' ),
        ];

        $priority = 150;
        foreach ( $contact_info_fields as $field_id => $field_label ) {
            $wp_customize->add_setting( 'aqualuxe_contact_form_contact_info_' . $field_id, [
                'default'           => $this->get_setting( 'contact_info.' . $field_id ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );

            $wp_customize->add_control( 'aqualuxe_contact_form_contact_info_' . $field_id, [
                'label'    => $field_label,
                'section'  => 'aqualuxe_contact_form',
                'type'     => 'text',
                'priority' => $priority,
            ] );

            $priority += 10;
        }

        // Social Links Show
        $wp_customize->add_setting( 'aqualuxe_contact_form_social_links_show', [
            'default'           => $this->get_setting( 'social_links.show' ),
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_social_links_show', [
            'label'    => __( 'Show Social Links', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'checkbox',
            'priority' => $priority,
        ] );

        $priority += 10;

        // Social Links
        $social_links = [
            'facebook'  => __( 'Facebook URL', 'aqualuxe' ),
            'twitter'   => __( 'Twitter URL', 'aqualuxe' ),
            'instagram' => __( 'Instagram URL', 'aqualuxe' ),
            'linkedin'  => __( 'LinkedIn URL', 'aqualuxe' ),
        ];

        foreach ( $social_links as $link_id => $link_label ) {
            $wp_customize->add_setting( 'aqualuxe_contact_form_social_links_' . $link_id, [
                'default'           => $this->get_setting( 'social_links.' . $link_id ),
                'sanitize_callback' => 'esc_url_raw',
            ] );

            $wp_customize->add_control( 'aqualuxe_contact_form_social_links_' . $link_id, [
                'label'    => $link_label,
                'section'  => 'aqualuxe_contact_form',
                'type'     => 'url',
                'priority' => $priority,
            ] );

            $priority += 10;
        }

        // Map Show
        $wp_customize->add_setting( 'aqualuxe_contact_form_map_show', [
            'default'           => $this->get_setting( 'map.show' ),
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_map_show', [
            'label'    => __( 'Show Map', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'checkbox',
            'priority' => $priority,
        ] );

        $priority += 10;

        // Map Fields
        $map_fields = [
            'latitude'  => __( 'Latitude', 'aqualuxe' ),
            'longitude' => __( 'Longitude', 'aqualuxe' ),
            'zoom'      => __( 'Zoom Level', 'aqualuxe' ),
        ];

        foreach ( $map_fields as $field_id => $field_label ) {
            $wp_customize->add_setting( 'aqualuxe_contact_form_map_' . $field_id, [
                'default'           => $this->get_setting( 'map.' . $field_id ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );

            $wp_customize->add_control( 'aqualuxe_contact_form_map_' . $field_id, [
                'label'    => $field_label,
                'section'  => 'aqualuxe_contact_form',
                'type'     => $field_id === 'zoom' ? 'number' : 'text',
                'priority' => $priority,
            ] );

            $priority += 10;
        }

        // Google Maps API Key
        $wp_customize->add_setting( 'aqualuxe_google_maps_api_key', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_google_maps_api_key', [
            'label'       => __( 'Google Maps API Key', 'aqualuxe' ),
            'description' => __( 'Required for map functionality.', 'aqualuxe' ),
            'section'     => 'aqualuxe_contact_form',
            'type'        => 'text',
            'priority'    => $priority,
        ] );

        $priority += 10;

        // Animation
        $wp_customize->add_setting( 'aqualuxe_contact_form_animation', [
            'default'           => $this->get_setting( 'animation' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_contact_form_animation', [
            'label'    => __( 'Animation', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_form',
            'type'     => 'select',
            'choices'  => [
                'none'  => __( 'None', 'aqualuxe' ),
                'fade'  => __( 'Fade', 'aqualuxe' ),
                'slide' => __( 'Slide', 'aqualuxe' ),
                'zoom'  => __( 'Zoom', 'aqualuxe' ),
            ],
            'priority' => $priority,
        ] );
    }

    /**
     * Get enabled form fields
     *
     * @return array
     */
    public function get_enabled_fields() {
        $fields = $this->get_setting( 'form_fields', [] );
        $enabled_fields = [];

        foreach ( $fields as $field_id => $field ) {
            if ( $field['enabled'] ) {
                $enabled_fields[ $field_id ] = $field;
            }
        }

        return $enabled_fields;
    }

    /**
     * Render the module
     *
     * @return void
     */
    public function render() {
        // Load template
        $this->load_template( 'contact-form' );
    }
}