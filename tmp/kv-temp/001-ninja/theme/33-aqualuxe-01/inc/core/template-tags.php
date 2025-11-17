<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function aqualuxe_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date. */
		esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function aqualuxe_posted_by() {
	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function aqualuxe_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
		if ( $categories_list ) {
			/* translators: 1: list of categories. */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
		if ( $tags_list ) {
			/* translators: 1: list of tags. */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		echo '</span>';
	}

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
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function aqualuxe_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
		</div><!-- .post-thumbnail -->

	<?php else : ?>

		<a class="post-thumbnail block mb-4 overflow-hidden rounded-lg" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'aqualuxe-blog',
				array(
					'class' => 'w-full h-auto transition-transform duration-300 hover:scale-105',
					'alt'   => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>
		</a>

		<?php
	endif; // End is_singular().
}

/**
 * Prints HTML with the comment count for the current post.
 */
function aqualuxe_comment_count() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		echo '</span>';
	}
}

/**
 * Displays the post excerpt with custom length.
 *
 * @param int $length Optional. Excerpt length. Default 55.
 */
function aqualuxe_the_excerpt( $length = 55 ) {
	$excerpt = get_the_excerpt();
	
	if ( ! $excerpt ) {
		$excerpt = get_the_content();
		$excerpt = strip_shortcodes( $excerpt );
		$excerpt = excerpt_remove_blocks( $excerpt );
		$excerpt = wp_strip_all_tags( $excerpt );
		$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
	}
	
	$excerpt = wp_trim_words( $excerpt, $length, '&hellip;' );
	
	echo '<p class="entry-excerpt">' . $excerpt . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the read more link.
 */
function aqualuxe_read_more_link() {
	$read_more_text = get_theme_mod( 'aqualuxe_blog_read_more_text', __( 'Read More', 'aqualuxe' ) );
	
	echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more-link">' . esc_html( $read_more_text ) . ' <span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a>';
}

/**
 * Displays the post author avatar.
 *
 * @param int $size Optional. Avatar size in pixels. Default 40.
 */
function aqualuxe_author_avatar( $size = 40 ) {
	echo get_avatar( get_the_author_meta( 'ID' ), $size, '', get_the_author(), array( 'class' => 'rounded-full' ) );
}

/**
 * Displays the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
	// Get the Template service instance.
	$theme = aqualuxe_get_theme_instance();
	$template = $theme->get_service( 'template' );
	
	// Display the breadcrumbs.
	echo $template->get_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the post thumbnail with responsive srcset.
 *
 * @param int    $post_id Optional. Post ID. Default current post.
 * @param string $size Optional. Image size. Default 'post-thumbnail'.
 * @param array  $attr Optional. Image attributes. Default empty array.
 */
function aqualuxe_responsive_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = array() ) {
	// Get the Template service instance.
	$theme = aqualuxe_get_theme_instance();
	$template = $theme->get_service( 'template' );
	
	// Display the responsive post thumbnail.
	echo $template->get_responsive_post_thumbnail( $post_id, $size, $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the dark mode toggle button.
 */
function aqualuxe_dark_mode_toggle() {
	// Check if dark mode is enabled in the customizer.
	$dark_mode_enabled = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
	
	// If dark mode is not enabled in the customizer, return early.
	if ( ! $dark_mode_enabled ) {
		return;
	}
	
	// Get the Dark_Mode service instance.
	$theme = aqualuxe_get_theme_instance();
	$dark_mode = $theme->get_service( 'dark_mode' );
	
	// Check if dark mode is active.
	$is_dark_mode = $dark_mode->is_dark_mode_active();
	
	// Display the dark mode toggle button.
	?>
	<button id="dark-mode-toggle" class="dark-mode-toggle" role="switch" aria-checked="<?php echo $is_dark_mode ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
		<span class="dark-mode-toggle-knob"></span>
		<span class="sr-only"><?php esc_html_e( 'Toggle dark mode', 'aqualuxe' ); ?></span>
	</button>
	<?php
}

/**
 * Displays the social sharing buttons.
 */
function aqualuxe_social_sharing() {
	// Check if social sharing is enabled in the customizer.
	$social_sharing_enabled = get_theme_mod( 'aqualuxe_enable_social_sharing', true );
	
	// If social sharing is not enabled in the customizer, return early.
	if ( ! $social_sharing_enabled ) {
		return;
	}
	
	// Get the current post URL and title.
	$post_url = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );
	
	// Get the social networks to display.
	$social_networks = get_theme_mod( 'aqualuxe_social_sharing_networks', array( 'facebook', 'twitter', 'linkedin', 'pinterest' ) );
	
	// If no social networks are selected, return early.
	if ( empty( $social_networks ) ) {
		return;
	}
	
	// Display the social sharing buttons.
	?>
	<div class="social-sharing">
		<h3 class="social-sharing-title"><?php esc_html_e( 'Share this post', 'aqualuxe' ); ?></h3>
		<ul class="social-sharing-list flex flex-wrap gap-2">
			<?php if ( in_array( 'facebook', $social_networks, true ) ) : ?>
				<li class="social-sharing-item">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
						<span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			
			<?php if ( in_array( 'twitter', $social_networks, true ) ) : ?>
				<li class="social-sharing-item">
					<a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link twitter" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
						<span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			
			<?php if ( in_array( 'linkedin', $social_networks, true ) ) : ?>
				<li class="social-sharing-item">
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link linkedin" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
						<span class="sr-only"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			
			<?php if ( in_array( 'pinterest', $social_networks, true ) ) : ?>
				<?php
				// Get the featured image URL for Pinterest.
				$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
				$featured_image_url = $featured_image ? urlencode( $featured_image[0] ) : '';
				?>
				<li class="social-sharing-item">
					<a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $featured_image_url; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link pinterest" aria-label="<?php esc_attr_e( 'Share on Pinterest', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
						<span class="sr-only"><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			
			<?php if ( in_array( 'email', $social_networks, true ) ) : ?>
				<li class="social-sharing-item">
					<a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>" class="social-sharing-link email" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
						<span class="sr-only"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
}

/**
 * Get the theme instance.
 *
 * @return AquaLuxe\Core\Theme
 */
function aqualuxe_get_theme_instance() {
	global $aqualuxe_theme;
	
	if ( ! isset( $aqualuxe_theme ) ) {
		$aqualuxe_theme = new \AquaLuxe\Core\Theme();
		$aqualuxe_theme->initialize();
	}
	
	return $aqualuxe_theme;
}

/**
 * Get the dark mode class.
 *
 * @return string
 */
function aqualuxe_get_dark_mode_class() {
	// Get the Dark_Mode service instance.
	$theme = aqualuxe_get_theme_instance();
	$dark_mode = $theme->get_service( 'dark_mode' );
	
	// Get the dark mode class.
	return $dark_mode->get_dark_mode_class();
}