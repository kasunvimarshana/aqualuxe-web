<?php
/**
 * Single product template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<main id="main" class="site-main product-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php
        while (have_posts()):
            the_post();
            
            // Breadcrumbs
            if (function_exists('woocommerce_breadcrumb')):
                woocommerce_breadcrumb();
            endif;
            
            // Product notices
            if (function_exists('woocommerce_output_all_notices')):
                woocommerce_output_all_notices();
            endif;
        ?>
            
            <div class="product-container">
                
                <!-- Product Content -->
                <div class="grid lg:grid-cols-2 gap-12 mb-16">
                    
                    <!-- Product Images -->
                    <div class="product-images">
                        <?php
                        /**
                         * Hook: woocommerce_before_single_product_summary.
                         *
                         * @hooked woocommerce_show_product_sale_flash - 10
                         * @hooked woocommerce_show_product_images - 20
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>
                    </div>
                    
                    <!-- Product Summary -->
                    <div class="product-summary">
                        <?php
                        /**
                         * Hook: woocommerce_single_product_summary.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         * @hooked WC_Structured_Data::generate_product_data() - 60
                         */
                        do_action('woocommerce_single_product_summary');
                        ?>
                    </div>
                    
                </div>
                
                <!-- Product Data Tabs -->
                <div class="product-tabs mb-16">
                    <?php
                    /**
                     * Hook: woocommerce_after_single_product_summary.
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_upsell_display - 15
                     * @hooked woocommerce_output_related_products - 20
                     */
                    do_action('woocommerce_after_single_product_summary');
                    ?>
                </div>
                
            </div>
            
        <?php
        endwhile;
        ?>
        
    </div>
</main>

<?php
get_footer('shop');
?>
