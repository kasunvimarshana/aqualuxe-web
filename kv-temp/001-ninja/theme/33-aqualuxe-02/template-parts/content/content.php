<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

$aqualuxe_post_classes = array(
	'post-card',
	'bg-white',
	'dark:bg-dark-800',
	'rounded-lg',
	'overflow-hidden',
	'shadow-sm',
	'border',
	'border-gray-100',
	'dark:border-dark-700',
	'transition-transform',
	'duration-300',
	'hover:-translate-y-1',
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $aqualuxe_post_classes ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'class' => 'w-full h-48 sm:h-56 object-cover',
						'alt'   => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content-wrapper p-5">
		<header class="entry-header mb-3">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title text-2xl font-serif font-bold text-dark-800 dark:text-white mb-3">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title text-xl font-serif font-bold"><a href="' . esc_url( get_permalink() ) . '" class="text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" rel="bookmark">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta text-sm text-gray-500 dark:text-gray-400 mt-2">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content text-gray-600 dark:text-gray-300 mb-4">
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
			endif;
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer flex items-center justify-between text-sm">
			<?php if ( ! is_singular() ) : ?>
				<a href="<?php the_permalink(); ?>" class="read-more text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<span class="sr-only"><?php echo esc_html( get_the_title() ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			<?php endif; ?>

			<?php aqualuxe_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->