<?php

declare(strict_types=1);

// This file provides minimal no-op shims so static analyzers don't flag
// WordPress template tags as undefined when the theme is scanned outside WP.
// These are conditionally defined and won't override real WP functions.

if (! function_exists('language_attributes')) {
    function language_attributes(): void { echo 'lang="en"'; }
}
if (! function_exists('bloginfo')) {
    function bloginfo($show = ''): void { echo 'AquaLuxe'; }
}
if (! function_exists('wp_head')) {
    function wp_head(): void {}
}
if (! function_exists('wp_footer')) {
    function wp_footer(): void {}
}
if (! function_exists('body_class')) {
    function body_class($class = ''): void { echo 'class="' . htmlspecialchars(is_string($class) ? $class : '') . '"'; }
}
if (! function_exists('has_custom_logo')) {
    function has_custom_logo(): bool { return false; }
}
if (! function_exists('the_custom_logo')) {
    function the_custom_logo(): void {}
}
if (! function_exists('home_url')) {
    function home_url($path = '/'): string { return '/'; }
}
if (! function_exists('wp_nav_menu')) {
    function wp_nav_menu($args = []): void { echo ''; }
}
if (! function_exists('wp_nonce_url')) {
    function wp_nonce_url($url, $action = -1): string { return (string) $url; }
}
if (! function_exists('add_query_arg')) {
    function add_query_arg($key, $value, $url = ''): string { return (string) $url; }
}
if (! function_exists('get_header')) {
    function get_header($name = null): void {}
}
if (! function_exists('get_footer')) {
    function get_footer($name = null): void {}
}
if (! function_exists('have_posts')) {
    function have_posts(): bool { return false; }
}
if (! function_exists('the_post')) {
    function the_post(): void {}
}
if (! function_exists('post_class')) {
    function post_class($class = ''): void { echo 'class="' . htmlspecialchars(is_string($class) ? $class : '') . '"'; }
}
if (! function_exists('the_permalink')) {
    function the_permalink(): void { echo '#'; }
}
if (! function_exists('the_title')) {
    function the_title(): void { echo 'Title'; }
}
if (! function_exists('the_excerpt')) {
    function the_excerpt(): void { echo 'Excerpt'; }
}
if (! function_exists('the_posts_pagination')) {
    function the_posts_pagination($args = []): void {}
}
if (! function_exists('the_content')) {
    function the_content(): void { echo 'Content'; }
}
if (! function_exists('the_archive_title')) {
    function the_archive_title(): void { echo 'Archive'; }
}
if (! function_exists('get_search_query')) {
    function get_search_query(): string { return ''; }
}
