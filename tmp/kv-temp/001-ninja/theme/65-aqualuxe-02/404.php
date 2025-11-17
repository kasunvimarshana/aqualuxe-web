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

<div class="container mx-auto px-4 py-16">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full px-4">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

                    <section class="error-404 not-found text-center">
                        <div class="error-image mb-8">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/404-fish.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="inline-block max-w-md w-full">
                        </div>

                        <header class="page-header mb-8">
                            <h1 class="page-title text-4xl font-bold"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content prose dark:prose-invert mx-auto max-w-2xl">
                            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                            <div class="search-form-container my-8">
                                <?php get_search_form(); ?>
                            </div>

                            <div class="widget widget_categories mb-8">
                                <h2 class="widget-title text-2xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                                <ul class="flex flex-wrap justify-center gap-2">
                                    <?php
                                    wp_list_categories(
                                        array(
                                            'orderby'    => 'count',
                                            'order'      => 'DESC',
                                            'show_count' => 1,
                                            'title_li'   => '',
                                            'number'     => 10,
                                        )
                                    );
                                    ?>
                                </ul>
                            </div><!-- .widget -->

                            <div class="mb-8">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                                    <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
                                </a>
                            </div>
                        </div><!-- .page-content -->
                    </section><!-- .error-404 -->

                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .w-full -->
    </div><!-- .flex -->
</div><!-- .container -->

<?php
get_footer();