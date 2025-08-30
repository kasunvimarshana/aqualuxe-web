<?php
/**
 * Template part for displaying services on the homepage
 *
 * @package AquaLuxe
 */

// Get services section settings from customizer
$services_title = get_theme_mod('aqualuxe_services_title', __('Our Services', 'aqualuxe'));
$services_description = get_theme_mod('aqualuxe_services_description', __('Professional aquatic services for all your needs', 'aqualuxe'));
$services_count = get_theme_mod('aqualuxe_services_count', 3);
$services_enable = get_theme_mod('aqualuxe_services_enable', true);
$view_all_text = get_theme_mod('aqualuxe_services_view_all_text', __('View All Services', 'aqualuxe'));

// Exit if services section is disabled
if (!$services_enable) {
    return;
}

// Check if the Services custom post type exists
if (!post_type_exists('services')) {
    return;
}

// Get services archive page URL
$services_page_url = get_post_type_archive_link('services');
?>

<section class="services">
    <div class="container">
        <div class="section-header">
            <?php if ($services_title) : ?>
                <h2 class="section-title"><?php echo esc_html($services_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($services_description) : ?>
                <p class="section-description"><?php echo esc_html($services_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="services-wrapper">
            <?php
            $args = array(
                'post_type'      => 'services',
                'posts_per_page' => $services_count,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            );
            
            $services_query = new WP_Query($args);
            
            if ($services_query->have_posts()) :
                echo '<div class="services-grid">';
                
                while ($services_query->have_posts()) : $services_query->the_post();
                    ?>
                    <div class="service-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="service-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('class' => 'service-thumbnail')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-content">
                            <h3 class="service-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <div class="service-description">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline service-link">
                                <?php echo esc_html__('Learn More', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                    <?php
                endwhile;
                
                echo '</div>';
                
                wp_reset_postdata();
            else :
                echo '<p class="no-services">' . esc_html__('No services found.', 'aqualuxe') . '</p>';
            endif;
            ?>
        </div>
        
        <?php if ($services_page_url && $view_all_text) : ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($services_page_url); ?>" class="btn btn-primary"><?php echo esc_html($view_all_text); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>