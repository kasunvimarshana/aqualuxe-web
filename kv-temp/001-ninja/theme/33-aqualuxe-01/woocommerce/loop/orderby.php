<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get orderby settings from theme customizer
$orderby_style = get_theme_mod( 'aqualuxe_product_orderby_style', 'dropdown' );
$show_result_count = get_theme_mod( 'aqualuxe_product_result_count', true );
$custom_orderby_options = get_theme_mod( 'aqualuxe_product_orderby_options', array() );

// If custom options are set, filter the default options
if ( ! empty( $custom_orderby_options ) && is_array( $custom_orderby_options ) ) {
    $enabled_options = array();
    foreach ( $custom_orderby_options as $option ) {
        if ( isset( $option['enabled'] ) && $option['enabled'] && isset( $option['key'] ) ) {
            $enabled_options[] = $option['key'];
        }
    }
    
    if ( ! empty( $enabled_options ) ) {
        add_filter( 'woocommerce_catalog_orderby', function( $orderby ) use ( $enabled_options ) {
            return array_intersect_key( $orderby, array_flip( $enabled_options ) );
        }, 20 );
    }
}
?>

<div class="aqualuxe-shop-ordering-wrapper aqualuxe-orderby-style-<?php echo esc_attr( $orderby_style ); ?>">
    <?php if ( $show_result_count ) : ?>
        <p class="woocommerce-result-count">
            <?php
            // phpcs:disable WordPress.Security
            if ( 1 === intval( $total ) ) {
                _e( 'Showing the single result', 'aqualuxe' );
            } elseif ( $total <= $per_page || -1 === $per_page ) {
                /* translators: %d: total results */
                printf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'aqualuxe' ), $total );
            } else {
                $first = ( $per_page * $current ) - $per_page + 1;
                $last  = min( $total, $per_page * $current );
                /* translators: 1: first result 2: last result 3: total results */
                printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'aqualuxe' ), $first, $last, $total );
            }
            // phpcs:enable WordPress.Security
            ?>
        </p>
    <?php endif; ?>

    <?php if ( $orderby_style === 'dropdown' ) : ?>
        <form class="woocommerce-ordering" method="get">
            <select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'aqualuxe' ); ?>">
                <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                    <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="paged" value="1" />
            <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
        </form>
    <?php elseif ( $orderby_style === 'buttons' ) : ?>
        <div class="aqualuxe-orderby-buttons">
            <span class="aqualuxe-orderby-label"><?php esc_html_e( 'Sort by:', 'aqualuxe' ); ?></span>
            <div class="aqualuxe-orderby-options">
                <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                    <?php
                    $current_url = add_query_arg( array(
                        'orderby' => $id,
                        'paged' => '1',
                    ), remove_query_arg( 'product-page' ) );
                    ?>
                    <a href="<?php echo esc_url( $current_url ); ?>" class="aqualuxe-orderby-option <?php echo $orderby === $id ? 'active' : ''; ?>">
                        <?php echo esc_html( $name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>