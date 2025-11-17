<?php
/**
 * Customizer Sanitization Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Ensure input is a valid key.
    // $input   = sanitize_key($input); 
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize radio.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_radio( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize number.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return int
 */
function aqualuxe_sanitize_number( $input, $setting ) {
    // Ensure $input is an absolute integer.
    $input = absint( $input );

    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;

    // Get min.
    $min = isset( $atts['min'] ) ? $atts['min'] : $input;

    // Get max.
    $max = isset( $atts['max'] ) ? $atts['max'] : $input;

    // Get step.
    $step = isset( $atts['step'] ) ? $atts['step'] : 1;

    // If the input is within the valid range, return it; otherwise, return the default.
    return ( $min <= $input && $input <= $max && is_int( $input / $step ) ? $input : $setting->default );
}

/**
 * Sanitize float.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return float
 */
function aqualuxe_sanitize_float( $input, $setting ) {
    // Ensure $input is a float.
    // return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $input = floatval( $input );

    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;

    // Get min.
    $min = isset( $atts['min'] ) ? $atts['min'] : $input;

    // Get max.
    $max = isset( $atts['max'] ) ? $atts['max'] : $input;

    // Get step.
    $step = isset( $atts['step'] ) ? $atts['step'] : 0.1;

    // If the input is within the valid range, return it; otherwise, return the default.
    return ( $min <= $input && $input <= $max ? $input : $setting->default );
}

/**
 * Sanitize rgba color.
 *
 * @param string $color RGBA color.
 * @return string
 */
function aqualuxe_sanitize_rgba_color( $color ) {
    if ( '' === $color ) {
        return '';
    }

    // If string does not start with 'rgba', then treat as hex.
    // sanitize the hex color and finally convert hex to rgba.
    if ( false === strpos( $color, 'rgba' ) ) {
        return sanitize_hex_color( $color );
    }

    // By now we know the string is formatted as an rgba color so we need to further sanitize it.
    $color = str_replace( ' ', '', $color );
    sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

    return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize image.
 *
 * @param string $image   Image URL.
 * @param object $setting Setting object.
 * @return string
 */
function aqualuxe_sanitize_image( $image, $setting ) {
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
    $file = wp_check_filetype( $image, $mimes );

    // If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Sanitize choices.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_choices( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    $input_keys = $input ? array_map( 'trim', explode( ',', $input ) ) : array();
    $valid_keys = array();

    foreach ( $input_keys as $key ) {
        // If the key is valid, add it to the valid keys array.
        if ( array_key_exists( $key, $choices ) ) {
            $valid_keys[] = $key;
        }
    }

    // Return the valid keys as a comma-separated string.
    return implode( ',', $valid_keys );
}

/**
 * Sanitize html.
 *
 * @param string $input HTML input.
 * @return string
 */
function aqualuxe_sanitize_html( $input ) {
    return wp_kses_post( $input );
}

/**
 * Sanitize dimensions.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_dimensions( $input, $setting ) {
    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;

    // Get the valid units.
    $valid_units = isset( $atts['units'] ) ? $atts['units'] : array( 'px', '%', 'em', 'rem' );

    // Explode the input into an array of values.
    $values = explode( ' ', $input );
    $sanitized_values = array();

    foreach ( $values as $value ) {
        // Get the numeric value.
        $numeric_value = preg_replace( '/[^0-9.-]/', '', $value );

        // Get the unit.
        $unit = str_replace( $numeric_value, '', $value );

        // If the unit is valid, add it to the sanitized values array.
        if ( in_array( $unit, $valid_units, true ) ) {
            $sanitized_values[] = $numeric_value . $unit;
        }
    }

    // Return the sanitized values as a space-separated string.
    return implode( ' ', $sanitized_values );
}

/**
 * Sanitize font family.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_font_family( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize font weight.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_font_weight( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize font style.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_font_style( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize text transform.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_text_transform( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}