<?php
/**
 * AquaLuxe Widget Importer - Enhanced Version
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Widget Importer Class - Enhanced with better performance and error handling
 */
class AquaLuxe_Widget_Importer {

    /**
     * Available widgets
     *
     * @var array
     */
    private $available_widgets = array();

    /**
     * Sidebar widgets
     *
     * @var array
     */
    private $sidebar_widgets = array();

    /**
     * Results
     *
     * @var array
     */
    private $results = array(
        'success' => array(),
        'errors' => array(),
    );

    /**
     * Constructor
     */
    public function __construct() {
        $this->available_widgets = $this->get_available_widgets();
        $this->sidebar_widgets = get_option('sidebars_widgets');
    }

    /**
     * Get available widgets
     *
     * @return array
     */
    private function get_available_widgets() {
        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;
        $available_widgets = array();

        foreach ($widget_controls as $widget) {
            // No duplicates
            if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
                $available_widgets[$widget['id_base']] = $widget;
            }
        }

        return $available_widgets;
    }

    /**
     * Import widgets
     *
     * @param string $file_path Path to the widgets file
     * @return array Results of the import
     */
    public function import($file_path) {
        // Reset results
        $this->results = array(
            'success' => array(),
            'errors' => array(),
        );

        // Check if file exists
        if (!file_exists($file_path)) {
            $this->results['errors'][] = __('Widgets file not found.', 'aqualuxe');
            return $this->results;
        }

        // Get file contents
        $data = file_get_contents($file_path);

        // Check if file is empty
        if (empty($data)) {
            $this->results['errors'][] = __('Widgets file is empty.', 'aqualuxe');
            return $this->results;
        }

        // Decode the data
        $data = json_decode($data, true);

        // Check if data is valid
        if (empty($data) || !is_array($data)) {
            $this->results['errors'][] = __('Widgets file contains invalid data.', 'aqualuxe');
            return $this->results;
        }

        // Import the widgets
        return $this->import_widgets($data);
    }

    /**
     * Import widgets from data
     *
     * @param array $data Widgets data
     * @return array Results of the import
     */
    private function import_widgets($data) {
        // Get all sidebars
        $sidebars_widgets = get_option('sidebars_widgets');
        
        // Check if sidebars_widgets is valid
        if (!is_array($sidebars_widgets)) {
            $sidebars_widgets = array();
        }

        // Loop through widgets data
        foreach ($data as $sidebar_id => $widgets) {
            // Skip inactive widgets (they will be handled later)
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }

            // Skip if not a sidebar
            if (!array_key_exists($sidebar_id, $sidebars_widgets)) {
                $this->results['errors'][] = sprintf(__('Sidebar "%s" does not exist.', 'aqualuxe'), $sidebar_id);
                continue;
            }

            // Loop through widgets
            foreach ($widgets as $widget_instance_id => $widget) {
                // Get widget base ID
                $id_base = preg_replace('/-[0-9]+$/', '', $widget_instance_id);

                // Check if widget is available
                if (!$this->is_widget_available($id_base)) {
                    $this->results['errors'][] = sprintf(__('Widget "%s" is not available.', 'aqualuxe'), $id_base);
                    continue;
                }

                // Get all instances for this widget
                $instances = get_option('widget_' . $id_base);
                
                // If no instances exist, create an empty array
                if (!is_array($instances)) {
                    $instances = array();
                }

                // Get the widget instance number
                $instance_id = preg_replace('/[a-z0-9_\-]+\-/', '', $widget_instance_id);

                // Add the widget instance
                $instances[$instance_id] = $widget;

                // Save the widget instances
                update_option('widget_' . $id_base, $instances);

                // Add the widget to the sidebar
                if (!in_array($widget_instance_id, $sidebars_widgets[$sidebar_id], true)) {
                    $sidebars_widgets[$sidebar_id][] = $widget_instance_id;
                }

                // Add to success array
                $this->results['success'][] = sprintf(
                    __('Widget "%1$s" imported to "%2$s".', 'aqualuxe'),
                    $id_base,
                    $sidebar_id
                );
            }
        }

        // Handle inactive widgets
        if (isset($data['wp_inactive_widgets']) && is_array($data['wp_inactive_widgets'])) {
            // If no inactive widgets exist, create an empty array
            if (!isset($sidebars_widgets['wp_inactive_widgets'])) {
                $sidebars_widgets['wp_inactive_widgets'] = array();
            }

            // Loop through inactive widgets
            foreach ($data['wp_inactive_widgets'] as $widget_instance_id => $widget) {
                // Get widget base ID
                $id_base = preg_replace('/-[0-9]+$/', '', $widget_instance_id);

                // Check if widget is available
                if (!$this->is_widget_available($id_base)) {
                    $this->results['errors'][] = sprintf(__('Widget "%s" is not available.', 'aqualuxe'), $id_base);
                    continue;
                }

                // Get all instances for this widget
                $instances = get_option('widget_' . $id_base);
                
                // If no instances exist, create an empty array
                if (!is_array($instances)) {
                    $instances = array();
                }

                // Get the widget instance number
                $instance_id = preg_replace('/[a-z0-9_\-]+\-/', '', $widget_instance_id);

                // Add the widget instance
                $instances[$instance_id] = $widget;

                // Save the widget instances
                update_option('widget_' . $id_base, $instances);

                // Add the widget to inactive widgets
                if (!in_array($widget_instance_id, $sidebars_widgets['wp_inactive_widgets'], true)) {
                    $sidebars_widgets['wp_inactive_widgets'][] = $widget_instance_id;
                }

                // Add to success array
                $this->results['success'][] = sprintf(
                    __('Widget "%1$s" imported to inactive widgets.', 'aqualuxe'),
                    $id_base
                );
            }
        }

        // Update sidebars_widgets option
        update_option('sidebars_widgets', $sidebars_widgets);

        // Return results
        return $this->results;
    }

    /**
     * Check if widget is available
     *
     * @param string $id_base Widget base ID
     * @return bool
     */
    private function is_widget_available($id_base) {
        return isset($this->available_widgets[$id_base]);
    }

    /**
     * Get widget name
     *
     * @param string $id_base Widget base ID
     * @return string
     */
    private function get_widget_name($id_base) {
        if (isset($this->available_widgets[$id_base]['name'])) {
            return $this->available_widgets[$id_base]['name'];
        }

        return $id_base;
    }
}