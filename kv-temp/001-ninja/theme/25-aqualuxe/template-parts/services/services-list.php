<?php
/**
 * Template part for displaying the services page services list section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_services_list_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_services_list_title', __('Our Services', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_services_list_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_services_list_subtitle', __('What We Offer', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_services_list_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_services_list_description', __('Explore our comprehensive range of services designed to meet all your aquatic needs.', 'aqualuxe'));
}

$services_count = get_post_meta($page_id, '_aqualuxe_services_list_count', true);
if (!$services_count) {
    $services_count = get_theme_mod('aqualuxe_services_list_count', -1); // -1 for all services
}

$services_category = get_post_meta($page_id, '_aqualuxe_services_list_category', true);
if (!$services_category) {
    $services_category = get_theme_mod('aqualuxe_services_list_category', '');
}

$section_background = get_post_meta($page_id, '_aqualuxe_services_list_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_services_list_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="services-list-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Query services
            $args = array(
                'post_type'      => 'service',
                'posts_per_page' => $services_count,
                'post_status'    => 'publish',
            );
            
            // Add category filter if set
            if ($services_category) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'service_category',
                        'field'    => 'term_id',
                        'terms'    => $services_category,
                    ),
                );
            }
            
            $services_query = new WP_Query($args);
            
            if ($services_query->have_posts()) :
                while ($services_query->have_posts()) :
                    $services_query->the_post();
                    
                    // Get service meta
                    $service_icon = get_post_meta(get_the_ID(), '_aqualuxe_service_icon', true);
                    $service_icon_type = get_post_meta(get_the_ID(), '_aqualuxe_service_icon_type', true);
                    $service_short_description = get_post_meta(get_the_ID(), '_aqualuxe_service_short_description', true);
                    $service_features = get_post_meta(get_the_ID(), '_aqualuxe_service_features', true);
                    ?>
                    
                    <div class="service-card bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="service-image">
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-56 object-cover')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-content p-6">
                            <div class="service-icon mb-4 text-primary">
                                <?php if ($service_icon_type === 'image' && has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 object-contain')); ?>
                                <?php elseif ($service_icon) : ?>
                                    <div class="icon-wrapper text-4xl">
                                        <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="service-title text-xl font-bold mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="service-description text-gray-600 mb-4">
                                <?php
                                if ($service_short_description) {
                                    echo wp_kses_post(wpautop($service_short_description));
                                } else {
                                    the_excerpt();
                                }
                                ?>
                            </div>
                            
                            <?php if ($service_features) : ?>
                                <div class="service-features mb-6">
                                    <ul class="space-y-2">
                                        <?php
                                        $features = explode("\n", $service_features);
                                        foreach ($features as $feature) :
                                            $feature = trim($feature);
                                            if (!empty($feature)) :
                                                ?>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <?php echo esc_html($feature); ?>
                                                </li>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="service-link text-primary hover:text-primary-dark font-medium inline-flex items-center">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="no-services col-span-full text-center py-8">
                    <p><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>