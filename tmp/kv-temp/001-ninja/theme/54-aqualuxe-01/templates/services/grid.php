<?php
/**
 * Services Grid Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_grid_title', __('Our Services', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_grid_subtitle', __('What We Provide', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_grid_description', __('Explore our comprehensive range of professional aquatic services designed to meet your specific needs.', 'aqualuxe'));
$layout = get_theme_mod('aqualuxe_services_grid_layout', 'grid'); // grid or list
$columns = get_theme_mod('aqualuxe_services_grid_columns', 3); // 2, 3, or 4
$show_images = get_theme_mod('aqualuxe_services_grid_show_images', true);
$show_excerpt = get_theme_mod('aqualuxe_services_grid_show_excerpt', true);
$show_button = get_theme_mod('aqualuxe_services_grid_show_button', true);
$button_text = get_theme_mod('aqualuxe_services_grid_button_text', __('Learn More', 'aqualuxe'));

// Get services
// In a real implementation, this would likely use a custom post type for services
// For this template, we'll use sample data
$services = [
    [
        'title' => __('Pool Installation', 'aqualuxe'),
        'excerpt' => __('Professional installation of residential and commercial pools with expert precision and attention to detail.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/pool-installation.jpg',
        'url' => '#pool-installation',
        'icon' => 'swimming-pool',
    ],
    [
        'title' => __('Maintenance & Cleaning', 'aqualuxe'),
        'excerpt' => __('Regular maintenance and cleaning services to keep your pool in pristine condition year-round.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/maintenance.jpg',
        'url' => '#maintenance',
        'icon' => 'tools',
    ],
    [
        'title' => __('Repair Services', 'aqualuxe'),
        'excerpt' => __('Expert repair services for all types of pool equipment, fixtures, and systems.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/repair.jpg',
        'url' => '#repair',
        'icon' => 'wrench',
    ],
    [
        'title' => __('Water Treatment', 'aqualuxe'),
        'excerpt' => __('Professional water treatment services to ensure clean, safe, and balanced pool water.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/water-treatment.jpg',
        'url' => '#water-treatment',
        'icon' => 'flask',
    ],
    [
        'title' => __('Renovation & Upgrades', 'aqualuxe'),
        'excerpt' => __('Transform your existing pool with our comprehensive renovation and upgrade services.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/renovation.jpg',
        'url' => '#renovation',
        'icon' => 'hammer',
    ],
    [
        'title' => __('Consultation & Design', 'aqualuxe'),
        'excerpt' => __('Expert consultation and design services to help you create the perfect aquatic environment.', 'aqualuxe'),
        'image' => get_template_directory_uri() . '/assets/images/services/consultation.jpg',
        'url' => '#consultation',
        'icon' => 'drafting-compass',
    ],
];

// Set column classes based on columns setting
switch ($columns) {
    case 2:
        $column_classes = 'md:grid-cols-2';
        break;
    case 4:
        $column_classes = 'md:grid-cols-2 lg:grid-cols-4';
        break;
    case 3:
    default:
        $column_classes = 'md:grid-cols-2 lg:grid-cols-3';
        break;
}
?>

<section class="services-grid py-16 md:py-24 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
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
                <p class="text-lg text-gray-700 dark:text-gray-300">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <?php if ($layout === 'grid') : ?>
            <div class="grid grid-cols-1 <?php echo esc_attr($column_classes); ?> gap-8">
                <?php foreach ($services as $service) : ?>
                    <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                        <?php if ($show_images && !empty($service['image'])) : ?>
                            <div class="service-image h-48 bg-cover bg-center" style="background-image: url('<?php echo esc_url($service['image']); ?>');">
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <?php if (!empty($service['icon'])) : ?>
                                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 mr-3">
                                        <i class="fas fa-<?php echo esc_attr($service['icon']); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    <?php echo esc_html($service['title']); ?>
                                </h3>
                            </div>
                            
                            <?php if ($show_excerpt && !empty($service['excerpt'])) : ?>
                                <p class="text-gray-700 dark:text-gray-300 mb-4">
                                    <?php echo esc_html($service['excerpt']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($show_button && !empty($service['url'])) : ?>
                                <a href="<?php echo esc_url($service['url']); ?>" class="inline-block text-blue-600 dark:text-blue-400 font-medium hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                    <?php echo esc_html($button_text); ?> <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="space-y-8">
                <?php foreach ($services as $service) : ?>
                    <div class="service-item bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <?php if ($show_images && !empty($service['image'])) : ?>
                                <div class="service-image md:w-1/3 h-48 md:h-auto bg-cover bg-center" style="background-image: url('<?php echo esc_url($service['image']); ?>');">
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6 md:w-2/3">
                                <div class="flex items-center mb-4">
                                    <?php if (!empty($service['icon'])) : ?>
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 mr-3">
                                            <i class="fas fa-<?php echo esc_attr($service['icon']); ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                        <?php echo esc_html($service['title']); ?>
                                    </h3>
                                </div>
                                
                                <?php if ($show_excerpt && !empty($service['excerpt'])) : ?>
                                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                                        <?php echo esc_html($service['excerpt']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($show_button && !empty($service['url'])) : ?>
                                    <a href="<?php echo esc_url($service['url']); ?>" class="inline-block text-blue-600 dark:text-blue-400 font-medium hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                        <?php echo esc_html($button_text); ?> <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>