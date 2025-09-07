<?php
/**
 * AquaLuxe Content Importer
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

namespace AquaLuxe\Modules\Demo_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the WordPress Importer classes if they don't exist.
if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require $class_wp_importer;
	}
}

if ( ! class_exists( 'WP_Import' ) ) {
	$class_wp_import = get_template_directory() . '/inc/wordpress-importer/class-wp-import.php';
	if ( file_exists( $class_wp_import ) ) {
		require $class_wp_import;
	}
}


/**
 * Class Content_Importer
 *
 * Handles importing content from a WXR file.
 */
class Content_Importer extends \WP_Import {

	/**
	 * Import the content from the given file.
	 *
	 * @param string $file Path to the WXR file.
	 */
	public function import( $file ) {
		// Disable import of authors.
		$this->fetch_attachments = true;
		$this->id = 'aqualuxe-importer'; // Add a unique ID for the importer.

		// Start the import.
		$this->import_start( $file );

		// Get authors from the file.
		$this->get_author_mapping();

		// Process terms.
		$this->process_terms();

		// Process posts.
		$this->process_posts();

		// Finish the import.
		$this->import_end();
	}
}
