<?php
/**
 * Template part for displaying footer main content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get footer layout
$footer_layout = aqualuxe_get_footer_layout();
?>

<div class="footer-main">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="footer-main-inner">
            <?php if ( $footer_layout === 'centered' ) : ?>
                <div class="footer-logo">
                    <?php aqualuxe_site_logo(); ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-info">
                <?php if ( $footer_layout !== 'centered' ) : ?>
                    <div class="footer-logo">
                        <?php aqualuxe_site_logo(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="footer-contact-info">
                    <?php
                    $address = get_theme_mod( 'aqualuxe_contact_address', '' );
                    $phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
                    $email = get_theme_mod( 'aqualuxe_contact_email', '' );
                    $hours = get_theme_mod( 'aqualuxe_contact_hours', '' );
                    
                    if ( ! empty( $address ) || ! empty( $phone ) || ! empty( $email ) || ! empty( $hours ) ) :
                        ?>
                        <ul class="contact-info-list">
                            <?php if ( ! empty( $address ) ) : ?>
                                <li class="contact-address">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span><?php echo wp_kses_post( nl2br( $address ) ); ?></span>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $phone ) ) : ?>
                                <li class="contact-phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $email ) ) : ?>
                                <li class="contact-email">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $hours ) ) : ?>
                                <li class="contact-hours">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <span><?php echo wp_kses_post( nl2br( $hours ) ); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
            
            <?php if ( get_theme_mod( 'aqualuxe_footer_menu', true ) ) : ?>
                <div class="footer-menu-wrapper">
                    <?php aqualuxe_footer_navigation(); ?>
                </div>
            <?php endif; ?>
            
            <?php if ( get_theme_mod( 'aqualuxe_footer_social', true ) ) : ?>
                <div class="footer-social">
                    <?php aqualuxe_social_links(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>