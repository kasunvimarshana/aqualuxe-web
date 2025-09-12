<?php
/**
 * Navigation header template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

<nav class="site-header sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-neutral-200 dark:bg-neutral-900/95 dark:border-neutral-700" aria-label="<?php esc_attr_e('Main Navigation', 'aqualuxe'); ?>">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold text-primary-600 hover:text-primary-700 transition-colors" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class'     => 'flex items-center space-x-6',
                        'container'      => false,
                        'depth'          => 3,
                    ]);
                }
                ?>
            </div>
            
            <!-- Header Actions -->
            <div class="flex items-center space-x-4">
                
                <!-- Search Toggle -->
                <button
                    type="button"
                    class="p-2 text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400"
                    data-search-toggle
                    aria-label="<?php esc_attr_e('Open Search', 'aqualuxe'); ?>"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <!-- Dark Mode Toggle -->
                <button
                    type="button"
                    class="p-2 text-neutral-600 hover:text-primary-600 transition-all duration-200 dark:text-neutral-300 dark:hover:text-primary-400"
                    data-dark-mode-toggle
                    aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>"
                >
                    <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                
                <!-- WooCommerce Cart (if active) -->
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="relative p-2 text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M7 13v6a2 2 0 002 2h8a2 2 0 002-2v-6"></path>
                        </svg>
                        <?php
                        $cart_count = WC()->cart->get_cart_contents_count();
                        if ($cart_count > 0) :
                        ?>
                            <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?php echo esc_html($cart_count); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
                
                <!-- Mobile Menu Toggle -->
                <button
                    type="button"
                    class="lg:hidden p-2 text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400"
                    data-mobile-menu-toggle
                    aria-label="<?php esc_attr_e('Open Mobile Menu', 'aqualuxe'); ?>"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
            </div>
        </div>
    </div>
</nav>