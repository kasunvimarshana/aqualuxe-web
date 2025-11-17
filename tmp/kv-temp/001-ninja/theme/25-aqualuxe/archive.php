<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4">
        <?php
        // Get the archive layout from theme options
        $archive_layout = get_theme_mod('aqualuxe_archive_layout', 'right-sidebar');

        // Determine if we should show the sidebar
        $show_sidebar = ($archive_layout === 'right-sidebar' || $archive_layout === 'left-sidebar') && is_active_sidebar('sidebar-1');
        
        // Set the content width class based on layout
        $content_class = $show_sidebar ? 'lg:w-2/3' : 'w-full';
        
        // Set the content order class based on layout
        $content_order = $archive_layout === 'left-sidebar' ? 'lg:order-2' : 'lg:order-1';
        
        // Set the sidebar order class based on layout
        $sidebar_order = $archive_layout === 'left-sidebar' ? 'lg:order-1' : 'lg:order-2';
        ?>

        <div class="flex flex-wrap lg:flex-nowrap <?php echo $archive_layout === 'left-sidebar' ? 'flex-row-reverse' : ''; ?>">
            <div class="w-full <?php echo esc_attr($content_class); ?> <?php echo esc_attr($content_order); ?>">
                <?php if (have_posts()) : ?>

                    <header class="page-header mb-8">
                        <?php
                        the_archive_title('<h1 class="page-title text-3xl font-bold mb-2">', '</h1>');
                        the_archive_description('<div class="archive-description text-gray-600">', '</div>');
                        ?>
                    </header><!-- .page-header -->

                    <?php
                    // Get the archive display style from theme options
                    $archive_style = get_theme_mod('aqualuxe_archive_style', 'list');
                    
                    if ($archive_style === 'grid') {
                        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
                    }
                    
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('template-parts/content/content', $archive_style);

                    endwhile;
                    
                    if ($archive_style === 'grid') {
                        echo '</div>';
                    }

                    aqualuxe_pagination();

                else :

                    get_template_part('template-parts/content/content', 'none');

                endif;
                ?>
            </div>

            <?php if ($show_sidebar) : ?>
                <div class="w-full lg:w-1/3 mt-8 lg:mt-0 <?php echo esc_attr($sidebar_order); ?> <?php echo $archive_layout === 'left-sidebar' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();