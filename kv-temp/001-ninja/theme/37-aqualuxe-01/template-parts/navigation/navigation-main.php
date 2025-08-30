<?php
/**
 * Template part for displaying the main navigation
 *
 * @package AquaLuxe
 */
?>

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