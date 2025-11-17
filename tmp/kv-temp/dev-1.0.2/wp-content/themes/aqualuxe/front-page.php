<?php

/**
 * Front Page Template
 *
 * @package aqualuxe
 */

get_header(); ?>

<div class="aqualuxe-home">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('aqualuxe_hero_title', 'Discover the Beauty of Ornamental Fish')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('aqualuxe_hero_subtitle', 'Premium quality ornamental fish for enthusiasts and collectors')); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-primary"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
                    <a href="#featured-products" class="button button-secondary"><?php esc_html_e('View Collection', 'aqualuxe'); ?></a>
                </div>
            </div>
            <div class="hero-image">
                <?php
                $hero_image = get_theme_mod('aqualuxe_hero_image');
                if ($hero_image) :
                    echo wp_get_attachment_image($hero_image, 'full', false, array('class' => 'hero-img'));
                else :
                    echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/hero-fish.jpg" alt="' . esc_attr__('Ornamental Fish', 'aqualuxe') . '" class="hero-img">';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured-products" class="featured-products-section">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e('Featured Fish', 'aqualuxe'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Our most popular ornamental fish varieties', 'aqualuxe'); ?></p>

            <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured" class="featured-products-grid"]'); ?>

            <div class="text-center">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-primary"><?php esc_html_e('View All Products', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <h2 class="section-title"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h2>
                <p><?php echo esc_html(get_theme_mod('aqualuxe_about_text', 'We are passionate breeders of premium ornamental fish, dedicated to providing the highest quality specimens to enthusiasts and collectors worldwide.')); ?></p>
                <div class="about-features">
                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h3><?php esc_html_e('Premium Quality', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Our fish are bred with care and attention to detail', 'aqualuxe'); ?></p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3><?php esc_html_e('Global Shipping', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('We ship our fish safely to destinations worldwide', 'aqualuxe'); ?></p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3><?php esc_html_e('Expert Care', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Our team provides expert advice and support', 'aqualuxe'); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="button button-primary"><?php esc_html_e('Learn More About Us', 'aqualuxe'); ?></a>
            </div>
            <div class="about-image">
                <?php
                $about_image = get_theme_mod('aqualuxe_about_image');
                if ($about_image) :
                    echo wp_get_attachment_image($about_image, 'full', false, array('class' => 'about-img'));
                else :
                    echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/about-fish.jpg" alt="' . esc_attr__('Our Fish Farm', 'aqualuxe') . '" class="about-img">';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e('Customer Testimonials', 'aqualuxe'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('What our customers say about us', 'aqualuxe'); ?></p>

            <div class="testimonials-grid">
                <?php
                $testimonials = get_theme_mod('aqualuxe_testimonials', json_encode(array(
                    array(
                        'text' => 'The quality of fish from AquaLuxe is exceptional. My aquarium has never looked better!',
                        'author' => 'John D.',
                        'location' => 'New York, USA'
                    ),
                    array(
                        'text' => 'Fast shipping and healthy fish. I\'ve been a customer for years and will continue to be.',
                        'author' => 'Sarah M.',
                        'location' => 'London, UK'
                    ),
                    array(
                        'text' => 'The variety of species is amazing. I\'ve found fish here that I couldn\'t find anywhere else.',
                        'author' => 'Michael T.',
                        'location' => 'Sydney, Australia'
                    )
                )));

                $testimonials_data = json_decode($testimonials, true);

                if ($testimonials_data) :
                    foreach ($testimonials_data as $testimonial) :
                ?>
                        <div class="testimonial">
                            <div class="testimonial-text">
                                <p><?php echo esc_html($testimonial['text']); ?></p>
                            </div>
                            <div class="testimonial-author">
                                <h4><?php echo esc_html($testimonial['author']); ?></h4>
                                <p><?php echo esc_html($testimonial['location']); ?></p>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h2><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('Get the latest updates on new species, special offers, and fish care tips', 'aqualuxe'); ?></p>
                <?php echo do_shortcode('[newsletter_form]'); ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>