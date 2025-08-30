<?php
/**
 * AquaLuxe API Notifications
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Notifications Class
 *
 * Handles API requests for notifications
 */
class AquaLuxe_API_Notifications extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'notifications';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get user notifications
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_notifications'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Mark notification as read
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/read', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'mark_as_read'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the notification.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Mark all notifications as read
        register_rest_route($this->namespace, '/' . $this->rest_base . '/read-all', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'mark_all_as_read'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
            ),
        ));

        // Delete notification
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_notification'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the notification.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Register device token
        register_rest_route($this->namespace, '/' . $this->rest_base . '/register-device', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'register_device'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'device_token' => array(
                        'description' => __('Device token for push notifications.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'device_type' => array(
                        'description' => __('Device type (ios, android).'),
                        'type' => 'string',
                        'required' => true,
                        'enum' => array('ios', 'android', 'web'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'device_name' => array(
                        'description' => __('Device name.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'app_version' => array(
                        'description' => __('App version.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'os_version' => array(
                        'description' => __('OS version.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Unregister device token
        register_rest_route($this->namespace, '/' . $this->rest_base . '/unregister-device', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'unregister_device'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'device_token' => array(
                        'description' => __('Device token for push notifications.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Get notification settings
        register_rest_route($this->namespace, '/' . $this->rest_base . '/settings', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_notification_settings'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
        ));

        // Update notification settings
        register_rest_route($this->namespace, '/' . $this->rest_base . '/settings', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_notification_settings'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'settings' => array(
                        'description' => __('Notification settings.'),
                        'type' => 'object',
                        'required' => true,
                    ),
                ),
            ),
        ));
    }

    /**
     * Get user notifications.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_notifications($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get filter params
        $is_read = $request->get_param('is_read');
        $type = $request->get_param('type');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        // Build query
        $query = "SELECT * FROM $table_name WHERE user_id = %d";
        $query_args = array($user_id);
        
        if (!is_null($is_read)) {
            $query .= " AND is_read = %d";
            $query_args[] = (int) $is_read;
        }
        
        if (!is_null($type)) {
            $query .= " AND type = %s";
            $query_args[] = $type;
        }
        
        // Count total
        $count_query = "SELECT COUNT(*) FROM ($query) AS t";
        $total = $wpdb->get_var($wpdb->prepare($count_query, $query_args));
        
        // Add pagination
        $query .= " ORDER BY date_created DESC LIMIT %d OFFSET %d";
        $query_args[] = $pagination['per_page'];
        $query_args[] = $pagination['offset'];
        
        // Get notifications
        $notifications = $wpdb->get_results($wpdb->prepare($query, $query_args));
        
        $data = array();
        
        foreach ($notifications as $notification) {
            $data[] = $this->prepare_notification_data($notification);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'notifications' => $data,
            'total' => (int) $total,
            'pages' => ceil($total / $pagination['per_page']),
            'unread_count' => $this->get_unread_count($user_id),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $total, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Mark notification as read.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function mark_as_read($request) {
        $start_time = microtime(true);
        
        $notification_id = $request->get_param('id');
        $user_id = $this->get_current_user_id();
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        // Check if notification exists and belongs to user
        $notification = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND user_id = %d",
                $notification_id,
                $user_id
            )
        );
        
        if (!$notification) {
            return $this->format_error('notification_not_found', __('Notification not found.'), 404);
        }
        
        // Mark as read
        $updated = $wpdb->update(
            $table_name,
            array(
                'is_read' => 1,
                'date_read' => current_time('mysql'),
            ),
            array(
                'id' => $notification_id,
            ),
            array(
                '%d',
                '%s',
            ),
            array(
                '%d',
            )
        );
        
        if ($updated === false) {
            return $this->format_error('update_failed', __('Failed to mark notification as read.'), 500);
        }
        
        // Get updated notification
        $notification = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $notification_id
            )
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'notification' => $this->prepare_notification_data($notification),
            'unread_count' => $this->get_unread_count($user_id),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Mark all notifications as read.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function mark_all_as_read($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        // Mark all as read
        $updated = $wpdb->update(
            $table_name,
            array(
                'is_read' => 1,
                'date_read' => current_time('mysql'),
            ),
            array(
                'user_id' => $user_id,
                'is_read' => 0,
            ),
            array(
                '%d',
                '%s',
            ),
            array(
                '%d',
                '%d',
            )
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'marked_count' => $updated,
            'unread_count' => 0,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Delete notification.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_notification($request) {
        $start_time = microtime(true);
        
        $notification_id = $request->get_param('id');
        $user_id = $this->get_current_user_id();
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        // Check if notification exists and belongs to user
        $notification = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND user_id = %d",
                $notification_id,
                $user_id
            )
        );
        
        if (!$notification) {
            return $this->format_error('notification_not_found', __('Notification not found.'), 404);
        }
        
        // Delete notification
        $deleted = $wpdb->delete(
            $table_name,
            array(
                'id' => $notification_id,
            ),
            array(
                '%d',
            )
        );
        
        if ($deleted === false) {
            return $this->format_error('delete_failed', __('Failed to delete notification.'), 500);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'deleted' => true,
            'unread_count' => $this->get_unread_count($user_id),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Register device token.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function register_device($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $device_token = $request->get_param('device_token');
        $device_type = $request->get_param('device_type');
        $device_name = $request->get_param('device_name');
        $app_version = $request->get_param('app_version');
        $os_version = $request->get_param('os_version');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        // Check if token already exists
        $existing_token = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE device_token = %s",
                $device_token
            )
        );
        
        $now = current_time('mysql');
        
        if ($existing_token) {
            // Update existing token
            $updated = $wpdb->update(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'device_type' => $device_type,
                    'device_name' => $device_name,
                    'app_version' => $app_version,
                    'os_version' => $os_version,
                    'date_updated' => $now,
                    'last_active' => $now,
                ),
                array(
                    'device_token' => $device_token,
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                ),
                array(
                    '%s',
                )
            );
            
            if ($updated === false) {
                return $this->format_error('update_failed', __('Failed to update device token.'), 500);
            }
            
            $token_id = $existing_token->id;
        } else {
            // Insert new token
            $inserted = $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'device_token' => $device_token,
                    'device_type' => $device_type,
                    'device_name' => $device_name,
                    'app_version' => $app_version,
                    'os_version' => $os_version,
                    'date_created' => $now,
                    'date_updated' => $now,
                    'last_active' => $now,
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
            
            if ($inserted === false) {
                return $this->format_error('insert_failed', __('Failed to register device token.'), 500);
            }
            
            $token_id = $wpdb->insert_id;
        }
        
        // Get token data
        $token = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $token_id
            )
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'token' => array(
                'id' => (int) $token->id,
                'user_id' => (int) $token->user_id,
                'device_token' => $token->device_token,
                'device_type' => $token->device_type,
                'device_name' => $token->device_name,
                'app_version' => $token->app_version,
                'os_version' => $token->os_version,
                'date_created' => $token->date_created,
                'date_updated' => $token->date_updated,
                'last_active' => $token->last_active,
            ),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Unregister device token.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function unregister_device($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $device_token = $request->get_param('device_token');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        // Check if token exists and belongs to user
        $token = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE device_token = %s AND user_id = %d",
                $device_token,
                $user_id
            )
        );
        
        if (!$token) {
            return $this->format_error('token_not_found', __('Device token not found.'), 404);
        }
        
        // Delete token
        $deleted = $wpdb->delete(
            $table_name,
            array(
                'device_token' => $device_token,
            ),
            array(
                '%s',
            )
        );
        
        if ($deleted === false) {
            return $this->format_error('delete_failed', __('Failed to unregister device token.'), 500);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'deleted' => true,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get notification settings.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_notification_settings($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get user settings
        $settings = get_user_meta($user_id, 'aqualuxe_notification_settings', true);
        
        if (!$settings) {
            // Default settings
            $settings = array(
                'order_status' => true,
                'promotions' => true,
                'auctions' => true,
                'trade_ins' => true,
                'new_products' => true,
                'care_reminders' => true,
                'subscription_reminders' => true,
                'push_enabled' => true,
                'email_enabled' => true,
            );
            
            update_user_meta($user_id, 'aqualuxe_notification_settings', $settings);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'settings' => $settings,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update notification settings.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_notification_settings($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $settings = $request->get_param('settings');
        
        // Get current settings
        $current_settings = get_user_meta($user_id, 'aqualuxe_notification_settings', true);
        
        if (!$current_settings) {
            $current_settings = array(
                'order_status' => true,
                'promotions' => true,
                'auctions' => true,
                'trade_ins' => true,
                'new_products' => true,
                'care_reminders' => true,
                'subscription_reminders' => true,
                'push_enabled' => true,
                'email_enabled' => true,
            );
        }
        
        // Update settings
        $updated_settings = array_merge($current_settings, $settings);
        
        // Save settings
        update_user_meta($user_id, 'aqualuxe_notification_settings', $updated_settings);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'settings' => $updated_settings,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get unread notifications count.
     *
     * @param int $user_id User ID.
     * @return int
     */
    protected function get_unread_count($user_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND is_read = 0",
                $user_id
            )
        );
        
        return (int) $count;
    }

    /**
     * Prepare notification data for API response.
     *
     * @param object $notification Notification object.
     * @return array
     */
    protected function prepare_notification_data($notification) {
        $data = array(
            'id' => (int) $notification->id,
            'user_id' => (int) $notification->user_id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => json_decode($notification->data),
            'is_read' => (bool) $notification->is_read,
            'date_created' => $notification->date_created,
            'date_read' => $notification->date_read,
        );
        
        return $data;
    }

    /**
     * Create notification.
     *
     * @param int $user_id User ID.
     * @param string $type Notification type.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @return int|false Notification ID or false on failure.
     */
    public static function create_notification($user_id, $type, $title, $message, $data = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'is_read' => 0,
                'date_created' => current_time('mysql'),
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            )
        );
        
        if ($inserted === false) {
            return false;
        }
        
        $notification_id = $wpdb->insert_id;
        
        // Send push notification
        $push_service = new AquaLuxe_API_Push_Service();
        $push_service->send_notification($user_id, $title, $message, $data, $type);
        
        return $notification_id;
    }

    /**
     * Create notification for multiple users.
     *
     * @param array $user_ids User IDs.
     * @param string $type Notification type.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @return array Array of notification IDs.
     */
    public static function create_notifications($user_ids, $type, $title, $message, $data = array()) {
        $notification_ids = array();
        
        foreach ($user_ids as $user_id) {
            $notification_id = self::create_notification($user_id, $type, $title, $message, $data);
            
            if ($notification_id) {
                $notification_ids[] = $notification_id;
            }
        }
        
        return $notification_ids;
    }

    /**
     * Create notification for all users.
     *
     * @param string $type Notification type.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @return array Array of notification IDs.
     */
    public static function create_notification_for_all_users($type, $title, $message, $data = array()) {
        $users = get_users(array(
            'fields' => 'ID',
        ));
        
        return self::create_notifications($users, $type, $title, $message, $data);
    }

    /**
     * Delete old notifications.
     *
     * @param int $days Number of days to keep notifications.
     * @return int Number of deleted notifications.
     */
    public static function delete_old_notifications($days = 30) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_notifications';
        
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        
        $deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $table_name WHERE date_created < %s",
                $date
            )
        );
        
        return $deleted;
    }
}