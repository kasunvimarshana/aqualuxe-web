<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Load theme textdomain
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
    
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_wpml_scripts');
        add_filter('body_class', 'aqualuxe_wpml_body_class');
    }
    
    // Check if Polylang is active
    if (defined('POLYLANG_VERSION')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_polylang_scripts');
        add_filter('body_class', 'aqualuxe_polylang_body_class');
    }
}
add_action('after_setup_theme', 'aqualuxe_multilingual_init');

/**
 * Enqueue WPML scripts
 */
function aqualuxe_wpml_scripts() {
    wp_enqueue_style('aqualuxe-wpml', AQUALUXE_ASSETS_URI . 'css/wpml.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WPML body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_wpml_body_class($classes) {
    $classes[] = 'wpml-active';
    
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (count($languages) > 1) {
            $classes[] = 'wpml-multiple-languages';
        }
    }
    
    return $classes;
}

/**
 * Enqueue Polylang scripts
 */
function aqualuxe_polylang_scripts() {
    wp_enqueue_style('aqualuxe-polylang', AQUALUXE_ASSETS_URI . 'css/polylang.css', array(), AQUALUXE_VERSION);
}

/**
 * Add Polylang body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_polylang_body_class($classes) {
    $classes[] = 'polylang-active';
    
    if (function_exists('pll_languages_list')) {
        $languages = pll_languages_list();
        
        if (count($languages) > 1) {
            $classes[] = 'polylang-multiple-languages';
        }
    }
    
    return $classes;
}

/**
 * Get translated page ID
 *
 * @param int $page_id
 * @param string $language
 * @return int
 */
function aqualuxe_get_translated_page_id($page_id, $language = '') {
    // Check if WPML is active
    if (function_exists('icl_object_id')) {
        return icl_object_id($page_id, 'page', true, $language);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_get_post')) {
        return pll_get_post($page_id, $language);
    }
    
    return $page_id;
}

/**
 * Get translated term ID
 *
 * @param int $term_id
 * @param string $taxonomy
 * @param string $language
 * @return int
 */
function aqualuxe_get_translated_term_id($term_id, $taxonomy, $language = '') {
    // Check if WPML is active
    if (function_exists('icl_object_id')) {
        return icl_object_id($term_id, $taxonomy, true, $language);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_get_term')) {
        return pll_get_term($term_id, $language);
    }
    
    return $term_id;
}

/**
 * Register strings for translation
 *
 * @param string $name
 * @param string $value
 * @param string $context
 * @return void
 */
function aqualuxe_register_string($name, $value, $context = 'AquaLuxe') {
    // Check if WPML is active
    if (function_exists('icl_register_string')) {
        icl_register_string($context, $name, $value);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_register_string')) {
        pll_register_string($name, $value, $context);
    }
}

/**
 * Translate registered string
 *
 * @param string $name
 * @param string $value
 * @param string $context
 * @return string
 */
function aqualuxe_translate_string($name, $value, $context = 'AquaLuxe') {
    // Check if WPML is active
    if (function_exists('icl_t')) {
        return icl_t($context, $name, $value);
    }
    
    // Check if Polylang is active
    if (function_exists('pll__')) {
        return pll__($value);
    }
    
    return $value;
}

/**
 * Register theme strings for translation
 */
function aqualuxe_register_theme_strings() {
    // Register customizer strings
    $options = get_option('aqualuxe_options', array());
    
    if (!empty($options)) {
        // Homepage strings
        if (isset($options['homepage_hero_title'])) {
            aqualuxe_register_string('homepage_hero_title', $options['homepage_hero_title']);
        }
        
        if (isset($options['homepage_hero_description'])) {
            aqualuxe_register_string('homepage_hero_description', $options['homepage_hero_description']);
        }
        
        if (isset($options['homepage_hero_button_text'])) {
            aqualuxe_register_string('homepage_hero_button_text', $options['homepage_hero_button_text']);
        }
        
        if (isset($options['homepage_featured_products_title'])) {
            aqualuxe_register_string('homepage_featured_products_title', $options['homepage_featured_products_title']);
        }
        
        if (isset($options['homepage_featured_products_description'])) {
            aqualuxe_register_string('homepage_featured_products_description', $options['homepage_featured_products_description']);
        }
        
        if (isset($options['homepage_categories_title'])) {
            aqualuxe_register_string('homepage_categories_title', $options['homepage_categories_title']);
        }
        
        if (isset($options['homepage_categories_description'])) {
            aqualuxe_register_string('homepage_categories_description', $options['homepage_categories_description']);
        }
        
        if (isset($options['homepage_services_title'])) {
            aqualuxe_register_string('homepage_services_title', $options['homepage_services_title']);
        }
        
        if (isset($options['homepage_services_description'])) {
            aqualuxe_register_string('homepage_services_description', $options['homepage_services_description']);
        }
        
        if (isset($options['homepage_testimonials_title'])) {
            aqualuxe_register_string('homepage_testimonials_title', $options['homepage_testimonials_title']);
        }
        
        if (isset($options['homepage_testimonials_description'])) {
            aqualuxe_register_string('homepage_testimonials_description', $options['homepage_testimonials_description']);
        }
        
        if (isset($options['homepage_blog_title'])) {
            aqualuxe_register_string('homepage_blog_title', $options['homepage_blog_title']);
        }
        
        if (isset($options['homepage_blog_description'])) {
            aqualuxe_register_string('homepage_blog_description', $options['homepage_blog_description']);
        }
        
        if (isset($options['homepage_newsletter_title'])) {
            aqualuxe_register_string('homepage_newsletter_title', $options['homepage_newsletter_title']);
        }
        
        if (isset($options['homepage_newsletter_description'])) {
            aqualuxe_register_string('homepage_newsletter_description', $options['homepage_newsletter_description']);
        }
        
        // Footer strings
        if (isset($options['copyright_text'])) {
            aqualuxe_register_string('copyright_text', $options['copyright_text']);
        }
        
        // Blog strings
        if (isset($options['blog_read_more_text'])) {
            aqualuxe_register_string('blog_read_more_text', $options['blog_read_more_text']);
        }
        
        if (isset($options['related_posts_title'])) {
            aqualuxe_register_string('related_posts_title', $options['related_posts_title']);
        }
    }
    
    // Register services strings
    $services = isset($options['homepage_services']) ? $options['homepage_services'] : array();
    
    if (!empty($services)) {
        foreach ($services as $index => $service) {
            if (isset($service['title'])) {
                aqualuxe_register_string('service_title_' . $index, $service['title']);
            }
            
            if (isset($service['description'])) {
                aqualuxe_register_string('service_description_' . $index, $service['description']);
            }
        }
    }
    
    // Register testimonials strings
    $testimonials = isset($options['homepage_testimonials']) ? $options['homepage_testimonials'] : array();
    
    if (!empty($testimonials)) {
        foreach ($testimonials as $index => $testimonial) {
            if (isset($testimonial['name'])) {
                aqualuxe_register_string('testimonial_name_' . $index, $testimonial['name']);
            }
            
            if (isset($testimonial['position'])) {
                aqualuxe_register_string('testimonial_position_' . $index, $testimonial['position']);
            }
            
            if (isset($testimonial['content'])) {
                aqualuxe_register_string('testimonial_content_' . $index, $testimonial['content']);
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_register_theme_strings');

/**
 * Get translated theme option
 *
 * @param string $option_name
 * @param mixed $default
 * @return mixed
 */
function aqualuxe_get_translated_option($option_name, $default = '') {
    $options = get_option('aqualuxe_options', array());
    $value = isset($options[$option_name]) ? $options[$option_name] : $default;
    
    return aqualuxe_translate_string($option_name, $value);
}

/**
 * Get language switcher
 *
 * @param array $args
 * @return string
 */
function aqualuxe_get_language_switcher($args = array()) {
    $defaults = array(
        'echo' => false,
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $output = '';
    
    // Check if WPML is active
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (!empty($languages)) {
            $output .= '<div class="aqualuxe-language-switcher">';
            $output .= '<ul class="aqualuxe-language-switcher-list">';
            
            foreach ($languages as $language) {
                $output .= '<li class="aqualuxe-language-switcher-item' . ($language['active'] ? ' active' : '') . '">';
                $output .= '<a href="' . esc_url($language['url']) . '" class="aqualuxe-language-switcher-link">';
                
                if (!empty($language['country_flag_url'])) {
                    $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" class="aqualuxe-language-switcher-flag" />';
                }
                
                $output .= '<span class="aqualuxe-language-switcher-name">' . esc_html($language['native_name']) . '</span>';
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
        }
    }
    
    // Check if Polylang is active
    elseif (function_exists('pll_the_languages')) {
        ob_start();
        pll_the_languages(array(
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
            'hide_if_empty' => 0,
        ));
        $output = ob_get_clean();
        
        if (!empty($output)) {
            $output = '<div class="aqualuxe-language-switcher">' . $output . '</div>';
        }
    }
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}