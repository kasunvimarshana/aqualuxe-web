<?php
/**
 * Homepage Latest Posts Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_blog_title', __('Latest Articles', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_blog_subtitle', __('Stay updated with our latest news and tips', 'aqualuxe'));
$number_of_posts = get_theme_mod('aqualuxe_homepage_blog_number', 3);
$columns = get_theme_mod('aqualuxe_homepage_blog_columns', 3);
$show_section = get_theme_mod('aqualuxe_homepage_blog_show', true);
$view_all_text = get_theme_mod('aqualuxe_homepage_blog_view_all', __('View All Articles', 'aqualuxe'));
$view_all_url = get_theme_mod('aqualuxe_homepage_blog_view_all_url', get_permalink(get_option('page_for_posts')));
$show_excerpt = get_theme_mod('aqualuxe_homepage_blog_show_excerpt', true);
$excerpt_length = get_theme_mod('aqualuxe_homepage_blog_excerpt_length', 20);
$show_date = get_theme_mod('aqualuxe_homepage_blog_show_date', true);
$show_author = get_theme_mod('aqualuxe_homepage_blog_show_author', true);
$show_category = get_theme_mod('aqualuxe_homepage_blog_show_category', true);
$read_more_text = get_theme_mod('aqualuxe_homepage_blog_read_more', __('Read More', 'aqualuxe'));

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set up query args
$args = array(
    'post_type' => 'post',
    'posts_per_page' => $number_of_posts,
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
);

// Get posts
$posts_query = new WP_Query($args);

// Exit if no posts
if (!$posts_query->have_posts()) {
    return;
}

// Set column class
$column_class = '';
switch ($columns) {
    case 1:
        $column_class = 'grid-cols-1';
        break;
    case 2:
        $column_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
    default:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        break;
    case 4:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
}

// Custom excerpt function
function aqualuxe_custom_excerpt($length) {
    $excerpt = get_the_excerpt();
    $excerpt = wp_strip_all_tags($excerpt);
    $words = explode(' ', $excerpt, $length + 1);
    
    if (count($words) > $length) {
        array_pop($words);
        $excerpt = implode(' ', $words) . '...';
    } else {
        $excerpt = implode(' ', $words);
    }
    
    return $excerpt;
}
?>

<section class="aqualuxe-latest-posts py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="grid <?php echo esc_attr($column_class); ?> gap-8">
            <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="aspect-w-16 aspect-h-9">
                                <?php the_post_thumbnail('medium_large', ['class' => 'object-cover w-full h-full']); ?>
                            </div>
                        </a>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <?php if ($show_category) : ?>
                            <div class="mb-2">
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    $category = $categories[0];
                                ?>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="text-xs font-semibold uppercase tracking-wider text-primary-600 hover:text-primary-700">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ($show_excerpt) : ?>
                            <div class="text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo aqualuxe_custom_excerpt($excerpt_length); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <?php if ($show_date) : ?>
                                    <span class="mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($show_author) : ?>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php the_author(); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($read_more_text) : ?>
                                <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                                    <?php echo esc_html($read_more_text); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ($view_all_text && $view_all_url) : ?>
            <div class="text-center mt-12">
                <a href="<?php echo esc_url($view_all_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($view_all_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>