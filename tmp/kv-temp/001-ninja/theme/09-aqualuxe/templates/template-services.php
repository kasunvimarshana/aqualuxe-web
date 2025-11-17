<?php
/**
 * Template Name: Services
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <div class="page-description text-lg text-gray-600 max-w-3xl mx-auto">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="page-content">
            <?php
            // Display the page content first
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            
            // Get all service categories
            $service_categories = get_terms(array(
                'taxonomy' => 'service_category',
                'hide_empty' => true,
            ));
            
            if (!empty($service_categories) && !is_wp_error($service_categories)) :
                // Loop through each category
                foreach ($service_categories as $category) :
                    // Get services in this category
                    $services = new WP_Query(array(
                        'post_type' => 'service',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'service_category',
                                'field' => 'term_id',
                                'terms' => $category->term_id,
                            ),
                        ),
                    ));
                    
                    if ($services->have_posts()) :
                        ?>
                        <section class="service-category-section mb-16">
                            <h2 class="category-title text-3xl font-bold mb-8 pb-2 border-b border-gray-200"><?php echo esc_html($category->name); ?></h2>
                            
                            <?php if (!empty($category->description)) : ?>
                                <div class="category-description text-lg text-gray-600 mb-8">
                                    <?php echo wp_kses_post($category->description); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <?php
                                while ($services->have_posts()) :
                                    $services->the_post();
                                    
                                    // Get service meta
                                    $price = get_post_meta(get_the_ID(), '_service_price', true);
                                    $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                                    $icon = get_post_meta(get_the_ID(), '_service_icon', true);
                                    ?>
                                    
                                    <div class="service-item bg-white rounded-lg shadow-md p-6 transition-transform duration-300 hover:transform hover:scale-105">
                                        <?php if ($icon) : ?>
                                            <div class="service-icon text-4xl text-primary mb-4">
                                                <i class="fa <?php echo esc_attr($icon); ?>"></i>
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
                                        
                                        <div class="service-meta flex flex-wrap gap-4 mb-4 text-sm">
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
                endforeach;
            else :
                // If no categories, just show all services
                $services = new WP_Query(array(
                    'post_type' => 'service',
                    'posts_per_page' => -1,
                ));
                
                if ($services->have_posts()) :
                    ?>
                    <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        while ($services->have_posts()) :
                            $services->the_post();
                            
                            // Get service meta
                            $price = get_post_meta(get_the_ID(), '_service_price', true);
                            $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                            $icon = get_post_meta(get_the_ID(), '_service_icon', true);
                            ?>
                            
                            <div class="service-item bg-white rounded-lg shadow-md p-6 transition-transform duration-300 hover:transform hover:scale-105">
                                <?php if ($icon) : ?>
                                    <div class="service-icon text-4xl text-primary mb-4">
                                        <i class="fa <?php echo esc_attr($icon); ?>"></i>
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
                                
                                <div class="service-meta flex flex-wrap gap-4 mb-4 text-sm">
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
                                
                                <a href="<?php the_permalink(); ?>" class="service-link inline-block text-primary hover:text-secondary">
                                    <?php esc_html_e('Learn More', 'aqualuxe'); ?> <i class="fa fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            
                        <?php endwhile; ?>
                    </div>
                    <?php
                else :
                    ?>
                    <div class="no-services text-center py-12">
                        <p class="text-lg text-gray-600"><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
                    </div>
                    <?php
                endif;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();