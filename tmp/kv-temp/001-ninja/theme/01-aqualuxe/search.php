<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod('aqualuxe_search_sidebar_position', 'right');
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
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
            <?php
            /* translators: %s: search query. */
            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>');
            ?>
        </h1>
        
        <?php if (get_theme_mod('aqualuxe_enable_search_filter', true)) : ?>
        <div class="search-filters flex flex-wrap gap-4 mt-6">
            <a href="<?php echo esc_url(add_query_arg('post_type', 'post', remove_query_arg('post_type', $_SERVER['REQUEST_URI']))); ?>" class="px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 dark:hover:bg-primary-900 <?php echo !isset($_GET['post_type']) || $_GET['post_type'] === 'post' ? 'bg-primary-100 dark:bg-primary-900' : ''; ?>">
                <?php esc_html_e('Blog Posts', 'aqualuxe'); ?>
            </a>
            <?php if (class_exists('WooCommerce')) : ?>
            <a href="<?php echo esc_url(add_query_arg('post_type', 'product', remove_query_arg('post_type', $_SERVER['REQUEST_URI']))); ?>" class="px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 dark:hover:bg-primary-900 <?php echo isset($_GET['post_type']) && $_GET['post_type'] === 'product' ? 'bg-primary-100 dark:bg-primary-900' : ''; ?>">
                <?php esc_html_e('Products', 'aqualuxe'); ?>
            </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(add_query_arg('post_type', 'aqualuxe_service', remove_query_arg('post_type', $_SERVER['REQUEST_URI']))); ?>" class="px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 dark:hover:bg-primary-900 <?php echo isset($_GET['post_type']) && $_GET['post_type'] === 'aqualuxe_service' ? 'bg-primary-100 dark:bg-primary-900' : ''; ?>">
                <?php esc_html_e('Services', 'aqualuxe'); ?>
            </a>
            <a href="<?php echo esc_url(remove_query_arg('post_type', $_SERVER['REQUEST_URI'])); ?>" class="px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 dark:hover:bg-primary-900 <?php echo !isset($_GET['post_type']) ? 'bg-primary-100 dark:bg-primary-900' : ''; ?>">
                <?php esc_html_e('All Content', 'aqualuxe'); ?>
            </a>
        </div>
        <?php endif; ?>
    </header><!-- .page-header -->

    <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php
            if (have_posts()) :

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('templates/components/content', 'search');

                endwhile;

                // Pagination
                if (get_theme_mod('aqualuxe_pagination_style', 'numbered') === 'numbered') :
                    aqualuxe_numeric_pagination();
                else :
                    the_posts_navigation(array(
                        'prev_text' => '<span class="nav-arrow">&larr;</span> ' . esc_html__('Older results', 'aqualuxe'),
                        'next_text' => esc_html__('Newer results', 'aqualuxe') . ' <span class="nav-arrow">&rarr;</span>',
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