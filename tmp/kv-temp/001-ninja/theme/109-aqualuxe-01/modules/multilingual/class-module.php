<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Multilingual;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Multilingual Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Multilingual';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('init', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('customize_register', array($this, 'customize_register'));
        add_filter('aqualuxe_language_switcher', array($this, 'language_switcher'));
        
        // Add WPML/Polylang support
        add_action('init', array($this, 'third_party_support'));
    }

    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Module-specific styles and scripts can be added here
    }

    /**
     * Add customizer controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function customize_register($wp_customize) {
        // Multilingual section
        $wp_customize->add_section('aqualuxe_multilingual', array(
            'title'    => __('Multilingual Settings', 'aqualuxe'),
            'priority' => 160,
        ));

        // Show language switcher
        $wp_customize->add_setting('aqualuxe_show_language_switcher', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_show_language_switcher', array(
            'label'   => __('Show Language Switcher', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type'    => 'checkbox',
        ));

        // Language switcher position
        $wp_customize->add_setting('aqualuxe_language_switcher_position', array(
            'default'           => 'header',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('aqualuxe_language_switcher_position', array(
            'label'   => __('Language Switcher Position', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type'    => 'select',
            'choices' => array(
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both'   => __('Both', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Language switcher
     *
     * @return string
     */
    public function language_switcher() {
        if (!get_theme_mod('aqualuxe_show_language_switcher', true)) {
            return '';
        }

        $output = '';
        
        // WPML support
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            
            if (!empty($languages)) {
                $output .= '<div class="language-switcher wpml-switcher">';
                $output .= '<ul class="flex space-x-2">';
                
                foreach ($languages as $lang) {
                    $class = $lang['active'] ? 'active' : '';
                    $output .= sprintf(
                        '<li class="%s"><a href="%s" class="px-2 py-1 text-sm rounded %s">%s</a></li>',
                        esc_attr($class),
                        esc_url($lang['url']),
                        $lang['active'] ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                        esc_html($lang['native_name'])
                    );
                }
                
                $output .= '</ul>';
                $output .= '</div>';
            }
        }
        
        // Polylang support
        elseif (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(array(
                'show_flags' => 1,
                'show_names' => 1,
                'echo'       => 0,
                'raw'        => 1,
            ));
            
            if (!empty($languages)) {
                $output .= '<div class="language-switcher polylang-switcher">';
                $output .= '<ul class="flex space-x-2">';
                
                foreach ($languages as $lang) {
                    $class = $lang['current_lang'] ? 'active' : '';
                    $output .= sprintf(
                        '<li class="%s"><a href="%s" class="px-2 py-1 text-sm rounded %s">%s %s</a></li>',
                        esc_attr($class),
                        esc_url($lang['url']),
                        $lang['current_lang'] ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                        $lang['flag'],
                        esc_html($lang['name'])
                    );
                }
                
                $output .= '</ul>';
                $output .= '</div>';
            }
        }
        
        // Fallback - manual language switcher
        else {
            $current_locale = get_locale();
            $languages = $this->get_manual_languages();
            
            if (!empty($languages)) {
                $output .= '<div class="language-switcher manual-switcher">';
                $output .= '<ul class="flex space-x-2">';
                
                foreach ($languages as $locale => $lang_data) {
                    $class = ($current_locale === $locale) ? 'active' : '';
                    $url = $this->get_language_url($locale);
                    
                    $output .= sprintf(
                        '<li class="%s"><a href="%s" class="px-2 py-1 text-sm rounded %s">%s</a></li>',
                        esc_attr($class),
                        esc_url($url),
                        ($current_locale === $locale) ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                        esc_html($lang_data['name'])
                    );
                }
                
                $output .= '</ul>';
                $output .= '</div>';
            }
        }

        return $output;
    }

    /**
     * Third party plugin support
     */
    public function third_party_support() {
        // WPML support
        if (defined('ICL_SITEPRESS_VERSION')) {
            add_filter('wpml_language_switcher_items', array($this, 'wpml_language_switcher_items'));
        }

        // Polylang support
        if (function_exists('pll_current_language')) {
            add_filter('pll_the_language_link', array($this, 'polylang_language_link'), 10, 3);
        }
    }

    /**
     * WPML language switcher items
     *
     * @param array $items Language switcher items
     * @return array
     */
    public function wpml_language_switcher_items($items) {
        foreach ($items as &$item) {
            $item['css_classes'] = array('language-item');
        }
        return $items;
    }

    /**
     * Polylang language link
     *
     * @param string $link Language link
     * @param string $lang Language
     * @param string $current Current language
     * @return string
     */
    public function polylang_language_link($link, $lang, $current) {
        $class = ($lang === $current) ? 'current-language' : '';
        return str_replace('<a ', '<a class="' . esc_attr($class) . '" ', $link);
    }

    /**
     * Get manual languages
     *
     * @return array
     */
    private function get_manual_languages() {
        return array(
            'en_US' => array(
                'name' => 'English',
                'flag' => '🇺🇸',
            ),
            'es_ES' => array(
                'name' => 'Español',
                'flag' => '🇪🇸',
            ),
            'fr_FR' => array(
                'name' => 'Français',
                'flag' => '🇫🇷',
            ),
            'de_DE' => array(
                'name' => 'Deutsch',
                'flag' => '🇩🇪',
            ),
            'zh_CN' => array(
                'name' => '中文',
                'flag' => '🇨🇳',
            ),
            'ja' => array(
                'name' => '日本語',
                'flag' => '🇯🇵',
            ),
        );
    }

    /**
     * Get language URL
     *
     * @param string $locale Locale
     * @return string
     */
    private function get_language_url($locale) {
        // This is a simple implementation
        // In a real scenario, you'd want to implement proper language switching
        return add_query_arg('lang', $locale, home_url());
    }
}