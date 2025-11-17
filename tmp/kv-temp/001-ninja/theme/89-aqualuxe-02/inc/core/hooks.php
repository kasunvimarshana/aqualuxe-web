<?php
/**
 * Custom hooks registry
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Define custom actions/filters to keep consistency across modules.
 */
class Hooks {
	public const BEFORE_HEADER = 'aqualuxe_before_header';
	public const AFTER_HEADER  = 'aqualuxe_after_header';
	public const BEFORE_FOOTER = 'aqualuxe_before_footer';
	public const AFTER_FOOTER  = 'aqualuxe_after_footer';
	public const BEFORE_MAIN   = 'aqualuxe_before_main';
	public const AFTER_MAIN    = 'aqualuxe_after_main';
}
