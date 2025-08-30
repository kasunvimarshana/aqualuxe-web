<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

$layout = \AquaLuxe\Helpers\Utils::get_theme_layout();
$sidebar_position = strpos( $layout, 'left' ) !== false ? 'left' : 'right';
$container_class = 'container';

if ( $layout === 'full-width' ) {
    $container_class .= ' full-width';
}
?>

<div class="<?php echo esc_attr( $container_class ); ?>">
    <div class="row">
        <?php if ( $layout !== 'full-width' && $sidebar_position === 'left' ) : ?>
            <div class="sidebar-container">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

        <div id="primary" class="content-area">
            <main id="main" class="site-main">

            <?php
            // Show advanced filtering form on WooCommerce shop and product archives
            if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
                get_template_part( 'template-parts/filtering/form' );
            }
            ?>

            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'templates/parts/content/content', get_post_type() );

                endwhile;

                the_posts_navigation();

            else :

                get_template_part( 'templates/parts/content/content', 'none' );

            endif;
            ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if ( $layout !== 'full-width' && $sidebar_position === 'right' ) : ?>
            <div class="sidebar-container">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();