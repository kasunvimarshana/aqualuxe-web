<?php
/**
 * AquaLuxe Customizer Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Customizer settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Customizer panels
     *
     * @var array
     */
    private $panels = [];

    /**
     * Customizer sections
     *
     * @var array
     */
    private $sections = [];

    /**
     * Customizer controls
     *
     * @var array
     */
    private $controls = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Add customizer settings
        add_action('customize_register', [$this, 'register_customizer']);
        
        // Add customizer preview script
        add_action('customize_preview_init', [$this, 'preview_script']);
        
        // Add customizer controls script
        add_action('customize_controls_enqueue_scripts', [$this, 'controls_script']);
    }

    /**
     * Register customizer
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer($wp_customize) {
        // Register panels
        foreach ($this->panels as $panel_id => $panel) {
            $wp_customize->add_panel($panel_id, $panel);
        }
        
        // Register sections
        foreach ($this->sections as $section_id => $section) {
            $wp_customize->add_section($section_id, $section);
        }
        
        // Register settings and controls
        foreach ($this->settings as $setting_id => $setting) {
            $wp_customize->add_setting($setting_id, $setting);
            
            if (isset($this->controls[$setting_id])) {
                $control = $this->controls[$setting_id];
                
                // Check if custom control
                if (isset($control['type']) && class_exists($control['type'])) {
                    $control_type = $control['type'];
                    unset($control['type']);
                    
                    $wp_customize->add_control(new $control_type($wp_customize, $setting_id, $control));
                } else {
                    $wp_customize->add_control($setting_id, $control);
                }
            }
        }
    }

    /**
     * Add panel
     *
     * @param string $panel_id Panel ID
     * @param array $args Panel arguments
     */
    public function add_panel($panel_id, $args) {
        $this->panels[$panel_id] = $args;
    }

    /**
     * Add section
     *
     * @param string $section_id Section ID
     * @param array $args Section arguments
     */
    public function add_section($section_id, $args) {
        $this->sections[$section_id] = $args;
    }

    /**
     * Add setting
     *
     * @param string $setting_id Setting ID
     * @param array $args Setting arguments
     */
    public function add_setting($setting_id, $args) {
        $defaults = [
            'default'           => '',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => '',
        ];
        
        $this->settings[$setting_id] = wp_parse_args($args, $defaults);
    }

    /**
     * Add control
     *
     * @param string $control_id Control ID
     * @param array $args Control arguments
     */
    public function add_control($control_id, $args) {
        $this->controls[$control_id] = $args;
    }

    /**
     * Add color control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_color_control($setting_id, $section_id, $label, $default = '#000000', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'color',
            'priority' => $priority,
        ]);
    }

    /**
     * Add image control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_image_control($setting_id, $section_id, $label, $default = '', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'WP_Customize_Image_Control',
            'priority' => $priority,
        ]);
    }

    /**
     * Add text control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_text_control($setting_id, $section_id, $label, $default = '', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'text',
            'priority' => $priority,
        ]);
    }

    /**
     * Add textarea control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_textarea_control($setting_id, $section_id, $label, $default = '', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'textarea',
            'priority' => $priority,
        ]);
    }

    /**
     * Add checkbox control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param bool $default Default value
     * @param int $priority Priority
     */
    public function add_checkbox_control($setting_id, $section_id, $label, $default = false, $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'checkbox',
            'priority' => $priority,
        ]);
    }

    /**
     * Add select control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param array $choices Choices
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_select_control($setting_id, $section_id, $label, $choices, $default = '', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'select',
            'choices'  => $choices,
            'priority' => $priority,
        ]);
    }

    /**
     * Add radio control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param array $choices Choices
     * @param string $default Default value
     * @param int $priority Priority
     */
    public function add_radio_control($setting_id, $section_id, $label, $choices, $default = '', $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control($setting_id, [
            'label'    => $label,
            'section'  => $section_id,
            'settings' => $setting_id,
            'type'     => 'radio',
            'choices'  => $choices,
            'priority' => $priority,
        ]);
    }

    /**
     * Add range control
     *
     * @param string $setting_id Setting ID
     * @param string $section_id Section ID
     * @param string $label Label
     * @param int $default Default value
     * @param int $min Min value
     * @param int $max Max value
     * @param int $step Step
     * @param int $priority Priority
     */
    public function add_range_control($setting_id, $section_id, $label, $default = 0, $min = 0, $max = 100, $step = 1, $priority = 10) {
        $this->add_setting($setting_id, [
            'default'           => $default,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        ]);
        
        $this->add_control($setting_id, [
            'label'       => $label,
            'section'     => $section_id,
            'settings'    => $setting_id,
            'type'        => 'range',
            'input_attrs' => [
                'min'  => $min,
                'max'  => $max,
                'step' => $step,
            ],
            'priority'    => $priority,
        ]);
    }

    /**
     * Preview script
     */
    public function preview_script() {
        AquaLuxe_Assets::enqueue_script('aqualuxe-customizer-preview', 'js/customizer.js', ['customize-preview', 'jquery']);
    }

    /**
     * Controls script
     */
    public function controls_script() {
        AquaLuxe_Assets::enqueue_script('aqualuxe-customizer-controls', 'js/customizer-controls.js', ['customize-controls', 'jquery']);
        AquaLuxe_Assets::enqueue_style('aqualuxe-customizer-controls', 'css/customizer-controls.css');
    }
}