<?php
/**
 * Customizer Importer
 *
 * @package AquaLuxe
 * @subpackage Importer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer Importer class
 */
class AquaLuxe_Customizer_Importer {
    /**
     * Import customizer settings from a JSON file
     *
     * @param string $file Path to the JSON file.
     * @return array Results of the import.
     */
    public function import($file) {
        $results = array();
        $data = $this->get_customizer_data($file);

        if (is_wp_error($data)) {
            return array(
                'success' => false,
                'message' => $data->get_error_message(),
            );
        }

        // Import customizer settings
        $this->import_customizer_settings($data);

        // Return results
        return array(
            'success' => true,
            'message' => __('Customizer settings imported successfully', 'aqualuxe'),
        );
    }

    /**
     * Get customizer data from a JSON file
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error
     */
    private function get_customizer_data($file) {
        // Check if file exists
        if (!file_exists($file)) {
            return new WP_Error('file_not_found', __('Customizer data file not found', 'aqualuxe'));
        }

        // Get file contents
        $data = file_get_contents($file);
        if (empty($data)) {
            return new WP_Error('empty_file', __('Customizer data file is empty', 'aqualuxe'));
        }

        // Decode the JSON data
        $data = json_decode($data, true);
        if (empty($data) || !is_array($data)) {
            return new WP_Error('invalid_json', __('Customizer data is not valid JSON', 'aqualuxe'));
        }

        return $data;
    }

    /**
     * Import customizer settings
     *
     * @param array $data Customizer settings data.
     */
    private function import_customizer_settings($data) {
        // Import theme mods
        if (isset($data['theme_mods']) && is_array($data['theme_mods'])) {
            foreach ($data['theme_mods'] as $key => $value) {
                set_theme_mod($key, $value);
            }
        }

        // Import custom options
        if (isset($data['options']) && is_array($data['options'])) {
            foreach ($data['options'] as $key => $value) {
                update_option($key, $value);
            }
        }

        // Import nav menu locations
        if (isset($data['nav_menu_locations']) && is_array($data['nav_menu_locations'])) {
            $nav_menus = wp_get_nav_menus();
            $menu_locations = get_theme_mod('nav_menu_locations');

            foreach ($data['nav_menu_locations'] as $location => $menu_id) {
                // Find the menu by name
                foreach ($nav_menus as $menu) {
                    if ($menu->name === $menu_id) {
                        $menu_locations[$location] = $menu->term_id;
                    }
                }
            }

            // Set menu locations
            set_theme_mod('nav_menu_locations', $menu_locations);
        }

        // Import custom CSS
        if (isset($data['custom_css']) && !empty($data['custom_css'])) {
            wp_update_custom_css_post($data['custom_css']);
        }
    }
}