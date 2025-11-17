<?php
/**
 * The sidebar containing the main widget area
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-primary') && !is_active_sidebar('sidebar-shop')) {
    return;
}

// Choose the appropriate sidebar
$sidebar_id = 'sidebar-primary';
if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag() || is_product())) {
    $sidebar_id = 'sidebar-shop';
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary">
    <?php dynamic_sidebar($sidebar_id); ?>
</aside><!-- #secondary -->