<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Archive options
$archive_layout = isset($options['archive_layout']) ? $options['archive_layout'] : 'right-sidebar';
$archive_display = isset($options['archive_display']) ? $options['archive_display'] : 'grid';
$grid_columns = isset($options['grid_columns']) ? $options['grid_columns'] : '3';
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$show_post_meta = isset($options['show_post_meta']) ? $options['show_post_meta'] : true;
$excerpt_length = isset($options['excerpt_length']) ? $options['excerpt_length'] : 55;
$read_more_text = isset($options['read_more_text']) ? $options['read_more_text'] : __('Read More', 'aqualuxe');
$pagination_style = isset($options['pagination_style']) ? $options['pagination_style'] : 'numbered';

// Set layout classes
$content_class = 'content-area';
$sidebar_class = 'sidebar-area';

if ($archive_layout === 'left-sidebar') {
    $content_class .= ' has-left-sidebar';
    $sidebar_class .= ' left-sidebar';
} elseif ($archive_layout === 'right-sidebar') {
    $content_class .= ' has-right-sidebar';
    $sidebar_class .= ' right-sidebar';
} elseif ($archive_layout === 'no-sidebar') {
    $content_class .= ' no-sidebar';
} elseif ($archive_layout === 'full-width') {
    $content_class .= ' full-width';
}

// Set archive display classes
$archive_class = 'posts-archive';
$archive_class .= ' archive-' . $archive_display;
if ($archive_display === 'grid' || $archive_display === 'masonry') {
    $archive_class .= ' columns-' . $grid_columns;
}

get_header();
?>

<div class="container">
    <div class="content-sidebar-wrap">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php if (have_posts()) : ?>
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header><!-- .page-header -->

                <div class="<?php echo esc_attr($archive_class); ?>">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/content/content', get_post_type());

                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination
                if ($pagination_style === 'numbered') {
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => '<span class="icon-arrow-left"></span><span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span>',
                        'next_text' => '<span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span><span class="icon-arrow-right"></span>',
                    ));
                } elseif ($pagination_style === 'prev_next') {
                    the_posts_navigation(array(
                        'prev_text' => '<span class="icon-arrow-left"></span> ' . __('Older Posts', 'aqualuxe'),
                        'next_text' => __('Newer Posts', 'aqualuxe') . ' <span class="icon-arrow-right"></span>',
                    ));
                } elseif ($pagination_style === 'load_more' || $pagination_style === 'infinite') {
                    echo '<div class="aqualuxe-pagination ' . esc_attr($pagination_style) . '">';
                    if ($pagination_style === 'load_more') {
                        echo '<button class="load-more-button">' . esc_html__('Load More', 'aqualuxe') . '</button>';
                    }
                    echo '<div class="page-load-status">
                        <div class="infinite-scroll-request loading-spinner"></div>
                        <p class="infinite-scroll-last">' . esc_html__('No more posts to load', 'aqualuxe') . '</p>
                        <p class="infinite-scroll-error">' . esc_html__('Error loading posts', 'aqualuxe') . '</p>
                    </div>';
                    echo '</div>';
                }
                ?>

            <?php else : ?>
                <?php get_template_part('templates/content/content', 'none'); ?>
            <?php endif; ?>
        </main><!-- #primary -->

        <?php if ($archive_layout === 'left-sidebar' || $archive_layout === 'right-sidebar') : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php
get_footer();