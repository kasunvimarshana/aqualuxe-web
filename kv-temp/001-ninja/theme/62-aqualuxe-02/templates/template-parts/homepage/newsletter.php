<?php
/**
 * Homepage Newsletter Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get newsletter settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_newsletter_subtitle', __( 'Get the latest updates, news and special offers delivered directly to your inbox.', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_newsletter_background', 'dark' );
$background_image = get_theme_mod( 'aqualuxe_newsletter_background_image', '' );
$form_shortcode = get_theme_mod( 'aqualuxe_newsletter_form_shortcode', '' );
$privacy_text = get_theme_mod( 'aqualuxe_newsletter_privacy_text', __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing communications.', 'aqualuxe' ) );

// Section classes
$section_classes = array( 'newsletter-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

if ( $background_image ) {
    $section_classes[] = 'has-background-image';
}

// Section style
$section_style = '';
if ( $background_image ) {
    $section_style = 'background-image: url(' . esc_url( $background_image ) . ');';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( $section_style ) echo 'style="' . esc_attr( $section_style ) . '"'; ?>>
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="section-header text-center">
                <?php if ( $section_title ) : ?>
                    <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( $section_subtitle ) : ?>
                    <div class="section-subtitle">
                        <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="newsletter-form">
                <?php if ( $form_shortcode ) : ?>
                    <?php echo do_shortcode( $form_shortcode ); ?>
                <?php else : ?>
                    <form class="newsletter-form-default" action="#" method="post">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                        </div>
                    </form>
                <?php endif; ?>
                
                <?php if ( $privacy_text ) : ?>
                    <div class="newsletter-privacy">
                        <p><?php echo wp_kses_post( $privacy_text ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>