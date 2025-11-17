<?php
/**
 * Template part for displaying posts in archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('archive-post mb-8'); ?>>
	<div class="archive-post-inner bg-white rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
					<?php the_post_thumbnail( 'aqualuxe-blog-thumbnail', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="entry-content-wrap p-6">
			<header class="entry-header mb-4">
				<?php
				the_title( '<h2 class="entry-title text-xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>' );

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

			<div class="entry-content prose prose-sm max-w-none mb-4">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->

			<footer class="entry-footer flex items-center justify-between">
				<div class="read-more">
					<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm">
						<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
						<?php aqualuxe_svg_icon( 'arrow-right', array( 'class' => 'w-4 h-4 ml-1' ) ); ?>
					</a>
				</div>

				<?php if ( has_category() ) : ?>
					<div class="post-categories text-sm">
						<?php
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="text-gray-600 hover:text-primary-600">' . esc_html( $categories[0]->name ) . '</a>';
						}
						?>
					</div>
				<?php endif; ?>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->