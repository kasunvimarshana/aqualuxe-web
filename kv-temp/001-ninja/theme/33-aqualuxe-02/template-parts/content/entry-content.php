<?php
/**
 * Template part for displaying post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Check if we're displaying a single post or an excerpt
$aqualuxe_is_singular = is_singular();

// Get post format
$aqualuxe_post_format = get_post_format() ?: 'standard';

// Check if we should display the full content or excerpt
$aqualuxe_show_full_content = $aqualuxe_is_singular || 'quote' === $aqualuxe_post_format || 'link' === $aqualuxe_post_format;

// Check if we should display social sharing buttons
$aqualuxe_show_social_sharing = $aqualuxe_is_singular && get_theme_mod( 'aqualuxe_show_social_sharing', true );

// Check if we should display table of contents
$aqualuxe_show_toc = $aqualuxe_is_singular && get_theme_mod( 'aqualuxe_show_toc', true ) && 'post' === get_post_type();
?>

<div class="entry-content <?php echo $aqualuxe_is_singular ? 'prose dark:prose-invert max-w-none' : ''; ?> text-gray-700 dark:text-gray-200">
	<?php
	// Display table of contents if enabled
	if ( $aqualuxe_show_toc && function_exists( 'aqualuxe_generate_toc' ) ) :
		aqualuxe_generate_toc();
	endif;
	
	// Display content or excerpt based on context
	if ( $aqualuxe_show_full_content ) :
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
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-number px-3 py-1 bg-gray-100 dark:bg-dark-700 rounded-md mr-2">',
				'link_after'  => '</span>',
			)
		);
	else :
		// For excerpts, check if a manual excerpt exists
		if ( has_excerpt() ) :
			the_excerpt();
		else :
			// Generate an automatic excerpt with custom length
			$excerpt_length = get_theme_mod( 'aqualuxe_excerpt_length', 25 );
			echo '<p>' . wp_trim_words( get_the_content(), $excerpt_length, '...' ) . '</p>';
		endif;
	endif;
	?>
</div><!-- .entry-content -->

<?php
// Display social sharing buttons if enabled
if ( $aqualuxe_show_social_sharing && function_exists( 'aqualuxe_social_sharing' ) ) :
	?>
	<div class="social-sharing-container mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
		<h3 class="text-lg font-medium text-dark-800 dark:text-white mb-4">
			<?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?>
		</h3>
		<?php aqualuxe_social_sharing(); ?>
	</div>
	<?php
endif;

// Display author bio if enabled
if ( $aqualuxe_is_singular && 'post' === get_post_type() && get_theme_mod( 'aqualuxe_show_author_bio', true ) ) :
	?>
	<div class="author-bio mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
		<div class="flex flex-col sm:flex-row items-center sm:items-start">
			<div class="author-avatar mb-4 sm:mb-0 sm:mr-6">
				<?php
				$author_bio_avatar_size = apply_filters( 'aqualuxe_author_bio_avatar_size', 96 );
				echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size, '', get_the_author(), array( 'class' => 'rounded-full' ) );
				?>
			</div>
			
			<div class="author-description text-center sm:text-left">
				<h3 class="author-title text-lg font-medium text-dark-800 dark:text-white mb-2">
					<?php
					/* translators: %s: Author name */
					printf( esc_html__( 'About %s', 'aqualuxe' ), get_the_author() );
					?>
				</h3>
				
				<div class="author-bio-content text-gray-600 dark:text-gray-300 mb-3">
					<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
				</div>
				
				<a class="author-link text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php
					/* translators: %s: Author name */
					printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() );
					?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
	</div>
	<?php
endif;