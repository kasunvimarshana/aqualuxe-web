<?php get_header(); ?>

<div class="home-page">
    
    <!-- Hero Section -->
    <section class="hero-section relative overflow-hidden bg-gradient-to-br from-primary-600 via-secondary-500 to-aqua-400 text-white">
        <div class="hero-background absolute inset-0 z-0">
            <?php
            $hero_image = get_theme_mod('aqualuxe_hero_image');
            if ($hero_image) :
                ?>
                <img src="<?php echo esc_url($hero_image); ?>" 
                     alt="<?php esc_attr_e('AquaLuxe Hero Background', 'aqualuxe'); ?>"
                     class="w-full h-full object-cover opacity-30">
                <?php
            endif;
            ?>
        </div>
        
        <div class="hero-content relative z-10 py-20 lg:py-32">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="hero-title text-5xl lg:text-7xl font-bold mb-6 font-secondary" data-aos="fade-up">
                        <?php echo wp_kses_post(get_theme_mod('aqualuxe_hero_title', 'Bringing Elegance to <span class="text-accent-400">Aquatic Life</span>')); ?>
                    </h1>
                    
                    <p class="hero-subtitle text-xl lg:text-2xl mb-8 text-gray-100 font-light leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post(get_theme_mod('aqualuxe_hero_subtitle', 'Discover rare fish, premium equipment, and professional aquarium services from industry experts serving customers globally.')); ?>
                    </p>
                    
                    <div class="hero-actions flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop')) ?: '/shop/'); ?>" 
                           class="btn btn-primary bg-accent-500 hover:bg-accent-600 text-black font-semibold px-8 py-4 rounded-full transition-all transform hover:scale-105 shadow-lg">
                            <?php esc_html_e('Explore Products', 'aqualuxe'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                           class="btn btn-outline border-2 border-white text-white hover:bg-white hover:text-primary-600 font-semibold px-8 py-4 rounded-full transition-all">
                            <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="featured-categories py-16 lg:py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold font-secondary text-gray-900 mb-4">
                    <?php esc_html_e('Explore Our Categories', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('From rare exotic fish to premium aquarium equipment, discover everything you need for your aquatic journey.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="categories-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $featured_categories = array(
                    array(
                        'title' => __('Rare Fish Species', 'aqualuxe'),
                        'description' => __('Exotic and rare fish from around the world', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/src/images/categories/rare-fish.jpg',
                        'link' => '/product-category/rare-fish/',
                        'icon' => 'fish'
                    ),
                    array(
                        'title' => __('Aquatic Plants', 'aqualuxe'),
                        'description' => __('Beautiful plants for natural aquascaping', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/src/images/categories/aquatic-plants.jpg',
                        'link' => '/product-category/aquatic-plants/',
                        'icon' => 'leaf'
                    ),
                    array(
                        'title' => __('Premium Equipment', 'aqualuxe'),
                        'description' => __('High-quality filtration and lighting systems', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/src/images/categories/equipment.jpg',
                        'link' => '/product-category/equipment/',
                        'icon' => 'cog'
                    ),
                    array(
                        'title' => __('Care Supplies', 'aqualuxe'),
                        'description' => __('Everything needed for optimal fish health', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/src/images/categories/care-supplies.jpg',
                        'link' => '/product-category/care-supplies/',
                        'icon' => 'heart'
                    )
                );
                
                foreach ($featured_categories as $index => $category) :
                    ?>
                    <div class="category-card group" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <a href="<?php echo esc_url($category['link']); ?>" class="block">
                            <div class="card bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-2">
                                <div class="category-image relative h-48 bg-gradient-to-br from-primary-500 to-secondary-400 overflow-hidden">
                                    <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <?php aqualuxe_category_icon($category['icon'], 'w-16 h-16 text-white'); ?>
                                    </div>
                                </div>
                                <div class="category-content p-6">
                                    <h3 class="category-title text-xl font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php echo esc_html($category['title']); ?>
                                    </h3>
                                    <p class="category-description text-gray-600 text-sm">
                                        <?php echo esc_html($category['description']); ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <?php if (class_exists('WooCommerce')) : ?>
    <section class="featured-products py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold font-secondary text-gray-900 mb-4">
                    <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('Handpicked premium products that showcase the finest in aquatic excellence.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <?php
            $featured_products = wc_get_featured_product_ids();
            if ($featured_products) :
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 8,
                    'post__in' => $featured_products,
                    'meta_query' => WC()->query->get_meta_query(),
                );
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) :
                    ?>
                    <div class="products-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php
                        $delay = 0;
                        while ($products->have_posts()) : $products->the_post();
                            global $product;
                            ?>
                            <div class="product-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                <?php wc_get_template_part('content', 'product'); ?>
                            </div>
                            <?php
                            $delay += 100;
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <div class="text-center mt-12" data-aos="fade-up">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                           class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-full transition-colors">
                            <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                        </a>
                    </div>
                    <?php
                endif;
            endif;
            ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- About Section -->
    <section class="about-section py-16 lg:py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="about-content grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-text" data-aos="fade-right">
                    <h2 class="text-4xl lg:text-5xl font-bold font-secondary text-gray-900 mb-6">
                        <?php echo wp_kses_post(get_theme_mod('aqualuxe_about_title', 'About <span class="text-primary-600">AquaLuxe</span>')); ?>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        <?php echo wp_kses_post(get_theme_mod('aqualuxe_about_content', 'With over two decades of experience in the aquatic industry, AquaLuxe has established itself as the premier destination for luxury aquarium solutions. We specialize in rare fish species, premium equipment, and professional services that bring elegance to aquatic life worldwide.')); ?>
                    </p>
                    
                    <div class="about-features space-y-4 mb-8">
                        <?php
                        $features = array(
                            __('Expert-sourced rare and exotic fish species', 'aqualuxe'),
                            __('Premium quality equipment and supplies', 'aqualuxe'),
                            __('Professional aquarium design and maintenance', 'aqualuxe'),
                            __('Global shipping with specialized handling', 'aqualuxe'),
                        );
                        
                        foreach ($features as $feature) :
                            ?>
                            <div class="feature-item flex items-center space-x-3">
                                <svg class="w-5 h-5 text-primary-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700"><?php echo esc_html($feature); ?></span>
                            </div>
                            <?php
                        endforeach;
                        ?>
                    </div>
                    
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" 
                       class="btn btn-outline border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-8 py-3 rounded-full transition-all">
                        <?php esc_html_e('Learn More About Us', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <div class="about-image" data-aos="fade-left">
                    <?php
                    $about_image = get_theme_mod('aqualuxe_about_image', get_template_directory_uri() . '/assets/src/images/about-aqualuxe.jpg');
                    ?>
                    <div class="image-container relative">
                        <img src="<?php echo esc_url($about_image); ?>" 
                             alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>"
                             class="w-full h-auto rounded-2xl shadow-lg">
                        <div class="image-overlay absolute inset-0 bg-gradient-to-tr from-primary-600/20 to-transparent rounded-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="services-preview py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold font-secondary text-gray-900 mb-4">
                    <?php esc_html_e('Professional Services', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('From custom aquarium design to ongoing maintenance, our expert team provides comprehensive aquatic solutions.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $services = array(
                    array(
                        'title' => __('Custom Aquarium Design', 'aqualuxe'),
                        'description' => __('Bespoke aquarium solutions tailored to your space and vision.', 'aqualuxe'),
                        'icon' => 'design',
                    ),
                    array(
                        'title' => __('Professional Installation', 'aqualuxe'),
                        'description' => __('Expert installation services ensuring perfect setup and functionality.', 'aqualuxe'),
                        'icon' => 'tools',
                    ),
                    array(
                        'title' => __('Maintenance & Care', 'aqualuxe'),
                        'description' => __('Regular maintenance services to keep your aquarium in pristine condition.', 'aqualuxe'),
                        'icon' => 'maintenance',
                    ),
                    array(
                        'title' => __('Fish Health Consultation', 'aqualuxe'),
                        'description' => __('Expert advice and treatment for optimal fish health and wellbeing.', 'aqualuxe'),
                        'icon' => 'health',
                    ),
                    array(
                        'title' => __('Aquascaping Services', 'aqualuxe'),
                        'description' => __('Artistic aquascaping to create stunning underwater landscapes.', 'aqualuxe'),
                        'icon' => 'landscape',
                    ),
                    array(
                        'title' => __('Training & Education', 'aqualuxe'),
                        'description' => __('Comprehensive training programs for aquarium enthusiasts.', 'aqualuxe'),
                        'icon' => 'education',
                    ),
                );
                
                foreach ($services as $index => $service) :
                    ?>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="card bg-white p-8 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 text-center group">
                            <div class="service-icon mb-6">
                                <div class="icon-container w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-400 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform duration-300">
                                    <?php aqualuxe_service_icon($service['icon'], 'w-8 h-8 text-white'); ?>
                                </div>
                            </div>
                            <h3 class="service-title text-xl font-semibold text-gray-900 mb-4">
                                <?php echo esc_html($service['title']); ?>
                            </h3>
                            <p class="service-description text-gray-600">
                                <?php echo esc_html($service['description']); ?>
                            </p>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
            
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                   class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-full transition-colors">
                    <?php esc_html_e('View All Services', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials py-16 lg:py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold font-secondary text-gray-900 mb-4">
                    <?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('Discover why aquarium enthusiasts worldwide trust AquaLuxe for their aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <?php
            $testimonials_query = new WP_Query(array(
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 6,
                'post_status' => 'publish',
            ));
            
            if ($testimonials_query->have_posts()) :
                ?>
                <div class="testimonial-slider swiper-container" data-aos="fade-up">
                    <div class="swiper-wrapper">
                        <?php
                        while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                            $rating = get_post_meta(get_the_ID(), '_testimonial_rating', true) ?: 5;
                            $customer_name = get_post_meta(get_the_ID(), '_customer_name', true) ?: get_the_title();
                            $customer_location = get_post_meta(get_the_ID(), '_customer_location', true);
                            ?>
                            <div class="swiper-slide">
                                <div class="testimonial-card bg-white p-8 rounded-2xl shadow-soft text-center">
                                    <div class="rating mb-4">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <svg class="w-5 h-5 inline <?php echo $i <= $rating ? 'text-accent-500' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <blockquote class="testimonial-content text-lg text-gray-700 mb-6">
                                        "<?php echo esc_html(get_the_content()); ?>"
                                    </blockquote>
                                    <div class="customer-info">
                                        <h4 class="customer-name font-semibold text-gray-900">
                                            <?php echo esc_html($customer_name); ?>
                                        </h4>
                                        <?php if ($customer_location) : ?>
                                        <p class="customer-location text-sm text-gray-600">
                                            <?php echo esc_html($customer_location); ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <div class="swiper-pagination mt-8"></div>
                </div>
                <?php
            endif;
            ?>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="newsletter-cta py-16 lg:py-24 bg-gradient-to-r from-primary-600 to-secondary-500 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold font-secondary mb-6">
                    <?php esc_html_e('Stay Updated with AquaLuxe', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8 text-gray-100 leading-relaxed">
                    <?php esc_html_e('Subscribe to our newsletter for the latest product arrivals, expert tips, and exclusive offers.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-lg mx-auto" method="post">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               name="newsletter_email" 
                               placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" 
                               required
                               class="flex-1 px-6 py-4 rounded-full text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-white/30">
                        <button type="submit" 
                                class="px-8 py-4 bg-accent-500 hover:bg-accent-600 text-black font-semibold rounded-full transition-colors focus:outline-none focus:ring-4 focus:ring-accent-300">
                            <?php esc_html_e('Subscribe Now', 'aqualuxe'); ?>
                        </button>
                    </div>
                    <p class="text-sm text-gray-200 mt-4">
                        <?php esc_html_e('Join over 10,000 aquarium enthusiasts worldwide. Unsubscribe anytime.', 'aqualuxe'); ?>
                    </p>
                </form>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
