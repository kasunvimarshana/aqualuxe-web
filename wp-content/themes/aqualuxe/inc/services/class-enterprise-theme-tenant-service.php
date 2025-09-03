<?php
/**
 * Enterprise Theme Tenant Management Service
 * 
 * Comprehensive multi-tenant management system providing
 * tenant isolation, domain routing, and resource management
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Tenant Management Service Class
 * 
 * Implements:
 * - Multi-tenant architecture with database isolation
 * - Domain and subdomain routing
 * - Tenant-specific configurations
 * - Resource allocation and limits
 * - Tenant lifecycle management
 * - Cross-tenant data isolation
 * - Tenant switching and context management
 */
class Enterprise_Theme_Tenant_Service {
    
    /**
     * Service configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * Current tenant instance
     * 
     * @var array|null
     */
    private ?array $current_tenant = null;
    
    /**
     * Tenant cache
     * 
     * @var array
     */
    private array $tenant_cache = [];
    
    /**
     * Database service instance
     * 
     * @var Enterprise_Theme_Database_Service
     */
    private Enterprise_Theme_Database_Service $database;
    
    /**
     * Cache service instance
     * 
     * @var Enterprise_Theme_Cache_Service
     */
    private Enterprise_Theme_Cache_Service $cache;
    
    /**
     * Tenant context stack
     * 
     * @var array
     */
    private array $context_stack = [];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     * @param Enterprise_Theme_Database_Service $database Database service
     * @param Enterprise_Theme_Cache_Service $cache Cache service
     */
    public function __construct(
        Enterprise_Theme_Config $config,
        Enterprise_Theme_Database_Service $database,
        Enterprise_Theme_Cache_Service $cache
    ) {
        $this->config = $config->get('multitenancy');
        $this->database = $database;
        $this->cache = $cache;
        $this->init_tenant_management();
    }
    
    /**
     * Get current tenant
     * 
     * @return array|null Current tenant data
     */
    public function get_current_tenant(): ?array {
        if ($this->current_tenant === null) {
            $this->current_tenant = $this->resolve_tenant();
        }
        
        return $this->current_tenant;
    }
    
    /**
     * Get tenant by ID
     * 
     * @param int $tenant_id Tenant ID
     * @return array|null Tenant data
     */
    public function get_tenant(int $tenant_id): ?array {
        $cache_key = "tenant_{$tenant_id}";
        
        if (isset($this->tenant_cache[$cache_key])) {
            return $this->tenant_cache[$cache_key];
        }
        
        $tenant = $this->cache->remember(
            $cache_key,
            function() use ($tenant_id) {
                $results = $this->database->get('tenants', [
                    'where' => ['id' => $tenant_id],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'tenants']
        );
        
        if ($tenant) {
            $tenant = $this->process_tenant_data($tenant);
            $this->tenant_cache[$cache_key] = $tenant;
        }
        
        return $tenant;
    }
    
    /**
     * Get tenant by domain
     * 
     * @param string $domain Domain name
     * @return array|null Tenant data
     */
    public function get_tenant_by_domain(string $domain): ?array {
        $cache_key = "tenant_domain_{$domain}";
        
        if (isset($this->tenant_cache[$cache_key])) {
            return $this->tenant_cache[$cache_key];
        }
        
        $tenant = $this->cache->remember(
            $cache_key,
            function() use ($domain) {
                $results = $this->database->get('tenants', [
                    'where' => [
                        'domain' => $domain,
                        'status' => 'active'
                    ],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'tenants']
        );
        
        if ($tenant) {
            $tenant = $this->process_tenant_data($tenant);
            $this->tenant_cache[$cache_key] = $tenant;
        }
        
        return $tenant;
    }
    
    /**
     * Get tenant by subdomain
     * 
     * @param string $subdomain Subdomain name
     * @return array|null Tenant data
     */
    public function get_tenant_by_subdomain(string $subdomain): ?array {
        $cache_key = "tenant_subdomain_{$subdomain}";
        
        if (isset($this->tenant_cache[$cache_key])) {
            return $this->tenant_cache[$cache_key];
        }
        
        $tenant = $this->cache->remember(
            $cache_key,
            function() use ($subdomain) {
                $results = $this->database->get('tenants', [
                    'where' => [
                        'subdomain' => $subdomain,
                        'status' => 'active'
                    ],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'tenants']
        );
        
        if ($tenant) {
            $tenant = $this->process_tenant_data($tenant);
            $this->tenant_cache[$cache_key] = $tenant;
        }
        
        return $tenant;
    }
    
    /**
     * Create new tenant
     * 
     * @param array $tenant_data Tenant data
     * @return int|false Tenant ID or false on failure
     */
    public function create_tenant(array $tenant_data): int|false {
        // Validate tenant data
        $validation = $this->validate_tenant_data($tenant_data);
        if (!$validation['valid']) {
            return false;
        }
        
        // Check for existing domain/subdomain
        if ($this->domain_exists($tenant_data['domain'])) {
            return false;
        }
        
        if (isset($tenant_data['subdomain']) && $this->subdomain_exists($tenant_data['subdomain'])) {
            return false;
        }
        
        // Prepare tenant data
        $prepared_data = [
            'domain' => sanitize_text_field($tenant_data['domain']),
            'subdomain' => isset($tenant_data['subdomain']) 
                ? sanitize_text_field($tenant_data['subdomain']) 
                : $this->generate_subdomain($tenant_data['name']),
            'name' => sanitize_text_field($tenant_data['name']),
            'status' => $tenant_data['status'] ?? 'active',
            'settings' => json_encode($tenant_data['settings'] ?? []),
        ];
        
        $tenant_id = $this->database->insert('tenants', $prepared_data);
        
        if ($tenant_id) {
            // Create tenant-specific resources
            $this->create_tenant_resources($tenant_id);
            
            // Clear cache
            $this->cache->flush_group('tenants');
            
            // Trigger tenant creation hook
            do_action('enterprise_theme_tenant_created', $tenant_id, $prepared_data);
        }
        
        return $tenant_id;
    }
    
    /**
     * Update tenant
     * 
     * @param int $tenant_id Tenant ID
     * @param array $tenant_data Updated tenant data
     * @return bool Success status
     */
    public function update_tenant(int $tenant_id, array $tenant_data): bool {
        $existing_tenant = $this->get_tenant($tenant_id);
        if (!$existing_tenant) {
            return false;
        }
        
        // Validate tenant data
        $validation = $this->validate_tenant_data($tenant_data, $tenant_id);
        if (!$validation['valid']) {
            return false;
        }
        
        // Prepare update data
        $update_data = [];
        
        if (isset($tenant_data['domain'])) {
            $update_data['domain'] = sanitize_text_field($tenant_data['domain']);
        }
        
        if (isset($tenant_data['subdomain'])) {
            $update_data['subdomain'] = sanitize_text_field($tenant_data['subdomain']);
        }
        
        if (isset($tenant_data['name'])) {
            $update_data['name'] = sanitize_text_field($tenant_data['name']);
        }
        
        if (isset($tenant_data['status'])) {
            $update_data['status'] = $tenant_data['status'];
        }
        
        if (isset($tenant_data['settings'])) {
            $current_settings = json_decode($existing_tenant['settings'], true) ?: [];
            $new_settings = array_merge($current_settings, $tenant_data['settings']);
            $update_data['settings'] = json_encode($new_settings);
        }
        
        if (empty($update_data)) {
            return true; // No changes
        }
        
        $result = $this->database->update('tenants', $update_data, ['id' => $tenant_id]);
        
        if ($result !== false) {
            // Clear cache
            $this->invalidate_tenant_cache($tenant_id);
            
            // Trigger tenant update hook
            do_action('enterprise_theme_tenant_updated', $tenant_id, $update_data);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete tenant
     * 
     * @param int $tenant_id Tenant ID
     * @param bool $force_delete Force deletion even with dependencies
     * @return bool Success status
     */
    public function delete_tenant(int $tenant_id, bool $force_delete = false): bool {
        $tenant = $this->get_tenant($tenant_id);
        if (!$tenant) {
            return false;
        }
        
        // Check for dependencies
        if (!$force_delete && $this->has_tenant_dependencies($tenant_id)) {
            return false;
        }
        
        // Clean up tenant resources
        $this->cleanup_tenant_resources($tenant_id);
        
        // Delete tenant record
        $result = $this->database->delete('tenants', ['id' => $tenant_id]);
        
        if ($result !== false) {
            // Clear cache
            $this->invalidate_tenant_cache($tenant_id);
            
            // Trigger tenant deletion hook
            do_action('enterprise_theme_tenant_deleted', $tenant_id, $tenant);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Switch to tenant context
     * 
     * @param int $tenant_id Tenant ID
     * @return bool Success status
     */
    public function switch_tenant(int $tenant_id): bool {
        $tenant = $this->get_tenant($tenant_id);
        if (!$tenant) {
            return false;
        }
        
        // Push current context to stack
        $this->context_stack[] = $this->current_tenant;
        
        // Switch to new tenant
        $this->current_tenant = $tenant;
        
        // Apply tenant configuration
        $this->apply_tenant_configuration($tenant);
        
        // Trigger tenant switch hook
        do_action('enterprise_theme_tenant_switched', $tenant_id, $tenant);
        
        return true;
    }
    
    /**
     * Restore previous tenant context
     * 
     * @return bool Success status
     */
    public function restore_tenant(): bool {
        if (empty($this->context_stack)) {
            return false;
        }
        
        $previous_tenant = array_pop($this->context_stack);
        $current_tenant_id = $this->current_tenant['id'] ?? null;
        
        $this->current_tenant = $previous_tenant;
        
        if ($previous_tenant) {
            $this->apply_tenant_configuration($previous_tenant);
        }
        
        // Trigger tenant restore hook
        do_action('enterprise_theme_tenant_restored', $current_tenant_id, $previous_tenant);
        
        return true;
    }
    
    /**
     * Get all tenants
     * 
     * @param array $options Query options
     * @return array List of tenants
     */
    public function get_all_tenants(array $options = []): array {
        $cache_key = 'all_tenants_' . md5(serialize($options));
        
        return $this->cache->remember(
            $cache_key,
            function() use ($options) {
                $tenants = $this->database->get('tenants', $options);
                
                return array_map([$this, 'process_tenant_data'], $tenants);
            },
            1800,
            ['group' => 'tenants']
        );
    }
    
    /**
     * Get tenant statistics
     * 
     * @param int $tenant_id Tenant ID (optional)
     * @return array Tenant statistics
     */
    public function get_tenant_statistics(int $tenant_id = null): array {
        if ($tenant_id) {
            return $this->get_single_tenant_statistics($tenant_id);
        }
        
        return [
            'total_tenants' => $this->database->count('tenants'),
            'active_tenants' => $this->database->count('tenants', ['status' => 'active']),
            'inactive_tenants' => $this->database->count('tenants', ['status' => 'inactive']),
            'pending_tenants' => $this->database->count('tenants', ['status' => 'pending']),
        ];
    }
    
    /**
     * Check if tenant is active
     * 
     * @param int $tenant_id Tenant ID
     * @return bool Active status
     */
    public function is_tenant_active(int $tenant_id): bool {
        $tenant = $this->get_tenant($tenant_id);
        return $tenant && $tenant['status'] === 'active';
    }
    
    /**
     * Get tenant setting
     * 
     * @param string $setting_key Setting key
     * @param mixed $default Default value
     * @param int $tenant_id Tenant ID (optional, uses current)
     * @return mixed Setting value
     */
    public function get_tenant_setting(string $setting_key, $default = null, int $tenant_id = null) {
        $tenant = $tenant_id ? $this->get_tenant($tenant_id) : $this->get_current_tenant();
        
        if (!$tenant) {
            return $default;
        }
        
        $settings = json_decode($tenant['settings'], true) ?: [];
        
        return $this->get_nested_setting($settings, $setting_key, $default);
    }
    
    /**
     * Set tenant setting
     * 
     * @param string $setting_key Setting key
     * @param mixed $value Setting value
     * @param int $tenant_id Tenant ID (optional, uses current)
     * @return bool Success status
     */
    public function set_tenant_setting(string $setting_key, $value, int $tenant_id = null): bool {
        $tenant = $tenant_id ? $this->get_tenant($tenant_id) : $this->get_current_tenant();
        
        if (!$tenant) {
            return false;
        }
        
        $settings = json_decode($tenant['settings'], true) ?: [];
        $this->set_nested_setting($settings, $setting_key, $value);
        
        return $this->update_tenant($tenant['id'], ['settings' => $settings]);
    }
    
    /**
     * Initialize tenant management
     * 
     * @return void
     */
    private function init_tenant_management(): void {
        // Hook into WordPress early to resolve tenant
        add_action('muplugins_loaded', [$this, 'early_tenant_resolution'], 5);
        
        // Hook into template loading to apply tenant-specific templates
        add_filter('template_include', [$this, 'apply_tenant_template'], 99);
        
        // Hook into option retrieval for tenant-specific options
        add_filter('pre_option', [$this, 'get_tenant_option'], 10, 2);
        add_action('update_option', [$this, 'update_tenant_option'], 10, 3);
    }
    
    /**
     * Resolve current tenant from request
     * 
     * @return array|null Resolved tenant
     */
    private function resolve_tenant(): ?array {
        // Try to resolve from domain first
        $domain = $this->get_request_domain();
        if ($domain) {
            $tenant = $this->get_tenant_by_domain($domain);
            if ($tenant) {
                return $tenant;
            }
        }
        
        // Try to resolve from subdomain
        $subdomain = $this->get_request_subdomain();
        if ($subdomain && $subdomain !== 'www') {
            $tenant = $this->get_tenant_by_subdomain($subdomain);
            if ($tenant) {
                return $tenant;
            }
        }
        
        // Default to master tenant if configured
        if ($this->config['default_tenant_id'] ?? null) {
            return $this->get_tenant($this->config['default_tenant_id']);
        }
        
        return null;
    }
    
    /**
     * Get request domain
     * 
     * @return string|null Request domain
     */
    private function get_request_domain(): ?string {
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? null;
        
        if (!$host) {
            return null;
        }
        
        // Remove port if present
        $host = preg_replace('/:\d+$/', '', $host);
        
        return strtolower($host);
    }
    
    /**
     * Get request subdomain
     * 
     * @return string|null Request subdomain
     */
    private function get_request_subdomain(): ?string {
        $domain = $this->get_request_domain();
        
        if (!$domain) {
            return null;
        }
        
        $parts = explode('.', $domain);
        
        // If we have at least 3 parts (subdomain.domain.tld), return the first part
        if (count($parts) >= 3) {
            return $parts[0];
        }
        
        return null;
    }
    
    /**
     * Process tenant data
     * 
     * @param array $tenant Raw tenant data
     * @return array Processed tenant data
     */
    private function process_tenant_data(array $tenant): array {
        if (isset($tenant['settings']) && is_string($tenant['settings'])) {
            $tenant['settings'] = json_decode($tenant['settings'], true) ?: [];
        }
        
        return $tenant;
    }
    
    /**
     * Validate tenant data
     * 
     * @param array $tenant_data Tenant data to validate
     * @param int $exclude_id Tenant ID to exclude from uniqueness checks
     * @return array Validation result
     */
    private function validate_tenant_data(array $tenant_data, int $exclude_id = null): array {
        $errors = [];
        
        // Required fields
        if (empty($tenant_data['domain'])) {
            $errors['domain'] = 'Domain is required';
        } elseif (!filter_var('http://' . $tenant_data['domain'], FILTER_VALIDATE_URL)) {
            $errors['domain'] = 'Invalid domain format';
        }
        
        if (empty($tenant_data['name'])) {
            $errors['name'] = 'Tenant name is required';
        }
        
        // Unique constraints
        if (!empty($tenant_data['domain'])) {
            $existing = $this->get_tenant_by_domain($tenant_data['domain']);
            if ($existing && (!$exclude_id || $existing['id'] != $exclude_id)) {
                $errors['domain'] = 'Domain already exists';
            }
        }
        
        if (!empty($tenant_data['subdomain'])) {
            $existing = $this->get_tenant_by_subdomain($tenant_data['subdomain']);
            if ($existing && (!$exclude_id || $existing['id'] != $exclude_id)) {
                $errors['subdomain'] = 'Subdomain already exists';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Check if domain exists
     * 
     * @param string $domain Domain to check
     * @return bool Domain existence status
     */
    private function domain_exists(string $domain): bool {
        return $this->get_tenant_by_domain($domain) !== null;
    }
    
    /**
     * Check if subdomain exists
     * 
     * @param string $subdomain Subdomain to check
     * @return bool Subdomain existence status
     */
    private function subdomain_exists(string $subdomain): bool {
        return $this->get_tenant_by_subdomain($subdomain) !== null;
    }
    
    /**
     * Generate subdomain from name
     * 
     * @param string $name Tenant name
     * @return string Generated subdomain
     */
    private function generate_subdomain(string $name): string {
        $subdomain = strtolower(trim($name));
        $subdomain = preg_replace('/[^a-z0-9\-]/', '-', $subdomain);
        $subdomain = preg_replace('/-+/', '-', $subdomain);
        $subdomain = trim($subdomain, '-');
        
        // Ensure uniqueness
        $original_subdomain = $subdomain;
        $counter = 1;
        
        while ($this->subdomain_exists($subdomain)) {
            $subdomain = $original_subdomain . '-' . $counter;
            $counter++;
        }
        
        return $subdomain;
    }
    
    /**
     * Create tenant-specific resources
     * 
     * @param int $tenant_id Tenant ID
     * @return void
     */
    private function create_tenant_resources(int $tenant_id): void {
        // Create tenant-specific database tables if needed
        // Create tenant-specific upload directories
        // Initialize tenant-specific configurations
        
        do_action('enterprise_theme_create_tenant_resources', $tenant_id);
    }
    
    /**
     * Check if tenant has dependencies
     * 
     * @param int $tenant_id Tenant ID
     * @return bool Dependencies status
     */
    private function has_tenant_dependencies(int $tenant_id): bool {
        // Check for vendors
        $vendor_count = $this->database->count('vendors', ['tenant_id' => $tenant_id]);
        
        // Check for other dependencies
        $has_dependencies = $vendor_count > 0;
        
        return apply_filters('enterprise_theme_tenant_has_dependencies', $has_dependencies, $tenant_id);
    }
    
    /**
     * Cleanup tenant resources
     * 
     * @param int $tenant_id Tenant ID
     * @return void
     */
    private function cleanup_tenant_resources(int $tenant_id): void {
        // Delete tenant-specific data
        // Clean up tenant-specific files
        // Remove tenant-specific configurations
        
        do_action('enterprise_theme_cleanup_tenant_resources', $tenant_id);
    }
    
    /**
     * Apply tenant configuration
     * 
     * @param array $tenant Tenant data
     * @return void
     */
    private function apply_tenant_configuration(array $tenant): void {
        // Apply tenant-specific settings
        // Set tenant-specific themes/plugins
        // Configure tenant-specific database connections
        
        do_action('enterprise_theme_apply_tenant_configuration', $tenant);
    }
    
    /**
     * Get single tenant statistics
     * 
     * @param int $tenant_id Tenant ID
     * @return array Tenant statistics
     */
    private function get_single_tenant_statistics(int $tenant_id): array {
        return [
            'vendors' => $this->database->count('vendors', ['tenant_id' => $tenant_id]),
            'active_vendors' => $this->database->count('vendors', [
                'tenant_id' => $tenant_id,
                'status' => 'active'
            ]),
            // Add more tenant-specific statistics as needed
        ];
    }
    
    /**
     * Invalidate tenant cache
     * 
     * @param int $tenant_id Tenant ID
     * @return void
     */
    private function invalidate_tenant_cache(int $tenant_id): void {
        $tenant = $this->get_tenant($tenant_id);
        
        if ($tenant) {
            // Clear specific cache entries
            $this->cache->delete("tenant_{$tenant_id}");
            $this->cache->delete("tenant_domain_{$tenant['domain']}");
            
            if (!empty($tenant['subdomain'])) {
                $this->cache->delete("tenant_subdomain_{$tenant['subdomain']}");
            }
            
            // Clear tenant cache group
            $this->cache->flush_group('tenants');
            
            // Clear local cache
            unset($this->tenant_cache["tenant_{$tenant_id}"]);
            unset($this->tenant_cache["tenant_domain_{$tenant['domain']}"]);
            
            if (!empty($tenant['subdomain'])) {
                unset($this->tenant_cache["tenant_subdomain_{$tenant['subdomain']}"]);
            }
        }
    }
    
    /**
     * Get nested setting value
     * 
     * @param array $settings Settings array
     * @param string $key Dot-notation key
     * @param mixed $default Default value
     * @return mixed Setting value
     */
    private function get_nested_setting(array $settings, string $key, $default = null) {
        $keys = explode('.', $key);
        $value = $settings;
        
        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Set nested setting value
     * 
     * @param array &$settings Settings array (by reference)
     * @param string $key Dot-notation key
     * @param mixed $value Value to set
     * @return void
     */
    private function set_nested_setting(array &$settings, string $key, $value): void {
        $keys = explode('.', $key);
        $current = &$settings;
        
        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $current[$k] = $value;
            } else {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
        }
    }
    
    /**
     * Early tenant resolution
     * 
     * @return void
     */
    public function early_tenant_resolution(): void {
        // Resolve tenant as early as possible
        $this->get_current_tenant();
    }
    
    /**
     * Apply tenant-specific template
     * 
     * @param string $template Template path
     * @return string Modified template path
     */
    public function apply_tenant_template(string $template): string {
        $tenant = $this->get_current_tenant();
        
        if (!$tenant) {
            return $template;
        }
        
        // Look for tenant-specific template
        $tenant_template = str_replace(
            get_template_directory(),
            get_template_directory() . '/tenants/' . $tenant['subdomain'],
            $template
        );
        
        if (file_exists($tenant_template)) {
            return $tenant_template;
        }
        
        return $template;
    }
    
    /**
     * Get tenant-specific option
     * 
     * @param mixed $pre_option Option value
     * @param string $option Option name
     * @return mixed Option value
     */
    public function get_tenant_option($pre_option, string $option) {
        $tenant = $this->get_current_tenant();
        
        if (!$tenant) {
            return $pre_option;
        }
        
        // Check for tenant-specific option
        $tenant_option = $this->get_tenant_setting("options.{$option}");
        
        if ($tenant_option !== null) {
            return $tenant_option;
        }
        
        return $pre_option;
    }
    
    /**
     * Update tenant-specific option
     * 
     * @param string $option Option name
     * @param mixed $old_value Old value
     * @param mixed $value New value
     * @return void
     */
    public function update_tenant_option(string $option, $old_value, $value): void {
        $tenant = $this->get_current_tenant();
        
        if (!$tenant) {
            return;
        }
        
        // Store as tenant-specific setting
        $this->set_tenant_setting("options.{$option}", $value);
    }
}
