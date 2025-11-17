<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get blog layout from theme mod
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
$post_classes = 'post-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md';

// Add specific classes based on layout
if ( $blog_layout === 'list' ) {
    $post_classes .= ' flex flex-col md:flex-row';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail <?php echo $blog_layout === 'list' ? 'md:w-1/3' : ''; ?>">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php 
                $thumbnail_size = $blog_layout === 'list' ? 'medium' : 'medium_large';
                the_post_thumbnail( $thumbnail_size, array(
                    'class' => 'w-full h-auto object-cover ' . ($blog_layout === 'list' ? 'h-full' : 'h-56 md:h-64'),
                ) ); 
                ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content-wrapper p-6 <?php echo $blog_layout === 'list' && has_post_thumbnail() ? 'md:w-2/3' : ''; ?>">
        <header class="entry-header mb-4">
            <?php
            if ( is_sticky() && is_home() && ! is_paged() ) {
                echo '<span class="sticky-post inline-block bg-primary text-white text-xs font-bold px-2 py-1 rounded mb-2">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
            }
            
            the_title( '<h2 class="entry-title text-xl md:text-2xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">', '</a></h2>' );

            if ( 'post' === get_post_type() ) :
                ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-content prose dark:prose-invert max-w-none">
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
            } else {
                the_excerpt();
            }
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer mt-4">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary hover:text-primary-dark dark:hover:text-primary-light font-medium transition-colors">
                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </footer><!-- .entry-footer -->
    </div><!-- .entry-content-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->