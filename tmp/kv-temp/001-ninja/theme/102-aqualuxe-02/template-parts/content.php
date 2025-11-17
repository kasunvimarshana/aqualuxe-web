<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg' ); ?>>
    
    <?php if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_show_featured_image', true ) ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <header class="entry-header mb-4">
            <?php
            if ( is_singular() ) :
                the_title( '<h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
            else :
                the_title( '<h2 class="entry-title text-xl font-semibold text-gray-900 dark:text-white mb-2 hover:text-primary-600 transition-colors"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="no-underline">', '</a></h2>' );
            endif;

            if ( 'post' === get_post_type() && get_theme_mod( 'aqualuxe_show_post_meta', true ) ) :
            ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex flex-wrap items-center gap-4 mb-4">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                    <span class="reading-time">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php echo esc_html( aqualuxe_estimated_reading_time() ); ?>
                    </span>
                </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-content text-gray-700 dark:text-gray-300 prose max-w-none">
            <?php
            if ( is_singular() ) {
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
            } else {
                the_excerpt();
            }
            ?>
        </div><!-- .entry-content -->

        <?php if ( ! is_singular() ) : ?>
            <div class="entry-footer mt-4">
                <a href="<?php the_permalink(); ?>" class="read-more-link inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors">
                    <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        <?php endif; ?>

        <?php if ( is_singular() ) : ?>
            <footer class="entry-footer mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <?php aqualuxe_entry_footer(); ?>
                </div>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->