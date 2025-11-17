<?php
/**
 * Demo Content Importer Reset
 *
 * Handles resetting the site to a clean state.
 *
 * @package DemoContentImporter
 * @subpackage Reset
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Reset Class
 */
class Demo_Content_Importer_Reset {

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
     * Reset site.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function reset_site() {
        $this->logger->info('Starting site reset');
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            $this->logger->error('Insufficient permissions to reset site');
            return new WP_Error(
                'insufficient_permissions',
                __('You do not have sufficient permissions to reset the site', 'demo-content-importer')
            );
        }
        
        // Backup database before reset
        $backup = new Demo_Content_Importer_Backup();
        $backup_file = $backup->backup_database();
        
        if (is_wp_error($backup_file)) {
            $this->logger->error('Failed to backup database before reset: ' . $backup_file->get_error_message());
            return $backup_file;
        }
        
        $this->logger->info('Database backed up before reset: ' . basename($backup_file));
        
        // Reset content
        $result = $this->reset_content();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset content: ' . $result->get_error_message());
            return $result;
        }
        
        // Reset uploads
        $result = $this->reset_uploads();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset uploads: ' . $result->get_error_message());
            return $result;
        }
        
        // Reset options
        $result = $this->reset_options();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset options: ' . $result->get_error_message());
            return $result;
        }
        
        // Reset widgets
        $result = $this->reset_widgets();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset widgets: ' . $result->get_error_message());
            return $result;
        }
        
        // Reset menus
        $result = $this->reset_menus();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset menus: ' . $result->get_error_message());
            return $result;
        }
        
        // Reset customizer
        $result = $this->reset_customizer();
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to reset customizer: ' . $result->get_error_message());
            return $result;
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        $this->logger->success('Site reset completed successfully');
        
        return true;
    }

    /**
     * Reset content.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_content() {
        global $wpdb;
        
        $this->logger->info('Resetting content');
        
        // Get current user ID
        $current_user_id = get_current_user_id();
        
        // Delete all posts
        $post_types = get_post_types(array('_builtin' => false));
        $post_types = array_merge($post_types, array('post', 'page', 'attachment'));
        
        $post_types_placeholders = implode(', ', array_fill(0, count($post_types), '%s'));
        
        $query = $wpdb->prepare(
            "DELETE FROM $wpdb->posts WHERE post_type IN ($post_types_placeholders) AND post_author != %d",
            array_merge($post_types, array($current_user_id))
        );
        
        $result = $wpdb->query($query);
        
        if ($result === false) {
            $this->logger->error('Failed to delete posts: ' . $wpdb->last_error);
            return new WP_Error(
                'delete_posts_failed',
                sprintf(__('Failed to delete posts: %s', 'demo-content-importer'), $wpdb->last_error)
            );
        }
        
        $this->logger->info(sprintf('Deleted %d posts', $result));
        
        // Delete all comments
        $query = "DELETE FROM $wpdb->comments";
        $result = $wpdb->query($query);
        
        if ($result === false) {
            $this->logger->error('Failed to delete comments: ' . $wpdb->last_error);
            return new WP_Error(
                'delete_comments_failed',
                sprintf(__('Failed to delete comments: %s', 'demo-content-importer'), $wpdb->last_error)
            );
        }
        
        $this->logger->info(sprintf('Deleted %d comments', $result));
        
        // Delete all terms
        $taxonomies = get_taxonomies();
        
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ));
            
            if (is_wp_error($terms)) {
                $this->logger->error(sprintf('Failed to get terms for taxonomy %s: %s', $taxonomy, $terms->get_error_message()));
                continue;
            }
            
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, $taxonomy);
            }
            
            $this->logger->info(sprintf('Deleted terms for taxonomy: %s', $taxonomy));
        }
        
        // Reset post and term counts
        $query = "DELETE FROM $wpdb->term_relationships";
        $wpdb->query($query);
        
        $query = "ALTER TABLE $wpdb->term_relationships AUTO_INCREMENT = 1";
        $wpdb->query($query);
        
        $this->logger->info('Reset term relationships');
        
        return true;
    }

    /**
     * Reset uploads.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_uploads() {
        $this->logger->info('Resetting uploads');
        
        // Get upload directory
        $upload_dir = wp_upload_dir();
        
        // Skip if uploads directory doesn't exist
        if (!is_dir($upload_dir['basedir'])) {
            $this->logger->warning('Uploads directory not found: ' . $upload_dir['basedir']);
            return true;
        }
        
        // Delete all files in uploads directory
        $this->delete_directory_contents($upload_dir['basedir']);
        
        $this->logger->info('Uploads directory reset');
        
        return true;
    }

    /**
     * Delete directory contents.
     *
     * @param string $dir Directory path.
     */
    private function delete_directory_contents($dir) {
        // Skip if directory doesn't exist
        if (!is_dir($dir)) {
            return;
        }
        
        // Get all files and directories
        $files = scandir($dir);
        
        foreach ($files as $file) {
            // Skip . and ..
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            // Skip .htaccess and index.php
            if ($file === '.htaccess' || $file === 'index.php') {
                continue;
            }
            
            $path = $dir . '/' . $file;
            
            if (is_dir($path)) {
                // Delete directory contents
                $this->delete_directory_contents($path);
                
                // Delete directory
                rmdir($path);
            } else {
                // Delete file
                unlink($path);
            }
        }
    }

    /**
     * Reset options.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_options() {
        global $wpdb;
        
        $this->logger->info('Resetting options');
        
        // Get excluded options
        $excluded_options = $this->get_excluded_options();
        
        // Convert excluded options to placeholders
        $excluded_placeholders = implode(', ', array_fill(0, count($excluded_options), '%s'));
        
        // Delete all options except excluded ones
        $query = $wpdb->prepare(
            "DELETE FROM $wpdb->options WHERE option_name NOT IN ($excluded_placeholders) AND option_name NOT LIKE %s AND option_name NOT LIKE %s",
            array_merge($excluded_options, array('_%transient_%', '_%site_transient_%'))
        );
        
        $result = $wpdb->query($query);
        
        if ($result === false) {
            $this->logger->error('Failed to delete options: ' . $wpdb->last_error);
            return new WP_Error(
                'delete_options_failed',
                sprintf(__('Failed to delete options: %s', 'demo-content-importer'), $wpdb->last_error)
            );
        }
        
        $this->logger->info(sprintf('Deleted %d options', $result));
        
        return true;
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
     * Reset widgets.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_widgets() {
        global $wpdb;
        
        $this->logger->info('Resetting widgets');
        
        // Delete all widgets
        $query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'widget_%'";
        $result = $wpdb->query($query);
        
        if ($result === false) {
            $this->logger->error('Failed to delete widgets: ' . $wpdb->last_error);
            return new WP_Error(
                'delete_widgets_failed',
                sprintf(__('Failed to delete widgets: %s', 'demo-content-importer'), $wpdb->last_error)
            );
        }
        
        $this->logger->info(sprintf('Deleted %d widget options', $result));
        
        // Reset sidebars
        update_option('sidebars_widgets', array('wp_inactive_widgets' => array()));
        
        $this->logger->info('Reset sidebars');
        
        return true;
    }

    /**
     * Reset menus.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_menus() {
        $this->logger->info('Resetting menus');
        
        // Get all menus
        $menus = wp_get_nav_menus();
        
        if (is_wp_error($menus)) {
            $this->logger->error('Failed to get menus: ' . $menus->get_error_message());
            return $menus;
        }
        
        // Delete all menus
        foreach ($menus as $menu) {
            wp_delete_nav_menu($menu->term_id);
        }
        
        $this->logger->info(sprintf('Deleted %d menus', count($menus)));
        
        // Reset menu locations
        set_theme_mod('nav_menu_locations', array());
        
        $this->logger->info('Reset menu locations');
        
        return true;
    }

    /**
     * Reset customizer.
     *
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function reset_customizer() {
        $this->logger->info('Resetting customizer');
        
        // Get theme mods
        $theme_mods = get_theme_mods();
        
        if (!is_array($theme_mods)) {
            $this->logger->warning('No theme mods found');
            return true;
        }
        
        // Reset theme mods
        foreach ($theme_mods as $key => $value) {
            // Skip nav_menu_locations (already reset)
            if ($key === 'nav_menu_locations') {
                continue;
            }
            
            remove_theme_mod($key);
        }
        
        $this->logger->info(sprintf('Reset %d theme mods', count($theme_mods)));
        
        return true;
    }
}