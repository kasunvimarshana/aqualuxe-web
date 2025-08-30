<?php
/**
 * Template part for displaying the search modal
 *
 * @package AquaLuxe
 */

// Check if search is enabled in customizer
$search_enabled = get_theme_mod( 'aqualuxe_search_enable', true );

if ( ! $search_enabled ) {
    return;
}
?>

<div id="search-modal" class="search-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
    <div class="search-modal-content bg-white dark:bg-gray-800 w-full max-w-2xl mx-auto p-6 rounded-lg shadow-xl">
        <div class="search-modal-header flex justify-between items-center mb-4">
            <h2 id="search-modal-title" class="text-xl font-bold"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></h2>
            <button id="search-modal-close" class="search-modal-close" aria-label="<?php esc_attr_e( 'Close search', 'aqualuxe' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="search-modal-body">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="flex">
                    <label class="sr-only" for="search-modal-input"><?php esc_html_e( 'Search for:', 'aqualuxe' ); ?></label>
                    <input type="search" id="search-modal-input" class="search-field w-full px-4 py-3 rounded-l border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="<?php esc_attr_e( 'Search...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <input type="hidden" name="post_type" value="product" />
                    <?php endif; ?>
                    
                    <button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-r">
                        <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
            
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <div class="search-categories mt-4">
                    <h3 class="text-sm font-medium mb-2"><?php esc_html_e( 'Popular Categories:', 'aqualuxe' ); ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $product_categories = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'number'     => 5,
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                        ) );

                        if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                            foreach ( $product_categories as $category ) {
                                echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="inline-block px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full">' . esc_html( $category->name ) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>