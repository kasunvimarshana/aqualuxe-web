<?php
/**
 * Homepage Blog Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_blog_title', __('Latest Articles', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_blog_description', __('Tips, guides, and news from the world of aquatics.', 'aqualuxe'));
$posts_count = get_theme_mod('aqualuxe_blog_count', 3);
$posts_columns = get_theme_mod('aqualuxe_blog_columns', 3);
$show_button = get_theme_mod('aqualuxe_blog_show_button', true);
$button_text = get_theme_mod('aqualuxe_blog_button_text', __('View All Articles', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_blog_button_url', get_permalink(get_option('page_for_posts')));
$category = get_theme_mod('aqualuxe_blog_category', 0);

// Get latest posts
$args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_count,
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
);

// Add category filter if set
if ($category) {
    $args['cat'] = $category;
}

$posts = new WP_Query($args);

// Only show section if posts exist
if ($posts->have_posts()) :
?>

<section class="blog-section py-16">
    <div class="container">
        <?php if ($section_title || $section_description) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_description) : ?>
                    <p class="section-description text-lg text-gray-600 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr($posts_columns); ?> gap-8">
            <?php
            while ($posts->have_posts()) :
                $posts->the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('blog-item bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-105'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="blog-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-auto')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-content p-6">
                        <div class="blog-meta text-sm text-gray-500 mb-2">
                            <?php
                            // Get categories
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<span class="blog-category">';
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-primary hover:text-secondary">' . esc_html($categories[0]->name) . '</a>';
                                echo '</span>';
                                echo '<span class="mx-2">•</span>';
                            }
                            ?>
                            <span class="blog-date"><?php echo get_the_date(); ?></span>
                        </div>
                        
                        <h3 class="blog-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="blog-excerpt text-gray-600 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-secondary">
                            <?php esc_html_e('Read More', 'aqualuxe'); ?> <i class="fa fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <?php if ($show_button && $button_url) : ?>
            <div class="text-center mt-10">
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();
endif;