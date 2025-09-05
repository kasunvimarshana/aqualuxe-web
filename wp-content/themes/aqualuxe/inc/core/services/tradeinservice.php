<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for managing Trade-In requests.
 *
 * Handles the creation of the 'trade_in_request' custom post type
 * and processing of new trade-in submissions.
 */
class TradeInService
{
    const CPT_SLUG = 'trade_in_request';

    public function __construct()
    {
        // The registration is hooked into 'init' from the module class.
    }

    /**
     * Register the Custom Post Type for Trade-In Requests.
     */
    public function register_cpt(): void
    {
        $labels = [
            'name'                  => _x('Trade-In Requests', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Trade-In Request', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Trade-Ins', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Trade-In Request', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Trade-In Request', 'aqualuxe'),
            'new_item'              => __('New Trade-In Request', 'aqualuxe'),
            'edit_item'             => __('Edit Trade-In Request', 'aqualuxe'),
            'view_item'             => __('View Trade-In Request', 'aqualuxe'),
            'all_items'             => __('All Trade-In Requests', 'aqualuxe'),
            'search_items'          => __('Search Trade-In Requests', 'aqualuxe'),
            'parent_item_colon'     => __('Parent Trade-In Requests:', 'aqualuxe'),
            'not_found'             => __('No trade-in requests found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No trade-in requests found in Trash.', 'aqualuxe'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false, // Not publicly queryable
            'publicly_queryable' => false,
            'show_ui'            => true,  // Show in admin
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-cart',
            'supports'           => ['title', 'editor', 'author', 'custom-fields'],
        ];

        \register_post_type(self::CPT_SLUG, $args);
    }

    /**
     * Create a new trade-in request from form data.
     *
     * @param array $data The sanitized form data.
     * @return int|\WP_Error The new post ID on success, or a WP_Error object on failure.
     */
    public function create_request(array $data)
    {
        $user = \wp_get_current_user();

        $title = sprintf(
            'Trade-In Request from %s for %s',
            $data['name'] ?? 'Guest',
            $data['product_name'] ?? 'Unknown Product'
        );

        $content = "Product Name: {$data['product_name']}\n"
                 . "Condition: {$data['condition']}\n"
                 . "Message: {$data['message']}";

        $post_data = [
            'post_type'    => self::CPT_SLUG,
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'pending', // Start as pending review
            'post_author'  => $user->ID,
        ];

        $post_id = \wp_insert_post($post_data, true);

        if (\is_wp_error($post_id)) {
            return $post_id;
        }

        // Store form fields as meta data for easier access
        \add_post_meta($post_id, '_trade_in_name', $data['name']);
        \add_post_meta($post_id, '_trade_in_email', $data['email']);
        \add_post_meta($post_id, '_trade_in_product_name', $data['product_name']);
        \add_post_meta($post_id, '_trade_in_condition', $data['condition']);

        return $post_id;
    }
}
