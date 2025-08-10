<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover max-h-96' ) ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content-wrapper p-6 md:p-8">
        <header class="entry-header mb-6">
            <?php
            if ( is_sticky() ) {
                echo '<span class="sticky-post inline-block bg-primary text-white text-xs font-bold px-2 py-1 rounded mb-2">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
            }
            
            the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>' );
            ?>

            <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
                <?php
                // Author avatar
                echo '<div class="author-avatar mr-3">';
                echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'class' => 'rounded-full' ) );
                echo '</div>';
                
                echo '<div>';
                aqualuxe_posted_by();
                echo '<span class="mx-2">•</span>';
                aqualuxe_posted_on();
                echo '</div>';
                ?>
            </div><!-- .entry-meta -->

            <?php
            // Categories
            $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
            if ( $categories_list ) {
                echo '<div class="post-categories mb-4">';
                echo '<span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
                echo '<span class="text-sm">' . $categories_list . '</span>';
                echo '</div>';
            }
            ?>
        </header><!-- .entry-header -->

        <div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none mb-8">
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
                    'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer pt-6 border-t border-gray-200 dark:border-gray-700">
            <?php
            // Tags
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
            if ( $tags_list ) {
                echo '<div class="post-tags mb-6">';
                echo '<span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
                echo '<span class="text-sm">' . $tags_list . '</span>';
                echo '</div>';
            }
            
            // Social sharing
            if ( function_exists( 'aqualuxe_social_share' ) ) {
                aqualuxe_social_share();
            }
            
            // Author bio
            if ( get_the_author_meta( 'description' ) ) {
                get_template_part( 'template-parts/content/author-bio' );
            }
            ?>
        </footer><!-- .entry-footer -->
    </div><!-- .entry-content-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->