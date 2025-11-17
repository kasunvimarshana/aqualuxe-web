<?php
/**
 * Template Tags
 *
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get social media icon SVG
 *
 * @param string $platform Social media platform
 * @return string SVG icon
 */
function aqualuxe_get_social_icon( $platform ) {
    $icons = array(
        'facebook' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.618 5.367 11.986 11.988 11.986 6.618 0 11.986-5.368 11.986-11.986C24.003 5.367 18.635.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.529L7.6 13.115c.47.662 1.233 1.094 2.098 1.094.616 0 1.17-.222 1.607-.586l2.345 2.345c-.943.636-2.07 1.02-3.201 1.02zm7.138 0c-1.131 0-2.258-.384-3.201-1.02l2.345-2.345c.437.364.991.586 1.607.586.865 0 1.628-.432 2.098-1.094l2.356 2.344c-.757.933-1.908 1.529-3.205 1.529z"/></svg>',
        'youtube' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        'linkedin' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
    );

    return $icons[ $platform ] ?? '';
}

/**
 * Footer menu fallback
 */
function aqualuxe_footer_menu_fallback() {
    echo '<ul class="footer-menu space-y-3">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">' . esc_html__( 'About', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/services' ) ) . '" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">' . esc_html__( 'Services', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">' . esc_html__( 'Contact', 'aqualuxe' ) . '</a></li>';
    echo '</ul>';
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post( get_the_title() )
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Display navigation to next/previous post when applicable.
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        array(
            'prev_text'          => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            'next_text'          => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            'in_same_term'       => true,
            'screen_reader_text' => __( 'Continue Reading', 'aqualuxe' ),
        )
    );
}

/**
 * Display estimated reading time
 */
function aqualuxe_estimated_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $post = get_post( $post_id );
    $word_count = str_word_count( strip_tags( $post->post_content ) );
    $reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute
    
    if ( $reading_time === 1 ) {
        return sprintf( esc_html__( '%d min read', 'aqualuxe' ), $reading_time );
    } else {
        return sprintf( esc_html__( '%d mins read', 'aqualuxe' ), $reading_time );
    }
}

/**
 * Generate breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    $separator = '<svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
    
    echo '<nav class="breadcrumbs py-4" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
    echo '<div class="container mx-auto px-4">';
    echo '<ol class="flex items-center text-sm text-gray-600 dark:text-gray-400">';
    
    // Home link
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-primary-600 transition-colors">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';
    
    if ( is_category() || is_single() ) {
        echo '<li>' . $separator . '</li>';
        
        if ( is_single() ) {
            $categories = get_the_category();
            if ( $categories ) {
                $category = $categories[0];
                echo '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="hover:text-primary-600 transition-colors">' . esc_html( $category->name ) . '</a></li>';
                echo '<li>' . $separator . '</li>';
            }
            echo '<li class="text-gray-900 dark:text-white">' . esc_html( get_the_title() ) . '</li>';
        } else {
            echo '<li class="text-gray-900 dark:text-white">' . esc_html( single_cat_title( '', false ) ) . '</li>';
        }
        
    } elseif ( is_page() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-900 dark:text-white">' . esc_html( get_the_title() ) . '</li>';
        
    } elseif ( is_search() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-900 dark:text-white">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</li>';
        
    } elseif ( is_404() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-900 dark:text-white">' . esc_html__( '404 Error', 'aqualuxe' ) . '</li>';
    }
    
    echo '</ol>';
    echo '</div>';
    echo '</nav>';
}