<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\WholesaleService;

/**
 * Wholesale Module.
 *
 * Adds features and styling for B2B/wholesale stores.
 */
class Wholesale implements ModuleInterface
{
    private ?WholesaleService $wholesale_service = null;

    public function boot(): void
    {
        $this->wholesale_service = new WholesaleService();

        if (!$this->wholesale_service->is_active()) {
            return;
        }

        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add a notice for non-wholesale users on products that have wholesale pricing.
        \add_action('woocommerce_single_product_summary', [$this, 'display_wholesale_notice'], 35);
    }

    public function enqueue_assets(): void
    {
        $css_path = '/modules/wholesale/assets/dist/wholesale.css';
        $css_file = AQUALUXE_DIR . $css_path;
        if (file_exists($css_file)) {
            \wp_enqueue_style(
                'aqualuxe-wholesale',
                AQUALUXE_URI . $css_path,
                [],
                filemtime($css_file)
            );
        }
    }

    /**
     * Displays a notice for guests or retail customers if a product has
     * special wholesale pricing.
     */
    public function display_wholesale_notice(): void
    {
        global $product;

        // We need a way to know if a product *has* wholesale pricing, even for retail users.
        // This logic is highly plugin-dependent.
        // For 'WooCommerce Wholesale Prices', a meta key `_wwp_wholesale_price` exists.
        $has_wholesale_price = !empty(\get_post_meta($product->get_id(), '_wwp_wholesale_price', true));

        if ($has_wholesale_price && !$this->wholesale_service->is_wholesale_customer()) {
            \get_template_part('modules/wholesale/templates/wholesale-notice');
        }
    }
}
