<?php
/**
 * Template part for displaying homepage newsletter section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if newsletter section is enabled
if ( ! get_theme_mod( 'aqualuxe_homepage_newsletter', true ) ) {
    return;
}

// Get newsletter settings
$title = get_theme_mod( 'aqualuxe_homepage_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$text = get_theme_mod( 'aqualuxe_homepage_newsletter_text', __( 'Stay updated with our latest products, offers and aquatic care tips.', 'aqualuxe' ) );
$form = get_theme_mod( 'aqualuxe_homepage_newsletter_form', '' );
$background = get_theme_mod( 'aqualuxe_homepage_newsletter_background', '' );

// Section classes
$section_classes = array( 'homepage-newsletter' );

if ( ! empty( $background ) ) {
    $section_classes[] = 'has-background-image';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( ! empty( $background ) ) : ?>style="background-image: url('<?php echo esc_url( $background ); ?>');"<?php endif; ?>>
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="newsletter-content">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="newsletter-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $text ) ) : ?>
                <div class="newsletter-text"><?php echo wp_kses_post( $text ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="newsletter-form">
            <?php
            if ( ! empty( $form ) ) {
                // Display form from shortcode
                echo do_shortcode( $form );
            } else {
                // Display default form
                ?>
                <form class="newsletter-form-default" action="#" method="post">
                    <div class="form-fields">
                        <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" required>
                        <button type="submit" class="button"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                    </div>
                    <div class="form-privacy">
                        <label>
                            <input type="checkbox" name="privacy" required>
                            <span><?php esc_html_e( 'I agree to the privacy policy', 'aqualuxe' ); ?></span>
                        </label>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</section>