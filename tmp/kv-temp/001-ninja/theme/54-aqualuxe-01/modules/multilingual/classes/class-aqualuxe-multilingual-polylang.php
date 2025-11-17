<?php
/**
 * AquaLuxe Polylang Compatibility
 *
 * @package AquaLuxe
 * @subpackage Multilingual
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Polylang Compatibility Class
 * 
 * Provides compatibility with Polylang plugin
 */
class AquaLuxe_Multilingual_Polylang {
    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Filter language switcher
        add_filter('aqualuxe_language_switcher_output', [$this, 'polylang_language_switcher'], 10, 2);
        
        // Filter current language
        add_filter('aqualuxe_current_language', [$this, 'polylang_current_language']);
        
        // Filter language URL
        add_filter('aqualuxe_language_url', [$this, 'polylang_language_url'], 10, 2);
        
        // Register strings for translation
        add_action('init', [$this, 'register_strings'], 20);
        
        // Filter strings
        add_filter('aqualuxe_translate_string', [$this, 'translate_string'], 10, 2);
        
        // Add admin notice if Polylang for WooCommerce is not active
        add_action('admin_notices', [$this, 'check_woocommerce_compatibility']);
        
        // Add compatibility with WooCommerce
        if (aqualuxe_is_woocommerce_active()) {
            $this->add_woocommerce_compatibility();
        }
    }

    /**
     * Polylang language switcher
     *
     * @param string $output Language switcher HTML
     * @param string $style Switcher style
     * @return string
     */
    public function polylang_language_switcher($output, $style) {
        // Check if pll_the_languages function exists
        if (!function_exists('pll_the_languages')) {
            return $output;
        }
        
        // Set up arguments based on style
        $args = [
            'echo' => 0,
            'hide_if_empty' => 1,
        ];
        
        switch ($style) {
            case 'dropdown':
                $args['dropdown'] = 1;
                break;
            case 'flags':
                $args['show_flags'] = 1;
                $args['show_names'] = 0;
                break;
            case 'text':
                $args['show_flags'] = 0;
                $args['show_names'] = 1;
                break;
        }
        
        // Get Polylang language switcher
        $polylang_output = pll_the_languages($args);
        
        // Return Polylang output if not empty
        if (!empty($polylang_output)) {
            return '<div class="aqualuxe-language-switcher aqualuxe-language-switcher-' . esc_attr($style) . '">' . $polylang_output . '</div>';
        }
        
        // Return original output as fallback
        return $output;
    }

    /**
     * Polylang current language
     *
     * @param string $language Current language
     * @return string
     */
    public function polylang_current_language($language) {
        if (function_exists('pll_current_language')) {
            $current = pll_current_language('locale');
            
            if ($current) {
                return $current;
            }
        }
        
        return $language;
    }

    /**
     * Polylang language URL
     *
     * @param string $url URL
     * @param string $language Language code
     * @return string
     */
    public function polylang_language_url($url, $language) {
        // Check if we're on a post/page
        if (is_singular() && function_exists('pll_get_post')) {
            $post_id = get_the_ID();
            
            // Get translation
            $translated_id = pll_get_post($post_id, $language);
            
            if ($translated_id) {
                return get_permalink($translated_id);
            }
        }
        // Check if we're on a term archive
        elseif ((is_tax() || is_category() || is_tag()) && function_exists('pll_get_term')) {
            $term = get_queried_object();
            
            // Get translation
            $translated_id = pll_get_term($term->term_id, $language);
            
            if ($translated_id) {
                return get_term_link($translated_id, $term->taxonomy);
            }
        }
        
        // Use Polylang's home URL
        if (function_exists('pll_home_url')) {
            return pll_home_url($language);
        }
        
        return $url;
    }

    /**
     * Register strings for translation
     */
    public function register_strings() {
        // Check if pll_register_string function exists
        if (!function_exists('pll_register_string')) {
            return;
        }
        
        // Register theme options
        $this->register_theme_options();
        
        // Register customizer options
        $this->register_customizer_options();
        
        // Register widget texts
        $this->register_widget_texts();
    }

    /**
     * Register theme options for translation
     */
    private function register_theme_options() {
        // Get theme settings
        $settings = aqualuxe_get_theme_settings();
        
        // Register translatable settings
        $translatable_settings = [
            'site_tagline',
            'footer_copyright',
            'footer_credits',
            'search_placeholder',
            'blog_read_more_text',
            'product_quick_view_text',
            'product_add_to_cart_text',
            'product_view_cart_text',
            'product_out_of_stock_text',
            'product_sale_text',
            'product_new_text',
            'product_featured_text',
        ];
        
        foreach ($translatable_settings as $key) {
            if (isset($settings[$key])) {
                pll_register_string('aqualuxe_theme_setting_' . $key, $settings[$key], 'AquaLuxe Theme');
            }
        }
    }

    /**
     * Register customizer options for translation
     */
    private function register_customizer_options() {
        // Get customizer settings
        $mods = get_theme_mods();
        
        // Register translatable settings
        $translatable_mods = [
            'aqualuxe_header_phone',
            'aqualuxe_header_email',
            'aqualuxe_header_address',
            'aqualuxe_header_cta_text',
            'aqualuxe_footer_text',
            'aqualuxe_footer_copyright',
            'aqualuxe_404_title',
            'aqualuxe_404_text',
            'aqualuxe_404_button_text',
        ];
        
        foreach ($translatable_mods as $key) {
            if (isset($mods[$key])) {
                pll_register_string('aqualuxe_customizer_' . $key, $mods[$key], 'AquaLuxe Customizer');
            }
        }
    }

    /**
     * Register widget texts for translation
     */
    private function register_widget_texts() {
        // Get all widgets
        $widgets = get_option('sidebars_widgets');
        
        if (!is_array($widgets)) {
            return;
        }
        
        // Loop through widget areas
        foreach ($widgets as $widget_area => $widget_list) {
            // Skip non-widget areas
            if ($widget_area === 'array_version' || !is_array($widget_list)) {
                continue;
            }
            
            // Loop through widgets
            foreach ($widget_list as $widget) {
                // Get widget type and ID
                $widget_type = preg_replace('/-[0-9]+$/', '', $widget);
                $widget_id = str_replace($widget_type . '-', '', $widget);
                
                // Get widget options
                $widget_options = get_option('widget_' . $widget_type);
                
                // Skip if no options found
                if (!isset($widget_options[$widget_id])) {
                    continue;
                }
                
                // Register text fields
                $this->register_widget_text_fields($widget_type, $widget_options[$widget_id], $widget);
            }
        }
    }

    /**
     * Register widget text fields for translation
     *
     * @param string $widget_type Widget type
     * @param array $widget_options Widget options
     * @param string $widget_id Widget ID
     */
    private function register_widget_text_fields($widget_type, $widget_options, $widget_id) {
        // Common text fields
        $text_fields = ['title', 'text', 'content', 'more_text', 'button_text'];
        
        // Register text fields
        foreach ($text_fields as $field) {
            if (isset($widget_options[$field])) {
                pll_register_string(
                    'aqualuxe_widget_' . $widget_type . '_' . $field . '_' . $widget_id,
                    $widget_options[$field],
                    'AquaLuxe Widgets'
                );
            }
        }
    }

    /**
     * Translate string
     *
     * @param string $string String to translate
     * @param string $context String context
     * @return string
     */
    public function translate_string($string, $context) {
        if (function_exists('pll__')) {
            return pll__($string);
        }
        
        return $string;
    }

    /**
     * Check if Polylang for WooCommerce is active
     */
    public function check_woocommerce_compatibility() {
        // Check if we're on the plugins page or theme settings page
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, ['plugins', 'appearance_page_aqualuxe-settings'])) {
            return;
        }
        
        // Check if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        // Check if Polylang for WooCommerce is active
        if (!defined('PLLWC_VERSION')) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf(
                __('AquaLuxe Theme: Polylang for WooCommerce is not active. For full multilingual e-commerce support, please %sinstall Polylang for WooCommerce%s.', 'aqualuxe'),
                '<a href="' . admin_url('plugin-install.php?s=polylang+for+woocommerce&tab=search&type=term') . '">',
                '</a>'
            ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Add WooCommerce compatibility
     */
    private function add_woocommerce_compatibility() {
        // Check if Polylang for WooCommerce is active
        if (defined('PLLWC_VERSION')) {
            return; // Let Polylang for WooCommerce handle compatibility
        }
        
        // Basic WooCommerce compatibility
        add_filter('woocommerce_product_get_name', [$this, 'translate_product_name'], 10, 2);
        add_filter('woocommerce_product_get_short_description', [$this, 'translate_product_short_description'], 10, 2);
        add_filter('woocommerce_product_get_description', [$this, 'translate_product_description'], 10, 2);
        
        // Filter category strings
        add_filter('woocommerce_product_category_title', [$this, 'translate_term_name'], 10, 2);
        
        // Filter attribute strings
        add_filter('woocommerce_attribute_label', [$this, 'translate_attribute_label'], 10, 2);
    }

    /**
     * Translate product name
     *
     * @param string $name Product name
     * @param object $product Product object
     * @return string
     */
    public function translate_product_name($name, $product) {
        if (function_exists('pll_get_post')) {
            $current_lang = pll_current_language();
            $translated_id = pll_get_post($product->get_id(), $current_lang);
            
            if ($translated_id && $translated_id !== $product->get_id()) {
                $translated_product = wc_get_product($translated_id);
                
                if ($translated_product) {
                    return $translated_product->get_name();
                }
            }
        }
        
        return $name;
    }

    /**
     * Translate product short description
     *
     * @param string $short_description Product short description
     * @param object $product Product object
     * @return string
     */
    public function translate_product_short_description($short_description, $product) {
        if (function_exists('pll_get_post')) {
            $current_lang = pll_current_language();
            $translated_id = pll_get_post($product->get_id(), $current_lang);
            
            if ($translated_id && $translated_id !== $product->get_id()) {
                $translated_product = wc_get_product($translated_id);
                
                if ($translated_product) {
                    return $translated_product->get_short_description();
                }
            }
        }
        
        return $short_description;
    }

    /**
     * Translate product description
     *
     * @param string $description Product description
     * @param object $product Product object
     * @return string
     */
    public function translate_product_description($description, $product) {
        if (function_exists('pll_get_post')) {
            $current_lang = pll_current_language();
            $translated_id = pll_get_post($product->get_id(), $current_lang);
            
            if ($translated_id && $translated_id !== $product->get_id()) {
                $translated_product = wc_get_product($translated_id);
                
                if ($translated_product) {
                    return $translated_product->get_description();
                }
            }
        }
        
        return $description;
    }

    /**
     * Translate term name
     *
     * @param string $name Term name
     * @param object $term Term object
     * @return string
     */
    public function translate_term_name($name, $term) {
        if (function_exists('pll_get_term')) {
            $current_lang = pll_current_language();
            $translated_id = pll_get_term($term->term_id, $current_lang);
            
            if ($translated_id && $translated_id !== $term->term_id) {
                $translated_term = get_term($translated_id, $term->taxonomy);
                
                if (!is_wp_error($translated_term)) {
                    return $translated_term->name;
                }
            }
        }
        
        return $name;
    }

    /**
     * Translate attribute label
     *
     * @param string $label Attribute label
     * @param string $name Attribute name
     * @return string
     */
    public function translate_attribute_label($label, $name) {
        if (function_exists('pll__')) {
            return pll__($label);
        }
        
        return $label;
    }
}