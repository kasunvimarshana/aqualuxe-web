<?php
/**
 * Testimonials Archive Template
 */
get_header();
?>
<main id="main" class="site-main testimonials-archive">
    <section class="testimonials-list">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class('testimonial-item'); ?>>
                    <div class="testimonial-content"><?php the_content(); ?></div>
                    <div class="testimonial-author"><?php the_title(); ?></div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php _e('No testimonials found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
