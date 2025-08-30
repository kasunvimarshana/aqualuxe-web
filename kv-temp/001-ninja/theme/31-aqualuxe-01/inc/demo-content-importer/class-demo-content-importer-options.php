<?php
/**
 * Demo Content Importer Options
 *
 * Handles importing theme options and settings.
 *
 * @package DemoContentImporter
 * @subpackage Options
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Options Class
 */
class Demo_Content_Importer_Options {

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
     * Import options from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Results of the import.
     */
    public function import($file) {
        $this->logger->info('Starting options import');
        
        // Get options data
        $data = $this->get_options_data($file);
        
        if (is_wp_error($data)) {
            $this->logger->error('Failed to get options data: ' . $data->get_error_message());
            return $data;
        }
        
        // Import options
        $result = $this->import_options($data);
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to import options: ' . $result->get_error_message());
            return $result;
        }
        
        $this->logger->success('Options imported successfully');
        
        return array(
            'success' => true,
            'message' => __('Options imported successfully', 'demo-content-importer'),
        );
    }

    /**
     * Get options data from a JSON file.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Options data or error.
     */
    private function get_options_data($file) {
        // Check if file exists
        if (!file_exists($file)) {
            return new WP_Error(
                'file_not_found',
                sprintf(__('Options data file not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Get file contents
        $data = file_get_contents($file);
        
        if (empty($data)) {
            return new WP_Error(
                'empty_file',
                sprintf(__('Options data file is empty: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Decode the JSON data
        $data = json_decode($data, true);
        
        if (empty($data) || !is_array($data)) {
            return new WP_Error(
                'invalid_json',
                sprintf(__('Options data is not valid JSON: %s', 'demo-content-importer'), $file)
            );
        }
        
        return $data;
    }

    /**
     * Import options.
     *
     * @param array $data Options data.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function import_options($data) {
        // Import theme options
        if (isset($data['theme_options']) && is_array($data['theme_options'])) {
            $this->logger->info('Importing theme options');
            
            foreach ($data['theme_options'] as $option_name => $option_value) {
                // Skip certain options
                if (in_array($option_name, $this->get_excluded_options(), true)) {
                    $this->logger->info(sprintf('Skipping excluded option: %s', $option_name));
                    continue;
                }
                
                // Process option value
                $option_value = $this->process_option_value($option_value);
                
                // Update option
                update_option($option_name, $option_value);
                $this->logger->info(sprintf('Imported option: %s', $option_name));
            }
        }
        
        // Import site options
        if (isset($data['site_options']) && is_array($data['site_options'])) {
            $this->logger->info('Importing site options');
            
            foreach ($data['site_options'] as $option_name => $option_value) {
                // Skip certain options
                if (in_array($option_name, $this->get_excluded_options(), true)) {
                    $this->logger->info(sprintf('Skipping excluded option: %s', $option_name));
                    continue;
                }
                
                // Process option value
                $option_value = $this->process_option_value($option_value);
                
                // Update option
                update_site_option($option_name, $option_value);
                $this->logger->info(sprintf('Imported site option: %s', $option_name));
            }
        }
        
        return true;
    }

    /**
     * Process option value.
     *
     * @param mixed $value Option value.
     * @return mixed Processed option value.
     */
    private function process_option_value($value) {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->process_option_value($item);
            }
        } elseif (is_object($value)) {
            foreach (get_object_vars($value) as $key => $item) {
                $value->$key = $this->process_option_value($item);
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

    /**
     * Get excluded options.
     *
     * @return array Excluded options.
     */
    private function get_excluded_options() {
        return array(
            'siteurl',
            'home',
            'blogname',
            'blogdescription',
            'users_can_register',
            'admin_email',
            'start_of_week',
            'use_balanceTags',
            'use_smilies',
            'require_name_email',
            'comments_notify',
            'posts_per_rss',
            'rss_use_excerpt',
            'mailserver_url',
            'mailserver_login',
            'mailserver_pass',
            'mailserver_port',
            'default_category',
            'default_comment_status',
            'default_ping_status',
            'default_pingback_flag',
            'posts_per_page',
            'date_format',
            'time_format',
            'links_updated_date_format',
            'comment_moderation',
            'moderation_notify',
            'permalink_structure',
            'hack_file',
            'blog_charset',
            'moderation_keys',
            'active_plugins',
            'category_base',
            'ping_sites',
            'comment_max_links',
            'gmt_offset',
            'default_email_category',
            'recently_edited',
            'template',
            'stylesheet',
            'comment_whitelist',
            'comment_registration',
            'html_type',
            'use_trackback',
            'default_role',
            'db_version',
            'uploads_use_yearmonth_folders',
            'upload_path',
            'blog_public',
            'default_link_category',
            'show_on_front',
            'tag_base',
            'show_avatars',
            'avatar_rating',
            'upload_url_path',
            'thumbnail_size_w',
            'thumbnail_size_h',
            'thumbnail_crop',
            'medium_size_w',
            'medium_size_h',
            'avatar_default',
            'large_size_w',
            'large_size_h',
            'image_default_link_type',
            'image_default_size',
            'image_default_align',
            'close_comments_for_old_posts',
            'close_comments_days_old',
            'thread_comments',
            'thread_comments_depth',
            'page_comments',
            'comments_per_page',
            'default_comments_page',
            'comment_order',
            'sticky_posts',
            'widget_categories',
            'widget_text',
            'widget_rss',
            'uninstall_plugins',
            'timezone_string',
            'page_for_posts',
            'page_on_front',
            'default_post_format',
            'link_manager_enabled',
            'finished_splitting_shared_terms',
            'site_icon',
            'medium_large_size_w',
            'medium_large_size_h',
            'wp_page_for_privacy_policy',
            'show_comments_cookies_opt_in',
            'admin_email_lifespan',
            'disallowed_keys',
            'comment_previously_approved',
            'auto_update_core_dev',
            'auto_update_core_minor',
            'auto_update_core_major',
            'wp_force_deactivated_plugins',
            'initial_db_version',
            'WPLANG',
        );
    }

    /**
     * Import menus.
     *
     * @param string $file Path to the JSON file.
     * @return array|WP_Error Results of the import.
     */
    public function import_menus($file) {
        $this->logger->info('Starting menus import');
        
        // Get menus data
        $data = $this->get_options_data($file);
        
        if (is_wp_error($data)) {
            $this->logger->error('Failed to get menus data: ' . $data->get_error_message());
            return $data;
        }
        
        // Import menus
        $result = $this->import_menu_data($data);
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to import menus: ' . $result->get_error_message());
            return $result;
        }
        
        $this->logger->success('Menus imported successfully');
        
        return array(
            'success' => true,
            'message' => __('Menus imported successfully', 'demo-content-importer'),
        );
    }

    /**
     * Import menu data.
     *
     * @param array $data Menu data.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function import_menu_data($data) {
        // Check if menus data exists
        if (!isset($data['menus']) || !is_array($data['menus'])) {
            return new WP_Error(
                'invalid_menus_data',
                __('Invalid menus data', 'demo-content-importer')
            );
        }
        
        // Import menus
        $menu_ids = array();
        
        foreach ($data['menus'] as $menu_name => $menu_items) {
            $this->logger->info(sprintf('Importing menu: %s', $menu_name));
            
            // Check if menu exists
            $menu_exists = wp_get_nav_menu_object($menu_name);
            
            if ($menu_exists) {
                $menu_id = $menu_exists->term_id;
                $this->logger->info(sprintf('Menu already exists: %s (ID: %d)', $menu_name, $menu_id));
            } else {
                // Create menu
                $menu_id = wp_create_nav_menu($menu_name);
                
                if (is_wp_error($menu_id)) {
                    $this->logger->error(sprintf('Failed to create menu: %s - %s', $menu_name, $menu_id->get_error_message()));
                    continue;
                }
                
                $this->logger->info(sprintf('Created menu: %s (ID: %d)', $menu_name, $menu_id));
            }
            
            // Store menu ID
            $menu_ids[$menu_name] = $menu_id;
            
            // Import menu items
            if (isset($menu_items) && is_array($menu_items)) {
                $this->import_menu_items($menu_id, $menu_items);
            }
        }
        
        // Import menu locations
        if (isset($data['menu_locations']) && is_array($data['menu_locations'])) {
            $this->logger->info('Importing menu locations');
            
            $locations = get_theme_mod('nav_menu_locations', array());
            
            foreach ($data['menu_locations'] as $location => $menu_name) {
                if (isset($menu_ids[$menu_name])) {
                    $locations[$location] = $menu_ids[$menu_name];
                    $this->logger->info(sprintf('Assigned menu "%s" to location "%s"', $menu_name, $location));
                }
            }
            
            // Save menu locations
            set_theme_mod('nav_menu_locations', $locations);
        }
        
        return true;
    }

    /**
     * Import menu items.
     *
     * @param int   $menu_id    Menu ID.
     * @param array $menu_items Menu items.
     */
    private function import_menu_items($menu_id, $menu_items) {
        // Keep track of menu items
        $menu_item_ids = array();
        
        // First pass: Create all menu items
        foreach ($menu_items as $item) {
            $this->logger->info(sprintf('Importing menu item: %s', $item['title']));
            
            // Set up menu item data
            $menu_item_data = array(
                'menu-item-title' => $item['title'],
                'menu-item-status' => 'publish',
                'menu-item-type' => $item['type'],
                'menu-item-position' => $item['position'],
                'menu-item-classes' => isset($item['classes']) ? $item['classes'] : '',
                'menu-item-description' => isset($item['description']) ? $item['description'] : '',
                'menu-item-attr-title' => isset($item['attr_title']) ? $item['attr_title'] : '',
                'menu-item-target' => isset($item['target']) ? $item['target'] : '',
            );
            
            // Set menu item URL or object ID based on type
            switch ($item['type']) {
                case 'custom':
                    $menu_item_data['menu-item-url'] = $this->handle_url($item['url']);
                    break;
                    
                case 'post_type':
                    // Try to find the post by title
                    $post = get_page_by_title($item['title'], OBJECT, $item['object']);
                    
                    if ($post) {
                        $menu_item_data['menu-item-object-id'] = $post->ID;
                        $menu_item_data['menu-item-object'] = $item['object'];
                    } else {
                        $this->logger->warning(sprintf('Post not found for menu item: %s', $item['title']));
                        continue 2; // Skip this item
                    }
                    break;
                    
                case 'taxonomy':
                    // Try to find the term by name
                    $term = get_term_by('name', $item['title'], $item['object']);
                    
                    if ($term) {
                        $menu_item_data['menu-item-object-id'] = $term->term_id;
                        $menu_item_data['menu-item-object'] = $item['object'];
                    } else {
                        $this->logger->warning(sprintf('Term not found for menu item: %s', $item['title']));
                        continue 2; // Skip this item
                    }
                    break;
            }
            
            // Add menu item
            $menu_item_id = wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
            
            if (is_wp_error($menu_item_id)) {
                $this->logger->error(sprintf('Failed to import menu item: %s - %s', $item['title'], $menu_item_id->get_error_message()));
                continue;
            }
            
            // Store menu item ID
            $menu_item_ids[$item['id']] = $menu_item_id;
            $this->logger->info(sprintf('Imported menu item: %s (ID: %d)', $item['title'], $menu_item_id));
        }
        
        // Second pass: Set up parent-child relationships
        foreach ($menu_items as $item) {
            // Skip if item was not imported
            if (!isset($menu_item_ids[$item['id']])) {
                continue;
            }
            
            // Skip if item has no parent
            if (empty($item['parent_id'])) {
                continue;
            }
            
            // Skip if parent was not imported
            if (!isset($menu_item_ids[$item['parent_id']])) {
                $this->logger->warning(sprintf('Parent menu item not found for: %s', $item['title']));
                continue;
            }
            
            // Update parent
            $menu_item_id = $menu_item_ids[$item['id']];
            $parent_id = $menu_item_ids[$item['parent_id']];
            
            update_post_meta($menu_item_id, '_menu_item_menu_item_parent', $parent_id);
            $this->logger->info(sprintf('Updated menu item parent: %s -> %s', $item['title'], $parent_id));
        }
    }
}