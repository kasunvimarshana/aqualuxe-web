<?php

/**
 * AquaLuxe Template Hooks
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Header
 */
add_action('aqualuxe_header', 'aqualuxe_site_branding', 10);
add_action('aqualuxe_header', 'aqualuxe_primary_navigation', 20);

/**
 * Footer
 */
add_action('aqualuxe_footer', 'aqualuxe_site_footer', 10);
