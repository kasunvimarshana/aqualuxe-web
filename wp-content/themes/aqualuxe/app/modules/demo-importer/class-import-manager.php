<?php

namespace App\Modules\DemoImporter;

use App\Modules\DemoImporter\Importers\ImporterInterface;
use App\Modules\DemoImporter\Util\Logger;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * Class ImportManager
 *
 * Manages the overall import process.
 *
 * @package App\Modules\DemoImporter
 */
class ImportManager
{
    /**
     * @var ImporterInterface[]
     */
    private $importers = [];

    /**
     * ImportManager constructor.
     */
    public function __construct()
    {
        $this->register_importers();
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    /**
     * Register all available importers.
     */
    private function register_importers()
    {
        $importer_classes = [
            Importers\PostImporter::class,
            Importers\TermImporter::class,
            Importers\ProductImporter::class,
            Importers\MediaImporter::class,
            Importers\MenuImporter::class,
            Importers\WidgetImporter::class,
        ];

        foreach ($importer_classes as $class) {
            $importer = new $class();
            $this->importers[$importer->get_name()] = $importer;
        }
    }

    /**
     * Register REST API routes.
     */
    public function register_rest_routes()
    {
        register_rest_route('aqualuxe/v1', '/import', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_import_request'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);

        register_rest_route('aqualuxe/v1', '/import/status', [
            'methods' => 'GET',
            'callback' => [$this, 'handle_status_request'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);

        register_rest_route('aqualuxe/v1', '/import/flush', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_flush_request'],
            'permission_callback' => [$this, 'permissions_check'],
        ]);
    }

    /**
     * Check if the user has permission to perform the action.
     *
     * @return bool
     */
    public function permissions_check()
    {
        return current_user_can('manage_options');
    }

    /**
     * Handle the import request from the admin UI.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function handle_import_request(WP_REST_Request $request)
    {
        $params = $request->get_json_params();

        // Define the default, logical import order.
        $default_steps = [
            'posts',
            'terms',
            'products',
            'menus',
            'widgets',
            // 'media' // Media importer is not fully implemented yet
        ];

        $steps = $params['steps'] ?? $default_steps;

        Logger::clear_log();
        Logger::log('Starting demo import process via REST API.');

        // This would be a background process in a real implementation
        try {
            $this->run_import($steps);
            Logger::log('Demo import process completed successfully.');
            return new WP_REST_Response([
                'message' => 'Import completed successfully.',
                'log' => Logger::get_log()
            ], 200);
        } catch (\Exception $e) {
            Logger::log('Demo import failed: ' . $e->getMessage());
            return new WP_Error('import_failed', $e->getMessage(), [
                'status' => 500,
                'log' => Logger::get_log()
            ]);
        }
    }

    /**
     * Run the import process.
     *
     * @param array $steps
     */
    private function run_import(array $steps)
    {
        foreach ($steps as $step) {
            if (isset($this->importers[$step])) {
                Logger::log("---");
                Logger::log("Running importer: {$step}");
                // The importer is now responsible for loading its own data.
                $this->importers[$step]->import([]);
                Logger::log("Finished importer: {$step}");
            } else {
                Logger::log("Importer '{$step}' not found, skipping.");
            }
        }
    }

    /**
     * Handle the status request.
     *
     * @return WP_REST_Response
     */
    public function handle_status_request()
    {
        // This would return the status of a background import process
        return new WP_REST_Response(['status' => 'idle'], 200);
    }

    /**
     * Handle the flush request.
     *
     * @return WP_REST_Response|WP_Error
     */
    public function handle_flush_request()
    {
        Logger::clear_log();
        Logger::log('Starting database flush process via REST API.');
        try {
            $this->flush_database();
            Logger::log('Database flush completed successfully.');
            return new WP_REST_Response([
                'message' => 'Database flushed successfully.',
                'log' => Logger::get_log()
            ], 200);
        } catch (\Exception $e) {
            Logger::log('Database flush failed: ' . $e->getMessage());
            return new WP_Error('flush_failed', $e->getMessage(), [
                'status' => 500,
                'log' => Logger::get_log()
            ]);
        }
    }

    /**
     * Flush the database of imported content.
     */
    private function flush_database()
    {
        global $wpdb;

        Logger::log('Flushing posts, pages, and custom post types...');
        $post_types_to_delete = [
            'post', 'page', 'attachment', 'product', 'product_variation',
            'service', 'project', 'event', 'team_member', 'testimonial', 'faq'
        ];
        $post_types_placeholder = implode("', '", $post_types_to_delete);
        $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type IN ('{$post_types_placeholder}')");

        if ($post_ids) {
            foreach ($post_ids as $post_id) {
                wp_delete_post($post_id, true);
            }
            Logger::log('Deleted ' . count($post_ids) . ' posts and their related data.');
        } else {
            Logger::log('No posts found to delete.');
        }

        Logger::log('Flushing terms...');
        $taxonomies_to_clear = [
            'category', 'post_tag', 'product_cat', 'product_tag',
            'service-category', 'project-category', 'event-category'
        ];
        $tax_placeholder = implode("', '", $taxonomies_to_clear);
        $term_ids = $wpdb->get_col("SELECT t.term_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('{$tax_placeholder}')");

        if ($term_ids) {
            foreach ($term_ids as $term_id) {
                $term = get_term($term_id);
                if ($term && !is_wp_error($term)) {
                    wp_delete_term($term_id, $term->taxonomy);
                }
            }
            Logger::log('Deleted ' . count($term_ids) . ' terms.');
        } else {
            Logger::log('No terms found to delete.');
        }

        Logger::log('Flushing menus...');
        $menus_to_delete = ['Main Menu', 'Footer Menu'];
        foreach ($menus_to_delete as $menu_name) {
            $menu = wp_get_nav_menu_object($menu_name);
            if ($menu) {
                wp_delete_nav_menu($menu_name);
                Logger::log("Deleted menu '{$menu_name}'.");
            }
        }
        // Unset theme locations
        remove_theme_mod('nav_menu_locations');
        Logger::log('Unset menu theme locations.');


        Logger::log('Flushing widgets...');
        update_option('sidebars_widgets', []);
        Logger::log('Cleared all sidebars of widgets.');

        Logger::log('Flush process complete.');
    }
}
