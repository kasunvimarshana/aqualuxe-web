<?php
/**
 * Demo Content Importer Media
 *
 * Handles importing media files.
 *
 * @package DemoContentImporter
 * @subpackage Media
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Media Class
 */
class Demo_Content_Importer_Media {

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger;

    /**
     * Media mapping.
     *
     * @var array
     */
    protected $media_mapping = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
    }

    /**
     * Import external image.
     *
     * @param string $url Image URL.
     * @return int|WP_Error Attachment ID on success, WP_Error on failure.
     */
    public function import_external_image($url) {
        // Check if URL is valid
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return new WP_Error(
                'invalid_url',
                sprintf(__('Invalid URL: %s', 'demo-content-importer'), $url)
            );
        }
        
        // Check if image is already imported
        $attachment_id = $this->get_attachment_id_by_url($url);
        
        if ($attachment_id) {
            $this->logger->info(sprintf('Image already imported: %s (ID: %d)', $url, $attachment_id));
            return $attachment_id;
        }
        
        // Get file name from URL
        $file_name = basename(parse_url($url, PHP_URL_PATH));
        
        // Generate unique file name
        $file_name = wp_unique_filename(wp_upload_dir()['path'], $file_name);
        
        // Download image
        $image_data = $this->download_image($url);
        
        if (is_wp_error($image_data)) {
            return $image_data;
        }
        
        // Upload image
        $attachment_id = $this->upload_image($file_name, $image_data, $url);
        
        if (is_wp_error($attachment_id)) {
            return $attachment_id;
        }
        
        // Store in media mapping
        $this->media_mapping[$url] = $attachment_id;
        
        return $attachment_id;
    }

    /**
     * Get attachment ID by URL.
     *
     * @param string $url Image URL.
     * @return int|false Attachment ID or false if not found.
     */
    private function get_attachment_id_by_url($url) {
        global $wpdb;
        
        // Check if URL is in media mapping
        if (isset($this->media_mapping[$url])) {
            return $this->media_mapping[$url];
        }
        
        // Get attachment ID from database
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s",
            '%' . $wpdb->esc_like(basename($url)) . '%'
        ));
        
        if ($attachment_id) {
            // Store in media mapping
            $this->media_mapping[$url] = $attachment_id;
            
            return $attachment_id;
        }
        
        return false;
    }

    /**
     * Download image.
     *
     * @param string $url Image URL.
     * @return string|WP_Error Image data on success, WP_Error on failure.
     */
    private function download_image($url) {
        $this->logger->info(sprintf('Downloading image: %s', $url));
        
        // Download image
        $response = wp_remote_get($url, array(
            'timeout' => 60,
            'sslverify' => false,
        ));
        
        // Check for errors
        if (is_wp_error($response)) {
            $this->logger->error(sprintf('Failed to download image: %s - %s', $url, $response->get_error_message()));
            return $response;
        }
        
        // Check response code
        $response_code = wp_remote_retrieve_response_code($response);
        
        if ($response_code !== 200) {
            $this->logger->error(sprintf('Failed to download image: %s - Response code: %d', $url, $response_code));
            return new WP_Error(
                'download_failed',
                sprintf(__('Failed to download image: %s - Response code: %d', 'demo-content-importer'), $url, $response_code)
            );
        }
        
        // Get image data
        $image_data = wp_remote_retrieve_body($response);
        
        if (empty($image_data)) {
            $this->logger->error(sprintf('Empty image data: %s', $url));
            return new WP_Error(
                'empty_image_data',
                sprintf(__('Empty image data: %s', 'demo-content-importer'), $url)
            );
        }
        
        return $image_data;
    }

    /**
     * Upload image.
     *
     * @param string $file_name  File name.
     * @param string $image_data Image data.
     * @param string $source_url Source URL.
     * @return int|WP_Error Attachment ID on success, WP_Error on failure.
     */
    private function upload_image($file_name, $image_data, $source_url) {
        $this->logger->info(sprintf('Uploading image: %s', $file_name));
        
        // Get upload directory
        $upload_dir = wp_upload_dir();
        
        // Create file path
        $file_path = $upload_dir['path'] . '/' . $file_name;
        
        // Save image to file
        $result = file_put_contents($file_path, $image_data);
        
        if (!$result) {
            $this->logger->error(sprintf('Failed to save image: %s', $file_path));
            return new WP_Error(
                'save_failed',
                sprintf(__('Failed to save image: %s', 'demo-content-importer'), $file_path)
            );
        }
        
        // Check the file type
        $file_type = wp_check_filetype($file_name, null);
        
        if (!$file_type['type']) {
            @unlink($file_path);
            $this->logger->error(sprintf('Invalid file type: %s', $file_name));
            return new WP_Error(
                'invalid_file_type',
                sprintf(__('Invalid file type: %s', 'demo-content-importer'), $file_name)
            );
        }
        
        // Prepare attachment data
        $attachment = array(
            'post_mime_type' => $file_type['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $upload_dir['url'] . '/' . $file_name,
        );
        
        // Insert attachment
        $attachment_id = wp_insert_attachment($attachment, $file_path);
        
        if (is_wp_error($attachment_id)) {
            @unlink($file_path);
            $this->logger->error(sprintf('Failed to insert attachment: %s - %s', $file_name, $attachment_id->get_error_message()));
            return $attachment_id;
        }
        
        // Include image functions
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        
        // Generate attachment metadata
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        
        // Add source URL as meta
        update_post_meta($attachment_id, '_source_url', $source_url);
        
        $this->logger->success(sprintf('Uploaded image: %s (ID: %d)', $file_name, $attachment_id));
        
        return $attachment_id;
    }

    /**
     * Import media from a directory.
     *
     * @param string $directory Directory path.
     * @return array|WP_Error Results of the import.
     */
    public function import_media_directory($directory) {
        $this->logger->info(sprintf('Importing media from directory: %s', $directory));
        
        // Check if directory exists
        if (!is_dir($directory)) {
            $this->logger->error(sprintf('Directory not found: %s', $directory));
            return new WP_Error(
                'directory_not_found',
                sprintf(__('Directory not found: %s', 'demo-content-importer'), $directory)
            );
        }
        
        // Get all files in directory
        $files = glob($directory . '/*.*');
        
        if (empty($files)) {
            $this->logger->warning(sprintf('No files found in directory: %s', $directory));
            return array(
                'success' => true,
                'message' => __('No files found in directory', 'demo-content-importer'),
                'imported' => 0,
            );
        }
        
        // Import files
        $imported = 0;
        
        foreach ($files as $file) {
            // Skip non-image files
            $file_type = wp_check_filetype(basename($file), null);
            
            if (!$file_type['type'] || strpos($file_type['type'], 'image/') !== 0) {
                $this->logger->info(sprintf('Skipping non-image file: %s', basename($file)));
                continue;
            }
            
            // Import image
            $attachment_id = $this->import_local_image($file);
            
            if (!is_wp_error($attachment_id)) {
                $imported++;
            }
        }
        
        $this->logger->success(sprintf('Imported %d media files from directory', $imported));
        
        return array(
            'success' => true,
            'message' => sprintf(__('Imported %d media files', 'demo-content-importer'), $imported),
            'imported' => $imported,
        );
    }

    /**
     * Import local image.
     *
     * @param string $file Image file path.
     * @return int|WP_Error Attachment ID on success, WP_Error on failure.
     */
    private function import_local_image($file) {
        $this->logger->info(sprintf('Importing local image: %s', basename($file)));
        
        // Check if file exists
        if (!file_exists($file)) {
            $this->logger->error(sprintf('File not found: %s', $file));
            return new WP_Error(
                'file_not_found',
                sprintf(__('File not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Get upload directory
        $upload_dir = wp_upload_dir();
        
        // Generate unique file name
        $file_name = wp_unique_filename($upload_dir['path'], basename($file));
        
        // Create file path
        $new_file_path = $upload_dir['path'] . '/' . $file_name;
        
        // Copy file
        $result = copy($file, $new_file_path);
        
        if (!$result) {
            $this->logger->error(sprintf('Failed to copy file: %s', $file));
            return new WP_Error(
                'copy_failed',
                sprintf(__('Failed to copy file: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Check the file type
        $file_type = wp_check_filetype($file_name, null);
        
        // Prepare attachment data
        $attachment = array(
            'post_mime_type' => $file_type['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $upload_dir['url'] . '/' . $file_name,
        );
        
        // Insert attachment
        $attachment_id = wp_insert_attachment($attachment, $new_file_path);
        
        if (is_wp_error($attachment_id)) {
            @unlink($new_file_path);
            $this->logger->error(sprintf('Failed to insert attachment: %s - %s', $file_name, $attachment_id->get_error_message()));
            return $attachment_id;
        }
        
        // Include image functions
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        
        // Generate attachment metadata
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $new_file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        
        // Add source path as meta
        update_post_meta($attachment_id, '_source_path', $file);
        
        $this->logger->success(sprintf('Imported local image: %s (ID: %d)', basename($file), $attachment_id));
        
        return $attachment_id;
    }
}