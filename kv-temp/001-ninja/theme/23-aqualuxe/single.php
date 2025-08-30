<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod( 'aqualuxe_single_post_sidebar', 'right' );
$container_class = 'container mx-auto px-4';
$content_class = 'site-content py-12';

if ( $sidebar_position === 'none' ) {
    $content_class .= ' max-w-4xl mx-auto';
} else {
    $content_class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';
}
?>

<main id="primary" class="<?php echo esc_attr( $container_class ); ?>">
    <div class="<?php echo esc_attr( $content_class ); ?>">
        <?php if ( $sidebar_position === 'left' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>

        <div class="content-area <?php echo $sidebar_position === 'none' ? '' : 'lg:col-span-9'; ?>">
            <?php
            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content/content', 'single' );

            endwhile; // End of the loop.
            ?>
        </div>

        <?php if ( $sidebar_position === 'right' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_footer();