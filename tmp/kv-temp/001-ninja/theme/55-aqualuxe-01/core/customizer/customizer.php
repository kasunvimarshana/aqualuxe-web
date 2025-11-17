<?php
/**
 * AquaLuxe Customizer
 *
 * This file contains the customizer setup for the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add selective refresh support for site title and description
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    // Add selective refresh support for widgets
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector' => '.site-title a',
            'render_callback' => 'aqualuxe_customize_partial_blogname',
        ]);
        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector' => '.site-description',
            'render_callback' => 'aqualuxe_customize_partial_blogdescription',
        ]);
    }

    // Load customizer controls
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-toggle-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-range-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-color-alpha-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-sortable-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-heading-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-divider-control.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/controls/class-aqualuxe-customize-radio-image-control.php';

    // Register custom controls
    $wp_customize->register_control_type('AquaLuxe_Customize_Toggle_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Range_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Color_Alpha_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Sortable_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Heading_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Divider_Control');
    $wp_customize->register_control_type('AquaLuxe_Customize_Radio_Image_Control');

    // Load customizer sections
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/general.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/header.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/footer.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/typography.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/colors.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/layout.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/blog.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/social.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/contact.php';
    require_once AQUALUXE_CORE_DIR . '/customizer/sections/performance.php';

    // Load WooCommerce customizer sections if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        require_once AQUALUXE_CORE_DIR . '/customizer/sections/woocommerce.php';
    }
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script(
        'aqualuxe-customizer',
        AQUALUXE_ASSETS_URI . '/js/customizer.js',
        ['customize-preview'],
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue customizer controls scripts.
 */
function aqualuxe_customize_controls_js() {
    wp_enqueue_script(
        'aqualuxe-customizer-controls',
        AQUALUXE_ASSETS_URI . '/js/customizer-controls.js',
        ['customize-controls', 'jquery'],
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_js');

/**
 * Enqueue customizer controls styles.
 */
function aqualuxe_customize_controls_css() {
    wp_enqueue_style(
        'aqualuxe-customizer-controls',
        AQUALUXE_ASSETS_URI . '/css/customizer-controls.css',
        [],
        AQUALUXE_VERSION
    );
}
add_action('customize_controls_print_styles', 'aqualuxe_customize_controls_css');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize radio.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_radio($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize number.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_number($input, $setting) {
    // Ensure $input is an absolute integer.
    $input = absint($input);

    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control($setting->id)->input_attrs;

    // Get min.
    $min = isset($atts['min']) ? $atts['min'] : $input;

    // Get max.
    $max = isset($atts['max']) ? $atts['max'] : $input;

    // Get step.
    $step = isset($atts['step']) ? $atts['step'] : 1;

    // If the input is valid, return it; otherwise, return the default.
    return ($min <= $input && $input <= $max && is_int($input / $step) ? $input : $setting->default);
}

/**
 * Sanitize range.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_range($input, $setting) {
    // Ensure $input is a number.
    $input = is_numeric($input) ? $input : 0;

    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control($setting->id)->input_attrs;

    // Get min.
    $min = isset($atts['min']) ? $atts['min'] : $input;

    // Get max.
    $max = isset($atts['max']) ? $atts['max'] : $input;

    // Get step.
    $step = isset($atts['step']) ? $atts['step'] : 1;

    // If the input is valid, return it; otherwise, return the default.
    return ($min <= $input && $input <= $max ? $input : $setting->default);
}

/**
 * Sanitize textarea.
 *
 * @param string $input The input from the setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_textarea($input) {
    return wp_kses_post($input);
}

/**
 * Sanitize URL.
 *
 * @param string $input The input from the setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_url($input) {
    return esc_url_raw($input);
}

/**
 * Sanitize sortable.
 *
 * @param string $input The input from the setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_sortable($input) {
    // If the input is a string, convert it to an array.
    if (!is_array($input)) {
        $input = explode(',', $input);
    }

    // Return the sanitized array.
    return array_map('sanitize_text_field', $input);
}

/**
 * Sanitize rgba color.
 *
 * @param string $color The input from the setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_rgba($color) {
    // If empty or an array return transparent.
    if (empty($color) || is_array($color)) {
        return 'rgba(0,0,0,0)';
    }

    // If string does not start with 'rgba', then treat as hex.
    // sanitize the hex color and finally convert hex to rgba.
    if (false === strpos($color, 'rgba')) {
        return sanitize_hex_color($color);
    }

    // By now we know the string is formatted as an rgba color so we need to further sanitize it.
    $color = str_replace(' ', '', $color);
    sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);

    return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize image.
 *
 * @param string $input The input from the setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_image($input) {
    // If the input is a valid attachment ID, return it; otherwise, return the default.
    return (is_numeric($input) ? $input : '');
}

/**
 * Sanitize dimensions.
 *
 * @param array $input The input from the setting.
 * @return array The sanitized input.
 */
function aqualuxe_sanitize_dimensions($input) {
    // If the input is not an array, return an empty array.
    if (!is_array($input)) {
        return [];
    }

    // Sanitize each dimension.
    $output = [];
    foreach ($input as $key => $value) {
        $output[$key] = is_numeric($value) ? $value : 0;
    }

    return $output;
}

/**
 * Sanitize font.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_font($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize font weight.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_font_weight($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize font style.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_font_style($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize text transform.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_text_transform($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize radio image.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_radio_image($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}