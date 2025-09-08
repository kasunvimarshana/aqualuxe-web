<?php
/**
 * Displays the services grid on the services page.
 *
 * @package AquaLuxe
 */

?>
<div class="container mx-auto py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
		<?php
		$args = array(
			'post_type'      => 'service',
			'posts_per_page' => - 1,
		);
		$services_query = new WP_Query( $args );

		if ( $services_query->have_posts() ) :
			while ( $services_query->have_posts() ) : $services_query->the_post();
				get_template_part( 'template-parts/pages/services/service-item' );
			endwhile;
		else :
			echo '<p>' . esc_html__( 'No services found.', 'aqualuxe' ) . '</p>';
		endif;
		wp_reset_postdata();
		?>
    </div>
</div>
