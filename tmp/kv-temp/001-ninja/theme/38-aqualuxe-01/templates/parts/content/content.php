<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_show_featured_image', true ) ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'aqualuxe-card', array( 'class' => 'card-image' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content">
		<header class="entry-header">
			<?php
			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>

			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <span class="screen-reader-text"><?php esc_html_e( 'about', 'aqualuxe' ); ?> <?php the_title(); ?></span></a>
		</footer><!-- .entry-footer -->
	</div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->