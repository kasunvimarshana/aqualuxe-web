<?php
/**
 * Singleton Trait
 *
 * Provides a reusable implementation of the Singleton pattern for the AquaLuxe theme.
 * This trait can be used by any class that needs singleton behavior.
 *
 * @package AquaLuxe
 * @subpackage Core\Traits
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

namespace AquaLuxe\Core\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton Trait
 *
 * Implements the Singleton design pattern as a reusable trait.
 * Classes using this trait will have singleton behavior automatically.
 *
 * @since 2.0.0
 */
trait Singleton_Trait {

	/**
	 * The single instance of the class.
	 *
	 * @since 2.0.0
	 * @var static|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance if one doesn't exist, otherwise returns the existing instance.
	 *
	 * @since 2.0.0
	 * @return static The singleton instance.
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @since 2.0.0
	 * @return void
	 * @throws \Exception When someone tries to clone the instance.
	 */
	public function __clone() {
		throw new \Exception( 'Cannot clone singleton instance.' );
	}

	/**
	 * Prevent unserialization of the instance.
	 *
	 * @since 2.0.0
	 * @return void
	 * @throws \Exception When someone tries to unserialize the instance.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton instance.' );
	}

	/**
	 * Reset the singleton instance (for testing purposes only).
	 *
	 * @since 2.0.0
	 * @internal This method is for testing purposes only.
	 * @return void
	 */
	public static function reset_instance(): void {
		static::$instance = null;
	}
}
