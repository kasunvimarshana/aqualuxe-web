<?php
/**
 * Template part for displaying a fallback shop page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="shop-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Shop', 'aqualuxe'); ?></h1>
    
    <div class="shop-fallback-message mb-8">
        <p><?php esc_html_e('The shop is currently unavailable. WooCommerce plugin is required to browse products.', 'aqualuxe'); ?></p>
    </div>
    
    <?php if (current_user_can('install_plugins')) : ?>
        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="shop-fallback-button">
            <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
        </a>
    <?php endif; ?>
    
    <div class="featured-posts mt-16">
        <h2 class="text-2xl font-bold mb-8"><?php esc_html_e('Latest Blog Posts', 'aqualuxe'); ?></h2>
        
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
</div>