<?php
/**
 * Template Name: About Page
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
						<div class="max-w-4xl mx-auto prose prose-lg dark:prose-invert">
							<?php the_content(); ?>
						</div>
					</div>
				</div>

				<?php if ( get_field( 'about_team_section_enable' ) !== false ) : ?>
					<section class="about-team py-16 bg-gray-50 dark:bg-gray-900">
						<div class="container mx-auto px-4">
							<div class="section-header text-center mb-12">
								<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( get_field( 'about_team_section_title' ) ?: __( 'Our Team', 'aqualuxe' ) ); ?></h2>
								<?php if ( $team_subtitle = get_field( 'about_team_section_subtitle' ) ) : ?>
									<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $team_subtitle ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( have_rows( 'about_team_members' ) ) : ?>
								<div class="team-members grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
									<?php while ( have_rows( 'about_team_members' ) ) : the_row(); ?>
										<div class="team-member bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
											<?php if ( $member_image = get_sub_field( 'image' ) ) : ?>
												<div class="team-member-image">
													<img src="<?php echo esc_url( $member_image ); ?>" alt="<?php echo esc_attr( get_sub_field( 'name' ) ); ?>" class="w-full h-64 object-cover">
												</div>
											<?php endif; ?>
											<div class="team-member-info p-6">
												<h3 class="team-member-name text-xl font-bold mb-1"><?php echo esc_html( get_sub_field( 'name' ) ); ?></h3>
												<?php if ( $member_position = get_sub_field( 'position' ) ) : ?>
													<p class="team-member-position text-primary-600 dark:text-primary-400 mb-3"><?php echo esc_html( $member_position ); ?></p>
												<?php endif; ?>
												<?php if ( $member_bio = get_sub_field( 'bio' ) ) : ?>
													<p class="team-member-bio text-gray-700 dark:text-gray-300"><?php echo esc_html( $member_bio ); ?></p>
												<?php endif; ?>
												<?php if ( have_rows( 'social_links' ) ) : ?>
													<div class="team-member-social flex mt-4 space-x-3">
														<?php while ( have_rows( 'social_links' ) ) : the_row(); ?>
															<a href="<?php echo esc_url( get_sub_field( 'url' ) ); ?>" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer">
																<?php
																$platform = get_sub_field( 'platform' );
																switch ( $platform ) {
																	case 'facebook':
																		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" /></svg>';
																		break;
																	case 'twitter':
																		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>';
																		break;
																	case 'linkedin':
																		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>';
																		break;
																	case 'instagram':
																		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" /></svg>';
																		break;
																	default:
																		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>';
																}
																?>
															</a>
														<?php endwhile; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</section>
				<?php endif; ?>

				<?php if ( get_field( 'about_features_section_enable' ) !== false ) : ?>
					<section class="about-features py-16">
						<div class="container mx-auto px-4">
							<div class="section-header text-center mb-12">
								<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( get_field( 'about_features_section_title' ) ?: __( 'Why Choose Us', 'aqualuxe' ) ); ?></h2>
								<?php if ( $features_subtitle = get_field( 'about_features_section_subtitle' ) ) : ?>
									<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $features_subtitle ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( have_rows( 'about_features' ) ) : ?>
								<div class="features-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
									<?php while ( have_rows( 'about_features' ) ) : the_row(); ?>
										<div class="feature-item text-center">
											<?php if ( $feature_icon = get_sub_field( 'icon' ) ) : ?>
												<div class="feature-icon mx-auto mb-4 w-16 h-16 flex items-center justify-center bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full">
													<img src="<?php echo esc_url( $feature_icon ); ?>" alt="" class="w-8 h-8">
												</div>
											<?php endif; ?>
											<h3 class="feature-title text-xl font-bold mb-3"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
											<?php if ( $feature_description = get_sub_field( 'description' ) ) : ?>
												<p class="feature-description text-gray-700 dark:text-gray-300"><?php echo esc_html( $feature_description ); ?></p>
											<?php endif; ?>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</section>
				<?php endif; ?>

				<?php if ( get_field( 'about_cta_section_enable' ) !== false ) : ?>
					<section class="about-cta py-16 bg-primary-600 text-white">
						<div class="container mx-auto px-4">
							<div class="max-w-3xl mx-auto text-center">
								<h2 class="cta-title text-3xl font-bold mb-4"><?php echo esc_html( get_field( 'about_cta_title' ) ?: __( 'Ready to Get Started?', 'aqualuxe' ) ); ?></h2>
								<?php if ( $cta_text = get_field( 'about_cta_text' ) ) : ?>
									<p class="cta-text text-lg mb-8"><?php echo esc_html( $cta_text ); ?></p>
								<?php endif; ?>
								<?php if ( $cta_button_text = get_field( 'about_cta_button_text' ) ) : ?>
									<a href="<?php echo esc_url( get_field( 'about_cta_button_url' ) ?: '#' ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-600 focus:ring-white">
										<?php echo esc_html( $cta_button_text ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</section>
				<?php endif; ?>
			</article>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();