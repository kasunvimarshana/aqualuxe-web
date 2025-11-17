<?php
/**
 * Template part for displaying the services section on the front page
 *
 * @package AquaLuxe
 */

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_services_title', __( 'Our Services', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_services_description', __( 'We offer a comprehensive range of professional aquatic services to meet all your needs.', 'aqualuxe' ) );
$columns = get_theme_mod( 'aqualuxe_services_columns', 3 );
$layout_style = get_theme_mod( 'aqualuxe_services_layout', 'grid' );
$background_style = get_theme_mod( 'aqualuxe_services_background', 'light' );
$view_all_text = get_theme_mod( 'aqualuxe_services_view_all_text', __( 'View All Services', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_services_view_all_url', home_url( '/services' ) );

// Section classes
$section_classes = array(
    'section',
    'services-section',
    'services-layout-' . $layout_style,
    'services-background-' . $background_style,
);

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_services', true );

if ( ! $show_section ) {
    return;
}

// Get services
$services = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $service_title = get_theme_mod( 'aqualuxe_service_' . $i . '_title', '' );
    $service_description = get_theme_mod( 'aqualuxe_service_' . $i . '_description', '' );
    $service_icon = get_theme_mod( 'aqualuxe_service_' . $i . '_icon', '' );
    $service_image = get_theme_mod( 'aqualuxe_service_' . $i . '_image', '' );
    $service_url = get_theme_mod( 'aqualuxe_service_' . $i . '_url', '' );
    
    if ( $service_title ) {
        $services[] = array(
            'title' => $service_title,
            'description' => $service_description,
            'icon' => $service_icon,
            'image' => $service_image,
            'url' => $service_url,
        );
    }
}

// Default services if none are set
if ( empty( $services ) ) {
    $services = array(
        array(
            'title' => __( 'Custom Aquarium Design', 'aqualuxe' ),
            'description' => __( 'Bespoke aquarium design and installation services tailored to your space and preferences.', 'aqualuxe' ),
            'icon' => 'palette',
            'image' => '',
            'url' => home_url( '/services/custom-aquarium-design' ),
        ),
        array(
            'title' => __( 'Maintenance Services', 'aqualuxe' ),
            'description' => __( 'Regular professional maintenance to keep your aquatic ecosystem healthy and beautiful.', 'aqualuxe' ),
            'icon' => 'tools',
            'image' => '',
            'url' => home_url( '/services/maintenance' ),
        ),
        array(
            'title' => __( 'Aquascaping', 'aqualuxe' ),
            'description' => __( 'Artistic aquascaping services to create stunning underwater landscapes in your aquarium.', 'aqualuxe' ),
            'icon' => 'image',
            'image' => '',
            'url' => home_url( '/services/aquascaping' ),
        ),
        array(
            'title' => __( 'Consultation', 'aqualuxe' ),
            'description' => __( 'Expert advice on species selection, equipment, and aquarium management.', 'aqualuxe' ),
            'icon' => 'message-circle',
            'image' => '',
            'url' => home_url( '/services/consultation' ),
        ),
        array(
            'title' => __( 'Breeding Programs', 'aqualuxe' ),
            'description' => __( 'Specialized breeding programs for rare and exotic fish species.', 'aqualuxe' ),
            'icon' => 'heart',
            'image' => '',
            'url' => home_url( '/services/breeding-programs' ),
        ),
        array(
            'title' => __( 'Aquarium Relocation', 'aqualuxe' ),
            'description' => __( 'Professional aquarium moving services to safely relocate your aquatic ecosystem.', 'aqualuxe' ),
            'icon' => 'truck',
            'image' => '',
            'url' => home_url( '/services/aquarium-relocation' ),
        ),
    );
}

// Limit services to display based on columns
$services = array_slice( $services, 0, $columns * 2 );
?>

<section id="services" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <div class="section-description"><?php echo wp_kses_post( $section_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="section-content">
            <div class="services-grid grid-cols-<?php echo esc_attr( $columns ); ?>">
                <?php foreach ( $services as $service ) : ?>
                    <div class="service-item">
                        <div class="service-inner">
                            <?php if ( ! empty( $service['image'] ) ) : ?>
                                <div class="service-image">
                                    <?php if ( ! empty( $service['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $service['url'] ); ?>">
                                            <?php echo wp_get_attachment_image( $service['image'], 'medium_large', false, array( 'class' => 'service-img' ) ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo wp_get_attachment_image( $service['image'], 'medium_large', false, array( 'class' => 'service-img' ) ); ?>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ( ! empty( $service['icon'] ) ) : ?>
                                <div class="service-icon">
                                    <i class="icon-<?php echo esc_attr( $service['icon'] ); ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-content">
                                <h3 class="service-title">
                                    <?php if ( ! empty( $service['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $service['url'] ); ?>"><?php echo esc_html( $service['title'] ); ?></a>
                                    <?php else : ?>
                                        <?php echo esc_html( $service['title'] ); ?>
                                    <?php endif; ?>
                                </h3>
                                
                                <?php if ( ! empty( $service['description'] ) ) : ?>
                                    <div class="service-description">
                                        <?php echo wp_kses_post( $service['description'] ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $service['url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $service['url'] ); ?>" class="service-link button button-text">
                                        <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if ( $view_all_text && $view_all_url ) : ?>
            <div class="section-footer">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="button"><?php echo esc_html( $view_all_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>