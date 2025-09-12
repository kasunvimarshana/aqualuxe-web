<?php
/**
 * Dark Mode Module
 *
 * Provides dark mode functionality with persistent user preferences.
 * Implements WCAG-compliant color schemes and accessibility features.
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Modules;

use AquaLuxe\Core\Base_Module;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dark Mode Module Class
 *
 * Handles dark mode toggle functionality, persistent preferences,
 * and accessibility considerations.
 *
 * @since 1.0.0
 */
class Dark_Mode extends Base_Module {

    /**
     * Get the module name.
     *
     * @return string The module name.
     */
    public function get_name(): string {
        return 'Dark Mode';
    }

    /**
     * Get the module description.
     *
     * @return string The module description.
     */
    public function get_description(): string {
        return 'Provides dark mode functionality with persistent user preferences and accessibility support.';
    }

    /**
     * Get the module version.
     *
     * @return string The module version.
     */
    public function get_version(): string {
        return '1.0.0';
    }

    /**
     * Get the module dependencies.
     *
     * @return array Array of required dependencies.
     */
    public function get_dependencies(): array {
        return array(); // No dependencies
    }

    /**
     * Module-specific setup.
     *
     * @return void
     */
    protected function setup(): void {
        // Add customizer controls
        add_action( 'customize_register', array( $this, 'customize_register' ) );
        
        // Add body classes
        add_filter( 'body_class', array( $this, 'add_body_classes' ) );
        
        // Add inline CSS for initial theme
        add_action( 'wp_head', array( $this, 'add_inline_css' ), 1 );
        
        // Add AJAX handlers
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        
        // Add admin bar toggle
        add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_toggle' ), 100 );
        
        $this->log( 'Dark Mode module setup complete' );
    }

    /**
     * Called on WordPress 'init' action.
     *
     * @return void
     */
    public function on_init(): void {
        // Register shortcode
        add_shortcode( 'aqualuxe_dark_mode_toggle', array( $this, 'shortcode_toggle' ) );
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {
        // Dark mode is handled by main CSS and JavaScript
        // Additional inline styles are added via wp_head
    }

    /**
     * Register customizer controls.
     *
     * @param \WP_Customize_Manager $wp_customize The customizer manager.
     * @return void
     */
    public function customize_register( $wp_customize ): void {
        // Add Dark Mode section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title'    => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'priority' => 30,
        ) );

        // Enable dark mode setting
        $wp_customize->add_setting( 'aqualuxe_dark_mode_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_enabled', array(
            'label'   => esc_html__( 'Enable Dark Mode', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ) );

        // Default theme setting
        $wp_customize->add_setting( 'aqualuxe_default_theme', array(
            'default'           => 'light',
            'sanitize_callback' => array( $this, 'sanitize_theme_choice' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_default_theme', array(
            'label'   => esc_html__( 'Default Theme', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'select',
            'choices' => array(
                'light'  => esc_html__( 'Light', 'aqualuxe' ),
                'dark'   => esc_html__( 'Dark', 'aqualuxe' ),
                'system' => esc_html__( 'Follow System Preference', 'aqualuxe' ),
            ),
        ) );

        // Auto toggle based on time
        $wp_customize->add_setting( 'aqualuxe_auto_toggle_time', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_auto_toggle_time', array(
            'label'       => esc_html__( 'Auto Toggle Based on Time', 'aqualuxe' ),
            'description' => esc_html__( 'Automatically switch to dark mode in the evening', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ) );

        // Dark mode start time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_start_time', array(
            'default'           => '18:00',
            'sanitize_callback' => array( $this, 'sanitize_time' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_start_time', array(
            'label'           => esc_html__( 'Dark Mode Start Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => array( $this, 'is_auto_toggle_enabled' ),
        ) );

        // Dark mode end time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_end_time', array(
            'default'           => '06:00',
            'sanitize_callback' => array( $this, 'sanitize_time' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_end_time', array(
            'label'           => esc_html__( 'Dark Mode End Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => array( $this, 'is_auto_toggle_enabled' ),
        ) );
    }

    /**
     * Add body classes for dark mode.
     *
     * @param array $classes Body classes.
     * @return array Modified body classes.
     */
    public function add_body_classes( array $classes ): array {
        if ( ! $this->is_dark_mode_enabled() ) {
            return $classes;
        }

        // Add dark mode capability class
        $classes[] = 'has-dark-mode';

        // Add current theme class based on user preference or default
        $current_theme = $this->get_current_theme();
        $classes[] = 'theme-' . $current_theme;

        // Add auto-toggle class if enabled
        if ( get_theme_mod( 'aqualuxe_auto_toggle_time', false ) ) {
            $classes[] = 'auto-toggle-time';
        }

        return $classes;
    }

    /**
     * Add inline CSS for initial theme setup.
     *
     * @return void
     */
    public function add_inline_css(): void {
        if ( ! $this->is_dark_mode_enabled() ) {
            return;
        }

        $current_theme = $this->get_current_theme();
        
        ?>
        <style id="aqualuxe-dark-mode-initial">
            /* Prevent flash of unstyled content */
            .theme-dark {
                color-scheme: dark;
            }
            
            .theme-light {
                color-scheme: light;
            }
            
            /* System preference detection */
            @media (prefers-color-scheme: dark) {
                .theme-system {
                    color-scheme: dark;
                }
            }
            
            @media (prefers-color-scheme: light) {
                .theme-system {
                    color-scheme: light;
                }
            }
            
            /* Smooth transition for theme changes */
            html {
                transition: color-scheme 0.3s ease;
            }
            
            /* High contrast mode support */
            @media (prefers-contrast: high) {
                .theme-dark {
                    --color-background-primary: #000000;
                    --color-text-primary: #ffffff;
                }
            }
        </style>
        
        <script id="aqualuxe-dark-mode-init">
            (function() {
                // Initialize theme immediately to prevent flash
                const theme = '<?php echo esc_js( $current_theme ); ?>';
                const autoToggle = <?php echo wp_json_encode( get_theme_mod( 'aqualuxe_auto_toggle_time', false ) ); ?>;
                
                if (autoToggle) {
                    const startTime = '<?php echo esc_js( get_theme_mod( 'aqualuxe_dark_mode_start_time', '18:00' ) ); ?>';
                    const endTime = '<?php echo esc_js( get_theme_mod( 'aqualuxe_dark_mode_end_time', '06:00' ) ); ?>';
                    const currentTime = new Date().toTimeString().substr(0, 5);
                    
                    const isDarkTime = (startTime > endTime) 
                        ? (currentTime >= startTime || currentTime < endTime)
                        : (currentTime >= startTime && currentTime < endTime);
                    
                    document.documentElement.classList.toggle('dark', isDarkTime);
                } else if (theme === 'system') {
                    document.documentElement.classList.toggle('dark', 
                        window.matchMedia('(prefers-color-scheme: dark)').matches);
                } else {
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                }
            })();
        </script>
        <?php
    }

    /**
     * Handle AJAX dark mode toggle.
     *
     * @return void
     */
    public function ajax_toggle_dark_mode(): void {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed', 'aqualuxe' ) );
        }

        $new_theme = sanitize_text_field( $_POST['theme'] ?? 'light' );
        $new_theme = $this->sanitize_theme_choice( $new_theme );

        // Store user preference
        if ( is_user_logged_in() ) {
            update_user_meta( get_current_user_id(), 'aqualuxe_theme_preference', $new_theme );
        } else {
            // For non-logged-in users, we'll rely on JavaScript localStorage
            setcookie( 'aqualuxe_theme_preference', $new_theme, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        }

        wp_send_json_success( array(
            'theme'   => $new_theme,
            'message' => sprintf( 
                esc_html__( 'Switched to %s mode', 'aqualuxe' ), 
                $new_theme 
            ),
        ) );
    }

    /**
     * Add dark mode toggle to admin bar.
     *
     * @param \WP_Admin_Bar $admin_bar The admin bar instance.
     * @return void
     */
    public function add_admin_bar_toggle( $admin_bar ): void {
        if ( ! $this->is_dark_mode_enabled() || ! current_user_can( 'read' ) ) {
            return;
        }

        $current_theme = $this->get_current_theme();
        $icon = $current_theme === 'dark' ? '☀️' : '🌙';
        $label = $current_theme === 'dark' 
            ? esc_html__( 'Switch to Light Mode', 'aqualuxe' )
            : esc_html__( 'Switch to Dark Mode', 'aqualuxe' );

        $admin_bar->add_node( array(
            'id'    => 'aqualuxe-dark-mode-toggle',
            'title' => $icon . ' ' . $label,
            'href'  => '#',
            'meta'  => array(
                'class' => 'aqualuxe-dark-mode-admin-toggle',
                'title' => $label,
            ),
        ) );
    }

    /**
     * Dark mode toggle shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function shortcode_toggle( $atts ): string {
        if ( ! $this->is_dark_mode_enabled() ) {
            return '';
        }

        $atts = shortcode_atts( array(
            'class'      => '',
            'text_light' => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'text_dark'  => esc_html__( 'Light Mode', 'aqualuxe' ),
            'icon_light' => '🌙',
            'icon_dark'  => '☀️',
            'show_text'  => 'true',
            'show_icon'  => 'true',
        ), $atts, 'aqualuxe_dark_mode_toggle' );

        $current_theme = $this->get_current_theme();
        $classes = array( 'aqualuxe-dark-mode-toggle', 'btn', 'btn-ghost' );
        
        if ( ! empty( $atts['class'] ) ) {
            $classes[] = sanitize_html_class( $atts['class'] );
        }

        $icon = wp_validate_boolean( $atts['show_icon'] ) 
            ? ( $current_theme === 'dark' ? $atts['icon_dark'] : $atts['icon_light'] )
            : '';
            
        $text = wp_validate_boolean( $atts['show_text'] )
            ? ( $current_theme === 'dark' ? $atts['text_dark'] : $atts['text_light'] )
            : '';

        $aria_label = $current_theme === 'dark'
            ? esc_attr__( 'Switch to light mode', 'aqualuxe' )
            : esc_attr__( 'Switch to dark mode', 'aqualuxe' );

        $output = sprintf(
            '<button type="button" class="%s" data-dark-mode-toggle aria-label="%s">',
            esc_attr( implode( ' ', $classes ) ),
            $aria_label
        );

        if ( $icon ) {
            $output .= '<span class="toggle-icon">' . esc_html( $icon ) . '</span>';
        }

        if ( $text ) {
            $output .= '<span class="toggle-text">' . esc_html( $text ) . '</span>';
        }

        $output .= '</button>';

        return $output;
    }

    /**
     * Check if dark mode is enabled.
     *
     * @return bool True if dark mode is enabled.
     */
    public function is_dark_mode_enabled(): bool {
        return get_theme_mod( 'aqualuxe_dark_mode_enabled', true );
    }

    /**
     * Get current theme preference.
     *
     * @return string Current theme (light, dark, or system).
     */
    public function get_current_theme(): string {
        // Check user preference first
        if ( is_user_logged_in() ) {
            $user_preference = get_user_meta( get_current_user_id(), 'aqualuxe_theme_preference', true );
            if ( ! empty( $user_preference ) ) {
                return $this->sanitize_theme_choice( $user_preference );
            }
        }

        // Check cookie for non-logged-in users
        if ( isset( $_COOKIE['aqualuxe_theme_preference'] ) ) {
            return $this->sanitize_theme_choice( $_COOKIE['aqualuxe_theme_preference'] );
        }

        // Fall back to default setting
        return get_theme_mod( 'aqualuxe_default_theme', 'light' );
    }

    /**
     * Sanitize theme choice.
     *
     * @param string $theme The theme choice.
     * @return string Sanitized theme choice.
     */
    public function sanitize_theme_choice( $theme ): string {
        $valid_themes = array( 'light', 'dark', 'system' );
        return in_array( $theme, $valid_themes, true ) ? $theme : 'light';
    }

    /**
     * Sanitize time input.
     *
     * @param string $time The time input.
     * @return string Sanitized time.
     */
    public function sanitize_time( $time ): string {
        if ( preg_match( '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time ) ) {
            return $time;
        }
        return '18:00';
    }

    /**
     * Check if auto toggle is enabled (for customizer active callback).
     *
     * @return bool True if auto toggle is enabled.
     */
    public function is_auto_toggle_enabled(): bool {
        return get_theme_mod( 'aqualuxe_auto_toggle_time', false );
    }
}