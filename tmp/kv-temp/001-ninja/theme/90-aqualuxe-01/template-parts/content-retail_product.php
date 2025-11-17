<?php
/**
 * The template for displaying the Loop for the 'retail_product' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('retail-product-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="retail-price">
            <?php printf(__('Retail Price: %s', 'aqualuxe'), get_post_meta(get_the_ID(), '_retail_price', true)); ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('View Product', 'aqualuxe'); ?></a>
    </div>
</article>
