<?php
/**
 * Template part for displaying the homepage services section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get section settings from theme options
$section_title = get_theme_mod('aqualuxe_services_title', __('Our Services', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_services_subtitle', __('What We Offer', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_services_description', __('Discover our comprehensive range of services designed to meet all your aquatic needs.', 'aqualuxe'));
$services_count = get_theme_mod('aqualuxe_services_count', 6);
$view_all_text = get_theme_mod('aqualuxe_services_view_all', __('View All Services', 'aqualuxe'));
$view_all_url = get_theme_mod('aqualuxe_services_view_all_url', '#');
$section_background = get_theme_mod('aqualuxe_services_background', 'white');

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="services-section py-16 <?php echo esc_attr($bg_class); ?>">
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
            
            $services_query = new WP_Query($args);
            
            if ($services_query->have_posts()) :
                while ($services_query->have_posts()) :
                    $services_query->the_post();
                    
                    // Get service icon
                    $service_icon = get_post_meta(get_the_ID(), '_aqualuxe_service_icon', true);
                    $service_icon_type = get_post_meta(get_the_ID(), '_aqualuxe_service_icon_type', true);
                    ?>
                    
                    <div class="service-card bg-white rounded-lg shadow-md p-6 transition-transform hover:transform hover:scale-105">
                        <div class="service-icon mb-4">
                            <?php if ($service_icon_type === 'image' && has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 object-contain')); ?>
                            <?php elseif ($service_icon) : ?>
                                <div class="icon-wrapper text-primary text-4xl">
                                    <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="service-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-gray-600 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="service-link text-primary hover:text-primary-dark font-medium inline-flex items-center">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
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
        
        <?php if ($view_all_text && $view_all_url) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url($view_all_url); ?>" class="button button-primary">
                    <?php echo esc_html($view_all_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>