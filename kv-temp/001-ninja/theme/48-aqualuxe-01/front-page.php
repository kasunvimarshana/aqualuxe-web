<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">
		<?php
		// Hero Section
		get_template_part( 'template-parts/components/hero', 'section', array(
			'title'       => esc_html__( 'Discover the Beauty of Aquatic Life', 'aqualuxe' ),
			'subtitle'    => esc_html__( 'Premium Aquarium Solutions', 'aqualuxe' ),
			'description' => esc_html__( 'Experience the elegance and tranquility of underwater ecosystems with our luxury aquarium products and services.', 'aqualuxe' ),
			'image'       => get_template_directory_uri() . '/assets/images/hero-bg.jpg',
			'overlay'     => 'rgba(0, 60, 120, 0.7)',
			'text_color'  => 'light',
			'buttons'     => array(
				array(
					'text'  => esc_html__( 'Shop Now', 'aqualuxe' ),
					'url'   => aqualuxe_is_woocommerce_active() ? esc_url( wc_get_page_permalink( 'shop' ) ) : '#',
					'style' => 'primary',
				),
				array(
					'text'  => esc_html__( 'Learn More', 'aqualuxe' ),
					'url'   => '#',
					'style' => 'outline',
				),
			),
			'height'      => 'large',
			'alignment'   => 'center',
		) );
		
		// Featured Categories Section
		if ( aqualuxe_is_woocommerce_active() ) {
			?>
			<section class="featured-categories py-12 md:py-16">
				<div class="container mx-auto px-4">
					<div class="section-header text-center mb-8 md:mb-12">
						<h2 class="section-title text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
							<?php esc_html_e( 'Explore Our Collections', 'aqualuxe' ); ?>
						</h2>
						<div class="section-description max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
							<?php esc_html_e( 'Discover our carefully curated selection of premium aquarium products designed for enthusiasts and professionals alike.', 'aqualuxe' ); ?>
						</div>
					</div>
					
					<div class="featured-categories-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php
						$featured_categories = array(
							array(
								'title' => esc_html__( 'Aquariums', 'aqualuxe' ),
								'description' => esc_html__( 'Premium tanks for every space', 'aqualuxe' ),
								'image' => get_template_directory_uri() . '/assets/images/category-aquariums.jpg',
								'link' => '#',
							),
							array(
								'title' => esc_html__( 'Equipment', 'aqualuxe' ),
								'description' => esc_html__( 'Professional-grade tools', 'aqualuxe' ),
								'image' => get_template_directory_uri() . '/assets/images/category-equipment.jpg',
								'link' => '#',
							),
							array(
								'title' => esc_html__( 'Decorations', 'aqualuxe' ),
								'description' => esc_html__( 'Elegant aquascaping elements', 'aqualuxe' ),
								'image' => get_template_directory_uri() . '/assets/images/category-decorations.jpg',
								'link' => '#',
							),
						);
						
						foreach ( $featured_categories as $category ) :
						?>
							<div class="featured-category relative rounded-lg overflow-hidden shadow-md group">
								<div class="featured-category-image aspect-w-16 aspect-h-9">
									<img src="<?php echo esc_url( $category['image'] ); ?>" alt="<?php echo esc_attr( $category['title'] ); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
									<div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
								</div>
								<div class="featured-category-content absolute bottom-0 left-0 w-full p-6 text-white">
									<h3 class="featured-category-title text-xl md:text-2xl font-serif font-bold mb-2">
										<?php echo esc_html( $category['title'] ); ?>
									</h3>
									<p class="featured-category-description text-sm text-white/80 mb-4">
										<?php echo esc_html( $category['description'] ); ?>
									</p>
									<a href="<?php echo esc_url( $category['link'] ); ?>" class="inline-flex items-center text-white hover:text-primary-light transition-colors">
										<?php esc_html_e( 'Explore', 'aqualuxe' ); ?>
										<i class="fas fa-arrow-right ml-2"></i>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
		
		// Featured Products Section
		if ( aqualuxe_is_woocommerce_active() ) {
			get_template_part( 'template-parts/components/featured', 'products', array(
				'title'           => esc_html__( 'Featured Products', 'aqualuxe' ),
				'subtitle'        => esc_html__( 'Our Selection', 'aqualuxe' ),
				'description'     => esc_html__( 'Discover our most popular aquarium products, carefully selected for quality and performance.', 'aqualuxe' ),
				'number'          => 4,
				'columns'         => 4,
				'featured_only'   => true,
				'view_more_text'  => esc_html__( 'View All Products', 'aqualuxe' ),
				'view_more_url'   => wc_get_page_permalink( 'shop' ),
			) );
		}
		
		// About Section
		?>
		<section class="about-section py-12 md:py-16 bg-light-dark dark:bg-dark-light">
			<div class="container mx-auto px-4">
				<div class="flex flex-wrap items-center -mx-4">
					<div class="w-full lg:w-1/2 px-4 mb-8 lg:mb-0">
						<div class="about-image relative">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-image.jpg' ); ?>" alt="<?php esc_attr_e( 'About AquaLuxe', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full">
							<div class="about-experience absolute -bottom-6 -right-6 bg-primary text-white p-6 rounded-lg shadow-lg">
								<div class="text-4xl font-bold mb-1">15+</div>
								<div class="text-sm"><?php esc_html_e( 'Years of Experience', 'aqualuxe' ); ?></div>
							</div>
						</div>
					</div>
					
					<div class="w-full lg:w-1/2 px-4">
						<div class="about-content">
							<div class="section-subtitle mb-2">
								<span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
									<?php esc_html_e( 'About Us', 'aqualuxe' ); ?>
								</span>
							</div>
							
							<h2 class="section-title text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
								<?php esc_html_e( 'Bringing Aquatic Excellence to Your Space', 'aqualuxe' ); ?>
							</h2>
							
							<div class="section-description prose dark:prose-invert mb-6">
								<p><?php esc_html_e( 'AquaLuxe was founded with a passion for aquatic life and a commitment to excellence. For over 15 years, we have been providing premium aquarium solutions to enthusiasts and professionals worldwide.', 'aqualuxe' ); ?></p>
								<p><?php esc_html_e( 'Our team of experts is dedicated to helping you create and maintain stunning aquatic ecosystems that bring beauty and tranquility to any space.', 'aqualuxe' ); ?></p>
							</div>
							
							<div class="about-features grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
								<div class="about-feature flex items-start">
									<div class="about-feature-icon text-primary mr-4">
										<i class="fas fa-check-circle text-xl"></i>
									</div>
									<div class="about-feature-content">
										<h4 class="text-lg font-bold mb-1"><?php esc_html_e( 'Premium Quality', 'aqualuxe' ); ?></h4>
										<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Only the finest materials and craftsmanship', 'aqualuxe' ); ?></p>
									</div>
								</div>
								
								<div class="about-feature flex items-start">
									<div class="about-feature-icon text-primary mr-4">
										<i class="fas fa-check-circle text-xl"></i>
									</div>
									<div class="about-feature-content">
										<h4 class="text-lg font-bold mb-1"><?php esc_html_e( 'Expert Support', 'aqualuxe' ); ?></h4>
										<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Guidance from experienced professionals', 'aqualuxe' ); ?></p>
									</div>
								</div>
								
								<div class="about-feature flex items-start">
									<div class="about-feature-icon text-primary mr-4">
										<i class="fas fa-check-circle text-xl"></i>
									</div>
									<div class="about-feature-content">
										<h4 class="text-lg font-bold mb-1"><?php esc_html_e( 'Sustainable Practices', 'aqualuxe' ); ?></h4>
										<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Eco-friendly products and operations', 'aqualuxe' ); ?></p>
									</div>
								</div>
								
								<div class="about-feature flex items-start">
									<div class="about-feature-icon text-primary mr-4">
										<i class="fas fa-check-circle text-xl"></i>
									</div>
									<div class="about-feature-content">
										<h4 class="text-lg font-bold mb-1"><?php esc_html_e( 'Global Shipping', 'aqualuxe' ); ?></h4>
										<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Delivery to customers worldwide', 'aqualuxe' ); ?></p>
									</div>
								</div>
							</div>
							
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about-us' ) ) ); ?>" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
								<?php esc_html_e( 'Learn More About Us', 'aqualuxe' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<?php
		// Testimonials Section
		get_template_part( 'template-parts/components/testimonials', null, array(
			'title'       => esc_html__( 'What Our Customers Say', 'aqualuxe' ),
			'subtitle'    => esc_html__( 'Testimonials', 'aqualuxe' ),
			'description' => esc_html__( 'Hear from our satisfied customers about their experience with AquaLuxe products and services.', 'aqualuxe' ),
			'style'       => 'slider',
			'number'      => 3,
			'background'  => 'image',
			'image_url'   => get_template_directory_uri() . '/assets/images/testimonials-bg.jpg',
		) );
		
		// Latest Blog Posts Section
		?>
		<section class="latest-posts py-12 md:py-16">
			<div class="container mx-auto px-4">
				<div class="section-header text-center mb-8 md:mb-12">
					<div class="section-subtitle mb-2">
						<span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
							<?php esc_html_e( 'Our Blog', 'aqualuxe' ); ?>
						</span>
					</div>
					
					<h2 class="section-title text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
						<?php esc_html_e( 'Latest Articles & Tips', 'aqualuxe' ); ?>
					</h2>
					
					<div class="section-description max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
						<?php esc_html_e( 'Stay updated with the latest news, tips, and insights from the world of aquariums and aquatic life.', 'aqualuxe' ); ?>
					</div>
				</div>
				
				<div class="latest-posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php
					$latest_posts = new WP_Query( array(
						'post_type'      => 'post',
						'posts_per_page' => 3,
						'orderby'        => 'date',
						'order'          => 'DESC',
					) );
					
					if ( $latest_posts->have_posts() ) :
						while ( $latest_posts->have_posts() ) :
							$latest_posts->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('latest-post bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1'); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="post-thumbnail">
										<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
											<?php the_post_thumbnail('medium', array('class' => 'w-full h-auto transition-transform duration-500 hover:scale-105')); ?>
										</a>
									</div>
								<?php endif; ?>
								
								<div class="post-content p-6">
									<header class="post-header mb-4">
										<?php
										// Display categories
										$categories = get_the_category();
										if ( ! empty( $categories ) ) :
										?>
											<div class="post-categories mb-2">
												<?php
												$category = $categories[0];
												echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block px-3 py-1 text-xs font-medium bg-primary text-white rounded-full hover:bg-primary-dark transition-colors">' . esc_html( $category->name ) . '</a>';
												?>
											</div>
										<?php endif; ?>
										
										<h3 class="post-title text-xl font-serif font-bold mb-2">
											<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
										</h3>
										
										<div class="post-meta text-sm text-gray-600 dark:text-gray-400">
											<span class="post-date">
												<i class="fas fa-calendar-alt mr-1"></i>
												<?php echo get_the_date(); ?>
											</span>
										</div>
									</header>
									
									<div class="post-excerpt prose dark:prose-invert max-w-none mb-4">
										<?php the_excerpt(); ?>
									</div>
									
									<div class="post-footer">
										<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors">
											<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
											<i class="fas fa-arrow-right ml-2"></i>
										</a>
									</div>
								</div>
							</article>
							<?php
						endwhile;
						wp_reset_postdata();
					else :
						?>
						<div class="col-span-3 text-center">
							<p><?php esc_html_e( 'No posts found.', 'aqualuxe' ); ?></p>
						</div>
						<?php
					endif;
					?>
				</div>
				
				<div class="section-footer text-center mt-8">
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
						<?php esc_html_e( 'View All Posts', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		</section>
		
		<?php
		// Newsletter Section
		if ( aqualuxe_get_option( 'enable_newsletter', true ) ) :
		?>
		<section class="newsletter-section py-12 md:py-16 bg-primary text-white">
			<div class="container mx-auto px-4">
				<div class="newsletter-content max-w-3xl mx-auto text-center">
					<h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
						<?php esc_html_e( 'Subscribe to Our Newsletter', 'aqualuxe' ); ?>
					</h2>
					
					<p class="mb-6 text-white/80">
						<?php esc_html_e( 'Stay updated with our latest products, special offers, and expert aquarium care tips.', 'aqualuxe' ); ?>
					</p>
					
					<div class="newsletter-form">
						<form action="#" method="post" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
							<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
							<button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-dark font-medium rounded-lg transition-colors">
								<?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>
		
	</main><!-- #main -->

<?php
get_footer();