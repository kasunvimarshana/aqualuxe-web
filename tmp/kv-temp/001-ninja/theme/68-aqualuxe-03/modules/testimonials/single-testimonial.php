<?php
/**
 * Single Testimonial Template
 */
get_header();
?>
<main id="main" class="site-main single-testimonial">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('testimonial-item'); ?>>
            <div class="testimonial-content"><?php the_content(); ?></div>
            <div class="testimonial-author"><?php the_title(); ?></div>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
