<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main" role="main">
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="hero-background absolute inset-0 z-0">
            <?php 
            $hero_image = get_theme_mod( 'aqualuxe_hero_image', AQUALUXE_THEME_URI . '/assets/src/images/hero-bg.jpg' );
            if ( $hero_image ) : ?>
                <img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Hero Background', 'aqualuxe' ); ?>" class="w-full h-full object-cover">
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/70 to-primary-600/50"></div>
        </div>
        
        <div class="hero-content relative z-10 container text-center text-white">
            <h1 class="hero-title text-5xl md:text-7xl font-serif font-bold mb-6 animate-fade-in-up">
                <?php echo esc_html( get_theme_mod( 'aqualuxe_hero_title', __( 'Bringing Elegance to Aquatic Life', 'aqualuxe' ) ) ); ?>
            </h1>
            
            <p class="hero-subtitle text-xl md:text-2xl mb-8 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
                <?php echo esc_html( get_theme_mod( 'aqualuxe_hero_subtitle', __( 'Discover premium aquatic solutions, rare species, and expert care services for your aquatic paradise.', 'aqualuxe' ) ) ); ?>
            </p>
            
            <div class="hero-buttons flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.4s;">
                <a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_hero_primary_link', '#shop' ) ); ?>" class="btn btn-primary btn-lg">
                    <?php echo esc_html( get_theme_mod( 'aqualuxe_hero_primary_text', __( 'Shop Now', 'aqualuxe' ) ) ); ?>
                </a>
                <a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_hero_secondary_link', '#services' ) ); ?>" class="btn btn-outline btn-lg text-white border-white hover:bg-white hover:text-primary-600">
                    <?php echo esc_html( get_theme_mod( 'aqualuxe_hero_secondary_text', __( 'Our Services', 'aqualuxe' ) ) ); ?>
                </a>
            </div>
        </div>
        
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Featured Products Section -->
    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
    <section id="featured-products" class="section bg-gray-50 dark:bg-gray-900">
        <div class="container">
            <div class="section-header text-center mb-12">
                <h2 class="text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e( 'Discover our carefully curated selection of premium aquatic life and equipment.', 'aqualuxe' ); ?>
                </p>
            </div>
            
            <div class="featured-products-slider swiper">
                <div class="swiper-wrapper">
                    <?php
                    $featured_products = wc_get_featured_product_ids();
                    if ( ! empty( $featured_products ) ) {
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => 8,
                            'post__in'       => $featured_products,
                            'meta_query'     => WC()->query->get_meta_query(),
                        );
                        
                        $featured_query = new WP_Query( $args );
                        
                        if ( $featured_query->have_posts() ) {
                            while ( $featured_query->have_posts() ) {
                                $featured_query->the_post();
                                global $product;
                                ?>
                                <div class="swiper-slide">
                                    <div class="product-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 group">
                                        <div class="product-image relative overflow-hidden">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                                <?php echo woocommerce_get_product_thumbnail(); ?>
                                            </a>
                                            <?php if ( $product->is_on_sale() ) : ?>
                                                <span class="sale-badge absolute top-3 left-3 bg-red-600 text-white px-2 py-1 text-xs font-medium rounded">
                                                    <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                                                </span>
                                            <?php endif; ?>
                                            <div class="product-actions absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                <button class="quick-view-button btn btn-primary mr-2" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" data-quick-view>
                                                    <?php esc_html_e( 'Quick View', 'aqualuxe' ); ?>
                                                </button>
                                                <?php if ( is_user_logged_in() ) : ?>
                                                    <button class="wishlist-button btn btn-outline" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" data-wishlist-toggle>
                                                        ♡
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="product-info p-4">
                                            <h3 class="product-title text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                    <?php echo esc_html( get_the_title() ); ?>
                                                </a>
                                            </h3>
                                            <div class="product-price text-primary-600 dark:text-primary-400 font-semibold mb-3">
                                                <?php echo $product->get_price_html(); ?>
                                            </div>
                                            <?php woocommerce_template_loop_add_to_cart(); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }
                    }
                    ?>
                </div>
                <div class="swiper-pagination mt-8"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            
            <div class="text-center mt-8">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e( 'View All Products', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Services Section -->
    <section id="services" class="section">
        <div class="container">
            <div class="section-header text-center mb-12">
                <h2 class="text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e( 'Our Services', 'aqualuxe' ); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e( 'From aquarium design to maintenance, we provide comprehensive aquatic solutions.', 'aqualuxe' ); ?>
                </p>
            </div>
            
            <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $services_query = new WP_Query( array(
                    'post_type'      => 'service',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                ) );
                
                if ( $services_query->have_posts() ) :
                    while ( $services_query->have_posts() ) : $services_query->the_post();
                        $price = get_post_meta( get_the_ID(), '_service_price', true );
                        $duration = get_post_meta( get_the_ID(), '_service_duration', true );
                        ?>
                        <div class="service-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="service-image mb-4">
                                    <?php aqualuxe_post_thumbnail( 'medium' ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="service-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="service-excerpt text-gray-600 dark:text-gray-400 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="service-meta flex justify-between items-center mb-4 text-sm text-gray-500 dark:text-gray-400">
                                <?php if ( $price ) : ?>
                                    <span class="service-price font-semibold text-primary-600 dark:text-primary-400">
                                        <?php echo esc_html( $price ); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ( $duration ) : ?>
                                    <span class="service-duration">
                                        <?php echo esc_html( $duration ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary btn-sm w-full">
                                <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="col-span-full text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            <?php esc_html_e( 'No services found. Please check back later.', 'aqualuxe' ); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-8">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="btn btn-outline btn-lg">
                    <?php esc_html_e( 'View All Services', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section bg-gray-50 dark:bg-gray-900">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-content">
                    <h2 class="text-4xl font-serif font-bold text-gray-900 dark:text-white mb-6">
                        <?php echo esc_html( get_theme_mod( 'aqualuxe_about_title', __( 'About AquaLuxe', 'aqualuxe' ) ) ); ?>
                    </h2>
                    
                    <div class="about-text text-gray-600 dark:text-gray-400 mb-6 space-y-4">
                        <?php echo wp_kses_post( get_theme_mod( 'aqualuxe_about_content', __( 'AquaLuxe is dedicated to bringing elegance to aquatic life worldwide. With years of expertise in aquarium design, fish breeding, and aquatic ecosystem management, we provide premium solutions for hobbyists and professionals alike.', 'aqualuxe' ) ) ); ?>
                    </div>
                    
                    <div class="about-stats grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                        <div class="stat text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600 dark:text-primary-400">10+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Years Experience', 'aqualuxe' ); ?></div>
                        </div>
                        <div class="stat text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600 dark:text-primary-400">500+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Happy Customers', 'aqualuxe' ); ?></div>
                        </div>
                        <div class="stat text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600 dark:text-primary-400">50+</div>
                            <div class="stat-label text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Rare Species', 'aqualuxe' ); ?></div>
                        </div>
                    </div>
                    
                    <a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_about_link', '/about' ) ); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e( 'Learn More About Us', 'aqualuxe' ); ?>
                    </a>
                </div>
                
                <div class="about-image">
                    <?php 
                    $about_image = get_theme_mod( 'aqualuxe_about_image', AQUALUXE_THEME_URI . '/assets/src/images/about-us.jpg' );
                    if ( $about_image ) : ?>
                        <img src="<?php echo esc_url( $about_image ); ?>" alt="<?php esc_attr_e( 'About AquaLuxe', 'aqualuxe' ); ?>" class="rounded-lg shadow-lg">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section">
        <div class="container">
            <div class="section-header text-center mb-12">
                <h2 class="text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e( 'What Our Customers Say', 'aqualuxe' ); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e( 'Hear from our satisfied customers about their experiences with AquaLuxe.', 'aqualuxe' ); ?>
                </p>
            </div>
            
            <div class="testimonials-slider swiper">
                <div class="swiper-wrapper">
                    <?php
                    $testimonials_query = new WP_Query( array(
                        'post_type'      => 'testimonial',
                        'posts_per_page' => 6,
                        'post_status'    => 'publish',
                    ) );
                    
                    if ( $testimonials_query->have_posts() ) :
                        while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
                            $author = get_post_meta( get_the_ID(), '_testimonial_author', true );
                            $company = get_post_meta( get_the_ID(), '_testimonial_company', true );
                            $rating = get_post_meta( get_the_ID(), '_testimonial_rating', true );
                            ?>
                            <div class="swiper-slide">
                                <div class="testimonial-card bg-white dark:bg-gray-800 rounded-lg p-8 text-center shadow-md">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="testimonial-avatar w-16 h-16 rounded-full mx-auto mb-4 overflow-hidden">
                                            <?php aqualuxe_post_thumbnail( 'thumbnail' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $rating ) : ?>
                                        <div class="testimonial-rating flex justify-center mb-4">
                                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                                <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <blockquote class="testimonial-content text-gray-600 dark:text-gray-400 mb-6 italic">
                                        "<?php echo esc_html( get_the_content() ); ?>"
                                    </blockquote>
                                    
                                    <div class="testimonial-author">
                                        <?php if ( $author ) : ?>
                                            <div class="author-name font-semibold text-gray-900 dark:text-white">
                                                <?php echo esc_html( $author ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ( $company ) : ?>
                                            <div class="author-company text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo esc_html( $company ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                <div class="swiper-pagination mt-8"></div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="section bg-primary-600 text-white">
        <div class="container text-center">
            <h2 class="text-4xl font-serif font-bold mb-4">
                <?php esc_html_e( 'Stay Updated', 'aqualuxe' ); ?>
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                <?php esc_html_e( 'Subscribe to our newsletter for the latest aquatic news, care tips, and exclusive offers.', 'aqualuxe' ); ?>
            </p>
            
            <form class="newsletter-form max-w-md mx-auto flex gap-4" action="#" method="post">
                <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e( 'Enter your email', 'aqualuxe' ); ?>" class="flex-1 px-4 py-3 rounded-lg text-gray-900" required>
                <button type="submit" class="btn bg-white text-primary-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium">
                    <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                </button>
            </form>
        </div>
    </section>
</main>

<?php
get_footer();