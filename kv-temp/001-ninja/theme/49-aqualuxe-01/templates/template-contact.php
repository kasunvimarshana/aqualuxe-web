<?php
/**
 * Template Name: Contact Page
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
						<div class="max-w-5xl mx-auto">
							<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
								<div class="lg:col-span-2">
									<div class="contact-form bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
										<h2 class="text-2xl font-bold mb-6"><?php echo esc_html( get_field( 'contact_form_title' ) ?: __( 'Send Us a Message', 'aqualuxe' ) ); ?></h2>
										
										<?php
										// Display contact form if a shortcode is provided
										$contact_form_shortcode = get_field( 'contact_form_shortcode' );
										if ( $contact_form_shortcode ) {
											echo do_shortcode( $contact_form_shortcode );
										} else {
											// Display default contact form
											?>
											<form class="contact-form-default">
												<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
													<div class="form-group">
														<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
														<input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
													</div>
													<div class="form-group">
														<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
														<input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
													</div>
												</div>
												<div class="form-group mb-4">
													<label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></label>
													<input type="text" id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
												</div>
												<div class="form-group mb-6">
													<label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e( 'Your Message', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
													<textarea id="message" name="message" rows="6" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required></textarea>
												</div>
												<div class="form-submit">
													<button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
														<?php esc_html_e( 'Send Message', 'aqualuxe' ); ?>
													</button>
												</div>
											</form>
											<?php
										}
										?>
									</div>
								</div>
								
								<div class="lg:col-span-1">
									<div class="contact-info bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
										<h2 class="text-2xl font-bold mb-6"><?php echo esc_html( get_field( 'contact_info_title' ) ?: __( 'Contact Information', 'aqualuxe' ) ); ?></h2>
										
										<div class="contact-details space-y-6">
											<?php if ( $address = get_field( 'contact_address' ) ) : ?>
												<div class="contact-address flex">
													<div class="contact-icon mr-4 text-primary-600 dark:text-primary-400">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
														</svg>
													</div>
													<div class="contact-text">
														<h3 class="text-lg font-medium mb-1"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h3>
														<p class="text-gray-700 dark:text-gray-300"><?php echo nl2br( esc_html( $address ) ); ?></p>
													</div>
												</div>
											<?php endif; ?>
											
											<?php if ( $phone = get_field( 'contact_phone' ) ) : ?>
												<div class="contact-phone flex">
													<div class="contact-icon mr-4 text-primary-600 dark:text-primary-400">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
														</svg>
													</div>
													<div class="contact-text">
														<h3 class="text-lg font-medium mb-1"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h3>
														<p class="text-gray-700 dark:text-gray-300">
															<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400"><?php echo esc_html( $phone ); ?></a>
														</p>
													</div>
												</div>
											<?php endif; ?>
											
											<?php if ( $email = get_field( 'contact_email' ) ) : ?>
												<div class="contact-email flex">
													<div class="contact-icon mr-4 text-primary-600 dark:text-primary-400">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
														</svg>
													</div>
													<div class="contact-text">
														<h3 class="text-lg font-medium mb-1"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h3>
														<p class="text-gray-700 dark:text-gray-300">
															<a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400"><?php echo esc_html( $email ); ?></a>
														</p>
													</div>
												</div>
											<?php endif; ?>
											
											<?php if ( $hours = get_field( 'contact_hours' ) ) : ?>
												<div class="contact-hours flex">
													<div class="contact-icon mr-4 text-primary-600 dark:text-primary-400">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
														</svg>
													</div>
													<div class="contact-text">
														<h3 class="text-lg font-medium mb-1"><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h3>
														<p class="text-gray-700 dark:text-gray-300"><?php echo nl2br( esc_html( $hours ) ); ?></p>
													</div>
												</div>
											<?php endif; ?>
										</div>
										
										<?php if ( have_rows( 'contact_social_links' ) ) : ?>
											<div class="contact-social mt-8">
												<h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
												<div class="social-links flex space-x-4">
													<?php while ( have_rows( 'contact_social_links' ) ) : the_row(); ?>
														<a href="<?php echo esc_url( get_sub_field( 'url' ) ); ?>" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer">
															<?php
															$platform = get_sub_field( 'platform' );
															switch ( $platform ) {
																case 'facebook':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" /></svg>';
																	break;
																case 'twitter':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>';
																	break;
																case 'instagram':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" /></svg>';
																	break;
																case 'linkedin':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>';
																	break;
																case 'youtube':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" /></svg>';
																	break;
																case 'pinterest':
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" /></svg>';
																	break;
																default:
																	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>';
															}
															?>
														</a>
													<?php endwhile; ?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php if ( get_field( 'contact_map_enable' ) ) : ?>
					<section class="contact-map">
						<?php
						$map_embed_code = get_field( 'contact_map_embed' );
						if ( $map_embed_code ) {
							echo '<div class="map-container h-96">' . $map_embed_code . '</div>';
						}
						?>
					</section>
				<?php endif; ?>
			</article>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();