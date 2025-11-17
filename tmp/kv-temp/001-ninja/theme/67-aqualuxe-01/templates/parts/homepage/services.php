<?php
/**
 * Template part for displaying homepage services section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get services settings
$title = get_theme_mod( 'aqualuxe_homepage_services_title', __( 'Our Services', 'aqualuxe' ) );
$subtitle = get_theme_mod( 'aqualuxe_homepage_services_subtitle', __( 'Professional Aquatic Solutions', 'aqualuxe' ) );
$text = get_theme_mod( 'aqualuxe_homepage_services_text', __( 'We offer a comprehensive range of professional services to meet all your aquatic needs.', 'aqualuxe' ) );
$button_text = get_theme_mod( 'aqualuxe_homepage_services_button_text', __( 'View All Services', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_homepage_services_button_url', '#' );

// Get services
$services = array();

// Check if we have custom services defined in theme mods
for ( $i = 1; $i <= 6; $i++ ) {
    $service_title = get_theme_mod( 'aqualuxe_service_' . $i . '_title', '' );
    $service_icon = get_theme_mod( 'aqualuxe_service_' . $i . '_icon', '' );
    $service_text = get_theme_mod( 'aqualuxe_service_' . $i . '_text', '' );
    $service_link = get_theme_mod( 'aqualuxe_service_' . $i . '_link', '' );
    
    if ( ! empty( $service_title ) ) {
        $services[] = array(
            'title' => $service_title,
            'icon'  => $service_icon,
            'text'  => $service_text,
            'link'  => $service_link,
        );
    }
}

// If no custom services, use default ones
if ( empty( $services ) ) {
    $services = array(
        array(
            'title' => __( 'Aquarium Design', 'aqualuxe' ),
            'icon'  => 'design',
            'text'  => __( 'Custom aquarium design and installation for homes, offices, and commercial spaces.', 'aqualuxe' ),
            'link'  => '#',
        ),
        array(
            'title' => __( 'Maintenance Services', 'aqualuxe' ),
            'icon'  => 'maintenance',
            'text'  => __( 'Regular maintenance services to keep your aquarium clean, healthy, and beautiful.', 'aqualuxe' ),
            'link'  => '#',
        ),
        array(
            'title' => __( 'Aquascaping', 'aqualuxe' ),
            'icon'  => 'aquascaping',
            'text'  => __( 'Professional aquascaping services to create stunning underwater landscapes.', 'aqualuxe' ),
            'link'  => '#',
        ),
        array(
            'title' => __( 'Consultation', 'aqualuxe' ),
            'icon'  => 'consultation',
            'text'  => __( 'Expert consultation for aquarium setup, fish selection, and aquatic plant care.', 'aqualuxe' ),
            'link'  => '#',
        ),
        array(
            'title' => __( 'Breeding Programs', 'aqualuxe' ),
            'icon'  => 'breeding',
            'text'  => __( 'Specialized breeding programs for rare and exotic fish species.', 'aqualuxe' ),
            'link'  => '#',
        ),
        array(
            'title' => __( 'Aquarium Relocation', 'aqualuxe' ),
            'icon'  => 'relocation',
            'text'  => __( 'Safe and professional aquarium relocation services for all sizes of tanks.', 'aqualuxe' ),
            'link'  => '#',
        ),
    );
}

// Get service icons
$service_icons = array(
    'design'       => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>',
    'maintenance'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
    'aquascaping'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
    'consultation' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
    'breeding'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>',
    'relocation'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>',
);
?>

<section class="homepage-services">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="section-header">
            <?php if ( ! empty( $subtitle ) ) : ?>
                <div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $text ) ) : ?>
                <div class="section-text"><?php echo wp_kses_post( $text ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="services-grid">
            <?php foreach ( $services as $service ) : ?>
                <div class="service-item">
                    <div class="service-icon">
                        <?php
                        if ( ! empty( $service['icon'] ) && isset( $service_icons[ $service['icon'] ] ) ) {
                            echo $service_icons[ $service['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        } else {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                        }
                        ?>
                    </div>
                    
                    <h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
                    
                    <?php if ( ! empty( $service['text'] ) ) : ?>
                        <div class="service-text"><?php echo wp_kses_post( $service['text'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $service['link'] ) ) : ?>
                        <a href="<?php echo esc_url( $service['link'] ); ?>" class="service-link"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
            <div class="section-footer">
                <a href="<?php echo esc_url( $button_url ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>