<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');

// Get product layout from theme options
$product_layout = aqualuxe_get_option('product_layout', 'default');
$container_class = aqualuxe_get_container_class();
$content_class = aqualuxe_get_content_class();
$sidebar_class = aqualuxe_get_sidebar_class();
$show_sidebar = aqualuxe_get_option('product_sidebar', false);
?>

<div id="primary" class="content-area product-layout-<?php echo esc_attr($product_layout); ?>">
    <main id="main" class="site-main">
        <div class="<?php echo esc_attr($container_class); ?>">
            <?php
            /**
             * Hook: woocommerce_before_main_content.
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action('woocommerce_before_main_content');
            ?>

            <?php if ($show_sidebar) : ?>
                <div class="row">
                    <div class="<?php echo esc_attr($content_class); ?>">
            <?php endif; ?>

            <?php while (have_posts()) : ?>
                <?php the_post(); ?>

                <?php wc_get_template_part('content', 'single-product'); ?>

            <?php endwhile; // end of the loop. ?>

            <?php if ($show_sidebar) : ?>
                    </div>
                    <div class="<?php echo esc_attr($sidebar_class); ?>">
                        <?php
                        /**
                         * Hook: woocommerce_sidebar.
                         *
                         * @hooked woocommerce_get_sidebar - 10
                         */
                        do_action('woocommerce_sidebar');
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_after_main_content.
             *
             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
             */
            do_action('woocommerce_after_main_content');
            ?>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer('shop');