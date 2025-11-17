<?php
/**
 * The front page template file
 *
 * This is the template that displays the homepage
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center text-white overflow-hidden">
        <!-- Background Video/Image -->
        <div class="hero-background absolute inset-0 z-0">
            <?php if (get_theme_mod('hero_video_url')) : ?>
                <video class="w-full h-full object-cover" autoplay muted loop>
                    <source src="<?php echo esc_url(get_theme_mod('hero_video_url')); ?>" type="video/mp4">
                </video>
            <?php else : ?>
                <div class="hero-image w-full h-full bg-gradient-to-br from-primary-600 to-aqua-600" 
                     style="background-image: url('<?php echo esc_url(get_theme_mod('hero_background_image', aqualuxe_asset('/images/hero-bg.jpg'))); ?>'); background-size: cover; background-position: center;">
                </div>
            <?php endif; ?>
            <div class="hero-overlay absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="hero-content relative z-10 text-center max-w-4xl mx-auto px-4">
            <h1 class="hero-title text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up">
                <?php echo get_theme_mod('hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe')); ?>
            </h1>
            
            <p class="hero-subtitle text-xl md:text-2xl mb-8 text-gray-200 animate-fade-in-up" style="animation-delay: 0.3s;">
                <?php echo get_theme_mod('hero_subtitle', __('Globally renowned for premium aquatic products, expert services, and sustainable solutions', 'aqualuxe')); ?>
            </p>
            
            <div class="hero-actions flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.6s;">
                <a href="<?php echo get_theme_mod('hero_primary_button_url', aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') : '#services'); ?>" 
                   class="btn btn-primary btn-lg">
                    <?php echo get_theme_mod('hero_primary_button_text', __('Explore Products', 'aqualuxe')); ?>
                </a>
                <a href="<?php echo get_theme_mod('hero_secondary_button_url', '#about'); ?>" 
                   class="btn btn-outline btn-lg border-white text-white hover:bg-white hover:text-primary-900">
                    <?php echo get_theme_mod('hero_secondary_button_text', __('Learn More', 'aqualuxe')); ?>
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features-section section bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo get_theme_mod('features_title', __('Why Choose AquaLuxe', 'aqualuxe')); ?>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php echo get_theme_mod('features_subtitle', __('Discover what makes us the global leader in aquatic excellence', 'aqualuxe')); ?>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Feature 1 -->
                <div class="feature-item text-center group">
                    <div class="feature-icon mb-6">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors duration-300">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="feature-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        <?php echo get_theme_mod('feature_1_title', __('Premium Quality', 'aqualuxe')); ?>
                    </h3>
                    <p class="feature-description text-gray-600 dark:text-gray-300">
                        <?php echo get_theme_mod('feature_1_description', __('Curated selection of the finest aquatic products and livestock from trusted global sources.', 'aqualuxe')); ?>
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-item text-center group">
                    <div class="feature-icon mb-6">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors duration-300">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="feature-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        <?php echo get_theme_mod('feature_2_title', __('Expert Support', 'aqualuxe')); ?>
                    </h3>
                    <p class="feature-description text-gray-600 dark:text-gray-300">
                        <?php echo get_theme_mod('feature_2_description', __('Professional aquatic specialists providing personalized guidance and ongoing support.', 'aqualuxe')); ?>
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-item text-center group">
                    <div class="feature-icon mb-6">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors duration-300">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="feature-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        <?php echo get_theme_mod('feature_3_title', __('Global Shipping', 'aqualuxe')); ?>
                    </h3>
                    <p class="feature-description text-gray-600 dark:text-gray-300">
                        <?php echo get_theme_mod('feature_3_description', __('Secure worldwide delivery with specialized packaging for live aquatic specimens.', 'aqualuxe')); ?>
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-item text-center group">
                    <div class="feature-icon mb-6">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors duration-300">
                            <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="feature-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        <?php echo get_theme_mod('feature_4_title', __('Sustainability', 'aqualuxe')); ?>
                    </h3>
                    <p class="feature-description text-gray-600 dark:text-gray-300">
                        <?php echo get_theme_mod('feature_4_description', __('Committed to ethical sourcing and environmental conservation in all our practices.', 'aqualuxe')); ?>
                    </p>
                </div>
                
            </div>
        </div>
    </section>
    
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
    <!-- Featured Products Section -->
    <section id="featured-products" class="featured-products-section section bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo get_theme_mod('featured_products_title', __('Featured Products', 'aqualuxe')); ?>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php echo get_theme_mod('featured_products_subtitle', __('Discover our most popular and exclusive aquatic offerings', 'aqualuxe')); ?>
                </p>
            </div>
            
            <div class="featured-products-grid">
                <?php
                $featured_products = wc_get_featured_product_ids();
                if ($featured_products) {
                    $args = [
                        'post_type' => 'product',
                        'posts_per_page' => 8,
                        'post__in' => $featured_products,
                        'meta_query' => WC()->query->get_meta_query(),
                    ];
                    
                    $products = new WP_Query($args);
                    
                    if ($products->have_posts()) {
                        echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">';
                        
                        while ($products->have_posts()) {
                            $products->the_post();
                            wc_get_template_part('content', 'product');
                        }
                        
                        echo '</div>';
                        wp_reset_postdata();
                    }
                }
                ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn-primary btn-lg">
                    <?php _e('View All Products', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Services Section -->
    <section id="services" class="services-section section bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo get_theme_mod('services_title', __('Our Services', 'aqualuxe')); ?>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php echo get_theme_mod('services_subtitle', __('Comprehensive aquatic solutions for enthusiasts and professionals worldwide', 'aqualuxe')); ?>
                </p>
            </div>
            
            <?php
            $services = new WP_Query([
                'post_type' => 'aqualuxe_service',
                'posts_per_page' => 6,
                'meta_key' => '_service_featured',
                'meta_value' => '1',
            ]);
            
            if ($services->have_posts()) :
            ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($services->have_posts()) : $services->the_post(); ?>
                        <div class="service-item bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-image mb-4">
                                    <?php the_post_thumbnail('aqualuxe-card', ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="service-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                <?php the_title(); ?>
                            </h3>
                            
                            <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="service-meta flex justify-between items-center">
                                <?php
                                $price = get_post_meta(get_the_ID(), '_service_price', true);
                                $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                                ?>
                                
                                <?php if ($price) : ?>
                                    <span class="service-price font-semibold text-primary-600">
                                        <?php echo esc_html($price); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($duration) : ?>
                                    <span class="service-duration text-sm text-gray-500">
                                        <?php echo esc_html($duration); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="service-actions mt-4">
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline w-full">
                                    <?php _e('Learn More', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php 
            wp_reset_postdata();
            endif; 
            ?>
            
            <div class="text-center mt-12">
                <a href="/services" class="btn btn-primary btn-lg">
                    <?php _e('View All Services', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section section bg-primary-50 dark:bg-primary-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo get_theme_mod('testimonials_title', __('What Our Clients Say', 'aqualuxe')); ?>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php echo get_theme_mod('testimonials_subtitle', __('Trusted by aquatic enthusiasts and professionals globally', 'aqualuxe')); ?>
                </p>
            </div>
            
            <?php
            $testimonials = new WP_Query([
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 3,
                'orderby' => 'rand',
            ]);
            
            if ($testimonials->have_posts()) :
            ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                        <?php
                        $client_name = get_post_meta(get_the_ID(), '_testimonial_client_name', true);
                        $client_position = get_post_meta(get_the_ID(), '_testimonial_client_position', true);
                        $client_company = get_post_meta(get_the_ID(), '_testimonial_client_company', true);
                        $rating = get_post_meta(get_the_ID(), '_testimonial_rating', true);
                        ?>
                        
                        <div class="testimonial-item bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                            <div class="testimonial-content mb-6">
                                <blockquote class="text-gray-700 dark:text-gray-300 italic">
                                    "<?php the_content(); ?>"
                                </blockquote>
                            </div>
                            
                            <?php if ($rating) : ?>
                                <div class="testimonial-rating mb-4">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <svg class="w-5 h-5 inline <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-author">
                                <div class="flex items-center">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="author-avatar mr-4">
                                            <?php the_post_thumbnail('thumbnail', ['class' => 'w-12 h-12 rounded-full object-cover']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="author-info">
                                        <div class="author-name font-semibold text-gray-900 dark:text-white">
                                            <?php echo esc_html($client_name); ?>
                                        </div>
                                        <?php if ($client_position || $client_company) : ?>
                                            <div class="author-title text-sm text-gray-600 dark:text-gray-400">
                                                <?php
                                                $title_parts = array_filter([$client_position, $client_company]);
                                                echo esc_html(implode(', ', $title_parts));
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php 
            wp_reset_postdata();
            endif; 
            ?>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section id="newsletter" class="newsletter-section section bg-primary-900 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">
                <?php echo get_theme_mod('newsletter_title', __('Stay Updated', 'aqualuxe')); ?>
            </h2>
            <p class="text-xl text-primary-200 mb-8 max-w-2xl mx-auto">
                <?php echo get_theme_mod('newsletter_subtitle', __('Subscribe to receive updates on new products, exclusive offers, and aquatic care tips', 'aqualuxe')); ?>
            </p>
            
            <form class="newsletter-form max-w-md mx-auto" id="newsletter-form">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           name="email" 
                           placeholder="<?php _e('Enter your email address', 'aqualuxe'); ?>" 
                           class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300" 
                           required>
                    <button type="submit" class="btn btn-secondary px-8 py-3">
                        <?php _e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </div>
            </form>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>