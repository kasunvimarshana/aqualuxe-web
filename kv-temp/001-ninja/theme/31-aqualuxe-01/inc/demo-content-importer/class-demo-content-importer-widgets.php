<?php
/**
 * Demo Content Importer Widgets
 *
 * Handles importing widgets.
 *
 * @package DemoContentImporter
 * @subpackage Widgets
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Widgets Class
 */
class Demo_Content_Importer_Widgets {

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
     * Import widgets from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Results of the import.
     */
    public function import($file) {
        global $wp_registered_sidebars;
        
        $this->logger->info('Starting widgets import');
        
        // Get widget data
        $data = $this->get_widget_data($file);
        
        if (is_wp_error($data)) {
            $this->logger->error('Failed to get widget data: ' . $data->get_error_message());
            return $data;
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
                $this->logger->warning(sprintf('Sidebar does not exist in theme: %s', $sidebar_id));
                $results['widgets']['failed'][] = array(
                    'sidebar' => $sidebar_id,
                    'message' => __('Sidebar does not exist in theme', 'demo-content-importer'),
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
                    $this->logger->warning(sprintf('Site does not support widget: %s', $id_base));
                    $results['widgets']['failed'][] = array(
                        'sidebar' => $sidebar_id,
                        'widget' => $widget_instance_id,
                        'message' => __('Site does not support widget', 'demo-content-importer'),
                    );
                    $fail = true;
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
                            $this->logger->info(sprintf('Widget already exists: %s in %s', $widget_instance_id, $sidebar_id));
                            $results['widgets']['imported'][] = array(
                                'sidebar' => $sidebar_id,
                                'widget' => $widget_instance_id,
                                'message' => __('Widget already exists', 'demo-content-importer'),
                            );
                            $fail = true;
                            break;
                        }
                    }
                }
                
                // No failure
                if (!$fail) {
                    // Process widget content for any URL replacements
                    $widget = $this->process_widget_content($widget);
                    
                    // Add widget instance
                    $single_widget_instances = get_option('widget_' . $id_base);
                    $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array('_multiwidget' => 1);
                    $single_widget_instances[] = $widget;
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);
                    
                    // If key is 0, make it 1
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
                    
                    // Avoid rarely fatal error when the option is an empty string
                    if (!$sidebars_widgets) {
                        $sidebars_widgets = array();
                    }
                    
                    // Use ID number from new widget instance
                    $new_instance_id = $id_base . '-' . $new_instance_id_number;
                    
                    // Add new instance to sidebar
                    $sidebars_widgets[$sidebar_id][] = $new_instance_id;
                    
                    // Save the amended data
                    update_option('sidebars_widgets', $sidebars_widgets);
                    
                    // Success message
                    $this->logger->success(sprintf('Widget imported: %s in %s', $widget_instance_id, $sidebar_id));
                    $results['widgets']['imported'][] = array(
                        'sidebar' => $sidebar_id,
                        'widget' => $widget_instance_id,
                        'message' => __('Widget imported', 'demo-content-importer'),
                    );
                }
            }
        }
        
        // Return results
        return $results;
    }

    /**
     * Get widget data from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Widget data or error.
     */
    private function get_widget_data($file) {
        // Check if file exists
        if (!file_exists($file)) {
            return new WP_Error(
                'file_not_found',
                sprintf(__('Widget data file not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Get file contents
        $data = file_get_contents($file);
        
        if (empty($data)) {
            return new WP_Error(
                'empty_file',
                sprintf(__('Widget data file is empty: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Decode the JSON data
        $data = json_decode($data, true);
        
        if (empty($data) || !is_array($data)) {
            return new WP_Error(
                'invalid_json',
                sprintf(__('Widget data is not valid JSON: %s', 'demo-content-importer'), $file)
            );
        }
        
        return $data;
    }

    /**
     * Get all available widgets.
     *
     * @return array Available widgets.
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

    /**
     * Process widget content for any URL replacements or other modifications.
     *
     * @param array $widget Widget data.
     * @return array Processed widget data.
     */
    private function process_widget_content($widget) {
        // Process widget content recursively
        foreach ($widget as $key => $value) {
            if (is_array($value)) {
                $widget[$key] = $this->process_widget_content($value);
            } elseif (is_string($value)) {
                // Handle image URLs
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)/i', $value)) {
                    $widget[$key] = $this->handle_image_url($value);
                }
                
                // Handle other URLs
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    $widget[$key] = $this->handle_url($value);
                }
            }
        }
        
        return $widget;
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

    /**
     * Handle URL.
     *
     * @param string $url URL.
     * @return string Updated URL.
     */
    private function handle_url($url) {
        // If URL is already a local URL, return it
        if (strpos($url, site_url()) === 0) {
            return $url;
        }
        
        // If URL is a demo URL, replace it with the local site URL
        $demo_url = 'https://aqualuxe.example.com';
        if (strpos($url, $demo_url) === 0) {
            $new_url = str_replace($demo_url, site_url(), $url);
            $this->logger->info(sprintf('Replaced URL: %s -> %s', $url, $new_url));
            return $new_url;
        }
        
        return $url;
    }
}