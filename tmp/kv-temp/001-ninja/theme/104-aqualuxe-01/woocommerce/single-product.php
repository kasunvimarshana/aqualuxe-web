<?php
/**
 * Single Product Template Override
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <div class="woocommerce-single-product max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <!-- Breadcrumb -->
                <nav class="breadcrumb-nav mb-8">
                    <?php woocommerce_breadcrumb(['delimiter' => ' <span class="mx-2 text-gray-400">/</span> ']); ?>
                </nav>
                
                <div class="product-main grid lg:grid-cols-2 gap-12">
                    
                    <!-- Product Images -->
                    <div class="product-images">
                        <?php
                        /**
                         * Hook: woocommerce_before_single_product_summary
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>
                    </div>
                    
                    <!-- Product Summary -->
                    <div class="product-summary">
                        <?php
                        /**
                         * Hook: woocommerce_single_product_summary
                         */
                        do_action('woocommerce_single_product_summary');
                        ?>
                    </div>
                    
                </div>
                
                <!-- Product Data Tabs -->
                <div class="product-tabs mt-16">
                    <?php
                    /**
                     * Hook: woocommerce_output_product_data_tabs
                     */
                    do_action('woocommerce_output_product_data_tabs');
                    ?>
                </div>
                
                <!-- Related Products -->
                <div class="related-products mt-16">
                    <?php
                    /**
                     * Hook: woocommerce_output_related_products
                     */
                    do_action('woocommerce_output_related_products');
                    ?>
                </div>
                
            </div>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php get_footer(); ?>