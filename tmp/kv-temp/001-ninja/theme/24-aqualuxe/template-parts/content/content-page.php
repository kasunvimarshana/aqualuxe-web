<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Check if title should be hidden
$hide_title = get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true );
$hide_featured_image = get_post_meta( get_the_ID(), '_aqualuxe_hide_featured_image', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden' ); ?>>
    <?php if ( has_post_thumbnail() && ! $hide_featured_image ) : ?>
        <div class="page-thumbnail">
            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover max-h-96' ) ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content-wrapper p-6 md:p-8">
        <?php if ( ! $hide_title ) : ?>
            <header class="entry-header mb-6">
                <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold">', '</h1>' ); ?>
            </header><!-- .entry-header -->
        <?php endif; ?>

        <div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->

        <?php if ( get_edit_post_link() ) : ?>
            <footer class="entry-footer mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
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
                    '<span class="edit-link inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">',
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg></span>'
                );
                ?>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div><!-- .entry-content-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->