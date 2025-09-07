<?php
/**
 * The template for displaying the Loop for the 'multivendor_product' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('multivendor-product-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="product-vendor">
            <?php
            $vendor_id = get_post_meta(get_the_ID(), '_vendor_id', true);
            $vendor = get_user_by('ID', $vendor_id);
            if ($vendor) {
                printf(__('Sold by: %s', 'aqualuxe'), $vendor->display_name);
            }
            ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('View Product', 'aqualuxe'); ?></a>
    </div>
</article>
