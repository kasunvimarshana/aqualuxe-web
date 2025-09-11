<?php
/**
 * The main template for displaying archive pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main">
    <?php aqualuxe_breadcrumbs(); ?>
    
    <div class="container mx-auto px-4 py-8">
        
        <?php if ( have_posts() ) : ?>
            
            <header class="page-header mb-8">
                <?php
                the_archive_title( '<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
                the_archive_description( '<div class="archive-description text-gray-600 dark:text-gray-400 text-lg">', '</div>' );
                ?>
            </header><!-- .page-header -->

            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    
                    /*
                     * Include the Post-Type-specific template for the content.
                     */
                    get_template_part( 'template-parts/content', get_post_type() );
                    
                endwhile;
                ?>
            </div>

            <?php
            // Previous/next page navigation
            the_posts_navigation( array(
                'prev_text'          => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">' . esc_html__( 'Older posts', 'aqualuxe' ) . '</span>',
                'next_text'          => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">' . esc_html__( 'Newer posts', 'aqualuxe' ) . '</span>',
                'screen_reader_text' => __( 'Posts navigation', 'aqualuxe' ),
            ) );
            ?>
            
        <?php else : ?>
            
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
            
        <?php endif; ?>
        
    </div>
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();