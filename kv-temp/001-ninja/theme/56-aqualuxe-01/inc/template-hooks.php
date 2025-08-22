<?php
/**
 * Template hooks
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Header
 *
 * @see aqualuxe_header_top()
 * @see aqualuxe_header_main()
 * @see aqualuxe_header_bottom()
 */
function aqualuxe_header_top() {
    aqualuxe_get_template_part( 'template-parts/header/top' );
}

function aqualuxe_header_main() {
    aqualuxe_get_template_part( 'template-parts/header/main' );
}

function aqualuxe_header_bottom() {
    aqualuxe_get_template_part( 'template-parts/header/bottom' );
}

add_action( 'aqualuxe_header', 'aqualuxe_header_top', 10 );
add_action( 'aqualuxe_header', 'aqualuxe_header_main', 20 );
add_action( 'aqualuxe_header', 'aqualuxe_header_bottom', 30 );

/**
 * Footer
 *
 * @see aqualuxe_footer_top()
 * @see aqualuxe_footer_main()
 * @see aqualuxe_footer_bottom()
 */
function aqualuxe_footer_top() {
    aqualuxe_get_template_part( 'template-parts/footer/top' );
}

function aqualuxe_footer_main() {
    aqualuxe_get_template_part( 'template-parts/footer/main' );
}

function aqualuxe_footer_bottom() {
    aqualuxe_get_template_part( 'template-parts/footer/bottom' );
}

add_action( 'aqualuxe_footer', 'aqualuxe_footer_top', 10 );
add_action( 'aqualuxe_footer', 'aqualuxe_footer_main', 20 );
add_action( 'aqualuxe_footer', 'aqualuxe_footer_bottom', 30 );

/**
 * Content
 *
 * @see aqualuxe_page_header()
 * @see aqualuxe_content_top()
 * @see aqualuxe_content_bottom()
 */
function aqualuxe_page_header() {
    if ( is_front_page() ) {
        return;
    }
    
    aqualuxe_get_template_part( 'template-parts/content/page-header' );
}

function aqualuxe_content_top() {
    aqualuxe_get_template_part( 'template-parts/content/content-top' );
}

function aqualuxe_content_bottom() {
    aqualuxe_get_template_part( 'template-parts/content/content-bottom' );
}

add_action( 'aqualuxe_before_content', 'aqualuxe_page_header', 10 );
add_action( 'aqualuxe_before_content', 'aqualuxe_content_top', 20 );
add_action( 'aqualuxe_after_content', 'aqualuxe_content_bottom', 10 );

/**
 * Posts
 *
 * @see aqualuxe_post_meta()
 * @see aqualuxe_post_thumbnail()
 * @see aqualuxe_post_content()
 * @see aqualuxe_post_footer()
 */
function aqualuxe_post_meta() {
    if ( 'post' === get_post_type() ) {
        aqualuxe_get_template_part( 'template-parts/content/post-meta' );
    }
}

function aqualuxe_post_thumbnail() {
    if ( has_post_thumbnail() ) {
        aqualuxe_get_template_part( 'template-parts/content/post-thumbnail' );
    }
}

function aqualuxe_post_content() {
    aqualuxe_get_template_part( 'template-parts/content/post-content' );
}

function aqualuxe_post_footer() {
    if ( 'post' === get_post_type() ) {
        aqualuxe_get_template_part( 'template-parts/content/post-footer' );
    }
}

add_action( 'aqualuxe_post_header', 'aqualuxe_post_meta', 10 );
add_action( 'aqualuxe_post_content_before', 'aqualuxe_post_thumbnail', 10 );
add_action( 'aqualuxe_post_content', 'aqualuxe_post_content', 10 );
add_action( 'aqualuxe_post_content_after', 'aqualuxe_post_footer', 10 );

/**
 * Pages
 *
 * @see aqualuxe_page_thumbnail()
 * @see aqualuxe_page_content()
 */
function aqualuxe_page_thumbnail() {
    if ( has_post_thumbnail() ) {
        aqualuxe_get_template_part( 'template-parts/content/page-thumbnail' );
    }
}

function aqualuxe_page_content() {
    aqualuxe_get_template_part( 'template-parts/content/page-content' );
}

add_action( 'aqualuxe_page_content_before', 'aqualuxe_page_thumbnail', 10 );
add_action( 'aqualuxe_page_content', 'aqualuxe_page_content', 10 );

/**
 * Sidebar
 *
 * @see aqualuxe_get_sidebar()
 */
function aqualuxe_get_sidebar() {
    $sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
    
    if ( 'none' !== $sidebar_position && is_active_sidebar( 'sidebar-1' ) ) {
        get_sidebar();
    }
}

add_action( 'aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10 );

/**
 * Comments
 *
 * @see aqualuxe_comments_before()
 * @see aqualuxe_comments_after()
 */
function aqualuxe_comments_before() {
    aqualuxe_get_template_part( 'template-parts/comments/comments-before' );
}

function aqualuxe_comments_after() {
    aqualuxe_get_template_part( 'template-parts/comments/comments-after' );
}

add_action( 'aqualuxe_comments_before', 'aqualuxe_comments_before', 10 );
add_action( 'aqualuxe_comments_after', 'aqualuxe_comments_after', 10 );

/**
 * Archive
 *
 * @see aqualuxe_archive_header()
 * @see aqualuxe_archive_description()
 */
function aqualuxe_archive_header() {
    if ( is_archive() ) {
        aqualuxe_get_template_part( 'template-parts/archive/archive-header' );
    }
}

function aqualuxe_archive_description() {
    if ( is_archive() && get_the_archive_description() ) {
        aqualuxe_get_template_part( 'template-parts/archive/archive-description' );
    }
}

add_action( 'aqualuxe_archive_header', 'aqualuxe_archive_header', 10 );
add_action( 'aqualuxe_archive_header', 'aqualuxe_archive_description', 20 );

/**
 * Search
 *
 * @see aqualuxe_search_header()
 * @see aqualuxe_search_form()
 */
function aqualuxe_search_header() {
    if ( is_search() ) {
        aqualuxe_get_template_part( 'template-parts/search/search-header' );
    }
}

function aqualuxe_search_form() {
    if ( is_search() ) {
        aqualuxe_get_template_part( 'template-parts/search/search-form' );
    }
}

add_action( 'aqualuxe_search_header', 'aqualuxe_search_header', 10 );
add_action( 'aqualuxe_search_header', 'aqualuxe_search_form', 20 );

/**
 * 404
 *
 * @see aqualuxe_404_header()
 * @see aqualuxe_404_content()
 */
function aqualuxe_404_header() {
    if ( is_404() ) {
        aqualuxe_get_template_part( 'template-parts/404/404-header' );
    }
}

function aqualuxe_404_content() {
    if ( is_404() ) {
        aqualuxe_get_template_part( 'template-parts/404/404-content' );
    }
}

add_action( 'aqualuxe_404_header', 'aqualuxe_404_header', 10 );
add_action( 'aqualuxe_404_content', 'aqualuxe_404_content', 10 );

/**
 * Breadcrumbs
 *
 * @see aqualuxe_breadcrumbs()
 */
function aqualuxe_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    echo aqualuxe_get_breadcrumbs();
}

add_action( 'aqualuxe_before_content', 'aqualuxe_breadcrumbs', 5 );

/**
 * Pagination
 *
 * @see aqualuxe_pagination()
 */
function aqualuxe_pagination() {
    if ( is_singular() ) {
        return;
    }
    
    global $wp_query;
    
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }
    
    $args = [
        'total'   => $wp_query->max_num_pages,
        'current' => max( 1, get_query_var( 'paged' ) ),
    ];
    
    echo aqualuxe_get_pagination( $args );
}

add_action( 'aqualuxe_after_content', 'aqualuxe_pagination', 20 );

/**
 * Social Links
 *
 * @see aqualuxe_social_links()
 */
function aqualuxe_social_links() {
    echo aqualuxe_get_social_links();
}

add_action( 'aqualuxe_social_links', 'aqualuxe_social_links', 10 );