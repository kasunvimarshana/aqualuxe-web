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
 */

get_header();

// Get page layout from meta
$page_layout = get_post_meta( get_the_ID(), '_aqualuxe_page_layout', true );

// If no custom layout is set, use the default from customizer
if ( ! $page_layout || $page_layout === 'default' ) {
    $page_layout = get_theme_mod( 'aqualuxe_default_page_layout', 'right-sidebar' );
}

$container_class = 'container mx-auto px-4';
$content_class = 'site-content py-12';

if ( $page_layout === 'full-width' ) {
    $content_class .= ' max-w-none';
} elseif ( $page_layout === 'narrow' ) {
    $content_class .= ' max-w-3xl mx-auto';
} else {
    $content_class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';
}
?>

<main id="primary" class="<?php echo esc_attr( $container_class ); ?>">
    <div class="<?php echo esc_attr( $content_class ); ?>">
        <?php if ( $page_layout === 'left-sidebar' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>

        <div class="content-area <?php echo ( $page_layout === 'left-sidebar' || $page_layout === 'right-sidebar' ) ? 'lg:col-span-9' : ''; ?>">
            <?php
            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content/content', 'page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </div>

        <?php if ( $page_layout === 'right-sidebar' ) : ?>
            <aside id="secondary" class="widget-area lg:col-span-3">
                <?php get_sidebar(); ?>
            </aside>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_footer();