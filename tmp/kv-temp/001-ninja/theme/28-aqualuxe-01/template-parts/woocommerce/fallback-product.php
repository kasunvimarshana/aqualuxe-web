<?php
/**
 * Template part for displaying a fallback product page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="product-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Product Not Available', 'aqualuxe'); ?></h1>
    
    <div class="product-fallback-message mb-8">
        <p><?php esc_html_e('This product is currently unavailable. WooCommerce plugin is required to view products.', 'aqualuxe'); ?></p>
    </div>
    
    <?php if (current_user_can('install_plugins')) : ?>
        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="shop-fallback-button">
            <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
        </a>
    <?php endif; ?>
    
    <div class="featured-content mt-16">
        <h2 class="text-2xl font-bold mb-8"><?php esc_html_e('Featured Content', 'aqualuxe'); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php
            // Display some featured content like services or blog posts
            $featured_posts = get_posts(array(
                'posts_per_page' => 2,
                'post_type' => array('post', 'services'),
                'orderby' => 'rand'
            ));
            
            foreach ($featured_posts as $post) :
                setup_postdata($post);
            ?>
                <article class="featured-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-64 object-cover')); ?>
                        </a>
                    <?php else : ?>
                        <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
            endforeach;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>