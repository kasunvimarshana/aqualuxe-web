<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with WooCommerce auction plugins.
 *
 * Detects if a supported plugin (e.g., WooCommerce Simple Auctions) is active
 * and provides a unified API for auction-related data.
 */
class AuctionService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (class_exists('WooCommerce_Simple_Auctions')) {
            $this->active_plugin = 'simple-auctions';
        }
        // Add checks for other auction plugins like YITH here.
    }

    /**
     * Check if a supported auction plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if a given product is an auction product.
     *
     * @param int|\WC_Product $product The product ID or product object.
     * @return bool
     */
    public function is_auction($product): bool
    {
        if (!$this->is_active()) {
            return false;
        }

        $product = is_numeric($product) ? \wc_get_product($product) : $product;
        if (!$product) {
            return false;
        }

        if ($this->active_plugin === 'simple-auctions') {
            return $product->is_type('auction');
        }

        return false;
    }

    /**
     * Get auction details for a product.
     *
     * @param \WC_Product $product
     * @return array|null A standardized array of auction details.
     */
    public function get_auction_details($product): ?array
    {
        if (!$this->is_auction($product) || !method_exists($product, 'get_auction_end_time')) {
            return null;
        }

        if ($this->active_plugin === 'simple-auctions') {
            return [
                'end_date' => $product->get_auction_end_time(),
                'is_sealed' => $product->is_sealed(),
                'current_bid' => $product->get_curent_bid(),
                'bid_count' => $product->get_auction_bid_count(),
                'is_running' => $product->is_started() && !$product->is_closed(),
            ];
        }

        return null;
    }
}
