<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

// Get sidebar position
$sidebar_position = get_theme_mod('aqualuxe_search_sidebar_position', 'right');
$container_class = ($sidebar_position === 'none') ? 'container-fluid' : 'container';
$has_sidebar = ($sidebar_position !== 'none');
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <div class="row">
        <?php if ($has_sidebar && $sidebar_position === 'left') : ?>
            <div class="col-lg-4 col-md-12 sidebar-column">
                <?php get_sidebar('search'); ?>
            </div>
        <?php endif; ?>

        <div class="<?php echo $has_sidebar ? 'col-lg-8 col-md-12' : 'col-12'; ?> content-column">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">

                    <?php
                    if (have_posts()) :

                        // Fire the content before hook
                        do_action('aqualuxe_content_before');
                        ?>

                        <header class="page-header">
                            <h1 class="page-title">
                                <?php
                                /* translators: %s: search query. */
                                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                                ?>
                            </h1>
                            
                            <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
                                <div class="search-breadcrumbs">
                                    <?php aqualuxe_breadcrumbs(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="search-form-container">
                                <?php get_search_form(); ?>
                                
                                <?php if (get_theme_mod('aqualuxe_search_show_popular_terms', true)) : ?>
                                    <div class="popular-search-terms">
                                        <h3><?php esc_html_e('Popular Searches:', 'aqualuxe'); ?></h3>
                                        <?php
                                        // Get popular search terms
                                        $popular_terms = get_terms(array(
                                            'taxonomy' => 'post_tag',
                                            'orderby' => 'count',
                                            'order' => 'DESC',
                                            'number' => 5,
                                        ));
                                        
                                        if (!empty($popular_terms) && !is_wp_error($popular_terms)) :
                                            echo '<ul class="popular-terms-list">';
                                            foreach ($popular_terms as $term) :
                                                echo '<li><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></li>';
                                            endforeach;
                                            echo '</ul>';
                                        endif;
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </header><!-- .page-header -->

                        <?php
                        // Get the blog layout
                        $blog_layout = get_theme_mod('aqualuxe_search_layout', 'list');
                        $blog_columns = get_theme_mod('aqualuxe_search_columns', 2);

                        // Add blog layout class
                        echo '<div class="blog-layout blog-layout-' . esc_attr($blog_layout) . ' blog-columns-' . esc_attr($blog_columns) . '">';

                        // Add filter options
                        ?>
                        <div class="search-filters">
                            <div class="search-filter-count">
                                <?php
                                $results_count = $wp_query->found_posts;
                                printf(
                                    /* translators: %d: number of search results */
                                    _n(
                                        '%d result found',
                                        '%d results found',
                                        $results_count,
                                        'aqualuxe'
                                    ),
                                    $results_count
                                );
                                ?>
                            </div>
                            
                            <div class="search-filter-sort">
                                <label for="search-sort"><?php esc_html_e('Sort by:', 'aqualuxe'); ?></label>
                                <select id="search-sort" class="search-sort-select">
                                    <option value="relevance"><?php esc_html_e('Relevance', 'aqualuxe'); ?></option>
                                    <option value="date-desc"><?php esc_html_e('Newest first', 'aqualuxe'); ?></option>
                                    <option value="date-asc"><?php esc_html_e('Oldest first', 'aqualuxe'); ?></option>
                                    <option value="title-asc"><?php esc_html_e('Title (A-Z)', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <div class="search-filter-view">
                                <button class="view-switch view-grid <?php echo $blog_layout === 'grid' ? 'active' : ''; ?>" data-view="grid">
                                    <span class="screen-reader-text"><?php esc_html_e('Grid View', 'aqualuxe'); ?></span>
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="view-switch view-list <?php echo $blog_layout === 'list' ? 'active' : ''; ?>" data-view="list">
                                    <span class="screen-reader-text"><?php esc_html_e('List View', 'aqualuxe'); ?></span>
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>

                        <div class="search-results-container">
                            <?php
                            // Start the Loop
                            while (have_posts()) :
                                the_post();

                                /**
                                 * Run the loop for the search to output the results.
                                 * If you want to overload this in a child theme then include a file
                                 * called content-search.php and that will be used instead.
                                 */
                                get_template_part('templates/content', 'search');

                            endwhile;
                            ?>
                        </div>

                        <?php
                        echo '</div><!-- .blog-layout -->';

                        // Pagination
                        if (get_theme_mod('aqualuxe_search_pagination_type', 'numbered') === 'infinite') :
                            // Infinite scroll
                            echo '<div class="aqualuxe-infinite-scroll-loader" style="display: none;">
                                <div class="aqualuxe-loader"></div>
                                <p>' . esc_html__('Loading more results...', 'aqualuxe') . '</p>
                            </div>';
                        else :
                            // Standard pagination
                            the_posts_pagination(array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                                'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
                                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                            ));
                        endif;

                        // Related searches
                        if (get_theme_mod('aqualuxe_search_show_related', true)) :
                            $search_query = get_search_query();
                            $related_args = array(
                                's' => '',
                                'posts_per_page' => 5,
                                'post_type' => 'post',
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => '_aqualuxe_related_searches',
                                        'value' => $search_query,
                                        'compare' => 'LIKE'
                                    )
                                )
                            );
                            
                            $related_query = new WP_Query($related_args);
                            
                            if ($related_query->have_posts()) :
                                ?>
                                <div class="related-searches">
                                    <h3><?php esc_html_e('Related Searches', 'aqualuxe'); ?></h3>
                                    <ul class="related-searches-list">
                                        <?php
                                        while ($related_query->have_posts()) :
                                            $related_query->the_post();
                                            echo '<li><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></li>';
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </ul>
                                </div>
                                <?php
                            endif;
                        endif;

                        // Fire the content after hook
                        do_action('aqualuxe_content_after');

                    else :

                        get_template_part('templates/content', 'none');

                    endif;
                    ?>

                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .content-column -->

        <?php if ($has_sidebar && $sidebar_position === 'right') : ?>
            <div class="col-lg-4 col-md-12 sidebar-column">
                <?php get_sidebar('search'); ?>
            </div>
        <?php endif; ?>
    </div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();