<?php
/**
 * The template for displaying the Loop for the 'b2c_product' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('b2c-product-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="b2c-price">
            <?php printf(__('Price: %s', 'aqualuxe'), get_post_meta(get_the_ID(), '_b2c_price', true)); ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('View Product', 'aqualuxe'); ?></a>
    </div>
</article>
