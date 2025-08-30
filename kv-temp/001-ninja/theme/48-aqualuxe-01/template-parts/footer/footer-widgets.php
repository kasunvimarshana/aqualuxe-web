<?php
/**
 * Template part for displaying the footer widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Check if footer widgets are active
if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
    return;
}

// Count active footer widget areas
$active_widgets = 0;
for ( $i = 1; $i <= 4; $i++ ) {
    if ( is_active_sidebar( 'footer-' . $i ) ) {
        $active_widgets++;
    }
}

// Set column class based on active widgets
$column_class = 'footer-widget-column';
if ( $active_widgets > 0 ) {
    $column_class .= ' footer-widget-column-' . $active_widgets;
    
    // Add grid classes based on number of active widgets
    switch ( $active_widgets ) {
        case 1:
            $grid_class = 'md:grid-cols-1';
            break;
        case 2:
            $grid_class = 'md:grid-cols-2';
            break;
        case 3:
            $grid_class = 'md:grid-cols-3';
            break;
        case 4:
            $grid_class = 'md:grid-cols-4';
            break;
        default:
            $grid_class = 'md:grid-cols-4';
    }
}
?>

<div class="footer-widgets bg-primary-dark text-white py-12">
    <div class="container mx-auto px-4">
        <div class="footer-widgets-inner grid grid-cols-1 <?php echo esc_attr( $grid_class ); ?> gap-8">
            <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                    <div class="<?php echo esc_attr( $column_class ); ?>">
                        <?php dynamic_sidebar( 'footer-' . $i ); ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        
        <?php if ( aqualuxe_get_option( 'enable_newsletter', true ) ) : ?>
            <div class="footer-newsletter mt-12 pt-8 border-t border-primary">
                <?php aqualuxe_newsletter_form(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>