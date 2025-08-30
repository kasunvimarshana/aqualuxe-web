<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

$post_classes = array(
	'post-card',
	'mb-8',
	'bg-white',
	'rounded-lg',
	'shadow-md',
	'overflow-hidden',
	'transition-transform',
	'duration-300',
	'hover:shadow-lg',
	'hover:-translate-y-1',
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-auto' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content p-6">
		<header class="entry-header mb-4">
			<?php
			if ( is_sticky() && is_home() && ! is_paged() ) {
	\t\t/* translators: %s: Text displayed for sticky posts */
				printf( '<span class="sticky-post bg-primary text-white text-xs px-2 py-1 rounded-full mb-2 inline-block">%s</span>', esc_html__( 'Featured', 'aqualuxe' ) );
			}

			the_title( '<h2 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="text-gray-900 hover:text-primary transition-colors">', '</a></h2>' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta text-sm text-gray-600 mb-3">
					<?php
					aqualuxe_posted_by();
					aqualuxe_posted_on();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content mb-4">
			<?php
			if ( get_theme_mod( 'aqualuxe_excerpt_or_content', 'excerpt' ) === 'excerpt' ) {
				the_excerpt();
			} else {
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
			}
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer flex items-center justify-between text-sm">
			<?php aqualuxe_entry_footer(); ?>
			
			<a href="<?php the_permalink(); ?>" class="read-more text-primary hover:underline font-medium">
				<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->