<?php
/**
 * The template for displaying the Loop for the 'auction' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('auction-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="auction-end-date">
            <?php printf(__('Ends on: %s', 'aqualuxe'), get_post_meta(get_the_ID(), '_auction_end_date', true)); ?>
        </div>
        <div class="current-bid">
            <?php printf(__('Current Bid: %s', 'aqualuxe'), get_post_meta(get_the_ID(), '_auction_current_bid', true)); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('View Auction', 'aqualuxe'); ?></a>
    </div>
</article>
