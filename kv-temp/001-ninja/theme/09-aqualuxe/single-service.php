<?php
/**
 * The template for displaying single service posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get service meta
$price = get_post_meta(get_the_ID(), '_service_price', true);
$duration = get_post_meta(get_the_ID(), '_service_duration', true);
$icon = get_post_meta(get_the_ID(), '_service_icon', true);
$booking_url = get_post_meta(get_the_ID(), '_service_booking_url', true);
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <?php if ($icon) : ?>
                                <div class="service-icon text-4xl text-primary mb-4">
                                    <i class="fa <?php echo esc_attr($icon); ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h1 class="entry-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                            
                            <div class="service-meta flex flex-wrap gap-4 text-lg">
                                <?php if ($price) : ?>
                                    <div class="service-price text-primary font-bold">
                                        <span class="label"><?php esc_html_e('Price:', 'aqualuxe'); ?></span>
                                        <span class="value"><?php echo esc_html($price); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($duration) : ?>
                                    <div class="service-duration text-gray-600">
                                        <span class="label"><?php esc_html_e('Duration:', 'aqualuxe'); ?></span>
                                        <span class="value"><?php echo esc_html($duration); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($booking_url) : ?>
                            <div class="service-booking mt-4 md:mt-0">
                                <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Book Now', 'aqualuxe'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </header><!-- .entry-header -->

                <?php if (has_post_thumbnail()) : ?>
                    <div class="service-featured-image mb-8">
                        <?php the_post_thumbnail('full', array('class' => 'rounded-lg shadow-lg w-full h-auto')); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content prose max-w-none mb-8">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

                <?php
                // Get service category terms
                $service_categories = get_the_terms(get_the_ID(), 'service_category');
                
                if ($service_categories && !is_wp_error($service_categories)) :
                ?>
                    <div class="service-categories mb-8 flex flex-wrap gap-2">
                        <?php foreach ($service_categories as $category) : ?>
                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm transition duration-300">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Service Process Section -->
                <?php
                $process_title = get_post_meta(get_the_ID(), '_service_process_title', true);
                $process_steps = get_post_meta(get_the_ID(), '_service_process_steps', true);
                
                if (empty($process_title)) {
                    $process_title = __('Our Service Process', 'aqualuxe');
                }
                
                // If no custom process steps, use default ones based on service title
                if (empty($process_steps)) {
                    $process_steps = array(
                        array(
                            'title' => __('Initial Consultation', 'aqualuxe'),
                            'description' => __('We begin with a thorough consultation to understand your specific needs and requirements.', 'aqualuxe'),
                        ),
                        array(
                            'title' => __('Custom Planning', 'aqualuxe'),
                            'description' => __('Our experts develop a customized plan tailored to your space, preferences, and budget.', 'aqualuxe'),
                        ),
                        array(
                            'title' => __('Professional Implementation', 'aqualuxe'),
                            'description' => __('Our skilled team executes the plan with precision, using high-quality materials and equipment.', 'aqualuxe'),
                        ),
                        array(
                            'title' => __('Quality Assurance', 'aqualuxe'),
                            'description' => __('We conduct thorough quality checks to ensure everything meets our high standards.', 'aqualuxe'),
                        ),
                        array(
                            'title' => __('Ongoing Support', 'aqualuxe'),
                            'description' => __('We provide continued support and maintenance to ensure long-term success.', 'aqualuxe'),
                        ),
                    );
                }
                ?>
                
                <section class="service-process mb-12 bg-gray-50 p-8 rounded-lg">
                    <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($process_title); ?></h2>
                    
                    <div class="process-steps">
                        <?php foreach ($process_steps as $index => $step) : ?>
                            <div class="process-step flex mb-6 last:mb-0">
                                <div class="step-number flex-shrink-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-4">
                                    <?php echo esc_html($index + 1); ?>
                                </div>
                                <div class="step-content">
                                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html($step['title']); ?></h3>
                                    <p class="text-gray-600"><?php echo esc_html($step['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Related Services Section -->
                <?php
                // Get related services from the same category
                $related_args = array(
                    'post_type' => 'service',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'orderby' => 'rand',
                );
                
                // Add category filter if this service has categories
                if ($service_categories && !is_wp_error($service_categories)) {
                    $category_ids = array();
                    foreach ($service_categories as $category) {
                        $category_ids[] = $category->term_id;
                    }
                    
                    $related_args['tax_query'] = array(
                        array(
                            'taxonomy' => 'service_category',
                            'field' => 'term_id',
                            'terms' => $category_ids,
                        ),
                    );
                }
                
                $related_services = new WP_Query($related_args);
                
                if ($related_services->have_posts()) :
                ?>
                    <section class="related-services mb-12">
                        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Related Services', 'aqualuxe'); ?></h2>
                        
                        <div class="services-grid grid grid-cols-1 md:grid-cols-3 gap-8">
                            <?php
                            while ($related_services->have_posts()) :
                                $related_services->the_post();
                                
                                // Get service meta
                                $rel_price = get_post_meta(get_the_ID(), '_service_price', true);
                                $rel_icon = get_post_meta(get_the_ID(), '_service_icon', true);
                                ?>
                                
                                <div class="service-item bg-white rounded-lg shadow-md p-6 transition-transform duration-300 hover:transform hover:scale-105">
                                    <?php if ($rel_icon) : ?>
                                        <div class="service-icon text-4xl text-primary mb-4">
                                            <i class="fa <?php echo esc_attr($rel_icon); ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="service-title text-xl font-bold mb-3">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <?php if (has_excerpt()) : ?>
                                        <div class="service-excerpt text-gray-600 mb-4">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($rel_price) : ?>
                                        <div class="service-price text-primary font-bold mb-4">
                                            <?php echo esc_html($rel_price); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <a href="<?php the_permalink(); ?>" class="service-link inline-block text-primary hover:text-secondary">
                                        <?php esc_html_e('Learn More', 'aqualuxe'); ?> <i class="fa fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                                
                            <?php endwhile; ?>
                        </div>
                    </section>
                <?php
                endif;
                wp_reset_postdata();
                ?>

                <!-- Call to Action Section -->
                <section class="service-cta bg-primary text-white p-8 rounded-lg text-center">
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Ready to Get Started?', 'aqualuxe'); ?></h2>
                    <p class="text-lg mb-6"><?php esc_html_e('Contact us today to schedule a consultation or book this service.', 'aqualuxe'); ?></p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <?php if ($booking_url) : ?>
                            <a href="<?php echo esc_url($booking_url); ?>" class="btn bg-white text-primary hover:bg-gray-100">
                                <?php esc_html_e('Book Now', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn bg-secondary text-white hover:bg-opacity-80">
                            <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                        </a>
                    </div>
                </section>
            </article><!-- #post-<?php the_ID(); ?> -->
            
        <?php endwhile; ?>
    </div>

</main><!-- #main -->

<?php
get_footer();