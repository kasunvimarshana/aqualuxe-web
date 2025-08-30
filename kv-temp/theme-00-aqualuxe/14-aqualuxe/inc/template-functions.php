<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

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
 * Determines if post thumbnail can be displayed.
 */
function aqualuxe_can_show_post_thumbnail() {
	return apply_filters( 'aqualuxe_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function aqualuxe_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link for excerpt.
 */
function aqualuxe_continue_reading_link() {
	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'aqualuxe' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="screen-reader-text">' . __( ' "', 'aqualuxe' ), '"</span>', false )
		);

		return ' <a class="more-link" href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}
}

/**
 * Filter the excerpt more link.
 */
function aqualuxe_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return aqualuxe_continue_reading_link();
	}
	
	return $more;
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Filter the "read more" link for excerpts.
 */
function aqualuxe_content_more_link( $more_link, $more_link_text ) {
	if ( ! is_admin() ) {
		return aqualuxe_continue_reading_link();
	}
	
	return $more_link;
}
add_filter( 'the_content_more_link', 'aqualuxe_content_more_link', 10, 2 );

/**
 * Filter the category archive title.
 */
function aqualuxe_category_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	}
	
	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_category_archive_title' );

/**
 * Filter the tag archive title.
 */
function aqualuxe_tag_archive_title( $title ) {
	if ( is_tag() ) {
		$title = single_tag_title( '', false );
	}
	
	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_tag_archive_title' );

/**
 * Filter the author archive title.
 */
function aqualuxe_author_archive_title( $title ) {
	if ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	}
	
	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_author_archive_title' );

/**
 * Filter the search result title.
 */
function aqualuxe_search_result_title() {
	/* translators: %s: search term */
	return sprintf( __( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
}

/**
 * Filter the 404 page title.
 */
function aqualuxe_404_page_title() {
	return __( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' );
}

/**
 * Filter the comments title.
 */
function aqualuxe_comments_title() {
	$comments_number = get_comments_number();
	
	if ( '1' === $comments_number ) {
		/* translators: %s: post title */
		return sprintf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'aqualuxe' ), get_the_title() );
	} else {
		/* translators: 1: number of comments, 2: post title */
		return sprintf( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'aqualuxe' ), number_format_i18n( $comments_number ), get_the_title() );
	}
}

/**
 * Filter the post navigation text.
 */
function aqualuxe_post_navigation_text() {
	return array(
		'prev' => '<span aria-hidden="true">' . __( '&larr;', 'aqualuxe' ) . '</span> ' . __( 'Previous', 'aqualuxe' ),
		'next' => __( 'Next', 'aqualuxe' ) . ' <span aria-hidden="true">' . __( '&rarr;', 'aqualuxe' ) . '</span>',
	);
}

/**
 * Filter the posts navigation text.
 */
function aqualuxe_posts_navigation_text() {
	return array(
		'prev' => '<span aria-hidden="true">' . __( '&larr;', 'aqualuxe' ) . '</span> ' . __( 'Older posts', 'aqualuxe' ),
		'next' => __( 'Newer posts', 'aqualuxe' ) . ' <span aria-hidden="true">' . __( '&rarr;', 'aqualuxe' ) . '</span>',
	);
}

/**
 * Filter the comment form defaults.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
	$defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
	$defaults['title_reply_after']  = '</h3>';
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Filter the comment form fields.
 */
function aqualuxe_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	
	$fields['author'] = '<p class="comment-form-author">' . 
		'<label for="author">' . __( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
		
	$fields['email'] = '<p class="comment-form-email">' . 
		'<label for="email">' . __( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
		
	$fields['url'] = '<p class="comment-form-url">' . 
		'<label for="url">' . __( 'Website', 'aqualuxe' ) . '</label> ' .
		'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';
		
	return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_fields' );

/**
 * Filter the comment form textarea.
 */
function aqualuxe_comment_form_textarea( $field ) {
	$field = '<p class="comment-form-comment">' . 
		'<label for="comment">' . _x( 'Comment', 'noun', 'aqualuxe' ) . '</label> ' .
		'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
		
	return $field;
}
add_filter( 'comment_form_field_comment', 'aqualuxe_comment_form_textarea' );

/**
 * Filter the comment reply link.
 */
function aqualuxe_comment_reply_link( $link, $args, $comment, $post ) {
	$link = str_replace( 'comment-reply-link', 'comment-reply-link button', $link );
	
	return $link;
}
add_filter( 'comment_reply_link', 'aqualuxe_comment_reply_link', 10, 4 );

/**
 * Filter the cancel comment reply link.
 */
function aqualuxe_cancel_comment_reply_link( $formatted_link, $link, $text ) {
	$formatted_link = str_replace( 'id="cancel-comment-reply-link"', 'id="cancel-comment-reply-link" class="button"', $formatted_link );
	
	return $formatted_link;
}
add_filter( 'cancel_comment_reply_link', 'aqualuxe_cancel_comment_reply_link', 10, 3 );

/**
 * Filter the post classes.
 */
function aqualuxe_post_classes( $classes ) {
	$classes[] = 'entry';
	
	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Filter the widget classes.
 */
function aqualuxe_widget_classes( $classes ) {
	$classes[] = 'widget';
	
	return $classes;
}
add_filter( 'widget_class', 'aqualuxe_widget_classes' );

/**
 * Filter the nav menu classes.
 */
function aqualuxe_nav_menu_classes( $classes, $item, $args, $depth ) {
	$classes[] = 'menu-item';
	
	if ( $depth > 0 ) {
		$classes[] = 'sub-menu-item';
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_classes', 10, 4 );

/**
 * Filter the nav menu item ID.
 */
function aqualuxe_nav_menu_item_id( $menu_id, $item, $args, $depth ) {
	return 'menu-item-' . $item->ID;
}
add_filter( 'nav_menu_item_id', 'aqualuxe_nav_menu_item_id', 10, 4 );

/**
 * Filter the nav menu link attributes.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	$atts['class'] = 'menu-link';
	
	if ( $depth > 0 ) {
		$atts['class'] .= ' sub-menu-link';
	}
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );