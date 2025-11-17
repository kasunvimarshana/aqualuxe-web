<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page layout options
$page_layout = get_post_meta( get_the_ID(), '_aqualuxe_page_layout', true );
$page_header_style = get_post_meta( get_the_ID(), '_aqualuxe_page_header_style', true );
$hide_title = get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true );

// Set default classes
$header_classes = 'entry-header mb-8';
if ( $page_header_style === 'centered' ) {
	$header_classes .= ' text-center';
} elseif ( $page_header_style === 'hero' && has_post_thumbnail() ) {
	$header_classes .= ' relative min-h-[300px] flex items-center justify-center mb-0';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $page_header_style === 'hero' && has_post_thumbnail() ) : ?>
		<div class="page-hero relative mb-8">
			<?php the_post_thumbnail( 'full', array(
				'class' => 'w-full h-[50vh] object-cover',
			) ); ?>
			<div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
				<?php if ( ! $hide_title ) : ?>
					<h1 class="entry-title text-4xl md:text-5xl font-bold text-white"><?php the_title(); ?></h1>
				<?php endif; ?>
			</div>
		</div>
	<?php else : ?>
		<header class="<?php echo esc_attr( $header_classes ); ?>">
			<?php if ( ! $hide_title ) : ?>
				<?php the_title( '<h1 class="entry-title text-4xl font-bold text-gray-900 dark:text-gray-100">', '</h1>' ); ?>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( has_post_thumbnail() && $page_header_style !== 'hero' ) : ?>
			<div class="page-thumbnail mb-8">
				<?php the_post_thumbnail( 'full', array(
					'class' => 'w-full h-auto rounded-lg shadow-md',
				) ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

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

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link inline-flex items-center px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->