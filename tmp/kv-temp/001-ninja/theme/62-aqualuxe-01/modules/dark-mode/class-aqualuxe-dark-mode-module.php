<?php
/**
 * AquaLuxe Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Dark Mode Module Class
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
    protected $module_description = 'Adds dark mode support to the AquaLuxe theme with persistent preference.';

    /**
     * Module version
     *
     * @var string
     */
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Initialize module
     *
     * @return void
     */
    public function init() {
        // Include required files
        $this->includes();
        
        // Register hooks
        $this->register_hooks();
        
        // Add dark mode toggle to header
        add_action( 'aqualuxe_header_after_navigation', array( $this, 'add_dark_mode_toggle' ) );
        
        // Add dark mode toggle to footer
        add_action( 'aqualuxe_footer_widgets_after', array( $this, 'add_dark_mode_toggle_footer' ) );
        
        // Add dark mode toggle to mobile menu
        add_action( 'aqualuxe_mobile_menu_after', array( $this, 'add_dark_mode_toggle_mobile' ) );
        
        // Add body class
        add_filter( 'body_class', array( $this, 'add_body_class' ) );
        
        // Add customizer settings
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
        
        // Add admin settings
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include helper functions
        require_once $this->get_module_dir() . 'inc/helpers.php';
        
        // Include template functions
        require_once $this->get_module_dir() . 'inc/template-functions.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        
        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        
        // Add dark mode script to footer
        add_action( 'wp_footer', array( $this, 'add_dark_mode_script' ) );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-dark-mode',
            $this->get_module_uri() . 'assets/css/dark-mode.css',
            array(),
            $this->get_module_version()
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-dark-mode',
            $this->get_module_uri() . 'assets/js/dark-mode.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-dark-mode' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-dark-mode' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'defaultMode' => $this->get_setting( 'default_mode', 'light' ),
                'autoDetect' => $this->get_setting( 'auto_detect', true ),
                'toggleStyle' => $this->get_setting( 'toggle_style', 'switch' ),
                'savePreference' => $this->get_setting( 'save_preference', true ),
                'cookieDuration' => $this->get_setting( 'cookie_duration', 30 ),
                'customColors' => $this->get_custom_colors(),
                'i18n' => array(
                    'toggleLight' => __( 'Switch to light mode', 'aqualuxe' ),
                    'toggleDark' => __( 'Switch to dark mode', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-dark-mode-admin',
            $this->get_module_uri() . 'assets/css/dark-mode-admin.css',
            array(),
            $this->get_module_version()
        );
        
        // Enqueue admin scripts
        wp_enqueue_script(
            'aqualuxe-dark-mode-admin',
            $this->get_module_uri() . 'assets/js/dark-mode-admin.js',
            array( 'jquery', 'wp-color-picker' ),
            $this->get_module_version(),
            true
        );
        
        // Enqueue color picker
        wp_enqueue_style( 'wp-color-picker' );
    }

    /**
     * Add dark mode toggle to header
     *
     * @return void
     */
    public function add_dark_mode_toggle() {
        // Check if dark mode toggle is enabled in header
        if ( ! $this->get_setting( 'show_in_header', true ) ) {
            return;
        }
        
        // Get toggle style
        $style = $this->get_setting( 'toggle_style', 'switch' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/dark-mode-toggle', $style, array(
            'location' => 'header',
        ) );
    }

    /**
     * Add dark mode toggle to footer
     *
     * @return void
     */
    public function add_dark_mode_toggle_footer() {
        // Check if dark mode toggle is enabled in footer
        if ( ! $this->get_setting( 'show_in_footer', false ) ) {
            return;
        }
        
        // Get toggle style
        $style = $this->get_setting( 'toggle_style_footer', 'switch' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/dark-mode-toggle', $style, array(
            'location' => 'footer',
        ) );
    }

    /**
     * Add dark mode toggle to mobile menu
     *
     * @return void
     */
    public function add_dark_mode_toggle_mobile() {
        // Check if dark mode toggle is enabled in mobile menu
        if ( ! $this->get_setting( 'show_in_mobile', true ) ) {
            return;
        }
        
        // Get toggle style
        $style = $this->get_setting( 'toggle_style_mobile', 'switch' );
        
        // Get template part
        aqualuxe_get_template_part( 'template-parts/dark-mode-toggle', $style, array(
            'location' => 'mobile',
        ) );
    }

    /**
     * Add body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function add_body_class( $classes ) {
        // Add dark mode class if default mode is dark
        if ( $this->get_setting( 'default_mode', 'light' ) === 'dark' ) {
            $classes[] = 'dark-mode';
        }
        
        return $classes;
    }

    /**
     * Add dark mode script to footer
     *
     * @return void
     */
    public function add_dark_mode_script() {
        ?>
        <script>
            // Check for saved preference
            (function() {
                var darkMode = localStorage.getItem('aqualuxeDarkMode');
                var autoDetect = <?php echo $this->get_setting( 'auto_detect', true ) ? 'true' : 'false'; ?>;
                var defaultMode = '<?php echo esc_js( $this->get_setting( 'default_mode', 'light' ) ); ?>';
                
                // If preference is saved, use it
                if (darkMode === 'dark') {
                    document.documentElement.classList.add('dark-mode');
                } else if (darkMode === 'light') {
                    document.documentElement.classList.remove('dark-mode');
                } else if (autoDetect) {
                    // If no preference is saved and auto detect is enabled, check system preference
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark-mode');
                    } else {
                        document.documentElement.classList.remove('dark-mode');
                    }
                } else {
                    // If no preference is saved and auto detect is disabled, use default mode
                    if (defaultMode === 'dark') {
                        document.documentElement.classList.add('dark-mode');
                    } else {
                        document.documentElement.classList.remove('dark-mode');
                    }
                }
            })();
        </script>
        <?php
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title' => __( 'Dark Mode Settings', 'aqualuxe' ),
            'priority' => 100,
            'panel' => 'aqualuxe_theme_options',
        ) );
        
        // Add settings
        $wp_customize->add_setting( 'aqualuxe_dark_mode_default_mode', array(
            'default' => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_auto_detect', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_toggle_style', array(
            'default' => 'switch',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_show_in_header', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_show_in_footer', array(
            'default' => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_show_in_mobile', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_save_preference', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_cookie_duration', array(
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ) );
        
        // Add controls
        $wp_customize->add_control( 'aqualuxe_dark_mode_default_mode', array(
            'label' => __( 'Default mode', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => array(
                'light' => __( 'Light', 'aqualuxe' ),
                'dark' => __( 'Dark', 'aqualuxe' ),
            ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_auto_detect', array(
            'label' => __( 'Auto detect system preference', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_toggle_style', array(
            'label' => __( 'Toggle style', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => array(
                'switch' => __( 'Switch', 'aqualuxe' ),
                'icon' => __( 'Icon', 'aqualuxe' ),
                'button' => __( 'Button', 'aqualuxe' ),
            ),
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_show_in_header', array(
            'label' => __( 'Show toggle in header', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_show_in_footer', array(
            'label' => __( 'Show toggle in footer', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_show_in_mobile', array(
            'label' => __( 'Show toggle in mobile menu', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_save_preference', array(
            'label' => __( 'Save user preference', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_dark_mode_cookie_duration', array(
            'label' => __( 'Preference duration (days)', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 365,
                'step' => 1,
            ),
        ) );
        
        // Add color settings
        $wp_customize->add_setting( 'aqualuxe_dark_mode_background_color', array(
            'default' => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_text_color', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_link_color', array(
            'default' => '#4dabf7',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_setting( 'aqualuxe_dark_mode_border_color', array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ) );
        
        // Add color controls
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_mode_background_color', array(
            'label' => __( 'Background color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_mode_text_color', array(
            'label' => __( 'Text color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_mode_link_color', array(
            'label' => __( 'Link color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_mode_border_color', array(
            'label' => __( 'Border color', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
        ) ) );
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __( 'Dark Mode Settings', 'aqualuxe' ),
            __( 'Dark Mode', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-dark-mode',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Register admin settings
     *
     * @return void
     */
    public function register_admin_settings() {
        // Register settings
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_default_mode' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_auto_detect' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_toggle_style' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_show_in_header' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_show_in_footer' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_show_in_mobile' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_save_preference' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_cookie_duration' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_background_color' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_text_color' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_link_color' );
        register_setting( 'aqualuxe_dark_mode', 'aqualuxe_dark_mode_border_color' );
        
        // Add settings section
        add_settings_section(
            'aqualuxe_dark_mode_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'settings_section_general' ),
            'aqualuxe_dark_mode'
        );
        
        add_settings_section(
            'aqualuxe_dark_mode_appearance',
            __( 'Appearance Settings', 'aqualuxe' ),
            array( $this, 'settings_section_appearance' ),
            'aqualuxe_dark_mode'
        );
        
        add_settings_section(
            'aqualuxe_dark_mode_colors',
            __( 'Color Settings', 'aqualuxe' ),
            array( $this, 'settings_section_colors' ),
            'aqualuxe_dark_mode'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_dark_mode_default_mode',
            __( 'Default mode', 'aqualuxe' ),
            array( $this, 'settings_field_default_mode' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_general'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_auto_detect',
            __( 'Auto detect system preference', 'aqualuxe' ),
            array( $this, 'settings_field_auto_detect' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_general'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_save_preference',
            __( 'Save user preference', 'aqualuxe' ),
            array( $this, 'settings_field_save_preference' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_general'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_cookie_duration',
            __( 'Preference duration (days)', 'aqualuxe' ),
            array( $this, 'settings_field_cookie_duration' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_general'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_toggle_style',
            __( 'Toggle style', 'aqualuxe' ),
            array( $this, 'settings_field_toggle_style' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_appearance'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_show_in_header',
            __( 'Show toggle in header', 'aqualuxe' ),
            array( $this, 'settings_field_show_in_header' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_appearance'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_show_in_footer',
            __( 'Show toggle in footer', 'aqualuxe' ),
            array( $this, 'settings_field_show_in_footer' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_appearance'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_show_in_mobile',
            __( 'Show toggle in mobile menu', 'aqualuxe' ),
            array( $this, 'settings_field_show_in_mobile' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_appearance'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_background_color',
            __( 'Background color', 'aqualuxe' ),
            array( $this, 'settings_field_background_color' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_colors'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_text_color',
            __( 'Text color', 'aqualuxe' ),
            array( $this, 'settings_field_text_color' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_colors'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_link_color',
            __( 'Link color', 'aqualuxe' ),
            array( $this, 'settings_field_link_color' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_colors'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_border_color',
            __( 'Border color', 'aqualuxe' ),
            array( $this, 'settings_field_border_color' ),
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_colors'
        );
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_dark_mode' );
                do_settings_sections( 'aqualuxe_dark_mode' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Settings section general
     *
     * @return void
     */
    public function settings_section_general() {
        echo '<p>' . esc_html__( 'Configure general dark mode settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section appearance
     *
     * @return void
     */
    public function settings_section_appearance() {
        echo '<p>' . esc_html__( 'Configure dark mode appearance settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section colors
     *
     * @return void
     */
    public function settings_section_colors() {
        echo '<p>' . esc_html__( 'Configure dark mode color settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings field default mode
     *
     * @return void
     */
    public function settings_field_default_mode() {
        $value = get_option( 'aqualuxe_dark_mode_default_mode', 'light' );
        ?>
        <select name="aqualuxe_dark_mode_default_mode" id="aqualuxe_dark_mode_default_mode">
            <option value="light" <?php selected( $value, 'light' ); ?>><?php esc_html_e( 'Light', 'aqualuxe' ); ?></option>
            <option value="dark" <?php selected( $value, 'dark' ); ?>><?php esc_html_e( 'Dark', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the default mode for new visitors.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field auto detect
     *
     * @return void
     */
    public function settings_field_auto_detect() {
        $value = get_option( 'aqualuxe_dark_mode_auto_detect', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_dark_mode_auto_detect" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Automatically detect and use the system preference', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, the theme will use the system preference for new visitors.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field save preference
     *
     * @return void
     */
    public function settings_field_save_preference() {
        $value = get_option( 'aqualuxe_dark_mode_save_preference', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_dark_mode_save_preference" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Save user preference in browser storage', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, the user\'s preference will be saved in browser storage.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field cookie duration
     *
     * @return void
     */
    public function settings_field_cookie_duration() {
        $value = get_option( 'aqualuxe_dark_mode_cookie_duration', 30 );
        ?>
        <input type="number" name="aqualuxe_dark_mode_cookie_duration" value="<?php echo esc_attr( $value ); ?>" min="1" max="365" step="1">
        <p class="description"><?php esc_html_e( 'Number of days to remember the user\'s preference.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field toggle style
     *
     * @return void
     */
    public function settings_field_toggle_style() {
        $value = get_option( 'aqualuxe_dark_mode_toggle_style', 'switch' );
        ?>
        <select name="aqualuxe_dark_mode_toggle_style" id="aqualuxe_dark_mode_toggle_style">
            <option value="switch" <?php selected( $value, 'switch' ); ?>><?php esc_html_e( 'Switch', 'aqualuxe' ); ?></option>
            <option value="icon" <?php selected( $value, 'icon' ); ?>><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></option>
            <option value="button" <?php selected( $value, 'button' ); ?>><?php esc_html_e( 'Button', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the style of the dark mode toggle.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field show in header
     *
     * @return void
     */
    public function settings_field_show_in_header() {
        $value = get_option( 'aqualuxe_dark_mode_show_in_header', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_dark_mode_show_in_header" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Show dark mode toggle in header', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Settings field show in footer
     *
     * @return void
     */
    public function settings_field_show_in_footer() {
        $value = get_option( 'aqualuxe_dark_mode_show_in_footer', false );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_dark_mode_show_in_footer" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Show dark mode toggle in footer', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Settings field show in mobile
     *
     * @return void
     */
    public function settings_field_show_in_mobile() {
        $value = get_option( 'aqualuxe_dark_mode_show_in_mobile', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_dark_mode_show_in_mobile" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Show dark mode toggle in mobile menu', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Settings field background color
     *
     * @return void
     */
    public function settings_field_background_color() {
        $value = get_option( 'aqualuxe_dark_mode_background_color', '#121212' );
        ?>
        <input type="text" name="aqualuxe_dark_mode_background_color" value="<?php echo esc_attr( $value ); ?>" class="aqualuxe-color-picker">
        <?php
    }

    /**
     * Settings field text color
     *
     * @return void
     */
    public function settings_field_text_color() {
        $value = get_option( 'aqualuxe_dark_mode_text_color', '#ffffff' );
        ?>
        <input type="text" name="aqualuxe_dark_mode_text_color" value="<?php echo esc_attr( $value ); ?>" class="aqualuxe-color-picker">
        <?php
    }

    /**
     * Settings field link color
     *
     * @return void
     */
    public function settings_field_link_color() {
        $value = get_option( 'aqualuxe_dark_mode_link_color', '#4dabf7' );
        ?>
        <input type="text" name="aqualuxe_dark_mode_link_color" value="<?php echo esc_attr( $value ); ?>" class="aqualuxe-color-picker">
        <?php
    }

    /**
     * Settings field border color
     *
     * @return void
     */
    public function settings_field_border_color() {
        $value = get_option( 'aqualuxe_dark_mode_border_color', '#333333' );
        ?>
        <input type="text" name="aqualuxe_dark_mode_border_color" value="<?php echo esc_attr( $value ); ?>" class="aqualuxe-color-picker">
        <?php
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
            'default_mode' => 'light',
            'auto_detect' => true,
            'toggle_style' => 'switch',
            'toggle_style_footer' => 'switch',
            'toggle_style_mobile' => 'switch',
            'show_in_header' => true,
            'show_in_footer' => false,
            'show_in_mobile' => true,
            'save_preference' => true,
            'cookie_duration' => 30,
            'background_color' => '#121212',
            'text_color' => '#ffffff',
            'link_color' => '#4dabf7',
            'border_color' => '#333333',
        );
    }

    /**
     * Get custom colors
     *
     * @return array
     */
    public function get_custom_colors() {
        return array(
            'background' => get_option( 'aqualuxe_dark_mode_background_color', '#121212' ),
            'text' => get_option( 'aqualuxe_dark_mode_text_color', '#ffffff' ),
            'link' => get_option( 'aqualuxe_dark_mode_link_color', '#4dabf7' ),
            'border' => get_option( 'aqualuxe_dark_mode_border_color', '#333333' ),
        );
    }
}