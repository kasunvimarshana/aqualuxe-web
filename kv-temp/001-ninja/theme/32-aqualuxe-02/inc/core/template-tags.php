<?php
/**
 * AquaLuxe Template Tags
 *
 * Custom template tags for this theme.
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
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

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
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

if ( ! function_exists( 'aqualuxe_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param array      $args    Arguments for the comment template.
	 * @param int        $depth   Depth of the current comment.
	 */
	function aqualuxe_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback':
			case 'trackback':
				?>
				<li class="post pingback">
					<p><?php esc_html_e( 'Pingback:', 'aqualuxe' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), ' ' ); ?></p>
				<?php
				break;
			default:
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class="comment-body">
						<footer class="comment-meta">
							<div class="comment-author vcard">
								<?php echo get_avatar( $comment, 60 ); ?>
								<?php
								printf(
									/* translators: 1: comment author, 2: date and time */
									esc_html__( '%1$s on %2$s', 'aqualuxe' ),
									sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
									sprintf(
										'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
										esc_url( get_comment_link( $comment->comment_ID ) ),
										get_comment_time( 'c' ),
										/* translators: 1: date, 2: time */
										sprintf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date(), get_comment_time() )
									)
								);
								?>
								<?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), ' ' ); ?>
							</div><!-- .comment-author .vcard -->

							<?php if ( '0' === $comment->comment_approved ) : ?>
								<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></em>
								<br />
							<?php endif; ?>
						</footer><!-- .comment-meta -->

						<div class="comment-content">
							<?php comment_text(); ?>
						</div><!-- .comment-content -->

						<div class="reply">
							<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
									)
								)
							);
							?>
						</div><!-- .reply -->
					</article><!-- #comment-## -->
				<?php
				break;
		endswitch;
	}
endif;

if ( ! function_exists( 'aqualuxe_get_svg' ) ) :
	/**
	 * Output an SVG icon.
	 *
	 * @param string $icon The icon name.
	 * @param array  $args Optional arguments.
	 */
	function aqualuxe_get_svg( $icon, $args = array() ) {
		// SVG icons directory.
		$icons_dir = AQUALUXE_DIR . '/assets/images/icons/';

		// Default arguments.
		$defaults = array(
			'class'   => '',
			'width'   => 24,
			'height'  => 24,
			'fill'    => 'currentColor',
			'aria-hidden' => 'true',
			'role'    => 'img',
		);

		// Parse arguments.
		$args = wp_parse_args( $args, $defaults );

		// SVG file path.
		$file = $icons_dir . $icon . '.svg';

		// Check if the SVG file exists.
		if ( ! file_exists( $file ) ) {
			return;
		}

		// Get the SVG file contents.
		$svg = file_get_contents( $file );

		// Add class to the SVG.
		if ( $args['class'] ) {
			$svg = str_replace( '<svg', '<svg class="' . esc_attr( $args['class'] ) . '"', $svg );
		}

		// Add width and height to the SVG.
		$svg = str_replace( '<svg', '<svg width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '"', $svg );

		// Add fill to the SVG.
		$svg = str_replace( '<svg', '<svg fill="' . esc_attr( $args['fill'] ) . '"', $svg );

		// Add accessibility attributes to the SVG.
		$svg = str_replace( '<svg', '<svg aria-hidden="' . esc_attr( $args['aria-hidden'] ) . '" role="' . esc_attr( $args['role'] ) . '"', $svg );

		// Output the SVG.
		echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'aqualuxe_get_icon_svg' ) ) :
	/**
	 * Get an SVG icon.
	 *
	 * @param string $icon The icon name.
	 * @param array  $args Optional arguments.
	 * @return string
	 */
	function aqualuxe_get_icon_svg( $icon, $args = array() ) {
		ob_start();
		aqualuxe_get_svg( $icon, $args );
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'aqualuxe_the_posts_navigation' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @param array $args Optional. See get_the_posts_navigation() for available arguments.
	 */
	function aqualuxe_the_posts_navigation( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prev_text'          => esc_html__( 'Older posts', 'aqualuxe' ),
				'next_text'          => esc_html__( 'Newer posts', 'aqualuxe' ),
				'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
			)
		);

		the_posts_navigation( $args );
	}
endif;

if ( ! function_exists( 'aqualuxe_the_post_navigation' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @param array $args Optional. See get_the_post_navigation() for available arguments.
	 */
	function aqualuxe_the_post_navigation( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prev_text'          => esc_html__( 'Previous post', 'aqualuxe' ),
				'next_text'          => esc_html__( 'Next post', 'aqualuxe' ),
				'screen_reader_text' => esc_html__( 'Post navigation', 'aqualuxe' ),
			)
		);

		the_post_navigation( $args );
	}
endif;

if ( ! function_exists( 'aqualuxe_numeric_pagination' ) ) :
	/**
	 * Display numeric pagination.
	 *
	 * @param array $args Optional. See paginate_links() for available arguments.
	 */
	function aqualuxe_numeric_pagination( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prev_text' => esc_html__( 'Previous', 'aqualuxe' ),
				'next_text' => esc_html__( 'Next', 'aqualuxe' ),
				'mid_size'  => 2,
				'type'      => 'list',
			)
		);

		$links = paginate_links( $args );

		if ( $links ) {
			echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">' . $links . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
	/**
	 * Display breadcrumbs.
	 *
	 * @param array $args Optional. Breadcrumbs arguments.
	 */
	function aqualuxe_breadcrumbs( $args = array() ) {
		// Check if breadcrumbs are enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
			return;
		}

		// Default arguments.
		$defaults = array(
			'container'     => 'nav',
			'container_id'  => 'breadcrumbs',
			'container_class' => 'breadcrumbs',
			'item_class'    => 'breadcrumb-item',
			'separator'     => '/',
			'home_text'     => esc_html__( 'Home', 'aqualuxe' ),
			'show_on_home'  => false,
			'show_current'  => true,
			'before_current' => '<span class="current">',
			'after_current' => '</span>',
		);

		// Parse arguments.
		$args = wp_parse_args( $args, $defaults );

		// Get the current page.
		global $post;
		$home_url = home_url( '/' );
		$home_text = $args['home_text'];
		$separator = '<span class="separator">' . $args['separator'] . '</span>';
		$breadcrumbs = array();

		// Home page.
		$breadcrumbs[] = array(
			'text' => $home_text,
			'url'  => $home_url,
		);

		// Front page.
		if ( is_front_page() ) {
			if ( ! $args['show_on_home'] ) {
				return;
			}
		} elseif ( is_home() ) {
			// Blog page.
			$breadcrumbs[] = array(
				'text' => esc_html__( 'Blog', 'aqualuxe' ),
				'url'  => get_permalink( get_option( 'page_for_posts' ) ),
			);
		} elseif ( is_singular( 'post' ) ) {
			// Single post.
			$categories = get_the_category();
			if ( $categories ) {
				$category = $categories[0];
				$breadcrumbs[] = array(
					'text' => $category->name,
					'url'  => get_category_link( $category->term_id ),
				);
			}
			$breadcrumbs[] = array(
				'text' => get_the_title(),
				'url'  => '',
			);
		} elseif ( is_singular( 'page' ) ) {
			// Single page.
			$ancestors = get_post_ancestors( $post );
			if ( $ancestors ) {
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $ancestor ) {
					$breadcrumbs[] = array(
						'text' => get_the_title( $ancestor ),
						'url'  => get_permalink( $ancestor ),
					);
				}
			}
			$breadcrumbs[] = array(
				'text' => get_the_title(),
				'url'  => '',
			);
		} elseif ( is_singular( 'attachment' ) ) {
			// Attachment.
			$parent = get_post( $post->post_parent );
			$breadcrumbs[] = array(
				'text' => $parent->post_title,
				'url'  => get_permalink( $parent->ID ),
			);
			$breadcrumbs[] = array(
				'text' => get_the_title(),
				'url'  => '',
			);
		} elseif ( is_singular() ) {
			// Custom post type.
			$post_type = get_post_type();
			$post_type_object = get_post_type_object( $post_type );
			$breadcrumbs[] = array(
				'text' => $post_type_object->labels->name,
				'url'  => get_post_type_archive_link( $post_type ),
			);
			$breadcrumbs[] = array(
				'text' => get_the_title(),
				'url'  => '',
			);
		} elseif ( is_category() ) {
			// Category archive.
			$breadcrumbs[] = array(
				'text' => single_cat_title( '', false ),
				'url'  => '',
			);
		} elseif ( is_tag() ) {
			// Tag archive.
			$breadcrumbs[] = array(
				'text' => single_tag_title( '', false ),
				'url'  => '',
			);
		} elseif ( is_author() ) {
			// Author archive.
			$breadcrumbs[] = array(
				'text' => get_the_author(),
				'url'  => '',
			);
		} elseif ( is_date() ) {
			// Date archive.
			if ( is_day() ) {
				$breadcrumbs[] = array(
					'text' => get_the_date(),
					'url'  => '',
				);
			} elseif ( is_month() ) {
				$breadcrumbs[] = array(
					'text' => get_the_date( 'F Y' ),
					'url'  => '',
				);
			} elseif ( is_year() ) {
				$breadcrumbs[] = array(
					'text' => get_the_date( 'Y' ),
					'url'  => '',
				);
			}
		} elseif ( is_post_type_archive() ) {
			// Custom post type archive.
			$breadcrumbs[] = array(
				'text' => post_type_archive_title( '', false ),
				'url'  => '',
			);
		} elseif ( is_tax() ) {
			// Custom taxonomy archive.
			$breadcrumbs[] = array(
				'text' => single_term_title( '', false ),
				'url'  => '',
			);
		} elseif ( is_search() ) {
			// Search results.
			$breadcrumbs[] = array(
				'text' => sprintf( esc_html__( 'Search results for: %s', 'aqualuxe' ), get_search_query() ),
				'url'  => '',
			);
		} elseif ( is_404() ) {
			// 404 page.
			$breadcrumbs[] = array(
				'text' => esc_html__( 'Page not found', 'aqualuxe' ),
				'url'  => '',
			);
		}

		// Output breadcrumbs.
		if ( $breadcrumbs ) {
			$output = '';

			// Open container.
			$output .= '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';

			// Add breadcrumbs.
			$count = count( $breadcrumbs );
			$i = 1;

			foreach ( $breadcrumbs as $breadcrumb ) {
				$output .= '<span class="' . esc_attr( $args['item_class'] ) . '">';

				if ( $i === $count && $args['show_current'] ) {
					$output .= $args['before_current'];
				}

				if ( $breadcrumb['url'] ) {
					$output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '">' . esc_html( $breadcrumb['text'] ) . '</a>';
				} else {
					$output .= esc_html( $breadcrumb['text'] );
				}

				if ( $i === $count && $args['show_current'] ) {
					$output .= $args['after_current'];
				}

				$output .= '</span>';

				if ( $i < $count ) {
					$output .= $separator;
				}

				$i++;
			}

			// Close container.
			$output .= '</' . esc_attr( $args['container'] ) . '>';

			// Filter output.
			$output = apply_filters( 'aqualuxe_breadcrumbs', $output );

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
endif;

if ( ! function_exists( 'aqualuxe_social_links' ) ) :
	/**
	 * Display social links.
	 *
	 * @param array $args Optional. Social links arguments.
	 */
	function aqualuxe_social_links( $args = array() ) {
		// Default arguments.
		$defaults = array(
			'container'     => 'div',
			'container_id'  => 'social-links',
			'container_class' => 'social-links',
			'item_class'    => 'social-link',
			'show_labels'   => false,
		);

		// Parse arguments.
		$args = wp_parse_args( $args, $defaults );

		// Get social links.
		$social_links = array(
			'facebook'  => array(
				'label' => esc_html__( 'Facebook', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_facebook_url', '' ),
				'icon'  => 'facebook',
			),
			'twitter'   => array(
				'label' => esc_html__( 'Twitter', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
				'icon'  => 'twitter',
			),
			'instagram' => array(
				'label' => esc_html__( 'Instagram', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_instagram_url', '' ),
				'icon'  => 'instagram',
			),
			'linkedin'  => array(
				'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
				'icon'  => 'linkedin',
			),
			'youtube'   => array(
				'label' => esc_html__( 'YouTube', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
				'icon'  => 'youtube',
			),
			'pinterest' => array(
				'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
				'url'   => get_theme_mod( 'aqualuxe_pinterest_url', '' ),
				'icon'  => 'pinterest',
			),
		);

		// Filter social links.
		$social_links = apply_filters( 'aqualuxe_social_links', $social_links );

		// Check if any social link is set.
		$has_links = false;
		foreach ( $social_links as $social_link ) {
			if ( $social_link['url'] ) {
				$has_links = true;
				break;
			}
		}

		if ( ! $has_links ) {
			return;
		}

		// Output social links.
		$output = '';

		// Open container.
		$output .= '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '">';

		// Add social links.
		foreach ( $social_links as $social_link ) {
			if ( $social_link['url'] ) {
				$output .= '<a href="' . esc_url( $social_link['url'] ) . '" class="' . esc_attr( $args['item_class'] ) . ' ' . esc_attr( $args['item_class'] ) . '-' . esc_attr( $social_link['icon'] ) . '" target="_blank" rel="noopener noreferrer">';
				$output .= aqualuxe_get_icon_svg( $social_link['icon'] );
				if ( $args['show_labels'] ) {
					$output .= '<span class="label">' . esc_html( $social_link['label'] ) . '</span>';
				} else {
					$output .= '<span class="screen-reader-text">' . esc_html( $social_link['label'] ) . '</span>';
				}
				$output .= '</a>';
			}
		}

		// Close container.
		$output .= '</' . esc_attr( $args['container'] ) . '>';

		// Filter output.
		$output = apply_filters( 'aqualuxe_social_links_output', $output );

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'aqualuxe_dark_mode_toggle' ) ) :
	/**
	 * Display dark mode toggle.
	 *
	 * @param array $args Optional. Dark mode toggle arguments.
	 */
	function aqualuxe_dark_mode_toggle( $args = array() ) {
		// Check if dark mode is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
			return;
		}

		// Default arguments.
		$defaults = array(
			'container'     => 'div',
			'container_id'  => 'dark-mode-toggle',
			'container_class' => 'dark-mode-toggle',
			'button_class'  => 'dark-mode-toggle-button',
			'icon_class'    => 'dark-mode-toggle-icon',
			'show_label'    => false,
			'label'         => esc_html__( 'Toggle Dark Mode', 'aqualuxe' ),
		);

		// Parse arguments.
		$args = wp_parse_args( $args, $defaults );

		// Output dark mode toggle.
		$output = '';

		// Open container.
		$output .= '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '">';

		// Add button.
		$output .= '<button type="button" class="' . esc_attr( $args['button_class'] ) . '" aria-pressed="false">';
		$output .= '<span class="' . esc_attr( $args['icon_class'] ) . '">';
		$output .= aqualuxe_get_icon_svg( 'sun' );
		$output .= aqualuxe_get_icon_svg( 'moon' );
		$output .= '</span>';

		if ( $args['show_label'] ) {
			$output .= '<span class="label">' . esc_html( $args['label'] ) . '</span>';
		} else {
			$output .= '<span class="screen-reader-text">' . esc_html( $args['label'] ) . '</span>';
		}

		$output .= '</button>';

		// Close container.
		$output .= '</' . esc_attr( $args['container'] ) . '>';

		// Filter output.
		$output = apply_filters( 'aqualuxe_dark_mode_toggle_output', $output );

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'aqualuxe_site_branding' ) ) :
	/**
	 * Display site branding.
	 *
	 * @param array $args Optional. Site branding arguments.
	 */
	function aqualuxe_site_branding( $args = array() ) {
		// Default arguments.
		$defaults = array(
			'container'     => 'div',
			'container_id'  => 'site-branding',
			'container_class' => 'site-branding',
			'show_title'    => true,
			'show_tagline'  => true,
			'show_logo'     => true,
		);

		// Parse arguments.
		$args = wp_parse_args( $args, $defaults );

		// Output site branding.
		$output = '';

		// Open container.
		$output .= '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '">';

		// Add logo.
		if ( $args['show_logo'] && has_custom_logo() ) {
			$output .= get_custom_logo();
		}

		// Add site title and tagline.
		if ( $args['show_title'] || $args['show_tagline'] ) {
			$output .= '<div class="site-title-wrapper">';

			if ( $args['show_title'] ) {
				if ( is_front_page() && is_home() ) {
					$output .= '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></h1>';
				} else {
					$output .= '<p class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></p>';
				}
			}

			if ( $args['show_tagline'] ) {
				$output .= '<p class="site-description">' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
			}

			$output .= '</div>';
		}

		// Close container.
		$output .= '</' . esc_attr( $args['container'] ) . '>';

		// Filter output.
		$output = apply_filters( 'aqualuxe_site_branding_output', $output );

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;