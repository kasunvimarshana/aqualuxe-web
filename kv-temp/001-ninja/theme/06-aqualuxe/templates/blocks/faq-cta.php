<?php
/**
 * FAQ Page Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get FAQ CTA settings from customizer or use defaults
$faq_cta_title = get_theme_mod( 'aqualuxe_faq_cta_title', 'Still Have Questions?' );
$faq_cta_text = get_theme_mod( 'aqualuxe_faq_cta_text', 'Our expert team is here to help with any questions you may have about our products or services. Contact us for personalized assistance.' );
$faq_cta_button_text = get_theme_mod( 'aqualuxe_faq_cta_button_text', 'Contact Us' );
$faq_cta_button_url = get_theme_mod( 'aqualuxe_faq_cta_button_url', '/contact' );
?>

<section class="faq-cta-section">
    <div class="container">
        <div class="faq-cta-content">
            <h2 class="cta-title"><?php echo esc_html( $faq_cta_title ); ?></h2>
            <div class="cta-text"><?php echo wp_kses_post( $faq_cta_text ); ?></div>
            <div class="cta-buttons">
                <a href="<?php echo esc_url( $faq_cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $faq_cta_button_text ); ?></a>
            </div>
        </div>
        
        <div class="faq-contact-options">
            <div class="contact-option">
                <div class="option-icon">
                    <span class="icon-phone"></span>
                </div>
                <h3 class="option-title"><?php esc_html_e( 'Call Us', 'aqualuxe' ); ?></h3>
                <div class="option-content">
                    <p><?php esc_html_e( 'Speak directly with our aquatic experts', 'aqualuxe' ); ?></p>
                    <a href="tel:+18005553474"><?php esc_html_e( '+1 (800) 555-FISH', 'aqualuxe' ); ?></a>
                </div>
            </div>
            
            <div class="contact-option">
                <div class="option-icon">
                    <span class="icon-email"></span>
                </div>
                <h3 class="option-title"><?php esc_html_e( 'Email Us', 'aqualuxe' ); ?></h3>
                <div class="option-content">
                    <p><?php esc_html_e( 'Send us your questions anytime', 'aqualuxe' ); ?></p>
                    <a href="mailto:support@aqualuxe.com"><?php esc_html_e( 'support@aqualuxe.com', 'aqualuxe' ); ?></a>
                </div>
            </div>
            
            <div class="contact-option">
                <div class="option-icon">
                    <span class="icon-chat"></span>
                </div>
                <h3 class="option-title"><?php esc_html_e( 'Live Chat', 'aqualuxe' ); ?></h3>
                <div class="option-content">
                    <p><?php esc_html_e( 'Chat with our support team', 'aqualuxe' ); ?></p>
                    <a href="#" class="start-chat"><?php esc_html_e( 'Start Chat', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>