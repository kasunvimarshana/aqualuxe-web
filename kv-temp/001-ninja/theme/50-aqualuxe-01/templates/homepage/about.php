<?php
/**
 * Template part for displaying the about section on the homepage
 *
 * @package AquaLuxe
 */

// Get about section settings from customizer or use defaults
$about_title = get_theme_mod( 'aqualuxe_about_title', __( 'About AquaLuxe', 'aqualuxe' ) );
$about_subtitle = get_theme_mod( 'aqualuxe_about_subtitle', __( 'Discover our story and passion for aquatic life', 'aqualuxe' ) );
$about_content = get_theme_mod( 'aqualuxe_about_content', __( 'AquaLuxe is a vertically integrated ornamental fish farming and aquatic lifestyle company serving both local and international markets. We specialize in rare and exotic fish species, premium aquatic plants, and custom aquarium solutions for collectors and enthusiasts. Our commitment to quality, sustainability, and exceptional service has made us a trusted name in the aquatic industry.', 'aqualuxe' ) );
$about_image = get_theme_mod( 'aqualuxe_about_image', get_template_directory_uri() . '/assets/dist/images/about-image.jpg' );
$about_button_text = get_theme_mod( 'aqualuxe_about_button_text', __( 'Learn More', 'aqualuxe' ) );
$about_button_url = get_theme_mod( 'aqualuxe_about_button_url', home_url( '/about' ) );
$about_features = get_theme_mod( 'aqualuxe_about_features', array(
	array(
		'icon'  => 'fish',
		'title' => __( 'Premium Selection', 'aqualuxe' ),
		'text'  => __( 'Rare and exotic fish species sourced from trusted breeders worldwide.', 'aqualuxe' ),
	),
	array(
		'icon'  => 'leaf',
		'title' => __( 'Sustainable Practices', 'aqualuxe' ),
		'text'  => __( 'Environmentally responsible breeding and farming techniques.', 'aqualuxe' ),
	),
	array(
		'icon'  => 'globe',
		'title' => __( 'Global Shipping', 'aqualuxe' ),
		'text'  => __( 'Safe and reliable shipping to collectors and retailers worldwide.', 'aqualuxe' ),
	),
) );
?>

<section id="about" class="about-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $about_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $about_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $about_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $about_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<div class="about-content">
			<div class="about-image">
				<?php if ( $about_image ) : ?>
					<img src="<?php echo esc_url( $about_image ); ?>" alt="<?php echo esc_attr( $about_title ); ?>">
				<?php endif; ?>
			</div>
			
			<div class="about-text">
				<?php if ( $about_content ) : ?>
					<div class="about-description">
						<?php echo wp_kses_post( wpautop( $about_content ) ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( $about_button_text && $about_button_url ) : ?>
					<div class="about-button">
						<a href="<?php echo esc_url( $about_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $about_button_text ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<?php if ( ! empty( $about_features ) && is_array( $about_features ) ) : ?>
			<div class="about-features">
				<?php foreach ( $about_features as $feature ) : ?>
					<div class="about-feature">
						<?php if ( ! empty( $feature['icon'] ) ) : ?>
							<div class="feature-icon">
								<?php aqualuxe_get_icon( $feature['icon'] ); ?>
							</div>
						<?php endif; ?>
						
						<?php if ( ! empty( $feature['title'] ) ) : ?>
							<h3 class="feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
						<?php endif; ?>
						
						<?php if ( ! empty( $feature['text'] ) ) : ?>
							<p class="feature-text"><?php echo esc_html( $feature['text'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>