<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get blog layout
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );

// Set post classes
$post_classes = array( 'post-item' );
if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) {
	$post_classes[] = 'post-grid-item';
} elseif ( 'list' === $blog_layout ) {
	$post_classes[] = 'post-list-item';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'post-thumbnail-img' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content">
		<header class="entry-header">
			<?php
			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					
					// Show categories if enabled
					if ( get_theme_mod( 'aqualuxe_show_post_categories', true ) ) {
						$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
						if ( $categories_list ) {
							echo '<span class="cat-links">' . $categories_list . '</span>';
						}
					}
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>

			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>
		</header><!-- .entry-header -->

		<?php if ( get_theme_mod( 'aqualuxe_show_post_excerpt', true ) ) : ?>
			<div class="entry-content">
				<?php
				if ( is_singular() ) {
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
							'after'  => '</div>',
						)
					);
				} else {
					// Get excerpt length from theme options
					$excerpt_length = get_theme_mod( 'aqualuxe_excerpt_length', 25 );
					
					// Get custom excerpt
					$excerpt = wp_trim_words( get_the_excerpt(), $excerpt_length, '...' );
					
					echo '<p>' . $excerpt . '</p>';
					
					// Read more link
					$read_more_text = get_theme_mod( 'aqualuxe_read_more_text', __( 'Read More', 'aqualuxe' ) );
					echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more-link">' . esc_html( $read_more_text ) . '</a>';
				}
				?>
			</div><!-- .entry-content -->
		<?php endif; ?>

		<?php if ( is_singular() ) : ?>
			<footer class="entry-footer">
				<?php aqualuxe_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->