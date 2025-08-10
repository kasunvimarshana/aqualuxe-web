<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4">
        <?php
        // Check if the post has a custom layout setting
        $post_layout = get_post_meta(get_the_ID(), '_aqualuxe_post_layout', true);
        if (!$post_layout) {
            $post_layout = get_theme_mod('aqualuxe_default_post_layout', 'right-sidebar');
        }

        // Determine if we should show the sidebar
        $show_sidebar = ($post_layout === 'right-sidebar' || $post_layout === 'left-sidebar') && is_active_sidebar('sidebar-1');
        
        // Set the content width class based on layout
        $content_class = $show_sidebar ? 'lg:w-2/3' : 'w-full';
        
        // Set the content order class based on layout
        $content_order = $post_layout === 'left-sidebar' ? 'lg:order-2' : 'lg:order-1';
        
        // Set the sidebar order class based on layout
        $sidebar_order = $post_layout === 'left-sidebar' ? 'lg:order-1' : 'lg:order-2';
        ?>

        <div class="flex flex-wrap lg:flex-nowrap <?php echo $post_layout === 'left-sidebar' ? 'flex-row-reverse' : ''; ?>">
            <div class="w-full <?php echo esc_attr($content_class); ?> <?php echo esc_attr($content_order); ?>">
                <?php
                while (have_posts()) :
                    the_post();

                    get_template_part('template-parts/content/content', 'single');

                    // Post navigation
                    the_post_navigation(
                        array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                        )
                    );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </div>

            <?php if ($show_sidebar) : ?>
                <div class="w-full lg:w-1/3 mt-8 lg:mt-0 <?php echo esc_attr($sidebar_order); ?> <?php echo $post_layout === 'left-sidebar' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();