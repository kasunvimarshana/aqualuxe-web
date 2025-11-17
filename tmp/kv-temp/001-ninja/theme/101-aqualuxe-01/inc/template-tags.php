<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Prints HTML with meta information for the current post-date/time
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

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author
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
 * Prints HTML with meta information for categories, tags and comments
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
 * Display post thumbnail with lazy loading
 */
function aqualuxe_post_thumbnail( $size = 'large', $lazy = true ) {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id();
    $image_src    = wp_get_attachment_image_src( $thumbnail_id, $size );
    $image_alt    = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
    
    if ( ! $image_alt ) {
        $image_alt = get_the_title();
    }

    if ( is_singular() ) : ?>
        <div class="post-thumbnail">
            <?php if ( $lazy ) : ?>
                <img 
                    data-src="<?php echo esc_url( $image_src[0] ); ?>" 
                    alt="<?php echo esc_attr( $image_alt ); ?>"
                    class="lazy w-full h-auto rounded-lg"
                    width="<?php echo esc_attr( $image_src[1] ); ?>"
                    height="<?php echo esc_attr( $image_src[2] ); ?>"
                />
            <?php else : ?>
                <?php the_post_thumbnail( $size, array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php if ( $lazy ) : ?>
                <img 
                    data-src="<?php echo esc_url( $image_src[0] ); ?>" 
                    alt="<?php echo esc_attr( $image_alt ); ?>"
                    class="lazy w-full h-auto rounded-lg"
                    width="<?php echo esc_attr( $image_src[1] ); ?>"
                    height="<?php echo esc_attr( $image_src[2] ); ?>"
                />
            <?php else : ?>
                <?php the_post_thumbnail( $size, array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
            <?php endif; ?>
        </a>
    <?php endif;
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $separator = '<span class="separator" aria-hidden="true">/</span>';
    $home_text = esc_html__( 'Home', 'aqualuxe' );
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb Navigation', 'aqualuxe' ) . '">';
    echo '<ol class="breadcrumb-list">';
    
    // Home link
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . $home_text . '</a>';
    echo '</li>';
    echo $separator;

    if ( is_category() || is_single() ) {
        if ( is_single() ) {
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $category = $categories[0];
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                echo '</li>';
                echo $separator;
            }
        }
        
        if ( is_category() ) {
            echo '<li class="breadcrumb-item current">';
            echo single_cat_title( '', false );
            echo '</li>';
        } else {
            echo '<li class="breadcrumb-item current">';
            echo get_the_title();
            echo '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li class="breadcrumb-item current">';
        echo get_the_title();
        echo '</li>';
    } elseif ( is_search() ) {
        echo '<li class="breadcrumb-item current">';
        echo esc_html__( 'Search Results', 'aqualuxe' );
        echo '</li>';
    } elseif ( is_404() ) {
        echo '<li class="breadcrumb-item current">';
        echo esc_html__( '404 Error', 'aqualuxe' );
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Get social share buttons
 */
function aqualuxe_social_share() {
    if ( ! is_singular() ) {
        return;
    }

    $url   = get_permalink();
    $title = get_the_title();
    
    $facebook_url  = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
    $twitter_url   = 'https://twitter.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title );
    $linkedin_url  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $url );
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . urlencode( $url ) . '&description=' . urlencode( $title );

    echo '<div class="social-share">';
    echo '<h4 class="share-title">' . esc_html__( 'Share this post', 'aqualuxe' ) . '</h4>';
    echo '<div class="share-buttons">';
    
    echo '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener" class="share-button facebook">';
    echo '<span class="screen-reader-text">' . esc_html__( 'Share on Facebook', 'aqualuxe' ) . '</span>';
    echo '</a>';
    
    echo '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener" class="share-button twitter">';
    echo '<span class="screen-reader-text">' . esc_html__( 'Share on Twitter', 'aqualuxe' ) . '</span>';
    echo '</a>';
    
    echo '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener" class="share-button linkedin">';
    echo '<span class="screen-reader-text">' . esc_html__( 'Share on LinkedIn', 'aqualuxe' ) . '</span>';
    echo '</a>';
    
    echo '<a href="' . esc_url( $pinterest_url ) . '" target="_blank" rel="noopener" class="share-button pinterest">';
    echo '<span class="screen-reader-text">' . esc_html__( 'Share on Pinterest', 'aqualuxe' ) . '</span>';
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display reading time estimate
 */
function aqualuxe_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed

    if ( $reading_time === 1 ) {
        $timer = esc_html__( 'minute', 'aqualuxe' );
    } else {
        $timer = esc_html__( 'minutes', 'aqualuxe' );
    }

    echo '<span class="reading-time">';
    printf(
        /* translators: %1$d: reading time in minutes, %2$s: minute/minutes */
        esc_html__( '%1$d %2$s read', 'aqualuxe' ),
        $reading_time,
        $timer
    );
    echo '</span>';
}