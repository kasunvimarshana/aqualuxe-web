<?php
/**
 * Services Page Individual Services Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get individual services settings from customizer or use defaults
$individual_title = get_theme_mod( 'aqualuxe_individual_services_title', 'Our Services' );
$individual_subtitle = get_theme_mod( 'aqualuxe_individual_services_subtitle', 'Explore our range of professional aquatic services' );

// Demo services data
$services = array(
    array(
        'title' => 'Custom Aquarium Design',
        'description' => 'Our design team works with you to create a custom aquarium that perfectly fits your space and aesthetic preferences. We handle everything from concept to installation, ensuring a seamless process.',
        'features' => array(
            'Personalized design consultation',
            'Custom tank sizing and configuration',
            'Aquascape design and planning',
            'Material selection and sourcing',
            'Professional installation',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-design.jpg',
        'icon' => 'icon-design',
    ),
    array(
        'title' => 'Aquarium Maintenance',
        'description' => 'Keep your aquarium looking its best with our professional maintenance services. Our technicians perform regular cleaning, water testing, and equipment checks to ensure optimal conditions for your aquatic life.',
        'features' => array(
            'Regular water testing and chemistry adjustments',
            'Filter cleaning and media replacement',
            'Glass cleaning and algae removal',
            'Substrate vacuuming',
            'Equipment inspection and maintenance',
            'Fish health assessment',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-maintenance.jpg',
        'icon' => 'icon-maintenance',
    ),
    array(
        'title' => 'Aquascaping Services',
        'description' => 'Transform your aquarium into a stunning underwater landscape with our professional aquascaping services. Our designers create natural-looking environments that enhance the beauty of your fish and promote their well-being.',
        'features' => array(
            'Custom aquascape design',
            'Premium hardscape materials (rocks, driftwood)',
            'Live plant selection and placement',
            'Substrate layering and contouring',
            'Ongoing maintenance and trimming',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-aquascaping.jpg',
        'icon' => 'icon-aquascaping',
    ),
    array(
        'title' => 'Fish Health Consultation',
        'description' => 'Our aquatic health specialists provide expert diagnosis and treatment recommendations for fish health issues. We offer both in-person and virtual consultations to help you maintain a healthy aquarium.',
        'features' => array(
            'Disease diagnosis and treatment',
            'Water quality assessment',
            'Nutrition and feeding recommendations',
            'Quarantine setup and protocols',
            'Preventative care advice',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-health.jpg',
        'icon' => 'icon-health',
    ),
    array(
        'title' => 'Breeding Program Setup',
        'description' => 'Interested in breeding your fish? Our experts can help you set up a successful breeding program with the right equipment, water conditions, and techniques for your specific species.',
        'features' => array(
            'Species-specific breeding environment design',
            'Conditioning protocols for breeding pairs',
            'Specialized equipment setup',
            'Fry rearing guidance',
            'Genetic selection advice',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-breeding.jpg',
        'icon' => 'icon-breeding',
    ),
    array(
        'title' => 'Commercial Aquarium Services',
        'description' => 'We provide comprehensive services for commercial aquariums in restaurants, hotels, offices, and other public spaces. Our team handles design, installation, and ongoing maintenance to ensure your display remains impressive.',
        'features' => array(
            'Commercial-grade system design',
            'Custom installation for public spaces',
            'Regular maintenance contracts',
            'Staff training programs',
            '24/7 emergency support',
        ),
        'image' => get_template_directory_uri() . '/demo-content/images/service-commercial.jpg',
        'icon' => 'icon-commercial',
    ),
);

// Filter services through a hook to allow customization
$services = apply_filters( 'aqualuxe_individual_services', $services );

// Return if no services
if ( empty( $services ) ) {
    return;
}
?>

<section class="individual-services-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $individual_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $individual_subtitle ); ?></div>
        </div>
        
        <div class="services-tabs">
            <div class="services-tabs-nav">
                <?php foreach ( $services as $index => $service ) : ?>
                    <button class="tab-button <?php echo $index === 0 ? 'active' : ''; ?>" data-tab="service-tab-<?php echo esc_attr( $index ); ?>">
                        <?php if ( ! empty( $service['icon'] ) ) : ?>
                            <span class="<?php echo esc_attr( $service['icon'] ); ?>"></span>
                        <?php endif; ?>
                        <?php echo esc_html( $service['title'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="services-tabs-content">
                <?php foreach ( $services as $index => $service ) : ?>
                    <div id="service-tab-<?php echo esc_attr( $index ); ?>" class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="service-content">
                            <div class="service-info">
                                <h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
                                <div class="service-description">
                                    <?php echo wpautop( esc_html( $service['description'] ) ); ?>
                                </div>
                                
                                <?php if ( ! empty( $service['features'] ) ) : ?>
                                    <div class="service-features">
                                        <h4><?php esc_html_e( 'What\'s Included', 'aqualuxe' ); ?></h4>
                                        <ul>
                                            <?php foreach ( $service['features'] as $feature ) : ?>
                                                <li><?php echo esc_html( $feature ); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="#services-pricing" class="btn btn-secondary"><?php esc_html_e( 'View Pricing', 'aqualuxe' ); ?></a>
                            </div>
                            
                            <div class="service-image">
                                <img src="<?php echo esc_url( $service['image'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to current button and content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    });
</script>