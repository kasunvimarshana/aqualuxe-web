<?php
// Importer admin page stub; full tool can be extended incrementally.
add_action( 'admin_menu', function(){
	add_submenu_page('aqualuxe', call_user_func('__','Demo Importer','aqualuxe'), call_user_func('__','Demo Importer','aqualuxe'), 'manage_options', 'aqualuxe-importer', function(){
		if ( ! call_user_func('current_user_can','manage_options') ) return;
		echo '<div class="wrap"><h1>' . call_user_func('esc_html__','AquaLuxe Demo Importer','aqualuxe') . '</h1>';
		echo '<p>' . call_user_func('esc_html__','Create a complete demo with pages, CPTs, products, and menus. Use Flush to reset.','aqualuxe') . '</p>';
		echo '<form method="post" class="card">';
		echo '<p><label><input type="checkbox" name="alx_flush" value="1"> ' . call_user_func('esc_html__','Flush existing content (dangerous)','aqualuxe') . '</label></p>';
		echo '<p><label><input type="checkbox" name="alx_products" value="1" checked> ' . call_user_func('esc_html__','Include WooCommerce demo products','aqualuxe') . '</label></p>';
		echo '<p><label>' . call_user_func('esc_html__','Locale (for future i18n seeds):','aqualuxe') . ' <input name="alx_locale" value="en" /></label></p>';
		call_user_func('wp_nonce_field','aqualuxe_import');
		call_user_func('submit_button', call_user_func('__','Run Import','aqualuxe') );
		echo '</form></div>';
	});
});

add_action('admin_init', function(){
	if ( ! isset($_POST['_wpnonce']) || ! call_user_func('wp_verify_nonce', $_POST['_wpnonce'], 'aqualuxe_import') ) return;
	if ( ! call_user_func('current_user_can','manage_options') ) return;

	$opts = [
		'flush'    => isset($_POST['alx_flush']) ? 1 : 0,
		'pages'    => 1,
		'services' => 1,
		'events'   => 1,
		'products' => isset($_POST['alx_products']) ? 1 : 0,
		'menus'    => 1,
		'locale'   => call_user_func('sanitize_text_field', $_POST['alx_locale'] ?? 'en' ),
	];
	require_once call_user_func('get_template_directory') . '/inc/Importer/Importer.php';
	$imp = new \AquaLuxe\Importer\Importer();
	$imp->run( $opts );
	add_action('admin_notices', function(){ echo '<div class="notice notice-success"><p>' . call_user_func('esc_html__','AquaLuxe demo content imported.','aqualuxe') . '</p></div>'; });
});
