<?php

/**
 * The admin-specific functionality of the theme.
 *
 * @link       https://aqualuxe.pro
 * @since      1.0.0
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/admin
 */

/**
 * The admin-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/admin
 * @author     Your Name <email@example.com>
 */
class AquaLuxe_Admin {

	/**
	 * The ID of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $theme_name    The ID of this theme.
	 */
	private $theme_name;

	/**
	 * The version of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this theme.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $theme_name       The name of this theme.
	 * @param      string    $version    The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->theme_name, AQUALUXE_THEME_URI . '/assets/dist/css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->theme_name, AQUALUXE_THEME_URI . '/assets/dist/js/admin.js', array( 'jquery' ), $this->version, false );

	}

}
