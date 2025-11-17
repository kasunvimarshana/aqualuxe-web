<?php
/**
 * AquaLuxe functions and definitions
 *
 * @package aqualuxe
 */

define( 'AQUALUXE_VERSION', '1.0.21' );
$__tpl_dir = function_exists('get_template_directory') ? call_user_func('get_template_directory') : __DIR__;
$__tpl_uri = function_exists('get_template_directory_uri') ? call_user_func('get_template_directory_uri') : '';
define( 'AQUALUXE_PATH', rtrim($__tpl_dir, '/\\') . DIRECTORY_SEPARATOR );
define( 'AQUALUXE_URI', $__tpl_uri ? rtrim($__tpl_uri, '/\\') . '/' : '' );

autoload_aqualuxe();

function autoload_aqualuxe() {
	// Simple PSR-4-like loader for theme namespaces.
	spl_autoload_register( function ( $class ) {
		if ( 0 !== strpos( $class, 'AquaLuxe\\' ) ) {
			return;
		}
		$path = AQUALUXE_PATH . 'inc/' . str_replace( [ 'AquaLuxe\\', '\\' ], [ '', '/' ], $class ) . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	} );
}

// Boot core.
add_action( 'after_setup_theme', function () {
	// Load text domain
	if ( function_exists('load_theme_textdomain') ) { call_user_func('load_theme_textdomain', 'aqualuxe', AQUALUXE_PATH . 'languages' ); }

	// Theme supports
	if ( function_exists('add_theme_support') ) {
		call_user_func('add_theme_support', 'title-tag' );
		call_user_func('add_theme_support', 'post-thumbnails' );
		call_user_func('add_theme_support', 'custom-logo', [ 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ] );
		call_user_func('add_theme_support', 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		call_user_func('add_theme_support', 'align-wide' );
		call_user_func('add_theme_support', 'woocommerce' );
		call_user_func('add_theme_support', 'automatic-feed-links' );
		call_user_func('add_theme_support', 'editor-styles' );
		call_user_func('add_theme_support', 'responsive-embeds' );
		call_user_func('add_theme_support', 'customize-selective-refresh-widgets' );
	}
	if ( function_exists('add_editor_style') ) { call_user_func('add_editor_style', 'assets/dist/css/theme.css' ); }

	// Content width
	global $content_width; if ( ! isset( $content_width ) ) { $content_width = 1200; }

	// Image sizes
	if ( function_exists('add_image_size') ) {
		call_user_func('add_image_size', 'alx-hero', 1920, 1080, true );
		call_user_func('add_image_size', 'alx-card', 600, 400, true );
	}

	if ( function_exists('register_nav_menus') ) {
		call_user_func('register_nav_menus', [
			'primary'   => ( function_exists('__') ? call_user_func('__', 'Primary Menu', 'aqualuxe' ) : 'Primary Menu' ),
			'footer'    => ( function_exists('__') ? call_user_func('__', 'Footer Menu', 'aqualuxe' ) : 'Footer Menu' ),
			'account'   => ( function_exists('__') ? call_user_func('__', 'Account Menu', 'aqualuxe' ) : 'Account Menu' ),
		] );
	}
} );

// Remove emoji scripts/styles for performance
add_action( 'init', function(){
	if ( function_exists('remove_action') ) {
		call_user_func('remove_action', 'wp_head', 'print_emoji_detection_script', 7 );
		call_user_func('remove_action', 'admin_print_scripts', 'print_emoji_detection_script' );
		call_user_func('remove_action', 'wp_print_styles', 'print_emoji_styles' );
		call_user_func('remove_action', 'admin_print_styles', 'print_emoji_styles' );
	}
}, 1 );

// Apply dark mode class as early as possible to avoid flash
add_action( 'wp_head', function(){
	echo "<script>(function(){try{var d=localStorage.getItem('alx-dark')==='1';var r=document.documentElement;if(d){r.classList.add('dark');}else{r.classList.remove('dark');}}catch(e){}})();</script>";
}, 0 );

// Optionally remove wp-embed on frontend
add_action( 'wp_enqueue_scripts', function(){
	$disable = true;
	if ( function_exists('apply_filters') ) { $disable = call_user_func( 'apply_filters', 'aqualuxe_disable_wp_embed', true ); }
	$not_admin = function_exists('is_admin') ? ! call_user_func('is_admin') : true;
	$not_customizer = function_exists('is_customize_preview') ? ! call_user_func('is_customize_preview') : true;
	if ( $disable && $not_admin && $not_customizer && function_exists('wp_deregister_script') ) {
		call_user_func('wp_deregister_script', 'wp-embed' );
	}
}, 100 );

// Load lightweight SEO/opengraph tags.
require_once AQUALUXE_PATH . 'inc/SEO.php';
require_once AQUALUXE_PATH . 'inc/SEO-ldjson.php';
// WooCommerce content wrapper helpers
if ( file_exists( AQUALUXE_PATH . 'woocommerce/wc-wrapper.php' ) ) {
	require_once AQUALUXE_PATH . 'woocommerce/wc-wrapper.php';
}

// WooCommerce mini cart link + live fragments
if ( ! function_exists( 'aqualuxe_cart_link_html' ) ) {
	function aqualuxe_cart_link_html(){
		$wc = function_exists('WC') ? call_user_func('WC') : null;
		$cnt = ($wc && isset($wc->cart) && $wc->cart) ? (int) $wc->cart->get_cart_contents_count() : 0;
		$url = function_exists('wc_get_cart_url') ? call_user_func('wc_get_cart_url') : ( function_exists('home_url') ? call_user_func('home_url','/cart') : '#cart' );
		$label = function_exists('esc_html__') ? call_user_func('esc_html__', 'Cart', 'aqualuxe' ) : 'Cart';
		$aria = function_exists('esc_attr__') ? call_user_func('esc_attr__', 'View cart','aqualuxe') : 'View cart';
		$href = function_exists('esc_url') ? call_user_func('esc_url', $url) : $url;
		return '<a href="' . $href . '" class="alx-cart-link" data-minicart-toggle="true" aria-controls="alx-mini-cart" aria-expanded="false" aria-label="' . $aria . '">' . $label . ' (<span class="alx-cart-count">' . $cnt . '</span>)</a>';
	}
}

// Feature toggle
if ( ! function_exists('aqualuxe_is_mini_cart_enabled') ) {
	function aqualuxe_is_mini_cart_enabled(){
		$enabled = true;
		if ( function_exists('get_theme_mod') ) {
			$mod = get_theme_mod('aqualuxe_enable_mini_cart', 1);
			$enabled = (bool) $mod;
		}
		return (bool) ( function_exists('apply_filters') ? call_user_func('apply_filters','aqualuxe_enable_mini_cart', $enabled) : $enabled );
	}
}

if ( ! function_exists('aqualuxe_mini_cart_content_html') ) {
	function aqualuxe_mini_cart_content_html(){
		// Build inner mini-cart content
		$inner = '';
		$shop_url = '#';
		if ( function_exists('wc_get_page_id') ) {
			$shop_id = (int) call_user_func('wc_get_page_id','shop');
			if ( $shop_id && function_exists('get_permalink') ) {
				$shop_url = call_user_func('get_permalink', $shop_id );
			}
		}
		if ( $shop_url === '#' && function_exists('home_url') ) {
			$shop_url = call_user_func('home_url','/shop');
		}
		$shop_url = function_exists('apply_filters') ? call_user_func('apply_filters','aqualuxe_start_shopping_url', $shop_url) : $shop_url;
		$shop_href = function_exists('esc_url') ? call_user_func('esc_url',$shop_url) : $shop_url;

		if ( function_exists('aqualuxe_cart_is_empty') && aqualuxe_cart_is_empty() ) {
			$title = function_exists('esc_html__') ? call_user_func('esc_html__','Your cart is empty','aqualuxe') : 'Your cart is empty';
			$cta   = function_exists('esc_html__') ? call_user_func('esc_html__','Start shopping','aqualuxe') : 'Start shopping';
			$inner = '<div class="alx-mini-cart__empty"><p>' . $title . '</p><a class="btn btn-primary" href="' . $shop_href . '">' . $cta . '</a></div>';
		} elseif ( function_exists('woocommerce_mini_cart') ) {
			ob_start();
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce outputs escaped HTML
			call_user_func('woocommerce_mini_cart');
			$inner = ob_get_clean();
		} else {
			$inner = '<p class="text-sm opacity-70">Mini cart unavailable.</p>';
		}
		$label = function_exists('__') ? call_user_func('__','Mini cart items','aqualuxe') : 'Mini cart items';
		return '<div class="alx-mini-cart__content" role="region" aria-label="' . $label . '">' . $inner . '</div>';
	}
}

if ( ! function_exists('aqualuxe_mini_cart_summary_html') ) {
	function aqualuxe_mini_cart_summary_html(){
		if ( function_exists('aqualuxe_cart_is_empty') && aqualuxe_cart_is_empty() ) {
			return '';
		}
		$wc = function_exists('WC') ? call_user_func('WC') : null;
		$subtotal = '';
		if ( $wc && isset($wc->cart) && $wc->cart ) {
			// get_cart_subtotal returns a formatted string
			$subtotal = $wc->cart->get_cart_subtotal();
		}
		$subtotal_label = function_exists('esc_html__') ? call_user_func('esc_html__','Subtotal','aqualuxe') : 'Subtotal';
		$cart_url = function_exists('wc_get_cart_url') ? call_user_func('wc_get_cart_url') : ( function_exists('home_url') ? call_user_func('home_url','/cart') : '#');
		$checkout_url = function_exists('wc_get_checkout_url') ? call_user_func('wc_get_checkout_url') : ( function_exists('home_url') ? call_user_func('home_url','/checkout') : '#');
		$view_cart = function_exists('esc_html__') ? call_user_func('esc_html__','View cart','aqualuxe') : 'View cart';
		$checkout = function_exists('esc_html__') ? call_user_func('esc_html__','Checkout','aqualuxe') : 'Checkout';
		$cart_href = function_exists('esc_url') ? call_user_func('esc_url', $cart_url) : $cart_url;
		$checkout_href = function_exists('esc_url') ? call_user_func('esc_url', $checkout_url) : $checkout_url;
		return '<div class="alx-mini-cart__summary"><div class="alx-mini-cart__subtotal"><span class="label">' . $subtotal_label . '</span><span class="amount">' . $subtotal . '</span></div><div class="alx-mini-cart__actions"><a class="btn btn-ghost" href="' . $cart_href . '">' . $view_cart . '</a><a class="btn btn-primary" href="' . $checkout_href . '">' . $checkout . '</a></div></div>';
	}
}

// Free shipping progress (configurable via 'aqualuxe_free_shipping_threshold' filter)
if ( ! function_exists('aqualuxe_mini_cart_progress_html') ) {
	function aqualuxe_mini_cart_progress_html(){
		if ( function_exists('aqualuxe_cart_is_empty') && aqualuxe_cart_is_empty() ) {
			return '';
		}
		$threshold = null;
		$use_auto = true;
		if ( function_exists('get_theme_mod') ) {
			$use_auto = (bool) get_theme_mod('aqualuxe_use_wc_threshold', 1);
		}
		$use_auto = function_exists('apply_filters') ? call_user_func('apply_filters','aqualuxe_use_wc_free_shipping_threshold', $use_auto) : $use_auto;
		if ( $use_auto ) {
			$threshold = aqualuxe_get_free_shipping_threshold();
		}
		// Allow override or default fallback via filter
		$threshold = $threshold !== null ? $threshold : 100.0;
		$threshold = (float) ( function_exists('apply_filters') ? call_user_func('apply_filters','aqualuxe_free_shipping_threshold', $threshold) : $threshold );
		$wc = function_exists('WC') ? call_user_func('WC') : null;
		$subtotal_num = 0.0;
		if ( $wc && isset($wc->cart) && $wc->cart ) {
			$subtotal_num = (float) $wc->cart->get_subtotal();
		}
		$remaining = max( 0.0, $threshold - $subtotal_num );
		$percent = $threshold > 0 ? max(0, min(100, ($subtotal_num / $threshold) * 100)) : 100;
		$label = function_exists('esc_html__') ? call_user_func('esc_html__','You are','aqualuxe') : 'You are';
		$away  = function_exists('esc_html__') ? call_user_func('esc_html__','away from free shipping','aqualuxe') : 'away from free shipping';
		$unlocked = function_exists('esc_html__') ? call_user_func('esc_html__','Free shipping unlocked!','aqualuxe') : 'Free shipping unlocked!';
		$fmt_remaining = $remaining;
		if ( function_exists('wc_price') ) {
			$fmt_remaining = call_user_func('wc_price', $remaining );
		}
		$classes = 'alx-mini-cart__progress' . ( $remaining <= 0 ? ' is-unlocked' : '' );
		$text = ( $remaining <= 0 ) ? $unlocked : ( $label . ' ' . $fmt_remaining . ' ' . $away );
		$remaining_attr = function_exists('esc_attr') ? call_user_func('esc_attr', (string) $remaining ) : (string) $remaining;
		return '<div class="' . $classes . '" role="status" aria-live="polite" data-percent="' . round($percent) . '" data-remaining="' . $remaining_attr . '"><div class="alx-mini-cart__progress-bar"><span class="alx-mini-cart__progress-fill" style="width:' . round($percent) . '%"></span></div><div class="alx-mini-cart__progress-text">' . $text . '</div></div>';
	}
}

// Detect free-shipping threshold from current WooCommerce shipping zone (if enabled)
if ( ! function_exists('aqualuxe_get_free_shipping_threshold') ) {
	function aqualuxe_get_free_shipping_threshold(){
		if ( ! class_exists('WooCommerce') ) return null;
		$wc = function_exists('WC') ? call_user_func('WC') : null;
		if ( ! $wc || ! isset($wc->cart) || ! $wc->cart ) return null;
		$packages = $wc->cart->get_shipping_packages();
		$package = is_array($packages) && ! empty($packages) ? reset($packages) : [];
		$zone = null;
		if ( function_exists('wc_get_shipping_zone') ) {
			$zone = call_user_func('wc_get_shipping_zone', $package );
		} elseif ( class_exists('WC_Shipping_Zones') && method_exists('WC_Shipping_Zones','get_zone_matching_package') ) {
			$zone = call_user_func(['WC_Shipping_Zones','get_zone_matching_package'], $package );
		}
		if ( ! $zone ) return null;
		$methods = method_exists($zone,'get_shipping_methods') ? $zone->get_shipping_methods(true) : [];
		$thresholds = [];
		foreach ( $methods as $method ) {
			$method_id = isset($method->id) ? $method->id : '';
			if ( $method_id !== 'free_shipping' ) continue;
			$requires = method_exists($method,'get_option') ? $method->get_option('requires','') : '';
			$min_amount = (float) ( method_exists($method,'get_option') ? $method->get_option('min_amount', 0 ) : 0 );
			if ( in_array( $requires, ['min_amount','either'], true ) && $min_amount > 0 ) {
				$thresholds[] = $min_amount;
			}
		}
		if ( empty($thresholds) ) return null;
		return (float) min($thresholds);
	}
}

// Helper: is cart empty?
if ( ! function_exists('aqualuxe_cart_is_empty') ) {
	function aqualuxe_cart_is_empty(){
		$wc = function_exists('WC') ? call_user_func('WC') : null;
		if ( ! $wc || ! isset($wc->cart) || ! $wc->cart ) return true;
		if ( method_exists($wc->cart,'is_empty') ) return (bool) $wc->cart->is_empty();
		return (int) $wc->cart->get_cart_contents_count() === 0;
	}
}

// Hook fragments once plugins are loaded and WooCommerce is available
add_action( 'plugins_loaded', function(){
	if ( class_exists('WooCommerce') && aqualuxe_is_mini_cart_enabled() ) {
		add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ){
			$fragments['a.alx-cart-link'] = aqualuxe_cart_link_html();
			$fragments['div.alx-mini-cart__content'] = aqualuxe_mini_cart_content_html();
			$fragments['div.alx-mini-cart__summary'] = aqualuxe_mini_cart_summary_html();
			$fragments['div.alx-mini-cart__progress'] = aqualuxe_mini_cart_progress_html();
			return $fragments;
		});
	}
});

// Enqueue built assets from mix-manifest.json only.
add_action( 'wp_enqueue_scripts', function () {
	$manifest_path = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
	$manifest_uri  = AQUALUXE_URI . 'assets/dist';

	$manifest = [];
	if ( file_exists( $manifest_path ) ) {
		$contents = file_get_contents( $manifest_path );
		$manifest = json_decode( $contents, true );
	}

	$enqueue = function( $file ) use ( $manifest, $manifest_uri ) {
		if ( isset( $manifest["/$file"] ) ) {
			return $manifest_uri . $manifest["/$file"];
		}
		return '';
	};

	$main_css = $enqueue('css/theme.css');
	$main_js  = $enqueue('js/theme.js');

	$using_fallback = false;

	if ( $main_css ) {
		call_user_func( 'wp_enqueue_style', 'aqualuxe-theme', $main_css, [], AQUALUXE_VERSION );
	} else {
		// Fallback to unversioned dist file if present
		$dist_css_path = AQUALUXE_PATH . 'assets/dist/css/theme.css';
		if ( file_exists( $dist_css_path ) ) {
			$dist_css_uri = AQUALUXE_URI . 'assets/dist/css/theme.css';
			call_user_func( 'wp_enqueue_style', 'aqualuxe-theme', $dist_css_uri, [], AQUALUXE_VERSION );
		} else {
		// Fallback to unbuilt dev CSS if manifest not found
		$fallback_css_path = AQUALUXE_PATH . 'assets/src/scss/theme.css';
		if ( file_exists( $fallback_css_path ) ) {
			$fallback_css_uri = AQUALUXE_URI . 'assets/src/scss/theme.css';
			call_user_func( 'wp_enqueue_style', 'aqualuxe-theme', $fallback_css_uri, [], AQUALUXE_VERSION );
			$using_fallback = true;
		}
		}
	}

	if ( $main_js ) {
		call_user_func( 'wp_enqueue_script', 'aqualuxe-theme', $main_js, [], AQUALUXE_VERSION, true );
	} else {
		// Fallback to unversioned dist file if present
		$dist_js_path = AQUALUXE_PATH . 'assets/dist/js/theme.js';
		if ( file_exists( $dist_js_path ) ) {
			$dist_js_uri = AQUALUXE_URI . 'assets/dist/js/theme.js';
			call_user_func( 'wp_enqueue_script', 'aqualuxe-theme', $dist_js_uri, [], AQUALUXE_VERSION, true );
		} else {
		// Fallback to unbuilt dev JS if manifest not found
		$fallback_js_path = AQUALUXE_PATH . 'assets/src/js/theme.js';
		if ( file_exists( $fallback_js_path ) ) {
			$fallback_js_uri = AQUALUXE_URI . 'assets/src/js/theme.js';
			call_user_func( 'wp_enqueue_script', 'aqualuxe-theme', $fallback_js_uri, [], AQUALUXE_VERSION, true );
			$using_fallback = true;
		}
		}
	}

	// Localize theme options for JS consumers
	if ( function_exists('wp_localize_script') ) {
		$hero_audio = function_exists('get_theme_mod') ? (bool) get_theme_mod('aqualuxe_enable_hero_audio', 0) : false;
		$hero_audio_src = function_exists('get_theme_mod') ? (string) get_theme_mod('aqualuxe_hero_audio_src', '') : '';
		call_user_func('wp_localize_script', 'aqualuxe-theme', 'AquaLuxeConfig', [
			'heroAudioEnabled' => $hero_audio ? 1 : 0,
			'heroAudioSrc' => $hero_audio_src,
		]);
	}

	// Extra screen CSS (optional)
	$screen_css = $enqueue('css/screen.css');
	if ( $screen_css ) {
		call_user_func( 'wp_enqueue_style', 'aqualuxe-screen', $screen_css, ['aqualuxe-theme'], AQUALUXE_VERSION );
	} else {
		// Unversioned dist fallback
		$dist_screen_path = AQUALUXE_PATH . 'assets/dist/css/screen.css';
		if ( file_exists( $dist_screen_path ) ) {
			$dist_screen_uri = AQUALUXE_URI . 'assets/dist/css/screen.css';
			call_user_func( 'wp_enqueue_style', 'aqualuxe-screen', $dist_screen_uri, ['aqualuxe-theme'], AQUALUXE_VERSION );
		} else {
			$fallback_screen_path = AQUALUXE_PATH . 'assets/src/scss/screen.css';
			if ( file_exists( $fallback_screen_path ) ) {
				$fallback_screen_uri = AQUALUXE_URI . 'assets/src/scss/screen.css';
				call_user_func( 'wp_enqueue_style', 'aqualuxe-screen', $fallback_screen_uri, ['aqualuxe-theme'], AQUALUXE_VERSION );
				$using_fallback = true;
			}
		}
	}

	// Surface an admin notice once if using fallbacks
	if ( $using_fallback && function_exists('is_admin') && function_exists('add_action') ) {
		$logged_in = true;
		if ( function_exists('is_user_logged_in') ) { $logged_in = call_user_func('is_user_logged_in'); }
		if ( $logged_in ) {
			call_user_func('add_action','admin_notices', function(){
				echo '<div class="notice notice-warning is-dismissible"><p>AquaLuxe: using development asset fallbacks (no mix-manifest.json). Run npm run build for production assets.</p></div>';
			});
		}
	}

	// Provide a tiny inline CSS baseline so the UI remains usable when Tailwind is not compiled
	if ( $using_fallback && function_exists('wp_register_style') && function_exists('wp_enqueue_style') && function_exists('wp_add_inline_style') ) {
		$minimal_css = 'html,body{margin:0;padding:0} .hidden{display:none !important} .container{max-width:1200px;margin:0 auto} .alx-mini-cart{position:fixed;inset:0;z-index:50} .alx-mini-cart__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.5);cursor:pointer} .alx-mini-cart__panel{position:absolute;right:0;top:0;height:100%;width:90%;max-width:384px;background:#fff;color:#111;box-shadow:-8px 0 24px rgba(0,0,0,.2);padding:16px 16px 112px;transform:translateX(100%);transition:transform .3s ease} .alx-mini-cart:not(.hidden) .alx-mini-cart__panel{transform:translateX(0)} .alx-mini-cart__summary{position:sticky;bottom:0;left:0;right:0;background:rgba(255,255,255,.95);backdrop-filter:saturate(1.2) blur(4px);border-top:1px solid #e5e7eb;margin:0 -16px;padding:12px 16px} .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:8px;font-weight:600;text-decoration:none;border:0;cursor:pointer} .btn-primary{background:#0ea5e9;color:#fff} .btn-ghost{background:rgba(0,0,0,.05);color:#111} .sr-only{position:absolute !important;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}';
		if ( function_exists('apply_filters') ) { $minimal_css = call_user_func('apply_filters','aqualuxe_minimal_inline_css', $minimal_css ); }
		call_user_func('wp_register_style','aqualuxe-fallback-inline', false, [], AQUALUXE_VERSION);
		call_user_func('wp_enqueue_style','aqualuxe-fallback-inline');
		call_user_func('wp_add_inline_style','aqualuxe-fallback-inline', $minimal_css);
	}
} );

// Register core post types & taxonomies via Core bootstrapper.
add_action( 'init', function(){
	AquaLuxe\Core\Bootstrap::init();
	// Contact form shortcode/handlers
	if ( class_exists('AquaLuxe\\Contact\\Form') ) {
		AquaLuxe\Contact\Form::init();
	}
} );

// Admin and Customizer bootstrapping.
add_action( 'admin_init', function(){
	AquaLuxe\Admin\Admin::init();
} );
add_action( 'customize_register', function( $wp_customize ){
	AquaLuxe\Admin\Customizer::register( $wp_customize );
} );

// Optional modules loader (toggle via config filter).
add_action( 'after_setup_theme', function(){
	$config = @include AQUALUXE_PATH . 'module-config.php';
	$default_modules = [ 'dark-mode', 'multilingual', 'services', 'events', 'woocommerce', 'wishlist', 'quick-view', 'filtering', 'importer' ];
	$modules = call_user_func( 'apply_filters', 'aqualuxe_modules', is_array($config) ? $config : $default_modules );
	foreach ( $modules as $module ) {
		$loader = AQUALUXE_PATH . 'modules/' . call_user_func( 'sanitize_key', $module ) . '/module.php';
		if ( file_exists( $loader ) ) {
			require_once $loader;
		}
	}

	// Make Customizer threshold override the default free shipping threshold when available
	if ( function_exists('add_filter') ) {
		add_filter('aqualuxe_free_shipping_threshold', function( $value ){
			if ( function_exists('get_theme_mod') ) {
				$mod = get_theme_mod('aqualuxe_free_shipping_threshold', null);
				if ( $mod !== null && $mod !== '' ) {
					return (float) $mod;
				}
			}
			return $value;
		});
	}
} );
