<?php

namespace App\Modules\DemoImporter\Importers;

use App\Modules\DemoImporter\Util\Logger;
use App\Modules\DemoImporter\Util\WpImporterUtil;

/**
 * Class PostImporter
 *
 * Imports posts, pages, and custom post types.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class PostImporter implements ImporterInterface
{
    /**
     * @var array
     */
    private $post_types_to_import = [
        'pages',
        'services',
        'projects',
        'events',
        'team-members',
        'testimonials',
        'faqs',
    ];

    public function import(array $data = [])
    {
        Logger::log('---');
        Logger::log('Starting post import...');

        foreach ($this->post_types_to_import as $post_type_file) {
            $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/{$post_type_file}.json";
            if (!file_exists($file_path)) {
                Logger::log("Data file not found for '{$post_type_file}', skipping.");
                continue;
            }

            $posts = json_decode(file_get_contents($file_path), true);
            if (empty($posts)) {
                Logger::log("No posts found for '{$post_type_file}', skipping.");
                continue;
            }

            Logger::log("Importing " . count($posts) . " items for '{$post_type_file}'.");

            foreach ($posts as $post_data) {
                $this->import_post($post_data);
            }
        }

        Logger::log('Post import finished.');
        Logger::log('---');
    }

    /**
     * @param array $post_data
     */
    private function import_post(array $post_data)
    {
        $post_title = $post_data['post_title'] ?? 'Untitled';

        if (WpImporterUtil::post_exists($post_title)) {
            Logger::log("Post '{$post_title}' already exists. Skipping.");
            return;
        }

        $post_args = [
            'post_title'   => $post_title,
            'post_content' => $post_data['post_content'] ?? '',
            'post_type'    => $post_data['post_type'] ?? 'post',
            'post_status'  => $post_data['post_status'] ?? 'publish',
            'post_author'  => WpImporterUtil::get_or_create_author()->ID,
        ];

        if (isset($post_data['post_excerpt'])) {
            $post_args['post_excerpt'] = $post_data['post_excerpt'];
        }

        $post_id = wp_insert_post($post_args, true);

        if (is_wp_error($post_id)) {
            Logger::log("Failed to import post '{$post_title}'. Error: " . $post_id->get_error_message());
            return;
        }

        Logger::log("Successfully imported post '{$post_title}' (ID: {$post_id}).");

        // Handle meta fields
        if (isset($post_data['meta_input']) && is_array($post_data['meta_input'])) {
            foreach ($post_data['meta_input'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
            Logger::log("...with meta data.");
        }

        // Handle featured image (placeholder logic)
        if (isset($post_data['featured_image'])) {
            WpImporterUtil::set_featured_image($post_id, $post_data['featured_image']);
        }
    }

    public function get_name(): string
    {
        return 'posts';
    }

    public function get_post_types_to_import(): array
    {
        return $this->post_types_to_import;
    }
}
