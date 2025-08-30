<?php
/**
 * Template Name: Cookie Policy
 * Description: The Cookie Policy page template for the AquaLuxe theme.
 *
 * @package AquaLuxe
 */
get_header();
?>
<main id="primary" class="site-main legal-page cookie-policy">
    <section class="legal-hero">
        <div class="container">
            <h1 class="legal-title">Cookie Policy</h1>
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
