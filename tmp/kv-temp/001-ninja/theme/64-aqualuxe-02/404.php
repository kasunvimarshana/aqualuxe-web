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

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="error-404 not-found">
            <div class="error-404-container">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( '404', 'aqualuxe' ); ?></h1>
                    <h2 class="page-subtitle"><?php esc_html_e( 'Page Not Found', 'aqualuxe' ); ?></h2>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?></p>

                    <div class="error-404-search">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="error-404-widgets">
                        <div class="error-404-widget">
                            <h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                            <ul>
                                <?php
                                wp_get_archives(
                                    [
                                        'type'      => 'postbypost',
                                        'limit'     => 5,
                                    ]
                                );
                                ?>
                            </ul>
                        </div>

                        <div class="error-404-widget">
                            <h2><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h2>
                            <ul>
                                <?php
                                wp_list_categories(
                                    [
                                        'orderby'    => 'count',
                                        'order'      => 'DESC',
                                        'show_count' => 1,
                                        'title_li'   => '',
                                        'number'     => 5,
                                    ]
                                );
                                ?>
                            </ul>
                        </div>

                        <div class="error-404-widget">
                            <h2><?php esc_html_e( 'Archives', 'aqualuxe' ); ?></h2>
                            <ul>
                                <?php
                                wp_get_archives(
                                    [
                                        'type'      => 'monthly',
                                        'limit'     => 5,
                                    ]
                                );
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="error-404-button">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                            <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();