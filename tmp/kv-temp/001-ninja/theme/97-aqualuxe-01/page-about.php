<?php
/**
 * The template for displaying the About page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
			<header class="entry-header text-center mb-12">
				<?php the_title( '<h1 class="entry-title text-5xl font-bold">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<section id="company-history" class="mb-16">
					<div class="grid md:grid-cols-2 gap-12 items-center">
						<div>
							<h2 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Our History', 'aqualuxe' ); ?></h2>
							<p class="text-lg">
								<?php // Demo content for company history ?>
								Founded in 2020, AquaLuxe started as a small local farm with a passion for rare and exotic fish. Today, we are a globally recognized brand, delivering elegance to aquatic life worldwide.
							</p>
						</div>
						<div class="rounded-lg overflow-hidden shadow-lg">
							<img src="https://via.placeholder.com/600x400" alt="AquaLuxe facility" class="w-full h-auto">
						</div>
					</div>
				</section>

				<section id="mission-values" class="mb-16 bg-gray-100 dark:bg-gray-800 p-12 rounded-lg">
					<h2 class="text-3xl font-bold text-center mb-8"><?php esc_html_e( 'Mission & Values', 'aqualuxe' ); ?></h2>
					<p class="text-lg text-center max-w-3xl mx-auto">
						<?php // Demo content for mission and values ?>
						Our mission is to provide the highest quality aquatic life, sourced ethically and sustainably. We believe in innovation, customer satisfaction, and the preservation of aquatic ecosystems.
					</p>
				</section>

				<section id="team" class="mb-16">
					<h2 class="text-3xl font-bold text-center mb-8"><?php esc_html_e( 'Our Team', 'aqualuxe' ); ?></h2>
					<p class="text-lg text-center max-w-3xl mx-auto mb-12">
						<?php // Demo content for team ?>
						Meet the passionate team of biologists, aquascapers, and customer service professionals who make AquaLuxe possible.
					</p>
					<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
						<?php // Placeholder for team members loop ?>
						<div class="team-member text-center">
							<img src="https://via.placeholder.com/150" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 shadow-md">
							<h3 class="text-xl font-semibold">John Doe</h3>
							<p class="text-gray-500 dark:text-gray-400">Lead Biologist</p>
						</div>
						<div class="team-member text-center">
							<img src="https://via.placeholder.com/150" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 shadow-md">
							<h3 class="text-xl font-semibold">Jane Smith</h3>
							<p class="text-gray-500 dark:text-gray-400">Head of Aquascaping</p>
						</div>
						<div class="team-member text-center">
							<img src="https://via.placeholder.com/150" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 shadow-md">
							<h3 class="text-xl font-semibold">Peter Jones</h3>
							<p class="text-gray-500 dark:text-gray-400">Customer Relations</p>
						</div>
					</div>
				</section>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->
    </div>
</main><!-- #main -->

<?php
get_footer();
