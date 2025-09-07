<?php
/**
 * The template for displaying comments
 *
 * @package AquaLuxe
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area mt-12">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title text-2xl font-semibold mb-6">
			<?php
			$comments_number = get_comments_number();
			if ( 1 === $comments_number ) {
				printf(
					/* translators: %s: Post title. */
					esc_html_x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'aqualuxe' ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: Number of comments, 2: Post title. */
					esc_html( _nx(
						'%1$s thought on &ldquo;%2$s&rdquo;',
						'%1$s thoughts on &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'aqualuxe'
					) ),
					esc_html( number_format_i18n( $comments_number ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-above" class="navigation comment-navigation mb-6" aria-label="<?php esc_attr_e( 'Comments navigation', 'aqualuxe' ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comments navigation', 'aqualuxe' ); ?></h2>
				<div class="nav-links flex justify-between">
					<div class="nav-previous">
						<?php previous_comments_link( esc_html__( 'Older Comments', 'aqualuxe' ) ); ?>
					</div>
					<div class="nav-next">
						<?php next_comments_link( esc_html__( 'Newer Comments', 'aqualuxe' ) ); ?>
					</div>
				</div>
			</nav>
		<?php endif; ?>

		<ol class="comment-list space-y-6">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 64,
				'callback'    => 'aqualuxe_comment_callback',
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation comment-navigation mt-6" aria-label="<?php esc_attr_e( 'Comments navigation', 'aqualuxe' ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comments navigation', 'aqualuxe' ); ?></h2>
				<div class="nav-links flex justify-between">
					<div class="nav-previous">
						<?php previous_comments_link( esc_html__( 'Older Comments', 'aqualuxe' ) ); ?>
					</div>
					<div class="nav-next">
						<?php next_comments_link( esc_html__( 'Newer Comments', 'aqualuxe' ) ); ?>
					</div>
				</div>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments text-slate-600 dark:text-slate-400 italic">
			<?php esc_html_e( 'Comments are closed.', 'aqualuxe' ); ?>
		</p>
	<?php endif; ?>

	<?php
	comment_form( array(
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-semibold mb-4">',
		'title_reply_after'  => '</h3>',
		'class_form'         => 'comment-form space-y-4',
		'class_submit'       => 'btn btn-primary',
		'label_submit'       => esc_html__( 'Post Comment', 'aqualuxe' ),
		'comment_field'      => '<div class="comment-form-comment"><label for="comment" class="block text-sm font-medium mb-2">' . esc_html_x( 'Comment', 'noun', 'aqualuxe' ) . ' <span class="required text-red-500">*</span></label><textarea id="comment" name="comment" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" cols="45" rows="8" required aria-required="true" aria-describedby="form-allowed-tags"></textarea></div>',
		'fields'             => array(
			'author' => '<div class="comment-form-author"><label for="author" class="block text-sm font-medium mb-2">' . esc_html__( 'Name', 'aqualuxe' ) . ' <span class="required text-red-500">*</span></label><input id="author" name="author" type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="" size="30" maxlength="245" autocomplete="name" required aria-required="true" /></div>',
			'email'  => '<div class="comment-form-email"><label for="email" class="block text-sm font-medium mb-2">' . esc_html__( 'Email', 'aqualuxe' ) . ' <span class="required text-red-500">*</span></label><input id="email" name="email" type="email" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required aria-required="true" /></div>',
			'url'    => '<div class="comment-form-url"><label for="url" class="block text-sm font-medium mb-2">' . esc_html__( 'Website', 'aqualuxe' ) . '</label><input id="url" name="url" type="url" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="" size="30" maxlength="200" autocomplete="url" /></div>',
		),
	) );
	?>
</div>
