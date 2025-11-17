<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    // Featured image
    if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_blog_featured_image', true ) ) {
        ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
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
                ?>
            </a>
        </div><!-- .post-thumbnail -->
        <?php
    }
    ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            // Post categories
            if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_blog_show_categories', true ) ) {
                aqualuxe_post_categories();
            }
            
            // Post title
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            
            // Post meta
            if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_blog_show_meta', true ) ) {
                aqualuxe_entry_meta();
            }
            ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            if ( get_theme_mod( 'aqualuxe_blog_content_display', 'excerpt' ) === 'excerpt' ) {
                the_excerpt();
                
                if ( get_theme_mod( 'aqualuxe_blog_show_read_more', true ) ) {
                    aqualuxe_read_more();
                }
            } else {
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
            }
            
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
            if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_blog_show_tags', true ) ) {
                aqualuxe_entry_footer_meta();
            }
            ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->