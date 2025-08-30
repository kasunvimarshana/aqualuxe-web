<?php
/**
 * Template part for displaying the header topbar
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get topbar data
$phone = get_theme_mod( 'aqualuxe_topbar_phone', '+1 (555) 123-4567' );
$email = get_theme_mod( 'aqualuxe_topbar_email', 'info@aqualuxe.com' );
$address = get_theme_mod( 'aqualuxe_topbar_address', '123 Aquarium St, Ocean City' );
?>

<div class="topbar">
    <div class="container">
        <div class="topbar-inner">
            <div class="topbar-left">
                <?php if ( $phone ) : ?>
                    <div class="topbar-phone">
                        <i class="fas fa-phone" aria-hidden="true"></i>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $email ) : ?>
                    <div class="topbar-email">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $address ) : ?>
                    <div class="topbar-address">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <span><?php echo esc_html( $address ); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="topbar-right">
                <?php if ( aqualuxe_is_wpml_active() || aqualuxe_is_polylang_active() ) : ?>
                    <div class="topbar-language">
                        <?php echo aqualuxe_get_language_switcher(); ?>
                    </div>
                <?php endif; ?>

                <?php if ( aqualuxe_is_woocommerce_active() && class_exists( 'WOOCS' ) ) : ?>
                    <div class="topbar-currency">
                        <?php echo aqualuxe_get_currency_switcher(); ?>
                    </div>
                <?php endif; ?>

                <div class="topbar-social">
                    <?php echo aqualuxe_get_social_links(); ?>
                </div>

                <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                    <div class="topbar-account">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>