<?php
/**
 * Helper functions
 *
 * @package AquaLuxe
 */

/**
 * Get asset URL
 *
 * @param string $path Asset path
 * @return string
 */
function aqualuxe_asset_url($path) {
    return aqualuxe_asset($path);
}

/**
 * Get template part with variables
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 * @return void
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    if (!empty($args) && is_array($args)) {
        extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
    }

    $templates = [];
    $name = (string) $name;
    if ('' !== $name) {
        $templates[] = "{$slug}-{$name}.php";
    }

    $templates[] = "{$slug}.php";

    // Look in the theme first
    $template = locate_template($templates);

    // If not found in theme, look in modules
    if (!$template) {
        foreach ($templates as $template_file) {
            // Check in modules
            $module_dirs = glob(AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR);
            foreach ($module_dirs as $module_dir) {
                $module_template = $module_dir . '/templates/' . $template_file;
                if (file_exists($module_template)) {
                    $template = $module_template;
                    break 2;
                }
            }
        }
    }

    // Allow plugins/themes to override the default template
    $template = apply_filters('aqualuxe_get_template_part', $template, $slug, $name, $args);

    if ($template) {
        include $template;
    }
}

/**
 * Get module template part
 *
 * @param string $module Module name
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 * @return void
 */
function aqualuxe_get_module_template_part($module, $slug, $name = null, $args = []) {
    if (!empty($args) && is_array($args)) {
        extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
    }

    $templates = [];
    $name = (string) $name;
    if ('' !== $name) {
        $templates[] = "{$slug}-{$name}.php";
    }

    $templates[] = "{$slug}.php";

    $module_dir = AQUALUXE_MODULES_DIR . $module;
    $template = '';

    foreach ($templates as $template_file) {
        $module_template = $module_dir . '/templates/' . $template_file;
        if (file_exists($module_template)) {
            $template = $module_template;
            break;
        }
    }

    // Allow plugins/themes to override the default template
    $template = apply_filters('aqualuxe_get_module_template_part', $template, $module, $slug, $name, $args);

    if ($template) {
        include $template;
    }
}

/**
 * Get template HTML
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 * @return string
 */
function aqualuxe_get_template_html($slug, $name = null, $args = []) {
    ob_start();
    aqualuxe_get_template_part($slug, $name, $args);
    return ob_get_clean();
}

/**
 * Get module template HTML
 *
 * @param string $module Module name
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 * @return string
 */
function aqualuxe_get_module_template_html($module, $slug, $name = null, $args = []) {
    ob_start();
    aqualuxe_get_module_template_part($module, $slug, $name, $args);
    return ob_get_clean();
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 * @return string
 */
function aqualuxe_get_icon($icon, $args = []) {
    $defaults = [
        'class' => '',
        'width' => 24,
        'height' => 24,
        'aria-hidden' => 'true',
        'role' => 'img',
        'focusable' => 'false',
    ];

    $args = wp_parse_args($args, $defaults);

    $svg = '';
    $icon_path = AQUALUXE_DIR . 'assets/src/svg/' . $icon . '.svg';

    if (file_exists($icon_path)) {
        $svg = file_get_contents($icon_path); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $svg = str_replace('<svg', '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" aria-hidden="' . esc_attr($args['aria-hidden']) . '" role="' . esc_attr($args['role']) . '" focusable="' . esc_attr($args['focusable']) . '"', $svg);
    }

    return $svg;
}

/**
 * Print SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 * @return void
 */
function aqualuxe_icon($icon, $args = []) {
    echo aqualuxe_get_icon($icon, $args); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get image URL
 *
 * @param int    $attachment_id Attachment ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_get_image_url($attachment_id, $size = 'full') {
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image ? $image[0] : '';
}

/**
 * Get image alt
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_alt($attachment_id) {
    return get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
}

/**
 * Get image caption
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_caption($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_excerpt : '';
}

/**
 * Get image description
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_description($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_content : '';
}

/**
 * Get image title
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_title($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_title : '';
}

/**
 * Get image data
 *
 * @param int    $attachment_id Attachment ID
 * @param string $size Image size
 * @return array
 */
function aqualuxe_get_image_data($attachment_id, $size = 'full') {
    return [
        'url' => aqualuxe_get_image_url($attachment_id, $size),
        'alt' => aqualuxe_get_image_alt($attachment_id),
        'caption' => aqualuxe_get_image_caption($attachment_id),
        'description' => aqualuxe_get_image_description($attachment_id),
        'title' => aqualuxe_get_image_title($attachment_id),
    ];
}

/**
 * Get post thumbnail data
 *
 * @param int    $post_id Post ID
 * @param string $size Image size
 * @return array
 */
function aqualuxe_get_post_thumbnail_data($post_id = null, $size = 'full') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $thumbnail_id = get_post_thumbnail_id($post_id);
    return aqualuxe_get_image_data($thumbnail_id, $size);
}

/**
 * Get option
 *
 * @param string $option Option name
 * @param mixed  $default Default value
 * @return mixed
 */
function aqualuxe_get_option($option, $default = '') {
    $options = get_option('aqualuxe_options', []);
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Get module option
 *
 * @param string $module Module name
 * @param string $option Option name
 * @param mixed  $default Default value
 * @return mixed
 */
function aqualuxe_get_module_option($module, $option, $default = '') {
    $options = get_option('aqualuxe_module_' . $module, []);
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_enabled() {
    return aqualuxe_is_module_active('dark-mode');
}

/**
 * Check if multilingual is enabled
 *
 * @return bool
 */
function aqualuxe_is_multilingual_enabled() {
    return aqualuxe_is_module_active('multilingual');
}

/**
 * Check if multicurrency is enabled
 *
 * @return bool
 */
function aqualuxe_is_multicurrency_enabled() {
    return aqualuxe_is_module_active('multicurrency');
}

/**
 * Check if WooCommerce is enabled
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_enabled() {
    return class_exists('WooCommerce') && aqualuxe_is_module_active('woocommerce');
}

/**
 * Check if wishlist is enabled
 *
 * @return bool
 */
function aqualuxe_is_wishlist_enabled() {
    return aqualuxe_is_module_active('wishlist');
}

/**
 * Check if bookings is enabled
 *
 * @return bool
 */
function aqualuxe_is_bookings_enabled() {
    return aqualuxe_is_module_active('bookings');
}

/**
 * Check if events is enabled
 *
 * @return bool
 */
function aqualuxe_is_events_enabled() {
    return aqualuxe_is_module_active('events');
}

/**
 * Check if subscriptions is enabled
 *
 * @return bool
 */
function aqualuxe_is_subscriptions_enabled() {
    return aqualuxe_is_module_active('subscriptions');
}

/**
 * Check if auctions is enabled
 *
 * @return bool
 */
function aqualuxe_is_auctions_enabled() {
    return aqualuxe_is_module_active('auctions');
}

/**
 * Check if wholesale is enabled
 *
 * @return bool
 */
function aqualuxe_is_wholesale_enabled() {
    return aqualuxe_is_module_active('wholesale');
}

/**
 * Check if affiliate is enabled
 *
 * @return bool
 */
function aqualuxe_is_affiliate_enabled() {
    return aqualuxe_is_module_active('affiliate');
}

/**
 * Check if franchise is enabled
 *
 * @return bool
 */
function aqualuxe_is_franchise_enabled() {
    return aqualuxe_is_module_active('franchise');
}

/**
 * Check if sustainability is enabled
 *
 * @return bool
 */
function aqualuxe_is_sustainability_enabled() {
    return aqualuxe_is_module_active('sustainability');
}

/**
 * Check if services is enabled
 *
 * @return bool
 */
function aqualuxe_is_services_enabled() {
    return aqualuxe_is_module_active('services');
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    if (aqualuxe_is_multilingual_enabled()) {
        $module = aqualuxe_get_module('multilingual');
        if ($module) {
            return $module->get_current_language();
        }
    }

    return get_locale();
}

/**
 * Get current currency
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    if (aqualuxe_is_multicurrency_enabled()) {
        $module = aqualuxe_get_module('multicurrency');
        if ($module) {
            return $module->get_current_currency();
        }
    }

    return get_woocommerce_currency();
}

/**
 * Get languages
 *
 * @return array
 */
function aqualuxe_get_languages() {
    if (aqualuxe_is_multilingual_enabled()) {
        $module = aqualuxe_get_module('multilingual');
        if ($module) {
            return $module->get_languages();
        }
    }

    return [
        get_locale() => [
            'name' => esc_html__('Default', 'aqualuxe'),
            'flag' => '',
            'url' => home_url('/'),
        ],
    ];
}

/**
 * Get currencies
 *
 * @return array
 */
function aqualuxe_get_currencies() {
    if (aqualuxe_is_multicurrency_enabled()) {
        $module = aqualuxe_get_module('multicurrency');
        if ($module) {
            return $module->get_currencies();
        }
    }

    return [
        get_woocommerce_currency() => [
            'name' => get_woocommerce_currency(),
            'symbol' => get_woocommerce_currency_symbol(),
            'rate' => 1,
        ],
    ];
}

/**
 * Get language name
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_name($language) {
    $languages = aqualuxe_get_languages();
    return isset($languages[$language]['name']) ? $languages[$language]['name'] : $language;
}

/**
 * Get language flag
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_flag($language) {
    $languages = aqualuxe_get_languages();
    return isset($languages[$language]['flag']) ? $languages[$language]['flag'] : '';
}

/**
 * Get language URL
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_url($language) {
    $languages = aqualuxe_get_languages();
    return isset($languages[$language]['url']) ? $languages[$language]['url'] : home_url('/');
}

/**
 * Get currency name
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_get_currency_name($currency) {
    $currencies = aqualuxe_get_currencies();
    return isset($currencies[$currency]['name']) ? $currencies[$currency]['name'] : $currency;
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_get_currency_symbol($currency) {
    $currencies = aqualuxe_get_currencies();
    return isset($currencies[$currency]['symbol']) ? $currencies[$currency]['symbol'] : $currency;
}

/**
 * Get currency rate
 *
 * @param string $currency Currency code
 * @return float
 */
function aqualuxe_get_currency_rate($currency) {
    $currencies = aqualuxe_get_currencies();
    return isset($currencies[$currency]['rate']) ? $currencies[$currency]['rate'] : 1;
}

/**
 * Convert price
 *
 * @param float  $price Price
 * @param string $from_currency From currency
 * @param string $to_currency To currency
 * @return float
 */
function aqualuxe_convert_price($price, $from_currency = '', $to_currency = '') {
    if (!$from_currency) {
        $from_currency = get_woocommerce_currency();
    }

    if (!$to_currency) {
        $to_currency = aqualuxe_get_current_currency();
    }

    if ($from_currency === $to_currency) {
        return $price;
    }

    $from_rate = aqualuxe_get_currency_rate($from_currency);
    $to_rate = aqualuxe_get_currency_rate($to_currency);

    return $price * ($to_rate / $from_rate);
}

/**
 * Format price
 *
 * @param float  $price Price
 * @param string $currency Currency
 * @return string
 */
function aqualuxe_format_price($price, $currency = '') {
    if (!$currency) {
        $currency = aqualuxe_get_current_currency();
    }

    if (function_exists('wc_price')) {
        return wc_price($price, ['currency' => $currency]);
    }

    return $currency . ' ' . number_format($price, 2);
}

/**
 * Get translated post ID
 *
 * @param int    $post_id Post ID
 * @param string $language Language
 * @return int
 */
function aqualuxe_get_translated_post_id($post_id, $language = '') {
    if (!$language) {
        $language = aqualuxe_get_current_language();
    }

    if (aqualuxe_is_multilingual_enabled()) {
        $module = aqualuxe_get_module('multilingual');
        if ($module) {
            return $module->get_translated_post_id($post_id, $language);
        }
    }

    return $post_id;
}

/**
 * Get translated term ID
 *
 * @param int    $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @param string $language Language
 * @return int
 */
function aqualuxe_get_translated_term_id($term_id, $taxonomy, $language = '') {
    if (!$language) {
        $language = aqualuxe_get_current_language();
    }

    if (aqualuxe_is_multilingual_enabled()) {
        $module = aqualuxe_get_module('multilingual');
        if ($module) {
            return $module->get_translated_term_id($term_id, $taxonomy, $language);
        }
    }

    return $term_id;
}

/**
 * Get translated URL
 *
 * @param string $url URL
 * @param string $language Language
 * @return string
 */
function aqualuxe_get_translated_url($url, $language = '') {
    if (!$language) {
        $language = aqualuxe_get_current_language();
    }

    if (aqualuxe_is_multilingual_enabled()) {
        $module = aqualuxe_get_module('multilingual');
        if ($module) {
            return $module->get_translated_url($url, $language);
        }
    }

    return $url;
}

/**
 * Get module URL
 *
 * @param string $module Module name
 * @param string $path Path
 * @return string
 */
function aqualuxe_get_module_url($module, $path = '') {
    $url = AQUALUXE_URI . 'modules/' . $module . '/';

    if ($path) {
        $url .= ltrim($path, '/');
    }

    return $url;
}

/**
 * Get module directory
 *
 * @param string $module Module name
 * @param string $path Path
 * @return string
 */
function aqualuxe_get_module_dir($module, $path = '') {
    $dir = AQUALUXE_MODULES_DIR . $module . '/';

    if ($path) {
        $dir .= ltrim($path, '/');
    }

    return $dir;
}

/**
 * Check if module exists
 *
 * @param string $module Module name
 * @return bool
 */
function aqualuxe_module_exists($module) {
    return is_dir(AQUALUXE_MODULES_DIR . $module);
}

/**
 * Get active modules
 *
 * @return array
 */
function aqualuxe_get_active_modules() {
    $active_modules = [];

    $module_dirs = glob(AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR);
    foreach ($module_dirs as $module_dir) {
        $module_name = basename($module_dir);
        if (aqualuxe_is_module_active($module_name)) {
            $active_modules[] = $module_name;
        }
    }

    return $active_modules;
}

/**
 * Get all modules
 *
 * @return array
 */
function aqualuxe_get_all_modules() {
    $modules = [];

    $module_dirs = glob(AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR);
    foreach ($module_dirs as $module_dir) {
        $module_name = basename($module_dir);
        $modules[] = $module_name;
    }

    return $modules;
}

/**
 * Get module info
 *
 * @param string $module Module name
 * @return array
 */
function aqualuxe_get_module_info($module) {
    $info = [
        'name' => $module,
        'title' => ucfirst(str_replace('-', ' ', $module)),
        'description' => '',
        'version' => '1.0.0',
        'author' => 'AquaLuxe',
        'author_uri' => 'https://aqualuxe.com',
        'requires' => [],
        'conflicts' => [],
    ];

    $info_file = AQUALUXE_MODULES_DIR . $module . '/info.php';
    if (file_exists($info_file)) {
        $module_info = include $info_file;
        if (is_array($module_info)) {
            $info = array_merge($info, $module_info);
        }
    }

    return $info;
}

/**
 * Check if a module is active
 *
 * @param string $module_name Module name
 * @return bool
 */
function aqualuxe_is_module_active($module_name) {
    return aqualuxe_init()->is_module_active($module_name);
}

/**
 * Get a module instance
 *
 * @param string $module_name Module name
 * @return mixed|null
 */
function aqualuxe_get_module($module_name) {
    return aqualuxe_init()->get_module($module_name);
}

/**
 * Get module settings URL
 *
 * @param string $module Module name
 * @return string
 */
function aqualuxe_get_module_settings_url($module) {
    return admin_url('admin.php?page=aqualuxe-modules&module=' . $module);
}