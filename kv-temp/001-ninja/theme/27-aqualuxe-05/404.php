<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-12">

    <section class="error-404 not-found text-center max-w-2xl mx-auto">
        <header class="page-header mb-8">
            <h1 class="page-title text-4xl md:text-5xl font-bold text-blue-900"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
            <div class="error-image mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 mx-auto text-blue-800 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <p class="text-xl mb-8"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

            <div class="search-form mb-12">
                <?php get_search_form(); ?>
            </div>

            <div class="widget-area grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div class="widget">
                    <h2 class="widget-title text-xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                    <ul class="recent-posts">
                        <?php
                        wp_get_archives(
                            array(
                                'type'    => 'postbypost',
                                'limit'   => 5,
                                'format'  => 'html',
                                'before'  => '<li class="mb-2">',
                                'after'   => '</li>',
                            )
                        );
                        ?>
                    </ul>
                </div>

                <div class="widget">
                    <h2 class="widget-title text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                    <ul class="categories-list">
                        <?php
                        wp_list_categories(
                            array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'show_count' => 1,
                                'title_li'   => '',
                                'number'     => 5,
                                'before'     => '<li class="mb-2">',
                                'after'      => '</li>',
                            )
                        );
                        ?>
                    </ul>
                </div>
            </div>

            <div class="back-home mt-12">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                    <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
                </a>
            </div>

        </div><!-- .page-content -->
    </section><!-- .error-404 -->

</main><!-- #main -->

<?php
get_footer();