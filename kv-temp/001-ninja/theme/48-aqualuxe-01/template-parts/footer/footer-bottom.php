<?php
/**
 * Template part for displaying the footer bottom section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="footer-bottom bg-primary-dark text-white py-4 border-t border-primary">
    <div class="container mx-auto px-4">
        <div class="footer-bottom-inner flex flex-col md:flex-row justify-between items-center">
            <div class="footer-copyright text-center md:text-left mb-4 md:mb-0">
                <?php aqualuxe_copyright(); ?>
            </div>
            
            <div class="footer-navigation mb-4 md:mb-0">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'container'      => 'nav',
                        'container_class' => 'footer-navigation',
                        'menu_class'     => 'footer-menu flex flex-wrap justify-center gap-4',
                        'menu_id'        => 'footer-menu',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    )
                );
                ?>
            </div>
            
            <div class="footer-social">
                <?php aqualuxe_social_links(); ?>
            </div>
        </div>
    </div>
</div>