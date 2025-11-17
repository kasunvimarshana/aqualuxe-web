<article class="aqualuxe-product-card">
    <a href="<?php the_permalink(); ?>">
        <?php woocommerce_template_loop_product_thumbnail(); ?>
        <h2><?php the_title(); ?></h2>
        <?php woocommerce_template_loop_price(); ?>
    </a>
</article>