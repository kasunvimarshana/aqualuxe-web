<?php
/**
 * Template part for displaying the hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero section settings from theme mods
$hero_title = get_theme_mod( 'aqualuxe_hero_title', esc_html__( 'Bringing Elegance to Aquatic Life – Globally', 'aqualuxe' ) );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', esc_html__( 'Premium Aquatic Products for Enthusiasts and Collectors', 'aqualuxe' ) );
$hero_text = get_theme_mod( 'aqualuxe_hero_text', esc_html__( 'Discover our exclusive collection of rare fish species, premium aquariums, and expert aquascaping services.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', esc_html__( 'Shop Collection', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', esc_html__( 'Our Services', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', '#services' );
$hero_image = get_theme_mod( 'aqualuxe_hero_image', '' );

// Check if hero section should be displayed
$show_hero = get_theme_mod( 'aqualuxe_show_hero', true );

if ( ! $show_hero ) {
	return;
}

// Set default background image if none is set
if ( empty( $hero_image ) ) {
	$hero_image = AQUALUXE_ASSETS_URI . 'images/hero-background.jpg';
}
?>

<section class="hero-section">
	<div class="hero-background" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');">
		<div class="hero-overlay"></div>
		<div class="container">
			<div class="hero-content">
				<?php if ( $hero_subtitle ) : ?>
					<div class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></div>
				<?php endif; ?>

				<?php if ( $hero_title ) : ?>
					<h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php endif; ?>

				<?php if ( $hero_text ) : ?>
					<div class="hero-text"><?php echo wp_kses_post( $hero_text ); ?></div>
				<?php endif; ?>

				<div class="hero-buttons">
					<?php if ( $hero_button_text && $hero_button_url ) : ?>
						<a href="<?php echo esc_url( $hero_button_url ); ?>" class="button button-primary"><?php echo esc_html( $hero_button_text ); ?></a>
					<?php endif; ?>

					<?php if ( $hero_secondary_button_text && $hero_secondary_button_url ) : ?>
						<a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="button button-secondary"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section><!-- .hero-section -->