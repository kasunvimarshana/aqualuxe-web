<?php
/**
 * Template Name: Services Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-featured-image relative">
						<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
						<div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
							<div class="container mx-auto px-4">
								<h1 class="entry-title text-4xl md:text-5xl font-bold text-white text-center"><?php the_title(); ?></h1>
							</div>
						</div>
					</div>
				<?php else : ?>
					<header class="entry-header bg-gray-100 dark:bg-gray-800 py-12">
						<div class="container mx-auto px-4">
							<h1 class="entry-title text-4xl font-bold text-center"><?php the_title(); ?></h1>
						</div>
					</header>
				<?php endif; ?>

				<div class="entry-content py-12">
					<div class="container mx-auto px-4">
						<?php if ( get_the_content() ) : ?>
							<div class="services-intro max-w-4xl mx-auto prose prose-lg dark:prose-invert mb-16 text-center">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>

						<?php if ( have_rows( 'services' ) ) : ?>
							<div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
								<?php
								while ( have_rows( 'services' ) ) : the_row();
									$service_title = get_sub_field( 'title' );
									$service_description = get_sub_field( 'description' );
									$service_icon = get_sub_field( 'icon' );
									$service_link = get_sub_field( 'link' );
									?>
									<div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-shadow duration-300 hover:shadow-md">
										<?php if ( $service_icon ) : ?>
											<div class="service-icon p-6 flex justify-center">
												<div class="w-16 h-16 flex items-center justify-center bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full">
													<img src="<?php echo esc_url( $service_icon ); ?>" alt="" class="w-8 h-8">
												</div>
											</div>
										<?php endif; ?>
										
										<div class="service-content p-6 pt-0 text-center">
											<h3 class="service-title text-xl font-bold mb-4"><?php echo esc_html( $service_title ); ?></h3>
											
											<?php if ( $service_description ) : ?>
												<div class="service-description text-gray-700 dark:text-gray-300 mb-6">
													<?php echo wp_kses_post( $service_description ); ?>
												</div>
											<?php endif; ?>
											
											<?php if ( $service_link ) : ?>
												<a href="<?php echo esc_url( $service_link ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
													<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
													</svg>
												</a>
											<?php endif; ?>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>

						<?php if ( have_rows( 'service_features' ) ) : ?>
							<div class="service-features py-16 bg-gray-50 dark:bg-gray-900 rounded-lg">
								<div class="container mx-auto px-4">
									<?php if ( $features_title = get_field( 'service_features_title' ) ) : ?>
										<div class="section-header text-center mb-12">
											<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $features_title ); ?></h2>
											
											<?php if ( $features_subtitle = get_field( 'service_features_subtitle' ) ) : ?>
												<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $features_subtitle ); ?></p>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									
									<div class="features-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
										<?php
										while ( have_rows( 'service_features' ) ) : the_row();
											$feature_title = get_sub_field( 'title' );
											$feature_description = get_sub_field( 'description' );
											$feature_icon = get_sub_field( 'icon' );
											?>
											<div class="feature-item bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
												<div class="flex items-start">
													<?php if ( $feature_icon ) : ?>
														<div class="feature-icon mr-4">
															<div class="w-12 h-12 flex items-center justify-center bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full">
																<img src="<?php echo esc_url( $feature_icon ); ?>" alt="" class="w-6 h-6">
															</div>
														</div>
													<?php endif; ?>
													
													<div class="feature-content">
														<h3 class="feature-title text-lg font-bold mb-2"><?php echo esc_html( $feature_title ); ?></h3>
														
														<?php if ( $feature_description ) : ?>
															<div class="feature-description text-gray-700 dark:text-gray-300">
																<?php echo wp_kses_post( $feature_description ); ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( have_rows( 'service_process_steps' ) ) : ?>
							<div class="service-process py-16">
								<div class="container mx-auto px-4">
									<?php if ( $process_title = get_field( 'service_process_title' ) ) : ?>
										<div class="section-header text-center mb-12">
											<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $process_title ); ?></h2>
											
											<?php if ( $process_subtitle = get_field( 'service_process_subtitle' ) ) : ?>
												<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $process_subtitle ); ?></p>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									
									<div class="process-steps">
										<?php
										$step_count = 0;
										while ( have_rows( 'service_process_steps' ) ) : the_row();
											$step_count++;
											$step_title = get_sub_field( 'title' );
											$step_description = get_sub_field( 'description' );
											$step_image = get_sub_field( 'image' );
											$direction = $step_count % 2 === 0 ? 'md:flex-row-reverse' : 'md:flex-row';
											?>
											<div class="process-step flex flex-col <?php echo esc_attr( $direction ); ?> items-center gap-8 mb-16 last:mb-0">
												<div class="process-step-content w-full md:w-1/2">
													<div class="flex items-start mb-4">
														<div class="step-number w-10 h-10 flex items-center justify-center bg-primary-600 text-white rounded-full font-bold text-lg mr-4">
															<?php echo esc_html( $step_count ); ?>
														</div>
														<h3 class="step-title text-2xl font-bold"><?php echo esc_html( $step_title ); ?></h3>
													</div>
													
													<?php if ( $step_description ) : ?>
														<div class="step-description text-gray-700 dark:text-gray-300 ml-14">
															<?php echo wp_kses_post( $step_description ); ?>
														</div>
													<?php endif; ?>
												</div>
												
												<?php if ( $step_image ) : ?>
													<div class="process-step-image w-full md:w-1/2">
														<img src="<?php echo esc_url( $step_image ); ?>" alt="<?php echo esc_attr( $step_title ); ?>" class="rounded-lg shadow-sm w-full h-auto">
													</div>
												<?php endif; ?>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( get_field( 'service_cta_enable' ) ) : ?>
							<div class="service-cta py-16 bg-primary-600 text-white rounded-lg">
								<div class="container mx-auto px-4">
									<div class="max-w-3xl mx-auto text-center">
										<h2 class="cta-title text-3xl font-bold mb-4"><?php echo esc_html( get_field( 'service_cta_title' ) ?: __( 'Ready to Get Started?', 'aqualuxe' ) ); ?></h2>
										
										<?php if ( $cta_text = get_field( 'service_cta_text' ) ) : ?>
											<p class="cta-text text-lg mb-8"><?php echo esc_html( $cta_text ); ?></p>
										<?php endif; ?>
										
										<?php if ( $cta_button_text = get_field( 'service_cta_button_text' ) ) : ?>
											<a href="<?php echo esc_url( get_field( 'service_cta_button_url' ) ?: '#' ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-600 focus:ring-white">
												<?php echo esc_html( $cta_button_text ); ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( have_rows( 'service_testimonials' ) ) : ?>
							<div class="service-testimonials py-16">
								<div class="container mx-auto px-4">
									<?php if ( $testimonials_title = get_field( 'service_testimonials_title' ) ) : ?>
										<div class="section-header text-center mb-12">
											<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $testimonials_title ); ?></h2>
											
											<?php if ( $testimonials_subtitle = get_field( 'service_testimonials_subtitle' ) ) : ?>
												<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $testimonials_subtitle ); ?></p>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									
									<div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
										<?php
										while ( have_rows( 'service_testimonials' ) ) : the_row();
											$testimonial_content = get_sub_field( 'content' );
											$testimonial_name = get_sub_field( 'name' );
											$testimonial_role = get_sub_field( 'role' );
											$testimonial_image = get_sub_field( 'image' );
											$testimonial_rating = get_sub_field( 'rating' );
											?>
											<div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
												<?php if ( $testimonial_rating ) : ?>
													<div class="testimonial-rating flex text-yellow-400 mb-4">
														<?php
														$rating = intval( $testimonial_rating );
														for ( $i = 1; $i <= 5; $i++ ) {
															if ( $i <= $rating ) {
																echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
																	<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
																</svg>';
															} else {
																echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
																	<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
																</svg>';
															}
														}
														?>
													</div>
												<?php endif; ?>
												
												<?php if ( $testimonial_content ) : ?>
													<div class="testimonial-content mb-6">
														<p class="text-gray-700 dark:text-gray-300"><?php echo esc_html( $testimonial_content ); ?></p>
													</div>
												<?php endif; ?>
												
												<div class="testimonial-author flex items-center">
													<?php if ( $testimonial_image ) : ?>
														<div class="testimonial-author-image mr-3">
															<img src="<?php echo esc_url( $testimonial_image ); ?>" alt="<?php echo esc_attr( $testimonial_name ); ?>" class="w-10 h-10 rounded-full object-cover">
														</div>
													<?php endif; ?>
													
													<div class="testimonial-author-info">
														<?php if ( $testimonial_name ) : ?>
															<h4 class="testimonial-author-name font-medium"><?php echo esc_html( $testimonial_name ); ?></h4>
														<?php endif; ?>
														
														<?php if ( $testimonial_role ) : ?>
															<p class="testimonial-author-role text-sm text-gray-600 dark:text-gray-400"><?php echo esc_html( $testimonial_role ); ?></p>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</article>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();