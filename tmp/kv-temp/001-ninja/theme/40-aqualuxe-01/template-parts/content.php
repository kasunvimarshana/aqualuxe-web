<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
$post_classes = array( 'post-item' );
$post_classes[] = 'post-layout-' . $blog_layout;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
	<div class="post-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'post-thumbnail-image' ) ); ?>
				</a>
			</div><!-- .post-thumbnail -->
		<?php endif; ?>

		<div class="post-content">
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
				if ( $blog_layout === 'grid' || $blog_layout === 'masonry' ) {
					the_excerpt();
				} else {
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
				}

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
						'after'  => '</div>',
					)
				);
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php if ( $blog_layout === 'grid' || $blog_layout === 'masonry' ) : ?>
					<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
				<?php endif; ?>
				
				<?php aqualuxe_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</div><!-- .post-content -->
	</div><!-- .post-inner -->
</article><!-- #post-<?php the_ID(); ?> -->