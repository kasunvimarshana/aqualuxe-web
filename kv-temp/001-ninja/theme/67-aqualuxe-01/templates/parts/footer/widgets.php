<?php
/**
 * Template part for displaying footer widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if footer widgets are enabled
if ( ! get_theme_mod( 'aqualuxe_footer_widgets', true ) ) {
    return;
}

// Get footer widgets columns
$columns = get_theme_mod( 'aqualuxe_footer_widgets_columns', '4' );

// Check if any footer widget area is active
if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) :
?>

<div class="footer-widgets">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="footer-widgets-inner columns-<?php echo esc_attr( $columns ); ?>">
            <?php
            for ( $i = 1; $i <= $columns; $i++ ) :
                if ( is_active_sidebar( 'footer-' . $i ) ) :
                    ?>
                    <div class="footer-widget footer-widget-<?php echo esc_attr( $i ); ?>">
                        <?php dynamic_sidebar( 'footer-' . $i ); ?>
                    </div>
                    <?php
                endif;
            endfor;
            ?>
        </div>
    </div>
</div>

<?php
endif;