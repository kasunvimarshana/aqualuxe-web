<?php
/**
 * Services Process Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_process_title', __('Our Process', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_process_subtitle', __('How We Work', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_process_description', __('Our streamlined process ensures a smooth experience from consultation to completion.', 'aqualuxe'));
$style = get_theme_mod('aqualuxe_services_process_style', 'timeline'); // timeline, steps, or cards
$steps = get_theme_mod('aqualuxe_services_process_steps', [
    [
        'number' => '1',
        'title' => __('Initial Consultation', 'aqualuxe'),
        'description' => __('We begin with a thorough consultation to understand your needs, preferences, and budget.', 'aqualuxe'),
        'icon' => 'comments',
    ],
    [
        'number' => '2',
        'title' => __('Custom Design', 'aqualuxe'),
        'description' => __('Our design team creates a custom plan tailored to your specific requirements and space.', 'aqualuxe'),
        'icon' => 'drafting-compass',
    ],
    [
        'number' => '3',
        'title' => __('Proposal & Approval', 'aqualuxe'),
        'description' => __('We present a detailed proposal including designs, materials, timeline, and costs for your approval.', 'aqualuxe'),
        'icon' => 'file-contract',
    ],
    [
        'number' => '4',
        'title' => __('Professional Installation', 'aqualuxe'),
        'description' => __('Our expert team handles the installation with precision, quality, and attention to detail.', 'aqualuxe'),
        'icon' => 'tools',
    ],
    [
        'number' => '5',
        'title' => __('Final Inspection', 'aqualuxe'),
        'description' => __('We conduct a thorough inspection to ensure everything meets our high standards of quality.', 'aqualuxe'),
        'icon' => 'clipboard-check',
    ],
    [
        'number' => '6',
        'title' => __('Ongoing Support', 'aqualuxe'),
        'description' => __('We provide continued support and maintenance services to keep your system in optimal condition.', 'aqualuxe'),
        'icon' => 'headset',
    ],
]);
?>

<section class="services-process py-16 md:py-24 bg-white dark:bg-gray-800">
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
        
        <?php if ($style === 'timeline' && $steps && is_array($steps)) : ?>
            <div class="relative">
                <!-- Vertical line for timeline -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-blue-200 dark:bg-blue-800"></div>
                
                <div class="space-y-12">
                    <?php foreach ($steps as $index => $step) : ?>
                        <?php $is_even = $index % 2 === 0; ?>
                        <div class="relative flex flex-col md:flex-row items-center">
                            <!-- Timeline dot -->
                            <div class="order-1 md:order-<?php echo $is_even ? '1' : '3'; ?> w-full md:w-1/2 <?php echo $is_even ? 'md:pr-8' : 'md:pl-8'; ?> <?php echo $is_even ? 'text-right' : 'text-left'; ?>">
                                <div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow-lg">
                                    <?php if (!empty($step['title'])) : ?>
                                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white flex items-center <?php echo $is_even ? 'justify-end' : 'justify-start'; ?>">
                                            <?php if (!$is_even && !empty($step['icon'])) : ?>
                                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 mr-3">
                                                    <i class="fas fa-<?php echo esc_attr($step['icon']); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php echo esc_html($step['title']); ?>
                                            
                                            <?php if ($is_even && !empty($step['icon'])) : ?>
                                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 ml-3">
                                                    <i class="fas fa-<?php echo esc_attr($step['icon']); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </h3>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($step['description'])) : ?>
                                        <p class="text-gray-700 dark:text-gray-300">
                                            <?php echo esc_html($step['description']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Center circle with number -->
                            <div class="order-2 absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg z-10">
                                <?php echo esc_html($step['number'] ?? ($index + 1)); ?>
                            </div>
                            
                            <!-- Empty space for opposite side -->
                            <div class="order-3 md:order-<?php echo $is_even ? '3' : '1'; ?> w-full md:w-1/2"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($style === 'steps' && $steps && is_array($steps)) : ?>
            <div class="flex flex-wrap -mx-4">
                <?php foreach ($steps as $step) : ?>
                    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold text-lg mr-4">
                                    <?php echo esc_html($step['number'] ?? ''); ?>
                                </div>
                                
                                <?php if (!empty($step['title'])) : ?>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                        <?php echo esc_html($step['title']); ?>
                                    </h3>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($step['description'])) : ?>
                                <p class="text-gray-700 dark:text-gray-300 flex-grow">
                                    <?php echo esc_html($step['description']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (!empty($step['icon'])) : ?>
                                <div class="mt-4 text-right">
                                    <i class="fas fa-<?php echo esc_attr($step['icon']); ?> text-2xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($style === 'cards' && $steps && is_array($steps)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($steps as $index => $step) : ?>
                    <div class="process-card bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-blue-600 text-white p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold"><?php echo esc_html($step['number'] ?? ($index + 1)); ?></span>
                                
                                <?php if (!empty($step['icon'])) : ?>
                                    <i class="fas fa-<?php echo esc_attr($step['icon']); ?> text-2xl"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <?php if (!empty($step['title'])) : ?>
                                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                    <?php echo esc_html($step['title']); ?>
                                </h3>
                            <?php endif; ?>
                            
                            <?php if (!empty($step['description'])) : ?>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <?php echo esc_html($step['description']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>