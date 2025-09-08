<?php
/**
 * Sidebar Template
 *
 * The primary sidebar containing widget areas for blog pages,
 * archives, and other content areas. Includes responsive design
 * and accessibility features.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if sidebar should be displayed
if (!is_active_sidebar('primary-sidebar') && !is_active_sidebar('blog-sidebar')) {
    return;
}
?>

<aside id="secondary" class="sidebar primary-sidebar" role="complementary" aria-label="<?php esc_attr_e('Primary sidebar', 'aqualuxe'); ?>">
    
    <div class="sidebar-inner space-y-6">
        
        <?php
        // Determine which sidebar to display
        $sidebar_id = 'primary-sidebar';
        
        if (is_home() || is_category() || is_tag() || is_author() || is_date() || is_search()) {
            $sidebar_id = 'blog-sidebar';
        } elseif (is_single() && get_post_type() === 'post') {
            $sidebar_id = 'blog-sidebar';
        } elseif (is_page()) {
            $sidebar_id = 'page-sidebar';
        } elseif (function_exists('is_woocommerce') && is_woocommerce()) {
            $sidebar_id = 'shop-sidebar';
        }
        
        // Display the appropriate sidebar
        if (is_active_sidebar($sidebar_id)) {
            dynamic_sidebar($sidebar_id);
        } elseif (is_active_sidebar('primary-sidebar')) {
            dynamic_sidebar('primary-sidebar');
        } else {
            // Default fallback widgets when no widgets are assigned
            ?>
            
            <!-- Search Widget -->
            <div class="widget widget_search bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    <?php esc_html_e('Search', 'aqualuxe'); ?>
                </h3>
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="relative">
                        <label for="sidebar-search" class="sr-only">
                            <?php esc_html_e('Search for:', 'aqualuxe'); ?>
                        </label>
                        <input 
                            type="search" 
                            id="sidebar-search"
                            class="block w-full pl-4 pr-12 py-3 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" 
                            value="<?php echo get_search_query(); ?>" 
                            name="s" 
                        />
                        <button 
                            type="submit"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                            aria-label="<?php esc_attr_e('Submit search', 'aqualuxe'); ?>"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Recent Posts Widget -->
            <?php
            $recent_posts = wp_get_recent_posts([
                'numberposts' => 5,
                'post_status' => 'publish'
            ]);
            
            if ($recent_posts) : ?>
                <div class="widget widget_recent_entries bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Recent Posts', 'aqualuxe'); ?>
                    </h3>
                    <ul class="space-y-3">
                        <?php foreach ($recent_posts as $recent_post) : ?>
                            <li class="recent-post-item">
                                <article class="flex space-x-3">
                                    <?php if (has_post_thumbnail($recent_post['ID'])) : ?>
                                        <div class="flex-shrink-0">
                                            <a href="<?php echo esc_url(get_permalink($recent_post['ID'])); ?>" class="block">
                                                <?php echo get_the_post_thumbnail($recent_post['ID'], 'thumbnail', [
                                                    'class' => 'w-16 h-16 object-cover rounded-md',
                                                    'alt' => get_the_title($recent_post['ID'])
                                                ]); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="text-sm font-medium mb-1">
                                            <a href="<?php echo esc_url(get_permalink($recent_post['ID'])); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 line-clamp-2">
                                                <?php echo esc_html(get_the_title($recent_post['ID'])); ?>
                                            </a>
                                        </h4>
                                        <time datetime="<?php echo esc_attr(get_the_date('c', $recent_post['ID'])); ?>" class="text-xs text-gray-500 dark:text-gray-400">
                                            <?php echo esc_html(get_the_date('', $recent_post['ID'])); ?>
                                        </time>
                                    </div>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Categories Widget -->
            <?php
            $categories = get_categories([
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 10,
                'hide_empty' => true
            ]);
            
            if ($categories) : ?>
                <div class="widget widget_categories bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Categories', 'aqualuxe'); ?>
                    </h3>
                    <ul class="space-y-2">
                        <?php foreach ($categories as $category) : ?>
                            <li class="category-item">
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="flex items-center justify-between group py-2 px-3 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        <?php echo esc_html($category->name); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded-full">
                                        <?php echo esc_html($category->count); ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Tags Widget -->
            <?php
            $tags = get_tags([
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 20,
                'hide_empty' => true
            ]);
            
            if ($tags) : ?>
                <div class="widget widget_tag_cloud bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Popular Tags', 'aqualuxe'); ?>
                    </h3>
                    <div class="tag-cloud flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="inline-block px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-blue-100 hover:text-blue-800 dark:hover:bg-blue-900 dark:hover:text-blue-200 transition-colors duration-200">
                                <?php echo esc_html($tag->name); ?>
                                <span class="ml-1 text-xs opacity-75">(<?php echo esc_html($tag->count); ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Archive Widget -->
            <?php
            $archives = wp_get_archives([
                'type' => 'monthly',
                'limit' => 12,
                'format' => 'custom',
                'echo' => false
            ]);
            
            if ($archives) : ?>
                <div class="widget widget_archive bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Archives', 'aqualuxe'); ?>
                    </h3>
                    <ul class="space-y-2">
                        <?php
                        // Get monthly archives
                        global $wpdb;
                        $months = $wpdb->get_results("
                            SELECT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts
                            FROM $wpdb->posts
                            WHERE post_type = 'post' AND post_status = 'publish'
                            GROUP BY YEAR(post_date), MONTH(post_date)
                            ORDER BY post_date DESC
                            LIMIT 12
                        ");
                        
                        foreach ($months as $month) :
                            $month_link = get_month_link($month->year, $month->month);
                            $month_name = date_i18n('F Y', mktime(0, 0, 0, $month->month, 1, $month->year));
                        ?>
                            <li class="archive-item">
                                <a href="<?php echo esc_url($month_link); ?>" class="flex items-center justify-between group py-2 px-3 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        <?php echo esc_html($month_name); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded-full">
                                        <?php echo esc_html($month->posts); ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Newsletter Widget -->
            <div class="widget widget_newsletter bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-md p-6 text-white">
                <h3 class="widget-title text-lg font-semibold mb-4 text-white">
                    <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
                </h3>
                <p class="text-blue-100 mb-4 text-sm">
                    <?php esc_html_e('Subscribe to our newsletter and get the latest updates delivered straight to your inbox.', 'aqualuxe'); ?>
                </p>
                <form class="newsletter-form" action="#" method="post">
                    <div class="space-y-3">
                        <input 
                            type="email" 
                            name="newsletter_email" 
                            placeholder="<?php esc_attr_e('Enter your email...', 'aqualuxe'); ?>"
                            class="w-full px-4 py-2 rounded-md border-0 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                            required
                        />
                        <button type="submit" class="w-full bg-white text-blue-600 font-medium py-2 px-4 rounded-md hover:bg-gray-100 transition-colors duration-200">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </div>
                    <p class="text-xs text-blue-200 mt-3">
                        <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                    </p>
                </form>
            </div>
            
            <?php
        }
        ?>
        
    </div>
    
</aside>

<!-- Add custom CSS for line-clamp utility -->
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
