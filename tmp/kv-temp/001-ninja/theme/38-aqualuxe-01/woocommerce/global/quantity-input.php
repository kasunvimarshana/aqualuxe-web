<?php
/**
 * Product quantity inputs
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
    </div>
    <?php
} else {
    /* translators: %s: Quantity. */
    $label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'aqualuxe' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'aqualuxe' );
    ?>
    <div class="quantity">
        <?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
        <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
        <div class="quantity-wrapper">
            <button type="button" class="quantity-button quantity-down" aria-label="<?php esc_attr_e( 'Decrease quantity', 'aqualuxe' ); ?>">−</button>
            <input
                type="number"
                id="<?php echo esc_attr( $input_id ); ?>"
                class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                step="<?php echo esc_attr( $step ); ?>"
                min="<?php echo esc_attr( $min_value ); ?>"
                max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                name="<?php echo esc_attr( $input_name ); ?>"
                value="<?php echo esc_attr( $input_value ); ?>"
                title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'aqualuxe' ); ?>"
                size="4"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                inputmode="<?php echo esc_attr( $inputmode ); ?>"
                autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'off' ); ?>"
            />
            <button type="button" class="quantity-button quantity-up" aria-label="<?php esc_attr_e( 'Increase quantity', 'aqualuxe' ); ?>">+</button>
        </div>
        <?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
    </div>
    <?php
}