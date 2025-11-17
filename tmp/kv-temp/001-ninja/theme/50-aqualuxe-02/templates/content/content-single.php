<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
	<header class="post-header">
		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
		
		<div class="post-meta">
			<span class="post-date">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
					<path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
				</svg>
				<?php aqualuxe_posted_on(); ?>
			</span>
			
			<span class="post-author">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
					<path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
				</svg>
				<?php aqualuxe_posted_by(); ?>
			</span>
			
			<?php if ( has_category() ) : ?>
				<span class="post-categories">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
						<path fill-rule="evenodd" d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
					</svg>
					<?php aqualuxe_post_categories(); ?>
				</span>
			<?php endif; ?>
			
			<?php if ( function_exists( 'aqualuxe_reading_time' ) ) : ?>
				<span class="post-reading-time">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
						<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
					</svg>
					<?php aqualuxe_reading_time(); ?>
				</span>
			<?php endif; ?>
		</div><!-- .post-meta -->
	</header><!-- .post-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'large', array( 'class' => 'post-image' ) ); ?>
			
			<?php if ( get_the_post_thumbnail_caption() ) : ?>
				<figcaption class="post-thumbnail-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="post-content">
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
	</div><!-- .post-content -->

	<footer class="post-footer">
		<?php if ( has_tag() ) : ?>
			<div class="post-tags">
				<h4><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></h4>
				<?php aqualuxe_post_tags(); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_social_sharing' ) ) : ?>
			<div class="post-share">
				<h4><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></h4>
				<?php aqualuxe_social_sharing(); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_author_box' ) ) : ?>
			<?php aqualuxe_author_box(); ?>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_related_posts' ) ) : ?>
			<?php aqualuxe_related_posts(); ?>
		<?php endif; ?>
	</footer><!-- .post-footer -->
</article><!-- #post-<?php the_ID(); ?> -->