<?php
/**
 * 404 Error Page Template
 *
 * Displays a user-friendly 404 error page with helpful navigation,
 * search functionality, and recent content suggestions.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main error-404-main" role="main" aria-label="<?php esc_attr_e('Main content', 'aqualuxe'); ?>">
    
    <div class="error-404-container min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-16">
        
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <!-- Error Animation/Illustration -->
            <div class="error-illustration mb-8">
                <div class="relative inline-block">
                    <!-- 404 Text with Animation -->
                    <div class="text-9xl md:text-[12rem] font-bold text-blue-200 dark:text-gray-700 select-none opacity-50 animate-pulse">
                        404
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-blue-500 rounded-full opacity-20 animate-bounce" style="animation-delay: 0.1s;"></div>
                        <div class="w-12 h-12 bg-indigo-500 rounded-full opacity-30 animate-bounce absolute top-4 right-8" style="animation-delay: 0.3s;"></div>
                        <div class="w-8 h-8 bg-purple-500 rounded-full opacity-25 animate-bounce absolute bottom-8 left-12" style="animation-delay: 0.5s;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Error Content -->
            <div class="error-content max-w-2xl mx-auto">
                
                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    <?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?>
                </h1>
                
                <!-- Subheading -->
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    <?php esc_html_e('The page you\'re looking for seems to have sailed away. Let\'s get you back on course!', 'aqualuxe'); ?>
                </p>
                
                <!-- Error Details -->
                <div class="error-details bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('What happened?', 'aqualuxe'); ?>
                    </h2>
                    <ul class="text-left space-y-2 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <?php esc_html_e('The page URL may have been typed incorrectly', 'aqualuxe'); ?>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <?php esc_html_e('The page may have been moved or deleted', 'aqualuxe'); ?>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <?php esc_html_e('You may have followed an outdated link', 'aqualuxe'); ?>
                        </li>
                    </ul>
                </div>
                
                <!-- Search Form -->
                <div class="error-search mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Try searching for what you need:', 'aqualuxe'); ?>
                    </h2>
                    <form role="search" method="get" class="search-form max-w-lg mx-auto" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="relative">
                            <label for="error-search" class="sr-only">
                                <?php esc_html_e('Search for:', 'aqualuxe'); ?>
                            </label>
                            <input 
                                type="search" 
                                id="error-search"
                                class="block w-full pl-4 pr-12 py-4 text-lg border border-gray-300 rounded-lg leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                placeholder="<?php esc_attr_e('Search our website...', 'aqualuxe'); ?>" 
                                value="<?php echo get_search_query(); ?>" 
                                name="s" 
                                autofocus
                            />
                            <button 
                                type="submit"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                aria-label="<?php esc_attr_e('Submit search', 'aqualuxe'); ?>"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Action Buttons -->
                <div class="error-actions flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    
                    <!-- Go Home Button -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                    </a>
                    
                    <!-- Go Back Button -->
                    <button onclick="history.back()" class="inline-flex items-center px-8 py-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <?php esc_html_e('Go Back', 'aqualuxe'); ?>
                    </button>
                    
                    <!-- Contact Button -->
                    <?php
                    $contact_page = get_page_by_path('contact');
                    if ($contact_page) :
                    ?>
                        <a href="<?php echo esc_url(get_permalink($contact_page->ID)); ?>" class="inline-flex items-center px-8 py-4 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold rounded-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                        </a>
                    <?php endif; ?>
                    
                </div>
                
            </div>
            
            <!-- Helpful Suggestions -->
            <div class="helpful-content grid gap-8 md:grid-cols-2 lg:grid-cols-3 max-w-6xl mx-auto">
                
                <!-- Recent Posts -->
                <?php
                $recent_posts = wp_get_recent_posts([
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ]);
                
                if ($recent_posts) : ?>
                    <div class="suggestion-block bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <?php esc_html_e('Recent Posts', 'aqualuxe'); ?>
                        </h3>
                        <ul class="space-y-3">
                            <?php foreach ($recent_posts as $recent_post) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_permalink($recent_post['ID'])); ?>" class="block text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                        <?php echo esc_html(get_the_title($recent_post['ID'])); ?>
                                    </a>
                                    <time class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo esc_html(get_the_date('', $recent_post['ID'])); ?>
                                    </time>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Popular Categories -->
                <?php
                $categories = get_categories([
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 5,
                    'hide_empty' => true
                ]);
                
                if ($categories) : ?>
                    <div class="suggestion-block bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <?php esc_html_e('Popular Categories', 'aqualuxe'); ?>
                        </h3>
                        <ul class="space-y-2">
                            <?php foreach ($categories as $category) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="flex items-center justify-between text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                        <span><?php echo esc_html($category->name); ?></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            (<?php echo esc_html($category->count); ?>)
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Quick Links -->
                <div class="suggestion-block bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                <?php esc_html_e('Home Page', 'aqualuxe'); ?>
                            </a>
                        </li>
                        <?php
                        $blog_page_id = get_option('page_for_posts');
                        if ($blog_page_id) :
                        ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($blog_page_id)); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                    <?php esc_html_e('Blog', 'aqualuxe'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php
                        // Common pages
                        $common_pages = ['about', 'services', 'portfolio', 'contact'];
                        foreach ($common_pages as $page_slug) :
                            $page = get_page_by_path($page_slug);
                            if ($page) :
                        ?>
                                <li>
                                    <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                        <?php echo esc_html(get_the_title($page->ID)); ?>
                                    </a>
                                </li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                        
                        <!-- Sitemap Link -->
                        <li>
                            <a href="<?php echo esc_url(home_url('/sitemap')); ?>" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                <?php esc_html_e('Sitemap', 'aqualuxe'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
</main>

<!-- Add custom animations -->
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.error-illustration .animate-bounce {
    animation: float 3s ease-in-out infinite;
}

.error-illustration .animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@media (prefers-reduced-motion: reduce) {
    .error-illustration .animate-bounce,
    .error-illustration .animate-pulse {
        animation: none;
    }
}
</style>

<!-- Add JavaScript for enhanced UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus on search input after page load
    const searchInput = document.getElementById('error-search');
    if (searchInput) {
        setTimeout(() => {
            searchInput.focus();
        }, 500);
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Press 'H' to go home
        if (e.key === 'h' || e.key === 'H') {
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                window.location.href = '<?php echo esc_js(home_url('/')); ?>';
            }
        }
        
        // Press 'B' to go back
        if (e.key === 'b' || e.key === 'B') {
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                history.back();
            }
        }
    });
    
    // Add visual feedback for button interactions
    const buttons = document.querySelectorAll('.error-actions a, .error-actions button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

<?php get_footer();
