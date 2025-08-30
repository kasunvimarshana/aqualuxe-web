<?php
/**
 * Template part for displaying related services
 *
 * @package AquaLuxe
 */

// Get related services options from theme customizer
$show_related_services = get_theme_mod('aqualuxe_show_related_services', true);
$related_services_title = get_theme_mod('aqualuxe_related_services_title', __('Related Services', 'aqualuxe'));
$related_services_count = get_theme_mod('aqualuxe_related_services_count', 3);
$related_services_orderby = get_theme_mod('aqualuxe_related_services_orderby', 'date');
$related_services_order = get_theme_mod('aqualuxe_related_services_order', 'DESC');

// Check if related services should be displayed
if (!$show_related_services) {
    return;
}

// Get current service ID
$service_id = get_the_ID();

// Get service categories
$service_categories = get_the_terms($service_id, 'service_category');

// If no categories found, return
if (empty($service_categories) || is_wp_error($service_categories)) {
    return;
}

// Get category IDs
$category_ids = array();
foreach ($service_categories as $category) {
    $category_ids[] = $category->term_id;
}

// Set up query arguments
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => $related_services_count,
    'orderby'        => $related_services_orderby,
    'order'          => $related_services_order,
    'post_status'    => 'publish',
    'post__not_in'   => array($service_id),
    'tax_query'      => array(
        array(
            'taxonomy' => 'service_category',
            'field'    => 'term_id',
            'terms'    => $category_ids,
        ),
    ),
);

// Get related services
$related_services_query = new WP_Query($args);

// Check if we have related services
if (!$related_services_query->have_posts()) {
    return;
}
?>

<div class="related-services">
    <?php if (!empty($related_services_title)) : ?>
        <h3 class="related-services-title"><?php echo esc_html($related_services_title); ?></h3>
    <?php endif; ?>
    
    <div class="related-services-list">
        <?php
        // Loop through related services
        while ($related_services_query->have_posts()) :
            $related_services_query->the_post();
            
            // Get service details
            $service_price = get_post_meta(get_the_ID(), 'service_price', true);
            $service_duration = get_post_meta(get_the_ID(), 'service_duration', true);
            ?>
            <div class="related-service-item">
                <div class="related-service-inner">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="related-service-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="related-service-content">
                        <h4 class="related-service-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="related-service-meta">
                            <?php if (!empty($service_price)) : ?>
                                <div class="related-service-price">
                                    <i class="fas fa-tag"></i>
                                    <span><?php echo esc_html(aqualuxe_format_price($service_price)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($service_duration)) : ?>
                                <div class="related-service-duration">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo esc_html($service_duration); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
    
    <div class="related-services-more">
        <a href="<?php echo esc_url(get_post_type_archive_link('service')); ?>" class="btn btn-outline-primary btn-sm">
            <?php echo esc_html__('View All Services', 'aqualuxe'); ?>
        </a>
    </div>
</div>