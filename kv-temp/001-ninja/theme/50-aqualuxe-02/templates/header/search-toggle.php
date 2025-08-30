<?php
/**
 * Template part for displaying the search toggle button in the header
 *
 * @package AquaLuxe
 */

// Get search toggle settings from customizer or use defaults
$search_toggle_enable = get_theme_mod( 'aqualuxe_search_toggle_enable', true );

// Only display the search toggle if it's enabled
if ( ! $search_toggle_enable ) {
	return;
}
?>

<button id="search-toggle" class="search-toggle" aria-label="<?php esc_attr_e( 'Toggle search', 'aqualuxe' ); ?>" aria-expanded="false" aria-controls="search-form">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="search-icon">
		<path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
	</svg>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="close-icon hidden">
		<path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
	</svg>
</button>