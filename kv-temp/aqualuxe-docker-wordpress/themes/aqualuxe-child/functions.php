<?php
/**
 * AquaLuxe Child Theme Functions
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Child Theme Class
 */
class AquaLuxeChildTheme
{
    /**
     * Theme version
     */
    const VERSION = '1.0.0';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_action('init', [$this, 'register_menus']);
        
        // WooCommerce hooks
        add_action('woocommerce_before_shop_loop', [$this, 'add_shop_filters']);
        add_filter('woocommerce_product_tabs', [$this, 'customize_product_tabs']);
        add_action('woocommerce_single_product_summary', [$this, 'add_product_features'], 25);
        
        // Custom post types
        add_action('init', [$this, 'register_custom_post_types']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_filter_products', [$this, 'ajax_filter_products']);
        add_action('wp_ajax_nopriv_aqualuxe_filter_products', [$this, 'ajax_filter_products']);
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles(): void
    {
        // Parent theme style
        wp_enqueue_style(
            'storefront-style',
            get_template_directory_uri() . '/style.css',
            [],
            wp_get_theme()->get('Version')
        );

        // Child theme style
        wp_enqueue_style(
            'aqualuxe-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            ['storefront-style'],
            self::VERSION
        );

        // Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
            [],
            null
        );

        // Custom CSS for specific pages
        if (is_shop() || is_product_category()) {
            wp_enqueue_style(
                'aqualuxe-shop',
                get_stylesheet_directory_uri() . '/assets/css/shop.css',
                ['aqualuxe-child-style'],
                self::VERSION
            );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(): void
    {
        // Main theme script
        wp_enqueue_script(
            'aqualuxe-main',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            ['jquery'],
            self::VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'loading_text' => __('Loading...', 'aqualuxe-child'),
        ]);

        // Product filter script for shop pages
        if (is_shop() || is_product_category()) {
            wp_enqueue_script(
                'aqualuxe-shop',
                get_stylesheet_directory_uri() . '/assets/js/shop.js',
                ['jquery', 'aqualuxe-main'],
                self::VERSION,
                true
            );
        }
    }

    /**
     * Theme setup
     */
    public function theme_setup(): void
    {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);

        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }

    /**
     * Register sidebars
     */
    public function register_sidebars(): void
    {
        register_sidebar([
            'name' => __('AquaLuxe Shop Sidebar', 'aqualuxe-child'),
            'id' => 'aqualuxe-shop-sidebar',
            'description' => __('Sidebar for shop pages', 'aqualuxe-child'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => __('AquaLuxe Footer 1', 'aqualuxe-child'),
            'id' => 'aqualuxe-footer-1',
            'description' => __('Footer widget area 1', 'aqualuxe-child'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ]);

        register_sidebar([
            'name' => __('AquaLuxe Footer 2', 'aqualuxe-child'),
            'id' => 'aqualuxe-footer-2',
            'description' => __('Footer widget area 2', 'aqualuxe-child'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ]);
    }

    /**
     * Register navigation menus
     */
    public function register_menus(): void
    {
        register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe-child'),
            'footer' => __('Footer Menu', 'aqualuxe-child'),
            'mobile' => __('Mobile Menu', 'aqualuxe-child'),
        ]);
    }

    /**
     * Add shop filters
     */
    public function add_shop_filters(): void
    {
        if (is_shop() || is_product_category()) {
            get_template_part('template-parts/shop-filters');
        }
    }

    /**
     * Customize product tabs
     */
    public function customize_product_tabs(array $tabs): array
    {
        // Add care instructions tab
        $tabs['care_instructions'] = [
            'title' => __('Care Instructions', 'aqualuxe-child'),
            'priority' => 25,
            'callback' => [$this, 'care_instructions_tab_content'],
        ];

        // Add compatibility tab
        $tabs['compatibility'] = [
            'title' => __('Compatibility', 'aqualuxe-child'),
            'priority' => 30,
            'callback' => [$this, 'compatibility_tab_content'],
        ];

        return $tabs;
    }

    /**
     * Care instructions tab content
     */
    public function care_instructions_tab_content(): void
    {
        global $product;
        
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if ($care_instructions) {
            echo wp_kses_post($care_instructions);
        } else {
            echo '<p>' . __('Care instructions will be provided with your purchase.', 'aqualuxe-child') . '</p>';
        }
    }

    /**
     * Compatibility tab content
     */
    public function compatibility_tab_content(): void
    {
        global $product;
        
        $compatibility = get_post_meta($product->get_id(), '_compatibility', true);
        
        if ($compatibility) {
            echo wp_kses_post($compatibility);
        } else {
            echo '<p>' . __('Please contact us for compatibility information.', 'aqualuxe-child') . '</p>';
        }
    }

    /**
     * Add product features
     */
    public function add_product_features(): void
    {
        global $product;
        
        $features = get_post_meta($product->get_id(), '_product_features', true);
        
        if ($features && is_array($features)) {
            echo '<div class="aqualuxe-product-features">';
            echo '<h3>' . __('Key Features', 'aqualuxe-child') . '</h3>';
            echo '<ul>';
            foreach ($features as $feature) {
                echo '<li>' . esc_html($feature) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }

    /**
     * Register custom post types
     */
    public function register_custom_post_types(): void
    {
        // Fish Care Guide post type
        register_post_type('fish_care_guide', [
            'labels' => [
                'name' => __('Fish Care Guides', 'aqualuxe-child'),
                'singular_name' => __('Fish Care Guide', 'aqualuxe-child'),
                'add_new' => __('Add New Guide', 'aqualuxe-child'),
                'add_new_item' => __('Add New Fish Care Guide', 'aqualuxe-child'),
                'edit_item' => __('Edit Fish Care Guide', 'aqualuxe-child'),
                'new_item' => __('New Fish Care Guide', 'aqualuxe-child'),
                'view_item' => __('View Fish Care Guide', 'aqualuxe-child'),
                'search_items' => __('Search Fish Care Guides', 'aqualuxe-child'),
                'not_found' => __('No fish care guides found', 'aqualuxe-child'),
                'not_found_in_trash' => __('No fish care guides found in trash', 'aqualuxe-child'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-pets',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite' => ['slug' => 'fish-care'],
        ]);
    }

    /**
     * AJAX filter products
     */
    public function ajax_filter_products(): void
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die('Security check failed');
        }

        $category = sanitize_text_field($_POST['category'] ?? '');
        $price_range = sanitize_text_field($_POST['price_range'] ?? '');
        $sort_by = sanitize_text_field($_POST['sort_by'] ?? '');

        // Build query args
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 12,
            'post_status' => 'publish',
        ];

        // Add category filter
        if ($category) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category,
                ],
            ];
        }

        // Add price filter
        if ($price_range) {
            $price_parts = explode('-', $price_range);
            if (count($price_parts) === 2) {
                $args['meta_query'] = [
                    [
                        'key' => '_price',
                        'value' => [(int)$price_parts[0], (int)$price_parts[1]],
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC',
                    ],
                ];
            }
        }

        // Add sorting
        switch ($sort_by) {
            case 'price_low':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price_high':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'name':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            default:
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ob_start();
            woocommerce_product_loop_start();
            
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
            $output = ob_get_clean();
            
            wp_send_json_success($output);
        } else {
            wp_send_json_error(__('No products found', 'aqualuxe-child'));
        }

        wp_die();
    }
}

// Initialize the theme
new AquaLuxeChildTheme();

/**
 * Helper Functions
 */

/**
 * Get product categories for filter
 */
function aqualuxe_get_product_categories(): array
{
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ]);

    $result = [];
    foreach ($categories as $category) {
        $result[] = [
            'slug' => $category->slug,
            'name' => $category->name,
            'count' => $category->count,
        ];
    }

    return $result;
}

/**
 * Get featured products
 */
function aqualuxe_get_featured_products(int $limit = 8): array
{
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_query' => [
            [
                'key' => '_featured',
                'value' => 'yes',
            ],
        ],
    ];

    return get_posts($args);
}

/**
 * Format price with currency
 */
function aqualuxe_format_price(float $price): string
{
    return wc_price($price);
}

/**
 * Get product rating HTML
 */
function aqualuxe_get_product_rating(int $product_id): string
{
    $product = wc_get_product($product_id);
    if (!$product) {
        return '';
    }

    $rating = $product->get_average_rating();
    $count = $product->get_rating_count();

    if ($rating > 0) {
        return wc_get_rating_html($rating, $count);
    }

    return '';
}

/**
 * Check if product is in stock
 */
function aqualuxe_is_product_in_stock(int $product_id): bool
{
    $product = wc_get_product($product_id);
    return $product && $product->is_in_stock();
}

/**
 * Get product gallery images
 */
function aqualuxe_get_product_gallery(int $product_id): array
{
    $product = wc_get_product($product_id);
    if (!$product) {
        return [];
    }

    $gallery_ids = $product->get_gallery_image_ids();
    $images = [];

    foreach ($gallery_ids as $image_id) {
        $images[] = [
            'id' => $image_id,
            'url' => wp_get_attachment_url($image_id),
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
        ];
    }

    return $images;
}
