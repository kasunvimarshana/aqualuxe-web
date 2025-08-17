<?php
/**
 * Template part for displaying the hero section
 *
 * @package AquaLuxe
 */

// Get hero settings from customizer
$hero_title = get_theme_mod( 'aqualuxe_hero_title', 'Bringing Elegance to Aquatic Life' );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', 'Premium aquatic products for your home and business' );
$hero_text = get_theme_mod( 'aqualuxe_hero_text', 'Discover our collection of high-quality aquatic products designed to enhance your aquatic experience.' );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', 'Shop Now' );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', '#' );
$hero_button_2_text = get_theme_mod( 'aqualuxe_hero_button_2_text', 'Learn More' );
$hero_button_2_url = get_theme_mod( 'aqualuxe_hero_button_2_url', '#' );
$hero_image = get_theme_mod( 'aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg' );
$hero_overlay = get_theme_mod( 'aqualuxe_hero_overlay', true );
$hero_height = get_theme_mod( 'aqualuxe_hero_height', 'medium' );

// Set height class based on setting
$height_class = '';
switch ( $hero_height ) {
	case 'small':
		$height_class = 'min-h-[400px]';
		break;
	case 'medium':
		$height_class = 'min-h-[600px]';
		break;
	case 'large':
		$height_class = 'min-h-[800px]';
		break;
	case 'full':
		$height_class = 'min-h-screen';
		break;
}

// Set overlay class if enabled
$overlay_class = $hero_overlay ? 'before:absolute before:inset-0 before:bg-black before:bg-opacity-40 before:z-0' : '';
?>

<section class="hero-section relative <?php echo esc_attr( $height_class . ' ' . $overlay_class ); ?> flex items-center">
	<div class="hero-background absolute inset-0 z-0">
		<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" class="w-full h-full object-cover">
	</div>
	
	<div class="container mx-auto px-4 relative z-10">
		<div class="hero-content max-w-2xl text-white">
			<?php if ( $hero_title ) : ?>
				<h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html( $hero_title ); ?></h1>
			<?php endif; ?>
			
			<?php if ( $hero_subtitle ) : ?>
				<h2 class="hero-subtitle text-xl md:text-2xl font-medium mb-4"><?php echo esc_html( $hero_subtitle ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $hero_text ) : ?>
				<div class="hero-text text-lg mb-8"><?php echo wp_kses_post( $hero_text ); ?></div>
			<?php endif; ?>
			
			<div class="hero-buttons flex flex-wrap gap-4">
				<?php if ( $hero_button_text && $hero_button_url ) : ?>
					<a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-md transition-colors">
						<?php echo esc_html( $hero_button_text ); ?>
					</a>
				<?php endif; ?>
				
				<?php if ( $hero_button_2_text && $hero_button_2_url ) : ?>
					<a href="<?php echo esc_url( $hero_button_2_url ); ?>" class="btn btn-secondary bg-transparent hover:bg-white hover:text-primary border-2 border-white text-white font-bold py-3 px-6 rounded-md transition-colors">
						<?php echo esc_html( $hero_button_2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>