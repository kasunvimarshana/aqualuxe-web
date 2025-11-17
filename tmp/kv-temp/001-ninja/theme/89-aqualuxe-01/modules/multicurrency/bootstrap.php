<?php
/** Multicurrency (lightweight, cookie-based) */
namespace AquaLuxe\Modules\Multicurrency;
if ( ! defined( 'ABSPATH' ) ) { exit; }

const COOKIE = 'alx_currency';
const ALLOWED = [ 'USD' => '$', 'EUR' => '\u20ac', 'GBP' => '\u00a3', 'LKR' => '\u0dbb\u0dd4', 'JPY' => '\u00a5' ];
// Default relative rates to base currency (from Woo store currency). Override via filter 'aqualuxe/currency/rates'.
const DEFAULT_RATES = [ 'USD' => 1.0, 'EUR' => 0.92, 'GBP' => 0.79, 'LKR' => 300.0, 'JPY' => 144.0 ];

// Filterable currencies map (code => symbol)
function currencies(): array {
	$list = ALLOWED;
	if ( \function_exists( 'apply_filters' ) ) {
		$list = (array) \call_user_func( 'apply_filters', 'aqualuxe/currencies', $list );
	}
	$norm = [];
	foreach ( $list as $code => $sym ) { $norm[ \strtoupper( (string) $code ) ] = (string) $sym; }
	return $norm;
}

function base_currency(): string {
	$def = \function_exists('get_option') ? (string) \call_user_func('get_option', 'woocommerce_currency', 'USD') : 'USD';
	return \strtoupper($def);
}

function rates(): array {
	$r = DEFAULT_RATES;
	if ( \function_exists('apply_filters') ) {
		$r = (array) \call_user_func('apply_filters', 'aqualuxe/currency/rates', $r );
	}
	// Normalize keys upper, numeric values
	$out = [];
	foreach ($r as $code => $val) { $out[\strtoupper((string)$code)] = (float) $val; }
	return $out;
}

function conversion_enabled(): bool {
	$enabled = true;
	if ( \function_exists('get_option') ) {
		$opt = \call_user_func('get_option', 'aqualuxe_currency_convert', '1');
		$enabled = $opt === '1' || $opt === 1 || $opt === true;
	}
	if ( \function_exists('apply_filters') ) {
		$enabled = (bool) \call_user_func('apply_filters', 'aqualuxe/currency/conversion_enabled', $enabled);
	}
	return $enabled;
}

function is_catalog(): bool {
	$is_cart = \function_exists('is_cart') ? (bool) \call_user_func('is_cart') : false;
	$is_checkout = \function_exists('is_checkout') ? (bool) \call_user_func('is_checkout') : false;
	$is_account = \function_exists('is_account_page') ? (bool) \call_user_func('is_account_page') : false;
	$is_admin = \function_exists('is_admin') ? (bool) \call_user_func('is_admin') : (\php_sapi_name() === 'cli');
	return ! $is_cart && ! $is_checkout && ! $is_account && ! $is_admin;
}

function price_decimals(): int {
	$d = 2;
	if ( \function_exists('get_option') ) {
		$d = (int) \call_user_func('get_option', 'woocommerce_price_num_decimals', 2);
	}
	return $d > 4 ? 4 : ($d < 0 ? 0 : $d);
}

function convert_amount( float $amount, string $from, string $to ): float {
	$from = \strtoupper($from); $to = \strtoupper($to);
	if ($from === $to) { return $amount; }
	$table = rates();
	// Interpret rates as 1 base_currency = rate[code] units of code
	$base = base_currency();
	if (!isset($table[$from]) || !isset($table[$to]) || !isset($table[$base])) { return $amount; }
	// Convert from -> base -> to
	// amount_in_base = amount / rate[from]
	// amount_in_to = amount_in_base * rate[to]
	$in_base = $table[$from] > 0 ? ($amount / (float)$table[$from]) : $amount;
	$converted = $in_base * (float)$table[$to];
	$dec = price_decimals();
	return (float) number_format( $converted, $dec, '.', '' );
}

function default_currency(): string {
	$def = \function_exists( 'get_option' ) ? (string) \call_user_func( 'get_option', 'woocommerce_currency', 'USD' ) : 'USD';
	if ( \function_exists( 'apply_filters' ) ) {
		$def = (string) \call_user_func( 'apply_filters', 'aqualuxe/default_currency', $def );
	}
	return \strtoupper( $def );
}

function current_currency(): string {
	$list = currencies();
	$cur  = isset( $_COOKIE[ COOKIE ] ) ? \strtoupper( (string) $_COOKIE[ COOKIE ] ) : default_currency();
	return \array_key_exists( $cur, $list ) ? $cur : default_currency();
}

function symbol_for( string $code ): string {
	$list = currencies();
	return $list[ \strtoupper( $code ) ] ?? '';
}

// Store selection
\add_action( 'init', function () {
	if ( isset( $_GET['alx_currency'] ) ) {
		$unslasher = \function_exists('wp_unslash') ? 'wp_unslash' : null;
		$sanitizer = \function_exists('sanitize_text_field') ? 'sanitize_text_field' : null;
		$raw = $unslasher ? (string) \call_user_func( $unslasher, $_GET['alx_currency'] ) : (string) $_GET['alx_currency'];
		$code = \strtoupper( $sanitizer ? (string) \call_user_func( $sanitizer, $raw ) : $raw );
	$allowed = currencies();
	if ( isset( $allowed[ $code ] ) ) {
			$exp = time() + ( \defined('YEAR_IN_SECONDS') ? (int) \constant('YEAR_IN_SECONDS') : 31536000 );
			$cpath = \defined('COOKIEPATH') ? (string) \constant('COOKIEPATH') : '/';
			$cdomain = \defined('COOKIE_DOMAIN') ? (string) \constant('COOKIE_DOMAIN') : '';
			$is_ssl = \function_exists('is_ssl') ? (bool) \call_user_func('is_ssl') : false;
			\setcookie( COOKIE, $code, $exp, $cpath, $cdomain, $is_ssl, true );
		}
	}
} );

// Switch WC currency & symbol display in catalog only (avoid cart/checkout inconsistencies)
\add_filter( 'woocommerce_currency_symbol', function ( $symbol, $currency ) {
	if ( !conversion_enabled() || !is_catalog() ) { return $symbol; }
	$sel = current_currency();
	return symbol_for( $sel ) ?: $symbol;
}, 10, 2 );

\add_filter( 'woocommerce_currency', function ( $currency ) {
	if ( !conversion_enabled() || !is_catalog() ) { return $currency; }
	$sel = current_currency();
	return \array_key_exists( $sel, currencies() ) ? $sel : $currency;
} );

// Convert product prices for catalog views
foreach ( [
	'woocommerce_product_get_price',
	'woocommerce_product_get_regular_price',
	'woocommerce_product_get_sale_price',
] as $hook ) {
	\add_filter( $hook, function( $price, $product ) {
		if ( !conversion_enabled() || !is_catalog() ) { return $price; }
		$from = base_currency();
		$to = current_currency();
		return convert_amount( (float) $price, $from, $to );
	}, 10, 2 );
}

// Shortcode switcher
\add_shortcode( 'alx_currency_switcher', function () {
	$cur = current_currency();
	$list = currencies();
	$out = '<form method="get" class="inline-flex items-center gap-2"><label class="sr-only">Currency</label><select name="alx_currency" class="border p-1">';
	foreach ( $list as $c => $s ) {
		$selected = \function_exists('selected') ? (string) \call_user_func('selected', $cur, $c, false ) : ( $cur === $c ? ' selected' : '' );
		$esc_attr = \function_exists('esc_attr') ? 'esc_attr' : null;
		$esc_html = \function_exists('esc_html') ? 'esc_html' : null;
		$val = $esc_attr ? \call_user_func( $esc_attr, $c ) : $c;
		$label = $esc_html ? \call_user_func( $esc_html, $c ) : $c;
		$out .= '<option value="' . $val . '" ' . $selected . '>' . $label . '</option>';
	}
	$apply = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__', 'Apply', 'aqualuxe') : 'Apply';
	$out .= '</select><button class="px-2 py-1 border rounded">' . $apply . '</button></form>';
	return $out;
} );
