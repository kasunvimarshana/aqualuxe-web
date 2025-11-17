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
    <section class="error-404 not-found">
        <div class="container">
            <div class="error-404-content">
                <div class="error-404-image">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( 'Page not found', 'aqualuxe' ); ?>">
                </div>
                
                <div class="error-404-text">
                    <h1 class="error-title"><?php esc_html_e( '404', 'aqualuxe' ); ?></h1>
                    <h2 class="error-subtitle"><?php esc_html_e( 'Oops! Page not found', 'aqualuxe' ); ?></h2>
                    <p class="error-description">
                        <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe' ); ?>
                    </p>
                    
                    <div class="error-actions">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="error-404-search">
                <h3><?php esc_html_e( 'Search our site', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Perhaps you can find what you\'re looking for with a search?', 'aqualuxe' ); ?></p>
                <?php get_search_form(); ?>
            </div>
            
            <div class="error-404-help">
                <div class="row">
                    <div class="col-md-4">
                        <div class="error-help-widget">
                            <h3><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h3>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts( array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish'
                                ) );
                                
                                foreach ( $recent_posts as $post ) :
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                                            <?php echo esc_html( $post['post_title'] ); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="error-help-widget">
                            <h3><?php esc_html_e( 'Popular Categories', 'aqualuxe' ); ?></h3>
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
                    
                    <div class="col-md-4">
                        <div class="error-help-widget">
                            <h3><?php esc_html_e( 'Try These Links', 'aqualuxe' ); ?></h3>
                            <ul>
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
                                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
                                <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- .error-404 -->
</main><!-- #main -->

<?php
get_footer();