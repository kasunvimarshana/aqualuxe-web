<?php
/**
 * Template Name: Product Comparison
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

get_header();

// Get compare list.
$compare_list = array();

// Check if user is logged in.
if ( is_user_logged_in() ) {
    // Get compare list from user meta.
    $compare_list = get_user_meta( get_current_user_id(), 'aqualuxe_compare_list', true );

    // Check if compare list exists.
    if ( ! $compare_list ) {
        $compare_list = array();
    }
} else {
    // Get compare list from cookie.
    $compare_list = isset( $_COOKIE['aqualuxe_compare_list'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_compare_list'] ) ), true ) : array();

    // Check if compare list exists.
    if ( ! $compare_list ) {
        $compare_list = array();
    }
}

// Get products.
$products = array();
foreach ( $compare_list as $product_id ) {
    $product = wc_get_product( $product_id );
    if ( $product ) {
        $products[] = $product;
    }
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="container">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html( get_the_title() ); ?></h1>
            </header>

            <div class="page-content">
                <?php
                // Check if there are products to compare.
                if ( ! empty( $products ) ) {
                    // Get attributes to compare.
                    $attributes = array();

                    // Loop through products.
                    foreach ( $products as $product ) {
                        // Get product attributes.
                        $product_attributes = $product->get_attributes();

                        // Loop through product attributes.
                        foreach ( $product_attributes as $attribute_name => $attribute ) {
                            // Skip if attribute is not visible.
                            if ( ! $attribute->get_visible() ) {
                                continue;
                            }

                            // Get attribute values.
                            $attribute_values = array();

                            // Check if attribute is a taxonomy.
                            if ( $attribute->is_taxonomy() ) {
                                // Get attribute terms.
                                $attribute_terms = $attribute->get_terms();

                                // Loop through attribute terms.
                                if ( $attribute_terms ) {
                                    foreach ( $attribute_terms as $attribute_term ) {
                                        $attribute_values[] = $attribute_term->name;
                                    }
                                }
                            } else {
                                // Get attribute value.
                                $attribute_values = $attribute->get_options();
                            }

                            // Add attribute values to attributes.
                            if ( ! isset( $attributes[ $attribute_name ] ) ) {
                                $attributes[ $attribute_name ] = array();
                            }

                            // Add attribute values to attributes.
                            $attributes[ $attribute_name ][ $product->get_id() ] = implode( ', ', $attribute_values );
                        }

                        // Add weight.
                        if ( $product->has_weight() ) {
                            $attributes['weight'][ $product->get_id() ] = $product->get_weight() . ' ' . get_option( 'woocommerce_weight_unit' );
                        }

                        // Add dimensions.
                        if ( $product->has_dimensions() ) {
                            $attributes['dimensions'][ $product->get_id() ] = $product->get_dimensions();
                        }

                        // Add SKU.
                        if ( $product->get_sku() ) {
                            $attributes['sku'][ $product->get_id() ] = $product->get_sku();
                        }

                        // Add stock.
                        $attributes['stock'][ $product->get_id() ] = $product->is_in_stock() ? __( 'In Stock', 'aqualuxe' ) : __( 'Out of Stock', 'aqualuxe' );
                    }
                    ?>
                    <div class="aqualuxe-compare-table-wrapper">
                        <table class="aqualuxe-compare-table">
                            <thead>
                                <tr>
                                    <th class="aqualuxe-compare-table-heading"><?php echo esc_html__( 'Product', 'aqualuxe' ); ?></th>
                                    <?php foreach ( $products as $product ) : ?>
                                        <th class="aqualuxe-compare-table-product">
                                            <div class="aqualuxe-compare-table-product-remove">
                                                <button type="button" class="aqualuxe-compare-table-product-remove-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                                    &times;
                                                </button>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-image">
                                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                                    <?php echo $product->get_image( 'thumbnail' ); ?>
                                                </a>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-title">
                                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                                    <?php echo esc_html( $product->get_name() ); ?>
                                                </a>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-price">
                                                <?php echo $product->get_price_html(); ?>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-rating">
                                                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-description">
                                                <?php echo wp_kses_post( $product->get_short_description() ); ?>
                                            </div>
                                            <div class="aqualuxe-compare-table-product-actions">
                                                <?php if ( $product->is_in_stock() ) : ?>
                                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button aqualuxe-compare-table-product-add-to-cart">
                                                        <?php echo esc_html( $product->add_to_cart_text() ); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <span class="aqualuxe-compare-table-product-out-of-stock">
                                                        <?php echo esc_html__( 'Out of Stock', 'aqualuxe' ); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $attributes as $attribute => $values ) : ?>
                                    <tr>
                                        <th class="aqualuxe-compare-table-attribute">
                                            <?php echo esc_html( wc_attribute_label( $attribute ) ); ?>
                                        </th>
                                        <?php foreach ( $products as $product ) : ?>
                                            <td class="aqualuxe-compare-table-value">
                                                <?php
                                                $product_id = $product->get_id();
                                                if ( isset( $values[ $product_id ] ) ) {
                                                    echo wp_kses_post( $values[ $product_id ] );
                                                } else {
                                                    echo '&mdash;';
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="aqualuxe-compare-empty">
                        <p><?php echo esc_html__( 'No products to compare.', 'aqualuxe' ); ?></p>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button"><?php echo esc_html__( 'Browse Products', 'aqualuxe' ); ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</div>

<?php
get_footer();