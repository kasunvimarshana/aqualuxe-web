<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class if there is no sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Add a class if dark mode is enabled by default
	if ( aqualuxe_get_option( 'dark_mode_default', false ) ) {
		$classes[] = 'dark-mode';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a custom class to the navigation menu items
 *
 * @param array $classes Array of the CSS classes that are applied to the menu item's <li> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @return array Modified classes array.
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args ) {
	if ( 'primary' === $args->theme_location ) {
		$classes[] = 'nav-item';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3 );

/**
 * Add custom class to navigation menu links
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @return array Modified attributes array.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( 'primary' === $args->theme_location ) {
		$atts['class'] = 'nav-link';
		
		// Add active class if current
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$atts['class'] .= ' nav-link-active';
		}
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3 );

/**
 * Custom comment callback
 *
 * @param object $comment Comment object.
 * @param array $args Comment arguments.
 * @param int $depth Comment depth.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'mb-6 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0 last:mb-0', empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-meta mb-2">
				<div class="comment-author vcard flex items-center">
					<?php
					if ( 0 != $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full mr-3' ) );
					}
					?>
					<div>
						<?php printf( '<cite class="fn font-medium">%s</cite>', get_comment_author_link() ); ?>
						<div class="comment-metadata text-sm text-gray-600 dark:text-gray-400">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php
									/* translators: 1: Comment date, 2: Comment time */
									printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date( '', $comment ), get_comment_time() );
									?>
								</time>
							</a>
							<?php edit_comment_link( __( 'Edit', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
						</div>
					</div>
				</div><!-- .comment-author -->
			</div><!-- .comment-meta -->

			<div class="comment-content prose dark:prose-invert text-sm">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '0' == $comment->comment_approved ) :
				?>
				<p class="comment-awaiting-moderation text-sm text-yellow-600 dark:text-yellow-400 mt-2"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
				<?php
			endif;
			?>

			<div class="reply mt-3">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply-link text-sm">',
							'after'     => '</div>',
						)
					)
				);
				?>
			</div>
		</article><!-- .comment-body -->
	<?php
}

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Custom excerpt more
 *
 * @param string $more More string.
 * @return string Modified more string.
 */
function aqualuxe_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
	add_image_size( 'aqualuxe-featured', 1200, 600, true );
	add_image_size( 'aqualuxe-square', 600, 600, true );
	add_image_size( 'aqualuxe-portrait', 600, 900, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes.
 * @return array Modified image sizes.
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-square'   => __( 'Square', 'aqualuxe' ),
		'aqualuxe-portrait' => __( 'Portrait', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Modify archive title
 *
 * @param string $title Archive title.
 * @return string Modified archive title.
 */
function aqualuxe_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = get_the_author();
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_archive_title' );

/**
 * Add schema markup to the body element
 *
 * @param array $attr Body element attributes.
 * @return array Modified attributes.
 */
function aqualuxe_body_schema( $attr ) {
	$attr['itemscope'] = '';
	$attr['itemtype']  = 'http://schema.org/WebPage';

	if ( is_singular( 'post' ) ) {
		$attr['itemscope'] = '';
		$attr['itemtype']  = 'http://schema.org/Article';
	}

	if ( is_search() ) {
		$attr['itemscope'] = '';
		$attr['itemtype']  = 'http://schema.org/SearchResultsPage';
	}

	return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_schema' );

/**
 * Add responsive video support
 *
 * @param string $html Embed HTML.
 * @return string Modified embed HTML.
 */
function aqualuxe_responsive_embeds( $html ) {
	return '<div class="responsive-video-container">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'aqualuxe_responsive_embeds', 10, 3 );
add_filter( 'video_embed_html', 'aqualuxe_responsive_embeds' ); // Jetpack

/**
 * Add custom classes to next/previous post links
 *
 * @param string $output The next or previous post link.
 * @return string Modified link.
 */
function aqualuxe_adjacent_post_link_attributes( $output ) {
	return str_replace( '<a href=', '<a class="hover:text-primary-500 transition-colors duration-200" href=', $output );
}
add_filter( 'next_post_link', 'aqualuxe_adjacent_post_link_attributes' );
add_filter( 'previous_post_link', 'aqualuxe_adjacent_post_link_attributes' );

/**
 * Add custom classes to page links
 *
 * @param string $link Page link.
 * @return string Modified page link.
 */
function aqualuxe_wp_link_pages_link( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="page-numbers current">' . $link . '</span>';
	}
	return '<span class="page-numbers">' . $link . '</span>';
}
add_filter( 'wp_link_pages_link', 'aqualuxe_wp_link_pages_link' );

/**
 * Modify page links separator
 *
 * @param string $args Page links arguments.
 * @return array Modified arguments.
 */
function aqualuxe_wp_link_pages_args( $args ) {
	$args['before'] = '<div class="page-links flex flex-wrap items-center justify-center mt-6 pt-6 border-t border-gray-200 dark:border-gray-700"><span class="page-links-title font-medium mr-2">' . __( 'Pages:', 'aqualuxe' ) . '</span>';
	$args['separator'] = '';
	return $args;
}
add_filter( 'wp_link_pages_args', 'aqualuxe_wp_link_pages_args' );

/**
 * Add custom classes to the comment reply link
 *
 * @param string $link Comment reply link.
 * @return string Modified comment reply link.
 */
function aqualuxe_comment_reply_link( $link ) {
	return str_replace( 'comment-reply-link', 'comment-reply-link inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200', $link );
}
add_filter( 'comment_reply_link', 'aqualuxe_comment_reply_link' );

/**
 * Add custom classes to the edit comment link
 *
 * @param string $link Edit comment link.
 * @return string Modified edit comment link.
 */
function aqualuxe_edit_comment_link( $link ) {
	return str_replace( 'comment-edit-link', 'comment-edit-link text-gray-500 hover:text-primary-500 transition-colors duration-200 ml-2', $link );
}
add_filter( 'edit_comment_link', 'aqualuxe_edit_comment_link' );

/**
 * Add custom classes to the cancel comment reply link
 *
 * @param string $link Cancel comment reply link.
 * @return string Modified cancel comment reply link.
 */
function aqualuxe_cancel_comment_reply_link( $link ) {
	return str_replace( 'id="cancel-comment-reply-link"', 'id="cancel-comment-reply-link" class="text-red-500 hover:text-red-600 transition-colors duration-200 text-sm ml-2"', $link );
}
add_filter( 'cancel_comment_reply_link', 'aqualuxe_cancel_comment_reply_link' );

/**
 * Add custom attributes to the navigation menu
 *
 * @param array $atts Menu attributes.
 * @return array Modified menu attributes.
 */
function aqualuxe_nav_menu_attributes( $atts ) {
	$atts['class'] = 'site-navigation';
	$atts['itemscope'] = '';
	$atts['itemtype'] = 'http://schema.org/SiteNavigationElement';
	
	return $atts;
}
add_filter( 'wp_nav_menu_container_allowedtags', 'aqualuxe_nav_menu_attributes' );

/**
 * Add custom classes to the gallery
 *
 * @param string $gallery Gallery HTML.
 * @param array $attr Gallery attributes.
 * @return string Modified gallery HTML.
 */
function aqualuxe_gallery_style( $gallery, $attr ) {
	return str_replace( 'gallery ', 'gallery grid grid-cols-2 md:grid-cols-3 gap-4 ', $gallery );
}
add_filter( 'post_gallery', 'aqualuxe_gallery_style', 10, 2 );

/**
 * Add custom classes to the search form
 *
 * @param string $form Search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form( $form ) {
	$form = str_replace( 'search-form', 'search-form relative', $form );
	$form = str_replace( 'search-submit', 'search-submit absolute right-0 top-0 h-full px-3 flex items-center justify-center text-gray-500 hover:text-primary-500 transition-colors duration-200', $form );
	$form = str_replace( 'search-field', 'search-field form-input w-full pl-4 pr-10 py-2', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Add custom classes to the password form
 *
 * @param string $output Password form HTML.
 * @return string Modified password form HTML.
 */
function aqualuxe_password_form( $output ) {
	$output = str_replace( 'post-password-form', 'post-password-form space-y-4', $output );
	$output = str_replace( '<p>', '<div class="form-group">', $output );
	$output = str_replace( '</p>', '</div>', $output );
	$output = str_replace( 'name="post_password"', 'name="post_password" class="form-input mr-2"', $output );
	$output = str_replace( 'type="submit"', 'type="submit" class="btn-primary"', $output );
	
	return $output;
}
add_filter( 'the_password_form', 'aqualuxe_password_form' );

/**
 * Add custom classes to the tag cloud widget
 *
 * @param array $args Tag cloud arguments.
 * @return array Modified tag cloud arguments.
 */
function aqualuxe_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';
	
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'aqualuxe_widget_tag_cloud_args' );

/**
 * Add custom classes to the tag cloud links
 *
 * @param string $return Tag cloud HTML.
 * @return string Modified tag cloud HTML.
 */
function aqualuxe_tag_cloud_filter( $return ) {
	return str_replace( '<a', '<a class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full text-sm transition-colors duration-200 mb-2 mr-2"', $return );
}
add_filter( 'wp_tag_cloud', 'aqualuxe_tag_cloud_filter' );

/**
 * Add custom classes to the calendar widget
 *
 * @param string $calendar Calendar HTML.
 * @return string Modified calendar HTML.
 */
function aqualuxe_calendar_filter( $calendar ) {
	$calendar = str_replace( 'id="wp-calendar"', 'id="wp-calendar" class="w-full text-center border-collapse"', $calendar );
	$calendar = str_replace( '<caption>', '<caption class="mb-2 font-medium">', $calendar );
	$calendar = str_replace( '<thead>', '<thead class="bg-gray-100 dark:bg-gray-700">', $calendar );
	$calendar = str_replace( '<td>', '<td class="py-1">', $calendar );
	$calendar = str_replace( '<td id="today">', '<td id="today" class="bg-primary-100 dark:bg-primary-900 font-medium">', $calendar );
	
	return $calendar;
}
add_filter( 'get_calendar', 'aqualuxe_calendar_filter' );

/**
 * Add custom classes to the categories widget
 *
 * @param array $args Categories widget arguments.
 * @return array Modified categories widget arguments.
 */
function aqualuxe_widget_categories_args( $args ) {
	$args['use_desc_for_title'] = false;
	
	return $args;
}
add_filter( 'widget_categories_args', 'aqualuxe_widget_categories_args' );

/**
 * Add custom classes to the categories widget links
 *
 * @param string $output Categories widget HTML.
 * @return string Modified categories widget HTML.
 */
function aqualuxe_list_categories( $output ) {
	$output = str_replace( 'cat-item', 'cat-item mb-2', $output );
	$output = str_replace( '</a> (', '</a> <span class="text-gray-500 dark:text-gray-400">(', $output );
	$output = str_replace( ')', ')</span>', $output );
	
	return $output;
}
add_filter( 'wp_list_categories', 'aqualuxe_list_categories' );

/**
 * Add custom classes to the archives widget
 *
 * @param string $links Archives widget HTML.
 * @return string Modified archives widget HTML.
 */
function aqualuxe_get_archives_link( $links ) {
	$links = str_replace( '</a>&nbsp;(', '</a> <span class="text-gray-500 dark:text-gray-400">(', $links );
	$links = str_replace( ')', ')</span>', $links );
	
	return $links;
}
add_filter( 'get_archives_link', 'aqualuxe_get_archives_link' );

/**
 * Add custom classes to the widget title
 *
 * @param string $title Widget title.
 * @return string Modified widget title.
 */
function aqualuxe_widget_title( $title ) {
	return $title ? '<h3 class="widget-title text-xl font-serif font-bold mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">' . $title . '</h3>' : $title;
}
add_filter( 'widget_title', 'aqualuxe_widget_title' );

/**
 * Add custom classes to the menu widget
 *
 * @param array $args Menu widget arguments.
 * @return array Modified menu widget arguments.
 */
function aqualuxe_widget_nav_menu_args( $args ) {
	$args['menu_class'] = 'menu list-none p-0 m-0 space-y-2';
	
	return $args;
}
add_filter( 'widget_nav_menu_args', 'aqualuxe_widget_nav_menu_args' );

/**
 * Add custom classes to the recent posts widget
 *
 * @param array $args Recent posts widget arguments.
 * @return array Modified recent posts widget arguments.
 */
function aqualuxe_widget_recent_entries_args( $args ) {
	$args['after_widget'] = str_replace( 'widget_recent_entries', 'widget_recent_entries list-none p-0 m-0 space-y-4', $args['after_widget'] );
	
	return $args;
}
add_filter( 'widget_recent_entries_args', 'aqualuxe_widget_recent_entries_args' );

/**
 * Add custom classes to the recent comments widget
 *
 * @param array $args Recent comments widget arguments.
 * @return array Modified recent comments widget arguments.
 */
function aqualuxe_widget_recent_comments_args( $args ) {
	$args['after_widget'] = str_replace( 'widget_recent_comments', 'widget_recent_comments list-none p-0 m-0 space-y-4', $args['after_widget'] );
	
	return $args;
}
add_filter( 'widget_recent_comments_args', 'aqualuxe_widget_recent_comments_args' );

/**
 * Add custom classes to the recent comments widget items
 *
 * @param string $output Recent comments widget HTML.
 * @return string Modified recent comments widget HTML.
 */
function aqualuxe_recent_comments_style( $output ) {
	$output = str_replace( 'recentcomments', 'recentcomments mb-2', $output );
	
	return $output;
}
add_filter( 'wp_list_comments', 'aqualuxe_recent_comments_style' );

/**
 * Add custom classes to the RSS widget
 *
 * @param array $args RSS widget arguments.
 * @return array Modified RSS widget arguments.
 */
function aqualuxe_widget_rss_args( $args ) {
	$args['after_widget'] = str_replace( 'widget_rss', 'widget_rss list-none p-0 m-0 space-y-4', $args['after_widget'] );
	
	return $args;
}
add_filter( 'widget_rss_args', 'aqualuxe_widget_rss_args' );

/**
 * Add custom classes to the meta widget
 *
 * @param array $args Meta widget arguments.
 * @return array Modified meta widget arguments.
 */
function aqualuxe_widget_meta_args( $args ) {
	$args['after_widget'] = str_replace( 'widget_meta', 'widget_meta list-none p-0 m-0 space-y-2', $args['after_widget'] );
	
	return $args;
}
add_filter( 'widget_meta_args', 'aqualuxe_widget_meta_args' );

/**
 * Add custom classes to the pages widget
 *
 * @param array $args Pages widget arguments.
 * @return array Modified pages widget arguments.
 */
function aqualuxe_widget_pages_args( $args ) {
	$args['after_widget'] = str_replace( 'widget_pages', 'widget_pages list-none p-0 m-0 space-y-2', $args['after_widget'] );
	
	return $args;
}
add_filter( 'widget_pages_args', 'aqualuxe_widget_pages_args' );

/**
 * Add custom classes to the text widget
 *
 * @param array $default_widgets Default widgets.
 * @return array Modified default widgets.
 */
function aqualuxe_widget_text_args( $default_widgets ) {
	$default_widgets['WP_Widget_Text']['classname'] = 'widget_text prose dark:prose-invert';
	
	return $default_widgets;
}
add_filter( 'widget_defaults', 'aqualuxe_widget_text_args' );