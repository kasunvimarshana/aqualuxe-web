<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header bg-gradient-to-r from-blue-500 to-teal-400 text-white py-12 mb-8">
    <div class="container mx-auto px-4">
        <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            <h1 class="woocommerce-products-header__title page-title text-4xl font-bold mb-4"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>

        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action( 'woocommerce_archive_description' );
        ?>
    </div>
</header>

<div class="container mx-auto px-4">
    <div class="flex flex-wrap lg:flex-nowrap">
        <!-- Sidebar with filters -->
        <div class="w-full lg:w-1/4 lg:pr-8 mb-8 lg:mb-0">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    <?php esc_html_e( 'Product Filters', 'aqualuxe' ); ?>
                </h2>
                
                <?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
                    <div class="shop-sidebar">
                        <?php dynamic_sidebar( 'shop-sidebar' ); ?>
                    </div>
                <?php else : ?>
                    <div class="product-filters-form">
                        <?php 
                        // Display product categories
                        $product_categories = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                        ) );
                        
                        if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) : ?>
                            <div class="filter-section mb-6">
                                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">
                                    <?php esc_html_e( 'Categories', 'aqualuxe' ); ?>
                                </h3>
                                <ul class="space-y-2">
                                    <?php foreach ( $product_categories as $category ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                                <?php echo esc_html( $category->name ); ?>
                                                <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">(<?php echo esc_html( $category->count ); ?>)</span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display price filter if WooCommerce Price Filter widget is not active
                        if ( ! is_active_widget( false, false, 'woocommerce_price_filter', true ) ) : ?>
                            <div class="filter-section mb-6">
                                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">
                                    <?php esc_html_e( 'Price Range', 'aqualuxe' ); ?>
                                </h3>
                                <div class="price-slider-container">
                                    <div class="price-inputs flex justify-between mb-4">
                                        <div class="min-price">
                                            <label for="min-price" class="sr-only"><?php esc_html_e( 'Minimum Price', 'aqualuxe' ); ?></label>
                                            <input type="number" id="min-price" name="min_price" placeholder="<?php esc_attr_e( 'Min', 'aqualuxe' ); ?>" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" min="0">
                                        </div>
                                        <div class="price-separator mx-2 self-center">-</div>
                                        <div class="max-price">
                                            <label for="max-price" class="sr-only"><?php esc_html_e( 'Maximum Price', 'aqualuxe' ); ?></label>
                                            <input type="number" id="max-price" name="max_price" placeholder="<?php esc_attr_e( 'Max', 'aqualuxe' ); ?>" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" min="0">
                                        </div>
                                    </div>
                                    <button type="button" class="filter-price-button w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">
                                        <?php esc_html_e( 'Filter', 'aqualuxe' ); ?>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display product attributes for filtering
                        $attribute_taxonomies = wc_get_attribute_taxonomies();
                        if ( ! empty( $attribute_taxonomies ) ) :
                            foreach ( $attribute_taxonomies as $attribute ) :
                                $taxonomy = 'pa_' . $attribute->attribute_name;
                                $terms = get_terms( array(
                                    'taxonomy'   => $taxonomy,
                                    'hide_empty' => true,
                                ) );
                                
                                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                                    <div class="filter-section mb-6">
                                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">
                                            <?php echo esc_html( $attribute->attribute_label ); ?>
                                        </h3>
                                        <div class="attribute-options space-y-2">
                                            <?php foreach ( $terms as $term ) : ?>
                                                <div class="attribute-option">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="filter_<?php echo esc_attr( $taxonomy ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" class="form-checkbox h-5 w-5 text-primary-600 transition duration-150 ease-in-out">
                                                        <span class="ml-2 text-gray-700 dark:text-gray-300"><?php echo esc_html( $term->name ); ?></span>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif;
                            endforeach;
                        endif; ?>
                        
                        <div class="filter-actions mt-8">
                            <button type="button" class="reset-filters w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-2 px-4 rounded transition-colors duration-300">
                                <?php esc_html_e( 'Reset Filters', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Main product content -->
        <div class="w-full lg:w-3/4">
            <?php
            if ( woocommerce_product_loop() ) {
                /**
                 * Hook: woocommerce_before_shop_loop.
                 *
                 * @hooked woocommerce_output_all_notices - 10
                 * @hooked woocommerce_result_count - 20
                 * @hooked woocommerce_catalog_ordering - 30
                 */
                ?>
                <div class="shop-controls flex flex-wrap items-center justify-between mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                </div>
                <?php

                woocommerce_product_loop_start();

                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();

                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );

                        wc_get_template_part( 'content', 'product' );
                    }
                }

                woocommerce_product_loop_end();

                /**
                 * Hook: woocommerce_after_shop_loop.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action( 'woocommerce_after_shop_loop' );
            } else {
                /**
                 * Hook: woocommerce_no_products_found.
                 *
                 * @hooked wc_no_products_found - 10
                 */
                do_action( 'woocommerce_no_products_found' );
            }
            ?>
        </div>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );