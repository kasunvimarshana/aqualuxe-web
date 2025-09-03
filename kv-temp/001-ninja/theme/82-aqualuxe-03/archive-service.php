<?php
if ( function_exists('get_header') ) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
	<h1 class="text-3xl font-semibold mb-6"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Services','aqualuxe') : 'Services'; ?></h1>
	<?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode','[alx_services_list]') : ''; ?>
</main>
<?php if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
