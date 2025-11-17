<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\VendorService;

/**
 * Multi-vendor Module.
 *
 * Integrates with Dokan or WCFM to display vendor information on product pages.
 */
class Multivendor implements ModuleInterface
{
    private ?VendorService $vendor_service = null;

    public function boot(): void
    {
        $this->vendor_service = new VendorService();

        if (!$this->vendor_service->is_active()) {
            return;
        }

        // Hook to display vendor info on single product pages
        \add_action('woocommerce_single_product_summary', [$this, 'display_vendor_info'], 11);

        // Hook to add a 'Sold by' label on shop loop items
        \add_action('woocommerce_after_shop_loop_item_title', [$this, 'display_loop_vendor_info']);
    }

    /**
     * Displays vendor information on the single product page.
     */
    public function display_vendor_info(): void
    {
        global $product;
        $vendor = $this->vendor_service->get_vendor_by_product($product->get_id());

        if ($vendor) {
            \get_template_part('modules/multivendor/templates/single-product-vendor-info', null, [
                'vendor' => $vendor
            ]);
        }
    }

    /**
     * Displays "Sold by" information on the shop page product loop.
     */
    public function display_loop_vendor_info(): void
    {
        global $product;
        $vendor = $this->vendor_service->get_vendor_by_product($product->get_id());

        if ($vendor) {
            \get_template_part('modules/multivendor/templates/loop-product-vendor-info', null, [
                'vendor' => $vendor
            ]);
        }
    }
}
