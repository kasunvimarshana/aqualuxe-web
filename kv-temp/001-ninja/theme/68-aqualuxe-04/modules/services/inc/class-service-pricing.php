<?php
/**
 * Service Pricing Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Services\Inc
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services\Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Service Pricing Class
 * 
 * This class handles service pricing functionality.
 */
class Service_Pricing {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // WooCommerce integration
        if ( class_exists( 'WooCommerce' ) ) {
            // Link service prices to WooCommerce products
            add_action( 'save_post_product', [ $this, 'sync_product_price_to_service' ], 10, 3 );
            add_action( 'save_post_aqualuxe_service', [ $this, 'sync_service_price_to_product' ], 10, 3 );
            
            // Add service data to cart item
            add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_service_data_to_cart_item' ], 10, 3 );
            
            // Display service data in cart
            add_filter( 'woocommerce_get_item_data', [ $this, 'display_service_data_in_cart' ], 10, 2 );
            
            // Add service data to order item
            add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'add_service_data_to_order_item' ], 10, 4 );
        }

        // Ajax handlers
        add_action( 'wp_ajax_get_service_price', [ $this, 'ajax_get_service_price' ] );
        add_action( 'wp_ajax_nopriv_get_service_price', [ $this, 'ajax_get_service_price' ] );
    }

    /**
     * Sync product price to service
     *
     * @param int $post_id
     * @param \WP_Post $post
     * @param bool $update
     * @return void
     */
    public function sync_product_price_to_service( $post_id, $post, $update ) {
        // Check if this is a service product
        $is_service = get_post_meta( $post_id, '_is_aqualuxe_service', true );
        if ( 'yes' !== $is_service ) {
            return;
        }

        // Get linked service ID
        $service_id = get_post_meta( $post_id, '_aqualuxe_service_id', true );
        if ( ! $service_id ) {
            return;
        }

        // Get product price
        $product = wc_get_product( $post_id );
        if ( ! $product ) {
            return;
        }

        $price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();

        // Update service price
        update_post_meta( $service_id, '_aqualuxe_service_price', $price );
        update_post_meta( $service_id, '_aqualuxe_service_sale_price', $sale_price );
    }

    /**
     * Sync service price to product
     *
     * @param int $post_id
     * @param \WP_Post $post
     * @param bool $update
     * @return void
     */
    public function sync_service_price_to_product( $post_id, $post, $update ) {
        // Check if WooCommerce integration is enabled
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : true;
        
        if ( ! $woocommerce_integration ) {
            return;
        }

        // Get service price
        $price = get_post_meta( $post_id, '_aqualuxe_service_price', true );
        $sale_price = get_post_meta( $post_id, '_aqualuxe_service_sale_price', true );
        $price_type = get_post_meta( $post_id, '_aqualuxe_service_price_type', true );

        // Skip if price type is quote
        if ( 'quote' === $price_type ) {
            return;
        }

        // Find linked product
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_is_aqualuxe_service',
                    'value' => 'yes',
                ],
                [
                    'key'   => '_aqualuxe_service_id',
                    'value' => $post_id,
                ],
            ],
        ];

        $products = get_posts( $args );

        // If product exists, update price
        if ( ! empty( $products ) ) {
            $product_id = $products[0]->ID;
            $product = wc_get_product( $product_id );
            
            if ( $product ) {
                $product->set_regular_price( $price );
                $product->set_sale_price( $sale_price );
                $product->save();
            }
        }
        // Otherwise, create new product if auto-create is enabled
        elseif ( isset( $settings['auto_create_products'] ) && $settings['auto_create_products'] ) {
            $this->create_product_from_service( $post_id );
        }
    }

    /**
     * Create product from service
     *
     * @param int $service_id
     * @return int
     */
    public function create_product_from_service( $service_id ) {
        // Get service data
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return 0;
        }

        $price = get_post_meta( $service_id, '_aqualuxe_service_price', true );
        $sale_price = get_post_meta( $service_id, '_aqualuxe_service_sale_price', true );
        $price_type = get_post_meta( $service_id, '_aqualuxe_service_price_type', true );

        // Skip if price type is quote
        if ( 'quote' === $price_type ) {
            return 0;
        }

        // Create product
        $product = new \WC_Product();
        $product->set_name( $service->post_title );
        $product->set_description( $service->post_content );
        $product->set_short_description( $service->post_excerpt );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        $product->set_regular_price( $price );
        $product->set_sale_price( $sale_price );
        $product->set_virtual( true );
        $product->set_sold_individually( true );

        // Set product image if service has featured image
        if ( has_post_thumbnail( $service_id ) ) {
            $thumbnail_id = get_post_thumbnail_id( $service_id );
            $product->set_image_id( $thumbnail_id );
        }

        // Save product
        $product_id = $product->save();

        // Set product meta
        update_post_meta( $product_id, '_is_aqualuxe_service', 'yes' );
        update_post_meta( $product_id, '_aqualuxe_service_id', $service_id );

        return $product_id;
    }

    /**
     * Add service data to cart item
     *
     * @param array $cart_item_data
     * @param int $product_id
     * @param int $variation_id
     * @return array
     */
    public function add_service_data_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
        // Check if this is a service product
        $is_service = get_post_meta( $product_id, '_is_aqualuxe_service', true );
        if ( 'yes' !== $is_service ) {
            return $cart_item_data;
        }

        // Get linked service ID
        $service_id = get_post_meta( $product_id, '_aqualuxe_service_id', true );
        if ( ! $service_id ) {
            return $cart_item_data;
        }

        // Get service data
        $service = new Service( $service_id );
        $data = $service->get_data();

        if ( empty( $data ) ) {
            return $cart_item_data;
        }

        // Add service data to cart item
        $cart_item_data['aqualuxe_service'] = [
            'id'       => $service_id,
            'title'    => $data['title'],
            'duration' => $service->get_duration( true ),
            'location' => $service->get_location(),
        ];

        // Check if date and time are provided
        if ( isset( $_POST['aqualuxe_service_date'] ) && isset( $_POST['aqualuxe_service_time'] ) ) {
            $cart_item_data['aqualuxe_service']['date'] = sanitize_text_field( $_POST['aqualuxe_service_date'] );
            $cart_item_data['aqualuxe_service']['time'] = sanitize_text_field( $_POST['aqualuxe_service_time'] );
        }

        return $cart_item_data;
    }

    /**
     * Display service data in cart
     *
     * @param array $item_data
     * @param array $cart_item
     * @return array
     */
    public function display_service_data_in_cart( $item_data, $cart_item ) {
        if ( isset( $cart_item['aqualuxe_service'] ) ) {
            $service = $cart_item['aqualuxe_service'];

            $item_data[] = [
                'key'   => __( 'Service', 'aqualuxe' ),
                'value' => $service['title'],
            ];

            if ( ! empty( $service['duration'] ) ) {
                $item_data[] = [
                    'key'   => __( 'Duration', 'aqualuxe' ),
                    'value' => $service['duration'],
                ];
            }

            if ( ! empty( $service['location'] ) ) {
                $item_data[] = [
                    'key'   => __( 'Location', 'aqualuxe' ),
                    'value' => $service['location'],
                ];
            }

            if ( ! empty( $service['date'] ) && ! empty( $service['time'] ) ) {
                $item_data[] = [
                    'key'   => __( 'Appointment', 'aqualuxe' ),
                    'value' => $service['date'] . ' ' . $service['time'],
                ];
            }
        }

        return $item_data;
    }

    /**
     * Add service data to order item
     *
     * @param \WC_Order_Item_Product $item
     * @param string $cart_item_key
     * @param array $values
     * @param \WC_Order $order
     * @return void
     */
    public function add_service_data_to_order_item( $item, $cart_item_key, $values, $order ) {
        if ( isset( $values['aqualuxe_service'] ) ) {
            $service = $values['aqualuxe_service'];

            $item->add_meta_data( '_aqualuxe_service_id', $service['id'] );
            $item->add_meta_data( __( 'Service', 'aqualuxe' ), $service['title'] );

            if ( ! empty( $service['duration'] ) ) {
                $item->add_meta_data( __( 'Duration', 'aqualuxe' ), $service['duration'] );
            }

            if ( ! empty( $service['location'] ) ) {
                $item->add_meta_data( __( 'Location', 'aqualuxe' ), $service['location'] );
            }

            if ( ! empty( $service['date'] ) && ! empty( $service['time'] ) ) {
                $item->add_meta_data( __( 'Appointment', 'aqualuxe' ), $service['date'] . ' ' . $service['time'] );
                $item->add_meta_data( '_aqualuxe_service_date', $service['date'] );
                $item->add_meta_data( '_aqualuxe_service_time', $service['time'] );
            }
        }
    }

    /**
     * Ajax get service price
     *
     * @return void
     */
    public function ajax_get_service_price() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe-services-nonce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'aqualuxe' ) ] );
        }

        // Check service ID
        if ( ! isset( $_POST['service_id'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid service ID', 'aqualuxe' ) ] );
        }

        $service_id = intval( $_POST['service_id'] );
        $service = new Service( $service_id );
        $data = $service->get_data();

        if ( empty( $data ) ) {
            wp_send_json_error( [ 'message' => __( 'Service not found', 'aqualuxe' ) ] );
        }

        // Get price data
        $price_data = [
            'price'       => $service->get_price(),
            'price_html'  => $service->get_formatted_price_with_type(),
            'price_type'  => $service->get_price_type(),
            'sale_price'  => $service->get_sale_price(),
            'has_sale'    => ! empty( $service->get_sale_price() ),
        ];

        wp_send_json_success( $price_data );
    }

    /**
     * Calculate package price
     *
     * @param array $service_ids
     * @return array
     */
    public static function calculate_package_price( $service_ids ) {
        if ( empty( $service_ids ) ) {
            return [
                'regular_price' => 0,
                'sale_price'    => 0,
                'savings'       => 0,
                'savings_percent' => 0,
            ];
        }

        $total_price = 0;
        $total_sale_price = 0;

        foreach ( $service_ids as $service_id ) {
            $service = new Service( $service_id );
            $price = $service->get_price();
            $sale_price = $service->get_sale_price();

            if ( $price ) {
                $total_price += floatval( $price );
            }

            if ( $sale_price ) {
                $total_sale_price += floatval( $sale_price );
            } elseif ( $price ) {
                $total_sale_price += floatval( $price );
            }
        }

        // Calculate savings
        $savings = $total_price - $total_sale_price;
        $savings_percent = $total_price > 0 ? round( ( $savings / $total_price ) * 100 ) : 0;

        return [
            'regular_price'   => $total_price,
            'sale_price'      => $total_sale_price,
            'savings'         => $savings,
            'savings_percent' => $savings_percent,
        ];
    }

    /**
     * Get formatted price
     *
     * @param float $price
     * @return string
     */
    public static function get_formatted_price( $price ) {
        return '$' . number_format( (float) $price, 2 );
    }

    /**
     * Get price types
     *
     * @return array
     */
    public static function get_price_types() {
        return [
            'fixed'    => __( 'Fixed Price', 'aqualuxe' ),
            'starting' => __( 'Starting From', 'aqualuxe' ),
            'hourly'   => __( 'Per Hour', 'aqualuxe' ),
            'quote'    => __( 'Quote Only', 'aqualuxe' ),
        ];
    }

    /**
     * Get price type label
     *
     * @param string $type
     * @return string
     */
    public static function get_price_type_label( $type ) {
        $types = self::get_price_types();
        return isset( $types[ $type ] ) ? $types[ $type ] : '';
    }
}

// Initialize the class
new Service_Pricing();