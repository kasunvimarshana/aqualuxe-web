<?php
/**
 * International Shipping Optimization Module Loader
 *
 * @package AquaLuxe\Modules\Shipping
 */
namespace AquaLuxe\Modules\Shipping;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {
    public static function init() {
        add_filter( 'woocommerce_package_rates', [ __CLASS__, 'optimize_shipping_rates' ], 20, 2 );
        add_filter( 'woocommerce_cart_shipping_packages', [ __CLASS__, 'split_packages_by_region' ] );
        add_action( 'woocommerce_after_shipping_rate', [ __CLASS__, 'show_estimated_delivery' ], 10, 2 );
    }

    /**
     * Optimize shipping rates for international customers
     * - Hide irrelevant methods, prioritize cheapest/fastest, etc.
     */
    public static function optimize_shipping_rates( $rates, $package ) {
        $country = $package['destination']['country'] ?? '';
        // Example: Hide local-only methods for international
        foreach ( $rates as $key => $rate ) {
            if ( $country && $country !== 'US' && strpos( $rate->method_id, 'local_pickup' ) !== false ) {
                unset( $rates[$key] );
            }
        }
        // Optionally sort by cost
        uasort( $rates, function($a, $b) {
            return $a->cost <=> $b->cost;
        });
        return $rates;
    }

    /**
     * Split packages by region for better rate calculation (optional)
     */
    public static function split_packages_by_region( $packages ) {
        // Example: Could split by product shipping class, vendor, etc.
        return $packages;
    }

    /**
     * Show estimated delivery time for each shipping method
     */
    public static function show_estimated_delivery( $method, $index ) {
        $estimates = apply_filters( 'aqualuxe_shipping_estimates', [
            'flat_rate' => __( '5-10 business days', 'aqualuxe' ),
            'free_shipping' => __( '7-14 business days', 'aqualuxe' ),
            'local_pickup' => __( 'Same day', 'aqualuxe' ),
            // Add more as needed
        ] );
        if ( isset( $estimates[ $method->method_id ] ) ) {
            echo '<span class="shipping-estimate">' . esc_html( $estimates[ $method->method_id ] ) . '</span>';
        }
    }
}

Loader::init();
