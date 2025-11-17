<?php
/**
 * Service for managing theme options and Customizer settings.
 *
 * @package Aqualuxe
 * @subpackage Core/Services
 */

namespace Aqualuxe\Core\Services;

use Aqualuxe\Core\Contracts\Service;

/**
 * OptionsService class.
 */
class OptionsService implements Service {

	/**
	 * The theme options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor.
	 * Loads the theme options.
	 */
	public function __construct() {
		$this->options = get_option( 'aqualuxe_options', array() );
	}

	/**
	 * Registers the service.
	 */
	public function register(): void {
		// Actions and filters related to theme options can be added here.
	}

	/**
	 * Get a single theme option.
	 *
	 * @param string $key The option key.
	 * @param mixed  $default The default value if the option is not found.
	 * @return mixed The option value.
	 */
	public function get( string $key, $default = null ) {
		return $this->options[ $key ] ?? $default;
	}

	/**
	 * Get all theme options.
	 *
	 * @return array
	 */
	public function get_all(): array {
		return $this->options;
	}
}
