<?php
/**
 * Front page template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main front-page" role="main">
    
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
        
        <!-- Background -->
        <div class="hero-background absolute inset-0">
            <?php if (has_header_image()) : ?>
                <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-600/80 via-primary-700/70 to-primary-800/80"></div>
            <?php else : ?>
                <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800"></div>
            <?php endif; ?>
        </div>
        
        <!-- Animated elements -->
        <div class="hero-elements absolute inset-0 overflow-hidden pointer-events-none">
            <div class="floating-element absolute top-20 left-10 w-4 h-4 bg-white/20 rounded-full animate-float" style="animation-delay: 0s;"></div>
            <div class="floating-element absolute top-40 right-20 w-6 h-6 bg-white/15 rounded-full animate-float" style="animation-delay: 1s;"></div>
            <div class="floating-element absolute bottom-40 left-1/4 w-3 h-3 bg-white/25 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="floating-element absolute bottom-60 right-1/3 w-5 h-5 bg-white/10 rounded-full animate-float" style="animation-delay: 1.5s;"></div>
        </div>
        
        <!-- Content -->
        <div class="hero-content relative z-10 max-w-6xl mx-auto px-4 text-center">
            <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 animate-fade-in" data-customize="aqualuxe_hero_title">
                <?php echo get_theme_mod('aqualuxe_hero_title', esc_html__('Bringing Elegance to Aquatic Life', 'aqualuxe')); ?>
            </h1>
            
            <p class="hero-subtitle text-xl md:text-2xl text-primary-100 mb-8 max-w-3xl mx-auto animate-slide-up" data-customize="aqualuxe_hero_subtitle">
                <?php echo get_theme_mod('aqualuxe_hero_subtitle', esc_html__('Discover the world\'s finest aquatic species, premium equipment, and expert care solutions for your aquatic paradise.', 'aqualuxe')); ?>
            </p>
            
            <div class="hero-actions flex flex-col sm:flex-row gap-4 justify-center items-center animate-scale-in">
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-accent btn-lg">
                        <?php esc_html_e('Explore Products', 'aqualuxe'); ?>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <a href="#about" class="btn btn-ghost text-white border-white hover:bg-white hover:text-primary-600">
                    <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce-gentle">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
        
    </section>
    
    <!-- Featured Products Section -->
    <?php if (class_exists('WooCommerce')) : ?>
    <section id="featured-products" class="featured-products py-20 bg-white dark:bg-neutral-900">
        <div class="container mx-auto px-4">
            
            <header class="section-header text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-neutral-800 dark:text-neutral-100 mb-6" data-animate="fade">
                    <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-neutral-600 dark:text-neutral-300 max-w-3xl mx-auto" data-animate="fade">
                    <?php esc_html_e('Discover our handpicked selection of premium aquatic species and essential equipment for your aquatic paradise.', 'aqualuxe'); ?>
                </p>
            </header>
            
            <div class="featured-products-grid">
                <?php
                $featured_products = wc_get_featured_product_ids();
                
                if (!empty($featured_products)) {
                    $args = [
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'posts_per_page' => 8,
                        'post__in'       => $featured_products,
                        'orderby'        => 'post__in',
                    ];
                    
                    $featured_query = new WP_Query($args);
                    
                    if ($featured_query->have_posts()) {
                        echo '<ul class="products grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">';
                        
                        while ($featured_query->have_posts()) {
                            $featured_query->the_post();
                            wc_get_template_part('content', 'product');
                        }
                        
                        echo '</ul>';
                        wp_reset_postdata();
                    }
                } else {
                    // Fallback to recent products if no featured products
                    echo do_shortcode('[products limit="8" columns="4" orderby="date" order="DESC"]');
                }
                ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            
        </div>
    </section>
    <?php endif; ?>
    
    <!-- About Section -->
    <section id="about" class="about-section py-20 bg-neutral-50 dark:bg-neutral-800">
        <div class="container mx-auto px-4">
            
            <div class="grid gap-12 lg:grid-cols-2 items-center">
                
                <div class="about-content" data-animate="fade">
                    <h2 class="text-4xl lg:text-5xl font-bold text-neutral-800 dark:text-neutral-100 mb-6">
                        <?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?>
                    </h2>
                    
                    <p class="text-lg text-neutral-600 dark:text-neutral-300 mb-6">
                        <?php esc_html_e('For over two decades, AquaLuxe has been the premier destination for aquatic enthusiasts worldwide. We specialize in rare and exotic aquatic species, premium equipment, and comprehensive care solutions.', 'aqualuxe'); ?>
                    </p>
                    
                    <p class="text-lg text-neutral-600 dark:text-neutral-300 mb-8">
                        <?php esc_html_e('Our commitment to excellence, sustainability, and customer satisfaction has made us the trusted choice for hobbyists, professionals, and institutions globally.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="about-features grid gap-4 sm:grid-cols-2 mb-8">
                        <div class="feature-item flex items-center">
                            <div class="feature-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">
                                <?php esc_html_e('Premium Quality', 'aqualuxe'); ?>
                            </span>
                        </div>
                        
                        <div class="feature-item flex items-center">
                            <div class="feature-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">
                                <?php esc_html_e('Expert Support', 'aqualuxe'); ?>
                            </span>
                        </div>
                        
                        <div class="feature-item flex items-center">
                            <div class="feature-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">
                                <?php esc_html_e('Global Shipping', 'aqualuxe'); ?>
                            </span>
                        </div>
                        
                        <div class="feature-item flex items-center">
                            <div class="feature-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-neutral-800 dark:text-neutral-100">
                                <?php esc_html_e('Sustainability Focus', 'aqualuxe'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <a href="<?php echo esc_url(get_permalink(get_page_by_title('About'))); ?>" class="btn btn-primary">
                        <?php esc_html_e('Learn More About Us', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <div class="about-image" data-animate="scale">
                    <div class="image-wrapper relative">
                        <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/dist/images/placeholder.jpg'); ?>" alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>" class="w-full h-auto rounded-lg shadow-soft">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/20 to-transparent rounded-lg"></div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="newsletter-section py-20 bg-primary-600">
        <div class="container mx-auto px-4 text-center">
            
            <div class="newsletter-content max-w-3xl mx-auto">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6" data-animate="fade">
                    <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
                </h2>
                
                <p class="text-xl text-primary-100 mb-8" data-animate="fade">
                    <?php esc_html_e('Subscribe to our newsletter for the latest aquatic care tips, product updates, and exclusive offers.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-md mx-auto" data-newsletter-form data-animate="scale">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input 
                            type="email" 
                            class="flex-1 px-4 py-3 rounded-lg border-0 text-neutral-900 placeholder-neutral-500 focus:ring-2 focus:ring-accent-500"
                            placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>"
                            required
                        >
                        <button type="submit" class="btn btn-accent whitespace-nowrap">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <p class="text-sm text-primary-200 mt-4">
                        <?php esc_html_e('By subscribing, you agree to our Privacy Policy and Terms of Service.', 'aqualuxe'); ?>
                    </p>
                </form>
                
            </div>
            
        </div>
    </section>
    
</main>

<?php get_footer(); ?>