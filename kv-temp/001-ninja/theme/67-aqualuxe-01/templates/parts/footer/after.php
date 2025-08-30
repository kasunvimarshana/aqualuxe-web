<?php
/**
 * Template part for displaying footer after content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Display custom footer scripts
$footer_scripts = get_theme_mod( 'aqualuxe_footer_scripts', '' );

if ( ! empty( $footer_scripts ) ) {
    echo $footer_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}