<?php
/**
 * Sanitize functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input The input from the setting.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default option.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize float
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize multi-select
 *
 * @param array $input The input from the setting.
 * @return array The sanitized input.
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_input = array();
    foreach ($input as $value) {
        $valid_input[] = sanitize_text_field($value);
    }
    
    return $valid_input;
}

/**
 * Sanitize hex color
 *
 * @param string $color The color to sanitize.
 * @return string The sanitized color.
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
 * Sanitize rgba color
 *
 * @param string $color The color to sanitize.
 * @return string The sanitized color.
 */
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
        $red = $matches[1];
        $green = $matches[2];
        $blue = $matches[3];
        $alpha = $matches[4];
        
        if ($red >= 0 && $red <= 255 && $green >= 0 && $green <= 255 && $blue >= 0 && $blue <= 255 && $alpha >= 0 && $alpha <= 1) {
            return "rgba($red, $green, $blue, $alpha)";
        }
    }
    
    // RGB color
    if (preg_match('/^rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/', $color, $matches)) {
        $red = $matches[1];
        $green = $matches[2];
        $blue = $matches[3];
        
        if ($red >= 0 && $red <= 255 && $green >= 0 && $green <= 255 && $blue >= 0 && $blue <= 255) {
            return "rgb($red, $green, $blue)";
        }
    }
    
    return '';
}

/**
 * Sanitize image
 *
 * @param string $image The image to sanitize.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized image.
 */
function aqualuxe_sanitize_image($image, $setting) {
    // Array with valid image file types.
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
 * Sanitize file
 *
 * @param string $file The file to sanitize.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized file.
 */
function aqualuxe_sanitize_file($file, $setting) {
    // Array with valid file types.
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon',
        'svg'          => 'image/svg+xml',
        'pdf'          => 'application/pdf',
        'doc|docx'     => 'application/msword',
        'xls|xlsx'     => 'application/vnd.ms-excel',
        'ppt|pptx'     => 'application/vnd.ms-powerpoint',
        'zip'          => 'application/zip',
        'txt'          => 'text/plain',
    );
    
    // Return an array with file extension and mime_type.
    $file_check = wp_check_filetype($file, $mimes);
    
    // If $file has a valid mime_type, return it; otherwise, return the default.
    return ($file_check['ext'] ? $file : $setting->default);
}

/**
 * Sanitize number
 *
 * @param int $number The number to sanitize.
 * @param WP_Customize_Setting $setting The setting object.
 * @return int The sanitized number.
 */
function aqualuxe_sanitize_number($number, $setting) {
    // Ensure $number is an absolute integer.
    $number = absint($number);
    
    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    
    // Get min.
    $min = isset($atts['min']) ? $atts['min'] : $number;
    
    // Get max.
    $max = isset($atts['max']) ? $atts['max'] : $number;
    
    // Get step.
    $step = isset($atts['step']) ? $atts['step'] : 1;
    
    // If the input is valid, return it; otherwise, return the default.
    return ($min <= $number && $number <= $max && is_int($number / $step) ? $number : $setting->default);
}

/**
 * Sanitize textarea
 *
 * @param string $text The text to sanitize.
 * @return string The sanitized text.
 */
function aqualuxe_sanitize_textarea($text) {
    return wp_kses_post($text);
}

/**
 * Sanitize URL
 *
 * @param string $url The URL to sanitize.
 * @return string The sanitized URL.
 */
function aqualuxe_sanitize_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize email
 *
 * @param string $email The email to sanitize.
 * @return string The sanitized email.
 */
function aqualuxe_sanitize_email($email) {
    return sanitize_email($email);
}

/**
 * Sanitize phone
 *
 * @param string $phone The phone to sanitize.
 * @return string The sanitized phone.
 */
function aqualuxe_sanitize_phone($phone) {
    return preg_replace('/[^\d+\-() ]/', '', $phone);
}

/**
 * Sanitize HTML
 *
 * @param string $html The HTML to sanitize.
 * @return string The sanitized HTML.
 */
function aqualuxe_sanitize_html($html) {
    $allowed_html = array(
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
        'p' => array(
            'class' => array(),
        ),
        'h1' => array(
            'class' => array(),
        ),
        'h2' => array(
            'class' => array(),
        ),
        'h3' => array(
            'class' => array(),
        ),
        'h4' => array(
            'class' => array(),
        ),
        'h5' => array(
            'class' => array(),
        ),
        'h6' => array(
            'class' => array(),
        ),
        'ul' => array(
            'class' => array(),
        ),
        'ol' => array(
            'class' => array(),
        ),
        'li' => array(
            'class' => array(),
        ),
        'div' => array(
            'class' => array(),
        ),
    );
    
    return wp_kses($html, $allowed_html);
}

/**
 * Sanitize CSS
 *
 * @param string $css The CSS to sanitize.
 * @return string The sanitized CSS.
 */
function aqualuxe_sanitize_css($css) {
    return wp_strip_all_tags($css);
}

/**
 * Sanitize JavaScript
 *
 * @param string $js The JavaScript to sanitize.
 * @return string The sanitized JavaScript.
 */
function aqualuxe_sanitize_js($js) {
    return wp_strip_all_tags($js);
}

/**
 * Sanitize date
 *
 * @param string $date The date to sanitize.
 * @param string $format The date format.
 * @return string The sanitized date.
 */
function aqualuxe_sanitize_date($date, $format = 'Y-m-d') {
    $date_obj = DateTime::createFromFormat($format, $date);
    
    if ($date_obj && $date_obj->format($format) === $date) {
        return $date;
    }
    
    return '';
}

/**
 * Sanitize time
 *
 * @param string $time The time to sanitize.
 * @param string $format The time format.
 * @return string The sanitized time.
 */
function aqualuxe_sanitize_time($time, $format = 'H:i') {
    $time_obj = DateTime::createFromFormat($format, $time);
    
    if ($time_obj && $time_obj->format($format) === $time) {
        return $time;
    }
    
    return '';
}

/**
 * Sanitize datetime
 *
 * @param string $datetime The datetime to sanitize.
 * @param string $format The datetime format.
 * @return string The sanitized datetime.
 */
function aqualuxe_sanitize_datetime($datetime, $format = 'Y-m-d H:i:s') {
    $datetime_obj = DateTime::createFromFormat($format, $datetime);
    
    if ($datetime_obj && $datetime_obj->format($format) === $datetime) {
        return $datetime;
    }
    
    return '';
}

/**
 * Sanitize JSON
 *
 * @param string $json The JSON to sanitize.
 * @return string The sanitized JSON.
 */
function aqualuxe_sanitize_json($json) {
    // Decode the JSON.
    $decoded = json_decode($json, true);
    
    // Check if the JSON is valid.
    if (json_last_error() !== JSON_ERROR_NONE) {
        return '';
    }
    
    // Encode the JSON.
    return wp_json_encode($decoded);
}

/**
 * Sanitize array
 *
 * @param array $array The array to sanitize.
 * @return array The sanitized array.
 */
function aqualuxe_sanitize_array($array) {
    if (!is_array($array)) {
        return array();
    }
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = aqualuxe_sanitize_array($value);
        } else {
            $array[$key] = sanitize_text_field($value);
        }
    }
    
    return $array;
}

/**
 * Sanitize dimensions
 *
 * @param string $dimensions The dimensions to sanitize.
 * @return string The sanitized dimensions.
 */
function aqualuxe_sanitize_dimensions($dimensions) {
    $dimensions = trim($dimensions);
    
    // Check if the dimensions are valid.
    if (preg_match('/^\d+x\d+$/', $dimensions)) {
        return $dimensions;
    }
    
    return '';
}

/**
 * Sanitize range
 *
 * @param int $value The value to sanitize.
 * @param WP_Customize_Setting $setting The setting object.
 * @return int The sanitized value.
 */
function aqualuxe_sanitize_range($value, $setting) {
    // Get the input attributes associated with the setting.
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    
    // Get min.
    $min = isset($atts['min']) ? $atts['min'] : $value;
    
    // Get max.
    $max = isset($atts['max']) ? $atts['max'] : $value;
    
    // Get step.
    $step = isset($atts['step']) ? $atts['step'] : 1;
    
    // If the input is valid, return it; otherwise, return the default.
    return ($min <= $value && $value <= $max && is_int($value / $step) ? $value : $setting->default);
}

/**
 * Sanitize font
 *
 * @param string $font The font to sanitize.
 * @return string The sanitized font.
 */
function aqualuxe_sanitize_font($font) {
    $allowed_fonts = array(
        'Arial, sans-serif',
        'Helvetica, sans-serif',
        'Georgia, serif',
        'Times New Roman, serif',
        'Courier New, monospace',
        'Verdana, sans-serif',
        'Tahoma, sans-serif',
        'Trebuchet MS, sans-serif',
        'Impact, sans-serif',
        'Comic Sans MS, cursive',
        'Lucida Console, monospace',
        'Lucida Sans Unicode, sans-serif',
        'Palatino Linotype, serif',
        'Book Antiqua, serif',
        'Arial Black, sans-serif',
        'Garamond, serif',
        'MS Sans Serif, sans-serif',
        'MS Serif, serif',
        'Symbol, sans-serif',
        'Webdings, sans-serif',
        'Wingdings, sans-serif',
        'Montserrat, sans-serif',
        'Open Sans, sans-serif',
        'Roboto, sans-serif',
        'Lato, sans-serif',
        'Poppins, sans-serif',
        'Source Sans Pro, sans-serif',
        'Playfair Display, serif',
        'Merriweather, serif',
        'Roboto Slab, serif',
        'Lora, serif',
        'Cormorant Garamond, serif',
    );
    
    if (in_array($font, $allowed_fonts)) {
        return $font;
    }
    
    return 'Arial, sans-serif';
}

/**
 * Sanitize font weight
 *
 * @param int $weight The font weight to sanitize.
 * @return int The sanitized font weight.
 */
function aqualuxe_sanitize_font_weight($weight) {
    $allowed_weights = array(100, 200, 300, 400, 500, 600, 700, 800, 900);
    
    if (in_array($weight, $allowed_weights)) {
        return $weight;
    }
    
    return 400;
}

/**
 * Sanitize font style
 *
 * @param string $style The font style to sanitize.
 * @return string The sanitized font style.
 */
function aqualuxe_sanitize_font_style($style) {
    $allowed_styles = array('normal', 'italic', 'oblique');
    
    if (in_array($style, $allowed_styles)) {
        return $style;
    }
    
    return 'normal';
}

/**
 * Sanitize text transform
 *
 * @param string $transform The text transform to sanitize.
 * @return string The sanitized text transform.
 */
function aqualuxe_sanitize_text_transform($transform) {
    $allowed_transforms = array('none', 'capitalize', 'uppercase', 'lowercase');
    
    if (in_array($transform, $allowed_transforms)) {
        return $transform;
    }
    
    return 'none';
}

/**
 * Sanitize text decoration
 *
 * @param string $decoration The text decoration to sanitize.
 * @return string The sanitized text decoration.
 */
function aqualuxe_sanitize_text_decoration($decoration) {
    $allowed_decorations = array('none', 'underline', 'overline', 'line-through');
    
    if (in_array($decoration, $allowed_decorations)) {
        return $decoration;
    }
    
    return 'none';
}

/**
 * Sanitize border style
 *
 * @param string $style The border style to sanitize.
 * @return string The sanitized border style.
 */
function aqualuxe_sanitize_border_style($style) {
    $allowed_styles = array('none', 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset');
    
    if (in_array($style, $allowed_styles)) {
        return $style;
    }
    
    return 'none';
}

/**
 * Sanitize background size
 *
 * @param string $size The background size to sanitize.
 * @return string The sanitized background size.
 */
function aqualuxe_sanitize_background_size($size) {
    $allowed_sizes = array('auto', 'cover', 'contain');
    
    if (in_array($size, $allowed_sizes)) {
        return $size;
    }
    
    return 'auto';
}

/**
 * Sanitize background position
 *
 * @param string $position The background position to sanitize.
 * @return string The sanitized background position.
 */
function aqualuxe_sanitize_background_position($position) {
    $allowed_positions = array(
        'left top',
        'left center',
        'left bottom',
        'center top',
        'center center',
        'center bottom',
        'right top',
        'right center',
        'right bottom',
    );
    
    if (in_array($position, $allowed_positions)) {
        return $position;
    }
    
    return 'center center';
}

/**
 * Sanitize background repeat
 *
 * @param string $repeat The background repeat to sanitize.
 * @return string The sanitized background repeat.
 */
function aqualuxe_sanitize_background_repeat($repeat) {
    $allowed_repeats = array('no-repeat', 'repeat', 'repeat-x', 'repeat-y');
    
    if (in_array($repeat, $allowed_repeats)) {
        return $repeat;
    }
    
    return 'no-repeat';
}

/**
 * Sanitize background attachment
 *
 * @param string $attachment The background attachment to sanitize.
 * @return string The sanitized background attachment.
 */
function aqualuxe_sanitize_background_attachment($attachment) {
    $allowed_attachments = array('scroll', 'fixed', 'local');
    
    if (in_array($attachment, $allowed_attachments)) {
        return $attachment;
    }
    
    return 'scroll';
}

/**
 * Sanitize display
 *
 * @param string $display The display to sanitize.
 * @return string The sanitized display.
 */
function aqualuxe_sanitize_display($display) {
    $allowed_displays = array('block', 'inline', 'inline-block', 'flex', 'inline-flex', 'grid', 'inline-grid', 'none');
    
    if (in_array($display, $allowed_displays)) {
        return $display;
    }
    
    return 'block';
}

/**
 * Sanitize position
 *
 * @param string $position The position to sanitize.
 * @return string The sanitized position.
 */
function aqualuxe_sanitize_position($position) {
    $allowed_positions = array('static', 'relative', 'absolute', 'fixed', 'sticky');
    
    if (in_array($position, $allowed_positions)) {
        return $position;
    }
    
    return 'static';
}

/**
 * Sanitize overflow
 *
 * @param string $overflow The overflow to sanitize.
 * @return string The sanitized overflow.
 */
function aqualuxe_sanitize_overflow($overflow) {
    $allowed_overflows = array('visible', 'hidden', 'scroll', 'auto');
    
    if (in_array($overflow, $allowed_overflows)) {
        return $overflow;
    }
    
    return 'visible';
}

/**
 * Sanitize visibility
 *
 * @param string $visibility The visibility to sanitize.
 * @return string The sanitized visibility.
 */
function aqualuxe_sanitize_visibility($visibility) {
    $allowed_visibilities = array('visible', 'hidden', 'collapse');
    
    if (in_array($visibility, $allowed_visibilities)) {
        return $visibility;
    }
    
    return 'visible';
}

/**
 * Sanitize cursor
 *
 * @param string $cursor The cursor to sanitize.
 * @return string The sanitized cursor.
 */
function aqualuxe_sanitize_cursor($cursor) {
    $allowed_cursors = array('auto', 'default', 'pointer', 'wait', 'text', 'move', 'not-allowed', 'help', 'zoom-in', 'zoom-out', 'grab', 'grabbing');
    
    if (in_array($cursor, $allowed_cursors)) {
        return $cursor;
    }
    
    return 'auto';
}

/**
 * Sanitize text align
 *
 * @param string $align The text align to sanitize.
 * @return string The sanitized text align.
 */
function aqualuxe_sanitize_text_align($align) {
    $allowed_aligns = array('left', 'center', 'right', 'justify');
    
    if (in_array($align, $allowed_aligns)) {
        return $align;
    }
    
    return 'left';
}

/**
 * Sanitize vertical align
 *
 * @param string $align The vertical align to sanitize.
 * @return string The sanitized vertical align.
 */
function aqualuxe_sanitize_vertical_align($align) {
    $allowed_aligns = array('baseline', 'top', 'middle', 'bottom', 'text-top', 'text-bottom');
    
    if (in_array($align, $allowed_aligns)) {
        return $align;
    }
    
    return 'baseline';
}

/**
 * Sanitize float
 *
 * @param string $float The float to sanitize.
 * @return string The sanitized float.
 */
function aqualuxe_sanitize_float_css($float) {
    $allowed_floats = array('none', 'left', 'right');
    
    if (in_array($float, $allowed_floats)) {
        return $float;
    }
    
    return 'none';
}

/**
 * Sanitize clear
 *
 * @param string $clear The clear to sanitize.
 * @return string The sanitized clear.
 */
function aqualuxe_sanitize_clear($clear) {
    $allowed_clears = array('none', 'left', 'right', 'both');
    
    if (in_array($clear, $allowed_clears)) {
        return $clear;
    }
    
    return 'none';
}

/**
 * Sanitize white space
 *
 * @param string $white_space The white space to sanitize.
 * @return string The sanitized white space.
 */
function aqualuxe_sanitize_white_space($white_space) {
    $allowed_white_spaces = array('normal', 'nowrap', 'pre', 'pre-line', 'pre-wrap');
    
    if (in_array($white_space, $allowed_white_spaces)) {
        return $white_space;
    }
    
    return 'normal';
}

/**
 * Sanitize word break
 *
 * @param string $word_break The word break to sanitize.
 * @return string The sanitized word break.
 */
function aqualuxe_sanitize_word_break($word_break) {
    $allowed_word_breaks = array('normal', 'break-all', 'keep-all', 'break-word');
    
    if (in_array($word_break, $allowed_word_breaks)) {
        return $word_break;
    }
    
    return 'normal';
}

/**
 * Sanitize word wrap
 *
 * @param string $word_wrap The word wrap to sanitize.
 * @return string The sanitized word wrap.
 */
function aqualuxe_sanitize_word_wrap($word_wrap) {
    $allowed_word_wraps = array('normal', 'break-word');
    
    if (in_array($word_wrap, $allowed_word_wraps)) {
        return $word_wrap;
    }
    
    return 'normal';
}

/**
 * Sanitize letter spacing
 *
 * @param string $letter_spacing The letter spacing to sanitize.
 * @return string The sanitized letter spacing.
 */
function aqualuxe_sanitize_letter_spacing($letter_spacing) {
    if ($letter_spacing === 'normal') {
        return 'normal';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $letter_spacing)) {
        return $letter_spacing;
    }
    
    return 'normal';
}

/**
 * Sanitize line height
 *
 * @param string $line_height The line height to sanitize.
 * @return string The sanitized line height.
 */
function aqualuxe_sanitize_line_height($line_height) {
    if ($line_height === 'normal') {
        return 'normal';
    }
    
    if (is_numeric($line_height) || preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $line_height)) {
        return $line_height;
    }
    
    return 'normal';
}

/**
 * Sanitize font size
 *
 * @param string $font_size The font size to sanitize.
 * @return string The sanitized font size.
 */
function aqualuxe_sanitize_font_size($font_size) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $font_size)) {
        return $font_size;
    }
    
    return '16px';
}

/**
 * Sanitize border width
 *
 * @param string $border_width The border width to sanitize.
 * @return string The sanitized border width.
 */
function aqualuxe_sanitize_border_width($border_width) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $border_width)) {
        return $border_width;
    }
    
    return '0';
}

/**
 * Sanitize border radius
 *
 * @param string $border_radius The border radius to sanitize.
 * @return string The sanitized border radius.
 */
function aqualuxe_sanitize_border_radius($border_radius) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $border_radius)) {
        return $border_radius;
    }
    
    return '0';
}

/**
 * Sanitize margin
 *
 * @param string $margin The margin to sanitize.
 * @return string The sanitized margin.
 */
function aqualuxe_sanitize_margin($margin) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $margin)) {
        return $margin;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $margin)) {
        return $margin;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $margin)) {
        return $margin;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $margin)) {
        return $margin;
    }
    
    return '0';
}

/**
 * Sanitize padding
 *
 * @param string $padding The padding to sanitize.
 * @return string The sanitized padding.
 */
function aqualuxe_sanitize_padding($padding) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $padding)) {
        return $padding;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $padding)) {
        return $padding;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $padding)) {
        return $padding;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $padding)) {
        return $padding;
    }
    
    return '0';
}

/**
 * Sanitize width
 *
 * @param string $width The width to sanitize.
 * @return string The sanitized width.
 */
function aqualuxe_sanitize_width($width) {
    if ($width === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%|vw)$/', $width)) {
        return $width;
    }
    
    return 'auto';
}

/**
 * Sanitize height
 *
 * @param string $height The height to sanitize.
 * @return string The sanitized height.
 */
function aqualuxe_sanitize_height($height) {
    if ($height === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%|vh)$/', $height)) {
        return $height;
    }
    
    return 'auto';
}

/**
 * Sanitize opacity
 *
 * @param float $opacity The opacity to sanitize.
 * @return float The sanitized opacity.
 */
function aqualuxe_sanitize_opacity($opacity) {
    if (is_numeric($opacity) && $opacity >= 0 && $opacity <= 1) {
        return $opacity;
    }
    
    return 1;
}

/**
 * Sanitize z-index
 *
 * @param int $z_index The z-index to sanitize.
 * @return int The sanitized z-index.
 */
function aqualuxe_sanitize_z_index($z_index) {
    if (is_numeric($z_index)) {
        return intval($z_index);
    }
    
    return 0;
}

/**
 * Sanitize transition
 *
 * @param string $transition The transition to sanitize.
 * @return string The sanitized transition.
 */
function aqualuxe_sanitize_transition($transition) {
    if (preg_match('/^[a-zA-Z-]+ \d+(\.\d+)?s( (linear|ease|ease-in|ease-out|ease-in-out))?$/', $transition)) {
        return $transition;
    }
    
    return 'all 0.3s ease';
}

/**
 * Sanitize transform
 *
 * @param string $transform The transform to sanitize.
 * @return string The sanitized transform.
 */
function aqualuxe_sanitize_transform($transform) {
    $allowed_transforms = array(
        'none',
        'translate',
        'translateX',
        'translateY',
        'scale',
        'scaleX',
        'scaleY',
        'rotate',
        'skew',
        'skewX',
        'skewY',
        'matrix',
    );
    
    foreach ($allowed_transforms as $allowed_transform) {
        if (strpos($transform, $allowed_transform) === 0) {
            return $transform;
        }
    }
    
    return 'none';
}

/**
 * Sanitize animation
 *
 * @param string $animation The animation to sanitize.
 * @return string The sanitized animation.
 */
function aqualuxe_sanitize_animation($animation) {
    $allowed_animations = array(
        'none',
        'fade',
        'fade-up',
        'fade-down',
        'fade-left',
        'fade-right',
        'fade-up-right',
        'fade-up-left',
        'fade-down-right',
        'fade-down-left',
        'flip-up',
        'flip-down',
        'flip-left',
        'flip-right',
        'slide-up',
        'slide-down',
        'slide-left',
        'slide-right',
        'zoom-in',
        'zoom-in-up',
        'zoom-in-down',
        'zoom-in-left',
        'zoom-in-right',
        'zoom-out',
        'zoom-out-up',
        'zoom-out-down',
        'zoom-out-left',
        'zoom-out-right',
    );
    
    if (in_array($animation, $allowed_animations)) {
        return $animation;
    }
    
    return 'none';
}

/**
 * Sanitize box shadow
 *
 * @param string $box_shadow The box shadow to sanitize.
 * @return string The sanitized box shadow.
 */
function aqualuxe_sanitize_box_shadow($box_shadow) {
    if ($box_shadow === 'none') {
        return 'none';
    }
    
    if (preg_match('/^(-?\d+(\.\d+)?px ){2,4}(rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})( inset)?$/', $box_shadow)) {
        return $box_shadow;
    }
    
    return 'none';
}

/**
 * Sanitize text shadow
 *
 * @param string $text_shadow The text shadow to sanitize.
 * @return string The sanitized text shadow.
 */
function aqualuxe_sanitize_text_shadow($text_shadow) {
    if ($text_shadow === 'none') {
        return 'none';
    }
    
    if (preg_match('/^(-?\d+(\.\d+)?px ){2,3}(rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})$/', $text_shadow)) {
        return $text_shadow;
    }
    
    return 'none';
}

/**
 * Sanitize filter
 *
 * @param string $filter The filter to sanitize.
 * @return string The sanitized filter.
 */
function aqualuxe_sanitize_filter($filter) {
    $allowed_filters = array(
        'blur',
        'brightness',
        'contrast',
        'grayscale',
        'hue-rotate',
        'invert',
        'opacity',
        'saturate',
        'sepia',
    );
    
    foreach ($allowed_filters as $allowed_filter) {
        if (strpos($filter, $allowed_filter) === 0) {
            return $filter;
        }
    }
    
    return 'none';
}

/**
 * Sanitize backdrop filter
 *
 * @param string $backdrop_filter The backdrop filter to sanitize.
 * @return string The sanitized backdrop filter.
 */
function aqualuxe_sanitize_backdrop_filter($backdrop_filter) {
    $allowed_filters = array(
        'blur',
        'brightness',
        'contrast',
        'grayscale',
        'hue-rotate',
        'invert',
        'opacity',
        'saturate',
        'sepia',
    );
    
    foreach ($allowed_filters as $allowed_filter) {
        if (strpos($backdrop_filter, $allowed_filter) === 0) {
            return $backdrop_filter;
        }
    }
    
    return 'none';
}

/**
 * Sanitize clip path
 *
 * @param string $clip_path The clip path to sanitize.
 * @return string The sanitized clip path.
 */
function aqualuxe_sanitize_clip_path($clip_path) {
    $allowed_clip_paths = array(
        'none',
        'inset',
        'circle',
        'ellipse',
        'polygon',
    );
    
    foreach ($allowed_clip_paths as $allowed_clip_path) {
        if (strpos($clip_path, $allowed_clip_path) === 0) {
            return $clip_path;
        }
    }
    
    return 'none';
}

/**
 * Sanitize mask image
 *
 * @param string $mask_image The mask image to sanitize.
 * @return string The sanitized mask image.
 */
function aqualuxe_sanitize_mask_image($mask_image) {
    $allowed_mask_images = array(
        'none',
        'url',
        'linear-gradient',
        'radial-gradient',
    );
    
    foreach ($allowed_mask_images as $allowed_mask_image) {
        if (strpos($mask_image, $allowed_mask_image) === 0) {
            return $mask_image;
        }
    }
    
    return 'none';
}

/**
 * Sanitize pointer events
 *
 * @param string $pointer_events The pointer events to sanitize.
 * @return string The sanitized pointer events.
 */
function aqualuxe_sanitize_pointer_events($pointer_events) {
    $allowed_pointer_events = array('auto', 'none');
    
    if (in_array($pointer_events, $allowed_pointer_events)) {
        return $pointer_events;
    }
    
    return 'auto';
}

/**
 * Sanitize user select
 *
 * @param string $user_select The user select to sanitize.
 * @return string The sanitized user select.
 */
function aqualuxe_sanitize_user_select($user_select) {
    $allowed_user_selects = array('auto', 'none', 'text', 'all');
    
    if (in_array($user_select, $allowed_user_selects)) {
        return $user_select;
    }
    
    return 'auto';
}

/**
 * Sanitize resize
 *
 * @param string $resize The resize to sanitize.
 * @return string The sanitized resize.
 */
function aqualuxe_sanitize_resize($resize) {
    $allowed_resizes = array('none', 'both', 'horizontal', 'vertical');
    
    if (in_array($resize, $allowed_resizes)) {
        return $resize;
    }
    
    return 'none';
}

/**
 * Sanitize object fit
 *
 * @param string $object_fit The object fit to sanitize.
 * @return string The sanitized object fit.
 */
function aqualuxe_sanitize_object_fit($object_fit) {
    $allowed_object_fits = array('fill', 'contain', 'cover', 'none', 'scale-down');
    
    if (in_array($object_fit, $allowed_object_fits)) {
        return $object_fit;
    }
    
    return 'fill';
}

/**
 * Sanitize object position
 *
 * @param string $object_position The object position to sanitize.
 * @return string The sanitized object position.
 */
function aqualuxe_sanitize_object_position($object_position) {
    $allowed_object_positions = array(
        'left top',
        'left center',
        'left bottom',
        'center top',
        'center center',
        'center bottom',
        'right top',
        'right center',
        'right bottom',
    );
    
    if (in_array($object_position, $allowed_object_positions)) {
        return $object_position;
    }
    
    return 'center center';
}

/**
 * Sanitize aspect ratio
 *
 * @param string $aspect_ratio The aspect ratio to sanitize.
 * @return string The sanitized aspect ratio.
 */
function aqualuxe_sanitize_aspect_ratio($aspect_ratio) {
    if ($aspect_ratio === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^\d+\/\d+$/', $aspect_ratio)) {
        return $aspect_ratio;
    }
    
    return 'auto';
}

/**
 * Sanitize flex direction
 *
 * @param string $flex_direction The flex direction to sanitize.
 * @return string The sanitized flex direction.
 */
function aqualuxe_sanitize_flex_direction($flex_direction) {
    $allowed_flex_directions = array('row', 'row-reverse', 'column', 'column-reverse');
    
    if (in_array($flex_direction, $allowed_flex_directions)) {
        return $flex_direction;
    }
    
    return 'row';
}

/**
 * Sanitize flex wrap
 *
 * @param string $flex_wrap The flex wrap to sanitize.
 * @return string The sanitized flex wrap.
 */
function aqualuxe_sanitize_flex_wrap($flex_wrap) {
    $allowed_flex_wraps = array('nowrap', 'wrap', 'wrap-reverse');
    
    if (in_array($flex_wrap, $allowed_flex_wraps)) {
        return $flex_wrap;
    }
    
    return 'nowrap';
}

/**
 * Sanitize justify content
 *
 * @param string $justify_content The justify content to sanitize.
 * @return string The sanitized justify content.
 */
function aqualuxe_sanitize_justify_content($justify_content) {
    $allowed_justify_contents = array('flex-start', 'flex-end', 'center', 'space-between', 'space-around', 'space-evenly');
    
    if (in_array($justify_content, $allowed_justify_contents)) {
        return $justify_content;
    }
    
    return 'flex-start';
}

/**
 * Sanitize align items
 *
 * @param string $align_items The align items to sanitize.
 * @return string The sanitized align items.
 */
function aqualuxe_sanitize_align_items($align_items) {
    $allowed_align_items = array('flex-start', 'flex-end', 'center', 'baseline', 'stretch');
    
    if (in_array($align_items, $allowed_align_items)) {
        return $align_items;
    }
    
    return 'flex-start';
}

/**
 * Sanitize align content
 *
 * @param string $align_content The align content to sanitize.
 * @return string The sanitized align content.
 */
function aqualuxe_sanitize_align_content($align_content) {
    $allowed_align_contents = array('flex-start', 'flex-end', 'center', 'space-between', 'space-around', 'stretch');
    
    if (in_array($align_content, $allowed_align_contents)) {
        return $align_content;
    }
    
    return 'flex-start';
}

/**
 * Sanitize align self
 *
 * @param string $align_self The align self to sanitize.
 * @return string The sanitized align self.
 */
function aqualuxe_sanitize_align_self($align_self) {
    $allowed_align_selfs = array('auto', 'flex-start', 'flex-end', 'center', 'baseline', 'stretch');
    
    if (in_array($align_self, $allowed_align_selfs)) {
        return $align_self;
    }
    
    return 'auto';
}

/**
 * Sanitize flex grow
 *
 * @param int $flex_grow The flex grow to sanitize.
 * @return int The sanitized flex grow.
 */
function aqualuxe_sanitize_flex_grow($flex_grow) {
    if (is_numeric($flex_grow) && $flex_grow >= 0) {
        return intval($flex_grow);
    }
    
    return 0;
}

/**
 * Sanitize flex shrink
 *
 * @param int $flex_shrink The flex shrink to sanitize.
 * @return int The sanitized flex shrink.
 */
function aqualuxe_sanitize_flex_shrink($flex_shrink) {
    if (is_numeric($flex_shrink) && $flex_shrink >= 0) {
        return intval($flex_shrink);
    }
    
    return 1;
}

/**
 * Sanitize flex basis
 *
 * @param string $flex_basis The flex basis to sanitize.
 * @return string The sanitized flex basis.
 */
function aqualuxe_sanitize_flex_basis($flex_basis) {
    if ($flex_basis === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%|vw)$/', $flex_basis)) {
        return $flex_basis;
    }
    
    return 'auto';
}

/**
 * Sanitize order
 *
 * @param int $order The order to sanitize.
 * @return int The sanitized order.
 */
function aqualuxe_sanitize_order($order) {
    if (is_numeric($order)) {
        return intval($order);
    }
    
    return 0;
}

/**
 * Sanitize grid template columns
 *
 * @param string $grid_template_columns The grid template columns to sanitize.
 * @return string The sanitized grid template columns.
 */
function aqualuxe_sanitize_grid_template_columns($grid_template_columns) {
    if ($grid_template_columns === 'none') {
        return 'none';
    }
    
    if (preg_match('/^(repeat\(\d+, \d+fr\)|repeat\(auto-fill, minmax\(\d+px, 1fr\)\)|repeat\(auto-fit, minmax\(\d+px, 1fr\)\))$/', $grid_template_columns)) {
        return $grid_template_columns;
    }
    
    if (preg_match('/^(\d+fr )+\d+fr$/', $grid_template_columns)) {
        return $grid_template_columns;
    }
    
    if (preg_match('/^(\d+px )+\d+px$/', $grid_template_columns)) {
        return $grid_template_columns;
    }
    
    return 'none';
}

/**
 * Sanitize grid template rows
 *
 * @param string $grid_template_rows The grid template rows to sanitize.
 * @return string The sanitized grid template rows.
 */
function aqualuxe_sanitize_grid_template_rows($grid_template_rows) {
    if ($grid_template_rows === 'none') {
        return 'none';
    }
    
    if (preg_match('/^(repeat\(\d+, \d+fr\)|repeat\(auto-fill, minmax\(\d+px, 1fr\)\)|repeat\(auto-fit, minmax\(\d+px, 1fr\)\))$/', $grid_template_rows)) {
        return $grid_template_rows;
    }
    
    if (preg_match('/^(\d+fr )+\d+fr$/', $grid_template_rows)) {
        return $grid_template_rows;
    }
    
    if (preg_match('/^(\d+px )+\d+px$/', $grid_template_rows)) {
        return $grid_template_rows;
    }
    
    return 'none';
}

/**
 * Sanitize grid template areas
 *
 * @param string $grid_template_areas The grid template areas to sanitize.
 * @return string The sanitized grid template areas.
 */
function aqualuxe_sanitize_grid_template_areas($grid_template_areas) {
    if ($grid_template_areas === 'none') {
        return 'none';
    }
    
    if (preg_match('/^"([a-zA-Z0-9_\-\. ]+)"( "([a-zA-Z0-9_\-\. ]+)")*$/', $grid_template_areas)) {
        return $grid_template_areas;
    }
    
    return 'none';
}

/**
 * Sanitize grid column
 *
 * @param string $grid_column The grid column to sanitize.
 * @return string The sanitized grid column.
 */
function aqualuxe_sanitize_grid_column($grid_column) {
    if ($grid_column === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^\d+ \/ \d+$/', $grid_column)) {
        return $grid_column;
    }
    
    if (preg_match('/^\d+ \/ span \d+$/', $grid_column)) {
        return $grid_column;
    }
    
    if (preg_match('/^span \d+ \/ \d+$/', $grid_column)) {
        return $grid_column;
    }
    
    if (preg_match('/^span \d+ \/ span \d+$/', $grid_column)) {
        return $grid_column;
    }
    
    return 'auto';
}

/**
 * Sanitize grid row
 *
 * @param string $grid_row The grid row to sanitize.
 * @return string The sanitized grid row.
 */
function aqualuxe_sanitize_grid_row($grid_row) {
    if ($grid_row === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^\d+ \/ \d+$/', $grid_row)) {
        return $grid_row;
    }
    
    if (preg_match('/^\d+ \/ span \d+$/', $grid_row)) {
        return $grid_row;
    }
    
    if (preg_match('/^span \d+ \/ \d+$/', $grid_row)) {
        return $grid_row;
    }
    
    if (preg_match('/^span \d+ \/ span \d+$/', $grid_row)) {
        return $grid_row;
    }
    
    return 'auto';
}

/**
 * Sanitize grid area
 *
 * @param string $grid_area The grid area to sanitize.
 * @return string The sanitized grid area.
 */
function aqualuxe_sanitize_grid_area($grid_area) {
    if ($grid_area === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^[a-zA-Z0-9_\-\.]+$/', $grid_area)) {
        return $grid_area;
    }
    
    return 'auto';
}

/**
 * Sanitize grid gap
 *
 * @param string $grid_gap The grid gap to sanitize.
 * @return string The sanitized grid gap.
 */
function aqualuxe_sanitize_grid_gap($grid_gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $grid_gap)) {
        return $grid_gap;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $grid_gap)) {
        return $grid_gap;
    }
    
    return '0';
}

/**
 * Sanitize grid auto flow
 *
 * @param string $grid_auto_flow The grid auto flow to sanitize.
 * @return string The sanitized grid auto flow.
 */
function aqualuxe_sanitize_grid_auto_flow($grid_auto_flow) {
    $allowed_grid_auto_flows = array('row', 'column', 'row dense', 'column dense');
    
    if (in_array($grid_auto_flow, $allowed_grid_auto_flows)) {
        return $grid_auto_flow;
    }
    
    return 'row';
}

/**
 * Sanitize grid auto columns
 *
 * @param string $grid_auto_columns The grid auto columns to sanitize.
 * @return string The sanitized grid auto columns.
 */
function aqualuxe_sanitize_grid_auto_columns($grid_auto_columns) {
    if ($grid_auto_columns === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%|fr)$/', $grid_auto_columns)) {
        return $grid_auto_columns;
    }
    
    return 'auto';
}

/**
 * Sanitize grid auto rows
 *
 * @param string $grid_auto_rows The grid auto rows to sanitize.
 * @return string The sanitized grid auto rows.
 */
function aqualuxe_sanitize_grid_auto_rows($grid_auto_rows) {
    if ($grid_auto_rows === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%|fr)$/', $grid_auto_rows)) {
        return $grid_auto_rows;
    }
    
    return 'auto';
}

/**
 * Sanitize place content
 *
 * @param string $place_content The place content to sanitize.
 * @return string The sanitized place content.
 */
function aqualuxe_sanitize_place_content($place_content) {
    $allowed_place_contents = array(
        'center',
        'start',
        'end',
        'flex-start',
        'flex-end',
        'space-between',
        'space-around',
        'space-evenly',
        'stretch',
        'center center',
        'center start',
        'center end',
        'center flex-start',
        'center flex-end',
        'center space-between',
        'center space-around',
        'center space-evenly',
        'center stretch',
        'start center',
        'start start',
        'start end',
        'start flex-start',
        'start flex-end',
        'start space-between',
        'start space-around',
        'start space-evenly',
        'start stretch',
        'end center',
        'end start',
        'end end',
        'end flex-start',
        'end flex-end',
        'end space-between',
        'end space-around',
        'end space-evenly',
        'end stretch',
        'flex-start center',
        'flex-start start',
        'flex-start end',
        'flex-start flex-start',
        'flex-start flex-end',
        'flex-start space-between',
        'flex-start space-around',
        'flex-start space-evenly',
        'flex-start stretch',
        'flex-end center',
        'flex-end start',
        'flex-end end',
        'flex-end flex-start',
        'flex-end flex-end',
        'flex-end space-between',
        'flex-end space-around',
        'flex-end space-evenly',
        'flex-end stretch',
        'space-between center',
        'space-between start',
        'space-between end',
        'space-between flex-start',
        'space-between flex-end',
        'space-between space-between',
        'space-between space-around',
        'space-between space-evenly',
        'space-between stretch',
        'space-around center',
        'space-around start',
        'space-around end',
        'space-around flex-start',
        'space-around flex-end',
        'space-around space-between',
        'space-around space-around',
        'space-around space-evenly',
        'space-around stretch',
        'space-evenly center',
        'space-evenly start',
        'space-evenly end',
        'space-evenly flex-start',
        'space-evenly flex-end',
        'space-evenly space-between',
        'space-evenly space-around',
        'space-evenly space-evenly',
        'space-evenly stretch',
        'stretch center',
        'stretch start',
        'stretch end',
        'stretch flex-start',
        'stretch flex-end',
        'stretch space-between',
        'stretch space-around',
        'stretch space-evenly',
        'stretch stretch',
    );
    
    if (in_array($place_content, $allowed_place_contents)) {
        return $place_content;
    }
    
    return 'center';
}

/**
 * Sanitize place items
 *
 * @param string $place_items The place items to sanitize.
 * @return string The sanitized place items.
 */
function aqualuxe_sanitize_place_items($place_items) {
    $allowed_place_items = array(
        'center',
        'start',
        'end',
        'flex-start',
        'flex-end',
        'self-start',
        'self-end',
        'stretch',
        'center center',
        'center start',
        'center end',
        'center flex-start',
        'center flex-end',
        'center self-start',
        'center self-end',
        'center stretch',
        'start center',
        'start start',
        'start end',
        'start flex-start',
        'start flex-end',
        'start self-start',
        'start self-end',
        'start stretch',
        'end center',
        'end start',
        'end end',
        'end flex-start',
        'end flex-end',
        'end self-start',
        'end self-end',
        'end stretch',
        'flex-start center',
        'flex-start start',
        'flex-start end',
        'flex-start flex-start',
        'flex-start flex-end',
        'flex-start self-start',
        'flex-start self-end',
        'flex-start stretch',
        'flex-end center',
        'flex-end start',
        'flex-end end',
        'flex-end flex-start',
        'flex-end flex-end',
        'flex-end self-start',
        'flex-end self-end',
        'flex-end stretch',
        'self-start center',
        'self-start start',
        'self-start end',
        'self-start flex-start',
        'self-start flex-end',
        'self-start self-start',
        'self-start self-end',
        'self-start stretch',
        'self-end center',
        'self-end start',
        'self-end end',
        'self-end flex-start',
        'self-end flex-end',
        'self-end self-start',
        'self-end self-end',
        'self-end stretch',
        'stretch center',
        'stretch start',
        'stretch end',
        'stretch flex-start',
        'stretch flex-end',
        'stretch self-start',
        'stretch self-end',
        'stretch stretch',
    );
    
    if (in_array($place_items, $allowed_place_items)) {
        return $place_items;
    }
    
    return 'center';
}

/**
 * Sanitize place self
 *
 * @param string $place_self The place self to sanitize.
 * @return string The sanitized place self.
 */
function aqualuxe_sanitize_place_self($place_self) {
    $allowed_place_selfs = array(
        'auto',
        'center',
        'start',
        'end',
        'flex-start',
        'flex-end',
        'self-start',
        'self-end',
        'stretch',
        'auto auto',
        'auto center',
        'auto start',
        'auto end',
        'auto flex-start',
        'auto flex-end',
        'auto self-start',
        'auto self-end',
        'auto stretch',
        'center auto',
        'center center',
        'center start',
        'center end',
        'center flex-start',
        'center flex-end',
        'center self-start',
        'center self-end',
        'center stretch',
        'start auto',
        'start center',
        'start start',
        'start end',
        'start flex-start',
        'start flex-end',
        'start self-start',
        'start self-end',
        'start stretch',
        'end auto',
        'end center',
        'end start',
        'end end',
        'end flex-start',
        'end flex-end',
        'end self-start',
        'end self-end',
        'end stretch',
        'flex-start auto',
        'flex-start center',
        'flex-start start',
        'flex-start end',
        'flex-start flex-start',
        'flex-start flex-end',
        'flex-start self-start',
        'flex-start self-end',
        'flex-start stretch',
        'flex-end auto',
        'flex-end center',
        'flex-end start',
        'flex-end end',
        'flex-end flex-start',
        'flex-end flex-end',
        'flex-end self-start',
        'flex-end self-end',
        'flex-end stretch',
        'self-start auto',
        'self-start center',
        'self-start start',
        'self-start end',
        'self-start flex-start',
        'self-start flex-end',
        'self-start self-start',
        'self-start self-end',
        'self-start stretch',
        'self-end auto',
        'self-end center',
        'self-end start',
        'self-end end',
        'self-end flex-start',
        'self-end flex-end',
        'self-end self-start',
        'self-end self-end',
        'self-end stretch',
        'stretch auto',
        'stretch center',
        'stretch start',
        'stretch end',
        'stretch flex-start',
        'stretch flex-end',
        'stretch self-start',
        'stretch self-end',
        'stretch stretch',
    );
    
    if (in_array($place_self, $allowed_place_selfs)) {
        return $place_self;
    }
    
    return 'auto';
}

/**
 * Sanitize gap
 *
 * @param string $gap The gap to sanitize.
 * @return string The sanitized gap.
 */
function aqualuxe_sanitize_gap($gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $gap)) {
        return $gap;
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%) -?\d+(\.\d+)?(px|em|rem|%)$/', $gap)) {
        return $gap;
    }
    
    return '0';
}

/**
 * Sanitize row gap
 *
 * @param string $row_gap The row gap to sanitize.
 * @return string The sanitized row gap.
 */
function aqualuxe_sanitize_row_gap($row_gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $row_gap)) {
        return $row_gap;
    }
    
    return '0';
}

/**
 * Sanitize column gap
 *
 * @param string $column_gap The column gap to sanitize.
 * @return string The sanitized column gap.
 */
function aqualuxe_sanitize_column_gap($column_gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $column_gap)) {
        return $column_gap;
    }
    
    return '0';
}

/**
 * Sanitize columns
 *
 * @param string $columns The columns to sanitize.
 * @return string The sanitized columns.
 */
function aqualuxe_sanitize_columns($columns) {
    if ($columns === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^\d+$/', $columns)) {
        return $columns;
    }
    
    if (preg_match('/^(\d+px )+\d+px$/', $columns)) {
        return $columns;
    }
    
    if (preg_match('/^(\d+fr )+\d+fr$/', $columns)) {
        return $columns;
    }
    
    return 'auto';
}

/**
 * Sanitize column count
 *
 * @param int $column_count The column count to sanitize.
 * @return int The sanitized column count.
 */
function aqualuxe_sanitize_column_count($column_count) {
    if (is_numeric($column_count) && $column_count >= 1) {
        return intval($column_count);
    }
    
    return 1;
}

/**
 * Sanitize column width
 *
 * @param string $column_width The column width to sanitize.
 * @return string The sanitized column width.
 */
function aqualuxe_sanitize_column_width($column_width) {
    if ($column_width === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $column_width)) {
        return $column_width;
    }
    
    return 'auto';
}

/**
 * Sanitize column rule
 *
 * @param string $column_rule The column rule to sanitize.
 * @return string The sanitized column rule.
 */
function aqualuxe_sanitize_column_rule($column_rule) {
    if ($column_rule === 'none') {
        return 'none';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem) (none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset) (rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})$/', $column_rule)) {
        return $column_rule;
    }
    
    return 'none';
}

/**
 * Sanitize column span
 *
 * @param string $column_span The column span to sanitize.
 * @return string The sanitized column span.
 */
function aqualuxe_sanitize_column_span($column_span) {
    $allowed_column_spans = array('none', 'all');
    
    if (in_array($column_span, $allowed_column_spans)) {
        return $column_span;
    }
    
    return 'none';
}

/**
 * Sanitize column fill
 *
 * @param string $column_fill The column fill to sanitize.
 * @return string The sanitized column fill.
 */
function aqualuxe_sanitize_column_fill($column_fill) {
    $allowed_column_fills = array('auto', 'balance', 'balance-all');
    
    if (in_array($column_fill, $allowed_column_fills)) {
        return $column_fill;
    }
    
    return 'auto';
}

/**
 * Sanitize column gap
 *
 * @param string $column_gap The column gap to sanitize.
 * @return string The sanitized column gap.
 */
function aqualuxe_sanitize_column_gap($column_gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $column_gap)) {
        return $column_gap;
    }
    
    return '0';
}

/**
 * Sanitize column rule width
 *
 * @param string $column_rule_width The column rule width to sanitize.
 * @return string The sanitized column rule width.
 */
function aqualuxe_sanitize_column_rule_width($column_rule_width) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem)$/', $column_rule_width)) {
        return $column_rule_width;
    }
    
    return '1px';
}

/**
 * Sanitize column rule style
 *
 * @param string $column_rule_style The column rule style to sanitize.
 * @return string The sanitized column rule style.
 */
function aqualuxe_sanitize_column_rule_style($column_rule_style) {
    $allowed_column_rule_styles = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset');
    
    if (in_array($column_rule_style, $allowed_column_rule_styles)) {
        return $column_rule_style;
    }
    
    return 'none';
}

/**
 * Sanitize column rule color
 *
 * @param string $column_rule_color The column rule color to sanitize.
 * @return string The sanitized column rule color.
 */
function aqualuxe_sanitize_column_rule_color($column_rule_color) {
    if (preg_match('/^(rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})$/', $column_rule_color)) {
        return $column_rule_color;
    }
    
    return '#000000';
}

/**
 * Sanitize column width
 *
 * @param string $column_width The column width to sanitize.
 * @return string The sanitized column width.
 */
function aqualuxe_sanitize_column_width_css($column_width) {
    if ($column_width === 'auto') {
        return 'auto';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $column_width)) {
        return $column_width;
    }
    
    return 'auto';
}

/**
 * Sanitize column count
 *
 * @param int $column_count The column count to sanitize.
 * @return int The sanitized column count.
 */
function aqualuxe_sanitize_column_count_css($column_count) {
    if (is_numeric($column_count) && $column_count >= 1) {
        return intval($column_count);
    }
    
    return 1;
}

/**
 * Sanitize column gap
 *
 * @param string $column_gap The column gap to sanitize.
 * @return string The sanitized column gap.
 */
function aqualuxe_sanitize_column_gap_css($column_gap) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem|%)$/', $column_gap)) {
        return $column_gap;
    }
    
    return '0';
}

/**
 * Sanitize column rule
 *
 * @param string $column_rule The column rule to sanitize.
 * @return string The sanitized column rule.
 */
function aqualuxe_sanitize_column_rule_css($column_rule) {
    if ($column_rule === 'none') {
        return 'none';
    }
    
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem) (none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset) (rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})$/', $column_rule)) {
        return $column_rule;
    }
    
    return 'none';
}

/**
 * Sanitize column span
 *
 * @param string $column_span The column span to sanitize.
 * @return string The sanitized column span.
 */
function aqualuxe_sanitize_column_span_css($column_span) {
    $allowed_column_spans = array('none', 'all');
    
    if (in_array($column_span, $allowed_column_spans)) {
        return $column_span;
    }
    
    return 'none';
}

/**
 * Sanitize column fill
 *
 * @param string $column_fill The column fill to sanitize.
 * @return string The sanitized column fill.
 */
function aqualuxe_sanitize_column_fill_css($column_fill) {
    $allowed_column_fills = array('auto', 'balance', 'balance-all');
    
    if (in_array($column_fill, $allowed_column_fills)) {
        return $column_fill;
    }
    
    return 'auto';
}

/**
 * Sanitize column rule width
 *
 * @param string $column_rule_width The column rule width to sanitize.
 * @return string The sanitized column rule width.
 */
function aqualuxe_sanitize_column_rule_width_css($column_rule_width) {
    if (preg_match('/^-?\d+(\.\d+)?(px|em|rem)$/', $column_rule_width)) {
        return $column_rule_width;
    }
    
    return '1px';
}

/**
 * Sanitize column rule style
 *
 * @param string $column_rule_style The column rule style to sanitize.
 * @return string The sanitized column rule style.
 */
function aqualuxe_sanitize_column_rule_style_css($column_rule_style) {
    $allowed_column_rule_styles = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset');
    
    if (in_array($column_rule_style, $allowed_column_rule_styles)) {
        return $column_rule_style;
    }
    
    return 'none';
}

/**
 * Sanitize column rule color
 *
 * @param string $column_rule_color The column rule color to sanitize.
 * @return string The sanitized column rule color.
 */
function aqualuxe_sanitize_column_rule_color_css($column_rule_color) {
    if (preg_match('/^(rgba?\([0-9, .]+\)|#[0-9a-fA-F]{3,8})$/', $column_rule_color)) {
        return $column_rule_color;
    }
    
    return '#000000';
}