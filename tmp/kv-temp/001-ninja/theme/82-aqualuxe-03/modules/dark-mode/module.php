<?php
// Minimal module loader; extend later per feature.
add_action( 'wp_footer', function(){
	echo '<button type="button" class="fixed bottom-4 right-4 btn btn-ghost" data-toggle-dark aria-pressed="false">' . esc_html__( 'Toggle dark mode', 'aqualuxe' ) . '</button>';
} );
