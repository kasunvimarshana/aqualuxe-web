<?php
/**
 * Template part for displaying the contact section on the homepage
 *
 * @package AquaLuxe
 */

// Get contact section settings from customizer
$contact_title = get_theme_mod('aqualuxe_contact_title', __('Get In Touch', 'aqualuxe'));
$contact_description = get_theme_mod('aqualuxe_contact_description', __('Have questions or need assistance? Contact us today!', 'aqualuxe'));
$contact_form_shortcode = get_theme_mod('aqualuxe_contact_form_shortcode', '');
$contact_address = get_theme_mod('aqualuxe_contact_address', '123 Aquarium Street, Ocean City, CA 90210');
$contact_phone = get_theme_mod('aqualuxe_contact_phone', '+1 (555) 123-4567');
$contact_email = get_theme_mod('aqualuxe_contact_email', 'info@aqualuxe.com');
$contact_hours = get_theme_mod('aqualuxe_contact_hours', 'Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed');
$contact_map_embed = get_theme_mod('aqualuxe_contact_map_embed', '');
$contact_enable = get_theme_mod('aqualuxe_contact_enable', true);

// Exit if contact section is disabled
if (!$contact_enable) {
    return;
}
?>

<section class="contact">
    <div class="container">
        <div class="section-header">
            <?php if ($contact_title) : ?>
                <h2 class="section-title"><?php echo esc_html($contact_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($contact_description) : ?>
                <p class="section-description"><?php echo esc_html($contact_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="contact-wrapper">
            <div class="contact-form-container">
                <?php if ($contact_form_shortcode) : ?>
                    <div class="contact-form">
                        <?php echo do_shortcode($contact_form_shortcode); ?>
                    </div>
                <?php else : ?>
                    <p class="no-form"><?php echo esc_html__('Contact form shortcode not configured. Please add a contact form shortcode in the customizer.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="contact-info-container">
                <div class="contact-info">
                    <?php if ($contact_address) : ?>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4><?php echo esc_html__('Address', 'aqualuxe'); ?></h4>
                                <p><?php echo esc_html($contact_address); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact_phone) : ?>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4><?php echo esc_html__('Phone', 'aqualuxe'); ?></h4>
                                <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact_email) : ?>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4><?php echo esc_html__('Email', 'aqualuxe'); ?></h4>
                                <p><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact_hours) : ?>
                        <div class="contact-info-item">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4><?php echo esc_html__('Business Hours', 'aqualuxe'); ?></h4>
                                <p><?php echo wp_kses_post($contact_hours); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($contact_map_embed) : ?>
                    <div class="contact-map">
                        <?php echo wp_kses($contact_map_embed, array(
                            'iframe' => array(
                                'src'             => array(),
                                'height'          => array(),
                                'width'           => array(),
                                'frameborder'     => array(),
                                'allowfullscreen' => array(),
                                'style'           => array(),
                                'loading'         => array(),
                            ),
                        )); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>