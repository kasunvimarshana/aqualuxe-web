<?php
/**
 * Template part for displaying page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden' ); ?>>
    
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail mb-6">
            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-64 object-cover rounded-lg' ) ); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <header class="entry-header mb-6">
            <?php the_title( '<h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' ); ?>
        </header><!-- .entry-header -->

        <div class="entry-content text-gray-700 dark:text-gray-300 prose prose-lg max-w-none">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->

        <?php if ( get_edit_post_link() ) : ?>
            <footer class="entry-footer mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="edit-link">
                    <?php
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
                        '<span class="edit-link text-sm text-primary-600 hover:text-primary-700">',
                        '</span>'
                    );
                    ?>
                </div>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->