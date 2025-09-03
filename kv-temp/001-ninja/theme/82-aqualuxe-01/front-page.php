<?php
if ( function_exists('get_header') ) { call_user_func('get_header'); }
// Use the Home template if exists, else simple fallback
$tpl = function_exists('locate_template') ? call_user_func('locate_template','templates/pages/home.php') : '';
if ($tpl) { include $tpl; }
else {
	$welcome = function_exists('esc_html__') ? call_user_func('esc_html__','Welcome to AquaLuxe','aqualuxe') : 'Welcome to AquaLuxe';
	echo '<main id="primary" class="site-main container mx-auto px-4 py-12"><h1>'.$welcome.'</h1></main>';
}
if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
