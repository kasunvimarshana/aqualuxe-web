<?php
/**
 * Template part for displaying footer before content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Display newsletter section if enabled
if ( get_theme_mod( 'aqualuxe_footer_newsletter', true ) ) :
    $newsletter_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
    $newsletter_text = get_theme_mod( 'aqualuxe_newsletter_text', __( 'Stay updated with our latest products, offers and aquatic care tips.', 'aqualuxe' ) );
    $newsletter_form = get_theme_mod( 'aqualuxe_newsletter_form', '' );
    ?>
    <div class="footer-newsletter">
        <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
            <div class="footer-newsletter-inner">
                <div class="newsletter-content">
                    <?php if ( ! empty( $newsletter_title ) ) : ?>
                        <h3 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $newsletter_text ) ) : ?>
                        <div class="newsletter-text"><?php echo wp_kses_post( $newsletter_text ); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="newsletter-form">
                    <?php
                    if ( ! empty( $newsletter_form ) ) {
                        // Display form from shortcode
                        echo do_shortcode( $newsletter_form );
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
        </div>
    </div>
    <?php
endif;