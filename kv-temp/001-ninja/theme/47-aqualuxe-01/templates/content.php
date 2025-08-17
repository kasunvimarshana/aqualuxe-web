<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0'); ?>>
	<header class="entry-header mb-4">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-3xl font-bold">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta text-sm text-gray-600 mt-2">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
		<div class="entry-thumbnail mb-4">
			<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content prose max-w-none">
		<?php
		if ( is_singular() ) :
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
		else :
			the_excerpt();
			?>
			<div class="read-more mt-4">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<?php aqualuxe_svg_icon( 'arrow-right', array( 'class' => 'w-4 h-4 ml-1' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-4">
		<?php aqualuxe_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->