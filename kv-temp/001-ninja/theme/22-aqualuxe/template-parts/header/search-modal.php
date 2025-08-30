<?php
/**
 * Template part for displaying the search modal
 *
 * @package AquaLuxe
 */
?>

<div id="search-modal" class="search-modal fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden">
	<div class="search-modal-content bg-white dark:bg-dark-400 rounded-lg shadow-lg max-w-2xl w-full p-6">
		<div class="search-modal-header flex justify-between items-center mb-4">
			<h3 class="text-xl font-bold"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></h3>
			<button id="search-modal-close" class="search-modal-close flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
				<span class="sr-only"><?php esc_html_e( 'Close search', 'aqualuxe' ); ?></span>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
		</div>
		
		<div class="search-modal-body">
			<form role="search" method="get" class="search-form relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="relative">
					<input type="search" class="search-field form-input w-full pl-10 pr-4 py-3 text-lg" placeholder="<?php echo esc_attr_x( 'Search for anything...', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autofocus />
					<button type="submit" class="search-submit absolute left-0 top-0 h-full px-3 flex items-center justify-center text-gray-500 hover:text-primary-500 transition-colors duration-200">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
						</svg>
						<span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
					</button>
				</div>
				
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="search-categories mt-4 flex items-center">
						<label class="inline-flex items-center mr-4">
							<input type="radio" name="post_type" value="any" class="form-radio" checked>
							<span class="ml-2"><?php esc_html_e( 'All', 'aqualuxe' ); ?></span>
						</label>
						<label class="inline-flex items-center mr-4">
							<input type="radio" name="post_type" value="post" class="form-radio">
							<span class="ml-2"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></span>
						</label>
						<label class="inline-flex items-center">
							<input type="radio" name="post_type" value="product" class="form-radio">
							<span class="ml-2"><?php esc_html_e( 'Products', 'aqualuxe' ); ?></span>
						</label>
					</div>
				<?php endif; ?>
			</form>
			
			<?php if ( aqualuxe_get_option( 'show_search_suggestions', true ) ) : ?>
				<div class="search-suggestions mt-6">
					<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2"><?php esc_html_e( 'Popular Searches:', 'aqualuxe' ); ?></h4>
					<div class="flex flex-wrap gap-2">
						<?php
						// Get popular search terms or predefined ones
						$popular_searches = aqualuxe_get_option( 'popular_searches', array( 'Tropical Fish', 'Aquarium', 'Plants', 'Filters', 'Food' ) );
						
						if ( ! empty( $popular_searches ) && is_array( $popular_searches ) ) :
							foreach ( $popular_searches as $search ) :
								if ( ! empty( $search ) ) :
									?>
									<a href="<?php echo esc_url( add_query_arg( 's', urlencode( $search ), home_url( '/' ) ) ); ?>" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full text-sm transition-colors duration-200">
										<?php echo esc_html( $search ); ?>
									</a>
									<?php
								endif;
							endforeach;
						endif;
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>