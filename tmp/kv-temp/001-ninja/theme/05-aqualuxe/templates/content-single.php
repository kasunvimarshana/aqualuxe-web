<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        // Post categories
        if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_categories', true ) ) {
            aqualuxe_post_categories();
        }
        
        // Post title
        the_title( '<h1 class="entry-title">', '</h1>' );
        
        // Post meta
        if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_meta', true ) ) {
            aqualuxe_entry_meta();
        }
        ?>
    </header><!-- .entry-header -->

    <?php
    // Featured image
    if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_single_featured_image', true ) ) {
        ?>
        <div class="post-thumbnail">
            <?php
            the_post_thumbnail(
                'aqualuxe-featured-image',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'featured-image',
                )
            );
            
            // Featured image caption
            aqualuxe_post_thumbnail_caption();
            ?>
        </div><!-- .post-thumbnail -->
        <?php
    }
    ?>

    <div class="entry-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        // Post tags
        if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_tags', true ) ) {
            aqualuxe_entry_footer_meta();
        }
        ?>
    </footer><!-- .entry-footer -->
    
    <?php
    // Author bio
    if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_author_bio', true ) ) {
        aqualuxe_author_bio();
    }
    
    // Post navigation
    if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_post_nav', true ) ) {
        aqualuxe_post_navigation();
    }
    
    // Related posts
    if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_single_show_related_posts', true ) ) {
        aqualuxe_related_posts();
    }
    ?>
</article><!-- #post-<?php the_ID(); ?> -->