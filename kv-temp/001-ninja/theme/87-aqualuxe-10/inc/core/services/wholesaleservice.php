<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with WooCommerce wholesale/B2B plugins.
 *
 * Detects active plugins (e.g., WooCommerce Wholesale Prices, B2BKing) and
 * provides a unified API for checking user roles and getting wholesale prices.
 */
class WholesaleService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (class_exists('WooCommerce_Wholesale_Prices')) {
            $this->active_plugin = 'wholesale-prices';
        } elseif (class_exists('B2BKingcore')) {
            $this->active_plugin = 'b2bking';
        }
    }

    /**
     * Check if a supported wholesale plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if the current user is a wholesale customer.
     *
     * @param int|null $user_id The user ID to check. Defaults to the current user.
     * @return bool
     */
    public function is_wholesale_customer(?int $user_id = null): bool
    {
        if (!$this->is_active()) {
            return false;
        }

        $user_id = $user_id ?? \get_current_user_id();
        if (!$user_id) {
            return false;
        }

        switch ($this->active_plugin) {
            case 'wholesale-prices':
                $user = \wp_get_current_user();
                // This plugin uses a specific role, e.g., 'wholesale_customer'
                $wholesale_role = apply_filters('wwp_wholesale_role', 'wholesale_customer');
                return in_array($wholesale_role, (array) $user->roles);

            case 'b2bking':
                // B2BKing uses a more complex system of groups
                $user_group = \get_user_meta($user_id, 'b2bking_customergroup', true);
                // 'b2c' is the default non-business group. Any other group is considered B2B.
                return $user_group !== 'b2c' && !empty($user_group);
        }

        return false;
    }

    /**
     * Get the wholesale price HTML for a product, if applicable.
     * The plugins usually handle this automatically via filters, but this
     * can be used for explicit checks.
     *
     * @param \WC_Product $product
     * @return string|null The price HTML or null.
     */
    public function get_wholesale_price_html(\WC_Product $product): ?string
    {
        if (!$this->is_wholesale_customer()) {
            return null;
        }

        // The plugins typically filter 'woocommerce_get_price_html' directly.
        // This function would be for cases where you need to get the price outside
        // of the normal display hooks. For now, we rely on the plugin's filters.
        return $product->get_price_html();
    }
}
