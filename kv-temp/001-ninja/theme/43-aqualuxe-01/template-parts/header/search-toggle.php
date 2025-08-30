<?php
/**
 * Template part for displaying the search toggle
 *
 * @package AquaLuxe
 */

// Check if search is enabled in customizer
$search_enabled = get_theme_mod( 'aqualuxe_search_enable', true );

if ( ! $search_enabled ) {
    return;
}
?>

<div class="search-toggle">
    <button id="search-toggle-btn" class="search-toggle-btn" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>" aria-expanded="false" aria-controls="search-modal">
        <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>
</div>