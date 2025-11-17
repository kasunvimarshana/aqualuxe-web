<?php
/**
 * Template part for displaying the header search
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Check if search is enabled
if ( ! get_theme_mod( 'aqualuxe_enable_search', true ) ) {
	return;
}
?>

<div id="header-search" class="header-search fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="header-search-container w-full max-w-3xl mx-auto mt-20 bg-white dark:bg-dark-800 rounded-lg shadow-medium p-6 transform transition-transform duration-300 scale-95 opacity-0">
        <div class="header-search-header flex items-center justify-between mb-4">
            <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                <?php esc_html_e( 'Search', 'aqualuxe' ); ?>
            </h3>
            <button id="search-close" class="search-close text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300" aria-label="<?php esc_attr_e( 'Close search', 'aqualuxe' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="header-search-content">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="relative">
                    <input type="search" id="header-search-input" class="search-field w-full p-4 pr-12 border border-gray-300 dark:border-dark-600 rounded-lg bg-gray-50 dark:bg-dark-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <button type="submit" class="search-submit absolute right-3 top-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
                <?php if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_search_products_only', false ) ) : ?>
                    <input type="hidden" name="post_type" value="product" />
                <?php endif; ?>
            </form>

            <?php if ( get_theme_mod( 'aqualuxe_show_search_categories', true ) ) : ?>
                <div class="search-categories mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php esc_html_e( 'Popular Categories:', 'aqualuxe' ); ?>
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        // Get categories to display in search
                        $categories_args = array(
                            'taxonomy'   => class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_search_products_only', false ) ? 'product_cat' : 'category',
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'hide_empty' => true,
                            'number'     => 8,
                        );

                        $categories = get_terms( $categories_args );

                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $category ) {
                                echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="inline-block px-3 py-1 bg-gray-100 dark:bg-dark-700 text-gray-800 dark:text-gray-200 text-sm rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-800 dark:hover:text-primary-200 transition-colors duration-300">' . esc_html( $category->name ) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( get_theme_mod( 'aqualuxe_show_search_trending', true ) ) : ?>
                <div class="search-trending mt-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php esc_html_e( 'Trending Searches:', 'aqualuxe' ); ?>
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        // Get trending search terms from theme mod or use defaults
                        $trending_searches = get_theme_mod( 'aqualuxe_trending_searches', 'Aquarium Fish, Tropical Fish, Aquarium Plants, Fish Food, Aquarium Accessories' );
                        $trending_searches = explode( ',', $trending_searches );

                        foreach ( $trending_searches as $search_term ) {
                            $search_term = trim( $search_term );
                            if ( ! empty( $search_term ) ) {
                                $search_url = add_query_arg( 's', urlencode( $search_term ), home_url( '/' ) );
                                if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_search_products_only', false ) ) {
                                    $search_url = add_query_arg( 'post_type', 'product', $search_url );
                                }
                                echo '<a href="' . esc_url( $search_url ) . '" class="inline-block px-3 py-1 border border-gray-200 dark:border-dark-600 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:border-primary-300 dark:hover:border-primary-700 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-300">' . esc_html( $search_term ) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>