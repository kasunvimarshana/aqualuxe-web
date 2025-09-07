<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for managing Services & Consultations.
 *
 * Handles the creation of the 'service' custom post type and associated taxonomies.
 */
class ConsultationService
{
    const CPT_SLUG = 'service';
    const TAXONOMY_SLUG = 'service_category';

    public function __construct()
    {
        // Registration is hooked into 'init' from the module class.
    }

    /**
     * Register the Custom Post Type and Taxonomy.
     */
    public function register_cpt_and_taxonomy(): void
    {
        $this->register_taxonomy();
        $this->register_cpt();
    }

    /**
     * Register the 'service' Custom Post Type.
     */
    private function register_cpt(): void
    {
        $labels = [
            'name'                  => _x('Services', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Service', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Services', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Service', 'aqualuxe'),
            'new_item'              => __('New Service', 'aqualuxe'),
            'edit_item'             => __('Edit Service', 'aqualuxe'),
            'view_item'             => __('View Service', 'aqualuxe'),
            'all_items'             => __('All Services', 'aqualuxe'),
            'search_items'          => __('Search Services', 'aqualuxe'),
            'not_found'             => __('No services found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No services found in Trash.', 'aqualuxe'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'services'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 26,
            'menu_icon'          => 'dashicons-clipboard',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'taxonomies'         => [self::TAXONOMY_SLUG],
        ];

        \register_post_type(self::CPT_SLUG, $args);
    }

    /**
     * Register the 'service_category' taxonomy.
     */
    private function register_taxonomy(): void
    {
        $labels = [
            'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Service Categories', 'aqualuxe'),
            'all_items'         => __('All Service Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Service Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Service Category', 'aqualuxe'),
            'update_item'       => __('Update Service Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
            'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'service-category'],
        ];

        \register_taxonomy(self::TAXONOMY_SLUG, [self::CPT_SLUG], $args);
    }
}
