<?php
/**
 * AquaLuxe Homepage Template
 * Modular homepage with sections
 */
get_header();
?>
<main id="main" class="site-main homepage">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/featured-services'); ?>
    <?php get_template_part('template-parts/testimonials'); ?>
    <?php get_template_part('template-parts/latest-events'); ?>
    <?php get_template_part('template-parts/featured-products'); ?>
</main>
<?php get_footer(); ?>
