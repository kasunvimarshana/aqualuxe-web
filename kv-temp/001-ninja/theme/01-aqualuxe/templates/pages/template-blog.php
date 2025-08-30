<?php
/**
 * Template Name: Blog Page
 *
 * This is the template that displays the blog page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

// Get blog display options
$blog_layout = get_post_meta(get_the_ID(), 'blog_layout', true);
if (!$blog_layout) {
    $blog_layout = 'grid'; // Default layout: grid or list
}

$posts_per_page = get_post_meta(get_the_ID(), 'blog_posts_per_page', true);
if (!$posts_per_page) {
    $posts_per_page = get_option('posts_per_page');
}

$sidebar_position = get_post_meta(get_the_ID(), 'blog_sidebar_position', true);
if (!$sidebar_position) {
    $sidebar_position = get_theme_mod('aqualuxe_blog_sidebar_position', 'right');
}

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

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Get blog category filter if set
$blog_category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Set up the query arguments
$args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
);

// Add category filter if set
if ($blog_category > 0) {
    $args['cat'] = $blog_category;
}

// Custom query
$blog_query = new WP_Query($args);
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'blog_hero_image', true);
    if (!$hero_image) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $hero_title = get_post_meta(get_the_ID(), 'blog_hero_title', true);
    if (!$hero_title) {
        $hero_title = get_the_title();
    }
    $hero_subtitle = get_post_meta(get_the_ID(), 'blog_hero_subtitle', true);
    ?>
    <section class="blog-hero relative py-16 bg-cover bg-center" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="<?php echo esc_attr($container_class); ?>">
        <?php
        // Category Filter
        $categories = get_categories();
        if (!empty($categories)) :
        ?>
        <div class="blog-filter mb-8 flex flex-wrap justify-center gap-3">
            <a href="<?php echo esc_url(remove_query_arg('category')); ?>" class="px-4 py-2 rounded-full <?php echo $blog_category === 0 ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200' : 'bg-gray-100 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-800 dark:text-gray-200'; ?>">
                <?php esc_html_e('All Categories', 'aqualuxe'); ?>
            </a>
            
            <?php foreach ($categories as $category) : ?>
                <a href="<?php echo esc_url(add_query_arg('category', $category->term_id)); ?>" class="px-4 py-2 rounded-full <?php echo $blog_category === $category->term_id ? 'bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200' : 'bg-gray-100 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-800 dark:text-gray-200'; ?>">
                    <?php echo esc_html($category->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
            <div class="<?php echo esc_attr($content_class); ?>">
                <?php if ($blog_query->have_posts()) : ?>
                    <?php if ($blog_layout === 'grid') : ?>
                        <div class="blog-grid grid md:grid-cols-2 lg:grid-cols-<?php echo $has_sidebar ? '2' : '3'; ?> gap-8">
                            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                                <article class="blog-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="blog-image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="blog-content p-6">
                                        <div class="blog-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            <span class="post-date">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span class="post-author">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <?php the_author(); ?>
                                            </span>
                                        </div>
                                        
                                        <h3 class="blog-title text-xl font-bold mb-2">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="blog-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                            <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else : // List layout ?>
                        <div class="blog-list space-y-8">
                            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                                <article class="blog-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                    <div class="flex flex-col md:flex-row">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="blog-image md:w-1/3">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="blog-content p-6 md:w-2/3">
                                            <div class="blog-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <span class="post-date">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <?php echo get_the_date(); ?>
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span class="post-author">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <?php the_author(); ?>
                                                </span>
                                                
                                                <?php if (has_category()) : ?>
                                                <span class="mx-2">•</span>
                                                <span class="post-categories">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                    <?php the_category(', '); ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <h3 class="blog-title text-xl font-bold mb-2">
                                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="blog-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                                <?php the_excerpt(); ?>
                                            </div>
                                            
                                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Pagination
                    $total_pages = $blog_query->max_num_pages;
                    
                    if ($total_pages > 1) :
                        $current_page = max(1, get_query_var('paged'));
                        
                        echo '<div class="pagination flex justify-center mt-12">';
                        echo '<div class="flex space-x-2">';
                        
                        // Previous page
                        if ($current_page > 1) :
                            echo '<a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-md">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
                            echo '</a>';
                        endif;
                        
                        // Page numbers
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        if ($start_page > 1) :
                            echo '<a href="' . esc_url(get_pagenum_link(1)) . '" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-md">1</a>';
                            if ($start_page > 2) :
                                echo '<span class="px-4 py-2">...</span>';
                            endif;
                        endif;
                        
                        for ($i = $start_page; $i <= $end_page; $i++) :
                            $is_current = $i === $current_page;
                            echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="px-4 py-2 ' . ($is_current ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900') . ' rounded-md">' . $i . '</a>';
                        endfor;
                        
                        if ($end_page < $total_pages) :
                            if ($end_page < $total_pages - 1) :
                                echo '<span class="px-4 py-2">...</span>';
                            endif;
                            echo '<a href="' . esc_url(get_pagenum_link($total_pages)) . '" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-md">' . $total_pages . '</a>';
                        endif;
                        
                        // Next page
                        if ($current_page < $total_pages) :
                            echo '<a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 rounded-md">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
                            echo '</a>';
                        endif;
                        
                        echo '</div>';
                        echo '</div>';
                    endif;
                    ?>

                <?php else : ?>
                    <div class="no-results bg-white dark:bg-gray-700 p-8 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No Posts Found', 'aqualuxe'); ?></h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6"><?php esc_html_e('Sorry, but no posts match your criteria. Please try again with different filters or check back later.', 'aqualuxe'); ?></p>
                        
                        <?php if ($blog_category > 0) : ?>
                            <a href="<?php echo esc_url(remove_query_arg('category')); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <?php esc_html_e('View All Posts', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <?php if ($has_sidebar) : ?>
                <aside id="secondary" class="widget-area lg:w-1/3 <?php echo $sidebar_position === 'left' ? 'lg:order-1' : 'lg:order-2'; ?> mt-8 lg:mt-0">
                    <?php dynamic_sidebar('sidebar-blog'); ?>
                </aside><!-- #secondary -->
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Featured Posts Section
    $show_featured = get_post_meta(get_the_ID(), 'blog_show_featured', true);
    $featured_title = get_post_meta(get_the_ID(), 'blog_featured_title', true);
    $featured_subtitle = get_post_meta(get_the_ID(), 'blog_featured_subtitle', true);
    
    if ($show_featured) :
    ?>
    <section class="featured-posts py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($featured_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($featured_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Featured Articles', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($featured_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($featured_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
            // Get featured posts
            $featured_args = array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'meta_key' => 'aqualuxe_featured_post',
                'meta_value' => '1',
            );
            
            $featured_query = new WP_Query($featured_args);
            
            // If no posts are explicitly marked as featured, get the most recent ones
            if (!$featured_query->have_posts()) {
                $featured_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                );
                $featured_query = new WP_Query($featured_args);
            }
            
            if ($featured_query->have_posts()) :
            ?>
                <div class="featured-grid grid md:grid-cols-3 gap-8">
                    <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                        <article class="featured-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="featured-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="featured-content p-6">
                                <div class="featured-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <span class="post-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                </div>
                                
                                <h3 class="featured-title text-xl font-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="featured-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Newsletter Section
    $show_newsletter = get_post_meta(get_the_ID(), 'blog_show_newsletter', true);
    $newsletter_title = get_post_meta(get_the_ID(), 'blog_newsletter_title', true);
    $newsletter_text = get_post_meta(get_the_ID(), 'blog_newsletter_text', true);
    $newsletter_shortcode = get_post_meta(get_the_ID(), 'blog_newsletter_shortcode', true);
    
    if ($show_newsletter) :
    ?>
    <section class="blog-newsletter py-16 bg-primary-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <?php if ($newsletter_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($newsletter_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($newsletter_text) : ?>
                    <p class="text-xl mb-6"><?php echo esc_html($newsletter_text); ?></p>
                <?php else : ?>
                    <p class="text-xl mb-6"><?php esc_html_e('Stay updated with our latest articles, news, and special offers.', 'aqualuxe'); ?></p>
                <?php endif; ?>
                
                <div class="newsletter-form">
                    <?php 
                    if ($newsletter_shortcode) {
                        echo do_shortcode($newsletter_shortcode);
                    } else {
                    ?>
                    <form class="flex flex-col sm:flex-row gap-4 justify-center">
                        <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="px-4 py-3 rounded-md w-full sm:w-auto flex-grow text-gray-900">
                        <button type="submit" class="bg-white text-primary-700 hover:bg-gray-100 px-6 py-3 rounded-md font-medium"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();