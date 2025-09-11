<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
	
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php 
				the_post_thumbnail( 'aqualuxe-featured', array(
					'alt' => the_title_attribute( array( 'echo' => false ) ),
					'loading' => 'lazy',
					'decoding' => 'async'
				) ); 
				?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="post-content">
		
		<header class="entry-header">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php aqualuxe_posted_on(); ?>
					<?php aqualuxe_posted_by(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php
			if ( is_singular() ) {
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				) );
			} else {
				the_excerpt();
				?>
				<a href="<?php the_permalink(); ?>" class="read-more-link">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<?php echo aqualuxe_get_svg_icon( 'arrow-right' ); ?>
				</a>
				<?php
			}
			?>
		</div><!-- .entry-summary -->

		<?php if ( is_singular() ) : ?>
			<footer class="entry-footer">
				<?php aqualuxe_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>

	</div><!-- .post-content -->

</article><!-- #post-<?php the_ID(); ?> -->