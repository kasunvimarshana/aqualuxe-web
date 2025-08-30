<?php
/**
 * Template part for displaying the header main section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="header-main py-4">
    <div class="container mx-auto px-4">
        <div class="header-main-inner flex justify-between items-center">
            <div class="site-branding flex items-center">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo">
                        <?php aqualuxe_site_logo(); ?>
                    </div>
                <?php else : ?>
                    <div class="site-title-wrapper">
                        <h1 class="site-title text-2xl font-bold">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                        </h1>
                        <?php
                        $aqualuxe_description = get_bloginfo( 'description', 'display' );
                        if ( $aqualuxe_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description text-sm text-gray-600"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="header-navigation hidden lg:block">
                <?php aqualuxe_primary_navigation(); ?>
            </div>

            <div class="header-actions flex items-center space-x-4">
                <?php aqualuxe_search_form(); ?>
                <?php aqualuxe_dark_mode_toggle(); ?>
                
                <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                    <?php aqualuxe_mini_cart(); ?>
                <?php endif; ?>
                
                <div class="mobile-menu-toggle-wrapper lg:hidden">
                    <?php aqualuxe_mobile_menu_toggle(); ?>
                </div>
            </div>
        </div>
    </div>
</div>