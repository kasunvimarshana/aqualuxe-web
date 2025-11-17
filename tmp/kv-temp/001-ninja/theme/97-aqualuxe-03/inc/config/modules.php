<?php
/**
 * Module Configuration - Define available theme modules and their settings
 *
 * This configuration file defines all available theme modules, their
 * dependencies, settings, and loading priorities. Modules are loaded
 * dynamically based on this configuration and user preferences.
 * 
 * @package AquaLuxe\Configuration
 * @since 2.0.0
 * @author Kasun Vimarshana
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

return [
    /**
     * Module Registry
     * 
     * Defines all available theme modules with their configuration.
     * Each module can have dependencies, settings, and loading conditions.
     */
    'modules' => [
        
        // WooCommerce Integration Module
        'woocommerce' => [
            'name' => 'WooCommerce Integration',
            'description' => 'Enhanced WooCommerce functionality with custom styling and features',
            'class' => 'AquaLuxe\\Modules\\WooCommerce',
            'enabled' => true,
            'priority' => 10,
            'dependencies' => [],
            'requires' => [
                'plugins' => [ 'woocommerce/woocommerce.php' ],
                'php_version' => '7.4',
                'wp_version' => '5.0'
            ],
            'settings' => [
                'custom_single_product_layout' => true,
                'ajax_add_to_cart' => true,
                'product_quick_view' => true,
                'wishlist_integration' => false,
                'compare_products' => false,
                'advanced_filtering' => true,
                'custom_checkout_flow' => true
            ],
            'assets' => [
                'css' => [ 'woocommerce.css' ],
                'js' => [ 'woocommerce.js' ]
            ]
        ],

        // SEO Optimization Module
        'seo' => [
            'name' => 'SEO Optimization',
            'description' => 'Advanced SEO features and optimization tools',
            'class' => 'AquaLuxe\\Modules\\SEO',
            'enabled' => true,
            'priority' => 20,
            'dependencies' => [],
            'settings' => [
                'meta_tags_optimization' => true,
                'schema_markup' => true,
                'open_graph_tags' => true,
                'twitter_cards' => true,
                'xml_sitemap_enhancement' => true,
                'breadcrumb_navigation' => true,
                'canonical_urls' => true,
                'robots_txt_optimization' => true
            ],
            'assets' => [
                'css' => [ 'seo.css' ],
                'js' => [ 'seo.js' ]
            ]
        ],

        // Multilingual Support Module  
        'multilingual' => [
            'name' => 'Multilingual Support',
            'description' => 'Multi-language functionality and RTL support',
            'class' => 'AquaLuxe\\Modules\\Multilingual',
            'enabled' => false,
            'priority' => 15,
            'dependencies' => [],
            'requires' => [
                'plugins' => [ 
                    'sitepress-multilingual-cms/sitepress.php', // WPML
                    'polylang/polylang.php' // Polylang (alternative)
                ]
            ],
            'settings' => [
                'language_switcher' => true,
                'rtl_support' => true,
                'translated_slugs' => true,
                'currency_switcher' => true,
                'geo_detection' => false,
                'translated_emails' => true
            ],
            'assets' => [
                'css' => [ 'multilingual.css', 'rtl.css' ],
                'js' => [ 'multilingual.js' ]
            ]
        ],

        // Social Media Integration Module
        'social_media' => [
            'name' => 'Social Media Integration',
            'description' => 'Social sharing, feeds, and authentication',
            'class' => 'AquaLuxe\\Modules\\Social_Media',
            'enabled' => true,
            'priority' => 30,
            'dependencies' => [],
            'settings' => [
                'social_sharing_buttons' => true,
                'social_login' => false,
                'instagram_feed' => true,
                'twitter_feed' => false,
                'facebook_integration' => true,
                'linkedin_sharing' => true,
                'pinterest_sharing' => true,
                'social_meta_tags' => true
            ],
            'assets' => [
                'css' => [ 'social-media.css' ],
                'js' => [ 'social-media.js' ]
            ]
        ],

        // Contact Forms Module
        'contact_forms' => [
            'name' => 'Advanced Contact Forms',
            'description' => 'Custom contact forms with advanced features',
            'class' => 'AquaLuxe\\Modules\\Contact_Forms',
            'enabled' => true,
            'priority' => 25,
            'dependencies' => [],
            'settings' => [
                'ajax_form_submission' => true,
                'form_validation' => true,
                'anti_spam_protection' => true,
                'file_uploads' => true,
                'email_notifications' => true,
                'form_analytics' => true,
                'gdpr_compliance' => true,
                'multi_step_forms' => false
            ],
            'assets' => [
                'css' => [ 'contact-forms.css' ],
                'js' => [ 'contact-forms.js' ]
            ]
        ],

        // Blog Enhancement Module
        'blog_enhancement' => [
            'name' => 'Blog Enhancement',
            'description' => 'Advanced blog features and layouts',
            'class' => 'AquaLuxe\\Modules\\Blog_Enhancement',
            'enabled' => true,
            'priority' => 20,
            'dependencies' => [],
            'settings' => [
                'related_posts' => true,
                'post_reading_time' => true,
                'social_sharing' => true,
                'post_views_counter' => true,
                'infinite_scroll' => false,
                'ajax_search' => true,
                'post_formats_styling' => true,
                'author_bio_box' => true,
                'estimated_reading_progress' => true
            ],
            'assets' => [
                'css' => [ 'blog-enhancement.css' ],
                'js' => [ 'blog-enhancement.js' ]
            ]
        ],

        // Portfolio Module
        'portfolio' => [
            'name' => 'Portfolio Showcase',
            'description' => 'Portfolio and project showcase functionality',
            'class' => 'AquaLuxe\\Modules\\Portfolio',
            'enabled' => false,
            'priority' => 25,
            'dependencies' => [],
            'settings' => [
                'portfolio_post_type' => true,
                'portfolio_categories' => true,
                'lightbox_gallery' => true,
                'filtering_system' => true,
                'masonry_layout' => true,
                'project_details_popup' => true,
                'client_testimonials' => true
            ],
            'assets' => [
                'css' => [ 'portfolio.css' ],
                'js' => [ 'portfolio.js', 'lightbox.js' ]
            ]
        ],

        // Events Management Module
        'events' => [
            'name' => 'Events Management',
            'description' => 'Event management and calendar functionality',
            'class' => 'AquaLuxe\\Modules\\Events',
            'enabled' => false,
            'priority' => 30,
            'dependencies' => [],
            'settings' => [
                'events_post_type' => true,
                'calendar_view' => true,
                'event_registration' => true,
                'recurring_events' => true,
                'event_categories' => true,
                'ical_export' => true,
                'google_calendar_sync' => false
            ],
            'assets' => [
                'css' => [ 'events.css', 'calendar.css' ],
                'js' => [ 'events.js', 'calendar.js' ]
            ]
        ],

        // Analytics Integration Module
        'analytics' => [
            'name' => 'Analytics Integration',
            'description' => 'Google Analytics, Facebook Pixel, and tracking',
            'class' => 'AquaLuxe\\Modules\\Analytics',
            'enabled' => true,
            'priority' => 5,
            'dependencies' => [],
            'settings' => [
                'google_analytics' => true,
                'google_tag_manager' => true,
                'facebook_pixel' => true,
                'hotjar_integration' => false,
                'conversion_tracking' => true,
                'ecommerce_tracking' => true,
                'custom_events_tracking' => true,
                'privacy_compliance' => true
            ],
            'assets' => [
                'css' => [],
                'js' => [ 'analytics.js' ]
            ]
        ],

        // Dark Mode Module
        'dark_mode' => [
            'name' => 'Dark Mode',
            'description' => 'Dark theme toggle and automatic detection',
            'class' => 'AquaLuxe\\Modules\\Dark_Mode',
            'enabled' => true,
            'priority' => 35,
            'dependencies' => [],
            'settings' => [
                'user_preference_toggle' => true,
                'system_preference_detection' => true,
                'automatic_switching' => false,
                'custom_dark_colors' => true,
                'smooth_transitions' => true,
                'remember_preference' => true
            ],
            'assets' => [
                'css' => [ 'dark-mode.css' ],
                'js' => [ 'dark-mode.js' ]
            ]
        ],

        // Accessibility Module
        'accessibility' => [
            'name' => 'Accessibility Features',
            'description' => 'Web accessibility compliance and features',
            'class' => 'AquaLuxe\\Modules\\Accessibility',
            'enabled' => true,
            'priority' => 40,
            'dependencies' => [],
            'settings' => [
                'skip_links' => true,
                'keyboard_navigation' => true,
                'aria_labels' => true,
                'color_contrast_compliance' => true,
                'font_size_adjuster' => true,
                'focus_indicators' => true,
                'screen_reader_optimization' => true,
                'wcag_compliance' => '2.1'
            ],
            'assets' => [
                'css' => [ 'accessibility.css' ],
                'js' => [ 'accessibility.js' ]
            ]
        ]
    ],

    /**
     * Module Loading Configuration
     */
    'loading' => [
        'load_on_init' => true,
        'async_loading' => false,
        'lazy_loading' => true,
        'check_dependencies' => true,
        'cache_module_list' => true,
        'cache_duration' => 3600 // 1 hour
    ],

    /**
     * Module Dependencies Resolution
     */
    'dependency_resolution' => [
        'auto_resolve' => true,
        'strict_mode' => false,
        'fallback_mode' => true,
        'notify_missing' => true
    ],

    /**
     * Asset Management for Modules
     */
    'asset_management' => [
        'combine_module_css' => false,
        'combine_module_js' => false,
        'minify_module_assets' => true,
        'version_module_assets' => true,
        'conditional_loading' => true
    ],

    /**
     * Module API Configuration
     */
    'api' => [
        'enable_module_api' => true,
        'api_version' => '2.0',
        'allowed_operations' => [ 'enable', 'disable', 'configure', 'status' ],
        'rate_limiting' => true,
        'authentication_required' => true
    ],

    /**
     * Development and Debug Settings
     */
    'development' => [
        'debug_module_loading' => AQUALUXE_DEV_MODE,
        'log_module_errors' => true,
        'performance_profiling' => AQUALUXE_DEV_MODE,
        'module_hot_reload' => AQUALUXE_DEV_MODE
    ]
];
