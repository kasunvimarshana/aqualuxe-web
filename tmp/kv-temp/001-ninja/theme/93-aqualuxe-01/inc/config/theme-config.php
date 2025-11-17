<?php
/**
 * Theme Configuration File
 *
 * Contains all theme configuration settings and feature toggles.
 * This file follows the configuration pattern for easy management
 * and deployment across different environments.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

return [
    
    /**
     * Theme Information
     */
    'theme' => [
        'name' => 'AquaLuxe',
        'version' => '1.0.0',
        'author' => 'AquaLuxe Development Team',
        'author_uri' => 'https://aqualuxe.com',
        'description' => 'A premium, modular, multitenant, multivendor WordPress theme for luxury aquatic commerce.',
        'text_domain' => 'aqualuxe',
        'domain_path' => '/languages'
    ],
    
    /**
     * Theme Features and Support
     */
    'features' => [
        'post_thumbnails' => true,
        'custom_logo' => true,
        'custom_header' => true,
        'custom_background' => true,
        'title_tag' => true,
        'automatic_feed_links' => true,
        'woocommerce' => true,
        'responsive_embeds' => true,
        'align_wide' => true,
        'wp_block_styles' => true,
        'editor_styles' => true,
        'dark_mode' => true,
        'rtl_support' => true
    ],
    
    /**
     * HTML5 Support
     */
    'html5' => [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ],
    
    /**
     * Post Formats Support
     */
    'post_formats' => [
        'aside',
        'gallery',
        'video',
        'quote',
        'link',
        'status',
        'audio'
    ],
    
    /**
     * Custom Image Sizes
     */
    'image_sizes' => [
        'aqualuxe-hero' => [
            'width' => 1920,
            'height' => 1080,
            'crop' => true
        ],
        'aqualuxe-product' => [
            'width' => 600,
            'height' => 600,
            'crop' => true
        ],
        'aqualuxe-thumbnail' => [
            'width' => 300,
            'height' => 300,
            'crop' => true
        ],
        'aqualuxe-gallery' => [
            'width' => 800,
            'height' => 600,
            'crop' => true
        ],
        'aqualuxe-blog' => [
            'width' => 800,
            'height' => 450,
            'crop' => true
        ]
    ],
    
    /**
     * Navigation Menus
     */
    'menus' => [
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'mobile' => __('Mobile Menu', 'aqualuxe'),
        'account' => __('Account Menu', 'aqualuxe'),
        'top-bar' => __('Top Bar Menu', 'aqualuxe')
    ],
    
    /**
     * Widget Areas (Sidebars)
     */
    'sidebars' => [
        'sidebar-main' => [
            'name' => __('Main Sidebar', 'aqualuxe'),
            'description' => __('Widgets for the main sidebar area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">',
            'after_title' => '</h3>'
        ],
        'header-top' => [
            'name' => __('Header Top', 'aqualuxe'),
            'description' => __('Widgets for the header top area.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="header-widget-title sr-only">',
            'after_title' => '</h4>'
        ],
        'footer-1' => [
            'name' => __('Footer Column 1', 'aqualuxe'),
            'description' => __('Widgets for the first footer column.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s mb-6">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title text-lg font-semibold mb-4 text-blue-400">',
            'after_title' => '</h3>'
        ],
        'footer-2' => [
            'name' => __('Footer Column 2', 'aqualuxe'),
            'description' => __('Widgets for the second footer column.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s mb-6">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title text-lg font-semibold mb-4 text-blue-400">',
            'after_title' => '</h3>'
        ],
        'footer-3' => [
            'name' => __('Footer Column 3', 'aqualuxe'),
            'description' => __('Widgets for the third footer column.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s mb-6">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title text-lg font-semibold mb-4 text-blue-400">',
            'after_title' => '</h3>'
        ],
        'footer-4' => [
            'name' => __('Footer Column 4', 'aqualuxe'),
            'description' => __('Widgets for the fourth footer column.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s mb-6">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title text-lg font-semibold mb-4 text-blue-400">',
            'after_title' => '</h3>'
        ],
        'shop-sidebar' => [
            'name' => __('Shop Sidebar', 'aqualuxe'),
            'description' => __('Widgets for the WooCommerce shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="shop-widget %2$s mb-8">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="shop-widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">',
            'after_title' => '</h3>'
        ]
    ],
    
    /**
     * Module Configuration
     * Each module can be enabled/disabled and configured individually
     */
    'modules' => [
        
        // Core modules (always enabled)
        'customizer' => [
            'enabled' => true,
            'config' => []
        ],
        'security' => [
            'enabled' => true,
            'config' => []
        ],
        'performance' => [
            'enabled' => true,
            'config' => []
        ],
        'seo' => [
            'enabled' => true,
            'config' => []
        ],
        
        // Feature modules (can be toggled)
        'multilingual' => [
            'enabled' => true,
            'config' => [
                'default_language' => 'en',
                'supported_languages' => ['en', 'es', 'fr', 'de', 'it']
            ]
        ],
        'dark_mode' => [
            'enabled' => true,
            'config' => [
                'default_mode' => 'light',
                'user_preference' => true,
                'system_preference' => true
            ]
        ],
        'woocommerce' => [
            'enabled' => true,
            'config' => [
                'product_gallery' => true,
                'quick_view' => true,
                'wishlist' => true,
                'compare' => true,
                'multicurrency' => true
            ]
        ],
        'demo_importer' => [
            'enabled' => true,
            'config' => [
                'demo_files' => [
                    'content' => 'demo-content.xml',
                    'customizer' => 'customizer-settings.dat',
                    'widgets' => 'widgets.wie'
                ]
            ]
        ],
        'booking' => [
            'enabled' => true,
            'config' => []
        ],
        'events' => [
            'enabled' => true,
            'config' => []
        ],
        'subscriptions' => [
            'enabled' => true,
            'config' => []
        ],
        'marketplace' => [
            'enabled' => true,
            'config' => []
        ],
        'affiliate' => [
            'enabled' => true,
            'config' => []
        ],
        'analytics' => [
            'enabled' => true,
            'config' => []
        ]
    ],
    
    /**
     * Asset Configuration
     */
    'assets' => [
        'version' => '1.0.0',
        'minify' => true,
        'combine' => true,
        'cache_bust' => true,
        'lazy_load' => true,
        'critical_css' => true,
        'preload' => [
            'fonts' => [
                'inter' => '/assets/dist/fonts/inter.woff2'
            ]
        ]
    ],
    
    /**
     * Performance Settings
     */
    'performance' => [
        'lazy_loading' => true,
        'image_optimization' => true,
        'minify_html' => true,
        'remove_query_strings' => true,
        'disable_emojis' => true,
        'limit_revisions' => 5,
        'optimize_database' => true
    ],
    
    /**
     * Security Settings
     */
    'security' => [
        'remove_wp_version' => true,
        'disable_xml_rpc' => true,
        'hide_login_errors' => true,
        'disable_file_editing' => true,
        'force_ssl_admin' => true,
        'secure_headers' => true
    ],
    
    /**
     * SEO Settings
     */
    'seo' => [
        'meta_tags' => true,
        'open_graph' => true,
        'twitter_cards' => true,
        'schema_markup' => true,
        'xml_sitemap' => true,
        'breadcrumbs' => true
    ],
    
    /**
     * WooCommerce Settings
     */
    'woocommerce' => [
        'gallery_zoom' => true,
        'gallery_lightbox' => true,
        'gallery_slider' => true,
        'single_product_gallery_layout' => 'horizontal',
        'shop_columns' => [
            'desktop' => 4,
            'tablet' => 3,
            'mobile' => 2
        ],
        'products_per_page' => 12,
        'enable_reviews' => true,
        'enable_related_products' => true,
        'enable_cross_sells' => true,
        'enable_upsells' => true
    ],
    
    /**
     * Customizer Settings
     */
    'customizer' => [
        'logo_max_width' => 200,
        'color_palette' => [
            'primary' => '#1e40af',
            'secondary' => '#06b6d4',
            'accent' => '#f59e0b',
            'dark' => '#1f2937',
            'light' => '#f9fafb'
        ],
        'typography' => [
            'body_font' => 'Inter',
            'heading_font' => 'Inter',
            'font_size_base' => 16
        ]
    ],
    
    /**
     * Content Width
     */
    'content_width' => 1200,
    
    /**
     * Editor Settings
     */
    'editor' => [
        'color_palette' => [
            [
                'name' => __('Primary Blue', 'aqualuxe'),
                'slug' => 'primary-blue',
                'color' => '#1e40af'
            ],
            [
                'name' => __('Secondary Cyan', 'aqualuxe'),
                'slug' => 'secondary-cyan',
                'color' => '#06b6d4'
            ],
            [
                'name' => __('Accent Amber', 'aqualuxe'),
                'slug' => 'accent-amber',
                'color' => '#f59e0b'
            ],
            [
                'name' => __('Dark Gray', 'aqualuxe'),
                'slug' => 'dark-gray',
                'color' => '#1f2937'
            ],
            [
                'name' => __('Light Gray', 'aqualuxe'),
                'slug' => 'light-gray',
                'color' => '#f9fafb'
            ]
        ],
        'font_sizes' => [
            [
                'name' => __('Small', 'aqualuxe'),
                'slug' => 'small',
                'size' => 14
            ],
            [
                'name' => __('Medium', 'aqualuxe'),
                'slug' => 'medium',
                'size' => 16
            ],
            [
                'name' => __('Large', 'aqualuxe'),
                'slug' => 'large',
                'size' => 20
            ],
            [
                'name' => __('Extra Large', 'aqualuxe'),
                'slug' => 'extra-large',
                'size' => 24
            ]
        ]
    ],
    
    /**
     * Custom Post Types
     */
    'post_types' => [
        'service' => [
            'enabled' => true,
            'public' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
        ],
        'portfolio' => [
            'enabled' => true,
            'public' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
        ],
        'testimonial' => [
            'enabled' => true,
            'public' => false,
            'supports' => ['title', 'editor', 'thumbnail']
        ],
        'team' => [
            'enabled' => true,
            'public' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
        ]
    ],
    
    /**
     * Environment Configuration
     */
    'environment' => [
        'development' => [
            'debug' => true,
            'minify' => false,
            'cache' => false
        ],
        'staging' => [
            'debug' => false,
            'minify' => true,
            'cache' => true
        ],
        'production' => [
            'debug' => false,
            'minify' => true,
            'cache' => true
        ]
    ]
    
];
