<?php
/**
 * Template part for displaying post footers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Check if we're displaying a single post
$aqualuxe_is_singular = is_singular();

// Check if we should display post tags
$aqualuxe_show_tags = get_theme_mod( 'aqualuxe_show_post_tags', true );

// Check if we should display post categories
$aqualuxe_show_categories = get_theme_mod( 'aqualuxe_show_post_categories', true );

// Check if we should display edit link
$aqualuxe_show_edit_link = get_edit_post_link() && current_user_can( 'edit_post', get_the_ID() );

// Only show footer if we have tags, categories, or edit link
if ( ! ( ( 'post' === get_post_type() && ( $aqualuxe_show_tags || $aqualuxe_show_categories ) ) || $aqualuxe_show_edit_link ) ) {
	return;
}
?>

<footer class="entry-footer <?php echo $aqualuxe_is_singular ? 'mt-8 pt-6 border-t border-gray-200 dark:border-dark-700' : ''; ?>">
	<?php if ( 'post' === get_post_type() ) : ?>
		<div class="post-taxonomies">
			<?php
			// Display categories if enabled
			if ( $aqualuxe_show_categories ) :
				$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
				if ( $categories_list ) :
					?>
					<div class="cat-links text-sm <?php echo $aqualuxe_is_singular ? 'mb-3' : ''; ?>">
						<span class="taxonomy-label font-medium text-dark-700 dark:text-gray-300">
							<?php esc_html_e( 'Categories:', 'aqualuxe' ); ?>
						</span>
						<span class="taxonomy-list text-gray-600 dark:text-gray-400">
							<?php echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_the_category_list() ?>
						</span>
					</div>
					<?php
				endif;
			endif;

			// Display tags if enabled
			if ( $aqualuxe_show_tags ) :
				$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
				if ( $tags_list ) :
					?>
					<div class="tag-links text-sm">
						<span class="taxonomy-label font-medium text-dark-700 dark:text-gray-300">
							<?php esc_html_e( 'Tags:', 'aqualuxe' ); ?>
						</span>
						<span class="taxonomy-list text-gray-600 dark:text-gray-400">
							<?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_the_tag_list() ?>
						</span>
					</div>
					<?php
				endif;
			endif;
			?>
		</div>
	<?php endif; ?>

	<?php
	// Display edit link if user can edit
	if ( $aqualuxe_show_edit_link ) :
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
			'<div class="edit-link text-sm mt-3">',
			'</div>',
			null,
			'inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-md transition-colors duration-200 text-gray-600 dark:text-gray-400'
		);
	endif;
	?>
	
	<?php
	// Display post navigation for single posts
	if ( $aqualuxe_is_singular && 'post' === get_post_type() && get_theme_mod( 'aqualuxe_show_post_navigation', true ) ) :
		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span> <span class="nav-title text-lg font-medium text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">%title</span>',
				'next_text' => '<span class="nav-subtitle text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span> <span class="nav-title text-lg font-medium text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">%title</span>',
				'class'     => 'post-navigation mt-8 pt-6 border-t border-gray-200 dark:border-dark-700',
			)
		);
	endif;
	?>
</footer><!-- .entry-footer -->