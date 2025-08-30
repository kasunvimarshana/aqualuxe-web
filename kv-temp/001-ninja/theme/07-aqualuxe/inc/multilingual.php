<?php
/**
 * Multilingual Support Functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Check if multilingual support is enabled
    if (!aqualuxe_is_multilingual_enabled()) {
        return;
    }

    // Add language switcher to the appropriate location
    $position = aqualuxe_get_option('aqualuxe_language_switcher_position', 'header');

    switch ($position) {
        case 'header':
            add_action('aqualuxe_header_icons', 'aqualuxe_language_switcher', 20);
            break;
        case 'top_bar':
            add_action('aqualuxe_top_bar', 'aqualuxe_language_switcher', 20);
            break;
        case 'footer':
            add_action('aqualuxe_footer_widgets', 'aqualuxe_language_switcher', 20);
            break;
        case 'menu':
            add_filter('wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_menu', 10, 2);
            break;
        case 'sidebar':
            add_action('widgets_init', 'aqualuxe_register_language_switcher_widget');
            break;
    }

    // Add language attributes to HTML tag
    add_filter('language_attributes', 'aqualuxe_language_attributes');

    // Add RTL support
    if (aqualuxe_get_option('aqualuxe_enable_rtl_support', true)) {
        add_action('wp_head', 'aqualuxe_rtl_support');
    }

    // Auto redirect based on browser language
    if (aqualuxe_get_option('aqualuxe_auto_redirect_language', false)) {
        add_action('template_redirect', 'aqualuxe_auto_redirect_language');
    }

    // Integration with translation plugins
    aqualuxe_translation_plugin_integration();
}
add_action('after_setup_theme', 'aqualuxe_multilingual_init');

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    // Get available languages
    $languages = aqualuxe_get_available_languages();
    
    if (empty($languages)) {
        return;
    }

    // Get current language
    $current_lang = aqualuxe_get_current_language();

    // Get language switcher style
    $style = aqualuxe_get_option('aqualuxe_language_switcher_style', 'dropdown');

    // Get display options
    $show_flags = aqualuxe_get_option('aqualuxe_show_language_flags', true);
    $show_names = aqualuxe_get_option('aqualuxe_show_language_names', true);
    $show_current = aqualuxe_get_option('aqualuxe_show_current_language', true);

    // Start output
    $output = '<div class="language-switcher language-switcher-' . esc_attr($style) . '">';

    switch ($style) {
        case 'dropdown':
            $output .= aqualuxe_language_switcher_dropdown($languages, $current_lang, $show_flags, $show_names, $show_current);
            break;
        case 'list':
            $output .= aqualuxe_language_switcher_list($languages, $current_lang, $show_flags, $show_names);
            break;
        case 'flags':
            $output .= aqualuxe_language_switcher_flags($languages, $current_lang, $show_names);
            break;
        case 'flags_name':
            $output .= aqualuxe_language_switcher_flags_name($languages, $current_lang);
            break;
    }

    $output .= '</div>';

    echo $output;
}

/**
 * Generate dropdown language switcher
 *
 * @param array  $languages    Available languages.
 * @param string $current_lang Current language.
 * @param bool   $show_flags   Whether to show flags.
 * @param bool   $show_names   Whether to show names.
 * @param bool   $show_current Whether to show current language.
 * @return string
 */
function aqualuxe_language_switcher_dropdown($languages, $current_lang, $show_flags, $show_names, $show_current) {
    $output = '<div class="lang-dropdown">';
    
    // Current language button
    if ($show_current && isset($languages[$current_lang])) {
        $output .= '<button type="button" class="lang-dropdown-button flex items-center" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
        
        if ($show_flags) {
            $output .= '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $languages[$current_lang]['flag']) . '" alt="' . esc_attr($languages[$current_lang]['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        }
        
        if ($show_names) {
            $output .= '<span>' . esc_html($languages[$current_lang]['name']) . '</span>';
        }
        
        $output .= '<svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
        $output .= '</button>';
    } else {
        $output .= '<button type="button" class="lang-dropdown-button flex items-center" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
        $output .= '<span>' . esc_html__('Language', 'aqualuxe') . '</span>';
        $output .= '<svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
        $output .= '</button>';
    }

    // Dropdown items
    $output .= '<div class="lang-dropdown-items hidden absolute z-10 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-dark-700 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
    
    foreach ($languages as $code => $lang) {
        $active_class = ($code === $current_lang) ? ' bg-gray-100 dark:bg-dark-600' : '';
        $url = aqualuxe_get_language_url($code);
        
        $output .= '<a href="' . esc_url($url) . '" class="lang-dropdown-item' . esc_attr($active_class) . '" role="menuitem" tabindex="-1" data-lang="' . esc_attr($code) . '">';
        $output .= '<div class="flex items-center">';
        
        if ($show_flags) {
            $output .= '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        }
        
        if ($show_names) {
            $output .= '<span>' . esc_html($lang['name']) . '</span>';
        }
        
        $output .= '</div>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}

/**
 * Generate list language switcher
 *
 * @param array  $languages    Available languages.
 * @param string $current_lang Current language.
 * @param bool   $show_flags   Whether to show flags.
 * @param bool   $show_names   Whether to show names.
 * @return string
 */
function aqualuxe_language_switcher_list($languages, $current_lang, $show_flags, $show_names) {
    $output = '<ul class="lang-list flex space-x-4">';
    
    foreach ($languages as $code => $lang) {
        $active_class = ($code === $current_lang) ? ' font-bold text-primary-600 dark:text-primary-400' : '';
        $url = aqualuxe_get_language_url($code);
        
        $output .= '<li class="lang-item' . esc_attr($active_class) . '">';
        $output .= '<a href="' . esc_url($url) . '" class="flex items-center" data-lang="' . esc_attr($code) . '">';
        
        if ($show_flags) {
            $output .= '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        }
        
        if ($show_names) {
            $output .= '<span>' . esc_html($lang['name']) . '</span>';
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';

    return $output;
}

/**
 * Generate flags language switcher
 *
 * @param array  $languages    Available languages.
 * @param string $current_lang Current language.
 * @param bool   $show_names   Whether to show names.
 * @return string
 */
function aqualuxe_language_switcher_flags($languages, $current_lang, $show_names) {
    $output = '<ul class="lang-flags flex space-x-3">';
    
    foreach ($languages as $code => $lang) {
        $active_class = ($code === $current_lang) ? ' ring-2 ring-primary-600 dark:ring-primary-400' : '';
        $url = aqualuxe_get_language_url($code);
        
        $output .= '<li class="lang-item">';
        $output .= '<a href="' . esc_url($url) . '" class="block" data-lang="' . esc_attr($code) . '">';
        $output .= '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" class="w-6 h-6 rounded-full' . esc_attr($active_class) . '">';
        
        if ($show_names) {
            $output .= '<span class="sr-only">' . esc_html($lang['name']) . '</span>';
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';

    return $output;
}

/**
 * Generate flags with names language switcher
 *
 * @param array  $languages    Available languages.
 * @param string $current_lang Current language.
 * @return string
 */
function aqualuxe_language_switcher_flags_name($languages, $current_lang) {
    $output = '<ul class="lang-flags-name flex flex-col space-y-2">';
    
    foreach ($languages as $code => $lang) {
        $active_class = ($code === $current_lang) ? ' font-bold text-primary-600 dark:text-primary-400' : '';
        $url = aqualuxe_get_language_url($code);
        
        $output .= '<li class="lang-item' . esc_attr($active_class) . '">';
        $output .= '<a href="' . esc_url($url) . '" class="flex items-center" data-lang="' . esc_attr($code) . '">';
        $output .= '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        $output .= '<span>' . esc_html($lang['name']) . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';

    return $output;
}

/**
 * Add language switcher to menu
 *
 * @param string $items Menu items.
 * @param object $args  Menu arguments.
 * @return string
 */
function aqualuxe_add_language_switcher_to_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        ob_start();
        aqualuxe_language_switcher();
        $switcher = ob_get_clean();
        
        $items .= '<li class="menu-item menu-item-language">' . $switcher . '</li>';
    }
    
    return $items;
}

/**
 * Register language switcher widget
 */
function aqualuxe_register_language_switcher_widget() {
    register_sidebar(array(
        'name' => esc_html__('Language Switcher', 'aqualuxe'),
        'id' => 'language-switcher',
        'description' => esc_html__('Add widgets here to appear in the language switcher area.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

/**
 * Add language attributes to HTML tag
 *
 * @param string $output Language attributes.
 * @return string
 */
function aqualuxe_language_attributes($output) {
    if (aqualuxe_is_multilingual_enabled()) {
        $current_lang = aqualuxe_get_current_language();
        
        // Check if current language is RTL
        $rtl_languages = array('ar', 'he', 'fa', 'ur');
        
        if (in_array($current_lang, $rtl_languages)) {
            $output .= ' dir="rtl"';
        } else {
            $output .= ' dir="ltr"';
        }
    }
    
    return $output;
}

/**
 * Add RTL support
 */
function aqualuxe_rtl_support() {
    if (aqualuxe_is_multilingual_enabled() && aqualuxe_get_option('aqualuxe_enable_rtl_support', true)) {
        $current_lang = aqualuxe_get_current_language();
        
        // Check if current language is RTL
        $rtl_languages = array('ar', 'he', 'fa', 'ur');
        
        if (in_array($current_lang, $rtl_languages)) {
            echo '<link rel="stylesheet" href="' . esc_url(AQUALUXE_URI . '/assets/css/rtl.css') . '" type="text/css" media="all" />';
        }
    }
}

/**
 * Auto redirect based on browser language
 */
function aqualuxe_auto_redirect_language() {
    // Only redirect on home page and only once
    if (!is_front_page() || isset($_COOKIE['aqualuxe_language_redirect'])) {
        return;
    }

    // Get available languages
    $languages = aqualuxe_get_available_languages();
    
    if (empty($languages)) {
        return;
    }

    // Get current language
    $current_lang = aqualuxe_get_current_language();

    // Get browser language
    $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    // Check if browser language is available and different from current language
    if (isset($languages[$browser_lang]) && $browser_lang !== $current_lang) {
        // Get URL for browser language
        $redirect_url = aqualuxe_get_language_url($browser_lang);
        
        // Set cookie to prevent redirect loop
        setcookie('aqualuxe_language_redirect', '1', time() + 86400, '/'); // 1 day
        
        // Redirect
        wp_redirect($redirect_url);
        exit;
    }
}

/**
 * Integration with translation plugins
 */
function aqualuxe_translation_plugin_integration() {
    $plugin = aqualuxe_get_option('aqualuxe_translation_plugin', 'polylang');
    
    switch ($plugin) {
        case 'polylang':
            aqualuxe_polylang_integration();
            break;
        case 'wpml':
            aqualuxe_wpml_integration();
            break;
        case 'gtranslate':
            aqualuxe_gtranslate_integration();
            break;
    }
}

/**
 * Integration with Polylang
 */
function aqualuxe_polylang_integration() {
    if (!function_exists('pll_current_language')) {
        return;
    }

    // Add support for custom post types
    if (aqualuxe_get_option('aqualuxe_translate_custom_post_types', true)) {
        add_filter('pll_get_post_types', 'aqualuxe_polylang_post_types');
    }

    // Add support for taxonomies
    if (aqualuxe_get_option('aqualuxe_translate_taxonomies', true)) {
        add_filter('pll_get_taxonomies', 'aqualuxe_polylang_taxonomies');
    }

    // Add support for strings
    add_action('after_setup_theme', 'aqualuxe_polylang_register_strings');
}

/**
 * Add custom post types to Polylang
 *
 * @param array $post_types Post types.
 * @return array
 */
function aqualuxe_polylang_post_types($post_types) {
    // Add your custom post types here
    $post_types['product'] = 'product';
    
    return $post_types;
}

/**
 * Add taxonomies to Polylang
 *
 * @param array $taxonomies Taxonomies.
 * @return array
 */
function aqualuxe_polylang_taxonomies($taxonomies) {
    // Add your taxonomies here
    $taxonomies['product_cat'] = 'product_cat';
    $taxonomies['product_tag'] = 'product_tag';
    
    return $taxonomies;
}

/**
 * Register strings with Polylang
 */
function aqualuxe_polylang_register_strings() {
    if (function_exists('pll_register_string')) {
        // Register theme strings
        pll_register_string('aqualuxe_footer_copyright', aqualuxe_get_option('aqualuxe_footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . esc_html__('All rights reserved.', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_hero_title', aqualuxe_get_option('aqualuxe_hero_title', esc_html__('Welcome to AquaLuxe', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_hero_subtitle', aqualuxe_get_option('aqualuxe_hero_subtitle', esc_html__('Premium Ornamental Fish for Collectors and Enthusiasts', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_hero_text', aqualuxe_get_option('aqualuxe_hero_text', esc_html__('Discover our exclusive collection of rare and exotic ornamental fish, sourced from sustainable farms around the world.', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_hero_button_text', aqualuxe_get_option('aqualuxe_hero_button_text', esc_html__('Shop Now', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_hero_button_2_text', aqualuxe_get_option('aqualuxe_hero_button_2_text', esc_html__('Learn More', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_featured_products_title', aqualuxe_get_option('aqualuxe_featured_products_title', esc_html__('Featured Products', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_featured_products_subtitle', aqualuxe_get_option('aqualuxe_featured_products_subtitle', esc_html__('Our most exclusive and sought-after specimens', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_testimonials_title', aqualuxe_get_option('aqualuxe_testimonials_title', esc_html__('What Our Customers Say', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_testimonials_subtitle', aqualuxe_get_option('aqualuxe_testimonials_subtitle', esc_html__('Hear from our satisfied collectors and enthusiasts', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_newsletter_title', aqualuxe_get_option('aqualuxe_newsletter_title', esc_html__('Subscribe to Our Newsletter', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_newsletter_subtitle', aqualuxe_get_option('aqualuxe_newsletter_subtitle', esc_html__('Stay updated with our latest arrivals, breeding success stories, and exclusive offers', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_sale_badge_text', aqualuxe_get_option('aqualuxe_sale_badge_text', esc_html__('Sale!', 'aqualuxe')), 'AquaLuxe', false);
        pll_register_string('aqualuxe_read_more_text', aqualuxe_get_option('aqualuxe_read_more_text', esc_html__('Read More', 'aqualuxe')), 'AquaLuxe', false);
    }
}

/**
 * Integration with WPML
 */
function aqualuxe_wpml_integration() {
    if (!defined('ICL_LANGUAGE_CODE')) {
        return;
    }

    // Add support for custom post types
    if (aqualuxe_get_option('aqualuxe_translate_custom_post_types', true)) {
        add_filter('wpml_is_translated_post_type', 'aqualuxe_wpml_post_types', 10, 2);
    }

    // Add support for taxonomies
    if (aqualuxe_get_option('aqualuxe_translate_taxonomies', true)) {
        add_filter('wpml_is_translated_taxonomy', 'aqualuxe_wpml_taxonomies', 10, 2);
    }

    // Add support for strings
    add_action('after_setup_theme', 'aqualuxe_wpml_register_strings');
}

/**
 * Add custom post types to WPML
 *
 * @param bool   $is_translated Whether the post type is translated.
 * @param string $post_type     Post type.
 * @return bool
 */
function aqualuxe_wpml_post_types($is_translated, $post_type) {
    // Add your custom post types here
    if ($post_type === 'product') {
        return true;
    }
    
    return $is_translated;
}

/**
 * Add taxonomies to WPML
 *
 * @param bool   $is_translated Whether the taxonomy is translated.
 * @param string $taxonomy      Taxonomy.
 * @return bool
 */
function aqualuxe_wpml_taxonomies($is_translated, $taxonomy) {
    // Add your taxonomies here
    if ($taxonomy === 'product_cat' || $taxonomy === 'product_tag') {
        return true;
    }
    
    return $is_translated;
}

/**
 * Register strings with WPML
 */
function aqualuxe_wpml_register_strings() {
    if (function_exists('icl_register_string')) {
        // Register theme strings
        icl_register_string('AquaLuxe', 'footer_copyright', aqualuxe_get_option('aqualuxe_footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . esc_html__('All rights reserved.', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'hero_title', aqualuxe_get_option('aqualuxe_hero_title', esc_html__('Welcome to AquaLuxe', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'hero_subtitle', aqualuxe_get_option('aqualuxe_hero_subtitle', esc_html__('Premium Ornamental Fish for Collectors and Enthusiasts', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'hero_text', aqualuxe_get_option('aqualuxe_hero_text', esc_html__('Discover our exclusive collection of rare and exotic ornamental fish, sourced from sustainable farms around the world.', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'hero_button_text', aqualuxe_get_option('aqualuxe_hero_button_text', esc_html__('Shop Now', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'hero_button_2_text', aqualuxe_get_option('aqualuxe_hero_button_2_text', esc_html__('Learn More', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'featured_products_title', aqualuxe_get_option('aqualuxe_featured_products_title', esc_html__('Featured Products', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'featured_products_subtitle', aqualuxe_get_option('aqualuxe_featured_products_subtitle', esc_html__('Our most exclusive and sought-after specimens', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'testimonials_title', aqualuxe_get_option('aqualuxe_testimonials_title', esc_html__('What Our Customers Say', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'testimonials_subtitle', aqualuxe_get_option('aqualuxe_testimonials_subtitle', esc_html__('Hear from our satisfied collectors and enthusiasts', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'newsletter_title', aqualuxe_get_option('aqualuxe_newsletter_title', esc_html__('Subscribe to Our Newsletter', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'newsletter_subtitle', aqualuxe_get_option('aqualuxe_newsletter_subtitle', esc_html__('Stay updated with our latest arrivals, breeding success stories, and exclusive offers', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'sale_badge_text', aqualuxe_get_option('aqualuxe_sale_badge_text', esc_html__('Sale!', 'aqualuxe')));
        icl_register_string('AquaLuxe', 'read_more_text', aqualuxe_get_option('aqualuxe_read_more_text', esc_html__('Read More', 'aqualuxe')));
    }
}

/**
 * Integration with GTranslate
 */
function aqualuxe_gtranslate_integration() {
    // GTranslate integration is handled via shortcode or widget
    // No additional code needed here
}

/**
 * Get available languages
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    $languages = array();
    $plugin = aqualuxe_get_option('aqualuxe_translation_plugin', 'polylang');
    
    switch ($plugin) {
        case 'polylang':
            if (function_exists('pll_languages_list')) {
                $langs = pll_languages_list(array('fields' => 'slug'));
                $names = pll_languages_list(array('fields' => 'name'));
                
                foreach ($langs as $key => $code) {
                    $languages[$code] = array(
                        'name' => $names[$key],
                        'flag' => $code . '.svg',
                    );
                }
            }
            break;
        case 'wpml':
            if (defined('ICL_LANGUAGE_CODE')) {
                $langs = apply_filters('wpml_active_languages', array());
                
                foreach ($langs as $code => $lang) {
                    $languages[$code] = array(
                        'name' => $lang['native_name'],
                        'flag' => $code . '.svg',
                    );
                }
            }
            break;
        case 'gtranslate':
            // For GTranslate, use default languages
            $languages = aqualuxe_get_default_languages();
            break;
        case 'none':
        default:
            // Use default languages
            $languages = aqualuxe_get_default_languages();
            break;
    }
    
    return $languages;
}

/**
 * Get default languages
 *
 * @return array
 */
function aqualuxe_get_default_languages() {
    $languages = array();
    
    // Get enabled languages from theme options
    if (aqualuxe_get_option('aqualuxe_enable_english', true)) {
        $languages['en'] = array(
            'name' => esc_html__('English', 'aqualuxe'),
            'flag' => 'en.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_french', true)) {
        $languages['fr'] = array(
            'name' => esc_html__('French', 'aqualuxe'),
            'flag' => 'fr.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_german', true)) {
        $languages['de'] = array(
            'name' => esc_html__('German', 'aqualuxe'),
            'flag' => 'de.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_spanish', true)) {
        $languages['es'] = array(
            'name' => esc_html__('Spanish', 'aqualuxe'),
            'flag' => 'es.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_italian', true)) {
        $languages['it'] = array(
            'name' => esc_html__('Italian', 'aqualuxe'),
            'flag' => 'it.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_japanese', true)) {
        $languages['ja'] = array(
            'name' => esc_html__('Japanese', 'aqualuxe'),
            'flag' => 'ja.svg',
        );
    }
    
    if (aqualuxe_get_option('aqualuxe_enable_chinese', true)) {
        $languages['zh'] = array(
            'name' => esc_html__('Chinese', 'aqualuxe'),
            'flag' => 'zh.svg',
        );
    }
    
    return $languages;
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    $plugin = aqualuxe_get_option('aqualuxe_translation_plugin', 'polylang');
    $default = aqualuxe_get_option('aqualuxe_default_language', 'en');
    
    switch ($plugin) {
        case 'polylang':
            if (function_exists('pll_current_language')) {
                return pll_current_language();
            }
            break;
        case 'wpml':
            if (defined('ICL_LANGUAGE_CODE')) {
                return ICL_LANGUAGE_CODE;
            }
            break;
        case 'gtranslate':
            if (isset($_COOKIE['googtrans'])) {
                $cookie = explode('/', $_COOKIE['googtrans']);
                if (isset($cookie[2])) {
                    return $cookie[2];
                }
            }
            break;
    }
    
    return $default;
}

/**
 * Get language URL
 *
 * @param string $lang Language code.
 * @return string
 */
function aqualuxe_get_language_url($lang) {
    $plugin = aqualuxe_get_option('aqualuxe_translation_plugin', 'polylang');
    
    switch ($plugin) {
        case 'polylang':
            if (function_exists('pll_home_url')) {
                return pll_home_url($lang);
            }
            break;
        case 'wpml':
            if (function_exists('icl_get_home_url')) {
                return apply_filters('wpml_home_url', home_url(), $lang);
            }
            break;
        case 'gtranslate':
            return add_query_arg('lang', $lang, home_url());
            break;
    }
    
    return home_url();
}