<?php
/**
 * Template part for displaying header top content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="header-top">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="header-top-inner">
            <?php
            // Display site logo
            aqualuxe_site_logo();
            
            // Display primary navigation
            aqualuxe_primary_navigation();
            
            // Display header actions
            ?>
            <div class="header-actions">
                <?php
                // Display search form
                aqualuxe_search_form();
                
                // Display dark mode toggle
                aqualuxe_dark_mode_toggle();
                
                // Display account link
                aqualuxe_account_link();
                
                // Display wishlist link
                aqualuxe_wishlist_link();
                
                // Display cart link
                aqualuxe_cart_link();
                ?>
            </div>
        </div>
    </div>
</div>