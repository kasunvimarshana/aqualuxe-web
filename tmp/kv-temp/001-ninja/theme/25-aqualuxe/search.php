<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4">
        <?php
        // Get the search layout from theme options
        $search_layout = get_theme_mod('aqualuxe_search_layout', 'right-sidebar');

        // Determine if we should show the sidebar
        $show_sidebar = ($search_layout === 'right-sidebar' || $search_layout === 'left-sidebar') && is_active_sidebar('sidebar-1');
        
        // Set the content width class based on layout
        $content_class = $show_sidebar ? 'lg:w-2/3' : 'w-full';
        
        // Set the content order class based on layout
        $content_order = $search_layout === 'left-sidebar' ? 'lg:order-2' : 'lg:order-1';
        
        // Set the sidebar order class based on layout
        $sidebar_order = $search_layout === 'left-sidebar' ? 'lg:order-1' : 'lg:order-2';
        ?>

        <div class="flex flex-wrap lg:flex-nowrap <?php echo $search_layout === 'left-sidebar' ? 'flex-row-reverse' : ''; ?>">
            <div class="w-full <?php echo esc_attr($content_class); ?> <?php echo esc_attr($content_order); ?>">
                <?php if (have_posts()) : ?>

                    <header class="page-header mb-8">
                        <h1 class="page-title text-3xl font-bold mb-2">
                            <?php
                            /* translators: %s: search query. */
                            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                            ?>
                        </h1>
                    </header><!-- .page-header -->

                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part('template-parts/content/content', 'search');

                    endwhile;

                    aqualuxe_pagination();

                else :

                    get_template_part('template-parts/content/content', 'none');

                endif;
                ?>
            </div>

            <?php if ($show_sidebar) : ?>
                <div class="w-full lg:w-1/3 mt-8 lg:mt-0 <?php echo esc_attr($sidebar_order); ?> <?php echo $search_layout === 'left-sidebar' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();