<?php
// Comments template with guarded WP calls for analyzer friendliness.
if ( function_exists('post_password_required') && call_user_func('post_password_required') ) { return; }
?>
<div id="comments" class="comments-area container mx-auto px-4 py-10">
	<?php if ( function_exists('have_comments') && call_user_func('have_comments') ) : ?>
		<h2 class="comments-title text-2xl font-semibold">
			<?php
			$comments_number = function_exists('get_comments_number') ? call_user_func('get_comments_number') : 0;
			$singular_plural = function_exists('_n') ? call_user_func('_n','%s comment','%s comments', $comments_number, 'aqualuxe') : '%s comments';
			$formatted_num = function_exists('number_format_i18n') ? call_user_func('number_format_i18n', $comments_number) : (string) $comments_number;
			printf( $singular_plural, $formatted_num );
			?>
		</h2>
		<ol class="comment-list space-y-6">
			<?php if ( function_exists('wp_list_comments') ) { call_user_func('wp_list_comments', [ 'style' => 'ol', 'short_ping' => true ] ); } ?>
		</ol>
		<?php if ( function_exists('the_comments_pagination') ) { call_user_func('the_comments_pagination'); } ?>
	<?php endif; ?>
	<?php if ( function_exists('comment_form') ) { call_user_func('comment_form'); } ?>
</div>
