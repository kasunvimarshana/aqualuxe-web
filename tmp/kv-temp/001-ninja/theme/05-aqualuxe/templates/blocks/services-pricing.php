<?php
/**
 * Services Page Pricing Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get pricing settings from customizer or use defaults
$pricing_title = get_theme_mod( 'aqualuxe_pricing_title', 'Service Pricing' );
$pricing_subtitle = get_theme_mod( 'aqualuxe_pricing_subtitle', 'Transparent pricing for our professional services' );

// Demo pricing plans
$pricing_plans = array(
    array(
        'name' => 'Basic Maintenance',
        'price' => '$99',
        'period' => 'per month',
        'description' => 'Perfect for small to medium freshwater aquariums up to 75 gallons.',
        'features' => array(
            'Monthly service visit',
            'Water testing and chemistry adjustments',
            'Glass cleaning',
            'Filter maintenance',
            'Substrate vacuuming',
            'Equipment inspection',
            'Basic fish health assessment',
        ),
        'popular' => false,
        'button_text' => 'Get Started',
        'button_url' => '#contact-form',
    ),
    array(
        'name' => 'Premium Maintenance',
        'price' => '$179',
        'period' => 'per month',
        'description' => 'Ideal for larger freshwater or saltwater aquariums up to 150 gallons.',
        'features' => array(
            'Bi-weekly service visits',
            'Comprehensive water testing',
            'Glass and décor cleaning',
            'Filter cleaning and media replacement',
            'Substrate vacuuming',
            'Equipment inspection and maintenance',
            'Detailed fish health assessment',
            'Basic plant trimming and maintenance',
            'Monthly water change (up to 25%)',
        ),
        'popular' => true,
        'button_text' => 'Get Started',
        'button_url' => '#contact-form',
    ),
    array(
        'name' => 'Ultimate Maintenance',
        'price' => '$299',
        'period' => 'per month',
        'description' => 'Comprehensive care for large or complex aquariums over 150 gallons.',
        'features' => array(
            'Weekly service visits',
            'Professional water testing and analysis',
            'Complete cleaning of all surfaces',
            'Advanced filtration maintenance',
            'Substrate cleaning and maintenance',
            'Equipment inspection, cleaning, and calibration',
            'Comprehensive fish health monitoring',
            'Advanced plant care and aquascaping',
            'Bi-weekly water changes (up to 30%)',
            '24/7 emergency support',
            'Monthly detailed health report',
        ),
        'popular' => false,
        'button_text' => 'Get Started',
        'button_url' => '#contact-form',
    ),
);

// Filter pricing plans through a hook to allow customization
$pricing_plans = apply_filters( 'aqualuxe_pricing_plans', $pricing_plans );

// Custom services pricing
$custom_services = array(
    array(
        'name' => 'Custom Aquarium Design',
        'price' => 'Starting at $500',
        'description' => 'Price varies based on tank size, complexity, and materials.',
    ),
    array(
        'name' => 'Professional Aquascaping',
        'price' => 'Starting at $350',
        'description' => 'Includes design, materials, and installation.',
    ),
    array(
        'name' => 'Fish Health Consultation',
        'price' => '$150',
        'description' => 'In-person or virtual consultation with our aquatic health specialist.',
    ),
    array(
        'name' => 'Breeding Program Setup',
        'price' => 'Starting at $400',
        'description' => 'Custom breeding setup design and implementation.',
    ),
    array(
        'name' => 'Commercial Installation',
        'price' => 'Custom Quote',
        'description' => 'Please contact us for a personalized quote for commercial projects.',
    ),
    array(
        'name' => 'Emergency Service Call',
        'price' => '$175',
        'description' => 'Same-day emergency service for critical issues.',
    ),
);

// Filter custom services through a hook to allow customization
$custom_services = apply_filters( 'aqualuxe_custom_services_pricing', $custom_services );
?>

<section id="services-pricing" class="services-pricing-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $pricing_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $pricing_subtitle ); ?></div>
        </div>
        
        <div class="pricing-plans">
            <?php foreach ( $pricing_plans as $plan ) : ?>
                <div class="pricing-plan <?php echo $plan['popular'] ? 'popular' : ''; ?>">
                    <?php if ( $plan['popular'] ) : ?>
                        <div class="popular-badge"><?php esc_html_e( 'Most Popular', 'aqualuxe' ); ?></div>
                    <?php endif; ?>
                    
                    <div class="plan-header">
                        <h3 class="plan-name"><?php echo esc_html( $plan['name'] ); ?></h3>
                        <div class="plan-price">
                            <span class="price"><?php echo esc_html( $plan['price'] ); ?></span>
                            <span class="period"><?php echo esc_html( $plan['period'] ); ?></span>
                        </div>
                        <div class="plan-description"><?php echo esc_html( $plan['description'] ); ?></div>
                    </div>
                    
                    <div class="plan-features">
                        <ul>
                            <?php foreach ( $plan['features'] as $feature ) : ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="plan-footer">
                        <a href="<?php echo esc_url( $plan['button_url'] ); ?>" class="btn <?php echo $plan['popular'] ? 'btn-primary' : 'btn-secondary'; ?>"><?php echo esc_html( $plan['button_text'] ); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="custom-services-pricing">
            <h3 class="custom-services-title"><?php esc_html_e( 'Additional Services', 'aqualuxe' ); ?></h3>
            
            <div class="custom-services-table">
                <table>
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Service', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Details', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $custom_services as $service ) : ?>
                            <tr>
                                <td><?php echo esc_html( $service['name'] ); ?></td>
                                <td><?php echo esc_html( $service['price'] ); ?></td>
                                <td><?php echo esc_html( $service['description'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="custom-quote">
                <p><?php esc_html_e( 'Need a custom solution? Contact us for a personalized quote tailored to your specific needs.', 'aqualuxe' ); ?></p>
                <a href="#contact-form" class="btn btn-primary"><?php esc_html_e( 'Request a Quote', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </div>
</section>