<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-16">
        <section class="error-404 not-found text-center">
            <div class="max-w-2xl mx-auto">
                <header class="page-header mb-8">
                    <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                    <div class="error-code text-8xl font-bold text-primary mb-6">404</div>
                    <p class="text-xl text-gray-600"><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>
                </header><!-- .page-header -->

                <div class="page-content">
                    <div class="search-form-container max-w-md mx-auto mb-12">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="widget-area mb-12">
                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h2>
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
                    </div>

                    <div class="widget-area mb-12">
                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                        <ul class="space-y-2">
                            <?php
                            wp_get_archives(
                                array(
                                    'type'      => 'postbypost',
                                    'limit'     => 5,
                                )
                            );
                            ?>
                        </ul>
                    </div>

                    <div class="back-home mt-8">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->
    </div>
</main><!-- #primary -->

<?php
get_footer();