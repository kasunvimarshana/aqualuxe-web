<?php
/**
 * Contact Page Form Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get form settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_contact_form_title', __( 'Send Us a Message', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_contact_form_subtitle', __( 'Fill out the form below and we\'ll get back to you as soon as possible.', 'aqualuxe' ) );
$form_shortcode = get_theme_mod( 'aqualuxe_contact_form_shortcode', '' );
$show_recaptcha = get_theme_mod( 'aqualuxe_contact_form_recaptcha', true );
$privacy_text = get_theme_mod( 'aqualuxe_contact_form_privacy_text', __( 'By submitting this form, you agree to our Privacy Policy and consent to receive communications from us.', 'aqualuxe' ) );
$layout_style = get_theme_mod( 'aqualuxe_contact_form_layout', 'side-by-side' );

// Section classes
$section_classes = array( 'contact-form-section', 'section' );
$section_classes[] = 'layout-' . $layout_style;
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-content">
                    <?php if ( $section_title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $section_subtitle ) : ?>
                        <div class="section-subtitle">
                            <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $form_shortcode ) : ?>
                        <div class="contact-form">
                            <?php echo do_shortcode( $form_shortcode ); ?>
                        </div>
                    <?php else : ?>
                        <div class="contact-form default-form">
                            <form id="contact-form" action="#" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></label>
                                    <input type="text" id="subject" name="subject" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="message"><?php esc_html_e( 'Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                    <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                                </div>
                                
                                <?php if ( $show_recaptcha ) : ?>
                                    <div class="form-group recaptcha">
                                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr( get_theme_mod( 'aqualuxe_recaptcha_site_key', '' ) ); ?>"></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $privacy_text ) : ?>
                                    <div class="form-group privacy-policy">
                                        <div class="form-check">
                                            <input type="checkbox" id="privacy" name="privacy" class="form-check-input" required>
                                            <label for="privacy" class="form-check-label"><?php echo wp_kses_post( $privacy_text ); ?></label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="form-group submit-group">
                                    <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Send Message', 'aqualuxe' ); ?></button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="contact-image">
                    <?php
                    $contact_image = get_theme_mod( 'aqualuxe_contact_form_image', get_template_directory_uri() . '/assets/images/contact/contact-form.jpg' );
                    if ( $contact_image ) :
                    ?>
                        <img src="<?php echo esc_url( $contact_image ); ?>" alt="<?php esc_attr_e( 'Contact Us', 'aqualuxe' ); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>