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
 */

get_header();

$sidebar_position = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'standard' );
$container_class = 'container mx-auto px-4';
$content_class = 'site-content py-12';

if ( $sidebar_position === 'none' ) {
    $content_class .= ' max-w-4xl mx-auto';
} else {
    $content_class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';
}

// Set blog layout classes
$blog_container_class = '';
if ( $blog_layout === 'grid' ) {
    $blog_container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8';
} elseif ( $blog_layout === 'masonry' ) {
    $blog_container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8 masonry-grid';
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
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header class="page-header mb-8">
                        <h1 class="page-title text-3xl font-bold text-gray-900 dark:text-gray-100"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;
                ?>

                <div class="<?php echo esc_attr( $blog_container_class ); ?>">
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