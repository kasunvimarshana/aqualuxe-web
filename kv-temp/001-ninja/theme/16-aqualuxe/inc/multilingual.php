<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if a multilingual plugin is active
 *
 * @return bool
 */
function aqualuxe_is_multilingual() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return true;
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return true;
    }
    
    // Check for TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        return true;
    }
    
    // Check for qTranslate-X
    if (function_exists('qtranxf_getSortedLanguages')) {
        return true;
    }
    
    // Check for Weglot
    if (function_exists('weglot_get_current_language')) {
        return true;
    }
    
    return false;
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    // WPML
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    
    // Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings');
        $settings = $trp_settings->get_settings();
        
        if (isset($_GET['trp-edit-translation']) && $_GET['trp-edit-translation'] == 'preview') {
            return isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : $settings['default-language'];
        } else {
            return isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : $settings['default-language'];
        }
    }
    
    // qTranslate-X
    if (function_exists('qtranxf_getLanguage')) {
        return qtranxf_getLanguage();
    }
    
    // Weglot
    if (function_exists('weglot_get_current_language')) {
        return weglot_get_current_language();
    }
    
    // Default
    return get_locale();
}

/**
 * Get default language
 *
 * @return string
 */
function aqualuxe_get_default_language() {
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        return $sitepress->get_default_language();
    }
    
    // Polylang
    if (function_exists('pll_default_language')) {
        return pll_default_language();
    }
    
    // TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings');
        $settings = $trp_settings->get_settings();
        
        return $settings['default-language'];
    }
    
    // qTranslate-X
    if (function_exists('qtranxf_getLanguage')) {
        global $q_config;
        return $q_config['default_language'];
    }
    
    // Weglot
    if (function_exists('weglot_get_original_language')) {
        return weglot_get_original_language();
    }
    
    // Default
    return get_locale();
}

/**
 * Get available languages
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    $languages = [];
    
    // WPML
    if (function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0');
        
        if (!empty($wpml_languages)) {
            foreach ($wpml_languages as $lang) {
                $languages[] = [
                    'code' => $lang['code'],
                    'name' => $lang['native_name'],
                    'url' => $lang['url'],
                    'flag' => $lang['country_flag_url'],
                    'active' => $lang['active'],
                ];
            }
        }
        
        return $languages;
    }
    
    // Polylang
    if (function_exists('pll_languages_list')) {
        $pll_languages = pll_languages_list(['fields' => '']);
        
        if (!empty($pll_languages)) {
            foreach ($pll_languages as $lang) {
                $languages[] = [
                    'code' => $lang->slug,
                    'name' => $lang->name,
                    'url' => $lang->home_url,
                    'flag' => $lang->flag_url,
                    'active' => $lang->slug === pll_current_language(),
                ];
            }
        }
        
        return $languages;
    }
    
    // TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings');
        $settings = $trp_settings->get_settings();
        $current_language = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : $settings['default-language'];
        
        if (!empty($settings['publish-languages'])) {
            foreach ($settings['publish-languages'] as $lang) {
                $url = add_query_arg('lang', $lang, home_url());
                
                $languages[] = [
                    'code' => $lang,
                    'name' => trp_get_language_names([$lang])[$lang],
                    'url' => $url,
                    'flag' => TRP_PLUGIN_URL . 'assets/images/flags/' . $lang . '.png',
                    'active' => $lang === $current_language,
                ];
            }
        }
        
        return $languages;
    }
    
    // qTranslate-X
    if (function_exists('qtranxf_getSortedLanguages')) {
        global $q_config;
        $qtranslate_languages = qtranxf_getSortedLanguages();
        $current_language = qtranxf_getLanguage();
        
        if (!empty($qtranslate_languages)) {
            foreach ($qtranslate_languages as $lang) {
                $url = qtranxf_convertURL('', $lang, false, true);
                
                $languages[] = [
                    'code' => $lang,
                    'name' => $q_config['language_name'][$lang],
                    'url' => $url,
                    'flag' => qtranxf_flag_url($lang),
                    'active' => $lang === $current_language,
                ];
            }
        }
        
        return $languages;
    }
    
    // Weglot
    if (function_exists('weglot_get_languages_available')) {
        $weglot_languages = weglot_get_languages_available();
        $current_language = weglot_get_current_language();
        
        if (!empty($weglot_languages)) {
            foreach ($weglot_languages as $lang) {
                $url = weglot_get_current_full_url($lang->getIso639());
                
                $languages[] = [
                    'code' => $lang->getIso639(),
                    'name' => $lang->getLocalName(),
                    'url' => $url,
                    'flag' => 'https://cdn.weglot.com/flags/' . $lang->getIso639() . '.svg',
                    'active' => $lang->getIso639() === $current_language,
                ];
            }
        }
        
        return $languages;
    }
    
    // Default
    $languages[] = [
        'code' => get_locale(),
        'name' => get_locale(),
        'url' => home_url(),
        'flag' => '',
        'active' => true,
    ];
    
    return $languages;
}

/**
 * Get language switcher
 *
 * @return string
 */
function aqualuxe_get_language_switcher() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return '';
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return '';
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return '';
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="language-switcher relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false" class="language-switcher-button">
            <?php if ($current['flag']) : ?>
                <img src="<?php echo esc_url($current['flag']); ?>" alt="<?php echo esc_attr($current['name']); ?>" class="w-5 h-auto inline-block mr-2">
            <?php endif; ?>
            
            <?php echo esc_html($current['code']); ?>
            
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <div x-show="open" class="language-switcher-dropdown" style="display: none;">
            <div class="py-1">
                <?php foreach ($languages as $language) : ?>
                    <?php if (!$language['active']) : ?>
                        <a href="<?php echo esc_url($language['url']); ?>" class="language-switcher-item">
                            <?php if ($language['flag']) : ?>
                                <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="w-5 h-auto inline-block mr-2">
                            <?php endif; ?>
                            
                            <?php echo esc_html($language['name']); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Display language switcher
 *
 * @return void
 */
function aqualuxe_language_switcher() {
    echo aqualuxe_get_language_switcher();
}

/**
 * Add language switcher to header
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_header() {
    $position = get_theme_mod('aqualuxe_language_switcher_position', 'header');
    
    if ($position === 'header' || $position === 'both') {
        aqualuxe_language_switcher();
    }
}
add_action('aqualuxe_header_right', 'aqualuxe_add_language_switcher_to_header', 20);

/**
 * Add language switcher to footer
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_footer() {
    $position = get_theme_mod('aqualuxe_language_switcher_position', 'header');
    
    if ($position === 'footer' || $position === 'both') {
        aqualuxe_language_switcher();
    }
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_add_language_switcher_to_footer', 20);

/**
 * Add RTL support
 *
 * @return void
 */
function aqualuxe_rtl_support() {
    // Check if RTL support is enabled
    if (!get_theme_mod('aqualuxe_rtl_support', true)) {
        return;
    }
    
    // Check if current language is RTL
    if (is_rtl()) {
        // Add RTL stylesheet
        wp_enqueue_style('aqualuxe-rtl', AQUALUXE_URI . '/assets/css/rtl.css', ['aqualuxe-styles'], AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_rtl_support', 20);

/**
 * Add hreflang tags
 *
 * @return void
 */
function aqualuxe_add_hreflang_tags() {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    // Add hreflang tags
    foreach ($languages as $language) {
        echo '<link rel="alternate" hreflang="' . esc_attr($language['code']) . '" href="' . esc_url($language['url']) . '" />' . "\n";
    }
    
    // Add x-default hreflang tag
    $default_language = aqualuxe_get_default_language();
    
    foreach ($languages as $language) {
        if ($language['code'] === $default_language) {
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($language['url']) . '" />' . "\n";
            break;
        }
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_tags', 1);

/**
 * Register strings for translation
 *
 * @return void
 */
function aqualuxe_register_strings_for_translation() {
    // Check if WPML String Translation is active
    if (function_exists('icl_register_string')) {
        // Register theme options
        $options = [
            'aqualuxe_header_button_text' => __('Header Button Text', 'aqualuxe'),
            'aqualuxe_footer_text' => __('Footer Text', 'aqualuxe'),
            'aqualuxe_copyright_text' => __('Copyright Text', 'aqualuxe'),
        ];
        
        foreach ($options as $option => $label) {
            $value = get_theme_mod($option, '');
            
            if (!empty($value)) {
                icl_register_string('AquaLuxe Theme', $label, $value);
            }
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll_register_string')) {
        // Register theme options
        $options = [
            'aqualuxe_header_button_text' => __('Header Button Text', 'aqualuxe'),
            'aqualuxe_footer_text' => __('Footer Text', 'aqualuxe'),
            'aqualuxe_copyright_text' => __('Copyright Text', 'aqualuxe'),
        ];
        
        foreach ($options as $option => $label) {
            $value = get_theme_mod($option, '');
            
            if (!empty($value)) {
                pll_register_string($label, $value, 'AquaLuxe Theme');
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_register_strings_for_translation');

/**
 * Translate strings
 *
 * @param string $string String to translate
 * @return string
 */
function aqualuxe_translate_string($string) {
    // Check if WPML String Translation is active
    if (function_exists('icl_t')) {
        return icl_t('AquaLuxe Theme', $string, $string);
    }
    
    // Check if Polylang is active
    if (function_exists('pll__')) {
        return pll__($string);
    }
    
    return $string;
}

/**
 * Get translated theme mod
 *
 * @param string $name Theme mod name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_translated_theme_mod($name, $default = '') {
    $value = get_theme_mod($name, $default);
    
    // Check if WPML String Translation is active
    if (function_exists('icl_t')) {
        $translated_value = icl_t('AquaLuxe Theme', $name, $value);
        
        if (!empty($translated_value)) {
            return $translated_value;
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll__')) {
        $translated_value = pll__($value);
        
        if (!empty($translated_value)) {
            return $translated_value;
        }
    }
    
    return $value;
}

/**
 * Add language switcher widget
 */
function aqualuxe_language_switcher_widget() {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    class Aqualuxe_Language_Switcher_Widget extends WP_Widget {
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_language_switcher',
                __('AquaLuxe Language Switcher', 'aqualuxe'),
                [
                    'description' => __('Display a language switcher for multilingual sites.', 'aqualuxe'),
                ]
            );
        }
        
        /**
         * Widget output
         *
         * @param array $args Widget arguments
         * @param array $instance Widget instance
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
            $show_flags = !empty($instance['show_flags']) ? true : false;
            $show_names = !empty($instance['show_names']) ? true : false;
            
            echo $args['before_widget'];
            
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            
            $languages = aqualuxe_get_available_languages();
            
            if (count($languages) > 1) {
                echo '<ul class="language-switcher-list">';
                
                foreach ($languages as $language) {
                    echo '<li class="language-item' . ($language['active'] ? ' active' : '') . '">';
                    echo '<a href="' . esc_url($language['url']) . '" class="language-link">';
                    
                    if ($show_flags && $language['flag']) {
                        echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag">';
                    }
                    
                    if ($show_names) {
                        echo '<span class="language-name">' . esc_html($language['name']) . '</span>';
                    } else {
                        echo '<span class="language-code">' . esc_html($language['code']) . '</span>';
                    }
                    
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
            }
            
            echo $args['after_widget'];
        }
        
        /**
         * Widget form
         *
         * @param array $instance Widget instance
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : '';
            $show_flags = !empty($instance['show_flags']) ? true : false;
            $show_names = !empty($instance['show_names']) ? true : false;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_flags); ?> id="<?php echo esc_attr($this->get_field_id('show_flags')); ?>" name="<?php echo esc_attr($this->get_field_name('show_flags')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_flags')); ?>"><?php esc_html_e('Show flags', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_names); ?> id="<?php echo esc_attr($this->get_field_id('show_names')); ?>" name="<?php echo esc_attr($this->get_field_name('show_names')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_names')); ?>"><?php esc_html_e('Show language names', 'aqualuxe'); ?></label>
            </p>
            <?php
        }
        
        /**
         * Update widget
         *
         * @param array $new_instance New instance
         * @param array $old_instance Old instance
         * @return array
         */
        public function update($new_instance, $old_instance) {
            $instance = [];
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['show_flags'] = (!empty($new_instance['show_flags'])) ? true : false;
            $instance['show_names'] = (!empty($new_instance['show_names'])) ? true : false;
            
            return $instance;
        }
    }
    
    register_widget('Aqualuxe_Language_Switcher_Widget');
}
add_action('widgets_init', 'aqualuxe_language_switcher_widget');

/**
 * Add language switcher shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_language_switcher_shortcode($atts) {
    $atts = shortcode_atts([
        'show_flags' => true,
        'show_names' => true,
        'dropdown' => false,
    ], $atts);
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return '';
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return '';
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return '';
    }
    
    ob_start();
    
    if ($atts['dropdown'] == true) {
        ?>
        <div class="language-switcher-shortcode dropdown" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="language-switcher-button">
                <?php if ($atts['show_flags'] && $current['flag']) : ?>
                    <img src="<?php echo esc_url($current['flag']); ?>" alt="<?php echo esc_attr($current['name']); ?>" class="language-flag">
                <?php endif; ?>
                
                <?php if ($atts['show_names']) : ?>
                    <span class="language-name"><?php echo esc_html($current['name']); ?></span>
                <?php else : ?>
                    <span class="language-code"><?php echo esc_html($current['code']); ?></span>
                <?php endif; ?>
                
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" class="language-switcher-dropdown" style="display: none;">
                <div class="py-1">
                    <?php foreach ($languages as $language) : ?>
                        <?php if (!$language['active']) : ?>
                            <a href="<?php echo esc_url($language['url']); ?>" class="language-switcher-item">
                                <?php if ($atts['show_flags'] && $language['flag']) : ?>
                                    <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag">
                                <?php endif; ?>
                                
                                <?php if ($atts['show_names']) : ?>
                                    <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                                <?php else : ?>
                                    <span class="language-code"><?php echo esc_html($language['code']); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <ul class="language-switcher-shortcode list">
            <?php foreach ($languages as $language) : ?>
                <li class="language-item<?php echo $language['active'] ? ' active' : ''; ?>">
                    <a href="<?php echo esc_url($language['url']); ?>" class="language-link">
                        <?php if ($atts['show_flags'] && $language['flag']) : ?>
                            <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag">
                        <?php endif; ?>
                        
                        <?php if ($atts['show_names']) : ?>
                            <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                        <?php else : ?>
                            <span class="language-code"><?php echo esc_html($language['code']); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_language_switcher', 'aqualuxe_language_switcher_shortcode');

/**
 * Add language class to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_language_class_to_body($classes) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $classes[] = 'lang-' . $current_language;
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_add_language_class_to_body');

/**
 * Add language direction class to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_language_direction_class_to_body($classes) {
    if (is_rtl()) {
        $classes[] = 'rtl';
    } else {
        $classes[] = 'ltr';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_add_language_direction_class_to_body');

/**
 * Add language switcher to mobile menu
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_mobile_menu($items, $args) {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return $items;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $items;
    }
    
    // Check if this is the mobile menu
    if ($args->theme_location !== 'mobile') {
        return $items;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return $items;
    }
    
    $language_items = '';
    
    foreach ($languages as $language) {
        $active_class = $language['active'] ? ' class="active"' : '';
        
        $language_items .= '<li' . $active_class . '>';
        $language_items .= '<a href="' . esc_url($language['url']) . '">';
        
        if ($language['flag']) {
            $language_items .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag">';
        }
        
        $language_items .= '<span class="language-name">' . esc_html($language['name']) . '</span>';
        $language_items .= '</a>';
        $language_items .= '</li>';
    }
    
    if ($language_items) {
        $items .= '<li class="menu-item-has-children language-menu-item">';
        $items .= '<a href="#">' . esc_html__('Languages', 'aqualuxe') . '</a>';
        $items .= '<ul class="sub-menu">';
        $items .= $language_items;
        $items .= '</ul>';
        $items .= '</li>';
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_mobile_menu', 10, 2);

/**
 * Add language switcher to account menu
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_account_menu($items, $endpoints) {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return $items;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $items;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return $items;
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return $items;
    }
    
    $language_items = [];
    
    foreach ($languages as $language) {
        $language_items[$language['code']] = [
            'title' => $language['name'],
            'url' => $language['url'],
        ];
    }
    
    // Add language switcher after logout
    $logout_position = array_search('customer-logout', array_keys($items));
    
    if ($logout_position !== false) {
        $items_before = array_slice($items, 0, $logout_position + 1);
        $items_after = array_slice($items, $logout_position + 1);
        
        $items = $items_before + ['language-switcher' => __('Language', 'aqualuxe')] + $items_after;
        
        add_filter('woocommerce_account_menu_item_language-switcher', function() use ($language_items, $current) {
            ob_start();
            ?>
            <div class="woocommerce-MyAccount-language-switcher">
                <div class="current-language">
                    <?php if ($current['flag']) : ?>
                        <img src="<?php echo esc_url($current['flag']); ?>" alt="<?php echo esc_attr($current['name']); ?>" class="language-flag">
                    <?php endif; ?>
                    
                    <span class="language-name"><?php echo esc_html($current['name']); ?></span>
                </div>
                
                <ul class="language-switcher-list">
                    <?php foreach ($language_items as $code => $language) : ?>
                        <?php if ($code !== $current['code']) : ?>
                            <li class="language-item">
                                <a href="<?php echo esc_url($language['url']); ?>" class="language-link">
                                    <?php if (isset($languages[$code]) && $languages[$code]['flag']) : ?>
                                        <img src="<?php echo esc_url($languages[$code]['flag']); ?>" alt="<?php echo esc_attr($language['title']); ?>" class="language-flag">
                                    <?php endif; ?>
                                    
                                    <span class="language-name"><?php echo esc_html($language['title']); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
            return ob_get_clean();
        });
    }
    
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_add_language_switcher_to_account_menu', 10, 2);

/**
 * Add language switcher endpoint
 *
 * @return void
 */
function aqualuxe_add_language_switcher_endpoint() {
    add_rewrite_endpoint('language-switcher', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_add_language_switcher_endpoint');

/**
 * Add language switcher endpoint content
 *
 * @return void
 */
function aqualuxe_language_switcher_endpoint_content() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="woocommerce-language-switcher">';
    echo '<h2>' . esc_html__('Select Language', 'aqualuxe') . '</h2>';
    
    echo '<ul class="language-switcher-list">';
    
    foreach ($languages as $language) {
        echo '<li class="language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag">';
        }
        
        echo '<span class="language-name">' . esc_html($language['name']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_account_language-switcher_endpoint', 'aqualuxe_language_switcher_endpoint_content');

/**
 * Add language switcher to footer menu
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_footer_menu($items, $args) {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return $items;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $items;
    }
    
    // Check if this is the footer menu
    if ($args->theme_location !== 'footer') {
        return $items;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return $items;
    }
    
    $language_items = '';
    
    foreach ($languages as $language) {
        $active_class = $language['active'] ? ' class="active"' : '';
        
        $language_items .= '<li' . $active_class . '>';
        $language_items .= '<a href="' . esc_url($language['url']) . '">';
        
        if ($language['flag']) {
            $language_items .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag">';
        }
        
        $language_items .= '<span class="language-name">' . esc_html($language['name']) . '</span>';
        $language_items .= '</a>';
        $language_items .= '</li>';
    }
    
    if ($language_items) {
        $items .= '<li class="menu-item-has-children language-menu-item">';
        $items .= '<a href="#">' . esc_html__('Languages', 'aqualuxe') . '</a>';
        $items .= '<ul class="sub-menu">';
        $items .= $language_items;
        $items .= '</ul>';
        $items .= '</li>';
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_footer_menu', 10, 2);

/**
 * Add language switcher to top bar
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_top_bar() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return;
    }
    
    echo '<div class="language-switcher-top-bar" x-data="{ open: false }">';
    echo '<button @click="open = !open" @click.away="open = false" class="language-switcher-button">';
    
    if ($current['flag']) {
        echo '<img src="' . esc_url($current['flag']) . '" alt="' . esc_attr($current['name']) . '" class="language-flag">';
    }
    
    echo '<span class="language-code">' . esc_html($current['code']) . '</span>';
    
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
    echo '</svg>';
    echo '</button>';
    
    echo '<div x-show="open" class="language-switcher-dropdown" style="display: none;">';
    echo '<div class="py-1">';
    
    foreach ($languages as $language) {
        if (!$language['active']) {
            echo '<a href="' . esc_url($language['url']) . '" class="language-switcher-item">';
            
            if ($language['flag']) {
                echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag">';
            }
            
            echo '<span class="language-name">' . esc_html($language['name']) . '</span>';
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
add_action('aqualuxe_top_bar', 'aqualuxe_add_language_switcher_to_top_bar', 20);

/**
 * Add language switcher to product meta
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_product_meta() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="product-language-switcher">';
    echo '<span class="product-language-label">' . esc_html__('Language:', 'aqualuxe') . '</span>';
    
    echo '<ul class="product-language-list">';
    
    foreach ($languages as $language) {
        echo '<li class="product-language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="product-language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="product-language-flag">';
        }
        
        echo '<span class="product-language-code">' . esc_html($language['code']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_product_meta_end', 'aqualuxe_add_language_switcher_to_product_meta', 20);

/**
 * Add language switcher to checkout
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_checkout() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="checkout-language-switcher">';
    echo '<p>' . esc_html__('Change language:', 'aqualuxe') . '</p>';
    
    echo '<ul class="checkout-language-list">';
    
    foreach ($languages as $language) {
        echo '<li class="checkout-language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="checkout-language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="checkout-language-flag">';
        }
        
        echo '<span class="checkout-language-name">' . esc_html($language['name']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_checkout_before_customer_details', 'aqualuxe_add_language_switcher_to_checkout', 5);

/**
 * Add language switcher to cart
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_cart() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="cart-language-switcher">';
    echo '<p>' . esc_html__('Change language:', 'aqualuxe') . '</p>';
    
    echo '<ul class="cart-language-list">';
    
    foreach ($languages as $language) {
        echo '<li class="cart-language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="cart-language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="cart-language-flag">';
        }
        
        echo '<span class="cart-language-name">' . esc_html($language['name']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_cart_totals_before_shipping', 'aqualuxe_add_language_switcher_to_cart', 5);

/**
 * Add language switcher to login form
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_login_form() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="login-language-switcher">';
    echo '<p>' . esc_html__('Change language:', 'aqualuxe') . '</p>';
    
    echo '<ul class="login-language-list">';
    
    foreach ($languages as $language) {
        echo '<li class="login-language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="login-language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="login-language-flag">';
        }
        
        echo '<span class="login-language-name">' . esc_html($language['name']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_login_form_end', 'aqualuxe_add_language_switcher_to_login_form', 5);
add_action('woocommerce_register_form_end', 'aqualuxe_add_language_switcher_to_login_form', 5);

/**
 * Add language switcher to my account
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_my_account() {
    // Check if language switcher is enabled
    if (!get_theme_mod('aqualuxe_language_switcher', true)) {
        return;
    }
    
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="my-account-language-switcher">';
    echo '<p>' . esc_html__('Change language:', 'aqualuxe') . '</p>';
    
    echo '<ul class="my-account-language-list">';
    
    foreach ($languages as $language) {
        echo '<li class="my-account-language-item' . ($language['active'] ? ' active' : '') . '">';
        echo '<a href="' . esc_url($language['url']) . '" class="my-account-language-link">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="my-account-language-flag">';
        }
        
        echo '<span class="my-account-language-name">' . esc_html($language['name']) . '</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_account_dashboard', 'aqualuxe_add_language_switcher_to_my_account', 5);

/**
 * Add language class to HTML tag
 *
 * @param string $output HTML output
 * @return string
 */
function aqualuxe_add_language_class_to_html($output) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $output = str_replace('<html', '<html lang="' . esc_attr($current_language) . '"', $output);
    }
    
    return $output;
}
add_filter('language_attributes', 'aqualuxe_add_language_class_to_html');

/**
 * Add RTL stylesheet
 *
 * @return void
 */
function aqualuxe_add_rtl_stylesheet() {
    // Check if RTL support is enabled
    if (!get_theme_mod('aqualuxe_rtl_support', true)) {
        return;
    }
    
    // Check if current language is RTL
    if (is_rtl()) {
        wp_enqueue_style('aqualuxe-rtl', AQUALUXE_URI . '/assets/css/rtl.css', ['aqualuxe-styles'], AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_add_rtl_stylesheet', 20);

/**
 * Add language specific CSS
 *
 * @return void
 */
function aqualuxe_add_language_specific_css() {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_css_file = AQUALUXE_DIR . '/assets/css/language-' . $current_language . '.css';
        
        if (file_exists($language_css_file)) {
            wp_enqueue_style('aqualuxe-language-' . $current_language, AQUALUXE_URI . '/assets/css/language-' . $current_language . '.css', ['aqualuxe-styles'], AQUALUXE_VERSION);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_add_language_specific_css', 20);

/**
 * Add language specific JavaScript
 *
 * @return void
 */
function aqualuxe_add_language_specific_js() {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_js_file = AQUALUXE_DIR . '/assets/js/language-' . $current_language . '.js';
        
        if (file_exists($language_js_file)) {
            wp_enqueue_script('aqualuxe-language-' . $current_language, AQUALUXE_URI . '/assets/js/language-' . $current_language . '.js', ['aqualuxe-scripts'], AQUALUXE_VERSION, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_add_language_specific_js', 20);

/**
 * Add language specific template parts
 *
 * @param string $template Template path
 * @return string
 */
function aqualuxe_add_language_specific_template_parts($template) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $template_name = basename($template);
        $template_dir = dirname($template);
        
        $language_template = $template_dir . '/languages/' . $current_language . '/' . $template_name;
        
        if (file_exists($language_template)) {
            return $language_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'aqualuxe_add_language_specific_template_parts');

/**
 * Add language specific sidebars
 *
 * @return void
 */
function aqualuxe_add_language_specific_sidebars() {
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    foreach ($languages as $language) {
        register_sidebar([
            'name' => sprintf(__('Blog Sidebar - %s', 'aqualuxe'), $language['name']),
            'id' => 'sidebar-1-' . $language['code'],
            'description' => sprintf(__('Add widgets here to appear in your blog sidebar for %s language.', 'aqualuxe'), $language['name']),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-xl font-bold mb-4">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => sprintf(__('Shop Sidebar - %s', 'aqualuxe'), $language['name']),
            'id' => 'sidebar-shop-' . $language['code'],
            'description' => sprintf(__('Add widgets here to appear in your shop sidebar for %s language.', 'aqualuxe'), $language['name']),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-xl font-bold mb-4">',
            'after_title' => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'aqualuxe_add_language_specific_sidebars', 11);

/**
 * Get language specific sidebar
 *
 * @param string $sidebar Sidebar ID
 * @return string
 */
function aqualuxe_get_language_specific_sidebar($sidebar) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_sidebar = $sidebar . '-' . $current_language;
        
        if (is_active_sidebar($language_sidebar)) {
            return $language_sidebar;
        }
    }
    
    return $sidebar;
}
add_filter('aqualuxe_get_sidebar', 'aqualuxe_get_language_specific_sidebar');

/**
 * Add language specific menus
 *
 * @return void
 */
function aqualuxe_add_language_specific_menus() {
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    foreach ($languages as $language) {
        register_nav_menu('primary-' . $language['code'], sprintf(__('Primary Menu - %s', 'aqualuxe'), $language['name']));
        register_nav_menu('footer-' . $language['code'], sprintf(__('Footer Menu - %s', 'aqualuxe'), $language['name']));
        register_nav_menu('social-' . $language['code'], sprintf(__('Social Links Menu - %s', 'aqualuxe'), $language['name']));
        register_nav_menu('mobile-' . $language['code'], sprintf(__('Mobile Menu - %s', 'aqualuxe'), $language['name']));
    }
}
add_action('after_setup_theme', 'aqualuxe_add_language_specific_menus');

/**
 * Get language specific menu
 *
 * @param array $args Menu args
 * @return array
 */
function aqualuxe_get_language_specific_menu($args) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language && isset($args['theme_location'])) {
        $language_menu = $args['theme_location'] . '-' . $current_language;
        
        if (has_nav_menu($language_menu)) {
            $args['theme_location'] = $language_menu;
        }
    }
    
    return $args;
}
add_filter('wp_nav_menu_args', 'aqualuxe_get_language_specific_menu');

/**
 * Add language specific CSS variables
 *
 * @param string $css CSS
 * @return string
 */
function aqualuxe_add_language_specific_css_variables($css) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_css = '';
        
        // Add language specific font sizes
        if ($current_language === 'ar' || $current_language === 'he' || $current_language === 'fa') {
            $language_css .= '
                :root {
                    --font-size-base: 18px;
                    --font-size-h1: 2.5rem;
                    --font-size-h2: 2rem;
                    --font-size-h3: 1.75rem;
                    --font-size-h4: 1.5rem;
                    --font-size-h5: 1.25rem;
                    --font-size-h6: 1rem;
                }
            ';
        } elseif ($current_language === 'zh' || $current_language === 'ja' || $current_language === 'ko') {
            $language_css .= '
                :root {
                    --font-size-base: 16px;
                    --font-size-h1: 2.25rem;
                    --font-size-h2: 1.875rem;
                    --font-size-h3: 1.5rem;
                    --font-size-h4: 1.25rem;
                    --font-size-h5: 1.125rem;
                    --font-size-h6: 1rem;
                }
            ';
        }
        
        // Add language specific fonts
        if ($current_language === 'ar') {
            $language_css .= '
                :root {
                    --font-body: "Cairo", sans-serif;
                    --font-heading: "Cairo", sans-serif;
                }
            ';
        } elseif ($current_language === 'zh') {
            $language_css .= '
                :root {
                    --font-body: "Noto Sans SC", sans-serif;
                    --font-heading: "Noto Sans SC", sans-serif;
                }
            ';
        } elseif ($current_language === 'ja') {
            $language_css .= '
                :root {
                    --font-body: "Noto Sans JP", sans-serif;
                    --font-heading: "Noto Sans JP", sans-serif;
                }
            ';
        } elseif ($current_language === 'ko') {
            $language_css .= '
                :root {
                    --font-body: "Noto Sans KR", sans-serif;
                    --font-heading: "Noto Sans KR", sans-serif;
                }
            ';
        } elseif ($current_language === 'th') {
            $language_css .= '
                :root {
                    --font-body: "Prompt", sans-serif;
                    --font-heading: "Prompt", sans-serif;
                }
            ';
        } elseif ($current_language === 'ru') {
            $language_css .= '
                :root {
                    --font-body: "Roboto", sans-serif;
                    --font-heading: "Roboto Slab", serif;
                }
            ';
        }
        
        $css .= $language_css;
    }
    
    return $css;
}
add_filter('aqualuxe_customizer_css', 'aqualuxe_add_language_specific_css_variables');

/**
 * Add language specific fonts
 *
 * @return void
 */
function aqualuxe_add_language_specific_fonts() {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $font_url = '';
        
        if ($current_language === 'ar') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap';
        } elseif ($current_language === 'zh') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap';
        } elseif ($current_language === 'ja') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap';
        } elseif ($current_language === 'ko') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap';
        } elseif ($current_language === 'th') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&display=swap';
        } elseif ($current_language === 'ru') {
            $font_url = 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Roboto+Slab:wght@400;500;700&display=swap';
        }
        
        if ($font_url) {
            wp_enqueue_style('aqualuxe-language-fonts', $font_url, [], null);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_add_language_specific_fonts', 1);

/**
 * Add language specific admin CSS
 *
 * @return void
 */
function aqualuxe_add_language_specific_admin_css() {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_css_file = AQUALUXE_DIR . '/assets/css/admin-language-' . $current_language . '.css';
        
        if (file_exists($language_css_file)) {
            wp_enqueue_style('aqualuxe-admin-language-' . $current_language, AQUALUXE_URI . '/assets/css/admin-language-' . $current_language . '.css', ['aqualuxe-admin-styles'], AQUALUXE_VERSION);
        }
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_add_language_specific_admin_css', 20);

/**
 * Add language specific admin JavaScript
 *
 * @return void
 */
function aqualuxe_add_language_specific_admin_js() {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $language_js_file = AQUALUXE_DIR . '/assets/js/admin-language-' . $current_language . '.js';
        
        if (file_exists($language_js_file)) {
            wp_enqueue_script('aqualuxe-admin-language-' . $current_language, AQUALUXE_URI . '/assets/js/admin-language-' . $current_language . '.js', ['aqualuxe-admin-scripts'], AQUALUXE_VERSION, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_add_language_specific_admin_js', 20);

/**
 * Add language switcher to admin bar
 *
 * @param WP_Admin_Bar $admin_bar Admin bar
 * @return void
 */
function aqualuxe_add_language_switcher_to_admin_bar($admin_bar) {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return;
    }
    
    $admin_bar->add_menu([
        'id' => 'aqualuxe-language-switcher',
        'title' => '<span class="ab-icon"></span><span class="ab-label">' . esc_html($current['name']) . '</span>',
        'href' => '#',
        'meta' => [
            'title' => __('Language', 'aqualuxe'),
        ],
    ]);
    
    foreach ($languages as $language) {
        if (!$language['active']) {
            $admin_bar->add_menu([
                'id' => 'aqualuxe-language-' . $language['code'],
                'parent' => 'aqualuxe-language-switcher',
                'title' => esc_html($language['name']),
                'href' => esc_url($language['url']),
                'meta' => [
                    'title' => esc_html($language['name']),
                ],
            ]);
        }
    }
}
add_action('admin_bar_menu', 'aqualuxe_add_language_switcher_to_admin_bar', 100);

/**
 * Add language switcher to admin notices
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_admin_notices() {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return;
    }
    
    echo '<div class="notice notice-info is-dismissible">';
    echo '<p>' . esc_html__('You are currently editing the site in:', 'aqualuxe') . ' <strong>' . esc_html($current['name']) . '</strong></p>';
    
    echo '<p>';
    echo esc_html__('Switch to:', 'aqualuxe') . ' ';
    
    foreach ($languages as $language) {
        if (!$language['active']) {
            echo '<a href="' . esc_url($language['url']) . '" class="button button-small">';
            
            if ($language['flag']) {
                echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="language-flag" style="vertical-align: middle; margin-right: 5px; width: 16px; height: auto;">';
            }
            
            echo esc_html($language['name']);
            echo '</a> ';
        }
    }
    
    echo '</p>';
    echo '</div>';
}
add_action('admin_notices', 'aqualuxe_add_language_switcher_to_admin_notices');

/**
 * Add language switcher to login form
 *
 * @return void
 */
function aqualuxe_add_language_switcher_to_wp_login_form() {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    echo '<div class="language-switcher-login" style="margin-bottom: 20px; text-align: center;">';
    echo '<p>' . esc_html__('Select language:', 'aqualuxe') . '</p>';
    
    echo '<div style="display: flex; justify-content: center; gap: 10px;">';
    
    foreach ($languages as $language) {
        echo '<a href="' . esc_url($language['url']) . '" style="display: inline-flex; align-items: center; padding: 5px 10px; border: 1px solid #ddd; border-radius: 3px; text-decoration: none; color: #333; background: ' . ($language['active'] ? '#f0f0f0' : 'transparent') . ';">';
        
        if ($language['flag']) {
            echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" style="width: 16px; height: auto; margin-right: 5px;">';
        }
        
        echo esc_html($language['name']);
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}
add_action('login_form', 'aqualuxe_add_language_switcher_to_wp_login_form');
add_action('register_form', 'aqualuxe_add_language_switcher_to_wp_login_form');
add_action('lostpassword_form', 'aqualuxe_add_language_switcher_to_wp_login_form');

/**
 * Add language switcher to login message
 *
 * @param string $message Login message
 * @return string
 */
function aqualuxe_add_language_switcher_to_login_message($message) {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $message;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return $message;
    }
    
    $language_switcher = '<div class="language-switcher-login-message" style="margin-bottom: 20px; text-align: center;">';
    $language_switcher .= '<div style="display: flex; justify-content: center; gap: 10px;">';
    
    foreach ($languages as $language) {
        $language_switcher .= '<a href="' . esc_url($language['url']) . '" style="display: inline-flex; align-items: center; padding: 5px 10px; border: 1px solid #ddd; border-radius: 3px; text-decoration: none; color: #333; background: ' . ($language['active'] ? '#f0f0f0' : 'transparent') . ';">';
        
        if ($language['flag']) {
            $language_switcher .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" style="width: 16px; height: auto; margin-right: 5px;">';
        }
        
        $language_switcher .= esc_html($language['name']);
        $language_switcher .= '</a>';
    }
    
    $language_switcher .= '</div>';
    $language_switcher .= '</div>';
    
    return $language_switcher . $message;
}
add_filter('login_message', 'aqualuxe_add_language_switcher_to_login_message');

/**
 * Add language switcher to admin footer
 *
 * @param string $text Admin footer text
 * @return string
 */
function aqualuxe_add_language_switcher_to_admin_footer($text) {
    // Check if multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $text;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return $text;
    }
    
    $language_switcher = '<div class="language-switcher-admin-footer" style="margin-top: 10px;">';
    $language_switcher .= '<span>' . esc_html__('Language:', 'aqualuxe') . ' </span>';
    
    foreach ($languages as $language) {
        $language_switcher .= '<a href="' . esc_url($language['url']) . '" style="display: inline-flex; align-items: center; margin-right: 10px; text-decoration: none; color: ' . ($language['active'] ? '#0073aa' : '#666') . ';">';
        
        if ($language['flag']) {
            $language_switcher .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" style="width: 16px; height: auto; margin-right: 5px;">';
        }
        
        $language_switcher .= esc_html($language['name']);
        $language_switcher .= '</a>';
    }
    
    $language_switcher .= '</div>';
    
    return $text . $language_switcher;
}
add_filter('admin_footer_text', 'aqualuxe_add_language_switcher_to_admin_footer');

/**
 * Add language class to admin body
 *
 * @param string $classes Admin body classes
 * @return string
 */
function aqualuxe_add_language_class_to_admin_body($classes) {
    $current_language = aqualuxe_get_current_language();
    
    if ($current_language) {
        $classes .= ' lang-' . $current_language;
    }
    
    return $classes;
}
add_filter('admin_body_class', 'aqualuxe_add_language_class_to_admin_body');

/**
 * Add language direction class to admin body
 *
 * @param string $classes Admin body classes
 * @return string
 */
function aqualuxe_add_language_direction_class_to_admin_body($classes) {
    if (is_rtl()) {
        $classes .= ' rtl';
    } else {
        $classes .= ' ltr';
    }
    
    return $classes;
}
add_filter('admin_body_class', 'aqualuxe_add_language_direction_class_to_admin_body');