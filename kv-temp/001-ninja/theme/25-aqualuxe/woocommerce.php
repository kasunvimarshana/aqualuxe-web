<?php
/**
 * The template for displaying WooCommerce pages
 *
 * This is the template that displays all WooCommerce pages by default.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4">
        <?php
        // Get the WooCommerce layout from theme options
        $woocommerce_layout = get_theme_mod('aqualuxe_woocommerce_layout', 'right-sidebar');

        // Override layout for single product pages if set
        if (is_product()) {
            $product_layout = get_theme_mod('aqualuxe_product_layout', 'no-sidebar');
            if ($product_layout) {
                $woocommerce_layout = $product_layout;
            }
        }

        // Determine if we should show the sidebar
        $show_sidebar = ($woocommerce_layout === 'right-sidebar' || $woocommerce_layout === 'left-sidebar') && is_active_sidebar('shop-sidebar');
        
        // Set the content width class based on layout
        $content_class = $show_sidebar ? 'lg:w-3/4' : 'w-full';
        
        // Set the content order class based on layout
        $content_order = $woocommerce_layout === 'left-sidebar' ? 'lg:order-2' : 'lg:order-1';
        
        // Set the sidebar order class based on layout
        $sidebar_order = $woocommerce_layout === 'left-sidebar' ? 'lg:order-1' : 'lg:order-2';
        ?>

        <div class="flex flex-wrap lg:flex-nowrap <?php echo $woocommerce_layout === 'left-sidebar' ? 'flex-row-reverse' : ''; ?>">
            <div class="w-full <?php echo esc_attr($content_class); ?> <?php echo esc_attr($content_order); ?>">
                <?php woocommerce_content(); ?>
            </div>

            <?php if ($show_sidebar) : ?>
                <div class="w-full lg:w-1/4 mt-8 lg:mt-0 <?php echo esc_attr($sidebar_order); ?> <?php echo $woocommerce_layout === 'left-sidebar' ? 'lg:pr-8' : 'lg:pl-8'; ?>">
                    <?php dynamic_sidebar('shop-sidebar'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();