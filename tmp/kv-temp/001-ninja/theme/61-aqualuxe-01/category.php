<?php
/**
 * The template for displaying category archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

// Get sidebar position
$sidebar_position = get_theme_mod('aqualuxe_category_sidebar_position', 'right');
$container_class = ($sidebar_position === 'none') ? 'container-fluid' : 'container';
$has_sidebar = ($sidebar_position !== 'none');

// Get current category
$category = get_queried_object();
$category_id = $category->term_id;

// Get category options
$category_layout = get_term_meta($category_id, 'aqualuxe_category_layout', true);
$category_columns = get_term_meta($category_id, 'aqualuxe_category_columns', true);
$category_featured_image = get_term_meta($category_id, 'aqualuxe_category_image', true);

// Use default if not set
if (empty($category_layout)) {
    $category_layout = get_theme_mod('aqualuxe_category_layout', 'grid');
}

if (empty($category_columns)) {
    $category_columns = get_theme_mod('aqualuxe_category_columns', 3);
}
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <div class="row">
        <?php if ($has_sidebar && $sidebar_position === 'left') : ?>
            <div class="col-lg-4 col-md-12 sidebar-column">
                <?php get_sidebar('category'); ?>
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

                        <header class="page-header category-header">
                            <?php if (!empty($category_featured_image)) : ?>
                                <div class="category-featured-image">
                                    <?php echo wp_get_attachment_image($category_featured_image, 'full'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="category-header-content">
                                <?php
                                the_archive_title('<h1 class="page-title">', '</h1>');
                                the_archive_description('<div class="archive-description">', '</div>');
                                ?>
                                
                                <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
                                    <div class="category-breadcrumbs">
                                        <?php aqualuxe_breadcrumbs(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display subcategories if they exist
                                $subcategories = get_terms(array(
                                    'taxonomy' => 'category',
                                    'parent' => $category_id,
                                    'hide_empty' => true,
                                ));
                                
                                if (!empty($subcategories) && !is_wp_error($subcategories)) :
                                ?>
                                    <div class="category-subcategories">
                                        <h3><?php esc_html_e('Subcategories', 'aqualuxe'); ?></h3>
                                        <ul class="subcategories-list">
                                            <?php foreach ($subcategories as $subcategory) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url(get_term_link($subcategory)); ?>" class="subcategory-link">
                                                        <?php echo esc_html($subcategory->name); ?>
                                                        <span class="count">(<?php echo esc_html($subcategory->count); ?>)</span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </header><!-- .page-header -->

                        <?php
                        // Add blog layout class
                        echo '<div class="blog-layout blog-layout-' . esc_attr($category_layout) . ' blog-columns-' . esc_attr($category_columns) . '">';

                        // Add filter options if enabled
                        if (get_theme_mod('aqualuxe_category_filters', true)) :
                            ?>
                            <div class="category-filters">
                                <div class="category-filter-sort">
                                    <label for="category-sort"><?php esc_html_e('Sort by:', 'aqualuxe'); ?></label>
                                    <select id="category-sort" class="category-sort-select">
                                        <option value="date-desc"><?php esc_html_e('Newest first', 'aqualuxe'); ?></option>
                                        <option value="date-asc"><?php esc_html_e('Oldest first', 'aqualuxe'); ?></option>
                                        <option value="title-asc"><?php esc_html_e('Title (A-Z)', 'aqualuxe'); ?></option>
                                        <option value="title-desc"><?php esc_html_e('Title (Z-A)', 'aqualuxe'); ?></option>
                                        <option value="comment-count"><?php esc_html_e('Most commented', 'aqualuxe'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="category-filter-view">
                                    <button class="view-switch view-grid <?php echo $category_layout === 'grid' ? 'active' : ''; ?>" data-view="grid">
                                        <span class="screen-reader-text"><?php esc_html_e('Grid View', 'aqualuxe'); ?></span>
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button class="view-switch view-list <?php echo $category_layout === 'list' ? 'active' : ''; ?>" data-view="list">
                                        <span class="screen-reader-text"><?php esc_html_e('List View', 'aqualuxe'); ?></span>
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="category-posts-container">
                            <?php
                            // Start the Loop
                            while (have_posts()) :
                                the_post();

                                /*
                                * Include the Post-Type-specific template for the content.
                                * If you want to override this in a child theme, then include a file
                                * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                */
                                get_template_part('templates/content', get_post_type());

                            endwhile;
                            ?>
                        </div>

                        <?php
                        echo '</div><!-- .blog-layout -->';

                        // Pagination
                        if (get_theme_mod('aqualuxe_category_pagination_type', 'numbered') === 'infinite') :
                            // Infinite scroll
                            echo '<div class="aqualuxe-infinite-scroll-loader" style="display: none;">
                                <div class="aqualuxe-loader"></div>
                                <p>' . esc_html__('Loading more posts...', 'aqualuxe') . '</p>
                            </div>';
                        else :
                            // Standard pagination
                            the_posts_pagination(array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                                'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
                                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                            ));
                        endif;

                        // Fire the content after hook
                        do_action('aqualuxe_content_after');

                    else :

                        get_template_part('templates/content', 'none');

                    endif;
                    ?>

                </main><!-- #main -->
                
                <?php if (is_active_sidebar('category-bottom')) : ?>
                    <div class="category-bottom-widgets">
                        <?php dynamic_sidebar('category-bottom'); ?>
                    </div>
                <?php endif; ?>
            </div><!-- #primary -->
        </div><!-- .content-column -->

        <?php if ($has_sidebar && $sidebar_position === 'right') : ?>
            <div class="col-lg-4 col-md-12 sidebar-column">
                <?php get_sidebar('category'); ?>
            </div>
        <?php endif; ?>
    </div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();