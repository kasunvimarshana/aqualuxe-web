<?php
defined('ABSPATH') || exit;
get_header('shop'); ?>

<header class="woocommerce-products-header">
    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
</header>

<div class="aqualuxe-product-grid">
    <?php if (woocommerce_product_loop()) {
        woocommerce_product_loop_start();
        while (have_posts()) {
            the_post();
            wc_get_template_part('content', 'product');
        }
        woocommerce_product_loop_end();
        do_action('woocommerce_after_shop_loop');
    } else {
        do_action('woocommerce_no_products_found');
    } ?>
</div>

<?php get_footer('shop'); ?>