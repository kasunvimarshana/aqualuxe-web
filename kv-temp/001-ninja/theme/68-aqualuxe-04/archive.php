<?php
/**
 * The template for displaying archive pages
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

            <?php if ( have_posts() ) : ?>

                <header class="page-header">
                    <?php
                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                    the_archive_description( '<div class="archive-description">', '</div>' );
                    ?>
                </header><!-- .page-header -->

                <?php
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