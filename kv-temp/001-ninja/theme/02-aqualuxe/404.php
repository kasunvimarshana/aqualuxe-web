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
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <section class="error-404 not-found">
                    <div class="error-404-content">
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content">
                            <div class="error-404-image">
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="img-fluid">
                            </div>
                            
                            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                            <div class="error-404-search">
                                <?php get_search_form(); ?>
                            </div>

                            <div class="error-404-widgets">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="widget widget_recent_entries">
                                            <h2 class="widget-title"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                                            <ul>
                                                <?php
                                                wp_get_archives(
                                                    array(
                                                        'type'    => 'postbypost',
                                                        'limit'   => 5,
                                                    )
                                                );
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="error-404-actions">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                                    <i class="fas fa-home"></i>
                                    <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                                </a>
                                
                                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-secondary">
                                        <i class="fas fa-shopping-cart"></i>
                                        <?php esc_html_e( 'Visit Shop', 'aqualuxe' ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div><!-- .page-content -->
                    </div>
                </section><!-- .error-404 -->
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();