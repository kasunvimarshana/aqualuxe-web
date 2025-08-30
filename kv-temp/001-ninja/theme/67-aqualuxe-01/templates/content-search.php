<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$blog_layout = aqualuxe_get_blog_layout();
$post_classes = array( 'post-item', 'post-layout-' . $blog_layout, 'search-result' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <?php do_action( 'aqualuxe_search_before_content' ); ?>

    <?php aqualuxe_post_thumbnail(); ?>

    <div class="post-content">
        <header class="entry-header">
            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

            <?php if ( 'post' === get_post_type() ) : ?>
                <?php aqualuxe_post_meta(); ?>
            <?php else : ?>
                <div class="entry-meta">
                    <span class="post-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer">
            <?php aqualuxe_read_more_link(); ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->

    <?php do_action( 'aqualuxe_search_after_content' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->