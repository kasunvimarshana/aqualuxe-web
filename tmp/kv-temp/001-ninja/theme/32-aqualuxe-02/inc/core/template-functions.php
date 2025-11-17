<?php
/**
 * AquaLuxe Template Functions
 *
 * Functions that enhance the theme by hooking into WordPress.
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 * 
 * Note: This is a core template function that replaces the one in functions.php
 */
function aqualuxe_core_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_core_pingback_header' );

/**
 * Adds custom classes to the array of body classes.
 *
 * This is an enhanced version of the function in functions.php
 * 
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_core_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Add a class if dark mode is enabled by default.
	if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
		$classes[] = 'dark-mode';
	}

	// Add a class for the layout.
	$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
	$classes[] = 'layout-' . $layout;

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_core_body_classes' );

/**
 * Add a "Read More" link to excerpts.
 *
 * @param string $more The current more text.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}

	return sprintf(
		'&hellip; <p class="more-link-container"><a href="%1$s" class="more-link">%2$s <span class="screen-reader-text">%3$s</span></a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		esc_html__( 'Continue reading', 'aqualuxe' ),
		esc_html( get_the_title( get_the_ID() ) )
	);
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Filter the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}

	return get_theme_mod( 'aqualuxe_excerpt_length', 55 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Add schema markup to the authors post link.
 *
 * @param string $link The author post link.
 * @return string
 */
function aqualuxe_schema_url( $link ) {
	return str_replace( 'rel="author"', 'rel="author" itemprop="url"', $link );
}
add_filter( 'the_author_posts_link', 'aqualuxe_schema_url' );

/**
 * Add schema markup to the comment author link.
 *
 * @param string $link The comment author link.
 * @return string
 */
function aqualuxe_schema_comment_author_url( $link ) {
	return str_replace( '<a', '<a itemprop="url"', $link );
}
add_filter( 'get_comment_author_link', 'aqualuxe_schema_comment_author_url' );

/**
 * Add schema markup to the comment author URL link.
 *
 * @param string $link The comment author URL link.
 * @return string
 */
function aqualuxe_schema_comment_author_url_link( $link ) {
	return str_replace( '<a', '<a itemprop="url"', $link );
}
add_filter( 'get_comment_author_url_link', 'aqualuxe_schema_comment_author_url_link' );

/**
 * Add schema markup to the comment reply link.
 *
 * @param string $link The comment reply link.
 * @return string
 */
function aqualuxe_schema_comment_reply_link( $link ) {
	return str_replace( '<a', '<a itemprop="replyToUrl"', $link );
}
add_filter( 'comment_reply_link', 'aqualuxe_schema_comment_reply_link' );

/**
 * Add schema markup to the category links.
 *
 * @param string $output The category links.
 * @return string
 */
function aqualuxe_schema_category_links( $output ) {
	return str_replace( '<a', '<a itemprop="url"', $output );
}
add_filter( 'the_category', 'aqualuxe_schema_category_links' );

/**
 * Add schema markup to the tag links.
 *
 * @param string $output The tag links.
 * @return string
 */
function aqualuxe_schema_tag_links( $output ) {
	return str_replace( '<a', '<a itemprop="url"', $output );
}
add_filter( 'the_tags', 'aqualuxe_schema_tag_links' );

/**
 * Add schema markup to the edit post link.
 *
 * @param string $link The edit post link.
 * @return string
 */
function aqualuxe_schema_edit_post_link( $link ) {
	return str_replace( '<a', '<a itemprop="url"', $link );
}
add_filter( 'edit_post_link', 'aqualuxe_schema_edit_post_link' );

/**
 * Add schema markup to the post thumbnail.
 *
 * @param string $html The post thumbnail HTML.
 * @return string
 */
function aqualuxe_schema_post_thumbnail( $html ) {
	return str_replace( '<img', '<img itemprop="image"', $html );
}
add_filter( 'post_thumbnail_html', 'aqualuxe_schema_post_thumbnail' );

/**
 * Add schema markup to the avatar.
 *
 * @param string $avatar The avatar HTML.
 * @return string
 */
function aqualuxe_schema_avatar( $avatar ) {
	return str_replace( '<img', '<img itemprop="image"', $avatar );
}
add_filter( 'get_avatar', 'aqualuxe_schema_avatar' );

/**
 * Add schema markup to the comment text.
 *
 * @param string $comment_text The comment text.
 * @return string
 */
function aqualuxe_schema_comment_text( $comment_text ) {
	return '<div itemprop="text">' . $comment_text . '</div>';
}
add_filter( 'comment_text', 'aqualuxe_schema_comment_text' );

/**
 * Add schema markup to the comment date.
 *
 * @param string $date The comment date.
 * @return string
 */
function aqualuxe_schema_comment_date( $date ) {
	return '<time itemprop="datePublished" datetime="' . get_comment_date( 'c' ) . '">' . $date . '</time>';
}
add_filter( 'get_comment_date', 'aqualuxe_schema_comment_date' );

/**
 * Add schema markup to the comment time.
 *
 * @param string $time The comment time.
 * @return string
 */
function aqualuxe_schema_comment_time( $time ) {
	return '<time itemprop="datePublished" datetime="' . get_comment_date( 'c' ) . '">' . $time . '</time>';
}
add_filter( 'get_comment_time', 'aqualuxe_schema_comment_time' );

/**
 * Add schema markup to the post date.
 *
 * @param string $date The post date.
 * @return string
 */
function aqualuxe_schema_post_date( $date ) {
	return '<time itemprop="datePublished" datetime="' . get_the_date( 'c' ) . '">' . $date . '</time>';
}
add_filter( 'get_the_date', 'aqualuxe_schema_post_date' );

/**
 * Add schema markup to the post modified date.
 *
 * @param string $date The post modified date.
 * @return string
 */
function aqualuxe_schema_post_modified_date( $date ) {
	return '<time itemprop="dateModified" datetime="' . get_the_modified_date( 'c' ) . '">' . $date . '</time>';
}
add_filter( 'get_the_modified_date', 'aqualuxe_schema_post_modified_date' );

/**
 * Add schema markup to the post time.
 *
 * @param string $time The post time.
 * @return string
 */
function aqualuxe_schema_post_time( $time ) {
	return '<time itemprop="datePublished" datetime="' . get_the_date( 'c' ) . '">' . $time . '</time>';
}
add_filter( 'get_the_time', 'aqualuxe_schema_post_time' );

/**
 * Add schema markup to the post title.
 *
 * @param string $title The post title.
 * @return string
 */
function aqualuxe_schema_post_title( $title ) {
	if ( is_singular() ) {
		return '<h1 class="entry-title" itemprop="headline">' . $title . '</h1>';
	} else {
		return '<h2 class="entry-title" itemprop="headline"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $title . '</a></h2>';
	}
}
add_filter( 'the_title', 'aqualuxe_schema_post_title', 10, 2 );

/**
 * Add schema markup to the post content.
 *
 * @param string $content The post content.
 * @return string
 */
function aqualuxe_schema_post_content( $content ) {
	if ( is_singular() ) {
		return '<div itemprop="articleBody">' . $content . '</div>';
	}
	return $content;
}
add_filter( 'the_content', 'aqualuxe_schema_post_content' );

/**
 * Add schema markup to the post excerpt.
 *
 * @param string $excerpt The post excerpt.
 * @return string
 */
function aqualuxe_schema_post_excerpt( $excerpt ) {
	return '<div itemprop="description">' . $excerpt . '</div>';
}
add_filter( 'the_excerpt', 'aqualuxe_schema_post_excerpt' );

/**
 * Add schema markup to the comment author.
 *
 * @param string $author The comment author.
 * @return string
 */
function aqualuxe_schema_comment_author( $author ) {
	return '<span itemprop="name">' . $author . '</span>';
}
add_filter( 'get_comment_author', 'aqualuxe_schema_comment_author' );

/**
 * Add schema markup to the author name.
 *
 * @param string $author The author name.
 * @return string
 */
function aqualuxe_schema_author_name( $author ) {
	return '<span itemprop="name">' . $author . '</span>';
}
add_filter( 'the_author', 'aqualuxe_schema_author_name' );

/**
 * Add schema markup to the author posts link.
 *
 * @param string $link The author posts link.
 * @return string
 */
function aqualuxe_schema_author_posts_link( $link ) {
	return str_replace( 'rel="author"', 'rel="author" itemprop="url"', $link );
}
add_filter( 'the_author_posts_link', 'aqualuxe_schema_author_posts_link' );

/**
 * Add schema markup to the author description.
 *
 * @param string $description The author description.
 * @return string
 */
function aqualuxe_schema_author_description( $description ) {
	return '<span itemprop="description">' . $description . '</span>';
}
add_filter( 'get_the_author_description', 'aqualuxe_schema_author_description' );

/**
 * Add schema markup to the post navigation.
 *
 * @param string $navigation The post navigation.
 * @return string
 */
function aqualuxe_schema_post_navigation( $navigation ) {
	return str_replace( '<a', '<a itemprop="url"', $navigation );
}
add_filter( 'the_post_navigation', 'aqualuxe_schema_post_navigation' );

/**
 * Add schema markup to the posts navigation.
 *
 * @param string $navigation The posts navigation.
 * @return string
 */
function aqualuxe_schema_posts_navigation( $navigation ) {
	return str_replace( '<a', '<a itemprop="url"', $navigation );
}
add_filter( 'the_posts_navigation', 'aqualuxe_schema_posts_navigation' );

/**
 * Add schema markup to the posts pagination.
 *
 * @param string $pagination The posts pagination.
 * @return string
 */
function aqualuxe_schema_posts_pagination( $pagination ) {
	return str_replace( '<a', '<a itemprop="url"', $pagination );
}
add_filter( 'the_posts_pagination', 'aqualuxe_schema_posts_pagination' );

/**
 * Add schema markup to the comments pagination.
 *
 * @param string $pagination The comments pagination.
 * @return string
 */
function aqualuxe_schema_comments_pagination( $pagination ) {
	return str_replace( '<a', '<a itemprop="url"', $pagination );
}
add_filter( 'the_comments_pagination', 'aqualuxe_schema_comments_pagination' );

/**
 * Add schema markup to the comments navigation.
 *
 * @param string $navigation The comments navigation.
 * @return string
 */
function aqualuxe_schema_comments_navigation( $navigation ) {
	return str_replace( '<a', '<a itemprop="url"', $navigation );
}
add_filter( 'the_comments_navigation', 'aqualuxe_schema_comments_navigation' );

/**
 * Add schema markup to the search form.
 *
 * @param string $form The search form.
 * @return string
 */
function aqualuxe_schema_search_form( $form ) {
	return str_replace( '<form', '<form itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"', $form );
}
add_filter( 'get_search_form', 'aqualuxe_schema_search_form' );

/**
 * Add schema markup to the breadcrumbs.
 *
 * @param string $breadcrumbs The breadcrumbs.
 * @return string
 */
function aqualuxe_schema_breadcrumbs( $breadcrumbs ) {
	return str_replace( '<nav', '<nav itemscope itemtype="https://schema.org/BreadcrumbList"', $breadcrumbs );
}
add_filter( 'aqualuxe_breadcrumbs', 'aqualuxe_schema_breadcrumbs' );

/**
 * Add schema markup to the site title.
 *
 * @param string $title The site title.
 * @return string
 */
function aqualuxe_schema_site_title( $title ) {
	return '<span itemprop="name">' . $title . '</span>';
}
add_filter( 'aqualuxe_site_title', 'aqualuxe_schema_site_title' );

/**
 * Add schema markup to the site description.
 *
 * @param string $description The site description.
 * @return string
 */
function aqualuxe_schema_site_description( $description ) {
	return '<span itemprop="description">' . $description . '</span>';
}
add_filter( 'aqualuxe_site_description', 'aqualuxe_schema_site_description' );

/**
 * Add schema markup to the site logo.
 *
 * @param string $logo The site logo.
 * @return string
 */
function aqualuxe_schema_site_logo( $logo ) {
	return str_replace( '<img', '<img itemprop="logo"', $logo );
}
add_filter( 'aqualuxe_site_logo', 'aqualuxe_schema_site_logo' );

/**
 * Add schema markup to the site navigation.
 *
 * @param string $navigation The site navigation.
 * @return string
 */
function aqualuxe_schema_site_navigation( $navigation ) {
	return str_replace( '<nav', '<nav itemscope itemtype="https://schema.org/SiteNavigationElement"', $navigation );
}
add_filter( 'aqualuxe_site_navigation', 'aqualuxe_schema_site_navigation' );

/**
 * Add schema markup to the site footer.
 *
 * @param string $footer The site footer.
 * @return string
 */
function aqualuxe_schema_site_footer( $footer ) {
	return str_replace( '<footer', '<footer itemscope itemtype="https://schema.org/WPFooter"', $footer );
}
add_filter( 'aqualuxe_site_footer', 'aqualuxe_schema_site_footer' );

/**
 * Add schema markup to the site header.
 *
 * @param string $header The site header.
 * @return string
 */
function aqualuxe_schema_site_header( $header ) {
	return str_replace( '<header', '<header itemscope itemtype="https://schema.org/WPHeader"', $header );
}
add_filter( 'aqualuxe_site_header', 'aqualuxe_schema_site_header' );

/**
 * Add schema markup to the site sidebar.
 *
 * @param string $sidebar The site sidebar.
 * @return string
 */
function aqualuxe_schema_site_sidebar( $sidebar ) {
	return str_replace( '<aside', '<aside itemscope itemtype="https://schema.org/WPSideBar"', $sidebar );
}
add_filter( 'aqualuxe_site_sidebar', 'aqualuxe_schema_site_sidebar' );

/**
 * Add schema markup to the site main.
 *
 * @param string $main The site main.
 * @return string
 */
function aqualuxe_schema_site_main( $main ) {
	return str_replace( '<main', '<main itemscope itemtype="https://schema.org/WebPageElement" itemprop="mainContentOfPage"', $main );
}
add_filter( 'aqualuxe_site_main', 'aqualuxe_schema_site_main' );

/**
 * Add schema markup to the site article.
 *
 * @param string $article The site article.
 * @return string
 */
function aqualuxe_schema_site_article( $article ) {
	return str_replace( '<article', '<article itemscope itemtype="https://schema.org/Article"', $article );
}
add_filter( 'aqualuxe_site_article', 'aqualuxe_schema_site_article' );

/**
 * Add schema markup to the site comment.
 *
 * @param string $comment The site comment.
 * @return string
 */
function aqualuxe_schema_site_comment( $comment ) {
	return str_replace( '<article', '<article itemscope itemtype="https://schema.org/Comment"', $comment );
}
add_filter( 'aqualuxe_site_comment', 'aqualuxe_schema_site_comment' );

/**
 * Add schema markup to the site comments.
 *
 * @param string $comments The site comments.
 * @return string
 */
function aqualuxe_schema_site_comments( $comments ) {
	return str_replace( '<div', '<div itemscope itemtype="https://schema.org/Comment"', $comments );
}
add_filter( 'aqualuxe_site_comments', 'aqualuxe_schema_site_comments' );

/**
 * Add schema markup to the site comment form.
 *
 * @param string $form The site comment form.
 * @return string
 */
function aqualuxe_schema_site_comment_form( $form ) {
	return str_replace( '<form', '<form itemscope itemtype="https://schema.org/CommentAction"', $form );
}
add_filter( 'aqualuxe_site_comment_form', 'aqualuxe_schema_site_comment_form' );