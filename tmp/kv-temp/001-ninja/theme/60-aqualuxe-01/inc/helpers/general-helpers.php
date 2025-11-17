<?php
/**
 * AquaLuxe General Helper Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Sanitize hex color
 *
 * @param string $color Hex color
 * @return string Sanitized hex color
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
 * Sanitize checkbox
 *
 * @param bool $checked Checkbox value
 * @return bool Sanitized checkbox value
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input   Select value
 * @param object $setting Setting object
 * @return string Sanitized select value
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices)) ? $input : $setting->default;
}

/**
 * Sanitize radio
 *
 * @param string $input   Radio value
 * @param object $setting Setting object
 * @return string Sanitized radio value
 */
function aqualuxe_sanitize_radio($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices)) ? $input : $setting->default;
}

/**
 * Sanitize number
 *
 * @param int    $number  Number value
 * @param object $setting Setting object
 * @return int Sanitized number value
 */
function aqualuxe_sanitize_number($number, $setting) {
    $number = absint($number);
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    $min = isset($atts['min']) ? $atts['min'] : $number;
    $max = isset($atts['max']) ? $atts['max'] : $number;
    $step = isset($atts['step']) ? $atts['step'] : 1;
    
    // If the number is within the valid range, return it; otherwise, return the default
    return ($min <= $number && $number <= $max && is_int($number / $step)) ? $number : $setting->default;
}

/**
 * Sanitize URL
 *
 * @param string $url URL
 * @return string Sanitized URL
 */
function aqualuxe_sanitize_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize textarea
 *
 * @param string $text Textarea value
 * @return string Sanitized textarea value
 */
function aqualuxe_sanitize_textarea($text) {
    return wp_kses_post($text);
}

/**
 * Sanitize HTML
 *
 * @param string $html HTML
 * @return string Sanitized HTML
 */
function aqualuxe_sanitize_html($html) {
    return wp_kses_post($html);
}

/**
 * Sanitize CSS
 *
 * @param string $css CSS
 * @return string Sanitized CSS
 */
function aqualuxe_sanitize_css($css) {
    return wp_strip_all_tags($css);
}

/**
 * Sanitize JavaScript
 *
 * @param string $js JavaScript
 * @return string Sanitized JavaScript
 */
function aqualuxe_sanitize_js($js) {
    return wp_strip_all_tags($js);
}

/**
 * Sanitize dimensions
 *
 * @param string $value Dimensions value
 * @return string Sanitized dimensions value
 */
function aqualuxe_sanitize_dimensions($value) {
    $values = explode(' ', $value);
    $sanitized_values = [];
    
    foreach ($values as $val) {
        if (preg_match('/^(\d*\.?\d+)(px|em|rem|%|vh|vw|pt|cm|mm|in|pc|ex|ch|vmin|vmax)$/', $val)) {
            $sanitized_values[] = $val;
        }
    }
    
    return implode(' ', $sanitized_values);
}

/**
 * Sanitize font
 *
 * @param string $font Font
 * @return string Sanitized font
 */
function aqualuxe_sanitize_font($font) {
    return sanitize_text_field($font);
}

/**
 * Sanitize font weight
 *
 * @param string $weight Font weight
 * @return string Sanitized font weight
 */
function aqualuxe_sanitize_font_weight($weight) {
    $allowed_weights = ['100', '200', '300', '400', '500', '600', '700', '800', '900', 'normal', 'bold'];
    
    return in_array($weight, $allowed_weights) ? $weight : '400';
}

/**
 * Sanitize font style
 *
 * @param string $style Font style
 * @return string Sanitized font style
 */
function aqualuxe_sanitize_font_style($style) {
    $allowed_styles = ['normal', 'italic', 'oblique'];
    
    return in_array($style, $allowed_styles) ? $style : 'normal';
}

/**
 * Sanitize text transform
 *
 * @param string $transform Text transform
 * @return string Sanitized text transform
 */
function aqualuxe_sanitize_text_transform($transform) {
    $allowed_transforms = ['none', 'capitalize', 'uppercase', 'lowercase'];
    
    return in_array($transform, $allowed_transforms) ? $transform : 'none';
}

/**
 * Sanitize text decoration
 *
 * @param string $decoration Text decoration
 * @return string Sanitized text decoration
 */
function aqualuxe_sanitize_text_decoration($decoration) {
    $allowed_decorations = ['none', 'underline', 'overline', 'line-through'];
    
    return in_array($decoration, $allowed_decorations) ? $decoration : 'none';
}

/**
 * Sanitize color scheme
 *
 * @param string $scheme Color scheme
 * @return string Sanitized color scheme
 */
function aqualuxe_sanitize_color_scheme($scheme) {
    $allowed_schemes = ['light', 'dark', 'custom'];
    
    return in_array($scheme, $allowed_schemes) ? $scheme : 'light';
}

/**
 * Sanitize layout
 *
 * @param string $layout Layout
 * @return string Sanitized layout
 */
function aqualuxe_sanitize_layout($layout) {
    $allowed_layouts = ['full-width', 'boxed', 'contained'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'contained';
}

/**
 * Sanitize sidebar position
 *
 * @param string $position Sidebar position
 * @return string Sanitized sidebar position
 */
function aqualuxe_sanitize_sidebar_position($position) {
    $allowed_positions = ['left', 'right', 'none'];
    
    return in_array($position, $allowed_positions) ? $position : 'right';
}

/**
 * Sanitize header layout
 *
 * @param string $layout Header layout
 * @return string Sanitized header layout
 */
function aqualuxe_sanitize_header_layout($layout) {
    $allowed_layouts = ['default', 'centered', 'split', 'minimal'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'default';
}

/**
 * Sanitize footer layout
 *
 * @param string $layout Footer layout
 * @return string Sanitized footer layout
 */
function aqualuxe_sanitize_footer_layout($layout) {
    $allowed_layouts = ['default', 'centered', 'minimal', 'widgets'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'default';
}

/**
 * Sanitize blog layout
 *
 * @param string $layout Blog layout
 * @return string Sanitized blog layout
 */
function aqualuxe_sanitize_blog_layout($layout) {
    $allowed_layouts = ['standard', 'grid', 'masonry', 'list'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'standard';
}

/**
 * Sanitize shop layout
 *
 * @param string $layout Shop layout
 * @return string Sanitized shop layout
 */
function aqualuxe_sanitize_shop_layout($layout) {
    $allowed_layouts = ['grid', 'list', 'masonry'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'grid';
}

/**
 * Sanitize product layout
 *
 * @param string $layout Product layout
 * @return string Sanitized product layout
 */
function aqualuxe_sanitize_product_layout($layout) {
    $allowed_layouts = ['standard', 'gallery', 'sticky', 'full-width'];
    
    return in_array($layout, $allowed_layouts) ? $layout : 'standard';
}

/**
 * Sanitize alignment
 *
 * @param string $alignment Alignment
 * @return string Sanitized alignment
 */
function aqualuxe_sanitize_alignment($alignment) {
    $allowed_alignments = ['left', 'center', 'right'];
    
    return in_array($alignment, $allowed_alignments) ? $alignment : 'left';
}

/**
 * Sanitize vertical alignment
 *
 * @param string $alignment Vertical alignment
 * @return string Sanitized vertical alignment
 */
function aqualuxe_sanitize_vertical_alignment($alignment) {
    $allowed_alignments = ['top', 'middle', 'bottom'];
    
    return in_array($alignment, $allowed_alignments) ? $alignment : 'middle';
}

/**
 * Sanitize image size
 *
 * @param string $size Image size
 * @return string Sanitized image size
 */
function aqualuxe_sanitize_image_size($size) {
    $allowed_sizes = ['thumbnail', 'medium', 'large', 'full', 'aqualuxe-featured', 'aqualuxe-square', 'aqualuxe-portrait', 'aqualuxe-gallery'];
    
    return in_array($size, $allowed_sizes) ? $size : 'full';
}

/**
 * Sanitize animation
 *
 * @param string $animation Animation
 * @return string Sanitized animation
 */
function aqualuxe_sanitize_animation($animation) {
    $allowed_animations = ['none', 'fade', 'slide', 'zoom', 'flip', 'bounce'];
    
    return in_array($animation, $allowed_animations) ? $animation : 'none';
}

/**
 * Sanitize transition
 *
 * @param string $transition Transition
 * @return string Sanitized transition
 */
function aqualuxe_sanitize_transition($transition) {
    $allowed_transitions = ['none', 'fade', 'slide', 'zoom', 'flip', 'bounce'];
    
    return in_array($transition, $allowed_transitions) ? $transition : 'none';
}

/**
 * Sanitize effect
 *
 * @param string $effect Effect
 * @return string Sanitized effect
 */
function aqualuxe_sanitize_effect($effect) {
    $allowed_effects = ['none', 'shadow', 'glow', 'outline', 'border'];
    
    return in_array($effect, $allowed_effects) ? $effect : 'none';
}

/**
 * Sanitize hover effect
 *
 * @param string $effect Hover effect
 * @return string Sanitized hover effect
 */
function aqualuxe_sanitize_hover_effect($effect) {
    $allowed_effects = ['none', 'zoom', 'fade', 'slide', 'flip', 'bounce'];
    
    return in_array($effect, $allowed_effects) ? $effect : 'none';
}

/**
 * Sanitize button style
 *
 * @param string $style Button style
 * @return string Sanitized button style
 */
function aqualuxe_sanitize_button_style($style) {
    $allowed_styles = ['default', 'outline', 'flat', 'rounded', 'pill'];
    
    return in_array($style, $allowed_styles) ? $style : 'default';
}

/**
 * Sanitize button size
 *
 * @param string $size Button size
 * @return string Sanitized button size
 */
function aqualuxe_sanitize_button_size($size) {
    $allowed_sizes = ['small', 'medium', 'large', 'extra-large'];
    
    return in_array($size, $allowed_sizes) ? $size : 'medium';
}

/**
 * Sanitize icon
 *
 * @param string $icon Icon
 * @return string Sanitized icon
 */
function aqualuxe_sanitize_icon($icon) {
    return sanitize_text_field($icon);
}

/**
 * Sanitize social network
 *
 * @param string $network Social network
 * @return string Sanitized social network
 */
function aqualuxe_sanitize_social_network($network) {
    $allowed_networks = ['facebook', 'twitter', 'instagram', 'linkedin', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'flickr', 'dribbble', 'github', 'behance', 'reddit', 'snapchat', 'whatsapp', 'telegram', 'tiktok', 'twitch', 'discord', 'slack'];
    
    return in_array($network, $allowed_networks) ? $network : '';
}

/**
 * Sanitize date format
 *
 * @param string $format Date format
 * @return string Sanitized date format
 */
function aqualuxe_sanitize_date_format($format) {
    $allowed_formats = ['F j, Y', 'Y-m-d', 'm/d/Y', 'd/m/Y', 'j F Y', 'F j Y', 'j M Y', 'M j, Y'];
    
    return in_array($format, $allowed_formats) ? $format : 'F j, Y';
}

/**
 * Sanitize time format
 *
 * @param string $format Time format
 * @return string Sanitized time format
 */
function aqualuxe_sanitize_time_format($format) {
    $allowed_formats = ['g:i a', 'g:i A', 'H:i'];
    
    return in_array($format, $allowed_formats) ? $format : 'g:i a';
}

/**
 * Sanitize currency
 *
 * @param string $currency Currency
 * @return string Sanitized currency
 */
function aqualuxe_sanitize_currency($currency) {
    $allowed_currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD', 'CHF', 'CNY', 'INR', 'BRL', 'RUB', 'KRW', 'SGD', 'NZD', 'MXN', 'HKD', 'TRY', 'ZAR', 'SEK', 'NOK', 'DKK', 'PLN', 'THB', 'IDR', 'HUF', 'CZK', 'ILS', 'CLP', 'PHP', 'AED', 'COP', 'SAR', 'MYR', 'RON'];
    
    return in_array($currency, $allowed_currencies) ? $currency : 'USD';
}

/**
 * Sanitize currency position
 *
 * @param string $position Currency position
 * @return string Sanitized currency position
 */
function aqualuxe_sanitize_currency_position($position) {
    $allowed_positions = ['left', 'right', 'left_space', 'right_space'];
    
    return in_array($position, $allowed_positions) ? $position : 'left';
}

/**
 * Sanitize thousand separator
 *
 * @param string $separator Thousand separator
 * @return string Sanitized thousand separator
 */
function aqualuxe_sanitize_thousand_separator($separator) {
    $allowed_separators = [',', '.', ' ', '\'', ''];
    
    return in_array($separator, $allowed_separators) ? $separator : ',';
}

/**
 * Sanitize decimal separator
 *
 * @param string $separator Decimal separator
 * @return string Sanitized decimal separator
 */
function aqualuxe_sanitize_decimal_separator($separator) {
    $allowed_separators = ['.', ','];
    
    return in_array($separator, $allowed_separators) ? $separator : '.';
}

/**
 * Sanitize decimal number
 *
 * @param int $number Decimal number
 * @return int Sanitized decimal number
 */
function aqualuxe_sanitize_decimal_number($number) {
    return absint($number);
}

/**
 * Sanitize price format
 *
 * @param string $format Price format
 * @return string Sanitized price format
 */
function aqualuxe_sanitize_price_format($format) {
    $allowed_formats = ['%1$s%2$s', '%2$s%1$s', '%1$s %2$s', '%2$s %1$s'];
    
    return in_array($format, $allowed_formats) ? $format : '%1$s%2$s';
}

/**
 * Sanitize country
 *
 * @param string $country Country
 * @return string Sanitized country
 */
function aqualuxe_sanitize_country($country) {
    return sanitize_text_field($country);
}

/**
 * Sanitize state
 *
 * @param string $state State
 * @return string Sanitized state
 */
function aqualuxe_sanitize_state($state) {
    return sanitize_text_field($state);
}

/**
 * Sanitize city
 *
 * @param string $city City
 * @return string Sanitized city
 */
function aqualuxe_sanitize_city($city) {
    return sanitize_text_field($city);
}

/**
 * Sanitize zip code
 *
 * @param string $zip Zip code
 * @return string Sanitized zip code
 */
function aqualuxe_sanitize_zip($zip) {
    return sanitize_text_field($zip);
}

/**
 * Sanitize address
 *
 * @param string $address Address
 * @return string Sanitized address
 */
function aqualuxe_sanitize_address($address) {
    return sanitize_text_field($address);
}

/**
 * Sanitize phone
 *
 * @param string $phone Phone
 * @return string Sanitized phone
 */
function aqualuxe_sanitize_phone($phone) {
    return sanitize_text_field($phone);
}

/**
 * Sanitize email
 *
 * @param string $email Email
 * @return string Sanitized email
 */
function aqualuxe_sanitize_email($email) {
    return sanitize_email($email);
}

/**
 * Sanitize username
 *
 * @param string $username Username
 * @return string Sanitized username
 */
function aqualuxe_sanitize_username($username) {
    return sanitize_user($username);
}

/**
 * Sanitize password
 *
 * @param string $password Password
 * @return string Sanitized password
 */
function aqualuxe_sanitize_password($password) {
    return $password;
}

/**
 * Sanitize file name
 *
 * @param string $filename File name
 * @return string Sanitized file name
 */
function aqualuxe_sanitize_file_name($filename) {
    return sanitize_file_name($filename);
}

/**
 * Sanitize file path
 *
 * @param string $path File path
 * @return string Sanitized file path
 */
function aqualuxe_sanitize_file_path($path) {
    return sanitize_text_field($path);
}

/**
 * Sanitize file URL
 *
 * @param string $url File URL
 * @return string Sanitized file URL
 */
function aqualuxe_sanitize_file_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize image ID
 *
 * @param int $id Image ID
 * @return int Sanitized image ID
 */
function aqualuxe_sanitize_image_id($id) {
    return absint($id);
}

/**
 * Sanitize image URL
 *
 * @param string $url Image URL
 * @return string Sanitized image URL
 */
function aqualuxe_sanitize_image_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize video ID
 *
 * @param int $id Video ID
 * @return int Sanitized video ID
 */
function aqualuxe_sanitize_video_id($id) {
    return absint($id);
}

/**
 * Sanitize video URL
 *
 * @param string $url Video URL
 * @return string Sanitized video URL
 */
function aqualuxe_sanitize_video_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize audio ID
 *
 * @param int $id Audio ID
 * @return int Sanitized audio ID
 */
function aqualuxe_sanitize_audio_id($id) {
    return absint($id);
}

/**
 * Sanitize audio URL
 *
 * @param string $url Audio URL
 * @return string Sanitized audio URL
 */
function aqualuxe_sanitize_audio_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize document ID
 *
 * @param int $id Document ID
 * @return int Sanitized document ID
 */
function aqualuxe_sanitize_document_id($id) {
    return absint($id);
}

/**
 * Sanitize document URL
 *
 * @param string $url Document URL
 * @return string Sanitized document URL
 */
function aqualuxe_sanitize_document_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize embed URL
 *
 * @param string $url Embed URL
 * @return string Sanitized embed URL
 */
function aqualuxe_sanitize_embed_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize embed code
 *
 * @param string $code Embed code
 * @return string Sanitized embed code
 */
function aqualuxe_sanitize_embed_code($code) {
    return wp_kses_post($code);
}

/**
 * Sanitize shortcode
 *
 * @param string $shortcode Shortcode
 * @return string Sanitized shortcode
 */
function aqualuxe_sanitize_shortcode($shortcode) {
    return sanitize_text_field($shortcode);
}

/**
 * Sanitize widget ID
 *
 * @param string $id Widget ID
 * @return string Sanitized widget ID
 */
function aqualuxe_sanitize_widget_id($id) {
    return sanitize_key($id);
}

/**
 * Sanitize menu ID
 *
 * @param int $id Menu ID
 * @return int Sanitized menu ID
 */
function aqualuxe_sanitize_menu_id($id) {
    return absint($id);
}

/**
 * Sanitize menu location
 *
 * @param string $location Menu location
 * @return string Sanitized menu location
 */
function aqualuxe_sanitize_menu_location($location) {
    return sanitize_key($location);
}

/**
 * Sanitize post ID
 *
 * @param int $id Post ID
 * @return int Sanitized post ID
 */
function aqualuxe_sanitize_post_id($id) {
    return absint($id);
}

/**
 * Sanitize post type
 *
 * @param string $type Post type
 * @return string Sanitized post type
 */
function aqualuxe_sanitize_post_type($type) {
    return sanitize_key($type);
}

/**
 * Sanitize taxonomy
 *
 * @param string $taxonomy Taxonomy
 * @return string Sanitized taxonomy
 */
function aqualuxe_sanitize_taxonomy($taxonomy) {
    return sanitize_key($taxonomy);
}

/**
 * Sanitize term ID
 *
 * @param int $id Term ID
 * @return int Sanitized term ID
 */
function aqualuxe_sanitize_term_id($id) {
    return absint($id);
}

/**
 * Sanitize user ID
 *
 * @param int $id User ID
 * @return int Sanitized user ID
 */
function aqualuxe_sanitize_user_id($id) {
    return absint($id);
}

/**
 * Sanitize role
 *
 * @param string $role Role
 * @return string Sanitized role
 */
function aqualuxe_sanitize_role($role) {
    return sanitize_key($role);
}

/**
 * Sanitize capability
 *
 * @param string $capability Capability
 * @return string Sanitized capability
 */
function aqualuxe_sanitize_capability($capability) {
    return sanitize_key($capability);
}

/**
 * Sanitize language
 *
 * @param string $language Language
 * @return string Sanitized language
 */
function aqualuxe_sanitize_language($language) {
    return sanitize_key($language);
}

/**
 * Sanitize locale
 *
 * @param string $locale Locale
 * @return string Sanitized locale
 */
function aqualuxe_sanitize_locale($locale) {
    return sanitize_key($locale);
}

/**
 * Sanitize timezone
 *
 * @param string $timezone Timezone
 * @return string Sanitized timezone
 */
function aqualuxe_sanitize_timezone($timezone) {
    return sanitize_text_field($timezone);
}

/**
 * Sanitize date
 *
 * @param string $date Date
 * @return string Sanitized date
 */
function aqualuxe_sanitize_date($date) {
    return sanitize_text_field($date);
}

/**
 * Sanitize time
 *
 * @param string $time Time
 * @return string Sanitized time
 */
function aqualuxe_sanitize_time($time) {
    return sanitize_text_field($time);
}

/**
 * Sanitize datetime
 *
 * @param string $datetime Datetime
 * @return string Sanitized datetime
 */
function aqualuxe_sanitize_datetime($datetime) {
    return sanitize_text_field($datetime);
}

/**
 * Sanitize color mode
 *
 * @param string $mode Color mode
 * @return string Sanitized color mode
 */
function aqualuxe_sanitize_color_mode($mode) {
    $allowed_modes = ['light', 'dark', 'auto'];
    
    return in_array($mode, $allowed_modes) ? $mode : 'light';
}

/**
 * Sanitize module ID
 *
 * @param string $id Module ID
 * @return string Sanitized module ID
 */
function aqualuxe_sanitize_module_id($id) {
    return sanitize_key($id);
}

/**
 * Sanitize module status
 *
 * @param bool $status Module status
 * @return bool Sanitized module status
 */
function aqualuxe_sanitize_module_status($status) {
    return (isset($status) && true === $status) ? true : false;
}

/**
 * Get image URL by ID
 *
 * @param int    $id   Image ID
 * @param string $size Image size
 * @return string Image URL
 */
function aqualuxe_get_image_url($id, $size = 'full') {
    if (!$id) {
        return '';
    }
    
    $image = wp_get_attachment_image_src($id, $size);
    
    if ($image) {
        return $image[0];
    }
    
    return '';
}

/**
 * Get image alt by ID
 *
 * @param int $id Image ID
 * @return string Image alt
 */
function aqualuxe_get_image_alt($id) {
    if (!$id) {
        return '';
    }
    
    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    
    if ($alt) {
        return $alt;
    }
    
    return '';
}

/**
 * Get image caption by ID
 *
 * @param int $id Image ID
 * @return string Image caption
 */
function aqualuxe_get_image_caption($id) {
    if (!$id) {
        return '';
    }
    
    $image = get_post($id);
    
    if ($image && isset($image->post_excerpt)) {
        return $image->post_excerpt;
    }
    
    return '';
}

/**
 * Get image description by ID
 *
 * @param int $id Image ID
 * @return string Image description
 */
function aqualuxe_get_image_description($id) {
    if (!$id) {
        return '';
    }
    
    $image = get_post($id);
    
    if ($image && isset($image->post_content)) {
        return $image->post_content;
    }
    
    return '';
}

/**
 * Get image title by ID
 *
 * @param int $id Image ID
 * @return string Image title
 */
function aqualuxe_get_image_title($id) {
    if (!$id) {
        return '';
    }
    
    $image = get_post($id);
    
    if ($image && isset($image->post_title)) {
        return $image->post_title;
    }
    
    return '';
}

/**
 * Get image dimensions by ID
 *
 * @param int    $id   Image ID
 * @param string $size Image size
 * @return array Image dimensions
 */
function aqualuxe_get_image_dimensions($id, $size = 'full') {
    if (!$id) {
        return [];
    }
    
    $image = wp_get_attachment_image_src($id, $size);
    
    if ($image) {
        return [
            'width' => $image[1],
            'height' => $image[2],
        ];
    }
    
    return [];
}

/**
 * Get video URL by ID
 *
 * @param int $id Video ID
 * @return string Video URL
 */
function aqualuxe_get_video_url($id) {
    if (!$id) {
        return '';
    }
    
    $video = wp_get_attachment_url($id);
    
    if ($video) {
        return $video;
    }
    
    return '';
}

/**
 * Get audio URL by ID
 *
 * @param int $id Audio ID
 * @return string Audio URL
 */
function aqualuxe_get_audio_url($id) {
    if (!$id) {
        return '';
    }
    
    $audio = wp_get_attachment_url($id);
    
    if ($audio) {
        return $audio;
    }
    
    return '';
}

/**
 * Get document URL by ID
 *
 * @param int $id Document ID
 * @return string Document URL
 */
function aqualuxe_get_document_url($id) {
    if (!$id) {
        return '';
    }
    
    $document = wp_get_attachment_url($id);
    
    if ($document) {
        return $document;
    }
    
    return '';
}

/**
 * Get YouTube video ID from URL
 *
 * @param string $url YouTube URL
 * @return string YouTube video ID
 */
function aqualuxe_get_youtube_id($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    
    return '';
}

/**
 * Get Vimeo video ID from URL
 *
 * @param string $url Vimeo URL
 * @return string Vimeo video ID
 */
function aqualuxe_get_vimeo_id($url) {
    $pattern = '/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^\/]*\/videos\/|album\/\d+\/video\/|)(\d+)(?:$|\/|\?))/i';
    
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    
    return '';
}

/**
 * Get embed URL from video URL
 *
 * @param string $url Video URL
 * @return string Embed URL
 */
function aqualuxe_get_embed_url($url) {
    $youtube_id = aqualuxe_get_youtube_id($url);
    
    if ($youtube_id) {
        return 'https://www.youtube.com/embed/' . $youtube_id;
    }
    
    $vimeo_id = aqualuxe_get_vimeo_id($url);
    
    if ($vimeo_id) {
        return 'https://player.vimeo.com/video/' . $vimeo_id;
    }
    
    return '';
}

/**
 * Get embed code from video URL
 *
 * @param string $url   Video URL
 * @param int    $width  Width
 * @param int    $height Height
 * @return string Embed code
 */
function aqualuxe_get_embed_code($url, $width = 640, $height = 360) {
    $embed_url = aqualuxe_get_embed_url($url);
    
    if ($embed_url) {
        return '<iframe width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" src="' . esc_url($embed_url) . '" frameborder="0" allowfullscreen></iframe>';
    }
    
    return '';
}

/**
 * Get post thumbnail URL
 *
 * @param int    $post_id Post ID
 * @param string $size    Image size
 * @return string Post thumbnail URL
 */
function aqualuxe_get_post_thumbnail_url($post_id = null, $size = 'full') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    
    return '';
}

/**
 * Get post thumbnail ID
 *
 * @param int $post_id Post ID
 * @return int Post thumbnail ID
 */
function aqualuxe_get_post_thumbnail_id($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_post_thumbnail_id($post_id);
    }
    
    return 0;
}

/**
 * Get post excerpt
 *
 * @param int $post_id Post ID
 * @param int $length  Excerpt length
 * @return string Post excerpt
 */
function aqualuxe_get_post_excerpt($post_id = null, $length = 55) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if ($post->post_excerpt) {
        return $post->post_excerpt;
    }
    
    $content = $post->post_content;
    $content = strip_shortcodes($content);
    $content = excerpt_remove_blocks($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    
    $excerpt_length = apply_filters('excerpt_length', $length);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
    
    return wp_trim_words($content, $excerpt_length, $excerpt_more);
}

/**
 * Get post author
 *
 * @param int $post_id Post ID
 * @return string Post author
 */
function aqualuxe_get_post_author($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    return get_the_author_meta('display_name', $post->post_author);
}

/**
 * Get post date
 *
 * @param int    $post_id Post ID
 * @param string $format  Date format
 * @return string Post date
 */
function aqualuxe_get_post_date($post_id = null, $format = '') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format');
    }
    
    return get_the_date($format, $post_id);
}

/**
 * Get post modified date
 *
 * @param int    $post_id Post ID
 * @param string $format  Date format
 * @return string Post modified date
 */
function aqualuxe_get_post_modified_date($post_id = null, $format = '') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format');
    }
    
    return get_the_modified_date($format, $post_id);
}

/**
 * Get post categories
 *
 * @param int    $post_id Post ID
 * @param string $sep     Separator
 * @return string Post categories
 */
function aqualuxe_get_post_categories($post_id = null, $sep = ', ') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if (!$categories) {
        return '';
    }
    
    $names = [];
    
    foreach ($categories as $category) {
        $names[] = $category->name;
    }
    
    return implode($sep, $names);
}

/**
 * Get post tags
 *
 * @param int    $post_id Post ID
 * @param string $sep     Separator
 * @return string Post tags
 */
function aqualuxe_get_post_tags($post_id = null, $sep = ', ') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $tags = get_the_tags($post_id);
    
    if (!$tags) {
        return '';
    }
    
    $names = [];
    
    foreach ($tags as $tag) {
        $names[] = $tag->name;
    }
    
    return implode($sep, $names);
}

/**
 * Get post comments count
 *
 * @param int $post_id Post ID
 * @return int Post comments count
 */
function aqualuxe_get_post_comments_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_comments_number($post_id);
}

/**
 * Get post views count
 *
 * @param int $post_id Post ID
 * @return int Post views count
 */
function aqualuxe_get_post_views_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, '_aqualuxe_post_views', true);
    
    if (!$count) {
        return 0;
    }
    
    return $count;
}

/**
 * Set post views count
 *
 * @param int $post_id Post ID
 * @param int $count   Views count
 */
function aqualuxe_set_post_views_count($post_id = null, $count = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    update_post_meta($post_id, '_aqualuxe_post_views', $count);
}

/**
 * Increment post views count
 *
 * @param int $post_id Post ID
 */
function aqualuxe_increment_post_views_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = aqualuxe_get_post_views_count($post_id);
    $count++;
    
    aqualuxe_set_post_views_count($post_id, $count);
}

/**
 * Get post likes count
 *
 * @param int $post_id Post ID
 * @return int Post likes count
 */
function aqualuxe_get_post_likes_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, '_aqualuxe_post_likes', true);
    
    if (!$count) {
        return 0;
    }
    
    return $count;
}

/**
 * Set post likes count
 *
 * @param int $post_id Post ID
 * @param int $count   Likes count
 */
function aqualuxe_set_post_likes_count($post_id = null, $count = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    update_post_meta($post_id, '_aqualuxe_post_likes', $count);
}

/**
 * Increment post likes count
 *
 * @param int $post_id Post ID
 */
function aqualuxe_increment_post_likes_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = aqualuxe_get_post_likes_count($post_id);
    $count++;
    
    aqualuxe_set_post_likes_count($post_id, $count);
}

/**
 * Get post shares count
 *
 * @param int $post_id Post ID
 * @return int Post shares count
 */
function aqualuxe_get_post_shares_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, '_aqualuxe_post_shares', true);
    
    if (!$count) {
        return 0;
    }
    
    return $count;
}

/**
 * Set post shares count
 *
 * @param int $post_id Post ID
 * @param int $count   Shares count
 */
function aqualuxe_set_post_shares_count($post_id = null, $count = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    update_post_meta($post_id, '_aqualuxe_post_shares', $count);
}

/**
 * Increment post shares count
 *
 * @param int $post_id Post ID
 */
function aqualuxe_increment_post_shares_count($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = aqualuxe_get_post_shares_count($post_id);
    $count++;
    
    aqualuxe_set_post_shares_count($post_id, $count);
}

/**
 * Get post reading time
 *
 * @param int $post_id Post ID
 * @return int Post reading time
 */
function aqualuxe_get_post_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return 0;
    }
    
    $content = $post->post_content;
    $content = strip_shortcodes($content);
    $content = excerpt_remove_blocks($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);
    
    $word_count = str_word_count($content);
    $reading_time = ceil($word_count / 200);
    
    if ($reading_time < 1) {
        $reading_time = 1;
    }
    
    return $reading_time;
}

/**
 * Get post URL
 *
 * @param int $post_id Post ID
 * @return string Post URL
 */
function aqualuxe_get_post_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_permalink($post_id);
}

/**
 * Get post edit URL
 *
 * @param int $post_id Post ID
 * @return string Post edit URL
 */
function aqualuxe_get_post_edit_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_edit_post_link($post_id);
}

/**
 * Get post delete URL
 *
 * @param int $post_id Post ID
 * @return string Post delete URL
 */
function aqualuxe_get_post_delete_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_delete_post_link($post_id);
}

/**
 * Get post type
 *
 * @param int $post_id Post ID
 * @return string Post type
 */
function aqualuxe_get_post_type($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_type($post_id);
}

/**
 * Get post type label
 *
 * @param int $post_id Post ID
 * @return string Post type label
 */
function aqualuxe_get_post_type_label($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_type = get_post_type($post_id);
    $post_type_object = get_post_type_object($post_type);
    
    if ($post_type_object) {
        return $post_type_object->labels->singular_name;
    }
    
    return '';
}

/**
 * Get post status
 *
 * @param int $post_id Post ID
 * @return string Post status
 */
function aqualuxe_get_post_status($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_status($post_id);
}

/**
 * Get post status label
 *
 * @param int $post_id Post ID
 * @return string Post status label
 */
function aqualuxe_get_post_status_label($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $status = get_post_status($post_id);
    $status_object = get_post_status_object($status);
    
    if ($status_object) {
        return $status_object->label;
    }
    
    return '';
}

/**
 * Get post format
 *
 * @param int $post_id Post ID
 * @return string Post format
 */
function aqualuxe_get_post_format($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $format = get_post_format($post_id);
    
    if (!$format) {
        return 'standard';
    }
    
    return $format;
}

/**
 * Get post format label
 *
 * @param int $post_id Post ID
 * @return string Post format label
 */
function aqualuxe_get_post_format_label($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $format = get_post_format($post_id);
    
    if (!$format) {
        return __('Standard', 'aqualuxe');
    }
    
    return get_post_format_string($format);
}

/**
 * Get post meta
 *
 * @param string $key     Meta key
 * @param int    $post_id Post ID
 * @param bool   $single  Single
 * @return mixed Post meta
 */
function aqualuxe_get_post_meta($key, $post_id = null, $single = true) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, $key, $single);
}

/**
 * Set post meta
 *
 * @param string $key     Meta key
 * @param mixed  $value   Meta value
 * @param int    $post_id Post ID
 */
function aqualuxe_set_post_meta($key, $value, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    update_post_meta($post_id, $key, $value);
}

/**
 * Delete post meta
 *
 * @param string $key     Meta key
 * @param int    $post_id Post ID
 */
function aqualuxe_delete_post_meta($key, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    delete_post_meta($post_id, $key);
}

/**
 * Get user meta
 *
 * @param string $key     Meta key
 * @param int    $user_id User ID
 * @param bool   $single  Single
 * @return mixed User meta
 */
function aqualuxe_get_user_meta($key, $user_id = null, $single = true) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    return get_user_meta($user_id, $key, $single);
}

/**
 * Set user meta
 *
 * @param string $key     Meta key
 * @param mixed  $value   Meta value
 * @param int    $user_id User ID
 */
function aqualuxe_set_user_meta($key, $value, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    update_user_meta($user_id, $key, $value);
}

/**
 * Delete user meta
 *
 * @param string $key     Meta key
 * @param int    $user_id User ID
 */
function aqualuxe_delete_user_meta($key, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    delete_user_meta($user_id, $key);
}

/**
 * Get term meta
 *
 * @param string $key     Meta key
 * @param int    $term_id Term ID
 * @param bool   $single  Single
 * @return mixed Term meta
 */
function aqualuxe_get_term_meta($key, $term_id, $single = true) {
    return get_term_meta($term_id, $key, $single);
}

/**
 * Set term meta
 *
 * @param string $key     Meta key
 * @param mixed  $value   Meta value
 * @param int    $term_id Term ID
 */
function aqualuxe_set_term_meta($key, $value, $term_id) {
    update_term_meta($term_id, $key, $value);
}

/**
 * Delete term meta
 *
 * @param string $key     Meta key
 * @param int    $term_id Term ID
 */
function aqualuxe_delete_term_meta($key, $term_id) {
    delete_term_meta($term_id, $key);
}

/**
 * Get option
 *
 * @param string $key     Option key
 * @param mixed  $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($key, $default = '') {
    return get_option($key, $default);
}

/**
 * Set option
 *
 * @param string $key   Option key
 * @param mixed  $value Option value
 */
function aqualuxe_set_option($key, $value) {
    update_option($key, $value);
}

/**
 * Delete option
 *
 * @param string $key Option key
 */
function aqualuxe_delete_option($key) {
    delete_option($key);
}

/**
 * Get transient
 *
 * @param string $key Transient key
 * @return mixed Transient value
 */
function aqualuxe_get_transient($key) {
    return get_transient($key);
}

/**
 * Set transient
 *
 * @param string $key    Transient key
 * @param mixed  $value  Transient value
 * @param int    $expiry Expiry time
 */
function aqualuxe_set_transient($key, $value, $expiry = 0) {
    set_transient($key, $value, $expiry);
}

/**
 * Delete transient
 *
 * @param string $key Transient key
 */
function aqualuxe_delete_transient($key) {
    delete_transient($key);
}

/**
 * Get site transient
 *
 * @param string $key Transient key
 * @return mixed Transient value
 */
function aqualuxe_get_site_transient($key) {
    return get_site_transient($key);
}

/**
 * Set site transient
 *
 * @param string $key    Transient key
 * @param mixed  $value  Transient value
 * @param int    $expiry Expiry time
 */
function aqualuxe_set_site_transient($key, $value, $expiry = 0) {
    set_site_transient($key, $value, $expiry);
}

/**
 * Delete site transient
 *
 * @param string $key Transient key
 */
function aqualuxe_delete_site_transient($key) {
    delete_site_transient($key);
}

/**
 * Get cookie
 *
 * @param string $key     Cookie key
 * @param mixed  $default Default value
 * @return mixed Cookie value
 */
function aqualuxe_get_cookie($key, $default = '') {
    if (isset($_COOKIE[$key])) {
        return $_COOKIE[$key];
    }
    
    return $default;
}

/**
 * Set cookie
 *
 * @param string $key    Cookie key
 * @param mixed  $value  Cookie value
 * @param int    $expiry Expiry time
 */
function aqualuxe_set_cookie($key, $value, $expiry = 0) {
    setcookie($key, $value, $expiry, COOKIEPATH, COOKIE_DOMAIN);
}

/**
 * Delete cookie
 *
 * @param string $key Cookie key
 */
function aqualuxe_delete_cookie($key) {
    setcookie($key, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
}

/**
 * Get session
 *
 * @param string $key     Session key
 * @param mixed  $default Default value
 * @return mixed Session value
 */
function aqualuxe_get_session($key, $default = '') {
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }
    
    return $default;
}

/**
 * Set session
 *
 * @param string $key   Session key
 * @param mixed  $value Session value
 */
function aqualuxe_set_session($key, $value) {
    $_SESSION[$key] = $value;
}

/**
 * Delete session
 *
 * @param string $key Session key
 */
function aqualuxe_delete_session($key) {
    unset($_SESSION[$key]);
}

/**
 * Get request
 *
 * @param string $key     Request key
 * @param mixed  $default Default value
 * @return mixed Request value
 */
function aqualuxe_get_request($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    
    return $default;
}

/**
 * Get get
 *
 * @param string $key     Get key
 * @param mixed  $default Default value
 * @return mixed Get value
 */
function aqualuxe_get_get($key, $default = '') {
    if (isset($_GET[$key])) {
        return $_GET[$key];
    }
    
    return $default;
}

/**
 * Get post
 *
 * @param string $key     Post key
 * @param mixed  $default Default value
 * @return mixed Post value
 */
function aqualuxe_get_post($key, $default = '') {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    
    return $default;
}

/**
 * Get server
 *
 * @param string $key     Server key
 * @param mixed  $default Default value
 * @return mixed Server value
 */
function aqualuxe_get_server($key, $default = '') {
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    
    return $default;
}

/**
 * Get env
 *
 * @param string $key     Env key
 * @param mixed  $default Default value
 * @return mixed Env value
 */
function aqualuxe_get_env($key, $default = '') {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    
    return $default;
}

/**
 * Get files
 *
 * @param string $key     Files key
 * @param mixed  $default Default value
 * @return mixed Files value
 */
function aqualuxe_get_files($key, $default = '') {
    if (isset($_FILES[$key])) {
        return $_FILES[$key];
    }
    
    return $default;
}

/**
 * Get current URL
 *
 * @return string Current URL
 */
function aqualuxe_get_current_url() {
    $protocol = is_ssl() ? 'https://' : 'http://';
    $host = aqualuxe_get_server('HTTP_HOST');
    $uri = aqualuxe_get_server('REQUEST_URI');
    
    return $protocol . $host . $uri;
}

/**
 * Get current page URL
 *
 * @return string Current page URL
 */
function aqualuxe_get_current_page_url() {
    global $wp;
    
    return home_url(add_query_arg([], $wp->request));
}

/**
 * Get current page path
 *
 * @return string Current page path
 */
function aqualuxe_get_current_page_path() {
    global $wp;
    
    return $wp->request;
}

/**
 * Get current page ID
 *
 * @return int Current page ID
 */
function aqualuxe_get_current_page_id() {
    global $wp_query;
    
    if (isset($wp_query->post->ID)) {
        return $wp_query->post->ID;
    }
    
    return 0;
}

/**
 * Get current page title
 *
 * @return string Current page title
 */
function aqualuxe_get_current_page_title() {
    global $wp_query;
    
    if (isset($wp_query->post->post_title)) {
        return $wp_query->post->post_title;
    }
    
    return '';
}

/**
 * Get current page type
 *
 * @return string Current page type
 */
function aqualuxe_get_current_page_type() {
    if (is_front_page() && is_home()) {
        return 'home';
    } elseif (is_front_page()) {
        return 'front_page';
    } elseif (is_home()) {
        return 'blog';
    } elseif (is_singular()) {
        return 'singular';
    } elseif (is_archive()) {
        return 'archive';
    } elseif (is_search()) {
        return 'search';
    } elseif (is_404()) {
        return '404';
    } else {
        return '';
    }
}

/**
 * Get current user ID
 *
 * @return int Current user ID
 */
function aqualuxe_get_current_user_id() {
    return get_current_user_id();
}

/**
 * Get current user
 *
 * @return WP_User Current user
 */
function aqualuxe_get_current_user() {
    return wp_get_current_user();
}

/**
 * Get current user role
 *
 * @return string Current user role
 */
function aqualuxe_get_current_user_role() {
    $user = aqualuxe_get_current_user();
    
    if ($user->ID === 0) {
        return '';
    }
    
    $roles = $user->roles;
    
    if (empty($roles)) {
        return '';
    }
    
    return $roles[0];
}

/**
 * Get current user roles
 *
 * @return array Current user roles
 */
function aqualuxe_get_current_user_roles() {
    $user = aqualuxe_get_current_user();
    
    if ($user->ID === 0) {
        return [];
    }
    
    return $user->roles;
}

/**
 * Get current user capabilities
 *
 * @return array Current user capabilities
 */
function aqualuxe_get_current_user_capabilities() {
    $user = aqualuxe_get_current_user();
    
    if ($user->ID === 0) {
        return [];
    }
    
    return $user->allcaps;
}

/**
 * Check if current user has capability
 *
 * @param string $capability Capability
 * @return bool
 */
function aqualuxe_current_user_has_capability($capability) {
    return current_user_can($capability);
}

/**
 * Check if current user has role
 *
 * @param string $role Role
 * @return bool
 */
function aqualuxe_current_user_has_role($role) {
    $user = aqualuxe_get_current_user();
    
    if ($user->ID === 0) {
        return false;
    }
    
    return in_array($role, (array) $user->roles);
}

/**
 * Check if user is logged in
 *
 * @return bool
 */
function aqualuxe_is_user_logged_in() {
    return is_user_logged_in();
}

/**
 * Check if user is admin
 *
 * @return bool
 */
function aqualuxe_is_user_admin() {
    return current_user_can('manage_options');
}

/**
 * Check if user is editor
 *
 * @return bool
 */
function aqualuxe_is_user_editor() {
    return current_user_can('edit_others_posts');
}

/**
 * Check if user is author
 *
 * @return bool
 */
function aqualuxe_is_user_author() {
    return current_user_can('publish_posts');
}

/**
 * Check if user is subscriber
 *
 * @return bool
 */
function aqualuxe_is_user_subscriber() {
    return current_user_can('read');
}

/**
 * Check if is admin
 *
 * @return bool
 */
function aqualuxe_is_admin() {
    return is_admin();
}

/**
 * Check if is login
 *
 * @return bool
 */
function aqualuxe_is_login() {
    return in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php']);
}

/**
 * Check if is ajax
 *
 * @return bool
 */
function aqualuxe_is_ajax() {
    return defined('DOING_AJAX') && DOING_AJAX;
}

/**
 * Check if is cron
 *
 * @return bool
 */
function aqualuxe_is_cron() {
    return defined('DOING_CRON') && DOING_CRON;
}

/**
 * Check if is rest
 *
 * @return bool
 */
function aqualuxe_is_rest() {
    return defined('REST_REQUEST') && REST_REQUEST;
}

/**
 * Check if is cli
 *
 * @return bool
 */
function aqualuxe_is_cli() {
    return defined('WP_CLI') && WP_CLI;
}

/**
 * Check if is ssl
 *
 * @return bool
 */
function aqualuxe_is_ssl() {
    return is_ssl();
}

/**
 * Check if is localhost
 *
 * @return bool
 */
function aqualuxe_is_localhost() {
    $server_name = aqualuxe_get_server('SERVER_NAME');
    $server_addr = aqualuxe_get_server('SERVER_ADDR');
    $remote_addr = aqualuxe_get_server('REMOTE_ADDR');
    
    $is_local_ip = in_array($server_addr, ['127.0.0.1', '::1']) || in_array($remote_addr, ['127.0.0.1', '::1']);
    $is_local_name = in_array($server_name, ['localhost', 'localhost.localdomain', '127.0.0.1', '::1']);
    
    return $is_local_ip || $is_local_name;
}

/**
 * Check if is development
 *
 * @return bool
 */
function aqualuxe_is_development() {
    return defined('WP_DEBUG') && WP_DEBUG;
}

/**
 * Check if is production
 *
 * @return bool
 */
function aqualuxe_is_production() {
    return !aqualuxe_is_development();
}

/**
 * Check if is staging
 *
 * @return bool
 */
function aqualuxe_is_staging() {
    $server_name = aqualuxe_get_server('SERVER_NAME');
    
    return strpos($server_name, 'staging') !== false || strpos($server_name, 'stage') !== false || strpos($server_name, 'test') !== false || strpos($server_name, 'dev') !== false;
}

/**
 * Check if is mobile
 *
 * @return bool
 */
function aqualuxe_is_mobile() {
    return wp_is_mobile();
}

/**
 * Check if is tablet
 *
 * @return bool
 */
function aqualuxe_is_tablet() {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return false;
    }
    
    $tablet_pattern = '/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i';
    
    return preg_match($tablet_pattern, $user_agent);
}

/**
 * Check if is desktop
 *
 * @return bool
 */
function aqualuxe_is_desktop() {
    return !aqualuxe_is_mobile() && !aqualuxe_is_tablet();
}

/**
 * Check if is bot
 *
 * @return bool
 */
function aqualuxe_is_bot() {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return false;
    }
    
    $bot_pattern = '/(bot|crawler|spider|crawling|slurp|duckduckbot|baiduspider|yandexbot|bingbot|googlebot|yahoo|ahrefsbot|msnbot|semrushbot|majestic|ahrefs|screaming frog)/i';
    
    return preg_match($bot_pattern, $user_agent);
}

/**
 * Check if is browser
 *
 * @param string $browser Browser
 * @return bool
 */
function aqualuxe_is_browser($browser) {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return false;
    }
    
    switch ($browser) {
        case 'chrome':
            return strpos($user_agent, 'Chrome') !== false;
        case 'firefox':
            return strpos($user_agent, 'Firefox') !== false;
        case 'safari':
            return strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false;
        case 'edge':
            return strpos($user_agent, 'Edge') !== false || strpos($user_agent, 'Edg') !== false;
        case 'ie':
            return strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false;
        case 'opera':
            return strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false;
        default:
            return false;
    }
}

/**
 * Check if is os
 *
 * @param string $os OS
 * @return bool
 */
function aqualuxe_is_os($os) {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return false;
    }
    
    switch ($os) {
        case 'windows':
            return strpos($user_agent, 'Windows') !== false;
        case 'mac':
            return strpos($user_agent, 'Macintosh') !== false || strpos($user_agent, 'Mac OS') !== false;
        case 'linux':
            return strpos($user_agent, 'Linux') !== false && strpos($user_agent, 'Android') === false;
        case 'android':
            return strpos($user_agent, 'Android') !== false;
        case 'ios':
            return strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false || strpos($user_agent, 'iPod') !== false;
        default:
            return false;
    }
}

/**
 * Get browser
 *
 * @return string Browser
 */
function aqualuxe_get_browser() {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return '';
    }
    
    if (strpos($user_agent, 'Edge') !== false || strpos($user_agent, 'Edg') !== false) {
        return 'edge';
    } elseif (strpos($user_agent, 'Chrome') !== false) {
        return 'chrome';
    } elseif (strpos($user_agent, 'Firefox') !== false) {
        return 'firefox';
    } elseif (strpos($user_agent, 'Safari') !== false) {
        return 'safari';
    } elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
        return 'opera';
    } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
        return 'ie';
    } else {
        return '';
    }
}

/**
 * Get os
 *
 * @return string OS
 */
function aqualuxe_get_os() {
    $user_agent = aqualuxe_get_server('HTTP_USER_AGENT');
    
    if (!$user_agent) {
        return '';
    }
    
    if (strpos($user_agent, 'Windows') !== false) {
        return 'windows';
    } elseif (strpos($user_agent, 'Android') !== false) {
        return 'android';
    } elseif (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false || strpos($user_agent, 'iPod') !== false) {
        return 'ios';
    } elseif (strpos($user_agent, 'Macintosh') !== false || strpos($user_agent, 'Mac OS') !== false) {
        return 'mac';
    } elseif (strpos($user_agent, 'Linux') !== false) {
        return 'linux';
    } else {
        return '';
    }
}

/**
 * Get device
 *
 * @return string Device
 */
function aqualuxe_get_device() {
    if (aqualuxe_is_mobile()) {
        return 'mobile';
    } elseif (aqualuxe_is_tablet()) {
        return 'tablet';
    } else {
        return 'desktop';
    }
}

/**
 * Get user agent
 *
 * @return string User agent
 */
function aqualuxe_get_user_agent() {
    return aqualuxe_get_server('HTTP_USER_AGENT');
}

/**
 * Get ip
 *
 * @return string IP
 */
function aqualuxe_get_ip() {
    $ip = aqualuxe_get_server('REMOTE_ADDR');
    
    if (!$ip) {
        $ip = aqualuxe_get_server('HTTP_CLIENT_IP');
    }
    
    if (!$ip) {
        $ip = aqualuxe_get_server('HTTP_X_FORWARDED_FOR');
    }
    
    if (!$ip) {
        $ip = '127.0.0.1';
    }
    
    return $ip;
}

/**
 * Get referer
 *
 * @return string Referer
 */
function aqualuxe_get_referer() {
    return aqualuxe_get_server('HTTP_REFERER');
}

/**
 * Get user language
 *
 * @return string User language
 */
function aqualuxe_get_user_language() {
    return aqualuxe_get_server('HTTP_ACCEPT_LANGUAGE');
}

/**
 * Get site language
 *
 * @return string Site language
 */
function aqualuxe_get_site_language() {
    return get_locale();
}

/**
 * Get site timezone
 *
 * @return string Site timezone
 */
function aqualuxe_get_site_timezone() {
    return get_option('timezone_string');
}

/**
 * Get site date format
 *
 * @return string Site date format
 */
function aqualuxe_get_site_date_format() {
    return get_option('date_format');
}

/**
 * Get site time format
 *
 * @return string Site time format
 */
function aqualuxe_get_site_time_format() {
    return get_option('time_format');
}

/**
 * Get site charset
 *
 * @return string Site charset
 */
function aqualuxe_get_site_charset() {
    return get_option('blog_charset');
}

/**
 * Get site name
 *
 * @return string Site name
 */
function aqualuxe_get_site_name() {
    return get_bloginfo('name');
}

/**
 * Get site description
 *
 * @return string Site description
 */
function aqualuxe_get_site_description() {
    return get_bloginfo('description');
}

/**
 * Get site url
 *
 * @return string Site URL
 */
function aqualuxe_get_site_url() {
    return get_bloginfo('url');
}

/**
 * Get site admin email
 *
 * @return string Site admin email
 */
function aqualuxe_get_site_admin_email() {
    return get_bloginfo('admin_email');
}

/**
 * Get site version
 *
 * @return string Site version
 */
function aqualuxe_get_site_version() {
    return get_bloginfo('version');
}

/**
 * Get theme name
 *
 * @return string Theme name
 */
function aqualuxe_get_theme_name() {
    return wp_get_theme()->get('Name');
}

/**
 * Get theme version
 *
 * @return string Theme version
 */
function aqualuxe_get_theme_version() {
    return wp_get_theme()->get('Version');
}

/**
 * Get theme author
 *
 * @return string Theme author
 */
function aqualuxe_get_theme_author() {
    return wp_get_theme()->get('Author');
}

/**
 * Get theme author uri
 *
 * @return string Theme author URI
 */
function aqualuxe_get_theme_author_uri() {
    return wp_get_theme()->get('AuthorURI');
}

/**
 * Get theme uri
 *
 * @return string Theme URI
 */
function aqualuxe_get_theme_uri() {
    return wp_get_theme()->get('ThemeURI');
}

/**
 * Get theme description
 *
 * @return string Theme description
 */
function aqualuxe_get_theme_description() {
    return wp_get_theme()->get('Description');
}

/**
 * Get theme tags
 *
 * @return array Theme tags
 */
function aqualuxe_get_theme_tags() {
    return wp_get_theme()->get('Tags');
}

/**
 * Get theme text domain
 *
 * @return string Theme text domain
 */
function aqualuxe_get_theme_text_domain() {
    return wp_get_theme()->get('TextDomain');
}

/**
 * Get theme domain path
 *
 * @return string Theme domain path
 */
function aqualuxe_get_theme_domain_path() {
    return wp_get_theme()->get('DomainPath');
}

/**
 * Get theme template
 *
 * @return string Theme template
 */
function aqualuxe_get_theme_template() {
    return wp_get_theme()->get('Template');
}

/**
 * Get theme status
 *
 * @return string Theme status
 */
function aqualuxe_get_theme_status() {
    return wp_get_theme()->get('Status');
}

/**
 * Get theme screenshot
 *
 * @return string Theme screenshot
 */
function aqualuxe_get_theme_screenshot() {
    return wp_get_theme()->get_screenshot();
}

/**
 * Get theme directory
 *
 * @return string Theme directory
 */
function aqualuxe_get_theme_directory() {
    return get_template_directory();
}

/**
 * Get theme directory uri
 *
 * @return string Theme directory URI
 */
function aqualuxe_get_theme_directory_uri() {
    return get_template_directory_uri();
}

/**
 * Get theme stylesheet directory
 *
 * @return string Theme stylesheet directory
 */
function aqualuxe_get_theme_stylesheet_directory() {
    return get_stylesheet_directory();
}

/**
 * Get theme stylesheet directory uri
 *
 * @return string Theme stylesheet directory URI
 */
function aqualuxe_get_theme_stylesheet_directory_uri() {
    return get_stylesheet_directory_uri();
}

/**
 * Get theme stylesheet
 *
 * @return string Theme stylesheet
 */
function aqualuxe_get_theme_stylesheet() {
    return get_stylesheet();
}

/**
 * Get theme template
 *
 * @return string Theme template
 */
function aqualuxe_get_theme_template_name() {
    return get_template();
}

/**
 * Get theme mods
 *
 * @return array Theme mods
 */
function aqualuxe_get_theme_mods() {
    return get_theme_mods();
}

/**
 * Get theme mod
 *
 * @param string $key     Theme mod key
 * @param mixed  $default Default value
 * @return mixed Theme mod value
 */
function aqualuxe_get_theme_mod($key, $default = '') {
    return get_theme_mod($key, $default);
}

/**
 * Set theme mod
 *
 * @param string $key   Theme mod key
 * @param mixed  $value Theme mod value
 */
function aqualuxe_set_theme_mod($key, $value) {
    set_theme_mod($key, $value);
}

/**
 * Remove theme mod
 *
 * @param string $key Theme mod key
 */
function aqualuxe_remove_theme_mod($key) {
    remove_theme_mod($key);
}

/**
 * Remove theme mods
 */
function aqualuxe_remove_theme_mods() {
    remove_theme_mods();
}

/**
 * Get theme support
 *
 * @param string $feature Feature
 * @return mixed Theme support
 */
function aqualuxe_get_theme_support($feature) {
    return get_theme_support($feature);
}

/**
 * Check if theme supports
 *
 * @param string $feature Feature
 * @return bool
 */
function aqualuxe_theme_supports($feature) {
    return current_theme_supports($feature);
}

/**
 * Get plugin path
 *
 * @param string $plugin Plugin
 * @return string Plugin path
 */
function aqualuxe_get_plugin_path($plugin) {
    return WP_PLUGIN_DIR . '/' . $plugin;
}

/**
 * Get plugin url
 *
 * @param string $plugin Plugin
 * @return string Plugin URL
 */
function aqualuxe_get_plugin_url($plugin) {
    return WP_PLUGIN_URL . '/' . $plugin;
}

/**
 * Check if plugin exists
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_plugin_exists($plugin) {
    return file_exists(aqualuxe_get_plugin_path($plugin));
}

/**
 * Check if plugin is active
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_is_plugin_active($plugin) {
    return in_array($plugin, (array) get_option('active_plugins', [])) || aqualuxe_is_plugin_active_for_network($plugin);
}

/**
 * Check if plugin is active for network
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_is_plugin_active_for_network($plugin) {
    if (!is_multisite()) {
        return false;
    }
    
    $plugins = get_site_option('active_sitewide_plugins');
    
    if (isset($plugins[$plugin])) {
        return true;
    }
    
    return false;
}

/**
 * Check if plugin is inactive
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_is_plugin_inactive($plugin) {
    return !aqualuxe_is_plugin_active($plugin);
}

/**
 * Activate plugin
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_activate_plugin($plugin) {
    if (!aqualuxe_plugin_exists($plugin)) {
        return false;
    }
    
    if (aqualuxe_is_plugin_active($plugin)) {
        return true;
    }
    
    $result = activate_plugin($plugin);
    
    return !is_wp_error($result);
}

/**
 * Deactivate plugin
 *
 * @param string $plugin Plugin
 * @return bool
 */
function aqualuxe_deactivate_plugin($plugin) {
    if (!aqualuxe_plugin_exists($plugin)) {
        return false;
    }
    
    if (aqualuxe_is_plugin_inactive($plugin)) {
        return true;
    }
    
    deactivate_plugins($plugin);
    
    return true;
}

/**
 * Get active plugins
 *
 * @return array Active plugins
 */
function aqualuxe_get_active_plugins() {
    return get_option('active_plugins', []);
}

/**
 * Get inactive plugins
 *
 * @return array Inactive plugins
 */
function aqualuxe_get_inactive_plugins() {
    $all_plugins = get_plugins();
    $active_plugins = aqualuxe_get_active_plugins();
    
    $inactive_plugins = [];
    
    foreach ($all_plugins as $plugin => $plugin_data) {
        if (!in_array($plugin, $active_plugins)) {
            $inactive_plugins[$plugin] = $plugin_data;
        }
    }
    
    return $inactive_plugins;
}

/**
 * Get all plugins
 *
 * @return array All plugins
 */
function aqualuxe_get_all_plugins() {
    return get_plugins();
}

/**
 * Get plugin data
 *
 * @param string $plugin Plugin
 * @return array Plugin data
 */
function aqualuxe_get_plugin_data($plugin) {
    if (!aqualuxe_plugin_exists($plugin)) {
        return [];
    }
    
    return get_plugin_data(aqualuxe_get_plugin_path($plugin));
}

/**
 * Get plugin version
 *
 * @param string $plugin Plugin
 * @return string Plugin version
 */
function aqualuxe_get_plugin_version($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Version'])) {
        return $plugin_data['Version'];
    }
    
    return '';
}

/**
 * Get plugin name
 *
 * @param string $plugin Plugin
 * @return string Plugin name
 */
function aqualuxe_get_plugin_name($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Name'])) {
        return $plugin_data['Name'];
    }
    
    return '';
}

/**
 * Get plugin description
 *
 * @param string $plugin Plugin
 * @return string Plugin description
 */
function aqualuxe_get_plugin_description($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Description'])) {
        return $plugin_data['Description'];
    }
    
    return '';
}

/**
 * Get plugin author
 *
 * @param string $plugin Plugin
 * @return string Plugin author
 */
function aqualuxe_get_plugin_author($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Author'])) {
        return $plugin_data['Author'];
    }
    
    return '';
}

/**
 * Get plugin author uri
 *
 * @param string $plugin Plugin
 * @return string Plugin author URI
 */
function aqualuxe_get_plugin_author_uri($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['AuthorURI'])) {
        return $plugin_data['AuthorURI'];
    }
    
    return '';
}

/**
 * Get plugin uri
 *
 * @param string $plugin Plugin
 * @return string Plugin URI
 */
function aqualuxe_get_plugin_uri($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['PluginURI'])) {
        return $plugin_data['PluginURI'];
    }
    
    return '';
}

/**
 * Get plugin text domain
 *
 * @param string $plugin Plugin
 * @return string Plugin text domain
 */
function aqualuxe_get_plugin_text_domain($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['TextDomain'])) {
        return $plugin_data['TextDomain'];
    }
    
    return '';
}

/**
 * Get plugin domain path
 *
 * @param string $plugin Plugin
 * @return string Plugin domain path
 */
function aqualuxe_get_plugin_domain_path($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['DomainPath'])) {
        return $plugin_data['DomainPath'];
    }
    
    return '';
}

/**
 * Get plugin network
 *
 * @param string $plugin Plugin
 * @return bool Plugin network
 */
function aqualuxe_get_plugin_network($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Network'])) {
        return $plugin_data['Network'];
    }
    
    return false;
}

/**
 * Get plugin requires wp
 *
 * @param string $plugin Plugin
 * @return string Plugin requires WP
 */
function aqualuxe_get_plugin_requires_wp($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['RequiresWP'])) {
        return $plugin_data['RequiresWP'];
    }
    
    return '';
}

/**
 * Get plugin requires php
 *
 * @param string $plugin Plugin
 * @return string Plugin requires PHP
 */
function aqualuxe_get_plugin_requires_php($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['RequiresPHP'])) {
        return $plugin_data['RequiresPHP'];
    }
    
    return '';
}

/**
 * Get plugin update uri
 *
 * @param string $plugin Plugin
 * @return string Plugin update URI
 */
function aqualuxe_get_plugin_update_uri($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['UpdateURI'])) {
        return $plugin_data['UpdateURI'];
    }
    
    return '';
}

/**
 * Get plugin title
 *
 * @param string $plugin Plugin
 * @return string Plugin title
 */
function aqualuxe_get_plugin_title($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['Title'])) {
        return $plugin_data['Title'];
    }
    
    return '';
}

/**
 * Get plugin author name
 *
 * @param string $plugin Plugin
 * @return string Plugin author name
 */
function aqualuxe_get_plugin_author_name($plugin) {
    $plugin_data = aqualuxe_get_plugin_data($plugin);
    
    if (isset($plugin_data['AuthorName'])) {
        return $plugin_data['AuthorName'];
    }
    
    return '';
}