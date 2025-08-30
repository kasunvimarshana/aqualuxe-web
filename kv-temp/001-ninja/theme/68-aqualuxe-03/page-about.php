<?php
/**
 * Template Name: About Page
 * Description: The About page template for the AquaLuxe theme. Modular, extensible, and ready for block or classic content.
 *
 * @package AquaLuxe
 */

global $post;
get_header();
?>

<main id="primary" class="site-main about-page">
    <?php
    // Hero/About Intro Section
    get_template_part('template-parts/about/hero');

    // Company Story/History Section
    get_template_part('template-parts/about/story');

    // Team Section
    get_template_part('template-parts/about/team');

    // Values/Mission Section
    get_template_part('template-parts/about/values');

    // Awards/Certifications Section
    get_template_part('template-parts/about/awards');

    // Partners/Clients Section
    get_template_part('template-parts/about/partners');

    // Testimonials Section (reuse home testimonial if exists)
    if ( locate_template('template-parts/home/testimonials.php') ) {
        get_template_part('template-parts/home/testimonials');
    }

    // Call to Action Section
    get_template_part('template-parts/about/cta');
    ?>

    <section class="about-content entry-content">
        <?php
        // Main page content (editable in WP admin)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>
