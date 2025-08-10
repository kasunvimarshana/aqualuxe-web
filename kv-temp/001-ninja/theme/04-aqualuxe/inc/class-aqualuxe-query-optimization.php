<?php
/**
 * AquaLuxe Query Optimization Class
 *
 * Handles database query optimization for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Query Optimization Class
 */
class AquaLuxe_Query_Optimization {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Query_Optimization
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Query_Optimization
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
        // Optimize fish species queries
        add_action('pre_get_posts', array($this, 'optimize_fish_species_queries'), 10);
        
        // Optimize taxonomy queries
        add_filter('terms_clauses', array($this, 'optimize_taxonomy_queries'), 10, 3);
        
        // Optimize meta queries
        add_filter('get_meta_sql', array($this, 'optimize_meta_queries'), 10, 6);
        
        // Optimize compatibility checker queries
        add_filter('aqualuxe_compatibility_query_args', array($this, 'optimize_compatibility_queries'), 10);
        
        // Optimize related fish species queries
        add_filter('aqualuxe_related_fish_query_args', array($this, 'optimize_related_fish_queries'), 10, 2);
        
        // Batch load fish species metadata
        add_action('wp', array($this, 'batch_load_fish_metadata'));
    }

    /**
     * Optimize fish species queries
     * 
     * @param WP_Query $query The query object
     */
    public function optimize_fish_species_queries($query) {
        // Only modify fish species queries
        if (!is_admin() && ($query->is_post_type_archive('fish_species') || $query->is_tax('fish_category') || $query->is_tax('fish_family') || $query->is_tax('fish_origin') || $query->is_tax('care_level'))) {
            // Set posts per page to avoid excessive queries
            $query->set('posts_per_page', 24);
            
            // Only get the fields we need
            if (!$query->is_main_query()) {
                $query->set('fields', 'ids');
            }
            
            // Optimize meta queries
            $meta_query = $query->get('meta_query');
            if (!empty($meta_query)) {
                // Ensure meta queries use proper indexes
                foreach ($meta_query as $key => $meta) {
                    if (is_array($meta) && isset($meta['compare']) && in_array($meta['compare'], array('LIKE', 'NOT LIKE'))) {
                        // LIKE queries are slow, try to avoid them if possible
                        if (isset($meta['value']) && is_string($meta['value']) && strlen($meta['value']) > 3) {
                            // For longer strings, use = instead of LIKE if it's an exact match search
                            if (strpos($meta['value'], '%') === false) {
                                $meta_query[$key]['compare'] = '=';
                            }
                        }
                    }
                }
                $query->set('meta_query', $meta_query);
            }
            
            // Add specific fields to orderby to improve query performance
            $orderby = $query->get('orderby');
            if ('title' === $orderby) {
                $query->set('orderby', array('title' => 'ASC', 'date' => 'DESC'));
            }
        }
    }

    /**
     * Optimize taxonomy queries
     * 
     * @param array $clauses Query clauses
     * @param array $taxonomies Taxonomies
     * @param array $args Query arguments
     * @return array Modified query clauses
     */
    public function optimize_taxonomy_queries($clauses, $taxonomies, $args) {
        // Only optimize fish-related taxonomies
        $fish_taxonomies = array('fish_category', 'fish_family', 'fish_origin', 'care_level');
        $is_fish_taxonomy = false;
        
        foreach ($taxonomies as $taxonomy) {
            if (in_array($taxonomy, $fish_taxonomies)) {
                $is_fish_taxonomy = true;
                break;
            }
        }
        
        if ($is_fish_taxonomy) {
            global $wpdb;
            
            // Add index hint if available
            if (isset($clauses['join']) && strpos($clauses['join'], $wpdb->term_relationships) !== false) {
                $clauses['join'] = str_replace(
                    "JOIN {$wpdb->term_relationships}",
                    "JOIN {$wpdb->term_relationships} USE INDEX (term_taxonomy_id)",
                    $clauses['join']
                );
            }
            
            // Limit fields if we're just counting
            if (isset($args['fields']) && $args['fields'] === 'count') {
                $clauses['fields'] = 'COUNT(DISTINCT t.term_id)';
            }
        }
        
        return $clauses;
    }

    /**
     * Optimize meta queries
     * 
     * @param array $sql Meta query SQL
     * @param array $queries Meta queries
     * @param string $type Type of meta
     * @param string $primary_table Primary table
     * @param string $primary_id_column Primary ID column
     * @param object $context Query context
     * @return array Modified meta query SQL
     */
    public function optimize_meta_queries($sql, $queries, $type, $primary_table, $primary_id_column, $context) {
        // Only optimize post meta queries
        if ($type !== 'post') {
            return $sql;
        }
        
        // Check if this is a fish species query
        if (!$context || !isset($context->query_vars['post_type']) || $context->query_vars['post_type'] !== 'fish_species') {
            return $sql;
        }
        
        // Add index hints to meta queries
        if (!empty($sql['join'])) {
            global $wpdb;
            
            // Replace meta joins with index hints
            $sql['join'] = preg_replace_callback(
                '/JOIN\s+' . preg_quote($wpdb->postmeta, '/') . '\s+AS\s+([^\s]+)/i',
                function($matches) {
                    return "JOIN {$wpdb->postmeta} USE INDEX (post_id) AS {$matches[1]}";
                },
                $sql['join']
            );
        }
        
        return $sql;
    }

    /**
     * Optimize compatibility checker queries
     * 
     * @param array $args Query arguments
     * @return array Modified query arguments
     */
    public function optimize_compatibility_queries($args) {
        // Set specific fields to retrieve
        $args['fields'] = 'ids';
        
        // Limit the number of posts
        if (!isset($args['posts_per_page']) || $args['posts_per_page'] < 0) {
            $args['posts_per_page'] = 100;
        }
        
        // Add cache flag
        $args['cache_results'] = true;
        
        // No need for complex meta queries in the initial fetch
        if (isset($args['meta_query'])) {
            unset($args['meta_query']);
        }
        
        return $args;
    }

    /**
     * Optimize related fish species queries
     * 
     * @param array $args Query arguments
     * @param int $post_id Current post ID
     * @return array Modified query arguments
     */
    public function optimize_related_fish_queries($args, $post_id) {
        // Set specific fields to retrieve
        $args['fields'] = 'ids';
        
        // Ensure we're not getting too many related fish
        $args['posts_per_page'] = min($args['posts_per_page'], 8);
        
        // Add cache flag
        $args['cache_results'] = true;
        
        // Use a simpler orderby
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        
        return $args;
    }

    /**
     * Batch load fish metadata
     */
    public function batch_load_fish_metadata() {
        // Only on fish species pages
        if (!is_singular('fish_species') && !is_post_type_archive('fish_species') && !is_tax('fish_category') && !is_tax('fish_family') && !is_tax('fish_origin') && !is_tax('care_level')) {
            return;
        }
        
        global $wp_query;
        
        // Get all post IDs in the current query
        $post_ids = wp_list_pluck($wp_query->posts, 'ID');
        
        if (empty($post_ids)) {
            return;
        }
        
        // Common meta keys for fish species
        $meta_keys = array(
            '_scientific_name',
            '_adult_size',
            '_lifespan',
            '_min_tank_size',
            '_temperature_min',
            '_temperature_max',
            '_ph_min',
            '_ph_max',
            '_hardness_min',
            '_hardness_max',
            '_diet',
            '_temperament',
            '_swimming_level',
            '_breeding_difficulty'
        );
        
        // Batch load all meta values
        update_meta_cache('post', $post_ids);
        
        // Batch load all terms
        $taxonomies = array('fish_category', 'fish_family', 'fish_origin', 'care_level');
        foreach ($taxonomies as $taxonomy) {
            update_object_term_cache($post_ids, 'fish_species');
        }
    }
}