<?php
/**
 * AquaLuxe WPML Compatibility
 *
 * @package AquaLuxe
 * @subpackage Multilingual
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe WPML Compatibility Class
 * 
 * Provides compatibility with WPML plugin
 */
class AquaLuxe_Multilingual_WPML {
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
        add_filter('aqualuxe_language_switcher_output', [$this, 'wpml_language_switcher'], 10, 2);
        
        // Filter current language
        add_filter('aqualuxe_current_language', [$this, 'wpml_current_language']);
        
        // Filter language URL
        add_filter('aqualuxe_language_url', [$this, 'wpml_language_url'], 10, 2);
        
        // Add theme options
        add_action('wpml_after_tm_loaded', [$this, 'register_strings']);
        
        // Filter strings
        add_filter('aqualuxe_translate_string', [$this, 'translate_string'], 10, 2);
        
        // Add admin notice if WPML String Translation is not active
        add_action('admin_notices', [$this, 'check_string_translation']);
        
        // Add compatibility with WooCommerce
        if (aqualuxe_is_woocommerce_active()) {
            $this->add_woocommerce_compatibility();
        }
    }

    /**
     * WPML language switcher
     *
     * @param string $output Language switcher HTML
     * @param string $style Switcher style
     * @return string
     */
    public function wpml_language_switcher($output, $style) {
        // Use WPML language switcher
        $args = [];
        
        switch ($style) {
            case 'dropdown':
                $args = [
                    'type' => 'dropdown',
                ];
                break;
            case 'flags':
                $args = [
                    'type' => 'custom',
                    'flags' => 1,
                    'native' => 0,
                    'display_link_for_current_lang' => 0,
                ];
                break;
            case 'text':
                $args = [
                    'type' => 'custom',
                    'flags' => 0,
                    'native' => 1,
                    'display_link_for_current_lang' => 0,
                ];
                break;
        }
        
        // Add custom class
        $args['after'] = '</div>';
        $args['before'] = '<div class="aqualuxe-language-switcher aqualuxe-language-switcher-' . esc_attr($style) . '">';
        
        // Get WPML language switcher
        ob_start();
        do_action('wpml_language_switcher', $args);
        $wpml_output = ob_get_clean();
        
        // Return WPML output if not empty
        if (!empty($wpml_output)) {
            return $wpml_output;
        }
        
        // Return original output as fallback
        return $output;
    }

    /**
     * WPML current language
     *
     * @param string $language Current language
     * @return string
     */
    public function wpml_current_language($language) {
        return apply_filters('wpml_current_language', $language);
    }

    /**
     * WPML language URL
     *
     * @param string $url URL
     * @param string $language Language code
     * @return string
     */
    public function wpml_language_url($url, $language) {
        global $sitepress;
        
        // Check if we're on a post/page
        if (is_singular()) {
            $post_id = get_the_ID();
            $post_type = get_post_type($post_id);
            
            // Get translation
            $translated_id = apply_filters('wpml_object_id', $post_id, $post_type, false, $language);
            
            if ($translated_id) {
                return get_permalink($translated_id);
            }
        }
        // Check if we're on a term archive
        elseif (is_tax() || is_category() || is_tag()) {
            $term = get_queried_object();
            
            // Get translation
            $translated_id = apply_filters('wpml_object_id', $term->term_id, $term->taxonomy, false, $language);
            
            if ($translated_id) {
                return get_term_link($translated_id, $term->taxonomy);
            }
        }
        
        // Use WPML's language URL
        return apply_filters('wpml_permalink', $url, $language);
    }

    /**
     * Register strings for translation
     */
    public function register_strings() {
        // Check if WPML String Translation is active
        if (!function_exists('icl_register_string')) {
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
                do_action('wpml_register_single_string', 'aqualuxe', 'Theme Setting: ' . $key, $settings[$key]);
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
                do_action('wpml_register_single_string', 'aqualuxe', 'Customizer: ' . $key, $mods[$key]);
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
                do_action(
                    'wpml_register_single_string',
                    'aqualuxe',
                    'Widget: ' . $widget_type . ' - ' . $field . ' (' . $widget_id . ')',
                    $widget_options[$field]
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
        return apply_filters('wpml_translate_single_string', $string, 'aqualuxe', $context);
    }

    /**
     * Check if WPML String Translation is active
     */
    public function check_string_translation() {
        // Check if we're on the plugins page or theme settings page
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, ['plugins', 'appearance_page_aqualuxe-settings'])) {
            return;
        }
        
        // Check if WPML String Translation is active
        if (!defined('WPML_ST_VERSION')) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf(
                __('AquaLuxe Theme: WPML String Translation is not active. For full multilingual support, please %sactivate WPML String Translation%s.', 'aqualuxe'),
                '<a href="' . admin_url('plugins.php') . '">',
                '</a>'
            ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Add WooCommerce compatibility
     */
    private function add_woocommerce_compatibility() {
        // Filter product strings
        add_filter('woocommerce_product_title', [$this, 'translate_product_title'], 10, 2);
        add_filter('woocommerce_product_get_short_description', [$this, 'translate_product_short_description'], 10, 2);
        add_filter('woocommerce_product_get_description', [$this, 'translate_product_description'], 10, 2);
        
        // Filter category strings
        add_filter('woocommerce_product_category_title', [$this, 'translate_term_name'], 10, 2);
        
        // Filter attribute strings
        add_filter('woocommerce_attribute_label', [$this, 'translate_attribute_label'], 10, 2);
        
        // Filter variation strings
        add_filter('woocommerce_variation_option_name', [$this, 'translate_variation_option_name']);
    }

    /**
     * Translate product title
     *
     * @param string $title Product title
     * @param object $product Product object
     * @return string
     */
    public function translate_product_title($title, $product) {
        // Use WPML's translated title
        $translated_product_id = apply_filters('wpml_object_id', $product->get_id(), 'product', true);
        
        if ($translated_product_id !== $product->get_id()) {
            $translated_product = wc_get_product($translated_product_id);
            
            if ($translated_product) {
                return $translated_product->get_title();
            }
        }
        
        return $title;
    }

    /**
     * Translate product short description
     *
     * @param string $short_description Product short description
     * @param object $product Product object
     * @return string
     */
    public function translate_product_short_description($short_description, $product) {
        // Use WPML's translated short description
        $translated_product_id = apply_filters('wpml_object_id', $product->get_id(), 'product', true);
        
        if ($translated_product_id !== $product->get_id()) {
            $translated_product = wc_get_product($translated_product_id);
            
            if ($translated_product) {
                return $translated_product->get_short_description();
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
        // Use WPML's translated description
        $translated_product_id = apply_filters('wpml_object_id', $product->get_id(), 'product', true);
        
        if ($translated_product_id !== $product->get_id()) {
            $translated_product = wc_get_product($translated_product_id);
            
            if ($translated_product) {
                return $translated_product->get_description();
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
        // Use WPML's translated term name
        $translated_term_id = apply_filters('wpml_object_id', $term->term_id, $term->taxonomy, true);
        
        if ($translated_term_id !== $term->term_id) {
            $translated_term = get_term($translated_term_id, $term->taxonomy);
            
            if (!is_wp_error($translated_term)) {
                return $translated_term->name;
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
        // Check if it's a taxonomy attribute
        if (taxonomy_exists(wc_attribute_taxonomy_name($name))) {
            $attribute_taxonomy = wc_get_attribute_taxonomy($name);
            
            if ($attribute_taxonomy) {
                // Use WPML's translated attribute label
                return apply_filters('wpml_translate_single_string', $attribute_taxonomy->attribute_label, 'WordPress', 'taxonomy singular name: ' . $attribute_taxonomy->attribute_label);
            }
        }
        
        return $label;
    }

    /**
     * Translate variation option name
     *
     * @param string $name Variation option name
     * @return string
     */
    public function translate_variation_option_name($name) {
        // Check if it's a term
        $term = get_term_by('name', $name, 'pa_' . sanitize_title($name));
        
        if ($term && !is_wp_error($term)) {
            // Use WPML's translated term name
            $translated_term_id = apply_filters('wpml_object_id', $term->term_id, $term->taxonomy, true);
            
            if ($translated_term_id !== $term->term_id) {
                $translated_term = get_term($translated_term_id, $term->taxonomy);
                
                if (!is_wp_error($translated_term)) {
                    return $translated_term->name;
                }
            }
        }
        
        return $name;
    }
}