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
		<?php aqualuxe_post_thumbnail(); ?>

		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<div class="read-more-link">
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<span class="screen-reader-text">
						<?php
						/* translators: %s: Name of current post. */
						printf( esc_html__( 'about %s', 'aqualuxe' ), get_the_title() );
						?>
					</span>
				</a>
			</div>
		</footer><!-- .entry-footer -->
	</div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->