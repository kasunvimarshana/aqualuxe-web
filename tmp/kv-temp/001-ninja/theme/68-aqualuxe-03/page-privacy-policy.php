<?php
/**
 * Template Name: Privacy Policy
 * Description: The Privacy Policy page template for the AquaLuxe theme.
 *
 * @package AquaLuxe
 */
get_header();
?>
<main id="primary" class="site-main legal-page privacy-policy">
    <section class="legal-hero">
        <div class="container">
            <h1 class="legal-title">Privacy Policy</h1>
        </div>
    </section>
    <section class="legal-content entry-content">
        <?php
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </section>
</main>
<?php get_footer(); ?>
