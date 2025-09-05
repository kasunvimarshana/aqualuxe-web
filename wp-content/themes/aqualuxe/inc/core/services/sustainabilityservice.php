<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for managing R&D / Sustainability Initiatives.
 *
 * Handles the creation of the 'sustainability' custom post type and its taxonomies.
 */
class SustainabilityService
{
    const CPT_SLUG = 'sustainability';
    const TAXONOMY_SLUG = 'sustainability_category';

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
     * Register the 'sustainability' Custom Post Type.
     */
    private function register_cpt(): void
    {
        $labels = [
            'name'                  => _x('Sustainability Initiatives', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Sustainability Initiative', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Sustainability', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Initiative', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Initiative', 'aqualuxe'),
            'new_item'              => __('New Initiative', 'aqualuxe'),
            'edit_item'             => __('Edit Initiative', 'aqualuxe'),
            'view_item'             => __('View Initiative', 'aqualuxe'),
            'all_items'             => __('All Initiatives', 'aqualuxe'),
            'search_items'          => __('Search Initiatives', 'aqualuxe'),
            'not_found'             => __('No initiatives found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No initiatives found in Trash.', 'aqualuxe'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'sustainability'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 28,
            'menu_icon'          => 'dashicons-leaf',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'taxonomies'         => [self::TAXONOMY_SLUG],
        ];

        \register_post_type(self::CPT_SLUG, $args);
    }

    /**
     * Register the 'sustainability_category' taxonomy.
     */
    private function register_taxonomy(): void
    {
        $labels = [
            'name'              => _x('Initiative Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Initiative Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Categories', 'aqualuxe'),
            'all_items'         => __('All Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Category', 'aqualuxe'),
            'update_item'       => __('Update Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Category', 'aqualuxe'),
            'new_item_name'     => __('New Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'sustainability-category'],
        ];

        \register_taxonomy(self::TAXONOMY_SLUG, [self::CPT_SLUG], $args);
    }
}
