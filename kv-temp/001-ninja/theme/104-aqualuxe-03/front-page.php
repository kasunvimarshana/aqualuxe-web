<?php
/**
 * Homepage Template
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main-content" class="site-main" role="main">
    
    <!-- Hero Section -->
    <?php 
    aqualuxe_get_template_part('layout/hero-section', null, [
        'hero_title' => __('Bringing Elegance to<br><span class="text-primary-300">Aquatic Life</span>', 'aqualuxe'),
        'hero_subtitle' => __('Discover premium ornamental fish, aquatic plants, and luxury aquarium solutions. Creating stunning underwater worlds that inspire and captivate.', 'aqualuxe'),
        'hero_cta_text' => __('Explore Collection', 'aqualuxe'),
        'hero_cta_url' => aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') : home_url('/services'),
        'hero_image' => aqualuxe_get_option('hero_image', ''),
        'hero_video' => aqualuxe_get_option('hero_video', ''),
    ]); 
    ?>
    
    <!-- Featured Products Section -->
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
        <section class="featured-products py-16 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <header class="section-header text-center mb-12">
                    <h2 class="section-title text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Featured Collection', 'aqualuxe'); ?>
                    </h2>
                    <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        <?php esc_html_e('Discover our handpicked selection of premium aquatic specimens and accessories, chosen for their exceptional quality and beauty.', 'aqualuxe'); ?>
                    </p>
                </header>
                
                <?php
                aqualuxe_get_template_part('woocommerce/product-grid', null, [
                    'posts_per_page' => 8,
                    'meta_query' => [
                        [
                            'key' => '_featured',
                            'value' => 'yes',
                            'compare' => '='
                        ]
                    ],
                    'grid_columns' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
                    'show_filters' => false,
                ]);
                ?>
                
                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline btn-lg">
                        <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Services Section -->
    <section class="services py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <header class="section-header text-center mb-12">
                <h2 class="section-title text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                </h2>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php esc_html_e('From design to maintenance, we provide comprehensive aquatic solutions to bring your underwater vision to life.', 'aqualuxe'); ?>
                </p>
            </header>
            
            <?php
            aqualuxe_get_template_part('components/service-cards', null, [
                'columns' => 'grid-cols-1 md:grid-cols-3',
                'show_excerpt' => true,
                'show_button' => true,
            ]);
            ?>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Content -->
                <div class="about-content">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e('Crafting Aquatic Excellence Since 2010', 'aqualuxe'); ?>
                    </h2>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                        <?php esc_html_e('AquaLuxe has been at the forefront of premium aquatic solutions, combining traditional aquaculture expertise with modern design principles to create stunning underwater ecosystems.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="features grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="feature flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300"><?php esc_html_e('Premium Quality', 'aqualuxe'); ?></span>
                        </div>
                        <div class="feature flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300"><?php esc_html_e('Global Shipping', 'aqualuxe'); ?></span>
                        </div>
                        <div class="feature flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300"><?php esc_html_e('Expert Support', 'aqualuxe'); ?></span>
                        </div>
                        <div class="feature flex items-center">
                            <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300"><?php esc_html_e('Sustainability Focus', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="btn btn-primary">
                        <?php esc_html_e('Learn More About Us', 'aqualuxe'); ?>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Image -->
                <div class="about-image">
                    <div class="relative rounded-lg overflow-hidden shadow-2xl">
                        <?php
                        $about_image = aqualuxe_get_option('about_image', '');
                        if (!empty($about_image)) :
                            ?>
                            <img 
                                src="<?php echo esc_url($about_image); ?>" 
                                alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>"
                                class="w-full h-auto"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <div class="w-full h-80 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-primary-600 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-secondary-600 rounded-full opacity-20"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats py-16 bg-primary-600">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="stat text-center">
                    <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2">500+</div>
                    <div class="stat-label text-primary-100 uppercase tracking-wide text-sm">
                        <?php esc_html_e('Fish Species', 'aqualuxe'); ?>
                    </div>
                </div>
                <div class="stat text-center">
                    <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2">15K+</div>
                    <div class="stat-label text-primary-100 uppercase tracking-wide text-sm">
                        <?php esc_html_e('Happy Customers', 'aqualuxe'); ?>
                    </div>
                </div>
                <div class="stat text-center">
                    <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2">50+</div>
                    <div class="stat-label text-primary-100 uppercase tracking-wide text-sm">
                        <?php esc_html_e('Countries Served', 'aqualuxe'); ?>
                    </div>
                </div>
                <div class="stat text-center">
                    <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2">10+</div>
                    <div class="stat-label text-primary-100 uppercase tracking-wide text-sm">
                        <?php esc_html_e('Years Experience', 'aqualuxe'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Latest Posts Section -->
    <section class="latest-posts py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <header class="section-header text-center mb-12">
                <h2 class="section-title text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Latest Articles', 'aqualuxe'); ?>
                </h2>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?php esc_html_e('Stay updated with the latest aquatic care tips, industry news, and expert insights from our team.', 'aqualuxe'); ?>
                </p>
            </header>
            
            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $latest_posts = new WP_Query([
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                ]);
                
                if ($latest_posts->have_posts()) :
                    while ($latest_posts->have_posts()) :
                        $latest_posts->the_post();
                        aqualuxe_get_template_part('content/post-content');
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            
            <div class="text-center mt-8">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline btn-lg">
                    <?php esc_html_e('View All Articles', 'aqualuxe'); ?>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="newsletter py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Stay in the Current', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    <?php esc_html_e('Subscribe to our newsletter for exclusive offers, aquatic care tips, and the latest product updates delivered directly to your inbox.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                    <input 
                        type="email" 
                        placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" 
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    <button 
                        type="submit" 
                        class="btn btn-primary px-8 py-3 whitespace-nowrap"
                    >
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </form>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                    <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                </p>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number text-3xl md:text-4xl font-bold text-white mb-2">50+</div>
                        <div class="stat-label text-sm text-gray-300 uppercase tracking-wide">
                            <?php esc_html_e('Countries Served', 'aqualuxe'); ?>
                        </div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number text-3xl md:text-4xl font-bold text-white mb-2">15+</div>
                        <div class="stat-label text-sm text-gray-300 uppercase tracking-wide">
                            <?php esc_html_e('Years Experience', 'aqualuxe'); ?>
                        </div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-number text-3xl md:text-4xl font-bold text-white mb-2">10K+</div>
                        <div class="stat-label text-sm text-gray-300 uppercase tracking-wide">
                            <?php esc_html_e('Happy Customers', 'aqualuxe'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="hero-scroll">
            <div class="scroll-text text-sm uppercase tracking-wide mb-2">
                <?php esc_html_e('Discover More', 'aqualuxe'); ?>
            </div>
            <div class="scroll-arrow">
                <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>
    
    <!-- Featured Products Section -->
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
    <section class="section-padding bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Featured Collection', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e('Discover our handpicked selection of premium ornamental fish and aquatic treasures.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="products grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">
                <?php
                $featured_products = wc_get_products([
                    'limit' => 8,
                    'featured' => true,
                    'status' => 'publish'
                ]);
                
                if ($featured_products) :
                    foreach ($featured_products as $product) :
                        wc_get_template_part('content', 'product');
                    endforeach;
                else :
                    // Fallback content if no products
                    for ($i = 1; $i <= 4; $i++) : ?>
                        <div class="product-card">
                            <div class="product-image bg-gray-200 dark:bg-gray-700 h-64 rounded-lg mb-4"></div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <?php echo sprintf(esc_html__('Featured Product %d', 'aqualuxe'), $i); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                <?php esc_html_e('Premium quality aquatic specimen.', 'aqualuxe'); ?>
                            </p>
                        </div>
                    <?php endfor;
                endif;
                ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Services Section -->
    <section class="section-padding bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e('From aquarium design to maintenance, we provide comprehensive solutions for your aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-on-scroll">
                <?php
                $services = [
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>',
                        'title' => __('Aquarium Design', 'aqualuxe'),
                        'description' => __('Custom aquarium design and installation services tailored to your space and vision.', 'aqualuxe')
                    ],
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                        'title' => __('Maintenance Services', 'aqualuxe'),
                        'description' => __('Professional aquarium maintenance to keep your aquatic environment healthy and beautiful.', 'aqualuxe')
                    ],
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                        'title' => __('Consultation', 'aqualuxe'),
                        'description' => __('Expert advice on fish selection, tank setup, and aquatic ecosystem management.', 'aqualuxe')
                    ],
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
                        'title' => __('Breeding Programs', 'aqualuxe'),
                        'description' => __('Specialized breeding programs for rare and exotic ornamental fish species.', 'aqualuxe')
                    ],
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                        'title' => __('Quarantine Services', 'aqualuxe'),
                        'description' => __('Professional quarantine facilities ensuring healthy and disease-free fish delivery.', 'aqualuxe')
                    ],
                    [
                        'icon' => '<svg class="w-16 h-16 mx-auto mb-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>',
                        'title' => __('Global Shipping', 'aqualuxe'),
                        'description' => __('Safe and reliable international shipping with specialized packaging for live aquatic animals.', 'aqualuxe')
                    ]
                ];
                
                foreach ($services as $service) : ?>
                    <div class="service-card hover-lift">
                        <?php echo $service['icon']; ?>
                        <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                        <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                        <a href="<?php echo esc_url(home_url('/services')); ?>" class="service-link">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="section-padding bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    <?php esc_html_e('Hear from aquarium enthusiasts who trust AquaLuxe for their aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="testimonials-carousel swiper animate-on-scroll">
                <div class="swiper-wrapper">
                    <?php
                    $testimonials = [
                        [
                            'content' => 'AquaLuxe transformed my living space with their stunning aquarium design. The attention to detail and quality of fish is unmatched.',
                            'author' => 'Sarah Johnson',
                            'title' => 'Interior Designer',
                            'rating' => 5
                        ],
                        [
                            'content' => 'Professional service from start to finish. Their breeding programs produce the most beautiful and healthy fish I\'ve ever seen.',
                            'author' => 'Michael Chen',
                            'title' => 'Aquarium Enthusiast',
                            'rating' => 5
                        ],
                        [
                            'content' => 'Excellent customer support and maintenance services. My aquarium has never looked better or been healthier.',
                            'author' => 'Emily Rodriguez',
                            'title' => 'Hotel Manager',
                            'rating' => 5
                        ]
                    ];
                    
                    foreach ($testimonials as $testimonial) : ?>
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-rating flex text-yellow-400 mb-4">
                                    <?php for ($i = 0; $i < $testimonial['rating']; $i++) : ?>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                                <div class="testimonial-content">
                                    <?php echo esc_html($testimonial['content']); ?>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-avatar w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 mr-4"></div>
                                    <div class="author-info">
                                        <div class="author-name"><?php echo esc_html($testimonial['author']); ?></div>
                                        <div class="author-title"><?php echo esc_html($testimonial['title']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="section-padding bg-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8 text-primary-100">
                    <?php esc_html_e('Get the latest aquarium care tips, new arrivals, and exclusive offers delivered to your inbox.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-md mx-auto flex gap-2" action="#" method="post">
                    <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>" class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white" required>
                    <button type="submit" class="px-6 py-3 bg-accent-600 text-white rounded-lg hover:bg-accent-700 transition-colors font-medium">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </form>
                
                <p class="text-sm text-primary-200 mt-4">
                    <?php esc_html_e('No spam, unsubscribe at any time.', 'aqualuxe'); ?>
                </p>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>