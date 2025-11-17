<?php
if ( function_exists('get_header') ) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
	<h1 class="text-3xl font-semibold mb-4"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Page not found','aqualuxe') : 'Page not found'; ?></h1>
	<p class="opacity-80"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','We can’t find the page you’re looking for. Try a search:','aqualuxe') : 'We can’t find the page you’re looking for. Try a search:'; ?></p>
	<?php if ( function_exists('get_search_form') ) { call_user_func('get_search_form'); } ?>
</main>
<?php if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
