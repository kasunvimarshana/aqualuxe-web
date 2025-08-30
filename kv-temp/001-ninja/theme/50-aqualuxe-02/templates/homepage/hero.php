<?php
/**
 * Template part for displaying the hero section on the homepage
 *
 * @package AquaLuxe
 */

// Get hero settings from customizer or use defaults
$hero_title = get_theme_mod( 'aqualuxe_hero_title', __( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ) );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', __( 'Premium ornamental fish, aquatic plants, and custom aquarium solutions for collectors and enthusiasts', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', __( 'Explore Collection', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', __( 'Our Services', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', home_url( '/services' ) );
$hero_background = get_theme_mod( 'aqualuxe_hero_background', get_template_directory_uri() . '/assets/dist/images/hero-background.jpg' );
$hero_video = get_theme_mod( 'aqualuxe_hero_video', '' );
?>

<section class="hero-section" style="background-image: url('<?php echo esc_url( $hero_background ); ?>');">
	<?php if ( $hero_video ) : ?>
		<div class="hero-video-container">
			<video class="hero-video" autoplay muted loop playsinline>
				<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
			</video>
		</div>
	<?php endif; ?>
	
	<div class="hero-overlay"></div>
	
	<div class="container">
		<div class="hero-content">
			<?php if ( $hero_title ) : ?>
				<h1 class="hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
			<?php endif; ?>
			
			<?php if ( $hero_subtitle ) : ?>
				<p class="hero-subtitle"><?php echo wp_kses_post( $hero_subtitle ); ?></p>
			<?php endif; ?>
			
			<div class="hero-buttons">
				<?php if ( $hero_button_text && $hero_button_url ) : ?>
					<a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $hero_button_text ); ?></a>
				<?php endif; ?>
				
				<?php if ( $hero_secondary_button_text && $hero_secondary_button_url ) : ?>
					<a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="btn btn-outline-primary"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="hero-scroll-down">
		<a href="#featured-products" aria-label="<?php esc_attr_e( 'Scroll down', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-.53 14.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V8.25a.75.75 0 00-1.5 0v5.69l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
			</svg>
		</a>
	</div>
</section>