<?php
/**
 * Enterprise Theme Vendor Management Service
 * 
 * Comprehensive multi-vendor marketplace management system
 * providing vendor registration, product management, commission
 * tracking, and vendor-specific features
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
 * Vendor Management Service Class
 * 
 * Implements:
 * - Vendor registration and profile management
 * - Product management and catalog
 * - Commission calculation and tracking
 * - Vendor dashboard and analytics
 * - Order management for vendors
 * - Vendor-specific settings and customization
 * - Multi-vendor marketplace features
 */
class Enterprise_Theme_Vendor_Service {
    
    /**
     * Service configuration
     * 
     * @var array
     */
    private array $config;
    
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
     * Tenant service instance
     * 
     * @var Enterprise_Theme_Tenant_Service
     */
    private Enterprise_Theme_Tenant_Service $tenant_service;
    
    /**
     * Vendor cache
     * 
     * @var array
     */
    private array $vendor_cache = [];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     * @param Enterprise_Theme_Database_Service $database Database service
     * @param Enterprise_Theme_Cache_Service $cache Cache service
     * @param Enterprise_Theme_Tenant_Service $tenant_service Tenant service
     */
    public function __construct(
        Enterprise_Theme_Config $config,
        Enterprise_Theme_Database_Service $database,
        Enterprise_Theme_Cache_Service $cache,
        Enterprise_Theme_Tenant_Service $tenant_service
    ) {
        $this->config = $config->get('multivendor');
        $this->database = $database;
        $this->cache = $cache;
        $this->tenant_service = $tenant_service;
        $this->init_vendor_management();
    }
    
    /**
     * Get vendor by ID
     * 
     * @param int $vendor_id Vendor ID
     * @return array|null Vendor data
     */
    public function get_vendor(int $vendor_id): ?array {
        $cache_key = "vendor_{$vendor_id}";
        
        if (isset($this->vendor_cache[$cache_key])) {
            return $this->vendor_cache[$cache_key];
        }
        
        $vendor = $this->cache->remember(
            $cache_key,
            function() use ($vendor_id) {
                $results = $this->database->get('vendors', [
                    'where' => ['id' => $vendor_id],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'vendors']
        );
        
        if ($vendor) {
            $vendor = $this->process_vendor_data($vendor);
            $this->vendor_cache[$cache_key] = $vendor;
        }
        
        return $vendor;
    }
    
    /**
     * Get vendor by user ID
     * 
     * @param int $user_id User ID
     * @param int $tenant_id Tenant ID (optional)
     * @return array|null Vendor data
     */
    public function get_vendor_by_user(int $user_id, int $tenant_id = null): ?array {
        if ($tenant_id === null) {
            $current_tenant = $this->tenant_service->get_current_tenant();
            $tenant_id = $current_tenant['id'] ?? 0;
        }
        
        $cache_key = "vendor_user_{$user_id}_{$tenant_id}";
        
        if (isset($this->vendor_cache[$cache_key])) {
            return $this->vendor_cache[$cache_key];
        }
        
        $vendor = $this->cache->remember(
            $cache_key,
            function() use ($user_id, $tenant_id) {
                $results = $this->database->get('vendors', [
                    'where' => [
                        'user_id' => $user_id,
                        'tenant_id' => $tenant_id
                    ],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'vendors']
        );
        
        if ($vendor) {
            $vendor = $this->process_vendor_data($vendor);
            $this->vendor_cache[$cache_key] = $vendor;
        }
        
        return $vendor;
    }
    
    /**
     * Get vendor by store slug
     * 
     * @param string $store_slug Store slug
     * @param int $tenant_id Tenant ID (optional)
     * @return array|null Vendor data
     */
    public function get_vendor_by_slug(string $store_slug, int $tenant_id = null): ?array {
        if ($tenant_id === null) {
            $current_tenant = $this->tenant_service->get_current_tenant();
            $tenant_id = $current_tenant['id'] ?? 0;
        }
        
        $cache_key = "vendor_slug_{$store_slug}_{$tenant_id}";
        
        if (isset($this->vendor_cache[$cache_key])) {
            return $this->vendor_cache[$cache_key];
        }
        
        $vendor = $this->cache->remember(
            $cache_key,
            function() use ($store_slug, $tenant_id) {
                $results = $this->database->get('vendors', [
                    'where' => [
                        'store_slug' => $store_slug,
                        'tenant_id' => $tenant_id,
                        'status' => 'active'
                    ],
                    'limit' => 1
                ]);
                
                return !empty($results) ? $results[0] : null;
            },
            3600,
            ['group' => 'vendors']
        );
        
        if ($vendor) {
            $vendor = $this->process_vendor_data($vendor);
            $this->vendor_cache[$cache_key] = $vendor;
        }
        
        return $vendor;
    }
    
    /**
     * Create new vendor
     * 
     * @param array $vendor_data Vendor data
     * @return int|false Vendor ID or false on failure
     */
    public function create_vendor(array $vendor_data): int|false {
        // Validate vendor data
        $validation = $this->validate_vendor_data($vendor_data);
        if (!$validation['valid']) {
            return false;
        }
        
        $current_tenant = $this->tenant_service->get_current_tenant();
        if (!$current_tenant) {
            return false;
        }
        
        // Check if user is already a vendor
        if ($this->get_vendor_by_user($vendor_data['user_id'], $current_tenant['id'])) {
            return false;
        }
        
        // Generate unique store slug
        $store_slug = $this->generate_store_slug($vendor_data['store_name'], $current_tenant['id']);
        
        // Prepare vendor data
        $prepared_data = [
            'user_id' => (int) $vendor_data['user_id'],
            'tenant_id' => $current_tenant['id'],
            'store_name' => sanitize_text_field($vendor_data['store_name']),
            'store_slug' => $store_slug,
            'status' => $vendor_data['status'] ?? 'pending',
            'commission_rate' => $this->validate_commission_rate($vendor_data['commission_rate'] ?? null),
            'settings' => json_encode($vendor_data['settings'] ?? []),
        ];
        
        $vendor_id = $this->database->insert('vendors', $prepared_data);
        
        if ($vendor_id) {
            // Create vendor-specific resources
            $this->create_vendor_resources($vendor_id);
            
            // Clear cache
            $this->cache->flush_group('vendors');
            
            // Trigger vendor creation hook
            do_action('enterprise_theme_vendor_created', $vendor_id, $prepared_data);
        }
        
        return $vendor_id;
    }
    
    /**
     * Update vendor
     * 
     * @param int $vendor_id Vendor ID
     * @param array $vendor_data Updated vendor data
     * @return bool Success status
     */
    public function update_vendor(int $vendor_id, array $vendor_data): bool {
        $existing_vendor = $this->get_vendor($vendor_id);
        if (!$existing_vendor) {
            return false;
        }
        
        // Validate vendor data
        $validation = $this->validate_vendor_data($vendor_data, $vendor_id);
        if (!$validation['valid']) {
            return false;
        }
        
        // Prepare update data
        $update_data = [];
        
        if (isset($vendor_data['store_name'])) {
            $update_data['store_name'] = sanitize_text_field($vendor_data['store_name']);
            
            // Update slug if name changed
            if ($update_data['store_name'] !== $existing_vendor['store_name']) {
                $update_data['store_slug'] = $this->generate_store_slug(
                    $update_data['store_name'],
                    $existing_vendor['tenant_id']
                );
            }
        }
        
        if (isset($vendor_data['status'])) {
            $update_data['status'] = $vendor_data['status'];
        }
        
        if (isset($vendor_data['commission_rate'])) {
            $update_data['commission_rate'] = $this->validate_commission_rate($vendor_data['commission_rate']);
        }
        
        if (isset($vendor_data['settings'])) {
            $current_settings = json_decode($existing_vendor['settings'], true) ?: [];
            $new_settings = array_merge($current_settings, $vendor_data['settings']);
            $update_data['settings'] = json_encode($new_settings);
        }
        
        if (empty($update_data)) {
            return true; // No changes
        }
        
        $result = $this->database->update('vendors', $update_data, ['id' => $vendor_id]);
        
        if ($result !== false) {
            // Clear cache
            $this->invalidate_vendor_cache($vendor_id);
            
            // Trigger vendor update hook
            do_action('enterprise_theme_vendor_updated', $vendor_id, $update_data);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete vendor
     * 
     * @param int $vendor_id Vendor ID
     * @param bool $force_delete Force deletion even with dependencies
     * @return bool Success status
     */
    public function delete_vendor(int $vendor_id, bool $force_delete = false): bool {
        $vendor = $this->get_vendor($vendor_id);
        if (!$vendor) {
            return false;
        }
        
        // Check for dependencies
        if (!$force_delete && $this->has_vendor_dependencies($vendor_id)) {
            return false;
        }
        
        // Clean up vendor resources
        $this->cleanup_vendor_resources($vendor_id);
        
        // Delete vendor record
        $result = $this->database->delete('vendors', ['id' => $vendor_id]);
        
        if ($result !== false) {
            // Clear cache
            $this->invalidate_vendor_cache($vendor_id);
            
            // Trigger vendor deletion hook
            do_action('enterprise_theme_vendor_deleted', $vendor_id, $vendor);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all vendors
     * 
     * @param array $options Query options
     * @return array List of vendors
     */
    public function get_all_vendors(array $options = []): array {
        // Add tenant filter if not specified
        if (!isset($options['where']['tenant_id'])) {
            $current_tenant = $this->tenant_service->get_current_tenant();
            if ($current_tenant) {
                $options['where']['tenant_id'] = $current_tenant['id'];
            }
        }
        
        $cache_key = 'all_vendors_' . md5(serialize($options));
        
        return $this->cache->remember(
            $cache_key,
            function() use ($options) {
                $vendors = $this->database->get('vendors', $options);
                
                return array_map([$this, 'process_vendor_data'], $vendors);
            },
            1800,
            ['group' => 'vendors']
        );
    }
    
    /**
     * Get vendor products
     * 
     * @param int $vendor_id Vendor ID
     * @param array $options Query options
     * @return array Vendor products
     */
    public function get_vendor_products(int $vendor_id, array $options = []): array {
        $options['where']['vendor_id'] = $vendor_id;
        
        return $this->database->get('vendor_products', $options);
    }
    
    /**
     * Calculate vendor commission
     * 
     * @param int $vendor_id Vendor ID
     * @param float $order_total Order total
     * @param array $options Calculation options
     * @return array Commission calculation
     */
    public function calculate_commission(int $vendor_id, float $order_total, array $options = []): array {
        $vendor = $this->get_vendor($vendor_id);
        if (!$vendor) {
            return [
                'commission_rate' => 0,
                'commission_amount' => 0,
                'vendor_amount' => $order_total,
                'platform_amount' => 0,
            ];
        }
        
        $commission_rate = $vendor['commission_rate'];
        
        // Apply commission rate modifiers
        $commission_rate = apply_filters(
            'enterprise_theme_vendor_commission_rate',
            $commission_rate,
            $vendor_id,
            $order_total,
            $options
        );
        
        $commission_amount = ($order_total * $commission_rate) / 100;
        $vendor_amount = $order_total - $commission_amount;
        
        return [
            'commission_rate' => $commission_rate,
            'commission_amount' => round($commission_amount, 2),
            'vendor_amount' => round($vendor_amount, 2),
            'platform_amount' => round($commission_amount, 2),
        ];
    }
    
    /**
     * Record vendor commission
     * 
     * @param int $vendor_id Vendor ID
     * @param int $order_id Order ID
     * @param array $commission_data Commission data
     * @return int|false Commission record ID or false on failure
     */
    public function record_commission(int $vendor_id, int $order_id, array $commission_data): int|false {
        $commission_record = [
            'vendor_id' => $vendor_id,
            'order_id' => $order_id,
            'order_total' => $commission_data['order_total'],
            'commission_rate' => $commission_data['commission_rate'],
            'commission_amount' => $commission_data['commission_amount'],
            'vendor_amount' => $commission_data['vendor_amount'],
            'status' => $commission_data['status'] ?? 'pending',
            'notes' => $commission_data['notes'] ?? '',
        ];
        
        return $this->database->insert('vendor_commissions', $commission_record);
    }
    
    /**
     * Get vendor commissions
     * 
     * @param int $vendor_id Vendor ID
     * @param array $options Query options
     * @return array Vendor commissions
     */
    public function get_vendor_commissions(int $vendor_id, array $options = []): array {
        $options['where']['vendor_id'] = $vendor_id;
        
        return $this->database->get('vendor_commissions', $options);
    }
    
    /**
     * Get vendor statistics
     * 
     * @param int $vendor_id Vendor ID
     * @return array Vendor statistics
     */
    public function get_vendor_statistics(int $vendor_id): array {
        $cache_key = "vendor_stats_{$vendor_id}";
        
        return $this->cache->remember(
            $cache_key,
            function() use ($vendor_id) {
                return [
                    'total_products' => $this->database->count('vendor_products', [
                        'vendor_id' => $vendor_id
                    ]),
                    'active_products' => $this->database->count('vendor_products', [
                        'vendor_id' => $vendor_id,
                        'status' => 'active'
                    ]),
                    'total_commissions' => $this->get_vendor_commission_total($vendor_id),
                    'pending_commissions' => $this->get_vendor_commission_total($vendor_id, 'pending'),
                    'paid_commissions' => $this->get_vendor_commission_total($vendor_id, 'paid'),
                ];
            },
            1800,
            ['group' => 'vendor_stats']
        );
    }
    
    /**
     * Check if user is vendor
     * 
     * @param int $user_id User ID
     * @param int $tenant_id Tenant ID (optional)
     * @return bool Vendor status
     */
    public function is_vendor(int $user_id, int $tenant_id = null): bool {
        return $this->get_vendor_by_user($user_id, $tenant_id) !== null;
    }
    
    /**
     * Check if vendor is active
     * 
     * @param int $vendor_id Vendor ID
     * @return bool Active status
     */
    public function is_vendor_active(int $vendor_id): bool {
        $vendor = $this->get_vendor($vendor_id);
        return $vendor && $vendor['status'] === 'active';
    }
    
    /**
     * Get vendor setting
     * 
     * @param int $vendor_id Vendor ID
     * @param string $setting_key Setting key
     * @param mixed $default Default value
     * @return mixed Setting value
     */
    public function get_vendor_setting(int $vendor_id, string $setting_key, $default = null) {
        $vendor = $this->get_vendor($vendor_id);
        
        if (!$vendor) {
            return $default;
        }
        
        $settings = json_decode($vendor['settings'], true) ?: [];
        
        return $this->get_nested_setting($settings, $setting_key, $default);
    }
    
    /**
     * Set vendor setting
     * 
     * @param int $vendor_id Vendor ID
     * @param string $setting_key Setting key
     * @param mixed $value Setting value
     * @return bool Success status
     */
    public function set_vendor_setting(int $vendor_id, string $setting_key, $value): bool {
        $vendor = $this->get_vendor($vendor_id);
        
        if (!$vendor) {
            return false;
        }
        
        $settings = json_decode($vendor['settings'], true) ?: [];
        $this->set_nested_setting($settings, $setting_key, $value);
        
        return $this->update_vendor($vendor_id, ['settings' => $settings]);
    }
    
    /**
     * Initialize vendor management
     * 
     * @return void
     */
    private function init_vendor_management(): void {
        // Hook into user registration for vendor setup
        add_action('user_register', [$this, 'handle_vendor_registration'], 10, 1);
        
        // Hook into product creation for vendor association
        add_action('wp_insert_post', [$this, 'handle_product_creation'], 10, 3);
        
        // Hook into order completion for commission calculation
        add_action('woocommerce_order_status_completed', [$this, 'handle_order_completion'], 10, 1);
    }
    
    /**
     * Process vendor data
     * 
     * @param array $vendor Raw vendor data
     * @return array Processed vendor data
     */
    private function process_vendor_data(array $vendor): array {
        if (isset($vendor['settings']) && is_string($vendor['settings'])) {
            $vendor['settings'] = json_decode($vendor['settings'], true) ?: [];
        }
        
        // Add computed fields
        $vendor['store_url'] = $this->get_vendor_store_url($vendor);
        $vendor['avatar_url'] = $this->get_vendor_avatar_url($vendor);
        
        return $vendor;
    }
    
    /**
     * Validate vendor data
     * 
     * @param array $vendor_data Vendor data to validate
     * @param int $exclude_id Vendor ID to exclude from uniqueness checks
     * @return array Validation result
     */
    private function validate_vendor_data(array $vendor_data, int $exclude_id = null): array {
        $errors = [];
        
        // Required fields
        if (empty($vendor_data['user_id'])) {
            $errors['user_id'] = 'User ID is required';
        }
        
        if (empty($vendor_data['store_name'])) {
            $errors['store_name'] = 'Store name is required';
        }
        
        // Validate commission rate
        if (isset($vendor_data['commission_rate'])) {
            $commission_rate = $vendor_data['commission_rate'];
            if (!is_numeric($commission_rate) || $commission_rate < 0 || $commission_rate > 100) {
                $errors['commission_rate'] = 'Commission rate must be between 0 and 100';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Generate unique store slug
     * 
     * @param string $store_name Store name
     * @param int $tenant_id Tenant ID
     * @return string Generated slug
     */
    private function generate_store_slug(string $store_name, int $tenant_id): string {
        $slug = strtolower(trim($store_name));
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness within tenant
        $original_slug = $slug;
        $counter = 1;
        
        while ($this->slug_exists($slug, $tenant_id)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if store slug exists
     * 
     * @param string $slug Store slug
     * @param int $tenant_id Tenant ID
     * @return bool Slug existence status
     */
    private function slug_exists(string $slug, int $tenant_id): bool {
        $result = $this->database->get('vendors', [
            'where' => [
                'store_slug' => $slug,
                'tenant_id' => $tenant_id
            ],
            'limit' => 1
        ]);
        
        return !empty($result);
    }
    
    /**
     * Validate commission rate
     * 
     * @param mixed $commission_rate Commission rate
     * @return float Validated commission rate
     */
    private function validate_commission_rate($commission_rate): float {
        if ($commission_rate === null) {
            return $this->config['default_commission_rate'] ?? 10.0;
        }
        
        $rate = floatval($commission_rate);
        
        // Ensure rate is within bounds
        $min_rate = $this->config['min_commission_rate'] ?? 0.0;
        $max_rate = $this->config['max_commission_rate'] ?? 50.0;
        
        return max($min_rate, min($max_rate, $rate));
    }
    
    /**
     * Create vendor-specific resources
     * 
     * @param int $vendor_id Vendor ID
     * @return void
     */
    private function create_vendor_resources(int $vendor_id): void {
        // Create vendor capabilities for user
        $vendor = $this->get_vendor($vendor_id);
        if ($vendor) {
            $user = get_user_by('ID', $vendor['user_id']);
            if ($user) {
                $user->add_cap('manage_vendor_products');
                $user->add_cap('view_vendor_analytics');
                $user->add_cap('manage_vendor_settings');
            }
        }
        
        do_action('enterprise_theme_create_vendor_resources', $vendor_id);
    }
    
    /**
     * Check if vendor has dependencies
     * 
     * @param int $vendor_id Vendor ID
     * @return bool Dependencies status
     */
    private function has_vendor_dependencies(int $vendor_id): bool {
        // Check for products
        $product_count = $this->database->count('vendor_products', ['vendor_id' => $vendor_id]);
        
        // Check for commissions
        $commission_count = $this->database->count('vendor_commissions', ['vendor_id' => $vendor_id]);
        
        $has_dependencies = $product_count > 0 || $commission_count > 0;
        
        return apply_filters('enterprise_theme_vendor_has_dependencies', $has_dependencies, $vendor_id);
    }
    
    /**
     * Cleanup vendor resources
     * 
     * @param int $vendor_id Vendor ID
     * @return void
     */
    private function cleanup_vendor_resources(int $vendor_id): void {
        // Remove vendor capabilities from user
        $vendor = $this->get_vendor($vendor_id);
        if ($vendor) {
            $user = get_user_by('ID', $vendor['user_id']);
            if ($user) {
                $user->remove_cap('manage_vendor_products');
                $user->remove_cap('view_vendor_analytics');
                $user->remove_cap('manage_vendor_settings');
            }
        }
        
        // Clean up vendor-specific data
        do_action('enterprise_theme_cleanup_vendor_resources', $vendor_id);
    }
    
    /**
     * Get vendor store URL
     * 
     * @param array $vendor Vendor data
     * @return string Store URL
     */
    private function get_vendor_store_url(array $vendor): string {
        $base_url = home_url();
        return "{$base_url}/store/{$vendor['store_slug']}/";
    }
    
    /**
     * Get vendor avatar URL
     * 
     * @param array $vendor Vendor data
     * @return string Avatar URL
     */
    private function get_vendor_avatar_url(array $vendor): string {
        $user_avatar = get_avatar_url($vendor['user_id']);
        
        // Check for custom vendor avatar in settings
        $custom_avatar = $vendor['settings']['avatar_url'] ?? null;
        
        return $custom_avatar ?: $user_avatar;
    }
    
    /**
     * Get vendor commission total
     * 
     * @param int $vendor_id Vendor ID
     * @param string $status Commission status (optional)
     * @return float Commission total
     */
    private function get_vendor_commission_total(int $vendor_id, string $status = null): float {
        $where = ['vendor_id' => $vendor_id];
        
        if ($status) {
            $where['status'] = $status;
        }
        
        $result = $this->database->query(
            "SELECT SUM(commission_amount) as total FROM {$this->database->get_table_name('vendor_commissions')} WHERE " .
            implode(' AND ', array_map(function($key) { return "{$key} = %s"; }, array_keys($where))),
            array_values($where)
        );
        
        return $result ? floatval($result[0]['total'] ?? 0) : 0.0;
    }
    
    /**
     * Invalidate vendor cache
     * 
     * @param int $vendor_id Vendor ID
     * @return void
     */
    private function invalidate_vendor_cache(int $vendor_id): void {
        $vendor = $this->get_vendor($vendor_id);
        
        if ($vendor) {
            // Clear specific cache entries
            $this->cache->delete("vendor_{$vendor_id}");
            $this->cache->delete("vendor_user_{$vendor['user_id']}_{$vendor['tenant_id']}");
            $this->cache->delete("vendor_slug_{$vendor['store_slug']}_{$vendor['tenant_id']}");
            $this->cache->delete("vendor_stats_{$vendor_id}");
            
            // Clear vendor cache groups
            $this->cache->flush_group('vendors');
            $this->cache->flush_group('vendor_stats');
            
            // Clear local cache
            unset($this->vendor_cache["vendor_{$vendor_id}"]);
            unset($this->vendor_cache["vendor_user_{$vendor['user_id']}_{$vendor['tenant_id']}"]);
            unset($this->vendor_cache["vendor_slug_{$vendor['store_slug']}_{$vendor['tenant_id']}"]);
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
     * Handle vendor registration
     * 
     * @param int $user_id User ID
     * @return void
     */
    public function handle_vendor_registration(int $user_id): void {
        // Check if user should be automatically registered as vendor
        $auto_vendor_registration = $this->config['auto_vendor_registration'] ?? false;
        
        if ($auto_vendor_registration) {
            $user = get_user_by('ID', $user_id);
            if ($user) {
                $this->create_vendor([
                    'user_id' => $user_id,
                    'store_name' => $user->display_name . "'s Store",
                    'status' => 'pending',
                ]);
            }
        }
    }
    
    /**
     * Handle product creation
     * 
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     * @param bool $update Whether this is an update
     * @return void
     */
    public function handle_product_creation(int $post_id, $post, bool $update): void {
        if ($post->post_type !== 'product' || $update) {
            return;
        }
        
        $vendor = $this->get_vendor_by_user(get_current_user_id());
        if ($vendor) {
            // Associate product with vendor
            $this->database->insert('vendor_products', [
                'vendor_id' => $vendor['id'],
                'product_id' => $post_id,
                'status' => 'active',
            ]);
        }
    }
    
    /**
     * Handle order completion
     * 
     * @param int $order_id Order ID
     * @return void
     */
    public function handle_order_completion(int $order_id): void {
        // This would integrate with WooCommerce or other e-commerce plugins
        // to calculate and record vendor commissions
        
        do_action('enterprise_theme_vendor_order_completed', $order_id);
    }
}
