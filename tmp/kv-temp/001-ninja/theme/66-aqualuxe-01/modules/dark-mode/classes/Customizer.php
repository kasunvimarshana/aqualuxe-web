<?php
/**
 * Dark Mode Customizer Class
 *
 * @package AquaLuxe\Modules\DarkMode
 */

namespace AquaLuxe\Modules\DarkMode;

/**
 * Dark Mode Customizer Class
 */
class Customizer {
    /**
     * Module instance
     *
     * @var Module
     */
    private $module;

    /**
     * WP_Customize_Manager instance
     *
     * @var \WP_Customize_Manager
     */
    private $wp_customize;

    /**
     * Constructor
     *
     * @param Module $module
     * @param \WP_Customize_Manager $wp_customize
     */
    public function __construct($module, $wp_customize) {
        $this->module = $module;
        $this->wp_customize = $wp_customize;
    }

    /**
     * Register customizer settings
     */
    public function register() {
        // Add dark mode section
        $this->wp_customize->add_section('aqualuxe_dark_mode', [
            'title' => __('Dark Mode', 'aqualuxe'),
            'priority' => 130,
        ]);

        // Add default mode setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_default_mode', [
            'default' => 'auto',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_key',
        ]);

        // Add default mode control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_default_mode', [
            'label' => __('Default Mode', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'light' => __('Light', 'aqualuxe'),
                'dark' => __('Dark', 'aqualuxe'),
                'auto' => __('Auto (System Preference)', 'aqualuxe'),
            ],
        ]);

        // Add toggle style setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_toggle_style', [
            'default' => 'switch',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_key',
        ]);

        // Add toggle style control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_toggle_style', [
            'label' => __('Toggle Style', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'switch' => __('Switch', 'aqualuxe'),
                'button' => __('Button', 'aqualuxe'),
                'icon' => __('Icon Only', 'aqualuxe'),
            ],
        ]);

        // Add show icon setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_show_icon', [
            'default' => true,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add show icon control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_show_icon', [
            'label' => __('Show Icon', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);

        // Add show text setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_show_text', [
            'default' => true,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add show text control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_show_text', [
            'label' => __('Show Text', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);

        // Add transition duration setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_transition_duration', [
            'default' => 300,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'absint',
        ]);

        // Add transition duration control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_transition_duration', [
            'label' => __('Transition Duration (ms)', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'number',
            'input_attrs' => [
                'min' => 0,
                'max' => 1000,
                'step' => 50,
            ],
        ]);

        // Add custom colors setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_custom_colors', [
            'default' => false,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add custom colors control
        $this->wp_customize->add_control('aqualuxe_module_dark-mode_custom_colors', [
            'label' => __('Use Custom Colors', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);

        // Add background color setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_background_color', [
            'default' => '#121212',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Add background color control
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_module_dark-mode_background_color', [
            'label' => __('Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'active_callback' => [$this, 'is_custom_colors_enabled'],
        ]));

        // Add text color setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_text_color', [
            'default' => '#e0e0e0',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Add text color control
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_module_dark-mode_text_color', [
            'label' => __('Text Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'active_callback' => [$this, 'is_custom_colors_enabled'],
        ]));

        // Add link color setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_link_color', [
            'default' => '#90caf9',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Add link color control
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_module_dark-mode_link_color', [
            'label' => __('Link Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'active_callback' => [$this, 'is_custom_colors_enabled'],
        ]));

        // Add accent color setting
        $this->wp_customize->add_setting('aqualuxe_module_dark-mode_accent_color', [
            'default' => '#64b5f6',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Add accent color control
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_module_dark-mode_accent_color', [
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'active_callback' => [$this, 'is_custom_colors_enabled'],
        ]));
    }

    /**
     * Check if custom colors are enabled
     *
     * @return bool
     */
    public function is_custom_colors_enabled() {
        return get_option('aqualuxe_module_dark-mode_custom_colors', false);
    }
}