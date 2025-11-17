<?php
// Classifieds module: lightweight CPT for buy/sell/trade listings.
// Self-contained and optional.

add_action('init', function(){
	register_post_type('classified', [
		'label' => __('Classifieds','aqualuxe'),
		'public' => true,
		'show_in_rest' => true,
		'supports' => ['title','editor','thumbnail','excerpt','custom-fields'],
		'has_archive' => true,
		'menu_icon' => 'dashicons-megaphone',
		'rewrite' => ['slug' => 'classifieds'],
	]);
	register_taxonomy('classified_type', ['classified'], [
		'label' => __('Listing Types','aqualuxe'),
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => ['slug' => 'classified-type'],
	]);
});

// Simple meta registration for price/contact (keeps it generic; can integrate ACF).
add_action('init', function(){
	register_post_meta('classified','_alx_price',[ 'show_in_rest'=>true,'single'=>true,'type'=>'string','auth_callback'=>'__return_true' ]);
	register_post_meta('classified','_alx_contact',[ 'show_in_rest'=>true,'single'=>true,'type'=>'string','auth_callback'=>'__return_true' ]);
});

// Basic templates using get_template_part fallbacks
add_filter('template_include', function($tpl){
	if ( is_singular('classified') ) {
		$alt = get_stylesheet_directory() . '/templates/single-classified.php';
		return file_exists($alt) ? $alt : __DIR__ . '/single-classified.php';
	}
	if ( is_post_type_archive('classified') ) {
		$alt = get_stylesheet_directory() . '/templates/archive-classified.php';
		return file_exists($alt) ? $alt : __DIR__ . '/archive-classified.php';
	}
	return $tpl;
});
