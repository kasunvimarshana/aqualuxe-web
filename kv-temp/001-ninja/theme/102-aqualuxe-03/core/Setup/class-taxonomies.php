<?php
/**
 * Custom Taxonomies Registration
 *
 * Registers all custom taxonomies for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Taxonomies Class
 */
class AquaLuxe_Taxonomies {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service categories
        $this->register_service_categories();
        
        // Service tags
        $this->register_service_tags();
        
        // Event categories
        $this->register_event_categories();
        
        // Event tags
        $this->register_event_tags();
        
        // Portfolio categories
        $this->register_portfolio_categories();
        
        // Portfolio tags
        $this->register_portfolio_tags();
        
        // Team departments
        $this->register_team_departments();
        
        // FAQ categories
        $this->register_faq_categories();
        
        // Partner types
        $this->register_partner_types();
    }
    
    /**
     * Register Service Categories taxonomy
     */
    private function register_service_categories() {
        $labels = array(
            'name'                       => _x('Service Categories', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Service Category', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Categories', 'aqualuxe'),
            'all_items'                  => __('All Categories', 'aqualuxe'),
            'parent_item'                => __('Parent Category', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
            'new_item_name'              => __('New Category Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Category', 'aqualuxe'),
            'edit_item'                  => __('Edit Category', 'aqualuxe'),
            'update_item'                => __('Update Category', 'aqualuxe'),
            'view_item'                  => __('View Category', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Categories', 'aqualuxe'),
            'search_items'               => __('Search Categories', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No categories', 'aqualuxe'),
            'items_list'                 => __('Categories list', 'aqualuxe'),
            'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'service-category'),
        );
        
        register_taxonomy('service_category', array('service'), $args);
    }
    
    /**
     * Register Service Tags taxonomy
     */
    private function register_service_tags() {
        $labels = array(
            'name'                       => _x('Service Tags', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Service Tag', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Tags', 'aqualuxe'),
            'all_items'                  => __('All Tags', 'aqualuxe'),
            'new_item_name'              => __('New Tag Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Tag', 'aqualuxe'),
            'edit_item'                  => __('Edit Tag', 'aqualuxe'),
            'update_item'                => __('Update Tag', 'aqualuxe'),
            'view_item'                  => __('View Tag', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Tags', 'aqualuxe'),
            'search_items'               => __('Search Tags', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No tags', 'aqualuxe'),
            'items_list'                 => __('Tags list', 'aqualuxe'),
            'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'service-tag'),
        );
        
        register_taxonomy('service_tag', array('service'), $args);
    }
    
    /**
     * Register Event Categories taxonomy
     */
    private function register_event_categories() {
        $labels = array(
            'name'                       => _x('Event Categories', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Event Category', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Categories', 'aqualuxe'),
            'all_items'                  => __('All Categories', 'aqualuxe'),
            'parent_item'                => __('Parent Category', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
            'new_item_name'              => __('New Category Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Category', 'aqualuxe'),
            'edit_item'                  => __('Edit Category', 'aqualuxe'),
            'update_item'                => __('Update Category', 'aqualuxe'),
            'view_item'                  => __('View Category', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Categories', 'aqualuxe'),
            'search_items'               => __('Search Categories', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No categories', 'aqualuxe'),
            'items_list'                 => __('Categories list', 'aqualuxe'),
            'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'event-category'),
        );
        
        register_taxonomy('event_category', array('event'), $args);
    }
    
    /**
     * Register Event Tags taxonomy
     */
    private function register_event_tags() {
        $labels = array(
            'name'                       => _x('Event Tags', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Event Tag', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Tags', 'aqualuxe'),
            'all_items'                  => __('All Tags', 'aqualuxe'),
            'new_item_name'              => __('New Tag Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Tag', 'aqualuxe'),
            'edit_item'                  => __('Edit Tag', 'aqualuxe'),
            'update_item'                => __('Update Tag', 'aqualuxe'),
            'view_item'                  => __('View Tag', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Tags', 'aqualuxe'),
            'search_items'               => __('Search Tags', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No tags', 'aqualuxe'),
            'items_list'                 => __('Tags list', 'aqualuxe'),
            'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'event-tag'),
        );
        
        register_taxonomy('event_tag', array('event'), $args);
    }
    
    /**
     * Register Portfolio Categories taxonomy
     */
    private function register_portfolio_categories() {
        $labels = array(
            'name'                       => _x('Portfolio Categories', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Portfolio Category', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Categories', 'aqualuxe'),
            'all_items'                  => __('All Categories', 'aqualuxe'),
            'parent_item'                => __('Parent Category', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
            'new_item_name'              => __('New Category Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Category', 'aqualuxe'),
            'edit_item'                  => __('Edit Category', 'aqualuxe'),
            'update_item'                => __('Update Category', 'aqualuxe'),
            'view_item'                  => __('View Category', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Categories', 'aqualuxe'),
            'search_items'               => __('Search Categories', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No categories', 'aqualuxe'),
            'items_list'                 => __('Categories list', 'aqualuxe'),
            'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'portfolio-category'),
        );
        
        register_taxonomy('portfolio_category', array('portfolio'), $args);
    }
    
    /**
     * Register Portfolio Tags taxonomy
     */
    private function register_portfolio_tags() {
        $labels = array(
            'name'                       => _x('Portfolio Tags', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Portfolio Tag', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Tags', 'aqualuxe'),
            'all_items'                  => __('All Tags', 'aqualuxe'),
            'new_item_name'              => __('New Tag Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Tag', 'aqualuxe'),
            'edit_item'                  => __('Edit Tag', 'aqualuxe'),
            'update_item'                => __('Update Tag', 'aqualuxe'),
            'view_item'                  => __('View Tag', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Tags', 'aqualuxe'),
            'search_items'               => __('Search Tags', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No tags', 'aqualuxe'),
            'items_list'                 => __('Tags list', 'aqualuxe'),
            'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'portfolio-tag'),
        );
        
        register_taxonomy('portfolio_tag', array('portfolio'), $args);
    }
    
    /**
     * Register Team Departments taxonomy
     */
    private function register_team_departments() {
        $labels = array(
            'name'                       => _x('Departments', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Department', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Departments', 'aqualuxe'),
            'all_items'                  => __('All Departments', 'aqualuxe'),
            'parent_item'                => __('Parent Department', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Department:', 'aqualuxe'),
            'new_item_name'              => __('New Department Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Department', 'aqualuxe'),
            'edit_item'                  => __('Edit Department', 'aqualuxe'),
            'update_item'                => __('Update Department', 'aqualuxe'),
            'view_item'                  => __('View Department', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate departments with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove departments', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Departments', 'aqualuxe'),
            'search_items'               => __('Search Departments', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No departments', 'aqualuxe'),
            'items_list'                 => __('Departments list', 'aqualuxe'),
            'items_list_navigation'      => __('Departments list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'department'),
        );
        
        register_taxonomy('team_department', array('team'), $args);
    }
    
    /**
     * Register FAQ Categories taxonomy
     */
    private function register_faq_categories() {
        $labels = array(
            'name'                       => _x('FAQ Categories', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('FAQ Category', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Categories', 'aqualuxe'),
            'all_items'                  => __('All Categories', 'aqualuxe'),
            'parent_item'                => __('Parent Category', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
            'new_item_name'              => __('New Category Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Category', 'aqualuxe'),
            'edit_item'                  => __('Edit Category', 'aqualuxe'),
            'update_item'                => __('Update Category', 'aqualuxe'),
            'view_item'                  => __('View Category', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Categories', 'aqualuxe'),
            'search_items'               => __('Search Categories', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No categories', 'aqualuxe'),
            'items_list'                 => __('Categories list', 'aqualuxe'),
            'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'faq-category'),
        );
        
        register_taxonomy('faq_category', array('faq'), $args);
    }
    
    /**
     * Register Partner Types taxonomy
     */
    private function register_partner_types() {
        $labels = array(
            'name'                       => _x('Partner Types', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name'              => _x('Partner Type', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name'                  => __('Partner Types', 'aqualuxe'),
            'all_items'                  => __('All Types', 'aqualuxe'),
            'parent_item'                => __('Parent Type', 'aqualuxe'),
            'parent_item_colon'          => __('Parent Type:', 'aqualuxe'),
            'new_item_name'              => __('New Type Name', 'aqualuxe'),
            'add_new_item'               => __('Add New Type', 'aqualuxe'),
            'edit_item'                  => __('Edit Type', 'aqualuxe'),
            'update_item'                => __('Update Type', 'aqualuxe'),
            'view_item'                  => __('View Type', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate types with commas', 'aqualuxe'),
            'add_or_remove_items'        => __('Add or remove types', 'aqualuxe'),
            'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
            'popular_items'              => __('Popular Types', 'aqualuxe'),
            'search_items'               => __('Search Types', 'aqualuxe'),
            'not_found'                  => __('Not Found', 'aqualuxe'),
            'no_terms'                   => __('No types', 'aqualuxe'),
            'items_list'                 => __('Types list', 'aqualuxe'),
            'items_list_navigation'      => __('Types list navigation', 'aqualuxe'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'partner-type'),
        );
        
        register_taxonomy('partner_type', array('partner'), $args);
    }
}

// Initialize custom taxonomies
new AquaLuxe_Taxonomies();