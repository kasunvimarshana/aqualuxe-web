<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header mb-6">
        <?php the_title( '<h1 class="entry-title text-3xl font-bold">', '</h1>' ); ?>

        <div class="entry-meta text-gray-600 dark:text-gray-400 text-sm mt-2">
            <?php
            aqualuxe_posted_on();
            aqualuxe_posted_by();
            ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-thumbnail mb-6">
            <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content prose dark:prose-invert max-w-none">
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

    <footer class="entry-footer mt-6">
        <?php aqualuxe_entry_footer(); ?>
    </footer><!-- .entry-footer -->

    <?php if ( '' !== get_the_author_meta( 'description' ) ) : ?>
        <div class="author-bio bg-gray-100 dark:bg-gray-800 p-6 rounded-lg mt-8">
            <div class="flex items-center mb-4">
                <div class="author-avatar mr-4">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'class' => 'rounded-full' ) ); ?>
                </div>
                <div class="author-info">
                    <h3 class="author-name text-xl font-bold">
                        <?php echo esc_html( get_the_author() ); ?>
                    </h3>
                    <div class="author-description prose dark:prose-invert max-w-none">
                        <?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <nav class="navigation post-navigation mt-8 border-t border-b border-gray-200 dark:border-gray-700 py-4">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'aqualuxe' ); ?></h2>
        <div class="nav-links flex flex-wrap justify-between">
            <?php
            previous_post_link( '<div class="nav-previous">%link</div>', '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>' );
            next_post_link( '<div class="nav-next">%link</div>', '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>' );
            ?>
        </div>
    </nav>

    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>
</article><!-- #post-<?php the_ID(); ?> -->