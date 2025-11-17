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

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-16">
        <section class="error-404 not-found text-center max-w-3xl mx-auto">
            <header class="page-header mb-8">
                <h1 class="page-title text-5xl font-bold mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <div class="error-image mb-8">
                    <img src="<?php echo esc_url(get_theme_mod('aqualuxe_404_image', get_template_directory_uri() . '/assets/images/404.svg')); ?>" alt="<?php esc_attr_e('404 Error', 'aqualuxe'); ?>" class="mx-auto max-w-full h-auto">
                </div>
                
                <p class="text-xl mb-6"><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>

                <div class="search-form-container mb-8 max-w-lg mx-auto">
                    <?php get_search_form(); ?>
                </div>

                <div class="widget-area mb-8">
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Most Used Categories', 'aqualuxe'); ?></h2>
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

                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary">
                        <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                    </a>
                </div>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer();