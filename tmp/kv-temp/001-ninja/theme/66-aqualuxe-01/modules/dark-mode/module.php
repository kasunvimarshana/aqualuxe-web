<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe\Modules\DarkMode
 */

namespace AquaLuxe\Modules\DarkMode;

/**
 * Dark Mode Module Class
 */
class Module extends \AquaLuxe\Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'dark-mode';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Dark Mode';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds dark mode support to the theme with persistent preference.';

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
    protected $dependencies = [];

    /**
     * Initialize module
     */
    public function init() {
        // Load module files
        $this->load_files();

        // Register hooks
        $this->register_hooks();

        // Register settings
        $this->register_module_settings();
    }

    /**
     * Load module files
     */
    private function load_files() {
        // Load classes
        require_once $this->path . 'classes/Preferences.php';
        require_once $this->path . 'classes/Customizer.php';
        require_once $this->path . 'classes/Widget.php';
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add dark mode toggle to header
        add_action('aqualuxe_header_extras', [$this, 'render_dark_mode_toggle']);

        // Add dark mode toggle widget
        add_action('widgets_init', [$this, 'register_widgets']);

        // Add dark mode toggle shortcode
        add_shortcode('aqualuxe_dark_mode_toggle', [$this, 'dark_mode_toggle_shortcode']);

        // Add dark mode body class
        add_filter('body_class', [$this, 'add_dark_mode_body_class']);

        // Add dark mode styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        // Add dark mode scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Add dark mode to customizer
        add_action('customize_register', [$this, 'add_to_customizer']);

        // Add dark mode to admin bar
        add_action('admin_bar_menu', [$this, 'add_to_admin_bar'], 100);

        // Add dark mode to localize script
        add_filter('aqualuxe_localize_script', [$this, 'add_to_localize_script']);
    }

    /**
     * Register module settings
     */
    private function register_module_settings() {
        $this->register_settings([
            [
                'option_name' => 'default_mode',
                'args' => [
                    'type' => 'string',
                    'default' => 'auto',
                    'sanitize_callback' => 'sanitize_key',
                ],
            ],
            [
                'option_name' => 'toggle_style',
                'args' => [
                    'type' => 'string',
                    'default' => 'switch',
                    'sanitize_callback' => 'sanitize_key',
                ],
            ],
            [
                'option_name' => 'show_icon',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_text',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'transition_duration',
                'args' => [
                    'type' => 'number',
                    'default' => 300,
                    'sanitize_callback' => 'absint',
                ],
            ],
            [
                'option_name' => 'custom_colors',
                'args' => [
                    'type' => 'boolean',
                    'default' => false,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'background_color',
                'args' => [
                    'type' => 'string',
                    'default' => '#121212',
                    'sanitize_callback' => 'sanitize_hex_color',
                ],
            ],
            [
                'option_name' => 'text_color',
                'args' => [
                    'type' => 'string',
                    'default' => '#e0e0e0',
                    'sanitize_callback' => 'sanitize_hex_color',
                ],
            ],
            [
                'option_name' => 'link_color',
                'args' => [
                    'type' => 'string',
                    'default' => '#90caf9',
                    'sanitize_callback' => 'sanitize_hex_color',
                ],
            ],
            [
                'option_name' => 'accent_color',
                'args' => [
                    'type' => 'string',
                    'default' => '#64b5f6',
                    'sanitize_callback' => 'sanitize_hex_color',
                ],
            ],
        ]);
    }

    /**
     * Render dark mode toggle
     */
    public function render_dark_mode_toggle() {
        $toggle_style = $this->get_setting('toggle_style', 'switch');
        $show_icon = $this->get_setting('show_icon', true);
        $show_text = $this->get_setting('show_text', true);

        $this->get_template_part('toggle', $toggle_style, [
            'show_icon' => $show_icon,
            'show_text' => $show_text,
        ]);
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('\AquaLuxe\Modules\DarkMode\Widget');
    }

    /**
     * Dark mode toggle shortcode
     *
     * @param array $atts
     * @return string
     */
    public function dark_mode_toggle_shortcode($atts) {
        $atts = shortcode_atts([
            'style' => $this->get_setting('toggle_style', 'switch'),
            'show_icon' => $this->get_setting('show_icon', true),
            'show_text' => $this->get_setting('show_text', true),
        ], $atts);

        ob_start();
        $this->get_template_part('toggle', $atts['style'], [
            'show_icon' => filter_var($atts['show_icon'], FILTER_VALIDATE_BOOLEAN),
            'show_text' => filter_var($atts['show_text'], FILTER_VALIDATE_BOOLEAN),
        ]);
        return ob_get_clean();
    }

    /**
     * Add dark mode body class
     *
     * @param array $classes
     * @return array
     */
    public function add_dark_mode_body_class($classes) {
        // Add dark-mode class if preference is dark
        $preferences = new Preferences();
        if ($preferences->get_mode() === 'dark') {
            $classes[] = 'dark-mode';
        }

        return $classes;
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Enqueue dark mode styles
        $this->enqueue_style('aqualuxe-dark-mode', 'assets/css/dark-mode.css');

        // Add inline styles for custom colors
        if ($this->get_setting('custom_colors', false)) {
            $background_color = $this->get_setting('background_color', '#121212');
            $text_color = $this->get_setting('text_color', '#e0e0e0');
            $link_color = $this->get_setting('link_color', '#90caf9');
            $accent_color = $this->get_setting('accent_color', '#64b5f6');

            $custom_css = "
                body.dark-mode {
                    --dark-bg: {$background_color};
                    --dark-text: {$text_color};
                    --dark-link: {$link_color};
                    --dark-accent: {$accent_color};
                }
            ";

            wp_add_inline_style('aqualuxe-dark-mode', $custom_css);
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Enqueue dark mode script
        $this->enqueue_script('aqualuxe-dark-mode', 'assets/js/dark-mode.js', ['jquery']);

        // Add inline script for transition duration
        $transition_duration = $this->get_setting('transition_duration', 300);
        $inline_js = "
            var AqualuxeDarkMode = AqualuxeDarkMode || {};
            AqualuxeDarkMode.transitionDuration = {$transition_duration};
        ";

        wp_add_inline_script('aqualuxe-dark-mode', $inline_js, 'before');
    }

    /**
     * Add to customizer
     *
     * @param \WP_Customize_Manager $wp_customize
     */
    public function add_to_customizer($wp_customize) {
        $customizer = new Customizer($this, $wp_customize);
        $customizer->register();
    }

    /**
     * Add to admin bar
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public function add_to_admin_bar($wp_admin_bar) {
        // Only add to admin bar if user is logged in
        if (!is_user_logged_in()) {
            return;
        }

        // Add dark mode toggle to admin bar
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-dark-mode-toggle',
            'title' => '<span class="ab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg></span><span class="ab-label">' . __('Toggle Dark Mode', 'aqualuxe') . '</span>',
            'href' => '#',
            'meta' => [
                'class' => 'aqualuxe-dark-mode-toggle',
                'title' => __('Toggle Dark Mode', 'aqualuxe'),
            ],
        ]);
    }

    /**
     * Add to localize script
     *
     * @param array $data
     * @return array
     */
    public function add_to_localize_script($data) {
        $preferences = new Preferences();
        
        $data['darkMode'] = [
            'mode' => $preferences->get_mode(),
            'defaultMode' => $this->get_setting('default_mode', 'auto'),
            'transitionDuration' => $this->get_setting('transition_duration', 300),
            'customColors' => $this->get_setting('custom_colors', false),
            'backgroundColor' => $this->get_setting('background_color', '#121212'),
            'textColor' => $this->get_setting('text_color', '#e0e0e0'),
            'linkColor' => $this->get_setting('link_color', '#90caf9'),
            'accentColor' => $this->get_setting('accent_color', '#64b5f6'),
        ];

        return $data;
    }

    /**
     * Get current mode
     *
     * @return string
     */
    public function get_current_mode() {
        $preferences = new Preferences();
        return $preferences->get_mode();
    }

    /**
     * Set mode
     *
     * @param string $mode
     * @return bool
     */
    public function set_mode($mode) {
        $preferences = new Preferences();
        return $preferences->set_mode($mode);
    }

    /**
     * Is dark mode
     *
     * @return bool
     */
    public function is_dark_mode() {
        $preferences = new Preferences();
        return $preferences->get_mode() === 'dark';
    }
}

// Register module
return new Module();