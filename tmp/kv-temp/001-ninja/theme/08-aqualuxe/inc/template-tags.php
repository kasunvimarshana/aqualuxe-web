<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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
			<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'featured-image' ) ); ?>
		</div><!-- .post-thumbnail -->

	<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'aqualuxe-card',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
					'class' => 'card-image',
				)
			);
			?>
		</a>

		<?php
	endif; // End is_singular().
}

/**
 * Displays post author information.
 */
function aqualuxe_author_info() {
	// Check if we're on a single post.
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	// Get author bio.
	$author_bio = get_the_author_meta( 'description' );

	// If there's no author bio, don't display the author box.
	if ( empty( $author_bio ) ) {
		return;
	}

	// Get author data.
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name' );
	$author_url = get_author_posts_url( $author_id );
	$author_avatar = get_avatar( $author_id, 96, '', $author_name, array( 'class' => 'author-avatar' ) );

	// Output the author box.
	?>
	<div class="author-info">
		<div class="author-avatar-wrapper">
			<?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="author-description">
			<h2 class="author-title">
				<?php
				/* translators: %s: Author name */
				printf( esc_html__( 'About %s', 'aqualuxe' ), esc_html( $author_name ) );
				?>
			</h2>
			<p class="author-bio">
				<?php echo wp_kses_post( $author_bio ); ?>
			</p>
			<a class="author-link" href="<?php echo esc_url( $author_url ); ?>" rel="author">
				<?php
				/* translators: %s: Author name */
				printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( $author_name ) );
				?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Displays the comment count.
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
 * Displays the post reading time.
 */
function aqualuxe_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed.

	if ( $reading_time < 1 ) {
		$reading_time = 1;
	}

	/* translators: %s: Reading time in minutes */
	$reading_time_text = sprintf( _n( '%s min read', '%s min read', $reading_time, 'aqualuxe' ), $reading_time );

	echo '<span class="reading-time">' . esc_html( $reading_time_text ) . '</span>';
}

/**
 * Displays the post views count.
 */
function aqualuxe_post_views() {
	$post_id = get_the_ID();
	$views = get_post_meta( $post_id, 'aqualuxe_post_views', true );

	if ( empty( $views ) ) {
		$views = 0;
	}

	/* translators: %s: Number of views */
	$views_text = sprintf( _n( '%s view', '%s views', $views, 'aqualuxe' ), number_format_i18n( $views ) );

	echo '<span class="post-views">' . esc_html( $views_text ) . '</span>';
}

/**
 * Displays the post share buttons.
 */
function aqualuxe_share_buttons() {
	// Check if share buttons are enabled.
	$share_buttons_enabled = get_theme_mod( 'aqualuxe_share_buttons_enable', true );

	if ( ! $share_buttons_enabled ) {
		return;
	}

	// Get the current post URL and title.
	$post_url = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );

	// Build the share URLs.
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
	$twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
	$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
	$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title;
	$email_url = 'mailto:?subject=' . $post_title . '&body=' . $post_url;

	// Output the share buttons.
	?>
	<div class="share-buttons">
		<span class="share-title"><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></span>
		<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-button facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
		</a>
		<a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-button twitter" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
		</a>
		<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-button linkedin" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
		</a>
		<a href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-button pinterest" aria-label="<?php esc_attr_e( 'Share on Pinterest', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M20 12h2"></path><path d="M2 12h2"></path><path d="M17.5 6.5l1.4-1.4"></path><path d="M5.1 17.9l1.4-1.4"></path><path d="M17.5 17.5l1.4 1.4"></path><path d="M5.1 6.1l1.4 1.4"></path></svg>
		</a>
		<a href="<?php echo esc_url( $email_url ); ?>" class="share-button email" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
		</a>
	</div>
	<?php
}

/**
 * Displays the post navigation.
 */
function aqualuxe_post_navigation() {
	the_post_navigation(
		array(
			'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
		)
	);
}

/**
 * Displays the post pagination.
 */
function aqualuxe_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 2,
			'prev_text' => sprintf(
				'<span class="nav-prev-text">%s</span>',
				esc_html__( 'Previous', 'aqualuxe' )
			),
			'next_text' => sprintf(
				'<span class="nav-next-text">%s</span>',
				esc_html__( 'Next', 'aqualuxe' )
			),
		)
	);
}

/**
 * Displays the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
	// Check if breadcrumbs are enabled.
	$breadcrumbs_enabled = get_theme_mod( 'aqualuxe_breadcrumbs_enable', true );

	if ( ! $breadcrumbs_enabled ) {
		return;
	}

	// Check if we're on the front page.
	if ( is_front_page() ) {
		return;
	}

	// Set up the breadcrumbs.
	$breadcrumbs = array();

	// Add the home link.
	$breadcrumbs[] = array(
		'title' => esc_html__( 'Home', 'aqualuxe' ),
		'url'   => home_url( '/' ),
	);

	// Add the appropriate breadcrumbs based on the page type.
	if ( is_home() ) {
		// Blog page.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Blog', 'aqualuxe' ),
			'url'   => '',
		);
	} elseif ( is_category() ) {
		// Category archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Category', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => single_cat_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_tag() ) {
		// Tag archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Tag', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => single_tag_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_author() ) {
		// Author archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Author', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_author(),
			'url'   => '',
		);
	} elseif ( is_year() ) {
		// Year archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Year', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date( 'Y' ),
			'url'   => '',
		);
	} elseif ( is_month() ) {
		// Month archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Month', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date( 'F Y' ),
			'url'   => '',
		);
	} elseif ( is_day() ) {
		// Day archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Day', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date(),
			'url'   => '',
		);
	} elseif ( is_tax() ) {
		// Custom taxonomy archive.
		$taxonomy = get_queried_object()->taxonomy;
		$term     = get_queried_object()->name;

		$breadcrumbs[] = array(
			'title' => $taxonomy,
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => $term,
			'url'   => '',
		);
	} elseif ( is_search() ) {
		// Search results.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Search', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_search_query(),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		// 404 page.
		$breadcrumbs[] = array(
			'title' => esc_html__( '404', 'aqualuxe' ),
			'url'   => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		// Single post.
		$categories = get_the_category();

		if ( ! empty( $categories ) ) {
			$category = $categories[0];

			$breadcrumbs[] = array(
				'title' => $category->name,
				'url'   => get_category_link( $category->term_id ),
			);
		}

		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_singular( 'page' ) ) {
		// Single page.
		$ancestors = get_post_ancestors( get_the_ID() );

		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );

			foreach ( $ancestors as $ancestor ) {
				$breadcrumbs[] = array(
					'title' => get_the_title( $ancestor ),
					'url'   => get_permalink( $ancestor ),
				);
			}
		}

		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_singular() ) {
		// Custom post type.
		$post_type = get_post_type_object( get_post_type() );

		$breadcrumbs[] = array(
			'title' => $post_type->labels->name,
			'url'   => get_post_type_archive_link( get_post_type() ),
		);

		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	}

	// Output the breadcrumbs.
	echo '<div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';

	foreach ( $breadcrumbs as $key => $breadcrumb ) {
		echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

		if ( ! empty( $breadcrumb['url'] ) ) {
			echo '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item">';
			echo '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
			echo '</a>';
		} else {
			echo '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
		}

		echo '<meta itemprop="position" content="' . esc_attr( $key + 1 ) . '" />';
		echo '</span>';

		if ( $key < count( $breadcrumbs ) - 1 ) {
			echo '<span class="breadcrumb-separator">/</span>';
		}
	}

	echo '</div>';
}

/**
 * Custom comment callback.
 *
 * @param object $comment Comment object.
 * @param array  $args    Comment arguments.
 * @param int    $depth   Comment depth.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					/* translators: %s: comment author link */
					printf(
						'<b class="fn">%s</b>',
						get_comment_author_link( $comment )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: comment date, 2: comment time */
							printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
						</time>
					</a>
					<?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			comment_reply_link(
				array_merge(
					$args,
					array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					)
				)
			);
			?>
		</article><!-- .comment-body -->
	<?php
}