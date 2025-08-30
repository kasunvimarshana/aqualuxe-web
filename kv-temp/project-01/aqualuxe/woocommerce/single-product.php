<?php
/**
 * The Template for displaying all single products
 *
 * @package AquaLuxe
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while (have_posts()) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part('content', 'single-product'); ?>

        <?php endwhile; ?>

    </main>
</div>

<?php
do_action('woocommerce_output_related_products_args');
get_sidebar('shop');
get_footer('shop');
?>