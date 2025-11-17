<?php
add_action('wp_head', function(){
	if ( ! call_user_func('is_singular') ) return;
	$ld = [
		'@context' => 'https://schema.org',
		'@type' => call_user_func('is_singular','product') ? 'Product' : 'Article',
		'name' => call_user_func('get_the_title'),
		'url' => call_user_func('get_permalink'),
		'author' => [ '@type' => 'Person', 'name' => call_user_func('get_bloginfo','name') ],
		'description' => call_user_func('wp_strip_all_tags', call_user_func('get_the_excerpt') ),
	];
	echo "\n<script type=\"application/ld+json\">" . call_user_func('wp_json_encode', $ld ) . "</script>\n";
}, 8);
