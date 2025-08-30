<?php
/**
 * Template part for displaying a fallback shop page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="shop-fallback container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Shop', 'aqualuxe'); ?></h1>
        
        <div class="shop-fallback-message mb-8 max-w-2xl mx-auto">
            <p class="text-lg text-gray-600 dark:text-gray-300"><?php esc_html_e('Our online shop is currently being updated. Please check back soon or contact us for product inquiries.', 'aqualuxe'); ?></p>
        </div>
        
        <?php if (current_user_can('install_plugins')) : ?>
            <div class="admin-message bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 p-4 rounded-lg mb-8 max-w-2xl mx-auto">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <?php esc_html_e('Admin Notice: WooCommerce is required to enable the full shop functionality.', 'aqualuxe'); ?>
                </p>
                <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="shop-fallback-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors inline-block mt-2">
                    <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
    // Display WooCommerce fallback widgets if available
    if (function_exists('aqualuxe_display_woocommerce_fallback_widgets')) {
        aqualuxe_display_woocommerce_fallback_widgets();
    }
    ?>
    
    <div class="featured-categories mb-16">
        <h2 class="text-2xl font-bold mb-8 text-center"><?php esc_html_e('Featured Categories', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="category bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:-translate-y-1">
                <div class="category-image h-48 bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2"><?php esc_html_e('Tropical Fish', 'aqualuxe'); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4"><?php esc_html_e('Explore our collection of exotic tropical fish from around the world.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                        <?php esc_html_e('Contact for Availability', 'aqualuxe'); ?> →
                    </a>
                </div>
            </div>
            
            <div class="category bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:-translate-y-1">
                <div class="category-image h-48 bg-green-100 dark:bg-green-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2"><?php esc_html_e('Aquarium Equipment', 'aqualuxe'); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4"><?php esc_html_e('Professional-grade equipment for maintaining your aquatic ecosystem.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                        <?php esc_html_e('Contact for Availability', 'aqualuxe'); ?> →
                    </a>
                </div>
            </div>
            
            <div class="category bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:-translate-y-1">
                <div class="category-image h-48 bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2"><?php esc_html_e('Custom Aquariums', 'aqualuxe'); ?></h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4"><?php esc_html_e('Bespoke aquarium designs tailored to your space and preferences.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                        <?php esc_html_e('Request a Quote', 'aqualuxe'); ?> →
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="featured-posts mt-16">
        <h2 class="text-2xl font-bold mb-8 text-center"><?php esc_html_e('Latest Articles', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $recent_posts = wp_get_recent_posts(array(
                'numberposts' => 3,
                'post_status' => 'publish'
            ));
            
            foreach ($recent_posts as $post) :
            ?>
                <article class="post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail($post['ID'])) : ?>
                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                            <?php echo get_the_post_thumbnail($post['ID'], 'medium', array('class' => 'w-full h-48 object-cover')); ?>
                        </a>
                    <?php else : ?>
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">
                            <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php echo esc_html(get_the_title($post['ID'])); ?>
                            </a>
                        </h3>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <?php echo esc_html(get_the_date('', $post['ID'])); ?>
                        </div>
                        
                        <div class="text-gray-600 dark:text-gray-300 mb-4">
                            <?php echo wp_trim_words(get_the_excerpt($post['ID']), 20); ?>
                        </div>
                        
                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline">
                            <?php esc_html_e('Read More', 'aqualuxe'); ?> →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="contact-section mt-16 text-center">
        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Need Assistance?', 'aqualuxe'); ?></h2>
        <p class="mb-6 max-w-2xl mx-auto"><?php esc_html_e('Our aquatic experts are ready to help you find the perfect products for your aquarium.', 'aqualuxe'); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors inline-block">
            <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
        </a>
    </div>
</div>