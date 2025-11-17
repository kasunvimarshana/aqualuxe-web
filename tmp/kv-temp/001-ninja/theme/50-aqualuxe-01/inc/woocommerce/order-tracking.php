<?php
/**
 * AquaLuxe Order Tracking Functions
 *
 * Functions for order tracking functionality
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get tracking URL for a shipping provider
 *
 * @param string $provider The shipping provider name
 * @param string $tracking_number The tracking number
 * @return string The tracking URL
 */
function aqualuxe_get_tracking_url( $provider, $tracking_number ) {
    $provider = strtolower( $provider );
    
    $tracking_urls = array(
        'usps'      => 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $tracking_number,
        'ups'       => 'https://www.ups.com/track?tracknum=' . $tracking_number,
        'fedex'     => 'https://www.fedex.com/apps/fedextrack/?tracknumbers=' . $tracking_number,
        'dhl'       => 'https://www.dhl.com/en/express/tracking.html?AWB=' . $tracking_number . '&brand=DHL',
        'ontrac'    => 'https://www.ontrac.com/tracking-details?trackingNum=' . $tracking_number,
        'amazon'    => 'https://track.amazon.com/tracking/' . $tracking_number,
        'royal mail' => 'https://www.royalmail.com/track-your-item#/tracking-results/' . $tracking_number,
        'canada post' => 'https://www.canadapost-postescanada.ca/track-reperage/en#/search?searchFor=' . $tracking_number,
        'australia post' => 'https://auspost.com.au/mypost/track/#/details/' . $tracking_number,
        'dpd'       => 'https://track.dpd.co.uk/search/' . $tracking_number,
        'hermes'    => 'https://www.evri.com/track/parcel/' . $tracking_number,
        'parcelforce' => 'https://www.parcelforce.com/track-trace?trackNumber=' . $tracking_number,
    );
    
    // Allow themes and plugins to add more tracking URLs
    $tracking_urls = apply_filters( 'aqualuxe_tracking_urls', $tracking_urls );
    
    if ( isset( $tracking_urls[ $provider ] ) ) {
        return $tracking_urls[ $provider ];
    }
    
    // Default to a Google search for the tracking number if provider not found
    return 'https://www.google.com/search?q=' . urlencode( $tracking_number . ' ' . $provider . ' tracking' );
}

/**
 * Add tracking information to order admin
 */
function aqualuxe_add_tracking_fields_to_order_admin() {
    add_meta_box(
        'aqualuxe_order_tracking',
        __( 'Shipping & Tracking', 'aqualuxe' ),
        'aqualuxe_order_tracking_meta_box',
        'shop_order',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_tracking_fields_to_order_admin' );

/**
 * Display tracking meta box content
 */
function aqualuxe_order_tracking_meta_box( $post ) {
    $order_id = $post->ID;
    $tracking_number = get_post_meta( $order_id, '_tracking_number', true );
    $tracking_provider = get_post_meta( $order_id, '_tracking_provider', true );
    $shipped_date = get_post_meta( $order_id, '_shipped_date', true );
    
    // Get list of shipping providers
    $providers = array(
        'usps' => 'USPS',
        'ups' => 'UPS',
        'fedex' => 'FedEx',
        'dhl' => 'DHL',
        'ontrac' => 'OnTrac',
        'amazon' => 'Amazon Logistics',
        'royal mail' => 'Royal Mail',
        'canada post' => 'Canada Post',
        'australia post' => 'Australia Post',
        'dpd' => 'DPD',
        'hermes' => 'Hermes/Evri',
        'parcelforce' => 'Parcelforce',
    );
    
    // Allow themes and plugins to add more providers
    $providers = apply_filters( 'aqualuxe_tracking_providers', $providers );
    
    wp_nonce_field( 'aqualuxe_save_tracking_data', 'aqualuxe_tracking_nonce' );
    ?>
    <p>
        <label for="tracking_provider"><?php esc_html_e( 'Shipping Provider:', 'aqualuxe' ); ?></label><br>
        <select name="tracking_provider" id="tracking_provider" class="widefat">
            <option value=""><?php esc_html_e( 'Select a provider...', 'aqualuxe' ); ?></option>
            <?php foreach ( $providers as $key => $name ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $tracking_provider, $key ); ?>>
                    <?php echo esc_html( $name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="tracking_number"><?php esc_html_e( 'Tracking Number:', 'aqualuxe' ); ?></label><br>
        <input type="text" name="tracking_number" id="tracking_number" class="widefat" value="<?php echo esc_attr( $tracking_number ); ?>">
    </p>
    <p>
        <label for="shipped_date"><?php esc_html_e( 'Date Shipped:', 'aqualuxe' ); ?></label><br>
        <input type="date" name="shipped_date" id="shipped_date" class="widefat" value="<?php echo esc_attr( $shipped_date ); ?>">
    </p>
    <?php
}

/**
 * Save tracking data
 */
function aqualuxe_save_tracking_data( $order_id ) {
    if ( ! isset( $_POST['aqualuxe_tracking_nonce'] ) || ! wp_verify_nonce( $_POST['aqualuxe_tracking_nonce'], 'aqualuxe_save_tracking_data' ) ) {
        return;
    }
    
    if ( isset( $_POST['tracking_number'] ) ) {
        update_post_meta( $order_id, '_tracking_number', sanitize_text_field( $_POST['tracking_number'] ) );
    }
    
    if ( isset( $_POST['tracking_provider'] ) ) {
        update_post_meta( $order_id, '_tracking_provider', sanitize_text_field( $_POST['tracking_provider'] ) );
    }
    
    if ( isset( $_POST['shipped_date'] ) ) {
        update_post_meta( $order_id, '_shipped_date', sanitize_text_field( $_POST['shipped_date'] ) );
    }
    
    // If tracking info was added and order is processing, add a note
    $order = wc_get_order( $order_id );
    if ( $order && 'processing' === $order->get_status() && ! empty( $_POST['tracking_number'] ) && ! empty( $_POST['tracking_provider'] ) ) {
        $provider_name = ucwords( $_POST['tracking_provider'] );
        $tracking_number = $_POST['tracking_number'];
        $note = sprintf( 
            __( 'Order shipped via %1$s with tracking number: %2$s', 'aqualuxe' ),
            $provider_name,
            $tracking_number
        );
        $order->add_order_note( $note, true ); // Add the note and notify the customer
    }
}
add_action( 'woocommerce_process_shop_order_meta', 'aqualuxe_save_tracking_data' );

/**
 * Add tracking information to order emails
 */
function aqualuxe_add_tracking_info_to_emails( $order, $sent_to_admin, $plain_text, $email ) {
    // Only add to certain email types
    if ( ! in_array( $email->id, array( 'customer_completed_order', 'customer_processing_order' ) ) ) {
        return;
    }
    
    $order_id = $order->get_id();
    $tracking_number = get_post_meta( $order_id, '_tracking_number', true );
    $tracking_provider = get_post_meta( $order_id, '_tracking_provider', true );
    
    if ( ! $tracking_number || ! $tracking_provider ) {
        return;
    }
    
    $tracking_url = aqualuxe_get_tracking_url( $tracking_provider, $tracking_number );
    $provider_name = ucwords( $tracking_provider );
    
    if ( $plain_text ) {
        echo "\n\n" . esc_html__( 'Tracking Information:', 'aqualuxe' ) . "\n";
        echo esc_html__( 'Provider:', 'aqualuxe' ) . ' ' . esc_html( $provider_name ) . "\n";
        echo esc_html__( 'Tracking Number:', 'aqualuxe' ) . ' ' . esc_html( $tracking_number ) . "\n";
        echo esc_html__( 'Track your order:', 'aqualuxe' ) . ' ' . esc_url( $tracking_url ) . "\n";
    } else {
        echo '<h2>' . esc_html__( 'Tracking Information', 'aqualuxe' ) . '</h2>';
        echo '<p><strong>' . esc_html__( 'Provider:', 'aqualuxe' ) . '</strong> ' . esc_html( $provider_name ) . '</p>';
        echo '<p><strong>' . esc_html__( 'Tracking Number:', 'aqualuxe' ) . '</strong> ' . esc_html( $tracking_number ) . '</p>';
        echo '<p><a href="' . esc_url( $tracking_url ) . '" target="_blank" class="button">' . esc_html__( 'Track your order', 'aqualuxe' ) . '</a></p>';
    }
}
add_action( 'woocommerce_email_after_order_table', 'aqualuxe_add_tracking_info_to_emails', 10, 4 );

/**
 * Add tracking information to the order received page
 */
function aqualuxe_add_tracking_info_to_order_received( $order ) {
    $order_id = $order->get_id();
    $tracking_number = get_post_meta( $order_id, '_tracking_number', true );
    $tracking_provider = get_post_meta( $order_id, '_tracking_provider', true );
    
    if ( ! $tracking_number || ! $tracking_provider ) {
        return;
    }
    
    $tracking_url = aqualuxe_get_tracking_url( $tracking_provider, $tracking_number );
    $provider_name = ucwords( $tracking_provider );
    
    echo '<section class="woocommerce-order-tracking">';
    echo '<h2>' . esc_html__( 'Tracking Information', 'aqualuxe' ) . '</h2>';
    echo '<p><strong>' . esc_html__( 'Provider:', 'aqualuxe' ) . '</strong> ' . esc_html( $provider_name ) . '</p>';
    echo '<p><strong>' . esc_html__( 'Tracking Number:', 'aqualuxe' ) . '</strong> ' . esc_html( $tracking_number ) . '</p>';
    echo '<p><a href="' . esc_url( $tracking_url ) . '" target="_blank" class="button">' . esc_html__( 'Track your order', 'aqualuxe' ) . '</a></p>';
    echo '</section>';
}
add_action( 'woocommerce_order_details_after_order_table', 'aqualuxe_add_tracking_info_to_order_received' );

/**
 * Create a dedicated order tracking page template
 */
function aqualuxe_order_tracking_template( $template ) {
    if ( is_page( 'order-tracking' ) ) {
        $new_template = locate_template( array( 'templates/order-tracking.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'aqualuxe_order_tracking_template' );