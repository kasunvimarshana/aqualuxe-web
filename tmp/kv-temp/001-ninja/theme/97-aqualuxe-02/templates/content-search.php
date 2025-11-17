<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300'); ?>>
    <div class="p-6 sm:p-8">
        <header class="entry-header mb-4">
            <?php the_title( sprintf( '<h2 class="entry-title text-2xl font-bold text-gray-900 dark:text-gray-100"><a href="%s" rel="bookmark" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

            <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta text-sm text-gray-500 dark:text-gray-400 mt-2">
                <?php
                if ( function_exists( 'aqualuxe_posted_on' ) ) {
                    aqualuxe_posted_on();
                }
                if ( function_exists( 'aqualuxe_posted_by' ) ) {
                    aqualuxe_posted_by();
                }
                ?>
            </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary text-gray-600 dark:text-gray-300">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer mt-6">
             <a href="<?php the_permalink(); ?>" class="read-more text-blue-600 dark:text-blue-400 hover:underline font-semibold"><?php esc_html_e( 'Continue reading &rarr;', 'aqualuxe' ); ?></a>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
