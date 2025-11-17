<?php
/**
 * Sidebar template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if (!is_active_sidebar('primary-sidebar') && !is_active_sidebar('secondary-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary">
    
    <div class="sidebar-content space-y-8">
        
        <!-- Primary Sidebar Widgets -->
        <?php if (is_active_sidebar('primary-sidebar')): ?>
            <div class="primary-widgets">
                <?php dynamic_sidebar('primary-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <!-- Context-Specific Widgets -->
        <?php if (is_single() && is_active_sidebar('single-sidebar')): ?>
            <div class="single-widgets">
                <?php dynamic_sidebar('single-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (is_page() && is_active_sidebar('page-sidebar')): ?>
            <div class="page-widgets">
                <?php dynamic_sidebar('page-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ((is_category() || is_tag() || is_archive()) && is_active_sidebar('archive-sidebar')): ?>
            <div class="archive-widgets">
                <?php dynamic_sidebar('archive-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (is_search() && is_active_sidebar('search-sidebar')): ?>
            <div class="search-widgets">
                <?php dynamic_sidebar('search-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag() || is_product()) && is_active_sidebar('shop-sidebar')): ?>
            <div class="shop-widgets">
                <?php dynamic_sidebar('shop-sidebar'); ?>
            </div>
        <?php endif; ?>
        
        <!-- Default Content if No Widgets -->
        <?php if (!is_active_sidebar('primary-sidebar')): ?>
            
            <!-- Search Widget -->
            <div class="widget search-widget card p-6">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Search', 'aqualuxe'); ?></h3>
                <?php get_search_form(); ?>
            </div>
            
            <!-- Recent Posts -->
            <div class="widget recent-posts-widget card p-6">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
                <?php
                $recent_posts = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true
                ]);
                
                if ($recent_posts->have_posts()):
                ?>
                    <div class="recent-posts-list space-y-4">
                        <?php
                        while ($recent_posts->have_posts()):
                            $recent_posts->the_post();
                        ?>
                            <article class="recent-post-item">
                                <div class="flex space-x-3">
                                    <?php if (has_post_thumbnail()): ?>
                                        <div class="post-thumb flex-shrink-0">
                                            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                <?php the_post_thumbnail('thumbnail', ['class' => 'w-12 h-12 object-cover rounded-lg']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-content flex-1">
                                        <h4 class="text-sm font-medium mb-1">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <time class="text-xs text-gray-500 dark:text-gray-400" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </div>
                                </div>
                            </article>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('No recent posts found.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Categories -->
            <div class="widget categories-widget card p-6">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Categories', 'aqualuxe'); ?></h3>
                <?php
                $categories = get_categories([
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 10,
                    'hide_empty' => true
                ]);
                
                if ($categories):
                ?>
                    <div class="categories-list space-y-2">
                        <?php foreach ($categories as $category): ?>
                            <div class="category-item">
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="flex items-center justify-between p-2 rounded hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">
                                    <span class="text-sm"><?php echo esc_html($category->name); ?></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-dark-600 px-2 py-1 rounded-full">
                                        <?php echo esc_html($category->count); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('No categories found.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Tags Cloud -->
            <?php
            $tags = get_tags([
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 20,
                'hide_empty' => true
            ]);
            
            if ($tags):
            ?>
                <div class="widget tags-widget card p-6">
                    <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Tags', 'aqualuxe'); ?></h3>
                    <div class="tags-cloud flex flex-wrap gap-2">
                        <?php
                        foreach ($tags as $tag):
                            $tag_size = min(($tag->count / 10) + 0.8, 1.2); // Scale between 0.8 and 1.2
                        ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                               class="tag-link inline-block bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors"
                               style="font-size: <?php echo esc_attr($tag_size); ?>em;"
                               title="<?php printf(esc_attr__('%s posts', 'aqualuxe'), $tag->count); ?>">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Newsletter Signup -->
            <div class="widget newsletter-widget card p-6 bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-primary-900/20 dark:to-secondary-900/20">
                <div class="text-center">
                    <div class="newsletter-icon mb-4">
                        <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="widget-title text-lg font-semibold mb-2"><?php esc_html_e('Stay Updated', 'aqualuxe'); ?></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <?php esc_html_e('Get the latest aquatic tips and product updates delivered to your inbox.', 'aqualuxe'); ?>
                    </p>
                    
                    <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="aqualuxe_newsletter_signup">
                        <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
                        
                        <div class="space-y-3">
                            <input type="email" 
                                   name="email" 
                                   placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-800"
                                   required>
                            <button type="submit" class="btn btn-primary btn-sm w-full">
                                <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                            </button>
                        </div>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <?php
                            printf(
                                wp_kses(
                                    __('By subscribing, you agree to our <a href="%s" class="text-primary-600 hover:text-primary-700">Privacy Policy</a>.', 'aqualuxe'),
                                    [
                                        'a' => [
                                            'href' => [],
                                            'class' => []
                                        ]
                                    ]
                                ),
                                esc_url(get_privacy_policy_url())
                            );
                            ?>
                        </p>
                    </form>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="widget social-widget card p-6">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h3>
                <div class="social-links flex justify-center space-x-3">
                    <?php
                    $social_links = [
                        'facebook' => get_theme_mod('facebook_url', ''),
                        'twitter' => get_theme_mod('twitter_url', ''),
                        'instagram' => get_theme_mod('instagram_url', ''),
                        'youtube' => get_theme_mod('youtube_url', ''),
                        'linkedin' => get_theme_mod('linkedin_url', ''),
                    ];
                    
                    foreach ($social_links as $platform => $url):
                        if ($url):
                    ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-link w-10 h-10 bg-gray-100 dark:bg-dark-700 rounded-full flex items-center justify-center hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors"
                           aria-label="<?php printf(esc_attr__('Follow us on %s', 'aqualuxe'), ucfirst($platform)); ?>">
                            <?php echo aqualuxe_get_social_icon($platform); ?>
                        </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            
        <?php endif; ?>
        
        <!-- Secondary Sidebar Widgets -->
        <?php if (is_active_sidebar('secondary-sidebar')): ?>
            <div class="secondary-widgets">
                <?php dynamic_sidebar('secondary-sidebar'); ?>
            </div>
        <?php endif; ?>
        
    </div>
    
</aside><!-- #secondary -->
