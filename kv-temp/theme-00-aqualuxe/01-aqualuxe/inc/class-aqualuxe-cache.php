
<?php
/**
 * AquaLuxe Cache Class
 *
 * Handles caching configuration for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Cache Class
 */
class AquaLuxe_Cache {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Cache
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Cache
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Set browser caching headers
        add_action('wp_head', array($this, 'set_cache_control_headers'), 1);
        
        // Add cache-related HTTP headers
        add_action('send_headers', array($this, 'add_cache_headers'));
        
        // Cache fish species data
        add_filter('get_post_metadata', array($this, 'cache_fish_species_metadata'), 10, 4);
        
        // Cache compatibility results
        add_filter('aqualuxe_compatibility_results', array($this, 'cache_compatibility_results'), 10, 2);
        
        // Cache calculator results
        add_filter('aqualuxe_calculator_results', array($this, 'cache_calculator_results'), 10, 2);
        
        // Flush cache when fish species is updated
        add_action('save_post_fish_species', array($this, 'flush_fish_species_cache'), 10, 3);
        
        // Add fragment caching for fish species lists
        add_filter('post_class', array($this, 'add_cache_fragment_class'), 10, 3);
        
        // Register cache cleanup on theme activation
        add_action('after_switch_theme', array($this, 'schedule_cache_cleanup'));
        
        // Register daily cache cleanup
        add_action('aqualuxe_daily_cache_cleanup', array($this, 'cleanup_expired_caches'));
    }

    /**
     * Set cache control headers
     */
    public function set_cache_control_headers() {
        // Don't cache if user is logged in or on admin pages
        if (is_user_logged_in() || is_admin()) {
            return;
        }
        
        // Set different cache times based on content type
        if (is_front_page()) {
            // Cache homepage for 1 hour
            $max_age = 3600;
        } elseif (is_singular('fish_species')) {
            // Cache fish species pages for 12 hours
            $max_age = 43200;
        } elseif (is_archive() || is_tax()) {
            // Cache archive pages for 6 hours
            $max_age = 21600;
        } else {
            // Default cache time - 4 hours
            $max_age = 14400;
        }
        
        // Allow cache time to be filtered
        $max_age = apply_filters('aqualuxe_cache_max_age', $max_age);
        
        // Output cache control header
        echo '<meta http-equiv="Cache-Control" content="public, max-age=' . esc_attr($max_age) . '" />' . "\
";
    }

    /**
     * Add cache headers
     */
    public function add_cache_headers() {
        // Don't set headers if user is logged in or on admin pages
        if (is_user_logged_in() || is_admin()) {
            return;
        }
        
        // Set different cache times based on content type
        if (is_front_page()) {
            // Cache homepage for 1 hour
            $max_age = 3600;
        } elseif (is_singular('fish_species')) {
            // Cache fish species pages for 12 hours
            $max_age = 43200;
        } elseif (is_archive() || is_tax()) {
            // Cache archive pages for 6 hours
            $max_age = 21600;
        } else {
            // Default cache time - 4 hours
            $max_age = 14400;
        }
        
        // Allow cache time to be filtered
        $max_age = apply_filters('aqualuxe_cache_max_age', $max_age);
        
        // Set cache control header
        header('Cache-Control: public, max-age=' . $max_age);
        
        // Set expires header
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $max_age) . ' GMT');
        
        // Set ETag header for better caching
        $request_uri = md5($_SERVER['REQUEST_URI']);
        header('ETag: "' . $request_uri . '"');
    }

    /**
     * Cache fish species metadata
     * 
     * @param mixed $value The value to return
     * @param int $object_id The object ID
     * @param string $meta_key The meta key
     * @param bool $single Whether to return a single value
     * @return mixed The cached value or original value
     */
    public function cache_fish_species_metadata($value, $object_id, $meta_key, $single) {
        // Only cache fish species metadata
        if (get_post_type($object_id) !== 'fish_species') {
            return $value;
        }
        
        // Skip for certain meta keys
        $skip_keys = array('_edit_lock', '_edit_last');
        if (in_array($meta_key, $skip_keys)) {
            return $value;
        }
        
        // Check if we have a cached value
        $cache_key = 'aqualuxe_fish_meta_' . $object_id . '_' . $meta_key;
        $cached_value = wp_cache_get($cache_key);
        
        if (false !== $cached_value) {
            return $single ? $cached_value : array($cached_value);
        }
        
        // If not in cache, let WordPress get the value
        return $value;
    }

    /**
     * Cache compatibility results
     * 
     * @param array $results The compatibility results
     * @param array $fish_ids The fish IDs being compared
     * @return array The cached or original results
     */
    public function cache_compatibility_results($results, $fish_ids) {
        // Sort fish IDs to ensure consistent cache key
        sort($fish_ids);
        
        // Create cache key
        $cache_key = 'aqualuxe_compatibility_' . md5(implode('_', $fish_ids));
        
        // Check if we have cached results
        $cached_results = wp_cache_get($cache_key);
        
        if (false !== $cached_results) {
            return $cached_results;
        }
        
        // If not in cache, cache the results for 24 hours
        wp_cache_set($cache_key, $results, '', 86400);
        
        return $results;
    }

    /**
     * Cache calculator results
     * 
     * @param array $results The calculator results
     * @param array $params The calculation parameters
     * @return array The cached or original results
     */
    public function cache_calculator_results($results, $params) {
        // Create cache key
        $cache_key = 'aqualuxe_calculator_' . md5(serialize($params));
        
        // Check if we have cached results
        $cached_results = wp_cache_get($cache_key);
        
        if (false !== $cached_results) {
            return $cached_results;
        }
        
        // If not in cache, cache the results for 24 hours
        wp_cache_set($cache_key, $results, '', 86400);
        
        return $results;
    }

    /**
     * Flush fish species cache when updated
     * 
     * @param int $post_id The post ID
     * @param WP_Post $post The post object
     * @param bool $update Whether this is an update
     */
    public function flush_fish_species_cache($post_id, $post, $update) {
        // Skip if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Skip if this is not a fish species
        if ('fish_species' !== $post->post_type) {
            return;
        }
        
        // Get all meta keys for this fish species
        $meta_keys = get_post_custom_keys($post_id);
        
        if ($meta_keys) {
            foreach ($meta_keys as $meta_key) {
                // Delete the cache for each meta key
                wp_cache_delete('aqualuxe_fish_meta_' . $post_id . '_' . $meta_key);
            }
        }
        
        // Delete compatibility caches involving this fish
        $this->delete_compatibility_caches($post_id);
    }

    /**
     * Delete compatibility caches involving a specific fish
     * 
     * @param int $fish_id The fish ID
     */
    private function delete_compatibility_caches($fish_id) {
        global $wpdb;
        
        // Get all fish species
        $fish_species = get_posts(array(
            'post_type' => 'fish_species',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));
        
        // For each fish species, delete cache with the current fish
        foreach ($fish_species as $other_fish_id) {
            $ids = array($fish_id, $other_fish_id);
            sort($ids);
            
            $cache_key = 'aqualuxe_compatibility_' . md5(implode('_', $ids));
            wp_cache_delete($cache_key);
        }
    }

    /**
     * Add cache fragment class to posts
     * 
     * @param array $classes An array of post classes
     * @param array $class Additional classes
     * @param int $post_id The post ID
     * @return array Modified array of post classes
     */
    public function add_cache_fragment_class($classes, $class, $post_id) {
        // Only add to fish species
        if (get_post_type($post_id) === 'fish_species') {
            $classes[] = 'cache-fragment';
            $classes[] = 'cache-fragment-' . $post_id;
        }
        
        return $classes;
    }

    /**
     * Schedule daily cache cleanup
     */
    public function schedule_cache_cleanup() {
        if (!wp_next_scheduled('aqualuxe_daily_cache_cleanup')) {
            wp_schedule_event(time(), 'daily', 'aqualuxe_daily_cache_cleanup');
        }
    }

    /**
     * Cleanup expired caches
     */
    public function cleanup_expired_caches() {
        // This is a placeholder function
        // In a real implementation, this would clean up any custom cache files or database entries
        // For now, we're relying on WordPress's built-in cache expiration
    }
}
