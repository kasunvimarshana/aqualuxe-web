<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with multi-vendor marketplace plugins.
 *
 * Detects active plugins (Dokan, WCFM Marketplace) and provides a unified API
 * for retrieving vendor information, store URLs, and other vendor-related data.
 */
class VendorService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (function_exists('dokan')) {
            $this->active_plugin = 'dokan';
        } elseif (class_exists('WCFMmp')) {
            $this->active_plugin = 'wcfm';
        }
    }

    /**
     * Check if a supported multi-vendor plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Get the vendor for a given product ID.
     *
     * @param int $product_id The ID of the WooCommerce product.
     * @return \stdClass|null A standardized vendor object or null if not found.
     */
    public function get_vendor_by_product(int $product_id): ?\stdClass
    {
        if (!$this->is_active()) {
            return null;
        }

        $vendor_id = \get_post_field('post_author', $product_id);
        if (!$vendor_id) {
            return null;
        }

        return $this->get_vendor_by_id($vendor_id);
    }

    /**
     * Get vendor details by vendor (user) ID.
     *
     * @param int $vendor_id The user ID of the vendor.
     * @return \stdClass|null A standardized vendor object.
     */
    public function get_vendor_by_id(int $vendor_id): ?\stdClass
    {
        if (!$this->is_active()) {
            return null;
        }

        switch ($this->active_plugin) {
            case 'dokan':
                return $this->get_dokan_vendor($vendor_id);
            case 'wcfm':
                return $this->get_wcfm_vendor($vendor_id);
            default:
                return null;
        }
    }

    /**
     * Get the URL for the vendor's store page.
     *
     * @param int $vendor_id The user ID of the vendor.
     * @return string|null The store URL or null.
     */
    public function get_vendor_store_url(int $vendor_id): ?string
    {
        if (!$this->is_active()) {
            return null;
        }

        switch ($this->active_plugin) {
            case 'dokan':
                return \dokan_get_store_url($vendor_id);
            case 'wcfm':
                return \wcfmmp_get_store_url($vendor_id);
            default:
                return null;
        }
    }

    private function get_dokan_vendor(int $vendor_id): ?\stdClass
    {
        if (!function_exists('dokan_get_store_info')) {
            return null;
        }

        $store_info = \dokan_get_store_info($vendor_id);
        if (!$store_info) {
            return null;
        }

        $vendor = new \stdClass();
        $vendor->id = $vendor_id;
        $vendor->name = $store_info['store_name'];
        $vendor->url = $this->get_vendor_store_url($vendor_id);
        $vendor->logo = \wp_get_attachment_url($store_info['gravatar']);

        return $vendor;
    }

    private function get_wcfm_vendor(int $vendor_id): ?\stdClass
    {
        if (!function_exists('wcfm_get_vendor_store_by_vendor')) {
            return null;
        }
        
        $store_user = \wcfm_get_vendor_store_by_vendor($vendor_id);
        if (!$store_user) {
            return null;
        }

        $vendor = new \stdClass();
        $vendor->id = $vendor_id;
        $vendor->name = \get_user_meta($vendor_id, 'wcfm_store_name', true);
        $vendor->url = $this->get_vendor_store_url($vendor_id);
        $vendor->logo = \wcfm_get_vendor_logo_by_vendor($vendor_id);

        return $vendor;
    }
}
