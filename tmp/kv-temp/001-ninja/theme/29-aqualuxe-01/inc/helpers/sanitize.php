<?php
/**
 * Sanitization helper functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize multi select
 *
 * @param array $input The input from the setting.
 * @return array
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array();
    
    foreach ($input as $value) {
        if (!empty($value)) {
            $valid_keys[] = sanitize_text_field($value);
        }
    }
    
    return $valid_keys;
}

/**
 * Sanitize float
 *
 * @param float $input The input from the setting.
 * @return float
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize number
 *
 * @param int $input The input from the setting.
 * @return int
 */
function aqualuxe_sanitize_number($input) {
    return absint($input);
}

/**
 * Sanitize rgba color
 *
 * @param string $color The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_rgba($color) {
    if (empty($color) || is_array($color)) {
        return 'rgba(0,0,0,0)';
    }
    
    // If string does not start with 'rgba', then treat as hex.
    // sanitize the hex color and finally convert hex to rgba
    if (false === strpos($color, 'rgba')) {
        return sanitize_hex_color($color);
    }
    
    // By now we know the string is formatted as an rgba color so we need to further sanitize it.
    $color = str_replace(' ', '', $color);
    sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
    
    return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize html
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_html($input) {
    return wp_kses_post($input);
}

/**
 * Sanitize image
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_image($input) {
    return esc_url_raw($input);
}

/**
 * Sanitize file
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_file($input) {
    return esc_url_raw($input);
}

/**
 * Sanitize email
 *
 * @param string $email The email address.
 * @return string
 */
function aqualuxe_sanitize_email($email) {
    return sanitize_email($email);
}

/**
 * Sanitize url
 *
 * @param string $url The URL.
 * @return string
 */
function aqualuxe_sanitize_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize choices
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_choices($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize font
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_font($input) {
    $valid_fonts = aqualuxe_get_font_choices();
    
    if (array_key_exists($input, $valid_fonts)) {
        return $input;
    }
    
    return 'system-ui';
}

/**
 * Sanitize font weight
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_font_weight($input) {
    $valid_weights = array('300', '400', '500', '600', '700', '800', '900');
    
    if (in_array($input, $valid_weights)) {
        return $input;
    }
    
    return '400';
}

/**
 * Sanitize text field
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_text($input) {
    return sanitize_text_field($input);
}

/**
 * Sanitize textarea
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_textarea($input) {
    return sanitize_textarea_field($input);
}

/**
 * Sanitize date
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_date($input) {
    $date = new DateTime($input);
    return $date->format('Y-m-d');
}

/**
 * Sanitize time
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_time($input) {
    $time = strtotime($input);
    return date('H:i', $time);
}

/**
 * Sanitize datetime
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_datetime($input) {
    $datetime = new DateTime($input);
    return $datetime->format('Y-m-d H:i:s');
}

/**
 * Sanitize dimensions
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_dimensions($input) {
    $dimensions = explode(' ', $input);
    $sanitized_dimensions = array();
    
    foreach ($dimensions as $dimension) {
        if (preg_match('/^(\d*\.?\d+)(px|em|rem|%|vh|vw|pt|cm|mm|in|pc|ex|ch|vmin|vmax)$/', $dimension)) {
            $sanitized_dimensions[] = $dimension;
        }
    }
    
    return implode(' ', $sanitized_dimensions);
}

/**
 * Sanitize range
 *
 * @param int    $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return int
 */
function aqualuxe_sanitize_range($input, $setting) {
    $attrs = $setting->manager->get_control($setting->id)->input_attrs;
    
    $min = (isset($attrs['min']) ? $attrs['min'] : $input);
    $max = (isset($attrs['max']) ? $attrs['max'] : $input);
    $step = (isset($attrs['step']) ? $attrs['step'] : 1);
    
    $number = floor($input / $step) * $step;
    
    return aqualuxe_in_range($number, $min, $max);
}

/**
 * Check if value is in range
 *
 * @param int $value The value to check.
 * @param int $min   The minimum value.
 * @param int $max   The maximum value.
 * @return int
 */
function aqualuxe_in_range($value, $min, $max) {
    if ($value < $min) {
        return $min;
    }
    
    if ($value > $max) {
        return $max;
    }
    
    return $value;
}

/**
 * Sanitize sortable
 *
 * @param array $input The input from the setting.
 * @return array
 */
function aqualuxe_sanitize_sortable($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array();
    
    foreach ($input as $value) {
        if (!empty($value)) {
            $valid_keys[] = sanitize_text_field($value);
        }
    }
    
    return $valid_keys;
}

/**
 * Sanitize Google Font
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_google_font($input) {
    // List of valid Google Fonts
    $google_fonts = array(
        'Open Sans',
        'Roboto',
        'Lato',
        'Montserrat',
        'Oswald',
        'Source Sans Pro',
        'Raleway',
        'PT Sans',
        'Merriweather',
        'Nunito',
        'Playfair Display',
        'Poppins',
        'Ubuntu',
        'Rubik',
        'Noto Sans',
        'Noto Serif',
        'Work Sans',
        'Fira Sans',
        'Quicksand',
        'Titillium Web',
        'Arimo',
        'Mulish',
        'Heebo',
        'Karla',
        'Inter',
        'Manrope',
        'DM Sans',
        'Barlow',
        'Josefin Sans',
        'Nunito Sans',
        'Cabin',
        'Libre Franklin',
        'Crimson Text',
        'Libre Baskerville',
        'Lora',
        'Bitter',
        'Cormorant Garamond',
        'Merriweather Sans',
        'Source Serif Pro',
        'Alegreya',
        'Alegreya Sans',
        'Archivo',
        'Archivo Narrow',
        'Asap',
        'Barlow Condensed',
        'Barlow Semi Condensed',
        'Catamaran',
        'Comfortaa',
        'Domine',
        'Exo 2',
        'Fira Sans Condensed',
        'IBM Plex Sans',
        'IBM Plex Serif',
        'Inconsolata',
        'Josefin Slab',
        'Karla',
        'Lato',
        'Libre Franklin',
        'Montserrat Alternates',
        'Nanum Gothic',
        'Noto Sans JP',
        'Noto Sans KR',
        'Noto Sans SC',
        'Noto Sans TC',
        'Noto Serif JP',
        'Noto Serif KR',
        'Noto Serif SC',
        'Noto Serif TC',
        'Nunito',
        'Open Sans Condensed',
        'Overpass',
        'Oxygen',
        'PT Sans Narrow',
        'PT Serif',
        'Playfair Display SC',
        'Prompt',
        'Roboto Condensed',
        'Roboto Mono',
        'Roboto Slab',
        'Rubik',
        'Spectral',
        'Teko',
        'Ubuntu Condensed',
        'Ubuntu Mono',
        'Varela Round',
        'Vollkorn',
        'Work Sans',
        'Yanone Kaffeesatz',
        'Zilla Slab',
    );
    
    if (in_array($input, $google_fonts)) {
        return $input;
    }
    
    return '';
}

/**
 * Sanitize social network
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_social_network($input) {
    $valid_networks = array(
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'pinterest',
        'tiktok',
        'reddit',
        'email',
        'whatsapp',
        'telegram',
    );
    
    if (in_array($input, $valid_networks)) {
        return $input;
    }
    
    return '';
}

/**
 * Sanitize payment icon
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_payment_icon($input) {
    $valid_icons = array(
        'visa',
        'mastercard',
        'amex',
        'discover',
        'paypal',
        'apple-pay',
        'google-pay',
        'stripe',
    );
    
    if (in_array($input, $valid_icons)) {
        return $input;
    }
    
    return '';
}

/**
 * Sanitize dark mode default
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_dark_mode_default($input) {
    $valid = array('light', 'dark', 'auto');
    
    if (in_array($input, $valid)) {
        return $input;
    }
    
    return 'auto';
}

/**
 * Sanitize multilingual style
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_multilingual_style($input) {
    $valid = array('dropdown', 'horizontal', 'flags');
    
    if (in_array($input, $valid)) {
        return $input;
    }
    
    return 'dropdown';
}

/**
 * Sanitize phone number
 *
 * @param string $phone The phone number.
 * @return string
 */
function aqualuxe_sanitize_phone($phone) {
    return preg_replace('/[^\d+\-() ]/', '', $phone);
}

/**
 * Sanitize CSS
 *
 * @param string $css The CSS code.
 * @return string
 */
function aqualuxe_sanitize_css($css) {
    return wp_strip_all_tags($css);
}

/**
 * Sanitize JavaScript
 *
 * @param string $js The JavaScript code.
 * @return string
 */
function aqualuxe_sanitize_js($js) {
    return wp_kses_post($js);
}

/**
 * Sanitize HTML with allowed tags
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_html_with_allowed_tags($input) {
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
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'class' => array(),
            'width' => array(),
            'height' => array(),
        ),
    );
    
    return wp_kses($input, $allowed_html);
}

/**
 * Sanitize color scheme
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_color_scheme($input) {
    $valid_schemes = array(
        'default',
        'light',
        'dark',
        'blue',
        'green',
        'red',
        'purple',
        'orange',
        'pink',
        'teal',
        'custom',
    );
    
    if (in_array($input, $valid_schemes)) {
        return $input;
    }
    
    return 'default';
}

/**
 * Sanitize layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_layout($input) {
    $valid_layouts = array(
        'wide',
        'boxed',
        'framed',
        'full',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return 'wide';
}

/**
 * Sanitize sidebar position
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_sidebar_position($input) {
    $valid_positions = array(
        'right',
        'left',
        'none',
    );
    
    if (in_array($input, $valid_positions)) {
        return $input;
    }
    
    return 'right';
}

/**
 * Sanitize blog layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_blog_layout($input) {
    $valid_layouts = array(
        'grid',
        'list',
        'masonry',
        'standard',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return 'grid';
}

/**
 * Sanitize header layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_header_layout($input) {
    $valid_layouts = array(
        'default',
        'centered',
        'transparent',
        'sticky',
        'minimal',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return 'default';
}

/**
 * Sanitize footer layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_footer_layout($input) {
    $valid_layouts = array(
        '1-column',
        '2-columns',
        '3-columns',
        '4-columns',
        'custom',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return '4-columns';
}

/**
 * Sanitize shop layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_shop_layout($input) {
    $valid_layouts = array(
        'grid',
        'list',
        'masonry',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return 'grid';
}

/**
 * Sanitize single post layout
 *
 * @param string $input The input from the setting.
 * @return string
 */
function aqualuxe_sanitize_single_post_layout($input) {
    $valid_layouts = array(
        'standard',
        'wide',
        'full-width',
        'narrow',
    );
    
    if (in_array($input, $valid_layouts)) {
        return $input;
    }
    
    return 'standard';
}