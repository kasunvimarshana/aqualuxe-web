<?php
/**
 * AquaLuxe Template Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Template_Hooks Class
 * 
 * Handles the theme template hooks
 */
class AquaLuxe_Template_Hooks {
    /**
     * Constructor
     */
    public function __construct() {
        // Header hooks
        add_action('aqualuxe_header', array($this, 'header_top_bar'), 10);
        add_action('aqualuxe_header', array($this, 'header_main'), 20);
        add_action('aqualuxe_header', array($this, 'header_navigation'), 30);
        
        // Footer hooks
        add_action('aqualuxe_footer', array($this, 'footer_widgets'), 10);
        add_action('aqualuxe_footer', array($this, 'footer_bottom'), 20);
        
        // Content hooks
        add_action('aqualuxe_before_main_content', array($this, 'breadcrumbs'), 10);
        add_action('aqualuxe_before_main_content', array($this, 'page_header'), 20);
        
        // Post hooks
        add_action('aqualuxe_post_header', array($this, 'post_title'), 10);
        add_action('aqualuxe_post_header', array($this, 'post_meta'), 20);
        add_action('aqualuxe_post_content', array($this, 'post_featured_image'), 10);
        add_action('aqualuxe_post_content', array($this, 'post_content'), 20);
        add_action('aqualuxe_post_footer', array($this, 'post_tags'), 10);
        add_action('aqualuxe_post_footer', array($this, 'post_share'), 20);
        add_action('aqualuxe_after_post_content', array($this, 'post_author_bio'), 10);
        add_action('aqualuxe_after_post_content', array($this, 'post_navigation'), 20);
        add_action('aqualuxe_after_post_content', array($this, 'related_posts'), 30);
        
        // Page hooks
        add_action('aqualuxe_page_header', array($this, 'page_title'), 10);
        add_action('aqualuxe_page_content', array($this, 'page_featured_image'), 10);
        add_action('aqualuxe_page_content', array($this, 'page_content'), 20);
        
        // Sidebar hooks
        add_action('aqualuxe_sidebar', array($this, 'get_sidebar'), 10);
        
        // WooCommerce hooks (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            // Remove default WooCommerce wrappers
            remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
            remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
            
            // Add custom wrappers
            add_action('woocommerce_before_main_content', array($this, 'woocommerce_wrapper_start'), 10);
            add_action('woocommerce_after_main_content', array($this, 'woocommerce_wrapper_end'), 10);
            
            // Shop hooks
            add_action('woocommerce_before_shop_loop', array($this, 'shop_filter_toggle'), 15);
            add_action('woocommerce_before_shop_loop', array($this, 'shop_active_filters'), 20);
            
            // Single product hooks
            add_action('woocommerce_before_single_product_summary', array($this, 'product_badges'), 5);
            add_action('woocommerce_single_product_summary', array($this, 'product_stock_status'), 25);
            add_action('woocommerce_single_product_summary', array($this, 'product_share'), 50);
            add_action('woocommerce_after_single_product_summary', array($this, 'product_recently_viewed'), 15);
            
            // Cart hooks
            add_action('woocommerce_before_cart', array($this, 'cart_progress'), 10);
            
            // Checkout hooks
            add_action('woocommerce_before_checkout_form', array($this, 'checkout_progress'), 10);
        }
    }

    /**
     * Header top bar
     */
    public function header_top_bar() {
        if (aqualuxe_get_option('header_top_bar', true)) {
            get_template_part('template-parts/header/top-bar');
        }
    }

    /**
     * Header main
     */
    public function header_main() {
        $header_layout = aqualuxe_get_option('header_layout', 'default');
        get_template_part('template-parts/header/header', $header_layout);
    }

    /**
     * Header navigation
     */
    public function header_navigation() {
        get_template_part('template-parts/header/navigation');
    }

    /**
     * Footer widgets
     */
    public function footer_widgets() {
        $footer_layout = aqualuxe_get_option('footer_layout', '4-columns');
        if ($footer_layout !== 'none') {
            get_template_part('template-parts/footer/widgets', $footer_layout);
        }
    }

    /**
     * Footer bottom
     */
    public function footer_bottom() {
        get_template_part('template-parts/footer/bottom');
    }

    /**
     * Breadcrumbs
     */
    public function breadcrumbs() {
        if (aqualuxe_get_option('enable_breadcrumbs', true)) {
            get_template_part('template-parts/content/breadcrumbs');
        }
    }

    /**
     * Page header
     */
    public function page_header() {
        if (is_page() && !is_front_page()) {
            get_template_part('template-parts/content/page-header');
        } elseif (is_archive() || is_search()) {
            get_template_part('template-parts/content/archive-header');
        }
    }

    /**
     * Post title
     */
    public function post_title() {
        if (is_singular('post')) {
            get_template_part('template-parts/content/post-title');
        }
    }

    /**
     * Post meta
     */
    public function post_meta() {
        if (is_singular('post') && aqualuxe_get_option('single_show_post_meta', true)) {
            get_template_part('template-parts/content/post-meta');
        }
    }

    /**
     * Post featured image
     */
    public function post_featured_image() {
        if (is_singular('post') && aqualuxe_get_option('single_show_featured_image', true)) {
            get_template_part('template-parts/content/post-featured-image');
        }
    }

    /**
     * Post content
     */
    public function post_content() {
        if (is_singular('post')) {
            get_template_part('template-parts/content/post-content');
        }
    }

    /**
     * Post tags
     */
    public function post_tags() {
        if (is_singular('post')) {
            get_template_part('template-parts/content/post-tags');
        }
    }

    /**
     * Post share
     */
    public function post_share() {
        if (is_singular('post')) {
            get_template_part('template-parts/content/post-share');
        }
    }

    /**
     * Post author bio
     */
    public function post_author_bio() {
        if (is_singular('post') && aqualuxe_get_option('show_author_bio', true)) {
            get_template_part('template-parts/content/post-author-bio');
        }
    }

    /**
     * Post navigation
     */
    public function post_navigation() {
        if (is_singular('post') && aqualuxe_get_option('show_post_nav', true)) {
            get_template_part('template-parts/content/post-navigation');
        }
    }

    /**
     * Related posts
     */
    public function related_posts() {
        if (is_singular('post') && aqualuxe_get_option('show_related_posts', true)) {
            get_template_part('template-parts/content/related-posts');
        }
    }

    /**
     * Page title
     */
    public function page_title() {
        if (is_page()) {
            get_template_part('template-parts/content/page-title');
        }
    }

    /**
     * Page featured image
     */
    public function page_featured_image() {
        if (is_page() && has_post_thumbnail()) {
            get_template_part('template-parts/content/page-featured-image');
        }
    }

    /**
     * Page content
     */
    public function page_content() {
        if (is_page()) {
            get_template_part('template-parts/content/page-content');
        }
    }

    /**
     * Get sidebar
     */
    public function get_sidebar() {
        $sidebar_position = aqualuxe_get_option('sidebar_position', 'right');
        
        if ($sidebar_position !== 'none') {
            if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag())) {
                if (aqualuxe_get_option('show_shop_sidebar', true)) {
                    get_sidebar('shop');
                }
            } elseif (class_exists('WooCommerce') && is_product()) {
                if (aqualuxe_get_option('show_product_sidebar', false)) {
                    get_sidebar('shop');
                }
            } else {
                get_sidebar();
            }
        }
    }

    /**
     * WooCommerce wrapper start
     */
    public function woocommerce_wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main" role="main">';
    }

    /**
     * WooCommerce wrapper end
     */
    public function woocommerce_wrapper_end() {
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
    }

    /**
     * Shop filter toggle
     */
    public function shop_filter_toggle() {
        get_template_part('template-parts/woocommerce/shop-filter-toggle');
    }

    /**
     * Shop active filters
     */
    public function shop_active_filters() {
        get_template_part('template-parts/woocommerce/shop-active-filters');
    }

    /**
     * Product badges
     */
    public function product_badges() {
        get_template_part('template-parts/woocommerce/product-badges');
    }

    /**
     * Product stock status
     */
    public function product_stock_status() {
        get_template_part('template-parts/woocommerce/product-stock-status');
    }

    /**
     * Product share
     */
    public function product_share() {
        get_template_part('template-parts/woocommerce/product-share');
    }

    /**
     * Product recently viewed
     */
    public function product_recently_viewed() {
        get_template_part('template-parts/woocommerce/product-recently-viewed');
    }

    /**
     * Cart progress
     */
    public function cart_progress() {
        get_template_part('template-parts/woocommerce/cart-progress');
    }

    /**
     * Checkout progress
     */
    public function checkout_progress() {
        get_template_part('template-parts/woocommerce/checkout-progress');
    }
}

// Initialize the class
new AquaLuxe_Template_Hooks();