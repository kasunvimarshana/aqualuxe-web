<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function aqualuxe_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
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

		echo '<span class="posted-on flex items-center mr-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function aqualuxe_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline flex items-center mr-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
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
				printf( '<div class="cat-links mb-4"><span class="text-sm font-medium text-gray-600 dark:text-gray-400 mr-2">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span> %1$s</div>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<div class="tags-links mb-4"><span class="text-sm font-medium text-gray-600 dark:text-gray-400 mr-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span> %1$s</div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			'<span class="edit-link flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors duration-200 mt-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
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

			<div class="post-thumbnail mb-8">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail block mb-4 overflow-hidden rounded-lg" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'aqualuxe-featured',
						array(
							'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300',
							'alt' => the_title_attribute(
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
endif;

if ( ! function_exists( 'aqualuxe_comment_count' ) ) :
	/**
	 * Displays the comment count with icon.
	 */
	function aqualuxe_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link flex items-center">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>';
			comments_popup_link(
				__( 'Leave a comment', 'aqualuxe' ),
				__( '1 Comment', 'aqualuxe' ),
				__( '% Comments', 'aqualuxe' )
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_categories_list' ) ) :
	/**
	 * Displays the categories list with custom HTML.
	 */
	function aqualuxe_categories_list() {
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				echo '<div class="cat-links flex items-center flex-wrap">';
				echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>';
				echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_tags_list' ) ) :
	/**
	 * Displays the tags list with custom HTML.
	 */
	function aqualuxe_tags_list() {
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '<div class="flex flex-wrap gap-2 mt-4">', '', '</div>' );
			if ( $tags_list ) {
				echo '<div class="tags-links">';
				echo '<span class="text-sm font-medium text-gray-600 dark:text-gray-400 block mb-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
				echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_post_meta' ) ) :
	/**
	 * Displays the post meta (categories, tags, etc.).
	 */
	function aqualuxe_post_meta() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-4">';
			aqualuxe_posted_by();
			aqualuxe_posted_on();
			aqualuxe_comment_count();
			echo '</div>';
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
	/**
	 * Displays related posts.
	 */
	function aqualuxe_related_posts() {
		$post_id = get_the_ID();
		$cat_ids = array();
		$categories = get_the_category( $post_id );

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$cat_ids[] = $category->term_id;
			}
		}

		$args = array(
			'category__in'      => $cat_ids,
			'post__not_in'      => array( $post_id ),
			'posts_per_page'    => 3,
			'ignore_sticky_posts' => 1,
		);

		$related_posts = new WP_Query( $args );

		if ( $related_posts->have_posts() ) :
			?>
			<div class="related-posts mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
				<h3 class="text-2xl font-serif font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
				
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<?php
					while ( $related_posts->have_posts() ) :
						$related_posts->the_post();
						?>
						<div class="related-post card overflow-hidden">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
									<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
								</a>
							<?php endif; ?>
							
							<div class="p-4">
								<h4 class="text-lg font-bold mb-2">
									<a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
										<?php the_title(); ?>
									</a>
								</h4>
								
								<div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
									<?php echo get_the_date(); ?>
								</div>
								
								<div class="text-sm line-clamp-3">
									<?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		endif;
	}
endif;

if ( ! function_exists( 'aqualuxe_post_navigation' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function aqualuxe_post_navigation() {
		$prev_post = get_previous_post();
		$next_post = get_next_post();

		if ( ! $prev_post && ! $next_post ) {
			return;
		}
		?>
		<nav class="post-navigation flex flex-col md:flex-row justify-between py-6 my-8 border-t border-b border-gray-200 dark:border-gray-700">
			<div class="post-navigation-prev mb-4 md:mb-0 md:w-1/2 md:pr-4">
				<?php if ( $prev_post ) : ?>
					<span class="nav-subtitle text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Previous:', 'aqualuxe' ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="nav-title text-lg font-bold hover:text-primary-500 transition-colors duration-200">
						<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
					</a>
				<?php endif; ?>
			</div>
			
			<div class="post-navigation-next md:w-1/2 md:pl-4 md:text-right">
				<?php if ( $next_post ) : ?>
					<span class="nav-subtitle text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Next:', 'aqualuxe' ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="nav-title text-lg font-bold hover:text-primary-500 transition-colors duration-200">
						<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</nav>
		<?php
	}
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
	/**
	 * Display pagination for archive pages.
	 */
	function aqualuxe_pagination() {
		the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg> ' . esc_html__( 'Previous', 'aqualuxe' ),
			'next_text' => esc_html__( 'Next', 'aqualuxe' ) . ' <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
			'class'     => 'pagination flex justify-center mt-8',
		) );
	}
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
	/**
	 * Display breadcrumbs.
	 */
	function aqualuxe_breadcrumbs() {
		if ( ! aqualuxe_get_option( 'show_breadcrumbs', true ) ) {
			return;
		}

		// Don't display on the homepage
		if ( is_front_page() ) {
			return;
		}

		// Define
		$separator = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';

		// Start the breadcrumb
		echo '<nav class="breadcrumbs py-3 text-sm text-gray-600 dark:text-gray-400" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
		echo '<ol class="flex flex-wrap items-center">';

		// Home page
		echo '<li class="breadcrumb-item flex items-center">';
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-primary-500 transition-colors duration-200">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
		echo '<span class="sr-only">' . esc_html__( 'Home', 'aqualuxe' ) . '</span>';
		echo '</a>';
		echo $separator;
		echo '</li>';

		if ( is_category() || is_single() ) {
			// Category
			if ( is_category() ) {
				echo '<li class="breadcrumb-item">' . single_cat_title( '', false ) . '</li>';
			}

			// Single post
			if ( is_single() ) {
				// Get categories
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					echo '<li class="breadcrumb-item flex items-center">';
					echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="hover:text-primary-500 transition-colors duration-200">' . esc_html( $categories[0]->name ) . '</a>';
					echo $separator;
					echo '</li>';
				}

				// Post title
				echo '<li class="breadcrumb-item">' . get_the_title() . '</li>';
			}
		} elseif ( is_page() ) {
			// Check if the page has a parent
			if ( $post->post_parent ) {
				$ancestors = get_post_ancestors( $post->ID );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor ) {
					echo '<li class="breadcrumb-item flex items-center">';
					echo '<a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="hover:text-primary-500 transition-colors duration-200">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
					echo $separator;
					echo '</li>';
				}
			}

			// Current page
			echo '<li class="breadcrumb-item">' . get_the_title() . '</li>';
		} elseif ( is_tag() ) {
			// Tag Archive
			echo '<li class="breadcrumb-item">' . esc_html__( 'Posts Tagged ', 'aqualuxe' ) . '"' . single_tag_title( '', false ) . '"</li>';
		} elseif ( is_author() ) {
			// Author Archive
			echo '<li class="breadcrumb-item">' . esc_html__( 'Author Archive', 'aqualuxe' ) . ': ' . get_the_author() . '</li>';
		} elseif ( is_day() ) {
			// Day Archive
			echo '<li class="breadcrumb-item">' . esc_html__( 'Daily Archives', 'aqualuxe' ) . ': ' . get_the_date() . '</li>';
		} elseif ( is_month() ) {
			// Month Archive
			echo '<li class="breadcrumb-item">' . esc_html__( 'Monthly Archives', 'aqualuxe' ) . ': ' . get_the_date( 'F Y' ) . '</li>';
		} elseif ( is_year() ) {
			// Year Archive
			echo '<li class="breadcrumb-item">' . esc_html__( 'Yearly Archives', 'aqualuxe' ) . ': ' . get_the_date( 'Y' ) . '</li>';
		} elseif ( is_search() ) {
			// Search Results
			echo '<li class="breadcrumb-item">' . esc_html__( 'Search Results for', 'aqualuxe' ) . ': ' . get_search_query() . '</li>';
		} elseif ( is_404() ) {
			// 404 Page
			echo '<li class="breadcrumb-item">' . esc_html__( 'Error 404', 'aqualuxe' ) . '</li>';
		} elseif ( is_archive() ) {
			// Other Archives
			echo '<li class="breadcrumb-item">' . get_the_archive_title() . '</li>';
		}

		echo '</ol>';
		echo '</nav>';
	}
endif;

if ( ! function_exists( 'aqualuxe_social_sharing' ) ) :
	/**
	 * Display social sharing buttons.
	 */
	function aqualuxe_social_sharing() {
		if ( ! aqualuxe_get_option( 'show_social_sharing', true ) ) {
			return;
		}

		// Only show on single posts and pages
		if ( ! is_singular( array( 'post', 'page' ) ) ) {
			return;
		}

		// Get current page URL
		$url = urlencode( get_permalink() );
		
		// Get current page title
		$title = urlencode( get_the_title() );
		
		// Get current page description
		$description = urlencode( get_the_excerpt() );
		
		// Construct sharing URLs
		$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
		$twitter_url = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
		$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&summary=' . $description;
		$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $description;
		$email_url = 'mailto:?subject=' . $title . '&body=' . $url;
		
		// Output sharing buttons
		?>
		<div class="social-sharing mt-8">
			<h3 class="text-lg font-medium mb-4"><?php esc_html_e( 'Share This', 'aqualuxe' ); ?></h3>
			
			<div class="flex space-x-2">
				<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="social-share-link facebook flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200">
					<span class="sr-only"><?php esc_html_e( 'Share on Facebook', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
					</svg>
				</a>
				
				<a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" class="social-share-link twitter flex items-center justify-center w-10 h-10 rounded-full bg-blue-400 text-white hover:bg-blue-500 transition-colors duration-200">
					<span class="sr-only"><?php esc_html_e( 'Share on Twitter', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
					</svg>
				</a>
				
				<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="social-share-link linkedin flex items-center justify-center w-10 h-10 rounded-full bg-blue-700 text-white hover:bg-blue-800 transition-colors duration-200">
					<span class="sr-only"><?php esc_html_e( 'Share on LinkedIn', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
					</svg>
				</a>
				
				<a href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank" rel="noopener noreferrer" class="social-share-link pinterest flex items-center justify-center w-10 h-10 rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors duration-200">
					<span class="sr-only"><?php esc_html_e( 'Share on Pinterest', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
					</svg>
				</a>
				
				<a href="<?php echo esc_url( $email_url ); ?>" target="_blank" rel="noopener noreferrer" class="social-share-link email flex items-center justify-center w-10 h-10 rounded-full bg-gray-600 text-white hover:bg-gray-700 transition-colors duration-200">
					<span class="sr-only"><?php esc_html_e( 'Share via Email', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
					</svg>
				</a>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'aqualuxe_reading_time' ) ) :
	/**
	 * Calculate and display estimated reading time.
	 */
	function aqualuxe_reading_time() {
		$content = get_post_field( 'post_content', get_the_ID() );
		$word_count = str_word_count( strip_tags( $content ) );
		$reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
		
		if ( $reading_time < 1 ) {
			$reading_time = 1;
		}
		
		printf(
			'<span class="reading-time flex items-center mr-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>%s</span>',
			sprintf(
				/* translators: %s: Reading time in minutes */
				_n( '%s min read', '%s min read', $reading_time, 'aqualuxe' ),
				number_format_i18n( $reading_time )
			)
		);
	}
endif;