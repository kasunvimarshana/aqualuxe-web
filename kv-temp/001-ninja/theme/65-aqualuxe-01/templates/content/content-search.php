<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 dark:border-gray-700'); ?>>
    <header class="entry-header mb-4">
        <?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

        <?php if ( 'post' === get_post_type() ) : ?>
        <div class="entry-meta text-gray-600 dark:text-gray-400 text-sm mt-2">
            <?php
            aqualuxe_posted_on();
            aqualuxe_posted_by();
            ?>
        </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-summary prose dark:prose-invert max-w-none">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer mt-4">
        <a href="<?php the_permalink(); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->