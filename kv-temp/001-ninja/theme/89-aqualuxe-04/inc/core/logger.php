<?php
/**
 * Lightweight logger utilities (analyzer-safe)
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Internal: format log line.
 */
function format_line( string $level, string $message, array $context = [] ): string {
    $ts = gmdate( 'c' );
    $ctx = $context ? ' ' . wp_json_encode( $context ) : '';
    return "[AquaLuxe][$level][$ts] $message$ctx";
}

/** Log info-level message. */
function log_info( string $message, array $context = [] ): void {
    if ( function_exists( 'error_log' ) ) {
        error_log( format_line( 'INFO', $message, $context ) );
    }
}

/** Log warning message. */
function log_warn( string $message, array $context = [] ): void {
    if ( function_exists( 'error_log' ) ) {
        error_log( format_line( 'WARN', $message, $context ) );
    }
}

/** Log error message. */
function log_error( string $message, array $context = [] ): void {
    if ( function_exists( 'error_log' ) ) {
        error_log( format_line( 'ERROR', $message, $context ) );
    }
}
