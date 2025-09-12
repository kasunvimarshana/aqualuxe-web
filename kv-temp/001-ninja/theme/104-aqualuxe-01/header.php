<?php
/**
 * Header template
 * 
 * @package AquaLuxe
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40" role="banner">
        <div class="container mx-auto px-4">
            
            <!-- Top Bar -->
            <div class="hidden lg:block py-2 border-b border-gray-100 dark:border-gray-800">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-6 text-gray-600 dark:text-gray-400">
                        <span><?php esc_html_e('Bringing elegance to aquatic life – globally', 'aqualuxe'); ?></span>
                        <?php if (aqualuxe_get_option('phone')) : ?>
                            <a href="tel:<?php echo esc_attr(aqualuxe_get_option('phone')); ?>" class="hover:text-primary-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <?php echo esc_html(aqualuxe_get_option('phone')); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (aqualuxe_get_option('email')) : ?>
                            <a href="mailto:<?php echo esc_attr(aqualuxe_get_option('email')); ?>" class="hover:text-primary-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <?php echo esc_html(aqualuxe_get_option('email')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <?php if (function_exists('pll_the_languages')) : ?>
                            <div class="language-switcher">
                                <?php pll_the_languages(['dropdown' => 1]); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Dark Mode Toggle -->
                        <?php aqualuxe_get_template_part('components/dark-mode-toggle'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Main Header -->
            <div class="flex items-center justify-between py-4">
                <!-- Site Branding -->
                <?php aqualuxe_get_template_part('header/site-branding'); ?>
                
                <!-- Primary Navigation -->
                <?php aqualuxe_get_template_part('header/primary-navigation'); ?>
                
                <!-- Mobile Navigation -->
                <?php aqualuxe_get_template_part('header/mobile-navigation'); ?>
            </div>
        </div>
    </header>
                <?php aqualuxe_get_template_part('header/primary-navigation'); ?>
                
                <!-- Mobile Navigation -->
                <?php aqualuxe_get_template_part('header/mobile-navigation'); ?>
            </div>
        </div>
    </header>
                    </button>
                    
                </div>
            </div>
            
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-4 py-4">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'menu_id' => 'mobile-menu-items',
                    'menu_class' => 'space-y-2',
                    'container' => false,
                    'fallback_cb' => 'wp_page_menu',
                    'walker' => new AquaLuxe_Mobile_Nav_Walker()
                ]);
                ?>
            </div>
        </div>
        
        <!-- Search Overlay -->
        <div id="search-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <?php esc_html_e('Search', 'aqualuxe'); ?>
                        </h3>
                        <button type="button" id="search-close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
        
    </header><!-- #masthead -->