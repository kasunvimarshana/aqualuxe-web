<?php
/**
 * WooCommerce Archive Product Override
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( function_exists('aqualuxe_content_start') ) { call_user_func('aqualuxe_content_start'); }
	if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_before_main_content'); }
	$show_title = true;
	if ( function_exists('apply_filters') ) { $show_title = call_user_func('apply_filters','woocommerce_show_page_title', true); }
	if ( $show_title ) :
		$title = function_exists('woocommerce_page_title') ? call_user_func('woocommerce_page_title', false ) : '';
		echo '<h1 class="text-2xl md:text-3xl font-semibold mb-6">' . $title . '</h1>';
	endif;

	if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_before_shop_loop'); }
	if ( function_exists('woocommerce_product_loop') ? call_user_func('woocommerce_product_loop') : false ) {
		if ( function_exists('woocommerce_product_loop_start') ) { call_user_func('woocommerce_product_loop_start'); }
		while ( function_exists('have_posts') && call_user_func('have_posts') ) {
			if ( function_exists('the_post') ) { call_user_func('the_post'); }
			if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_shop_loop'); }
			if ( function_exists('wc_get_template_part') ) { call_user_func('wc_get_template_part','content','product'); }
		}
		if ( function_exists('woocommerce_product_loop_end') ) { call_user_func('woocommerce_product_loop_end'); }
		/** Pagination */
		if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_after_shop_loop'); }
	} else {
		/** No products found */
		if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_no_products_found'); }
	}
	if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_after_main_content'); }

if ( function_exists('aqualuxe_content_end') ) { call_user_func('aqualuxe_content_end'); }
