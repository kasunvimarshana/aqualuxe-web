<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

// Search options
$archive_layout = isset($options['archive_layout']) ? $options['archive_layout'] : 'right-sidebar';
$archive_display = isset($options['archive_display']) ? $options['archive_display'] : 'list'; // Default to list for search results
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
$archive_class = 'posts-archive search-results-archive';
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
                    <h1 class="page-title">
                        <?php
                        /* translators: %s: search query. */
                        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                        ?>
                    </h1>
                </header><!-- .page-header -->

                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>

                <div class="search-results-count">
                    <?php
                    $found_posts = $wp_query->found_posts;
                    printf(
                        /* translators: %d: number of search results */
                        _n(
                            'Found %d result',
                            'Found %d results',
                            $found_posts,
                            'aqualuxe'
                        ),
                        $found_posts
                    );
                    ?>
                </div>

                <div class="<?php echo esc_attr($archive_class); ?>">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part('templates/content/content', 'search');

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
                        'prev_text' => '<span class="icon-arrow-left"></span> ' . __('Older Results', 'aqualuxe'),
                        'next_text' => __('Newer Results', 'aqualuxe') . ' <span class="icon-arrow-right"></span>',
                    ));
                } elseif ($pagination_style === 'load_more' || $pagination_style === 'infinite') {
                    echo '<div class="aqualuxe-pagination ' . esc_attr($pagination_style) . '">';
                    if ($pagination_style === 'load_more') {
                        echo '<button class="load-more-button">' . esc_html__('Load More', 'aqualuxe') . '</button>';
                    }
                    echo '<div class="page-load-status">
                        <div class="infinite-scroll-request loading-spinner"></div>
                        <p class="infinite-scroll-last">' . esc_html__('No more results to load', 'aqualuxe') . '</p>
                        <p class="infinite-scroll-error">' . esc_html__('Error loading results', 'aqualuxe') . '</p>
                    </div>';
                    echo '</div>';
                }
                ?>

            <?php else : ?>
                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        /* translators: %s: search query. */
                        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                        ?>
                    </h1>
                </header><!-- .page-header -->

                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>

                <div class="no-results">
                    <h2><?php esc_html_e('No results found', 'aqualuxe'); ?></h2>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                    
                    <?php if (function_exists('aqualuxe_popular_searches')) : ?>
                        <div class="popular-searches">
                            <h3><?php esc_html_e('Popular Searches', 'aqualuxe'); ?></h3>
                            <?php aqualuxe_popular_searches(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main><!-- #primary -->

        <?php if ($archive_layout === 'left-sidebar' || $archive_layout === 'right-sidebar') : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php
get_footer();