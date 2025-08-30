<?php
/**
 * Homepage Services Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_services_title', __('Our Services', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_services_subtitle', __('Professional aquatic services for all your needs', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_homepage_services_show', true);
$view_all_text = get_theme_mod('aqualuxe_homepage_services_view_all', __('View All Services', 'aqualuxe'));
$view_all_url = get_theme_mod('aqualuxe_homepage_services_view_all_url', '#');

// Get services from customizer
$services = get_theme_mod('aqualuxe_homepage_services', [
    [
        'icon' => 'water',
        'title' => __('Aquarium Installation', 'aqualuxe'),
        'description' => __('Professional installation of aquariums of all sizes, from small home tanks to large commercial displays.', 'aqualuxe'),
        'url' => '#',
    ],
    [
        'icon' => 'tools',
        'title' => __('Maintenance & Cleaning', 'aqualuxe'),
        'description' => __('Regular maintenance services to keep your aquarium clean, healthy, and looking its best.', 'aqualuxe'),
        'url' => '#',
    ],
    [
        'icon' => 'leaf',
        'title' => __('Aquascaping', 'aqualuxe'),
        'description' => __('Creative aquascaping services to transform your aquarium into a stunning underwater landscape.', 'aqualuxe'),
        'url' => '#',
    ],
    [
        'icon' => 'fish',
        'title' => __('Fish Health Consultation', 'aqualuxe'),
        'description' => __('Expert advice and treatment for fish health issues to ensure your aquatic pets thrive.', 'aqualuxe'),
        'url' => '#',
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}
?>

<section class="aqualuxe-services py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($services as $service) : ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <?php if (!empty($service['icon'])) : ?>
                        <div class="text-primary-600 mb-4">
                            <?php aqualuxe_icon($service['icon'], ['class' => 'w-12 h-12']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($service['title'])) : ?>
                        <h3 class="text-xl font-semibold mb-3"><?php echo esc_html($service['title']); ?></h3>
                    <?php endif; ?>
                    
                    <?php if (!empty($service['description'])) : ?>
                        <p class="text-gray-600 dark:text-gray-400 mb-4"><?php echo esc_html($service['description']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($service['url'])) : ?>
                        <a href="<?php echo esc_url($service['url']); ?>" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($view_all_text && $view_all_url) : ?>
            <div class="text-center mt-12">
                <a href="<?php echo esc_url($view_all_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($view_all_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
/**
 * Helper function to display icons
 * This is a placeholder function that would be defined in the theme's functions.php or a helper file
 */
if (!function_exists('aqualuxe_icon')) {
    function aqualuxe_icon($icon, $args = []) {
        $class = isset($args['class']) ? $args['class'] : 'w-6 h-6';
        
        switch ($icon) {
            case 'water':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>';
                break;
            case 'tools':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>';
                break;
            case 'leaf':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
                break;
            case 'fish':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                </svg>';
                break;
            case 'trophy':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>';
                break;
            case 'globe':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
                break;
            case 'headset':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>';
                break;
            default:
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
        }
    }
}