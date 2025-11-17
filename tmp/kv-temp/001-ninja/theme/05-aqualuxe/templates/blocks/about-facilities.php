<?php
/**
 * About Page Facilities Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get facilities settings from customizer or use defaults
$facilities_title = get_theme_mod( 'aqualuxe_facilities_title', 'Our Facilities' );
$facilities_subtitle = get_theme_mod( 'aqualuxe_facilities_subtitle', 'Where the magic happens' );

// Demo facilities images
$facilities_images = array(
    array(
        'title' => 'Breeding Facility',
        'description' => 'Our state-of-the-art breeding tanks maintain perfect water conditions for each species.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-1.jpg',
    ),
    array(
        'title' => 'Research Laboratory',
        'description' => 'Our research team works to improve breeding techniques and fish health.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-2.jpg',
    ),
    array(
        'title' => 'Conditioning Area',
        'description' => 'Fish are carefully conditioned before shipping to ensure they arrive in perfect health.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-3.jpg',
    ),
    array(
        'title' => 'Packing Station',
        'description' => 'Our specialized packing methods ensure safe transit for even the most delicate species.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-4.jpg',
    ),
    array(
        'title' => 'Water Filtration System',
        'description' => 'Advanced filtration systems maintain pristine water quality throughout our facilities.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-5.jpg',
    ),
    array(
        'title' => 'Outdoor Ponds',
        'description' => 'Natural ponds for breeding and raising certain species in a more natural environment.',
        'image' => get_template_directory_uri() . '/demo-content/images/facility-6.jpg',
    ),
);

// Filter facilities images through a hook to allow customization
$facilities_images = apply_filters( 'aqualuxe_about_facilities_images', $facilities_images );

// Return if no facilities images
if ( empty( $facilities_images ) ) {
    return;
}
?>

<section class="about-facilities-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $facilities_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $facilities_subtitle ); ?></div>
        </div>
        
        <div class="facilities-intro">
            <p><?php esc_html_e( 'Our 15-acre facility in Florida houses over 500 specialized tanks and ponds designed to replicate the natural habitats of our fish. We use cutting-edge technology to maintain perfect water conditions while minimizing our environmental footprint through solar power and water recycling systems.', 'aqualuxe' ); ?></p>
        </div>
        
        <div class="facilities-gallery">
            <?php foreach ( $facilities_images as $image ) : ?>
                <div class="facility-item">
                    <div class="facility-image">
                        <img src="<?php echo esc_url( $image['image'] ); ?>" alt="<?php echo esc_attr( $image['title'] ); ?>">
                    </div>
                    <div class="facility-overlay">
                        <div class="facility-content">
                            <h3 class="facility-title"><?php echo esc_html( $image['title'] ); ?></h3>
                            <div class="facility-description"><?php echo esc_html( $image['description'] ); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="facilities-stats">
            <div class="stat-item">
                <div class="stat-number">15</div>
                <div class="stat-label"><?php esc_html_e( 'Acres', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label"><?php esc_html_e( 'Specialized Tanks', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">200+</div>
                <div class="stat-label"><?php esc_html_e( 'Fish Species', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label"><?php esc_html_e( 'Solar Powered', 'aqualuxe' ); ?></div>
            </div>
        </div>
    </div>
</section>