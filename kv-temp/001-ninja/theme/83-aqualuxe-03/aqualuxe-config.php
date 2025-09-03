<?php
/**
 * AquaLuxe Enterprise Configuration
 * 
 * Business-specific configuration and constants for AquaLuxe's
 * ornamental aquatic solutions platform
 * 
 * @package AquaLuxe_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Business Configuration Class
 */
class AquaLuxe_Config {
    
    /**
     * Theme Constants
     */
    const THEME_NAME = 'AquaLuxe Enterprise';
    const THEME_VERSION = '1.0.0';
    const THEME_TEXTDOMAIN = 'aqualuxe-enterprise';
    const THEME_PREFIX = 'aqualuxe_';
    
    /**
     * Business Information
     */
    const BUSINESS_NAME = 'AquaLuxe';
    const BUSINESS_TAGLINE = 'Bringing elegance to aquatic life – globally.';
    const BUSINESS_DESCRIPTION = 'Premium Ornamental Aquatic Solutions';
    
    /**
     * Business Model Configuration
     */
    public static function get_business_models() {
        return array(
            'retail' => array(
                'name' => 'Retail Sales',
                'description' => 'Direct sales via shop, online store, expos',
                'enabled' => true,
                'features' => array('online_store', 'walk_in_sales', 'event_sales')
            ),
            'wholesale' => array(
                'name' => 'Wholesale Sales', 
                'description' => 'Bulk pricing and long-term contracts',
                'enabled' => true,
                'features' => array('bulk_pricing', 'b2b_portal', 'contracts')
            ),
            'trading' => array(
                'name' => 'Trade & Exchange',
                'description' => 'Fish-for-fish trade, buy-back programs, auctions',
                'enabled' => true,
                'features' => array('exchanges', 'auctions', 'buy_back')
            ),
            'services' => array(
                'name' => 'Professional Services',
                'description' => 'Design, installation, maintenance, consultancy',
                'enabled' => true,
                'features' => array('design', 'installation', 'maintenance', 'training')
            ),
            'export' => array(
                'name' => 'Export Model',
                'description' => 'Global fish and plant exports with certifications',
                'enabled' => true,
                'features' => array('international_shipping', 'certifications', 'compliance')
            ),
            'subscription' => array(
                'name' => 'Subscription Model',
                'description' => 'Monthly care kits, tank service packages',
                'enabled' => true,
                'features' => array('recurring_billing', 'care_kits', 'service_plans')
            )
        );
    }
    
    /**
     * Product Categories
     */
    public static function get_product_categories() {
        return array(
            'livestock' => array(
                'name' => 'Livestock',
                'icon' => 'fas fa-fish',
                'subcategories' => array(
                    'freshwater_fish' => 'Freshwater Fish',
                    'marine_fish' => 'Marine Fish', 
                    'invertebrates' => 'Invertebrates',
                    'exotic_rare' => 'Exotic & Rare'
                )
            ),
            'plants' => array(
                'name' => 'Aquatic Plants',
                'icon' => 'fas fa-leaf',
                'subcategories' => array(
                    'submerged' => 'Submerged Plants',
                    'emergent' => 'Emergent Plants',
                    'tissue_culture' => 'Tissue Culture',
                    'aquascaping' => 'Aquascaping Plants'
                )
            ),
            'equipment' => array(
                'name' => 'Equipment & Supplies',
                'icon' => 'fas fa-cogs',
                'subcategories' => array(
                    'tanks' => 'Tanks & Aquariums',
                    'filtration' => 'Filtration Systems',
                    'lighting' => 'Lighting',
                    'co2_systems' => 'CO₂ Systems',
                    'substrate' => 'Substrate & Decor',
                    'chemicals' => 'Water Conditioners'
                )
            ),
            'services' => array(
                'name' => 'Professional Services',
                'icon' => 'fas fa-tools',
                'subcategories' => array(
                    'design' => 'Aquarium Design',
                    'installation' => 'Installation',
                    'maintenance' => 'Maintenance',
                    'consultation' => 'Consultancy',
                    'training' => 'Training',
                    'rental' => 'Event Rentals'
                )
            )
        );
    }
    
    /**
     * Target Markets
     */
    public static function get_target_markets() {
        return array(
            'b2c' => array(
                'name' => 'B2C - Consumer',
                'description' => 'Hobbyists, collectors, homeowners',
                'segments' => array('hobbyists', 'collectors', 'homeowners', 'beginners')
            ),
            'b2b' => array(
                'name' => 'B2B - Business',
                'description' => 'Pet shops, retailers, hotels, designers',
                'segments' => array('pet_stores', 'retailers', 'hotels', 'designers', 'contractors')
            ),
            'educational' => array(
                'name' => 'Educational',
                'description' => 'Schools, colleges, public aquariums',
                'segments' => array('schools', 'universities', 'public_aquariums', 'research_centers')
            ),
            'export' => array(
                'name' => 'Export Markets',
                'description' => 'International importers and distributors',
                'segments' => array('europe', 'asia', 'middle_east', 'north_america', 'oceania')
            )
        );
    }
    
    /**
     * Service Types
     */
    public static function get_service_types() {
        return array(
            'design_installation' => array(
                'name' => 'Aquarium Design & Installation',
                'description' => 'Custom aquariums for homes, offices, hotels',
                'duration' => '1-4 weeks',
                'pricing_type' => 'project_based'
            ),
            'maintenance' => array(
                'name' => 'Maintenance Services',
                'description' => 'Scheduled cleaning, water testing, fish care',
                'duration' => 'ongoing',
                'pricing_type' => 'subscription'
            ),
            'quarantine' => array(
                'name' => 'Quarantine & Health Check',
                'description' => 'Disease prevention for export and retail',
                'duration' => '2-4 weeks',
                'pricing_type' => 'per_fish'
            ),
            'training' => array(
                'name' => 'Training & Consultancy',
                'description' => 'Hobbyist training, aquaculture consultancy',
                'duration' => '1-3 days',
                'pricing_type' => 'hourly'
            ),
            'rental' => array(
                'name' => 'Event Rentals',
                'description' => 'Short-term aquarium setups for events',
                'duration' => '1-7 days',
                'pricing_type' => 'daily'
            )
        );
    }
    
    /**
     * Export Countries
     */
    public static function get_export_countries() {
        return array(
            'europe' => array(
                'region' => 'Europe',
                'countries' => array('Germany', 'Netherlands', 'UK', 'France', 'Italy', 'Spain'),
                'lead_time' => '5-7 days',
                'certifications_required' => true
            ),
            'asia' => array(
                'region' => 'Asia',
                'countries' => array('Japan', 'Singapore', 'Malaysia', 'Thailand', 'South Korea'),
                'lead_time' => '3-5 days',
                'certifications_required' => true
            ),
            'middle_east' => array(
                'region' => 'Middle East',
                'countries' => array('UAE', 'Saudi Arabia', 'Qatar', 'Kuwait'),
                'lead_time' => '4-6 days',
                'certifications_required' => true
            ),
            'north_america' => array(
                'region' => 'North America',
                'countries' => array('USA', 'Canada'),
                'lead_time' => '7-10 days',
                'certifications_required' => true
            )
        );
    }
    
    /**
     * Website Navigation Structure
     */
    public static function get_navigation_structure() {
        return array(
            'primary' => array(
                'home' => array('label' => 'Home', 'url' => '/'),
                'about' => array('label' => 'About Us', 'url' => '/about/'),
                'shop' => array(
                    'label' => 'Shop',
                    'url' => '/shop/',
                    'submenu' => array(
                        'fish' => array('label' => 'Fish', 'url' => '/shop/fish/'),
                        'plants' => array('label' => 'Plants', 'url' => '/shop/plants/'),
                        'equipment' => array('label' => 'Equipment', 'url' => '/shop/equipment/'),
                        'accessories' => array('label' => 'Accessories', 'url' => '/shop/accessories/')
                    )
                ),
                'services' => array(
                    'label' => 'Services',
                    'url' => '/services/',
                    'submenu' => array(
                        'design' => array('label' => 'Aquarium Design', 'url' => '/services/design/'),
                        'maintenance' => array('label' => 'Maintenance', 'url' => '/services/maintenance/'),
                        'consultation' => array('label' => 'Consultation', 'url' => '/services/consultation/')
                    )
                ),
                'wholesale' => array('label' => 'Wholesale & B2B', 'url' => '/wholesale/'),
                'trade' => array('label' => 'Buy, Sell & Trade', 'url' => '/trade/'),
                'export' => array('label' => 'Export', 'url' => '/export/'),
                'events' => array('label' => 'Events', 'url' => '/events/'),
                'contact' => array('label' => 'Contact', 'url' => '/contact/')
            ),
            'footer' => array(
                'privacy' => array('label' => 'Privacy Policy', 'url' => '/privacy/'),
                'terms' => array('label' => 'Terms & Conditions', 'url' => '/terms/'),
                'shipping' => array('label' => 'Shipping Policy', 'url' => '/shipping/'),
                'returns' => array('label' => 'Returns', 'url' => '/returns/')
            )
        );
    }
    
    /**
     * Color Scheme (Aquatic Theme)
     */
    public static function get_color_scheme() {
        return array(
            'primary' => '#0077BE',      // Ocean Blue
            'secondary' => '#00A86B',    // Jade Green
            'accent' => '#20B2AA',       // Light Sea Green
            'success' => '#00C851',      // Success Green
            'warning' => '#FF8800',      // Amber
            'danger' => '#FF4444',       // Coral Red
            'info' => '#17A2B8',         // Aqua Blue
            'light' => '#F8F9FA',        // Light Gray
            'dark' => '#1C2331',         // Deep Navy
            'background' => '#FFFFFF',    // White
            'surface' => '#F5F5F5'       // Light Surface
        );
    }
    
    /**
     * Typography Configuration
     */
    public static function get_typography() {
        return array(
            'headings' => array(
                'font_family' => 'Poppins, sans-serif',
                'weights' => array(400, 500, 600, 700)
            ),
            'body' => array(
                'font_family' => 'Open Sans, sans-serif',
                'weights' => array(300, 400, 500, 600)
            ),
            'accent' => array(
                'font_family' => 'Playfair Display, serif',
                'weights' => array(400, 500, 700)
            )
        );
    }
    
    /**
     * Social Media Configuration
     */
    public static function get_social_media() {
        return array(
            'facebook' => array('icon' => 'fab fa-facebook-f', 'label' => 'Facebook'),
            'instagram' => array('icon' => 'fab fa-instagram', 'label' => 'Instagram'),
            'youtube' => array('icon' => 'fab fa-youtube', 'label' => 'YouTube'),
            'twitter' => array('icon' => 'fab fa-twitter', 'label' => 'Twitter'),
            'linkedin' => array('icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'),
            'tiktok' => array('icon' => 'fab fa-tiktok', 'label' => 'TikTok'),
            'whatsapp' => array('icon' => 'fab fa-whatsapp', 'label' => 'WhatsApp')
        );
    }
    
    /**
     * Currency Configuration
     */
    public static function get_currencies() {
        return array(
            'USD' => array('symbol' => '$', 'name' => 'US Dollar'),
            'EUR' => array('symbol' => '€', 'name' => 'Euro'),
            'GBP' => array('symbol' => '£', 'name' => 'British Pound'),
            'AUD' => array('symbol' => 'A$', 'name' => 'Australian Dollar'),
            'CAD' => array('symbol' => 'C$', 'name' => 'Canadian Dollar'),
            'SGD' => array('symbol' => 'S$', 'name' => 'Singapore Dollar'),
            'AED' => array('symbol' => 'د.إ', 'name' => 'UAE Dirham'),
            'JPY' => array('symbol' => '¥', 'name' => 'Japanese Yen')
        );
    }
    
    /**
     * Language Configuration
     */
    public static function get_languages() {
        return array(
            'en' => array('name' => 'English', 'flag' => '🇺🇸', 'rtl' => false),
            'es' => array('name' => 'Español', 'flag' => '🇪🇸', 'rtl' => false),
            'fr' => array('name' => 'Français', 'flag' => '🇫🇷', 'rtl' => false),
            'de' => array('name' => 'Deutsch', 'flag' => '🇩🇪', 'rtl' => false),
            'it' => array('name' => 'Italiano', 'flag' => '🇮🇹', 'rtl' => false),
            'pt' => array('name' => 'Português', 'flag' => '🇵🇹', 'rtl' => false),
            'nl' => array('name' => 'Nederlands', 'flag' => '🇳🇱', 'rtl' => false),
            'ar' => array('name' => 'العربية', 'flag' => '🇸🇦', 'rtl' => true),
            'ja' => array('name' => '日本語', 'flag' => '🇯🇵', 'rtl' => false),
            'ko' => array('name' => '한국어', 'flag' => '🇰🇷', 'rtl' => false)
        );
    }
    
    /**
     * SEO Configuration
     */
    public static function get_seo_config() {
        return array(
            'site_name' => 'AquaLuxe - Premium Ornamental Aquatic Solutions',
            'tagline' => 'Bringing elegance to aquatic life – globally',
            'description' => 'Premium ornamental fish, aquatic plants, custom aquariums, and professional services. Local and international wholesale, retail, trading, and export.',
            'keywords' => array(
                'ornamental fish', 'aquarium', 'aquatic plants', 'fish export',
                'custom aquarium', 'aquarium maintenance', 'wholesale fish',
                'marine fish', 'freshwater fish', 'aquascaping'
            ),
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image'
        );
    }
    
    /**
     * Initialize AquaLuxe Configuration
     */
    public static function init() {
        // Define constants
        if (!defined('AQUALUXE_VERSION')) {
            define('AQUALUXE_VERSION', self::THEME_VERSION);
        }
        
        if (!defined('AQUALUXE_TEXTDOMAIN')) {
            define('AQUALUXE_TEXTDOMAIN', self::THEME_TEXTDOMAIN);
        }
        
        if (!defined('AQUALUXE_PREFIX')) {
            define('AQUALUXE_PREFIX', self::THEME_PREFIX);
        }
        
        // Hook into WordPress init
        add_action('init', array(__CLASS__, 'setup_theme_support'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('customize_register', array(__CLASS__, 'customize_register'));
    }
    
    /**
     * Setup theme support
     */
    public static function setup_theme_support() {
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Custom post types for AquaLuxe
        self::register_post_types();
        
        // Custom taxonomies
        self::register_taxonomies();
    }
    
    /**
     * Register custom post types
     */
    public static function register_post_types() {
        // Fish Species
        register_post_type('fish_species', array(
            'labels' => array(
                'name' => 'Fish Species',
                'singular_name' => 'Fish Species'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'menu_icon' => 'dashicons-admin-generic'
        ));
        
        // Services
        register_post_type('aqualuxe_service', array(
            'labels' => array(
                'name' => 'Services',
                'singular_name' => 'Service'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'menu_icon' => 'dashicons-admin-tools'
        ));
        
        // Events
        register_post_type('aqualuxe_event', array(
            'labels' => array(
                'name' => 'Events',
                'singular_name' => 'Event'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'menu_icon' => 'dashicons-calendar-alt'
        ));
    }
    
    /**
     * Register custom taxonomies
     */
    public static function register_taxonomies() {
        // Fish Categories
        register_taxonomy('fish_category', 'fish_species', array(
            'labels' => array(
                'name' => 'Fish Categories',
                'singular_name' => 'Fish Category'
            ),
            'hierarchical' => true,
            'public' => true
        ));
        
        // Service Categories
        register_taxonomy('service_category', 'aqualuxe_service', array(
            'labels' => array(
                'name' => 'Service Categories',
                'singular_name' => 'Service Category'
            ),
            'hierarchical' => true,
            'public' => true
        ));
    }
    
    /**
     * Enqueue theme assets
     */
    public static function enqueue_assets() {
        // Google Fonts
        wp_enqueue_style('aqualuxe-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&family=Playfair+Display:wght@400;500;700&display=swap');
        
        // Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        
        // Theme styles
        wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
        
        // Theme scripts
        wp_enqueue_script('aqualuxe-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'business_info' => array(
                'name' => self::BUSINESS_NAME,
                'tagline' => self::BUSINESS_TAGLINE
            )
        ));
    }
    
    /**
     * Customizer configuration
     */
    public static function customize_register($wp_customize) {
        // AquaLuxe Business Section
        $wp_customize->add_section('aqualuxe_business', array(
            'title' => 'AquaLuxe Business Settings',
            'priority' => 30
        ));
        
        // Business Info
        $wp_customize->add_setting('aqualuxe_business_phone');
        $wp_customize->add_control('aqualuxe_business_phone', array(
            'label' => 'Business Phone',
            'section' => 'aqualuxe_business',
            'type' => 'text'
        ));
        
        $wp_customize->add_setting('aqualuxe_business_email');
        $wp_customize->add_control('aqualuxe_business_email', array(
            'label' => 'Business Email',
            'section' => 'aqualuxe_business',
            'type' => 'email'
        ));
        
        // Export certifications
        $wp_customize->add_setting('aqualuxe_export_certifications');
        $wp_customize->add_control('aqualuxe_export_certifications', array(
            'label' => 'Export Certifications',
            'section' => 'aqualuxe_business',
            'type' => 'textarea'
        ));
    }
}

// Initialize AquaLuxe Configuration
AquaLuxe_Config::init();
