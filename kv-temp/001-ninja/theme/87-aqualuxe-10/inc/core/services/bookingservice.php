<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with WooCommerce Bookings plugins.
 *
 * Detects if a supported plugin (e.g., WooCommerce Bookings) is active
 * and provides a unified API for booking-related data.
 */
class BookingService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (class_exists('WC_Bookings')) {
            $this->active_plugin = 'woocommerce-bookings';
        }
        // Add checks for other booking plugins here.
    }

    /**
     * Check if a supported booking plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if a given product is a bookable product.
     *
     * @param int|\WC_Product $product The product ID or product object.
     * @return bool
     */
    public function is_booking($product): bool
    {
        if (!$this->is_active()) {
            return false;
        }

        $product = is_numeric($product) ? \wc_get_product($product) : $product;
        if (!$product || !is_a($product, 'WC_Product')) {
            return false;
        }

        if ($this->active_plugin === 'woocommerce-bookings') {
            return $product->is_type('booking');
        }

        return false;
    }

    /**
     * Get booking details for a product.
     *
     * @param \WC_Product $product
     * @return array|null A standardized array of booking details.
     */
    public function get_booking_details($product): ?array
    {
        if (!$this->is_booking($product) || !is_a($product, 'WC_Product_Booking')) {
            return null;
        }

        if ($this->active_plugin === 'woocommerce-bookings') {
            return [
                'duration_type' => $product->get_duration_type(),
                'duration' => $product->get_duration(),
                'duration_unit' => $product->get_duration_unit(),
                'min_duration' => $product->get_min_duration(),
                'max_duration' => $product->get_max_duration(),
                'requires_confirmation' => $product->requires_confirmation(),
                'has_persons' => $product->has_persons(),
            ];
        }

        return null;
    }
}
