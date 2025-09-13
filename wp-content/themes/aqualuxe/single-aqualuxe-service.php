<?php
/**
 * Single Service Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <?php while (have_posts()) : the_post(); ?>
            
            <?php aqualuxe_breadcrumb(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('service-single max-w-4xl mx-auto'); ?>>
                
                <!-- Service Header -->
                <header class="service-header mb-8">
                    <h1 class="service-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php the_title(); ?>
                    </h1>
                    
                    <?php if (has_excerpt()) : ?>
                        <div class="service-excerpt text-xl text-gray-600 dark:text-gray-400 mb-6">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Service Meta -->
                    <div class="service-meta flex flex-wrap gap-6 mb-8">
                        <?php
                        $price = get_post_meta(get_the_ID(), '_aqualuxe_service_price', true);
                        $duration = get_post_meta(get_the_ID(), '_aqualuxe_service_duration', true);
                        ?>
                        
                        <?php if ($price) : ?>
                            <div class="service-price flex items-center">
                                <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-lg font-semibold text-primary-600"><?php echo esc_html($price); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($duration) : ?>
                            <div class="service-duration flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400"><?php echo esc_html($duration); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="service-image mb-8">
                            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'w-full h-64 md:h-96 object-cover rounded-lg')); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Service Content -->
                <div class="service-content prose prose-lg dark:prose-invert max-w-none mb-8">
                    <?php the_content(); ?>
                </div>

                <!-- Service Features -->
                <?php
                $features = get_post_meta(get_the_ID(), '_aqualuxe_service_features', true);
                if ($features) :
                    $features_list = explode("\n", $features);
                ?>
                    <div class="service-features mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            <?php _e('What\'s Included', 'aqualuxe'); ?>
                        </h3>
                        <ul class="space-y-2">
                            <?php foreach ($features_list as $feature) : ?>
                                <?php if (trim($feature)) : ?>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-primary-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300"><?php echo esc_html(trim($feature)); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Booking Section -->
                <?php
                $booking_enabled = get_post_meta(get_the_ID(), '_aqualuxe_service_booking_enabled', true);
                if ($booking_enabled) :
                ?>
                    <div class="service-booking bg-primary-50 dark:bg-primary-900/20 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <?php _e('Book This Service', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            <?php _e('Ready to get started? Contact us to schedule your service.', 'aqualuxe'); ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                                <?php _e('Contact Us', 'aqualuxe'); ?>
                            </a>
                            <a href="tel:+1234567890" class="btn btn-outline">
                                <?php _e('Call Now', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Service Categories -->
                <?php
                $categories = get_the_terms(get_the_ID(), 'service_category');
                if ($categories && !is_wp_error($categories)) :
                ?>
                    <div class="service-categories mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            <?php _e('Service Categories', 'aqualuxe'); ?>
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($categories as $category) : ?>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                                   class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Social Sharing -->
                <?php aqualuxe_social_sharing(); ?>

            </article>

            <!-- Related Services -->
            <?php
            $related_services = new WP_Query(array(
                'post_type' => 'aqualuxe_service',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'meta_query' => array(
                    array(
                        'key' => '_aqualuxe_service_booking_enabled',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));

            if ($related_services->have_posts()) :
            ?>
                <section class="related-services section-padding-sm">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                            <?php _e('Other Services', 'aqualuxe'); ?>
                        </h2>
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <?php while ($related_services->have_posts()) : $related_services->the_post(); ?>
                                <div class="service-card card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="service-thumbnail mb-4">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('aqualuxe-thumbnail', array('class' => 'w-full h-48 object-cover rounded-lg')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="service-title text-lg font-semibold mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <div class="service-excerpt text-gray-600 dark:text-gray-400 mb-4">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                                        <?php _e('Learn More →', 'aqualuxe'); ?>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>