<?php
/**
 * Template part for displaying services on the homepage
 *
 * @package AquaLuxe
 */

// Get services options from theme customizer
$show_services = get_theme_mod('aqualuxe_show_home_services', true);
$services_title = get_theme_mod('aqualuxe_home_services_title', __('Our Services', 'aqualuxe'));
$services_subtitle = get_theme_mod('aqualuxe_home_services_subtitle', __('Professional aquatic services for your needs', 'aqualuxe'));
$services_count = get_theme_mod('aqualuxe_home_services_count', 6);
$services_columns = get_theme_mod('aqualuxe_home_services_columns', 3);
$services_style = get_theme_mod('aqualuxe_home_services_style', 'grid');
$services_category = get_theme_mod('aqualuxe_home_services_category', 0);
$services_orderby = get_theme_mod('aqualuxe_home_services_orderby', 'menu_order');
$services_order = get_theme_mod('aqualuxe_home_services_order', 'ASC');
$services_button_text = get_theme_mod('aqualuxe_home_services_button_text', __('View All Services', 'aqualuxe'));
$services_button_url = get_theme_mod('aqualuxe_home_services_button_url', get_post_type_archive_link('service'));

// Check if services should be displayed
if (!$show_services) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => $services_count,
    'orderby'        => $services_orderby,
    'order'          => $services_order,
);

// Add category filter
if ($services_category > 0) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'service_category',
            'field'    => 'term_id',
            'terms'    => $services_category,
        ),
    );
}

// Get services
$services_query = new WP_Query($args);

// Check if we have services
if (!$services_query->have_posts()) {
    return;
}

// Set up column classes
$column_class = 'col-lg-4 col-md-6';

switch ($services_columns) {
    case 2:
        $column_class = 'col-lg-6 col-md-6';
        break;
    case 3:
        $column_class = 'col-lg-4 col-md-6';
        break;
    case 4:
        $column_class = 'col-lg-3 col-md-6';
        break;
}

// Service item classes
$service_item_class = 'service-item';
$service_item_class .= ' service-style-' . $services_style;
?>

<div class="services-section section-padding">
    <div class="container">
        <div class="section-header text-center">
            <?php if (!empty($services_title)) : ?>
                <h2 class="section-title"><?php echo esc_html($services_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($services_subtitle)) : ?>
                <div class="section-subtitle"><?php echo esc_html($services_subtitle); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="services-wrapper">
            <?php if ($services_style === 'carousel') : ?>
                <div class="services-carousel">
                    <?php
                    // Loop through services
                    while ($services_query->have_posts()) :
                        $services_query->the_post();
                        
                        // Get service details
                        $service_icon = get_post_meta(get_the_ID(), 'service_icon', true);
                        $service_price = get_post_meta(get_the_ID(), 'service_price', true);
                        $service_duration = get_post_meta(get_the_ID(), 'service_duration', true);
                        ?>
                        <div class="<?php echo esc_attr($service_item_class); ?>">
                            <div class="service-inner">
                                <?php if (!empty($service_icon)) : ?>
                                    <div class="service-icon">
                                        <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                    </div>
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <div class="service-image">
                                        <?php the_post_thumbnail('aqualuxe-service-thumbnail', array('class' => 'img-fluid')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-content">
                                    <h3 class="service-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="service-excerpt">
                                        <?php echo wp_kses_post(aqualuxe_get_excerpt(15)); ?>
                                    </div>
                                    
                                    <div class="service-meta">
                                        <?php if (!empty($service_price)) : ?>
                                            <div class="service-price">
                                                <i class="fas fa-tag"></i>
                                                <span><?php echo esc_html(aqualuxe_format_price($service_price)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($service_duration)) : ?>
                                            <div class="service-duration">
                                                <i class="far fa-clock"></i>
                                                <span><?php echo esc_html($service_duration); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="service-link">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                            <?php echo esc_html__('Learn More', 'aqualuxe'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <div class="row">
                    <?php
                    // Loop through services
                    while ($services_query->have_posts()) :
                        $services_query->the_post();
                        
                        // Get service details
                        $service_icon = get_post_meta(get_the_ID(), 'service_icon', true);
                        $service_price = get_post_meta(get_the_ID(), 'service_price', true);
                        $service_duration = get_post_meta(get_the_ID(), 'service_duration', true);
                        ?>
                        <div class="<?php echo esc_attr($column_class); ?>">
                            <div class="<?php echo esc_attr($service_item_class); ?>">
                                <div class="service-inner">
                                    <?php if (!empty($service_icon)) : ?>
                                        <div class="service-icon">
                                            <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                        </div>
                                    <?php elseif (has_post_thumbnail()) : ?>
                                        <div class="service-image">
                                            <?php the_post_thumbnail('aqualuxe-service-thumbnail', array('class' => 'img-fluid')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="service-content">
                                        <h3 class="service-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="service-excerpt">
                                            <?php echo wp_kses_post(aqualuxe_get_excerpt(15)); ?>
                                        </div>
                                        
                                        <div class="service-meta">
                                            <?php if (!empty($service_price)) : ?>
                                                <div class="service-price">
                                                    <i class="fas fa-tag"></i>
                                                    <span><?php echo esc_html(aqualuxe_format_price($service_price)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($service_duration)) : ?>
                                                <div class="service-duration">
                                                    <i class="far fa-clock"></i>
                                                    <span><?php echo esc_html($service_duration); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="service-link">
                                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                                <?php echo esc_html__('Learn More', 'aqualuxe'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($services_button_text) && !empty($services_button_url)) : ?>
            <div class="text-center mt-5">
                <a href="<?php echo esc_url($services_button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($services_button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>