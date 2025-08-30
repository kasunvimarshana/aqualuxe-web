<?php
/**
 * AquaLuxe Theme Setup
 * 
 * Handles theme initialization, features, and WordPress integration.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Theme_Setup {
    
    /**
     * Initialize theme setup
     */
    public static function init() {
        add_action('after_setup_theme', [__CLASS__, 'setup_theme_features']);
        add_action('widgets_init', [__CLASS__, 'register_widget_areas']);
        add_action('init', [__CLASS__, 'register_nav_menus']);
        add_action('init', [__CLASS__, 'register_image_sizes']);
        add_action('wp_head', [__CLASS__, 'add_theme_meta_tags']);
    }
    
    /**
     * Set up theme features and support
     */
    public static function setup_theme_features() {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('custom-logo', [
            'height' => 100,
            'width' => 300,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => ['site-title', 'site-description']
        ]);
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => ''
        ]);
        add_theme_support('post-formats', [
            'aside',
            'gallery',
            'video',
            'quote',
            'link'
        ]);
        
        // Add editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/editor.css');
        
        // Add responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add wide and full alignment
        add_theme_support('align-wide');
        
        // Add custom line height
        add_theme_support('custom-line-height');
        
        // Add custom units
        add_theme_support('custom-units');
        
        // Set content width
        if (!isset($GLOBALS['content_width'])) {
            $GLOBALS['content_width'] = 1200;
        }
    }
    
    /**
     * Register navigation menus
     */
    public static function register_nav_menus() {
        register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer' => __('Footer Menu', 'aqualuxe'),
            'mobile' => __('Mobile Menu', 'aqualuxe'),
            'secondary' => __('Secondary Menu', 'aqualuxe')
        ]);
    }
    
    /**
     * Register custom image sizes
     */
    public static function register_image_sizes() {
        // Hero section images
        add_image_size('aqualuxe-hero', 1920, 800, true);
        
        // Featured product images
        add_image_size('aqualuxe-product-featured', 600, 400, true);
        
        // Blog post thumbnails
        add_image_size('aqualuxe-blog-thumb', 400, 300, true);
        
        // Gallery thumbnails
        add_image_size('aqualuxe-gallery-thumb', 300, 300, true);
        
        // Service images
        add_image_size('aqualuxe-service', 500, 350, true);
        
        // Team member photos
        add_image_size('aqualuxe-team', 350, 350, true);
        
        // Testimonial avatars
        add_image_size('aqualuxe-avatar', 80, 80, true);
    }
    
    /**
     * Register widget areas
     */
    public static function register_widget_areas() {
        // Main sidebar
        register_sidebar([
            'name' => __('Main Sidebar', 'aqualuxe'),
            'id' => 'sidebar-main',
            'description' => __('Main sidebar for blog and pages', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        ]);
        
        // Shop sidebar
        if (aqualuxe_is_woocommerce_active()) {
            register_sidebar([
                'name' => __('Shop Sidebar', 'aqualuxe'),
                'id' => 'sidebar-shop',
                'description' => __('Sidebar for WooCommerce shop and product pages', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            ]);
        }
        
        // Footer widget areas
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name' => sprintf(__('Footer Widget Area %d', 'aqualuxe'), $i),
                'id' => 'footer-widget-' . $i,
                'description' => sprintf(__('Footer widget area %d', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            ]);
        }
        
        // Homepage widget areas
        $homepage_widgets = [
            'hero' => __('Homepage Hero', 'aqualuxe'),
            'featured-products' => __('Featured Products', 'aqualuxe'),
            'services' => __('Services Section', 'aqualuxe'),
            'testimonials' => __('Testimonials', 'aqualuxe'),
            'newsletter' => __('Newsletter Section', 'aqualuxe')
        ];
        
        foreach ($homepage_widgets as $id => $name) {
            register_sidebar([
                'name' => $name,
                'id' => 'homepage-' . $id,
                'description' => $name . ' ' . __('widget area', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>'
            ]);
        }
    }
    
    /**
     * Add theme meta tags
     */
    public static function add_theme_meta_tags() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        echo '<meta name="theme-color" content="' . esc_attr(get_theme_mod('aqualuxe_primary_color', '#0EA5E9')) . '">' . "\n";
        
        // Add Open Graph meta tags
        if (is_singular()) {
            global $post;
            
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 20)) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            echo '<meta property="og:type" content="article">' . "\n";
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'aqualuxe-hero');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
                }
            }
        }
        
        // Add Twitter Card meta tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:site" content="@aqualuxe">' . "\n";
        
        // Add Schema.org markup
        self::add_schema_markup();
    }
    
    /**
     * Add Schema.org structured data
     */
    private static function add_schema_markup() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => wp_get_attachment_url(get_theme_mod('custom_logo'))
            ],
            'sameAs' => [
                get_theme_mod('aqualuxe_social_facebook', ''),
                get_theme_mod('aqualuxe_social_instagram', ''),
                get_theme_mod('aqualuxe_social_twitter', ''),
                get_theme_mod('aqualuxe_social_youtube', '')
            ]
        ];
        
        // Add contact information
        $contact_phone = get_theme_mod('aqualuxe_contact_phone', '');
        $contact_email = get_theme_mod('aqualuxe_contact_email', '');
        
        if ($contact_phone || $contact_email) {
            $schema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service'
            ];
            
            if ($contact_phone) {
                $schema['contactPoint']['telephone'] = $contact_phone;
            }
            
            if ($contact_email) {
                $schema['contactPoint']['email'] = $contact_email;
            }
        }
        
        // Add address if available
        $address = get_theme_mod('aqualuxe_business_address', '');
        if ($address) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => get_theme_mod('aqualuxe_business_city', ''),
                'addressCountry' => get_theme_mod('aqualuxe_business_country', '')
            ];
        }
        
        // Remove empty values
        $schema['sameAs'] = array_filter($schema['sameAs']);
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
    
    /**
     * Add theme support for WooCommerce
     */
    public static function add_woocommerce_support() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width' => 600,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 2,
                'max_rows' => 8,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 6
            ]
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}
