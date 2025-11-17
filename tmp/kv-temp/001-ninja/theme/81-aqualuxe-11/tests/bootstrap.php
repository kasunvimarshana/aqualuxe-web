<?php
// Minimal bootstrap for theme unit tests (does not spin up full WP).
if (!function_exists('get_theme_mod')) {
	function get_theme_mod($name, $default = false) { return $default; }
}
if (!function_exists('add_filter')) {
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) { /* noop in unit tests */ }
}
if (!function_exists('add_action')) {
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) { /* noop in unit tests */ }
}
if (!function_exists('add_shortcode')) {
	function add_shortcode($tag, $callback) { /* noop in unit tests */ }
}
if (!function_exists('shortcode_exists')) {
	function shortcode_exists($tag) { return false; }
}
require_once __DIR__ . '/../inc/helpers.php';
