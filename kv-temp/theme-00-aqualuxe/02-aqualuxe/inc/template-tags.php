<?php
/**
 * Custom template tags for AquaLuxe theme
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display navigation to next/previous set of posts when applicable.
 */
function aqualuxe_posts_navigation() {
    // Don't print empty markup if there's only one page.
    if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
        return;
    }
    
    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );
    
    if ( isset( $url_parts[1] ) ) {
        wp_parse_str( $url_parts[1], $query_args );
    }
    
    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
    
    $format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
    
    // Set up paginated links.
    $links = paginate_links( array(
        'base'     => $pagenum_link,
        'format'   => $format,
        'total'    => $GLOBALS['wp_query']->max_num_pages,
        'current'  => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => __( '&larr; Previous', 'aqualuxe' ),
        'next_text' => __( 'Next &rarr;', 'aqualuxe' ),
        'type'      => 'list',
    ) );
    
    if ( $links ) {
        echo '<nav class="navigation paging-navigation" role="navigation">';
        echo '<h2 class="screen-reader-text">' . __( 'Posts navigation', 'aqualuxe' ) . '</h2>';
        echo '<div class="pagination loop-pagination">' . $links . '</div>';
        echo '</nav>';
    }
}

/**
 * Display navigation to next/previous post when applicable.
 */
function aqualuxe_post_navigation() {
    $args = array(
        'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'aqualuxe' ) . '</span> ' .
            '<span class="screen-reader-text">' . __( 'Next post:', 'aqualuxe' ) . '</span> ' .
            '<span class="post-title">%title</span>',
        'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'aqualuxe' ) . '</span> ' .
            '<span class="screen-reader-text">' . __( 'Previous post:', 'aqualuxe' ) . '</span> ' .
            '<span class="post-title">%title</span>',
    );
    
    the_post_navigation( $args );
}

/**
 * Display post entry meta
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    
    $time_string = sprintf( $time_string,
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
    
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );
    
    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
}

/**
 * Display product entry meta
 */
function aqualuxe_product_meta() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    echo '<div class="product-meta">';
    
    // Display SKU
    if ( $product->get_sku() ) {
        echo '<span class="sku_wrapper">' . __( 'SKU:', 'aqualuxe' ) . ' <span class="sku">' . $product->get_sku() . '</span></span>';
    }
    
    // Display categories
    $categories = wc_get_product_category_list( $product->get_id() );
    if ( $categories ) {
        echo '<span class="posted_in">' . __( 'Categories:', 'aqualuxe' ) . ' ' . $categories . '</span>';
    }
    
    // Display tags
    $tags = wc_get_product_tag_list( $product->get_id() );
    if ( $tags ) {
        echo '<span class="tagged_as">' . __( 'Tags:', 'aqualuxe' ) . ' ' . $tags . '</span>';
    }
    
    echo '</div>';
}

/**
 * Display product rating
 */
function aqualuxe_product_rating() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average      = $product->get_average_rating();
    
    if ( $rating_count > 0 ) {
        echo '<div class="product-rating">';
        echo wc_get_rating_html( $average, $rating_count );
        
        if ( comments_open() ) {
            echo '<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(' . sprintf( _n( '%s review', '%s reviews', $review_count, 'aqualuxe' ), $review_count ) . ')</a>';
        }
        
        echo '</div>';
    }
}

/**
 * Display product price
 */
function aqualuxe_product_price() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    echo '<div class="product-price">';
    echo $product->get_price_html();
    echo '</div>';
}

/**
 * Display product add to cart button
 */
function aqualuxe_product_add_to_cart() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    echo '<div class="product-add-to-cart">';
    woocommerce_template_loop_add_to_cart();
    echo '</div>';
}

/**
 * Display breadcrumb navigation
 */
function aqualuxe_breadcrumb() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    woocommerce_breadcrumb( array(
        'delimiter'   => '<span class="breadcrumb-separator">/</span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => __( 'Home', 'aqualuxe' ),
    ) );
}

/**
 * Display social sharing buttons
 */
function aqualuxe_social_sharing() {
    $url = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    
    echo '<div class="social-sharing">';
    echo '<h3>' . __( 'Share this:', 'aqualuxe' ) . '</h3>';
    echo '<ul>';
    echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" rel="noopener">' . __( 'Facebook', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener">' . __( 'Twitter', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '" target="_blank" rel="noopener">' . __( 'LinkedIn', 'aqualuxe' ) . '</a></li>';
    echo '<li><a href="https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title . '" target="_blank" rel="noopener">' . __( 'Pinterest', 'aqualuxe' ) . '</a></li>';
    echo '</ul>';
    echo '</div>';
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    if ( is_single() ) {
        $categories = get_the_category();
        $category_ids = array();
        
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in' => $category_ids,
            'post__not_in' => array( get_the_ID() ),
            'posts_per_page' => 3,
            'orderby' => 'rand',
        );
        
        $related_posts = new WP_Query( $args );
        
        if ( $related_posts->have_posts() ) {
            echo '<div class="related-posts">';
            echo '<h3>' . __( 'Related Posts', 'aqualuxe' ) . '</h3>';
            echo '<div class="related-posts-grid">';
            
            while ( $related_posts->have_posts() ) {
                $related_posts->the_post();
                echo '<div class="related-post">';
                if ( has_post_thumbnail() ) {
                    echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), 'aqualuxe-blog-thumbnail' ) . '</a>';
                }
                echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                echo '<div class="entry-meta">' . aqualuxe_posted_on() . '</div>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            
            wp_reset_postdata();
        }
    }
}

/**
 * Display post thumbnail with custom size
 */
function aqualuxe_post_thumbnail( $size = 'full' ) {
    if ( has_post_thumbnail() ) {
        echo '<div class="post-thumbnail">';
        the_post_thumbnail( $size );
        echo '</div>';
    }
}

/**
 * Display post excerpt with custom length
 */
function aqualuxe_excerpt( $length = 30 ) {
    $excerpt = wp_trim_words( get_the_excerpt(), $length, '...' );
    echo '<div class="entry-excerpt">' . $excerpt . '</div>';
}

/**
 * Display post categories
 */
function aqualuxe_post_categories() {
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        echo '<div class="post-categories">';
        echo '<span class="categories-label">' . __( 'Categories:', 'aqualuxe' ) . '</span> ';
        the_category( ', ' );
        echo '</div>';
    }
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    $tags = get_the_tags();
    if ( ! empty( $tags ) ) {
        echo '<div class="post-tags">';
        echo '<span class="tags-label">' . __( 'Tags:', 'aqualuxe' ) . '</span> ';
        the_tags( '', ', ' );
        echo '</div>';
    }
}

/**
 * Display comment count
 */
function aqualuxe_comment_count() {
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
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
                get_the_title()
            )
        );
        echo '</span>';
    }
}