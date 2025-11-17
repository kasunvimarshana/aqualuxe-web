<?php
/**
 * AquaLuxe Theme Configuration
 *
 * Central configuration file for theme settings and constants
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Configuration Class
 */
class AquaLuxe_Config {
    
    /**
     * Theme version
     */
    const VERSION = '1.0.0';
    
    /**
     * Theme text domain
     */
    const TEXT_DOMAIN = 'aqualuxe';
    
    /**
     * Minimum WordPress version
     */
    const MIN_WP_VERSION = '5.0';
    
    /**
     * Minimum PHP version
     */
    const MIN_PHP_VERSION = '7.4';
    
    /**
     * Theme features configuration
     *
     * @return array
     */
    public static function get_theme_features() {
        return array(
            'modular_architecture' => true,
            'multilingual_support' => true,
            'dark_mode' => true,
            'demo_importer' => true,
            'woocommerce_support' => true,
            'custom_post_types' => true,
            'customizer_integration' => true,
            'accessibility_features' => true,
            'seo_optimization' => true,
            'security_hardening' => true,
            'performance_optimization' => true,
        );
    }
    
    /**
     * Module configuration
     *
     * @return array
     */
    public static function get_modules_config() {
        return array(
            'multilingual' => array(
                'enabled' => true,
                'file' => 'multilingual/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => true,
            ),
            'dark_mode' => array(
                'enabled' => true,
                'file' => 'dark-mode/module.php',
                'dependencies' => array(),
                'admin_page' => false,
                'customizer_section' => true,
            ),
            'subscriptions' => array(
                'enabled' => true,
                'file' => 'subscriptions/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'bookings' => array(
                'enabled' => true,
                'file' => 'bookings/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'events' => array(
                'enabled' => true,
                'file' => 'events/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'auctions' => array(
                'enabled' => true,
                'file' => 'auctions/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'wholesale' => array(
                'enabled' => true,
                'file' => 'wholesale/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'franchise' => array(
                'enabled' => true,
                'file' => 'franchise/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'sustainability' => array(
                'enabled' => true,
                'file' => 'sustainability/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => true,
            ),
            'affiliates' => array(
                'enabled' => true,
                'file' => 'affiliates/module.php',
                'dependencies' => array(),
                'admin_page' => true,
                'customizer_section' => false,
            ),
            'services' => array(
                'enabled' => true,
                'file' => 'services/module.php',
                'dependencies' => array(),
                'admin_page' => false, // Uses built-in post type admin
                'customizer_section' => false,
            ),
            'marketplace' => array(
                'enabled' => true,
                'file' => 'marketplace/module.php',
                'dependencies' => array('woocommerce'),
                'admin_page' => true,
                'customizer_section' => false,
            ),
        );
    }
    
    /**
     * Custom post types configuration
     *
     * @return array
     */
    public static function get_post_types_config() {
        return array(
            'aqualuxe_service' => array(
                'labels' => array(
                    'name' => __('Services', 'aqualuxe'),
                    'singular_name' => __('Service', 'aqualuxe'),
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                'menu_icon' => 'dashicons-admin-tools',
                'rewrite' => array('slug' => 'services'),
            ),
            'aqualuxe_event' => array(
                'labels' => array(
                    'name' => __('Events', 'aqualuxe'),
                    'singular_name' => __('Event', 'aqualuxe'),
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                'menu_icon' => 'dashicons-calendar-alt',
                'rewrite' => array('slug' => 'events'),
            ),
            'aqualuxe_booking' => array(
                'labels' => array(
                    'name' => __('Bookings', 'aqualuxe'),
                    'singular_name' => __('Booking', 'aqualuxe'),
                ),
                'public' => false,
                'show_ui' => true,
                'supports' => array('title', 'custom-fields'),
                'menu_icon' => 'dashicons-calendar',
                'capability_type' => 'post',
                'capabilities' => array(
                    'create_posts' => 'edit_bookings',
                ),
            ),
        );
    }
    
    /**
     * Custom taxonomies configuration
     *
     * @return array
     */
    public static function get_taxonomies_config() {
        return array(
            'service_category' => array(
                'post_types' => array('aqualuxe_service'),
                'labels' => array(
                    'name' => __('Service Categories', 'aqualuxe'),
                    'singular_name' => __('Service Category', 'aqualuxe'),
                ),
                'hierarchical' => true,
                'public' => true,
                'rewrite' => array('slug' => 'service-category'),
            ),
            'service_tag' => array(
                'post_types' => array('aqualuxe_service'),
                'labels' => array(
                    'name' => __('Service Tags', 'aqualuxe'),
                    'singular_name' => __('Service Tag', 'aqualuxe'),
                ),
                'hierarchical' => false,
                'public' => true,
                'rewrite' => array('slug' => 'service-tag'),
            ),
            'event_category' => array(
                'post_types' => array('aqualuxe_event'),
                'labels' => array(
                    'name' => __('Event Categories', 'aqualuxe'),
                    'singular_name' => __('Event Category', 'aqualuxe'),
                ),
                'hierarchical' => true,
                'public' => true,
                'rewrite' => array('slug' => 'event-category'),
            ),
        );
    }
    
    /**
     * Image sizes configuration
     *
     * @return array
     */
    public static function get_image_sizes() {
        return array(
            'aqualuxe-thumbnail' => array(400, 300, true),
            'aqualuxe-medium' => array(800, 600, true),
            'aqualuxe-large' => array(1200, 800, true),
            'aqualuxe-featured' => array(1200, 600, true),
            'aqualuxe-hero' => array(1920, 1080, true),
            'aqualuxe-product-thumb' => array(300, 300, true),
            'aqualuxe-product-gallery' => array(600, 600, true),
        );
    }
    
    /**
     * Navigation menus configuration
     *
     * @return array
     */
    public static function get_nav_menus() {
        return array(
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer' => __('Footer Menu', 'aqualuxe'),
            'mobile' => __('Mobile Menu', 'aqualuxe'),
            'secondary' => __('Secondary Menu', 'aqualuxe'),
        );
    }
    
    /**
     * Widget areas configuration
     *
     * @return array
     */
    public static function get_widget_areas() {
        return array(
            'sidebar-1' => array(
                'name' => __('Primary Sidebar', 'aqualuxe'),
                'description' => __('Main sidebar that appears on the right.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ),
            'footer-1' => array(
                'name' => __('Footer Area 1', 'aqualuxe'),
                'description' => __('First footer widget area.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ),
            'footer-2' => array(
                'name' => __('Footer Area 2', 'aqualuxe'),
                'description' => __('Second footer widget area.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ),
            'footer-3' => array(
                'name' => __('Footer Area 3', 'aqualuxe'),
                'description' => __('Third footer widget area.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ),
            'footer-4' => array(
                'name' => __('Footer Area 4', 'aqualuxe'),
                'description' => __('Fourth footer widget area.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ),
        );
    }
    
    /**
     * Theme support configuration
     *
     * @return array
     */
    public static function get_theme_support() {
        return array(
            'automatic-feed-links',
            'title-tag',
            'post-thumbnails',
            'html5' => array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ),
            'customize-selective-refresh-widgets',
            'custom-logo' => array(
                'height' => 250,
                'width' => 250,
                'flex-width' => true,
                'flex-height' => true,
            ),
            'editor-styles',
            'responsive-embeds',
            'custom-background' => array(
                'default-color' => 'ffffff',
            ),
            'woocommerce',
            'wc-product-gallery-zoom',
            'wc-product-gallery-lightbox',
            'wc-product-gallery-slider',
        );
    }
    
    /**
     * Customizer configuration
     *
     * @return array
     */
    public static function get_customizer_config() {
        return array(
            'sections' => array(
                'aqualuxe_colors' => array(
                    'title' => __('Theme Colors', 'aqualuxe'),
                    'priority' => 40,
                ),
                'aqualuxe_typography' => array(
                    'title' => __('Typography', 'aqualuxe'),
                    'priority' => 50,
                ),
                'aqualuxe_layout' => array(
                    'title' => __('Layout Options', 'aqualuxe'),
                    'priority' => 60,
                ),
                'aqualuxe_header' => array(
                    'title' => __('Header Settings', 'aqualuxe'),
                    'priority' => 70,
                ),
                'aqualuxe_footer' => array(
                    'title' => __('Footer Settings', 'aqualuxe'),
                    'priority' => 80,
                ),
                'aqualuxe_woocommerce' => array(
                    'title' => __('WooCommerce', 'aqualuxe'),
                    'priority' => 90,
                ),
            ),
            'settings' => array(
                'aqualuxe_primary_color' => array(
                    'default' => '#0891b2',
                    'type' => 'color',
                    'section' => 'aqualuxe_colors',
                ),
                'aqualuxe_secondary_color' => array(
                    'default' => '#065f46',
                    'type' => 'color',
                    'section' => 'aqualuxe_colors',
                ),
                'aqualuxe_accent_color' => array(
                    'default' => '#f59e0b',
                    'type' => 'color',
                    'section' => 'aqualuxe_colors',
                ),
                'aqualuxe_logo_text' => array(
                    'default' => 'AquaLuxe',
                    'type' => 'text',
                    'section' => 'aqualuxe_header',
                ),
                'aqualuxe_tagline' => array(
                    'default' => 'Bringing elegance to aquatic life – globally',
                    'type' => 'textarea',
                    'section' => 'aqualuxe_header',
                ),
                'aqualuxe_footer_text' => array(
                    'default' => '© 2024 AquaLuxe. All rights reserved.',
                    'type' => 'textarea',
                    'section' => 'aqualuxe_footer',
                ),
            ),
        );
    }
    
    /**
     * Performance optimization settings
     *
     * @return array
     */
    public static function get_performance_config() {
        return array(
            'lazy_loading' => true,
            'image_optimization' => true,
            'css_minification' => true,
            'js_minification' => true,
            'gzip_compression' => true,
            'browser_caching' => true,
            'critical_css' => true,
            'preload_fonts' => true,
            'dns_prefetch' => array(
                'fonts.googleapis.com',
                'fonts.gstatic.com',
            ),
        );
    }
    
    /**
     * Security configuration
     *
     * @return array
     */
    public static function get_security_config() {
        return array(
            'hide_wp_version' => true,
            'remove_generator_tag' => true,
            'disable_file_editing' => true,
            'secure_headers' => true,
            'csrf_protection' => true,
            'sanitize_file_names' => true,
            'disable_xml_rpc' => false, // Keep enabled for some plugins
            'limit_login_attempts' => true,
        );
    }
    
    /**
     * SEO configuration
     *
     * @return array
     */
    public static function get_seo_config() {
        return array(
            'schema_markup' => true,
            'open_graph' => true,
            'twitter_cards' => true,
            'meta_descriptions' => true,
            'canonical_urls' => true,
            'xml_sitemap' => true,
            'breadcrumbs' => true,
            'structured_data' => true,
        );
    }
    
    /**
     * Accessibility configuration
     *
     * @return array
     */
    public static function get_accessibility_config() {
        return array(
            'skip_links' => true,
            'focus_indicators' => true,
            'aria_labels' => true,
            'alt_texts' => true,
            'keyboard_navigation' => true,
            'color_contrast' => true,
            'screen_reader_text' => true,
            'semantic_markup' => true,
        );
    }
    
    /**
     * Get all configuration
     *
     * @return array
     */
    public static function get_all_config() {
        return array(
            'theme_features' => self::get_theme_features(),
            'modules' => self::get_modules_config(),
            'post_types' => self::get_post_types_config(),
            'taxonomies' => self::get_taxonomies_config(),
            'image_sizes' => self::get_image_sizes(),
            'nav_menus' => self::get_nav_menus(),
            'widget_areas' => self::get_widget_areas(),
            'theme_support' => self::get_theme_support(),
            'customizer' => self::get_customizer_config(),
            'performance' => self::get_performance_config(),
            'security' => self::get_security_config(),
            'seo' => self::get_seo_config(),
            'accessibility' => self::get_accessibility_config(),
        );
    }
}

// Make configuration available globally
if (!function_exists('aqualuxe_get_config')) {
    /**
     * Get theme configuration
     *
     * @param string $key Configuration key
     * @return mixed
     */
    function aqualuxe_get_config($key = null) {
        $config = AquaLuxe_Config::get_all_config();
        
        if ($key === null) {
            return $config;
        }
        
        return isset($config[$key]) ? $config[$key] : null;
    }
}