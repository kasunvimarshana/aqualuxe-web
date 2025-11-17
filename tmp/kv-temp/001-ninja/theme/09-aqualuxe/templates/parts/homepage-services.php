<?php
/**
 * Homepage Services Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_services_title', __('Our Services', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_services_description', __('Professional aquatic services for hobbyists and businesses.', 'aqualuxe'));
$services_count = get_theme_mod('aqualuxe_services_count', 3);
$services_columns = get_theme_mod('aqualuxe_services_columns', 3);
$show_button = get_theme_mod('aqualuxe_services_show_button', true);
$button_text = get_theme_mod('aqualuxe_services_button_text', __('View All Services', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_services_button_url', get_post_type_archive_link('service'));

// Get services
$args = array(
    'post_type' => 'service',
    'posts_per_page' => $services_count,
    'post_status' => 'publish',
);

$services = new WP_Query($args);

// Only show section if services exist
if ($services->have_posts()) :
?>

<section class="services-section py-16">
    <div class="container">
        <?php if ($section_title || $section_description) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_description) : ?>
                    <p class="section-description text-lg text-gray-600 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="services-grid columns-<?php echo esc_attr($services_columns); ?>">
            <?php
            while ($services->have_posts()) :
                $services->the_post();
                
                // Get service meta
                $price = get_post_meta(get_the_ID(), '_service_price', true);
                $icon = get_post_meta(get_the_ID(), '_service_icon', true);
                ?>
                
                <div class="service-item">
                    <?php if ($icon) : ?>
                        <div class="service-icon">
                            <i class="fa <?php echo esc_attr($icon); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h3 class="service-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <?php if (has_excerpt()) : ?>
                        <div class="service-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($price) : ?>
                        <div class="service-price">
                            <?php echo esc_html($price); ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php the_permalink(); ?>" class="service-link">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                    </a>
                </div>
                
            <?php endwhile; ?>
        </div>
        
        <?php if ($show_button && $button_url) : ?>
            <div class="text-center mt-10">
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();
endif;