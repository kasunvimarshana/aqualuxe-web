<?php
/**
 * Template part for displaying a fallback analytics dashboard when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="analytics-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Analytics Dashboard', 'aqualuxe'); ?></h1>
    
    <div class="analytics-fallback-message mb-8">
        <p><?php esc_html_e('The analytics dashboard is currently unavailable. WooCommerce plugin is required to access analytics features.', 'aqualuxe'); ?></p>
    </div>
    
    <?php if (current_user_can('install_plugins')) : ?>
        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="analytics-fallback-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
            <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url(home_url()); ?>" class="analytics-fallback-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
            <?php esc_html_e('Return to Home', 'aqualuxe'); ?>
        </a>
    <?php endif; ?>
    
    <div class="analytics-illustration mt-12 mb-12 flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
    </div>
    
    <div class="analytics-alternative mt-16">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Alternative Analytics Options', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="analytics-option bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-xl font-bold mb-2"><?php esc_html_e('Google Analytics', 'aqualuxe'); ?></h3>
                <p class="mb-4"><?php esc_html_e('Track website traffic, user behavior, and conversion metrics with Google Analytics.', 'aqualuxe'); ?></p>
                <a href="https://analytics.google.com/" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">
                    <?php esc_html_e('Learn More', 'aqualuxe'); ?> →
                </a>
            </div>
            
            <div class="analytics-option bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-bold mb-2"><?php esc_html_e('WordPress Statistics', 'aqualuxe'); ?></h3>
                <p class="mb-4"><?php esc_html_e('Use WordPress statistics plugins to monitor site performance and visitor data.', 'aqualuxe'); ?></p>
                <a href="<?php echo esc_url(admin_url('plugin-install.php?s=statistics&tab=search&type=term')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                    <?php esc_html_e('Find Plugins', 'aqualuxe'); ?> →
                </a>
            </div>
            
            <div class="analytics-option bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-bold mb-2"><?php esc_html_e('Content Reports', 'aqualuxe'); ?></h3>
                <p class="mb-4"><?php esc_html_e('Generate reports on your content performance using WordPress dashboard tools.', 'aqualuxe'); ?></p>
                <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                    <?php esc_html_e('Go to Dashboard', 'aqualuxe'); ?> →
                </a>
            </div>
        </div>
    </div>
</div>