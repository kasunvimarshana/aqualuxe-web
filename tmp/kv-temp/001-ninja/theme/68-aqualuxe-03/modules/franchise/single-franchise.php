<?php
/**
 * Single Franchise Location Template
 */
get_header();
?>
<main id="main" class="site-main single-franchise">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('franchise-item'); ?>>
            <div class="franchise-title"><?php the_title(); ?></div>
            <div class="franchise-content"><?php the_content(); ?></div>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
