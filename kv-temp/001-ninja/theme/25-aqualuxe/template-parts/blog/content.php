<?php
/**
 * Template part for displaying the blog page content section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get blog settings from page meta or theme options
$posts_per_page = get_post_meta($page_id, '_aqualuxe_blog_posts_per_page', true);
if (!$posts_per_page) {
    $posts_per_page = get_theme_mod('aqualuxe_blog_posts_per_page', get_option('posts_per_page'));
}

$blog_layout = get_post_meta($page_id, '_aqualuxe_blog_layout', true);
if (!$blog_layout) {
    $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'right-sidebar');
}

$blog_style = get_post_meta($page_id, '_aqualuxe_blog_style', true);
if (!$blog_style) {
    $blog_style = get_theme_mod('aqualuxe_blog_style', 'grid');
}

$featured_category = get_post_meta($page_id, '_aqualuxe_blog_featured_category', true);
if (!$featured_category) {
    $featured_category = get_theme_mod('aqualuxe_blog_featured_category', '');
}

$excluded_categories = get_post_meta($page_id, '_aqualuxe_blog_excluded_categories', true);
if (!$excluded_categories) {
    $excluded_categories = get_theme_mod('aqualuxe_blog_excluded_categories', '');
}

// Determine if we should show the sidebar
$show_sidebar = ($blog_layout === 'right-sidebar' || $blog_layout === 'left-sidebar') && is_active_sidebar('sidebar-1');

// Set the content width class based on layout
$content_class = $show_sidebar ? 'lg:w-2/3' : 'w-full';

// Set the content order class based on layout
$content_order = $blog_layout === 'left-sidebar' ? 'lg:order-2' : 'lg:order-1';

// Set the sidebar order class based on layout
$sidebar_order = $blog_layout === 'left-sidebar' ? 'lg:order-1' : 'lg:order-2';

// Set the grid columns class based on style and sidebar
if ($blog_style === 'grid') {
    $grid_class = $show_sidebar ? 'md:grid-cols-2' : 'md:grid-cols-2 lg:grid-cols-3';
} else {
    $grid_class = 'grid-cols-1';
}

// Get the current page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Build the query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
);

// Add category filters if set
if ($featured_category) {
    $args['cat'] = $featured_category;
}

if ($excluded_categories) {
    $args['category__not_in'] = explode(',', $excluded_categories);
}

// Create a new query
$blog_query = new WP_Query($args);
?>

<section class="blog-content-section py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap lg:flex-nowrap <?php echo $blog_layout === 'left-sidebar' ? 'flex-row-reverse' : ''; ?>">
            <div class="w-full <?php echo esc_attr($content_class); ?> <?php echo esc_attr($content_order); ?>">
                <?php if ($blog_query->have_posts()) : ?>
                    
                    <?php if ($blog_style === 'grid') : ?>
                        <div class="blog-grid grid <?php echo esc_attr($grid_class); ?> gap-8">
                            <?php
                            while ($blog_query->have_posts()) :
                                $blog_query->the_post();
                                get_template_part('template-parts/content/content', 'grid');
                            endwhile;
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="blog-list space-y-12">
                            <?php
                            while ($blog_query->have_posts()) :
                                $blog_query->the_post();
                                get_template_part('template-parts/content/content');
                            endwhile;
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pagination-container mt-12">
                        <?php
                        // Custom pagination function from template-functions.php
                        aqualuxe_pagination($blog_query);
                        ?>
                    </div>
                    
                    <?php wp_reset_postdata(); ?>
                    
                <?php else : ?>
                    <div class="no-posts">
                        <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($show_sidebar) : ?>
                <div class="w-full lg:w-1/3 mt-8 lg:mt-0 <?php echo esc_attr($sidebar_order); ?> <?php echo $blog_layout === 'left-sidebar' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>