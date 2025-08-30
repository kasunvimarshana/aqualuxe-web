<?php

/**
 * Template Name: Franchise Page
 *
 * This template is used for the franchise inquiry page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Franchise Hero Section -->
    <section class="franchise-hero">
        <div class="franchise-hero-overlay"></div>
        <div class="container mx-auto px-4">
            <div class="franchise-hero-content">
                <h1 class="franchise-hero-title"><?php echo esc_html(get_the_title()); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <div class="franchise-hero-subtitle">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
                <a href="#franchise-inquiry" class="franchise-hero-cta"><?php esc_html_e('Start Your Journey', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <!-- Franchise Benefits Section -->
        <?php echo do_shortcode('[franchise_benefits]'); ?>

        <!-- Franchise Stats Section -->
        <?php echo do_shortcode('[franchise_stats]'); ?>

        <!-- Franchise Process Section -->
        <?php echo do_shortcode('[franchise_process]'); ?>

        <!-- Franchise Testimonials Section -->
        <?php echo do_shortcode('[franchise_testimonials]'); ?>

        <!-- Franchise FAQ Section -->
        <?php echo do_shortcode('[franchise_faq]'); ?>

        <!-- Franchise Inquiry Form Section -->
        <div id="franchise-inquiry" class="py-12">
            <?php echo do_shortcode('[franchise_inquiry]'); ?>
        </div>
    </div>
</main>

<?php
get_footer();
