<?php
/**
 * The template for displaying the FAQ page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<header class="page-header text-center mb-12">
			<h1 class="page-title text-5xl font-bold"><?php esc_html_e( 'Frequently Asked Questions', 'aqualuxe' ); ?></h1>
		</header>

		<div class="page-content max-w-3xl mx-auto">
			<div class="space-y-4">
			<?php
			$faq_args = array(
				'post_type'      => 'faq',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			);
			$faqs = new WP_Query( $faq_args );
			if ( $faqs->have_posts() ) :
				while ( $faqs->have_posts() ) :
					$faqs->the_post();
					?>
					<details class="faq-item bg-gray-100 dark:bg-gray-800 p-6 rounded-lg">
						<summary class="font-semibold text-lg cursor-pointer"><?php the_title(); ?></summary>
						<div class="faq-content prose dark:prose-invert max-w-none mt-4">
							<?php the_content(); ?>
						</div>
					</details>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				get_template_part( 'templates/content', 'none' );
			endif;
			?>
			</div>
		</div>
    </div>
</main><!-- #main -->

<?php
get_footer();
