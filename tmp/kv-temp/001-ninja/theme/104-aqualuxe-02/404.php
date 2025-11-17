<?php
/**
 * 404 Error Template
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-16">
        
        <div class="text-center max-w-2xl mx-auto">
            
            <!-- 404 Illustration -->
            <div class="mb-8">
                <div class="text-9xl font-bold text-primary-600 dark:text-primary-400 mb-4">404</div>
                <div class="w-32 h-32 mx-auto mb-8 text-primary-300">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                <?php esc_html_e('Page Not Found', 'aqualuxe'); ?>
            </h1>
            
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                <?php esc_html_e('Sorry, the page you are looking for doesn\'t exist or has been moved. Let\'s get you back on track!', 'aqualuxe'); ?>
            </p>
            
            <!-- Search Form -->
            <div class="mb-8 max-w-md mx-auto">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Try searching for what you need:', 'aqualuxe'); ?>
                </h2>
                <?php get_search_form(); ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                </a>
                
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline-primary btn-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                        </svg>
                        <?php esc_html_e('Browse Shop', 'aqualuxe'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/services')); ?>" class="btn btn-outline-primary btn-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Helpful Links -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                    <?php esc_html_e('Popular Pages', 'aqualuxe'); ?>
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    $popular_pages = [
                        home_url('/') => __('Home', 'aqualuxe'),
                        home_url('/about/') => __('About Us', 'aqualuxe'),
                        home_url('/services/') => __('Services', 'aqualuxe'),
                        home_url('/contact/') => __('Contact', 'aqualuxe'),
                    ];
                    
                    if (aqualuxe_is_woocommerce_active()) {
                        $popular_pages[wc_get_page_permalink('shop')] = __('Shop', 'aqualuxe');
                        $popular_pages[wc_get_page_permalink('myaccount')] = __('My Account', 'aqualuxe');
                    }
                    
                    foreach ($popular_pages as $url => $title) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" class="block p-3 text-center bg-white dark:bg-gray-700 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <?php echo esc_html($title); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Posts -->
            <?php
            $recent_posts = get_posts([
                'numberposts' => 3,
                'post_status' => 'publish'
            ]);
            
            if ($recent_posts) :
            ?>
                <div class="mt-12">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e('Recent Articles', 'aqualuxe'); ?>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($recent_posts as $post) :
                            setup_postdata($post);
                        ?>
                            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="aspect-w-16 aspect-h-9">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('aqualuxe-featured', [
                                                'class' => 'w-full h-full object-cover',
                                                'alt' => esc_attr(get_the_title())
                                            ]); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                            <?php echo esc_html(get_the_title()); ?>
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </p>
                                </div>
                            </article>
                        <?php endforeach;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        
    </div>
</main>

<?php get_footer(); ?>