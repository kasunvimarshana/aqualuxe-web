<?php
// Basic WooCommerce template wrapper for consistency when WC disabled.
if ( ! function_exists( 'aqualuxe_content_start' ) ) {
	function aqualuxe_content_start(){ echo '<main id="primary" class="site-main container mx-auto px-4 py-8">'; }
}
if ( ! function_exists( 'aqualuxe_content_end' ) ) {
	function aqualuxe_content_end(){ echo '</main>'; }
}
