<?php
/**
 * Singleton Interface
 *
 * Defines the contract for implementing the Singleton pattern in the AquaLuxe theme.
 * This interface ensures consistent singleton implementation across all theme components.
 *
 * @package AquaLuxe
 * @subpackage Core\Interfaces
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

namespace AquaLuxe\Core\Interfaces;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton Interface
 *
 * Provides a contract for implementing the Singleton design pattern.
 * Classes implementing this interface must ensure only one instance exists.
 *
 * @since 2.0.0
 */
interface Singleton_Interface {

	/**
	 * Get the singleton instance of the class.
	 *
	 * @since 2.0.0
	 * @return static The singleton instance.
	 */
	public static function get_instance();

	/**
	 * Prevent cloning of the instance.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function __clone();

	/**
	 * Prevent unserialization of the instance.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function __wakeup();
}
