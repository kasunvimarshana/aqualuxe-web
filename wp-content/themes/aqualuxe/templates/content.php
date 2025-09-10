<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none mb-12'); ?>>
	<header class="entry-header mb-8">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-3xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta text-sm text-gray-500 dark:text-gray-400">
				<?php
				// Example meta, you can create helper functions for these
				echo '<span>' . get_the_date() . '</span>';
				echo '<span class="mx-2">&bull;</span>';
				echo '<span>' . get_the_author() . '</span>';
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && is_singular() ) : ?>
		<div class="post-thumbnail mb-8 rounded-lg overflow-hidden">
			<?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-auto' ] ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		if ( is_singular() ) {
			the_content();
		} else {
			the_excerpt();
			echo '<a href="' . esc_url( get_permalink() ) . '" class="text-blue-600 dark:text-blue-400 hover:underline">' . esc_html__( 'Continue reading', 'aqualuxe' ) . '</a>';
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-8">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() ) : ?>
	<footer class="entry-footer mt-8">
		<div class="entry-meta text-sm text-gray-500 dark:text-gray-400">
			<?php
			// Example footer meta
			$categories = get_the_category_list( ', ' );
			if ( $categories ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %s', 'aqualuxe' ) . '</span>', $categories );
			}
			$tags = get_the_tag_list( '', ', ' );
			if ( $tags ) {
				printf( '<span class="tags-links ml-4">' . esc_html__( 'Tagged %s', 'aqualuxe' ) . '</span>', $tags );
			}
			?>
		</div>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
