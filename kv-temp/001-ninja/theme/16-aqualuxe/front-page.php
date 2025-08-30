<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    if (get_theme_mod('aqualuxe_show_hero', true)) :
    ?>
    <section class="hero-section relative bg-primary-dark overflow-hidden">
        <div class="absolute inset-0 bg-bubble-pattern opacity-10"></div>
        
        <?php if (get_theme_mod('aqualuxe_hero_type', 'image') === 'slider') : ?>
            <!-- Hero Slider -->
            <div class="hero-slider">
                <?php
                // Get slider content from customizer or default
                $slider_items = get_theme_mod('aqualuxe_hero_slider', [
                    [
                        'image' => get_template_directory_uri() . '/assets/images/hero/hero-1.jpg',
                        'title' => esc_html__('Premium Ornamental Fish', 'aqualuxe'),
                        'description' => esc_html__('Discover our exclusive collection of rare and exotic fish species', 'aqualuxe'),
                        'button_text' => esc_html__('Explore Collection', 'aqualuxe'),
                        'button_url' => '#',
                    ],
                    [
                        'image' => get_template_directory_uri() . '/assets/images/hero/hero-2.jpg',
                        'title' => esc_html__('Expert Aquarium Solutions', 'aqualuxe'),
                        'description' => esc_html__('Professional aquarium design, installation, and maintenance services', 'aqualuxe'),
                        'button_text' => esc_html__('Our Services', 'aqualuxe'),
                        'button_url' => '#',
                    ],
                ]);
                
                foreach ($slider_items as $item) :
                ?>
                    <div class="hero-slide relative min-h-[600px] flex items-center">
                        <div class="absolute inset-0 z-0">
                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-primary-dark bg-opacity-60"></div>
                        </div>
                        <div class="container-fluid relative z-10 py-20">
                            <div class="max-w-3xl">
                                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6"><?php echo esc_html($item['title']); ?></h1>
                                <p class="text-xl md:text-2xl text-white opacity-90 mb-8"><?php echo esc_html($item['description']); ?></p>
                                <?php if (!empty($item['button_text'])) : ?>
                                    <a href="<?php echo esc_url($item['button_url']); ?>" class="btn btn-accent"><?php echo esc_html($item['button_text']); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <!-- Hero Image -->
            <div class="hero-image relative min-h-[600px] flex items-center">
                <div class="absolute inset-0 z-0">
                    <img src="<?php echo esc_url(get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero/hero-1.jpg')); ?>" alt="<?php echo esc_attr(get_theme_mod('aqualuxe_hero_title', esc_html__('Premium Ornamental Fish', 'aqualuxe'))); ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-primary-dark bg-opacity-60"></div>
                </div>
                <div class="container-fluid relative z-10 py-20">
                    <div class="max-w-3xl">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6"><?php echo esc_html(get_theme_mod('aqualuxe_hero_title', esc_html__('Premium Ornamental Fish', 'aqualuxe'))); ?></h1>
                        <p class="text-xl md:text-2xl text-white opacity-90 mb-8"><?php echo esc_html(get_theme_mod('aqualuxe_hero_description', esc_html__('Discover our exclusive collection of rare and exotic fish species', 'aqualuxe'))); ?></p>
                        <?php if (get_theme_mod('aqualuxe_hero_button_text')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_hero_button_url', '#')); ?>" class="btn btn-accent"><?php echo esc_html(get_theme_mod('aqualuxe_hero_button_text', esc_html__('Explore Collection', 'aqualuxe'))); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php
    // Features Section
    if (get_theme_mod('aqualuxe_show_features', true)) :
    ?>
    <section class="features-section py-16 bg-white dark:bg-dark-bg">
        <div class="container-fluid">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_features_title', esc_html__('Why Choose AquaLuxe', 'aqualuxe'))); ?></h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_features_subtitle', esc_html__('We provide premium quality ornamental fish and exceptional aquarium services', 'aqualuxe'))); ?></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                // Feature 1
                ?>
                <div class="feature-card text-center p-6 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none transition-transform duration-300 hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-primary-light dark:bg-primary-dark text-primary dark:text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_feature1_title', esc_html__('Premium Quality', 'aqualuxe'))); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html(get_theme_mod('aqualuxe_feature1_description', esc_html__('We source the highest quality ornamental fish from trusted breeders worldwide.', 'aqualuxe'))); ?></p>
                </div>

                <?php
                // Feature 2
                ?>
                <div class="feature-card text-center p-6 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none transition-transform duration-300 hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-primary-light dark:bg-primary-dark text-primary dark:text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_feature2_title', esc_html__('Expert Support', 'aqualuxe'))); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html(get_theme_mod('aqualuxe_feature2_description', esc_html__('Our team of aquatic specialists provides expert advice and ongoing support.', 'aqualuxe'))); ?></p>
                </div>

                <?php
                // Feature 3
                ?>
                <div class="feature-card text-center p-6 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none transition-transform duration-300 hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-primary-light dark:bg-primary-dark text-primary dark:text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_feature3_title', esc_html__('Global Shipping', 'aqualuxe'))); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html(get_theme_mod('aqualuxe_feature3_description', esc_html__('We deliver healthy fish to customers worldwide with our specialized shipping methods.', 'aqualuxe'))); ?></p>
                </div>

                <?php
                // Feature 4
                ?>
                <div class="feature-card text-center p-6 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none transition-transform duration-300 hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-primary-light dark:bg-primary-dark text-primary dark:text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_feature4_title', esc_html__('Sustainable Practices', 'aqualuxe'))); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html(get_theme_mod('aqualuxe_feature4_description', esc_html__('We are committed to ethical breeding and environmentally responsible practices.', 'aqualuxe'))); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Featured Products Section
    if (get_theme_mod('aqualuxe_show_featured_products', true) && class_exists('WooCommerce')) :
    ?>
    <section class="featured-products-section py-16 bg-gray-50 dark:bg-dark-bg">
        <div class="container-fluid">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_featured_products_title', esc_html__('Featured Products', 'aqualuxe'))); ?></h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_featured_products_subtitle', esc_html__('Explore our selection of premium fish and aquarium supplies', 'aqualuxe'))); ?></p>
            </div>

            <div class="featured-products">
                <?php
                // Display featured products
                $featured_products_count = get_theme_mod('aqualuxe_featured_products_count', 4);
                
                echo do_shortcode('[products featured="true" limit="' . intval($featured_products_count) . '" columns="4"]');
                ?>
            </div>

            <?php if (get_theme_mod('aqualuxe_featured_products_button_text')) : ?>
                <div class="text-center mt-10">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_featured_products_button_url', wc_get_page_permalink('shop'))); ?>" class="btn btn-primary">
                        <?php echo esc_html(get_theme_mod('aqualuxe_featured_products_button_text', esc_html__('View All Products', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Featured Fish Species Section
    if (get_theme_mod('aqualuxe_show_fish_species', true)) :
    ?>
    <section class="fish-species-section py-16 bg-white dark:bg-dark-bg relative overflow-hidden">
        <div class="absolute inset-0 bg-water-pattern opacity-5"></div>
        <div class="container-fluid relative z-10">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_fish_species_title', esc_html__('Exotic Fish Species', 'aqualuxe'))); ?></h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_fish_species_subtitle', esc_html__('Discover our collection of rare and beautiful ornamental fish', 'aqualuxe'))); ?></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Query fish species
                $fish_args = array(
                    'post_type' => 'fish-species',
                    'posts_per_page' => get_theme_mod('aqualuxe_fish_species_count', 6),
                );
                
                $fish_query = new WP_Query($fish_args);
                
                if ($fish_query->have_posts()) :
                    while ($fish_query->have_posts()) : $fish_query->the_post();
                        ?>
                        <div class="fish-card bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="fish-image relative overflow-hidden h-64">
                                    <?php the_post_thumbnail('aqualuxe-card', ['class' => 'w-full h-full object-cover']); ?>
                                    
                                    <?php
                                    // Display water type badge if available
                                    $water_types = get_the_terms(get_the_ID(), 'water_type');
                                    if ($water_types && !is_wp_error($water_types)) :
                                        $water_type = array_shift($water_types);
                                        ?>
                                        <span class="absolute top-4 right-4 bg-primary text-white text-xs px-2 py-1 rounded-full">
                                            <?php echo esc_html($water_type->name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="fish-content p-6">
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <?php
                                // Display scientific name if available
                                $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
                                if ($scientific_name) :
                                    ?>
                                    <p class="text-gray-600 dark:text-gray-400 italic mb-3"><?php echo esc_html($scientific_name); ?></p>
                                <?php endif; ?>
                                
                                <div class="fish-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center">
                                    <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="col-span-full text-center">
                        <p><?php esc_html_e('No fish species found.', 'aqualuxe'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>

            <?php if (get_theme_mod('aqualuxe_fish_species_button_text')) : ?>
                <div class="text-center mt-10">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_fish_species_button_url', home_url('/fish-species/'))); ?>" class="btn btn-primary">
                        <?php echo esc_html(get_theme_mod('aqualuxe_fish_species_button_text', esc_html__('View All Species', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Testimonials Section
    if (get_theme_mod('aqualuxe_show_testimonials', true)) :
    ?>
    <section class="testimonials-section py-16 bg-primary-dark text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-bubble-pattern opacity-10"></div>
        <div class="container-fluid relative z-10">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_testimonials_title', esc_html__('What Our Customers Say', 'aqualuxe'))); ?></h2>
                <p class="text-xl opacity-90 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_testimonials_subtitle', esc_html__('Hear from our satisfied customers around the world', 'aqualuxe'))); ?></p>
            </div>

            <div class="testimonials-slider">
                <?php
                // Query testimonials
                $testimonial_args = array(
                    'post_type' => 'testimonial',
                    'posts_per_page' => get_theme_mod('aqualuxe_testimonials_count', 6),
                );
                
                $testimonial_query = new WP_Query($testimonial_args);
                
                if ($testimonial_query->have_posts()) :
                    while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
                        // Get testimonial meta
                        $rating = get_post_meta(get_the_ID(), 'rating', true);
                        $position = get_post_meta(get_the_ID(), 'position', true);
                        $company = get_post_meta(get_the_ID(), 'company', true);
                        ?>
                        <div class="testimonial-item p-6 md:p-8 bg-white dark:bg-dark-card rounded-xl shadow-soft text-gray-800 dark:text-white">
                            <div class="testimonial-content mb-6">
                                <?php if ($rating) : ?>
                                    <div class="rating flex mb-4">
                                        <?php
                                        $rating = min(5, max(1, intval($rating)));
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>';
                                            } else {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <blockquote class="text-lg italic">
                                    <?php the_content(); ?>
                                </blockquote>
                            </div>
                            
                            <div class="testimonial-author flex items-center">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="author-image mr-4">
                                        <?php the_post_thumbnail('thumbnail', ['class' => 'w-12 h-12 rounded-full']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="author-info">
                                    <h4 class="author-name font-bold"><?php the_title(); ?></h4>
                                    <?php if ($position || $company) : ?>
                                        <p class="author-title text-sm text-gray-600 dark:text-gray-400">
                                            <?php 
                                            if ($position) {
                                                echo esc_html($position);
                                                if ($company) {
                                                    echo ', ' . esc_html($company);
                                                }
                                            } elseif ($company) {
                                                echo esc_html($company);
                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="text-center">
                        <p><?php esc_html_e('No testimonials found.', 'aqualuxe'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Latest Blog Posts Section
    if (get_theme_mod('aqualuxe_show_blog', true)) :
    ?>
    <section class="blog-section py-16 bg-white dark:bg-dark-bg">
        <div class="container-fluid">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_blog_title', esc_html__('Latest Articles', 'aqualuxe'))); ?></h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_blog_subtitle', esc_html__('Expert tips and insights about aquarium care and fish keeping', 'aqualuxe'))); ?></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Query blog posts
                $blog_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => get_theme_mod('aqualuxe_blog_count', 3),
                );
                
                $blog_query = new WP_Query($blog_args);
                
                if ($blog_query->have_posts()) :
                    while ($blog_query->have_posts()) : $blog_query->the_post();
                        ?>
                        <article class="blog-card bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="blog-image relative overflow-hidden h-48">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-card', ['class' => 'w-full h-full object-cover']); ?>
                                    </a>
                                    <div class="blog-date absolute top-4 left-4 bg-accent text-primary-dark text-xs px-3 py-1 rounded-full">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-content p-6">
                                <?php
                                // Display categories
                                $categories = get_the_category();
                                if ($categories) :
                                    ?>
                                    <div class="blog-categories mb-2">
                                        <?php
                                        foreach ($categories as $category) {
                                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-xs text-primary hover:text-primary-dark mr-2">' . esc_html($category->name) . '</a>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="text-xl font-bold mb-3">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="blog-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <div class="blog-meta flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <span class="blog-author flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php the_author(); ?>
                                    </span>
                                    
                                    <span class="blog-comments ml-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <?php comments_number('0', '1', '%'); ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="col-span-full text-center">
                        <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>

            <?php if (get_theme_mod('aqualuxe_blog_button_text')) : ?>
                <div class="text-center mt-10">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_blog_button_url', get_permalink(get_option('page_for_posts')))); ?>" class="btn btn-primary">
                        <?php echo esc_html(get_theme_mod('aqualuxe_blog_button_text', esc_html__('View All Articles', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Newsletter Section
    if (get_theme_mod('aqualuxe_show_newsletter', true)) :
    ?>
    <section class="newsletter-section py-16 bg-primary text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-water-pattern opacity-10"></div>
        <div class="container-fluid relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_newsletter_title', esc_html__('Subscribe to Our Newsletter', 'aqualuxe'))); ?></h2>
                <p class="text-xl opacity-90 mb-8"><?php echo esc_html(get_theme_mod('aqualuxe_newsletter_subtitle', esc_html__('Get the latest updates, tips, and special offers delivered directly to your inbox', 'aqualuxe'))); ?></p>
                
                <form id="newsletter-form" class="newsletter-form max-w-lg mx-auto">
                    <div class="flex flex-col sm:flex-row">
                        <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="w-full sm:w-auto flex-grow rounded-l-md sm:rounded-r-none rounded-r-md sm:rounded-l-md mb-2 sm:mb-0 border-white focus:ring-accent focus:border-accent" required>
                        <button type="submit" class="bg-accent hover:bg-accent-dark text-primary-dark px-6 py-3 rounded-r-md sm:rounded-l-none rounded-l-md sm:rounded-r-md transition-colors duration-300">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </div>
                    <div class="response-message mt-4"></div>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Instagram Feed Section
    if (get_theme_mod('aqualuxe_show_instagram', true)) :
    ?>
    <section class="instagram-section py-16 bg-white dark:bg-dark-bg">
        <div class="container-fluid">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_instagram_title', esc_html__('Follow Us on Instagram', 'aqualuxe'))); ?></h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html(get_theme_mod('aqualuxe_instagram_subtitle', esc_html__('See our beautiful fish and aquariums on Instagram', 'aqualuxe'))); ?></p>
            </div>

            <?php
            // Instagram feed shortcode
            $instagram_shortcode = get_theme_mod('aqualuxe_instagram_shortcode', '');
            if ($instagram_shortcode) {
                echo do_shortcode($instagram_shortcode);
            } else {
                // Fallback Instagram grid
                ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php for ($i = 1; $i <= 6; $i++) : ?>
                        <div class="instagram-item relative overflow-hidden aspect-square">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/instagram/insta-' . $i . '.jpg'); ?>" alt="<?php printf(esc_attr__('Instagram Image %d', 'aqualuxe'), $i); ?>" class="w-full h-full object-cover">
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_instagram_url', 'https://instagram.com/')); ?>" class="instagram-overlay absolute inset-0 bg-primary-dark bg-opacity-70 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    <?php endfor; ?>
                </div>
                <?php
            }
            ?>

            <?php if (get_theme_mod('aqualuxe_instagram_button_text')) : ?>
                <div class="text-center mt-10">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_instagram_url', 'https://instagram.com/')); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer">
                        <?php echo esc_html(get_theme_mod('aqualuxe_instagram_button_text', esc_html__('Follow on Instagram', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Display the page content if it exists
    while (have_posts()) :
        the_post();
        
        if (get_the_content()) :
            ?>
            <section class="page-content-section py-16 bg-white dark:bg-dark-bg">
                <div class="container-fluid">
                    <div class="prose prose-lg dark:prose-invert max-w-none">
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