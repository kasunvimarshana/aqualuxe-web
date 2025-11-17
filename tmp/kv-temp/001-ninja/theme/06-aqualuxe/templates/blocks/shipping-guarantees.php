<?php
/**
 * Shipping & Returns Page Guarantees Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get guarantees settings from customizer or use defaults
$guarantees_title = get_theme_mod( 'aqualuxe_guarantees_title', 'Our Guarantees' );
$guarantees_subtitle = get_theme_mod( 'aqualuxe_guarantees_subtitle', 'We stand behind the quality of our products' );
?>

<section class="guarantees-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $guarantees_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $guarantees_subtitle ); ?></div>
        </div>
        
        <div class="guarantees-content">
            <div class="guarantees-intro">
                <p><?php esc_html_e( 'At AquaLuxe, we\'re committed to your complete satisfaction. Our guarantees reflect our confidence in the quality of our products and our dedication to customer service.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="guarantees-grid">
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <span class="icon-arrival"></span>
                    </div>
                    <h3><?php esc_html_e( '100% Live Arrival Guarantee', 'aqualuxe' ); ?></h3>
                    <div class="guarantee-description">
                        <p><?php esc_html_e( 'We guarantee that all fish and live plants will arrive alive and in good condition. If any arrive dead or severely stressed, simply take clear photos within 2 hours of delivery and contact us for a replacement or refund.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'This guarantee applies to all shipping methods recommended for the specific species and assumes proper acclimation following our guidelines.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <span class="icon-health"></span>
                    </div>
                    <h3><?php esc_html_e( '7-Day Health Guarantee', 'aqualuxe' ); ?></h3>
                    <div class="guarantee-description">
                        <p><?php esc_html_e( 'Our fish are covered by a 7-day health guarantee against disease and genetic defects. If a fish dies within 7 days of delivery due to a pre-existing condition, we\'ll provide a replacement or refund.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'To qualify, you must provide water parameter test results (ammonia, nitrite, nitrate, pH) and photos of the deceased fish. This guarantee assumes proper acclimation and appropriate tank conditions.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <span class="icon-quality"></span>
                    </div>
                    <h3><?php esc_html_e( 'Quality Assurance', 'aqualuxe' ); ?></h3>
                    <div class="guarantee-description">
                        <p><?php esc_html_e( 'All equipment and dry goods are inspected before shipping to ensure they meet our quality standards. If you receive a defective item, we\'ll replace it or provide a refund within 30 days of purchase.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'For manufacturer defects that appear after 30 days but within the manufacturer\'s warranty period, we\'ll assist you with the warranty claim process.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <span class="icon-satisfaction"></span>
                    </div>
                    <h3><?php esc_html_e( 'Satisfaction Guarantee', 'aqualuxe' ); ?></h3>
                    <div class="guarantee-description">
                        <p><?php esc_html_e( 'We want you to be completely satisfied with your purchase. If you\'re not happy with any non-living product for any reason, you can return it in new, unused condition within 30 days for a full refund or exchange.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'This guarantee excludes live animals, plants, food, and medications once opened, as well as custom or special order items.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="premium-guarantees">
                <h3><?php esc_html_e( 'AquaLuxe Club Premium Guarantees', 'aqualuxe' ); ?></h3>
                <div class="premium-content">
                    <div class="premium-image">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/demo-content/images/premium-guarantee.jpg' ); ?>" alt="<?php esc_attr_e( 'Premium Guarantees', 'aqualuxe' ); ?>">
                    </div>
                    <div class="premium-text">
                        <p><?php esc_html_e( 'Members of our AquaLuxe Club loyalty program receive enhanced guarantees:', 'aqualuxe' ); ?></p>
                        <ul>
                            <li><?php esc_html_e( 'Extended 14-day health guarantee for fish', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Priority replacement shipping', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'One-time courtesy replacement for beginner mistakes', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( '45-day return period for equipment and dry goods', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Dedicated support line for expedited service', 'aqualuxe' ); ?></li>
                        </ul>
                        <a href="#" class="btn btn-secondary"><?php esc_html_e( 'Join AquaLuxe Club', 'aqualuxe' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>