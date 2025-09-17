<?php
/**
 * Front Page Template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <!-- Hero Section -->
    <section class="hero relative overflow-hidden bg-gradient-aqua min-h-screen flex items-center">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="hero-content text-white">
                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 text-shadow-lg">
                        <?php echo esc_html(get_theme_mod('aqualuxe_hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'))); ?>
                    </h1>
                    <p class="text-xl lg:text-2xl mb-8 opacity-90">
                        <?php echo esc_html(get_theme_mod('aqualuxe_hero_subtitle', __('Globally sourced rare fish species, premium aquascaping, and professional aquarium services', 'aqualuxe'))); ?>
                    </p>
                    <div class="hero-actions flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-primary btn-lg">
                            <?php esc_html_e('Explore Services', 'aqualuxe'); ?>
                        </a>
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-ghost btn-lg border-white text-white hover:bg-white hover:text-primary-600">
                                <?php esc_html_e('Shop Premium Products', 'aqualuxe'); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-ghost btn-lg border-white text-white hover:bg-white hover:text-primary-600">
                                <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-400/20 to-aqua-400/20 rounded-2xl"></div>
                        <?php 
                        $hero_image = get_theme_mod('aqualuxe_hero_image');
                        if ($hero_image) :
                            echo wp_get_attachment_image($hero_image, 'large', false, ['class' => 'w-full h-auto rounded-2xl shadow-2xl']);
                        else :
                            ?>
                            <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Aquarium" 
                                 class="w-full h-auto rounded-2xl shadow-2xl">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Featured Services -->
    <section class="featured-services py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    <?php esc_html_e('Our Premium Services', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('From custom aquarium design to rare species sourcing, we provide comprehensive aquatic solutions for enthusiasts and professionals worldwide.', 'aqualuxe'); ?>
                </p>
            </div>

            <?php
            $featured_services = new WP_Query([
                'post_type' => 'aqualuxe_service',
                'posts_per_page' => 6,
                'meta_query' => [
                    [
                        'key' => '_aqualuxe_service_featured',
                        'value' => '1',
                        'compare' => '='
                    ]
                ]
            ]);

            if ($featured_services->have_posts()) :
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($featured_services->have_posts()) : $featured_services->the_post(); ?>
                        <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-image relative overflow-hidden h-48">
                                    <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300']); ?>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-content p-6">
                                <h3 class="text-xl font-semibold mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-primary-600 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </p>
                                
                                <?php 
                                $price = get_post_meta(get_the_ID(), '_aqualuxe_service_price', true);
                                if ($price) :
                                    ?>
                                    <div class="service-price text-lg font-bold text-primary-600 mb-4">
                                        <?php echo esc_html($price); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline">
                                    <?php esc_html_e('Learn More', 'aqualuxe'); ?>
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
                <a href="<?php echo esc_url(get_post_type_archive_link('aqualuxe_service')); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('View All Services', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products (WooCommerce) -->
    <?php if (class_exists('WooCommerce')) : ?>
        <section class="featured-products py-16 lg:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        <?php esc_html_e('Discover our curated selection of rare fish species, premium equipment, and aquascaping essentials.', 'aqualuxe'); ?>
                    </p>
                </div>

                <?php echo do_shortcode('[featured_products limit="8" columns="4"]'); ?>

                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Shop All Products', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php else : ?>
        <!-- Fallback Product Categories -->
        <section class="product-categories py-16 lg:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Our Product Categories', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        <?php esc_html_e('Explore our comprehensive range of aquatic products and services.', 'aqualuxe'); ?>
                    </p>
                </div>

                <?php echo AquaLuxe_WooCommerce_Integration::fallback_shop_content(); ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- About Section -->
    <section class="about-section py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-content">
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Excellence in Aquatic Life', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        <?php esc_html_e('With over two decades of experience in the aquatic industry, AquaLuxe has established itself as the premier destination for rare fish species, premium aquascaping, and professional aquarium services.', 'aqualuxe'); ?>
                    </p>
                    <p class="text-lg text-gray-600 mb-8">
                        <?php esc_html_e('Our global network of suppliers and breeders ensures that we can source the most exotic and healthy specimens while maintaining the highest standards of care and sustainability.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="about-stats grid grid-cols-2 gap-6 mb-8">
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600">500+</div>
                            <div class="stat-label text-gray-600"><?php esc_html_e('Rare Species', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600">1000+</div>
                            <div class="stat-label text-gray-600"><?php esc_html_e('Happy Clients', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600">50+</div>
                            <div class="stat-label text-gray-600"><?php esc_html_e('Countries Served', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-primary-600">20+</div>
                            <div class="stat-label text-gray-600"><?php esc_html_e('Years Experience', 'aqualuxe'); ?></div>
                        </div>
                    </div>
                    
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="btn btn-primary">
                        <?php esc_html_e('Learn More About Us', 'aqualuxe'); ?>
                    </a>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                         alt="Aquarium Setup" 
                         class="w-full h-auto rounded-2xl shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials py-16 lg:py-24 bg-gray-900 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    <?php esc_html_e('What Our Clients Say', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    <?php esc_html_e('Discover why aquarium enthusiasts and professionals worldwide trust AquaLuxe for their aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>

            <?php
            $testimonials = new WP_Query([
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 3,
                'meta_query' => [
                    [
                        'key' => '_aqualuxe_testimonial_featured',
                        'value' => '1',
                        'compare' => '='
                    ]
                ]
            ]);

            if ($testimonials->have_posts()) :
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                        <div class="testimonial-card bg-gray-800 rounded-xl p-8 hover:bg-gray-700 transition-colors duration-300">
                            <?php 
                            $rating = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_rating', true);
                            if ($rating) :
                                ?>
                                <div class="testimonial-rating mb-4">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="text-yellow-400 text-lg">
                                            <?php echo $i <= $rating ? '★' : '☆'; ?>
                                        </span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <blockquote class="text-lg text-gray-300 mb-6">
                                "<?php echo esc_html(get_the_content()); ?>"
                            </blockquote>
                            
                            <footer class="testimonial-author">
                                <div class="author-name text-white font-semibold">
                                    <?php echo esc_html(get_post_meta(get_the_ID(), '_aqualuxe_testimonial_author', true)); ?>
                                </div>
                                <?php 
                                $position = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_position', true);
                                $company = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_company', true);
                                if ($position || $company) :
                                    ?>
                                    <div class="author-position text-gray-400 text-sm">
                                        <?php echo esc_html(trim($position . ($position && $company ? ', ' : '') . $company)); ?>
                                    </div>
                                <?php endif; ?>
                            </footer>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="newsletter py-16 lg:py-24 bg-gradient-luxury">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    <?php esc_html_e('Stay in the Loop', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    <?php esc_html_e('Get the latest updates on new arrivals, exclusive offers, and aquascaping tips delivered to your inbox.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-md mx-auto">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white" 
                               placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" 
                               required>
                        <button type="submit" class="btn btn-primary bg-white text-gray-900 hover:bg-gray-100">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </div>
                    <p class="text-sm mt-4 opacity-75">
                        <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                    </p>
                </form>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>