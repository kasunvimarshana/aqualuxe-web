<?php
/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package WordPress
 * @subpackage Importer
 */

if (!class_exists('WP_Importer')) {
    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    if (file_exists($class_wp_importer)) {
        require $class_wp_importer;
    }
}

/**
 * WordPress importer class.
 */
class WP_Import extends WP_Importer {
    var $max_wxr_version = 1.2; // max. supported WXR version

    var $id; // WXR attachment ID
    var $file;
    var $import_start_time;
    var $version;
    var $authors = array();
    var $posts = array();
    var $terms = array();
    var $categories = array();
    var $tags = array();
    var $base_url = '';
    var $processed_authors = array();
    var $author_mapping = array();
    var $processed_terms = array();
    var $processed_posts = array();
    var $post_orphans = array();
    var $processed_menu_items = array();
    var $menu_item_orphans = array();
    var $missing_menu_items = array();
    var $fetch_attachments = false;
    var $url_remap = array();
    var $featured_images = array();
    var $logger;

    /**
     * Constructor
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
    }

    /**
     * Registered callback function for the WordPress Importer
     *
     * Manages the three separate stages of the WXR import process
     */
    public function dispatch() {
        $this->header();

        $step = empty($_GET['step']) ? 0 : (int) $_GET['step'];
        switch ($step) {
            case 0:
                $this->greet();
                break;
            case 1:
                check_admin_referer('import-upload');
                if ($this->handle_upload()) {
                    $this->import_options();
                }
                break;
            case 2:
                check_admin_referer('import-wordpress');
                $this->fetch_attachments = (!empty($_POST['fetch_attachments']) && $this->allow_fetch_attachments());
                $this->id = (int) $_POST['import_id'];
                $file = get_attached_file($this->id);
                set_time_limit(0);
                $this->import($file);
                break;
        }

        $this->footer();
    }

    /**
     * The main controller for the actual import stage.
     *
     * @param string $file Path to the WXR file for importing
     */
    public function import($file) {
        add_filter('import_post_meta_key', array($this, 'is_valid_meta_key'));
        add_filter('http_request_timeout', array($this, 'bump_request_timeout'));

        $this->import_start_time = time();
        $this->file = $file;

        $this->logger->info('Starting import process');
        $this->logger->info('Import file: ' . $file);

        // Attempt to import the file
        $this->import_start($file);
    }

    /**
     * Start the import process
     *
     * @param string $file Path to the WXR file for importing
     */
    private function import_start($file) {
        // Get the XML parser
        $parser = new WXR_Parser();
        $import_data = $parser->parse($file);

        if (is_wp_error($import_data)) {
            $this->logger->error('Error parsing the WXR file: ' . $import_data->get_error_message());
            return;
        }

        $this->version = $import_data['version'];
        $this->authors = $import_data['authors'];
        $this->posts = $import_data['posts'];
        $this->terms = $import_data['terms'];
        $this->categories = $import_data['categories'];
        $this->tags = $import_data['tags'];
        $this->base_url = $import_data['base_url'];

        // Process the import data
        $this->process_authors();
        $this->process_categories();
        $this->process_tags();
        $this->process_terms();
        $this->process_posts();
    }

    /**
     * Create new categories based on import information
     */
    private function process_categories() {
        $this->logger->info('Processing categories');
        
        if (empty($this->categories)) {
            $this->logger->info('No categories to process');
            return;
        }

        foreach ($this->categories as $cat) {
            // If the category already exists, leave it alone
            $term_id = term_exists($cat['category_nicename'], 'category');
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($cat['term_id'])) {
                    $this->processed_terms[intval($cat['term_id'])] = (int) $term_id;
                }
                $this->logger->info('Category already exists: ' . $cat['category_nicename']);
                continue;
            }

            $parent = empty($cat['category_parent']) ? 0 : category_exists($cat['category_parent']);
            $description = isset($cat['category_description']) ? $cat['category_description'] : '';

            $data = array(
                'category_nicename' => $cat['category_nicename'],
                'category_parent' => $parent,
                'cat_name' => $cat['cat_name'],
                'category_description' => $description
            );

            $id = wp_insert_category($data);
            if (!is_wp_error($id) && $id > 0) {
                if (isset($cat['term_id'])) {
                    $this->processed_terms[intval($cat['term_id'])] = $id;
                }
                $this->logger->info('Imported category: ' . $cat['cat_name']);
            } else {
                $this->logger->error('Failed to import category: ' . $cat['cat_name']);
                if (is_wp_error($id)) {
                    $this->logger->error($id->get_error_message());
                }
            }
        }
    }

    /**
     * Create new post tags based on import information
     */
    private function process_tags() {
        $this->logger->info('Processing tags');
        
        if (empty($this->tags)) {
            $this->logger->info('No tags to process');
            return;
        }

        foreach ($this->tags as $tag) {
            // If the tag already exists, leave it alone
            $term_id = term_exists($tag['tag_slug'], 'post_tag');
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($tag['term_id'])) {
                    $this->processed_terms[intval($tag['term_id'])] = (int) $term_id;
                }
                $this->logger->info('Tag already exists: ' . $tag['tag_name']);
                continue;
            }

            $description = isset($tag['tag_description']) ? $tag['tag_description'] : '';
            $args = array('slug' => $tag['tag_slug'], 'description' => $description);

            $id = wp_insert_term($tag['tag_name'], 'post_tag', $args);
            if (!is_wp_error($id)) {
                if (isset($tag['term_id'])) {
                    $this->processed_terms[intval($tag['term_id'])] = $id['term_id'];
                }
                $this->logger->info('Imported tag: ' . $tag['tag_name']);
            } else {
                $this->logger->error('Failed to import tag: ' . $tag['tag_name']);
                $this->logger->error($id->get_error_message());
            }
        }
    }

    /**
     * Create new terms based on import information
     */
    private function process_terms() {
        $this->logger->info('Processing terms');
        
        if (empty($this->terms)) {
            $this->logger->info('No terms to process');
            return;
        }

        foreach ($this->terms as $term) {
            // If the term already exists in the correct taxonomy, leave it alone
            $term_id = term_exists($term['slug'], $term['term_taxonomy']);
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($term['term_id'])) {
                    $this->processed_terms[intval($term['term_id'])] = (int) $term_id;
                }
                $this->logger->info('Term already exists: ' . $term['term_name']);
                continue;
            }

            if (empty($term['term_parent'])) {
                $parent = 0;
            } else {
                $parent = term_exists($term['term_parent'], $term['term_taxonomy']);
                if (is_array($parent)) {
                    $parent = $parent['term_id'];
                }
            }

            $description = isset($term['term_description']) ? $term['term_description'] : '';
            $args = array('slug' => $term['slug'], 'description' => $description, 'parent' => $parent);

            $id = wp_insert_term($term['term_name'], $term['term_taxonomy'], $args);
            if (!is_wp_error($id)) {
                if (isset($term['term_id'])) {
                    $this->processed_terms[intval($term['term_id'])] = $id['term_id'];
                }
                $this->logger->info('Imported term: ' . $term['term_name']);
            } else {
                $this->logger->error('Failed to import term: ' . $term['term_name']);
                $this->logger->error($id->get_error_message());
            }
        }
    }

    /**
     * Create new user authors based on import information
     */
    private function process_authors() {
        $this->logger->info('Processing authors');
        
        if (empty($this->authors)) {
            $this->logger->info('No authors to process');
            return;
        }

        foreach ($this->authors as $author) {
            // If the author already exists, leave it alone
            if (username_exists($author['author_login'])) {
                $user = get_user_by('login', $author['author_login']);
                $this->processed_authors[$author['author_login']] = $user->ID;
                $this->author_mapping[$author['author_id']] = $user->ID;
                $this->logger->info('Author already exists: ' . $author['author_login']);
                continue;
            }

            // Create the user
            $userdata = array(
                'user_login' => $author['author_login'],
                'user_pass' => wp_generate_password(),
                'user_email' => isset($author['author_email']) ? $author['author_email'] : '',
                'display_name' => $author['author_display_name'],
                'first_name' => isset($author['author_first_name']) ? $author['author_first_name'] : '',
                'last_name' => isset($author['author_last_name']) ? $author['author_last_name'] : '',
            );

            $user_id = wp_insert_user($userdata);
            if (!is_wp_error($user_id)) {
                $this->processed_authors[$author['author_login']] = $user_id;
                $this->author_mapping[$author['author_id']] = $user_id;
                $this->logger->info('Imported author: ' . $author['author_login']);
            } else {
                $this->logger->error('Failed to import author: ' . $author['author_login']);
                $this->logger->error($user_id->get_error_message());
            }
        }
    }

    /**
     * Create new posts based on import information
     */
    private function process_posts() {
        $this->logger->info('Processing posts');
        
        if (empty($this->posts)) {
            $this->logger->info('No posts to process');
            return;
        }

        foreach ($this->posts as $post) {
            $this->process_post($post);
        }

        // Process any orphaned menu items
        $this->process_menu_item_orphans();
    }

    /**
     * Process a single post
     *
     * @param array $post Post data
     */
    private function process_post($post) {
        // Skip post if it already exists
        $post_exists = post_exists($post['post_title'], '', $post['post_date']);
        if ($post_exists && get_post_type($post_exists) == $post['post_type']) {
            $this->processed_posts[intval($post['post_id'])] = intval($post_exists);
            $this->logger->info('Post already exists: ' . $post['post_title']);
            return;
        }

        // Map the author
        if (isset($post['post_author']) && !empty($post['post_author'])) {
            if (isset($this->author_mapping[$post['post_author']])) {
                $post['post_author'] = $this->author_mapping[$post['post_author']];
            } else {
                $post['post_author'] = get_current_user_id();
            }
        } else {
            $post['post_author'] = get_current_user_id();
        }

        // Set post status
        if (isset($post['status']) && !empty($post['status'])) {
            $post['post_status'] = $post['status'];
        }

        // Process post content
        $post['post_content'] = $this->process_post_content($post['post_content']);

        // Create the post
        $post_id = wp_insert_post($post, true);

        if (is_wp_error($post_id)) {
            $this->logger->error('Failed to import post: ' . $post['post_title']);
            $this->logger->error($post_id->get_error_message());
            return;
        }

        $this->processed_posts[intval($post['post_id'])] = intval($post_id);
        $this->logger->info('Imported post: ' . $post['post_title']);

        // Process post meta
        if (isset($post['postmeta']) && is_array($post['postmeta'])) {
            foreach ($post['postmeta'] as $meta) {
                $key = $meta['key'];
                $value = $meta['value'];

                // Skip certain meta keys
                if ($key == '_edit_lock' || $key == '_edit_last') {
                    continue;
                }

                // Process meta value
                $value = $this->process_meta_value($value);

                // Add the meta
                add_post_meta($post_id, $key, $value);
            }
        }

        // Process terms
        if (isset($post['terms']) && is_array($post['terms'])) {
            $terms_to_set = array();
            foreach ($post['terms'] as $term) {
                // Back compat with WXR 1.0
                $term_id = isset($term['term_id']) ? $term['term_id'] : $term['term_taxonomy_id'];

                if (isset($this->processed_terms[$term_id])) {
                    $terms_to_set[$term['taxonomy']][] = intval($this->processed_terms[$term_id]);
                }
            }

            foreach ($terms_to_set as $tax => $ids) {
                wp_set_object_terms($post_id, $ids, $tax);
            }
        }

        // Process comments
        if (isset($post['comments']) && is_array($post['comments'])) {
            foreach ($post['comments'] as $comment) {
                $comment['comment_post_ID'] = $post_id;
                $comment_id = wp_insert_comment($comment);
            }
        }

        // Process attachments
        if ($post['post_type'] == 'attachment' && $this->fetch_attachments) {
            $this->process_attachment($post, $post_id);
        }

        // Process featured image
        if (isset($post['postmeta'])) {
            foreach ($post['postmeta'] as $meta) {
                if ($meta['key'] == '_thumbnail_id' && isset($this->processed_posts[intval($meta['value'])])) {
                    set_post_thumbnail($post_id, $this->processed_posts[intval($meta['value'])]);
                }
            }
        }
    }

    /**
     * Process post content
     *
     * @param string $content Post content
     * @return string Processed post content
     */
    private function process_post_content($content) {
        // Process shortcodes
        $content = $this->process_shortcodes($content);

        // Process URLs
        $content = $this->process_urls($content);

        return $content;
    }

    /**
     * Process shortcodes in content
     *
     * @param string $content Content with shortcodes
     * @return string Processed content
     */
    private function process_shortcodes($content) {
        // Process shortcodes with IDs
        $pattern = '/\[(\w+)(\s+)id="(\d+)"/i';
        $content = preg_replace_callback($pattern, array($this, 'process_shortcode_id'), $content);

        return $content;
    }

    /**
     * Process shortcode ID
     *
     * @param array $matches Regex matches
     * @return string Processed shortcode
     */
    private function process_shortcode_id($matches) {
        $shortcode = $matches[1];
        $space = $matches[2];
        $old_id = $matches[3];

        if (isset($this->processed_posts[$old_id])) {
            $new_id = $this->processed_posts[$old_id];
            return '[' . $shortcode . $space . 'id="' . $new_id . '"';
        }

        return $matches[0];
    }

    /**
     * Process URLs in content
     *
     * @param string $content Content with URLs
     * @return string Processed content
     */
    private function process_urls($content) {
        // Replace old site URL with new site URL
        if (!empty($this->base_url)) {
            $content = str_replace($this->base_url, site_url(), $content);
        }

        // Replace attachment URLs
        foreach ($this->url_remap as $old_url => $new_url) {
            $content = str_replace($old_url, $new_url, $content);
        }

        return $content;
    }

    /**
     * Process meta value
     *
     * @param mixed $value Meta value
     * @return mixed Processed meta value
     */
    private function process_meta_value($value) {
        // If value is serialized, unserialize it
        if (is_serialized($value)) {
            $value = maybe_unserialize($value);

            // Process array or object
            if (is_array($value) || is_object($value)) {
                $value = $this->process_meta_array_or_object($value);
            }

            // Serialize back
            $value = maybe_serialize($value);
        } else if (is_string($value)) {
            // Process URLs in string
            $value = $this->process_urls($value);
        }

        return $value;
    }

    /**
     * Process meta array or object
     *
     * @param mixed $data Array or object
     * @return mixed Processed array or object
     */
    private function process_meta_array_or_object($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->process_meta_array_or_object($value);
                } else if (is_string($value)) {
                    $data[$key] = $this->process_urls($value);
                }
            }
        } else if (is_object($data)) {
            foreach (get_object_vars($data) as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data->$key = $this->process_meta_array_or_object($value);
                } else if (is_string($value)) {
                    $data->$key = $this->process_urls($value);
                }
            }
        }

        return $data;
    }

    /**
     * Process an attachment
     *
     * @param array $post Attachment post data
     * @param int $post_id Post ID
     */
    private function process_attachment($post, $post_id) {
        $url = $post['attachment_url'];
        $this->logger->info('Processing attachment: ' . $url);

        // If the URL is already local, skip it
        if (strpos($url, site_url()) === 0) {
            $this->logger->info('Attachment URL is already local: ' . $url);
            return;
        }

        // Download the attachment
        $upload = $this->fetch_remote_file($url, $post);
        if (is_wp_error($upload)) {
            $this->logger->error('Failed to import attachment: ' . $url);
            $this->logger->error($upload->get_error_message());
            return;
        }

        // Update the attachment post
        $attachment = array(
            'ID' => $post_id,
            'guid' => $upload['url'],
        );
        wp_update_post($attachment);
        update_attached_file($post_id, $upload['file']);

        // Generate attachment metadata
        $attachment_metadata = wp_generate_attachment_metadata($post_id, $upload['file']);
        wp_update_attachment_metadata($post_id, $attachment_metadata);

        // Map the old URL to the new URL
        $this->url_remap[$url] = $upload['url'];
        $this->url_remap[$post['guid']] = $upload['url'];

        $this->logger->info('Imported attachment: ' . $url . ' -> ' . $upload['url']);
    }

    /**
     * Attempt to download a remote file attachment
     *
     * @param string $url URL of item to fetch
     * @param array $post Attachment post data
     * @return array|WP_Error Local file location details on success, WP_Error otherwise
     */
    private function fetch_remote_file($url, $post) {
        // Extract the file name from the URL
        $file_name = basename(parse_url($url, PHP_URL_PATH));

        // Get upload directory
        $upload = wp_upload_dir($post['post_date']);
        if ($upload['error']) {
            return new WP_Error('upload_dir_error', $upload['error']);
        }

        // Generate unique file name
        $file_name = wp_unique_filename($upload['path'], $file_name);
        $file_path = $upload['path'] . '/' . $file_name;

        // Download the file
        $response = wp_remote_get($url, array(
            'timeout' => 300,
            'stream' => true,
            'filename' => $file_path,
        ));

        if (is_wp_error($response)) {
            @unlink($file_path);
            return $response;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code != 200) {
            @unlink($file_path);
            return new WP_Error('import_file_error', sprintf(__('Remote server returned error response %1$d %2$s', 'demo-content-importer'), $response_code, get_status_header_desc($response_code)));
        }

        $file_size = filesize($file_path);
        if (0 == $file_size) {
            @unlink($file_path);
            return new WP_Error('import_file_error', __('Zero size file downloaded', 'demo-content-importer'));
        }

        // Set correct file permissions
        $stat = stat(dirname($file_path));
        $perms = $stat['mode'] & 0000666;
        chmod($file_path, $perms);

        return array(
            'file' => $file_path,
            'url' => $upload['url'] . '/' . $file_name,
        );
    }

    /**
     * Process menu item orphans
     */
    private function process_menu_item_orphans() {
        $this->logger->info('Processing menu item orphans');
        
        if (empty($this->menu_item_orphans)) {
            $this->logger->info('No menu item orphans to process');
            return;
        }

        foreach ($this->menu_item_orphans as $child_id => $parent_id) {
            if (isset($this->processed_menu_items[$parent_id])) {
                $processed_parent_id = $this->processed_menu_items[$parent_id];
                update_post_meta($child_id, '_menu_item_menu_item_parent', $processed_parent_id);
                $this->logger->info('Updated menu item parent: ' . $child_id . ' -> ' . $processed_parent_id);
            }
        }
    }

    /**
     * Bump up the request timeout for importing
     *
     * @param int $val The request timeout value
     * @return int The request timeout value
     */
    public function bump_request_timeout($val) {
        return 300;
    }

    /**
     * Check if the meta key is valid for import
     *
     * @param string $key The meta key
     * @return string The meta key
     */
    public function is_valid_meta_key($key) {
        // Skip internal meta keys
        if (in_array($key, array('_wp_attached_file', '_wp_attachment_metadata', '_edit_lock', '_edit_last'))) {
            return $key;
        }

        return $key;
    }
}

/**
 * WXR Parser class
 */
class WXR_Parser {
    /**
     * Parse a WXR file
     *
     * @param string $file Path to WXR file
     * @return array|WP_Error Information extracted from the WXR file
     */
    public function parse($file) {
        // Load the XML file
        $xml = simplexml_load_file($file);
        if (!$xml) {
            return new WP_Error('WXR_parse_error', __('Could not parse the WXR file', 'demo-content-importer'));
        }

        // Get namespaces
        $namespaces = $xml->getNamespaces(true);
        
        // Check if this is a WXR file
        if (!isset($namespaces['wp'])) {
            return new WP_Error('WXR_parse_error', __('This does not appear to be a WXR file', 'demo-content-importer'));
        }

        // Get the WordPress namespace
        $wp = $xml->children($namespaces['wp']);

        // Get the base URL
        $base_url = $wp->xpath('/rss/channel/wp:base_site_url');
        $base_url = (string) $base_url[0];

        // Get authors
        $authors = array();
        foreach ($wp->xpath('/rss/channel/wp:author') as $author) {
            $authors[] = array(
                'author_id' => (string) $author->xpath('wp:author_id')[0],
                'author_login' => (string) $author->xpath('wp:author_login')[0],
                'author_email' => (string) $author->xpath('wp:author_email')[0],
                'author_display_name' => (string) $author->xpath('wp:author_display_name')[0],
                'author_first_name' => (string) $author->xpath('wp:author_first_name')[0],
                'author_last_name' => (string) $author->xpath('wp:author_last_name')[0],
            );
        }

        // Get categories
        $categories = array();
        foreach ($wp->xpath('/rss/channel/wp:category') as $category) {
            $categories[] = array(
                'term_id' => (string) $category->xpath('wp:term_id')[0],
                'category_nicename' => (string) $category->xpath('wp:category_nicename')[0],
                'category_parent' => (string) $category->xpath('wp:category_parent')[0],
                'cat_name' => (string) $category->xpath('wp:cat_name')[0],
                'category_description' => (string) $category->xpath('wp:category_description')[0],
            );
        }

        // Get tags
        $tags = array();
        foreach ($wp->xpath('/rss/channel/wp:tag') as $tag) {
            $tags[] = array(
                'term_id' => (string) $tag->xpath('wp:term_id')[0],
                'tag_slug' => (string) $tag->xpath('wp:tag_slug')[0],
                'tag_name' => (string) $tag->xpath('wp:tag_name')[0],
                'tag_description' => (string) $tag->xpath('wp:tag_description')[0],
            );
        }

        // Get terms
        $terms = array();
        foreach ($wp->xpath('/rss/channel/wp:term') as $term) {
            $terms[] = array(
                'term_id' => (string) $term->xpath('wp:term_id')[0],
                'term_taxonomy' => (string) $term->xpath('wp:term_taxonomy')[0],
                'slug' => (string) $term->xpath('wp:term_slug')[0],
                'term_parent' => (string) $term->xpath('wp:term_parent')[0],
                'term_name' => (string) $term->xpath('wp:term_name')[0],
                'term_description' => (string) $term->xpath('wp:term_description')[0],
            );
        }

        // Get posts
        $posts = array();
        foreach ($xml->channel->item as $item) {
            $post = array(
                'post_title' => (string) $item->title,
                'guid' => (string) $item->guid,
                'post_author' => (string) $item->xpath('dc:creator')[0],
                'post_content' => (string) $item->xpath('content:encoded')[0],
                'post_excerpt' => (string) $item->xpath('excerpt:encoded')[0],
                'post_id' => (string) $item->xpath('wp:post_id')[0],
                'post_date' => (string) $item->xpath('wp:post_date')[0],
                'post_date_gmt' => (string) $item->xpath('wp:post_date_gmt')[0],
                'comment_status' => (string) $item->xpath('wp:comment_status')[0],
                'ping_status' => (string) $item->xpath('wp:ping_status')[0],
                'post_name' => (string) $item->xpath('wp:post_name')[0],
                'status' => (string) $item->xpath('wp:status')[0],
                'post_parent' => (string) $item->xpath('wp:post_parent')[0],
                'menu_order' => (string) $item->xpath('wp:menu_order')[0],
                'post_type' => (string) $item->xpath('wp:post_type')[0],
                'post_password' => (string) $item->xpath('wp:post_password')[0],
                'is_sticky' => (string) $item->xpath('wp:is_sticky')[0],
                'attachment_url' => (string) $item->xpath('wp:attachment_url')[0],
            );

            // Get post meta
            $postmeta = array();
            foreach ($item->xpath('wp:postmeta') as $meta) {
                $postmeta[] = array(
                    'key' => (string) $meta->xpath('wp:meta_key')[0],
                    'value' => (string) $meta->xpath('wp:meta_value')[0],
                );
            }
            $post['postmeta'] = $postmeta;

            // Get terms
            $post_terms = array();
            foreach ($item->category as $category) {
                $attrs = $category->attributes();
                $post_terms[] = array(
                    'domain' => (string) $attrs['domain'],
                    'nicename' => (string) $attrs['nicename'],
                    'term_id' => (string) $attrs['term_id'],
                    'taxonomy' => (string) $attrs['domain'],
                );
            }
            $post['terms'] = $post_terms;

            // Get comments
            $comments = array();
            foreach ($item->xpath('wp:comment') as $comment) {
                $comments[] = array(
                    'comment_id' => (string) $comment->xpath('wp:comment_id')[0],
                    'comment_author' => (string) $comment->xpath('wp:comment_author')[0],
                    'comment_author_email' => (string) $comment->xpath('wp:comment_author_email')[0],
                    'comment_author_url' => (string) $comment->xpath('wp:comment_author_url')[0],
                    'comment_author_IP' => (string) $comment->xpath('wp:comment_author_IP')[0],
                    'comment_date' => (string) $comment->xpath('wp:comment_date')[0],
                    'comment_date_gmt' => (string) $comment->xpath('wp:comment_date_gmt')[0],
                    'comment_content' => (string) $comment->xpath('wp:comment_content')[0],
                    'comment_approved' => (string) $comment->xpath('wp:comment_approved')[0],
                    'comment_type' => (string) $comment->xpath('wp:comment_type')[0],
                    'comment_parent' => (string) $comment->xpath('wp:comment_parent')[0],
                    'comment_user_id' => (string) $comment->xpath('wp:comment_user_id')[0],
                );
            }
            $post['comments'] = $comments;

            $posts[] = $post;
        }

        return array(
            'version' => '1.2',
            'authors' => $authors,
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'terms' => $terms,
            'base_url' => $base_url,
        );
    }
}