<?php
/**
 * Template part for displaying the about section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get about section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_about_title', esc_html__( 'About AquaLuxe', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_about_subtitle', esc_html__( 'Our Story', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_about_text', esc_html__( 'AquaLuxe was founded with a passion for bringing elegance to aquatic life. We specialize in rare and exotic fish species, premium aquariums, and professional aquascaping services. Our team of experts is dedicated to providing the highest quality products and services to aquatic enthusiasts and collectors worldwide.', 'aqualuxe' ) );
$section_image = get_theme_mod( 'aqualuxe_about_image', '' );
$button_text = get_theme_mod( 'aqualuxe_about_button_text', esc_html__( 'Learn More', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_about_button_url', home_url( '/about' ) );

// Check if about section should be displayed
$show_about = get_theme_mod( 'aqualuxe_show_about', true );

if ( ! $show_about ) {
	return;
}

// Set default image if none is set
if ( empty( $section_image ) ) {
	$section_image = AQUALUXE_ASSETS_URI . 'images/about-image.jpg';
}
?>

<section id="about" class="about-section">
	<div class="container">
		<div class="about-inner">
			<div class="about-image">
				<img src="<?php echo esc_url( $section_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="about-img">
			</div>

			<div class="about-content">
				<?php if ( $section_subtitle ) : ?>
					<div class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></div>
				<?php endif; ?>

				<?php if ( $section_title ) : ?>
					<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $section_text ) : ?>
					<div class="section-text"><?php echo wp_kses_post( $section_text ); ?></div>
				<?php endif; ?>

				<?php if ( $button_text && $button_url ) : ?>
					<div class="about-button">
						<a href="<?php echo esc_url( $button_url ); ?>" class="button button-primary"><?php echo esc_html( $button_text ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section><!-- .about-section -->