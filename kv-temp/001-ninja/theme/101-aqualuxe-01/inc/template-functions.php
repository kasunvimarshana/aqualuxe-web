<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
 * Changes comment form default fields.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
    $comment_field = $defaults['comment_field'];

    // Adjust height of comment form.
    $defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

    return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function aqualuxe_get_the_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_year() ) {
        $title = get_the_date( _x( 'Y', 'yearly archives date format', 'aqualuxe' ) );
    } elseif ( is_month() ) {
        $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'aqualuxe' ) );
    } elseif ( is_day() ) {
        $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'aqualuxe' ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audios', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title', 'aqualuxe' );
        }
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_get_the_archive_title' );

/**
 * Determines if post thumbnail can be displayed.
 */
function aqualuxe_can_show_post_thumbnail() {
    return apply_filters( 'aqualuxe_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns true if a blog has more than 1 category.
 */
function aqualuxe_categorized_blog() {
    $category_count = get_transient( 'aqualuxe_categories' );

    if ( false === $category_count ) {
        // Create an array of all the categories that are attached to posts.
        $categories = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $category_count = count( $categories );

        set_transient( 'aqualuxe_categories', $category_count );
    }

    // Allow viewing case of 0 or 1 categories in post preview.
    if ( is_preview() ) {
        return true;
    }

    return $category_count > 1;
}

/**
 * Flush out the transients used in aqualuxe_categorized_blog.
 */
function aqualuxe_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'aqualuxe_categories' );
}
add_action( 'edit_category', 'aqualuxe_category_transient_flusher' );
add_action( 'save_post', 'aqualuxe_category_transient_flusher' );

/**
 * Add custom classes to the body tag
 */
function aqualuxe_body_classes( $classes ) {
    global $post;

    // Adds a class of hfeed to blogs.
    if ( is_home() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // Add sidebar position class
    $sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
    $classes[] = 'sidebar-' . $sidebar_position;

    // Add post format class for single posts
    if ( is_single() && has_post_format() ) {
        $classes[] = 'has-post-format';
        $classes[] = 'format-' . get_post_format();
    }

    // Add class if featured image
    if ( is_singular() && has_post_thumbnail() ) {
        $classes[] = 'has-featured-image';
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Custom walker for navigation menus
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu depth-$depth\">\n";
    }

    /**
     * Ends the list after the elements are added.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }

    /**
     * Starts the element output.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="'    . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="'   . esc_attr( $item->url ) .'"' : '';

        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= ( $args->link_before ?? '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( $args->link_after ?? '' );
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}

/**
 * Optimize WordPress performance
 */
function aqualuxe_optimize_performance() {
    // Remove unnecessary head elements
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );

    // Remove emoji scripts and styles
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'aqualuxe_optimize_performance' );

/**
 * Add Google Fonts to the page
 */
function aqualuxe_google_fonts() {
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
    
    $fonts = array();
    
    if ( 'Inter' !== $body_font ) {
        $fonts[] = $body_font . ':300,400,500,600,700';
    }
    
    if ( 'Playfair Display' !== $heading_font && $heading_font !== $body_font ) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }
    
    if ( ! empty( $fonts ) ) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $fonts ) . '&display=swap';
        wp_enqueue_style( 'aqualuxe-google-fonts', $fonts_url, array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_google_fonts' );