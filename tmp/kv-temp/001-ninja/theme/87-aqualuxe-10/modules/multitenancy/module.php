<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\MultitenancyService;

/**
 * Multitenancy Module.
 *
 * Adapts the theme to a multitenant environment.
 */
class Multitenancy implements ModuleInterface
{
    private ?MultitenancyService $multitenancy_service = null;

    public function boot(): void
    {
        $this->multitenancy_service = new MultitenancyService();

        if (!$this->multitenancy_service->is_active()) {
            return;
        }

        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Display a banner with the tenant's name
        \add_action('wp_body_open', [$this, 'display_tenant_banner']);
    }

    public function enqueue_assets(): void
    {
        $css_path = '/modules/multitenancy/assets/dist/multitenancy.css';
        if (file_exists(AQUALUXE_DIR . $css_path)) {
            \wp_enqueue_style(
                'aqualuxe-multitenancy',
                AQUALUXE_URI . $css_path,
                [],
                filemtime(AQUALUXE_DIR . $css_path)
            );
        }
    }

    /**
     * Displays a banner at the top of the site if it's a tenant site.
     */
    public function display_tenant_banner(): void
    {
        $tenant_id = $this->multitenancy_service->get_current_tenant_id();
        if (!$tenant_id) {
            return;
        }

        // Assume tenant name is stored in meta. A real plugin might have a function like get_tenant_name().
        $tenant_name = $this->multitenancy_service->get_tenant_meta('tenant_name');

        if (empty($tenant_name)) {
            $tenant_name = "Tenant ID: $tenant_id";
        }

        \get_template_part('modules/multitenancy/templates/tenant-banner', null, ['tenant_name' => $tenant_name]);
    }
}
