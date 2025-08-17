<?php
/**
 * Template part for displaying the services section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get services section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_services_title', esc_html__( 'Our Services', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_services_subtitle', esc_html__( 'Professional Aquatic Solutions', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_services_text', esc_html__( 'We offer a range of professional services to help you create and maintain your perfect aquatic environment.', 'aqualuxe' ) );
$button_text = get_theme_mod( 'aqualuxe_services_button_text', esc_html__( 'View All Services', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_services_button_url', home_url( '/services' ) );

// Check if services section should be displayed
$show_services = get_theme_mod( 'aqualuxe_show_services', true );

if ( ! $show_services ) {
	return;
}

// Define services
$services = array(
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" /><path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" /></svg>',
		'title' => esc_html__( 'Custom Aquarium Design', 'aqualuxe' ),
		'desc'  => esc_html__( 'We design and create custom aquariums tailored to your space and preferences, from concept to installation.', 'aqualuxe' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" /></svg>',
		'title' => esc_html__( 'Professional Aquascaping', 'aqualuxe' ),
		'desc'  => esc_html__( 'Our expert aquascapers create stunning underwater landscapes using premium plants, hardscape materials, and artistic techniques.', 'aqualuxe' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" /><path d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z" /></svg>',
		'title' => esc_html__( 'Maintenance Services', 'aqualuxe' ),
		'desc'  => esc_html__( 'Regular maintenance packages to keep your aquarium in pristine condition, including water testing, cleaning, and equipment checks.', 'aqualuxe' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z" /></svg>',
		'title' => esc_html__( 'Consultation & Training', 'aqualuxe' ),
		'desc'  => esc_html__( 'Expert advice and personalized training sessions to help you succeed in maintaining your aquatic ecosystem.', 'aqualuxe' ),
	),
);
?>

<section id="services" class="services-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $section_subtitle ) : ?>
				<div class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></div>
			<?php endif; ?>

			<?php if ( $section_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_text ) : ?>
				<div class="section-text"><?php echo wp_kses_post( $section_text ); ?></div>
			<?php endif; ?>
		</div>

		<div class="services-grid">
			<?php foreach ( $services as $service ) : ?>
				<div class="service-card">
					<div class="service-icon">
						<?php echo wp_kses( $service['icon'], array( 'svg' => array( 'xmlns' => array(), 'viewBox' => array(), 'fill' => array(), 'class' => array() ), 'path' => array( 'd' => array(), 'fill-rule' => array(), 'clip-rule' => array() ) ) ); ?>
					</div>
					<h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<div class="service-description"><?php echo esc_html( $service['desc'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $button_text && $button_url ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( $button_url ); ?>" class="button button-primary"><?php echo esc_html( $button_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section><!-- .services-section -->