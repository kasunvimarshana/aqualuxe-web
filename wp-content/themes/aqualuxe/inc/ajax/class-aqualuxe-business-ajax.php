<?php
/**
 * AquaLuxe Business Module AJAX Handlers
 * 
 * Handles all AJAX requests for the comprehensive business operations
 * including wholesale, retail, trading, export, and service management
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Business_AJAX {
    
    /**
     * Initialize AJAX handlers
     */
    public static function init() {
        // Dashboard statistics
        add_action('wp_ajax_aqualuxe_get_dashboard_stats', [__CLASS__, 'get_dashboard_stats']);
        add_action('wp_ajax_aqualuxe_get_recent_activity', [__CLASS__, 'get_recent_activity']);
        
        // Product management
        add_action('wp_ajax_aqualuxe_load_products', [__CLASS__, 'load_products']);
        add_action('wp_ajax_nopriv_aqualuxe_load_products', [__CLASS__, 'load_products']);
        
        // Trading system
        add_action('wp_ajax_aqualuxe_load_trades', [__CLASS__, 'load_trades']);
        add_action('wp_ajax_aqualuxe_create_trade', [__CLASS__, 'create_trade']);
        add_action('wp_ajax_aqualuxe_respond_trade', [__CLASS__, 'respond_trade']);
        
        // Service booking
        add_action('wp_ajax_aqualuxe_book_service', [__CLASS__, 'book_service']);
        add_action('wp_ajax_aqualuxe_get_available_slots', [__CLASS__, 'get_available_slots']);
        
        // Export operations
        add_action('wp_ajax_aqualuxe_load_export_shipments', [__CLASS__, 'load_export_shipments']);
        add_action('wp_ajax_aqualuxe_get_shipment_tracking', [__CLASS__, 'get_shipment_tracking']);
        add_action('wp_ajax_aqualuxe_create_export_request', [__CLASS__, 'create_export_request']);
        
        // Wholesale operations
        add_action('wp_ajax_aqualuxe_apply_wholesale', [__CLASS__, 'apply_wholesale']);
        add_action('wp_ajax_aqualuxe_load_wholesale_orders', [__CLASS__, 'load_wholesale_orders']);
        
        // General business operations
        add_action('wp_ajax_aqualuxe_update_pricing', [__CLASS__, 'update_pricing']);
        add_action('wp_ajax_aqualuxe_calculate_shipping', [__CLASS__, 'calculate_shipping']);
    }

    /**
     * Get dashboard statistics
     */
    public static function get_dashboard_stats() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        $business_module = AquaLuxe_Business_Module::get_instance();
        $current_user_id = get_current_user_id();
        
        try {
            // Get product counts
            $fish_species = wp_count_posts('fish_species');
            $plants = wp_count_posts('aquatic_plants');
            
            // Get revenue data (last 30 days)
            $revenue_query = $wpdb->prepare("
                SELECT SUM(total_amount) as revenue
                FROM {$wpdb->prefix}aqualuxe_business_transactions 
                WHERE user_id = %d 
                AND transaction_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND status = 'completed'
            ", $current_user_id);
            
            $revenue = $wpdb->get_var($revenue_query) ?: 0;
            
            // Get pending orders
            $pending_orders = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(*) 
                FROM {$wpdb->prefix}aqualuxe_business_transactions 
                WHERE vendor_id = %d 
                AND status = 'pending'
            ", $current_user_id));
            
            // Get export statistics if user has export permissions
            $export_stats = null;
            if (current_user_can('manage_exports') || current_user_can('administrator')) {
                $export_stats = [
                    'total' => $wpdb->get_var("
                        SELECT COUNT(*) 
                        FROM {$wpdb->prefix}aqualuxe_export_shipments
                    "),
                    'countries' => $wpdb->get_var("
                        SELECT COUNT(DISTINCT destination_country) 
                        FROM {$wpdb->prefix}aqualuxe_export_shipments
                    "),
                    'revenue' => $wpdb->get_var("
                        SELECT SUM(total_value) 
                        FROM {$wpdb->prefix}aqualuxe_export_shipments 
                        WHERE status = 'delivered'
                    ") ?: 0
                ];
            }

            wp_send_json_success([
                'fish_species' => $fish_species->publish ?: 0,
                'plants' => $plants->publish ?: 0,
                'revenue' => floatval($revenue),
                'pending_orders' => intval($pending_orders),
                'export' => $export_stats
            ]);

        } catch (Exception $e) {
            error_log('AquaLuxe Dashboard Stats Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to load dashboard statistics']);
        }
    }

    /**
     * Get recent activity
     */
    public static function get_recent_activity() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        $current_user_id = get_current_user_id();
        
        try {
            // Get recent transactions
            $activities = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    'transaction' as type,
                    transaction_type as action,
                    total_amount as amount,
                    created_at as time,
                    status
                FROM {$wpdb->prefix}aqualuxe_business_transactions 
                WHERE user_id = %d 
                ORDER BY created_at DESC 
                LIMIT 10
            ", $current_user_id));
            
            // Format activities for display
            $formatted_activities = [];
            foreach ($activities as $activity) {
                $icon = self::get_activity_icon($activity->type, $activity->action);
                $description = self::format_activity_description($activity);
                $time = human_time_diff(strtotime($activity->time)) . ' ago';
                
                $formatted_activities[] = [
                    'icon' => $icon,
                    'description' => $description,
                    'time' => $time
                ];
            }

            wp_send_json_success($formatted_activities);

        } catch (Exception $e) {
            error_log('AquaLuxe Recent Activity Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to load recent activity']);
        }
    }

    /**
     * Load products with filtering
     */
    public static function load_products() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        $filters = isset($_POST['filters']) ? $_POST['filters'] : [];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = 12;
        
        try {
            // Build WP_Query arguments
            $args = [
                'post_type' => ['fish_species', 'aquatic_plants'],
                'post_status' => 'publish',
                'posts_per_page' => $per_page,
                'paged' => $page,
                'meta_query' => [],
                'tax_query' => []
            ];

            // Apply category filters
            if (!empty($filters['categories'])) {
                $args['post_type'] = $filters['categories'];
            }

            // Apply price filters
            if (!empty($filters['price_min']) || !empty($filters['price_max'])) {
                $price_query = ['key' => '_price', 'type' => 'NUMERIC'];
                
                if (!empty($filters['price_min'])) {
                    $price_query['value'] = floatval($filters['price_min']);
                    $price_query['compare'] = '>=';
                }
                
                if (!empty($filters['price_max'])) {
                    if (!empty($filters['price_min'])) {
                        $args['meta_query']['relation'] = 'AND';
                        $args['meta_query'][] = [
                            'key' => '_price',
                            'value' => floatval($filters['price_min']),
                            'type' => 'NUMERIC',
                            'compare' => '>='
                        ];
                        $args['meta_query'][] = [
                            'key' => '_price',
                            'value' => floatval($filters['price_max']),
                            'type' => 'NUMERIC',
                            'compare' => '<='
                        ];
                    } else {
                        $price_query['value'] = floatval($filters['price_max']);
                        $price_query['compare'] = '<=';
                        $args['meta_query'][] = $price_query;
                    }
                } else {
                    $args['meta_query'][] = $price_query;
                }
            }

            // Apply availability filters
            if (!empty($filters['availability'])) {
                $availability_query = ['relation' => 'OR'];
                foreach ($filters['availability'] as $availability) {
                    $availability_query[] = [
                        'key' => '_stock_status',
                        'value' => $availability,
                        'compare' => '='
                    ];
                }
                $args['meta_query'][] = $availability_query;
            }

            // Apply sorting
            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'name-asc':
                        $args['orderby'] = 'title';
                        $args['order'] = 'ASC';
                        break;
                    case 'name-desc':
                        $args['orderby'] = 'title';
                        $args['order'] = 'DESC';
                        break;
                    case 'price-asc':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'ASC';
                        break;
                    case 'price-desc':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'DESC';
                        break;
                    case 'date-desc':
                        $args['orderby'] = 'date';
                        $args['order'] = 'DESC';
                        break;
                }
            }

            $query = new WP_Query($args);
            $products = [];

            while ($query->have_posts()) {
                $query->the_post();
                $products[] = self::format_product_data(get_post());
            }
            wp_reset_postdata();

            // Prepare pagination
            $pagination = [
                'total' => $query->found_posts,
                'pages' => $query->max_num_pages,
                'current' => $page
            ];

            wp_send_json_success([
                'products' => $products,
                'count' => $query->found_posts,
                'pagination' => $pagination
            ]);

        } catch (Exception $e) {
            error_log('AquaLuxe Load Products Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to load products']);
        }
    }

    /**
     * Load trade requests
     */
    public static function load_trades() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        $type = sanitize_text_field($_POST['type'] ?? 'active');
        $current_user_id = get_current_user_id();
        
        try {
            $where_clause = '';
            switch ($type) {
                case 'active':
                    $where_clause = "status IN ('pending', 'in_progress')";
                    break;
                case 'my-trades':
                    $where_clause = $wpdb->prepare("requester_id = %d", $current_user_id);
                    break;
                case 'completed':
                    $where_clause = "status = 'completed'";
                    break;
            }

            $trades = $wpdb->get_results("
                SELECT te.*, u.display_name as user_name, u.user_email
                FROM {$wpdb->prefix}aqualuxe_trade_exchanges te
                LEFT JOIN {$wpdb->users} u ON te.requester_id = u.ID
                WHERE {$where_clause}
                ORDER BY te.created_at DESC
                LIMIT 20
            ");

            $formatted_trades = [];
            foreach ($trades as $trade) {
                $formatted_trades[] = [
                    'id' => $trade->id,
                    'user' => [
                        'name' => $trade->user_name,
                        'avatar' => get_avatar_url($trade->user_email, ['size' => 48])
                    ],
                    'description' => $trade->description,
                    'offering' => json_decode($trade->offering_items, true) ?: [],
                    'seeking' => json_decode($trade->seeking_items, true) ?: [],
                    'status' => $trade->status,
                    'can_respond' => ($trade->requester_id !== $current_user_id && $trade->status === 'pending')
                ];
            }

            wp_send_json_success($formatted_trades);

        } catch (Exception $e) {
            error_log('AquaLuxe Load Trades Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to load trades']);
        }
    }

    /**
     * Book a service
     */
    public static function book_service() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        $booking_data = $_POST['booking_data'];
        $current_user_id = get_current_user_id();
        
        try {
            // Validate booking data
            $required_fields = ['service_type', 'service_date', 'service_time'];
            foreach ($required_fields as $field) {
                if (empty($booking_data[$field])) {
                    wp_send_json_error(['message' => "Missing required field: {$field}"]);
                }
            }

            // Check availability
            $datetime = $booking_data['service_date'] . ' ' . $booking_data['service_time'] . ':00';
            $existing_booking = $wpdb->get_var($wpdb->prepare("
                SELECT id FROM {$wpdb->prefix}aqualuxe_service_bookings 
                WHERE scheduled_datetime = %s 
                AND status NOT IN ('cancelled', 'completed')
            ", $datetime));

            if ($existing_booking) {
                wp_send_json_error(['message' => 'This time slot is already booked']);
            }

            // Create booking
            $result = $wpdb->insert(
                $wpdb->prefix . 'aqualuxe_service_bookings',
                [
                    'customer_id' => $current_user_id,
                    'service_type' => sanitize_text_field($booking_data['service_type']),
                    'scheduled_datetime' => $datetime,
                    'notes' => sanitize_textarea_field($booking_data['service_notes'] ?? ''),
                    'status' => 'pending',
                    'created_at' => current_time('mysql')
                ],
                ['%d', '%s', '%s', '%s', '%s', '%s']
            );

            if ($result === false) {
                throw new Exception('Failed to create booking');
            }

            // Send confirmation email
            $booking_id = $wpdb->insert_id;
            self::send_booking_confirmation($booking_id, $booking_data);

            wp_send_json_success([
                'message' => 'Service booked successfully!',
                'booking_id' => $booking_id
            ]);

        } catch (Exception $e) {
            error_log('AquaLuxe Book Service Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to book service']);
        }
    }

    /**
     * Load export shipments
     */
    public static function load_export_shipments() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        if (!current_user_can('manage_exports') && !current_user_can('administrator')) {
            wp_send_json_error(['message' => 'Insufficient permissions']);
        }

        global $wpdb;
        
        try {
            $shipments = $wpdb->get_results("
                SELECT * FROM {$wpdb->prefix}aqualuxe_export_shipments 
                WHERE status IN ('processing', 'shipped', 'in_transit')
                ORDER BY created_at DESC
                LIMIT 20
            ");

            $formatted_shipments = [];
            foreach ($shipments as $shipment) {
                $formatted_shipments[] = [
                    'id' => $shipment->id,
                    'tracking_number' => $shipment->tracking_number,
                    'destination' => $shipment->destination_country,
                    'status' => $shipment->status,
                    'value' => $shipment->total_value,
                    'created_date' => date('M j, Y', strtotime($shipment->created_at)),
                    'estimated_delivery' => $shipment->estimated_delivery
                ];
            }

            wp_send_json_success($formatted_shipments);

        } catch (Exception $e) {
            error_log('AquaLuxe Load Export Shipments Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to load export shipments']);
        }
    }

    /**
     * Get shipment tracking details
     */
    public static function get_shipment_tracking() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
            wp_die('Security check failed');
        }

        global $wpdb;
        $shipment_id = intval($_POST['shipment_id']);
        
        try {
            $shipment = $wpdb->get_row($wpdb->prepare("
                SELECT * FROM {$wpdb->prefix}aqualuxe_export_shipments 
                WHERE id = %d
            ", $shipment_id));

            if (!$shipment) {
                wp_send_json_error(['message' => 'Shipment not found']);
            }

            // Generate tracking timeline
            $timeline = self::generate_tracking_timeline($shipment);

            wp_send_json_success([
                'tracking_number' => $shipment->tracking_number,
                'status' => ucfirst($shipment->status),
                'destination' => $shipment->destination_country,
                'estimated_delivery' => date('M j, Y', strtotime($shipment->estimated_delivery)),
                'timeline' => $timeline
            ]);

        } catch (Exception $e) {
            error_log('AquaLuxe Get Shipment Tracking Error: ' . $e->getMessage());
            wp_send_json_error(['message' => 'Failed to get tracking information']);
        }
    }

    /**
     * Helper Methods
     */
    private static function format_product_data($post) {
        $pricing = self::get_product_pricing($post->ID);
        $specs = self::get_product_specs($post->ID);
        $badges = self::get_product_badges($post->ID);

        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'url' => get_permalink($post->ID),
            'image' => get_the_post_thumbnail_url($post->ID, 'medium'),
            'category' => get_post_type($post->ID) === 'fish_species' ? 'Fish Species' : 'Aquatic Plants',
            'specs' => $specs,
            'pricing' => $pricing,
            'badges' => $badges
        ];
    }

    private static function get_product_pricing($product_id) {
        $retail_price = get_post_meta($product_id, '_price', true);
        $wholesale_price = get_post_meta($product_id, '_wholesale_price', true);
        $bulk_price = get_post_meta($product_id, '_bulk_price', true);

        return [
            'retail' => [
                'current' => number_format(floatval($retail_price), 2),
                'original' => null,
                'discount' => null
            ],
            'wholesale' => [
                'current' => number_format(floatval($wholesale_price ?: $retail_price * 0.8), 2),
                'notes' => 'Minimum order applies'
            ],
            'bulk' => [
                'current' => number_format(floatval($bulk_price ?: $retail_price * 0.65), 2),
                'notes' => 'Bulk discount for 10+ items'
            ]
        ];
    }

    private static function get_product_specs($product_id) {
        $specs = [];
        
        // Get common specs based on post type
        if (get_post_type($product_id) === 'fish_species') {
            $size = get_post_meta($product_id, '_fish_size', true);
            $temperament = get_post_meta($product_id, '_temperament', true);
            $water_type = get_post_meta($product_id, '_water_type', true);
            
            if ($size) $specs[] = "Size: {$size}";
            if ($temperament) $specs[] = "Temperament: {$temperament}";
            if ($water_type) $specs[] = "Water: {$water_type}";
        } else {
            $light_requirement = get_post_meta($product_id, '_light_requirement', true);
            $growth_rate = get_post_meta($product_id, '_growth_rate', true);
            
            if ($light_requirement) $specs[] = "Light: {$light_requirement}";
            if ($growth_rate) $specs[] = "Growth: {$growth_rate}";
        }

        return $specs;
    }

    private static function get_product_badges($product_id) {
        $badges = [];
        
        // Check for various badge conditions
        $stock_status = get_post_meta($product_id, '_stock_status', true);
        $is_rare = get_post_meta($product_id, '_is_rare', true);
        $export_available = get_post_meta($product_id, '_export_available', true);
        
        if ($stock_status === 'in_stock') {
            $badges[] = ['type' => 'new', 'text' => 'In Stock'];
        }
        
        if ($is_rare) {
            $badges[] = ['type' => 'rare', 'text' => 'Rare'];
        }
        
        if ($export_available) {
            $badges[] = ['type' => 'export', 'text' => 'Export Available'];
        }

        return $badges;
    }

    private static function get_activity_icon($type, $action) {
        $icons = [
            'transaction' => [
                'sale' => '💰',
                'purchase' => '🛒',
                'trade' => '🔄',
                'export' => '🌍'
            ],
            'service' => '⚙️',
            'product' => '📦'
        ];

        return $icons[$type][$action] ?? $icons[$type] ?? '📋';
    }

    private static function format_activity_description($activity) {
        switch ($activity->action) {
            case 'sale':
                return "Sold item for $" . number_format($activity->amount, 2);
            case 'purchase':
                return "Purchased item for $" . number_format($activity->amount, 2);
            case 'trade':
                return "Completed trade exchange";
            case 'export':
                return "Export shipment worth $" . number_format($activity->amount, 2);
            default:
                return "Business activity: " . $activity->action;
        }
    }

    private static function generate_tracking_timeline($shipment) {
        // This would typically integrate with actual shipping APIs
        // For now, generate a sample timeline based on shipment status
        
        $timeline = [
            [
                'title' => 'Order Processed',
                'description' => 'Export documentation prepared and verified',
                'date' => date('M j, Y g:i A', strtotime($shipment->created_at)),
                'status' => 'completed',
                'icon' => '📋'
            ],
            [
                'title' => 'Shipped',
                'description' => 'Package dispatched from facility',
                'date' => date('M j, Y g:i A', strtotime($shipment->shipped_date ?? $shipment->created_at . ' +1 day')),
                'status' => in_array($shipment->status, ['shipped', 'in_transit', 'delivered']) ? 'completed' : 'pending',
                'icon' => '🚚'
            ],
            [
                'title' => 'In Transit',
                'description' => 'Package is on its way to destination',
                'date' => in_array($shipment->status, ['in_transit', 'delivered']) ? 
                    date('M j, Y g:i A', strtotime($shipment->created_at . ' +3 days')) : '',
                'status' => in_array($shipment->status, ['in_transit', 'delivered']) ? 'completed' : 'pending',
                'icon' => '✈️'
            ],
            [
                'title' => 'Delivered',
                'description' => 'Package delivered to recipient',
                'date' => $shipment->status === 'delivered' ? 
                    date('M j, Y g:i A', strtotime($shipment->delivered_date)) : '',
                'status' => $shipment->status === 'delivered' ? 'completed' : 'pending',
                'icon' => '📦'
            ]
        ];

        return array_filter($timeline, function($event) {
            return $event['status'] === 'completed' || !empty($event['date']);
        });
    }

    private static function send_booking_confirmation($booking_id, $booking_data) {
        // Implementation for sending booking confirmation email
        // This would integrate with WordPress mail system
        
        $user = wp_get_current_user();
        $subject = 'AquaLuxe Service Booking Confirmation';
        $message = sprintf(
            "Your %s service has been booked for %s at %s.\n\nBooking ID: %d\n\nThank you for choosing AquaLuxe!",
            $booking_data['service_type'],
            $booking_data['service_date'],
            $booking_data['service_time'],
            $booking_id
        );
        
        wp_mail($user->user_email, $subject, $message);
    }
}

// Initialize AJAX handlers
AquaLuxe_Business_AJAX::init();
