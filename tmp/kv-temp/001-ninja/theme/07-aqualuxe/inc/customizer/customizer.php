<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add theme customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize)
{
    // Add custom controls
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-heading.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-separator.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-slider.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-toggle.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-radio-image.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-sortable.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-color-alpha.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-dimensions.php';
    require_once AQUALUXE_DIR . '/inc/customizer/controls/control-typography.php';

    // Load customizer sections
    require_once AQUALUXE_DIR . '/inc/customizer/sections/general.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/header.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/footer.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/homepage.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/blog.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/typography.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/colors.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/advanced.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/multilingual.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/dark-mode.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/social-media.php';

    // Load WooCommerce customizer settings if WooCommerce is active
    if (class_exists('WooCommerce')) {
        require_once AQUALUXE_DIR . '/inc/customizer/sections/woocommerce.php';
    }
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Enqueue customizer control panel scripts and styles
 */
function aqualuxe_customize_controls_enqueue_scripts()
{
    wp_enqueue_style(
        'aqualuxe-customizer-style',
        AQUALUXE_URI . '/assets/css/customizer.css',
        [],
        AQUALUXE_VERSION
    );

    wp_enqueue_script(
        'aqualuxe-customizer-script',
        AQUALUXE_URI . '/assets/js/customizer.js',
        ['jquery', 'customize-controls'],
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Enqueue customizer live preview scripts
 */
function aqualuxe_customize_preview_init()
{
    wp_enqueue_script(
        'aqualuxe-customizer-preview',
        AQUALUXE_URI . '/assets/js/customizer-preview.js',
        ['customize-preview', 'jquery'],
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_init');

/**
 * Sanitize checkbox value
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked)
{
    return (isset($checked) && true === $checked);
}

/**
 * Sanitize select value
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting)
{
    $input   = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;

    return array_key_exists($input, $choices) ? $input : $setting->default;
}

/**
 * Sanitize dimensions array
 *
 * @param array $value The dimensions array.
 * @return array
 */
function aqualuxe_sanitize_dimensions($value)
{
    if (!is_array($value)) {
        return [];
    }

    foreach ($value as $key => $val) {
        $value[$key] = absint($val);
    }

    return $value;
}

/**
 * Sanitize float value
 *
 * @param float $input The input from the setting.
 * @return float
 */
function aqualuxe_sanitize_float($input)
{
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize multi select value
 *
 * @param array $input The input from the setting.
 * @return array
 */
function aqualuxe_sanitize_multi_select($input)
{
    if (!is_array($input)) {
        return [];
    }

    foreach ($input as $key => $value) {
        $input[$key] = sanitize_text_field($value);
    }

    return $input;
}
