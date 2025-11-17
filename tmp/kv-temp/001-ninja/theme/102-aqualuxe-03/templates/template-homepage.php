<?php
/**
 * Homepage Template
 * 
 * Template Name: Homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
    
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="hero-background absolute inset-0 z-0">
            <div class="hero-image absolute inset-0 bg-gradient-to-br from-aqua-500 to-primary-600">
                <?php
                $hero_image = get_theme_mod('hero_background_image');
                if ($hero_image) :
                ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('Hero Background', 'aqualuxe'); ?>" class="w-full h-full object-cover opacity-60">
                <?php endif; ?>
            </div>
            <div class="hero-overlay absolute inset-0 bg-black opacity-40"></div>
        </div>
        
        <div class="hero-content relative z-10 container mx-auto px-4 text-center text-white">
            <div class="max-w-4xl mx-auto">
                <h1 class="hero-title text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in">
                    <?php echo esc_html(get_theme_mod('hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'))); ?>
                </h1>
                <p class="hero-subtitle text-xl md:text-2xl mb-8 opacity-90 animate-fade-in" style="animation-delay: 0.2s;">
                    <?php echo esc_html(get_theme_mod('hero_subtitle', __('Globally sourced premium aquatic products and professional services', 'aqualuxe'))); ?>
                </p>
                <div class="hero-actions space-x-4 animate-fade-in" style="animation-delay: 0.4s;">
                    <a href="<?php echo esc_url(get_theme_mod('hero_primary_button_url', '#products')); ?>" class="btn-primary btn-lg">
                        <?php echo esc_html(get_theme_mod('hero_primary_button_text', __('Explore Products', 'aqualuxe'))); ?>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('hero_secondary_button_url', '#services')); ?>" class="btn-outline btn-lg border-white text-white hover:bg-white hover:text-primary-600">
                        <?php echo esc_html(get_theme_mod('hero_secondary_button_text', __('Our Services', 'aqualuxe'))); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce-gentle">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
    
    <!-- Featured Products Section -->
    <?php if (aqualuxe_is_woocommerce_activated()) : ?>
        <section id="products" class="featured-products-section py-16 lg:py-24 bg-white dark:bg-secondary-900">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="section-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                        <?php esc_html_e('Featured Products', 'aqualuxe'); ?>
                    </h2>
                    <p class="section-subtitle text-lg text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
                        <?php esc_html_e('Discover our premium selection of aquatic products, carefully curated for discerning enthusiasts.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="featured-products">
                    <?php
                    echo do_shortcode('[products limit="8" columns="4" visibility="featured"]');
                    ?>
                </div>
                
                <div class="section-footer text-center mt-12">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary btn-lg">
                        <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Services Section -->
    <section id="services" class="services-section py-16 lg:py-24 bg-secondary-50 dark:bg-secondary-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="section-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                    <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                </h2>
                <p class="section-subtitle text-lg text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
                    <?php esc_html_e('Professional aquatic services designed to help you create and maintain the perfect aquatic environment.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $services = get_posts(array(
                    'post_type' => 'service',
                    'numberposts' => 6,
                    'post_status' => 'publish',
                ));
                
                if ($services) :
                    foreach ($services as $service) :
                        setup_postdata($service);
                ?>
                    <div class="service-card card group hover:shadow-lg transition-all duration-300">
                        <?php if (has_post_thumbnail($service->ID)) : ?>
                            <div class="service-image overflow-hidden rounded-t-lg">
                                <a href="<?php echo esc_url(get_permalink($service->ID)); ?>">
                                    <?php echo get_the_post_thumbnail($service->ID, 'aqualuxe-featured-medium', array('class' => 'w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h3 class="service-title text-xl font-semibold text-secondary-900 dark:text-secondary-100 mb-3">
                                <a href="<?php echo esc_url(get_permalink($service->ID)); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                    <?php echo esc_html(get_the_title($service->ID)); ?>
                                </a>
                            </h3>
                            <p class="service-excerpt text-secondary-600 dark:text-secondary-400 mb-4">
                                <?php echo esc_html(get_the_excerpt($service->ID)); ?>
                            </p>
                            <a href="<?php echo esc_url(get_permalink($service->ID)); ?>" class="service-link inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php
                    endforeach;
                    wp_reset_postdata();
                else :
                ?>
                    <!-- Default Services -->
                    <div class="service-card card">
                        <div class="card-body text-center">
                            <div class="service-icon mb-4">
                                <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <h3 class="service-title text-xl font-semibold text-secondary-900 dark:text-secondary-100 mb-3">
                                <?php esc_html_e('Aquarium Design', 'aqualuxe'); ?>
                            </h3>
                            <p class="service-description text-secondary-600 dark:text-secondary-400">
                                <?php esc_html_e('Custom aquarium design and installation services for residential and commercial spaces.', 'aqualuxe'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="service-card card">
                        <div class="card-body text-center">
                            <div class="service-icon mb-4">
                                <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="service-title text-xl font-semibold text-secondary-900 dark:text-secondary-100 mb-3">
                                <?php esc_html_e('Maintenance Services', 'aqualuxe'); ?>
                            </h3>
                            <p class="service-description text-secondary-600 dark:text-secondary-400">
                                <?php esc_html_e('Regular maintenance and care services to keep your aquatic environment healthy and beautiful.', 'aqualuxe'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="service-card card">
                        <div class="card-body text-center">
                            <div class="service-icon mb-4">
                                <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="service-title text-xl font-semibold text-secondary-900 dark:text-secondary-100 mb-3">
                                <?php esc_html_e('Consultation', 'aqualuxe'); ?>
                            </h3>
                            <p class="service-description text-secondary-600 dark:text-secondary-400">
                                <?php esc_html_e('Expert consultation services for aquatic system planning, troubleshooting, and optimization.', 'aqualuxe'); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section py-16 lg:py-24 bg-white dark:bg-secondary-900">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-content">
                    <h2 class="section-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100 mb-6">
                        <?php echo esc_html(get_theme_mod('about_title', __('About AquaLuxe', 'aqualuxe'))); ?>
                    </h2>
                    <div class="about-text text-lg text-secondary-600 dark:text-secondary-400 space-y-4">
                        <?php
                        $about_content = get_theme_mod('about_content', __('AquaLuxe is dedicated to bringing elegance to aquatic life globally. We specialize in premium aquatic products, professional services, and innovative solutions for aquarium enthusiasts and professionals worldwide.', 'aqualuxe'));
                        echo wp_kses_post(wpautop($about_content));
                        ?>
                    </div>
                    <div class="about-stats grid grid-cols-2 gap-6 mt-8">
                        <div class="stat">
                            <div class="stat-number text-3xl font-bold text-primary-600 dark:text-primary-400">
                                <?php echo esc_html(get_theme_mod('stat_years', '15+')); ?>
                            </div>
                            <div class="stat-label text-sm text-secondary-600 dark:text-secondary-400">
                                <?php esc_html_e('Years Experience', 'aqualuxe'); ?>
                            </div>
                        </div>
                        <div class="stat">
                            <div class="stat-number text-3xl font-bold text-primary-600 dark:text-primary-400">
                                <?php echo esc_html(get_theme_mod('stat_customers', '1000+')); ?>
                            </div>
                            <div class="stat-label text-sm text-secondary-600 dark:text-secondary-400">
                                <?php esc_html_e('Happy Customers', 'aqualuxe'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about-image">
                    <?php
                    $about_image = get_theme_mod('about_image');
                    if ($about_image) :
                    ?>
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>" class="w-full h-auto rounded-lg shadow-luxury">
                    <?php else : ?>
                        <div class="about-image-placeholder bg-gradient-to-br from-aqua-400 to-primary-600 rounded-lg shadow-luxury h-96 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section py-16 lg:py-24 bg-secondary-50 dark:bg-secondary-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="section-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                    <?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?>
                </h2>
                <p class="section-subtitle text-lg text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
                    <?php esc_html_e('Hear from our satisfied customers who trust AquaLuxe for their aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $testimonials = get_posts(array(
                    'post_type' => 'testimonial',
                    'numberposts' => 3,
                    'post_status' => 'publish',
                ));
                
                if ($testimonials) :
                    foreach ($testimonials as $testimonial) :
                        setup_postdata($testimonial);
                        $author = get_post_meta($testimonial->ID, 'testimonial_author', true);
                        $company = get_post_meta($testimonial->ID, 'testimonial_company', true);
                        $rating = get_post_meta($testimonial->ID, 'testimonial_rating', true);
                ?>
                    <div class="testimonial-card card">
                        <div class="card-body">
                            <?php if ($rating) : ?>
                                <div class="testimonial-rating flex items-center mb-4">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <svg class="w-4 h-4 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-secondary-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <blockquote class="testimonial-content text-secondary-700 dark:text-secondary-300 mb-4">
                                "<?php echo esc_html(get_the_content($testimonial->ID)); ?>"
                            </blockquote>
                            
                            <div class="testimonial-author">
                                <div class="author-name font-semibold text-secondary-900 dark:text-secondary-100">
                                    <?php echo esc_html($author ?: get_the_title($testimonial->ID)); ?>
                                </div>
                                <?php if ($company) : ?>
                                    <div class="author-company text-sm text-secondary-600 dark:text-secondary-400">
                                        <?php echo esc_html($company); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                    endforeach;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="newsletter-section py-16 lg:py-24 bg-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="section-title text-3xl lg:text-4xl font-bold mb-6">
                    <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
                </h2>
                <p class="section-subtitle text-lg opacity-90 mb-8">
                    <?php esc_html_e('Subscribe to our newsletter for the latest aquatic trends, product updates, and exclusive offers.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form flex flex-col md:flex-row gap-4 max-w-md mx-auto">
                    <div class="flex-1">
                        <label for="newsletter-email" class="screen-reader-text"><?php esc_html_e('Email Address', 'aqualuxe'); ?></label>
                        <input type="email" 
                               id="newsletter-email" 
                               name="email" 
                               class="form-input w-full text-secondary-900" 
                               placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>" 
                               required>
                    </div>
                    <button type="submit" class="btn btn-secondary whitespace-nowrap">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </form>
            </div>
        </div>
    </section>
    
</main>

<?php
get_footer();