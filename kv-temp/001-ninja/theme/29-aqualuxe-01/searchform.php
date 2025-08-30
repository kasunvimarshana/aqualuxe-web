<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr_x( 'Site search', 'label', 'aqualuxe' ); ?>">
	<label for="search-field" class="sr-only">
		<?php echo esc_html_x( 'Search for:', 'label', 'aqualuxe' ); ?>
	</label>
	<div class="relative">
		<input type="search" id="search-field" class="search-field block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 pl-10" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dark-400 dark:text-dark-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
			</svg>
		</div>
		<button type="submit" class="search-submit absolute inset-y-0 right-0 px-4 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200" aria-label="<?php echo esc_attr_x( 'Submit search', 'submit button', 'aqualuxe' ); ?>">
			<span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
			</svg>
		</button>
	</div>
</form>