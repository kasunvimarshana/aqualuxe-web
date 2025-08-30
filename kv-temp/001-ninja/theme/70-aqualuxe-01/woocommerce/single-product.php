<?php
/**
 * Single Product Template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<div class="single-product-container">
    <div class="container mx-auto px-4 py-8">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-content', get_the_ID()); ?>>
                
                <div class="product-navigation mb-6">
                    <?php
                    /**
                     * woocommerce_single_product_summary hook.
                     */
                    // Breadcrumb navigation
                    if (function_exists('woocommerce_breadcrumb')) {
                        woocommerce_breadcrumb();
                    }
                    ?>
                </div>

                <div class="product-main grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-12">
                    <!-- Product Images -->
                    <div class="product-images">
                        <?php
                        /**
                         * woocommerce_before_single_product_summary hook.
                         *
                         * @hooked woocommerce_show_product_images - 20
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>
                    </div>

                    <!-- Product Summary -->
                    <div class="product-summary">
                        <?php
                        /**
                         * woocommerce_single_product_summary hook.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         */
                        do_action('woocommerce_single_product_summary');
                        ?>

                        <!-- Additional Product Actions -->
                        <div class="product-actions mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4">
                                <!-- Wishlist Button -->
                                <button class="wishlist-btn flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span>Add to Wishlist</span>
                                </button>

                                <!-- Compare Button -->
                                <button class="compare-btn flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:border-blue-500 hover:text-blue-500 transition-colors" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span>Compare</span>
                                </button>

                                <!-- Share Button -->
                                <div class="share-dropdown relative">
                                    <button class="share-btn flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:border-green-500 hover:text-green-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                        </svg>
                                        <span>Share</span>
                                    </button>
                                    
                                    <div class="share-menu hidden absolute top-full mt-2 right-0 bg-white border border-gray-200 rounded-lg shadow-lg p-2 w-48 z-10">
                                        <a href="#" class="share-facebook flex items-center gap-2 p-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                            <span>Facebook</span>
                                        </a>
                                        <a href="#" class="share-twitter flex items-center gap-2 p-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                            <span>Twitter</span>
                                        </a>
                                        <a href="#" class="share-pinterest flex items-center gap-2 p-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                            <span>Pinterest</span>
                                        </a>
                                        <button class="share-copy flex items-center gap-2 p-2 text-sm text-gray-700 hover:bg-gray-100 rounded w-full text-left">
                                            <span>Copy Link</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Tabs -->
                <div class="product-tabs">
                    <?php
                    /**
                     * woocommerce_after_single_product_summary hook.
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_upsell_display - 15
                     * @hooked woocommerce_output_related_products - 20
                     */
                    do_action('woocommerce_after_single_product_summary');
                    ?>
                </div>

                <!-- Related Products -->
                <div class="related-products mt-16">
                    <?php woocommerce_output_related_products(); ?>
                </div>

                <!-- Recently Viewed Products -->
                <div class="recently-viewed-products mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Recently Viewed</h2>
                    <div class="recently-viewed-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <!-- This would be populated by JavaScript -->
                    </div>
                </div>

            </div>

        <?php endwhile; // end of the loop. ?>
    </div>
</div>

<!-- Quick View Modal Template -->
<div id="quick-view-modal" class="modal quick-view-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-content max-w-4xl">
            <div class="modal-header flex justify-between items-center p-6 border-b">
                <h3 class="modal-title text-xl font-semibold">Quick View</h3>
                <button class="modal-close text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="modal-body p-6">
                <!-- Quick view content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
?>
