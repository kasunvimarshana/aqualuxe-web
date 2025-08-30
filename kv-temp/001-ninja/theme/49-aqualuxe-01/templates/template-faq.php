<?php
/**
 * Template Name: FAQ Page
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
						<div class="max-w-4xl mx-auto">
							<?php if ( get_the_content() ) : ?>
								<div class="faq-intro prose prose-lg dark:prose-invert mb-12">
									<?php the_content(); ?>
								</div>
							<?php endif; ?>

							<?php if ( have_rows( 'faq_categories' ) ) : ?>
								<div class="faq-categories mb-12">
									<div class="faq-categories-nav flex flex-wrap justify-center gap-4 mb-8">
										<?php
										$category_count = 0;
										while ( have_rows( 'faq_categories' ) ) : the_row();
											$category_count++;
											$category_id = 'faq-category-' . $category_count;
											$category_name = get_sub_field( 'category_name' );
											$active_class = $category_count === 1 ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600';
											?>
											<button type="button" class="faq-category-button px-4 py-2 rounded-md transition duration-200 <?php echo esc_attr( $active_class ); ?>" data-category="<?php echo esc_attr( $category_id ); ?>">
												<?php echo esc_html( $category_name ); ?>
											</button>
										<?php endwhile; ?>
									</div>

									<div class="faq-categories-content">
										<?php
										$category_count = 0;
										while ( have_rows( 'faq_categories' ) ) : the_row();
											$category_count++;
											$category_id = 'faq-category-' . $category_count;
											$display_style = $category_count === 1 ? 'block' : 'hidden';
											?>
											<div id="<?php echo esc_attr( $category_id ); ?>" class="faq-category <?php echo esc_attr( $display_style ); ?>">
												<?php if ( have_rows( 'faqs' ) ) : ?>
													<div class="faq-items space-y-4">
														<?php
														$faq_count = 0;
														while ( have_rows( 'faqs' ) ) : the_row();
															$faq_count++;
															$faq_id = 'faq-' . $category_count . '-' . $faq_count;
															$question = get_sub_field( 'question' );
															$answer = get_sub_field( 'answer' );
															?>
															<div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
																<button type="button" class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>">
																	<span class="text-lg font-medium"><?php echo esc_html( $question ); ?></span>
																	<svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
																		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
																	</svg>
																</button>
																<div id="<?php echo esc_attr( $faq_id ); ?>" class="faq-answer px-6 pb-4 hidden">
																	<div class="prose prose-lg dark:prose-invert">
																		<?php echo wp_kses_post( $answer ); ?>
																	</div>
																</div>
															</div>
														<?php endwhile; ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							<?php elseif ( have_rows( 'faqs' ) ) : ?>
								<div class="faq-items space-y-4">
									<?php
									$faq_count = 0;
									while ( have_rows( 'faqs' ) ) : the_row();
										$faq_count++;
										$faq_id = 'faq-' . $faq_count;
										$question = get_sub_field( 'question' );
										$answer = get_sub_field( 'answer' );
										?>
										<div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
											<button type="button" class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>">
												<span class="text-lg font-medium"><?php echo esc_html( $question ); ?></span>
												<svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
												</svg>
											</button>
											<div id="<?php echo esc_attr( $faq_id ); ?>" class="faq-answer px-6 pb-4 hidden">
												<div class="prose prose-lg dark:prose-invert">
													<?php echo wp_kses_post( $answer ); ?>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

							<?php if ( get_field( 'faq_contact_section_enable' ) ) : ?>
								<div class="faq-contact mt-16 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
									<h2 class="text-2xl font-bold mb-4"><?php echo esc_html( get_field( 'faq_contact_title' ) ?: __( 'Still Have Questions?', 'aqualuxe' ) ); ?></h2>
									<p class="text-lg text-gray-700 dark:text-gray-300 mb-6"><?php echo esc_html( get_field( 'faq_contact_text' ) ?: __( 'If you couldn\'t find the answer to your question, please contact us.', 'aqualuxe' ) ); ?></p>
									<a href="<?php echo esc_url( get_field( 'faq_contact_button_url' ) ?: get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
										<?php echo esc_html( get_field( 'faq_contact_button_text' ) ?: __( 'Contact Us', 'aqualuxe' ) ); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</article>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<script>
document.addEventListener('DOMContentLoaded', function() {
	// FAQ Category Tabs
	const categoryButtons = document.querySelectorAll('.faq-category-button');
	const categoryContents = document.querySelectorAll('.faq-category');
	
	categoryButtons.forEach(button => {
		button.addEventListener('click', () => {
			const categoryId = button.getAttribute('data-category');
			
			// Update active button
			categoryButtons.forEach(btn => {
				btn.classList.remove('bg-primary-600', 'text-white');
				btn.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
			});
			button.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
			button.classList.add('bg-primary-600', 'text-white');
			
			// Show selected category content
			categoryContents.forEach(content => {
				content.classList.add('hidden');
				if (content.id === categoryId) {
					content.classList.remove('hidden');
				}
			});
		});
	});
	
	// FAQ Accordion
	const faqQuestions = document.querySelectorAll('.faq-question');
	
	faqQuestions.forEach(question => {
		question.addEventListener('click', () => {
			const faqItem = question.parentElement;
			const answer = question.nextElementSibling;
			const icon = question.querySelector('.faq-icon');
			const isExpanded = question.getAttribute('aria-expanded') === 'true';
			
			// Toggle current FAQ item
			question.setAttribute('aria-expanded', !isExpanded);
			answer.classList.toggle('hidden');
			icon.classList.toggle('rotate-180');
		});
	});
});
</script>

<?php
get_footer();