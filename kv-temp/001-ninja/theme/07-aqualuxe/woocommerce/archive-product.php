<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header mb-8">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title text-3xl md:text-4xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<?php
// Display shop banner if enabled
if ( get_theme_mod( 'aqualuxe_enable_shop_banner', true ) && is_shop() ) :
	$banner_image = get_theme_mod( 'aqualuxe_shop_banner_image', '' );
	$banner_title = get_theme_mod( 'aqualuxe_shop_banner_title', esc_html__( 'Welcome to Our Shop', 'aqualuxe' ) );
	$banner_text = get_theme_mod( 'aqualuxe_shop_banner_text', esc_html__( 'Discover our premium selection of aquarium products and ornamental fish.', 'aqualuxe' ) );
	$banner_button_text = get_theme_mod( 'aqualuxe_shop_banner_button_text', esc_html__( 'View Featured Products', 'aqualuxe' ) );
	$banner_button_url = get_theme_mod( 'aqualuxe_shop_banner_button_url', '#featured-products' );
	
	if ( ! empty( $banner_image ) || ! empty( $banner_title ) || ! empty( $banner_text ) ) :
	?>
		<div class="shop-banner relative overflow-hidden rounded-lg mb-8 bg-gradient-to-r from-primary-900 to-primary-700 dark:from-primary-950 dark:to-primary-800">
			<div class="container mx-auto px-4 py-12 md:py-16 relative z-10">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
					<div class="shop-banner-content text-white">
						<?php if ( ! empty( $banner_title ) ) : ?>
							<h2 class="text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $banner_title ); ?></h2>
						<?php endif; ?>
						
						<?php if ( ! empty( $banner_text ) ) : ?>
							<p class="text-lg mb-6"><?php echo esc_html( $banner_text ); ?></p>
						<?php endif; ?>
						
						<?php if ( ! empty( $banner_button_text ) && ! empty( $banner_button_url ) ) : ?>
							<a href="<?php echo esc_url( $banner_button_url ); ?>" class="inline-flex items-center px-6 py-3 bg-white text-primary-700 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-medium">
								<?php echo esc_html( $banner_button_text ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
								</svg>
							</a>
						<?php endif; ?>
					</div>
					
					<?php if ( ! empty( $banner_image ) ) : ?>
						<div class="shop-banner-image hidden md:block">
							<img src="<?php echo esc_url( $banner_image ); ?>" alt="<?php echo esc_attr( $banner_title ); ?>" class="w-full h-auto rounded-lg shadow-lg">
						</div>
					<?php endif; ?>
				</div>
			</div>
			
			<?php if ( empty( $banner_image ) ) : ?>
				<div class="absolute inset-0 z-0 opacity-20">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute bottom-0 left-0 w-full h-full">
						<path fill="currentColor" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,202.7C672,203,768,181,864,181.3C960,181,1056,203,1152,202.7C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
					</svg>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php
// Display category grid if enabled
if ( get_theme_mod( 'aqualuxe_enable_category_grid', true ) && is_shop() ) :
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => get_theme_mod( 'aqualuxe_category_grid_count', 4 ),
	) );
	
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
	?>
		<div class="product-categories-grid mb-12">
			<h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6">
				<?php echo esc_html( get_theme_mod( 'aqualuxe_category_grid_title', esc_html__( 'Shop by Category', 'aqualuxe' ) ) ); ?>
			</h2>
			
			<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
				<?php foreach ( $categories as $category ) : ?>
					<?php
					$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$image = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'medium' ) : '';
					?>
					<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category-card relative overflow-hidden rounded-lg group">
						<div class="aspect-w-1 aspect-h-1">
							<?php if ( $image ) : ?>
								<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
							<?php else : ?>
								<div class="w-full h-full bg-gray-200 dark:bg-dark-700 flex items-center justify-center">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
								</div>
							<?php endif; ?>
							<div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
								<div class="text-white">
									<h3 class="text-lg font-medium"><?php echo esc_html( $category->name ); ?></h3>
									<?php if ( $category->count > 0 ) : ?>
										<span class="text-sm opacity-80">
											<?php
											printf(
												/* translators: %d: number of products */
												_n( '%d product', '%d products', $category->count, 'aqualuxe' ),
												$category->count
											);
											?>
										</span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	// Display filter sidebar if enabled
	if ( get_theme_mod( 'aqualuxe_enable_shop_filters', true ) && ( is_shop() || is_product_category() || is_product_tag() ) ) :
	?>
		<div class="shop-filters mb-6 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg">
			<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
				<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2 md:mb-0">
					<?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?>
				</h3>
				<button id="toggle-filters" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-300 flex items-center">
					<span class="show-filters-text"><?php esc_html_e( 'Show Filters', 'aqualuxe' ); ?></span>
					<span class="hide-filters-text hidden"><?php esc_html_e( 'Hide Filters', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 show-filters-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 hide-filters-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
					</svg>
				</button>
			</div>
			
			<div id="shop-filters-content" class="hidden pt-4 border-t border-gray-200 dark:border-dark-700">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<?php if ( is_active_sidebar( 'shop-filters' ) ) : ?>
						<?php dynamic_sidebar( 'shop-filters' ); ?>
					<?php else : ?>
						<div class="filter-widget">
							<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h4>
							<ul class="space-y-2">
								<?php
								$product_categories = get_terms( array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => true,
									'parent'     => 0,
								) );
								
								if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
									foreach ( $product_categories as $category ) {
										echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">' . esc_html( $category->name ) . '</a></li>';
									}
								}
								?>
							</ul>
						</div>
						
						<div class="filter-widget">
							<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Price Range', 'aqualuxe' ); ?></h4>
							<?php the_widget( 'WC_Widget_Price_Filter' ); ?>
						</div>
						
						<div class="filter-widget">
							<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Product Tags', 'aqualuxe' ); ?></h4>
							<div class="flex flex-wrap gap-2">
								<?php
								$product_tags = get_terms( array(
									'taxonomy'   => 'product_tag',
									'hide_empty' => true,
								) );
								
								if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) {
									foreach ( $product_tags as $tag ) {
										echo '<a href="' . esc_url( get_term_link( $tag ) ) . '" class="inline-block px-3 py-1 bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-300">' . esc_html( $tag->name ) . '</a>';
									}
								}
								?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if ( get_theme_mod( 'aqualuxe_enable_active_filters', true ) ) : ?>
					<div class="active-filters mt-6 pt-4 border-t border-gray-200 dark:border-dark-700">
						<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Active Filters', 'aqualuxe' ); ?></h4>
						<?php the_widget( 'WC_Widget_Layered_Nav_Filters' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

// Display featured products section if enabled
if ( get_theme_mod( 'aqualuxe_enable_featured_products', true ) && is_shop() ) :
	$featured_products_title = get_theme_mod( 'aqualuxe_featured_products_title', esc_html__( 'Featured Products', 'aqualuxe' ) );
	$featured_products_count = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
	
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $featured_products_count,
		'tax_query'           => array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			),
		),
	);
	
	$featured_products = new WP_Query( $args );
	
	if ( $featured_products->have_posts() ) :
	?>
		<div id="featured-products" class="featured-products-section mt-12 pt-8 border-t border-gray-200 dark:border-dark-700">
			<h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6">
				<?php echo esc_html( $featured_products_title ); ?>
			</h2>
			
			<div class="featured-products-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
				<?php
				while ( $featured_products->have_posts() ) :
					$featured_products->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				
				wp_reset_postdata();
				?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );