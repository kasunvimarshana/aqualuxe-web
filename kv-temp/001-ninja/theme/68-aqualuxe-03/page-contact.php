<?php
/**
 * Template Name: Contact Page
 * Description: The Contact page template for the AquaLuxe theme. Includes map and contact form.
 *
 * @package AquaLuxe
 */

global $post;
get_header();
?>

<main id="primary" class="site-main contact-page">
    <?php
    // Hero/Intro Section
    get_template_part('template-parts/contact/hero');

    // Map Section
    get_template_part('template-parts/contact/map');

    // Contact Form Section
    get_template_part('template-parts/contact/form');
    ?>

    <section class="contact-content entry-content">
        <?php
        // Main page content (editable in WP admin)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>
