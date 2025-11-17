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
    <main id="main" class="site-main" role="main">

        <?php do_action( 'aqualuxe_404_before' ); ?>

        <section class="error-404 not-found">
            <div class="error-404-content">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <div class="error-404-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </div>

                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                    <?php get_search_form(); ?>

                    <div class="error-404-widgets">
                        <div class="error-404-widget">
                            <h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                            <ul>
                                <?php
                                wp_get_archives( array(
                                    'type'      => 'postbypost',
                                    'limit'     => 5,
                                ) );
                                ?>
                            </ul>
                        </div>

                        <div class="error-404-widget">
                            <h2><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                            <ul>
                                <?php
                                wp_list_categories( array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 5,
                                ) );
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="error-404-actions">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?></a>
                        
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Visit Shop', 'aqualuxe' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404-content -->
        </section><!-- .error-404 -->

        <?php do_action( 'aqualuxe_404_after' ); ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();