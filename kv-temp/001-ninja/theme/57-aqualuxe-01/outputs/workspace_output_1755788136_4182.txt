<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Load customizer sections
    require_once AQUALUXE_INC_DIR . 'customizer/sections/general.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/header.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/footer.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/blog.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/colors.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/typography.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/layout.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/social.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/contact.php';

    // Load WooCommerce customizer section if WooCommerce is active
    if (class_exists('WooCommerce')) {
        require_once AQUALUXE_INC_DIR . 'customizer/sections/woocommerce.php';
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
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', array('customize-preview'), filemtime(get_template_directory() . '/assets/dist/js/customizer.js'), true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue customizer control scripts.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    wp_enqueue_script('aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', array('jquery'), filemtime(get_template_directory() . '/assets/dist/js/customizer-controls.js'), true);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true === $checked) ? true : false);
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
 * @param string $input The value to sanitize.
 * @param object $setting The setting object.
 * @return int Sanitized value.
 */
function aqualuxe_sanitize_number($input, $setting) {
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
 * Sanitize textarea.
 *
 * @param string $input The value to sanitize.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_textarea($input) {
    return wp_kses_post($input);
}

/**
 * Sanitize URL.
 *
 * @param string $input The URL to sanitize.
 * @return string Sanitized URL.
 */
function aqualuxe_sanitize_url($input) {
    return esc_url_raw($input);
}

/**
 * Sanitize hex color.
 *
 * @param string $color The color to sanitize.
 * @return string Sanitized color.
 */
function aqualuxe_sanitize_hex_color($color) {
    if ('' === $color) {
        return '';
    }
    
    // 3 or 6 hex digits, or the empty string.
    if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
        return $color;
    }
    
    return '';
}

/**
 * Sanitize rgba color.
 *
 * @param string $color The color to sanitize.
 * @return string Sanitized color.
 */
function aqualuxe_sanitize_rgba_color($color) {
    if ('' === $color) {
        return '';
    }
    
    // If string does not start with 'rgba', then treat as hex.
    // sanitize the hex color and finally convert hex to rgba.
    if (false === strpos($color, 'rgba')) {
        return aqualuxe_sanitize_hex_color($color);
    }
    
    // By now we know the string is formatted as an rgba color so we need to further sanitize it.
    $color = str_replace(' ', '', $color);
    sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
    
    return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize image.
 *
 * @param string $image The image to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized image.
 */
function aqualuxe_sanitize_image($image, $setting) {
    // Array of valid image file types.
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon',
        'svg'          => 'image/svg+xml',
    );
    
    // Return an array with file extension and mime_type.
    $file = wp_check_filetype($image, $mimes);
    
    // If $image has a valid mime_type, return it; otherwise, return the default.
    return ($file['ext'] ? $image : $setting->default);
}

/**
 * Sanitize font.
 *
 * @param string $input The font to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized font.
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
 * @param string $input The font weight to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized font weight.
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
 * @param string $input The font style to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized font style.
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
 * @param string $input The text transform to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized text transform.
 */
function aqualuxe_sanitize_text_transform($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize dimensions.
 *
 * @param string $input The dimensions to sanitize.
 * @return string Sanitized dimensions.
 */
function aqualuxe_sanitize_dimensions($input) {
    return is_array($input) ? array_map('absint', $input) : absint($input);
}

/**
 * Sanitize shortcode.
 *
 * @param string $input The shortcode to sanitize.
 * @return string Sanitized shortcode.
 */
function aqualuxe_sanitize_shortcode($input) {
    return wp_kses($input, array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'class' => array(),
            'target' => array(),
            'rel' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array(
            'class' => array(),
        ),
        'div' => array(
            'class' => array(),
        ),
        'p' => array(
            'class' => array(),
        ),
    ));
}