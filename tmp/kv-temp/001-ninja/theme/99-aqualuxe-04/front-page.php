<?php
/**
 * Homepage Template
 *
 * @package AquaLuxe
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-hero-pattern opacity-5"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-200 dark:bg-primary-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent-200 dark:bg-accent-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-secondary-200 dark:bg-secondary-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-bold font-serif text-gray-900 dark:text-white mb-6 leading-tight">
                <span class="block text-gradient-aqua animate-fade-up">
                    <?php echo esc_html(get_theme_mod('hero_title', 'Bringing Elegance to Aquatic Life')); ?>
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl sm:text-2xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed animate-fade-up animation-delay-300">
                <?php echo esc_html(get_theme_mod('hero_subtitle', 'Premium aquariums, expert care, and stunning aquascapes for discerning enthusiasts worldwide.')); ?>
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-up animation-delay-600">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                   class="group bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center">
                    <?php esc_html_e('Explore Our Services', 'aqualuxe'); ?>
                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                       class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur text-gray-900 dark:text-white border-2 border-gray-200 dark:border-gray-700 hover:border-primary-500 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                        <?php esc_html_e('Shop Premium Products', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Trust Indicators -->
            <div class="mt-12 animate-fade-up animation-delay-900">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <?php esc_html_e('Trusted by aquarium enthusiasts worldwide', 'aqualuxe'); ?>
                </p>
                <div class="flex items-center justify-center space-x-8 opacity-60">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-sm">4.9/5 Rating</span>
                    </div>
                    <div class="text-sm">500+ Happy Customers</div>
                    <div class="text-sm">15+ Years Experience</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<?php if (aqualuxe_is_woocommerce_active()) : ?>
<!-- Featured Products Section -->
<section class="featured-products py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold font-serif text-gray-900 dark:text-white mb-4">
                <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                <?php esc_html_e('Discover our curated selection of premium aquatic life and equipment', 'aqualuxe'); ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            // Get featured products
            $featured_products = wc_get_products(array(
                'status' => 'publish',
                'featured' => true,
                'limit' => 4,
            ));
            
            if ($featured_products) :
                foreach ($featured_products as $product) :
                    wc_get_template_part('content', 'product');
                endforeach;
            else :
                // Fallback content if no featured products
                for ($i = 1; $i <= 4; $i++) :
            ?>
                <div class="product-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="aspect-square bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900 dark:to-accent-900 flex items-center justify-center">
                        <svg class="w-16 h-16 text-primary-400 dark:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                            <?php echo sprintf(esc_html__('Featured Product %d', 'aqualuxe'), $i); ?>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                            <?php esc_html_e('Premium quality aquatic product for enthusiasts', 'aqualuxe'); ?>
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-primary-600 dark:text-primary-400">
                                $<?php echo esc_html(number_format(mt_rand(1999, 9999) / 100, 2)); ?>
                            </span>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <?php esc_html_e('View Details', 'aqualuxe'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php
                endfor;
            endif;
            ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
               class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<section class="services-section py-20 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold font-serif text-gray-900 dark:text-white mb-4">
                <?php esc_html_e('Professional Services', 'aqualuxe'); ?>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                <?php esc_html_e('Expert aquarium design, maintenance, and consultation services', 'aqualuxe'); ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service 1: Design -->
            <div class="service-card group">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Custom Design', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php esc_html_e('Bespoke aquarium design tailored to your space and vision. From concept to installation.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                       class="inline-flex items-center text-primary-600 dark:text-primary-400 font-semibold hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Service 2: Maintenance -->
            <div class="service-card group">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-accent-100 dark:bg-accent-900 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent-200 dark:group-hover:bg-accent-800 transition-colors">
                        <svg class="w-8 h-8 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Maintenance', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php esc_html_e('Professional maintenance services to keep your aquarium healthy and beautiful year-round.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                       class="inline-flex items-center text-primary-600 dark:text-primary-400 font-semibold hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Service 3: Consultation -->
            <div class="service-card group">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-secondary-100 dark:bg-secondary-900 rounded-xl flex items-center justify-center mb-6 group-hover:bg-secondary-200 dark:group-hover:bg-secondary-800 transition-colors">
                        <svg class="w-8 h-8 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Expert Consultation', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php esc_html_e('Get expert advice on aquarium setup, fish selection, and advanced aquascaping techniques.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                       class="inline-flex items-center text-primary-600 dark:text-primary-400 font-semibold hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold font-serif text-gray-900 dark:text-white mb-4">
                <?php esc_html_e('What Our Clients Say', 'aqualuxe'); ?>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                <?php esc_html_e('Trusted by aquarium enthusiasts and professionals worldwide', 'aqualuxe'); ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            // Get testimonials
            $testimonials = get_posts(array(
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));
            
            if ($testimonials) :
                foreach ($testimonials as $testimonial) :
                    $rating = get_post_meta($testimonial->ID, '_testimonial_rating', true) ?: 5;
                    $location = get_post_meta($testimonial->ID, '_testimonial_location', true);
            ?>
                <div class="testimonial-card bg-white dark:bg-gray-700 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="text-gray-600 dark:text-gray-300 mb-6 italic">
                        "<?php echo esc_html(get_the_content(null, false, $testimonial)); ?>"
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            <?php echo esc_html(substr(get_the_title($testimonial), 0, 1)); ?>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                <?php echo esc_html(get_the_title($testimonial)); ?>
                            </div>
                            <?php if ($location) : ?>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo esc_html($location); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
                endforeach;
            else :
                // Fallback testimonials
                $fallback_testimonials = array(
                    array(
                        'name' => 'Sarah Johnson',
                        'content' => 'AquaLuxe transformed my living room with a stunning 200-gallon reef tank. The design is breathtaking and the fish are healthy and vibrant.',
                        'location' => 'New York, NY',
                        'rating' => 5
                    ),
                    array(
                        'name' => 'Michael Chen',
                        'content' => 'Their maintenance service is exceptional. My aquarium has never looked better, and I have peace of mind knowing experts are taking care of everything.',
                        'location' => 'Los Angeles, CA',
                        'rating' => 5
                    ),
                    array(
                        'name' => 'Emily Rodriguez',
                        'content' => 'The breeding consultation helped me successfully breed my discus fish. The expert advice was invaluable and the results speak for themselves.',
                        'location' => 'Miami, FL',
                        'rating' => 5
                    )
                );
                
                foreach ($fallback_testimonials as $testimonial) :
            ?>
                <div class="testimonial-card bg-white dark:bg-gray-700 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="text-gray-600 dark:text-gray-300 mb-6 italic">
                        "<?php echo esc_html($testimonial['content']); ?>"
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            <?php echo esc_html(substr($testimonial['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                <?php echo esc_html($testimonial['name']); ?>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo esc_html($testimonial['location']); ?>
                            </div>
                        </div>
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
<section class="newsletter-section py-20 bg-gradient-to-br from-primary-600 to-accent-600 text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-bold font-serif mb-4">
                <?php esc_html_e('Stay Updated with AquaLuxe', 'aqualuxe'); ?>
            </h2>
            <p class="text-xl text-primary-100 mb-8">
                <?php esc_html_e('Get the latest aquarium tips, product updates, and exclusive offers delivered to your inbox.', 'aqualuxe'); ?>
            </p>
            
            <form class="newsletter-form max-w-md mx-auto" action="#" method="post">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           name="email" 
                           placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>"
                           class="flex-1 px-6 py-4 rounded-xl text-gray-900 placeholder-gray-500 border-0 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600"
                           required>
                    <button type="submit" 
                            class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-4 rounded-xl font-semibold transition-colors whitespace-nowrap">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </div>
                <p class="text-sm text-primary-200 mt-4">
                    <?php esc_html_e('No spam, unsubscribe at any time.', 'aqualuxe'); ?>
                </p>
            </form>
        </div>
    </div>
</section>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animation-delay-300 {
    animation-delay: 300ms;
}

.animation-delay-600 {
    animation-delay: 600ms;
}

.animation-delay-900 {
    animation-delay: 900ms;
}
</style>

<?php get_footer(); ?>