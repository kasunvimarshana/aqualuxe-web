<?php
/**
 * Event Calendar Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get calendar and module from template args
$calendar = $args['calendar'] ?? null;
$module = $args['module'] ?? null;
$args = $args['args'] ?? [];

if ( ! $calendar || ! $module ) {
	return;
}

// Render calendar
$calendar->render( $args );