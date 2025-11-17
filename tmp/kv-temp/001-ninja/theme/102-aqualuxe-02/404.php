<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-16">
        
        <section class="error-404 not-found text-center max-w-2xl mx-auto">
            
            <!-- 404 Illustration -->
            <div class="error-illustration mb-8">
                <svg class="w-64 h-64 mx-auto text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            
            <header class="page-header mb-8">
                <h1 class="page-title text-6xl font-bold text-gray-900 dark:text-white mb-4">404</h1>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-6">
                    <?php esc_html_e( 'Oops! Page Not Found', 'aqualuxe' ); ?>
                </h2>
            </header><!-- .page-header -->

            <div class="page-content mb-8">
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                    <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?>
                </p>

                <!-- Search Form -->
                <div class="search-form-wrapper mb-8">
                    <form role="search" method="get" class="search-form flex max-w-md mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <label for="s" class="sr-only"><?php esc_html_e( 'Search for:', 'aqualuxe' ); ?></label>
                        <input type="search" id="s" class="search-field flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit" class="search-submit px-6 py-3 bg-primary-600 text-white rounded-r-lg hover:bg-primary-700 transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        </button>
                    </form>
                </div>

                <!-- Quick Links -->
                <div class="quick-links">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e( 'Try these pages instead:', 'aqualuxe' ); ?>
                    </h3>
                    
                    <div class="links-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="link-card bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center border border-gray-200 dark:border-gray-700">
                            <svg class="w-8 h-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></span>
                        </a>
                        
                        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="link-card bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center border border-gray-200 dark:border-gray-700">
                            <svg class="w-8 h-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php esc_html_e( 'About', 'aqualuxe' ); ?></span>
                        </a>
                        
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="link-card bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center border border-gray-200 dark:border-gray-700">
                            <svg class="w-8 h-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></span>
                        </a>
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="back-button mt-8">
                    <button onclick="history.back()" class="btn btn-primary inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <?php esc_html_e( 'Go Back', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div><!-- .page-content -->
            
        </section><!-- .error-404 -->
        
    </div>
</main><!-- #primary -->

<?php
get_footer();