<?php
/**
 * Displays the content for a single post.
 *
 * @package AquaLuxe
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 shadow-md rounded-lg p-6'); ?>>
	<header class="entry-header mb-4">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-3xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail mb-4">
			<?php the_post_thumbnail('large', ['class' => 'rounded-lg']); ?>
		</div>
	<?php endif; ?>

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
				get_the_title()
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

	<footer class="entry-footer mt-4 text-sm text-gray-500 dark:text-gray-400">
		<span class="cat-links"><?php echo get_the_category_list( ', ' ); ?></span>
		<span class="tags-links"><?php echo get_the_tag_list( '', ', ' ); ?></span>
		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'aqualuxe' ), __( '1 Comment', 'aqualuxe' ), __( '% Comments', 'aqualuxe' ) ); ?></span>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
