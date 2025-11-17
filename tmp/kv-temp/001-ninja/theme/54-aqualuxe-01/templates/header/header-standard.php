<?php
/**
 * Template part for displaying the standard header layout
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$show_search = get_theme_mod('aqualuxe_show_search', true);
?>

<div class="aqualuxe-header-main py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="site-branding flex items-center">
                <?php
                if (has_custom_logo()) :
                    the_custom_logo();
                else :
                ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                    </h1>
                <?php
                endif;
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation hidden lg:block">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'menu_class' => 'flex space-x-6',
                ]);
                ?>
            </nav><!-- #site-navigation -->

            <div class="header-actions flex items-center space-x-4">
                <?php if ($show_search) : ?>
                    <button class="header-search-toggle text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-expanded="false" aria-controls="header-search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                    </button>
                <?php endif; ?>

                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="header-account text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="sr-only"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
                    </a>

                    <?php if (aqualuxe_is_module_active('wishlist')) : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('aqualuxe_wishlist_page'))); ?>" class="header-wishlist text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
                        </a>
                    <?php endif; ?>

                    <button class="header-cart-toggle text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-expanded="false" aria-controls="cart-drawer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="sr-only"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                        <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <?php
                            if (aqualuxe_is_woocommerce_active()) {
                                echo esc_html(WC()->cart->get_cart_contents_count());
                            } else {
                                echo '0';
                            }
                            ?>
                        </span>
                    </button>
                <?php endif; ?>

                <button class="mobile-menu-toggle lg:hidden text-gray-700 dark:text-gray-300" aria-expanded="false" aria-controls="mobile-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="sr-only"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if ($show_search) : ?>
    <div id="header-search" class="header-search bg-white dark:bg-gray-800 py-4 border-t border-gray-200 dark:border-gray-700 hidden">
        <div class="container mx-auto px-4">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="flex">
                    <input type="search" class="search-field flex-grow border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md focus:ring-primary-500 focus:border-primary-500" placeholder="<?php echo esc_attr_x('Search&hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    
                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <input type="hidden" name="post_type" value="product" />
                    <?php endif; ?>
                    
                    <button type="submit" class="search-submit bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-r-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="sr-only"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php
// WooCommerce category menu (only if WooCommerce is active)
if (aqualuxe_is_woocommerce_active()) :
?>
    <div class="aqualuxe-category-menu border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-3">
                <div class="category-dropdown relative group">
                    <button class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span><?php esc_html_e('Shop by Category', 'aqualuxe'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div class="category-dropdown-menu absolute left-0 top-full z-50 bg-white dark:bg-gray-800 shadow-lg rounded-md w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top scale-95 group-hover:scale-100">
                        <?php
                        $product_categories = get_terms([
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => 0,
                        ]);
                        
                        if (!empty($product_categories) && !is_wp_error($product_categories)) :
                        ?>
                            <ul class="py-2">
                                <?php foreach ($product_categories as $category) : ?>
                                    <li class="relative group/item">
                                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 flex justify-between items-center">
                                            <span><?php echo esc_html($category->name); ?></span>
                                            
                                            <?php
                                            $child_categories = get_terms([
                                                'taxonomy' => 'product_cat',
                                                'hide_empty' => true,
                                                'parent' => $category->term_id,
                                            ]);
                                            
                                            if (!empty($child_categories) && !is_wp_error($child_categories)) :
                                            ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                
                                                <div class="submenu absolute left-full top-0 bg-white dark:bg-gray-800 shadow-lg rounded-md w-64 opacity-0 invisible group-hover/item:opacity-100 group-hover/item:visible transition-all duration-300">
                                                    <ul class="py-2">
                                                        <?php foreach ($child_categories as $child_category) : ?>
                                                            <li>
                                                                <a href="<?php echo esc_url(get_term_link($child_category)); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    <?php echo esc_html($child_category->name); ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="featured-links hidden md:flex space-x-6">
                    <a href="<?php echo esc_url(home_url('/shop/new-arrivals/')); ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <?php esc_html_e('New Arrivals', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/shop/best-sellers/')); ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <?php esc_html_e('Best Sellers', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/shop/featured/')); ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <?php esc_html_e('Featured', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/shop/sale/')); ?>" class="text-red-600 hover:text-red-700 transition-colors">
                        <?php esc_html_e('Sale', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <div class="shipping-info hidden lg:block">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        <?php esc_html_e('Free shipping on orders over $100', 'aqualuxe'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>