<?php
/**
 * AquaLuxe Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage DarkMode
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Dark Mode Module Class
 * 
 * Handles dark mode functionality for the theme
 */
class AquaLuxe_DarkMode_Module {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_DarkMode_Module
     */
    private static $instance = null;

    /**
     * Module settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_DarkMode_Module
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load module settings
        $this->load_settings();
        
        // Initialize module
        $this->init();
    }

    /**
     * Load module settings
     */
    private function load_settings() {
        $this->settings = apply_filters('aqualuxe_dark_mode_settings', [
            'enabled' => true,
            'default_mode' => 'light', // light, dark, auto (system preference)
            'toggle_style' => 'switch', // switch, icon, text
            'toggle_position' => 'header', // header, footer, menu, sidebar
            'auto_detect_system' => true,
            'remember_user_preference' => true,
            'cookie_expiration' => 30, // days
            'transition_duration' => 300, // milliseconds
            'custom_colors' => false,
            'dark_mode_colors' => [
                'background' => '#121212',
                'surface' => '#1e1e1e',
                'primary' => '#63b3ed',
                'secondary' => '#4fd1c5',
                'text' => '#f7fafc',
                'border' => '#4a5568',
            ],
        ]);
    }

    /**
     * Initialize module
     */
    private function init() {
        // Check if module is enabled
        if (!$this->settings['enabled']) {
            return;
        }

        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add toggle button
        switch ($this->settings['toggle_position']) {
            case 'header':
                add_action('aqualuxe_header', [$this, 'render_toggle']);
                break;
            case 'footer':
                add_action('aqualuxe_footer', [$this, 'render_toggle']);
                break;
            case 'menu':
                add_filter('wp_nav_menu_items', [$this, 'add_toggle_to_menu'], 10, 2);
                break;
            case 'sidebar':
                add_action('aqualuxe_sidebar', [$this, 'render_toggle']);
                break;
        }
        
        // Add body class
        add_filter('body_class', [$this, 'add_body_class']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'customize_register']);
        
        // Add widget
        add_action('widgets_init', [$this, 'register_widgets']);
        
        // Add admin settings
        add_action('admin_init', [$this, 'admin_init']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'admin_menu']);
        
        // Add custom colors to head
        add_action('wp_head', [$this, 'add_custom_colors']);
    }

    /**
     * Render dark mode toggle
     */
    public function render_toggle() {
        // Get toggle style
        $style = $this->settings['toggle_style'];
        
        // Get current mode
        $current_mode = $this->get_current_mode();
        
        // Start output
        echo '<div class="aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle-' . esc_attr($style) . '">';
        
        switch ($style) {
            case 'switch':
                $this->render_switch_toggle($current_mode);
                break;
            case 'icon':
                $this->render_icon_toggle($current_mode);
                break;
            case 'text':
                $this->render_text_toggle($current_mode);
                break;
        }
        
        echo '</div>';
    }

    /**
     * Render switch toggle
     *
     * @param string $current_mode Current mode (light or dark)
     */
    private function render_switch_toggle($current_mode) {
        $checked = $current_mode === 'dark' ? ' checked' : '';
        
        echo '<label class="aqualuxe-dark-mode-switch">';
        echo '<span class="screen-reader-text">' . esc_html__('Toggle Dark Mode', 'aqualuxe') . '</span>';
        echo '<input type="checkbox" class="aqualuxe-dark-mode-checkbox"' . $checked . '>';
        echo '<span class="aqualuxe-dark-mode-slider"></span>';
        echo '</label>';
    }

    /**
     * Render icon toggle
     *
     * @param string $current_mode Current mode (light or dark)
     */
    private function render_icon_toggle($current_mode) {
        echo '<button class="aqualuxe-dark-mode-icon" aria-label="' . esc_attr__('Toggle Dark Mode', 'aqualuxe') . '">';
        echo '<span class="aqualuxe-dark-mode-icon-light' . ($current_mode === 'light' ? ' active' : '') . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>';
        echo '</span>';
        echo '<span class="aqualuxe-dark-mode-icon-dark' . ($current_mode === 'dark' ? ' active' : '') . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>';
        echo '</span>';
        echo '</button>';
    }

    /**
     * Render text toggle
     *
     * @param string $current_mode Current mode (light or dark)
     */
    private function render_text_toggle($current_mode) {
        echo '<button class="aqualuxe-dark-mode-text" aria-label="' . esc_attr__('Toggle Dark Mode', 'aqualuxe') . '">';
        echo '<span class="aqualuxe-dark-mode-text-light' . ($current_mode === 'light' ? ' active' : '') . '">';
        echo esc_html__('Light', 'aqualuxe');
        echo '</span>';
        echo '<span class="aqualuxe-dark-mode-separator">|</span>';
        echo '<span class="aqualuxe-dark-mode-text-dark' . ($current_mode === 'dark' ? ' active' : '') . '">';
        echo esc_html__('Dark', 'aqualuxe');
        echo '</span>';
        echo '</button>';
    }

    /**
     * Add toggle to menu
     *
     * @param string $items Menu items HTML
     * @param object $args Menu arguments
     * @return string
     */
    public function add_toggle_to_menu($items, $args) {
        // Only add to primary menu
        if ($args->theme_location !== 'primary') {
            return $items;
        }
        
        // Get current mode
        $current_mode = $this->get_current_mode();
        
        // Start output
        $output = '<li class="menu-item aqualuxe-dark-mode-menu-item">';
        $output .= '<a href="#" class="aqualuxe-dark-mode-menu-toggle">';
        
        // Add icon
        $output .= '<span class="aqualuxe-dark-mode-menu-icon">';
        if ($current_mode === 'dark') {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>';
        } else {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>';
        }
        $output .= '</span>';
        
        // Add text
        $output .= '<span class="aqualuxe-dark-mode-menu-text">';
        if ($current_mode === 'dark') {
            $output .= esc_html__('Light Mode', 'aqualuxe');
        } else {
            $output .= esc_html__('Dark Mode', 'aqualuxe');
        }
        $output .= '</span>';
        
        $output .= '</a>';
        $output .= '</li>';
        
        return $items . $output;
    }

    /**
     * Add body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function add_body_class($classes) {
        // Get current mode
        $current_mode = $this->get_current_mode();
        
        // Add class
        $classes[] = 'aqualuxe-' . $current_mode . '-mode';
        
        return $classes;
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_URI . 'modules/dark-mode/assets/css/dark-mode.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_URI . 'modules/dark-mode/assets/js/dark-mode.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-dark-mode-nonce'),
            'currentMode' => $this->get_current_mode(),
            'defaultMode' => $this->settings['default_mode'],
            'autoDetectSystem' => $this->settings['auto_detect_system'],
            'rememberPreference' => $this->settings['remember_user_preference'],
            'cookieExpiration' => $this->settings['cookie_expiration'],
            'transitionDuration' => $this->settings['transition_duration'],
        ]);
    }

    /**
     * Get current mode
     *
     * @return string
     */
    public function get_current_mode() {
        $mode = $this->settings['default_mode'];
        
        // Check for cookie
        if ($this->settings['remember_user_preference'] && isset($_COOKIE['aqualuxe_dark_mode'])) {
            $cookie_mode = sanitize_text_field($_COOKIE['aqualuxe_dark_mode']);
            
            if (in_array($cookie_mode, ['light', 'dark'])) {
                $mode = $cookie_mode;
            }
        }
        
        // Auto detect system preference if no cookie is set
        if ($this->settings['auto_detect_system'] && !isset($_COOKIE['aqualuxe_dark_mode']) && $mode === 'auto') {
            // We can't detect system preference server-side, so we'll default to light
            // The JavaScript will handle this on the client side
            $mode = 'light';
        }
        
        return $mode;
    }

    /**
     * Add custom colors to head
     */
    public function add_custom_colors() {
        // Check if custom colors are enabled
        if (!$this->settings['custom_colors']) {
            return;
        }
        
        // Get colors
        $colors = $this->settings['dark_mode_colors'];
        
        // Output CSS
        echo '<style id="aqualuxe-dark-mode-colors">';
        echo '.aqualuxe-dark-mode {';
        echo '--aqualuxe-bg-dark: ' . esc_attr($colors['background']) . ';';
        echo '--aqualuxe-surface-dark: ' . esc_attr($colors['surface']) . ';';
        echo '--aqualuxe-primary-dark: ' . esc_attr($colors['primary']) . ';';
        echo '--aqualuxe-secondary-dark: ' . esc_attr($colors['secondary']) . ';';
        echo '--aqualuxe-text-dark: ' . esc_attr($colors['text']) . ';';
        echo '--aqualuxe-border-dark: ' . esc_attr($colors['border']) . ';';
        echo '}';
        echo '</style>';
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer object
     */
    public function customize_register($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_dark_mode', [
            'title' => __('Dark Mode Settings', 'aqualuxe'),
            'priority' => 130,
        ]);
        
        // Add settings
        $wp_customize->add_setting('aqualuxe_dark_mode_enabled', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_default', [
            'default' => 'light',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_toggle_style', [
            'default' => 'switch',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_toggle_position', [
            'default' => 'header',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_auto_detect', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_remember', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_dark_mode_custom_colors', [
            'default' => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        // Add controls
        $wp_customize->add_control('aqualuxe_dark_mode_enabled', [
            'label' => __('Enable Dark Mode', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_default', [
            'label' => __('Default Mode', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'light' => __('Light', 'aqualuxe'),
                'dark' => __('Dark', 'aqualuxe'),
                'auto' => __('Auto (System Preference)', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_toggle_style', [
            'label' => __('Toggle Style', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'switch' => __('Switch', 'aqualuxe'),
                'icon' => __('Icon', 'aqualuxe'),
                'text' => __('Text', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_toggle_position', [
            'label' => __('Toggle Position', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'menu' => __('Primary Menu', 'aqualuxe'),
                'sidebar' => __('Sidebar', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_auto_detect', [
            'label' => __('Auto-detect System Preference', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_remember', [
            'label' => __('Remember User Preference', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_custom_colors', [
            'label' => __('Use Custom Colors', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        // Add color controls if WP version supports it
        if (method_exists($wp_customize, 'add_setting') && class_exists('WP_Customize_Color_Control')) {
            // Background color
            $wp_customize->add_setting('aqualuxe_dark_mode_bg_color', [
                'default' => '#121212',
                'sanitize_callback' => 'sanitize_hex_color',
            ]);
            
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_bg_color', [
                'label' => __('Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_bg_color',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_dark_mode_custom_colors', false);
                },
            ]));
            
            // Surface color
            $wp_customize->add_setting('aqualuxe_dark_mode_surface_color', [
                'default' => '#1e1e1e',
                'sanitize_callback' => 'sanitize_hex_color',
            ]);
            
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_surface_color', [
                'label' => __('Surface Color', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_surface_color',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_dark_mode_custom_colors', false);
                },
            ]));
            
            // Primary color
            $wp_customize->add_setting('aqualuxe_dark_mode_primary_color', [
                'default' => '#63b3ed',
                'sanitize_callback' => 'sanitize_hex_color',
            ]);
            
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_primary_color', [
                'label' => __('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_primary_color',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_dark_mode_custom_colors', false);
                },
            ]));
            
            // Text color
            $wp_customize->add_setting('aqualuxe_dark_mode_text_color', [
                'default' => '#f7fafc',
                'sanitize_callback' => 'sanitize_hex_color',
            ]);
            
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_text_color', [
                'label' => __('Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_text_color',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_dark_mode_custom_colors', false);
                },
            ]));
        }
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('AquaLuxe_DarkMode_Widget');
    }

    /**
     * Admin init
     */
    public function admin_init() {
        // Register settings
        register_setting('aqualuxe_dark_mode_settings', 'aqualuxe_dark_mode_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings'],
        ]);
        
        // Add settings section
        add_settings_section(
            'aqualuxe_dark_mode_section',
            __('Dark Mode Settings', 'aqualuxe'),
            [$this, 'settings_section_callback'],
            'aqualuxe_dark_mode'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_dark_mode_enabled',
            __('Enable Dark Mode', 'aqualuxe'),
            [$this, 'enabled_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_default',
            __('Default Mode', 'aqualuxe'),
            [$this, 'default_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_toggle_style',
            __('Toggle Style', 'aqualuxe'),
            [$this, 'toggle_style_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_toggle_position',
            __('Toggle Position', 'aqualuxe'),
            [$this, 'toggle_position_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_auto_detect',
            __('Auto-detect System Preference', 'aqualuxe'),
            [$this, 'auto_detect_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_remember',
            __('Remember User Preference', 'aqualuxe'),
            [$this, 'remember_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
        
        add_settings_field(
            'aqualuxe_dark_mode_custom_colors',
            __('Use Custom Colors', 'aqualuxe'),
            [$this, 'custom_colors_field_callback'],
            'aqualuxe_dark_mode',
            'aqualuxe_dark_mode_section'
        );
    }

    /**
     * Admin menu
     */
    public function admin_menu() {
        add_submenu_page(
            'themes.php',
            __('Dark Mode Settings', 'aqualuxe'),
            __('Dark Mode', 'aqualuxe'),
            'manage_options',
            'aqualuxe_dark_mode',
            [$this, 'settings_page']
        );
    }

    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>' . esc_html__('Configure dark mode settings for your site.', 'aqualuxe') . '</p>';
    }

    /**
     * Enabled field callback
     */
    public function enabled_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $enabled = isset($settings['enabled']) ? $settings['enabled'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_dark_mode_settings[enabled]" value="1"' . checked($enabled, true, false) . '>';
    }

    /**
     * Default field callback
     */
    public function default_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $default = isset($settings['default_mode']) ? $settings['default_mode'] : 'light';
        
        echo '<select name="aqualuxe_dark_mode_settings[default_mode]">';
        echo '<option value="light"' . selected($default, 'light', false) . '>' . esc_html__('Light', 'aqualuxe') . '</option>';
        echo '<option value="dark"' . selected($default, 'dark', false) . '>' . esc_html__('Dark', 'aqualuxe') . '</option>';
        echo '<option value="auto"' . selected($default, 'auto', false) . '>' . esc_html__('Auto (System Preference)', 'aqualuxe') . '</option>';
        echo '</select>';
    }

    /**
     * Toggle style field callback
     */
    public function toggle_style_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $style = isset($settings['toggle_style']) ? $settings['toggle_style'] : 'switch';
        
        echo '<select name="aqualuxe_dark_mode_settings[toggle_style]">';
        echo '<option value="switch"' . selected($style, 'switch', false) . '>' . esc_html__('Switch', 'aqualuxe') . '</option>';
        echo '<option value="icon"' . selected($style, 'icon', false) . '>' . esc_html__('Icon', 'aqualuxe') . '</option>';
        echo '<option value="text"' . selected($style, 'text', false) . '>' . esc_html__('Text', 'aqualuxe') . '</option>';
        echo '</select>';
    }

    /**
     * Toggle position field callback
     */
    public function toggle_position_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $position = isset($settings['toggle_position']) ? $settings['toggle_position'] : 'header';
        
        echo '<select name="aqualuxe_dark_mode_settings[toggle_position]">';
        echo '<option value="header"' . selected($position, 'header', false) . '>' . esc_html__('Header', 'aqualuxe') . '</option>';
        echo '<option value="footer"' . selected($position, 'footer', false) . '>' . esc_html__('Footer', 'aqualuxe') . '</option>';
        echo '<option value="menu"' . selected($position, 'menu', false) . '>' . esc_html__('Primary Menu', 'aqualuxe') . '</option>';
        echo '<option value="sidebar"' . selected($position, 'sidebar', false) . '>' . esc_html__('Sidebar', 'aqualuxe') . '</option>';
        echo '</select>';
    }

    /**
     * Auto detect field callback
     */
    public function auto_detect_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $auto_detect = isset($settings['auto_detect_system']) ? $settings['auto_detect_system'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_dark_mode_settings[auto_detect_system]" value="1"' . checked($auto_detect, true, false) . '>';
    }

    /**
     * Remember field callback
     */
    public function remember_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $remember = isset($settings['remember_user_preference']) ? $settings['remember_user_preference'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_dark_mode_settings[remember_user_preference]" value="1"' . checked($remember, true, false) . '>';
    }

    /**
     * Custom colors field callback
     */
    public function custom_colors_field_callback() {
        $settings = get_option('aqualuxe_dark_mode_settings', []);
        $custom_colors = isset($settings['custom_colors']) ? $settings['custom_colors'] : false;
        
        echo '<input type="checkbox" name="aqualuxe_dark_mode_settings[custom_colors]" value="1"' . checked($custom_colors, true, false) . '>';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Show settings form
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aqualuxe_dark_mode_settings');
                do_settings_sections('aqualuxe_dark_mode');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input
     * @return array
     */
    public function sanitize_settings($input) {
        $output = [];
        
        // Sanitize enabled
        $output['enabled'] = isset($input['enabled']) ? (bool) $input['enabled'] : false;
        
        // Sanitize default mode
        $output['default_mode'] = isset($input['default_mode']) && in_array($input['default_mode'], ['light', 'dark', 'auto']) ? $input['default_mode'] : 'light';
        
        // Sanitize toggle style
        $output['toggle_style'] = isset($input['toggle_style']) && in_array($input['toggle_style'], ['switch', 'icon', 'text']) ? $input['toggle_style'] : 'switch';
        
        // Sanitize toggle position
        $output['toggle_position'] = isset($input['toggle_position']) && in_array($input['toggle_position'], ['header', 'footer', 'menu', 'sidebar']) ? $input['toggle_position'] : 'header';
        
        // Sanitize auto detect
        $output['auto_detect_system'] = isset($input['auto_detect_system']) ? (bool) $input['auto_detect_system'] : false;
        
        // Sanitize remember preference
        $output['remember_user_preference'] = isset($input['remember_user_preference']) ? (bool) $input['remember_user_preference'] : false;
        
        // Sanitize custom colors
        $output['custom_colors'] = isset($input['custom_colors']) ? (bool) $input['custom_colors'] : false;
        
        return $output;
    }
}

/**
 * AquaLuxe Dark Mode Widget
 */
class AquaLuxe_DarkMode_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_dark_mode_widget',
            __('AquaLuxe Dark Mode Toggle', 'aqualuxe'),
            [
                'description' => __('Displays a dark mode toggle switch.', 'aqualuxe'),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Get style
        $style = !empty($instance['style']) ? $instance['style'] : 'switch';
        
        // Get dark mode module
        $dark_mode = AquaLuxe_DarkMode_Module::instance();
        
        // Override style
        add_filter('aqualuxe_dark_mode_settings', function($settings) use ($style) {
            $settings['toggle_style'] = $style;
            return $settings;
        });
        
        // Display dark mode toggle
        $dark_mode->render_toggle();
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $style = !empty($instance['style']) ? $instance['style'] : 'switch';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'aqualuxe'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>">
                <?php esc_html_e('Style:', 'aqualuxe'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="switch" <?php selected($style, 'switch'); ?>><?php esc_html_e('Switch', 'aqualuxe'); ?></option>
                <option value="icon" <?php selected($style, 'icon'); ?>><?php esc_html_e('Icon', 'aqualuxe'); ?></option>
                <option value="text" <?php selected($style, 'text'); ?>><?php esc_html_e('Text', 'aqualuxe'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['style'] = (!empty($new_instance['style'])) ? sanitize_text_field($new_instance['style']) : 'switch';
        
        return $instance;
    }
}

// Initialize the module
AquaLuxe_DarkMode_Module::instance();