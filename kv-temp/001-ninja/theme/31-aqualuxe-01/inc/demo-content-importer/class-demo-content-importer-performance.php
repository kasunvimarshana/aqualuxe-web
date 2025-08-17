<?php
/**
 * Demo Content Importer Performance Optimization
 *
 * Handles performance optimization for the demo content importer.
 *
 * @package DemoContentImporter
 * @version 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Performance Class
 */
class Demo_Content_Importer_Performance {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Memory usage statistics.
     *
     * @var array
     */
    protected $memory_stats = array();

    /**
     * Time tracking.
     *
     * @var array
     */
    protected $time_tracking = array();

    /**
     * Batch size for content imports.
     *
     * @var int
     */
    protected $batch_size = 10;

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger = null;

    /**
     * Constructor.
     */
    public function __construct() {
        // Get logger instance
        $this->logger = Demo_Content_Importer_Logger::get_instance();
        
        // Initialize memory stats
        $this->memory_stats = array(
            'start' => memory_get_usage(),
            'peak' => 0,
            'checkpoints' => array(),
        );
        
        // Initialize time tracking
        $this->time_tracking = array(
            'start' => microtime(true),
            'checkpoints' => array(),
        );
        
        // Set batch size based on available memory
        $this->set_optimal_batch_size();
        
        // Add hooks
        add_action('dci_before_import', array($this, 'start_tracking'), 10, 2);
        add_action('dci_after_import', array($this, 'end_tracking'), 10, 2);
        add_filter('dci_import_batch_size', array($this, 'filter_batch_size'), 10, 2);
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
     * Set optimal batch size based on available memory.
     */
    protected function set_optimal_batch_size() {
        // Get memory limit in bytes
        $memory_limit = $this->get_memory_limit();
        
        // Calculate available memory (75% of total)
        $available_memory = $memory_limit * 0.75;
        
        // Estimate memory per item (default: 1MB)
        $memory_per_item = 1048576; // 1MB in bytes
        
        // Calculate batch size
        $calculated_batch_size = floor($available_memory / $memory_per_item);
        
        // Set batch size (minimum 5, maximum 50)
        $this->batch_size = max(5, min(50, $calculated_batch_size));
    }

    /**
     * Get PHP memory limit in bytes.
     *
     * @return int Memory limit in bytes.
     */
    protected function get_memory_limit() {
        $memory_limit = ini_get('memory_limit');
        
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = $matches[1] * 1024 * 1024 * 1024;
            } else if ($matches[2] == 'M') {
                $memory_limit = $matches[1] * 1024 * 1024;
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] * 1024;
            }
        }
        
        return $memory_limit;
    }

    /**
     * Start performance tracking.
     *
     * @param string $demo_id Demo ID.
     * @param array $import_options Import options.
     */
    public function start_tracking($demo_id, $import_options) {
        // Reset memory stats
        $this->memory_stats = array(
            'start' => memory_get_usage(),
            'peak' => 0,
            'checkpoints' => array(),
        );
        
        // Reset time tracking
        $this->time_tracking = array(
            'start' => microtime(true),
            'checkpoints' => array(),
        );
        
        // Log start
        $this->logger->log('Starting performance tracking for import', 'info');
        $this->logger->log('Initial memory usage: ' . $this->format_bytes($this->memory_stats['start']), 'info');
        $this->logger->log('Batch size: ' . $this->batch_size . ' items', 'info');
    }

    /**
     * End performance tracking.
     *
     * @param string $demo_id Demo ID.
     * @param array $import_options Import options.
     */
    public function end_tracking($demo_id, $import_options) {
        // Get final memory usage
        $final_memory = memory_get_usage();
        $peak_memory = memory_get_peak_usage(true);
        
        // Get total time
        $total_time = microtime(true) - $this->time_tracking['start'];
        
        // Update stats
        $this->memory_stats['final'] = $final_memory;
        $this->memory_stats['peak'] = $peak_memory;
        $this->time_tracking['total'] = $total_time;
        
        // Log results
        $this->logger->log('Performance tracking completed', 'info');
        $this->logger->log('Final memory usage: ' . $this->format_bytes($final_memory), 'info');
        $this->logger->log('Peak memory usage: ' . $this->format_bytes($peak_memory), 'info');
        $this->logger->log('Total import time: ' . round($total_time, 2) . ' seconds', 'info');
        
        // Log memory increase
        $memory_increase = $final_memory - $this->memory_stats['start'];
        $this->logger->log('Memory increase: ' . $this->format_bytes($memory_increase), 'info');
        
        // Log checkpoint data if available
        if (!empty($this->memory_stats['checkpoints'])) {
            $this->logger->log('Memory usage by checkpoint:', 'info');
            foreach ($this->memory_stats['checkpoints'] as $checkpoint => $memory) {
                $this->logger->log('- ' . $checkpoint . ': ' . $this->format_bytes($memory), 'info');
            }
        }
        
        if (!empty($this->time_tracking['checkpoints'])) {
            $this->logger->log('Time spent by checkpoint:', 'info');
            foreach ($this->time_tracking['checkpoints'] as $checkpoint => $time) {
                $this->logger->log('- ' . $checkpoint . ': ' . round($time, 2) . ' seconds', 'info');
            }
        }
    }

    /**
     * Add checkpoint for memory tracking.
     *
     * @param string $checkpoint Checkpoint name.
     */
    public function add_checkpoint($checkpoint) {
        // Record memory usage
        $memory_usage = memory_get_usage();
        $this->memory_stats['checkpoints'][$checkpoint] = $memory_usage;
        
        // Record time
        $current_time = microtime(true);
        $time_since_start = $current_time - $this->time_tracking['start'];
        
        // Calculate time since last checkpoint
        $last_checkpoint_time = $this->time_tracking['start'];
        if (!empty($this->time_tracking['checkpoints'])) {
            $last_checkpoint = array_keys($this->time_tracking['checkpoints']);
            $last_checkpoint = end($last_checkpoint);
            $last_checkpoint_time = $this->time_tracking['checkpoints'][$last_checkpoint];
        }
        
        $time_since_last = $current_time - $last_checkpoint_time;
        $this->time_tracking['checkpoints'][$checkpoint] = $time_since_last;
        
        // Update peak memory
        $peak_memory = memory_get_peak_usage(true);
        if ($peak_memory > $this->memory_stats['peak']) {
            $this->memory_stats['peak'] = $peak_memory;
        }
        
        // Log checkpoint
        $this->logger->log('Checkpoint: ' . $checkpoint, 'info');
        $this->logger->log('Memory usage: ' . $this->format_bytes($memory_usage), 'info');
        $this->logger->log('Time since last checkpoint: ' . round($time_since_last, 2) . ' seconds', 'info');
    }

    /**
     * Filter batch size.
     *
     * @param int $batch_size Batch size.
     * @param string $import_type Import type.
     * @return int Filtered batch size.
     */
    public function filter_batch_size($batch_size, $import_type) {
        // Use calculated batch size
        $filtered_batch_size = $this->batch_size;
        
        // Adjust based on import type
        switch ($import_type) {
            case 'posts':
                // Posts can be memory-intensive
                $filtered_batch_size = max(5, floor($this->batch_size * 0.8));
                break;
                
            case 'media':
                // Media imports are very memory-intensive
                $filtered_batch_size = max(3, floor($this->batch_size * 0.5));
                break;
                
            case 'users':
                // Users are lightweight
                $filtered_batch_size = min(100, $this->batch_size * 2);
                break;
                
            case 'terms':
                // Terms are lightweight
                $filtered_batch_size = min(100, $this->batch_size * 2);
                break;
        }
        
        return $filtered_batch_size;
    }

    /**
     * Process items in batches.
     *
     * @param array $items Items to process.
     * @param callable $callback Callback function to process each item.
     * @param string $type Type of items being processed.
     * @return array Results of processing.
     */
    public function process_in_batches($items, $callback, $type = 'items') {
        $results = array();
        $total_items = count($items);
        $batch_size = apply_filters('dci_import_batch_size', $this->batch_size, $type);
        $batches = ceil($total_items / $batch_size);
        
        $this->logger->log('Processing ' . $total_items . ' ' . $type . ' in ' . $batches . ' batches', 'info');
        
        // Process in batches
        for ($i = 0; $i < $batches; $i++) {
            // Get batch items
            $batch_items = array_slice($items, $i * $batch_size, $batch_size);
            $batch_count = count($batch_items);
            
            $this->logger->log('Processing batch ' . ($i + 1) . ' of ' . $batches . ' (' . $batch_count . ' ' . $type . ')', 'info');
            
            // Process batch
            $batch_start_time = microtime(true);
            $batch_start_memory = memory_get_usage();
            
            foreach ($batch_items as $key => $item) {
                $result = call_user_func($callback, $item);
                $results[] = $result;
            }
            
            // Log batch stats
            $batch_time = microtime(true) - $batch_start_time;
            $batch_memory = memory_get_usage() - $batch_start_memory;
            
            $this->logger->log('Batch ' . ($i + 1) . ' completed in ' . round($batch_time, 2) . ' seconds', 'info');
            $this->logger->log('Batch ' . ($i + 1) . ' memory usage: ' . $this->format_bytes($batch_memory), 'info');
            
            // Clean up memory
            $this->clean_up_memory();
            
            // Add progress update
            $progress = min(100, round((($i + 1) * $batch_size / $total_items) * 100));
            $this->logger->log('Progress: ' . $progress . '%', 'info');
        }
        
        return $results;
    }

    /**
     * Clean up memory.
     */
    public function clean_up_memory() {
        // Force garbage collection if available
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
        
        // Log memory usage
        $this->logger->log('Memory cleanup performed. Current usage: ' . $this->format_bytes(memory_get_usage()), 'info');
    }

    /**
     * Format bytes to human-readable format.
     *
     * @param int $bytes Bytes to format.
     * @param int $precision Precision.
     * @return string Formatted bytes.
     */
    protected function format_bytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get memory usage statistics.
     *
     * @return array Memory usage statistics.
     */
    public function get_memory_stats() {
        return $this->memory_stats;
    }

    /**
     * Get time tracking data.
     *
     * @return array Time tracking data.
     */
    public function get_time_tracking() {
        return $this->time_tracking;
    }

    /**
     * Get batch size.
     *
     * @return int Batch size.
     */
    public function get_batch_size() {
        return $this->batch_size;
    }

    /**
     * Set batch size.
     *
     * @param int $batch_size Batch size.
     */
    public function set_batch_size($batch_size) {
        $this->batch_size = max(1, intval($batch_size));
    }
}

// Initialize the class
Demo_Content_Importer_Performance::get_instance();