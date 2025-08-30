<?php
/**
 * AquaLuxe Assets Class
 *
 * Handles all asset loading (CSS, JS, fonts) for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Assets
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Assets
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add preconnect for Google Fonts
        add_filter('wp_resource_hints', array($this, 'resource_hints'), 10, 2);
        
        // Add async/defer attributes to scripts
        add_filter('script_loader_tag', array($this, 'script_loader_tag'), 10, 2);
        
        // Add editor styles
        add_action('after_setup_theme', array($this, 'add_editor_styles'));
        
        // Add admin styles
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue Font Awesome
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
            array(),
            '5.15.4'
        );
        
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array('storefront-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . '/css/main.css',
            array('aqualuxe-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue responsive CSS
        wp_enqueue_style(
            'aqualuxe-responsive',
            AQUALUXE_ASSETS_URI . '/css/responsive.css',
            array('aqualuxe-main'),
            AQUALUXE_VERSION
        );
        
        // Enqueue enhanced mobile responsive CSS
        wp_enqueue_style(
            'aqualuxe-mobile-responsive',
            AQUALUXE_ASSETS_URI . '/css/mobile-responsive.css',
            array('aqualuxe-responsive'),
            AQUALUXE_VERSION
        );
        
        // Enqueue print CSS
        wp_enqueue_style(
            'aqualuxe-print',
            AQUALUXE_ASSETS_URI . '/css/print.css',
            array(),
            AQUALUXE_VERSION,
            'print'
        );
        
        // Enqueue placeholder CSS
        wp_enqueue_style(
            'aqualuxe-placeholder',
            AQUALUXE_ASSETS_URI . '/css/placeholder.css',
            array('aqualuxe-main'),
            AQUALUXE_VERSION
        );
        
        // Enqueue lazy loading CSS
        wp_enqueue_style(
            'aqualuxe-lazy-loading',
            AQUALUXE_ASSETS_URI . '/css/lazy-loading.css',
            array('aqualuxe-main'),
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce custom styles
        if (class_exists('WooCommerce')) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/css/woocommerce.css',
                array('aqualuxe-main'),
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue Fish Species styles
        if (is_singular('fish_species') || is_post_type_archive('fish_species') || is_tax('fish_category') || is_tax('care_level') || is_tax('fish_family') || is_tax('fish_origin')) {
            wp_enqueue_style(
                'aqualuxe-fish-species',
                AQUALUXE_ASSETS_URI . '/css/fish-species.css',
                array('aqualuxe-main'),
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue Calculator styles
        if (is_page('water-parameter-calculator') || is_page('tank-volume-calculator') || is_page('fish-stocking-calculator') || has_shortcode(get_post()->post_content, 'water_parameter_calculator') || has_shortcode(get_post()->post_content, 'tank_volume_calculator') || has_shortcode(get_post()->post_content, 'stocking_calculator')) {
            wp_enqueue_style(
                'aqualuxe-calculator',
                AQUALUXE_ASSETS_URI . '/css/calculator.css',
                array('aqualuxe-main'),
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue Compatibility Checker styles
        if (is_page('fish-compatibility-checker') || is_singular('fish_species') || has_shortcode(get_post()->post_content, 'fish_compatibility_checker')) {
            wp_enqueue_style(
                'aqualuxe-compatibility',
                AQUALUXE_ASSETS_URI . '/css/compatibility.css',
                array('aqualuxe-main'),
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue custom scripts
        wp_enqueue_script(
            'aqualuxe-navigation',
            AQUALUXE_ASSETS_URI . '/js/navigation.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_script(
            'aqualuxe-skip-link-focus-fix',
            AQUALUXE_ASSETS_URI . '/js/skip-link-focus-fix.js',
            array(),
            AQUALUXE_VERSION,
            true
        );
        
        // Main theme script
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . '/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Lazy loading script - load on all pages
        wp_enqueue_script(
            'aqualuxe-lazy-loading',
            AQUALUXE_ASSETS_URI . '/js/lazy-loading.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // WooCommerce custom scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/js/woocommerce.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
            
            // Add AJAX cart functionality
            wp_localize_script('aqualuxe-woocommerce', 'aqualuxe_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('aqualuxe-ajax-nonce'),
            ));
        }
        
        // Enqueue Fish Species tabs script
        if (is_singular('fish_species')) {
            wp_enqueue_script(
                'aqualuxe-fish-tabs',
                AQUALUXE_ASSETS_URI . '/js/compatibility.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Enqueue Calculator scripts
        if (is_page('water-parameter-calculator') || is_page('tank-volume-calculator') || is_page('fish-stocking-calculator') || has_shortcode(get_post()->post_content, 'water_parameter_calculator') || has_shortcode(get_post()->post_content, 'tank_volume_calculator') || has_shortcode(get_post()->post_content, 'stocking_calculator')) {
            wp_enqueue_script(
                'aqualuxe-calculator',
                AQUALUXE_ASSETS_URI . '/js/calculator.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
            
            // Add AJAX calculator functionality
            wp_localize_script('aqualuxe-calculator', 'aqualuxeCalculator', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-calculator-nonce'),
                'i18n'    => array(
                    'error'   => __('Error occurred. Please try again.', 'aqualuxe'),
                    'success' => __('Calculation complete!', 'aqualuxe'),
                    'loading' => __('Calculating...', 'aqualuxe'),
                ),
            ));
        }
        
        // Enqueue Compatibility Checker scripts
        if (is_page('fish-compatibility-checker') || has_shortcode(get_post()->post_content, 'fish_compatibility_checker')) {
            wp_enqueue_script(
                'aqualuxe-compatibility',
                AQUALUXE_ASSETS_URI . '/js/compatibility.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
            
            // Add AJAX compatibility checker functionality
            wp_localize_script('aqualuxe-compatibility', 'aqualuxeCompatibility', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-compatibility-nonce'),
                'i18n'    => array(
                    'error'       => __('Error occurred. Please try again.', 'aqualuxe'),
                    'loading'     => __('Checking compatibility...', 'aqualuxe'),
                    'select_fish' => __('Please select at least two fish species.', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Add preconnect for Google Fonts
     *
     * @param array  $urls           URLs to print for resource hints.
     * @param string $relation_type  The relation type the URLs are printed.
     * @return array $urls           URLs to print for resource hints.
     */
    public function resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }
        
        return $urls;
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @return string Modified script tag.
     */
    public function script_loader_tag($tag, $handle) {
        // Add defer attribute to non-critical scripts
        $scripts_to_defer = array(
            'aqualuxe-navigation',
            'aqualuxe-skip-link-focus-fix',
        );
        
        foreach ($scripts_to_defer as $script) {
            if ($script === $handle) {
                return str_replace(' src', ' defer src', $tag);
            }
        }
        
        // Add async attribute to non-critical scripts that can load asynchronously
        $scripts_to_async = array(
            'font-awesome',
        );
        
        foreach ($scripts_to_async as $script) {
            if ($script === $handle) {
                return str_replace(' src', ' async src', $tag);
            }
        }
        
        return $tag;
    }

    /**
     * Add editor styles
     */
    public function add_editor_styles() {
        add_editor_style(array(
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap',
            'assets/css/editor-style.css',
        ));
    }

    /**
     * Add admin styles
     */
    public function admin_styles() {
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . '/css/admin.css',
            array(),
            AQUALUXE_VERSION
        );
    }
}