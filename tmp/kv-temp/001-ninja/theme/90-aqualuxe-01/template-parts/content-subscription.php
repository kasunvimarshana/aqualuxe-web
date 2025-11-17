<?php
/**
 * The template for displaying the Loop for the 'subscription' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('subscription-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="subscription-price">
            <?php echo get_post_meta(get_the_ID(), '_subscription_price', true); ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Subscribe Now', 'aqualuxe'); ?></a>
    </div>
</article>
