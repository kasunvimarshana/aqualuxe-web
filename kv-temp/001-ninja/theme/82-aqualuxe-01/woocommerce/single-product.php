<?php
/**
 * WooCommerce Single Product Override
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( function_exists('aqualuxe_content_start') ) { call_user_func('aqualuxe_content_start'); }
	if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_before_main_content'); }
	while ( function_exists('have_posts') && call_user_func('have_posts') ) : if ( function_exists('the_post') ) call_user_func('the_post');
		if ( function_exists('wc_get_template_part') ) { call_user_func('wc_get_template_part','content','single-product'); }
	endwhile; // end of the loop.
	if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_after_main_content'); }

if ( function_exists('aqualuxe_content_end') ) { call_user_func('aqualuxe_content_end'); }
