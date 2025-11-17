<?php
/**
 * Template part for displaying content before the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if we're on the front page
if ( is_front_page() ) :
    // Newsletter section
    $footer_newsletter = get_theme_mod( 'aqualuxe_footer_newsletter', true );
    $footer_newsletter_text = get_theme_mod( 'aqualuxe_footer_newsletter_text', __( 'Subscribe to our newsletter to get the latest updates', 'aqualuxe' ) );
    
    if ( $footer_newsletter ) :
        ?>
        <div class="newsletter-section">
            <div class="container">
                <div class="newsletter-inner">
                    <div class="newsletter-content">
                        <h3 class="newsletter-title"><?php esc_html_e( 'Join Our Newsletter', 'aqualuxe' ); ?></h3>
                        <?php if ( $footer_newsletter_text ) : ?>
                            <p class="newsletter-description"><?php echo esc_html( $footer_newsletter_text ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="newsletter-form">
                        <form action="#" method="post" class="newsletter-subscribe-form">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                                <button type="submit" class="newsletter-submit button">
                                    <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endif;
endif;

// Instagram feed
$instagram_access_token = \AquaLuxe\Helpers\Utils::get_theme_instagram_access_token();
if ( ! empty( $instagram_access_token ) ) :
    ?>
    <div class="instagram-feed">
        <div class="container">
            <h3 class="instagram-title"><?php esc_html_e( 'Follow Us on Instagram', 'aqualuxe' ); ?></h3>
            <div class="instagram-items">
                <!-- Instagram feed will be loaded via JavaScript -->
                <div class="instagram-placeholder"></div>
            </div>
            <div class="instagram-follow">
                <a href="https://www.instagram.com/aqualuxe" target="_blank" rel="noopener noreferrer" class="button button-outline">
                    <i class="icon-instagram"></i>
                    <?php esc_html_e( 'Follow on Instagram', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
endif;

// CTA section
if ( is_singular( 'product' ) || is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) :
    ?>
    <div class="cta-section">
        <div class="container">
            <div class="cta-inner">
                <div class="cta-content">
                    <h3 class="cta-title"><?php esc_html_e( 'Need Help with Your Aquarium?', 'aqualuxe' ); ?></h3>
                    <p class="cta-description"><?php esc_html_e( 'Our experts are ready to assist you with custom aquarium design, installation, and maintenance services.', 'aqualuxe' ); ?></p>
                </div>
                <div class="cta-buttons">
                    <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="button"><?php esc_html_e( 'Our Services', 'aqualuxe' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button button-outline"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;