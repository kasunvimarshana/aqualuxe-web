<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'dark-mode';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Dark Mode';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds dark mode functionality with persistent user preference';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array(
        'auto_detect' => true,
        'toggle_position' => 'header',
        'default_mode' => 'light',
        'transition_duration' => 0.3,
        'custom_colors' => false,
        'dark_background' => '#121212',
        'dark_text' => '#e0e0e0',
    );

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Load module settings
        $this->load_settings();

        // Include module files
        $this->include_files();
    }

    /**
     * Include module files
     *
     * @return void
     */
    private function include_files() {
        // Include helper functions
        require_once dirname( __FILE__ ) . '/includes/helpers.php';

        // Include template functions
        require_once dirname( __FILE__ ) . '/includes/template-functions.php';
    }

    /**
     * Setup module hooks
     *
     * @return void
     */
    public function setup_hooks() {
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Add body class
        add_filter( 'body_class', array( $this, 'add_body_class' ) );

        // Add dark mode toggle to header
        if ( 'header' === $this->settings['toggle_position'] ) {
            add_action( 'aqualuxe_header_after_navigation', array( $this, 'render_dark_mode_toggle' ) );
        }

        // Add dark mode toggle to footer
        if ( 'footer' === $this->settings['toggle_position'] ) {
            add_action( 'aqualuxe_footer_before_widgets', array( $this, 'render_dark_mode_toggle' ) );
        }

        // Add inline CSS for dark mode
        add_action( 'wp_head', array( $this, 'add_inline_css' ) );
    }

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function enqueue_assets() {
        // Enqueue dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_THEME_URI . 'modules/dark-mode/assets/js/dark-mode.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            array(
                'autoDetect'        => $this->settings['auto_detect'],
                'defaultMode'       => $this->settings['default_mode'],
                'transitionDuration' => $this->settings['transition_duration'],
                'nonce'             => wp_create_nonce( 'aqualuxe_dark_mode_nonce' ),
                'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
            )
        );

        // Enqueue dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_THEME_URI . 'modules/dark-mode/assets/css/dark-mode.css',
            array(),
            $this->version
        );
    }

    /**
     * Add body class
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function add_body_class( $classes ) {
        // Add dark-mode class if dark mode is active
        if ( $this->is_dark_mode_active() ) {
            $classes[] = 'dark-mode';
        }

        return $classes;
    }

    /**
     * Check if dark mode is active
     *
     * @return bool
     */
    public function is_dark_mode_active() {
        // Check cookie for user preference
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return 'dark' === $_COOKIE['aqualuxe_dark_mode'];
        }

        // Use default mode
        return 'dark' === $this->settings['default_mode'];
    }

    /**
     * Render dark mode toggle
     *
     * @return void
     */
    public function render_dark_mode_toggle() {
        $is_dark_mode = $this->is_dark_mode_active();
        ?>
        <div class="dark-mode-toggle">
            <button id="dark-mode-toggle-btn" class="dark-mode-toggle-btn" aria-label="<?php echo esc_attr( $is_dark_mode ? __( 'Switch to Light Mode', 'aqualuxe' ) : __( 'Switch to Dark Mode', 'aqualuxe' ) ); ?>">
                <span class="dark-mode-toggle-icon light-mode-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </span>
                <span class="dark-mode-toggle-icon dark-mode-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </span>
                <span class="screen-reader-text"><?php echo esc_html( $is_dark_mode ? __( 'Switch to Light Mode', 'aqualuxe' ) : __( 'Switch to Dark Mode', 'aqualuxe' ) ); ?></span>
            </button>
        </div>
        <?php
    }

    /**
     * Add inline CSS for dark mode
     *
     * @return void
     */
    public function add_inline_css() {
        // Only add custom colors if enabled
        if ( ! $this->settings['custom_colors'] ) {
            return;
        }

        $css = '
            :root {
                --dark-mode-bg: ' . esc_attr( $this->settings['dark_background'] ) . ';
                --dark-mode-text: ' . esc_attr( $this->settings['dark_text'] ) . ';
                --dark-mode-transition: ' . esc_attr( $this->settings['transition_duration'] ) . 's;
            }
        ';

        echo '<style id="aqualuxe-dark-mode-custom-css">' . wp_strip_all_tags( $css ) . '</style>';
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add Dark Mode section
        $wp_customize->add_section(
            'aqualuxe_dark_mode',
            array(
                'title'    => __( 'Dark Mode', 'aqualuxe' ),
                'priority' => 30,
            )
        );

        // Auto detect setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_auto_detect',
            array(
                'default'           => $this->settings['auto_detect'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_auto_detect',
            array(
                'label'    => __( 'Auto detect system preference', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'type'     => 'checkbox',
                'priority' => 10,
            )
        );

        // Toggle position setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_toggle_position',
            array(
                'default'           => $this->settings['toggle_position'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_toggle_position',
            array(
                'label'    => __( 'Toggle position', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'type'     => 'select',
                'choices'  => array(
                    'header' => __( 'Header', 'aqualuxe' ),
                    'footer' => __( 'Footer', 'aqualuxe' ),
                    'none'   => __( 'None (custom placement)', 'aqualuxe' ),
                ),
                'priority' => 20,
            )
        );

        // Default mode setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_default_mode',
            array(
                'default'           => $this->settings['default_mode'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_default_mode',
            array(
                'label'    => __( 'Default mode', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'type'     => 'select',
                'choices'  => array(
                    'light' => __( 'Light', 'aqualuxe' ),
                    'dark'  => __( 'Dark', 'aqualuxe' ),
                ),
                'priority' => 30,
            )
        );

        // Transition duration setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_transition_duration',
            array(
                'default'           => $this->settings['transition_duration'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_transition_duration',
            array(
                'label'    => __( 'Transition duration (seconds)', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 2,
                    'step' => 0.1,
                ),
                'priority' => 40,
            )
        );

        // Custom colors setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_custom_colors',
            array(
                'default'           => $this->settings['custom_colors'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_custom_colors',
            array(
                'label'    => __( 'Use custom colors', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'type'     => 'checkbox',
                'priority' => 50,
            )
        );

        // Dark background color
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_dark_background',
            array(
                'default'           => $this->settings['dark_background'],
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_dark_mode_dark_background',
                array(
                    'label'    => __( 'Dark mode background color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_dark_mode',
                    'priority' => 60,
                )
            )
        );

        // Dark text color
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_dark_text',
            array(
                'default'           => $this->settings['dark_text'],
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_dark_mode_dark_text',
                array(
                    'label'    => __( 'Dark mode text color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_dark_mode',
                    'priority' => 70,
                )
            )
        );
    }
}