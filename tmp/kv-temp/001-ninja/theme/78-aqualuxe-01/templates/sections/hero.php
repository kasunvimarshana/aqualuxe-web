<?php
/**
 * Hero section template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$hero_title = get_theme_mod('aqualuxe_hero_title', 'Bringing elegance to aquatic life');
$hero_subtitle = get_theme_mod('aqualuxe_hero_subtitle', 'Discover premium fish, plants, and aquarium equipment for enthusiasts worldwide');
$hero_button_text = get_theme_mod('aqualuxe_hero_button_text', 'Shop Now');
$hero_button_url = get_theme_mod('aqualuxe_hero_button_url', '/shop');
$hero_background = aqualuxe_get_hero_background();
?>

<section class="hero-section relative min-h-screen-3/4 flex items-center justify-center overflow-hidden">
    
    <!-- Background -->
    <div class="hero-background absolute inset-0 z-0">
        <?php if ($hero_background) : ?>
            <div class="hero-image absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url($hero_background); ?>');">
                <div class="hero-overlay absolute inset-0 bg-black bg-opacity-40"></div>
            </div>
        <?php else : ?>
            <div class="hero-gradient absolute inset-0 bg-gradient-to-br from-aqua-500 via-ocean-500 to-luxe-500">
                <div class="hero-overlay absolute inset-0 bg-black bg-opacity-20"></div>
            </div>
        <?php endif; ?>
        
        <!-- Animated Elements -->
        <div class="hero-animation absolute inset-0">
            <div class="floating-element absolute top-20 left-10 w-8 h-8 bg-white bg-opacity-20 rounded-full animate-float"></div>
            <div class="floating-element absolute top-40 right-20 w-6 h-6 bg-white bg-opacity-15 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="floating-element absolute bottom-32 left-1/4 w-4 h-4 bg-white bg-opacity-10 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="floating-element absolute bottom-20 right-1/3 w-12 h-12 bg-white bg-opacity-25 rounded-full animate-float" style="animation-delay: 0.5s;"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="hero-content relative z-10 text-center text-white px-4">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="max-w-4xl mx-auto">
                
                <!-- Subtitle Badge -->
                <div class="hero-badge inline-block bg-white bg-opacity-20 backdrop-blur-sm rounded-full px-6 py-2 mb-6 text-sm font-medium animate-fade-in">
                    <?php esc_html_e('Premium Aquatic Solutions', 'aqualuxe'); ?>
                </div>

                <!-- Main Title -->
                <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 animate-slide-up">
                    <?php echo esc_html($hero_title); ?>
                </h1>

                <!-- Subtitle -->
                <?php if ($hero_subtitle) : ?>
                    <p class="hero-subtitle text-xl md:text-2xl text-gray-100 mb-8 max-w-2xl mx-auto animate-slide-up" style="animation-delay: 0.2s;">
                        <?php echo esc_html($hero_subtitle); ?>
                    </p>
                <?php endif; ?>

                <!-- CTA Buttons -->
                <div class="hero-buttons flex flex-col sm:flex-row gap-4 justify-center items-center animate-slide-up" style="animation-delay: 0.4s;">
                    <?php if ($hero_button_text && $hero_button_url) : ?>
                        <a href="<?php echo esc_url($hero_button_url); ?>" class="hero-cta-primary inline-block bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                            <?php echo esc_html($hero_button_text); ?>
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo esc_url(home_url('/about')); ?>" class="hero-cta-secondary inline-block border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-8 py-4 rounded-lg transition-all duration-300">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                    </a>
                </div>

                <!-- Features -->
                <div class="hero-features grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12 animate-fade-in" style="animation-delay: 0.6s;">
                    <div class="feature-item text-center">
                        <div class="feature-icon w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title font-semibold mb-2"><?php esc_html_e('Premium Quality', 'aqualuxe'); ?></h3>
                        <p class="feature-description text-sm text-gray-200"><?php esc_html_e('Hand-selected fish and equipment', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="feature-item text-center">
                        <div class="feature-icon w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title font-semibold mb-2"><?php esc_html_e('Expert Care', 'aqualuxe'); ?></h3>
                        <p class="feature-description text-sm text-gray-200"><?php esc_html_e('Professional guidance and support', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="feature-item text-center">
                        <div class="feature-icon w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title font-semibold mb-2"><?php esc_html_e('Global Shipping', 'aqualuxe'); ?></h3>
                        <p class="feature-description text-sm text-gray-200"><?php esc_html_e('Worldwide delivery available', 'aqualuxe'); ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>

</section>

<!-- Featured Products Section -->
<?php if (aqualuxe_is_woocommerce_active()) : ?>
    <section class="featured-products-section py-16 bg-gray-50">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    <?php esc_html_e('Discover our hand-picked selection of premium aquatic products', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="featured-products-grid">
                <?php echo do_shortcode('[featured_products limit="4" columns="4"]'); ?>
            </div>
            
            <div class="text-center mt-8">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Testimonials Section -->
<section class="testimonials-section py-16">
    <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                <?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                <?php esc_html_e('Join thousands of satisfied aquarium enthusiasts worldwide', 'aqualuxe'); ?>
            </p>
        </div>
        
        <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
            $testimonials = get_posts([
                'post_type' => 'aql_testimonial',
                'numberposts' => 3,
                'post_status' => 'publish',
            ]);
            
            if ($testimonials) :
                foreach ($testimonials as $testimonial) :
                    $rating = get_post_meta($testimonial->ID, '_testimonial_rating', true) ?: 5;
                    $location = get_post_meta($testimonial->ID, '_testimonial_location', true);
                    ?>
                    <div class="testimonial-item bg-white p-6 rounded-lg shadow-md">
                        <div class="testimonial-rating flex mb-4">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        
                        <blockquote class="testimonial-content text-gray-700 mb-4 italic">
                            "<?php echo esc_html($testimonial->post_content); ?>"
                        </blockquote>
                        
                        <div class="testimonial-author">
                            <div class="author-name font-semibold text-gray-900"><?php echo esc_html($testimonial->post_title); ?></div>
                            <?php if ($location) : ?>
                                <div class="author-location text-sm text-gray-600"><?php echo esc_html($location); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endforeach;
                wp_reset_postdata();
            else :
                // Default testimonials if none exist
                $default_testimonials = [
                    [
                        'name' => 'Sarah Johnson',
                        'location' => 'New York, NY',
                        'content' => 'AquaLuxe transformed my living room with a stunning aquarium. The fish are healthy and the design is breathtaking!',
                        'rating' => 5,
                    ],
                    [
                        'name' => 'Michael Chen',
                        'location' => 'Los Angeles, CA',
                        'content' => 'Excellent service and high-quality fish. My aquascaping project exceeded all expectations.',
                        'rating' => 5,
                    ],
                    [
                        'name' => 'Emma Davis',
                        'location' => 'London, UK',
                        'content' => 'Professional installation and ongoing support. Highly recommend for anyone serious about aquariums.',
                        'rating' => 5,
                    ],
                ];
                
                foreach ($default_testimonials as $testimonial) :
                    ?>
                    <div class="testimonial-item bg-white p-6 rounded-lg shadow-md">
                        <div class="testimonial-rating flex mb-4">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        
                        <blockquote class="testimonial-content text-gray-700 mb-4 italic">
                            "<?php echo esc_html($testimonial['content']); ?>"
                        </blockquote>
                        
                        <div class="testimonial-author">
                            <div class="author-name font-semibold text-gray-900"><?php echo esc_html($testimonial['name']); ?></div>
                            <div class="author-location text-sm text-gray-600"><?php echo esc_html($testimonial['location']); ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
            
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-16 bg-primary text-white">
    <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php esc_html_e('Stay Connected', 'aqualuxe'); ?>
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90">
                <?php esc_html_e('Get aquarium tips, product updates, and exclusive offers delivered to your inbox', 'aqualuxe'); ?>
            </p>
            
            <form class="newsletter-form max-w-md mx-auto" action="#" method="post">
                <div class="flex">
                    <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" class="flex-1 px-6 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" required>
                    <button type="submit" class="bg-secondary hover:bg-secondary-dark text-white font-semibold px-8 py-3 rounded-r-lg transition-colors">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </div>
                <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
            </form>
        </div>
    </div>
</section>
