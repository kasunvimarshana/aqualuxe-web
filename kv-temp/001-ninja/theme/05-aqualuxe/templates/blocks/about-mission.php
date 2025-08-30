<?php
/**
 * About Page Mission and Values Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get mission settings from customizer or use defaults
$mission_title = get_theme_mod( 'aqualuxe_mission_title', 'Our Mission & Values' );
$mission_subtitle = get_theme_mod( 'aqualuxe_mission_subtitle', 'What drives us every day' );
?>

<section class="about-mission-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $mission_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $mission_subtitle ); ?></div>
        </div>
        
        <div class="mission-statement">
            <div class="mission-content">
                <h3><?php esc_html_e( 'Our Mission', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'To provide the highest quality ornamental fish while promoting responsible aquarium keeping, conservation, and education about aquatic ecosystems.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        
        <div class="values-grid">
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-sustainability"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Sustainability', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We are committed to sustainable breeding practices that reduce pressure on wild populations. Over 90% of our fish are captive-bred in our facilities.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-quality"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Quality', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We maintain the highest standards in fish health, coloration, and genetic diversity. Each fish is carefully selected and conditioned before shipping.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-education"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Education', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We believe in educating hobbyists about proper fish care, habitat requirements, and conservation. Knowledge leads to better care and appreciation.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-innovation"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Innovation', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We continuously improve our breeding techniques, shipping methods, and customer experience through research and technological advancement.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-ethics"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Ethics', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We operate with transparency and integrity in all aspects of our business, from fair trade practices with suppliers to honest communication with customers.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="value-item">
                <div class="value-icon">
                    <span class="icon-conservation"></span>
                </div>
                <h3 class="value-title"><?php esc_html_e( 'Conservation', 'aqualuxe' ); ?></h3>
                <div class="value-description">
                    <p><?php esc_html_e( 'We actively participate in conservation programs for endangered aquatic species and habitats, donating a portion of our profits to these efforts.', 'aqualuxe' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>