<?php
/**
 * Template part for displaying footer widgets
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-footer')) {
    return;
}
?>

<div class="footer-widgets bg-gray-50 dark:bg-gray-800 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            // Get all footer widgets
            $sidebar_widgets = wp_get_sidebars_widgets();
            $footer_widgets = isset($sidebar_widgets['sidebar-footer']) ? $sidebar_widgets['sidebar-footer'] : array();
            
            if (!empty($footer_widgets)) :
                $widget_count = count($footer_widgets);
                $widgets_per_column = ceil($widget_count / 4);
                $current_widget = 0;
                
                for ($column = 0; $column < 4; $column++) :
                    ?>
                    <div class="footer-column">
                        <?php
                        for ($i = 0; $i < $widgets_per_column && $current_widget < $widget_count; $i++) :
                            $widget_id = $footer_widgets[$current_widget];
                            $current_widget++;
                            ?>
                            <div class="footer-widget-item mb-6 last:mb-0">
                                <?php dynamic_sidebar('sidebar-footer'); ?>
                            </div>
                            <?php
                        endfor;
                        ?>
                    </div>
                    <?php
                endfor;
            else :
                // Default footer content when no widgets are set
                ?>
                <div class="footer-column">
                    <h4 class="footer-widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?>
                    </h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        <?php esc_html_e('Bringing elegance to aquatic life – globally. Premium aquatic solutions for discerning enthusiasts worldwide.', 'aqualuxe'); ?>
                    </p>
                    <div class="social-links flex space-x-4">
                        <?php aqualuxe_get_template_part('components/social-links'); ?>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h4 class="footer-widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                    </h4>
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><?php esc_html_e('Home', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                    </ul>
                </div>
                
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <div class="footer-column">
                    <h4 class="footer-widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Shop Categories', 'aqualuxe'); ?>
                    </h4>
                    <ul class="space-y-2">
                        <?php
                        $product_categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'number' => 6,
                        ));
                        
                        if (!is_wp_error($product_categories) && !empty($product_categories)) :
                            foreach ($product_categories as $category) :
                                ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                                       class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="footer-column">
                    <h4 class="footer-widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Contact Info', 'aqualuxe'); ?>
                    </h4>
                    <ul class="space-y-3">
                        <?php if (aqualuxe_get_option('address')) : ?>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-1 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400"><?php echo esc_html(aqualuxe_get_option('address')); ?></span>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (aqualuxe_get_option('phone')) : ?>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:<?php echo esc_attr(aqualuxe_get_option('phone')); ?>" 
                                   class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                    <?php echo esc_html(aqualuxe_get_option('phone')); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (aqualuxe_get_option('email')) : ?>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:<?php echo esc_attr(aqualuxe_get_option('email')); ?>" 
                                   class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                    <?php echo esc_html(aqualuxe_get_option('email')); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>