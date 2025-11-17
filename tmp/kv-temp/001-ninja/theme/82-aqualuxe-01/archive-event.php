<?php
/** Archive Events (guarded) */
if ( function_exists('get_header') ) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
	<h1 class="text-3xl font-semibold mb-6"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Events','aqualuxe') : 'Events'; ?></h1>
	<?php if ( function_exists('have_posts') && call_user_func('have_posts') ) : ?>
		<ul class="space-y-4">
		<?php while ( function_exists('have_posts') && call_user_func('have_posts') ) : if ( function_exists('the_post') ) call_user_func('the_post'); ?>
			<li><a class="underline" href="<?php if ( function_exists('the_permalink') ) { call_user_func('the_permalink'); } ?>"><?php if ( function_exists('the_title') ) { call_user_func('the_title'); } ?></a></li>
		<?php endwhile; ?>
		</ul>
		<div class="mt-8"><?php if ( function_exists('the_posts_pagination') ) { call_user_func('the_posts_pagination'); } ?></div>
	<?php else: ?>
		<p><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','No events found.','aqualuxe') : 'No events found.'; ?></p>
	<?php endif; ?>
</main>
<?php if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
