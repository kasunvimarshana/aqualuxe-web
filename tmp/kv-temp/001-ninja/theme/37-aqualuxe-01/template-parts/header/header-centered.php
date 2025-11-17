<?php
/**
 * Template part for displaying the centered header layout
 *
 * @package AquaLuxe
 */
?>

<div class="py-4 flex flex-col items-center">
    <div class="site-branding text-center mb-4">
        <?php if (has_custom_logo()) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php else : ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
            </h1>
            
            <?php
            $aqualuxe_description = get_bloginfo('description', 'display');
            if ($aqualuxe_description || is_customize_preview()) :
            ?>
                <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div><!-- .site-branding -->

    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'aqualuxe'); ?>">
        <button id="menu-toggle" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="sr-only"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'menu hidden lg:flex',
                'container'      => false,
                'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
            )
        );
        ?>
    </nav><!-- #site-navigation -->

    <div id="mobile-menu" class="mobile-menu hidden w-full lg:hidden">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'menu_id'        => 'mobile-primary-menu',
                'menu_class'     => 'menu',
                'container'      => false,
                'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
            )
        );
        ?>
    </div><!-- #mobile-menu -->
</div>

<div class="header-actions flex justify-center space-x-4 pb-4">
    <?php if (get_theme_mod('aqualuxe_enable_search', true)) : ?>
        <button id="search-toggle" class="search-toggle" aria-expanded="false" aria-controls="search-modal">
            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    <?php endif; ?>

    <?php if (get_theme_mod('aqualuxe_enable_dark_mode', true)) : ?>
        <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
            <span class="sr-only"><?php esc_html_e('Toggle Dark Mode', 'aqualuxe'); ?></span>
            <span class="dark-mode-toggle-circle"></span>
        </button>
    <?php endif; ?>

    <?php if (class_exists('WooCommerce') && get_theme_mod('aqualuxe_enable_woocommerce_integration', true)) : ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="account-link">
            <span class="sr-only"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </a>

        <button id="mini-cart-toggle" class="mini-cart-toggle relative" aria-expanded="false" aria-controls="mini-cart">
            <span class="sr-only"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="cart-count absolute -top-2 -right-2 bg-primary-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
            </span>
        </button>

        <div id="mini-cart" class="mini-cart hidden absolute right-4 top-full mt-2 w-80 bg-white dark:bg-secondary-800 rounded-lg shadow-lg z-50 p-4">
            <div class="mini-cart-content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (get_theme_mod('aqualuxe_enable_search', true)) : ?>
    <div id="search-modal" class="search-modal hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="search-modal-content bg-white dark:bg-secondary-800 rounded-lg shadow-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold"><?php esc_html_e('Search', 'aqualuxe'); ?></h2>
                <button id="search-close" class="search-close">
                    <span class="sr-only"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <?php get_search_form(); ?>
        </div>
    </div>
<?php endif; ?>