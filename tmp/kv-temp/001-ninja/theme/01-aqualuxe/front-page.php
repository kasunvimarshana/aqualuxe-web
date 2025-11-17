<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    if (get_theme_mod('aqualuxe_enable_hero', true)) :
        $hero_style = get_theme_mod('aqualuxe_hero_style', 'default');
        $hero_bg_image = get_theme_mod('aqualuxe_hero_bg_image', get_template_directory_uri() . '/assets/images/hero-bg.jpg');
        $hero_title = get_theme_mod('aqualuxe_hero_title', __('Premium Aquarium Products & Services', 'aqualuxe'));
        $hero_subtitle = get_theme_mod('aqualuxe_hero_subtitle', __('Discover the beauty of aquatic life with our high-quality products and expert services', 'aqualuxe'));
        $hero_button_text = get_theme_mod('aqualuxe_hero_button_text', __('Shop Now', 'aqualuxe'));
        $hero_button_url = get_theme_mod('aqualuxe_hero_button_url', '#');
        $hero_button2_text = get_theme_mod('aqualuxe_hero_button2_text', __('Our Services', 'aqualuxe'));
        $hero_button2_url = get_theme_mod('aqualuxe_hero_button2_url', '#');
    ?>
        <section id="hero" class="hero-section relative <?php echo esc_attr($hero_style); ?>-style">
            <?php if ($hero_style === 'default' || $hero_style === 'overlay') : ?>
                <div class="hero-bg relative h-[600px] md:h-[700px] bg-cover bg-center" style="background-image: url('<?php echo esc_url($hero_bg_image); ?>');">
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                        <div class="hero-content max-w-2xl text-white">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                            <p class="text-xl md:text-2xl mb-8"><?php echo esc_html($hero_subtitle); ?></p>
                            <div class="hero-buttons flex flex-wrap gap-4">
                                <?php if ($hero_button_text) : ?>
                                    <a href="<?php echo esc_url($hero_button_url); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                                        <?php echo esc_html($hero_button_text); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($hero_button2_text) : ?>
                                    <a href="<?php echo esc_url($hero_button2_url); ?>" class="btn-secondary inline-block px-6 py-3 bg-white hover:bg-gray-100 text-primary-600 rounded-md transition-colors">
                                        <?php echo esc_html($hero_button2_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($hero_style === 'split') : ?>
                <div class="hero-split grid md:grid-cols-2 min-h-[600px]">
                    <div class="hero-content-col bg-primary-900 text-white p-8 md:p-12 lg:p-16 flex items-center">
                        <div class="hero-content max-w-lg">
                            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                            <p class="text-lg md:text-xl mb-8"><?php echo esc_html($hero_subtitle); ?></p>
                            <div class="hero-buttons flex flex-wrap gap-4">
                                <?php if ($hero_button_text) : ?>
                                    <a href="<?php echo esc_url($hero_button_url); ?>" class="btn-primary inline-block px-6 py-3 bg-white hover:bg-gray-100 text-primary-900 rounded-md transition-colors">
                                        <?php echo esc_html($hero_button_text); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($hero_button2_text) : ?>
                                    <a href="<?php echo esc_url($hero_button2_url); ?>" class="btn-secondary inline-block px-6 py-3 border border-white hover:bg-white hover:text-primary-900 text-white rounded-md transition-colors">
                                        <?php echo esc_html($hero_button2_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image-col bg-cover bg-center" style="background-image: url('<?php echo esc_url($hero_bg_image); ?>');"></div>
                </div>
            <?php elseif ($hero_style === 'video') : 
                $hero_video_url = get_theme_mod('aqualuxe_hero_video_url', '');
            ?>
                <div class="hero-video relative h-[600px] md:h-[700px] overflow-hidden">
                    <div class="absolute inset-0 bg-black bg-opacity-50 z-10"></div>
                    <?php if ($hero_video_url) : ?>
                        <video class="absolute inset-0 w-full h-full object-cover" autoplay muted loop>
                            <source src="<?php echo esc_url($hero_video_url); ?>" type="video/mp4">
                        </video>
                    <?php else : ?>
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url($hero_bg_image); ?>');"></div>
                    <?php endif; ?>
                    <div class="container mx-auto px-4 h-full flex items-center relative z-20">
                        <div class="hero-content max-w-2xl text-white">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                            <p class="text-xl md:text-2xl mb-8"><?php echo esc_html($hero_subtitle); ?></p>
                            <div class="hero-buttons flex flex-wrap gap-4">
                                <?php if ($hero_button_text) : ?>
                                    <a href="<?php echo esc_url($hero_button_url); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                                        <?php echo esc_html($hero_button_text); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($hero_button2_text) : ?>
                                    <a href="<?php echo esc_url($hero_button2_url); ?>" class="btn-secondary inline-block px-6 py-3 bg-white hover:bg-gray-100 text-primary-600 rounded-md transition-colors">
                                        <?php echo esc_html($hero_button2_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php
    // Features Section
    if (get_theme_mod('aqualuxe_enable_features', true)) :
        $features_title = get_theme_mod('aqualuxe_features_title', __('Why Choose AquaLuxe', 'aqualuxe'));
        $features_subtitle = get_theme_mod('aqualuxe_features_subtitle', __('Discover the benefits of our premium aquarium products and services', 'aqualuxe'));
    ?>
        <section id="features" class="features-section py-16 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($features_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($features_subtitle); ?></p>
                </div>
                
                <div class="features-grid grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    for ($i = 1; $i <= 6; $i++) :
                        $feature_icon = get_theme_mod("aqualuxe_feature_{$i}_icon", "icon-{$i}");
                        $feature_title = get_theme_mod("aqualuxe_feature_{$i}_title", __('Feature Title', 'aqualuxe'));
                        $feature_desc = get_theme_mod("aqualuxe_feature_{$i}_desc", __('Feature description goes here.', 'aqualuxe'));
                        
                        if ($feature_title) :
                    ?>
                        <div class="feature-item p-6 bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="feature-icon text-primary-600 dark:text-primary-400 mb-4">
                                <?php if ($i === 1) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                <?php elseif ($i === 2) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                <?php elseif ($i === 3) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                <?php elseif ($i === 4) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                <?php elseif ($i === 5) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                <?php else : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <h3 class="feature-title text-xl font-bold mb-2"><?php echo esc_html($feature_title); ?></h3>
                            <p class="feature-desc text-gray-600 dark:text-gray-300"><?php echo esc_html($feature_desc); ?></p>
                        </div>
                    <?php
                        endif;
                    endfor;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Featured Products Section
    if (get_theme_mod('aqualuxe_enable_featured_products', true) && class_exists('WooCommerce')) :
        $products_title = get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'));
        $products_subtitle = get_theme_mod('aqualuxe_featured_products_subtitle', __('Explore our selection of premium aquarium products', 'aqualuxe'));
        $products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
    ?>
        <section id="featured-products" class="featured-products-section py-16">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($products_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($products_subtitle); ?></p>
                </div>
                
                <div class="products-grid">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $products_count,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'featured',
                            ),
                        ),
                    );
                    $featured_products = new WP_Query($args);
                    
                    if ($featured_products->have_posts()) :
                        woocommerce_product_loop_start();
                        
                        while ($featured_products->have_posts()) : $featured_products->the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        
                        woocommerce_product_loop_end();
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-center"><?php esc_html_e('No featured products found.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                        <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Services Section
    if (get_theme_mod('aqualuxe_enable_services', true)) :
        $services_title = get_theme_mod('aqualuxe_services_title', __('Our Services', 'aqualuxe'));
        $services_subtitle = get_theme_mod('aqualuxe_services_subtitle', __('Professional aquarium services for all your needs', 'aqualuxe'));
        $services_count = get_theme_mod('aqualuxe_services_count', 3);
    ?>
        <section id="services" class="services-section py-16 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($services_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($services_subtitle); ?></p>
                </div>
                
                <div class="services-grid grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $args = array(
                        'post_type' => 'aqualuxe_service',
                        'posts_per_page' => $services_count,
                    );
                    $services_query = new WP_Query($args);
                    
                    if ($services_query->have_posts()) :
                        while ($services_query->have_posts()) : $services_query->the_post();
                    ?>
                        <div class="service-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-content p-6">
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                    <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-center col-span-3"><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(get_post_type_archive_link('aqualuxe_service')); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                        <?php esc_html_e('View All Services', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Testimonials Section
    if (get_theme_mod('aqualuxe_enable_testimonials', true)) :
        $testimonials_title = get_theme_mod('aqualuxe_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
        $testimonials_subtitle = get_theme_mod('aqualuxe_testimonials_subtitle', __('Read testimonials from our satisfied customers', 'aqualuxe'));
        $testimonials_count = get_theme_mod('aqualuxe_testimonials_count', 3);
    ?>
        <section id="testimonials" class="testimonials-section py-16">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($testimonials_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($testimonials_subtitle); ?></p>
                </div>
                
                <div class="testimonials-slider max-w-4xl mx-auto">
                    <?php
                    $args = array(
                        'post_type' => 'aqualuxe_testimonial',
                        'posts_per_page' => $testimonials_count,
                    );
                    $testimonials_query = new WP_Query($args);
                    
                    if ($testimonials_query->have_posts()) :
                    ?>
                        <div class="testimonials-grid grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post(); 
                            $rating = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_rating', true);
                            $position = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_position', true);
                        ?>
                            <div class="testimonial-item bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                <div class="testimonial-content mb-4">
                                    <svg class="h-8 w-8 text-primary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                    </svg>
                                    <div class="testimonial-text text-gray-600 dark:text-gray-300">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                
                                <div class="testimonial-meta flex items-center">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="testimonial-avatar mr-4">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-info">
                                        <h4 class="testimonial-name font-bold"><?php the_title(); ?></h4>
                                        <?php if ($position) : ?>
                                            <p class="testimonial-position text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html($position); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($rating) : ?>
                                            <div class="testimonial-rating flex text-yellow-400 mt-1">
                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                    <?php if ($i <= $rating) : ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    <?php else : ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                        </svg>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        endwhile;
                        wp_reset_postdata();
                        ?>
                        </div>
                    <?php else : ?>
                        <p class="text-center"><?php esc_html_e('No testimonials found.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // CTA Section
    if (get_theme_mod('aqualuxe_enable_cta', true)) :
        $cta_title = get_theme_mod('aqualuxe_cta_title', __('Ready to Transform Your Aquarium?', 'aqualuxe'));
        $cta_text = get_theme_mod('aqualuxe_cta_text', __('Contact us today to learn more about our products and services.', 'aqualuxe'));
        $cta_button_text = get_theme_mod('aqualuxe_cta_button_text', __('Contact Us', 'aqualuxe'));
        $cta_button_url = get_theme_mod('aqualuxe_cta_button_url', '#');
        $cta_bg_image = get_theme_mod('aqualuxe_cta_bg_image', get_template_directory_uri() . '/assets/images/cta-bg.jpg');
    ?>
        <section id="cta" class="cta-section py-16 bg-cover bg-center relative" style="background-image: url('<?php echo esc_url($cta_bg_image); ?>');">
            <div class="absolute inset-0 bg-primary-900 bg-opacity-80"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($cta_title); ?></h2>
                    <p class="text-xl mb-8"><?php echo esc_html($cta_text); ?></p>
                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn-primary inline-block px-8 py-4 bg-white hover:bg-gray-100 text-primary-900 rounded-md transition-colors text-lg font-medium">
                        <?php echo esc_html($cta_button_text); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Blog Section
    if (get_theme_mod('aqualuxe_enable_blog', true)) :
        $blog_title = get_theme_mod('aqualuxe_blog_section_title', __('Latest Articles', 'aqualuxe'));
        $blog_subtitle = get_theme_mod('aqualuxe_blog_section_subtitle', __('Read our latest news and aquarium care tips', 'aqualuxe'));
        $blog_count = get_theme_mod('aqualuxe_blog_count', 3);
    ?>
        <section id="latest-blog" class="blog-section py-16">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($blog_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($blog_subtitle); ?></p>
                </div>
                
                <div class="blog-grid grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => $blog_count,
                    );
                    $blog_query = new WP_Query($args);
                    
                    if ($blog_query->have_posts()) :
                        while ($blog_query->have_posts()) : $blog_query->the_post();
                    ?>
                        <article class="blog-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="blog-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-content p-6">
                                <div class="blog-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <span class="post-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="post-author">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php the_author(); ?>
                                    </span>
                                </div>
                                
                                <h3 class="blog-title text-xl font-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="blog-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-center col-span-3"><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn-primary inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                        <?php esc_html_e('View All Articles', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Instagram Feed Section
    if (get_theme_mod('aqualuxe_enable_instagram', false)) :
        $instagram_title = get_theme_mod('aqualuxe_instagram_title', __('Follow Us on Instagram', 'aqualuxe'));
        $instagram_subtitle = get_theme_mod('aqualuxe_instagram_subtitle', __('See our latest photos and updates', 'aqualuxe'));
        $instagram_shortcode = get_theme_mod('aqualuxe_instagram_shortcode', '');
    ?>
        <section id="instagram-feed" class="instagram-section py-16 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($instagram_title); ?></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($instagram_subtitle); ?></p>
                </div>
                
                <div class="instagram-feed">
                    <?php 
                    if ($instagram_shortcode) {
                        echo do_shortcode($instagram_shortcode);
                    } else {
                    ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            <?php for ($i = 1; $i <= 6; $i++) : ?>
                                <div class="instagram-item aspect-square bg-gray-200 dark:bg-gray-700 rounded-md overflow-hidden">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_instagram', '#')); ?>" target="_blank" rel="noopener noreferrer" class="btn-secondary inline-block px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-md transition-colors">
                        <?php esc_html_e('Follow on Instagram', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // Content
    while (have_posts()) :
        the_post();
        
        // If there's content in the page, display it
        if (get_the_content()) :
    ?>
        <section id="page-content" class="page-content-section py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php
        endif;
    endwhile;
    ?>
</main><!-- #main -->

<?php
get_footer();