<?php
/**
 * Template Hooks Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Template Hooks Class
 * 
 * This class sets up all template hooks for the theme.
 */
class Template_Hooks {
    /**
     * Instance of this class
     *
     * @var Template_Hooks
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Template_Hooks
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
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Header hooks
        add_action( 'aqualuxe_header', [ $this, 'header_before' ], 5 );
        add_action( 'aqualuxe_header', [ $this, 'header_top' ], 10 );
        add_action( 'aqualuxe_header', [ $this, 'header_main' ], 20 );
        add_action( 'aqualuxe_header', [ $this, 'header_bottom' ], 30 );
        add_action( 'aqualuxe_header', [ $this, 'header_after' ], 35 );
        
        // Footer hooks
        add_action( 'aqualuxe_footer', [ $this, 'footer_before' ], 5 );
        add_action( 'aqualuxe_footer', [ $this, 'footer_widgets' ], 10 );
        add_action( 'aqualuxe_footer', [ $this, 'footer_main' ], 20 );
        add_action( 'aqualuxe_footer', [ $this, 'footer_bottom' ], 30 );
        add_action( 'aqualuxe_footer', [ $this, 'footer_after' ], 35 );
        
        // Content hooks
        add_action( 'aqualuxe_content_before', [ $this, 'content_before' ], 10 );
        add_action( 'aqualuxe_content_after', [ $this, 'content_after' ], 10 );
        
        // Page hooks
        add_action( 'aqualuxe_page_before', [ $this, 'page_before' ], 10 );
        add_action( 'aqualuxe_page_after', [ $this, 'page_after' ], 10 );
        
        // Post hooks
        add_action( 'aqualuxe_post_before', [ $this, 'post_before' ], 10 );
        add_action( 'aqualuxe_post_after', [ $this, 'post_after' ], 10 );
        add_action( 'aqualuxe_post_content_before', [ $this, 'post_content_before' ], 10 );
        add_action( 'aqualuxe_post_content_after', [ $this, 'post_content_after' ], 10 );
        
        // Sidebar hooks
        add_action( 'aqualuxe_sidebar_before', [ $this, 'sidebar_before' ], 10 );
        add_action( 'aqualuxe_sidebar_after', [ $this, 'sidebar_after' ], 10 );
        
        // Comments hooks
        add_action( 'aqualuxe_comments_before', [ $this, 'comments_before' ], 10 );
        add_action( 'aqualuxe_comments_after', [ $this, 'comments_after' ], 10 );
        
        // Archive hooks
        add_action( 'aqualuxe_archive_before', [ $this, 'archive_before' ], 10 );
        add_action( 'aqualuxe_archive_after', [ $this, 'archive_after' ], 10 );
        
        // Search hooks
        add_action( 'aqualuxe_search_before', [ $this, 'search_before' ], 10 );
        add_action( 'aqualuxe_search_after', [ $this, 'search_after' ], 10 );
        
        // 404 hooks
        add_action( 'aqualuxe_404_before', [ $this, 'error_404_before' ], 10 );
        add_action( 'aqualuxe_404_after', [ $this, 'error_404_after' ], 10 );
        
        // Homepage hooks
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_hero' ], 10 );
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_featured_products' ], 20 );
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_services' ], 30 );
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_testimonials' ], 40 );
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_blog' ], 50 );
        add_action( 'aqualuxe_homepage', [ $this, 'homepage_newsletter' ], 60 );
        
        // About page hooks
        add_action( 'aqualuxe_about_page', [ $this, 'about_hero' ], 10 );
        add_action( 'aqualuxe_about_page', [ $this, 'about_intro' ], 20 );
        add_action( 'aqualuxe_about_page', [ $this, 'about_team' ], 30 );
        add_action( 'aqualuxe_about_page', [ $this, 'about_values' ], 40 );
        add_action( 'aqualuxe_about_page', [ $this, 'about_testimonials' ], 50 );
        
        // Services page hooks
        add_action( 'aqualuxe_services_page', [ $this, 'services_hero' ], 10 );
        add_action( 'aqualuxe_services_page', [ $this, 'services_intro' ], 20 );
        add_action( 'aqualuxe_services_page', [ $this, 'services_list' ], 30 );
        add_action( 'aqualuxe_services_page', [ $this, 'services_cta' ], 40 );
        
        // Contact page hooks
        add_action( 'aqualuxe_contact_page', [ $this, 'contact_hero' ], 10 );
        add_action( 'aqualuxe_contact_page', [ $this, 'contact_info' ], 20 );
        add_action( 'aqualuxe_contact_page', [ $this, 'contact_form' ], 30 );
        add_action( 'aqualuxe_contact_page', [ $this, 'contact_map' ], 40 );
        
        // FAQ page hooks
        add_action( 'aqualuxe_faq_page', [ $this, 'faq_hero' ], 10 );
        add_action( 'aqualuxe_faq_page', [ $this, 'faq_intro' ], 20 );
        add_action( 'aqualuxe_faq_page', [ $this, 'faq_list' ], 30 );
        add_action( 'aqualuxe_faq_page', [ $this, 'faq_cta' ], 40 );
    }

    /**
     * Header before
     *
     * @return void
     */
    public function header_before() {
        get_template_part( 'templates/parts/header/before' );
    }

    /**
     * Header top
     *
     * @return void
     */
    public function header_top() {
        get_template_part( 'templates/parts/header/top' );
    }

    /**
     * Header main
     *
     * @return void
     */
    public function header_main() {
        get_template_part( 'templates/parts/header/main' );
    }

    /**
     * Header bottom
     *
     * @return void
     */
    public function header_bottom() {
        get_template_part( 'templates/parts/header/bottom' );
    }

    /**
     * Header after
     *
     * @return void
     */
    public function header_after() {
        get_template_part( 'templates/parts/header/after' );
    }

    /**
     * Footer before
     *
     * @return void
     */
    public function footer_before() {
        get_template_part( 'templates/parts/footer/before' );
    }

    /**
     * Footer widgets
     *
     * @return void
     */
    public function footer_widgets() {
        get_template_part( 'templates/parts/footer/widgets' );
    }

    /**
     * Footer main
     *
     * @return void
     */
    public function footer_main() {
        get_template_part( 'templates/parts/footer/main' );
    }

    /**
     * Footer bottom
     *
     * @return void
     */
    public function footer_bottom() {
        get_template_part( 'templates/parts/footer/bottom' );
    }

    /**
     * Footer after
     *
     * @return void
     */
    public function footer_after() {
        get_template_part( 'templates/parts/footer/after' );
    }

    /**
     * Content before
     *
     * @return void
     */
    public function content_before() {
        get_template_part( 'templates/parts/content/before' );
    }

    /**
     * Content after
     *
     * @return void
     */
    public function content_after() {
        get_template_part( 'templates/parts/content/after' );
    }

    /**
     * Page before
     *
     * @return void
     */
    public function page_before() {
        get_template_part( 'templates/parts/page/before' );
    }

    /**
     * Page after
     *
     * @return void
     */
    public function page_after() {
        get_template_part( 'templates/parts/page/after' );
    }

    /**
     * Post before
     *
     * @return void
     */
    public function post_before() {
        get_template_part( 'templates/parts/post/before' );
    }

    /**
     * Post after
     *
     * @return void
     */
    public function post_after() {
        get_template_part( 'templates/parts/post/after' );
    }

    /**
     * Post content before
     *
     * @return void
     */
    public function post_content_before() {
        get_template_part( 'templates/parts/post/content-before' );
    }

    /**
     * Post content after
     *
     * @return void
     */
    public function post_content_after() {
        get_template_part( 'templates/parts/post/content-after' );
    }

    /**
     * Sidebar before
     *
     * @return void
     */
    public function sidebar_before() {
        get_template_part( 'templates/parts/sidebar/before' );
    }

    /**
     * Sidebar after
     *
     * @return void
     */
    public function sidebar_after() {
        get_template_part( 'templates/parts/sidebar/after' );
    }

    /**
     * Comments before
     *
     * @return void
     */
    public function comments_before() {
        get_template_part( 'templates/parts/comments/before' );
    }

    /**
     * Comments after
     *
     * @return void
     */
    public function comments_after() {
        get_template_part( 'templates/parts/comments/after' );
    }

    /**
     * Archive before
     *
     * @return void
     */
    public function archive_before() {
        get_template_part( 'templates/parts/archive/before' );
    }

    /**
     * Archive after
     *
     * @return void
     */
    public function archive_after() {
        get_template_part( 'templates/parts/archive/after' );
    }

    /**
     * Search before
     *
     * @return void
     */
    public function search_before() {
        get_template_part( 'templates/parts/search/before' );
    }

    /**
     * Search after
     *
     * @return void
     */
    public function search_after() {
        get_template_part( 'templates/parts/search/after' );
    }

    /**
     * Error 404 before
     *
     * @return void
     */
    public function error_404_before() {
        get_template_part( 'templates/parts/404/before' );
    }

    /**
     * Error 404 after
     *
     * @return void
     */
    public function error_404_after() {
        get_template_part( 'templates/parts/404/after' );
    }

    /**
     * Homepage hero
     *
     * @return void
     */
    public function homepage_hero() {
        get_template_part( 'templates/parts/homepage/hero' );
    }

    /**
     * Homepage featured products
     *
     * @return void
     */
    public function homepage_featured_products() {
        // Only show if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            get_template_part( 'templates/parts/homepage/featured-products' );
        }
    }

    /**
     * Homepage services
     *
     * @return void
     */
    public function homepage_services() {
        get_template_part( 'templates/parts/homepage/services' );
    }

    /**
     * Homepage testimonials
     *
     * @return void
     */
    public function homepage_testimonials() {
        get_template_part( 'templates/parts/homepage/testimonials' );
    }

    /**
     * Homepage blog
     *
     * @return void
     */
    public function homepage_blog() {
        get_template_part( 'templates/parts/homepage/blog' );
    }

    /**
     * Homepage newsletter
     *
     * @return void
     */
    public function homepage_newsletter() {
        get_template_part( 'templates/parts/homepage/newsletter' );
    }

    /**
     * About hero
     *
     * @return void
     */
    public function about_hero() {
        get_template_part( 'templates/parts/about/hero' );
    }

    /**
     * About intro
     *
     * @return void
     */
    public function about_intro() {
        get_template_part( 'templates/parts/about/intro' );
    }

    /**
     * About team
     *
     * @return void
     */
    public function about_team() {
        get_template_part( 'templates/parts/about/team' );
    }

    /**
     * About values
     *
     * @return void
     */
    public function about_values() {
        get_template_part( 'templates/parts/about/values' );
    }

    /**
     * About testimonials
     *
     * @return void
     */
    public function about_testimonials() {
        get_template_part( 'templates/parts/about/testimonials' );
    }

    /**
     * Services hero
     *
     * @return void
     */
    public function services_hero() {
        get_template_part( 'templates/parts/services/hero' );
    }

    /**
     * Services intro
     *
     * @return void
     */
    public function services_intro() {
        get_template_part( 'templates/parts/services/intro' );
    }

    /**
     * Services list
     *
     * @return void
     */
    public function services_list() {
        get_template_part( 'templates/parts/services/list' );
    }

    /**
     * Services CTA
     *
     * @return void
     */
    public function services_cta() {
        get_template_part( 'templates/parts/services/cta' );
    }

    /**
     * Contact hero
     *
     * @return void
     */
    public function contact_hero() {
        get_template_part( 'templates/parts/contact/hero' );
    }

    /**
     * Contact info
     *
     * @return void
     */
    public function contact_info() {
        get_template_part( 'templates/parts/contact/info' );
    }

    /**
     * Contact form
     *
     * @return void
     */
    public function contact_form() {
        get_template_part( 'templates/parts/contact/form' );
    }

    /**
     * Contact map
     *
     * @return void
     */
    public function contact_map() {
        get_template_part( 'templates/parts/contact/map' );
    }

    /**
     * FAQ hero
     *
     * @return void
     */
    public function faq_hero() {
        get_template_part( 'templates/parts/faq/hero' );
    }

    /**
     * FAQ intro
     *
     * @return void
     */
    public function faq_intro() {
        get_template_part( 'templates/parts/faq/intro' );
    }

    /**
     * FAQ list
     *
     * @return void
     */
    public function faq_list() {
        get_template_part( 'templates/parts/faq/list' );
    }

    /**
     * FAQ CTA
     *
     * @return void
     */
    public function faq_cta() {
        get_template_part( 'templates/parts/faq/cta' );
    }
}