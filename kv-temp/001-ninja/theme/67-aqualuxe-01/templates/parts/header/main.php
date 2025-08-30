<?php
/**
 * Template part for displaying header main content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get header layout
$header_layout = aqualuxe_get_header_layout();

// Only display main header for certain layouts
if ( in_array( $header_layout, array( 'default', 'centered', 'split' ) ) ) :
?>

<div class="header-main">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="header-main-inner">
            <?php
            // Different layout for split header
            if ( $header_layout === 'split' ) :
                ?>
                <div class="header-main-left">
                    <?php aqualuxe_primary_navigation( 'left' ); ?>
                </div>
                
                <div class="header-main-center">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                
                <div class="header-main-right">
                    <?php aqualuxe_primary_navigation( 'right' ); ?>
                </div>
                <?php
            elseif ( $header_layout === 'centered' ) :
                ?>
                <div class="header-main-top">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                
                <div class="header-main-bottom">
                    <?php aqualuxe_primary_navigation(); ?>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</div>

<?php
endif;