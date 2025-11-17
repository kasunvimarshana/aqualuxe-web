<?php
/**
 * Quick View Modal Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="quick-view-modal" class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center hidden">
	<div class="quick-view-overlay absolute inset-0 bg-dark-900 bg-opacity-75"></div>
	<div class="quick-view-container relative bg-white dark:bg-dark-800 rounded-lg shadow-xl max-w-5xl w-full mx-4 max-h-[90vh] overflow-y-auto">
		<button class="quick-view-close absolute top-4 right-4 text-dark-500 dark:text-dark-300 hover:text-dark-700 dark:hover:text-white transition-colors duration-200 z-10">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
			</svg>
			<span class="sr-only"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
		</button>
		<div class="quick-view-content p-6">
			<div class="quick-view-loading flex items-center justify-center py-12">
				<svg class="animate-spin h-8 w-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
				<span class="sr-only"><?php esc_html_e( 'Loading...', 'aqualuxe' ); ?></span>
			</div>
		</div>
	</div>
</div>