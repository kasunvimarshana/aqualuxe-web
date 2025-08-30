<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
	<header class="entry-header mb-8">
		<?php the_title( '<h1 class="entry-title text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">', '</h1>' ); ?>
		
		<div class="entry-meta flex items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
			<?php
			aqualuxe_posted_on();
			aqualuxe_posted_by();
			?>
		</div><!-- .entry-meta -->
		
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail mb-8">
				<?php the_post_thumbnail( 'full', array(
					'class' => 'w-full h-auto rounded-lg shadow-lg',
				) ); ?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links my-6 p-4 bg-gray-100 dark:bg-gray-800 rounded">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
				'link_before' => '<span class="px-2 py-1 bg-white dark:bg-gray-700 rounded mx-1">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
		<div class="flex flex-wrap gap-4">
			<?php aqualuxe_entry_footer(); ?>
		</div>
		
		<?php if ( function_exists( 'aqualuxe_social_sharing' ) ) : ?>
			<div class="social-sharing mt-6">
				<h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
				<?php aqualuxe_social_sharing(); ?>
			</div>
		<?php endif; ?>
		
		<?php
		// Author bio
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'template-parts/biography' );
		endif;
		?>
	</footer><!-- .entry-footer -->
	
	<div class="post-navigation mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
		<?php
		the_post_navigation(
			array(
				'prev_text' => '<div class="nav-subtitle text-sm text-gray-600 dark:text-gray-400 mb-1">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</div><div class="nav-title text-lg font-medium text-blue-600 dark:text-blue-400">%title</div>',
				'next_text' => '<div class="nav-subtitle text-sm text-gray-600 dark:text-gray-400 mb-1">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</div><div class="nav-title text-lg font-medium text-blue-600 dark:text-blue-400">%title</div>',
				'class' => 'flex flex-wrap justify-between gap-4',
			)
		);
		?>
	</div><!-- .post-navigation -->
	
	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>
</article><!-- #post-<?php the_ID(); ?> -->