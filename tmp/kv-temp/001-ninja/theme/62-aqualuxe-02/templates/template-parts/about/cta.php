<?php
/**
 * About Page Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get CTA settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_cta_title', __( 'Join Our Team', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_about_cta_content', __( 'We\'re always looking for talented individuals to join our team. Check out our current openings and apply today!', 'aqualuxe' ) );
$primary_button_text = get_theme_mod( 'aqualuxe_about_cta_primary_button_text', __( 'View Careers', 'aqualuxe' ) );
$primary_button_url = get_theme_mod( 'aqualuxe_about_cta_primary_button_url', '#' );
$secondary_button_text = get_theme_mod( 'aqualuxe_about_cta_secondary_button_text', __( 'Contact Us', 'aqualuxe' ) );
$secondary_button_url = get_theme_mod( 'aqualuxe_about_cta_secondary_button_url', '#' );
$background_color = get_theme_mod( 'aqualuxe_about_cta_background', 'primary' );
$background_image = get_theme_mod( 'aqualuxe_about_cta_background_image', '' );
$text_alignment = get_theme_mod( 'aqualuxe_about_cta_text_alignment', 'center' );

// Section classes
$section_classes = array( 'cta-section', 'section' );
if ( $background_color === 'primary' ) {
    $section_classes[] = 'bg-primary text-light';
} elseif ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

if ( $background_image ) {
    $section_classes[] = 'has-background-image';
}

$section_classes[] = 'text-' . $text_alignment;

// Section style
$section_style = '';
if ( $background_image ) {
    $section_style = 'background-image: url(' . esc_url( $background_image ) . ');';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( $section_style ) echo 'style="' . esc_attr( $section_style ) . '"'; ?>>
    <div class="container">
        <div class="cta-wrapper">
            <?php if ( $section_title ) : ?>
                <h2 class="cta-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_content ) : ?>
                <div class="cta-content">
                    <p><?php echo wp_kses_post( $section_content ); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="cta-buttons">
                <?php if ( $primary_button_text && $primary_button_url ) : ?>
                    <a href="<?php echo esc_url( $primary_button_url ); ?>" class="btn btn-light btn-lg"><?php echo esc_html( $primary_button_text ); ?></a>
                <?php endif; ?>
                
                <?php if ( $secondary_button_text && $secondary_button_url ) : ?>
                    <a href="<?php echo esc_url( $secondary_button_url ); ?>" class="btn btn-outline-light btn-lg"><?php echo esc_html( $secondary_button_text ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>