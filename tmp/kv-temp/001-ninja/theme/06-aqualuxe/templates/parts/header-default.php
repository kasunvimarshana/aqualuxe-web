<?php
/**
 * Template part for displaying the default header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<header id="masthead" class="site-header header-default">
    <div class="header-top">
        <div class="container">
            <div class="header-top-inner">
                <div class="header-top-left">
                    <?php
                    if ( get_theme_mod( 'aqualuxe_header_contact_info', true ) ) {
                        $phone = get_theme_mod( 'aqualuxe_header_phone', '' );
                        $email = get_theme_mod( 'aqualuxe_header_email', '' );
                        
                        if ( $phone || $email ) {
                            echo '<div class="header-contact-info">';
                            
                            if ( $phone ) {
                                echo '<span class="header-phone"><i class="fa fa-phone"></i> ' . esc_html( $phone ) . '</span>';
                            }
                            
                            if ( $email ) {
                                echo '<span class="header-email"><i class="fa fa-envelope"></i> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></span>';
                            }
                            
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                
                <div class="header-top-right">
                    <?php
                    // Secondary menu
                    if ( has_nav_menu( 'secondary' ) ) {
                        aqualuxe_secondary_navigation();
                    }
                    
                    // Social links
                    if ( get_theme_mod( 'aqualuxe_header_social', true ) ) {
                        aqualuxe_social_links();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="header-main">
        <div class="container">
            <div class="header-main-inner">
                <?php aqualuxe_header_logo(); ?>
                
                <div class="header-navigation-wrapper">
                    <?php aqualuxe_primary_navigation(); ?>
                </div>
                
                <div class="header-actions">
                    <?php
                    // Header search
                    if ( get_theme_mod( 'aqualuxe_header_search', true ) ) {
                        aqualuxe_header_search();
                    }
                    
                    // Header icons (cart, account, wishlist)
                    aqualuxe_header_icons();
                    
                    // Mobile menu toggle
                    aqualuxe_mobile_menu();
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->

<?php
// Page header
if ( ! is_front_page() ) {
    aqualuxe_page_header();
}
?>