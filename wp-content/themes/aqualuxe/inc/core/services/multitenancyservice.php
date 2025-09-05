<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for abstracting multitenancy functionality.
 *
 * This service provides a layer to interact with a multitenancy plugin
 * (e.g., WP Multi-Tenant, WPCS). It helps in identifying the current tenant
 * and scoping data accordingly.
 *
 * Note: A real implementation would depend heavily on the chosen multitenancy plugin.
 * This service provides a generic, conceptual structure.
 */
class MultitenancyService
{
    private bool $is_active = false;

    public function __construct()
    {
        // A real check would be more specific, e.g., class_exists('WP_Multi_Tenant_Manager')
        // or a defined constant from the plugin.
        if (defined('MULTITENANCY_ACTIVE') && MULTITENANCY_ACTIVE) {
            $this->is_active = true;
        }
    }

    /**
     * Check if multitenancy is active.
     */
    public function is_active(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the ID of the current tenant.
     *
     * @return int|null The tenant ID, or null if not in a tenant context.
     */
    public function get_current_tenant_id(): ?int
    {
        if (!$this->is_active()) {
            return null;
        }

        // This function would be provided by the multitenancy plugin.
        if (function_exists('get_current_tenant_id')) {
            return (int) \get_current_tenant_id();
        }

        // Fallback or alternative method.
        // Some plugins might use a global object.
        global $tenant;
        if (isset($tenant) && !empty($tenant->id)) {
            return (int) $tenant->id;
        }

        return null;
    }

    /**
     * Get a specific piece of metadata for the current tenant.
     *
     * @param string $meta_key The key for the metadata.
     * @param bool $single Whether to return a single value.
     * @return mixed
     */
    public function get_tenant_meta(string $meta_key, bool $single = true)
    {
        $tenant_id = $this->get_current_tenant_id();
        if (!$tenant_id) {
            return null;
        }

        // This function would be provided by the multitenancy plugin.
        if (function_exists('get_tenant_meta')) {
            return \get_tenant_meta($tenant_id, $meta_key, $single);
        }

        return null;
    }

    /**
     * A conceptual filter to scope a WP_Query to the current tenant.
     *
     * In a real scenario, the multitenancy plugin would likely handle this automatically.
     * This is a demonstration of how it might be done manually if needed.
     *
     * @param \WP_Query $query
     */
    public function scope_query_to_tenant(\WP_Query $query): void
    {
        if (!$this->is_active() || !$this->get_current_tenant_id()) {
            return;
        }

        // The multitenancy plugin should automatically handle this via pre_get_posts.
        // If manual scoping is needed, it would look something like this:
        /*
        $meta_query = $query->get('meta_query', []);
        $meta_query[] = [
            'key'   => '_tenant_id', // The meta key used to scope posts
            'value' => $this->get_current_tenant_id(),
        ];
        $query->set('meta_query', $meta_query);
        */
    }
}
