<?php
/**
 * Homepage Template
 * Template Name: AquaLuxe Homepage
 */
get_header();
?>
<main id="main" class="site-main homepage">
    <?php get_template_part('template-parts/home/hero'); ?>
    <?php get_template_part('template-parts/home/services'); ?>
    <?php get_template_part('template-parts/home/testimonials'); ?>
    <?php get_template_part('template-parts/home/events'); ?>
    <?php get_template_part('template-parts/home/products'); ?>
</main>
<?php get_footer(); ?>
