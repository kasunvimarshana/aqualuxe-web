<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop'); ?>

<div class="container-fluid py-12">
    <?php
    // Breadcrumbs
    if (function_exists('aqualuxe_breadcrumbs')) :
        aqualuxe_breadcrumbs();
    endif;
    ?>

    <?php
    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action('woocommerce_before_main_content');
    ?>

    <div class="product-main bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part('content', 'single-product'); ?>

        <?php endwhile; // end of the loop. ?>
    </div>

    <?php
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action('woocommerce_after_main_content');
    ?>

    <?php
    /**
     * Hook: aqualuxe_after_single_product.
     *
     * @hooked aqualuxe_recently_viewed_products - 10
     * @hooked aqualuxe_related_products_custom - 20
     */
    do_action('aqualuxe_after_single_product');
    ?>
</div>

<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */