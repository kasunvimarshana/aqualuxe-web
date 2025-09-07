<?php
/**
 * The template for displaying the Loop for the 'classified' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('classified-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="classified-price">
            <?php echo get_post_meta(get_the_ID(), '_classified_price', true); ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('View Listing', 'aqualuxe'); ?></a>
    </div>
</article>
