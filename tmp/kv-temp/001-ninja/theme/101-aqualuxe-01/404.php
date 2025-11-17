<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main container py-16" role="main">
    <section class="error-404 not-found text-center max-w-2xl mx-auto">
        <div class="error-illustration mb-8">
            <svg class="w-32 h-32 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.485 0-4.691 1.132-6.17 2.91m13.17-2.91A7.962 7.962 0 0012 15c2.485 0 4.691 1.132 6.17 2.91M8 21l4-7 4 7M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
        </div>

        <header class="page-header mb-8">
            <h1 class="page-title text-8xl font-bold text-gray-900 dark:text-white mb-4">404</h1>
            <h2 class="error-subtitle text-2xl font-serif text-gray-700 dark:text-gray-300 mb-4">
                <?php esc_html_e( 'Oops! Page Not Found', 'aqualuxe' ); ?>
            </h2>
        </header>

        <div class="page-content">
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                <?php esc_html_e( 'It looks like the page you\'re looking for has swum away. Let\'s help you find what you\'re looking for.', 'aqualuxe' ); ?>
            </p>

            <div class="error-search mb-8">
                <form role="search" method="get" class="search-form max-w-md mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="flex">
                        <input type="search" 
                               class="search-field flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-l-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50" 
                               placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'aqualuxe' ); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" />
                        <button type="submit" 
                                class="search-submit px-6 py-3 bg-primary-600 text-white rounded-r-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="helpful-links grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="helpful-link text-center">
                    <div class="link-icon w-12 h-12 mx-auto mb-3 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        <?php esc_html_e( 'Shop Products', 'aqualuxe' ); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">
                        <?php esc_html_e( 'Browse our premium aquatic products', 'aqualuxe' ); ?>
                    </p>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
                            <?php esc_html_e( 'Visit Shop', 'aqualuxe' ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="helpful-link text-center">
                    <div class="link-icon w-12 h-12 mx-auto mb-3 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        <?php esc_html_e( 'Read Blog', 'aqualuxe' ); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">
                        <?php esc_html_e( 'Learn about aquatic care and tips', 'aqualuxe' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
                        <?php esc_html_e( 'Read Articles', 'aqualuxe' ); ?>
                    </a>
                </div>

                <div class="helpful-link text-center">
                    <div class="link-icon w-12 h-12 mx-auto mb-3 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        <?php esc_html_e( 'Get Help', 'aqualuxe' ); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">
                        <?php esc_html_e( 'Contact our expert team', 'aqualuxe' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Contact' ) ) ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
                        <?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div>

            <div class="back-home">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();