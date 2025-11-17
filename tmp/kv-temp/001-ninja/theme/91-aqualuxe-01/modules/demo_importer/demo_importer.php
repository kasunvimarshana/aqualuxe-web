<?php
/**
 * Demo Importer Module
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define module constants.
define( 'AQUALUXE_DEMO_IMPORTER_DIR', trailingslashit( AQUALUXE_THEME_DIR . 'modules/demo_importer' ) );
define( 'AQUALUXE_DEMO_IMPORTER_URI', trailingslashit( AQUALUXE_THEME_URI . 'modules/demo_importer' ) );

// Include files.
require_once AQUALUXE_DEMO_IMPORTER_DIR . 'includes/admin-page.php';
require_once AQUALUXE_DEMO_IMPORTER_DIR . 'includes/importer.php';
