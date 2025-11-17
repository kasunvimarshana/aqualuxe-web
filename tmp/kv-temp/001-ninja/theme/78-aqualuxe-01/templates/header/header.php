<?php
/**
 * Header template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
$sticky_header = get_theme_mod('aqualuxe_sticky_header', true);
$transparent_header = get_theme_mod('aqualuxe_header_transparent', false) && is_front_page();

$header_classes = [
    'site-header',
    'bg-white',
    'border-b',
    'border-gray-200',
    'z-50',
];

if ($sticky_header) {
    $header_classes[] = 'sticky';
    $header_classes[] = 'top-0';
}

if ($transparent_header) {
    $header_classes[] = 'absolute';
    $header_classes[] = 'w-full';
    $header_classes[] = 'bg-transparent';
    $header_classes[] = 'border-transparent';
}

$header_classes[] = 'header-' . $header_layout;
?>

<header class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
    <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
        <div class="flex items-center justify-between py-4">
            
            <!-- Logo/Site Title -->
            <div class="site-branding flex items-center">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <div class="site-title-wrap">
                        <h1 class="site-title text-2xl font-bold">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-900 hover:text-primary transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php if (get_bloginfo('description')) : ?>
                            <p class="site-description text-sm text-gray-600">
                                <?php echo esc_html(get_bloginfo('description', 'display')); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu flex space-x-8',
                    'container' => false,
                    'walker' => new AquaLuxe_Walker_Nav_Menu(),
                    'fallback_cb' => 'aqualuxe_fallback_menu',
                ]);
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions flex items-center space-x-4">
                
                <!-- Search -->
                <button type="button" class="search-toggle p-2 text-gray-600 hover:text-primary transition-colors" aria-label="<?php esc_attr_e('Toggle Search', 'aqualuxe'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Dark Mode Toggle -->
                <?php if (aqualuxe_is_module_enabled('dark-mode')) : ?>
                    <button type="button" class="dark-mode-toggle p-2 text-gray-600 hover:text-primary transition-colors" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5 dark-mode-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg class="w-5 h-5 dark-mode-icon-dark hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                <?php endif; ?>

                <!-- WooCommerce Cart -->
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#'); ?>" class="cart-toggle relative p-2 text-gray-600 hover:text-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293A1 1 0 005.414 17H19M7 13v4a2 2 0 002 2h2m3-6v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <?php if (function_exists('WC') && WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                            <span class="cart-count absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Toggle -->
                <button type="button" class="mobile-menu-toggle lg:hidden p-2 text-gray-600 hover:text-primary transition-colors" aria-label="<?php esc_attr_e('Toggle Mobile Menu', 'aqualuxe'); ?>" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path class="close-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

            </div>
        </div>
    </div>

    <!-- Search Overlay -->
    <div class="search-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
        <div class="search-modal absolute top-20 left-1/2 transform -translate-x-1/2 w-full max-w-lg mx-auto bg-white rounded-lg shadow-xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold"><?php esc_html_e('Search', 'aqualuxe'); ?></h3>
                    <button type="button" class="search-close text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <?php aqualuxe_search_form(); ?>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu lg:hidden hidden">
        <div class="mobile-menu-overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        <div class="mobile-menu-content fixed top-0 right-0 h-full w-80 bg-white shadow-xl z-50 transform translate-x-full transition-transform">
            <div class="mobile-menu-header flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold"><?php esc_html_e('Menu', 'aqualuxe'); ?></h3>
                <button type="button" class="mobile-menu-close text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mobile-navigation p-4" aria-label="<?php esc_attr_e('Mobile Navigation', 'aqualuxe'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'menu_class' => 'mobile-nav-menu space-y-2',
                    'container' => false,
                    'walker' => new AquaLuxe_Walker_Nav_Menu(),
                    'fallback_cb' => 'aqualuxe_fallback_menu',
                ]);
                ?>
            </nav>
        </div>
    </div>
</header>

<?php
/**
 * Fallback menu for when no menu is assigned
 */
function aqualuxe_fallback_menu() {
    echo '<ul class="nav-menu flex space-x-8">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="text-gray-700 hover:text-primary transition-colors">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    if (aqualuxe_is_woocommerce_active() && get_option('woocommerce_shop_page_id')) {
        echo '<li><a href="' . esc_url(get_permalink(get_option('woocommerce_shop_page_id'))) . '" class="text-gray-700 hover:text-primary transition-colors">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
    }
    
    $pages = get_pages(['sort_column' => 'menu_order', 'number' => 5]);
    foreach ($pages as $page) {
        if ($page->ID !== get_option('page_on_front')) {
            echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '" class="text-gray-700 hover:text-primary transition-colors">' . esc_html($page->post_title) . '</a></li>';
        }
    }
    
    echo '</ul>';
}
