<?php
/**
 * Dark Mode Module
 *
 * Handles dark mode functionality with persistent user preference
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Dark Mode Class
 */
class AquaLuxe_Dark_Mode {

    /**
     * Constructor
     */
    public function __construct() {
        // Module is auto-initialized when loaded
    }

    /**
     * Initialize the module
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_initial_theme_script' ), 1 );
        add_action( 'customize_register', array( $this, 'customize_register' ) );
        add_action( 'wp_ajax_save_theme_preference', array( $this, 'ajax_save_theme_preference' ) );
        add_action( 'wp_ajax_nopriv_save_theme_preference', array( $this, 'ajax_save_theme_preference' ) );
        add_filter( 'body_class', array( $this, 'body_classes' ) );
    }

    /**
     * Enqueue dark mode scripts
     */
    public function enqueue_scripts() {
        // Dark mode script is already enqueued in main assets
        // Just add localization
        wp_localize_script( 'aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_dark_mode_nonce' ),
            'strings'  => array(
                'dark_mode_enabled'  => esc_html__( 'Dark mode enabled', 'aqualuxe' ),
                'light_mode_enabled' => esc_html__( 'Light mode enabled', 'aqualuxe' ),
                'switch_to_dark'     => esc_html__( 'Switch to dark mode', 'aqualuxe' ),
                'switch_to_light'    => esc_html__( 'Switch to light mode', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Add script to prevent FOUC (Flash of Unstyled Content)
     */
    public function add_initial_theme_script() {
        ?>
        <script>
        (function() {
            // Check for saved theme preference or default to system preference
            const savedTheme = localStorage.getItem('aqualuxe-theme');
            const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let theme = 'light';
            if (savedTheme === 'dark') {
                theme = 'dark';
            } else if (savedTheme === 'light') {
                theme = 'light';
            } else if (systemPrefersDark) {
                theme = 'dark';
            }
            
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            
            // Remove no-js class
            document.documentElement.classList.remove('no-js');
        })();
        </script>
        <?php
    }

    /**
     * Add customizer settings for dark mode
     */
    public function customize_register( $wp_customize ) {
        // Dark Mode Section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title'    => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'priority' => 30,
        ) );

        // Enable Dark Mode
        $wp_customize->add_setting( 'dark_mode_enabled', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_mode_enabled', array(
            'label'   => esc_html__( 'Enable Dark Mode Toggle', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ) );

        // Default Theme
        $wp_customize->add_setting( 'default_theme', array(
            'default'           => 'light',
            'sanitize_callback' => array( $this, 'sanitize_theme_choice' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'default_theme', array(
            'label'   => esc_html__( 'Default Theme', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'radio',
            'choices' => array(
                'light'  => esc_html__( 'Light', 'aqualuxe' ),
                'dark'   => esc_html__( 'Dark', 'aqualuxe' ),
                'system' => esc_html__( 'Follow System Preference', 'aqualuxe' ),
            ),
        ) );

        // Dark Mode Colors
        $wp_customize->add_setting( 'dark_primary_color', array(
            'default'           => '#14b8a6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_primary_color', array(
            'label'   => esc_html__( 'Dark Mode Primary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );

        $wp_customize->add_setting( 'dark_secondary_color', array(
            'default'           => '#0f766e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_secondary_color', array(
            'label'   => esc_html__( 'Dark Mode Secondary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );

        $wp_customize->add_setting( 'dark_accent_color', array(
            'default'           => '#eec25a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_accent_color', array(
            'label'   => esc_html__( 'Dark Mode Accent Color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );

        // Auto Switch Settings
        $wp_customize->add_setting( 'auto_switch_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'auto_switch_enabled', array(
            'label'       => esc_html__( 'Auto Switch Based on Time', 'aqualuxe' ),
            'description' => esc_html__( 'Automatically switch to dark mode during nighttime hours', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ) );

        $wp_customize->add_setting( 'dark_mode_start_time', array(
            'default'           => '18:00',
            'sanitize_callback' => array( $this, 'sanitize_time' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_mode_start_time', array(
            'label'   => esc_html__( 'Dark Mode Start Time', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'time',
        ) );

        $wp_customize->add_setting( 'dark_mode_end_time', array(
            'default'           => '06:00',
            'sanitize_callback' => array( $this, 'sanitize_time' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_mode_end_time', array(
            'label'   => esc_html__( 'Dark Mode End Time', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'time',
        ) );
    }

    /**
     * Sanitize theme choice
     */
    public function sanitize_theme_choice( $input ) {
        $valid_choices = array( 'light', 'dark', 'system' );
        return in_array( $input, $valid_choices ) ? $input : 'light';
    }

    /**
     * Sanitize time input
     */
    public function sanitize_time( $input ) {
        if ( preg_match( '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $input ) ) {
            return $input;
        }
        return '18:00'; // Default fallback
    }

    /**
     * Add body classes for dark mode
     */
    public function body_classes( $classes ) {
        $default_theme = get_theme_mod( 'default_theme', 'light' );
        
        if ( $default_theme === 'dark' ) {
            $classes[] = 'default-dark';
        } elseif ( $default_theme === 'system' ) {
            $classes[] = 'system-preference';
        }

        if ( get_theme_mod( 'dark_mode_enabled', false ) ) {
            $classes[] = 'dark-mode-enabled';
        }

        if ( get_theme_mod( 'auto_switch_enabled', true ) ) {
            $classes[] = 'auto-switch-enabled';
        }

        return $classes;
    }

    /**
     * AJAX handler for saving theme preference
     */
    public function ajax_save_theme_preference() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_dark_mode_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        $theme = sanitize_text_field( $_POST['theme'] );
        
        if ( ! in_array( $theme, array( 'light', 'dark' ) ) ) {
            wp_send_json_error( 'Invalid theme' );
        }

        // Save user preference if logged in
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aqualuxe_theme_preference', $theme );
        }

        // Update analytics (optional)
        $this->track_theme_usage( $theme );

        wp_send_json_success( array(
            'theme' => $theme,
            'message' => sprintf( 
                esc_html__( '%s mode activated', 'aqualuxe' ), 
                ucfirst( $theme ) 
            )
        ) );
    }

    /**
     * Track theme usage for analytics
     */
    private function track_theme_usage( $theme ) {
        $stats = get_option( 'aqualuxe_theme_stats', array(
            'light' => 0,
            'dark'  => 0,
        ) );

        if ( isset( $stats[ $theme ] ) ) {
            $stats[ $theme ]++;
            update_option( 'aqualuxe_theme_stats', $stats );
        }
    }

    /**
     * Get user's preferred theme
     */
    public function get_user_theme_preference() {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $preference = get_user_meta( $user_id, 'aqualuxe_theme_preference', true );
            
            if ( in_array( $preference, array( 'light', 'dark' ) ) ) {
                return $preference;
            }
        }

        return get_theme_mod( 'default_theme', 'light' );
    }

    /**
     * Check if it's time for auto dark mode
     */
    public function is_dark_mode_time() {
        if ( ! get_theme_mod( 'auto_switch_enabled', true ) ) {
            return false;
        }

        $start_time = get_theme_mod( 'dark_mode_start_time', '18:00' );
        $end_time = get_theme_mod( 'dark_mode_end_time', '06:00' );
        
        $current_time = current_time( 'H:i' );
        
        // Handle overnight periods (e.g., 18:00 to 06:00)
        if ( $start_time > $end_time ) {
            return ( $current_time >= $start_time || $current_time <= $end_time );
        } else {
            return ( $current_time >= $start_time && $current_time <= $end_time );
        }
    }

    /**
     * Get theme statistics
     */
    public function get_theme_stats() {
        return get_option( 'aqualuxe_theme_stats', array(
            'light' => 0,
            'dark'  => 0,
        ) );
    }
}