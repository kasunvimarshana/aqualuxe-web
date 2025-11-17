<?php
/**
 * AquaLuxe About Page Template
 */
get_header();
?>
<main id="main" class="site-main about-page">
    <section class="about-intro">
        <h1>About AquaLuxe</h1>
        <div class="about-content">
            <?php the_content(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
