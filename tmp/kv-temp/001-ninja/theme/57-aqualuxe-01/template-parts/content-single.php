<?php
/**
 * Template part for displaying posts in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		the_title( '<h1 class="entry-title">', '</h1>' );

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

	<?php aqualuxe_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
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
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php aqualuxe_entry_footer(); ?>
		
		<?php if ( function_exists( 'aqualuxe_social_sharing' ) ) : ?>
			<div class="social-sharing">
				<h3><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
				<?php aqualuxe_social_sharing(); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_author_bio' ) ) : ?>
			<div class="author-bio">
				<?php aqualuxe_author_bio(); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_related_posts' ) ) : ?>
			<div class="related-posts">
				<h3><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
				<?php aqualuxe_related_posts(); ?>
			</div>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->