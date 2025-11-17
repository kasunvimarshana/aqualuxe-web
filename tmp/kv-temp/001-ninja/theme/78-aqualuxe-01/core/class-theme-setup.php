<?php
/**
 * Theme setup and configuration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', [$this, 'content_width'], 0);
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_filter('excerpt_length', [$this, 'custom_excerpt_length']);
        add_filter('excerpt_more', [$this, 'custom_excerpt_more']);
        add_action('wp_head', [$this, 'add_meta_tags']);
        add_action('wp_head', [$this, 'add_schema_markup']);
    }
    
    /**
     * Set content width
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services post type
        register_post_type('aql_service', [
            'labels' => [
                'name' => esc_html__('Services', 'aqualuxe'),
                'singular_name' => esc_html__('Service', 'aqualuxe'),
                'add_new' => esc_html__('Add New Service', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service', 'aqualuxe'),
                'new_item' => esc_html__('New Service', 'aqualuxe'),
                'view_item' => esc_html__('View Service', 'aqualuxe'),
                'search_items' => esc_html__('Search Services', 'aqualuxe'),
                'not_found' => esc_html__('No services found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No services found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-admin-tools',
            'rewrite' => ['slug' => 'services'],
            'show_in_rest' => true,
        ]);
        
        // Events post type
        register_post_type('aql_event', [
            'labels' => [
                'name' => esc_html__('Events', 'aqualuxe'),
                'singular_name' => esc_html__('Event', 'aqualuxe'),
                'add_new' => esc_html__('Add New Event', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event', 'aqualuxe'),
                'new_item' => esc_html__('New Event', 'aqualuxe'),
                'view_item' => esc_html__('View Event', 'aqualuxe'),
                'search_items' => esc_html__('Search Events', 'aqualuxe'),
                'not_found' => esc_html__('No events found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No events found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-calendar-alt',
            'rewrite' => ['slug' => 'events'],
            'show_in_rest' => true,
        ]);
        
        // Portfolio post type
        register_post_type('aql_portfolio', [
            'labels' => [
                'name' => esc_html__('Portfolio', 'aqualuxe'),
                'singular_name' => esc_html__('Portfolio Item', 'aqualuxe'),
                'add_new' => esc_html__('Add New Item', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Portfolio Item', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Portfolio Item', 'aqualuxe'),
                'new_item' => esc_html__('New Portfolio Item', 'aqualuxe'),
                'view_item' => esc_html__('View Portfolio Item', 'aqualuxe'),
                'search_items' => esc_html__('Search Portfolio', 'aqualuxe'),
                'not_found' => esc_html__('No portfolio items found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No portfolio items found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-portfolio',
            'rewrite' => ['slug' => 'portfolio'],
            'show_in_rest' => true,
        ]);
        
        // Testimonials post type
        register_post_type('aql_testimonial', [
            'labels' => [
                'name' => esc_html__('Testimonials', 'aqualuxe'),
                'singular_name' => esc_html__('Testimonial', 'aqualuxe'),
                'add_new' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Testimonial', 'aqualuxe'),
                'new_item' => esc_html__('New Testimonial', 'aqualuxe'),
                'view_item' => esc_html__('View Testimonial', 'aqualuxe'),
                'search_items' => esc_html__('Search Testimonials', 'aqualuxe'),
                'not_found' => esc_html__('No testimonials found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No testimonials found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'menu_icon' => 'dashicons-format-quote',
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service categories
        register_taxonomy('service_category', 'aql_service', [
            'labels' => [
                'name' => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'service-category'],
        ]);
        
        // Portfolio categories
        register_taxonomy('portfolio_category', 'aql_portfolio', [
            'labels' => [
                'name' => esc_html__('Portfolio Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Portfolio Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'portfolio-category'],
        ]);
        
        // Event categories
        register_taxonomy('event_category', 'aql_event', [
            'labels' => [
                'name' => esc_html__('Event Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Event Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'event-category'],
        ]);
    }
    
    /**
     * Custom excerpt length
     */
    public function custom_excerpt_length($length) {
        return 30;
    }
    
    /**
     * Custom excerpt more
     */
    public function custom_excerpt_more($more) {
        return '...';
    }
    
    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        
        // Open Graph tags
        if (is_singular()) {
            global $post;
            $title = get_the_title();
            $description = get_the_excerpt() ? get_the_excerpt() : get_bloginfo('description');
            $image = get_the_post_thumbnail_url($post->ID, 'large');
            $url = get_permalink();
            
            echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
            echo '<meta property="og:type" content="article">' . "\n";
            
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
            }
        }
        
        // Twitter Card tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        // Theme color
        echo '<meta name="theme-color" content="#14b8a6">' . "\n";
    }
    
    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        if (is_singular()) {
            global $post;
            
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => get_the_title(),
                'description' => get_the_excerpt() ? get_the_excerpt() : get_bloginfo('description'),
                'url' => get_permalink(),
                'datePublished' => get_the_date('c'),
                'dateModified' => get_the_modified_date('c'),
                'author' => [
                    '@type' => 'Person',
                    'name' => get_the_author(),
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                    ],
                ],
            ];
            
            if (has_post_thumbnail()) {
                $schema['image'] = get_the_post_thumbnail_url($post->ID, 'large');
            }
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }
}

// Initialize
new AquaLuxe_Theme_Setup();
