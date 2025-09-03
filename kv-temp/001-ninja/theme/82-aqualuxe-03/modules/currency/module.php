<?php
// Minimal multicurrency readiness without hard dependency on a plugin.
// Adds a currency switcher placeholder and filter points.

if ( ! function_exists('aqualuxe_get_currencies') ) {
	function aqualuxe_get_currencies() : array {
		$currs = [
			'USD' => ['$','US Dollar'],
			'EUR' => ['€','Euro'],
			'GBP' => ['£','British Pound'],
		];
		return (array) apply_filters('aqualuxe_currencies', $currs);
	}
}

if ( ! function_exists('aqualuxe_get_active_currency') ) {
	function aqualuxe_get_active_currency() : string {
		$k = isset($_COOKIE['alx_currency']) ? sanitize_text_field($_COOKIE['alx_currency']) : '';
		$all = aqualuxe_get_currencies();
		return array_key_exists($k, $all) ? $k : apply_filters('aqualuxe_default_currency','USD');
	}
}

add_action('init', function(){
	if ( isset($_GET['alx_currency']) ) {
		$cur = sanitize_text_field($_GET['alx_currency']);
		$all = aqualuxe_get_currencies();
		if ( isset($all[$cur]) ) {
			setcookie('alx_currency', $cur, time()+60*60*24*30, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
		}
	}
});

// Frontend switcher in header (simple, replace with menu item if needed)
add_action('wp_footer', function(){
	$all = aqualuxe_get_currencies(); $active = aqualuxe_get_active_currency();
	echo '<div class="fixed left-4 bottom-4 z-40 bg-white/90 dark:bg-slate-800/90 backdrop-blur rounded px-3 py-2 text-sm shadow pointer-events-auto" role="group" aria-label="Currency">';
	foreach($all as $code => $meta){
		$href = esc_url( add_query_arg('alx_currency', $code) );
		$cls = 'px-2 py-1 inline-block rounded ' . ($code===$active? 'bg-sky-600 text-white' : 'bg-black/5 dark:bg-white/10');
		echo '<a class="'.$cls.'" href="'.$href.'" rel="nofollow">'.esc_html($code).'</a> ';
	}
	echo '</div>';
});

// Example price filter point
add_filter('aqualuxe_price_display', function( $html, $amount ){
	$curr = aqualuxe_get_active_currency();
	$map = aqualuxe_get_currencies();
	$symbol = $map[$curr][0] ?? '$';
	$rate = (float) apply_filters('aqualuxe_currency_rate_'.$curr, 1.0);
	$converted = (float) $amount * max(0.0001, $rate);
	return sprintf('<span class="alx-price" data-currency="%s">%s%s</span>', esc_attr($curr), esc_html($symbol), esc_html( number_format_i18n($converted, 2) ));
}, 10, 2);
