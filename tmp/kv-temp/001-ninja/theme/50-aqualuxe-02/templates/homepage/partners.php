<?php
/**
 * Template part for displaying the partners section on the homepage
 *
 * @package AquaLuxe
 */

// Get partners section settings from customizer or use defaults
$partners_title = get_theme_mod( 'aqualuxe_partners_title', __( 'Our Partners', 'aqualuxe' ) );
$partners_subtitle = get_theme_mod( 'aqualuxe_partners_subtitle', __( 'Trusted by leading organizations worldwide', 'aqualuxe' ) );

// Get partners from customizer or use defaults
$partners = get_theme_mod( 'aqualuxe_partners', array(
	array(
		'name'  => __( 'Aquatic Conservation Society', 'aqualuxe' ),
		'logo'  => get_template_directory_uri() . '/assets/dist/images/partner-1.png',
		'url'   => '#',
	),
	array(
		'name'  => __( 'Marine Research Institute', 'aqualuxe' ),
		'logo'  => get_template_directory_uri() . '/assets/dist/images/partner-2.png',
		'url'   => '#',
	),
	array(
		'name'  => __( 'Global Aquarium Association', 'aqualuxe' ),
		'logo'  => get_template_directory_uri() . '/assets/dist/images/partner-3.png',
		'url'   => '#',
	),
	array(
		'name'  => __( 'Sustainable Fisheries Alliance', 'aqualuxe' ),
		'logo'  => get_template_directory_uri() . '/assets/dist/images/partner-4.png',
		'url'   => '#',
	),
	array(
		'name'  => __( 'International Aquatic Trade Council', 'aqualuxe' ),
		'logo'  => get_template_directory_uri() . '/assets/dist/images/partner-5.png',
		'url'   => '#',
	),
) );

// Only display the section if partners are defined
if ( ! empty( $partners ) && is_array( $partners ) ) :
?>

<section id="partners" class="partners-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $partners_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $partners_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $partners_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $partners_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<div class="partners-grid">
			<?php foreach ( $partners as $partner ) : ?>
				<div class="partner-item">
					<?php if ( ! empty( $partner['url'] ) ) : ?>
						<a href="<?php echo esc_url( $partner['url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<?php endif; ?>
					
					<?php if ( ! empty( $partner['logo'] ) ) : ?>
						<img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>">
					<?php else : ?>
						<span class="partner-name"><?php echo esc_html( $partner['name'] ); ?></span>
					<?php endif; ?>
					
					<?php if ( ! empty( $partner['url'] ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php endif; ?>