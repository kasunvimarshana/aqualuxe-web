<?php
/**
 * Demo Content Importer Progress
 *
 * Handles progress tracking and reporting for the demo content importer.
 *
 * @package DemoContentImporter
 * @version 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Progress Class
 */
class Demo_Content_Importer_Progress {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger = null;

    /**
     * Progress data.
     *
     * @var array
     */
    protected $progress = array();

    /**
     * Current import ID.
     *
     * @var string
     */
    protected $import_id = '';

    /**
     * Constructor.
     */
    public function __construct() {
        // Get logger instance
        $this->logger = Demo_Content_Importer_Logger::get_instance();
        
        // Initialize progress data
        $this->progress = array(
            'status' => 'idle',
            'current_step' => '',
            'total_steps' => 0,
            'completed_steps' => 0,
            'percentage' => 0,
            'current_item' => '',
            'total_items' => 0,
            'completed_items' => 0,
            'item_percentage' => 0,
            'start_time' => 0,
            'current_time' => 0,
            'elapsed_time' => 0,
            'estimated_time' => 0,
            'steps' => array(),
            'messages' => array(),
        );
        
        // Add hooks
        add_action('dci_before_import', array($this, 'start_progress'), 10, 2);
        add_action('dci_after_import', array($this, 'complete_progress'), 10, 2);
        add_action('dci_import_step_start', array($this, 'start_step'), 10, 2);
        add_action('dci_import_step_complete', array($this, 'complete_step'), 10, 2);
        add_action('dci_import_item_start', array($this, 'start_item'), 10, 3);
        add_action('dci_import_item_complete', array($this, 'complete_item'), 10, 3);
        
        // AJAX handlers
        add_action('wp_ajax_dci_get_progress', array($this, 'ajax_get_progress'));
    }

    /**
     * Get instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Start progress tracking.
     *
     * @param string $demo_id Demo ID.
     * @param array $import_options Import options.
     */
    public function start_progress($demo_id, $import_options) {
        // Generate unique import ID
        $this->import_id = 'import_' . uniqid();
        
        // Reset progress data
        $this->progress = array(
            'status' => 'running',
            'demo_id' => $demo_id,
            'import_options' => $import_options,
            'current_step' => '',
            'total_steps' => $this->calculate_total_steps($import_options),
            'completed_steps' => 0,
            'percentage' => 0,
            'current_item' => '',
            'total_items' => 0,
            'completed_items' => 0,
            'item_percentage' => 0,
            'start_time' => time(),
            'current_time' => time(),
            'elapsed_time' => 0,
            'estimated_time' => 0,
            'steps' => array(),
            'messages' => array(),
        );
        
        // Define steps based on import options
        $this->define_steps($import_options);
        
        // Save initial progress
        $this->save_progress();
        
        // Log start
        $this->logger->log('Started import progress tracking for demo: ' . $demo_id, 'info');
        $this->add_message('Starting import process for demo: ' . $demo_id);
    }

    /**
     * Complete progress tracking.
     *
     * @param string $demo_id Demo ID.
     * @param array $import_options Import options.
     */
    public function complete_progress($demo_id, $import_options) {
        // Update progress data
        $this->progress['status'] = 'completed';
        $this->progress['percentage'] = 100;
        $this->progress['completed_steps'] = $this->progress['total_steps'];
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Save final progress
        $this->save_progress();
        
        // Log completion
        $this->logger->log('Completed import progress tracking for demo: ' . $demo_id, 'info');
        $this->add_message('Import process completed successfully!');
        $this->add_message('Total time: ' . $this->format_time($this->progress['elapsed_time']));
    }

    /**
     * Start a step.
     *
     * @param string $step_id Step ID.
     * @param array $step_data Step data.
     */
    public function start_step($step_id, $step_data) {
        // Update progress data
        $this->progress['current_step'] = $step_id;
        $this->progress['current_item'] = '';
        $this->progress['total_items'] = isset($step_data['total_items']) ? $step_data['total_items'] : 0;
        $this->progress['completed_items'] = 0;
        $this->progress['item_percentage'] = 0;
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Update step data
        if (isset($this->progress['steps'][$step_id])) {
            $this->progress['steps'][$step_id]['status'] = 'running';
            $this->progress['steps'][$step_id]['start_time'] = time();
        }
        
        // Save progress
        $this->save_progress();
        
        // Log step start
        $step_name = isset($step_data['name']) ? $step_data['name'] : $step_id;
        $this->logger->log('Started import step: ' . $step_name, 'info');
        $this->add_message('Starting ' . $step_name . '...');
    }

    /**
     * Complete a step.
     *
     * @param string $step_id Step ID.
     * @param array $step_data Step data.
     */
    public function complete_step($step_id, $step_data) {
        // Update progress data
        $this->progress['completed_steps']++;
        $this->progress['percentage'] = ($this->progress['completed_steps'] / $this->progress['total_steps']) * 100;
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Calculate estimated time remaining
        if ($this->progress['completed_steps'] > 0) {
            $time_per_step = $this->progress['elapsed_time'] / $this->progress['completed_steps'];
            $remaining_steps = $this->progress['total_steps'] - $this->progress['completed_steps'];
            $this->progress['estimated_time'] = $time_per_step * $remaining_steps;
        }
        
        // Update step data
        if (isset($this->progress['steps'][$step_id])) {
            $this->progress['steps'][$step_id]['status'] = 'completed';
            $this->progress['steps'][$step_id]['end_time'] = time();
            $this->progress['steps'][$step_id]['duration'] = $this->progress['steps'][$step_id]['end_time'] - $this->progress['steps'][$step_id]['start_time'];
        }
        
        // Save progress
        $this->save_progress();
        
        // Log step completion
        $step_name = isset($step_data['name']) ? $step_data['name'] : $step_id;
        $this->logger->log('Completed import step: ' . $step_name, 'info');
        $this->add_message($step_name . ' completed successfully.');
    }

    /**
     * Start an item.
     *
     * @param string $step_id Step ID.
     * @param string $item_id Item ID.
     * @param array $item_data Item data.
     */
    public function start_item($step_id, $item_id, $item_data) {
        // Update progress data
        $this->progress['current_item'] = $item_id;
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Save progress
        $this->save_progress();
        
        // Log item start (only for significant items to avoid excessive logging)
        if (isset($item_data['significant']) && $item_data['significant']) {
            $item_name = isset($item_data['name']) ? $item_data['name'] : $item_id;
            $this->logger->log('Processing item: ' . $item_name, 'info');
            $this->add_message('Processing ' . $item_name . '...');
        }
    }

    /**
     * Complete an item.
     *
     * @param string $step_id Step ID.
     * @param string $item_id Item ID.
     * @param array $item_data Item data.
     */
    public function complete_item($step_id, $item_id, $item_data) {
        // Update progress data
        $this->progress['completed_items']++;
        if ($this->progress['total_items'] > 0) {
            $this->progress['item_percentage'] = ($this->progress['completed_items'] / $this->progress['total_items']) * 100;
        } else {
            $this->progress['item_percentage'] = 0;
        }
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Save progress
        $this->save_progress();
        
        // Log item completion (only for significant items to avoid excessive logging)
        if (isset($item_data['significant']) && $item_data['significant']) {
            $item_name = isset($item_data['name']) ? $item_data['name'] : $item_id;
            $this->logger->log('Completed item: ' . $item_name, 'info');
            $this->add_message($item_name . ' processed successfully.');
        }
    }

    /**
     * Calculate total steps based on import options.
     *
     * @param array $import_options Import options.
     * @return int Total steps.
     */
    protected function calculate_total_steps($import_options) {
        $total_steps = 1; // Initialization step
        
        // Add steps based on import options
        if (!empty($import_options['plugins'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['content'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['media'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['widgets'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['customizer'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['options'])) {
            $total_steps++;
        }
        
        if (!empty($import_options['menus'])) {
            $total_steps++;
        }
        
        $total_steps++; // Finalization step
        
        return $total_steps;
    }

    /**
     * Define steps based on import options.
     *
     * @param array $import_options Import options.
     */
    protected function define_steps($import_options) {
        // Initialize steps array
        $this->progress['steps'] = array(
            'initialization' => array(
                'name' => 'Initialization',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            ),
        );
        
        // Add steps based on import options
        if (!empty($import_options['plugins'])) {
            $this->progress['steps']['plugins'] = array(
                'name' => 'Plugin Installation',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['content'])) {
            $this->progress['steps']['content'] = array(
                'name' => 'Content Import',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['media'])) {
            $this->progress['steps']['media'] = array(
                'name' => 'Media Import',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['widgets'])) {
            $this->progress['steps']['widgets'] = array(
                'name' => 'Widget Import',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['customizer'])) {
            $this->progress['steps']['customizer'] = array(
                'name' => 'Customizer Import',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['options'])) {
            $this->progress['steps']['options'] = array(
                'name' => 'Options Import',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        if (!empty($import_options['menus'])) {
            $this->progress['steps']['menus'] = array(
                'name' => 'Menu Setup',
                'status' => 'pending',
                'start_time' => 0,
                'end_time' => 0,
                'duration' => 0,
            );
        }
        
        // Add finalization step
        $this->progress['steps']['finalization'] = array(
            'name' => 'Finalization',
            'status' => 'pending',
            'start_time' => 0,
            'end_time' => 0,
            'duration' => 0,
        );
    }

    /**
     * Save progress data.
     */
    protected function save_progress() {
        // Update current time
        $this->progress['current_time'] = time();
        $this->progress['elapsed_time'] = $this->progress['current_time'] - $this->progress['start_time'];
        
        // Save to transient
        set_transient('dci_progress_' . $this->import_id, $this->progress, DAY_IN_SECONDS);
        
        // Save current import ID
        update_option('dci_current_import_id', $this->import_id);
    }

    /**
     * Get progress data.
     *
     * @param string $import_id Import ID.
     * @return array Progress data.
     */
    public function get_progress($import_id = '') {
        // If no import ID provided, use current import ID
        if (empty($import_id)) {
            if (!empty($this->import_id)) {
                $import_id = $this->import_id;
            } else {
                $import_id = get_option('dci_current_import_id', '');
            }
        }
        
        // If still no import ID, return empty progress
        if (empty($import_id)) {
            return $this->progress;
        }
        
        // Get progress from transient
        $progress = get_transient('dci_progress_' . $import_id);
        
        // If no progress found, return current progress
        if (false === $progress) {
            return $this->progress;
        }
        
        return $progress;
    }

    /**
     * Add message to progress.
     *
     * @param string $message Message to add.
     * @param string $type Message type.
     */
    public function add_message($message, $type = 'info') {
        // Add message to progress
        $this->progress['messages'][] = array(
            'message' => $message,
            'type' => $type,
            'time' => time(),
        );
        
        // Limit messages to 100
        if (count($this->progress['messages']) > 100) {
            array_shift($this->progress['messages']);
        }
        
        // Save progress
        $this->save_progress();
    }

    /**
     * Format time in seconds to human-readable format.
     *
     * @param int $seconds Time in seconds.
     * @return string Formatted time.
     */
    protected function format_time($seconds) {
        if ($seconds < 60) {
            return $seconds . ' seconds';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $seconds = $seconds % 60;
            return $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' ' . $seconds . ' second' . ($seconds != 1 ? 's' : '');
        } else {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . ' hour' . ($hours != 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes != 1 ? 's' : '');
        }
    }

    /**
     * AJAX handler for getting progress.
     */
    public function ajax_get_progress() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-progress-nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to access this data.'));
        }
        
        // Get import ID
        $import_id = isset($_POST['import_id']) ? sanitize_text_field($_POST['import_id']) : '';
        
        // Get progress
        $progress = $this->get_progress($import_id);
        
        // Send response
        wp_send_json_success(array('progress' => $progress));
    }

    /**
     * Get import ID.
     *
     * @return string Import ID.
     */
    public function get_import_id() {
        return $this->import_id;
    }

    /**
     * Set import ID.
     *
     * @param string $import_id Import ID.
     */
    public function set_import_id($import_id) {
        $this->import_id = $import_id;
    }
}

// Initialize the class
Demo_Content_Importer_Progress::get_instance();