<?php
/**
 * Template Name: Services Page
 *
 * This is the template that displays the services page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'services_hero_image', true);
    if (!$hero_image) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $hero_title = get_post_meta(get_the_ID(), 'services_hero_title', true);
    if (!$hero_title) {
        $hero_title = get_the_title();
    }
    $hero_subtitle = get_post_meta(get_the_ID(), 'services_hero_subtitle', true);
    ?>
    <section class="services-hero relative py-20 bg-cover bg-center" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    // Intro Section
    $intro_title = get_post_meta(get_the_ID(), 'services_intro_title', true);
    $intro_content = get_post_meta(get_the_ID(), 'services_intro_content', true);
    
    if ($intro_title || $intro_content || get_the_content()) :
    ?>
    <section class="services-intro py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <?php if ($intro_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center"><?php echo esc_html($intro_title); ?></h2>
                <?php endif; ?>
                
                <div class="prose dark:prose-invert lg:prose-lg max-w-none">
                    <?php if ($intro_content) : ?>
                        <?php echo wp_kses_post(wpautop($intro_content)); ?>
                    <?php elseif (get_the_content()) : ?>
                        <?php the_content(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Services Grid
    $services_title = get_post_meta(get_the_ID(), 'services_grid_title', true);
    $services_subtitle = get_post_meta(get_the_ID(), 'services_grid_subtitle', true);
    $services_layout = get_post_meta(get_the_ID(), 'services_layout', true);
    if (!$services_layout) {
        $services_layout = 'grid'; // Default layout
    }
    ?>
    <section class="services-grid py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($services_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($services_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Our Services', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($services_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($services_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
            // Get service categories if they exist
            $service_cats = get_terms(array(
                'taxonomy' => 'aqualuxe_service_cat',
                'hide_empty' => true,
            ));
            
            // If we have categories and more than one, show filter
            if (!is_wp_error($service_cats) && count($service_cats) > 1) :
            ?>
            <div class="services-filter flex flex-wrap justify-center gap-4 mb-12">
                <button class="filter-btn px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 font-medium" data-filter="all">
                    <?php esc_html_e('All Services', 'aqualuxe'); ?>
                </button>
                
                <?php foreach ($service_cats as $cat) : ?>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-800 dark:text-gray-200 font-medium" data-filter="<?php echo esc_attr($cat->slug); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php
            // Get services
            $args = array(
                'post_type' => 'aqualuxe_service',
                'posts_per_page' => -1,
            );
            $services_query = new WP_Query($args);
            
            if ($services_query->have_posts()) :
                if ($services_layout === 'grid') :
            ?>
                <div class="services-container grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($services_query->have_posts()) : $services_query->the_post(); 
                        // Get service categories
                        $service_terms = get_the_terms(get_the_ID(), 'aqualuxe_service_cat');
                        $service_cats_class = '';
                        $service_cats_names = array();
                        
                        if (!empty($service_terms) && !is_wp_error($service_terms)) {
                            foreach ($service_terms as $term) {
                                $service_cats_class .= ' ' . $term->slug;
                                $service_cats_names[] = $term->name;
                            }
                        }
                    ?>
                        <div class="service-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow<?php echo esc_attr($service_cats_class); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-content p-6">
                                <?php if (!empty($service_cats_names)) : ?>
                                    <div class="service-categories mb-2">
                                        <?php foreach ($service_cats_names as $cat_name) : ?>
                                            <span class="inline-block px-2 py-1 text-xs bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full mr-2 mb-2"><?php echo esc_html($cat_name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
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
                    <?php endwhile; ?>
                </div>
            <?php 
                elseif ($services_layout === 'list') : 
            ?>
                <div class="services-container space-y-8">
                    <?php while ($services_query->have_posts()) : $services_query->the_post(); 
                        // Get service categories
                        $service_terms = get_the_terms(get_the_ID(), 'aqualuxe_service_cat');
                        $service_cats_class = '';
                        $service_cats_names = array();
                        
                        if (!empty($service_terms) && !is_wp_error($service_terms)) {
                            foreach ($service_terms as $term) {
                                $service_cats_class .= ' ' . $term->slug;
                                $service_cats_names[] = $term->name;
                            }
                        }
                    ?>
                        <div class="service-item bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow<?php echo esc_attr($service_cats_class); ?>">
                            <div class="flex flex-col md:flex-row">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="service-image md:w-1/3">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-content p-6 md:w-2/3">
                                    <?php if (!empty($service_cats_names)) : ?>
                                        <div class="service-categories mb-2">
                                            <?php foreach ($service_cats_names as $cat_name) : ?>
                                                <span class="inline-block px-2 py-1 text-xs bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full mr-2 mb-2"><?php echo esc_html($cat_name); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
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
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php
                endif;
                wp_reset_postdata();
            else :
            ?>
                <div class="text-center">
                    <p><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    // Process Section
    $process_title = get_post_meta(get_the_ID(), 'services_process_title', true);
    $process_subtitle = get_post_meta(get_the_ID(), 'services_process_subtitle', true);
    $process_steps = get_post_meta(get_the_ID(), 'services_process_steps', true);
    
    if ($process_title || $process_subtitle || $process_steps) :
    ?>
    <section class="services-process py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($process_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($process_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Our Process', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($process_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($process_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($process_steps && is_array($process_steps)) : ?>
                <div class="process-steps max-w-4xl mx-auto">
                    <?php foreach ($process_steps as $index => $step) : ?>
                        <div class="process-step flex flex-col md:flex-row items-start md:items-center mb-12 last:mb-0">
                            <div class="step-number flex-shrink-0 w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center text-xl font-bold mb-4 md:mb-0 md:mr-6">
                                <?php echo esc_html($index + 1); ?>
                            </div>
                            <div class="step-content">
                                <h3 class="text-xl font-bold mb-2"><?php echo esc_html($step['title']); ?></h3>
                                <div class="text-gray-600 dark:text-gray-300">
                                    <?php echo wp_kses_post(wpautop($step['description'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Pricing Section
    $pricing_title = get_post_meta(get_the_ID(), 'services_pricing_title', true);
    $pricing_subtitle = get_post_meta(get_the_ID(), 'services_pricing_subtitle', true);
    $pricing_packages = get_post_meta(get_the_ID(), 'services_pricing_packages', true);
    $pricing_note = get_post_meta(get_the_ID(), 'services_pricing_note', true);
    
    if ($pricing_title || $pricing_subtitle || $pricing_packages) :
    ?>
    <section class="services-pricing py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($pricing_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($pricing_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Our Pricing', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($pricing_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($pricing_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($pricing_packages && is_array($pricing_packages)) : ?>
                <div class="pricing-packages grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($pricing_packages as $package) : 
                        $is_featured = !empty($package['featured']) && $package['featured'] === 'yes';
                    ?>
                        <div class="pricing-package bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md <?php echo $is_featured ? 'border-2 border-primary-500 relative' : ''; ?>">
                            <?php if ($is_featured) : ?>
                                <div class="absolute top-0 right-0">
                                    <div class="bg-primary-500 text-white px-4 py-1 transform rotate-45 translate-x-1/3 translate-y-1/4">
                                        <?php esc_html_e('Popular', 'aqualuxe'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="package-header p-6 <?php echo $is_featured ? 'bg-primary-50 dark:bg-primary-900' : 'bg-gray-50 dark:bg-gray-800'; ?>">
                                <h3 class="text-xl font-bold mb-2 <?php echo $is_featured ? 'text-primary-700 dark:text-primary-300' : ''; ?>">
                                    <?php echo esc_html($package['name']); ?>
                                </h3>
                                
                                <?php if (!empty($package['description'])) : ?>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4"><?php echo esc_html($package['description']); ?></p>
                                <?php endif; ?>
                                
                                <div class="package-price">
                                    <span class="text-3xl font-bold"><?php echo esc_html($package['price']); ?></span>
                                    <?php if (!empty($package['period'])) : ?>
                                        <span class="text-gray-600 dark:text-gray-300"><?php echo esc_html($package['period']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="package-features p-6">
                                <?php if (!empty($package['features']) && is_array($package['features'])) : ?>
                                    <ul class="space-y-3">
                                        <?php foreach ($package['features'] as $feature) : ?>
                                            <li class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span><?php echo esc_html($feature); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                
                                <?php if (!empty($package['button_text']) && !empty($package['button_url'])) : ?>
                                    <div class="package-action mt-6">
                                        <a href="<?php echo esc_url($package['button_url']); ?>" class="block w-full py-3 px-4 text-center rounded-md <?php echo $is_featured ? 'bg-primary-600 hover:bg-primary-700 text-white' : 'bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white'; ?> transition-colors">
                                            <?php echo esc_html($package['button_text']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($pricing_note) : ?>
                <div class="pricing-note text-center mt-8 text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    <?php echo wp_kses_post(wpautop($pricing_note)); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Testimonials Section
    $testimonials_title = get_post_meta(get_the_ID(), 'services_testimonials_title', true);
    $testimonials_subtitle = get_post_meta(get_the_ID(), 'services_testimonials_subtitle', true);
    
    if ($testimonials_title || $testimonials_subtitle) :
    ?>
    <section class="services-testimonials py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($testimonials_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($testimonials_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('What Our Clients Say', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($testimonials_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($testimonials_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
            $args = array(
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 3,
            );
            $testimonials_query = new WP_Query($args);
            
            if ($testimonials_query->have_posts()) :
            ?>
                <div class="testimonials-grid grid md:grid-cols-3 gap-8">
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
                    <?php endwhile; ?>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // CTA Section
    $cta_title = get_post_meta(get_the_ID(), 'services_cta_title', true);
    $cta_text = get_post_meta(get_the_ID(), 'services_cta_text', true);
    $cta_button_text = get_post_meta(get_the_ID(), 'services_cta_button_text', true);
    $cta_button_url = get_post_meta(get_the_ID(), 'services_cta_button_url', true);
    $cta_bg_image = get_post_meta(get_the_ID(), 'services_cta_bg_image', true);
    
    if ($cta_title || $cta_text) :
    ?>
    <section class="services-cta py-16 bg-cover bg-center relative" <?php if ($cta_bg_image) : ?>style="background-image: url('<?php echo esc_url($cta_bg_image); ?>');"<?php else : ?>style="background-color: #0f172a;"<?php endif; ?>>
        <div class="absolute inset-0 bg-primary-900 bg-opacity-80"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <?php if ($cta_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($cta_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Ready to Get Started?', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($cta_text) : ?>
                    <p class="text-xl mb-8"><?php echo esc_html($cta_text); ?></p>
                <?php endif; ?>
                
                <?php if ($cta_button_text && $cta_button_url) : ?>
                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn-primary inline-block px-8 py-4 bg-white hover:bg-gray-100 text-primary-900 rounded-md transition-colors text-lg font-medium">
                        <?php echo esc_html($cta_button_text); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary inline-block px-8 py-4 bg-white hover:bg-gray-100 text-primary-900 rounded-md transition-colors text-lg font-medium">
                        <?php esc_html_e('Contact Us Today', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();