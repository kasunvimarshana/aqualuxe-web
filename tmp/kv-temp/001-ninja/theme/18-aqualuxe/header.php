<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo is_admin_bar_showing() ? 'admin-bar' : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site flex flex-col min-h-screen">
    <a class="skip-link sr-only focus:not-sr-only" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <?php
    // Top bar with contact info and social links
    if (get_theme_mod('aqualuxe_show_topbar', true)) :
    ?>
    <div class="bg-primary-dark text-white py-2">
        <div class="container-fluid flex flex-wrap justify-between items-center text-sm">
            <div class="flex items-center space-x-4">
                <?php if (get_theme_mod('aqualuxe_phone')) : ?>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="tel:<?php echo esc_attr(get_theme_mod('aqualuxe_phone')); ?>" class="hover:text-accent transition-colors duration-300">
                            <?php echo esc_html(get_theme_mod('aqualuxe_phone')); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (get_theme_mod('aqualuxe_email')) : ?>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email')); ?>" class="hover:text-accent transition-colors duration-300">
                            <?php echo esc_html(get_theme_mod('aqualuxe_email')); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (get_theme_mod('aqualuxe_address')) : ?>
                    <div class="hidden md:flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span><?php echo esc_html(get_theme_mod('aqualuxe_address')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php
                // Social links
                $social_networks = [
                    'facebook' => [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
                    ],
                    'twitter' => [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
                    ],
                    'instagram' => [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
                    ],
                    'youtube' => [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>',
                    ],
                    'pinterest' => [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>',
                    ],
                ];
                
                foreach ($social_networks as $network => $data) {
                    $url = get_theme_mod("aqualuxe_{$network}_url");
                    if ($url) {
                        echo '<a href="' . esc_url($url) . '" class="hover:text-accent transition-colors duration-300" target="_blank" rel="noopener noreferrer">' . $data['icon'] . '</a>';
                    }
                }
                ?>
                
                <!-- Language Switcher -->
                <?php if (function_exists('aqualuxe_language_switcher')) : ?>
                    <div class="language-switcher">
                        <?php aqualuxe_language_switcher(); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Dark Mode Toggle -->
                <div class="dark-mode-toggle">
                    <input type="checkbox" id="dark-mode-toggle" class="peer sr-only">
                    <label for="dark-mode-toggle" class="toggle peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-light cursor-pointer"></label>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <header id="masthead" class="site-header bg-white dark:bg-dark-bg shadow-sm sticky top-0 z-50">
        <div class="container-fluid py-4">
            <div class="flex justify-between items-center">
                <div class="site-branding flex items-center">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold text-primary dark:text-white">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php
                        $aqualuxe_description = get_bloginfo('description', 'display');
                        if ($aqualuxe_description || is_customize_preview()) :
                        ?>
                            <p class="site-description ml-4 text-sm text-gray-600 dark:text-gray-300"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <nav id="site-navigation" class="main-navigation hidden lg:flex">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'flex space-x-6',
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>

                <div class="flex items-center space-x-4">
                    <?php if (class_exists('WooCommerce')) : ?>
                        <!-- Search -->
                        <div class="relative">
                            <button id="search-toggle" class="p-2 hover:text-primary transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            <div id="search-form" class="absolute right-0 top-full mt-2 w-64 bg-white dark:bg-dark-card shadow-lg rounded-lg p-4 hidden">
                                <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                                    <div class="flex">
                                        <input type="search" id="woocommerce-product-search-field" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-l-md focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search products&hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 rounded-r-md transition-colors duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="post_type" value="product" />
                                </form>
                            </div>
                        </div>

                        <!-- Account -->
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="p-2 hover:text-primary transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>

                        <!-- Wishlist -->
                        <?php if (function_exists('aqualuxe_get_wishlist_url')) : ?>
                            <a href="<?php echo esc_url(aqualuxe_get_wishlist_url()); ?>" class="p-2 hover:text-primary transition-colors duration-300 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <?php if (function_exists('aqualuxe_get_wishlist_count') && aqualuxe_get_wishlist_count() > 0) : ?>
                                    <span class="wishlist-count absolute -top-1 -right-1 bg-accent text-primary-dark text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                        <?php echo esc_html(aqualuxe_get_wishlist_count()); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <!-- Cart -->
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="p-2 hover:text-primary transition-colors duration-300 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-accent text-primary-dark text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                    <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-button" class="lg:hidden p-2 hover:text-primary transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white dark:bg-dark-bg border-t border-gray-200 dark:border-gray-700">
            <div class="container-fluid py-4">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'container'      => false,
                        'menu_class'     => 'mobile-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content flex-grow">