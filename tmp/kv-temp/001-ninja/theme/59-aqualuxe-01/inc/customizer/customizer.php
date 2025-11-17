<?php
/**
 * AquaLuxe Customizer
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customize_register($wp_customize) {
    // Add custom controls
    require_once AQUALUXE_INC_DIR . 'customizer/controls/range-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/toggle-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/color-alpha-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/dimensions-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/sortable-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/radio-image-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/typography-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/separator-control.php';
    require_once AQUALUXE_INC_DIR . 'customizer/controls/heading-control.php';
    
    // Add custom sections
    require_once AQUALUXE_INC_DIR . 'customizer/sections/general.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/header.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/footer.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/typography.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/colors.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/blog.php';
    require_once AQUALUXE_INC_DIR . 'customizer/sections/social.php';
    
    // Add WooCommerce section if active
    if (aqualuxe_is_woocommerce_active()) {
        require_once AQUALUXE_INC_DIR . 'customizer/sections/woocommerce.php';
    }
    
    // Add modules sections
    if (aqualuxe_is_module_active('dark-mode')) {
        require_once AQUALUXE_INC_DIR . 'customizer/sections/dark-mode.php';
    }
    
    if (aqualuxe_is_module_active('multilingual')) {
        require_once AQUALUXE_INC_DIR . 'customizer/sections/multilingual.php';
    }
    
    // Register settings and controls
    aqualuxe_customizer_general_settings($wp_customize);
    aqualuxe_customizer_header_settings($wp_customize);
    aqualuxe_customizer_footer_settings($wp_customize);
    aqualuxe_customizer_typography_settings($wp_customize);
    aqualuxe_customizer_colors_settings($wp_customize);
    aqualuxe_customizer_blog_settings($wp_customize);
    aqualuxe_customizer_social_settings($wp_customize);
    
    // Register WooCommerce settings if active
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_customizer_woocommerce_settings($wp_customize);
    }
    
    // Register modules settings
    if (aqualuxe_is_module_active('dark-mode')) {
        aqualuxe_customizer_dark_mode_settings($wp_customize);
    }
    
    if (aqualuxe_is_module_active('multilingual')) {
        aqualuxe_customizer_multilingual_settings($wp_customize);
    }
    
    // Allow modules to register their own customizer settings
    do_action('aqualuxe_customize_register', $wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Enqueue customizer scripts
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    wp_enqueue_script('aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', ['jquery', 'customize-controls'], AQUALUXE_VERSION, true);
    wp_enqueue_style('aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'css/customizer-controls.css', [], AQUALUXE_VERSION);
    
    wp_localize_script('aqualuxe-customizer-controls', 'aqualuxeCustomizerControls', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe-customizer-nonce'),
    ]);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Enqueue customizer preview scripts
 */
function aqualuxe_customize_preview_init() {
    wp_enqueue_script('aqualuxe-customizer-preview', AQUALUXE_ASSETS_URI . 'js/customizer-preview.js', ['jquery', 'customize-preview'], AQUALUXE_VERSION, true);
    
    wp_localize_script('aqualuxe-customizer-preview', 'aqualuxeCustomizerPreview', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe-customizer-preview-nonce'),
    ]);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_init');

/**
 * Generate customizer CSS
 *
 * @return string
 */
function aqualuxe_get_customizer_css() {
    ob_start();
    
    // Get theme mods
    $primary_color = aqualuxe_get_theme_mod('primary_color', '#0077b6');
    $secondary_color = aqualuxe_get_theme_mod('secondary_color', '#00b4d8');
    $accent_color = aqualuxe_get_theme_mod('accent_color', '#48cae4');
    $dark_color = aqualuxe_get_theme_mod('dark_color', '#03045e');
    $light_color = aqualuxe_get_theme_mod('light_color', '#caf0f8');
    $gold_color = aqualuxe_get_theme_mod('gold_color', '#d4af37');
    
    $body_font_family = aqualuxe_get_theme_mod('body_font_family', 'Montserrat');
    $body_font_size = aqualuxe_get_theme_mod('body_font_size', '16');
    $body_line_height = aqualuxe_get_theme_mod('body_line_height', '1.6');
    $body_font_weight = aqualuxe_get_theme_mod('body_font_weight', '400');
    $body_text_transform = aqualuxe_get_theme_mod('body_text_transform', 'none');
    
    $heading_font_family = aqualuxe_get_theme_mod('heading_font_family', 'Playfair Display');
    $heading_font_weight = aqualuxe_get_theme_mod('heading_font_weight', '700');
    $heading_text_transform = aqualuxe_get_theme_mod('heading_text_transform', 'none');
    $heading_line_height = aqualuxe_get_theme_mod('heading_line_height', '1.2');
    
    $container_width = aqualuxe_get_theme_mod('container_width', '1140');
    $site_layout = aqualuxe_get_theme_mod('site_layout', 'wide');
    $site_boxed_width = aqualuxe_get_theme_mod('site_boxed_width', '1200');
    $site_boxed_shadow = aqualuxe_get_theme_mod('site_boxed_shadow', true);
    $site_boxed_border_radius = aqualuxe_get_theme_mod('site_boxed_border_radius', '10');
    
    $header_height = aqualuxe_get_theme_mod('header_height', '80');
    $header_background_color = aqualuxe_get_theme_mod('header_background_color', '#ffffff');
    $header_text_color = aqualuxe_get_theme_mod('header_text_color', '#000814');
    $header_link_color = aqualuxe_get_theme_mod('header_link_color', '#000814');
    $header_link_hover_color = aqualuxe_get_theme_mod('header_link_hover_color', $primary_color);
    $header_sticky = aqualuxe_get_theme_mod('header_sticky', true);
    $header_sticky_background_color = aqualuxe_get_theme_mod('header_sticky_background_color', '#ffffff');
    $header_sticky_shadow = aqualuxe_get_theme_mod('header_sticky_shadow', true);
    
    $footer_background_color = aqualuxe_get_theme_mod('footer_background_color', '#000814');
    $footer_text_color = aqualuxe_get_theme_mod('footer_text_color', '#ffffff');
    $footer_link_color = aqualuxe_get_theme_mod('footer_link_color', '#ffffff');
    $footer_link_hover_color = aqualuxe_get_theme_mod('footer_link_hover_color', $primary_color);
    $footer_widget_title_color = aqualuxe_get_theme_mod('footer_widget_title_color', '#ffffff');
    $footer_border_top = aqualuxe_get_theme_mod('footer_border_top', true);
    $footer_border_top_color = aqualuxe_get_theme_mod('footer_border_top_color', $primary_color);
    
    $button_background_color = aqualuxe_get_theme_mod('button_background_color', $primary_color);
    $button_text_color = aqualuxe_get_theme_mod('button_text_color', '#ffffff');
    $button_border_radius = aqualuxe_get_theme_mod('button_border_radius', '4');
    $button_hover_background_color = aqualuxe_get_theme_mod('button_hover_background_color', $dark_color);
    $button_hover_text_color = aqualuxe_get_theme_mod('button_hover_text_color', '#ffffff');
    
    // Root variables
    ?>
    :root {
        --aqualuxe-primary-color: <?php echo esc_attr($primary_color); ?>;
        --aqualuxe-secondary-color: <?php echo esc_attr($secondary_color); ?>;
        --aqualuxe-accent-color: <?php echo esc_attr($accent_color); ?>;
        --aqualuxe-dark-color: <?php echo esc_attr($dark_color); ?>;
        --aqualuxe-light-color: <?php echo esc_attr($light_color); ?>;
        --aqualuxe-gold-color: <?php echo esc_attr($gold_color); ?>;
        
        --aqualuxe-body-font-family: '<?php echo esc_attr($body_font_family); ?>', sans-serif;
        --aqualuxe-body-font-size: <?php echo esc_attr($body_font_size); ?>px;
        --aqualuxe-body-line-height: <?php echo esc_attr($body_line_height); ?>;
        --aqualuxe-body-font-weight: <?php echo esc_attr($body_font_weight); ?>;
        --aqualuxe-body-text-transform: <?php echo esc_attr($body_text_transform); ?>;
        
        --aqualuxe-heading-font-family: '<?php echo esc_attr($heading_font_family); ?>', serif;
        --aqualuxe-heading-font-weight: <?php echo esc_attr($heading_font_weight); ?>;
        --aqualuxe-heading-text-transform: <?php echo esc_attr($heading_text_transform); ?>;
        --aqualuxe-heading-line-height: <?php echo esc_attr($heading_line_height); ?>;
        
        --aqualuxe-container-width: <?php echo esc_attr($container_width); ?>px;
        --aqualuxe-site-boxed-width: <?php echo esc_attr($site_boxed_width); ?>px;
        --aqualuxe-site-boxed-border-radius: <?php echo esc_attr($site_boxed_border_radius); ?>px;
        
        --aqualuxe-header-height: <?php echo esc_attr($header_height); ?>px;
        --aqualuxe-header-background-color: <?php echo esc_attr($header_background_color); ?>;
        --aqualuxe-header-text-color: <?php echo esc_attr($header_text_color); ?>;
        --aqualuxe-header-link-color: <?php echo esc_attr($header_link_color); ?>;
        --aqualuxe-header-link-hover-color: <?php echo esc_attr($header_link_hover_color); ?>;
        --aqualuxe-header-sticky-background-color: <?php echo esc_attr($header_sticky_background_color); ?>;
        
        --aqualuxe-footer-background-color: <?php echo esc_attr($footer_background_color); ?>;
        --aqualuxe-footer-text-color: <?php echo esc_attr($footer_text_color); ?>;
        --aqualuxe-footer-link-color: <?php echo esc_attr($footer_link_color); ?>;
        --aqualuxe-footer-link-hover-color: <?php echo esc_attr($footer_link_hover_color); ?>;
        --aqualuxe-footer-widget-title-color: <?php echo esc_attr($footer_widget_title_color); ?>;
        --aqualuxe-footer-border-top-color: <?php echo esc_attr($footer_border_top_color); ?>;
        
        --aqualuxe-button-background-color: <?php echo esc_attr($button_background_color); ?>;
        --aqualuxe-button-text-color: <?php echo esc_attr($button_text_color); ?>;
        --aqualuxe-button-border-radius: <?php echo esc_attr($button_border_radius); ?>px;
        --aqualuxe-button-hover-background-color: <?php echo esc_attr($button_hover_background_color); ?>;
        --aqualuxe-button-hover-text-color: <?php echo esc_attr($button_hover_text_color); ?>;
    }
    
    /* Body styles */
    body {
        font-family: var(--aqualuxe-body-font-family);
        font-size: var(--aqualuxe-body-font-size);
        line-height: var(--aqualuxe-body-line-height);
        font-weight: var(--aqualuxe-body-font-weight);
        text-transform: var(--aqualuxe-body-text-transform);
    }
    
    /* Heading styles */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--aqualuxe-heading-font-family);
        font-weight: var(--aqualuxe-heading-font-weight);
        text-transform: var(--aqualuxe-heading-text-transform);
        line-height: var(--aqualuxe-heading-line-height);
    }
    
    /* Container styles */
    .container {
        max-width: var(--aqualuxe-container-width);
    }
    
    /* Site layout styles */
    <?php if ($site_layout === 'boxed') : ?>
    .site-boxed {
        max-width: var(--aqualuxe-site-boxed-width);
        margin-left: auto;
        margin-right: auto;
        border-radius: var(--aqualuxe-site-boxed-border-radius);
        overflow: hidden;
        <?php if ($site_boxed_shadow) : ?>
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        <?php endif; ?>
    }
    <?php endif; ?>
    
    /* Header styles */
    .site-header {
        height: var(--aqualuxe-header-height);
        background-color: var(--aqualuxe-header-background-color);
        color: var(--aqualuxe-header-text-color);
    }
    
    .site-header a {
        color: var(--aqualuxe-header-link-color);
    }
    
    .site-header a:hover,
    .site-header a:focus {
        color: var(--aqualuxe-header-link-hover-color);
    }
    
    <?php if ($header_sticky) : ?>
    .site-header.sticky {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
        background-color: var(--aqualuxe-header-sticky-background-color);
        <?php if ($header_sticky_shadow) : ?>
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        <?php endif; ?>
    }
    
    body.admin-bar .site-header.sticky {
        top: 32px;
    }
    
    @media screen and (max-width: 782px) {
        body.admin-bar .site-header.sticky {
            top: 46px;
        }
    }
    <?php endif; ?>
    
    /* Footer styles */
    .site-footer {
        background-color: var(--aqualuxe-footer-background-color);
        color: var(--aqualuxe-footer-text-color);
        <?php if ($footer_border_top) : ?>
        border-top: 3px solid var(--aqualuxe-footer-border-top-color);
        <?php endif; ?>
    }
    
    .site-footer a {
        color: var(--aqualuxe-footer-link-color);
    }
    
    .site-footer a:hover,
    .site-footer a:focus {
        color: var(--aqualuxe-footer-link-hover-color);
    }
    
    .site-footer .widget-title {
        color: var(--aqualuxe-footer-widget-title-color);
    }
    
    /* Button styles */
    .button,
    button,
    input[type="button"],
    input[type="reset"],
    input[type="submit"],
    .wp-block-button__link,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button {
        background-color: var(--aqualuxe-button-background-color);
        color: var(--aqualuxe-button-text-color);
        border-radius: var(--aqualuxe-button-border-radius);
    }
    
    .button:hover,
    button:hover,
    input[type="button"]:hover,
    input[type="reset"]:hover,
    input[type="submit"]:hover,
    .wp-block-button__link:hover,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover {
        background-color: var(--aqualuxe-button-hover-background-color);
        color: var(--aqualuxe-button-hover-text-color);
    }
    
    /* WooCommerce styles */
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
    .woocommerce ul.products li.product .price,
    .woocommerce div.product p.price,
    .woocommerce div.product span.price {
        color: var(--aqualuxe-primary-color);
    }
    
    .woocommerce span.onsale {
        background-color: var(--aqualuxe-accent-color);
    }
    
    .woocommerce #respond input#submit.alt,
    .woocommerce a.button.alt,
    .woocommerce button.button.alt,
    .woocommerce input.button.alt {
        background-color: var(--aqualuxe-primary-color);
    }
    
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover {
        background-color: var(--aqualuxe-dark-color);
    }
    <?php endif; ?>
    
    /* Dark mode styles */
    <?php if (aqualuxe_is_module_active('dark-mode')) : ?>
    .dark-mode {
        --aqualuxe-header-background-color: #121212;
        --aqualuxe-header-text-color: #ffffff;
        --aqualuxe-header-link-color: #ffffff;
        --aqualuxe-header-sticky-background-color: #121212;
    }
    <?php endif; ?>
    
    /* Custom CSS */
    <?php echo wp_kses_post(aqualuxe_get_theme_mod('custom_css', '')); ?>
    <?php
    
    return ob_get_clean();
}

/**
 * Output customizer CSS
 */
function aqualuxe_customizer_css() {
    $css = aqualuxe_get_customizer_css();
    
    if (!empty($css)) {
        wp_add_inline_style('aqualuxe-style', $css);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_customizer_css', 20);

/**
 * Sanitize checkbox
 *
 * @param bool $input Input
 * @return bool
 */
function aqualuxe_sanitize_checkbox($input) {
    return (isset($input) && true === $input) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Input
 * @param object $setting Setting
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize radio
 *
 * @param string $input Input
 * @param object $setting Setting
 * @return string
 */
function aqualuxe_sanitize_radio($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize number
 *
 * @param int $number Number
 * @param object $setting Setting
 * @return int
 */
function aqualuxe_sanitize_number($number, $setting) {
    $number = absint($number);
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    $min = isset($atts['min']) ? $atts['min'] : $number;
    $max = isset($atts['max']) ? $atts['max'] : $number;
    $step = isset($atts['step']) ? $atts['step'] : 1;
    
    return ($min <= $number && $number <= $max && is_int($number / $step) ? $number : $setting->default);
}

/**
 * Sanitize number range
 *
 * @param int $number Number
 * @param object $setting Setting
 * @return int
 */
function aqualuxe_sanitize_number_range($number, $setting) {
    $number = absint($number);
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    $min = isset($atts['min']) ? $atts['min'] : $number;
    $max = isset($atts['max']) ? $atts['max'] : $number;
    $step = isset($atts['step']) ? $atts['step'] : 1;
    
    return ($min <= $number && $number <= $max ? $number : $setting->default);
}

/**
 * Sanitize dimensions
 *
 * @param array $value Value
 * @return array
 */
function aqualuxe_sanitize_dimensions($value) {
    if (!is_array($value)) {
        return [];
    }
    
    foreach ($value as $key => $val) {
        $value[$key] = absint($val);
    }
    
    return $value;
}

/**
 * Sanitize sortable
 *
 * @param array $value Value
 * @param object $setting Setting
 * @return array
 */
function aqualuxe_sanitize_sortable($value, $setting) {
    if (!is_array($value)) {
        return [];
    }
    
    $default = $setting->default;
    
    foreach ($value as $key => $val) {
        if (!array_key_exists($val, $default)) {
            unset($value[$key]);
        }
    }
    
    return $value;
}

/**
 * Sanitize typography
 *
 * @param array $value Value
 * @return array
 */
function aqualuxe_sanitize_typography($value) {
    if (!is_array($value)) {
        return [];
    }
    
    $allowed_keys = [
        'font-family',
        'font-size',
        'font-weight',
        'line-height',
        'letter-spacing',
        'text-transform',
        'text-decoration',
        'font-style',
    ];
    
    foreach ($value as $key => $val) {
        if (!in_array($key, $allowed_keys)) {
            unset($value[$key]);
        }
    }
    
    return $value;
}

/**
 * Sanitize color alpha
 *
 * @param string $color Color
 * @return string
 */
function aqualuxe_sanitize_color_alpha($color) {
    if ('' === $color) {
        return '';
    }
    
    if (false === strpos($color, 'rgba')) {
        return sanitize_hex_color($color);
    }
    
    $color = str_replace(' ', '', $color);
    sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
    
    return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize image
 *
 * @param string $image Image
 * @param object $setting Setting
 * @return string
 */
function aqualuxe_sanitize_image($image, $setting) {
    $mimes = [
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon',
        'svg'          => 'image/svg+xml',
    ];
    
    // Return an array with file extension and mime_type.
    $file = wp_check_filetype($image, $mimes);
    
    // If $image has a valid mime_type, return it; otherwise, return the default.
    return ($file['ext'] ? $image : $setting->default);
}

/**
 * Sanitize html
 *
 * @param string $html HTML
 * @return string
 */
function aqualuxe_sanitize_html($html) {
    return wp_kses_post($html);
}

/**
 * Sanitize js
 *
 * @param string $js JS
 * @return string
 */
function aqualuxe_sanitize_js($js) {
    return $js;
}

/**
 * Sanitize css
 *
 * @param string $css CSS
 * @return string
 */
function aqualuxe_sanitize_css($css) {
    return $css;
}

/**
 * Sanitize url
 *
 * @param string $url URL
 * @return string
 */
function aqualuxe_sanitize_url($url) {
    return esc_url_raw($url);
}

/**
 * Sanitize email
 *
 * @param string $email Email
 * @return string
 */
function aqualuxe_sanitize_email($email) {
    return sanitize_email($email);
}

/**
 * Sanitize text
 *
 * @param string $text Text
 * @return string
 */
function aqualuxe_sanitize_text($text) {
    return sanitize_text_field($text);
}

/**
 * Sanitize textarea
 *
 * @param string $text Text
 * @return string
 */
function aqualuxe_sanitize_textarea($text) {
    return sanitize_textarea_field($text);
}

/**
 * Sanitize key
 *
 * @param string $key Key
 * @return string
 */
function aqualuxe_sanitize_key($key) {
    return sanitize_key($key);
}

/**
 * Sanitize choices
 *
 * @param string $input Input
 * @param object $setting Setting
 * @return string
 */
function aqualuxe_sanitize_choices($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize multi choices
 *
 * @param array $input Input
 * @param object $setting Setting
 * @return array
 */
function aqualuxe_sanitize_multi_choices($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    $input = (array) $input;
    $valid_input = [];
    
    foreach ($input as $value) {
        if (array_key_exists($value, $choices)) {
            $valid_input[] = $value;
        }
    }
    
    return $valid_input;
}