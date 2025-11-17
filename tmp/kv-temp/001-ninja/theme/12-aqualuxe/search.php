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
?>

<main id="primary" class="site-main">

    <?php
    // Search header
    $search_header_bg = get_theme_mod('aqualuxe_search_header_bg', '');
    ?>

    <section class="page-header-wrapper" <?php if (!empty($search_header_bg)) : ?>style="background-image: url('<?php echo esc_url($search_header_bg); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="page-header text-center">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query. */
                    printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
                
                <?php
                if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                    aqualuxe_breadcrumbs();
                }
                ?>
            </div>
        </div>
    </section>

    <section class="search-results-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="search-form-wrapper" data-aos="fade-up">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <?php if (have_posts()) : ?>
                        <div class="search-results-count" data-aos="fade-up">
                            <?php
                            $found_posts = $wp_query->found_posts;
                            printf(
                                /* translators: %d: number of search results */
                                _n(
                                    'Found %d result for your search',
                                    'Found %d results for your search',
                                    $found_posts,
                                    'aqualuxe'
                                ),
                                $found_posts
                            );
                            ?>
                        </div>
                        
                        <div class="search-results-list" data-aos="fade-up">
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
                            
                            // Pagination
                            the_posts_pagination(array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Previous', 'aqualuxe'),
                                'next_text' => esc_html__('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
                                'screen_reader_text' => esc_html__('Posts navigation', 'aqualuxe'),
                            ));
                            ?>
                        </div>
                    <?php
                    else :
                        ?>
                        <div class="no-results" data-aos="fade-up">
                            <h2><?php esc_html_e('No Results Found', 'aqualuxe'); ?></h2>
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                            
                            <div class="search-suggestions">
                                <h3><?php esc_html_e('Search Suggestions:', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Check your spelling.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Try more general keywords.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Try different keywords.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Try fewer keywords.', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="popular-searches">
                                <h3><?php esc_html_e('Popular Categories:', 'aqualuxe'); ?></h3>
                                <ul>
                                    <?php
                                    wp_list_categories(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'show_count' => true,
                                        'title_li' => '',
                                        'number' => 5
                                    ));
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-lg-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();