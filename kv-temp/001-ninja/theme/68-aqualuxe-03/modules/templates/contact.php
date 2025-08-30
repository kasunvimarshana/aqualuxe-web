<?php
/**
 * AquaLuxe Contact Page Template
 */
get_header();
?>
<main id="main" class="site-main contact-page">
    <section class="contact-form-section">
        <h1>Contact Us</h1>
        <?php echo do_shortcode('[contact-form-7 id="123" title="Contact form"]'); ?>
        <div class="contact-map">
            <!-- Map embed or shortcode here -->
        </div>
    </section>
</main>
<?php get_footer(); ?>
