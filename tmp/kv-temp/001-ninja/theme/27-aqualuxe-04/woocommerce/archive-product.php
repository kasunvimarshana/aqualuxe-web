<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
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
<div class="container mx-auto px-4 py-12">
    <header class="woocommerce-products-header mb-12 text-center">
        <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            <h1 class="woocommerce-products-header__title page-title text-4xl md:text-5xl mb-4"><?php woocommerce_page_title(); ?></h1>
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
    </header>

    <div class="shop-layout grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="shop-sidebar lg:col-span-3">
            <div class="filter-sidebar card p-6 sticky top-32">
                <h2 class="text-xl font-bold mb-6"><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></h2>
                
                <button class="filter-toggle lg:hidden flex items-center justify-between w-full mb-6 text-lg font-medium" aria-expanded="false">
                    <?php esc_html_e( 'Show Filters', 'aqualuxe' ); ?>
                    <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div class="filter-content hidden lg:block">
                    <?php
                    /**
                     * Hook: woocommerce_sidebar.
                     *
                     * @hooked woocommerce_get_sidebar - 10
                     */
                    do_action( 'woocommerce_sidebar' );
                    ?>
                    
                    <?php if ( function_exists( 'dynamic_sidebar' ) && is_active_sidebar( 'shop-filters' ) ) : ?>
                        <?php dynamic_sidebar( 'shop-filters' ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="shop-main lg:col-span-9">
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
                <div class="shop-controls flex flex-wrap items-center justify-between mb-8">
                    <div class="shop-result-count mb-4 md:mb-0">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    <div class="shop-ordering flex items-center">
                        <?php woocommerce_catalog_ordering(); ?>
                        
                        <div class="view-switcher ml-4 flex border border-dark-200 rounded-md overflow-hidden dark:border-dark-700">
                            <button class="view-grid w-10 h-10 flex items-center justify-center bg-white dark:bg-dark-800 border-r border-dark-200 dark:border-dark-700 active" aria-label="<?php esc_attr_e( 'Grid view', 'aqualuxe' ); ?>" data-view="grid">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button class="view-list w-10 h-10 flex items-center justify-center bg-white dark:bg-dark-800" aria-label="<?php esc_attr_e( 'List view', 'aqualuxe' ); ?>" data-view="list">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
                
                do_action( 'woocommerce_before_shop_loop' );

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
            
            <?php
            // Featured products section
            if ( is_shop() && !is_search() && !is_filtered() ) {
                $featured_products = wc_get_products( array(
                    'featured' => true,
                    'limit' => 4,
                    'orderby' => 'rand',
                ) );
                
                if ( !empty( $featured_products ) ) : ?>
                    <div class="featured-products mt-16">
                        <h2 class="text-2xl font-bold mb-8"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <?php foreach ( $featured_products as $featured_product ) : ?>
                                <div class="featured-product card overflow-hidden">
                                    <a href="<?php echo esc_url( $featured_product->get_permalink() ); ?>" class="block">
                                        <?php if ( $featured_product->get_image_id() ) : ?>
                                            <div class="featured-product-image relative overflow-hidden">
                                                <?php echo wp_get_attachment_image( $featured_product->get_image_id(), 'woocommerce_thumbnail', false, array( 'class' => 'w-full h-48 object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
                                                
                                                <?php if ( $featured_product->is_on_sale() ) : ?>
                                                    <div class="product-badge badge-sale absolute top-4 right-4">
                                                        <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="featured-product-content p-4">
                                            <h3 class="text-lg font-bold mb-2"><?php echo esc_html( $featured_product->get_name() ); ?></h3>
                                            
                                            <div class="featured-product-price text-primary-600 dark:text-primary-400 font-medium">
                                                <?php echo wp_kses_post( $featured_product->get_price_html() ); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif;
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

/**
 * Check if WooCommerce filters are active
 */
function is_filtered() {
    return isset( $_GET['orderby'] ) || isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) || isset( $_GET['rating_filter'] ) || isset( $_GET['filter_color'] ) || isset( $_GET['filter_size'] );
}