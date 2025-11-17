<?php
/**
 * The template for displaying search results pages
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
                <h1 class="page-title text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php
                    printf(
                        /* translators: %s: search query. */
                        esc_html__( 'Search Results for: %s', 'aqualuxe' ),
                        '<span class="text-primary-600">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header><!-- .page-header -->

            <div class="search-results grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    
                    /*
                     * Run the loop for the search to output the results.
                     */
                    get_template_part( 'template-parts/content', 'search' );
                    
                endwhile;
                ?>
            </div>

            <?php
            the_posts_navigation( array(
                'prev_text'          => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">' . esc_html__( 'Older results', 'aqualuxe' ) . '</span>',
                'next_text'          => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">' . esc_html__( 'Newer results', 'aqualuxe' ) . '</span>',
                'screen_reader_text' => __( 'Search results navigation', 'aqualuxe' ),
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