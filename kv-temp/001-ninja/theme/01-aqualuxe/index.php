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

$sidebar_position = get_theme_mod('aqualuxe_blog_sidebar_position', 'right');
$container_class = 'container mx-auto px-4 py-12';
$content_class = 'site-main';
$has_sidebar = is_active_sidebar('sidebar-blog') && $sidebar_position !== 'none';

if ($has_sidebar) {
    $content_class .= ' lg:w-2/3';
    if ($sidebar_position === 'left') {
        $content_class .= ' lg:order-2';
    } else {
        $content_class .= ' lg:order-1';
    }
}
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <?php
    if (is_home() && !is_front_page()) :
        $blog_title = get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe'));
        $blog_description = get_theme_mod('aqualuxe_blog_description', '');
    ?>
        <header class="page-header mb-8 text-center">
            <h1 class="page-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($blog_title); ?></h1>
            <?php if ($blog_description) : ?>
                <div class="archive-description max-w-2xl mx-auto">
                    <p><?php echo esc_html($blog_description); ?></p>
                </div>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php
            if (have_posts()) :

                /* Display the appropriate header when required. */
                if (is_search()) :
            ?>
                    <header class="page-header mb-8">
                        <h1 class="page-title text-2xl md:text-3xl font-bold">
                            <?php
                            /* translators: %s: search query. */
                            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                            ?>
                        </h1>
                    </header><!-- .page-header -->
                <?php
                endif;

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part('templates/components/content', get_post_type());

                endwhile;

                // Pagination
                if (get_theme_mod('aqualuxe_pagination_style', 'numbered') === 'numbered') :
                    aqualuxe_numeric_pagination();
                else :
                    the_posts_navigation(array(
                        'prev_text' => '<span class="nav-arrow">&larr;</span> ' . esc_html__('Older posts', 'aqualuxe'),
                        'next_text' => esc_html__('Newer posts', 'aqualuxe') . ' <span class="nav-arrow">&rarr;</span>',
                    ));
                endif;

            else :

                get_template_part('templates/components/content', 'none');

            endif;
            ?>
        </main><!-- #main -->

        <?php if ($has_sidebar) : ?>
            <aside id="secondary" class="widget-area lg:w-1/3 <?php echo $sidebar_position === 'left' ? 'lg:order-1' : 'lg:order-2'; ?> mt-8 lg:mt-0">
                <?php dynamic_sidebar('sidebar-blog'); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();