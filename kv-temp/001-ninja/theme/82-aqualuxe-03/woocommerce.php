<?php
/**
 * General WooCommerce template wrapper (guarded for analyzer friendliness)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( function_exists('get_header') ) { call_user_func('get_header'); }
if ( function_exists('aqualuxe_content_start') ) { call_user_func('aqualuxe_content_start'); }
if ( function_exists('woocommerce_content') ) { call_user_func('woocommerce_content'); }
if ( function_exists('aqualuxe_content_end') ) { call_user_func('aqualuxe_content_end'); }
if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
