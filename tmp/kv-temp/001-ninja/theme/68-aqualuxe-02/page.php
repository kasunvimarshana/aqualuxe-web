<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
            while ( have_posts() ) :
                the_post();

                get_template_part( 'templates/parts/content/content', 'page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
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