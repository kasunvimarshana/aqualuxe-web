<?php
/**
 * Template Name: FAQ Page
 * Description: The FAQ page template for the AquaLuxe theme. Modular and dynamic.
 *
 * @package AquaLuxe
 */

global $post;
get_header();
?>

<main id="primary" class="site-main faq-page">
    <?php
    // Hero/Intro Section
    get_template_part('template-parts/faq/hero');

    // FAQ List Section
    get_template_part('template-parts/faq/list');
    ?>

    <section class="faq-content entry-content">
        <?php
        // Main page content (editable in WP admin)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>
