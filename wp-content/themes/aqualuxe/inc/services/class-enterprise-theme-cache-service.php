<?php
/**
 * Enterprise Theme Cache Service
 * 
 * Advanced caching system with multiple backends and cache invalidation
 * Implements cache warming, hierarchical invalidation, and performance monitoring
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
 * Cache Service Class
 * 
 * Implements:
 * - Multiple cache backends (Redis, Memcached, File, Database)
 * - Cache groups and tags for selective invalidation
 * - Cache compression and serialization
 * - Cache statistics and monitoring
 * - Automatic cache warming
 */
class Enterprise_Theme_Cache_Service {
    
    /**
     * Cache backends
     * 
     * @var array
     */
    private array $backends = [];
    
    /**
     * Default cache backend
     * 
     * @var string
     */
    private string $default_backend = 'file';
    
    /**
     * Cache configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * Cache statistics
     * 
     * @var array
     */
    private array $stats = [
        'hits' => 0,
        'misses' => 0,
        'sets' => 0,
        'deletes' => 0,
        'flushes' => 0,
    ];
    
    /**
     * Cache groups
     * 
     * @var array
     */
    private array $groups = [];
    
    /**
     * Cache tags
     * 
     * @var array
     */
    private array $tags = [];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     */
    public function __construct(Enterprise_Theme_Config $config) {
        $this->config = $config->get('performance.caching');
        $this->init_backends();
        $this->init_cache_groups();
    }
    
    /**
     * Get cached value
     * 
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     * @param array $options Cache options
     * @return mixed Cached value or default
     */
    public function get(string $key, $default = null, array $options = []) {
        $backend = $options['backend'] ?? $this->default_backend;
        $group = $options['group'] ?? 'default';
        
        $full_key = $this->build_cache_key($key, $group);
        
        if (!isset($this->backends[$backend])) {
            return $default;
        }
        
        $value = $this->backends[$backend]->get($full_key);
        
        if ($value !== false) {
            $this->stats['hits']++;
            
            // Decompress if needed
            if ($options['compress'] ?? false) {
                $value = $this->decompress($value);
            }
            
            // Unserialize if needed
            if ($options['serialize'] ?? true) {
                $value = maybe_unserialize($value);
            }
            
            return $value;
        }
        
        $this->stats['misses']++;
        return $default;
    }
    
    /**
     * Set cached value
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $expiration Expiration time in seconds
     * @param array $options Cache options
     * @return bool Success status
     */
    public function set(string $key, $value, int $expiration = null, array $options = []): bool {
        $backend = $options['backend'] ?? $this->default_backend;
        $group = $options['group'] ?? 'default';
        $tags = $options['tags'] ?? [];
        
        if ($expiration === null) {
            $expiration = $this->config['cache_lifetime'] ?? 3600;
        }
        
        $full_key = $this->build_cache_key($key, $group);
        
        if (!isset($this->backends[$backend])) {
            return false;
        }
        
        // Serialize if needed
        if ($options['serialize'] ?? true) {
            $value = maybe_serialize($value);
        }
        
        // Compress if needed
        if ($options['compress'] ?? false) {
            $value = $this->compress($value);
        }
        
        $result = $this->backends[$backend]->set($full_key, $value, $expiration);
        
        if ($result) {
            $this->stats['sets']++;
            
            // Store cache metadata
            $this->store_cache_metadata($full_key, $group, $tags);
        }
        
        return $result;
    }
    
    /**
     * Delete cached value
     * 
     * @param string $key Cache key
     * @param array $options Cache options
     * @return bool Success status
     */
    public function delete(string $key, array $options = []): bool {
        $backend = $options['backend'] ?? $this->default_backend;
        $group = $options['group'] ?? 'default';
        
        $full_key = $this->build_cache_key($key, $group);
        
        if (!isset($this->backends[$backend])) {
            return false;
        }
        
        $result = $this->backends[$backend]->delete($full_key);
        
        if ($result) {
            $this->stats['deletes']++;
            $this->delete_cache_metadata($full_key);
        }
        
        return $result;
    }
    
    /**
     * Flush cache by group
     * 
     * @param string $group Cache group
     * @param array $options Cache options
     * @return bool Success status
     */
    public function flush_group(string $group, array $options = []): bool {
        $backend = $options['backend'] ?? $this->default_backend;
        
        if (!isset($this->backends[$backend])) {
            return false;
        }
        
        if (!isset($this->groups[$group])) {
            return true; // No keys in group
        }
        
        $success = true;
        foreach ($this->groups[$group] as $key) {
            if (!$this->backends[$backend]->delete($key)) {
                $success = false;
            }
        }
        
        if ($success) {
            unset($this->groups[$group]);
            $this->stats['flushes']++;
        }
        
        return $success;
    }
    
    /**
     * Flush cache by tags
     * 
     * @param array $tags Cache tags
     * @param array $options Cache options
     * @return bool Success status
     */
    public function flush_by_tags(array $tags, array $options = []): bool {
        $backend = $options['backend'] ?? $this->default_backend;
        
        if (!isset($this->backends[$backend])) {
            return false;
        }
        
        $keys_to_delete = [];
        
        foreach ($tags as $tag) {
            if (isset($this->tags[$tag])) {
                $keys_to_delete = array_merge($keys_to_delete, $this->tags[$tag]);
            }
        }
        
        $keys_to_delete = array_unique($keys_to_delete);
        $success = true;
        
        foreach ($keys_to_delete as $key) {
            if (!$this->backends[$backend]->delete($key)) {
                $success = false;
            }
        }
        
        if ($success) {
            // Clean up tag metadata
            foreach ($tags as $tag) {
                unset($this->tags[$tag]);
            }
            $this->stats['flushes']++;
        }
        
        return $success;
    }
    
    /**
     * Flush all cache
     * 
     * @param array $options Cache options
     * @return bool Success status
     */
    public function flush_all(array $options = []): bool {
        $backend = $options['backend'] ?? $this->default_backend;
        
        if (!isset($this->backends[$backend])) {
            return false;
        }
        
        $result = $this->backends[$backend]->flush();
        
        if ($result) {
            $this->groups = [];
            $this->tags = [];
            $this->stats['flushes']++;
        }
        
        return $result;
    }
    
    /**
     * Get cache statistics
     * 
     * @return array Cache statistics
     */
    public function get_statistics(): array {
        $total_requests = $this->stats['hits'] + $this->stats['misses'];
        $hit_ratio = $total_requests > 0 ? ($this->stats['hits'] / $total_requests) * 100 : 0;
        
        return array_merge($this->stats, [
            'hit_ratio' => round($hit_ratio, 2),
            'total_requests' => $total_requests,
            'backends' => array_keys($this->backends),
            'groups' => array_keys($this->groups),
            'tags' => array_keys($this->tags),
        ]);
    }
    
    /**
     * Warm cache for specific keys
     * 
     * @param array $keys_to_warm Keys with their regeneration callbacks
     * @param array $options Warming options
     * @return array Warming results
     */
    public function warm_cache(array $keys_to_warm, array $options = []): array {
        $results = [];
        
        foreach ($keys_to_warm as $key => $callback) {
            try {
                $value = call_user_func($callback);
                $this->set($key, $value, null, $options);
                $results[$key] = true;
            } catch (Exception $e) {
                $results[$key] = false;
                error_log("Cache warming failed for key {$key}: " . $e->getMessage());
            }
        }
        
        return $results;
    }
    
    /**
     * Get or set cache value (cache-aside pattern)
     * 
     * @param string $key Cache key
     * @param callable $callback Callback to generate value if not cached
     * @param int $expiration Expiration time in seconds
     * @param array $options Cache options
     * @return mixed Cached or generated value
     */
    public function remember(string $key, callable $callback, int $expiration = null, array $options = []) {
        $value = $this->get($key, null, $options);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = call_user_func($callback);
        $this->set($key, $value, $expiration, $options);
        
        return $value;
    }
    
    /**
     * Initialize cache backends
     * 
     * @return void
     */
    private function init_backends(): void {
        // File cache backend (always available)
        $this->backends['file'] = new Enterprise_Theme_File_Cache_Backend($this->config);
        
        // Redis backend (if available)
        if (class_exists('Redis') && ($this->config['redis']['enabled'] ?? false)) {
            try {
                $this->backends['redis'] = new Enterprise_Theme_Redis_Cache_Backend($this->config['redis']);
                $this->default_backend = 'redis';
            } catch (Exception $e) {
                error_log('Redis cache backend initialization failed: ' . $e->getMessage());
            }
        }
        
        // Memcached backend (if available)
        if (class_exists('Memcached') && ($this->config['memcached']['enabled'] ?? false)) {
            try {
                $this->backends['memcached'] = new Enterprise_Theme_Memcached_Cache_Backend($this->config['memcached']);
                if ($this->default_backend === 'file') {
                    $this->default_backend = 'memcached';
                }
            } catch (Exception $e) {
                error_log('Memcached cache backend initialization failed: ' . $e->getMessage());
            }
        }
        
        // Database cache backend
        if ($this->config['database']['enabled'] ?? false) {
            $this->backends['database'] = new Enterprise_Theme_Database_Cache_Backend($this->config['database']);
        }
    }
    
    /**
     * Initialize cache groups
     * 
     * @return void
     */
    private function init_cache_groups(): void {
        $this->groups = [
            'default' => [],
            'posts' => [],
            'terms' => [],
            'users' => [],
            'options' => [],
            'transients' => [],
            'fragments' => [],
            'api' => [],
            'templates' => [],
        ];
    }
    
    /**
     * Build cache key with group prefix
     * 
     * @param string $key Base cache key
     * @param string $group Cache group
     * @return string Full cache key
     */
    private function build_cache_key(string $key, string $group): string {
        $prefix = $this->config['key_prefix'] ?? 'et_';
        return $prefix . $group . ':' . $key;
    }
    
    /**
     * Store cache metadata for group and tag management
     * 
     * @param string $key Cache key
     * @param string $group Cache group
     * @param array $tags Cache tags
     * @return void
     */
    private function store_cache_metadata(string $key, string $group, array $tags): void {
        // Store in group
        if (!isset($this->groups[$group])) {
            $this->groups[$group] = [];
        }
        $this->groups[$group][] = $key;
        
        // Store in tags
        foreach ($tags as $tag) {
            if (!isset($this->tags[$tag])) {
                $this->tags[$tag] = [];
            }
            $this->tags[$tag][] = $key;
        }
    }
    
    /**
     * Delete cache metadata
     * 
     * @param string $key Cache key
     * @return void
     */
    private function delete_cache_metadata(string $key): void {
        // Remove from groups
        foreach ($this->groups as $group => $keys) {
            $index = array_search($key, $keys);
            if ($index !== false) {
                unset($this->groups[$group][$index]);
            }
        }
        
        // Remove from tags
        foreach ($this->tags as $tag => $keys) {
            $index = array_search($key, $keys);
            if ($index !== false) {
                unset($this->tags[$tag][$index]);
            }
        }
    }
    
    /**
     * Compress value
     * 
     * @param mixed $value Value to compress
     * @return string Compressed value
     */
    private function compress($value): string {
        if (function_exists('gzcompress')) {
            return gzcompress(serialize($value));
        }
        
        return serialize($value);
    }
    
    /**
     * Decompress value
     * 
     * @param string $value Compressed value
     * @return mixed Decompressed value
     */
    private function decompress(string $value) {
        if (function_exists('gzuncompress')) {
            $decompressed = @gzuncompress($value);
            if ($decompressed !== false) {
                return unserialize($decompressed);
            }
        }
        
        return unserialize($value);
    }
}

/**
 * File Cache Backend
 */
class Enterprise_Theme_File_Cache_Backend {
    
    /**
     * Cache directory
     * 
     * @var string
     */
    private string $cache_dir;
    
    /**
     * Constructor
     * 
     * @param array $config Backend configuration
     */
    public function __construct(array $config) {
        $this->cache_dir = $config['file']['directory'] ?? WP_CONTENT_DIR . '/cache/enterprise-theme';
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
    }
    
    /**
     * Get cached value
     * 
     * @param string $key Cache key
     * @return mixed Cached value or false
     */
    public function get(string $key) {
        $file_path = $this->get_file_path($key);
        
        if (!file_exists($file_path)) {
            return false;
        }
        
        $content = file_get_contents($file_path);
        if ($content === false) {
            return false;
        }
        
        $data = unserialize($content);
        
        // Check expiration
        if ($data['expiration'] > 0 && time() > $data['expiration']) {
            unlink($file_path);
            return false;
        }
        
        return $data['value'];
    }
    
    /**
     * Set cached value
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $expiration Expiration time in seconds
     * @return bool Success status
     */
    public function set(string $key, $value, int $expiration): bool {
        $file_path = $this->get_file_path($key);
        $data = [
            'value' => $value,
            'expiration' => $expiration > 0 ? time() + $expiration : 0,
            'created' => time(),
        ];
        
        $content = serialize($data);
        return file_put_contents($file_path, $content, LOCK_EX) !== false;
    }
    
    /**
     * Delete cached value
     * 
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete(string $key): bool {
        $file_path = $this->get_file_path($key);
        
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
        
        return true;
    }
    
    /**
     * Flush all cache
     * 
     * @return bool Success status
     */
    public function flush(): bool {
        $files = glob($this->cache_dir . '/*');
        $success = true;
        
        foreach ($files as $file) {
            if (is_file($file) && !unlink($file)) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    /**
     * Get file path for cache key
     * 
     * @param string $key Cache key
     * @return string File path
     */
    private function get_file_path(string $key): string {
        $hash = md5($key);
        $subdir = substr($hash, 0, 2);
        $dir = $this->cache_dir . '/' . $subdir;
        
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }
        
        return $dir . '/' . $hash . '.cache';
    }
}
