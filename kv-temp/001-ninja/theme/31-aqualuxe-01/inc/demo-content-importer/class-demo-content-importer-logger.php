<?php
/**
 * Demo Content Importer Logger
 *
 * Handles logging during the import process.
 *
 * @package DemoContentImporter
 * @subpackage Logger
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Logger Class
 */
class Demo_Content_Importer_Logger {

    /**
     * Log file path.
     *
     * @var string
     */
    protected $log_file = '';

    /**
     * Log entries.
     *
     * @var array
     */
    protected $log_entries = array();

    /**
     * Session ID.
     *
     * @var string
     */
    protected $session_id = '';

    /**
     * Constructor.
     *
     * @param string $session_id Import session ID.
     */
    public function __construct($session_id = '') {
        $this->session_id = !empty($session_id) ? $session_id : uniqid('dci_');
        $this->log_file = DCI_CACHE_DIR . '/' . $this->session_id . '_log.txt';
        
        // Initialize log file
        $this->init_log_file();
    }

    /**
     * Initialize log file.
     */
    private function init_log_file() {
        // Create log file if it doesn't exist
        if (!file_exists($this->log_file)) {
            $header = "==========================================================\n";
            $header .= "Demo Content Importer Log\n";
            $header .= "Session ID: {$this->session_id}\n";
            $header .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $header .= "==========================================================\n\n";
            
            file_put_contents($this->log_file, $header);
        }
    }

    /**
     * Add log entry.
     *
     * @param string $message Log message.
     * @param string $type    Log type (info, warning, error, success).
     */
    public function add($message, $type = 'info') {
        // Format timestamp
        $timestamp = date('Y-m-d H:i:s');
        
        // Format log entry
        $entry = "[{$timestamp}] [{$type}] {$message}\n";
        
        // Add to log entries array
        $this->log_entries[] = array(
            'timestamp' => $timestamp,
            'type' => $type,
            'message' => $message,
        );
        
        // Append to log file
        file_put_contents($this->log_file, $entry, FILE_APPEND);
    }

    /**
     * Add info log entry.
     *
     * @param string $message Log message.
     */
    public function info($message) {
        $this->add($message, 'info');
    }

    /**
     * Add warning log entry.
     *
     * @param string $message Log message.
     */
    public function warning($message) {
        $this->add($message, 'warning');
    }

    /**
     * Add error log entry.
     *
     * @param string $message Log message.
     */
    public function error($message) {
        $this->add($message, 'error');
    }

    /**
     * Add success log entry.
     *
     * @param string $message Log message.
     */
    public function success($message) {
        $this->add($message, 'success');
    }

    /**
     * Get log entries.
     *
     * @param string $type Filter by log type (optional).
     * @return array Log entries.
     */
    public function get_entries($type = '') {
        if (empty($type)) {
            return $this->log_entries;
        }
        
        return array_filter($this->log_entries, function($entry) use ($type) {
            return $entry['type'] === $type;
        });
    }

    /**
     * Get log file content.
     *
     * @return string Log file content.
     */
    public function get_log_content() {
        if (!file_exists($this->log_file)) {
            return '';
        }
        
        return file_get_contents($this->log_file);
    }

    /**
     * Clear log.
     */
    public function clear() {
        $this->log_entries = array();
        
        // Re-initialize log file
        $this->init_log_file();
    }

    /**
     * Get log file path.
     *
     * @return string Log file path.
     */
    public function get_log_file() {
        return $this->log_file;
    }

    /**
     * Get session ID.
     *
     * @return string Session ID.
     */
    public function get_session_id() {
        return $this->session_id;
    }
}