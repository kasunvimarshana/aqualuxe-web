<?php

/**
 * Template Name: Contact Us
 *
 * @package aqualuxe
 */

get_header(); ?>

<div class="aqualuxe-page contact-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div>

        <div class="contact-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="contact-text">
                        <?php the_content(); ?>
                    </div>
            <?php endwhile;
            endif; ?>

            <div class="contact-container">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <h2><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h2>
                    <?php echo do_shortcode('[contact-form-7 id="contact-form" title="Contact Form"]'); ?>
                </div>

                <!-- Contact Information -->
                <div class="contact-info-container">
                    <h2><?php esc_html_e('Contact Information', 'aqualuxe'); ?></h2>
                    <div class="contact-info">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-text">
                                <h3><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                                <p><?php echo esc_html(get_theme_mod('aqualuxe_address', '123 Aqua Lane, Ocean City, OC 12345')); ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <h3><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                                <p><?php echo esc_html(get_theme_mod('aqualuxe_phone', '+1 (555) 123-4567')); ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <h3><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                                <p><?php echo esc_html(get_theme_mod('aqualuxe_email', 'info@aqualuxe.com')); ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-text">
                                <h3><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h3>
                                <p><?php echo esc_html(get_theme_mod('aqualuxe_hours', 'Monday - Friday: 9am - 5pm<br>Saturday: 10am - 4pm<br>Sunday: Closed')); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-media">
                        <h3><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h3>
                        <div class="social-icons">
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_facebook', '#')); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_twitter', '#')); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_instagram', '#')); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_youtube', '#')); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="map-container">
                <h2><?php esc_html_e('Find Us', 'aqualuxe'); ?></h2>
                <div class="map">
                    <?php echo get_theme_mod('aqualuxe_map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.123456789!2d-74.0059413!3d40.7127837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzQ2LjAiTiA3NMKwMDAnMjEuNCJX!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'); ?>
                </div>
            </div>

            <!-- FAQ -->
            <div class="faq-section">
                <h2><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h2>
                <div class="faq-container">
                    <?php
                    $faqs = get_theme_mod('aqualuxe_faqs', json_encode(array(
                        array(
                            'question' => 'Do you ship internationally?',
                            'answer' => 'Yes, we ship our fish to many countries around the world. Please contact us for specific shipping information to your location.'
                        ),
                        array(
                            'question' => 'How do you ensure the health of your fish during shipping?',
                            'answer' => 'We use specialized packaging and oxygen supply systems to ensure fish arrive healthy. We also have a live arrival guarantee for all our shipments.'
                        ),
                        array(
                            'question' => 'What payment methods do you accept?',
                            'answer' => 'We accept all major credit cards, PayPal, and bank transfers for international orders.'
                        ),
                        array(
                            'question' => 'Can I visit your facility?',
                            'answer' => 'We welcome visits by appointment only. Please contact us to schedule a tour of our breeding facility.'
                        )
                    )));

                    $faq_data = json_decode($faqs, true);

                    if ($faq_data) :
                        foreach ($faq_data as $index => $faq) :
                    ?>
                            <div class="faq-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="faq-question">
                                    <h3><?php echo esc_html($faq['question']); ?></h3>
                                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p><?php echo esc_html($faq['answer']); ?></p>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>