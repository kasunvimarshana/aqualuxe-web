<?php
/**
 * Template part for displaying service cards
 *
 * @package AquaLuxe
 */

$services = isset($services) ? $services : [];
$columns = isset($columns) ? $columns : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
$show_excerpt = isset($show_excerpt) ? $show_excerpt : true;
$show_button = isset($show_button) ? $show_button : true;

if (empty($services)) {
    // Get services from custom post type if available
    $services_query = new WP_Query([
        'post_type' => 'aqualuxe_service',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'meta_query' => [
            [
                'key' => '_aqualuxe_service_featured',
                'value' => '1',
                'compare' => '='
            ]
        ]
    ]);
    
    if ($services_query->have_posts()) {
        while ($services_query->have_posts()) {
            $services_query->the_post();
            $services[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(),
                'permalink' => get_the_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'aqualuxe-featured'),
                'icon' => get_post_meta(get_the_ID(), '_aqualuxe_service_icon', true),
                'price' => get_post_meta(get_the_ID(), '_aqualuxe_service_price', true),
                'duration' => get_post_meta(get_the_ID(), '_aqualuxe_service_duration', true),
            ];
        }
        wp_reset_postdata();
    }
}

// Default services if none found
if (empty($services)) {
    $services = [
        [
            'title' => __('Aquarium Design & Setup', 'aqualuxe'),
            'excerpt' => __('Custom aquarium design and professional setup services for residential and commercial spaces.', 'aqualuxe'),
            'icon' => 'design',
            'price' => __('From $500', 'aqualuxe'),
            'duration' => __('2-5 days', 'aqualuxe'),
            'permalink' => '#',
        ],
        [
            'title' => __('Maintenance & Care', 'aqualuxe'),
            'excerpt' => __('Regular maintenance services to keep your aquatic environment healthy and beautiful.', 'aqualuxe'),
            'icon' => 'maintenance',
            'price' => __('From $75/month', 'aqualuxe'),
            'duration' => __('Monthly', 'aqualuxe'),
            'permalink' => '#',
        ],
        [
            'title' => __('Fish Health Consultation', 'aqualuxe'),
            'excerpt' => __('Expert consultation on fish health, disease prevention, and aquatic ecosystem balance.', 'aqualuxe'),
            'icon' => 'health',
            'price' => __('From $100', 'aqualuxe'),
            'duration' => __('1 hour', 'aqualuxe'),
            'permalink' => '#',
        ],
    ];
}

if (empty($services)) {
    return;
}
?>

<div class="services-grid">
    <div class="grid <?php echo esc_attr($columns); ?> gap-6">
        <?php foreach ($services as $service) : ?>
            <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                
                <!-- Service Image/Icon -->
                <div class="service-image relative h-48 bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                    <?php if (!empty($service['thumbnail'])) : ?>
                        <img 
                            src="<?php echo esc_url($service['thumbnail']); ?>" 
                            alt="<?php echo esc_attr($service['title']); ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                    <?php else : ?>
                        <div class="service-icon text-white">
                            <?php
                            $icon_class = 'w-16 h-16';
                            switch ($service['icon']) {
                                case 'design':
                                    ?>
                                    <svg class="<?php echo esc_attr($icon_class); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                    </svg>
                                    <?php
                                    break;
                                case 'maintenance':
                                    ?>
                                    <svg class="<?php echo esc_attr($icon_class); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php
                                    break;
                                case 'health':
                                    ?>
                                    <svg class="<?php echo esc_attr($icon_class); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <svg class="<?php echo esc_attr($icon_class); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <?php
                                    break;
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Service Badge -->
                    <?php if (!empty($service['price'])) : ?>
                        <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3 py-1 rounded-full text-sm font-medium">
                            <?php echo esc_html($service['price']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Service Content -->
                <div class="service-content p-6">
                    
                    <!-- Service Title -->
                    <h3 class="service-title text-xl font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                        <?php if (!empty($service['permalink'])) : ?>
                            <a href="<?php echo esc_url($service['permalink']); ?>">
                                <?php echo esc_html($service['title']); ?>
                            </a>
                        <?php else : ?>
                            <?php echo esc_html($service['title']); ?>
                        <?php endif; ?>
                    </h3>
                    
                    <!-- Service Meta -->
                    <?php if (!empty($service['duration'])) : ?>
                        <div class="service-meta mb-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <?php echo esc_html($service['duration']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Service Excerpt -->
                    <?php if ($show_excerpt && !empty($service['excerpt'])) : ?>
                        <p class="service-excerpt text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                            <?php echo esc_html($service['excerpt']); ?>
                        </p>
                    <?php endif; ?>
                    
                    <!-- Service Button -->
                    <?php if ($show_button && !empty($service['permalink'])) : ?>
                        <div class="service-action">
                            <a href="<?php echo esc_url($service['permalink']); ?>" 
                               class="btn btn-outline btn-sm group-hover:btn-primary transition-all duration-200">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>