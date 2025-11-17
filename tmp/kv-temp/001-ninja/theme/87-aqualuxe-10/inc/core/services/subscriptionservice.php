<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with WooCommerce Subscriptions.
 *
 * Detects if the plugin is active and provides a unified API for retrieving
 * subscription-related information for a product.
 */
class SubscriptionService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (class_exists('WC_Subscriptions')) {
            $this->active_plugin = 'woocommerce-subscriptions';
        }
        // Add checks for other subscription plugins here if needed
    }

    /**
     * Check if a supported subscription plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if a given product is a subscription product.
     *
     * @param int|\WC_Product $product The product ID or product object.
     * @return bool
     */
    public function is_subscription($product): bool
    {
        if (!$this->is_active()) {
            return false;
        }

        if ($this->active_plugin === 'woocommerce-subscriptions' && \function_exists('wcs_is_subscription')) {
            return \wcs_is_subscription($product);
        }

        return false;
    }

    /**
     * Get the subscription details for a product.
     *
     * @param \WC_Product_Subscription|\WC_Product_Variable_Subscription $product
     * @return array|null A standardized array of subscription details.
     */
    public function get_subscription_details($product): ?array
    {
        if (!$this->is_subscription($product)) {
            return null;
        }

        if ($this->active_plugin === 'woocommerce-subscriptions') {
            $details = [
                'price_string' => $product->get_price_html(),
                'is_variable' => $product->is_type('variable-subscription'),
                'variations' => [],
            ];

            if ($details['is_variable']) {
                // For variable subscriptions, we can extract variation data
                $variations = $product->get_available_variations();
                foreach ($variations as $variation) {
                    $variation_obj = \wc_get_product($variation['variation_id']);
                    if ($variation_obj && is_a($variation_obj, 'WC_Product')) {
                        $details['variations'][] = [
                            'id' => $variation['variation_id'],
                            'attributes' => $variation['attributes'],
                            'price_string' => $variation_obj->get_price_html(),
                        ];
                    }
                }
            }
            return $details;
        }

        return null;
    }
}
