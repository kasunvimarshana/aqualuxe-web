<?php
/**
 * Contact Page Form Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get contact form settings from customizer or use defaults
$contact_form_title = get_theme_mod( 'aqualuxe_contact_form_title', 'Send Us a Message' );
$contact_form_subtitle = get_theme_mod( 'aqualuxe_contact_form_subtitle', 'Have questions or need assistance? Fill out the form below and we\'ll get back to you as soon as possible.' );
$contact_form_shortcode = get_theme_mod( 'aqualuxe_contact_form_shortcode', '' );
?>

<section class="contact-form-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $contact_form_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $contact_form_subtitle ); ?></div>
        </div>
        
        <div class="contact-form-wrapper">
            <?php if ( ! empty( $contact_form_shortcode ) ) : ?>
                <?php echo do_shortcode( $contact_form_shortcode ); ?>
            <?php else : ?>
                <!-- Default contact form if no shortcode is provided -->
                <form class="contact-form" id="contact-form" action="#" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone"><?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?></label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <select id="subject" name="subject" required>
                                <option value=""><?php esc_html_e( 'Select a subject', 'aqualuxe' ); ?></option>
                                <option value="general"><?php esc_html_e( 'General Inquiry', 'aqualuxe' ); ?></option>
                                <option value="order"><?php esc_html_e( 'Order Question', 'aqualuxe' ); ?></option>
                                <option value="shipping"><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></option>
                                <option value="species"><?php esc_html_e( 'Fish Species Information', 'aqualuxe' ); ?></option>
                                <option value="wholesale"><?php esc_html_e( 'Wholesale Inquiry', 'aqualuxe' ); ?></option>
                                <option value="other"><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message"><?php esc_html_e( 'Your Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="privacy" name="privacy" required>
                            <label for="privacy"><?php esc_html_e( 'I have read and agree to the', 'aqualuxe' ); ?> <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank"><?php esc_html_e( 'Privacy Policy', 'aqualuxe' ); ?></a></label>
                        </div>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Send Message', 'aqualuxe' ); ?></button>
                    </div>
                    
                    <div class="form-response"></div>
                </form>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('contact-form');
                        const response = document.querySelector('.form-response');
                        
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            
                            // This is a demo form - in a real implementation, you would send the form data to the server
                            // For demo purposes, we'll just show a success message
                            response.innerHTML = '<div class="alert alert-success"><?php esc_html_e( 'Thank you for your message! This is a demo form. In a real implementation, your message would be sent to our team.', 'aqualuxe' ); ?></div>';
                            form.reset();
                        });
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
</section>