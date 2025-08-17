<?php
/**
 * AquaLuxe API Sync Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Sync_Controller Class
 *
 * Handles API requests for data synchronization
 */
class AquaLuxe_API_Sync_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'sync';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get sync status
        register_rest_route($this->namespace, '/' . $this->rest_base . '/status', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_sync_status'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
        ));

        // Get sync data
        register_rest_route($this->namespace, '/' . $this->rest_base . '/data', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_sync_data'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'entity_types' => array(
                        'description' => __('Entity types to sync.'),
                        'type' => 'array',
                        'required' => false,
                        'default' => array('products', 'categories', 'orders', 'customers'),
                        'sanitize_callback' => function($param) {
                            return is_array($param) ? $param : array();
                        },
                    ),
                    'last_sync' => array(
                        'description' => __('Last sync timestamp.'),
                        'type' => 'string',
                        'required' => false,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Get delta sync
        register_rest_route($this->namespace, '/' . $this->rest_base . '/delta', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_delta_sync'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'entity_type' => array(
                        'description' => __('Entity type to sync.'),
                        'type' => 'string',
                        'required' => true,
                        'enum' => array('products', 'categories', 'orders', 'customers', 'auctions', 'trade_ins'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'last_sync' => array(
                        'description' => __('Last sync timestamp.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'page' => array(
                        'description' => __('Page number.'),
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1,
                        'minimum' => 1,
                        'sanitize_callback' => 'absint',
                    ),
                    'per_page' => array(
                        'description' => __('Number of items per page.'),
                        'type' => 'integer',
                        'required' => false,
                        'default' => 50,
                        'minimum' => 1,
                        'maximum' => 100,
                        'sanitize_callback' => 'absint',
                    ),
                ),
            ),
        ));

        // Submit sync conflicts
        register_rest_route($this->namespace, '/' . $this->rest_base . '/conflicts', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'submit_sync_conflicts'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'conflicts' => array(
                        'description' => __('Array of sync conflicts.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'object',
                            'required' => array('entity_type', 'entity_id', 'resolution'),
                            'properties' => array(
                                'entity_type' => array(
                                    'type' => 'string',
                                    'enum' => array('products', 'categories', 'orders', 'customers', 'auctions', 'trade_ins'),
                                ),
                                'entity_id' => array(
                                    'type' => 'integer',
                                ),
                                'resolution' => array(
                                    'type' => 'string',
                                    'enum' => array('server', 'client'),
                                ),
                                'client_data' => array(
                                    'type' => 'object',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ));

        // Get entity schema
        register_rest_route($this->namespace, '/' . $this->rest_base . '/schema/(?P<entity_type>[a-zA-Z0-9_-]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_entity_schema'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'entity_type' => array(
                        'description' => __('Entity type.'),
                        'type' => 'string',
                        'required' => true,
                        'enum' => array('products', 'categories', 'orders', 'customers', 'auctions', 'trade_ins'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Get sync settings
        register_rest_route($this->namespace, '/' . $this->rest_base . '/settings', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_sync_settings'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
        ));
    }

    /**
     * Get sync status.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_sync_status($request) {
        $start_time = microtime(true);

        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get last sync timestamp for user
        $last_sync = get_user_meta($user_id, '_aqualuxe_last_sync', true);
        
        // Get entity counts
        $product_count = $this->get_entity_count('product');
        $category_count = $this->get_entity_count('product_cat');
        $order_count = $this->get_entity_count('shop_order');
        $customer_count = $this->get_customer_count();
        $auction_count = $this->get_entity_count('auction');
        $trade_in_count = $this->get_entity_count('trade_in_request');
        
        // Get sync settings
        $sync_settings = $this->get_sync_settings_data();
        
        // Prepare response
        $response = $this->format_response(array(
            'last_sync' => $last_sync ? $last_sync : null,
            'entity_counts' => array(
                'products' => $product_count,
                'categories' => $category_count,
                'orders' => $order_count,
                'customers' => $customer_count,
                'auctions' => $auction_count,
                'trade_ins' => $trade_in_count,
            ),
            'settings' => $sync_settings,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get sync data.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_sync_data($request) {
        $start_time = microtime(true);

        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get request params
        $entity_types = $request->get_param('entity_types');
        $last_sync = $request->get_param('last_sync');
        
        // Initialize data array
        $data = array();
        
        // Get current timestamp
        $current_timestamp = current_time('mysql');
        
        // Process each entity type
        foreach ($entity_types as $entity_type) {
            switch ($entity_type) {
                case 'products':
                    $data['products'] = $this->get_products_data($last_sync);
                    break;
                case 'categories':
                    $data['categories'] = $this->get_categories_data($last_sync);
                    break;
                case 'orders':
                    $data['orders'] = $this->get_orders_data($last_sync, $user_id);
                    break;
                case 'customers':
                    if ($this->is_current_user_admin()) {
                        $data['customers'] = $this->get_customers_data($last_sync);
                    }
                    break;
                case 'auctions':
                    $data['auctions'] = $this->get_auctions_data($last_sync);
                    break;
                case 'trade_ins':
                    $data['trade_ins'] = $this->get_trade_ins_data($last_sync, $user_id);
                    break;
            }
        }
        
        // Update last sync timestamp for user
        update_user_meta($user_id, '_aqualuxe_last_sync', $current_timestamp);
        
        // Prepare response
        $response = $this->format_response(array(
            'sync_timestamp' => $current_timestamp,
            'data' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get delta sync.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_delta_sync($request) {
        $start_time = microtime(true);

        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get request params
        $entity_type = $request->get_param('entity_type');
        $last_sync = $request->get_param('last_sync');
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        
        // Initialize data array
        $data = array();
        $total_items = 0;
        $total_pages = 0;
        
        // Get current timestamp
        $current_timestamp = current_time('mysql');
        
        // Process entity type
        switch ($entity_type) {
            case 'products':
                $result = $this->get_products_delta($last_sync, $page, $per_page);
                $data = $result['data'];
                $total_items = $result['total'];
                $total_pages = $result['pages'];
                break;
            case 'categories':
                $result = $this->get_categories_delta($last_sync, $page, $per_page);
                $data = $result['data'];
                $total_items = $result['total'];
                $total_pages = $result['pages'];
                break;
            case 'orders':
                $result = $this->get_orders_delta($last_sync, $user_id, $page, $per_page);
                $data = $result['data'];
                $total_items = $result['total'];
                $total_pages = $result['pages'];
                break;
            case 'customers':
                if ($this->is_current_user_admin()) {
                    $result = $this->get_customers_delta($last_sync, $page, $per_page);
                    $data = $result['data'];
                    $total_items = $result['total'];
                    $total_pages = $result['pages'];
                }
                break;
            case 'auctions':
                $result = $this->get_auctions_delta($last_sync, $page, $per_page);
                $data = $result['data'];
                $total_items = $result['total'];
                $total_pages = $result['pages'];
                break;
            case 'trade_ins':
                $result = $this->get_trade_ins_delta($last_sync, $user_id, $page, $per_page);
                $data = $result['data'];
                $total_items = $result['total'];
                $total_pages = $result['pages'];
                break;
        }
        
        // Update last sync timestamp for user if this is the last page
        if ($page >= $total_pages) {
            update_user_meta($user_id, '_aqualuxe_last_sync_' . $entity_type, $current_timestamp);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'entity_type' => $entity_type,
            'sync_timestamp' => $current_timestamp,
            'data' => $data,
            'total' => $total_items,
            'pages' => $total_pages,
            'page' => $page,
            'per_page' => $per_page,
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $total_items, $per_page);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Submit sync conflicts.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function submit_sync_conflicts($request) {
        $start_time = microtime(true);

        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get request params
        $conflicts = $request->get_param('conflicts');
        
        // Process conflicts
        $results = array();
        
        foreach ($conflicts as $conflict) {
            $entity_type = $conflict['entity_type'];
            $entity_id = $conflict['entity_id'];
            $resolution = $conflict['resolution'];
            $client_data = isset($conflict['client_data']) ? $conflict['client_data'] : null;
            
            $result = array(
                'entity_type' => $entity_type,
                'entity_id' => $entity_id,
                'resolution' => $resolution,
                'success' => false,
            );
            
            // Process resolution
            if ($resolution === 'client' && $client_data) {
                // Apply client data to server
                switch ($entity_type) {
                    case 'products':
                        $result['success'] = $this->resolve_product_conflict($entity_id, $client_data, $user_id);
                        break;
                    case 'categories':
                        $result['success'] = $this->resolve_category_conflict($entity_id, $client_data, $user_id);
                        break;
                    case 'orders':
                        $result['success'] = $this->resolve_order_conflict($entity_id, $client_data, $user_id);
                        break;
                    case 'customers':
                        if ($this->is_current_user_admin()) {
                            $result['success'] = $this->resolve_customer_conflict($entity_id, $client_data, $user_id);
                        }
                        break;
                    case 'auctions':
                        $result['success'] = $this->resolve_auction_conflict($entity_id, $client_data, $user_id);
                        break;
                    case 'trade_ins':
                        $result['success'] = $this->resolve_trade_in_conflict($entity_id, $client_data, $user_id);
                        break;
                }
            } else {
                // Server data takes precedence, no action needed
                $result['success'] = true;
            }
            
            $results[] = $result;
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'results' => $results,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get entity schema.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_entity_schema($request) {
        $start_time = microtime(true);

        // Get entity type
        $entity_type = $request->get_param('entity_type');
        
        // Get schema
        $schema = $this->get_schema_for_entity_type($entity_type);
        
        if (!$schema) {
            return $this->format_error('invalid_entity_type', __('Invalid entity type.'), 400);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'entity_type' => $entity_type,
            'schema' => $schema,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get sync settings.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_sync_settings($request) {
        $start_time = microtime(true);

        // Get sync settings
        $settings = $this->get_sync_settings_data();
        
        // Prepare response
        $response = $this->format_response(array(
            'settings' => $settings,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get entity count.
     *
     * @param string $post_type Post type.
     * @return int
     */
    protected function get_entity_count($post_type) {
        $count_posts = wp_count_posts($post_type);
        
        if ($post_type === 'shop_order') {
            // For orders, only count processing, completed, and on-hold
            $count = 0;
            
            if (isset($count_posts->processing)) {
                $count += $count_posts->processing;
            }
            
            if (isset($count_posts->completed)) {
                $count += $count_posts->completed;
            }
            
            if (isset($count_posts->{'on-hold'})) {
                $count += $count_posts->{'on-hold'};
            }
            
            return $count;
        } else {
            // For other post types, count published
            return isset($count_posts->publish) ? $count_posts->publish : 0;
        }
    }

    /**
     * Get customer count.
     *
     * @return int
     */
    protected function get_customer_count() {
        $customer_query = new WP_User_Query(array(
            'role' => 'customer',
            'count_total' => true,
        ));
        
        return $customer_query->get_total();
    }

    /**
     * Get products data.
     *
     * @param string $last_sync Last sync timestamp.
     * @return array
     */
    protected function get_products_data($last_sync) {
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            
            if ($product) {
                $products[] = $this->prepare_product_data_for_sync($product);
            }
        }
        
        return $products;
    }

    /**
     * Get products delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_products_delta($last_sync, $page, $per_page) {
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            
            if ($product) {
                $products[] = $this->prepare_product_data_for_sync($product);
            }
        }
        
        return array(
            'data' => $products,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $per_page),
        );
    }

    /**
     * Prepare product data for sync.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function prepare_product_data_for_sync($product) {
        $data = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'permalink' => get_permalink($product->get_id()),
            'date_created' => $product->get_date_created() ? $product->get_date_created()->format('Y-m-d H:i:s') : null,
            'date_modified' => $product->get_date_modified() ? $product->get_date_modified()->format('Y-m-d H:i:s') : null,
            'type' => $product->get_type(),
            'status' => $product->get_status(),
            'featured' => $product->is_featured(),
            'catalog_visibility' => $product->get_catalog_visibility(),
            'description' => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price(),
            'date_on_sale_from' => $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->format('Y-m-d H:i:s') : null,
            'date_on_sale_to' => $product->get_date_on_sale_to() ? $product->get_date_on_sale_to()->format('Y-m-d H:i:s') : null,
            'on_sale' => $product->is_on_sale(),
            'purchasable' => $product->is_purchasable(),
            'total_sales' => $product->get_total_sales(),
            'virtual' => $product->is_virtual(),
            'downloadable' => $product->is_downloadable(),
            'downloads' => $this->get_downloads($product),
            'download_limit' => $product->get_download_limit(),
            'download_expiry' => $product->get_download_expiry(),
            'tax_status' => $product->get_tax_status(),
            'tax_class' => $product->get_tax_class(),
            'manage_stock' => $product->managing_stock(),
            'stock_quantity' => $product->get_stock_quantity(),
            'stock_status' => $product->get_stock_status(),
            'backorders' => $product->get_backorders(),
            'backorders_allowed' => $product->backorders_allowed(),
            'backordered' => $product->is_on_backorder(),
            'sold_individually' => $product->is_sold_individually(),
            'weight' => $product->get_weight(),
            'dimensions' => array(
                'length' => $product->get_length(),
                'width' => $product->get_width(),
                'height' => $product->get_height(),
            ),
            'shipping_required' => $product->needs_shipping(),
            'shipping_taxable' => $product->is_shipping_taxable(),
            'shipping_class' => $product->get_shipping_class(),
            'shipping_class_id' => $product->get_shipping_class_id(),
            'reviews_allowed' => $product->get_reviews_allowed(),
            'average_rating' => $product->get_average_rating(),
            'rating_count' => $product->get_rating_count(),
            'related_ids' => $product->get_related(),
            'upsell_ids' => $product->get_upsell_ids(),
            'cross_sell_ids' => $product->get_cross_sell_ids(),
            'parent_id' => $product->get_parent_id(),
            'purchase_note' => $product->get_purchase_note(),
            'categories' => $this->get_taxonomy_terms($product, 'product_cat'),
            'tags' => $this->get_taxonomy_terms($product, 'product_tag'),
            'images' => $this->get_images($product),
            'attributes' => $this->get_attributes($product),
            'default_attributes' => $this->get_default_attributes($product),
            'variations' => $this->get_variations($product),
            'menu_order' => $product->get_menu_order(),
            'meta_data' => $this->get_meta_data($product),
            'sync_version' => get_post_meta($product->get_id(), '_sync_version', true),
        );
        
        return $data;
    }

    /**
     * Get product downloads.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_downloads($product) {
        $downloads = array();
        
        if ($product->is_downloadable()) {
            foreach ($product->get_downloads() as $file_id => $file) {
                $downloads[] = array(
                    'id' => $file_id,
                    'name' => $file['name'],
                    'file' => $file['file'],
                );
            }
        }
        
        return $downloads;
    }

    /**
     * Get product taxonomy terms.
     *
     * @param WC_Product $product Product object.
     * @param string $taxonomy Taxonomy name.
     * @return array
     */
    protected function get_taxonomy_terms($product, $taxonomy) {
        $terms = array();
        
        foreach (wc_get_object_terms($product->get_id(), $taxonomy) as $term) {
            $terms[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        return $terms;
    }

    /**
     * Get product images.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_images($product) {
        $images = array();
        $attachment_ids = array();
        
        // Add featured image
        if ($product->get_image_id()) {
            $attachment_ids[] = $product->get_image_id();
        }
        
        // Add gallery images
        $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());
        
        // Build image data
        foreach ($attachment_ids as $attachment_id) {
            $attachment = wp_get_attachment_image_src($attachment_id, 'full');
            
            if (!is_array($attachment)) {
                continue;
            }
            
            $images[] = array(
                'id' => (int) $attachment_id,
                'src' => current($attachment),
                'name' => get_the_title($attachment_id),
                'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
            );
        }
        
        return $images;
    }

    /**
     * Get product attributes.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_attributes($product) {
        $attributes = array();
        
        foreach ($product->get_attributes() as $attribute) {
            $attribute_data = array(
                'id' => $attribute['is_taxonomy'] ? wc_attribute_taxonomy_id_by_name($attribute['name']) : 0,
                'name' => $attribute['name'],
                'position' => $attribute['position'],
                'visible' => (bool) $attribute['is_visible'],
                'variation' => (bool) $attribute['is_variation'],
                'options' => array(),
            );
            
            if ($attribute['is_taxonomy']) {
                $terms = wc_get_object_terms($product->get_id(), $attribute['name']);
                foreach ($terms as $term) {
                    $attribute_data['options'][] = array(
                        'id' => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug,
                    );
                }
            } else {
                $attribute_data['options'] = $attribute['value'];
            }
            
            $attributes[] = $attribute_data;
        }
        
        return $attributes;
    }

    /**
     * Get product default attributes.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_default_attributes($product) {
        $default_attributes = array();
        
        if ($product->is_type('variable')) {
            foreach ($product->get_default_attributes() as $attribute_name => $attribute_value) {
                $default_attributes[] = array(
                    'name' => $attribute_name,
                    'option' => $attribute_value,
                );
            }
        }
        
        return $default_attributes;
    }

    /**
     * Get product variations.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_variations($product) {
        $variations = array();
        
        if ($product->is_type('variable')) {
            foreach ($product->get_children() as $child_id) {
                $variation = wc_get_product($child_id);
                
                if (!$variation || !$variation->exists()) {
                    continue;
                }
                
                $variation_data = array(
                    'id' => $variation->get_id(),
                    'sku' => $variation->get_sku(),
                    'price' => $variation->get_price(),
                    'regular_price' => $variation->get_regular_price(),
                    'sale_price' => $variation->get_sale_price(),
                    'on_sale' => $variation->is_on_sale(),
                    'purchasable' => $variation->is_purchasable(),
                    'visible' => $variation->is_visible(),
                    'virtual' => $variation->is_virtual(),
                    'downloadable' => $variation->is_downloadable(),
                    'downloads' => $this->get_downloads($variation),
                    'download_limit' => $variation->get_download_limit(),
                    'download_expiry' => $variation->get_download_expiry(),
                    'tax_status' => $variation->get_tax_status(),
                    'tax_class' => $variation->get_tax_class(),
                    'manage_stock' => $variation->managing_stock(),
                    'stock_quantity' => $variation->get_stock_quantity(),
                    'stock_status' => $variation->get_stock_status(),
                    'backorders' => $variation->get_backorders(),
                    'backorders_allowed' => $variation->backorders_allowed(),
                    'backordered' => $variation->is_on_backorder(),
                    'weight' => $variation->get_weight(),
                    'dimensions' => array(
                        'length' => $variation->get_length(),
                        'width' => $variation->get_width(),
                        'height' => $variation->get_height(),
                    ),
                    'shipping_class' => $variation->get_shipping_class(),
                    'shipping_class_id' => $variation->get_shipping_class_id(),
                    'image' => $this->get_images($variation),
                    'attributes' => array(),
                    'meta_data' => $this->get_meta_data($variation),
                );
                
                // Get variation attributes
                foreach ($variation->get_variation_attributes() as $attribute_name => $attribute_value) {
                    $variation_data['attributes'][] = array(
                        'name' => str_replace('attribute_', '', $attribute_name),
                        'option' => $attribute_value,
                    );
                }
                
                $variations[] = $variation_data;
            }
        }
        
        return $variations;
    }

    /**
     * Get meta data.
     *
     * @param WC_Data $object Data object.
     * @return array
     */
    protected function get_meta_data($object) {
        $meta_data = array();
        
        foreach ($object->get_meta_data() as $meta) {
            // Skip internal meta
            if (substr($meta->key, 0, 1) === '_' && $meta->key !== '_sync_version') {
                continue;
            }
            
            $meta_data[] = array(
                'key' => $meta->key,
                'value' => $meta->value,
            );
        }
        
        return $meta_data;
    }

    /**
     * Get categories data.
     *
     * @param string $last_sync Last sync timestamp.
     * @return array
     */
    protected function get_categories_data($last_sync) {
        // Get categories
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        );
        
        $categories = get_terms($args);
        $data = array();
        
        foreach ($categories as $category) {
            // Check if category was modified after last sync
            $modified = get_term_meta($category->term_id, '_modified_date', true);
            
            if ($last_sync && $modified && strtotime($modified) <= strtotime($last_sync)) {
                continue;
            }
            
            $data[] = $this->prepare_category_data_for_sync($category);
        }
        
        return $data;
    }

    /**
     * Get categories delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_categories_delta($last_sync, $page, $per_page) {
        // Get categories
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'number' => $per_page,
            'offset' => ($page - 1) * $per_page,
        );
        
        $categories = get_terms($args);
        $data = array();
        
        foreach ($categories as $category) {
            // Check if category was modified after last sync
            $modified = get_term_meta($category->term_id, '_modified_date', true);
            
            if ($last_sync && $modified && strtotime($modified) <= strtotime($last_sync)) {
                continue;
            }
            
            $data[] = $this->prepare_category_data_for_sync($category);
        }
        
        // Get total count
        $count_args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'fields' => 'count',
        );
        
        $total = wp_count_terms($count_args);
        
        return array(
            'data' => $data,
            'total' => $total,
            'pages' => ceil($total / $per_page),
        );
    }

    /**
     * Prepare category data for sync.
     *
     * @param WP_Term $category Category term object.
     * @return array
     */
    protected function prepare_category_data_for_sync($category) {
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $image = array();
        
        if ($thumbnail_id) {
            $attachment = wp_get_attachment_image_src($thumbnail_id, 'full');
            
            if ($attachment) {
                $image = array(
                    'id' => (int) $thumbnail_id,
                    'src' => $attachment[0],
                    'name' => get_the_title($thumbnail_id),
                    'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        $data = array(
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'parent' => $category->parent,
            'description' => $category->description,
            'display' => get_term_meta($category->term_id, 'display_type', true),
            'image' => $image,
            'menu_order' => get_term_meta($category->term_id, 'order', true),
            'count' => $category->count,
            'sync_version' => get_term_meta($category->term_id, '_sync_version', true),
        );
        
        return $data;
    }

    /**
     * Get orders data.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $user_id User ID.
     * @return array
     */
    protected function get_orders_data($last_sync, $user_id) {
        // Query args
        $args = array(
            'post_type' => 'shop_order',
            'post_status' => array('wc-processing', 'wc-completed', 'wc-on-hold'),
            'posts_per_page' => -1,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Limit to user's orders if not admin
        if (!$this->is_current_user_admin()) {
            $args['meta_query'] = array(
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '=',
                ),
            );
        }
        
        // Get orders
        $query = new WP_Query($args);
        $orders = array();
        
        foreach ($query->posts as $post) {
            $order = wc_get_order($post->ID);
            
            if ($order) {
                $orders[] = $this->prepare_order_data_for_sync($order);
            }
        }
        
        return $orders;
    }

    /**
     * Get orders delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $user_id User ID.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_orders_delta($last_sync, $user_id, $page, $per_page) {
        // Query args
        $args = array(
            'post_type' => 'shop_order',
            'post_status' => array('wc-processing', 'wc-completed', 'wc-on-hold'),
            'posts_per_page' => $per_page,
            'paged' => $page,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Limit to user's orders if not admin
        if (!$this->is_current_user_admin()) {
            $args['meta_query'] = array(
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '=',
                ),
            );
        }
        
        // Get orders
        $query = new WP_Query($args);
        $orders = array();
        
        foreach ($query->posts as $post) {
            $order = wc_get_order($post->ID);
            
            if ($order) {
                $orders[] = $this->prepare_order_data_for_sync($order);
            }
        }
        
        return array(
            'data' => $orders,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $per_page),
        );
    }

    /**
     * Prepare order data for sync.
     *
     * @param WC_Order $order Order object.
     * @return array
     */
    protected function prepare_order_data_for_sync($order) {
        $data = array(
            'id' => $order->get_id(),
            'parent_id' => $order->get_parent_id(),
            'number' => $order->get_order_number(),
            'order_key' => $order->get_order_key(),
            'created_via' => $order->get_created_via(),
            'version' => $order->get_version(),
            'status' => $order->get_status(),
            'currency' => $order->get_currency(),
            'date_created' => $order->get_date_created() ? $order->get_date_created()->format('Y-m-d H:i:s') : null,
            'date_modified' => $order->get_date_modified() ? $order->get_date_modified()->format('Y-m-d H:i:s') : null,
            'discount_total' => $order->get_discount_total(),
            'discount_tax' => $order->get_discount_tax(),
            'shipping_total' => $order->get_shipping_total(),
            'shipping_tax' => $order->get_shipping_tax(),
            'cart_tax' => $order->get_cart_tax(),
            'total' => $order->get_total(),
            'total_tax' => $order->get_total_tax(),
            'customer_id' => $order->get_customer_id(),
            'customer_note' => $order->get_customer_note(),
            'billing' => array(
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'company' => $order->get_billing_company(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'postcode' => $order->get_billing_postcode(),
                'country' => $order->get_billing_country(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
            ),
            'shipping' => array(
                'first_name' => $order->get_shipping_first_name(),
                'last_name' => $order->get_shipping_last_name(),
                'company' => $order->get_shipping_company(),
                'address_1' => $order->get_shipping_address_1(),
                'address_2' => $order->get_shipping_address_2(),
                'city' => $order->get_shipping_city(),
                'state' => $order->get_shipping_state(),
                'postcode' => $order->get_shipping_postcode(),
                'country' => $order->get_shipping_country(),
            ),
            'payment_method' => $order->get_payment_method(),
            'payment_method_title' => $order->get_payment_method_title(),
            'transaction_id' => $order->get_transaction_id(),
            'date_paid' => $order->get_date_paid() ? $order->get_date_paid()->format('Y-m-d H:i:s') : null,
            'date_completed' => $order->get_date_completed() ? $order->get_date_completed()->format('Y-m-d H:i:s') : null,
            'cart_hash' => $order->get_cart_hash(),
            'line_items' => array(),
            'tax_lines' => array(),
            'shipping_lines' => array(),
            'fee_lines' => array(),
            'coupon_lines' => array(),
            'refunds' => array(),
            'meta_data' => $this->get_meta_data($order),
            'sync_version' => get_post_meta($order->get_id(), '_sync_version', true),
        );
        
        // Add line items
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            
            $line_item = array(
                'id' => $item_id,
                'name' => $item->get_name(),
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $item->get_quantity(),
                'tax_class' => $item->get_tax_class(),
                'subtotal' => $item->get_subtotal(),
                'subtotal_tax' => $item->get_subtotal_tax(),
                'total' => $item->get_total(),
                'total_tax' => $item->get_total_tax(),
                'taxes' => $item->get_taxes(),
                'meta_data' => array(),
            );
            
            // Add meta data
            foreach ($item->get_meta_data() as $meta) {
                $line_item['meta_data'][] = array(
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['line_items'][] = $line_item;
        }
        
        // Add tax lines
        foreach ($order->get_tax_lines() as $tax_line_id => $tax_line) {
            $data['tax_lines'][] = array(
                'id' => $tax_line_id,
                'rate_code' => $tax_line->get_rate_code(),
                'rate_id' => $tax_line->get_rate_id(),
                'label' => $tax_line->get_label(),
                'compound' => $tax_line->is_compound(),
                'tax_total' => $tax_line->get_tax_total(),
                'shipping_tax_total' => $tax_line->get_shipping_tax_total(),
                'meta_data' => array(),
            );
        }
        
        // Add shipping lines
        foreach ($order->get_shipping_methods() as $shipping_line_id => $shipping_line) {
            $data['shipping_lines'][] = array(
                'id' => $shipping_line_id,
                'method_title' => $shipping_line->get_method_title(),
                'method_id' => $shipping_line->get_method_id(),
                'instance_id' => $shipping_line->get_instance_id(),
                'total' => $shipping_line->get_total(),
                'total_tax' => $shipping_line->get_total_tax(),
                'taxes' => $shipping_line->get_taxes(),
                'meta_data' => array(),
            );
        }
        
        // Add fee lines
        foreach ($order->get_fees() as $fee_line_id => $fee_line) {
            $data['fee_lines'][] = array(
                'id' => $fee_line_id,
                'name' => $fee_line->get_name(),
                'tax_class' => $fee_line->get_tax_class(),
                'tax_status' => $fee_line->get_tax_status(),
                'amount' => $fee_line->get_amount(),
                'total' => $fee_line->get_total(),
                'total_tax' => $fee_line->get_total_tax(),
                'taxes' => $fee_line->get_taxes(),
                'meta_data' => array(),
            );
        }
        
        // Add coupon lines
        foreach ($order->get_coupons() as $coupon_line_id => $coupon_line) {
            $data['coupon_lines'][] = array(
                'id' => $coupon_line_id,
                'code' => $coupon_line->get_code(),
                'discount' => $coupon_line->get_discount(),
                'discount_tax' => $coupon_line->get_discount_tax(),
                'meta_data' => array(),
            );
        }
        
        // Add refunds
        foreach ($order->get_refunds() as $refund) {
            $data['refunds'][] = array(
                'id' => $refund->get_id(),
                'reason' => $refund->get_reason(),
                'total' => $refund->get_amount(),
                'date_created' => $refund->get_date_created() ? $refund->get_date_created()->format('Y-m-d H:i:s') : null,
            );
        }
        
        return $data;
    }

    /**
     * Get customers data.
     *
     * @param string $last_sync Last sync timestamp.
     * @return array
     */
    protected function get_customers_data($last_sync) {
        // Query args
        $args = array(
            'role' => 'customer',
            'number' => -1,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                array(
                    'column' => 'user_modified',
                    'after' => $last_sync,
                ),
            );
        }
        
        // Get customers
        $user_query = new WP_User_Query($args);
        $customers = array();
        
        foreach ($user_query->get_results() as $user) {
            $customers[] = $this->prepare_customer_data_for_sync($user);
        }
        
        return $customers;
    }

    /**
     * Get customers delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_customers_delta($last_sync, $page, $per_page) {
        // Query args
        $args = array(
            'role' => 'customer',
            'number' => $per_page,
            'paged' => $page,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                array(
                    'column' => 'user_modified',
                    'after' => $last_sync,
                ),
            );
        }
        
        // Get customers
        $user_query = new WP_User_Query($args);
        $customers = array();
        
        foreach ($user_query->get_results() as $user) {
            $customers[] = $this->prepare_customer_data_for_sync($user);
        }
        
        return array(
            'data' => $customers,
            'total' => $user_query->get_total(),
            'pages' => ceil($user_query->get_total() / $per_page),
        );
    }

    /**
     * Prepare customer data for sync.
     *
     * @param WP_User $user User object.
     * @return array
     */
    protected function prepare_customer_data_for_sync($user) {
        $data = array(
            'id' => $user->ID,
            'date_created' => $user->user_registered,
            'date_modified' => get_user_meta($user->ID, '_user_modified', true),
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->user_login,
            'role' => $user->roles[0],
            'billing' => array(
                'first_name' => get_user_meta($user->ID, 'billing_first_name', true),
                'last_name' => get_user_meta($user->ID, 'billing_last_name', true),
                'company' => get_user_meta($user->ID, 'billing_company', true),
                'address_1' => get_user_meta($user->ID, 'billing_address_1', true),
                'address_2' => get_user_meta($user->ID, 'billing_address_2', true),
                'city' => get_user_meta($user->ID, 'billing_city', true),
                'state' => get_user_meta($user->ID, 'billing_state', true),
                'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
                'country' => get_user_meta($user->ID, 'billing_country', true),
                'email' => get_user_meta($user->ID, 'billing_email', true),
                'phone' => get_user_meta($user->ID, 'billing_phone', true),
            ),
            'shipping' => array(
                'first_name' => get_user_meta($user->ID, 'shipping_first_name', true),
                'last_name' => get_user_meta($user->ID, 'shipping_last_name', true),
                'company' => get_user_meta($user->ID, 'shipping_company', true),
                'address_1' => get_user_meta($user->ID, 'shipping_address_1', true),
                'address_2' => get_user_meta($user->ID, 'shipping_address_2', true),
                'city' => get_user_meta($user->ID, 'shipping_city', true),
                'state' => get_user_meta($user->ID, 'shipping_state', true),
                'postcode' => get_user_meta($user->ID, 'shipping_postcode', true),
                'country' => get_user_meta($user->ID, 'shipping_country', true),
            ),
            'is_paying_customer' => get_user_meta($user->ID, 'paying_customer', true),
            'meta_data' => array(),
            'sync_version' => get_user_meta($user->ID, '_sync_version', true),
        );
        
        // Add meta data
        $meta_data = get_user_meta($user->ID);
        
        foreach ($meta_data as $key => $values) {
            // Skip internal meta and already included meta
            if (substr($key, 0, 1) === '_' || in_array($key, array(
                'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1',
                'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode',
                'billing_country', 'billing_email', 'billing_phone', 'shipping_first_name',
                'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2',
                'shipping_city', 'shipping_state', 'shipping_postcode', 'shipping_country',
                'paying_customer'
            ))) {
                continue;
            }
            
            $data['meta_data'][] = array(
                'key' => $key,
                'value' => $values[0],
            );
        }
        
        return $data;
    }

    /**
     * Get auctions data.
     *
     * @param string $last_sync Last sync timestamp.
     * @return array
     */
    protected function get_auctions_data($last_sync) {
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Get auctions
        $query = new WP_Query($args);
        $auctions = array();
        
        foreach ($query->posts as $post) {
            $auctions[] = $this->prepare_auction_data_for_sync($post);
        }
        
        return $auctions;
    }

    /**
     * Get auctions delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_auctions_delta($last_sync, $page, $per_page) {
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Get auctions
        $query = new WP_Query($args);
        $auctions = array();
        
        foreach ($query->posts as $post) {
            $auctions[] = $this->prepare_auction_data_for_sync($post);
        }
        
        return array(
            'data' => $auctions,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $per_page),
        );
    }

    /**
     * Prepare auction data for sync.
     *
     * @param WP_Post $auction Auction post object.
     * @return array
     */
    protected function prepare_auction_data_for_sync($auction) {
        // Get auction meta
        $start_date = get_post_meta($auction->ID, '_auction_start_date', true);
        $end_date = get_post_meta($auction->ID, '_auction_end_date', true);
        $status = get_post_meta($auction->ID, '_auction_status', true);
        $starting_bid = get_post_meta($auction->ID, '_auction_starting_bid', true);
        $current_bid = get_post_meta($auction->ID, '_auction_current_bid', true);
        $reserve_price = get_post_meta($auction->ID, '_auction_reserve_price', true);
        $bid_increment = get_post_meta($auction->ID, '_auction_bid_increment', true);
        $featured = get_post_meta($auction->ID, '_auction_featured', true);
        $current_bidder = get_post_meta($auction->ID, '_auction_current_bidder', true);
        
        // Get time remaining
        $time_remaining = 0;
        
        if ($status === 'active' && $end_date) {
            $time_remaining = strtotime($end_date) - time();
            
            if ($time_remaining < 0) {
                $time_remaining = 0;
            }
        }
        
        // Get bidder data
        $bidder_data = null;
        
        if ($current_bidder) {
            $user = get_user_by('id', $current_bidder);
            
            if ($user) {
                $bidder_data = array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                );
            }
        }
        
        // Get images
        $images = array();
        
        // Featured image
        $thumbnail_id = get_post_thumbnail_id($auction->ID);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            
            if ($image) {
                $images[] = array(
                    'id' => $thumbnail_id,
                    'src' => $image[0],
                    'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        // Gallery images
        $gallery_ids = get_post_meta($auction->ID, '_auction_gallery', true);
        
        if ($gallery_ids) {
            $gallery_ids = explode(',', $gallery_ids);
            
            foreach ($gallery_ids as $gallery_id) {
                $image = wp_get_attachment_image_src($gallery_id, 'full');
                
                if ($image) {
                    $images[] = array(
                        'id' => $gallery_id,
                        'src' => $image[0],
                        'alt' => get_post_meta($gallery_id, '_wp_attachment_image_alt', true),
                    );
                }
            }
        }
        
        // Get categories
        $categories = array();
        $terms = wp_get_post_terms($auction->ID, 'auction_category');
        
        foreach ($terms as $term) {
            $categories[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        // Get bids
        $bids = $this->get_auction_bid_history($auction->ID, 10, 0);
        
        // Prepare data
        $data = array(
            'id' => $auction->ID,
            'title' => $auction->post_title,
            'slug' => $auction->post_name,
            'permalink' => get_permalink($auction->ID),
            'date_created' => $auction->post_date,
            'date_modified' => $auction->post_modified,
            'status' => $status,
            'description' => $auction->post_content,
            'short_description' => $auction->post_excerpt,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'time_remaining' => $time_remaining,
            'starting_bid' => $starting_bid ? floatval($starting_bid) : 0,
            'current_bid' => $current_bid ? floatval($current_bid) : 0,
            'reserve_price' => $reserve_price ? floatval($reserve_price) : 0,
            'reserve_met' => $current_bid >= $reserve_price,
            'bid_increment' => $bid_increment ? floatval($bid_increment) : 0,
            'featured' => $featured === '1',
            'current_bidder' => $bidder_data,
            'bid_count' => count($bids),
            'bids' => $bids,
            'images' => $images,
            'categories' => $categories,
            'meta_data' => array(),
            'sync_version' => get_post_meta($auction->ID, '_sync_version', true),
        );
        
        // Add meta data
        $meta_data = get_post_meta($auction->ID);
        
        foreach ($meta_data as $key => $values) {
            // Skip internal meta and already included meta
            if (substr($key, 0, 1) === '_' && $key !== '_sync_version') {
                continue;
            }
            
            $data['meta_data'][] = array(
                'key' => $key,
                'value' => $values[0],
            );
        }
        
        return $data;
    }

    /**
     * Get auction bid history.
     *
     * @param int $auction_id Auction ID.
     * @param int $limit Number of bids to get.
     * @param int $offset Offset.
     * @return array
     */
    protected function get_auction_bid_history($auction_id, $limit = 10, $offset = 0) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'auction_bids';
        
        $bids = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE auction_id = %d ORDER BY date_created DESC LIMIT %d OFFSET %d",
                $auction_id,
                $limit,
                $offset
            )
        );
        
        $data = array();
        
        foreach ($bids as $bid) {
            $user = get_user_by('id', $bid->user_id);
            
            $data[] = array(
                'id' => $bid->id,
                'user' => array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                ),
                'amount' => floatval($bid->amount),
                'date' => $bid->date_created,
            );
        }
        
        return $data;
    }

    /**
     * Get trade-ins data.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $user_id User ID.
     * @return array
     */
    protected function get_trade_ins_data($last_sync, $user_id) {
        // Query args
        $args = array(
            'post_type' => 'trade_in_request',
            'post_status' => 'any',
            'posts_per_page' => -1,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Limit to user's trade-ins if not admin
        if (!$this->is_current_user_admin()) {
            $args['author'] = $user_id;
        }
        
        // Get trade-ins
        $query = new WP_Query($args);
        $trade_ins = array();
        
        foreach ($query->posts as $post) {
            $trade_ins[] = $this->prepare_trade_in_data_for_sync($post);
        }
        
        return $trade_ins;
    }

    /**
     * Get trade-ins delta.
     *
     * @param string $last_sync Last sync timestamp.
     * @param int $user_id User ID.
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    protected function get_trade_ins_delta($last_sync, $user_id, $page, $per_page) {
        // Query args
        $args = array(
            'post_type' => 'trade_in_request',
            'post_status' => 'any',
            'posts_per_page' => $per_page,
            'paged' => $page,
        );
        
        // Add date filter if last sync is provided
        if ($last_sync) {
            $args['date_query'] = array(
                'column' => 'post_modified',
                'after' => $last_sync,
            );
        }
        
        // Limit to user's trade-ins if not admin
        if (!$this->is_current_user_admin()) {
            $args['author'] = $user_id;
        }
        
        // Get trade-ins
        $query = new WP_Query($args);
        $trade_ins = array();
        
        foreach ($query->posts as $post) {
            $trade_ins[] = $this->prepare_trade_in_data_for_sync($post);
        }
        
        return array(
            'data' => $trade_ins,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $per_page),
        );
    }

    /**
     * Prepare trade-in data for sync.
     *
     * @param WP_Post $trade_in Trade-in post object.
     * @return array
     */
    protected function prepare_trade_in_data_for_sync($trade_in) {
        // Get trade-in meta
        $status = get_post_meta($trade_in->ID, '_trade_in_status', true);
        $condition = get_post_meta($trade_in->ID, '_trade_in_condition', true);
        $age = get_post_meta($trade_in->ID, '_trade_in_age', true);
        $brand = get_post_meta($trade_in->ID, '_trade_in_brand', true);
        $model = get_post_meta($trade_in->ID, '_trade_in_model', true);
        $serial_number = get_post_meta($trade_in->ID, '_trade_in_serial_number', true);
        $asking_price = get_post_meta($trade_in->ID, '_trade_in_asking_price', true);
        $contact_preference = get_post_meta($trade_in->ID, '_trade_in_contact_preference', true);
        $submission_date = get_post_meta($trade_in->ID, '_trade_in_submission_date', true);
        $evaluation_date = get_post_meta($trade_in->ID, '_trade_in_evaluation_date', true);
        $evaluation_amount = get_post_meta($trade_in->ID, '_trade_in_evaluation_amount', true);
        $evaluation_notes = get_post_meta($trade_in->ID, '_trade_in_evaluation_notes', true);
        $expiration_date = get_post_meta($trade_in->ID, '_trade_in_expiration_date', true);
        
        // Get images
        $images = array();
        
        // Featured image
        $thumbnail_id = get_post_thumbnail_id($trade_in->ID);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            
            if ($image) {
                $images[] = array(
                    'id' => $thumbnail_id,
                    'src' => $image[0],
                    'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        // Gallery images
        $gallery_ids = get_post_meta($trade_in->ID, '_trade_in_gallery', true);
        
        if ($gallery_ids) {
            $gallery_ids = explode(',', $gallery_ids);
            
            foreach ($gallery_ids as $gallery_id) {
                $image = wp_get_attachment_image_src($gallery_id, 'full');
                
                if ($image) {
                    $images[] = array(
                        'id' => $gallery_id,
                        'src' => $image[0],
                        'alt' => get_post_meta($gallery_id, '_wp_attachment_image_alt', true),
                    );
                }
            }
        }
        
        // Get categories
        $categories = array();
        $terms = wp_get_post_terms($trade_in->ID, 'trade_in_category');
        
        foreach ($terms as $term) {
            $categories[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        // Get user data
        $user = get_user_by('id', $trade_in->post_author);
        $user_data = array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'email' => $user->user_email,
        );
        
        // Prepare data
        $data = array(
            'id' => $trade_in->ID,
            'title' => $trade_in->post_title,
            'description' => $trade_in->post_content,
            'status' => $status,
            'condition' => $condition,
            'age' => intval($age),
            'brand' => $brand,
            'model' => $model,
            'serial_number' => $serial_number,
            'asking_price' => $asking_price ? floatval($asking_price) : null,
            'contact_preference' => $contact_preference,
            'submission_date' => $submission_date,
            'date_created' => $trade_in->post_date,
            'date_modified' => $trade_in->post_modified,
            'user' => $user_data,
            'images' => $images,
            'categories' => $categories,
            'meta_data' => array(),
            'sync_version' => get_post_meta($trade_in->ID, '_sync_version', true),
        );
        
        // Add evaluation data if available
        if ($status === 'evaluated' || $status === 'accepted' || $status === 'completed') {
            $data['evaluation'] = array(
                'date' => $evaluation_date,
                'amount' => $evaluation_amount ? floatval($evaluation_amount) : 0,
                'notes' => $evaluation_notes,
                'expiration_date' => $expiration_date,
                'is_expired' => $expiration_date && strtotime($expiration_date) < time(),
            );
        }
        
        // Add meta data
        $meta_data = get_post_meta($trade_in->ID);
        
        foreach ($meta_data as $key => $values) {
            // Skip internal meta and already included meta
            if (substr($key, 0, 1) === '_' && $key !== '_sync_version') {
                continue;
            }
            
            $data['meta_data'][] = array(
                'key' => $key,
                'value' => $values[0],
            );
        }
        
        return $data;
    }

    /**
     * Resolve product conflict.
     *
     * @param int $product_id Product ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_product_conflict($product_id, $client_data, $user_id) {
        // Only admins can resolve product conflicts
        if (!$this->is_current_user_admin()) {
            return false;
        }
        
        $product = wc_get_product($product_id);
        
        if (!$product) {
            return false;
        }
        
        // Update product data
        if (isset($client_data['name'])) {
            $product->set_name($client_data['name']);
        }
        
        if (isset($client_data['description'])) {
            $product->set_description($client_data['description']);
        }
        
        if (isset($client_data['short_description'])) {
            $product->set_short_description($client_data['short_description']);
        }
        
        if (isset($client_data['sku'])) {
            $product->set_sku($client_data['sku']);
        }
        
        if (isset($client_data['price'])) {
            $product->set_price($client_data['price']);
        }
        
        if (isset($client_data['regular_price'])) {
            $product->set_regular_price($client_data['regular_price']);
        }
        
        if (isset($client_data['sale_price'])) {
            $product->set_sale_price($client_data['sale_price']);
        }
        
        if (isset($client_data['stock_quantity'])) {
            $product->set_stock_quantity($client_data['stock_quantity']);
        }
        
        if (isset($client_data['stock_status'])) {
            $product->set_stock_status($client_data['stock_status']);
        }
        
        // Update meta data
        if (isset($client_data['meta_data']) && is_array($client_data['meta_data'])) {
            foreach ($client_data['meta_data'] as $meta) {
                if (isset($meta['key']) && isset($meta['value'])) {
                    $product->update_meta_data($meta['key'], $meta['value']);
                }
            }
        }
        
        // Update sync version
        $product->update_meta_data('_sync_version', time());
        
        // Save product
        $product->save();
        
        return true;
    }

    /**
     * Resolve category conflict.
     *
     * @param int $category_id Category ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_category_conflict($category_id, $client_data, $user_id) {
        // Only admins can resolve category conflicts
        if (!$this->is_current_user_admin()) {
            return false;
        }
        
        $category = get_term($category_id, 'product_cat');
        
        if (!$category || is_wp_error($category)) {
            return false;
        }
        
        // Update category data
        $args = array();
        
        if (isset($client_data['name'])) {
            $args['name'] = $client_data['name'];
        }
        
        if (isset($client_data['slug'])) {
            $args['slug'] = $client_data['slug'];
        }
        
        if (isset($client_data['description'])) {
            $args['description'] = $client_data['description'];
        }
        
        if (isset($client_data['parent'])) {
            $args['parent'] = $client_data['parent'];
        }
        
        if (!empty($args)) {
            wp_update_term($category_id, 'product_cat', $args);
        }
        
        // Update display type
        if (isset($client_data['display'])) {
            update_term_meta($category_id, 'display_type', $client_data['display']);
        }
        
        // Update thumbnail
        if (isset($client_data['image']) && isset($client_data['image']['id'])) {
            update_term_meta($category_id, 'thumbnail_id', $client_data['image']['id']);
        }
        
        // Update order
        if (isset($client_data['menu_order'])) {
            update_term_meta($category_id, 'order', $client_data['menu_order']);
        }
        
        // Update sync version
        update_term_meta($category_id, '_sync_version', time());
        
        // Update modified date
        update_term_meta($category_id, '_modified_date', current_time('mysql'));
        
        return true;
    }

    /**
     * Resolve order conflict.
     *
     * @param int $order_id Order ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_order_conflict($order_id, $client_data, $user_id) {
        // Only admins can resolve order conflicts
        if (!$this->is_current_user_admin()) {
            return false;
        }
        
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return false;
        }
        
        // Update order data
        if (isset($client_data['status'])) {
            $order->update_status($client_data['status']);
        }
        
        if (isset($client_data['customer_note'])) {
            $order->set_customer_note($client_data['customer_note']);
        }
        
        // Update meta data
        if (isset($client_data['meta_data']) && is_array($client_data['meta_data'])) {
            foreach ($client_data['meta_data'] as $meta) {
                if (isset($meta['key']) && isset($meta['value'])) {
                    $order->update_meta_data($meta['key'], $meta['value']);
                }
            }
        }
        
        // Update sync version
        $order->update_meta_data('_sync_version', time());
        
        // Save order
        $order->save();
        
        return true;
    }

    /**
     * Resolve customer conflict.
     *
     * @param int $customer_id Customer ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_customer_conflict($customer_id, $client_data, $user_id) {
        // Only admins can resolve customer conflicts
        if (!$this->is_current_user_admin()) {
            return false;
        }
        
        $customer = get_user_by('id', $customer_id);
        
        if (!$customer) {
            return false;
        }
        
        // Update customer data
        $args = array(
            'ID' => $customer_id,
        );
        
        if (isset($client_data['email'])) {
            $args['user_email'] = $client_data['email'];
        }
        
        if (isset($client_data['first_name'])) {
            $args['first_name'] = $client_data['first_name'];
        }
        
        if (isset($client_data['last_name'])) {
            $args['last_name'] = $client_data['last_name'];
        }
        
        if (!empty($args)) {
            wp_update_user($args);
        }
        
        // Update billing address
        if (isset($client_data['billing'])) {
            foreach ($client_data['billing'] as $key => $value) {
                update_user_meta($customer_id, 'billing_' . $key, $value);
            }
        }
        
        // Update shipping address
        if (isset($client_data['shipping'])) {
            foreach ($client_data['shipping'] as $key => $value) {
                update_user_meta($customer_id, 'shipping_' . $key, $value);
            }
        }
        
        // Update meta data
        if (isset($client_data['meta_data']) && is_array($client_data['meta_data'])) {
            foreach ($client_data['meta_data'] as $meta) {
                if (isset($meta['key']) && isset($meta['value'])) {
                    update_user_meta($customer_id, $meta['key'], $meta['value']);
                }
            }
        }
        
        // Update sync version
        update_user_meta($customer_id, '_sync_version', time());
        
        // Update modified date
        update_user_meta($customer_id, '_user_modified', current_time('mysql'));
        
        return true;
    }

    /**
     * Resolve auction conflict.
     *
     * @param int $auction_id Auction ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_auction_conflict($auction_id, $client_data, $user_id) {
        // Only admins can resolve auction conflicts
        if (!$this->is_current_user_admin()) {
            return false;
        }
        
        $auction = get_post($auction_id);
        
        if (!$auction || $auction->post_type !== 'auction') {
            return false;
        }
        
        // Update auction data
        $args = array(
            'ID' => $auction_id,
        );
        
        if (isset($client_data['title'])) {
            $args['post_title'] = $client_data['title'];
        }
        
        if (isset($client_data['description'])) {
            $args['post_content'] = $client_data['description'];
        }
        
        if (isset($client_data['short_description'])) {
            $args['post_excerpt'] = $client_data['short_description'];
        }
        
        if (!empty($args)) {
            wp_update_post($args);
        }
        
        // Update auction meta
        if (isset($client_data['status'])) {
            update_post_meta($auction_id, '_auction_status', $client_data['status']);
        }
        
        if (isset($client_data['start_date'])) {
            update_post_meta($auction_id, '_auction_start_date', $client_data['start_date']);
        }
        
        if (isset($client_data['end_date'])) {
            update_post_meta($auction_id, '_auction_end_date', $client_data['end_date']);
        }
        
        if (isset($client_data['starting_bid'])) {
            update_post_meta($auction_id, '_auction_starting_bid', $client_data['starting_bid']);
        }
        
        if (isset($client_data['reserve_price'])) {
            update_post_meta($auction_id, '_auction_reserve_price', $client_data['reserve_price']);
        }
        
        if (isset($client_data['bid_increment'])) {
            update_post_meta($auction_id, '_auction_bid_increment', $client_data['bid_increment']);
        }
        
        if (isset($client_data['featured'])) {
            update_post_meta($auction_id, '_auction_featured', $client_data['featured'] ? '1' : '0');
        }
        
        // Update meta data
        if (isset($client_data['meta_data']) && is_array($client_data['meta_data'])) {
            foreach ($client_data['meta_data'] as $meta) {
                if (isset($meta['key']) && isset($meta['value'])) {
                    update_post_meta($auction_id, $meta['key'], $meta['value']);
                }
            }
        }
        
        // Update sync version
        update_post_meta($auction_id, '_sync_version', time());
        
        return true;
    }

    /**
     * Resolve trade-in conflict.
     *
     * @param int $trade_in_id Trade-in ID.
     * @param array $client_data Client data.
     * @param int $user_id User ID.
     * @return bool
     */
    protected function resolve_trade_in_conflict($trade_in_id, $client_data, $user_id) {
        $trade_in = get_post($trade_in_id);
        
        if (!$trade_in || $trade_in->post_type !== 'trade_in_request') {
            return false;
        }
        
        // Check if user has permission to update this trade-in
        if ($trade_in->post_author != $user_id && !$this->is_current_user_admin()) {
            return false;
        }
        
        // Update trade-in data
        $args = array(
            'ID' => $trade_in_id,
        );
        
        if (isset($client_data['title'])) {
            $args['post_title'] = $client_data['title'];
        }
        
        if (isset($client_data['description'])) {
            $args['post_content'] = $client_data['description'];
        }
        
        if (!empty($args)) {
            wp_update_post($args);
        }
        
        // Only admins can update status and evaluation
        if ($this->is_current_user_admin()) {
            if (isset($client_data['status'])) {
                update_post_meta($trade_in_id, '_trade_in_status', $client_data['status']);
            }
            
            if (isset($client_data['evaluation'])) {
                if (isset($client_data['evaluation']['amount'])) {
                    update_post_meta($trade_in_id, '_trade_in_evaluation_amount', $client_data['evaluation']['amount']);
                }
                
                if (isset($client_data['evaluation']['notes'])) {
                    update_post_meta($trade_in_id, '_trade_in_evaluation_notes', $client_data['evaluation']['notes']);
                }
                
                if (isset($client_data['evaluation']['date'])) {
                    update_post_meta($trade_in_id, '_trade_in_evaluation_date', $client_data['evaluation']['date']);
                }
                
                if (isset($client_data['evaluation']['expiration_date'])) {
                    update_post_meta($trade_in_id, '_trade_in_expiration_date', $client_data['evaluation']['expiration_date']);
                }
                
                // Set evaluator
                update_post_meta($trade_in_id, '_trade_in_evaluator_id', $user_id);
            }
        }
        
        // Update trade-in meta
        if (isset($client_data['condition'])) {
            update_post_meta($trade_in_id, '_trade_in_condition', $client_data['condition']);
        }
        
        if (isset($client_data['age'])) {
            update_post_meta($trade_in_id, '_trade_in_age', $client_data['age']);
        }
        
        if (isset($client_data['brand'])) {
            update_post_meta($trade_in_id, '_trade_in_brand', $client_data['brand']);
        }
        
        if (isset($client_data['model'])) {
            update_post_meta($trade_in_id, '_trade_in_model', $client_data['model']);
        }
        
        if (isset($client_data['serial_number'])) {
            update_post_meta($trade_in_id, '_trade_in_serial_number', $client_data['serial_number']);
        }
        
        if (isset($client_data['asking_price'])) {
            update_post_meta($trade_in_id, '_trade_in_asking_price', $client_data['asking_price']);
        }
        
        if (isset($client_data['contact_preference'])) {
            update_post_meta($trade_in_id, '_trade_in_contact_preference', $client_data['contact_preference']);
        }
        
        // Update meta data
        if (isset($client_data['meta_data']) && is_array($client_data['meta_data'])) {
            foreach ($client_data['meta_data'] as $meta) {
                if (isset($meta['key']) && isset($meta['value'])) {
                    update_post_meta($trade_in_id, $meta['key'], $meta['value']);
                }
            }
        }
        
        // Update sync version
        update_post_meta($trade_in_id, '_sync_version', time());
        
        return true;
    }

    /**
     * Get schema for entity type.
     *
     * @param string $entity_type Entity type.
     * @return array|null
     */
    protected function get_schema_for_entity_type($entity_type) {
        switch ($entity_type) {
            case 'products':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'name' => array('type' => 'string'),
                        'slug' => array('type' => 'string'),
                        'permalink' => array('type' => 'string'),
                        'date_created' => array('type' => 'string', 'format' => 'date-time'),
                        'date_modified' => array('type' => 'string', 'format' => 'date-time'),
                        'type' => array('type' => 'string'),
                        'status' => array('type' => 'string'),
                        'featured' => array('type' => 'boolean'),
                        'catalog_visibility' => array('type' => 'string'),
                        'description' => array('type' => 'string'),
                        'short_description' => array('type' => 'string'),
                        'sku' => array('type' => 'string'),
                        'price' => array('type' => 'string'),
                        'regular_price' => array('type' => 'string'),
                        'sale_price' => array('type' => 'string'),
                        'date_on_sale_from' => array('type' => 'string', 'format' => 'date-time'),
                        'date_on_sale_to' => array('type' => 'string', 'format' => 'date-time'),
                        'on_sale' => array('type' => 'boolean'),
                        'purchasable' => array('type' => 'boolean'),
                        'total_sales' => array('type' => 'integer'),
                        'virtual' => array('type' => 'boolean'),
                        'downloadable' => array('type' => 'boolean'),
                        'downloads' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'download_limit' => array('type' => 'integer'),
                        'download_expiry' => array('type' => 'integer'),
                        'tax_status' => array('type' => 'string'),
                        'tax_class' => array('type' => 'string'),
                        'manage_stock' => array('type' => 'boolean'),
                        'stock_quantity' => array('type' => 'integer'),
                        'stock_status' => array('type' => 'string'),
                        'backorders' => array('type' => 'string'),
                        'backorders_allowed' => array('type' => 'boolean'),
                        'backordered' => array('type' => 'boolean'),
                        'sold_individually' => array('type' => 'boolean'),
                        'weight' => array('type' => 'string'),
                        'dimensions' => array('type' => 'object'),
                        'shipping_required' => array('type' => 'boolean'),
                        'shipping_taxable' => array('type' => 'boolean'),
                        'shipping_class' => array('type' => 'string'),
                        'shipping_class_id' => array('type' => 'integer'),
                        'reviews_allowed' => array('type' => 'boolean'),
                        'average_rating' => array('type' => 'string'),
                        'rating_count' => array('type' => 'integer'),
                        'related_ids' => array('type' => 'array', 'items' => array('type' => 'integer')),
                        'upsell_ids' => array('type' => 'array', 'items' => array('type' => 'integer')),
                        'cross_sell_ids' => array('type' => 'array', 'items' => array('type' => 'integer')),
                        'parent_id' => array('type' => 'integer'),
                        'purchase_note' => array('type' => 'string'),
                        'categories' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'tags' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'images' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'attributes' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'default_attributes' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'variations' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'menu_order' => array('type' => 'integer'),
                        'meta_data' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            case 'categories':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'name' => array('type' => 'string'),
                        'slug' => array('type' => 'string'),
                        'parent' => array('type' => 'integer'),
                        'description' => array('type' => 'string'),
                        'display' => array('type' => 'string'),
                        'image' => array('type' => 'object'),
                        'menu_order' => array('type' => 'integer'),
                        'count' => array('type' => 'integer'),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            case 'orders':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'parent_id' => array('type' => 'integer'),
                        'number' => array('type' => 'string'),
                        'order_key' => array('type' => 'string'),
                        'created_via' => array('type' => 'string'),
                        'version' => array('type' => 'string'),
                        'status' => array('type' => 'string'),
                        'currency' => array('type' => 'string'),
                        'date_created' => array('type' => 'string', 'format' => 'date-time'),
                        'date_modified' => array('type' => 'string', 'format' => 'date-time'),
                        'discount_total' => array('type' => 'string'),
                        'discount_tax' => array('type' => 'string'),
                        'shipping_total' => array('type' => 'string'),
                        'shipping_tax' => array('type' => 'string'),
                        'cart_tax' => array('type' => 'string'),
                        'total' => array('type' => 'string'),
                        'total_tax' => array('type' => 'string'),
                        'customer_id' => array('type' => 'integer'),
                        'customer_note' => array('type' => 'string'),
                        'billing' => array('type' => 'object'),
                        'shipping' => array('type' => 'object'),
                        'payment_method' => array('type' => 'string'),
                        'payment_method_title' => array('type' => 'string'),
                        'transaction_id' => array('type' => 'string'),
                        'date_paid' => array('type' => 'string', 'format' => 'date-time'),
                        'date_completed' => array('type' => 'string', 'format' => 'date-time'),
                        'cart_hash' => array('type' => 'string'),
                        'line_items' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'tax_lines' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'shipping_lines' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'fee_lines' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'coupon_lines' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'refunds' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'meta_data' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            case 'customers':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'date_created' => array('type' => 'string', 'format' => 'date-time'),
                        'date_modified' => array('type' => 'string', 'format' => 'date-time'),
                        'email' => array('type' => 'string'),
                        'first_name' => array('type' => 'string'),
                        'last_name' => array('type' => 'string'),
                        'username' => array('type' => 'string'),
                        'role' => array('type' => 'string'),
                        'billing' => array('type' => 'object'),
                        'shipping' => array('type' => 'object'),
                        'is_paying_customer' => array('type' => 'boolean'),
                        'meta_data' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            case 'auctions':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'title' => array('type' => 'string'),
                        'slug' => array('type' => 'string'),
                        'permalink' => array('type' => 'string'),
                        'date_created' => array('type' => 'string', 'format' => 'date-time'),
                        'date_modified' => array('type' => 'string', 'format' => 'date-time'),
                        'status' => array('type' => 'string'),
                        'description' => array('type' => 'string'),
                        'short_description' => array('type' => 'string'),
                        'start_date' => array('type' => 'string', 'format' => 'date-time'),
                        'end_date' => array('type' => 'string', 'format' => 'date-time'),
                        'time_remaining' => array('type' => 'integer'),
                        'starting_bid' => array('type' => 'number'),
                        'current_bid' => array('type' => 'number'),
                        'reserve_price' => array('type' => 'number'),
                        'reserve_met' => array('type' => 'boolean'),
                        'bid_increment' => array('type' => 'number'),
                        'featured' => array('type' => 'boolean'),
                        'current_bidder' => array('type' => 'object'),
                        'bid_count' => array('type' => 'integer'),
                        'bids' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'images' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'categories' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'meta_data' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            case 'trade_ins':
                return array(
                    'type' => 'object',
                    'properties' => array(
                        'id' => array('type' => 'integer'),
                        'title' => array('type' => 'string'),
                        'description' => array('type' => 'string'),
                        'status' => array('type' => 'string'),
                        'condition' => array('type' => 'string'),
                        'age' => array('type' => 'integer'),
                        'brand' => array('type' => 'string'),
                        'model' => array('type' => 'string'),
                        'serial_number' => array('type' => 'string'),
                        'asking_price' => array('type' => 'number'),
                        'contact_preference' => array('type' => 'string'),
                        'submission_date' => array('type' => 'string', 'format' => 'date-time'),
                        'date_created' => array('type' => 'string', 'format' => 'date-time'),
                        'date_modified' => array('type' => 'string', 'format' => 'date-time'),
                        'user' => array('type' => 'object'),
                        'images' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'categories' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'evaluation' => array('type' => 'object'),
                        'meta_data' => array('type' => 'array', 'items' => array('type' => 'object')),
                        'sync_version' => array('type' => 'integer'),
                    ),
                );
            default:
                return null;
        }
    }

    /**
     * Get sync settings data.
     *
     * @return array
     */
    protected function get_sync_settings_data() {
        $settings = array(
            'sync_interval' => get_option('aqualuxe_sync_interval', 15), // minutes
            'conflict_resolution' => get_option('aqualuxe_conflict_resolution', 'server'), // server or client
            'entities' => array(
                'products' => array(
                    'enabled' => get_option('aqualuxe_sync_products', true),
                    'batch_size' => get_option('aqualuxe_sync_products_batch', 50),
                ),
                'categories' => array(
                    'enabled' => get_option('aqualuxe_sync_categories', true),
                    'batch_size' => get_option('aqualuxe_sync_categories_batch', 100),
                ),
                'orders' => array(
                    'enabled' => get_option('aqualuxe_sync_orders', true),
                    'batch_size' => get_option('aqualuxe_sync_orders_batch', 20),
                ),
                'customers' => array(
                    'enabled' => get_option('aqualuxe_sync_customers', true),
                    'batch_size' => get_option('aqualuxe_sync_customers_batch', 50),
                ),
                'auctions' => array(
                    'enabled' => get_option('aqualuxe_sync_auctions', true),
                    'batch_size' => get_option('aqualuxe_sync_auctions_batch', 20),
                ),
                'trade_ins' => array(
                    'enabled' => get_option('aqualuxe_sync_trade_ins', true),
                    'batch_size' => get_option('aqualuxe_sync_trade_ins_batch', 20),
                ),
            ),
            'background_sync' => get_option('aqualuxe_background_sync', true),
            'network_requirements' => array(
                'wifi_only' => get_option('aqualuxe_sync_wifi_only', false),
                'battery_level' => get_option('aqualuxe_sync_battery_level', 20),
            ),
        );
        
        return $settings;
    }
}