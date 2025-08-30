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
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
            
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>
        </header><!-- .page-header -->

        <div class="row">
            <?php
            // Determine layout
            $layout = aqualuxe_get_archive_layout();
            $content_class = 'content-area';
            
            if ($layout === 'left-sidebar') {
                $content_class .= ' col-lg-8 col-lg-push-4';
            } elseif ($layout === 'right-sidebar') {
                $content_class .= ' col-lg-8';
            } else {
                $content_class .= ' col-lg-12';
            }
            ?>

            <div class="<?php echo esc_attr($content_class); ?>">
                <?php if (have_posts()) : ?>
                    <div class="search-filters">
                        <div class="search-result-count">
                            <?php
                            /* translators: %d: number of search results. */
                            printf(
                                esc_html(_n('%d result found', '%d results found', $wp_query->found_posts, 'aqualuxe')),
                                number_format_i18n($wp_query->found_posts)
                            );
                            ?>
                        </div>
                        
                        <div class="search-filter-options">
                            <form id="search-filter-form" class="search-filter-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
                                
                                <div class="filter-item">
                                    <label for="search-post-type"><?php echo esc_html__('Filter by:', 'aqualuxe'); ?></label>
                                    <select id="search-post-type" name="post_type" class="form-control">
                                        <option value="any" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : 'any', 'any'); ?>>
                                            <?php echo esc_html__('All Content', 'aqualuxe'); ?>
                                        </option>
                                        <option value="post" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'post'); ?>>
                                            <?php echo esc_html__('Blog Posts', 'aqualuxe'); ?>
                                        </option>
                                        <option value="page" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'page'); ?>>
                                            <?php echo esc_html__('Pages', 'aqualuxe'); ?>
                                        </option>
                                        <?php if (post_type_exists('service')) : ?>
                                            <option value="service" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'service'); ?>>
                                                <?php echo esc_html__('Services', 'aqualuxe'); ?>
                                            </option>
                                        <?php endif; ?>
                                        <?php if (post_type_exists('project')) : ?>
                                            <option value="project" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'project'); ?>>
                                                <?php echo esc_html__('Projects', 'aqualuxe'); ?>
                                            </option>
                                        <?php endif; ?>
                                        <?php if (post_type_exists('event')) : ?>
                                            <option value="event" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'event'); ?>>
                                                <?php echo esc_html__('Events', 'aqualuxe'); ?>
                                            </option>
                                        <?php endif; ?>
                                        <?php if (post_type_exists('faq')) : ?>
                                            <option value="faq" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'faq'); ?>>
                                                <?php echo esc_html__('FAQs', 'aqualuxe'); ?>
                                            </option>
                                        <?php endif; ?>
                                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                            <option value="product" <?php selected(isset($_GET['post_type']) ? $_GET['post_type'] : '', 'product'); ?>>
                                                <?php echo esc_html__('Products', 'aqualuxe'); ?>
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="filter-item">
                                    <label for="search-orderby"><?php echo esc_html__('Sort by:', 'aqualuxe'); ?></label>
                                    <select id="search-orderby" name="orderby" class="form-control">
                                        <option value="relevance" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : 'relevance', 'relevance'); ?>>
                                            <?php echo esc_html__('Relevance', 'aqualuxe'); ?>
                                        </option>
                                        <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>>
                                            <?php echo esc_html__('Newest First', 'aqualuxe'); ?>
                                        </option>
                                        <option value="title" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title'); ?>>
                                            <?php echo esc_html__('Title (A-Z)', 'aqualuxe'); ?>
                                        </option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm"><?php echo esc_html__('Apply', 'aqualuxe'); ?></button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="search-results-list">
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
                        ?>
                    </div>

                    <?php
                    // Pagination
                    aqualuxe_pagination();

                else :

                    get_template_part('template-parts/content/content', 'none');

                endif;
                ?>
            </div><!-- .content-area -->

            <?php
            // Display sidebar if layout is not full-width
            if ($layout !== 'full-width') {
                $sidebar_class = 'widget-area';
                
                if ($layout === 'left-sidebar') {
                    $sidebar_class .= ' col-lg-4 col-lg-pull-8';
                } else {
                    $sidebar_class .= ' col-lg-4';
                }
                ?>
                <aside id="secondary" class="<?php echo esc_attr($sidebar_class); ?>">
                    <?php get_sidebar(); ?>
                </aside><!-- .widget-area -->
                <?php
            }
            ?>
        </div><!-- .row -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();