<?php
/**
 * The template for displaying the services page.
 *
 * Template Name: Services Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			the_title( '<h1 class="entry-title text-center text-4xl font-bold my-8">', '</h1>' );
			the_content();
		endwhile;
		?>

        <div class="container mx-auto py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $args = array(
                    'post_type' => 'service',
                    'posts_per_page' => -1,
                );
                $services_query = new WP_Query( $args );

                if ( $services_query->have_posts() ) :
                    while ( $services_query->have_posts() ) : $services_query->the_post();
                        ?>
                        <div class="service-item bg-white rounded-lg shadow-md p-6 text-center">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="service-thumbnail mb-4">
                                    <?php the_post_thumbnail( 'medium_large', ['class' => 'mx-auto rounded-lg'] ); ?>
                                </div>
                            <?php endif; ?>
                            <h3 class="text-2xl font-bold mb-2"><?php the_title(); ?></h3>
                            <div class="service-content">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="text-blue-500 hover:underline mt-4 inline-block"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
                        </div>
                        <?php
                    endwhile;
                else :
                    echo '<p>' . esc_html__( 'No services found.', 'aqualuxe' ) . '</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>

	</main><!-- #main -->

<?php
get_footer();
