<?php
/**
 * Services Page Overview Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get services overview settings from customizer or use defaults
$overview_title = get_theme_mod( 'aqualuxe_services_overview_title', 'What We Offer' );
$overview_subtitle = get_theme_mod( 'aqualuxe_services_overview_subtitle', 'Comprehensive aquatic services for hobbyists and professionals' );
$overview_content = get_theme_mod( 'aqualuxe_services_overview_content', 'At AquaLuxe, we offer a wide range of services to help you create and maintain the perfect aquatic environment. From custom aquarium design and installation to maintenance services and expert consultations, our team of aquatic specialists is here to help you every step of the way.' );
$overview_image = get_theme_mod( 'aqualuxe_services_overview_image', get_template_directory_uri() . '/demo-content/images/services-overview.jpg' );
?>

<section class="services-overview-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $overview_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $overview_subtitle ); ?></div>
        </div>
        
        <div class="services-overview-content">
            <div class="overview-text">
                <?php echo wpautop( wp_kses_post( $overview_content ) ); ?>
                
                <div class="overview-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="icon-quality"></span>
                        </div>
                        <div class="feature-content">
                            <h3><?php esc_html_e( 'Expert Knowledge', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'Our team includes marine biologists and aquaculture specialists with decades of experience.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="icon-customization"></span>
                        </div>
                        <div class="feature-content">
                            <h3><?php esc_html_e( 'Customized Solutions', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'We tailor our services to meet your specific needs and aquarium requirements.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="icon-support"></span>
                        </div>
                        <div class="feature-content">
                            <h3><?php esc_html_e( 'Ongoing Support', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'We provide continuous support and guidance to ensure the long-term success of your aquatic environment.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overview-image">
                <img src="<?php echo esc_url( $overview_image ); ?>" alt="<?php echo esc_attr( $overview_title ); ?>">
            </div>
        </div>
    </div>
</section>