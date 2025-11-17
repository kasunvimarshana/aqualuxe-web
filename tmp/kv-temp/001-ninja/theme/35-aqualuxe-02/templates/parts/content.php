<?php
/**
 * Template part for displaying posts
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
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
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
				<?php
			endif;
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php aqualuxe_entry_meta(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->