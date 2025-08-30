<?php
/**
 * Services Pricing Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_pricing_title', __('Our Pricing Plans', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_pricing_subtitle', __('Transparent Pricing', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_pricing_description', __('Choose the perfect service package that fits your needs and budget.', 'aqualuxe'));
$layout = get_theme_mod('aqualuxe_services_pricing_layout', 'cards'); // cards or table
$show_popular = get_theme_mod('aqualuxe_services_pricing_show_popular', true);
$currency = get_theme_mod('aqualuxe_services_pricing_currency', '$');
$period = get_theme_mod('aqualuxe_services_pricing_period', __('per month', 'aqualuxe'));
$button_text = get_theme_mod('aqualuxe_services_pricing_button_text', __('Get Started', 'aqualuxe'));
$show_disclaimer = get_theme_mod('aqualuxe_services_pricing_show_disclaimer', true);
$disclaimer = get_theme_mod('aqualuxe_services_pricing_disclaimer', __('* Prices may vary based on specific requirements and pool size. Contact us for a personalized quote.', 'aqualuxe'));

// Get pricing plans
// In a real implementation, this would likely use a custom post type or options
// For this template, we'll use sample data
$pricing_plans = [
    [
        'name' => __('Basic', 'aqualuxe'),
        'price' => '99',
        'description' => __('Essential maintenance for residential pools', 'aqualuxe'),
        'popular' => false,
        'features' => [
            __('Weekly water testing', 'aqualuxe'),
            __('Chemical balancing', 'aqualuxe'),
            __('Skimming and cleaning', 'aqualuxe'),
            __('Filter maintenance', 'aqualuxe'),
            __('Email support', 'aqualuxe'),
        ],
        'url' => '#basic-plan',
        'color' => 'blue',
    ],
    [
        'name' => __('Premium', 'aqualuxe'),
        'price' => '199',
        'description' => __('Comprehensive care for residential pools', 'aqualuxe'),
        'popular' => true,
        'features' => [
            __('Twice weekly service', 'aqualuxe'),
            __('Advanced water testing', 'aqualuxe'),
            __('Chemical balancing', 'aqualuxe'),
            __('Skimming and cleaning', 'aqualuxe'),
            __('Filter maintenance', 'aqualuxe'),
            __('Equipment inspection', 'aqualuxe'),
            __('Priority support', 'aqualuxe'),
        ],
        'url' => '#premium-plan',
        'color' => 'blue',
    ],
    [
        'name' => __('Ultimate', 'aqualuxe'),
        'price' => '349',
        'description' => __('Complete solution for luxury pools', 'aqualuxe'),
        'popular' => false,
        'features' => [
            __('Three weekly visits', 'aqualuxe'),
            __('Comprehensive water testing', 'aqualuxe'),
            __('Premium chemical treatment', 'aqualuxe'),
            __('Deep cleaning', 'aqualuxe'),
            __('Filter maintenance', 'aqualuxe'),
            __('Equipment inspection & tuning', 'aqualuxe'),
            __('Seasonal opening/closing', 'aqualuxe'),
            __('24/7 emergency support', 'aqualuxe'),
        ],
        'url' => '#ultimate-plan',
        'color' => 'blue',
    ],
];
?>

<section class="services-pricing py-16 md:py-24 bg-white dark:bg-gray-800">
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
        
        <?php if ($layout === 'cards') : ?>
            <div class="flex flex-wrap justify-center -mx-4">
                <?php foreach ($pricing_plans as $plan) : ?>
                    <?php 
                    $is_popular = !empty($plan['popular']) && $plan['popular'] && $show_popular;
                    $card_classes = 'w-full md:w-1/2 lg:w-1/3 px-4 mb-8';
                    if ($is_popular) {
                        $card_classes .= ' md:-mt-4';
                    }
                    ?>
                    <div class="<?php echo esc_attr($card_classes); ?>">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:transform hover:scale-105 <?php echo $is_popular ? 'border-2 border-blue-500 dark:border-blue-400' : ''; ?>">
                            <?php if ($is_popular) : ?>
                                <div class="bg-blue-500 text-white text-center py-2">
                                    <span class="font-semibold"><?php esc_html_e('Most Popular', 'aqualuxe'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6 <?php echo $is_popular ? 'bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20' : ''; ?>">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    <?php echo esc_html($plan['name']); ?>
                                </h3>
                                
                                <?php if (!empty($plan['description'])) : ?>
                                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                                        <?php echo esc_html($plan['description']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="flex items-end mb-6">
                                    <span class="text-gray-900 dark:text-white text-2xl font-semibold"><?php echo esc_html($currency); ?></span>
                                    <span class="text-gray-900 dark:text-white text-5xl font-bold"><?php echo esc_html($plan['price']); ?></span>
                                    <?php if ($period) : ?>
                                        <span class="text-gray-600 dark:text-gray-300 ml-2"><?php echo esc_html($period); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="p-6 flex-grow">
                                <?php if (!empty($plan['features']) && is_array($plan['features'])) : ?>
                                    <ul class="space-y-3 mb-6">
                                        <?php foreach ($plan['features'] as $feature) : ?>
                                            <li class="flex items-start">
                                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-700 dark:text-gray-300"><?php echo esc_html($feature); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-6 bg-gray-50 dark:bg-gray-800">
                                <?php if (!empty($plan['url']) && $button_text) : ?>
                                    <a href="<?php echo esc_url($plan['url']); ?>" class="block w-full py-3 px-4 text-center rounded-lg <?php echo $is_popular ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-900 dark:text-white'; ?> transition-colors font-semibold">
                                        <?php echo esc_html($button_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($layout === 'table') : ?>
            <div class="overflow-x-auto">
                <table class="w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="py-4 px-6 text-left text-gray-900 dark:text-white font-semibold"><?php esc_html_e('Features', 'aqualuxe'); ?></th>
                            <?php foreach ($pricing_plans as $plan) : ?>
                                <th class="py-4 px-6 text-center text-gray-900 dark:text-white font-semibold">
                                    <?php echo esc_html($plan['name']); ?>
                                    <?php if (!empty($plan['popular']) && $plan['popular'] && $show_popular) : ?>
                                        <div class="mt-2">
                                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-blue-500 text-white rounded-full">
                                                <?php esc_html_e('Popular', 'aqualuxe'); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-semibold"><?php esc_html_e('Price', 'aqualuxe'); ?></td>
                            <?php foreach ($pricing_plans as $plan) : ?>
                                <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-700 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-gray-900 dark:text-white text-lg font-semibold"><?php echo esc_html($currency); ?></span>
                                        <span class="text-gray-900 dark:text-white text-2xl font-bold"><?php echo esc_html($plan['price']); ?></span>
                                        <?php if ($period) : ?>
                                            <span class="text-gray-600 dark:text-gray-300 ml-1"><?php echo esc_html($period); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <?php
                        // Get all unique features
                        $all_features = [];
                        foreach ($pricing_plans as $plan) {
                            if (!empty($plan['features']) && is_array($plan['features'])) {
                                foreach ($plan['features'] as $feature) {
                                    if (!in_array($feature, $all_features)) {
                                        $all_features[] = $feature;
                                    }
                                }
                            }
                        }
                        
                        // Display features
                        foreach ($all_features as $feature) :
                        ?>
                            <tr>
                                <td class="py-3 px-6 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                    <?php echo esc_html($feature); ?>
                                </td>
                                <?php foreach ($pricing_plans as $plan) : ?>
                                    <td class="py-3 px-6 border-b border-gray-200 dark:border-gray-700 text-center">
                                        <?php if (!empty($plan['features']) && is_array($plan['features']) && in_array($feature, $plan['features'])) : ?>
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        <?php else : ?>
                                            <svg class="w-5 h-5 text-gray-400 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        
                        <tr>
                            <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-700"></td>
                            <?php foreach ($pricing_plans as $plan) : ?>
                                <?php $is_popular = !empty($plan['popular']) && $plan['popular'] && $show_popular; ?>
                                <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-700 text-center">
                                    <?php if (!empty($plan['url']) && $button_text) : ?>
                                        <a href="<?php echo esc_url($plan['url']); ?>" class="inline-block py-2 px-4 rounded-lg <?php echo $is_popular ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-900 dark:text-white'; ?> transition-colors font-semibold">
                                            <?php echo esc_html($button_text); ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <?php if ($show_disclaimer && $disclaimer) : ?>
            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                <?php echo esc_html($disclaimer); ?>
            </div>
        <?php endif; ?>
    </div>
</section>