<?php
/**
 * Hooks Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Hooks Class
 * 
 * This class is responsible for managing custom hooks and filters.
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
        $this->setup_hooks();
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        // Header hooks
        add_action( 'aqualuxe_before_header', [ $this, 'before_header' ], 10 );
        add_action( 'aqualuxe_header', [ $this, 'header' ], 10 );
        add_action( 'aqualuxe_after_header', [ $this, 'after_header' ], 10 );

        // Content hooks
        add_action( 'aqualuxe_before_content', [ $this, 'before_content' ], 10 );
        add_action( 'aqualuxe_content', [ $this, 'content' ], 10 );
        add_action( 'aqualuxe_after_content', [ $this, 'after_content' ], 10 );

        // Sidebar hooks
        add_action( 'aqualuxe_sidebar', [ $this, 'sidebar' ], 10 );

        // Footer hooks
        add_action( 'aqualuxe_before_footer', [ $this, 'before_footer' ], 10 );
        add_action( 'aqualuxe_footer', [ $this, 'footer' ], 10 );
        add_action( 'aqualuxe_after_footer', [ $this, 'after_footer' ], 10 );

        // Post hooks
        add_action( 'aqualuxe_before_post', [ $this, 'before_post' ], 10 );
        add_action( 'aqualuxe_post_header', [ $this, 'post_header' ], 10 );
        add_action( 'aqualuxe_post_content', [ $this, 'post_content' ], 10 );
        add_action( 'aqualuxe_post_footer', [ $this, 'post_footer' ], 10 );
        add_action( 'aqualuxe_after_post', [ $this, 'after_post' ], 10 );

        // Page hooks
        add_action( 'aqualuxe_before_page', [ $this, 'before_page' ], 10 );
        add_action( 'aqualuxe_page_header', [ $this, 'page_header' ], 10 );
        add_action( 'aqualuxe_page_content', [ $this, 'page_content' ], 10 );
        add_action( 'aqualuxe_page_footer', [ $this, 'page_footer' ], 10 );
        add_action( 'aqualuxe_after_page', [ $this, 'after_page' ], 10 );

        // Comments hooks
        add_action( 'aqualuxe_before_comments', [ $this, 'before_comments' ], 10 );
        add_action( 'aqualuxe_comments', [ $this, 'comments' ], 10 );
        add_action( 'aqualuxe_after_comments', [ $this, 'after_comments' ], 10 );

        // Archive hooks
        add_action( 'aqualuxe_before_archive', [ $this, 'before_archive' ], 10 );
        add_action( 'aqualuxe_archive_header', [ $this, 'archive_header' ], 10 );
        add_action( 'aqualuxe_archive_content', [ $this, 'archive_content' ], 10 );
        add_action( 'aqualuxe_archive_footer', [ $this, 'archive_footer' ], 10 );
        add_action( 'aqualuxe_after_archive', [ $this, 'after_archive' ], 10 );

        // Search hooks
        add_action( 'aqualuxe_before_search', [ $this, 'before_search' ], 10 );
        add_action( 'aqualuxe_search_header', [ $this, 'search_header' ], 10 );
        add_action( 'aqualuxe_search_content', [ $this, 'search_content' ], 10 );
        add_action( 'aqualuxe_search_footer', [ $this, 'search_footer' ], 10 );
        add_action( 'aqualuxe_after_search', [ $this, 'after_search' ], 10 );

        // 404 hooks
        add_action( 'aqualuxe_before_404', [ $this, 'before_404' ], 10 );
        add_action( 'aqualuxe_404_header', [ $this, 'header_404' ], 10 );
        add_action( 'aqualuxe_404_content', [ $this, 'content_404' ], 10 );
        add_action( 'aqualuxe_404_footer', [ $this, 'footer_404' ], 10 );
        add_action( 'aqualuxe_after_404', [ $this, 'after_404' ], 10 );

        // Schema.org hooks
        add_action( 'aqualuxe_schema_org_markup', [ $this, 'schema_org_markup' ], 10 );

        // Open Graph hooks
        add_action( 'aqualuxe_open_graph_tags', [ $this, 'open_graph_tags' ], 10 );

        // Custom hooks for modules
        add_action( 'aqualuxe_before_modules', [ $this, 'before_modules' ], 10 );
        add_action( 'aqualuxe_after_modules', [ $this, 'after_modules' ], 10 );
    }

    /**
     * Before header hook
     *
     * @return void
     */
    public function before_header() {
        do_action( 'aqualuxe_before_header_content' );
        Template::get_instance()->get_template_part( 'templates/parts/header/before-header' );
    }

    /**
     * Header hook
     *
     * @return void
     */
    public function header() {
        do_action( 'aqualuxe_before_header_inner' );
        Template::get_instance()->get_template_part( 'templates/parts/header/header' );
        do_action( 'aqualuxe_after_header_inner' );
    }

    /**
     * After header hook
     *
     * @return void
     */
    public function after_header() {
        Template::get_instance()->get_template_part( 'templates/parts/header/after-header' );
        do_action( 'aqualuxe_after_header_content' );
    }

    /**
     * Before content hook
     *
     * @return void
     */
    public function before_content() {
        do_action( 'aqualuxe_before_content_inner' );
        Template::get_instance()->get_template_part( 'templates/parts/content/before-content' );
    }

    /**
     * Content hook
     *
     * @return void
     */
    public function content() {
        do_action( 'aqualuxe_before_content_inner' );
        
        if ( is_singular() ) {
            if ( is_page() ) {
                do_action( 'aqualuxe_before_page' );
                do_action( 'aqualuxe_page_header' );
                do_action( 'aqualuxe_page_content' );
                do_action( 'aqualuxe_page_footer' );
                do_action( 'aqualuxe_after_page' );
            } else {
                do_action( 'aqualuxe_before_post' );
                do_action( 'aqualuxe_post_header' );
                do_action( 'aqualuxe_post_content' );
                do_action( 'aqualuxe_post_footer' );
                do_action( 'aqualuxe_after_post' );
            }
            
            if ( comments_open() || get_comments_number() ) {
                do_action( 'aqualuxe_before_comments' );
                do_action( 'aqualuxe_comments' );
                do_action( 'aqualuxe_after_comments' );
            }
        } elseif ( is_archive() ) {
            do_action( 'aqualuxe_before_archive' );
            do_action( 'aqualuxe_archive_header' );
            do_action( 'aqualuxe_archive_content' );
            do_action( 'aqualuxe_archive_footer' );
            do_action( 'aqualuxe_after_archive' );
        } elseif ( is_search() ) {
            do_action( 'aqualuxe_before_search' );
            do_action( 'aqualuxe_search_header' );
            do_action( 'aqualuxe_search_content' );
            do_action( 'aqualuxe_search_footer' );
            do_action( 'aqualuxe_after_search' );
        } elseif ( is_404() ) {
            do_action( 'aqualuxe_before_404' );
            do_action( 'aqualuxe_404_header' );
            do_action( 'aqualuxe_404_content' );
            do_action( 'aqualuxe_404_footer' );
            do_action( 'aqualuxe_after_404' );
        }
        
        do_action( 'aqualuxe_after_content_inner' );
    }

    /**
     * After content hook
     *
     * @return void
     */
    public function after_content() {
        Template::get_instance()->get_template_part( 'templates/parts/content/after-content' );
        do_action( 'aqualuxe_after_content_inner' );
    }

    /**
     * Sidebar hook
     *
     * @return void
     */
    public function sidebar() {
        do_action( 'aqualuxe_before_sidebar' );
        Template::get_instance()->get_template_part( 'templates/parts/sidebar/sidebar' );
        do_action( 'aqualuxe_after_sidebar' );
    }

    /**
     * Before footer hook
     *
     * @return void
     */
    public function before_footer() {
        do_action( 'aqualuxe_before_footer_content' );
        Template::get_instance()->get_template_part( 'templates/parts/footer/before-footer' );
    }

    /**
     * Footer hook
     *
     * @return void
     */
    public function footer() {
        do_action( 'aqualuxe_before_footer_inner' );
        Template::get_instance()->get_template_part( 'templates/parts/footer/footer' );
        do_action( 'aqualuxe_after_footer_inner' );
    }

    /**
     * After footer hook
     *
     * @return void
     */
    public function after_footer() {
        Template::get_instance()->get_template_part( 'templates/parts/footer/after-footer' );
        do_action( 'aqualuxe_after_footer_content' );
    }

    /**
     * Before post hook
     *
     * @return void
     */
    public function before_post() {
        Template::get_instance()->get_template_part( 'templates/parts/post/before-post' );
    }

    /**
     * Post header hook
     *
     * @return void
     */
    public function post_header() {
        Template::get_instance()->get_template_part( 'templates/parts/post/header' );
    }

    /**
     * Post content hook
     *
     * @return void
     */
    public function post_content() {
        Template::get_instance()->get_template_part( 'templates/parts/post/content' );
    }

    /**
     * Post footer hook
     *
     * @return void
     */
    public function post_footer() {
        Template::get_instance()->get_template_part( 'templates/parts/post/footer' );
    }

    /**
     * After post hook
     *
     * @return void
     */
    public function after_post() {
        Template::get_instance()->get_template_part( 'templates/parts/post/after-post' );
    }

    /**
     * Before page hook
     *
     * @return void
     */
    public function before_page() {
        Template::get_instance()->get_template_part( 'templates/parts/page/before-page' );
    }

    /**
     * Page header hook
     *
     * @return void
     */
    public function page_header() {
        Template::get_instance()->get_template_part( 'templates/parts/page/header' );
    }

    /**
     * Page content hook
     *
     * @return void
     */
    public function page_content() {
        Template::get_instance()->get_template_part( 'templates/parts/page/content' );
    }

    /**
     * Page footer hook
     *
     * @return void
     */
    public function page_footer() {
        Template::get_instance()->get_template_part( 'templates/parts/page/footer' );
    }

    /**
     * After page hook
     *
     * @return void
     */
    public function after_page() {
        Template::get_instance()->get_template_part( 'templates/parts/page/after-page' );
    }

    /**
     * Before comments hook
     *
     * @return void
     */
    public function before_comments() {
        Template::get_instance()->get_template_part( 'templates/parts/comments/before-comments' );
    }

    /**
     * Comments hook
     *
     * @return void
     */
    public function comments() {
        Template::get_instance()->get_template_part( 'templates/parts/comments/comments' );
    }

    /**
     * After comments hook
     *
     * @return void
     */
    public function after_comments() {
        Template::get_instance()->get_template_part( 'templates/parts/comments/after-comments' );
    }

    /**
     * Before archive hook
     *
     * @return void
     */
    public function before_archive() {
        Template::get_instance()->get_template_part( 'templates/parts/archive/before-archive' );
    }

    /**
     * Archive header hook
     *
     * @return void
     */
    public function archive_header() {
        Template::get_instance()->get_template_part( 'templates/parts/archive/header' );
    }

    /**
     * Archive content hook
     *
     * @return void
     */
    public function archive_content() {
        Template::get_instance()->get_template_part( 'templates/parts/archive/content' );
    }

    /**
     * Archive footer hook
     *
     * @return void
     */
    public function archive_footer() {
        Template::get_instance()->get_template_part( 'templates/parts/archive/footer' );
    }

    /**
     * After archive hook
     *
     * @return void
     */
    public function after_archive() {
        Template::get_instance()->get_template_part( 'templates/parts/archive/after-archive' );
    }

    /**
     * Before search hook
     *
     * @return void
     */
    public function before_search() {
        Template::get_instance()->get_template_part( 'templates/parts/search/before-search' );
    }

    /**
     * Search header hook
     *
     * @return void
     */
    public function search_header() {
        Template::get_instance()->get_template_part( 'templates/parts/search/header' );
    }

    /**
     * Search content hook
     *
     * @return void
     */
    public function search_content() {
        Template::get_instance()->get_template_part( 'templates/parts/search/content' );
    }

    /**
     * Search footer hook
     *
     * @return void
     */
    public function search_footer() {
        Template::get_instance()->get_template_part( 'templates/parts/search/footer' );
    }

    /**
     * After search hook
     *
     * @return void
     */
    public function after_search() {
        Template::get_instance()->get_template_part( 'templates/parts/search/after-search' );
    }

    /**
     * Before 404 hook
     *
     * @return void
     */
    public function before_404() {
        Template::get_instance()->get_template_part( 'templates/parts/404/before-404' );
    }

    /**
     * 404 header hook
     *
     * @return void
     */
    public function header_404() {
        Template::get_instance()->get_template_part( 'templates/parts/404/header' );
    }

    /**
     * 404 content hook
     *
     * @return void
     */
    public function content_404() {
        Template::get_instance()->get_template_part( 'templates/parts/404/content' );
    }

    /**
     * 404 footer hook
     *
     * @return void
     */
    public function footer_404() {
        Template::get_instance()->get_template_part( 'templates/parts/404/footer' );
    }

    /**
     * After 404 hook
     *
     * @return void
     */
    public function after_404() {
        Template::get_instance()->get_template_part( 'templates/parts/404/after-404' );
    }

    /**
     * Schema.org markup hook
     *
     * @return void
     */
    public function schema_org_markup() {
        Template::get_instance()->get_template_part( 'templates/parts/schema-org' );
    }

    /**
     * Open Graph tags hook
     *
     * @return void
     */
    public function open_graph_tags() {
        Template::get_instance()->get_template_part( 'templates/parts/open-graph' );
    }

    /**
     * Before modules hook
     *
     * @return void
     */
    public function before_modules() {
        do_action( 'aqualuxe_before_modules_content' );
    }

    /**
     * After modules hook
     *
     * @return void
     */
    public function after_modules() {
        do_action( 'aqualuxe_after_modules_content' );
    }
}