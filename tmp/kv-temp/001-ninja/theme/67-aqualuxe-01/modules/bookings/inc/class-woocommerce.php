<?php
/**
 * WooCommerce Integration Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * WooCommerce Integration Class
 * 
 * This class handles WooCommerce integration.
 */
class WooCommerce {
    /**
     * Instance of this class
     *
     * @var WooCommerce
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return WooCommerce
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add booking data to cart item
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_booking_data_to_cart_item' ], 10, 3 );
        
        // Display booking data in cart
        add_filter( 'woocommerce_get_item_data', [ $this, 'display_booking_data_in_cart' ], 10, 2 );
        
        // Add booking data to order item
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'add_booking_data_to_order_item' ], 10, 4 );
        
        // Create booking after order is processed
        add_action( 'woocommerce_order_status_processing', [ $this, 'create_booking_from_order' ] );
        add_action( 'woocommerce_order_status_completed', [ $this, 'create_booking_from_order' ] );
        
        // Update booking status when order status changes
        add_action( 'woocommerce_order_status_changed', [ $this, 'update_booking_status' ], 10, 4 );
        
        // Add booking details to order emails
        add_action( 'woocommerce_email_order_details', [ $this, 'add_booking_details_to_email' ], 10, 4 );
        
        // Add booking details to order admin
        add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'add_booking_details_to_order_admin' ], 10, 1 );
        
        // Add booking details to order page
        add_action( 'woocommerce_order_details_after_order_table', [ $this, 'add_booking_details_to_order_page' ], 10, 1 );
        
        // Add booking form to product page
        add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_booking_form_to_product' ] );
        
        // Validate booking data before adding to cart
        add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'validate_booking_data' ], 10, 5 );
        
        // Add custom product type
        add_filter( 'product_type_selector', [ $this, 'add_bookable_product_type' ] );
        
        // Add custom product data tabs
        add_filter( 'woocommerce_product_data_tabs', [ $this, 'add_bookable_product_data_tab' ] );
        
        // Add custom product data fields
        add_action( 'woocommerce_product_data_panels', [ $this, 'add_bookable_product_data_fields' ] );
        
        // Save custom product data
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_bookable_product_data' ] );
    }

    /**
     * Add booking data to cart item
     *
     * @param array $cart_item_data Cart item data.
     * @param int   $product_id Product ID.
     * @param int   $variation_id Variation ID.
     * @return array
     */
    public function add_booking_data_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
        // Check if booking data is provided
        if ( isset( $_POST['booking_date'] ) && isset( $_POST['booking_time'] ) ) {
            // Get booking data
            $booking_date = sanitize_text_field( $_POST['booking_date'] );
            $booking_time = sanitize_text_field( $_POST['booking_time'] );
            
            // Get service ID
            $service_id = get_post_meta( $product_id, '_aqualuxe_service_id', true );
            
            // If no service ID is found, use product ID
            if ( ! $service_id ) {
                $service_id = $product_id;
            }
            
            // Add booking data to cart item
            $cart_item_data['booking_data'] = [
                'service_id' => $service_id,
                'date'       => $booking_date,
                'time'       => $booking_time,
            ];
            
            // Add customer data if provided
            if ( isset( $_POST['booking_customer_name'] ) ) {
                $cart_item_data['booking_data']['customer_name'] = sanitize_text_field( $_POST['booking_customer_name'] );
            }
            
            if ( isset( $_POST['booking_customer_email'] ) ) {
                $cart_item_data['booking_data']['customer_email'] = sanitize_email( $_POST['booking_customer_email'] );
            }
            
            if ( isset( $_POST['booking_customer_phone'] ) ) {
                $cart_item_data['booking_data']['customer_phone'] = sanitize_text_field( $_POST['booking_customer_phone'] );
            }
            
            if ( isset( $_POST['booking_customer_notes'] ) ) {
                $cart_item_data['booking_data']['customer_notes'] = sanitize_textarea_field( $_POST['booking_customer_notes'] );
            }
        }
        
        return $cart_item_data;
    }

    /**
     * Display booking data in cart
     *
     * @param array $item_data Item data.
     * @param array $cart_item Cart item.
     * @return array
     */
    public function display_booking_data_in_cart( $item_data, $cart_item ) {
        // Check if booking data exists
        if ( isset( $cart_item['booking_data'] ) ) {
            // Get booking data
            $booking_data = $cart_item['booking_data'];
            
            // Add date to item data
            if ( isset( $booking_data['date'] ) ) {
                $item_data[] = [
                    'key'   => __( 'Date', 'aqualuxe' ),
                    'value' => $this->format_date( $booking_data['date'] ),
                ];
            }
            
            // Add time to item data
            if ( isset( $booking_data['time'] ) ) {
                $item_data[] = [
                    'key'   => __( 'Time', 'aqualuxe' ),
                    'value' => $this->format_time( $booking_data['time'] ),
                ];
            }
        }
        
        return $item_data;
    }

    /**
     * Add booking data to order item
     *
     * @param WC_Order_Item_Product $item Order item.
     * @param string                $cart_item_key Cart item key.
     * @param array                 $values Cart item values.
     * @param WC_Order              $order Order object.
     * @return void
     */
    public function add_booking_data_to_order_item( $item, $cart_item_key, $values, $order ) {
        // Check if booking data exists
        if ( isset( $values['booking_data'] ) ) {
            // Get booking data
            $booking_data = $values['booking_data'];
            
            // Add booking data to order item
            foreach ( $booking_data as $key => $value ) {
                $item->add_meta_data( '_booking_' . $key, $value );
            }
        }
    }

    /**
     * Create booking from order
     *
     * @param int $order_id Order ID.
     * @return void
     */
    public function create_booking_from_order( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        // Check if order exists
        if ( ! $order ) {
            return;
        }
        
        // Check if booking has already been created
        $booking_id = get_post_meta( $order_id, '_booking_id', true );
        
        if ( $booking_id ) {
            return;
        }
        
        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {
            // Get booking data
            $service_id = $item->get_meta( '_booking_service_id' );
            $date = $item->get_meta( '_booking_date' );
            $time = $item->get_meta( '_booking_time' );
            
            // Check if booking data exists
            if ( $service_id && $date && $time ) {
                // Get customer data
                $customer_name = $item->get_meta( '_booking_customer_name' );
                $customer_email = $item->get_meta( '_booking_customer_email' );
                $customer_phone = $item->get_meta( '_booking_customer_phone' );
                $customer_notes = $item->get_meta( '_booking_customer_notes' );
                
                // If customer data is not provided, use order data
                if ( ! $customer_name ) {
                    $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                }
                
                if ( ! $customer_email ) {
                    $customer_email = $order->get_billing_email();
                }
                
                if ( ! $customer_phone ) {
                    $customer_phone = $order->get_billing_phone();
                }
                
                // Create booking data
                $booking_data = [
                    'service_id'      => $service_id,
                    'date'            => $date,
                    'time'            => $time,
                    'customer_name'   => $customer_name,
                    'customer_email'  => $customer_email,
                    'customer_phone'  => $customer_phone,
                    'customer_notes'  => $customer_notes,
                    'customer_address' => $order->get_billing_address_1(),
                    'customer_city'   => $order->get_billing_city(),
                    'customer_state'  => $order->get_billing_state(),
                    'customer_zip'    => $order->get_billing_postcode(),
                    'customer_country' => $order->get_billing_country(),
                    'status'          => 'confirmed',
                    'booking_notes'   => sprintf( __( 'Booking created from order #%s', 'aqualuxe' ), $order_id ),
                ];
                
                // Create booking
                $booking = new Booking();
                $booking_id = $booking->create( $booking_data );
                
                // Check if booking was created successfully
                if ( ! is_wp_error( $booking_id ) ) {
                    // Add booking ID to order
                    update_post_meta( $order_id, '_booking_id', $booking_id );
                    
                    // Add order ID to booking
                    update_post_meta( $booking_id, '_order_id', $order_id );
                    
                    // Add item ID to booking
                    update_post_meta( $booking_id, '_order_item_id', $item_id );
                    
                    // Add booking ID to item
                    wc_add_order_item_meta( $item_id, '_booking_id', $booking_id );
                    
                    // Trigger action
                    do_action( 'aqualuxe_booking_created_from_order', $booking_id, $order_id );
                }
            }
        }
    }

    /**
     * Update booking status
     *
     * @param int    $order_id Order ID.
     * @param string $old_status Old status.
     * @param string $new_status New status.
     * @param object $order Order object.
     * @return void
     */
    public function update_booking_status( $order_id, $old_status, $new_status, $order ) {
        // Get booking ID
        $booking_id = get_post_meta( $order_id, '_booking_id', true );
        
        // Check if booking exists
        if ( ! $booking_id ) {
            return;
        }
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return;
        }
        
        // Update booking status based on order status
        switch ( $new_status ) {
            case 'processing':
            case 'completed':
                $booking->update( [ 'status' => 'confirmed' ] );
                break;
                
            case 'cancelled':
                $booking->update( [ 'status' => 'cancelled' ] );
                break;
                
            case 'refunded':
                $booking->update( [ 'status' => 'cancelled' ] );
                break;
                
            case 'failed':
                $booking->update( [ 'status' => 'cancelled' ] );
                break;
        }
    }

    /**
     * Add booking details to email
     *
     * @param WC_Order $order Order object.
     * @param bool     $sent_to_admin Whether the email is sent to admin.
     * @param bool     $plain_text Whether the email is plain text.
     * @param WC_Email $email Email object.
     * @return void
     */
    public function add_booking_details_to_email( $order, $sent_to_admin, $plain_text, $email ) {
        // Get booking ID
        $booking_id = get_post_meta( $order->get_id(), '_booking_id', true );
        
        // Check if booking exists
        if ( ! $booking_id ) {
            return;
        }
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return;
        }
        
        // Get service
        $service = new Service( $booking->get_service_id() );
        
        // Check if service exists
        if ( ! $service->get_id() ) {
            return;
        }
        
        // Output booking details
        if ( $plain_text ) {
            // Plain text email
            echo "\n\n" . esc_html__( 'Booking Details', 'aqualuxe' ) . "\n";
            echo esc_html__( 'Service:', 'aqualuxe' ) . ' ' . esc_html( $service->get_title() ) . "\n";
            echo esc_html__( 'Date:', 'aqualuxe' ) . ' ' . esc_html( $booking->get_formatted_date() ) . "\n";
            echo esc_html__( 'Time:', 'aqualuxe' ) . ' ' . esc_html( $booking->get_formatted_time() ) . "\n";
            echo esc_html__( 'Duration:', 'aqualuxe' ) . ' ' . esc_html( $booking->get_formatted_duration() ) . "\n";
            echo esc_html__( 'Status:', 'aqualuxe' ) . ' ' . esc_html( $booking->get_formatted_status() ) . "\n";
            
            // Add location if available
            if ( $service->get_location() ) {
                echo esc_html__( 'Location:', 'aqualuxe' ) . ' ' . esc_html( $service->get_location() ) . "\n";
            }
        } else {
            // HTML email
            ?>
            <h2><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h2>
            <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Service', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $booking->get_formatted_status() ); ?></td>
                    </tr>
                    <?php if ( $service->get_location() ) : ?>
                    <tr>
                        <th style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></th>
                        <td style="text-align: left; border-bottom: 1px solid #eee; padding: 12px;"><?php echo esc_html( $service->get_location() ); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php
        }
    }

    /**
     * Add booking details to order admin
     *
     * @param WC_Order $order Order object.
     * @return void
     */
    public function add_booking_details_to_order_admin( $order ) {
        // Get booking ID
        $booking_id = get_post_meta( $order->get_id(), '_booking_id', true );
        
        // Check if booking exists
        if ( ! $booking_id ) {
            return;
        }
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return;
        }
        
        // Get service
        $service = new Service( $booking->get_service_id() );
        
        // Check if service exists
        if ( ! $service->get_id() ) {
            return;
        }
        
        // Output booking details
        ?>
        <div class="order_data_column">
            <h3><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h3>
            <p>
                <strong><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $service->get_title() ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $booking->get_formatted_date() ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'Time:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $booking->get_formatted_time() ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'Duration:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $booking->get_formatted_duration() ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'Status:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $booking->get_formatted_status() ); ?>
            </p>
            <?php if ( $service->get_location() ) : ?>
            <p>
                <strong><?php esc_html_e( 'Location:', 'aqualuxe' ); ?></strong>
                <?php echo esc_html( $service->get_location() ); ?>
            </p>
            <?php endif; ?>
            <p>
                <a href="<?php echo esc_url( get_edit_post_link( $booking_id ) ); ?>" class="button"><?php esc_html_e( 'View Booking', 'aqualuxe' ); ?></a>
            </p>
        </div>
        <?php
    }

    /**
     * Add booking details to order page
     *
     * @param WC_Order $order Order object.
     * @return void
     */
    public function add_booking_details_to_order_page( $order ) {
        // Get booking ID
        $booking_id = get_post_meta( $order->get_id(), '_booking_id', true );
        
        // Check if booking exists
        if ( ! $booking_id ) {
            return;
        }
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return;
        }
        
        // Get service
        $service = new Service( $booking->get_service_id() );
        
        // Check if service exists
        if ( ! $service->get_id() ) {
            return;
        }
        
        // Output booking details
        ?>
        <h2><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h2>
        <table class="woocommerce-table woocommerce-table--booking-details shop_table booking_details">
            <tbody>
                <tr>
                    <th><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $service->get_title() ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Time:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Duration:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Status:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $booking->get_formatted_status() ); ?></td>
                </tr>
                <?php if ( $service->get_location() ) : ?>
                <tr>
                    <th><?php esc_html_e( 'Location:', 'aqualuxe' ); ?></th>
                    <td><?php echo esc_html( $service->get_location() ); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Add booking form to product
     *
     * @return void
     */
    public function add_booking_form_to_product() {
        global $product;
        
        // Check if product exists
        if ( ! $product ) {
            return;
        }
        
        // Get service ID
        $service_id = get_post_meta( $product->get_id(), '_aqualuxe_service_id', true );
        
        // If no service ID is found, check if product is bookable
        if ( ! $service_id ) {
            $is_bookable = get_post_meta( $product->get_id(), '_aqualuxe_bookable', true );
            
            if ( $is_bookable ) {
                $service_id = $product->get_id();
            } else {
                return;
            }
        }
        
        // Get service
        $service = new Service( $service_id );
        
        // Check if service exists
        if ( ! $service->get_id() ) {
            return;
        }
        
        // Get next available date
        $next_date = $service->get_next_available_date();
        
        // Check if service is available
        if ( ! $next_date ) {
            echo '<p class="booking-unavailable">' . esc_html__( 'This service is currently unavailable for booking.', 'aqualuxe' ) . '</p>';
            return;
        }
        
        // Get available time slots
        $availability = new Availability( $service_id, $next_date );
        $available_slots = $availability->get_available_time_slots();
        
        // Check if time slots are available
        if ( empty( $available_slots ) ) {
            echo '<p class="booking-unavailable">' . esc_html__( 'No available time slots for this service.', 'aqualuxe' ) . '</p>';
            return;
        }
        
        // Output booking form
        ?>
        <div class="booking-form">
            <h3><?php esc_html_e( 'Book This Service', 'aqualuxe' ); ?></h3>
            
            <div class="booking-form-fields">
                <div class="booking-date-field">
                    <label for="booking_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
                    <input type="date" name="booking_date" id="booking_date" value="<?php echo esc_attr( $next_date ); ?>" required>
                </div>
                
                <div class="booking-time-field">
                    <label for="booking_time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
                    <select name="booking_time" id="booking_time" required>
                        <option value=""><?php esc_html_e( 'Select a time', 'aqualuxe' ); ?></option>
                        <?php foreach ( $available_slots as $slot ) : ?>
                            <option value="<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $this->format_time( $slot ) ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <?php if ( ! is_user_logged_in() ) : ?>
                <div class="booking-customer-fields">
                    <div class="booking-customer-name-field">
                        <label for="booking_customer_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
                        <input type="text" name="booking_customer_name" id="booking_customer_name" required>
                    </div>
                    
                    <div class="booking-customer-email-field">
                        <label for="booking_customer_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                        <input type="email" name="booking_customer_email" id="booking_customer_email" required>
                    </div>
                    
                    <div class="booking-customer-phone-field">
                        <label for="booking_customer_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                        <input type="tel" name="booking_customer_phone" id="booking_customer_phone" required>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="booking-customer-notes-field">
                    <label for="booking_customer_notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                    <textarea name="booking_customer_notes" id="booking_customer_notes" rows="3"></textarea>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Validate booking data
     *
     * @param bool  $passed Whether validation passed.
     * @param int   $product_id Product ID.
     * @param int   $quantity Quantity.
     * @param int   $variation_id Variation ID.
     * @param array $variations Variations.
     * @return bool
     */
    public function validate_booking_data( $passed, $product_id, $quantity, $variation_id = 0, $variations = [] ) {
        // Check if booking data is provided
        if ( isset( $_POST['booking_date'] ) && isset( $_POST['booking_time'] ) ) {
            // Get booking data
            $booking_date = sanitize_text_field( $_POST['booking_date'] );
            $booking_time = sanitize_text_field( $_POST['booking_time'] );
            
            // Get service ID
            $service_id = get_post_meta( $product_id, '_aqualuxe_service_id', true );
            
            // If no service ID is found, use product ID
            if ( ! $service_id ) {
                $service_id = $product_id;
            }
            
            // Get service
            $service = new Service( $service_id );
            
            // Check if service exists
            if ( ! $service->get_id() ) {
                wc_add_notice( __( 'Invalid service.', 'aqualuxe' ), 'error' );
                return false;
            }
            
            // Check if date is valid
            if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $booking_date ) ) {
                wc_add_notice( __( 'Invalid date format.', 'aqualuxe' ), 'error' );
                return false;
            }
            
            // Check if time is valid
            if ( ! preg_match( '/^\d{2}:\d{2}$/', $booking_time ) ) {
                wc_add_notice( __( 'Invalid time format.', 'aqualuxe' ), 'error' );
                return false;
            }
            
            // Check if service is available on the selected date
            $availability = new Availability( $service_id, $booking_date );
            
            if ( ! $availability->is_available() ) {
                wc_add_notice( __( 'Service is not available on the selected date.', 'aqualuxe' ), 'error' );
                return false;
            }
            
            // Check if time slot is available
            if ( ! $availability->is_time_slot_available( $booking_time ) ) {
                wc_add_notice( __( 'Selected time slot is not available.', 'aqualuxe' ), 'error' );
                return false;
            }
            
            // Check if customer data is provided
            if ( ! is_user_logged_in() ) {
                if ( ! isset( $_POST['booking_customer_name'] ) || empty( $_POST['booking_customer_name'] ) ) {
                    wc_add_notice( __( 'Please enter your name.', 'aqualuxe' ), 'error' );
                    return false;
                }
                
                if ( ! isset( $_POST['booking_customer_email'] ) || empty( $_POST['booking_customer_email'] ) ) {
                    wc_add_notice( __( 'Please enter your email.', 'aqualuxe' ), 'error' );
                    return false;
                }
                
                if ( ! is_email( $_POST['booking_customer_email'] ) ) {
                    wc_add_notice( __( 'Please enter a valid email.', 'aqualuxe' ), 'error' );
                    return false;
                }
                
                if ( ! isset( $_POST['booking_customer_phone'] ) || empty( $_POST['booking_customer_phone'] ) ) {
                    wc_add_notice( __( 'Please enter your phone number.', 'aqualuxe' ), 'error' );
                    return false;
                }
            }
        }
        
        return $passed;
    }

    /**
     * Add bookable product type
     *
     * @param array $product_types Product types.
     * @return array
     */
    public function add_bookable_product_type( $product_types ) {
        $product_types['bookable'] = __( 'Bookable product', 'aqualuxe' );
        return $product_types;
    }

    /**
     * Add bookable product data tab
     *
     * @param array $tabs Product data tabs.
     * @return array
     */
    public function add_bookable_product_data_tab( $tabs ) {
        $tabs['bookings'] = [
            'label'    => __( 'Bookings', 'aqualuxe' ),
            'target'   => 'bookings_product_data',
            'class'    => [ 'show_if_bookable' ],
            'priority' => 21,
        ];
        
        return $tabs;
    }

    /**
     * Add bookable product data fields
     *
     * @return void
     */
    public function add_bookable_product_data_fields() {
        global $post;
        
        // Get product
        $product = wc_get_product( $post->ID );
        
        // Get product meta
        $is_bookable = get_post_meta( $post->ID, '_aqualuxe_bookable', true );
        $duration = get_post_meta( $post->ID, '_aqualuxe_duration', true );
        $buffer_before = get_post_meta( $post->ID, '_aqualuxe_buffer_before', true );
        $buffer_after = get_post_meta( $post->ID, '_aqualuxe_buffer_after', true );
        $capacity = get_post_meta( $post->ID, '_aqualuxe_capacity', true );
        $location = get_post_meta( $post->ID, '_aqualuxe_location', true );
        
        // Output bookings tab
        ?>
        <div id="bookings_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                // Is bookable
                woocommerce_wp_checkbox(
                    [
                        'id'          => '_aqualuxe_bookable',
                        'label'       => __( 'Bookable', 'aqualuxe' ),
                        'description' => __( 'Check this box if this product is bookable.', 'aqualuxe' ),
                        'value'       => $is_bookable ? 'yes' : 'no',
                    ]
                );
                
                // Duration
                woocommerce_wp_text_input(
                    [
                        'id'          => '_aqualuxe_duration',
                        'label'       => __( 'Duration (minutes)', 'aqualuxe' ),
                        'description' => __( 'The duration of the booking in minutes.', 'aqualuxe' ),
                        'type'        => 'number',
                        'custom_attributes' => [
                            'min'  => '1',
                            'step' => '1',
                        ],
                        'value'       => $duration ? $duration : 60,
                    ]
                );
                
                // Buffer before
                woocommerce_wp_text_input(
                    [
                        'id'          => '_aqualuxe_buffer_before',
                        'label'       => __( 'Buffer Before (minutes)', 'aqualuxe' ),
                        'description' => __( 'Buffer time before the booking in minutes.', 'aqualuxe' ),
                        'type'        => 'number',
                        'custom_attributes' => [
                            'min'  => '0',
                            'step' => '1',
                        ],
                        'value'       => $buffer_before ? $buffer_before : 0,
                    ]
                );
                
                // Buffer after
                woocommerce_wp_text_input(
                    [
                        'id'          => '_aqualuxe_buffer_after',
                        'label'       => __( 'Buffer After (minutes)', 'aqualuxe' ),
                        'description' => __( 'Buffer time after the booking in minutes.', 'aqualuxe' ),
                        'type'        => 'number',
                        'custom_attributes' => [
                            'min'  => '0',
                            'step' => '1',
                        ],
                        'value'       => $buffer_after ? $buffer_after : 0,
                    ]
                );
                
                // Capacity
                woocommerce_wp_text_input(
                    [
                        'id'          => '_aqualuxe_capacity',
                        'label'       => __( 'Capacity', 'aqualuxe' ),
                        'description' => __( 'The maximum number of bookings for the same time slot.', 'aqualuxe' ),
                        'type'        => 'number',
                        'custom_attributes' => [
                            'min'  => '1',
                            'step' => '1',
                        ],
                        'value'       => $capacity ? $capacity : 1,
                    ]
                );
                
                // Location
                woocommerce_wp_text_input(
                    [
                        'id'          => '_aqualuxe_location',
                        'label'       => __( 'Location', 'aqualuxe' ),
                        'description' => __( 'The location where the service will be provided.', 'aqualuxe' ),
                        'value'       => $location,
                    ]
                );
                ?>
            </div>
            
            <div class="options_group">
                <p class="form-field">
                    <label><?php esc_html_e( 'Availability', 'aqualuxe' ); ?></label>
                    <span class="description"><?php esc_html_e( 'Set the availability for this service.', 'aqualuxe' ); ?></span>
                </p>
                
                <?php
                // Get availability
                $availability = get_post_meta( $post->ID, '_aqualuxe_availability', true );
                
                // Set default availability if not set
                if ( ! $availability ) {
                    $availability = [
                        'monday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                        'tuesday'   => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                        'wednesday' => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                        'thursday'  => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                        'friday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                        'saturday'  => [ 'enabled' => false, 'slots' => [] ],
                        'sunday'    => [ 'enabled' => false, 'slots' => [] ],
                    ];
                }
                
                // Days of the week
                $days = [
                    'monday'    => __( 'Monday', 'aqualuxe' ),
                    'tuesday'   => __( 'Tuesday', 'aqualuxe' ),
                    'wednesday' => __( 'Wednesday', 'aqualuxe' ),
                    'thursday'  => __( 'Thursday', 'aqualuxe' ),
                    'friday'    => __( 'Friday', 'aqualuxe' ),
                    'saturday'  => __( 'Saturday', 'aqualuxe' ),
                    'sunday'    => __( 'Sunday', 'aqualuxe' ),
                ];
                
                // Output availability fields
                foreach ( $days as $day => $day_name ) :
                    $enabled = isset( $availability[ $day ]['enabled'] ) ? $availability[ $day ]['enabled'] : false;
                    $slots = isset( $availability[ $day ]['slots'] ) ? $availability[ $day ]['slots'] : [];
                ?>
                <div class="availability-day">
                    <p class="form-field">
                        <label><?php echo esc_html( $day_name ); ?></label>
                        <input type="checkbox" name="_aqualuxe_availability[<?php echo esc_attr( $day ); ?>][enabled]" value="1" <?php checked( $enabled ); ?>>
                        <span class="description"><?php esc_html_e( 'Enable', 'aqualuxe' ); ?></span>
                    </p>
                    
                    <div class="availability-slots" style="<?php echo $enabled ? '' : 'display: none;'; ?>">
                        <?php if ( ! empty( $slots ) ) : ?>
                            <?php foreach ( $slots as $i => $slot ) : ?>
                                <p class="form-field">
                                    <label><?php esc_html_e( 'Time Slot', 'aqualuxe' ); ?></label>
                                    <input type="time" name="_aqualuxe_availability[<?php echo esc_attr( $day ); ?>][slots][<?php echo esc_attr( $i ); ?>][start]" value="<?php echo esc_attr( $slot['start'] ); ?>">
                                    <span class="description"><?php esc_html_e( 'to', 'aqualuxe' ); ?></span>
                                    <input type="time" name="_aqualuxe_availability[<?php echo esc_attr( $day ); ?>][slots][<?php echo esc_attr( $i ); ?>][end]" value="<?php echo esc_attr( $slot['end'] ); ?>">
                                    <button type="button" class="button remove-slot"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
                                </p>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="form-field">
                                <label><?php esc_html_e( 'Time Slot', 'aqualuxe' ); ?></label>
                                <input type="time" name="_aqualuxe_availability[<?php echo esc_attr( $day ); ?>][slots][0][start]" value="09:00">
                                <span class="description"><?php esc_html_e( 'to', 'aqualuxe' ); ?></span>
                                <input type="time" name="_aqualuxe_availability[<?php echo esc_attr( $day ); ?>][slots][0][end]" value="17:00">
                                <button type="button" class="button remove-slot"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
                            </p>
                        <?php endif; ?>
                        
                        <p class="form-field">
                            <button type="button" class="button add-slot" data-day="<?php echo esc_attr( $day ); ?>"><?php esc_html_e( 'Add Slot', 'aqualuxe' ); ?></button>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery(function($) {
                // Show/hide availability slots
                $('input[name^="_aqualuxe_availability"][name$="[enabled]"]').on('change', function() {
                    var $slots = $(this).closest('.availability-day').find('.availability-slots');
                    if ($(this).is(':checked')) {
                        $slots.show();
                    } else {
                        $slots.hide();
                    }
                });
                
                // Add slot
                $('.add-slot').on('click', function() {
                    var day = $(this).data('day');
                    var $slots = $(this).closest('.availability-slots');
                    var index = $slots.find('.form-field').length - 1;
                    
                    var html = '<p class="form-field">' +
                        '<label><?php esc_html_e( 'Time Slot', 'aqualuxe' ); ?></label>' +
                        '<input type="time" name="_aqualuxe_availability[' + day + '][slots][' + index + '][start]" value="09:00">' +
                        '<span class="description"><?php esc_html_e( 'to', 'aqualuxe' ); ?></span>' +
                        '<input type="time" name="_aqualuxe_availability[' + day + '][slots][' + index + '][end]" value="17:00">' +
                        '<button type="button" class="button remove-slot"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
                        '</p>';
                    
                    $(this).closest('.form-field').before(html);
                });
                
                // Remove slot
                $(document).on('click', '.remove-slot', function() {
                    $(this).closest('.form-field').remove();
                });
                
                // Show bookings tab for bookable products
                $('select#product-type').on('change', function() {
                    if ($(this).val() === 'bookable') {
                        $('#_aqualuxe_bookable').prop('checked', true).trigger('change');
                    }
                });
                
                // Show bookings tab when bookable checkbox is checked
                $('#_aqualuxe_bookable').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.bookings_options').show();
                        $('.show_if_bookable').show();
                    } else {
                        $('.bookings_options').hide();
                        $('.show_if_bookable').hide();
                    }
                }).trigger('change');
            });
        </script>
        <?php
    }

    /**
     * Save bookable product data
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_bookable_product_data( $post_id ) {
        // Save bookable flag
        $is_bookable = isset( $_POST['_aqualuxe_bookable'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_aqualuxe_bookable', $is_bookable );
        
        // Save duration
        if ( isset( $_POST['_aqualuxe_duration'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_duration', absint( $_POST['_aqualuxe_duration'] ) );
        }
        
        // Save buffer before
        if ( isset( $_POST['_aqualuxe_buffer_before'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_buffer_before', absint( $_POST['_aqualuxe_buffer_before'] ) );
        }
        
        // Save buffer after
        if ( isset( $_POST['_aqualuxe_buffer_after'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_buffer_after', absint( $_POST['_aqualuxe_buffer_after'] ) );
        }
        
        // Save capacity
        if ( isset( $_POST['_aqualuxe_capacity'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_capacity', absint( $_POST['_aqualuxe_capacity'] ) );
        }
        
        // Save location
        if ( isset( $_POST['_aqualuxe_location'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_location', sanitize_text_field( $_POST['_aqualuxe_location'] ) );
        }
        
        // Save availability
        if ( isset( $_POST['_aqualuxe_availability'] ) ) {
            $availability = [];
            
            foreach ( $_POST['_aqualuxe_availability'] as $day => $day_data ) {
                $availability[ $day ] = [
                    'enabled' => isset( $day_data['enabled'] ) ? true : false,
                    'slots'   => [],
                ];
                
                if ( isset( $day_data['slots'] ) && is_array( $day_data['slots'] ) ) {
                    foreach ( $day_data['slots'] as $slot ) {
                        if ( isset( $slot['start'] ) && isset( $slot['end'] ) ) {
                            $availability[ $day ]['slots'][] = [
                                'start' => sanitize_text_field( $slot['start'] ),
                                'end'   => sanitize_text_field( $slot['end'] ),
                            ];
                        }
                    }
                }
            }
            
            update_post_meta( $post_id, '_aqualuxe_availability', $availability );
        }
        
        // Create service if product is bookable
        if ( $is_bookable === 'yes' ) {
            // Check if service already exists
            $service_id = get_post_meta( $post_id, '_aqualuxe_service_id', true );
            
            if ( ! $service_id ) {
                // Get product
                $product = wc_get_product( $post_id );
                
                // Create service data
                $service_data = [
                    'title'       => $product->get_name(),
                    'description' => $product->get_description(),
                    'duration'    => get_post_meta( $post_id, '_aqualuxe_duration', true ),
                    'buffer_before' => get_post_meta( $post_id, '_aqualuxe_buffer_before', true ),
                    'buffer_after' => get_post_meta( $post_id, '_aqualuxe_buffer_after', true ),
                    'capacity'    => get_post_meta( $post_id, '_aqualuxe_capacity', true ),
                    'location'    => get_post_meta( $post_id, '_aqualuxe_location', true ),
                    'availability' => get_post_meta( $post_id, '_aqualuxe_availability', true ),
                    'price'       => $product->get_regular_price(),
                    'sale_price'  => $product->get_sale_price(),
                ];
                
                // Create service
                $service = new Service();
                $service_id = $service->create( $service_data );
                
                // Save service ID to product
                if ( ! is_wp_error( $service_id ) ) {
                    update_post_meta( $post_id, '_aqualuxe_service_id', $service_id );
                    update_post_meta( $service_id, '_wc_product_id', $post_id );
                }
            } else {
                // Update service
                $service = new Service( $service_id );
                
                // Get product
                $product = wc_get_product( $post_id );
                
                // Update service data
                $service_data = [
                    'title'       => $product->get_name(),
                    'description' => $product->get_description(),
                    'duration'    => get_post_meta( $post_id, '_aqualuxe_duration', true ),
                    'buffer_before' => get_post_meta( $post_id, '_aqualuxe_buffer_before', true ),
                    'buffer_after' => get_post_meta( $post_id, '_aqualuxe_buffer_after', true ),
                    'capacity'    => get_post_meta( $post_id, '_aqualuxe_capacity', true ),
                    'location'    => get_post_meta( $post_id, '_aqualuxe_location', true ),
                    'availability' => get_post_meta( $post_id, '_aqualuxe_availability', true ),
                    'price'       => $product->get_regular_price(),
                    'sale_price'  => $product->get_sale_price(),
                ];
                
                // Update service
                $service->update( $service_data );
            }
        }
    }

    /**
     * Format date
     *
     * @param string $date Date in Y-m-d format.
     * @return string
     */
    private function format_date( $date ) {
        // Get date format
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $date_format = isset( $settings['date_format'] ) ? $settings['date_format'] : 'mm/dd/yyyy';
        
        // Format date
        $date_obj = new \DateTime( $date );
        
        switch ( $date_format ) {
            case 'dd/mm/yyyy':
                return $date_obj->format( 'd/m/Y' );
            case 'yyyy-mm-dd':
                return $date_obj->format( 'Y-m-d' );
            case 'mm/dd/yyyy':
            default:
                return $date_obj->format( 'm/d/Y' );
        }
    }

    /**
     * Format time
     *
     * @param string $time Time in HH:MM format.
     * @return string
     */
    private function format_time( $time ) {
        // Get time format
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $time_format = isset( $settings['time_format'] ) ? $settings['time_format'] : '12';
        
        // Format time
        $time_obj = new \DateTime( '2000-01-01 ' . $time );
        
        if ( $time_format === '12' ) {
            return $time_obj->format( 'g:i A' );
        } else {
            return $time_obj->format( 'H:i' );
        }
    }
}