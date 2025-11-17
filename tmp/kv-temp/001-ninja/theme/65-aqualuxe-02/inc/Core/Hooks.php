<?php
/**
 * Hooks Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Hooks Class
 * 
 * Handles theme hooks and filters
 */
class Hooks {
    /**
     * Instance of this class
     *
     * @var Hooks
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Hooks
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Content hooks
        $this->register_content_hooks();
        
        // Header hooks
        $this->register_header_hooks();
        
        // Footer hooks
        $this->register_footer_hooks();
        
        // Sidebar hooks
        $this->register_sidebar_hooks();
        
        // Comments hooks
        $this->register_comments_hooks();
        
        // Post hooks
        $this->register_post_hooks();
        
        // Page hooks
        $this->register_page_hooks();
        
        // Archive hooks
        $this->register_archive_hooks();
        
        // Search hooks
        $this->register_search_hooks();
        
        // 404 hooks
        $this->register_404_hooks();
        
        // WooCommerce hooks
        if ( class_exists( 'WooCommerce' ) ) {
            $this->register_woocommerce_hooks();
        }
    }

    /**
     * Register content hooks
     */
    private function register_content_hooks() {
        // Before content
        add_action( 'aqualuxe_before_content', [ $this, 'before_content' ] );
        
        // After content
        add_action( 'aqualuxe_after_content', [ $this, 'after_content' ] );
        
        // No content
        add_action( 'aqualuxe_no_content', [ $this, 'no_content' ] );
    }

    /**
     * Register header hooks
     */
    private function register_header_hooks() {
        // Before header
        add_action( 'aqualuxe_before_header', [ $this, 'before_header' ] );
        
        // Header
        add_action( 'aqualuxe_header', [ $this, 'header' ] );
        
        // After header
        add_action( 'aqualuxe_after_header', [ $this, 'after_header' ] );
    }

    /**
     * Register footer hooks
     */
    private function register_footer_hooks() {
        // Before footer
        add_action( 'aqualuxe_before_footer', [ $this, 'before_footer' ] );
        
        // Footer
        add_action( 'aqualuxe_footer', [ $this, 'footer' ] );
        
        // After footer
        add_action( 'aqualuxe_after_footer', [ $this, 'after_footer' ] );
    }

    /**
     * Register sidebar hooks
     */
    private function register_sidebar_hooks() {
        // Before sidebar
        add_action( 'aqualuxe_before_sidebar', [ $this, 'before_sidebar' ] );
        
        // Sidebar
        add_action( 'aqualuxe_sidebar', [ $this, 'sidebar' ] );
        
        // After sidebar
        add_action( 'aqualuxe_after_sidebar', [ $this, 'after_sidebar' ] );
    }

    /**
     * Register comments hooks
     */
    private function register_comments_hooks() {
        // Before comments
        add_action( 'aqualuxe_before_comments', [ $this, 'before_comments' ] );
        
        // Comments
        add_action( 'aqualuxe_comments', [ $this, 'comments' ] );
        
        // After comments
        add_action( 'aqualuxe_after_comments', [ $this, 'after_comments' ] );
        
        // No comments
        add_action( 'aqualuxe_no_comments', [ $this, 'no_comments' ] );
        
        // Comments closed
        add_action( 'aqualuxe_comments_closed', [ $this, 'comments_closed' ] );
    }

    /**
     * Register post hooks
     */
    private function register_post_hooks() {
        // Before post
        add_action( 'aqualuxe_before_post', [ $this, 'before_post' ] );
        
        // Post
        add_action( 'aqualuxe_post', [ $this, 'post' ] );
        
        // After post
        add_action( 'aqualuxe_after_post', [ $this, 'after_post' ] );
        
        // Post meta
        add_action( 'aqualuxe_post_meta', [ $this, 'post_meta' ] );
        
        // Post thumbnail
        add_action( 'aqualuxe_post_thumbnail', [ $this, 'post_thumbnail' ] );
        
        // Post content
        add_action( 'aqualuxe_post_content', [ $this, 'post_content' ] );
        
        // Post excerpt
        add_action( 'aqualuxe_post_excerpt', [ $this, 'post_excerpt' ] );
        
        // Post author
        add_action( 'aqualuxe_post_author', [ $this, 'post_author' ] );
        
        // Post navigation
        add_action( 'aqualuxe_post_navigation', [ $this, 'post_navigation' ] );
        
        // Post tags
        add_action( 'aqualuxe_post_tags', [ $this, 'post_tags' ] );
        
        // Post categories
        add_action( 'aqualuxe_post_categories', [ $this, 'post_categories' ] );
        
        // Post comments
        add_action( 'aqualuxe_post_comments', [ $this, 'post_comments' ] );
        
        // Post related
        add_action( 'aqualuxe_post_related', [ $this, 'post_related' ] );
    }

    /**
     * Register page hooks
     */
    private function register_page_hooks() {
        // Before page
        add_action( 'aqualuxe_before_page', [ $this, 'before_page' ] );
        
        // Page
        add_action( 'aqualuxe_page', [ $this, 'page' ] );
        
        // After page
        add_action( 'aqualuxe_after_page', [ $this, 'after_page' ] );
        
        // Page title
        add_action( 'aqualuxe_page_title', [ $this, 'page_title' ] );
        
        // Page content
        add_action( 'aqualuxe_page_content', [ $this, 'page_content' ] );
        
        // Page navigation
        add_action( 'aqualuxe_page_navigation', [ $this, 'page_navigation' ] );
        
        // Page comments
        add_action( 'aqualuxe_page_comments', [ $this, 'page_comments' ] );
    }

    /**
     * Register archive hooks
     */
    private function register_archive_hooks() {
        // Before archive
        add_action( 'aqualuxe_before_archive', [ $this, 'before_archive' ] );
        
        // Archive
        add_action( 'aqualuxe_archive', [ $this, 'archive' ] );
        
        // After archive
        add_action( 'aqualuxe_after_archive', [ $this, 'after_archive' ] );
        
        // Archive title
        add_action( 'aqualuxe_archive_title', [ $this, 'archive_title' ] );
        
        // Archive description
        add_action( 'aqualuxe_archive_description', [ $this, 'archive_description' ] );
        
        // Archive navigation
        add_action( 'aqualuxe_archive_navigation', [ $this, 'archive_navigation' ] );
    }

    /**
     * Register search hooks
     */
    private function register_search_hooks() {
        // Before search
        add_action( 'aqualuxe_before_search', [ $this, 'before_search' ] );
        
        // Search
        add_action( 'aqualuxe_search', [ $this, 'search' ] );
        
        // After search
        add_action( 'aqualuxe_after_search', [ $this, 'after_search' ] );
        
        // Search title
        add_action( 'aqualuxe_search_title', [ $this, 'search_title' ] );
        
        // Search form
        add_action( 'aqualuxe_search_form', [ $this, 'search_form' ] );
        
        // No search results
        add_action( 'aqualuxe_no_search_results', [ $this, 'no_search_results' ] );
    }

    /**
     * Register 404 hooks
     */
    private function register_404_hooks() {
        // Before 404
        add_action( 'aqualuxe_before_404', [ $this, 'before_404' ] );
        
        // 404
        add_action( 'aqualuxe_404', [ $this, '404' ] );
        
        // After 404
        add_action( 'aqualuxe_after_404', [ $this, 'after_404' ] );
        
        // 404 title
        add_action( 'aqualuxe_404_title', [ $this, '404_title' ] );
        
        // 404 content
        add_action( 'aqualuxe_404_content', [ $this, '404_content' ] );
        
        // 404 search
        add_action( 'aqualuxe_404_search', [ $this, '404_search' ] );
    }

    /**
     * Register WooCommerce hooks
     */
    private function register_woocommerce_hooks() {
        // Before shop
        add_action( 'aqualuxe_before_shop', [ $this, 'before_shop' ] );
        
        // Shop
        add_action( 'aqualuxe_shop', [ $this, 'shop' ] );
        
        // After shop
        add_action( 'aqualuxe_after_shop', [ $this, 'after_shop' ] );
        
        // Before product
        add_action( 'aqualuxe_before_product', [ $this, 'before_product' ] );
        
        // Product
        add_action( 'aqualuxe_product', [ $this, 'product' ] );
        
        // After product
        add_action( 'aqualuxe_after_product', [ $this, 'after_product' ] );
        
        // Before cart
        add_action( 'aqualuxe_before_cart', [ $this, 'before_cart' ] );
        
        // Cart
        add_action( 'aqualuxe_cart', [ $this, 'cart' ] );
        
        // After cart
        add_action( 'aqualuxe_after_cart', [ $this, 'after_cart' ] );
        
        // Before checkout
        add_action( 'aqualuxe_before_checkout', [ $this, 'before_checkout' ] );
        
        // Checkout
        add_action( 'aqualuxe_checkout', [ $this, 'checkout' ] );
        
        // After checkout
        add_action( 'aqualuxe_after_checkout', [ $this, 'after_checkout' ] );
        
        // Before account
        add_action( 'aqualuxe_before_account', [ $this, 'before_account' ] );
        
        // Account
        add_action( 'aqualuxe_account', [ $this, 'account' ] );
        
        // After account
        add_action( 'aqualuxe_after_account', [ $this, 'after_account' ] );
    }

    /**
     * Before content
     */
    public function before_content() {
        Template::get_template_part( 'templates/global/before-content' );
    }

    /**
     * After content
     */
    public function after_content() {
        Template::get_template_part( 'templates/global/after-content' );
    }

    /**
     * No content
     */
    public function no_content() {
        Template::get_template_part( 'templates/global/no-content' );
    }

    /**
     * Before header
     */
    public function before_header() {
        Template::get_template_part( 'templates/header/before-header' );
    }

    /**
     * Header
     */
    public function header() {
        Template::get_template_part( 'templates/header/header' );
    }

    /**
     * After header
     */
    public function after_header() {
        Template::get_template_part( 'templates/header/after-header' );
    }

    /**
     * Before footer
     */
    public function before_footer() {
        Template::get_template_part( 'templates/footer/before-footer' );
    }

    /**
     * Footer
     */
    public function footer() {
        Template::get_template_part( 'templates/footer/footer' );
    }

    /**
     * After footer
     */
    public function after_footer() {
        Template::get_template_part( 'templates/footer/after-footer' );
    }

    /**
     * Before sidebar
     */
    public function before_sidebar() {
        Template::get_template_part( 'templates/sidebar/before-sidebar' );
    }

    /**
     * Sidebar
     */
    public function sidebar() {
        Template::get_template_part( 'templates/sidebar/sidebar' );
    }

    /**
     * After sidebar
     */
    public function after_sidebar() {
        Template::get_template_part( 'templates/sidebar/after-sidebar' );
    }

    /**
     * Before comments
     */
    public function before_comments() {
        Template::get_template_part( 'templates/comments/before-comments' );
    }

    /**
     * Comments
     */
    public function comments() {
        Template::get_template_part( 'templates/comments/comments' );
    }

    /**
     * After comments
     */
    public function after_comments() {
        Template::get_template_part( 'templates/comments/after-comments' );
    }

    /**
     * No comments
     */
    public function no_comments() {
        Template::get_template_part( 'templates/comments/no-comments' );
    }

    /**
     * Comments closed
     */
    public function comments_closed() {
        Template::get_template_part( 'templates/comments/comments-closed' );
    }

    /**
     * Before post
     */
    public function before_post() {
        Template::get_template_part( 'templates/post/before-post' );
    }

    /**
     * Post
     */
    public function post() {
        Template::get_template_part( 'templates/post/post' );
    }

    /**
     * After post
     */
    public function after_post() {
        Template::get_template_part( 'templates/post/after-post' );
    }

    /**
     * Post meta
     */
    public function post_meta() {
        Template::get_template_part( 'templates/post/meta' );
    }

    /**
     * Post thumbnail
     */
    public function post_thumbnail() {
        Template::get_template_part( 'templates/post/thumbnail' );
    }

    /**
     * Post content
     */
    public function post_content() {
        Template::get_template_part( 'templates/post/content' );
    }

    /**
     * Post excerpt
     */
    public function post_excerpt() {
        Template::get_template_part( 'templates/post/excerpt' );
    }

    /**
     * Post author
     */
    public function post_author() {
        Template::get_template_part( 'templates/post/author' );
    }

    /**
     * Post navigation
     */
    public function post_navigation() {
        Template::get_template_part( 'templates/post/navigation' );
    }

    /**
     * Post tags
     */
    public function post_tags() {
        Template::get_template_part( 'templates/post/tags' );
    }

    /**
     * Post categories
     */
    public function post_categories() {
        Template::get_template_part( 'templates/post/categories' );
    }

    /**
     * Post comments
     */
    public function post_comments() {
        Template::get_template_part( 'templates/post/comments' );
    }

    /**
     * Post related
     */
    public function post_related() {
        Template::get_template_part( 'templates/post/related' );
    }

    /**
     * Before page
     */
    public function before_page() {
        Template::get_template_part( 'templates/page/before-page' );
    }

    /**
     * Page
     */
    public function page() {
        Template::get_template_part( 'templates/page/page' );
    }

    /**
     * After page
     */
    public function after_page() {
        Template::get_template_part( 'templates/page/after-page' );
    }

    /**
     * Page title
     */
    public function page_title() {
        Template::get_template_part( 'templates/page/title' );
    }

    /**
     * Page content
     */
    public function page_content() {
        Template::get_template_part( 'templates/page/content' );
    }

    /**
     * Page navigation
     */
    public function page_navigation() {
        Template::get_template_part( 'templates/page/navigation' );
    }

    /**
     * Page comments
     */
    public function page_comments() {
        Template::get_template_part( 'templates/page/comments' );
    }

    /**
     * Before archive
     */
    public function before_archive() {
        Template::get_template_part( 'templates/archive/before-archive' );
    }

    /**
     * Archive
     */
    public function archive() {
        Template::get_template_part( 'templates/archive/archive' );
    }

    /**
     * After archive
     */
    public function after_archive() {
        Template::get_template_part( 'templates/archive/after-archive' );
    }

    /**
     * Archive title
     */
    public function archive_title() {
        Template::get_template_part( 'templates/archive/title' );
    }

    /**
     * Archive description
     */
    public function archive_description() {
        Template::get_template_part( 'templates/archive/description' );
    }

    /**
     * Archive navigation
     */
    public function archive_navigation() {
        Template::get_template_part( 'templates/archive/navigation' );
    }

    /**
     * Before search
     */
    public function before_search() {
        Template::get_template_part( 'templates/search/before-search' );
    }

    /**
     * Search
     */
    public function search() {
        Template::get_template_part( 'templates/search/search' );
    }

    /**
     * After search
     */
    public function after_search() {
        Template::get_template_part( 'templates/search/after-search' );
    }

    /**
     * Search title
     */
    public function search_title() {
        Template::get_template_part( 'templates/search/title' );
    }

    /**
     * Search form
     */
    public function search_form() {
        Template::get_template_part( 'templates/search/form' );
    }

    /**
     * No search results
     */
    public function no_search_results() {
        Template::get_template_part( 'templates/search/no-results' );
    }

    /**
     * Before 404
     */
    public function before_404() {
        Template::get_template_part( 'templates/404/before-404' );
    }

    /**
     * 404
     */
    public function the_404() {
        Template::get_template_part( 'templates/404/404' );
    }

    /**
     * After 404
     */
    public function after_404() {
        Template::get_template_part( 'templates/404/after-404' );
    }

    /**
     * 404 title
     */
    public function the_404_title() {
        Template::get_template_part( 'templates/404/title' );
    }

    /**
     * 404 content
     */
    public function the_404_content() {
        Template::get_template_part( 'templates/404/content' );
    }

    /**
     * 404 search
     */
    public function the_404_search() {
        Template::get_template_part( 'templates/404/search' );
    }

    /**
     * Before shop
     */
    public function before_shop() {
        Template::get_template_part( 'templates/woocommerce/before-shop' );
    }

    /**
     * Shop
     */
    public function shop() {
        Template::get_template_part( 'templates/woocommerce/shop' );
    }

    /**
     * After shop
     */
    public function after_shop() {
        Template::get_template_part( 'templates/woocommerce/after-shop' );
    }

    /**
     * Before product
     */
    public function before_product() {
        Template::get_template_part( 'templates/woocommerce/before-product' );
    }

    /**
     * Product
     */
    public function product() {
        Template::get_template_part( 'templates/woocommerce/product' );
    }

    /**
     * After product
     */
    public function after_product() {
        Template::get_template_part( 'templates/woocommerce/after-product' );
    }

    /**
     * Before cart
     */
    public function before_cart() {
        Template::get_template_part( 'templates/woocommerce/before-cart' );
    }

    /**
     * Cart
     */
    public function cart() {
        Template::get_template_part( 'templates/woocommerce/cart' );
    }

    /**
     * After cart
     */
    public function after_cart() {
        Template::get_template_part( 'templates/woocommerce/after-cart' );
    }

    /**
     * Before checkout
     */
    public function before_checkout() {
        Template::get_template_part( 'templates/woocommerce/before-checkout' );
    }

    /**
     * Checkout
     */
    public function checkout() {
        Template::get_template_part( 'templates/woocommerce/checkout' );
    }

    /**
     * After checkout
     */
    public function after_checkout() {
        Template::get_template_part( 'templates/woocommerce/after-checkout' );
    }

    /**
     * Before account
     */
    public function before_account() {
        Template::get_template_part( 'templates/woocommerce/before-account' );
    }

    /**
     * Account
     */
    public function account() {
        Template::get_template_part( 'templates/woocommerce/account' );
    }

    /**
     * After account
     */
    public function after_account() {
        Template::get_template_part( 'templates/woocommerce/after-account' );
    }
}