<?php
/**
 * Template part for displaying the four-column footer layout
 *
 * @package AquaLuxe
 */
?>

<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <?php dynamic_sidebar('footer-1'); ?>
        <?php else : ?>
            <h3 class="widget-title text-lg font-bold mb-4 pb-2 border-b border-secondary-800"><?php esc_html_e('About Us', 'aqualuxe'); ?></h3>
            <p class="mb-4"><?php esc_html_e('AquaLuxe is a premium WordPress theme for luxury businesses with WooCommerce integration.', 'aqualuxe'); ?></p>
            <div class="social-icons flex space-x-4">
                <a href="#" class="text-secondary-400 hover:text-primary-400" aria-label="<?php esc_attr_e('Facebook', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                    </svg>
                </a>
                <a href="#" class="text-secondary-400 hover:text-primary-400" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                </a>
                <a href="#" class="text-secondary-400 hover:text-primary-400" aria-label="<?php esc_attr_e('Instagram', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="#" class="text-secondary-400 hover:text-primary-400" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-2')) : ?>
            <?php dynamic_sidebar('footer-2'); ?>
        <?php else : ?>
            <h3 class="widget-title text-lg font-bold mb-4 pb-2 border-b border-secondary-800"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
            <ul class="footer-links space-y-2">
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('Home', 'aqualuxe'); ?></a></li>
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                <li><a href="#" class="text-secondary-400 hover:text-primary-400"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
            </ul>
        <?php endif; ?>
    </div>

    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-3')) : ?>
            <?php dynamic_sidebar('footer-3'); ?>
        <?php elseif (class_exists('WooCommerce')) : ?>
            <h3 class="widget-title text-lg font-bold mb-4 pb-2 border-b border-secondary-800"><?php esc_html_e('Latest Products', 'aqualuxe'); ?></h3>
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $products = new WP_Query($args);
            
            if ($products->have_posts()) :
                echo '<ul class="product-list space-y-4">';
                while ($products->have_posts()) : $products->the_post();
                    global $product;
                    ?>
                    <li class="flex items-center">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="flex-shrink-0 mr-3">
                                <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 object-cover rounded')); ?>
                            </a>
                        <?php endif; ?>
                        <div>
                            <a href="<?php the_permalink(); ?>" class="text-secondary-300 hover:text-primary-400 font-medium"><?php the_title(); ?></a>
                            <div class="text-secondary-400 text-sm"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                        </div>
                    </li>
                    <?php
                endwhile;
                echo '</ul>';
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__('No products found', 'aqualuxe') . '</p>';
            endif;
        else : ?>
            <h3 class="widget-title text-lg font-bold mb-4 pb-2 border-b border-secondary-800"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $recent_posts = new WP_Query($args);
            
            if ($recent_posts->have_posts()) :
                echo '<ul class="recent-posts space-y-4">';
                while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" class="text-secondary-300 hover:text-primary-400"><?php the_title(); ?></a>
                        <div class="text-secondary-400 text-xs"><?php echo get_the_date(); ?></div>
                    </li>
                    <?php
                endwhile;
                echo '</ul>';
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__('No posts found', 'aqualuxe') . '</p>';
            endif;
        ?>
        <?php endif; ?>
    </div>

    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-4')) : ?>
            <?php dynamic_sidebar('footer-4'); ?>
        <?php else : ?>
            <h3 class="widget-title text-lg font-bold mb-4 pb-2 border-b border-secondary-800"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></h3>
            <address class="not-italic text-secondary-400 space-y-2">
                <p class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>123 Luxury Street<br>Beverly Hills, CA 90210</span>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>(123) 456-7890</span>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>info@aqualuxe.example.com</span>
                </p>
            </address>
            
            <h4 class="text-base font-medium mt-6 mb-3"><?php esc_html_e('Opening Hours', 'aqualuxe'); ?></h4>
            <ul class="opening-hours text-secondary-400 space-y-1">
                <li class="flex justify-between">
                    <span><?php esc_html_e('Monday - Friday', 'aqualuxe'); ?></span>
                    <span>9:00 AM - 8:00 PM</span>
                </li>
                <li class="flex justify-between">
                    <span><?php esc_html_e('Saturday', 'aqualuxe'); ?></span>
                    <span>10:00 AM - 6:00 PM</span>
                </li>
                <li class="flex justify-between">
                    <span><?php esc_html_e('Sunday', 'aqualuxe'); ?></span>
                    <span>10:00 AM - 4:00 PM</span>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>