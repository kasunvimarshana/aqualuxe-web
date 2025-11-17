<?php
/**
 * Template Name: Services Page
 * Description: The Services page template for the AquaLuxe theme. Modular, dynamic, and extensible.
 *
 * @package AquaLuxe
 */

global $post;
get_header();
?>

<main id="primary" class="site-main services-page">
    <?php
    // Hero/Intro Section
    get_template_part('template-parts/services/hero');

    // Services Grid Section
    get_template_part('template-parts/services/grid');

    // Service Categories Filter (optional)
    get_template_part('template-parts/services/categories');

    // Booking/CTA Section
    get_template_part('template-parts/services/cta');
    ?>

    <section class="services-content entry-content">
        <?php
        // Main page content (editable in WP admin)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>
