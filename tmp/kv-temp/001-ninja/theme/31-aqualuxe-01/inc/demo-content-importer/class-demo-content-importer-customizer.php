<?php
/**
 * Demo Content Importer Customizer
 *
 * Handles importing customizer settings.
 *
 * @package DemoContentImporter
 * @subpackage Customizer
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Customizer Class
 */
class Demo_Content_Importer_Customizer {

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
    }

    /**
     * Import customizer settings from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Results of the import.
     */
    public function import($file) {
        $this->logger->info('Starting customizer settings import');
        
        // Get customizer data
        $data = $this->get_customizer_data($file);
        
        if (is_wp_error($data)) {
            $this->logger->error('Failed to get customizer data: ' . $data->get_error_message());
            return $data;
        }
        
        // Import customizer settings
        $result = $this->import_customizer_settings($data);
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to import customizer settings: ' . $result->get_error_message());
            return $result;
        }
        
        $this->logger->success('Customizer settings imported successfully');
        
        return array(
            'success' => true,
            'message' => __('Customizer settings imported successfully', 'demo-content-importer'),
        );
    }

    /**
     * Get customizer data from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Customizer data or error.
     */
    private function get_customizer_data($file) {
        // Check if file exists
        if (!file_exists($file)) {
            return new WP_Error(
                'file_not_found',
                sprintf(__('Customizer data file not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Get file contents
        $data = file_get_contents($file);
        
        if (empty($data)) {
            return new WP_Error(
                'empty_file',
                sprintf(__('Customizer data file is empty: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Decode the JSON data
        $data = json_decode($data, true);
        
        if (empty($data) || !is_array($data)) {
            return new WP_Error(
                'invalid_json',
                sprintf(__('Customizer data is not valid JSON: %s', 'demo-content-importer'), $file)
            );
        }
        
        return $data;
    }

    /**
     * Import customizer settings.
     *
     * @param array $data Customizer settings data.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function import_customizer_settings($data) {
        // Import theme mods
        if (isset($data['theme_mods']) && is_array($data['theme_mods'])) {
            $this->logger->info('Importing theme mods');
            
            foreach ($data['theme_mods'] as $key => $value) {
                // Skip certain theme mods that should not be imported
                if (in_array($key, array('nav_menu_locations'), true)) {
                    continue;
                }
                
                // Handle image URLs
                if (is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|webp)/i', $value)) {
                    $value = $this->handle_image_url($value);
                }
                
                set_theme_mod($key, $value);
                $this->logger->info(sprintf('Imported theme mod: %s', $key));
            }
        }
        
        // Import custom options
        if (isset($data['options']) && is_array($data['options'])) {
            $this->logger->info('Importing custom options');
            
            foreach ($data['options'] as $key => $value) {
                update_option($key, $value);
                $this->logger->info(sprintf('Imported option: %s', $key));
            }
        }
        
        // Import nav menu locations
        if (isset($data['nav_menu_locations']) && is_array($data['nav_menu_locations'])) {
            $this->logger->info('Importing nav menu locations');
            
            $nav_menus = wp_get_nav_menus();
            $menu_locations = get_theme_mod('nav_menu_locations', array());
            
            foreach ($data['nav_menu_locations'] as $location => $menu_name) {
                // Find the menu by name
                foreach ($nav_menus as $menu) {
                    if ($menu->name === $menu_name) {
                        $menu_locations[$location] = $menu->term_id;
                        $this->logger->info(sprintf('Assigned menu "%s" to location "%s"', $menu_name, $location));
                        break;
                    }
                }
            }
            
            // Save menu locations
            set_theme_mod('nav_menu_locations', $menu_locations);
        }
        
        return true;
    }

    /**
     * Handle image URL.
     *
     * @param string $url Image URL.
     * @return string Updated image URL.
     */
    private function handle_image_url($url) {
        // If URL is already a local URL, return it
        if (strpos($url, site_url()) === 0) {
            return $url;
        }
        
        // If URL is a placeholder, return it
        if (strpos($url, 'placeholder.com') !== false) {
            return $url;
        }
        
        // If URL is a demo URL, try to download it
        $media_importer = new Demo_Content_Importer_Media();
        $attachment_id = $media_importer->import_external_image($url);
        
        if (is_wp_error($attachment_id)) {
            $this->logger->warning(sprintf('Failed to import image: %s - %s', $url, $attachment_id->get_error_message()));
            return $url;
        }
        
        $new_url = wp_get_attachment_url($attachment_id);
        $this->logger->info(sprintf('Imported image: %s -> %s', $url, $new_url));
        
        return $new_url;
    }
}