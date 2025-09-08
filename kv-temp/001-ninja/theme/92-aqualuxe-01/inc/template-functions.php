<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

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
 * Custom callback for displaying comments.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    An array of arguments.
 * @param int        $depth   The depth of the comment.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'p-4 bg-gray-100 dark:bg-gray-700 rounded-lg' : 'parent p-4 bg-gray-100 dark:bg-gray-700 rounded-lg' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body flex space-x-4">
			<div class="flex-shrink-0">
				<?php if ( 0 !== $args['avatar_size'] ) : ?>
					<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) ); ?>
				<?php endif; ?>
			</div>

			<div class="flex-grow">
				<div class="comment-meta flex items-center justify-between mb-2">
					<div class="comment-author vcard">
						<?php
						/* translators: %s: comment author link */
						printf(
							__( '%s <span class="says">says:</span>', 'aqualuxe' ),
							sprintf( '<b class="fn text-lg font-semibold text-gray-900 dark:text-white">%s</b>', get_comment_author_link() )
						);
						?>
					</div><!-- .comment-author -->

					<div class="comment-metadata text-sm text-gray-500 dark:text-gray-400">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php
								/* translators: 1: date, 2: time */
								printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
							</time>
						</a>
						<?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->
				</div>

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation text-yellow-500"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
				<?php endif; ?>

				<div class="comment-content prose dark:prose-invert max-w-none">
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
							'before'    => '<div class="reply mt-2">',
							'after'     => '</div>',
							'class'     => 'comment-reply-link text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline',
						)
					)
				);
				?>
			</div><!-- .flex-grow -->
		</article><!-- .comment-body -->
	<?php
}

/**
 * Filters the default comment form fields.
 *
 * @param array $fields The default comment fields.
 * @return array
 */
function aqualuxe_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );

	$fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" /></p>';

	$fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					   '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req . ' class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" /></p>';

	$fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'aqualuxe' ) . '</label> ' .
					 '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" /></p>';

	return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_fields' );

/**
 * Filters the comment form comment field.
 *
 * @param string $comment_field The comment field HTML.
 * @return string
 */
function aqualuxe_comment_form_field_comment( $comment_field ) {
	$comment_field = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'aqualuxe' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea></p>';
	return $comment_field;
}
add_filter( 'comment_form_field_comment', 'aqualuxe_comment_form_field_comment' );
