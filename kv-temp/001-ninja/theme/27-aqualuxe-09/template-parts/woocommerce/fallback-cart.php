<?php
/**
 * Template part for displaying a fallback cart page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="cart-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></h1>
    
    <div class="cart-fallback-message mb-8">
        <p><?php esc_html_e('The shopping cart is currently unavailable. WooCommerce plugin is required to use the cart functionality.', 'aqualuxe'); ?></p>
    </div>
    
    <?php if (current_user_can('install_plugins')) : ?>
        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="shop-fallback-button">
            <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url(home_url()); ?>" class="shop-fallback-button">
            <?php esc_html_e('Return to Home', 'aqualuxe'); ?>
        </a>
    <?php endif; ?>
    
    <div class="cart-illustration mt-12 mb-12 flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    </div>
    
    <div class="cart-suggestions">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('You might be interested in', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            // Display some featured services or blog posts
            $args = array(
                'post_type' => 'services',
                'posts_per_page' => 3,
            );
            
            $services_query = new WP_Query($args);
            
            if ($services_query->have_posts()) :
                while ($services_query->have_posts()) : $services_query->the_post();
            ?>
                <div class="service-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                        </a>
                    <?php else : ?>
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="text-gray-600 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline">
                            <?php esc_html_e('Learn More', 'aqualuxe'); ?> →
                        </a>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback to regular posts if no services are found
                $recent_posts = get_posts(array('posts_per_page' => 3));
                foreach ($recent_posts as $post) :
                    setup_postdata($post);
            ?>
                <div class="post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                        </a>
                    <?php else : ?>
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <?php echo get_the_date(); ?>
                        </div>
                        
                        <div class="text-gray-600 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline">
                            <?php esc_html_e('Read More', 'aqualuxe'); ?> →
                        </a>
                    </div>
                </div>
            <?php
                endforeach;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</div>