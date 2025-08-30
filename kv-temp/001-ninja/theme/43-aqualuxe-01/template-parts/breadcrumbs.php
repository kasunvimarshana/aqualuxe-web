<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if we should display breadcrumbs
$show_breadcrumbs = apply_filters('aqualuxe_show_breadcrumbs', true);

if (!$show_breadcrumbs) {
    return;
}

// Use the breadcrumbs function from our SEO module
if (function_exists('aqualuxe_breadcrumbs')) {
    aqualuxe_breadcrumbs();
}