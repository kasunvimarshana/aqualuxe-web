<?php
/**
 * Franchise Locations Archive Template
 */
get_header();
?>
<main id="main" class="site-main franchise-archive">
    <section class="franchise-list">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('franchise-item'); ?>>
                    <div class="franchise-title"><?php the_title(); ?></div>
                    <div class="franchise-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php _e('No franchise locations found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
