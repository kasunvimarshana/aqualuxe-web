<?php
/**
 * 404 Error Page Template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-16">
        
        <div class="error-404 text-center">
            <div class="max-w-2xl mx-auto">
                
                <!-- Error Animation/Illustration -->
                <div class="error-illustration mb-12">
                    <div class="relative">
                        <!-- Animated Fish -->
                        <div class="fish-animation mb-8">
                            <svg class="w-64 h-64 mx-auto text-primary-600" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Water Bubbles -->
                                <circle cx="50" cy="50" r="3" fill="currentColor" opacity="0.3" class="animate-bounce">
                                    <animate attributeName="cy" values="50;30;50" dur="2s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="150" cy="70" r="2" fill="currentColor" opacity="0.4" class="animate-bounce">
                                    <animate attributeName="cy" values="70;45;70" dur="1.5s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="80" cy="160" r="2.5" fill="currentColor" opacity="0.2" class="animate-bounce">
                                    <animate attributeName="cy" values="160;140;160" dur="2.5s" repeatCount="indefinite"/>
                                </circle>
                                
                                <!-- Fish Body -->
                                <ellipse cx="100" cy="100" rx="40" ry="25" fill="currentColor" opacity="0.8">
                                    <animateTransform attributeName="transform" type="translate" values="0,0;5,0;0,0" dur="3s" repeatCount="indefinite"/>
                                </ellipse>
                                
                                <!-- Fish Tail -->
                                <path d="M140 100 L160 85 L155 100 L160 115 Z" fill="currentColor" opacity="0.6">
                                    <animateTransform attributeName="transform" type="translate" values="0,0;-3,0;0,0" dur="3s" repeatCount="indefinite"/>
                                </path>
                                
                                <!-- Fish Eye -->
                                <circle cx="80" cy="95" r="4" fill="white"/>
                                <circle cx="82" cy="93" r="2" fill="#1a202c"/>
                                
                                <!-- Fish Fins -->
                                <path d="M100 125 L95 140 L105 140 Z" fill="currentColor" opacity="0.6">
                                    <animateTransform attributeName="transform" type="translate" values="0,0;2,-1;0,0" dur="3s" repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                        
                        <!-- 404 Number -->
                        <div class="error-number text-9xl font-bold text-gray-200 dark:text-dark-700 leading-none mb-4">
                            404
                        </div>
                    </div>
                </div>
                
                <!-- Error Content -->
                <div class="error-content">
                    <h1 class="error-title text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?>
                    </h1>
                    
                    <p class="error-description text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        <?php esc_html_e('It looks like the page you\'re looking for has drifted away like a fish in the ocean. Don\'t worry, we\'ll help you navigate back to safer waters.', 'aqualuxe'); ?>
                    </p>
                    
                    <!-- Error Actions -->
                    <div class="error-actions space-y-6">
                        
                        <!-- Search Form -->
                        <div class="search-form max-w-lg mx-auto">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Try searching for what you need:', 'aqualuxe'); ?></h3>
                            <?php get_search_form(); ?>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="quick-links">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Or explore these popular sections:', 'aqualuxe'); ?></h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <?php esc_html_e('Home Page', 'aqualuxe'); ?>
                                </a>
                                
                                <?php if (class_exists('WooCommerce')): ?>
                                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <?php esc_html_e('Shop', 'aqualuxe'); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <?php esc_html_e('Blog', 'aqualuxe'); ?>
                                </a>
                                
                                <?php
                                $contact_page = get_page_by_path('contact');
                                if ($contact_page):
                                ?>
                                    <a href="<?php echo esc_url(get_permalink($contact_page)); ?>" class="btn btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php esc_html_e('Contact', 'aqualuxe'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Popular Content Section -->
        <section class="popular-content mt-20">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12"><?php esc_html_e('Popular Content', 'aqualuxe'); ?></h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Popular Posts -->
                    <div class="popular-posts">
                        <h3 class="text-xl font-semibold mb-6"><?php esc_html_e('Popular Posts', 'aqualuxe'); ?></h3>
                        <?php
                        $popular_posts = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        ]);
                        
                        if ($popular_posts->have_posts()):
                        ?>
                            <div class="space-y-4">
                                <?php
                                while ($popular_posts->have_posts()):
                                    $popular_posts->the_post();
                                ?>
                                    <article class="popular-post flex space-x-4">
                                        <?php if (has_post_thumbnail()): ?>
                                            <div class="post-thumb flex-shrink-0">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('thumbnail', ['class' => 'w-16 h-16 object-cover rounded-lg']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="post-content flex-1">
                                            <h4 class="text-sm font-semibold mb-1">
                                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h4>
                                            <time class="text-xs text-gray-500 dark:text-gray-400" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </div>
                                    </article>
                                <?php
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('No popular posts found.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Popular Categories -->
                    <div class="popular-categories">
                        <h3 class="text-xl font-semibold mb-6"><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h3>
                        <?php
                        $popular_categories = get_categories([
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 5,
                            'hide_empty' => true
                        ]);
                        
                        if ($popular_categories):
                        ?>
                            <div class="space-y-3">
                                <?php foreach ($popular_categories as $category): ?>
                                    <div class="category-item">
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-dark-800 transition-colors">
                                            <span class="font-medium"><?php echo esc_html($category->name); ?></span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html($category->count); ?> <?php esc_html_e('posts', 'aqualuxe'); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('No categories found.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Recent Comments -->
                    <div class="recent-comments">
                        <h3 class="text-xl font-semibold mb-6"><?php esc_html_e('Recent Comments', 'aqualuxe'); ?></h3>
                        <?php
                        $recent_comments = get_comments([
                            'number' => 5,
                            'status' => 'approve',
                            'type' => 'comment'
                        ]);
                        
                        if ($recent_comments):
                        ?>
                            <div class="space-y-4">
                                <?php foreach ($recent_comments as $comment): ?>
                                    <div class="comment-item">
                                        <div class="flex items-start space-x-3">
                                            <div class="comment-avatar flex-shrink-0">
                                                <?php echo get_avatar($comment->comment_author_email, 32, '', $comment->comment_author, ['class' => 'rounded-full']); ?>
                                            </div>
                                            <div class="comment-content flex-1">
                                                <div class="comment-author text-sm font-medium">
                                                    <?php echo esc_html($comment->comment_author); ?>
                                                </div>
                                                <div class="comment-text text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <?php echo esc_html(wp_trim_words($comment->comment_content, 10)); ?>
                                                </div>
                                                <div class="comment-meta text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <a href="<?php echo esc_url(get_comment_link($comment)); ?>" class="hover:text-primary-600 transition-colors">
                                                        <?php echo esc_html(human_time_diff(strtotime($comment->comment_date), current_time('timestamp'))); ?> <?php esc_html_e('ago', 'aqualuxe'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('No recent comments.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </section>
        
        <!-- Help Section -->
        <section class="help-section mt-20 bg-gray-50 dark:bg-dark-800 rounded-2xl p-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Still Need Help?', 'aqualuxe'); ?></h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    <?php esc_html_e('If you can\'t find what you\'re looking for, our support team is here to help you navigate our aquatic paradise.', 'aqualuxe'); ?>
                </p>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="help-item">
                        <div class="help-icon w-12 h-12 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2"><?php esc_html_e('Contact Support', 'aqualuxe'); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <?php esc_html_e('Get in touch with our team', 'aqualuxe'); ?>
                        </p>
                        <?php
                        $contact_page = get_page_by_path('contact');
                        if ($contact_page):
                        ?>
                            <a href="<?php echo esc_url(get_permalink($contact_page)); ?>" class="btn btn-outline btn-sm">
                                <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-icon w-12 h-12 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2"><?php esc_html_e('FAQ', 'aqualuxe'); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <?php esc_html_e('Find answers to common questions', 'aqualuxe'); ?>
                        </p>
                        <?php
                        $faq_page = get_page_by_path('faq');
                        if ($faq_page):
                        ?>
                            <a href="<?php echo esc_url(get_permalink($faq_page)); ?>" class="btn btn-outline btn-sm">
                                <?php esc_html_e('View FAQ', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-icon w-12 h-12 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2"><?php esc_html_e('Documentation', 'aqualuxe'); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <?php esc_html_e('Browse our help documentation', 'aqualuxe'); ?>
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
    </div>
</main>

<?php
get_footer();
?>
