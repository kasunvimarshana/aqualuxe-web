<?php
/**
 * Template part for displaying author biography
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<div class="author-bio">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
	</div><!-- .author-avatar -->

	<div class="author-info">
		<h2 class="author-title">
			<?php
			/* translators: %s: Author name */
			printf( esc_html__( 'About %s', 'aqualuxe' ), get_the_author() );
			?>
		</h2>
		<div class="author-description">
			<?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?>
		</div><!-- .author-description -->
		<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			<?php
			/* translators: %s: Author name */
			printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() );
			?>
		</a>
	</div><!-- .author-info -->
</div><!-- .author-bio -->