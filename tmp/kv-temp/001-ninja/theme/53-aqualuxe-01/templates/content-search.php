<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-inner">
		<?php aqualuxe_post_thumbnail_html(); ?>
		
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php aqualuxe_post_meta(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<div class="read-more">
				<a href="<?php the_permalink(); ?>" class="read-more-link">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
				</a>
			</div>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<?php aqualuxe_post_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->