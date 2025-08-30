<?php
/**
 * Template part for displaying the testimonials section on the homepage
 *
 * @package AquaLuxe
 */

// Get testimonials section settings from customizer or use defaults
$testimonials_title = get_theme_mod( 'aqualuxe_testimonials_title', __( 'What Our Clients Say', 'aqualuxe' ) );
$testimonials_subtitle = get_theme_mod( 'aqualuxe_testimonials_subtitle', __( 'Hear from our satisfied customers around the world', 'aqualuxe' ) );
$testimonials_background = get_theme_mod( 'aqualuxe_testimonials_background', get_template_directory_uri() . '/assets/dist/images/testimonials-background.jpg' );

// Get testimonials from customizer or use defaults
$testimonials = get_theme_mod( 'aqualuxe_testimonials', array(
	array(
		'name'      => __( 'John Smith', 'aqualuxe' ),
		'position'  => __( 'Aquarium Enthusiast', 'aqualuxe' ),
		'content'   => __( 'AquaLuxe has transformed my home with their stunning custom aquarium. Their attention to detail and knowledge of aquatic species is unmatched. The maintenance service keeps everything pristine without any effort on my part.', 'aqualuxe' ),
		'rating'    => 5,
		'image'     => get_template_directory_uri() . '/assets/dist/images/testimonial-1.jpg',
	),
	array(
		'name'      => __( 'Sarah Johnson', 'aqualuxe' ),
		'position'  => __( 'Restaurant Owner', 'aqualuxe' ),
		'content'   => __( 'The custom reef tank that AquaLuxe designed for our restaurant has become a centerpiece that customers love. Their maintenance team keeps it looking spectacular, and their service is always professional and timely.', 'aqualuxe' ),
		'rating'    => 5,
		'image'     => get_template_directory_uri() . '/assets/dist/images/testimonial-2.jpg',
	),
	array(
		'name'      => __( 'Michael Chen', 'aqualuxe' ),
		'position'  => __( 'Collector', 'aqualuxe' ),
		'content'   => __( 'As a serious collector of rare fish species, I\'ve been impressed by AquaLuxe\'s ability to source exactly what I\'m looking for. Their quarantine procedures ensure that every specimen arrives in perfect health. Truly a premium service.', 'aqualuxe' ),
		'rating'    => 5,
		'image'     => get_template_directory_uri() . '/assets/dist/images/testimonial-3.jpg',
	),
) );
?>

<section id="testimonials" class="testimonials-section" style="background-image: url('<?php echo esc_url( $testimonials_background ); ?>');">
	<div class="testimonials-overlay"></div>
	
	<div class="container">
		<div class="section-header">
			<?php if ( $testimonials_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $testimonials_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $testimonials_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $testimonials_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<?php if ( ! empty( $testimonials ) && is_array( $testimonials ) ) : ?>
			<div class="testimonials-slider" data-testimonials-slider>
				<?php foreach ( $testimonials as $testimonial ) : ?>
					<div class="testimonial-item">
						<div class="testimonial-content">
							<?php if ( ! empty( $testimonial['content'] ) ) : ?>
								<div class="testimonial-text">
									<svg class="quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
										<path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
									</svg>
									<p><?php echo esc_html( $testimonial['content'] ); ?></p>
								</div>
							<?php endif; ?>
							
							<div class="testimonial-meta">
								<?php if ( ! empty( $testimonial['image'] ) ) : ?>
									<div class="testimonial-image">
										<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
									</div>
								<?php endif; ?>
								
								<div class="testimonial-info">
									<?php if ( ! empty( $testimonial['name'] ) ) : ?>
										<h4 class="testimonial-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
									<?php endif; ?>
									
									<?php if ( ! empty( $testimonial['position'] ) ) : ?>
										<p class="testimonial-position"><?php echo esc_html( $testimonial['position'] ); ?></p>
									<?php endif; ?>
									
									<?php if ( ! empty( $testimonial['rating'] ) ) : ?>
										<div class="testimonial-rating">
											<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
												<?php if ( $i <= $testimonial['rating'] ) : ?>
													<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
														<path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
													</svg>
												<?php else : ?>
													<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
														<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
													</svg>
												<?php endif; ?>
											<?php endfor; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<div class="testimonials-navigation">
				<button class="testimonial-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'aqualuxe' ); ?>" data-testimonials-prev>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
						<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-4.28 9.22a.75.75 0 000 1.06l3 3a.75.75 0 101.06-1.06l-1.72-1.72h5.69a.75.75 0 000-1.5h-5.69l1.72-1.72a.75.75 0 00-1.06-1.06l-3 3z" clip-rule="evenodd" />
					</svg>
				</button>
				<div class="testimonials-dots" data-testimonials-dots></div>
				<button class="testimonial-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'aqualuxe' ); ?>" data-testimonials-next>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
						<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" clip-rule="evenodd" />
					</svg>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>