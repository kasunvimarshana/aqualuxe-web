<?php
/**
 * The front page template file
 *
 * This template is used for the site's homepage
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Video/Image -->
    <div class="hero-background absolute inset-0 z-0">
        <?php
        $hero_video = get_theme_mod('aqualuxe_hero_video');
        $hero_image = get_theme_mod('aqualuxe_hero_image', AQUALUXE_THEME_URI . '/assets/src/images/hero-bg.jpg');
        ?>
        
        <?php if ($hero_video): ?>
            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('AquaLuxe Hero', 'aqualuxe'); ?>" class="w-full h-full object-cover">
            </video>
        <?php else: ?>
            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('AquaLuxe Hero', 'aqualuxe'); ?>" class="w-full h-full object-cover lazy" loading="lazy">
        <?php endif; ?>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/70 to-primary-600/50"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="hero-content relative z-10 text-center text-white max-w-4xl mx-auto px-4">
        <h1 class="hero-title text-4xl md:text-6xl lg:text-7xl font-display font-bold mb-6 animate-fade-in-up">
            <?php echo esc_html(get_theme_mod('aqualuxe_hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'))); ?>
        </h1>
        
        <p class="hero-subtitle text-xl md:text-2xl mb-8 text-gray-200 animate-fade-in-up animation-delay-300">
            <?php echo esc_html(get_theme_mod('aqualuxe_hero_subtitle', __('Premium ornamental fish, aquatic plants, and luxury aquarium solutions – globally.', 'aqualuxe'))); ?>
        </p>
        
        <div class="hero-buttons flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6 animate-fade-in-up animation-delay-600">
            <?php if (aqualuxe_is_woocommerce_active()): ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg px-8 py-4 text-lg font-semibold">
                    <?php esc_html_e('Explore Our Collection', 'aqualuxe'); ?>
                </a>
            <?php endif; ?>
            
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-outline btn-lg px-8 py-4 text-lg font-semibold border-white text-white hover:bg-white hover:text-primary-600">
                <?php esc_html_e('Our Services', 'aqualuxe'); ?>
            </a>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-16 bg-gray-50 dark:bg-dark-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Why Choose AquaLuxe?', 'aqualuxe'); ?></h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e('We combine expertise, quality, and innovation to deliver exceptional aquatic solutions worldwide.', 'aqualuxe'); ?>
            </p>
        </div>
        
        <div class="features-grid grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="feature-item text-center p-6 card card-hover">
                <div class="feature-icon mb-4">
                    <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Premium Quality', 'aqualuxe'); ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Hand-selected specimens and top-tier equipment for discerning aquarists.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="feature-item text-center p-6 card card-hover">
                <div class="feature-icon mb-4">
                    <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Global Reach', 'aqualuxe'); ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Worldwide shipping with proper certifications and care protocols.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="feature-item text-center p-6 card card-hover">
                <div class="feature-icon mb-4">
                    <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Expert Knowledge', 'aqualuxe'); ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Decades of experience in aquaculture and aquascaping expertise.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="feature-item text-center p-6 card card-hover">
                <div class="feature-icon mb-4">
                    <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Dedicated Support', 'aqualuxe'); ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Ongoing care guidance and comprehensive after-sales support.', 'aqualuxe'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if (aqualuxe_is_woocommerce_active()): ?>
<section class="featured-products-section py-16">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Featured Collection', 'aqualuxe'); ?></h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e('Discover our handpicked selection of premium ornamental fish and aquatic plants.', 'aqualuxe'); ?>
            </p>
        </div>
        
        <?php
        // Get featured products
        $featured_products = wc_get_featured_product_ids();
        if (!empty($featured_products)) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 8,
                'post__in' => $featured_products,
                'meta_query' => WC()->query->get_meta_query(),
                'tax_query' => WC()->query->get_tax_query(),
            );
        } else {
            // Fallback to latest products if no featured products
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 8,
                'meta_query' => WC()->query->get_meta_query(),
                'tax_query' => WC()->query->get_tax_query(),
            );
        }
        
        $products_query = new WP_Query($args);
        
        if ($products_query->have_posts()): ?>
            <div class="products-grid grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while ($products_query->have_posts()): $products_query->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                </a>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<section class="services-section py-16 bg-gray-50 dark:bg-dark-800">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Our Services', 'aqualuxe'); ?></h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e('From custom aquarium design to ongoing maintenance, we provide comprehensive aquatic solutions.', 'aqualuxe'); ?>
            </p>
        </div>
        
        <?php
        // Get featured services
        $services_query = new WP_Query(array(
            'post_type' => 'aqualuxe_service',
            'posts_per_page' => 6,
            'meta_query' => array(
                array(
                    'key' => '_featured_service',
                    'value' => 'yes',
                    'compare' => '='
                )
            )
        ));
        
        if ($services_query->have_posts()): ?>
            <div class="services-grid grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($services_query->have_posts()): $services_query->the_post(); ?>
                    <div class="service-item card p-6 card-hover">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="service-image mb-4">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'aqualuxe-featured')); ?>" 
                                     alt="<?php echo esc_attr(get_the_title()); ?>" 
                                     class="w-full h-48 object-cover rounded-lg lazy" 
                                     loading="lazy">
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="text-xl font-semibold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
                        </p>
                        
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('View All Services', 'aqualuxe'); ?>
                </a>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-16">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('What Our Clients Say', 'aqualuxe'); ?></h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e('Trusted by aquarists, designers, and businesses worldwide.', 'aqualuxe'); ?>
            </p>
        </div>
        
        <?php
        // Get testimonials
        $testimonials_query = new WP_Query(array(
            'post_type' => 'aqualuxe_testimonial',
            'posts_per_page' => 6,
            'meta_query' => array(
                array(
                    'key' => '_featured_testimonial',
                    'value' => 'yes',
                    'compare' => '='
                )
            )
        ));
        
        if ($testimonials_query->have_posts()): ?>
            <div class="testimonials-slider" data-glide>
                <div class="glide__track" data-glide-el="track">
                    <div class="glide__slides">
                        <?php while ($testimonials_query->have_posts()): $testimonials_query->the_post(); ?>
                            <div class="glide__slide">
                                <div class="testimonial-item text-center max-w-2xl mx-auto p-8">
                                    <div class="testimonial-content mb-6">
                                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                        </svg>
                                        <blockquote class="text-lg text-gray-600 dark:text-gray-400 italic">
                                            "<?php echo esc_html(get_the_content()); ?>"
                                        </blockquote>
                                    </div>
                                    
                                    <div class="testimonial-author flex items-center justify-center space-x-4">
                                        <?php if (has_post_thumbnail()): ?>
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'thumbnail')); ?>" 
                                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                                 class="w-12 h-12 rounded-full object-cover">
                                        <?php endif; ?>
                                        
                                        <div class="author-info">
                                            <h4 class="font-semibold"><?php the_title(); ?></h4>
                                            <?php if ($company = get_post_meta(get_the_ID(), '_testimonial_company', true)): ?>
                                                <p class="text-sm text-gray-500"><?php echo esc_html($company); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class="glide__arrows text-center mt-8" data-glide-el="controls">
                    <button class="glide__arrow glide__arrow--left btn btn-outline btn-sm mr-2" data-glide-dir="<">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="glide__arrow glide__arrow--right btn btn-outline btn-sm" data-glide-dir=">">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-16 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Ready to Transform Your Aquatic Space?', 'aqualuxe'); ?></h2>
        <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">
            <?php esc_html_e('Get expert consultation and discover the perfect aquatic solutions for your needs.', 'aqualuxe'); ?>
        </p>
        
        <div class="cta-buttons flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn bg-white text-primary-600 hover:bg-gray-100 btn-lg px-8 py-4 font-semibold">
                <?php esc_html_e('Get Free Consultation', 'aqualuxe'); ?>
            </a>
            
            <?php if (aqualuxe_is_woocommerce_active()): ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline border-white text-white hover:bg-white hover:text-primary-600 btn-lg px-8 py-4 font-semibold">
                    <?php esc_html_e('Browse Products', 'aqualuxe'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<?php if (get_theme_mod('aqualuxe_newsletter_enabled', true)): ?>
<section class="newsletter-section py-16 bg-gray-50 dark:bg-dark-800">
    <div class="container mx-auto px-4">
        <div class="newsletter-content max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4"><?php esc_html_e('Stay Connected', 'aqualuxe'); ?></h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                <?php esc_html_e('Get the latest updates on new arrivals, care tips, and exclusive offers.', 'aqualuxe'); ?>
            </p>
            
            <form class="newsletter-form flex flex-col sm:flex-row max-w-md mx-auto space-y-4 sm:space-y-0 sm:space-x-4" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" class="flex-1 px-4 py-3 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                <button type="submit" class="btn btn-primary px-6 py-3 font-semibold">
                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                </button>
                <input type="hidden" name="action" value="aqualuxe_newsletter_subscribe">
                <?php wp_nonce_field('aqualuxe_newsletter_nonce', 'newsletter_nonce'); ?>
            </form>
            
            <p class="text-sm text-gray-500 mt-4">
                <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
            </p>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
