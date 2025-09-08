<?php
/**
 * Displays the author bio.
 *
 * @package AquaLuxe
 */

?>
<div class="author-bio bg-gray-100 dark:bg-gray-700 p-6 rounded-lg flex items-center">
	<div class="author-avatar mr-4">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', ['class' => 'rounded-full'] ); ?>
	</div>
	<div class="author-description">
		<h3 class="author-title text-xl font-bold mb-2"><?php echo get_the_author(); ?></h3>
		<p class="author-text"><?php the_author_meta( 'description' ); ?></p>
		<a class="author-link mt-2 inline-block text-blue-500 hover:underline" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			<?php printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() ); ?>
		</a>
	</div>
</div>
