<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only"><?php esc_html_e( 'Search for:', 'aqualuxe' ); ?></label>
	<div class="relative">
		<input type="search" class="search-field w-full px-4 py-2 rounded-md border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-dark-600 dark:text-light-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<button type="submit" class="search-submit absolute right-0 top-0 h-full px-4 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-500">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
			</svg>
			<span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
		</button>
	</div>
</form>