<?php
/**
 * Widget Importer
 *
 * @package AquaLuxe
 * @subpackage Importer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Widget Importer class
 */
class AquaLuxe_Widget_Importer {
    /**
     * Import widgets from a JSON file
     *
     * @param string $file Path to the JSON file.
     * @return array Results of the import.
     */
    public function import($file) {
        global $wp_registered_sidebars;

        $results = array();
        $data = $this->get_widget_data($file);

        if (is_wp_error($data)) {
            return array(
                'success' => false,
                'message' => $data->get_error_message(),
            );
        }

        // Get all available widgets site supports
        $available_widgets = $this->get_available_widgets();

        // Get all existing widget instances
        $widget_instances = array();
        foreach ($available_widgets as $widget_data) {
            $widget_instances[$widget_data['id_base']] = get_option('widget_' . $widget_data['id_base']);
        }

        // Begin results
        $results = array(
            'success' => true,
            'widgets' => array(
                'imported' => array(),
                'failed' => array(),
            ),
        );

        // Loop import data's sidebars
        foreach ($data as $sidebar_id => $widgets) {
            // Skip inactive widgets
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }

            // Check if sidebar exists
            if (!isset($wp_registered_sidebars[$sidebar_id])) {
                $results['widgets']['failed'][] = array(
                    'sidebar' => $sidebar_id,
                    'message' => __('Sidebar does not exist in theme', 'aqualuxe'),
                );
                continue;
            }

            // Loop widgets
            foreach ($widgets as $widget_instance_id => $widget) {
                $fail = false;

                // Get id_base (remove -# from end) and instance ID number
                $id_base = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                $instance_id_number = str_replace($id_base . '-', '', $widget_instance_id);

                // Does site support this widget?
                if (!$fail && !isset($available_widgets[$id_base])) {
                    $fail = true;
                    $results['widgets']['failed'][] = array(
                        'sidebar' => $sidebar_id,
                        'widget' => $widget_instance_id,
                        'message' => __('Site does not support widget', 'aqualuxe'),
                    );
                }

                // Convert multidimensional objects to multidimensional arrays
                $widget = json_decode(json_encode($widget), true);

                // Does widget with identical settings already exist in same sidebar?
                if (!$fail && isset($widget_instances[$id_base])) {
                    // Get existing widgets in this sidebar
                    $sidebars_widgets = get_option('sidebars_widgets');
                    $sidebar_widgets = isset($sidebars_widgets[$sidebar_id]) ? $sidebars_widgets[$sidebar_id] : array();

                    // Loop widgets with ID base
                    $single_widget_instances = !empty($widget_instances[$id_base]) ? $widget_instances[$id_base] : array();
                    foreach ($single_widget_instances as $check_id => $check_widget) {
                        // Is widget in same sidebar and has identical settings?
                        if (in_array("$id_base-$check_id", $sidebar_widgets, true) && (array) $widget === $check_widget) {
                            $fail = true;
                            $results['widgets']['failed'][] = array(
                                'sidebar' => $sidebar_id,
                                'widget' => $widget_instance_id,
                                'message' => __('Widget already exists', 'aqualuxe'),
                            );
                            break;
                        }
                    }
                }

                // No failure
                if (!$fail) {
                    // Add widget instance
                    $single_widget_instances = get_option('widget_' . $id_base);
                    $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array('_multiwidget' => 1);
                    $single_widget_instances[] = $widget;

                    // Get the key it was given
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                    if ('0' === strval($new_instance_id_number)) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset($single_widget_instances[0]);
                    }

                    // Move _multiwidget to end of array for uniformity
                    if (isset($single_widget_instances['_multiwidget'])) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset($single_widget_instances['_multiwidget']);
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget
                    update_option('widget_' . $id_base, $single_widget_instances);

                    // Assign widget instance to sidebar
                    $sidebars_widgets = get_option('sidebars_widgets');
                    if (!isset($sidebars_widgets[$sidebar_id])) {
                        $sidebars_widgets[$sidebar_id] = array();
                    }
                    $new_instance_id = $id_base . '-' . $new_instance_id_number;
                    $sidebars_widgets[$sidebar_id][] = $new_instance_id;
                    update_option('sidebars_widgets', $sidebars_widgets);

                    // Success
                    $results['widgets']['imported'][] = array(
                        'sidebar' => $sidebar_id,
                        'widget' => $widget_instance_id,
                        'message' => __('Imported', 'aqualuxe'),
                    );
                }
            }
        }

        // Return results
        return $results;
    }

    /**
     * Get widget data from a JSON file
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error
     */
    private function get_widget_data($file) {
        // Check if file exists
        if (!file_exists($file)) {
            return new WP_Error('file_not_found', __('Widget data file not found', 'aqualuxe'));
        }

        // Get file contents
        $data = file_get_contents($file);
        if (empty($data)) {
            return new WP_Error('empty_file', __('Widget data file is empty', 'aqualuxe'));
        }

        // Decode the JSON data
        $data = json_decode($data);
        if (empty($data) || !is_object($data)) {
            return new WP_Error('invalid_json', __('Widget data is not valid JSON', 'aqualuxe'));
        }

        return $data;
    }

    /**
     * Get all available widgets
     *
     * @return array
     */
    private function get_available_widgets() {
        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;
        $available_widgets = array();

        foreach ($widget_controls as $widget) {
            if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
                $available_widgets[$widget['id_base']] = $widget;
            }
        }

        return $available_widgets;
    }
}