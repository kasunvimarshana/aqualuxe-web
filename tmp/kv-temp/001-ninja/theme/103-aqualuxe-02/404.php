<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main min-h-screen flex items-center" role="main">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            
            <!-- 404 Illustration -->
            <div class="mb-12">
                <div class="relative inline-block">
                    <!-- Large 404 Text -->
                    <h1 class="text-8xl md:text-9xl font-bold text-gradient-aqua leading-none select-none">
                        404
                    </h1>
                    
                    <!-- Floating aquatic elements -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute top-1/4 left-1/4 w-8 h-8 text-aqua-300 animate-float">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16a6 6 0 006-6V6a6 6 0 10-12 0v4a6 6 0 006 6zM4 6a4 4 0 118 0v4a4 4 0 01-8 0V6z"/>
                            </svg>
                        </div>
                        <div class="absolute top-3/4 right-1/4 w-6 h-6 text-luxury-400 animate-bubble" style="animation-delay: 1s;">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="absolute bottom-1/4 left-1/2 w-4 h-4 text-coral-300 animate-wave" style="animation-delay: 2s;">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="mb-8">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-ocean-800 dark:text-gray-100 mb-4">
                    <?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?>
                </h2>
                
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 max-w-2xl mx-auto">
                    <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Let\'s get you back on track!', 'aqualuxe'); ?>
                </p>
            </div>
            
            <!-- Search Form -->
            <div class="mb-12 max-w-md mx-auto">
                <form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
                    <label for="error-search" class="sr-only">
                        <?php esc_html_e('Search for:', 'aqualuxe'); ?>
                    </label>
                    <div class="relative">
                        <input 
                            type="search" 
                            id="error-search"
                            class="search-field w-full px-4 py-3 pr-12 text-lg border-2 border-aqua-300 rounded-lg focus:border-aqua-500 focus:outline-none focus:ring-2 focus:ring-aqua-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:border-aqua-400" 
                            placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" 
                            value="<?php echo get_search_query(); ?>" 
                            name="s" 
                            required
                        />
                        <button 
                            type="submit" 
                            class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 text-aqua-500 hover:text-aqua-700 transition-colors"
                            aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                
                <!-- Go Home -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-aqua-100 dark:bg-aqua-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-aqua-600 dark:text-aqua-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ocean-800 dark:text-gray-200 mb-2">
                        <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <?php esc_html_e('Return to our homepage and start fresh.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary w-full text-center">
                        <?php esc_html_e('Homepage', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <!-- Browse Products -->
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-luxury-100 dark:bg-luxury-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-luxury-600 dark:text-luxury-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ocean-800 dark:text-gray-200 mb-2">
                        <?php esc_html_e('Browse Products', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <?php esc_html_e('Explore our aquatic collection.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-secondary w-full text-center">
                        <?php esc_html_e('Shop Now', 'aqualuxe'); ?>
                    </a>
                </div>
                <?php else : ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-coral-100 dark:bg-coral-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-coral-600 dark:text-coral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ocean-800 dark:text-gray-200 mb-2">
                        <?php esc_html_e('Read Blog', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <?php esc_html_e('Check out our latest articles and guides.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn-secondary w-full text-center">
                        <?php esc_html_e('View Blog', 'aqualuxe'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- Contact Us -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-coral-100 dark:bg-coral-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-coral-600 dark:text-coral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ocean-800 dark:text-gray-200 mb-2">
                        <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <?php esc_html_e('Get in touch with our support team.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-outline w-full text-center">
                        <?php esc_html_e('Contact', 'aqualuxe'); ?>
                    </a>
                </div>
                
            </div>
            
            <!-- Popular Links -->
            <?php if (has_nav_menu('primary')) : ?>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                <h3 class="text-lg font-semibold text-ocean-800 dark:text-gray-200 mb-4">
                    <?php esc_html_e('Popular Pages', 'aqualuxe'); ?>
                </h3>
                
                <nav class="popular-links" aria-label="<?php esc_attr_e('Popular pages', 'aqualuxe'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex flex-wrap justify-center gap-4',
                        'depth'          => 1,
                        'link_before'    => '<span class="inline-block px-4 py-2 text-aqua-600 hover:text-aqua-800 dark:text-aqua-400 dark:hover:text-aqua-300 transition-colors">',
                        'link_after'     => '</span>',
                    ));
                    ?>
                </nav>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</main>

<?php get_footer(); ?>