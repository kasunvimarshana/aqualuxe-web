<?php
/**
 * AquaLuxe Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Display HTML attributes
 *
 * @param string $context Context for the attributes
 */
function aqualuxe_html_attributes( $context = 'default' ) {
    $attributes = apply_filters( 'aqualuxe_html_attributes', [] );
    
    foreach ( $attributes as $name => $value ) {
        if ( $value === '' ) {
            echo esc_html( $name ) . ' ';
        } else {
            echo sprintf( '%s="%s" ', esc_html( $name ), esc_attr( $value ) );
        }
    }
}

/**
 * Display site header
 */
function aqualuxe_site_header() {
    get_template_part( 'templates/header' );
}

/**
 * Display site footer
 */
function aqualuxe_site_footer() {
    get_template_part( 'templates/footer' );
}

/**
 * Display site content
 */
function aqualuxe_site_content() {
    echo '<div id="content" class="site-content">';
    echo '<div class="container">';
    echo '<div class="site-content-inner">';
    
    // Main content
    echo '<main id="primary" class="site-main" ' . aqualuxe_get_attr( 'main' ) . '>';
    
    // Content
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            
            // Include template based on post type
            if ( is_singular() ) {
                get_template_part( 'templates/content/content', get_post_type() );
            } else {
                get_template_part( 'templates/content/content', 'archive' );
            }
        }
        
        // Pagination
        if ( ! is_singular() ) {
            aqualuxe_pagination();
        }
    } else {
        get_template_part( 'templates/content/content', 'none' );
    }
    
    echo '</main>';
    
    // Sidebar
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
        echo '<aside id="secondary" class="widget-area" ' . aqualuxe_get_attr( 'sidebar' ) . '>';
        dynamic_sidebar( 'sidebar-1' );
        echo '</aside>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display post thumbnail
 */
function aqualuxe_post_thumbnail() {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }
    
    if ( is_singular() ) {
        echo '<div class="post-thumbnail">';
        the_post_thumbnail( 'full', [
            'class' => 'post-thumbnail-image',
            'alt' => get_the_title(),
            'loading' => 'lazy',
        ] );
        echo '</div>';
    } else {
        echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';
        the_post_thumbnail( 'medium_large', [
            'class' => 'post-thumbnail-image',
            'alt' => get_the_title(),
            'loading' => 'lazy',
        ] );
        echo '</a>';
    }
}

/**
 * Display post meta
 */
function aqualuxe_post_meta() {
    // Hide category and tag text for pages
    if ( 'post' !== get_post_type() ) {
        return;
    }
    
    // Posted on
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    
    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( 'c' ) ),
        esc_html( get_the_modified_date() )
    );
    
    echo '<div class="entry-meta">';
    
    // Author
    echo '<span class="posted-by">';
    echo '<span class="author vcard">';
    echo '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
    echo '</span>';
    echo '</span>';
    
    // Date
    echo '<span class="posted-on">';
    echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
    echo '</span>';
    
    // Categories
    $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
    if ( $categories_list ) {
        echo '<span class="cat-links">';
        echo $categories_list;
        echo '</span>';
    }
    
    // Comments
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                wp_kses_post( get_the_title() )
            )
        );
        echo '</span>';
    }
    
    // Edit link
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            wp_kses_post( get_the_title() )
        ),
        '<span class="edit-link">',
        '</span>'
    );
    
    echo '</div>';
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    // Hide tag text for pages
    if ( 'post' !== get_post_type() ) {
        return;
    }
    
    // Tags
    $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
    if ( $tags_list ) {
        echo '<div class="entry-tags">';
        echo '<span class="tags-links">';
        echo $tags_list;
        echo '</span>';
        echo '</div>';
    }
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        [
            'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
        ]
    );
}

/**
 * Display comments
 */
function aqualuxe_comments() {
    // If comments are open or we have at least one comment, load up the comment template
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    // Get related posts
    $related_posts = aqualuxe_get_related_posts();
    
    if ( ! $related_posts->have_posts() ) {
        return;
    }
    
    echo '<div class="related-posts">';
    echo '<h2 class="related-posts-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h2>';
    echo '<div class="related-posts-list">';
    
    while ( $related_posts->have_posts() ) {
        $related_posts->the_post();
        
        echo '<article class="related-post">';
        
        // Thumbnail
        if ( has_post_thumbnail() ) {
            echo '<a class="related-post-thumbnail" href="' . esc_url( get_permalink() ) . '">';
            the_post_thumbnail( 'medium', [
                'class' => 'related-post-thumbnail-image',
                'alt' => get_the_title(),
                'loading' => 'lazy',
            ] );
            echo '</a>';
        }
        
        // Title
        echo '<h3 class="related-post-title">';
        echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
        echo '</h3>';
        
        // Date
        echo '<div class="related-post-meta">';
        echo '<time class="related-post-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
        echo '</div>';
        
        echo '</article>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Restore original post data
    wp_reset_postdata();
}

/**
 * Display social sharing
 */
function aqualuxe_social_sharing() {
    echo aqualuxe_get_social_sharing();
}

/**
 * Display author bio
 */
function aqualuxe_author_bio() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    $author_id = get_the_author_meta( 'ID' );
    
    if ( ! $author_id ) {
        return;
    }
    
    $author_bio = get_the_author_meta( 'description', $author_id );
    
    if ( ! $author_bio ) {
        return;
    }
    
    echo '<div class="author-bio">';
    echo '<div class="author-bio-avatar">';
    echo get_avatar( $author_id, 96 );
    echo '</div>';
    echo '<div class="author-bio-content">';
    echo '<h2 class="author-bio-title">';
    echo sprintf( esc_html__( 'About %s', 'aqualuxe' ), get_the_author() );
    echo '</h2>';
    echo '<div class="author-bio-description">';
    echo wpautop( $author_bio );
    echo '</div>';
    echo '<a class="author-bio-link" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">';
    echo sprintf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() );
    echo '</a>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    echo aqualuxe_get_breadcrumbs();
}

/**
 * Display page header
 */
function aqualuxe_page_header() {
    echo aqualuxe_get_page_header();
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    echo aqualuxe_get_language_switcher();
}

/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    echo aqualuxe_get_currency_switcher();
}

/**
 * Display dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    echo aqualuxe_get_dark_mode_toggle();
}

/**
 * Display contact info
 */
function aqualuxe_contact_info() {
    echo aqualuxe_get_contact_info_html();
}

/**
 * Display social links
 */
function aqualuxe_social_links() {
    echo aqualuxe_get_social_links_html();
}

/**
 * Display logo
 *
 * @param string $type Logo type (default or light)
 */
function aqualuxe_logo( $type = 'default' ) {
    echo aqualuxe_get_logo( $type );
}

/**
 * Display mobile menu toggle
 */
function aqualuxe_mobile_menu_toggle() {
    echo aqualuxe_get_mobile_menu_toggle();
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    echo aqualuxe_get_search_form();
}

/**
 * Display search toggle
 */
function aqualuxe_search_toggle() {
    echo aqualuxe_get_search_toggle();
}

/**
 * Display search modal
 */
function aqualuxe_search_modal() {
    echo aqualuxe_get_search_modal();
}

/**
 * Display mobile menu
 */
function aqualuxe_mobile_menu() {
    echo aqualuxe_get_mobile_menu();
}

/**
 * Display header cart
 */
function aqualuxe_header_cart() {
    echo aqualuxe_get_header_cart();
}

/**
 * Display header account
 */
function aqualuxe_header_account() {
    echo aqualuxe_get_header_account();
}

/**
 * Display header wishlist
 */
function aqualuxe_header_wishlist() {
    echo aqualuxe_get_header_wishlist();
}

/**
 * Display header actions
 */
function aqualuxe_header_actions() {
    echo aqualuxe_get_header_actions();
}

/**
 * Display header top bar
 */
function aqualuxe_header_top_bar() {
    echo aqualuxe_get_header_top_bar();
}

/**
 * Display header main
 */
function aqualuxe_header_main() {
    echo aqualuxe_get_header_main();
}

/**
 * Display footer widgets
 */
function aqualuxe_footer_widgets() {
    echo aqualuxe_get_footer_widgets();
}

/**
 * Display footer bottom
 */
function aqualuxe_footer_bottom() {
    echo aqualuxe_get_footer_bottom();
}