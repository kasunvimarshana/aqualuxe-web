<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class if sidebar is active.
    $sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
    if ( 'none' !== $sidebar_position && is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'has-sidebar';
        $classes[] = 'sidebar-' . $sidebar_position;
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add class for dark mode
    if ( get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
        $classes[] = 'dark-mode-enabled';
    }

    // Add class for header layout
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    $classes[] = 'header-' . $header_layout;

    // Add class for sticky header
    if ( get_theme_mod( 'aqualuxe_sticky_header', true ) ) {
        $classes[] = 'sticky-header-enabled';
    }

    // Add class for footer layout
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
    $classes[] = 'footer-' . $footer_layout;

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Get template part with passed args
 *
 * @param string $slug Template slug.
 * @param string $name Template name.
 * @param array  $args Template arguments.
 * @return void
 */
function aqualuxe_get_template_part( $slug, $name = null, $args = [] ) {
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    $template = '';

    // Look in yourtheme/slug-name.php and yourtheme/templates/slug-name.php
    if ( $name ) {
        $template = locate_template( [ "{$slug}-{$name}.php", "templates/{$slug}-{$name}.php" ] );
    }

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( get_template_directory() . "/templates/{$slug}-{$name}.php" ) ) {
        $template = get_template_directory() . "/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look for the slug.php
    if ( ! $template ) {
        $template = locate_template( [ "{$slug}.php", "templates/{$slug}.php" ] );
    }

    // Allow 3rd party plugins to filter template file from their plugin.
    $template = apply_filters( 'aqualuxe_get_template_part', $template, $slug, $name, $args );

    if ( $template ) {
        include $template;
    }
}

/**
 * Get the page title
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    if ( is_home() ) {
        if ( get_option( 'page_for_posts', true ) ) {
            return get_the_title( get_option( 'page_for_posts', true ) );
        } else {
            return __( 'Latest Posts', 'aqualuxe' );
        }
    } elseif ( is_archive() ) {
        return get_the_archive_title();
    } elseif ( is_search() ) {
        return sprintf( __( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
    } elseif ( is_404() ) {
        return __( 'Page Not Found', 'aqualuxe' );
    } else {
        return get_the_title();
    }
}

/**
 * Get the post thumbnail with fallback
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param array  $attr    Image attributes.
 * @return string
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = [] ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, $attr );
    } else {
        // Fallback image
        $placeholder_image = get_theme_mod( 'aqualuxe_placeholder_image', '' );
        
        if ( $placeholder_image ) {
            return sprintf(
                '<img src="%1$s" alt="%2$s" class="wp-post-image" />',
                esc_url( $placeholder_image ),
                esc_attr( get_the_title( $post_id ) )
            );
        }
        
        return '';
    }
}

/**
 * Get the post excerpt with custom length
 *
 * @param int $length Excerpt length.
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_excerpt( $length = 55, $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $post = get_post( $post_id );
    
    if ( empty( $post ) ) {
        return '';
    }

    if ( has_excerpt( $post_id ) ) {
        $excerpt = $post->post_excerpt;
    } else {
        $excerpt = $post->post_content;
    }

    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = excerpt_remove_blocks( $excerpt );
    $excerpt = wp_strip_all_tags( $excerpt );
    $excerpt = wp_trim_words( $excerpt, $length, '...' );

    return $excerpt;
}

/**
 * Get the post meta
 *
 * @param string $meta_type Meta type (date, author, comments, categories, tags).
 * @param int    $post_id   Post ID.
 * @return string
 */
function aqualuxe_get_post_meta( $meta_type, $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $output = '';

    switch ( $meta_type ) {
        case 'date':
            $output = sprintf(
                '<span class="posted-on"><time datetime="%1$s">%2$s</time></span>',
                esc_attr( get_the_date( 'c', $post_id ) ),
                esc_html( get_the_date( '', $post_id ) )
            );
            break;

        case 'author':
            $output = sprintf(
                '<span class="byline">%1$s <a href="%2$s">%3$s</a></span>',
                esc_html__( 'by', 'aqualuxe' ),
                esc_url( get_author_posts_url( get_post_field( 'post_author', $post_id ) ) ),
                esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ) )
            );
            break;

        case 'comments':
            $comments_number = get_comments_number( $post_id );
            if ( '1' === $comments_number ) {
                $output = sprintf(
                    '<span class="comments-link"><a href="%1$s">%2$s</a></span>',
                    esc_url( get_comments_link( $post_id ) ),
                    esc_html__( '1 Comment', 'aqualuxe' )
                );
            } else {
                $output = sprintf(
                    '<span class="comments-link"><a href="%1$s">%2$s</a></span>',
                    esc_url( get_comments_link( $post_id ) ),
                    sprintf( esc_html( _n( '%s Comment', '%s Comments', $comments_number, 'aqualuxe' ) ), number_format_i18n( $comments_number ) )
                );
            }
            break;

        case 'categories':
            $categories = get_the_category( $post_id );
            if ( ! empty( $categories ) ) {
                $output = '<span class="cat-links">';
                foreach ( $categories as $category ) {
                    $output .= sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( get_category_link( $category->term_id ) ),
                        esc_html( $category->name )
                    );
                }
                $output .= '</span>';
            }
            break;

        case 'tags':
            $tags = get_the_tags( $post_id );
            if ( ! empty( $tags ) ) {
                $output = '<span class="tags-links">';
                foreach ( $tags as $tag ) {
                    $output .= sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( get_tag_link( $tag->term_id ) ),
                        esc_html( $tag->name )
                    );
                }
                $output .= '</span>';
            }
            break;
    }

    return $output;
}

/**
 * Get the pagination
 *
 * @param array $args Pagination arguments.
 * @return string
 */
function aqualuxe_get_pagination( $args = [] ) {
    $defaults = [
        'total'     => 1,
        'current'   => 1,
        'format'    => '?paged=%#%',
        'base'      => get_pagenum_link( 1 ) . '%_%',
        'prev_text' => __( '&laquo; Previous', 'aqualuxe' ),
        'next_text' => __( 'Next &raquo;', 'aqualuxe' ),
        'type'      => 'array',
        'end_size'  => 3,
        'mid_size'  => 3,
    ];

    $args = wp_parse_args( $args, $defaults );

    $pagination = paginate_links( $args );

    if ( ! is_array( $pagination ) ) {
        return '';
    }

    $output = '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
    $output .= '<ul class="pagination-list">';

    foreach ( $pagination as $key => $page_link ) {
        $output .= '<li class="pagination-item">' . $page_link . '</li>';
    }

    $output .= '</ul>';
    $output .= '</nav>';

    return $output;
}

/**
 * Get the breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    if ( is_front_page() ) {
        return '';
    }

    $output = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
    $output .= '<ol class="breadcrumbs-list">';
    
    // Home
    $output .= '<li class="breadcrumbs-item">';
    $output .= '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';
    $output .= '</li>';

    if ( is_category() || is_single() ) {
        if ( is_single() ) {
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $output .= '<li class="breadcrumbs-item">';
                $output .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                $output .= '</li>';
            }
            $output .= '<li class="breadcrumbs-item">' . get_the_title() . '</li>';
        } else {
            $output .= '<li class="breadcrumbs-item">' . single_cat_title( '', false ) . '</li>';
        }
    } elseif ( is_page() ) {
        if ( $post->post_parent ) {
            $ancestors = get_post_ancestors( $post->ID );
            $ancestors = array_reverse( $ancestors );
            foreach ( $ancestors as $ancestor ) {
                $output .= '<li class="breadcrumbs-item">';
                $output .= '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . get_the_title( $ancestor ) . '</a>';
                $output .= '</li>';
            }
        }
        $output .= '<li class="breadcrumbs-item">' . get_the_title() . '</li>';
    } elseif ( is_tag() ) {
        $output .= '<li class="breadcrumbs-item">' . single_tag_title( '', false ) . '</li>';
    } elseif ( is_author() ) {
        $output .= '<li class="breadcrumbs-item">' . get_the_author() . '</li>';
    } elseif ( is_year() ) {
        $output .= '<li class="breadcrumbs-item">' . get_the_date( 'Y' ) . '</li>';
    } elseif ( is_month() ) {
        $output .= '<li class="breadcrumbs-item">' . get_the_date( 'F Y' ) . '</li>';
    } elseif ( is_day() ) {
        $output .= '<li class="breadcrumbs-item">' . get_the_date() . '</li>';
    } elseif ( is_search() ) {
        $output .= '<li class="breadcrumbs-item">' . sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ) . '</li>';
    } elseif ( is_404() ) {
        $output .= '<li class="breadcrumbs-item">' . __( 'Page Not Found', 'aqualuxe' ) . '</li>';
    }

    $output .= '</ol>';
    $output .= '</nav>';

    return $output;
}

/**
 * Get social media links
 *
 * @return string
 */
function aqualuxe_get_social_links() {
    $social_links = [
        'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
        'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
        'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
        'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
        'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
        'pinterest' => get_theme_mod( 'aqualuxe_pinterest_url', '' ),
    ];

    $output = '';

    foreach ( $social_links as $network => $url ) {
        if ( ! empty( $url ) ) {
            $output .= sprintf(
                '<a href="%1$s" class="social-link social-link--%2$s" target="_blank" rel="noopener noreferrer">
                    <span class="screen-reader-text">%3$s</span>
                    <i class="fab fa-%2$s" aria-hidden="true"></i>
                </a>',
                esc_url( $url ),
                esc_attr( $network ),
                esc_html( ucfirst( $network ) )
            );
        }
    }

    return $output;
}

/**
 * Get related posts
 *
 * @param int   $post_id Post ID.
 * @param int   $number  Number of posts to get.
 * @param array $args    Query arguments.
 * @return WP_Query
 */
function aqualuxe_get_related_posts( $post_id = null, $number = 3, $args = [] ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category( $post_id );
    $category_ids = [];

    if ( $categories ) {
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
    }

    $defaults = [
        'post_type'      => 'post',
        'posts_per_page' => $number,
        'post_status'    => 'publish',
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ];

    if ( ! empty( $category_ids ) ) {
        $defaults['category__in'] = $category_ids;
    }

    $args = wp_parse_args( $args, $defaults );

    return new WP_Query( $args );
}