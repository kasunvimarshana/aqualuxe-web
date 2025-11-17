<?php
/**
 * Template part for displaying a fallback account page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="account-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('My Account', 'aqualuxe'); ?></h1>
    
    <div class="account-fallback-message mb-8">
        <p><?php esc_html_e('The account functionality is currently unavailable. WooCommerce plugin is required to access your account.', 'aqualuxe'); ?></p>
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
    
    <div class="account-illustration mt-12 mb-12 flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>
    
    <?php if (is_user_logged_in()) : ?>
        <div class="user-info mt-8 mb-12">
            <?php
            $current_user = wp_get_current_user();
            ?>
            <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Your Profile', 'aqualuxe'); ?></h2>
            
            <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md max-w-md mx-auto">
                <div class="flex items-center mb-6">
                    <?php echo get_avatar($current_user->ID, 80, '', '', array('class' => 'rounded-full mr-4')); ?>
                    <div class="text-left">
                        <h3 class="text-xl font-bold"><?php echo esc_html($current_user->display_name); ?></h3>
                        <p class="text-gray-600 dark:text-gray-400"><?php echo esc_html($current_user->user_email); ?></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <a href="<?php echo esc_url(admin_url('profile.php')); ?>" class="block p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium"><?php esc_html_e('Edit Profile', 'aqualuxe'); ?></span>
                    </a>
                    
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="block p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium"><?php esc_html_e('Logout', 'aqualuxe'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="login-section mt-8 mb-12">
            <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Login', 'aqualuxe'); ?></h2>
            
            <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md max-w-md mx-auto">
                <?php
                $args = array(
                    'redirect' => home_url(),
                    'form_id' => 'loginform',
                    'label_username' => __('Username or Email Address', 'aqualuxe'),
                    'label_password' => __('Password', 'aqualuxe'),
                    'label_remember' => __('Remember Me', 'aqualuxe'),
                    'label_log_in' => __('Log In', 'aqualuxe'),
                    'remember' => true
                );
                wp_login_form($args);
                ?>
                
                <div class="mt-4 text-center">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="text-blue-600 dark:text-blue-400 hover:underline">
                        <?php esc_html_e('Lost your password?', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <?php if (get_option('users_can_register')) : ?>
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="mb-4"><?php esc_html_e('Don\'t have an account?', 'aqualuxe'); ?></p>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-2 px-4 rounded transition-colors">
                            <?php esc_html_e('Register', 'aqualuxe'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="featured-content mt-16">
        <h2 class="text-2xl font-bold mb-8"><?php esc_html_e('Featured Content', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            // Display some featured content
            $args = array(
                'post_type' => array('post', 'services'),
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            $featured_query = new WP_Query($args);
            
            if ($featured_query->have_posts()) :
                while ($featured_query->have_posts()) : $featured_query->the_post();
            ?>
                <article class="featured-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
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
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</div>