<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Use standard header instead of shop-specific header to avoid dependency issues
get_header(); 
?>

<div class="container mx-auto px-4 py-12">
    <?php
        /**
         * woocommerce_before_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         */
        do_action('woocommerce_before_main_content');
    ?>

    <div class="product-layout grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="product-main lg:col-span-8">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>

                <?php wc_get_template_part('content', 'single-product'); ?>

            <?php endwhile; // end of the loop. ?>
        </div>

        <div class="product-sidebar lg:col-span-4">
            <?php
                /**
                 * Hook: woocommerce_sidebar.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action('woocommerce_sidebar');
            ?>

            <?php
            // Related products
            if (function_exists('woocommerce_related_products')) {
                $args = array(
                    'posts_per_page' => 3,
                    'columns'        => 1,
                    'orderby'        => 'rand',
                );
                woocommerce_related_products($args);
            }
            ?>

            <?php
            // Recently viewed products
            if (function_exists('woocommerce_output_recently_viewed_products')) {
                $args = array(
                    'posts_per_page' => 3,
                );
                woocommerce_output_recently_viewed_products($args);
            }
            ?>
        </div>
    </div>

    <?php
        /**
         * woocommerce_after_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action('woocommerce_after_main_content');
    ?>
</div>

<?php
// Use standard footer instead of shop-specific footer to avoid dependency issues
get_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */