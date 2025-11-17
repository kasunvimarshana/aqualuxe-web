<?php
/**
 * Recently Viewed Products Template
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if we have any products
if (empty($viewed_products)) {
    return;
}

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];

// Get products per row
$products_per_row = $module->get_option('products_per_row', 3);

$args = [
    'post_type' => 'product',
    'posts_per_page' => $products_per_row * 2,
    'post__in' => $viewed_products,
    'orderby' => 'post__in',
    'post_status' => 'publish',
];

$products = new WP_Query($args);

if ($products->have_posts()) :
    ?>
    <section class="recently-viewed-products">
        <h2><?php esc_html_e('Recently Viewed Products', 'aqualuxe'); ?></h2>
        
        <div class="products columns-<?php echo esc_attr($products_per_row); ?>">
            <?php while ($products->have_posts()) : $products->the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
endif;

wp_reset_postdata();