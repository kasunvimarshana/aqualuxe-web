<?php
if ( function_exists('get_header') ) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
	<h1 class="text-3xl font-semibold mb-6"><?php
		$__q = function_exists('get_search_query') ? call_user_func('get_search_query') : '';
		$__fmt = function_exists('esc_html__') ? call_user_func('esc_html__','Search results for "%s"','aqualuxe') : 'Search results for "%s"';
		printf($__fmt, $__q);
	?></h1>
	<?php if ( function_exists('have_posts') && call_user_func('have_posts') ) : ?>
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
		<?php while ( function_exists('have_posts') && call_user_func('have_posts') ) : if ( function_exists('the_post') ) call_user_func('the_post'); ?>
			<article <?php if ( function_exists('post_class') ) { call_user_func('post_class','p-4 bg-white/5 rounded-md'); } else { echo 'class="p-4 bg-white/5 rounded-md"'; } ?> >
				<h2 class="text-xl font-semibold"><a href="<?php if ( function_exists('the_permalink') ) { call_user_func('the_permalink'); } ?>"><?php if ( function_exists('the_title') ) { call_user_func('the_title'); } ?></a></h2>
				<div class="opacity-80 text-sm mt-2"><?php if ( function_exists('the_excerpt') ) { call_user_func('the_excerpt'); } ?></div>
			</article>
		<?php endwhile; ?>
		</div>
		<div class="mt-8"><?php if ( function_exists('the_posts_pagination') ) { call_user_func('the_posts_pagination'); } ?></div>
	<?php else: ?>
		<p><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','No results found.','aqualuxe') : 'No results found.'; ?></p>
	<?php endif; ?>
</main>
<?php if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
