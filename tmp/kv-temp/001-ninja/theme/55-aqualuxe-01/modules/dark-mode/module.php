<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Module_Dark_Mode extends AquaLuxe_Module {
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
    protected $description = 'Adds dark mode functionality to the theme with persistent user preference.';

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
    protected function init() {
        // Add body class
        add_filter('body_class', [$this, 'body_class']);

        // Add dark mode toggle to header actions
        add_action('aqualuxe_header_actions', [$this, 'dark_mode_toggle'], 50);

        // Add dark mode script to footer
        add_action('wp_footer', [$this, 'dark_mode_script']);
    }

    /**
     * Register module settings in customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Call parent method
        parent::customize_register($wp_customize);

        // Add dark mode default setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[default_mode]', [
            'default' => 'light',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_default_mode'],
        ]);

        // Add dark mode default control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_default_mode', [
            'label' => __('Default Mode', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[default_mode]',
            'type' => 'select',
            'choices' => [
                'light' => __('Light', 'aqualuxe'),
                'dark' => __('Dark', 'aqualuxe'),
                'auto' => __('Auto (follow system preference)', 'aqualuxe'),
            ],
            'priority' => 20,
        ]);

        // Add dark mode toggle position setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[toggle_position]', [
            'default' => 'header',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_toggle_position'],
        ]);

        // Add dark mode toggle position control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_toggle_position', [
            'label' => __('Toggle Position', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[toggle_position]',
            'type' => 'select',
            'choices' => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both' => __('Both', 'aqualuxe'),
                'none' => __('None', 'aqualuxe'),
            ],
            'priority' => 30,
        ]);

        // Add dark mode animation setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[animation]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add dark mode animation control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_animation', [
            'label' => __('Enable Transition Animation', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[animation]',
            'type' => 'checkbox',
            'priority' => 40,
        ]);
    }

    /**
     * Register module assets
     */
    public function register_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            $this->url . '/assets/css/dark-mode.css',
            [],
            $this->version
        );

        // Register dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $this->url . '/assets/js/dark-mode.js',
            ['jquery', 'alpine'],
            $this->version,
            true
        );

        // Add script data
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', [
            'defaultMode' => $this->get_option('default_mode', 'light'),
            'animation' => $this->get_option('animation', true),
            'i18n' => [
                'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
                'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
                'autoMode' => esc_html__('Auto Mode', 'aqualuxe'),
            ],
        ]);
    }

    /**
     * Add body class
     *
     * @param array $classes
     * @return array
     */
    public function body_class($classes) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return $classes;
        }

        // Add dark mode class
        $classes[] = 'dark-mode-enabled';

        // Add animation class
        if ($this->get_option('animation', true)) {
            $classes[] = 'dark-mode-animated';
        }

        return $classes;
    }

    /**
     * Dark mode toggle
     */
    public function dark_mode_toggle() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Check toggle position
        $position = $this->get_option('toggle_position', 'header');
        if ($position === 'none' || ($position === 'footer' && current_action() === 'aqualuxe_header_actions')) {
            return;
        }

        // Get template
        $this->get_template_part('toggle');
    }

    /**
     * Dark mode script
     */
    public function dark_mode_script() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Get template
        $this->get_template_part('script');
    }

    /**
     * Sanitize default mode
     *
     * @param string $value
     * @return string
     */
    public function sanitize_default_mode($value) {
        $allowed_values = ['light', 'dark', 'auto'];
        return in_array($value, $allowed_values) ? $value : 'light';
    }

    /**
     * Sanitize toggle position
     *
     * @param string $value
     * @return string
     */
    public function sanitize_toggle_position($value) {
        $allowed_values = ['header', 'footer', 'both', 'none'];
        return in_array($value, $allowed_values) ? $value : 'header';
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function get_default_options() {
        return array_merge(parent::get_default_options(), [
            'default_mode' => 'light',
            'toggle_position' => 'header',
            'animation' => true,
        ]);
    }
}

// Initialize module
new AquaLuxe_Module_Dark_Mode();