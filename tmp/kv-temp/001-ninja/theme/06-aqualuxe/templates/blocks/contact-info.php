<?php
/**
 * Contact Page Information Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get contact info settings from customizer or use defaults
$contact_info_title = get_theme_mod( 'aqualuxe_contact_info_title', 'Get In Touch' );
$contact_info_subtitle = get_theme_mod( 'aqualuxe_contact_info_subtitle', 'We\'re here to help with any questions about our fish or services' );

// Contact information
$phone = get_theme_mod( 'aqualuxe_contact_phone', '+1 (800) 555-FISH' );
$email = get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.com' );
$address = get_theme_mod( 'aqualuxe_contact_address', '123 Aquarium Way, Coral Springs, FL 33065, USA' );

// Business hours
$business_hours = array(
    'Monday'    => '9:00 AM - 5:00 PM',
    'Tuesday'   => '9:00 AM - 5:00 PM',
    'Wednesday' => '9:00 AM - 5:00 PM',
    'Thursday'  => '9:00 AM - 5:00 PM',
    'Friday'    => '9:00 AM - 5:00 PM',
    'Saturday'  => '10:00 AM - 3:00 PM',
    'Sunday'    => 'Closed',
);

// Filter business hours through a hook to allow customization
$business_hours = apply_filters( 'aqualuxe_contact_business_hours', $business_hours );

// Social media
$social_media = array(
    'facebook'  => get_theme_mod( 'aqualuxe_social_facebook', '#' ),
    'twitter'   => get_theme_mod( 'aqualuxe_social_twitter', '#' ),
    'instagram' => get_theme_mod( 'aqualuxe_social_instagram', '#' ),
    'youtube'   => get_theme_mod( 'aqualuxe_social_youtube', '#' ),
    'pinterest' => get_theme_mod( 'aqualuxe_social_pinterest', '#' ),
);

// Filter social media through a hook to allow customization
$social_media = apply_filters( 'aqualuxe_contact_social_media', $social_media );
?>

<section class="contact-info-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $contact_info_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $contact_info_subtitle ); ?></div>
        </div>
        
        <div class="contact-info-grid">
            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <span class="icon-phone"></span>
                </div>
                <h3 class="contact-info-title"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h3>
                <div class="contact-info-content">
                    <p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <span class="icon-email"></span>
                </div>
                <h3 class="contact-info-title"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h3>
                <div class="contact-info-content">
                    <p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-info-icon">
                    <span class="icon-location"></span>
                </div>
                <h3 class="contact-info-title"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h3>
                <div class="contact-info-content">
                    <p><?php echo esc_html( $address ); ?></p>
                </div>
            </div>
        </div>
        
        <div class="contact-additional-info">
            <div class="business-hours">
                <h3><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h3>
                <ul class="hours-list">
                    <?php foreach ( $business_hours as $day => $hours ) : ?>
                        <li>
                            <span class="day"><?php echo esc_html( $day ); ?></span>
                            <span class="hours"><?php echo esc_html( $hours ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="social-media">
                <h3><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
                <div class="social-icons">
                    <?php foreach ( $social_media as $platform => $url ) : ?>
                        <?php if ( ! empty( $url ) && $url !== '#' ) : ?>
                            <a href="<?php echo esc_url( $url ); ?>" class="social-icon <?php echo esc_attr( $platform ); ?>" target="_blank" rel="noopener">
                                <span class="icon-<?php echo esc_attr( $platform ); ?>"></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>