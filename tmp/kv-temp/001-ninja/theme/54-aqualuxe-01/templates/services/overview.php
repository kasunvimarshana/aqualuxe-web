<?php
/**
 * Services Overview Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_overview_title', __('Comprehensive Aquatic Solutions', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_overview_subtitle', __('What We Offer', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_overview_description', __('Our team of experts provides a wide range of services to meet all your aquatic needs, from installation to maintenance.', 'aqualuxe'));
$image = get_theme_mod('aqualuxe_services_overview_image', get_template_directory_uri() . '/assets/images/services-overview.jpg');
$image_position = get_theme_mod('aqualuxe_services_overview_image_position', 'right');
$features = get_theme_mod('aqualuxe_services_overview_features', [
    [
        'icon' => 'water',
        'title' => __('Professional Installation', 'aqualuxe'),
        'description' => __('Expert installation of all aquatic systems with precision and care.', 'aqualuxe'),
    ],
    [
        'icon' => 'tools',
        'title' => __('Regular Maintenance', 'aqualuxe'),
        'description' => __('Scheduled maintenance to keep your systems running at peak performance.', 'aqualuxe'),
    ],
    [
        'icon' => 'life-ring',
        'title' => __('Emergency Support', 'aqualuxe'),
        'description' => __('24/7 emergency support for critical system issues.', 'aqualuxe'),
    ],
]);
?>

<section class="services-overview py-16 md:py-24 bg-white dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
            <?php if ($image_position === 'left') : ?>
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <div class="rounded-lg overflow-hidden shadow-xl">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-auto">
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="w-full <?php echo $image ? 'lg:w-1/2' : ''; ?> <?php echo ($image && $image_position === 'left') ? 'lg:pl-12' : 'lg:pr-12'; ?>">
                <?php if ($subtitle) : ?>
                    <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 bg-opacity-50">
                        <?php echo esc_html($subtitle); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($description) : ?>
                    <p class="text-lg mb-8 text-gray-700 dark:text-gray-300">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($features && is_array($features)) : ?>
                    <div class="space-y-6">
                        <?php foreach ($features as $feature) : ?>
                            <div class="flex items-start">
                                <?php if (!empty($feature['icon'])) : ?>
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                            <i class="fas fa-<?php echo esc_attr($feature['icon']); ?> text-xl"></i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <?php if (!empty($feature['title'])) : ?>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                                            <?php echo esc_html($feature['title']); ?>
                                        </h3>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($feature['description'])) : ?>
                                        <p class="text-gray-700 dark:text-gray-300">
                                            <?php echo esc_html($feature['description']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($image && $image_position === 'right') : ?>
                <div class="w-full lg:w-1/2 mt-10 lg:mt-0">
                    <div class="rounded-lg overflow-hidden shadow-xl">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-auto">
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>