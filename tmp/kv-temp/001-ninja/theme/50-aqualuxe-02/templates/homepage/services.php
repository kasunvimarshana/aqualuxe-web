<?php
/**
 * Template part for displaying the services section on the homepage
 *
 * @package AquaLuxe
 */

// Get services section settings from customizer or use defaults
$services_title = get_theme_mod( 'aqualuxe_services_title', __( 'Our Services', 'aqualuxe' ) );
$services_subtitle = get_theme_mod( 'aqualuxe_services_subtitle', __( 'Professional aquatic solutions for every need', 'aqualuxe' ) );
$services_button_text = get_theme_mod( 'aqualuxe_services_button_text', __( 'View All Services', 'aqualuxe' ) );
$services_button_url = get_theme_mod( 'aqualuxe_services_button_url', home_url( '/services' ) );

// Get services from customizer or use defaults
$services = get_theme_mod( 'aqualuxe_services', array(
	array(
		'icon'        => 'design',
		'title'       => __( 'Aquarium Design', 'aqualuxe' ),
		'description' => __( 'Custom aquarium design and installation for homes, offices, and commercial spaces.', 'aqualuxe' ),
		'link'        => home_url( '/services/aquarium-design' ),
	),
	array(
		'icon'        => 'maintenance',
		'title'       => __( 'Maintenance Services', 'aqualuxe' ),
		'description' => __( 'Regular cleaning, water testing, and fish care to keep your aquarium healthy and beautiful.', 'aqualuxe' ),
		'link'        => home_url( '/services/maintenance' ),
	),
	array(
		'icon'        => 'health',
		'title'       => __( 'Quarantine & Health Check', 'aqualuxe' ),
		'description' => __( 'Professional quarantine services and health assessments for new and existing aquatic life.', 'aqualuxe' ),
		'link'        => home_url( '/services/health-check' ),
	),
	array(
		'icon'        => 'consultation',
		'title'       => __( 'Expert Consultation', 'aqualuxe' ),
		'description' => __( 'Personalized advice on aquarium setup, species selection, and aquascaping from our experts.', 'aqualuxe' ),
		'link'        => home_url( '/services/consultation' ),
	),
) );
?>

<section id="services" class="services-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $services_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $services_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $services_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $services_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<?php if ( ! empty( $services ) && is_array( $services ) ) : ?>
			<div class="services-grid">
				<?php foreach ( $services as $service ) : ?>
					<div class="service-card">
						<?php if ( ! empty( $service['icon'] ) ) : ?>
							<div class="service-icon">
								<?php aqualuxe_get_icon( $service['icon'] ); ?>
							</div>
						<?php endif; ?>
						
						<?php if ( ! empty( $service['title'] ) ) : ?>
							<h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
						<?php endif; ?>
						
						<?php if ( ! empty( $service['description'] ) ) : ?>
							<p class="service-description"><?php echo esc_html( $service['description'] ); ?></p>
						<?php endif; ?>
						
						<?php if ( ! empty( $service['link'] ) ) : ?>
							<a href="<?php echo esc_url( $service['link'] ); ?>" class="service-link">
								<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
									<path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
								</svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<?php if ( $services_button_text && $services_button_url ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( $services_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $services_button_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>