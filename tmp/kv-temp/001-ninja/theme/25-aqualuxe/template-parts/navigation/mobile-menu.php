<?php
/**
 * Template part for displaying the mobile menu
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<div id="mobile-menu" class="mobile-menu fixed inset-0 bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="mobile-menu-header flex justify-between items-center p-4 border-b">
        <div class="site-branding">
            <?php
            if (has_custom_logo()) :
                the_custom_logo();
            else :
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
            <?php endif; ?>
        </div>
        <button id="mobile-menu-close" class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="mobile-menu-content p-4 overflow-y-auto h-full pb-32">
        <nav class="mobile-navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-primary-menu',
                    'menu_class'     => 'mobile-menu-items',
                    'container'      => false,
                )
            );
            ?>
        </nav>
        
        <div class="mobile-contact mt-8 space-y-4">
            <?php if (get_theme_mod('aqualuxe_phone_number')) : ?>
                <a href="tel:<?php echo esc_attr(get_theme_mod('aqualuxe_phone_number')); ?>" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <?php echo esc_html(get_theme_mod('aqualuxe_phone_number')); ?>
                </a>
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_email')) : ?>
                <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email')); ?>" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <?php echo esc_html(get_theme_mod('aqualuxe_email')); ?>
                </a>
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_address')) : ?>
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <address class="not-italic">
                        <?php echo wp_kses_post(nl2br(get_theme_mod('aqualuxe_address'))); ?>
                    </address>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (has_nav_menu('social')) : ?>
            <div class="mobile-social-links mt-8">
                <h4 class="text-lg font-medium mb-3"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'social',
                        'menu_class'     => 'flex space-x-4',
                        'container'      => false,
                        'depth'          => 1,
                        'link_before'    => '<span class="screen-reader-text">',
                        'link_after'     => '</span>',
                    )
                );
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>