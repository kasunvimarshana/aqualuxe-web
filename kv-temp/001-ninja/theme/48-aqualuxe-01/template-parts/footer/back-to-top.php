<?php
/**
 * Template part for displaying the back to top button
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Check if back to top button is enabled
if ( ! aqualuxe_get_option( 'enable_back_to_top', true ) ) {
    return;
}
?>

<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-primary hover:bg-primary-dark text-white rounded-full w-10 h-10 flex items-center justify-center shadow-md opacity-0 invisible transition-all duration-300 z-40" aria-label="<?php esc_attr_e( 'Back to Top', 'aqualuxe' ); ?>">
    <i class="fas fa-chevron-up"></i>
</button>