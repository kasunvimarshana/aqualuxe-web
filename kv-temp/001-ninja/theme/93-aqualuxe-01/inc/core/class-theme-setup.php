<?php
/**
 * Theme Setup Class
 *
 * Handles core theme setup and configuration.
 * This class implements the Single Responsibility Principle
 * by focusing solely on theme initialization.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Theme_Setup
 */
class AquaLuxe_Theme_Setup {
    
    /**
     * Theme configuration
     * @var array
     */
    private $config;
    
    /**
     * Constructor
     * @param array $config Theme configuration array
     */
    public function __construct($config = []) {
        $this->config = $config;
        $this->init();
    }
    
    /**
     * Initialize the theme setup
     */
    private function init() {
        add_action('after_setup_theme', [$this, 'setup_theme_support']);
        add_action('after_setup_theme', [$this, 'setup_image_sizes']);
        add_action('after_setup_theme', [$this, 'setup_menus']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_editor_styles']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
        add_filter('body_class', [$this, 'add_body_classes']);
        add_filter('post_class', [$this, 'add_post_classes']);
    }
    
    /**
     * Set up theme support features
     */
    public function setup_theme_support() {
        // Core WordPress features
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        add_theme_support('custom-logo', [
            'height' => 100,
            'width' => 300,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => ['site-title', 'site-description']
        ]);
        add_theme_support('custom-header', [
            'default-image' => '',
            'width' => 1920,
            'height' => 1080,
            'flex-height' => true,
            'flex-width' => true,
            'uploads' => true,
            'header-text' => false
        ]);
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
            'default-preset' => 'fill',
            'default-position-x' => 'center',
            'default-position-y' => 'center',
            'default-size' => 'cover',
            'default-repeat' => 'no-repeat'
        ]);
        
        // HTML5 support
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        
        // Post formats
        add_theme_support('post-formats', [
            'aside',
            'gallery',
            'video',
            'quote',
            'link',
            'status',
            'audio'
        ]);
        
        // WordPress 5.0+ Gutenberg features
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        
        // Custom color palette for Gutenberg
        add_theme_support('editor-color-palette', [
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
            ],
            [
                'name' => __('White', 'aqualuxe'),
                'slug' => 'white',
                'color' => '#ffffff'
            ],
            [
                'name' => __('Black', 'aqualuxe'),
                'slug' => 'black',
                'color' => '#000000'
            ]
        ]);
        
        // Custom font sizes for Gutenberg
        add_theme_support('editor-font-sizes', [
            [
                'name' => __('Small', 'aqualuxe'),
                'slug' => 'small',
                'size' => 14
            ],
            [
                'name' => __('Normal', 'aqualuxe'),
                'slug' => 'normal',
                'size' => 16
            ],
            [
                'name' => __('Medium', 'aqualuxe'),
                'slug' => 'medium',
                'size' => 20
            ],
            [
                'name' => __('Large', 'aqualuxe'),
                'slug' => 'large',
                'size' => 24
            ],
            [
                'name' => __('Extra Large', 'aqualuxe'),
                'slug' => 'extra-large',
                'size' => 32
            ]
        ]);
        
        // WooCommerce support
        if (class_exists('WooCommerce')) {
            add_theme_support('woocommerce', [
                'thumbnail_image_width' => 300,
                'single_image_width' => 600,
                'gallery_thumbnail_image_width' => 100,
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
        
        // Content width
        if (!isset($GLOBALS['content_width'])) {
            $GLOBALS['content_width'] = 1200;
        }
    }
    
    /**
     * Register custom image sizes
     */
    public function setup_image_sizes() {
        $image_sizes = $this->config['image_sizes'] ?? [];
        
        foreach ($image_sizes as $name => $size) {
            add_image_size(
                $name,
                $size['width'],
                $size['height'],
                $size['crop'] ?? false
            );
        }
    }
    
    /**
     * Register navigation menus
     */
    public function setup_menus() {
        $menus = $this->config['menus'] ?? [];
        
        if (!empty($menus)) {
            register_nav_menus($menus);
        }
    }
    
    /**
     * Register widget areas (sidebars)
     */
    public function register_sidebars() {
        $sidebars = $this->config['sidebars'] ?? [];
        
        foreach ($sidebars as $id => $sidebar) {
            register_sidebar(array_merge([
                'id' => $id,
                'name' => $sidebar['name'],
                'description' => $sidebar['description'] ?? '',
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            ], $sidebar));
        }
    }
    
    /**
     * Enqueue editor styles
     */
    public function enqueue_editor_styles() {
        if (is_admin()) {
            add_editor_style('assets/dist/css/editor.css');
        }
    }
    
    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        // Block editor styles
        wp_enqueue_style(
            'aqualuxe-block-editor',
            AQUALUXE_ASSETS_URI . '/dist/css/editor.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Block editor scripts
        wp_enqueue_script(
            'aqualuxe-block-editor',
            AQUALUXE_ASSETS_URI . '/dist/js/editor.js',
            ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Add custom body classes
     * @param array $classes Existing body classes
     * @return array Modified body classes
     */
    public function add_body_classes($classes) {
        // Add theme-specific class
        $classes[] = 'aqualuxe-theme';
        
        // Add WooCommerce status
        if (class_exists('WooCommerce')) {
            $classes[] = 'woocommerce-enabled';
            
            if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
                $classes[] = 'woocommerce-page';
            }
        } else {
            $classes[] = 'woocommerce-disabled';
        }
        
        // Add page template class
        if (is_page_template()) {
            $template = get_page_template_slug();
            $classes[] = 'page-template-' . sanitize_html_class(str_replace(['/', '.php'], ['-', ''], $template));
        }
        
        // Add mobile detection
        if (wp_is_mobile()) {
            $classes[] = 'mobile-device';
        }
        
        // Add dark mode support
        $classes[] = 'supports-dark-mode';
        
        // Add accessibility class if user prefers reduced motion
        $classes[] = 'supports-prefers-reduced-motion';
        
        return array_unique($classes);
    }
    
    /**
     * Add custom post classes
     * @param array $classes Existing post classes
     * @return array Modified post classes
     */
    public function add_post_classes($classes) {
        global $post;
        
        if (!$post) {
            return $classes;
        }
        
        // Add has-thumbnail class
        if (has_post_thumbnail($post->ID)) {
            $classes[] = 'has-post-thumbnail';
        } else {
            $classes[] = 'no-post-thumbnail';
        }
        
        // Add excerpt class
        if (has_excerpt($post->ID)) {
            $classes[] = 'has-excerpt';
        }
        
        // Add comment status
        if (comments_open($post->ID)) {
            $classes[] = 'comments-open';
        } else {
            $classes[] = 'comments-closed';
        }
        
        // Add word count class
        $word_count = str_word_count(strip_tags($post->post_content));
        if ($word_count < 300) {
            $classes[] = 'short-content';
        } elseif ($word_count > 1000) {
            $classes[] = 'long-content';
        } else {
            $classes[] = 'medium-content';
        }
        
        return array_unique($classes);
    }
}
