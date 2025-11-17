<?php
/**
 * Helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if a plugin is active
 *
 * @param string $plugin Plugin path/name.
 * @return bool
 */
function aqualuxe_is_plugin_active($plugin) {
    if (!function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    return is_plugin_active($plugin);
}

/**
 * Get theme option with default fallback
 *
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Get current page layout
 *
 * @return string
 */
function aqualuxe_get_page_layout() {
    // Default layout
    $layout = 'right-sidebar';
    
    // Check if it's a WooCommerce page
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $layout = aqualuxe_get_option('aqualuxe_shop_layout', 'right-sidebar');
        } elseif (is_product()) {
            $layout = aqualuxe_get_option('aqualuxe_product_layout', 'no-sidebar');
        }
    }
    
    // Check if it's a blog page
    if (is_home() || is_archive() || is_search() || is_singular('post')) {
        $layout = aqualuxe_get_option('aqualuxe_blog_layout', 'right-sidebar');
    }
    
    // Check for page-specific layout
    if (is_page() || is_single()) {
        $page_layout = get_post_meta(get_the_ID(), 'aqualuxe_page_layout', true);
        
        if (!empty($page_layout) && $page_layout !== 'default') {
            $layout = $page_layout;
        }
    }
    
    return apply_filters('aqualuxe_page_layout', $layout);
}

/**
 * Check if the current page has a sidebar
 *
 * @return bool
 */
function aqualuxe_has_sidebar() {
    $layout = aqualuxe_get_page_layout();
    
    return $layout === 'left-sidebar' || $layout === 'right-sidebar';
}

/**
 * Get sidebar position
 *
 * @return string
 */
function aqualuxe_get_sidebar_position() {
    $layout = aqualuxe_get_page_layout();
    
    return $layout === 'left-sidebar' ? 'left' : 'right';
}

/**
 * Get content column classes
 *
 * @return string
 */
function aqualuxe_get_content_column_classes() {
    $classes = 'content-area';
    
    if (aqualuxe_has_sidebar()) {
        $classes .= ' col-lg-9';
        
        if (aqualuxe_get_sidebar_position() === 'left') {
            $classes .= ' order-lg-2';
        }
    } else {
        $classes .= ' col-lg-12';
    }
    
    return apply_filters('aqualuxe_content_column_classes', $classes);
}

/**
 * Get sidebar column classes
 *
 * @return string
 */
function aqualuxe_get_sidebar_column_classes() {
    $classes = 'sidebar-area';
    
    $classes .= ' col-lg-3';
    
    if (aqualuxe_get_sidebar_position() === 'left') {
        $classes .= ' order-lg-1';
    }
    
    return apply_filters('aqualuxe_sidebar_column_classes', $classes);
}

/**
 * Get Google Fonts URL
 *
 * @return string
 */
function aqualuxe_get_google_fonts_url() {
    $fonts_url = '';
    $fonts = array();
    $subsets = 'latin,latin-ext';
    
    // Get fonts from theme options
    $body_font = aqualuxe_get_option('aqualuxe_body_font', 'Inter');
    $heading_font = aqualuxe_get_option('aqualuxe_heading_font', 'Playfair Display');
    
    // Add body font
    if (!empty($body_font)) {
        $fonts[] = $body_font . ':400,500,600,700';
    }
    
    // Add heading font
    if (!empty($heading_font) && $heading_font !== $body_font) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }
    
    if ($fonts) {
        $fonts_url = add_query_arg(
            array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
                'display' => 'swap',
            ),
            'https://fonts.googleapis.com/css'
        );
    }
    
    return $fonts_url;
}

/**
 * Get image sizes
 *
 * @return array
 */
function aqualuxe_get_image_sizes() {
    global $_wp_additional_image_sizes;
    
    $sizes = array();
    
    foreach (get_intermediate_image_sizes() as $size) {
        if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'), true)) {
            $sizes[$size] = array(
                'width' => get_option("{$size}_size_w"),
                'height' => get_option("{$size}_size_h"),
                'crop' => (bool) get_option("{$size}_crop"),
            );
        } elseif (isset($_wp_additional_image_sizes[$size])) {
            $sizes[$size] = array(
                'width' => $_wp_additional_image_sizes[$size]['width'],
                'height' => $_wp_additional_image_sizes[$size]['height'],
                'crop' => $_wp_additional_image_sizes[$size]['crop'],
            );
        }
    }
    
    return $sizes;
}

/**
 * Get image size dimensions
 *
 * @param string $size Image size.
 * @return array|bool
 */
function aqualuxe_get_image_size($size) {
    $sizes = aqualuxe_get_image_sizes();
    
    if (isset($sizes[$size])) {
        return $sizes[$size];
    }
    
    return false;
}

/**
 * Get responsive image attributes
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param array  $attr Additional attributes.
 * @return string
 */
function aqualuxe_get_responsive_image_attributes($attachment_id, $size = 'full', $attr = array()) {
    if (!$attachment_id) {
        return '';
    }
    
    $image = wp_get_attachment_image_src($attachment_id, $size);
    
    if (!$image) {
        return '';
    }
    
    $image_meta = wp_get_attachment_metadata($attachment_id);
    
    if (!$image_meta) {
        return '';
    }
    
    $width = $image[1];
    $height = $image[2];
    
    if (is_array($image_meta)) {
        $sizes_array = array();
        $srcset_array = array();
        
        foreach (aqualuxe_get_image_sizes() as $size_name => $size_data) {
            $intermediate = image_get_intermediate_size($attachment_id, $size_name);
            
            if ($intermediate) {
                $srcset_array[] = $intermediate['url'] . ' ' . $intermediate['width'] . 'w';
                
                if ($size_data['width'] <= $width) {
                    $sizes_array[$size_data['width']] = $size_data['width'] . 'px';
                }
            }
        }
        
        if (!empty($srcset_array)) {
            $attr['srcset'] = implode(', ', $srcset_array);
        }
        
        if (!empty($sizes_array)) {
            ksort($sizes_array);
            $sizes = '(max-width: ' . array_key_last($sizes_array) . 'px) 100vw, ' . array_key_last($sizes_array) . 'px';
            $attr['sizes'] = $sizes;
        }
    }
    
    $attributes = '';
    
    foreach ($attr as $name => $value) {
        $attributes .= $name . '="' . esc_attr($value) . '" ';
    }
    
    return $attributes;
}

/**
 * Get responsive image
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param array  $attr Additional attributes.
 * @return string
 */
function aqualuxe_get_responsive_image($attachment_id, $size = 'full', $attr = array()) {
    if (!$attachment_id) {
        return '';
    }
    
    $html = '';
    $image = wp_get_attachment_image_src($attachment_id, $size);
    
    if ($image) {
        $attr['src'] = $image[0];
        $attr['width'] = $image[1];
        $attr['height'] = $image[2];
        
        if (!isset($attr['alt'])) {
            $attr['alt'] = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        }
        
        $attr = aqualuxe_get_responsive_image_attributes($attachment_id, $size, $attr);
        
        $html = '<img ' . $attr . '>';
    }
    
    return $html;
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 * @return string
 */
function aqualuxe_get_svg_icon($icon, $args = array()) {
    // SVG icons directory
    $icons_dir = AQUALUXE_DIR . '/assets/dist/images/icons';
    
    // Check if icon exists
    if (!file_exists($icons_dir . '/' . $icon . '.svg')) {
        return '';
    }
    
    // Default arguments
    $defaults = array(
        'class' => 'svg-icon',
        'width' => 24,
        'height' => 24,
        'aria-hidden' => 'true',
        'role' => 'img',
    );
    
    // Parse arguments
    $args = wp_parse_args($args, $defaults);
    
    // Get SVG content
    $svg = file_get_contents($icons_dir . '/' . $icon . '.svg');
    
    // Add attributes
    $attributes = '';
    
    foreach ($args as $key => $value) {
        if ($key === 'class') {
            // Add default class
            $value .= ' svg-icon-' . $icon;
        }
        
        $attributes .= $key . '="' . esc_attr($value) . '" ';
    }
    
    // Add attributes to SVG
    $svg = str_replace('<svg ', '<svg ' . $attributes, $svg);
    
    return $svg;
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 */
function aqualuxe_svg_icon($icon, $args = array()) {
    echo aqualuxe_get_svg_icon($icon, $args);
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_get_currency_symbol($currency = '') {
    if (empty($currency)) {
        $currency = get_woocommerce_currency();
    }
    
    $symbols = array(
        'AED' => 'د.إ',
        'AFN' => '؋',
        'ALL' => 'L',
        'AMD' => '֏',
        'ANG' => 'ƒ',
        'AOA' => 'Kz',
        'ARS' => '$',
        'AUD' => '$',
        'AWG' => 'ƒ',
        'AZN' => '₼',
        'BAM' => 'KM',
        'BBD' => '$',
        'BDT' => '৳',
        'BGN' => 'лв',
        'BHD' => '.د.ب',
        'BIF' => 'Fr',
        'BMD' => '$',
        'BND' => '$',
        'BOB' => 'Bs.',
        'BRL' => 'R$',
        'BSD' => '$',
        'BTC' => '₿',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYN' => 'Br',
        'BYR' => 'Br',
        'BZD' => '$',
        'CAD' => '$',
        'CDF' => 'Fr',
        'CHF' => 'Fr',
        'CLP' => '$',
        'CNY' => '¥',
        'COP' => '$',
        'CRC' => '₡',
        'CUC' => '$',
        'CUP' => '$',
        'CVE' => '$',
        'CZK' => 'Kč',
        'DJF' => 'Fr',
        'DKK' => 'kr',
        'DOP' => 'RD$',
        'DZD' => 'د.ج',
        'EGP' => 'ج.م',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '€',
        'FJD' => '$',
        'FKP' => '£',
        'GBP' => '£',
        'GEL' => '₾',
        'GGP' => '£',
        'GHS' => '₵',
        'GIP' => '£',
        'GMD' => 'D',
        'GNF' => 'Fr',
        'GTQ' => 'Q',
        'GYD' => '$',
        'HKD' => '$',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => 'Ft',
        'IDR' => 'Rp',
        'ILS' => '₪',
        'IMP' => '£',
        'INR' => '₹',
        'IQD' => 'ع.د',
        'IRR' => '﷼',
        'ISK' => 'kr',
        'JEP' => '£',
        'JMD' => '$',
        'JOD' => 'د.ا',
        'JPY' => '¥',
        'KES' => 'Sh',
        'KGS' => 'с',
        'KHR' => '៛',
        'KMF' => 'Fr',
        'KPW' => '₩',
        'KRW' => '₩',
        'KWD' => 'د.ك',
        'KYD' => '$',
        'KZT' => '₸',
        'LAK' => '₭',
        'LBP' => 'ل.ل',
        'LKR' => 'රු',
        'LRD' => '$',
        'LSL' => 'L',
        'LYD' => 'ل.د',
        'MAD' => 'د.م.',
        'MDL' => 'L',
        'MGA' => 'Ar',
        'MKD' => 'ден',
        'MMK' => 'Ks',
        'MNT' => '₮',
        'MOP' => 'P',
        'MRO' => 'UM',
        'MRU' => 'UM',
        'MUR' => '₨',
        'MVR' => '.ރ',
        'MWK' => 'MK',
        'MXN' => '$',
        'MYR' => 'RM',
        'MZN' => 'MT',
        'NAD' => '$',
        'NGN' => '₦',
        'NIO' => 'C$',
        'NOK' => 'kr',
        'NPR' => '₨',
        'NZD' => '$',
        'OMR' => 'ر.ع.',
        'PAB' => 'B/.',
        'PEN' => 'S/',
        'PGK' => 'K',
        'PHP' => '₱',
        'PKR' => '₨',
        'PLN' => 'zł',
        'PRB' => 'р.',
        'PYG' => '₲',
        'QAR' => 'ر.ق',
        'RON' => 'lei',
        'RSD' => 'дин',
        'RUB' => '₽',
        'RWF' => 'Fr',
        'SAR' => 'ر.س',
        'SBD' => '$',
        'SCR' => '₨',
        'SDG' => 'ج.س.',
        'SEK' => 'kr',
        'SGD' => '$',
        'SHP' => '£',
        'SLL' => 'Le',
        'SOS' => 'Sh',
        'SRD' => '$',
        'SSP' => '£',
        'STD' => 'Db',
        'STN' => 'Db',
        'SYP' => 'ل.س',
        'SZL' => 'L',
        'THB' => '฿',
        'TJS' => 'ЅМ',
        'TMT' => 'm',
        'TND' => 'د.ت',
        'TOP' => 'T$',
        'TRY' => '₺',
        'TTD' => '$',
        'TVD' => '$',
        'TWD' => 'NT$',
        'TZS' => 'Sh',
        'UAH' => '₴',
        'UGX' => 'Sh',
        'USD' => '$',
        'UYU' => '$',
        'UZS' => 'so\'m',
        'VEF' => 'Bs',
        'VES' => 'Bs.S',
        'VND' => '₫',
        'VUV' => 'Vt',
        'WST' => 'T',
        'XAF' => 'Fr',
        'XCD' => '$',
        'XOF' => 'Fr',
        'XPF' => 'Fr',
        'YER' => '﷼',
        'ZAR' => 'R',
        'ZMW' => 'ZK',
    );
    
    return isset($symbols[$currency]) ? $symbols[$currency] : '';
}

/**
 * Format price
 *
 * @param float  $price Price.
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_format_price($price, $currency = '') {
    if (aqualuxe_is_woocommerce_active()) {
        return wc_price($price, array('currency' => $currency));
    }
    
    if (empty($currency)) {
        $currency = 'USD';
    }
    
    $symbol = aqualuxe_get_currency_symbol($currency);
    
    return $symbol . number_format($price, 2);
}

/**
 * Get post views
 *
 * @param int $post_id Post ID.
 * @return int
 */
function aqualuxe_get_post_views($post_id) {
    $count_key = 'aqualuxe_post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count === '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return 0;
    }
    
    return (int) $count;
}

/**
 * Set post views
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_set_post_views($post_id) {
    $count_key = 'aqualuxe_post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count === '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

/**
 * Track post views
 */
function aqualuxe_track_post_views() {
    if (is_singular('post')) {
        aqualuxe_set_post_views(get_the_ID());
    }
}
add_action('wp_head', 'aqualuxe_track_post_views');

/**
 * Get reading time
 *
 * @param string $content Post content.
 * @return int
 */
function aqualuxe_get_reading_time($content = '') {
    if (empty($content)) {
        $content = get_the_content();
    }
    
    // Count words
    $word_count = str_word_count(strip_tags($content));
    
    // Calculate reading time (assuming 200 words per minute)
    $reading_time = ceil($word_count / 200);
    
    // Minimum reading time is 1 minute
    return max(1, $reading_time);
}

/**
 * Get estimated reading time
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_get_estimated_reading_time($content = '') {
    $reading_time = aqualuxe_get_reading_time($content);
    
    /* translators: %d: number of minutes */
    return sprintf(_n('%d min read', '%d min read', $reading_time, 'aqualuxe'), $reading_time);
}

/**
 * Get post share URL
 *
 * @param string $network Social network.
 * @param int    $post_id Post ID.
 * @return string
 */
function aqualuxe_get_post_share_url($network, $post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    $thumbnail = get_the_post_thumbnail_url($post_id, 'full');
    
    switch ($network) {
        case 'facebook':
            return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
        
        case 'twitter':
            return 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title);
        
        case 'linkedin':
            return 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . urlencode($title);
        
        case 'pinterest':
            return 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&media=' . urlencode($thumbnail) . '&description=' . urlencode($title);
        
        case 'email':
            return 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($url);
        
        default:
            return '';
    }
}

/**
 * Get social share buttons
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_social_share_buttons($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $networks = array(
        'facebook' => array(
            'label' => __('Share on Facebook', 'aqualuxe'),
            'icon' => 'fab fa-facebook-f',
        ),
        'twitter' => array(
            'label' => __('Share on Twitter', 'aqualuxe'),
            'icon' => 'fab fa-twitter',
        ),
        'linkedin' => array(
            'label' => __('Share on LinkedIn', 'aqualuxe'),
            'icon' => 'fab fa-linkedin-in',
        ),
        'pinterest' => array(
            'label' => __('Share on Pinterest', 'aqualuxe'),
            'icon' => 'fab fa-pinterest-p',
        ),
        'email' => array(
            'label' => __('Share via Email', 'aqualuxe'),
            'icon' => 'fas fa-envelope',
        ),
    );
    
    $output = '<div class="social-share-buttons">';
    
    foreach ($networks as $network => $data) {
        $url = aqualuxe_get_post_share_url($network, $post_id);
        
        $output .= '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="social-share-button ' . esc_attr($network) . '">';
        $output .= '<i class="' . esc_attr($data['icon']) . '" aria-hidden="true"></i>';
        $output .= '<span class="screen-reader-text">' . esc_html($data['label']) . '</span>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Display social share buttons
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_social_share_buttons($post_id = 0) {
    echo aqualuxe_get_social_share_buttons($post_id);
}

/**
 * Get post thumbnail with fallback
 *
 * @param string $size Thumbnail size.
 * @param int    $post_id Post ID.
 * @return string
 */
function aqualuxe_get_post_thumbnail($size = 'post-thumbnail', $post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size);
    }
    
    // Get default image from theme options
    $default_image_id = aqualuxe_get_option('aqualuxe_default_thumbnail', '');
    
    if ($default_image_id) {
        return wp_get_attachment_image($default_image_id, $size);
    }
    
    // Return placeholder image
    $size_data = aqualuxe_get_image_size($size);
    
    if ($size_data) {
        $width = $size_data['width'];
        $height = $size_data['height'];
    } else {
        $width = 800;
        $height = 600;
    }
    
    return '<img src="https://via.placeholder.com/' . $width . 'x' . $height . '" alt="' . esc_attr(get_the_title($post_id)) . '" class="wp-post-image">';
}

/**
 * Display post thumbnail with fallback
 *
 * @param string $size Thumbnail size.
 * @param int    $post_id Post ID.
 */
function aqualuxe_post_thumbnail($size = 'post-thumbnail', $post_id = 0) {
    echo aqualuxe_get_post_thumbnail($size, $post_id);
}

/**
 * Get theme version
 *
 * @return string
 */
function aqualuxe_get_theme_version() {
    $theme = wp_get_theme();
    
    return $theme->get('Version');
}

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get module status
 *
 * @param string $module Module name.
 * @return bool
 */
function aqualuxe_is_module_active($module) {
    $modules = get_option('aqualuxe_active_modules', array());
    
    return isset($modules[$module]) && $modules[$module];
}

/**
 * Get active modules
 *
 * @return array
 */
function aqualuxe_get_active_modules() {
    return get_option('aqualuxe_active_modules', array());
}

/**
 * Activate module
 *
 * @param string $module Module name.
 * @return bool
 */
function aqualuxe_activate_module($module) {
    $modules = get_option('aqualuxe_active_modules', array());
    $modules[$module] = true;
    
    return update_option('aqualuxe_active_modules', $modules);
}

/**
 * Deactivate module
 *
 * @param string $module Module name.
 * @return bool
 */
function aqualuxe_deactivate_module($module) {
    $modules = get_option('aqualuxe_active_modules', array());
    $modules[$module] = false;
    
    return update_option('aqualuxe_active_modules', $modules);
}

/**
 * Get available modules
 *
 * @return array
 */
function aqualuxe_get_available_modules() {
    return array(
        'dark-mode' => array(
            'name' => __('Dark Mode', 'aqualuxe'),
            'description' => __('Adds dark mode support with persistent user preference.', 'aqualuxe'),
            'default' => true,
        ),
        'multilingual' => array(
            'name' => __('Multilingual', 'aqualuxe'),
            'description' => __('Adds multilingual support for WPML and Polylang.', 'aqualuxe'),
            'default' => true,
        ),
        'subscriptions' => array(
            'name' => __('Subscriptions', 'aqualuxe'),
            'description' => __('Adds subscription functionality for recurring payments.', 'aqualuxe'),
            'default' => false,
        ),
        'auctions' => array(
            'name' => __('Auctions', 'aqualuxe'),
            'description' => __('Adds auction functionality for products.', 'aqualuxe'),
            'default' => false,
        ),
        'bookings' => array(
            'name' => __('Bookings', 'aqualuxe'),
            'description' => __('Adds booking and scheduling functionality.', 'aqualuxe'),
            'default' => false,
        ),
        'events' => array(
            'name' => __('Events', 'aqualuxe'),
            'description' => __('Adds events and ticketing functionality.', 'aqualuxe'),
            'default' => false,
        ),
        'wholesale' => array(
            'name' => __('Wholesale', 'aqualuxe'),
            'description' => __('Adds wholesale and B2B functionality.', 'aqualuxe'),
            'default' => true,
        ),
        'trade-ins' => array(
            'name' => __('Trade-ins', 'aqualuxe'),
            'description' => __('Adds trade-in functionality for products.', 'aqualuxe'),
            'default' => false,
        ),
        'services' => array(
            'name' => __('Services', 'aqualuxe'),
            'description' => __('Adds service booking and management functionality.', 'aqualuxe'),
            'default' => true,
        ),
        'franchise' => array(
            'name' => __('Franchise', 'aqualuxe'),
            'description' => __('Adds franchise and licensing functionality.', 'aqualuxe'),
            'default' => false,
        ),
        'sustainability' => array(
            'name' => __('Sustainability', 'aqualuxe'),
            'description' => __('Adds sustainability and R&D functionality.', 'aqualuxe'),
            'default' => false,
        ),
        'affiliate' => array(
            'name' => __('Affiliate', 'aqualuxe'),
            'description' => __('Adds affiliate and referral functionality.', 'aqualuxe'),
            'default' => false,
        ),
    );
}

/**
 * Initialize default modules
 */
function aqualuxe_initialize_default_modules() {
    $modules = get_option('aqualuxe_active_modules', array());
    
    if (empty($modules)) {
        $available_modules = aqualuxe_get_available_modules();
        $default_modules = array();
        
        foreach ($available_modules as $module => $data) {
            $default_modules[$module] = $data['default'];
        }
        
        update_option('aqualuxe_active_modules', $default_modules);
    }
}
add_action('after_switch_theme', 'aqualuxe_initialize_default_modules');