<?php
/**
 * Sanitization functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @subpackage Helpers
 * @since 1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
if (!function_exists('aqualuxe_sanitize_checkbox')) {
    function aqualuxe_sanitize_checkbox($checked) {
        return (isset($checked) && true === $checked);
    }
}

/**
 * Sanitize select.
 *
 * @param string $input   The input to sanitize.
 * @param object $setting The setting object.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_select')) {
    function aqualuxe_sanitize_select($input, $setting) {
        // Get the list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // Return input if valid or return default option.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}

/**
 * Sanitize image.
 *
 * @param string $image   The image to sanitize.
 * @param object $setting The setting object.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_image')) {
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
            'webp'         => 'image/webp',
        );
        
        // Return an array with file extension and mime_type.
        $file = wp_check_filetype($image, $mimes);
        
        // If $image has a valid mime_type, return it; otherwise, return the default.
        return ($file['ext'] ? $image : $setting->default);
    }
}

/**
 * Sanitize hex color.
 *
 * @param string $color The color to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_hex_color')) {
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
}

/**
 * Sanitize rgba color.
 *
 * @param string $color The color to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_rgba_color')) {
    function aqualuxe_sanitize_rgba_color($color) {
        if ('' === $color) {
            return '';
        }
        
        // Hex color
        if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
            return $color;
        }
        
        // RGBA color
        if (preg_match('/^rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d*(?:\.\d+)?)\s*\)$/', $color, $matches)) {
            $red   = $matches[1] >= 0 && $matches[1] <= 255 ? $matches[1] : 0;
            $green = $matches[2] >= 0 && $matches[2] <= 255 ? $matches[2] : 0;
            $blue  = $matches[3] >= 0 && $matches[3] <= 255 ? $matches[3] : 0;
            $alpha = $matches[4] >= 0 && $matches[4] <= 1 ? $matches[4] : 1;
            
            return "rgba($red,$green,$blue,$alpha)";
        }
        
        // RGB color
        if (preg_match('/^rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/', $color, $matches)) {
            $red   = $matches[1] >= 0 && $matches[1] <= 255 ? $matches[1] : 0;
            $green = $matches[2] >= 0 && $matches[2] <= 255 ? $matches[2] : 0;
            $blue  = $matches[3] >= 0 && $matches[3] <= 255 ? $matches[3] : 0;
            
            return "rgb($red,$green,$blue)";
        }
        
        return '';
    }
}

/**
 * Sanitize dark mode default.
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_dark_mode_default')) {
    function aqualuxe_sanitize_dark_mode_default($input) {
        $valid = array('light', 'dark', 'auto');
        
        if (in_array($input, $valid, true)) {
            return $input;
        }
        
        return 'auto';
    }
}

/**
 * Sanitize font weight.
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_font_weight')) {
    function aqualuxe_sanitize_font_weight($input) {
        $valid = array('100', '200', '300', '400', '500', '600', '700', '800', '900');
        
        if (in_array($input, $valid, true)) {
            return $input;
        }
        
        return '400';
    }
}

/**
 * Sanitize font style.
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_font_style')) {
    function aqualuxe_sanitize_font_style($input) {
        $valid = array('normal', 'italic');
        
        if (in_array($input, $valid, true)) {
            return $input;
        }
        
        return 'normal';
    }
}

/**
 * Sanitize text transform.
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if (!function_exists('aqualuxe_sanitize_text_transform')) {
    function aqualuxe_sanitize_text_transform($input) {
        $valid = array('none', 'capitalize', 'uppercase', 'lowercase');
        
        if (in_array($input, $valid, true)) {
            return $input;
        }
        
        return 'none';
    }
}

/**
 * Sanitize dimensions.
 *
 * @param array $dimensions The dimensions to sanitize.
 * @return array
 */
if (!function_exists('aqualuxe_sanitize_dimensions')) {
    function aqualuxe_sanitize_dimensions($dimensions) {
        if (!is_array($dimensions)) {
            return array();
        }
        
        foreach ($dimensions as $key => $value) {
            $dimensions[$key] = absint($value);
        }
        
        return $dimensions;
    }
}

/**
 * Sanitize sortable.
 *
 * @param array $input The input to sanitize.
 * @return array
 */
if (!function_exists('aqualuxe_sanitize_sortable')) {
    function aqualuxe_sanitize_sortable($input) {
        if (!is_array($input)) {
            return array();
        }
        
        foreach ($input as $key => $value) {
            $input[$key] = sanitize_text_field($value);
        }
        
        return $input;
    }
}