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

<div id="primary" class="content-area col-lg-12">
    <main id="main" class="site-main">

        <section class="error-404 not-found">
            <div class="error-404-content">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                    <?php get_search_form(); ?>

                    <div class="error-404-widgets row">
                        <div class="col-md-4">
                            <div class="widget widget_recent_entries">
                                <h2 class="widget-title"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                                <ul>
                                    <?php
                                    wp_get_archives(
                                        array(
                                            'type'  => 'postbypost',
                                            'limit' => 5,
                                        )
                                    );
                                    ?>
                                </ul>
                            </div><!-- .widget -->
                        </div>

                        <div class="col-md-4">
                            <div class="widget widget_categories">
                                <h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                                <ul>
                                    <?php
                                    wp_list_categories(
                                        array(
                                            'orderby'    => 'count',
                                            'order'      => 'DESC',
                                            'show_count' => 1,
                                            'title_li'   => '',
                                            'number'     => 5,
                                        )
                                    );
                                    ?>
                                </ul>
                            </div><!-- .widget -->
                        </div>

                        <div class="col-md-4">
                            <div class="widget widget_tag_cloud">
                                <h2 class="widget-title"><?php esc_html_e( 'Popular Tags', 'aqualuxe' ); ?></h2>
                                <?php
                                wp_tag_cloud(
                                    array(
                                        'number'     => 10,
                                        'orderby'    => 'count',
                                        'order'      => 'DESC',
                                        'show_count' => 1,
                                    )
                                );
                                ?>
                            </div><!-- .widget -->
                        </div>
                    </div><!-- .error-404-widgets -->

                    <div class="back-to-home">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?></a>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404-content -->
        </section><!-- .error-404 -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();