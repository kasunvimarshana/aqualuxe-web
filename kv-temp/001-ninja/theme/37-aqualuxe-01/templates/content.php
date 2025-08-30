<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<div class="post-card-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail( 'aqualuxe-thumbnail', array( 'class' => 'post-thumbnail-image' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="post-card-content">
			<header class="entry-header">
				<?php
				if ( is_sticky() && is_home() && ! is_paged() ) {
					printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'aqualuxe' ) );
				}
				
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

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
				the_excerpt();
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <span class="screen-reader-text"><?php esc_html_e( 'about', 'aqualuxe' ); ?> <?php the_title(); ?></span></a>
			</footer><!-- .entry-footer -->
		</div><!-- .post-card-content -->
	</div><!-- .post-card-inner -->
</article><!-- #post-<?php the_ID(); ?> -->