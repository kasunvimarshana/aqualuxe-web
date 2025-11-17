<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod( 'aqualuxe_archive_sidebar', 'right' );
$archive_layout = get_theme_mod( 'aqualuxe_archive_layout', 'standard' );
$container_class = 'container mx-auto px-4';
$content_class = 'site-content py-12';

if ( $sidebar_position === 'none' ) {
    $content_class .= ' max-w-4xl mx-auto';
} else {
    $content_class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';
}

// Set archive layout classes
$archive_container_class = '';
if ( $archive_layout === 'grid' ) {
    $archive_container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8';
} elseif ( $archive_layout === 'masonry' ) {
    $archive_container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8 masonry-grid';
} elseif ( $archive_layout === 'list' ) {
    $archive_container_class = 'space-y-12';
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
            <?php if ( have_posts() ) : ?>

                <header class="page-header mb-8">
                    <?php
                    the_archive_title( '<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-gray-100">', '</h1>' );
                    the_archive_description( '<div class="archive-description prose dark:prose-invert max-w-none mt-4">', '</div>' );
                    ?>
                </header><!-- .page-header -->

                <div class="<?php echo esc_attr( $archive_container_class ); ?>">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part( 'template-parts/content/content', get_post_type() );

                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination
                aqualuxe_pagination();

            else :

                get_template_part( 'template-parts/content/content', 'none' );

            endif;
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