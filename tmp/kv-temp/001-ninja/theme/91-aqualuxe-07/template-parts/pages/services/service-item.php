<?php
/**
 * Displays a single service item.
 *
 * @package AquaLuxe
 */

?>
<div class="service-item bg-white rounded-lg shadow-md p-6 text-center">
	<?php if ( has_post_thumbnail() ) : ?>
        <div class="service-thumbnail mb-4">
			<?php the_post_thumbnail( 'medium_large', [ 'class' => 'mx-auto rounded-lg' ] ); ?>
        </div>
	<?php endif; ?>
    <h3 class="text-2xl font-bold mb-2"><?php the_title(); ?></h3>
    <div class="service-content">
		<?php the_excerpt(); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="text-blue-500 hover:underline mt-4 inline-block"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
</div>
