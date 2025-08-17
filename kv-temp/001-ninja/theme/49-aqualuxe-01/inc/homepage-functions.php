<?php
/**
 * Homepage template functions
 *
 * Functions for the homepage template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Homepage Hero Section
 */
function aqualuxe_homepage_hero_section() {
	// Check if the hero section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_hero_enable', true ) ) {
		return;
	}

	// Get hero settings
	$hero_title = get_theme_mod( 'aqualuxe_homepage_hero_title', __( 'Welcome to AquaLuxe', 'aqualuxe' ) );
	$hero_subtitle = get_theme_mod( 'aqualuxe_homepage_hero_subtitle', __( 'Discover our exclusive collection of premium products', 'aqualuxe' ) );
	$hero_button_text = get_theme_mod( 'aqualuxe_homepage_hero_button_text', __( 'Shop Now', 'aqualuxe' ) );
	$hero_button_url = get_theme_mod( 'aqualuxe_homepage_hero_button_url', '#' );
	$hero_image = get_theme_mod( 'aqualuxe_homepage_hero_image' );
	$hero_overlay = get_theme_mod( 'aqualuxe_homepage_hero_overlay', true );
	$hero_height = get_theme_mod( 'aqualuxe_homepage_hero_height', 'medium' );
	$hero_text_align = get_theme_mod( 'aqualuxe_homepage_hero_text_align', 'center' );
	
	// Set height class based on setting
	$height_class = '';
	switch ( $hero_height ) {
		case 'small':
			$height_class = 'min-h-[400px]';
			break;
		case 'medium':
			$height_class = 'min-h-[600px]';
			break;
		case 'large':
			$height_class = 'min-h-[800px]';
			break;
		default:
			$height_class = 'min-h-[600px]';
	}
	
	// Set text alignment class
	$text_align_class = '';
	switch ( $hero_text_align ) {
		case 'left':
			$text_align_class = 'text-left items-start';
			break;
		case 'center':
			$text_align_class = 'text-center items-center';
			break;
		case 'right':
			$text_align_class = 'text-right items-end';
			break;
		default:
			$text_align_class = 'text-center items-center';
	}
	
	// Set background image style
	$bg_style = '';
	if ( $hero_image ) {
		$bg_style = 'background-image: url(' . esc_url( $hero_image ) . '); background-size: cover; background-position: center;';
	}
	?>
	<section class="homepage-hero relative <?php echo esc_attr( $height_class ); ?>" style="<?php echo esc_attr( $bg_style ); ?>">
		<?php if ( $hero_overlay ) : ?>
			<div class="absolute inset-0 bg-black bg-opacity-40"></div>
		<?php endif; ?>
		
		<div class="container mx-auto px-4 relative z-10 h-full">
			<div class="flex flex-col justify-center <?php echo esc_attr( $text_align_class ); ?> h-full">
				<?php if ( $hero_title ) : ?>
					<h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4"><?php echo esc_html( $hero_title ); ?></h1>
				<?php endif; ?>
				
				<?php if ( $hero_subtitle ) : ?>
					<p class="hero-subtitle text-xl md:text-2xl text-white mb-8 max-w-3xl"><?php echo esc_html( $hero_subtitle ); ?></p>
				<?php endif; ?>
				
				<?php if ( $hero_button_text && $hero_button_url ) : ?>
					<a href="<?php echo esc_url( $hero_button_url ); ?>" class="hero-button inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<?php echo esc_html( $hero_button_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_before_homepage_content', 'aqualuxe_homepage_hero_section', 10 );

/**
 * Homepage Featured Categories
 */
function aqualuxe_homepage_featured_categories() {
	// Check if the featured categories section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_featured_categories_enable', true ) ) {
		return;
	}
	
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_featured_categories_title', __( 'Shop by Category', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_featured_categories_subtitle', __( 'Browse our popular categories', 'aqualuxe' ) );
	$category_ids = get_theme_mod( 'aqualuxe_homepage_featured_categories', array() );
	
	// If no categories are selected, get the first 4 categories
	if ( empty( $category_ids ) ) {
		$product_categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 4,
			'orderby'    => 'count',
			'order'      => 'DESC',
		) );
		
		if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
			$category_ids = wp_list_pluck( $product_categories, 'term_id' );
		}
	}
	
	// If still no categories, return
	if ( empty( $category_ids ) ) {
		return;
	}
	?>
	<section class="homepage-featured-categories py-16">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
				<?php
				foreach ( $category_ids as $category_id ) {
					$category = get_term( $category_id, 'product_cat' );
					
					if ( ! $category || is_wp_error( $category ) ) {
						continue;
					}
					
					$category_link = get_term_link( $category, 'product_cat' );
					$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
					$image_url = $image ? $image[0] : wc_placeholder_img_src( 'medium' );
					?>
					<a href="<?php echo esc_url( $category_link ); ?>" class="category-card group block relative rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
						<div class="category-image aspect-w-16 aspect-h-9">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
						</div>
						<div class="category-overlay absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
							<div>
								<h3 class="category-name text-xl font-bold text-white mb-1"><?php echo esc_html( $category->name ); ?></h3>
								<?php if ( $category->count > 0 ) : ?>
									<span class="category-count text-sm text-white/80">
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
					</a>
					<?php
				}
				?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_featured_categories', 10 );

/**
 * Homepage Featured Products
 */
function aqualuxe_homepage_featured_products() {
	// Check if the featured products section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_featured_products_enable', true ) ) {
		return;
	}
	
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_featured_products_subtitle', __( 'Our handpicked selection of premium products', 'aqualuxe' ) );
	$product_ids = get_theme_mod( 'aqualuxe_homepage_featured_products', array() );
	$products_count = get_theme_mod( 'aqualuxe_homepage_featured_products_count', 8 );
	$products_columns = get_theme_mod( 'aqualuxe_homepage_featured_products_columns', 4 );
	
	// Set up the query args
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $products_count,
	);
	
	// If specific products are selected
	if ( ! empty( $product_ids ) ) {
		$args['post__in'] = $product_ids;
		$args['orderby'] = 'post__in';
	} else {
		// Otherwise, get featured products
		$tax_query[] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'IN',
		);
		$args['tax_query'] = $tax_query;
	}
	
	// Run the query
	$products = new WP_Query( $args );
	
	if ( ! $products->have_posts() ) {
		return;
	}
	
	// Set column classes based on setting
	$column_classes = '';
	switch ( $products_columns ) {
		case 2:
			$column_classes = 'md:grid-cols-2';
			break;
		case 3:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-3';
			break;
		case 4:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-4';
			break;
		case 5:
			$column_classes = 'md:grid-cols-3 lg:grid-cols-5';
			break;
		default:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-4';
	}
	?>
	<section class="homepage-featured-products py-16 bg-gray-50 dark:bg-gray-900">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="products grid grid-cols-1 <?php echo esc_attr( $column_classes ); ?> gap-6">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			
			<?php if ( get_theme_mod( 'aqualuxe_homepage_featured_products_button_enable', true ) ) : ?>
				<div class="text-center mt-12">
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_homepage_featured_products_button_text', __( 'View All Products', 'aqualuxe' ) ) ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_featured_products', 20 );

/**
 * Homepage Banner Section
 */
function aqualuxe_homepage_banner_section() {
	// Check if the banner section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_banner_enable', true ) ) {
		return;
	}
	
	// Get banner settings
	$banner_title = get_theme_mod( 'aqualuxe_homepage_banner_title', __( 'Special Offer', 'aqualuxe' ) );
	$banner_subtitle = get_theme_mod( 'aqualuxe_homepage_banner_subtitle', __( 'Get 20% off on selected items', 'aqualuxe' ) );
	$banner_text = get_theme_mod( 'aqualuxe_homepage_banner_text', __( 'Limited time offer. Don\'t miss out!', 'aqualuxe' ) );
	$banner_button_text = get_theme_mod( 'aqualuxe_homepage_banner_button_text', __( 'Shop Now', 'aqualuxe' ) );
	$banner_button_url = get_theme_mod( 'aqualuxe_homepage_banner_button_url', '#' );
	$banner_image = get_theme_mod( 'aqualuxe_homepage_banner_image' );
	$banner_overlay = get_theme_mod( 'aqualuxe_homepage_banner_overlay', true );
	$banner_text_align = get_theme_mod( 'aqualuxe_homepage_banner_text_align', 'left' );
	
	// Set text alignment class
	$text_align_class = '';
	switch ( $banner_text_align ) {
		case 'left':
			$text_align_class = 'text-left items-start';
			break;
		case 'center':
			$text_align_class = 'text-center items-center';
			break;
		case 'right':
			$text_align_class = 'text-right items-end';
			break;
		default:
			$text_align_class = 'text-left items-start';
	}
	
	// Set background image style
	$bg_style = '';
	if ( $banner_image ) {
		$bg_style = 'background-image: url(' . esc_url( $banner_image ) . '); background-size: cover; background-position: center;';
	}
	?>
	<section class="homepage-banner relative py-20" style="<?php echo esc_attr( $bg_style ); ?>">
		<?php if ( $banner_overlay ) : ?>
			<div class="absolute inset-0 bg-black bg-opacity-50"></div>
		<?php endif; ?>
		
		<div class="container mx-auto px-4 relative z-10">
			<div class="max-w-lg <?php echo esc_attr( $text_align_class ); ?>">
				<?php if ( $banner_title ) : ?>
					<h2 class="banner-title text-3xl md:text-4xl font-bold text-white mb-4"><?php echo esc_html( $banner_title ); ?></h2>
				<?php endif; ?>
				
				<?php if ( $banner_subtitle ) : ?>
					<p class="banner-subtitle text-xl text-white mb-4"><?php echo esc_html( $banner_subtitle ); ?></p>
				<?php endif; ?>
				
				<?php if ( $banner_text ) : ?>
					<p class="banner-text text-white mb-8"><?php echo esc_html( $banner_text ); ?></p>
				<?php endif; ?>
				
				<?php if ( $banner_button_text && $banner_button_url ) : ?>
					<a href="<?php echo esc_url( $banner_button_url ); ?>" class="banner-button inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<?php echo esc_html( $banner_button_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_banner_section', 30 );

/**
 * Homepage New Arrivals
 */
function aqualuxe_homepage_new_arrivals() {
	// Check if the new arrivals section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_new_arrivals_enable', true ) ) {
		return;
	}
	
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_new_arrivals_title', __( 'New Arrivals', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_new_arrivals_subtitle', __( 'Check out our latest products', 'aqualuxe' ) );
	$products_count = get_theme_mod( 'aqualuxe_homepage_new_arrivals_count', 8 );
	$products_columns = get_theme_mod( 'aqualuxe_homepage_new_arrivals_columns', 4 );
	
	// Set up the query args
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $products_count,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);
	
	// Run the query
	$products = new WP_Query( $args );
	
	if ( ! $products->have_posts() ) {
		return;
	}
	
	// Set column classes based on setting
	$column_classes = '';
	switch ( $products_columns ) {
		case 2:
			$column_classes = 'md:grid-cols-2';
			break;
		case 3:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-3';
			break;
		case 4:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-4';
			break;
		case 5:
			$column_classes = 'md:grid-cols-3 lg:grid-cols-5';
			break;
		default:
			$column_classes = 'md:grid-cols-2 lg:grid-cols-4';
	}
	?>
	<section class="homepage-new-arrivals py-16">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="products grid grid-cols-1 <?php echo esc_attr( $column_classes ); ?> gap-6">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			
			<?php if ( get_theme_mod( 'aqualuxe_homepage_new_arrivals_button_enable', true ) ) : ?>
				<div class="text-center mt-12">
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_homepage_new_arrivals_button_text', __( 'View All Products', 'aqualuxe' ) ) ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_new_arrivals', 40 );

/**
 * Homepage Testimonials
 */
function aqualuxe_homepage_testimonials() {
	// Check if the testimonials section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_testimonials_enable', true ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_testimonials_subtitle', __( 'Read testimonials from our satisfied customers', 'aqualuxe' ) );
	
	// Get testimonials
	$testimonials = get_theme_mod( 'aqualuxe_homepage_testimonials', array() );
	
	// If no testimonials, use default ones
	if ( empty( $testimonials ) ) {
		$testimonials = array(
			array(
				'name'    => __( 'John Doe', 'aqualuxe' ),
				'role'    => __( 'Customer', 'aqualuxe' ),
				'content' => __( 'I\'m extremely satisfied with the quality of products and the excellent customer service. Will definitely shop here again!', 'aqualuxe' ),
				'rating'  => 5,
			),
			array(
				'name'    => __( 'Jane Smith', 'aqualuxe' ),
				'role'    => __( 'Customer', 'aqualuxe' ),
				'content' => __( 'The products exceeded my expectations. Fast shipping and beautiful packaging. Highly recommended!', 'aqualuxe' ),
				'rating'  => 5,
			),
			array(
				'name'    => __( 'Michael Johnson', 'aqualuxe' ),
				'role'    => __( 'Customer', 'aqualuxe' ),
				'content' => __( 'Great experience shopping here. The website is easy to navigate and the products are top-notch quality.', 'aqualuxe' ),
				'rating'  => 4,
			),
		);
	}
	
	if ( empty( $testimonials ) ) {
		return;
	}
	?>
	<section class="homepage-testimonials py-16 bg-gray-50 dark:bg-gray-900">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="testimonials-slider grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ( $testimonials as $testimonial ) : ?>
					<div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
						<?php if ( ! empty( $testimonial['rating'] ) ) : ?>
							<div class="testimonial-rating flex text-yellow-400 mb-4">
								<?php
								$rating = intval( $testimonial['rating'] );
								for ( $i = 1; $i <= 5; $i++ ) {
									if ( $i <= $rating ) {
										echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
											<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
										</svg>';
									} else {
										echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
										</svg>';
									}
								}
								?>
							</div>
						<?php endif; ?>
						
						<?php if ( ! empty( $testimonial['content'] ) ) : ?>
							<div class="testimonial-content mb-6">
								<p class="text-gray-700 dark:text-gray-300"><?php echo esc_html( $testimonial['content'] ); ?></p>
							</div>
						<?php endif; ?>
						
						<div class="testimonial-author flex items-center">
							<?php if ( ! empty( $testimonial['image'] ) ) : ?>
								<div class="testimonial-author-image mr-3">
									<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-10 h-10 rounded-full object-cover">
								</div>
							<?php endif; ?>
							
							<div class="testimonial-author-info">
								<?php if ( ! empty( $testimonial['name'] ) ) : ?>
									<h4 class="testimonial-author-name font-medium"><?php echo esc_html( $testimonial['name'] ); ?></h4>
								<?php endif; ?>
								
								<?php if ( ! empty( $testimonial['role'] ) ) : ?>
									<p class="testimonial-author-role text-sm text-gray-600 dark:text-gray-400"><?php echo esc_html( $testimonial['role'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_testimonials', 50 );

/**
 * Homepage Blog Posts
 */
function aqualuxe_homepage_blog_posts() {
	// Check if the blog posts section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_blog_enable', true ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_blog_title', __( 'Latest News', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_blog_subtitle', __( 'Stay updated with our latest articles and news', 'aqualuxe' ) );
	$posts_count = get_theme_mod( 'aqualuxe_homepage_blog_count', 3 );
	
	// Set up the query args
	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $posts_count,
	);
	
	// Run the query
	$posts = new WP_Query( $args );
	
	if ( ! $posts->have_posts() ) {
		return;
	}
	?>
	<section class="homepage-blog py-16">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="blog-posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php
				while ( $posts->have_posts() ) :
					$posts->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block">
								<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
							</a>
						<?php endif; ?>
						
						<div class="p-6">
							<header class="entry-header mb-4">
								<?php the_title( '<h3 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">', '</a></h3>' ); ?>
								
								<div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
									<?php
									echo '<span class="posted-on">' . esc_html__( 'Posted on ', 'aqualuxe' ) . '<time class="entry-date published" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time></span>';
									?>
								</div>
							</header>
							
							<div class="entry-content mb-4">
								<?php the_excerpt(); ?>
							</div>
							
							<footer class="entry-footer">
								<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
									<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</a>
							</footer>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			
			<?php if ( get_theme_mod( 'aqualuxe_homepage_blog_button_enable', true ) ) : ?>
				<div class="text-center mt-12">
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_homepage_blog_button_text', __( 'View All Posts', 'aqualuxe' ) ) ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_blog_posts', 60 );

/**
 * Homepage Brands Section
 */
function aqualuxe_homepage_brands_section() {
	// Check if the brands section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_brands_enable', true ) ) {
		return;
	}
	
	// Get section settings
	$section_title = get_theme_mod( 'aqualuxe_homepage_brands_title', __( 'Our Brands', 'aqualuxe' ) );
	$section_subtitle = get_theme_mod( 'aqualuxe_homepage_brands_subtitle', __( 'We work with the best brands in the industry', 'aqualuxe' ) );
	
	// Get brands
	$brands = get_theme_mod( 'aqualuxe_homepage_brands', array() );
	
	if ( empty( $brands ) ) {
		return;
	}
	?>
	<section class="homepage-brands py-16 bg-gray-50 dark:bg-gray-900">
		<div class="container mx-auto px-4">
			<?php if ( $section_title || $section_subtitle ) : ?>
				<div class="section-header text-center mb-12">
					<?php if ( $section_title ) : ?>
						<h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>
					
					<?php if ( $section_subtitle ) : ?>
						<p class="section-subtitle text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $section_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<div class="brands-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
				<?php foreach ( $brands as $brand ) : ?>
					<?php if ( ! empty( $brand['image'] ) ) : ?>
						<div class="brand-item flex items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
							<?php if ( ! empty( $brand['url'] ) ) : ?>
								<a href="<?php echo esc_url( $brand['url'] ); ?>" target="_blank" rel="noopener noreferrer">
									<img src="<?php echo esc_url( $brand['image'] ); ?>" alt="<?php echo ! empty( $brand['name'] ) ? esc_attr( $brand['name'] ) : esc_attr__( 'Brand Logo', 'aqualuxe' ); ?>" class="max-h-16 w-auto">
								</a>
							<?php else : ?>
								<img src="<?php echo esc_url( $brand['image'] ); ?>" alt="<?php echo ! empty( $brand['name'] ) ? esc_attr( $brand['name'] ) : esc_attr__( 'Brand Logo', 'aqualuxe' ); ?>" class="max-h-16 w-auto">
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_brands_section', 70 );

/**
 * Homepage Newsletter Section
 */
function aqualuxe_homepage_newsletter() {
	// Check if the newsletter section is enabled
	if ( ! get_theme_mod( 'aqualuxe_homepage_newsletter_enable', true ) ) {
		return;
	}
	
	// Get newsletter settings
	$newsletter_title = get_theme_mod( 'aqualuxe_homepage_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
	$newsletter_subtitle = get_theme_mod( 'aqualuxe_homepage_newsletter_subtitle', __( 'Stay updated with our latest news and offers', 'aqualuxe' ) );
	$newsletter_button_text = get_theme_mod( 'aqualuxe_homepage_newsletter_button_text', __( 'Subscribe', 'aqualuxe' ) );
	$newsletter_privacy_text = get_theme_mod( 'aqualuxe_homepage_newsletter_privacy_text', __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing emails.', 'aqualuxe' ) );
	?>
	<section class="homepage-newsletter py-16 bg-primary-600 text-white">
		<div class="container mx-auto px-4">
			<div class="max-w-3xl mx-auto text-center">
				<?php if ( $newsletter_title ) : ?>
					<h2 class="newsletter-title text-3xl font-bold mb-4"><?php echo esc_html( $newsletter_title ); ?></h2>
				<?php endif; ?>
				
				<?php if ( $newsletter_subtitle ) : ?>
					<p class="newsletter-subtitle text-lg mb-8"><?php echo esc_html( $newsletter_subtitle ); ?></p>
				<?php endif; ?>
				
				<form class="newsletter-form flex flex-col md:flex-row gap-4 justify-center">
					<input type="email" class="newsletter-email flex-grow px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" required>
					<button type="submit" class="newsletter-submit px-6 py-3 bg-white text-primary-600 hover:bg-gray-100 rounded-md font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600">
						<?php echo esc_html( $newsletter_button_text ); ?>
					</button>
				</form>
				
				<?php if ( $newsletter_privacy_text ) : ?>
					<p class="newsletter-privacy-text text-sm mt-4 text-white text-opacity-80"><?php echo esc_html( $newsletter_privacy_text ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}
add_action( 'aqualuxe_homepage_sections', 'aqualuxe_homepage_newsletter', 80 );