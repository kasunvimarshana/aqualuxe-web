<?php
/**
 * Portfolio Archive Template
 */
get_header();
?>
<main id="main" class="site-main portfolio-archive">
    <section class="portfolio-grid">
        <?php if ( have_posts() ) : ?>
            <div class="portfolio-masonry">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class('portfolio-item'); ?>>
                        <a href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="portfolio-thumb">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            <h2 class="portfolio-title"><?php the_title(); ?></h2>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php _e('No portfolio items found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
