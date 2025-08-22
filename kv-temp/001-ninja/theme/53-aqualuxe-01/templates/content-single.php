<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-inner">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php aqualuxe_post_meta(); ?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<?php aqualuxe_post_thumbnail_html( null, 'large' ); ?>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php aqualuxe_post_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->