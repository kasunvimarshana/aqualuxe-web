<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for managing Franchise & Licensing opportunities.
 *
 * Handles the creation of the 'franchise' custom post type.
 */
class FranchiseService
{
    const CPT_SLUG = 'franchise';

    public function __construct()
    {
        // Registration is hooked into 'init' from the module class.
    }

    /**
     * Register the 'franchise' Custom Post Type.
     */
    public function register_cpt(): void
    {
        $labels = [
            'name'                  => _x('Franchise Opportunities', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Franchise Opportunity', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Franchising', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Franchise Opportunity', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Franchise Opportunity', 'aqualuxe'),
            'new_item'              => __('New Franchise Opportunity', 'aqualuxe'),
            'edit_item'             => __('Edit Franchise Opportunity', 'aqualuxe'),
            'view_item'             => __('View Franchise Opportunity', 'aqualuxe'),
            'all_items'             => __('All Franchise Opportunities', 'aqualuxe'),
            'search_items'          => __('Search Franchise Opportunities', 'aqualuxe'),
            'not_found'             => __('No franchise opportunities found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No franchise opportunities found in Trash.', 'aqualuxe'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'franchise-opportunities'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 27,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        ];

        \register_post_type(self::CPT_SLUG, $args);
    }
}
