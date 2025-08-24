<?php
/**
 * Auctions Archive Template
 */
get_header();
?>
<main id="main" class="site-main auctions-archive">
    <section class="auction-list">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('auction-item'); ?>>
                    <div class="auction-title"><?php the_title(); ?></div>
                    <div class="auction-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php _e('No auction items found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
