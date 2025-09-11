<?php
/**
 * Multilingual Module Bootstrap
 *
 * @package AquaLuxe\Modules\Multilingual
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multilingual Module Class
 */
class AquaLuxe_Multilingual {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('body_class', array($this, 'body_class'));
        add_action('wp_footer', array($this, 'render_language_switcher'));
    }
    
    /**
     * Initialize
     */
    public function init() {
        // Check if WPML or Polylang is active
        if ($this->is_wpml_active() || $this->is_polylang_active()) {
            // Use existing plugin functionality
            return;
        }
        
        // Basic multilingual support
        add_action('wp_head', array($this, 'add_hreflang_tags'));
        add_filter('locale', array($this, 'set_locale'));
    }
    
    /**
     * Check if WPML is active
     */
    private function is_wpml_active() {
        return defined('ICL_SITEPRESS_VERSION');
    }
    
    /**
     * Check if Polylang is active
     */
    private function is_polylang_active() {
        return function_exists('pll_languages_list');
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_localize_script('aqualuxe-script', 'aqualuxe_multilingual', array(
            'current_language' => $this->get_current_language(),
            'available_languages' => $this->get_available_languages(),
            'rtl' => is_rtl(),
        ));
    }
    
    /**
     * Add body class for language
     */
    public function body_class($classes) {
        $language = $this->get_current_language();
        $classes[] = 'lang-' . $language;
        
        if (is_rtl()) {
            $classes[] = 'rtl';
        }
        
        return $classes;
    }
    
    /**
     * Get current language
     */
    public function get_current_language() {
        // WPML support
        if ($this->is_wpml_active()) {
            return ICL_LANGUAGE_CODE;
        }
        
        // Polylang support
        if ($this->is_polylang_active()) {
            return pll_current_language();
        }
        
        // Check URL or cookie
        if (isset($_GET['lang'])) {
            $lang = sanitize_text_field($_GET['lang']);
            setcookie('aqualuxe_language', $lang, time() + (30 * DAY_IN_SECONDS), '/');
            return $lang;
        }
        
        if (isset($_COOKIE['aqualuxe_language'])) {
            return sanitize_text_field($_COOKIE['aqualuxe_language']);
        }
        
        // Browser language detection
        $browser_lang = $this->detect_browser_language();
        if ($browser_lang) {
            return $browser_lang;
        }
        
        return 'en';
    }
    
    /**
     * Get available languages
     */
    public function get_available_languages() {
        // WPML support
        if ($this->is_wpml_active()) {
            return apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
        }
        
        // Polylang support
        if ($this->is_polylang_active()) {
            return pll_languages_list();
        }
        
        // Default supported languages
        return array(
            'en' => array(
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇺🇸',
            ),
            'es' => array(
                'code' => 'es',
                'name' => 'Spanish',
                'native_name' => 'Español',
                'flag' => '🇪🇸',
            ),
            'fr' => array(
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'flag' => '🇫🇷',
            ),
            'de' => array(
                'code' => 'de',
                'name' => 'German',
                'native_name' => 'Deutsch',
                'flag' => '🇩🇪',
            ),
            'it' => array(
                'code' => 'it',
                'name' => 'Italian',
                'native_name' => 'Italiano',
                'flag' => '🇮🇹',
            ),
            'pt' => array(
                'code' => 'pt',
                'name' => 'Portuguese',
                'native_name' => 'Português',
                'flag' => '🇵🇹',
            ),
            'ru' => array(
                'code' => 'ru',
                'name' => 'Russian',
                'native_name' => 'Русский',
                'flag' => '🇷🇺',
            ),
            'zh' => array(
                'code' => 'zh',
                'name' => 'Chinese',
                'native_name' => '中文',
                'flag' => '🇨🇳',
            ),
            'ja' => array(
                'code' => 'ja',
                'name' => 'Japanese',
                'native_name' => '日本語',
                'flag' => '🇯🇵',
            ),
            'ar' => array(
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
            ),
        );
    }
    
    /**
     * Detect browser language
     */
    private function detect_browser_language() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return false;
        }
        
        $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $available_languages = array_keys($this->get_available_languages());
        
        foreach ($langs as $lang) {
            $lang = substr(trim($lang), 0, 2);
            if (in_array($lang, $available_languages)) {
                return $lang;
            }
        }
        
        return false;
    }
    
    /**
     * Set locale based on language
     */
    public function set_locale($locale) {
        $language = $this->get_current_language();
        
        $locale_map = array(
            'en' => 'en_US',
            'es' => 'es_ES',
            'fr' => 'fr_FR',
            'de' => 'de_DE',
            'it' => 'it_IT',
            'pt' => 'pt_PT',
            'ru' => 'ru_RU',
            'zh' => 'zh_CN',
            'ja' => 'ja',
            'ar' => 'ar',
        );
        
        return isset($locale_map[$language]) ? $locale_map[$language] : $locale;
    }
    
    /**
     * Add hreflang tags
     */
    public function add_hreflang_tags() {
        $languages = $this->get_available_languages();
        $current_url = home_url($_SERVER['REQUEST_URI']);
        
        foreach ($languages as $lang_code => $language) {
            $lang_url = add_query_arg('lang', $lang_code, $current_url);
            echo '<link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' . esc_url($lang_url) . '">' . "\n";
        }
    }
    
    /**
     * Render language switcher
     */
    public function render_language_switcher() {
        if ($this->is_wpml_active() || $this->is_polylang_active()) {
            return; // Let the plugin handle it
        }
        
        $languages = $this->get_available_languages();
        $current_language = $this->get_current_language();
        
        if (count($languages) <= 1) {
            return;
        }
        
        ?>
        <div id="language-switcher" class="fixed bottom-6 left-6 z-50">
            <div class="language-switcher-dropdown">
                <button class="language-toggle" aria-expanded="false">
                    <span class="current-language">
                        <?php 
                        echo isset($languages[$current_language]['flag']) ? $languages[$current_language]['flag'] : '🌐';
                        echo ' ' . (isset($languages[$current_language]['code']) ? strtoupper($languages[$current_language]['code']) : 'EN');
                        ?>
                    </span>
                    <span class="arrow">▼</span>
                </button>
                <ul class="language-list" aria-hidden="true">
                    <?php foreach ($languages as $lang_code => $language) : ?>
                        <?php if ($lang_code !== $current_language) : ?>
                            <li>
                                <a href="<?php echo esc_url(add_query_arg('lang', $lang_code)); ?>" 
                                   data-language="<?php echo esc_attr($lang_code); ?>"
                                   class="language-option">
                                    <span class="flag"><?php echo isset($language['flag']) ? $language['flag'] : '🌐'; ?></span>
                                    <span class="name"><?php echo esc_html(isset($language['native_name']) ? $language['native_name'] : $language['name']); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <style>
        #language-switcher {
            font-size: 14px;
        }
        
        .language-switcher-dropdown {
            position: relative;
        }
        
        .language-toggle {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .language-list {
            position: absolute;
            bottom: 100%;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            list-style: none;
            margin: 0;
            padding: 0;
            min-width: 150px;
            display: none;
        }
        
        .language-list[aria-hidden="false"] {
            display: block;
        }
        
        .language-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        
        .language-option:hover {
            background: #f5f5f5;
        }
        
        .language-option:last-child {
            border-bottom: none;
        }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.language-toggle');
            const list = document.querySelector('.language-list');
            
            if (toggle && list) {
                toggle.addEventListener('click', function() {
                    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isOpen);
                    list.setAttribute('aria-hidden', isOpen);
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#language-switcher')) {
                        toggle.setAttribute('aria-expanded', 'false');
                        list.setAttribute('aria-hidden', 'true');
                    }
                });
            }
        });
        </script>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Multilingual();