<?php
/**
 * Services Page Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get services CTA settings from customizer or use defaults
$services_cta_title = get_theme_mod( 'aqualuxe_services_cta_title', 'Ready to Transform Your Aquatic Experience?' );
$services_cta_text = get_theme_mod( 'aqualuxe_services_cta_text', 'Let our experts help you create and maintain the perfect aquatic environment. Contact us today to discuss your needs and discover how our services can benefit you.' );
$services_cta_button_text = get_theme_mod( 'aqualuxe_services_cta_button_text', 'Schedule a Consultation' );
$services_cta_button_url = get_theme_mod( 'aqualuxe_services_cta_button_url', '#contact-form' );
$services_cta_background = get_theme_mod( 'aqualuxe_services_cta_background', get_template_directory_uri() . '/demo-content/images/services-cta-background.jpg' );
?>

<section class="services-cta-section" style="background-image: url('<?php echo esc_url( $services_cta_background ); ?>');">
    <div class="cta-overlay"></div>
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title"><?php echo esc_html( $services_cta_title ); ?></h2>
            <div class="cta-text"><?php echo wp_kses_post( $services_cta_text ); ?></div>
            <div class="cta-buttons">
                <a href="<?php echo esc_url( $services_cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $services_cta_button_text ); ?></a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Shop Products', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </div>
</section>