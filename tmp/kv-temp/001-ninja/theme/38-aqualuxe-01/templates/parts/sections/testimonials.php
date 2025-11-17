<?php
/**
 * Template part for displaying the testimonials section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get testimonials section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_testimonials_title', esc_html__( 'What Our Clients Say', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_testimonials_subtitle', esc_html__( 'Testimonials', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_testimonials_text', esc_html__( 'Hear from our satisfied clients about their experience with AquaLuxe products and services.', 'aqualuxe' ) );
$background_image = get_theme_mod( 'aqualuxe_testimonials_background', '' );

// Check if testimonials section should be displayed
$show_testimonials = get_theme_mod( 'aqualuxe_show_testimonials', true );

if ( ! $show_testimonials ) {
	return;
}

// Set default background image if none is set
if ( empty( $background_image ) ) {
	$background_image = AQUALUXE_ASSETS_URI . 'images/testimonials-background.jpg';
}

// Define testimonials
$testimonials = array(
	array(
		'content'  => esc_html__( 'AquaLuxe transformed my living space with a stunning custom aquarium. Their attention to detail and expertise in aquascaping created a breathtaking underwater landscape that has become the centerpiece of my home.', 'aqualuxe' ),
		'name'     => esc_html__( 'Michael Johnson', 'aqualuxe' ),
		'position' => esc_html__( 'Luxury Home Owner', 'aqualuxe' ),
		'image'    => AQUALUXE_ASSETS_URI . 'images/testimonial-1.jpg',
	),
	array(
		'content'  => esc_html__( 'As a collector of rare fish species, I\'ve been impressed by AquaLuxe\'s selection and knowledge. Their team provided expert guidance on creating the perfect environment for my collection, and their maintenance service ensures everything stays in pristine condition.', 'aqualuxe' ),
		'name'     => esc_html__( 'Sarah Williams', 'aqualuxe' ),
		'position' => esc_html__( 'Aquatic Collector', 'aqualuxe' ),
		'image'    => AQUALUXE_ASSETS_URI . 'images/testimonial-2.jpg',
	),
	array(
		'content'  => esc_html__( 'Our hotel lobby has been completely transformed by the magnificent aquarium designed and installed by AquaLuxe. Guests are constantly amazed by the beauty of the display, and the maintenance team keeps it looking flawless with minimal disruption to our operations.', 'aqualuxe' ),
		'name'     => esc_html__( 'David Chen', 'aqualuxe' ),
		'position' => esc_html__( 'Luxury Hotel Manager', 'aqualuxe' ),
		'image'    => AQUALUXE_ASSETS_URI . 'images/testimonial-3.jpg',
	),
);
?>

<section id="testimonials" class="testimonials-section" style="background-image: url('<?php echo esc_url( $background_image ); ?>');">
	<div class="testimonials-overlay"></div>
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

		<div class="testimonials-slider">
			<?php foreach ( $testimonials as $testimonial ) : ?>
				<div class="testimonial-card">
					<div class="testimonial-content">
						<div class="testimonial-quote">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
								<path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 006.75-6.75v-2.25h-2.25a6.721 6.721 0 00-6.75 6.75v2.25h1.054zm14.446 0a6.707 6.707 0 001.196.107 6.721 6.721 0 006.75-6.75v-2.25h-2.25a6.721 6.721 0 00-6.75 6.75v2.25h1.054z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="testimonial-text"><?php echo esc_html( $testimonial['content'] ); ?></div>
					</div>
					<div class="testimonial-author">
						<?php if ( ! empty( $testimonial['image'] ) ) : ?>
							<div class="testimonial-author-image">
								<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
							</div>
						<?php endif; ?>
						<div class="testimonial-author-info">
							<h4 class="testimonial-author-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
							<div class="testimonial-author-position"><?php echo esc_html( $testimonial['position'] ); ?></div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section><!-- .testimonials-section -->