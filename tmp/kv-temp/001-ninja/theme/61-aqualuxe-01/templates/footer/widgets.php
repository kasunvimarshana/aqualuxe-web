<?php
/**
 * Template part for displaying the footer widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get footer width
$footer_width = get_theme_mod( 'aqualuxe_footer_width', 'container' );

// Get footer widgets columns
$footer_widgets_columns = get_theme_mod( 'aqualuxe_footer_widgets_columns', 4 );

// Check if any footer widget area is active
$is_active = false;
for ( $i = 1; $i <= $footer_widgets_columns; $i++ ) {
    if ( is_active_sidebar( 'footer-' . $i ) ) {
        $is_active = true;
        break;
    }
}

// Return if no footer widget area is active
if ( ! $is_active ) {
    return;
}

// Set column class based on number of columns
$column_class = 'col-md-' . ( 12 / $footer_widgets_columns );
?>

<div class="footer-widgets">
    <div class="<?php echo esc_attr( $footer_width ); ?>">
        <div class="row">
            <?php for ( $i = 1; $i <= $footer_widgets_columns; $i++ ) : ?>
                <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                    <div class="footer-widget-area <?php echo esc_attr( $column_class ); ?>">
                        <?php dynamic_sidebar( 'footer-' . $i ); ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</div>