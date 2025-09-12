<?php
/**
 * Multilingual Module
 * 
 * Handles multilingual functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Multilingual_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_head', [$this, 'add_meta_tags']);
        add_filter('language_attributes', [$this, 'language_attributes']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        // Check for WPML or Polylang
        if ($this->is_wpml_active() || $this->is_polylang_active()) {
            add_action('wp_footer', [$this, 'language_switcher']);
        }
    }
    
    /**
     * Check if WPML is active
     */
    private function is_wpml_active() {
        return function_exists('icl_get_languages');
    }
    
    /**
     * Check if Polylang is active
     */
    private function is_polylang_active() {
        return function_exists('pll_the_languages');
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if ($this->is_wpml_active() || $this->is_polylang_active()) {
            // Only enqueue if files exist
            $css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/modules/multilingual.css';
            $js_file = AQUALUXE_THEME_DIR . '/assets/dist/js/modules/multilingual.js';
            
            if (file_exists($css_file)) {
                wp_enqueue_style(
                    'aqualuxe-multilingual',
                    AQUALUXE_ASSETS_URI . '/css/modules/multilingual.css',
                    [],
                    AQUALUXE_VERSION
                );
            }
            
            if (file_exists($js_file)) {
                wp_enqueue_script(
                    'aqualuxe-multilingual',
                    AQUALUXE_ASSETS_URI . '/js/modules/multilingual.js',
                    ['jquery'],
                    AQUALUXE_VERSION,
                    true
                );
            }
        }
    }
    
    /**
     * Add language meta tags
     */
    public function add_meta_tags() {
        if ($this->is_wpml_active()) {
            $this->wpml_meta_tags();
        } elseif ($this->is_polylang_active()) {
            $this->polylang_meta_tags();
        }
    }
    
    /**
     * WPML meta tags
     */
    private function wpml_meta_tags() {
        if (function_exists('icl_get_languages') && defined('ICL_LANGUAGE_CODE')) {
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            if (!empty($languages)) {
                foreach ($languages as $language) {
                    if ($language['url'] && $language['code'] !== ICL_LANGUAGE_CODE) {
                        echo '<link rel="alternate" hreflang="' . esc_attr($language['code']) . '" href="' . esc_url($language['url']) . '" />' . "\n";
                    }
                }
            }
        }
    }
    
    /**
     * Polylang meta tags
     */
    private function polylang_meta_tags() {
        if (function_exists('pll_the_languages') && function_exists('pll_current_language')) {
            $languages = pll_the_languages(['raw' => 1]);
            if (!empty($languages)) {
                foreach ($languages as $language) {
                    if ($language['url'] && $language['slug'] !== pll_current_language()) {
                        echo '<link rel="alternate" hreflang="' . esc_attr($language['slug']) . '" href="' . esc_url($language['url']) . '" />' . "\n";
                    }
                }
            }
        }
    }
    
    /**
     * Language attributes
     */
    public function language_attributes($output) {
        if ($this->is_wpml_active() && defined('ICL_LANGUAGE_CODE')) {
            $output = str_replace('lang="' . get_bloginfo('language') . '"', 'lang="' . ICL_LANGUAGE_CODE . '"', $output);
        } elseif ($this->is_polylang_active() && function_exists('pll_current_language')) {
            $current_lang = pll_current_language();
            if ($current_lang) {
                $output = str_replace('lang="' . get_bloginfo('language') . '"', 'lang="' . $current_lang . '"', $output);
            }
        }
        return $output;
    }
    
    /**
     * Language switcher
     */
    public function language_switcher() {
        if (!aqualuxe_get_option('show_language_switcher', true)) {
            return;
        }
        
        $languages = $this->get_languages();
        if (empty($languages)) {
            return;
        }
        
        ?>
        <div id="language-switcher" class="fixed bottom-4 right-4 z-50 lg:hidden">
            <div class="relative">
                <button id="language-toggle" class="bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-globe"></i>
                </button>
                <div id="language-menu" class="absolute bottom-full right-0 mb-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 min-w-32 hidden">
                    <?php foreach ($languages as $language): ?>
                        <a href="<?php echo esc_url($language['url']); ?>" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo $language['current'] ? 'font-semibold text-primary-600' : ''; ?>">
                            <span class="flag-icon flag-icon-<?php echo esc_attr($language['country_code']); ?> mr-2"></span>
                            <?php echo esc_html($language['native_name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get languages array
     */
    private function get_languages() {
        $languages = [];
        
        if ($this->is_wpml_active()) {
            $wpml_languages = icl_get_languages('skip_missing=0&orderby=code');
            if (!empty($wpml_languages)) {
                foreach ($wpml_languages as $language) {
                    $languages[] = [
                        'code' => $language['code'],
                        'country_code' => strtolower($language['code']),
                        'name' => $language['translated_name'],
                        'native_name' => $language['native_name'],
                        'url' => $language['url'],
                        'current' => $language['active']
                    ];
                }
            }
        } elseif ($this->is_polylang_active()) {
            $pll_languages = pll_the_languages(['raw' => 1]);
            if (!empty($pll_languages)) {
                foreach ($pll_languages as $language) {
                    $languages[] = [
                        'code' => $language['slug'],
                        'country_code' => strtolower($language['slug']),
                        'name' => $language['name'],
                        'native_name' => $language['name'],
                        'url' => $language['url'],
                        'current' => $language['current_lang']
                    ];
                }
            }
        }
        
        return $languages;
    }
}