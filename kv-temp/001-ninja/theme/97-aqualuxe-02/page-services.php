<?php
/**
 * The template for displaying the Services page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<header class="page-header text-center mb-12">
			<h1 class="page-title text-5xl font-bold"><?php esc_html_e( 'Our Services', 'aqualuxe' ); ?></h1>
		</header>

		<div class="page-content">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			$services_args = array(
				'post_type'      => 'service',
				'posts_per_page' => -1,
			);
			$services = new WP_Query( $services_args );
			if ( $services->have_posts() ) :
				while ( $services->have_posts() ) :
					$services->the_post();
					get_template_part( 'templates/content', 'service' );
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
