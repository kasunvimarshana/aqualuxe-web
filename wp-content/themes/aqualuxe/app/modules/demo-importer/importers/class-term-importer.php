<?php

namespace App\Modules\DemoImporter\Importers;

use App\Modules\DemoImporter\Util\Logger;

/**
 * Class TermImporter
 *
 * Imports taxonomies and terms.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class TermImporter implements ImporterInterface
{
    /**
     * @var array
     */
    private $taxonomy_files = [
        'service-categories',
        'project-categories',
        'event-categories',
        'product-taxonomies',
    ];

    public function import(array $data = [])
    {
        Logger::log('---');
        Logger::log('Starting term import...');

        foreach ($this->taxonomy_files as $tax_file) {
            $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/{$tax_file}.json";
            if (!file_exists($file_path)) {
                Logger::log("Data file not found for '{$tax_file}', skipping.");
                continue;
            }

            $terms = json_decode(file_get_contents($file_path), true);
            if (empty($terms)) {
                Logger::log("No terms found for '{$tax_file}', skipping.");
                continue;
            }

            Logger::log("Importing " . count($terms) . " items for '{$tax_file}'.");

            foreach ($terms as $term_data) {
                $this->import_term($term_data);
            }
        }

        // After all terms are imported, re-assign post terms
        $this->reassign_post_terms();


        Logger::log('Term import finished.');
        Logger::log('---');
    }

    /**
     * @param array $term_data
     */
    private function import_term(array $term_data)
    {
        $term_name = $term_data['name'] ?? '';
        $taxonomy = $term_data['taxonomy'] ?? '';

        if (empty($term_name) || empty($taxonomy)) {
            Logger::log("Skipping term import due to missing name or taxonomy.");
            return;
        }

        if (term_exists($term_name, $taxonomy)) {
            Logger::log("Term '{$term_name}' in taxonomy '{$taxonomy}' already exists. Skipping.");
            return;
        }

        $args = [
            'description' => $term_data['description'] ?? '',
            'slug'        => $term_data['slug'] ?? '',
        ];

        if (!empty($term_data['parent'])) {
            $parent_term = get_term_by('slug', $term_data['parent'], $taxonomy);
            if ($parent_term) {
                $args['parent'] = $parent_term->term_id;
            }
        }

        $result = wp_insert_term($term_name, $taxonomy, $args);

        if (is_wp_error($result)) {
            Logger::log("Failed to import term '{$term_name}'. Error: " . $result->get_error_message());
        } else {
            Logger::log("Successfully imported term '{$term_name}' (ID: {$result['term_id']}).");
        }
    }

    private function reassign_post_terms()
    {
        Logger::log('Re-assigning terms to posts...');

        $post_importer = new PostImporter();
        $post_types_to_process = $post_importer->get_post_types_to_import();

        foreach ($post_types_to_process as $post_type_file) {
            $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/{$post_type_file}.json";
            if (!file_exists($file_path)) continue;

            $posts = json_decode(file_get_contents($file_path), true);
            if (empty($posts)) continue;

            foreach ($posts as $post_data) {
                if (!isset($post_data['terms']) || !is_array($post_data['terms'])) {
                    continue;
                }

                $post = get_page_by_title($post_data['post_title'], OBJECT, $post_data['post_type']);
                if (!$post) continue;

                foreach ($post_data['terms'] as $taxonomy => $term_slugs) {
                    wp_set_object_terms($post->ID, $term_slugs, $taxonomy, false);
                    Logger::log("Assigned terms to post '{$post_data['post_title']}'.");
                }
            }
        }
    }


    public function get_name(): string
    {
        return 'terms';
    }
}
