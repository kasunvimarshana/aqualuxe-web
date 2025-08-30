<?php
/**
 * Demo Content Importer Content
 *
 * Handles importing content (posts, pages, etc.).
 *
 * @package DemoContentImporter
 * @subpackage Content
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Content Class
 */
class Demo_Content_Importer_Content {

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger;

    /**
     * WP Importer instance.
     *
     * @var WP_Import
     */
    protected $importer;

    /**
     * Media mapping.
     *
     * @var array
     */
    protected $media_mapping = array();

    /**
     * Post mapping.
     *
     * @var array
     */
    protected $post_mapping = array();

    /**
     * Term mapping.
     *
     * @var array
     */
    protected $term_mapping = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
        
        // Include WordPress Importer
        if (!class_exists('WP_Import')) {
            require_once DCI_INCLUDES_DIR . '/wordpress-importer/class-wp-import.php';
        }
        
        // Initialize WP Importer
        $this->importer = new WP_Import();
    }

    /**
     * Import content from a WXR file.
     *
     * @param string $file Path to the WXR file.
     * @return array|WP_Error Results of the import.
     */
    public function import($file) {
        $this->logger->info('Starting content import');
        
        // Check if file exists
        if (!file_exists($file)) {
            $this->logger->error(sprintf('Content file not found: %s', $file));
            return new WP_Error(
                'file_not_found',
                sprintf(__('Content file not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Set import options
        $this->importer->fetch_attachments = true;
        
        // Start the import
        ob_start();
        $this->importer->import($file);
        $output = ob_get_clean();
        
        // Get import results
        $this->media_mapping = $this->importer->url_remap;
        $this->post_mapping = $this->importer->processed_posts;
        $this->term_mapping = $this->importer->processed_terms;
        
        // Log import results
        $this->logger->info(sprintf('Imported %d posts', count($this->post_mapping)));
        $this->logger->info(sprintf('Imported %d terms', count($this->term_mapping)));
        $this->logger->info(sprintf('Imported %d media items', count($this->media_mapping)));
        
        // Process post meta
        $this->process_post_meta();
        
        // Set homepage
        $this->set_homepage();
        
        // Set blog page
        $this->set_blog_page();
        
        $this->logger->success('Content imported successfully');
        
        return array(
            'success' => true,
            'message' => __('Content imported successfully', 'demo-content-importer'),
            'stats' => array(
                'posts' => count($this->post_mapping),
                'terms' => count($this->term_mapping),
                'media' => count($this->media_mapping),
            ),
        );
    }

    /**
     * Process post meta.
     */
    private function process_post_meta() {
        global $wpdb;
        
        $this->logger->info('Processing post meta');
        
        // Get all post meta with serialized values
        $meta_rows = $wpdb->get_results(
            "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta 
            WHERE meta_value LIKE '%s:%' OR meta_value LIKE '%a:%' OR meta_value LIKE '%O:%'",
            ARRAY_A
        );
        
        if (!empty($meta_rows)) {
            foreach ($meta_rows as $meta_row) {
                $meta_value = maybe_unserialize($meta_row['meta_value']);
                
                // Skip if not an array or object
                if (!is_array($meta_value) && !is_object($meta_value)) {
                    continue;
                }
                
                // Process meta value
                $processed_value = $this->process_meta_value($meta_value);
                
                // Update meta if changed
                if ($processed_value !== $meta_value) {
                    update_post_meta($meta_row['post_id'], $meta_row['meta_key'], $processed_value);
                    $this->logger->info(sprintf('Updated post meta: %d - %s', $meta_row['post_id'], $meta_row['meta_key']));
                }
            }
        }
    }

    /**
     * Process meta value recursively.
     *
     * @param mixed $value Meta value.
     * @return mixed Processed meta value.
     */
    private function process_meta_value($value) {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->process_meta_value($item);
            }
        } elseif (is_object($value)) {
            foreach (get_object_vars($value) as $key => $item) {
                $value->$key = $this->process_meta_value($item);
            }
        } elseif (is_string($value)) {
            // Handle image URLs
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)/i', $value)) {
                $value = $this->handle_image_url($value);
            }
            
            // Handle other URLs
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                $value = $this->handle_url($value);
            }
        }
        
        return $value;
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
        
        // Check if URL is in media mapping
        foreach ($this->media_mapping as $old_url => $new_url) {
            if ($old_url === $url) {
                return $new_url;
            }
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
        
        // Add to media mapping
        $this->media_mapping[$url] = $new_url;
        
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

    /**
     * Set homepage.
     */
    private function set_homepage() {
        // Get homepage by title
        $homepage = get_page_by_title('Home');
        
        if ($homepage) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $homepage->ID);
            $this->logger->info(sprintf('Set homepage: %d', $homepage->ID));
        }
    }

    /**
     * Set blog page.
     */
    private function set_blog_page() {
        // Get blog page by title
        $blog_page = get_page_by_title('Blog');
        
        if ($blog_page) {
            update_option('page_for_posts', $blog_page->ID);
            $this->logger->info(sprintf('Set blog page: %d', $blog_page->ID));
        }
    }
}