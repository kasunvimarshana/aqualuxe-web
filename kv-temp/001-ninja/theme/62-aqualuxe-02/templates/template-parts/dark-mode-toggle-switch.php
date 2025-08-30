<?php
/**
 * Template part for displaying dark mode toggle switch
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get args
$args = isset( $args ) ? $args : array();

// Default arguments
$defaults = array(
    'location' => 'header',
);

// Parse arguments
$args = wp_parse_args( $args, $defaults );

// Add class based on location
$class = 'aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--switch aqualuxe-dark-mode-toggle--' . esc_attr( $args['location'] );
?>

<div class="<?php echo esc_attr( $class ); ?>">
    <label for="aqualuxe-dark-mode-toggle-<?php echo esc_attr( $args['location'] ); ?>" class="aqualuxe-dark-mode-toggle__label">
        <input type="checkbox" id="aqualuxe-dark-mode-toggle-<?php echo esc_attr( $args['location'] ); ?>" class="aqualuxe-dark-mode-toggle__checkbox">
        <span class="aqualuxe-dark-mode-toggle__switch"></span>
        <span class="aqualuxe-dark-mode-toggle__text"><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></span>
    </label>
</div>