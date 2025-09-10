<?php
/**
 * Asset handling
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles asset enqueuing and registration.
 */
class AquaLuxe_Assets {

	/**
	 * The manifest file path.
	 *
	 * @var string
	 */
	private $manifest_path;

	/**
	 * The manifest data.
	 *
	 * @var array
	 */
	private $manifest;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
		if ( file_exists( $this->manifest_path ) ) {
			$this->manifest = json_decode( file_get_contents( $this->manifest_path ), true );
		} else {
			$this->manifest = array();
		}
	}

	/**
	 * Get the path to a versioned Mix file.
	 *
	 * @param  string  $path
	 * @return string
	 */
	public function get_asset_path( $path ) {
		if ( ! empty( $this->manifest[ $path ] ) ) {
			return get_template_directory_uri() . '/assets/dist' . $this->manifest[ $path ];
		}
		return get_template_directory_uri() . '/assets/dist' . $path;
	}
}
