<?php
/**
 * Single Auction Item Template
 */
get_header();
?>
<main id="main" class="site-main single-auction">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('auction-item'); ?>>
            <div class="auction-title"><?php the_title(); ?></div>
            <div class="auction-content"><?php the_content(); ?></div>
            <div class="auction-timer" id="auction-timer">Auction ends in: <span>00:00:00</span></div>
            <form class="auction-bid-form">
                <input type="number" name="bid_amount" placeholder="Your bid" required />
                <button type="submit">Place Bid</button>
            </form>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
