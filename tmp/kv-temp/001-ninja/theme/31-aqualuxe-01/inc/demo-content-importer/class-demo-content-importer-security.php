<?php
/**
 * Demo Content Importer Security
 *
 * Handles security features for the demo content importer.
 *
 * @package DemoContentImporter
 * @version 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Security Class
 */
class Demo_Content_Importer_Security {

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
     * Allowed file types for import.
     *
     * @var array
     */
    protected $allowed_file_types = array(
        // Images
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        // Documents
        'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt',
        // Audio/Video
        'mp3', 'mp4', 'wav', 'ogg',
        // Archives
        'zip', 'gz',
        // Data
        'xml', 'json', 'csv',
        // WordPress
        'wie', 'dat',
    );

    /**
     * Disallowed file types for import.
     *
     * @var array
     */
    protected $disallowed_file_types = array(
        // Executable
        'exe', 'php', 'js', 'py', 'rb', 'pl', 'sh', 'bat', 'cmd',
        // System
        'dll', 'so', 'bin', 'sys',
    );

    /**
     * Constructor.
     */
    public function __construct() {
        // Get logger instance
        $this->logger = Demo_Content_Importer_Logger::get_instance();
        
        // Add hooks
        add_filter('dci_validate_import_file', array($this, 'validate_import_file'), 10, 2);
        add_filter('dci_validate_import_data', array($this, 'validate_import_data'), 10, 2);
        add_filter('dci_allowed_file_types', array($this, 'filter_allowed_file_types'), 10);
        add_filter('upload_mimes', array($this, 'filter_upload_mimes'), 10, 1);
        
        // Security checks
        add_action('dci_before_import', array($this, 'verify_user_permissions'), 5, 2);
        add_action('dci_before_reset', array($this, 'verify_user_permissions'), 5, 1);
        add_action('dci_before_backup', array($this, 'verify_user_permissions'), 5, 1);
        add_action('dci_before_restore', array($this, 'verify_user_permissions'), 5, 1);
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
     * Verify user permissions.
     *
     * @param string $action Action being performed.
     * @param array $data Additional data.
     */
    public function verify_user_permissions($action, $data = array()) {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            $this->logger->log('Security violation: User does not have permission to perform this action', 'error');
            wp_die('You do not have permission to perform this action.');
        }
        
        // Verify nonce if provided
        if (isset($_REQUEST['_wpnonce'])) {
            $nonce_action = 'dci_' . $action;
            if (!wp_verify_nonce($_REQUEST['_wpnonce'], $nonce_action)) {
                $this->logger->log('Security violation: Invalid nonce for ' . $action, 'error');
                wp_die('Security check failed. Please try again.');
            }
        }
        
        // Log the action
        $this->logger->log('User ' . wp_get_current_user()->user_login . ' performing action: ' . $action, 'info');
    }

    /**
     * Validate import file.
     *
     * @param bool $valid Whether the file is valid.
     * @param string $file_path Path to the file.
     * @return bool Whether the file is valid.
     */
    public function validate_import_file($valid, $file_path) {
        // Check if file exists
        if (!file_exists($file_path)) {
            $this->logger->log('Security check: File does not exist: ' . $file_path, 'error');
            return false;
        }
        
        // Check file extension
        $file_info = pathinfo($file_path);
        $extension = strtolower($file_info['extension']);
        
        // Check against disallowed types
        if (in_array($extension, $this->disallowed_file_types)) {
            $this->logger->log('Security check: File type not allowed: ' . $extension, 'error');
            return false;
        }
        
        // Check against allowed types
        $allowed_types = apply_filters('dci_allowed_file_types', $this->allowed_file_types);
        if (!in_array($extension, $allowed_types)) {
            $this->logger->log('Security check: File type not in allowed list: ' . $extension, 'error');
            return false;
        }
        
        // Check file size
        $max_size = apply_filters('dci_max_file_size', 10 * 1024 * 1024); // 10MB default
        $file_size = filesize($file_path);
        if ($file_size > $max_size) {
            $this->logger->log('Security check: File size exceeds maximum allowed: ' . size_format($file_size), 'error');
            return false;
        }
        
        // For XML files, perform additional validation
        if ($extension === 'xml') {
            return $this->validate_xml_file($file_path);
        }
        
        // For JSON files, perform additional validation
        if ($extension === 'json') {
            return $this->validate_json_file($file_path);
        }
        
        // For ZIP files, perform additional validation
        if ($extension === 'zip') {
            return $this->validate_zip_file($file_path);
        }
        
        return $valid;
    }

    /**
     * Validate XML file.
     *
     * @param string $file_path Path to the XML file.
     * @return bool Whether the XML file is valid.
     */
    protected function validate_xml_file($file_path) {
        // Use libxml to validate XML
        $use_errors = libxml_use_internal_errors(true);
        
        // Create a new DOMDocument
        $dom = new DOMDocument();
        
        // Try to load the XML file
        $loaded = $dom->load($file_path);
        
        // Get any errors
        $errors = libxml_get_errors();
        
        // Reset libxml error handling
        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);
        
        // Check if XML loaded successfully
        if (!$loaded) {
            $this->logger->log('Security check: Invalid XML file', 'error');
            foreach ($errors as $error) {
                $this->logger->log('XML Error: ' . $error->message, 'error');
            }
            return false;
        }
        
        // Check for PHP code in XML
        $xml_content = file_get_contents($file_path);
        if (preg_match('/<\?php|<\? |eval\(|base64_decode\(|gzinflate\(|str_rot13\(|preg_replace\(.*\/e/i', $xml_content)) {
            $this->logger->log('Security check: XML file contains potentially malicious PHP code', 'error');
            return false;
        }
        
        // Check for excessive entity expansion (XXE attack prevention)
        if (preg_match('/<!ENTITY/i', $xml_content)) {
            $this->logger->log('Security check: XML file contains entity declarations (potential XXE attack)', 'error');
            return false;
        }
        
        return true;
    }

    /**
     * Validate JSON file.
     *
     * @param string $file_path Path to the JSON file.
     * @return bool Whether the JSON file is valid.
     */
    protected function validate_json_file($file_path) {
        // Get file contents
        $json_content = file_get_contents($file_path);
        
        // Try to decode JSON
        $json_data = json_decode($json_content);
        
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->log('Security check: Invalid JSON file - ' . json_last_error_msg(), 'error');
            return false;
        }
        
        // Check for PHP code in JSON
        if (preg_match('/<\?php|<\? |eval\(|base64_decode\(|gzinflate\(|str_rot13\(|preg_replace\(.*\/e/i', $json_content)) {
            $this->logger->log('Security check: JSON file contains potentially malicious PHP code', 'error');
            return false;
        }
        
        return true;
    }

    /**
     * Validate ZIP file.
     *
     * @param string $file_path Path to the ZIP file.
     * @return bool Whether the ZIP file is valid.
     */
    protected function validate_zip_file($file_path) {
        // Check if ZipArchive is available
        if (!class_exists('ZipArchive')) {
            $this->logger->log('Security check: ZipArchive class not available', 'error');
            return false;
        }
        
        // Open the ZIP file
        $zip = new ZipArchive();
        $result = $zip->open($file_path);
        
        if ($result !== true) {
            $this->logger->log('Security check: Failed to open ZIP file', 'error');
            return false;
        }
        
        // Check each file in the ZIP
        $disallowed_found = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file_info = $zip->statIndex($i);
            $file_name = $file_info['name'];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Check against disallowed types
            if (in_array($extension, $this->disallowed_file_types)) {
                $this->logger->log('Security check: ZIP contains disallowed file type: ' . $file_name, 'error');
                $disallowed_found = true;
                break;
            }
            
            // Check for hidden files
            if (strpos(basename($file_name), '.') === 0) {
                $this->logger->log('Security check: ZIP contains hidden file: ' . $file_name, 'warning');
            }
            
            // Check for absolute paths or path traversal
            if (strpos($file_name, '..') !== false || strpos($file_name, ':') !== false) {
                $this->logger->log('Security check: ZIP contains suspicious path: ' . $file_name, 'error');
                $disallowed_found = true;
                break;
            }
        }
        
        // Close the ZIP file
        $zip->close();
        
        return !$disallowed_found;
    }

    /**
     * Validate import data.
     *
     * @param bool $valid Whether the data is valid.
     * @param array $data Import data.
     * @return bool Whether the data is valid.
     */
    public function validate_import_data($valid, $data) {
        // Check if data is empty
        if (empty($data)) {
            $this->logger->log('Security check: Import data is empty', 'error');
            return false;
        }
        
        // Check for required fields
        $required_fields = array('content', 'version');
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                $this->logger->log('Security check: Import data missing required field: ' . $field, 'error');
                return false;
            }
        }
        
        // Check version compatibility
        if (isset($data['version']) && version_compare($data['version'], '1.0.0', '<')) {
            $this->logger->log('Security check: Import data version not compatible: ' . $data['version'], 'error');
            return false;
        }
        
        // Sanitize content data
        if (isset($data['content']) && is_array($data['content'])) {
            foreach ($data['content'] as $key => $content_item) {
                // Check for malicious content
                if (is_string($content_item) && $this->contains_malicious_code($content_item)) {
                    $this->logger->log('Security check: Import data contains potentially malicious code in ' . $key, 'error');
                    return false;
                }
            }
        }
        
        return $valid;
    }

    /**
     * Check if content contains potentially malicious code.
     *
     * @param string $content Content to check.
     * @return bool Whether the content contains potentially malicious code.
     */
    protected function contains_malicious_code($content) {
        // Check for PHP code
        if (preg_match('/<\?php|<\? |eval\(|base64_decode\(|gzinflate\(|str_rot13\(|preg_replace\(.*\/e/i', $content)) {
            return true;
        }
        
        // Check for JavaScript event handlers
        if (preg_match('/on(load|click|mouseover|mouseout|mousedown|mouseup|keydown|keypress|keyup|focus|blur|submit|change|select|unload|error)=/i', $content)) {
            return true;
        }
        
        // Check for iframe with suspicious src
        if (preg_match('/<iframe.*src=["\'](https?:)?\/\/((?!youtube\.com|vimeo\.com|dailymotion\.com|player\.twitch\.tv).)*["\']/i', $content)) {
            return true;
        }
        
        // Check for script tags
        if (preg_match('/<script.*>.*<\/script>/is', $content)) {
            // Allow some common scripts like Google Analytics
            if (!preg_match('/googletagmanager\.com|google-analytics\.com/i', $content)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Filter allowed file types.
     *
     * @param array $types Allowed file types.
     * @return array Filtered allowed file types.
     */
    public function filter_allowed_file_types($types) {
        // Add any additional allowed types
        $additional_types = array();
        
        // Merge with existing types
        $types = array_merge($types, $additional_types);
        
        // Remove any disallowed types
        $types = array_diff($types, $this->disallowed_file_types);
        
        return array_unique($types);
    }

    /**
     * Filter upload MIME types.
     *
     * @param array $mimes Allowed MIME types.
     * @return array Filtered MIME types.
     */
    public function filter_upload_mimes($mimes) {
        // Only modify MIME types during import
        if (!defined('DCI_IMPORTING') || !DCI_IMPORTING) {
            return $mimes;
        }
        
        // Add SVG support
        $mimes['svg'] = 'image/svg+xml';
        
        // Add import file types
        $mimes['xml'] = 'application/xml';
        $mimes['json'] = 'application/json';
        $mimes['wie'] = 'application/octet-stream';
        $mimes['dat'] = 'application/octet-stream';
        
        return $mimes;
    }

    /**
     * Encrypt data.
     *
     * @param string $data Data to encrypt.
     * @param string $key Encryption key.
     * @return string|bool Encrypted data or false on failure.
     */
    public function encrypt_data($data, $key = '') {
        // Use WordPress salt if no key provided
        if (empty($key)) {
            $key = defined('AUTH_SALT') ? AUTH_SALT : wp_salt();
        }
        
        // Check if OpenSSL is available
        if (!function_exists('openssl_encrypt')) {
            $this->logger->log('Encryption failed: OpenSSL not available', 'error');
            return false;
        }
        
        // Generate initialization vector
        $iv_size = openssl_cipher_iv_length('AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($iv_size);
        
        // Encrypt the data
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        
        // Combine IV and encrypted data
        $result = base64_encode($iv . $encrypted);
        
        return $result;
    }

    /**
     * Decrypt data.
     *
     * @param string $data Encrypted data.
     * @param string $key Encryption key.
     * @return string|bool Decrypted data or false on failure.
     */
    public function decrypt_data($data, $key = '') {
        // Use WordPress salt if no key provided
        if (empty($key)) {
            $key = defined('AUTH_SALT') ? AUTH_SALT : wp_salt();
        }
        
        // Check if OpenSSL is available
        if (!function_exists('openssl_decrypt')) {
            $this->logger->log('Decryption failed: OpenSSL not available', 'error');
            return false;
        }
        
        // Decode the data
        $data = base64_decode($data);
        
        // Get initialization vector size
        $iv_size = openssl_cipher_iv_length('AES-256-CBC');
        
        // Extract IV and encrypted data
        $iv = substr($data, 0, $iv_size);
        $encrypted = substr($data, $iv_size);
        
        // Decrypt the data
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
        
        return $decrypted;
    }

    /**
     * Generate secure token.
     *
     * @param int $length Token length.
     * @return string Secure token.
     */
    public function generate_token($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Sanitize file name.
     *
     * @param string $filename File name to sanitize.
     * @return string Sanitized file name.
     */
    public function sanitize_file_name($filename) {
        // Remove any directory paths
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Ensure filename doesn't start with a dot (hidden file)
        $filename = ltrim($filename, '.');
        
        return $filename;
    }

    /**
     * Implement role-based access control.
     *
     * @param string $capability Capability to check.
     * @return bool Whether the current user has the capability.
     */
    public function current_user_can_import($capability = 'manage_options') {
        // Default to manage_options capability
        $required_capability = apply_filters('dci_required_capability', $capability);
        
        // Check if user has the required capability
        return current_user_can($required_capability);
    }
}

// Initialize the class
Demo_Content_Importer_Security::get_instance();