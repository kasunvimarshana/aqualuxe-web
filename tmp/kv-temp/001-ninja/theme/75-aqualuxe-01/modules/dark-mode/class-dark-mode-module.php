<?php
/**
 * Dark Mode Module
 *
 * Provides dark/light mode toggle functionality with user preference persistence
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode_Module {
    
    /**
     * Module instance
     *
     * @var AquaLuxe_Dark_Mode_Module
     */
    private static $instance = null;
    
    /**
     * Get module instance
     *
     * @return AquaLuxe_Dark_Mode_Module
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'inline_styles' ) );
        add_action( 'wp_ajax_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'wp_ajax_nopriv_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'customize_register', array( $this, 'customize_register' ) );
        add_filter( 'body_class', array( $this, 'add_body_class' ) );
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if ( ! $this->is_enabled() ) {
            return;
        }
        
        // Dark mode CSS variables
        wp_add_inline_style( 'aqualuxe-main', $this->get_dark_mode_css() );
        
        // Dark mode JavaScript
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/modules/dark-mode/assets/dark-mode.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script( 'aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'dark_mode_nonce' ),
            'enabled'  => $this->is_enabled(),
        ) );
    }
    
    /**
     * Add inline styles for dark mode
     */
    public function inline_styles() {
        if ( ! $this->is_enabled() ) {
            return;
        }
        
        $current_mode = $this->get_current_mode();
        ?>
        <style id="aqualuxe-dark-mode-inline">
            :root {
                --dark-mode-transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
            }
            
            <?php if ( 'dark' === $current_mode ) : ?>
            html {
                color-scheme: dark;
            }
            
            .dark-mode-enabled {
                --color-bg-primary: #1f2937;
                --color-bg-secondary: #111827;
                --color-text-primary: #f9fafb;
                --color-text-secondary: #d1d5db;
                --color-border: #374151;
            }
            <?php else : ?>
            html {
                color-scheme: light;
            }
            
            .light-mode-enabled {
                --color-bg-primary: #ffffff;
                --color-bg-secondary: #f9fafb;
                --color-text-primary: #111827;
                --color-text-secondary: #6b7280;
                --color-border: #e5e7eb;
            }
            <?php endif; ?>
            
            /* Toggle button styles */
            .dark-mode-toggle-wrapper {
                position: relative;
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                user-select: none;
            }
            
            .toggle-switch {
                position: relative;
                width: 2rem;
                height: 1rem;
                background-color: #6b7280;
                border-radius: 9999px;
                transition: background-color 0.3s ease;
            }
            
            .dark-mode-toggle:checked + .toggle-switch {
                background-color: #0ea5e9;
            }
            
            .toggle-handle {
                position: absolute;
                top: 0.125rem;
                left: 0.125rem;
                width: 0.75rem;
                height: 0.75rem;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .dark-mode-toggle:checked + .toggle-switch .toggle-handle {
                transform: translateX(1rem);
            }
            
            /* Smooth transitions for all elements */
            * {
                transition: var(--dark-mode-transition);
            }
            
            /* Disable transitions during page load */
            .preload * {
                transition: none !important;
            }
        </style>
        <?php
    }
    
    /**
     * Get dark mode CSS
     */
    private function get_dark_mode_css() {
        return '
            .dark {
                --color-bg-primary: #1f2937;
                --color-bg-secondary: #111827;
                --color-bg-tertiary: #374151;
                --color-text-primary: #f9fafb;
                --color-text-secondary: #d1d5db;
                --color-text-muted: #9ca3af;
                --color-border: #374151;
                --color-border-light: #4b5563;
            }
            
            .dark body {
                background-color: var(--color-bg-primary);
                color: var(--color-text-primary);
            }
            
            .dark .card,
            .dark .widget {
                background-color: var(--color-bg-tertiary);
                border-color: var(--color-border);
            }
            
            .dark .site-header {
                background-color: var(--color-bg-primary);
                border-color: var(--color-border);
            }
            
            .dark .site-footer {
                background-color: var(--color-bg-secondary);
            }
            
            .dark input,
            .dark textarea,
            .dark select {
                background-color: var(--color-bg-tertiary);
                border-color: var(--color-border);
                color: var(--color-text-primary);
            }
            
            .dark .btn-ghost:hover {
                background-color: var(--color-bg-tertiary);
            }
        ';
    }
    
    /**
     * AJAX handler for toggling dark mode
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer( 'dark_mode_nonce', 'nonce' );
        
        $mode = sanitize_text_field( $_POST['mode'] ?? 'light' );
        
        if ( ! in_array( $mode, array( 'light', 'dark' ), true ) ) {
            wp_die( 'Invalid mode' );
        }
        
        // Store user preference
        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), 'dark_mode_preference', $mode );
        }
        
        // Set cookie for non-logged-in users
        setcookie( 'aqualuxe_dark_mode', $mode, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        wp_send_json_success( array(
            'mode' => $mode,
            'message' => sprintf(
                /* translators: %s: Mode name (light/dark) */
                __( 'Switched to %s mode', 'aqualuxe' ),
                $mode
            ),
        ) );
    }
    
    /**
     * Add customizer options
     */
    public function customize_register( $wp_customize ) {
        // Dark Mode Section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title'       => __( 'Dark Mode', 'aqualuxe' ),
            'description' => __( 'Configure dark mode settings', 'aqualuxe' ),
            'priority'    => 60,
        ) );
        
        // Enable Dark Mode
        $wp_customize->add_setting( 'aqualuxe_dark_mode_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_enabled', array(
            'label'   => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ) );
        
        // Default Mode
        $wp_customize->add_setting( 'aqualuxe_dark_mode_default', array(
            'default'           => 'light',
            'sanitize_callback' => array( $this, 'sanitize_mode' ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_default', array(
            'label'   => __( 'Default Mode', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'select',
            'choices' => array(
                'light' => __( 'Light', 'aqualuxe' ),
                'dark'  => __( 'Dark', 'aqualuxe' ),
                'auto'  => __( 'Auto (System Preference)', 'aqualuxe' ),
            ),
        ) );
        
        // Remember User Preference
        $wp_customize->add_setting( 'aqualuxe_dark_mode_remember', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_remember', array(
            'label'   => __( 'Remember User Preference', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ) );
        
        // Auto Switch Times
        $wp_customize->add_setting( 'aqualuxe_dark_mode_auto_switch', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_auto_switch', array(
            'label'       => __( 'Auto Switch Based on Time', 'aqualuxe' ),
            'description' => __( 'Automatically switch to dark mode during evening hours', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ) );
        
        // Dark Mode Start Time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_start_time', array(
            'default'           => '18:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_start_time', array(
            'label'           => __( 'Dark Mode Start Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_dark_mode_auto_switch', false );
            },
        ) );
        
        // Dark Mode End Time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_end_time', array(
            'default'           => '06:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_end_time', array(
            'label'           => __( 'Dark Mode End Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_dark_mode_auto_switch', false );
            },
        ) );
    }
    
    /**
     * Sanitize mode value
     */
    public function sanitize_mode( $mode ) {
        return in_array( $mode, array( 'light', 'dark', 'auto' ), true ) ? $mode : 'light';
    }
    
    /**
     * Add body class for dark mode
     */
    public function add_body_class( $classes ) {
        if ( ! $this->is_enabled() ) {
            return $classes;
        }
        
        $current_mode = $this->get_current_mode();
        
        if ( 'dark' === $current_mode ) {
            $classes[] = 'dark';
            $classes[] = 'dark-mode-enabled';
        } else {
            $classes[] = 'light-mode-enabled';
        }
        
        return $classes;
    }
    
    /**
     * Check if dark mode is enabled
     */
    public function is_enabled() {
        return get_theme_mod( 'aqualuxe_dark_mode_enabled', true );
    }
    
    /**
     * Get current mode
     */
    public function get_current_mode() {
        if ( ! $this->is_enabled() ) {
            return 'light';
        }
        
        // Check user preference first
        if ( is_user_logged_in() && get_theme_mod( 'aqualuxe_dark_mode_remember', true ) ) {
            $user_preference = get_user_meta( get_current_user_id(), 'dark_mode_preference', true );
            if ( $user_preference ) {
                return $user_preference;
            }
        }
        
        // Check cookie for non-logged-in users
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return sanitize_text_field( $_COOKIE['aqualuxe_dark_mode'] );
        }
        
        // Check auto switch based on time
        if ( get_theme_mod( 'aqualuxe_dark_mode_auto_switch', false ) ) {
            $current_time = current_time( 'H:i' );
            $start_time = get_theme_mod( 'aqualuxe_dark_mode_start_time', '18:00' );
            $end_time = get_theme_mod( 'aqualuxe_dark_mode_end_time', '06:00' );
            
            if ( $start_time > $end_time ) {
                // Night period spans midnight
                if ( $current_time >= $start_time || $current_time <= $end_time ) {
                    return 'dark';
                }
            } else {
                // Normal day period
                if ( $current_time >= $start_time && $current_time <= $end_time ) {
                    return 'dark';
                }
            }
        }
        
        // Return default mode
        $default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'light' );
        
        if ( 'auto' === $default_mode ) {
            // This will be handled by JavaScript based on system preference
            return 'light'; // Fallback
        }
        
        return $default_mode;
    }
    
    /**
     * Get dark mode toggle HTML
     */
    public function get_toggle_html( $args = array() ) {
        if ( ! $this->is_enabled() ) {
            return '';
        }
        
        $defaults = array(
            'show_labels' => true,
            'size' => 'normal',
            'class' => '',
        );
        
        $args = wp_parse_args( $args, $defaults );
        
        $current_mode = $this->get_current_mode();
        $checked = 'dark' === $current_mode ? 'checked' : '';
        
        ob_start();
        ?>
        <div class="dark-mode-toggle-wrapper <?php echo esc_attr( $args['class'] ); ?>" data-size="<?php echo esc_attr( $args['size'] ); ?>">
            <?php if ( $args['show_labels'] ) : ?>
            <span class="toggle-label toggle-label-light">
                <i class="fas fa-sun" aria-hidden="true"></i>
                <span class="sr-only"><?php esc_html_e( 'Light mode', 'aqualuxe' ); ?></span>
            </span>
            <?php endif; ?>
            
            <label class="toggle-switch-wrapper">
                <input type="checkbox" class="dark-mode-toggle sr-only" <?php echo $checked; ?>>
                <span class="toggle-switch">
                    <span class="toggle-handle"></span>
                </span>
                <span class="sr-only"><?php esc_html_e( 'Toggle dark mode', 'aqualuxe' ); ?></span>
            </label>
            
            <?php if ( $args['show_labels'] ) : ?>
            <span class="toggle-label toggle-label-dark">
                <i class="fas fa-moon" aria-hidden="true"></i>
                <span class="sr-only"><?php esc_html_e( 'Dark mode', 'aqualuxe' ); ?></span>
            </span>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize module
function aqualuxe_dark_mode_module() {
    return AquaLuxe_Dark_Mode_Module::get_instance();
}

// Helper function to get toggle HTML
function aqualuxe_dark_mode_toggle( $args = array() ) {
    echo aqualuxe_dark_mode_module()->get_toggle_html( $args );
}

// Initialize
aqualuxe_dark_mode_module();
