<?php
/**
 * Template part for displaying author bio
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if not on a single post or if author bio is disabled
if ( ! is_single() || ! get_theme_mod( 'aqualuxe_show_author_bio', true ) ) {
	return;
}

$author_id = get_the_author_meta( 'ID' );
$author_bio = get_the_author_meta( 'description' );

// Only show if there's a bio
if ( ! $author_bio ) {
	return;
}
?>

<div class="author-bio">
	<div class="author-avatar">
		<?php echo get_avatar( $author_id, 96, '', esc_attr( get_the_author() ), array( 'class' => 'author-avatar-image' ) ); ?>
	</div>

	<div class="author-content">
		<h3 class="author-title">
			<?php
			/* translators: %s: Author name */
			printf( esc_html__( 'About %s', 'aqualuxe' ), esc_html( get_the_author() ) );
			?>
		</h3>

		<div class="author-description">
			<?php echo wp_kses_post( wpautop( $author_bio ) ); ?>
		</div>

		<a class="author-link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
			<?php
			/* translators: %s: Author name */
			printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author() ) );
			?>
		</a>
	</div>
</div><!-- .author-bio -->