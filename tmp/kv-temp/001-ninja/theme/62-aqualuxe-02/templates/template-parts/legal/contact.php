<?php
/**
 * Legal Pages Contact Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get contact settings from customizer or ACF
$show_contact = get_theme_mod( 'aqualuxe_legal_show_contact', true );
$section_title = get_theme_mod( 'aqualuxe_legal_contact_title', __( 'Questions About Our Policies?', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_legal_contact_content', __( 'If you have any questions about our policies, please don\'t hesitate to contact us.', 'aqualuxe' ) );
$contact_email = get_theme_mod( 'aqualuxe_legal_contact_email', 'legal@aqualuxe.com' );
$contact_page_url = get_theme_mod( 'aqualuxe_legal_contact_page_url', get_permalink( get_page_by_path( 'contact' ) ) );
$contact_button_text = get_theme_mod( 'aqualuxe_legal_contact_button_text', __( 'Contact Us', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_legal_contact_background', 'light' );

// Skip if contact section is disabled
if ( ! $show_contact ) {
    return;
}

// Section classes
$section_classes = array( 'legal-contact-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="legal-contact-wrapper text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_content ) : ?>
                <div class="section-content">
                    <p><?php echo wp_kses_post( $section_content ); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ( $contact_email ) : ?>
                <div class="contact-email">
                    <p><?php esc_html_e( 'Email:', 'aqualuxe' ); ?> <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
                </div>
            <?php endif; ?>
            
            <?php if ( $contact_page_url && $contact_button_text ) : ?>
                <div class="contact-button">
                    <a href="<?php echo esc_url( $contact_page_url ); ?>" class="btn btn-primary"><?php echo esc_html( $contact_button_text ); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>