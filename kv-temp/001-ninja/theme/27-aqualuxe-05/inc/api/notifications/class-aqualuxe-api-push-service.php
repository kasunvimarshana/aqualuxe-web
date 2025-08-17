<?php
/**
 * AquaLuxe API Push Service
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Push_Service Class
 *
 * Handles push notifications
 */
class AquaLuxe_API_Push_Service {

    /**
     * Firebase API key.
     *
     * @var string
     */
    private $firebase_api_key;

    /**
     * Firebase server URL.
     *
     * @var string
     */
    private $firebase_url = 'https://fcm.googleapis.com/fcm/send';

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->firebase_api_key = get_option('aqualuxe_firebase_api_key', '');
    }

    /**
     * Send push notification to user.
     *
     * @param int $user_id User ID.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array|false Array of results or false on failure.
     */
    public function send_notification($user_id, $title, $message, $data = array(), $type = '') {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Get user notification settings
        $settings = get_user_meta($user_id, 'aqualuxe_notification_settings', true);
        
        if (!$settings) {
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
        }
        
        // Check if push notifications are enabled
        if (!isset($settings['push_enabled']) || !$settings['push_enabled']) {
            return false;
        }
        
        // Check if notification type is enabled
        if (!empty($type) && isset($settings[$type]) && !$settings[$type]) {
            return false;
        }
        
        // Get user device tokens
        $device_tokens = $this->get_user_device_tokens($user_id);
        
        if (empty($device_tokens)) {
            return false;
        }
        
        $results = array();
        
        // Group tokens by device type
        $grouped_tokens = array(
            'ios' => array(),
            'android' => array(),
            'web' => array(),
        );
        
        foreach ($device_tokens as $token) {
            $grouped_tokens[$token->device_type][] = $token->device_token;
        }
        
        // Send to iOS devices
        if (!empty($grouped_tokens['ios'])) {
            $ios_result = $this->send_ios_notification($grouped_tokens['ios'], $title, $message, $data, $type);
            
            if ($ios_result) {
                $results['ios'] = $ios_result;
            }
        }
        
        // Send to Android devices
        if (!empty($grouped_tokens['android'])) {
            $android_result = $this->send_android_notification($grouped_tokens['android'], $title, $message, $data, $type);
            
            if ($android_result) {
                $results['android'] = $android_result;
            }
        }
        
        // Send to web devices
        if (!empty($grouped_tokens['web'])) {
            $web_result = $this->send_web_notification($grouped_tokens['web'], $title, $message, $data, $type);
            
            if ($web_result) {
                $results['web'] = $web_result;
            }
        }
        
        // Log results
        $this->log_notification($user_id, $title, $message, $data, $type, $results);
        
        return $results;
    }

    /**
     * Send push notification to multiple users.
     *
     * @param array $user_ids User IDs.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array Array of results.
     */
    public function send_notifications($user_ids, $title, $message, $data = array(), $type = '') {
        $results = array();
        
        foreach ($user_ids as $user_id) {
            $result = $this->send_notification($user_id, $title, $message, $data, $type);
            
            if ($result) {
                $results[$user_id] = $result;
            }
        }
        
        return $results;
    }

    /**
     * Send push notification to all users.
     *
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array Array of results.
     */
    public function send_notification_to_all_users($title, $message, $data = array(), $type = '') {
        $users = get_users(array(
            'fields' => 'ID',
        ));
        
        return $this->send_notifications($users, $title, $message, $data, $type);
    }

    /**
     * Send push notification to topic.
     *
     * @param string $topic Topic name.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array|false Array of results or false on failure.
     */
    public function send_notification_to_topic($topic, $title, $message, $data = array(), $type = '') {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Prepare notification data
        $notification = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default',
            'badge' => '1',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        );
        
        // Add notification type to data
        if (!empty($type)) {
            $data['notification_type'] = $type;
        }
        
        // Prepare payload
        $fields = array(
            'to' => '/topics/' . $topic,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        );
        
        // Send notification
        $result = $this->send_firebase_notification($fields);
        
        // Log results
        $this->log_notification(0, $title, $message, $data, $type, $result, $topic);
        
        return $result;
    }

    /**
     * Send iOS notification.
     *
     * @param array $tokens Device tokens.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array|false Array of results or false on failure.
     */
    private function send_ios_notification($tokens, $title, $message, $data = array(), $type = '') {
        // Prepare notification data
        $notification = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default',
            'badge' => '1',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        );
        
        // Add notification type to data
        if (!empty($type)) {
            $data['notification_type'] = $type;
        }
        
        // Prepare payload
        $fields = array(
            'registration_ids' => $tokens,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        );
        
        // Send notification
        return $this->send_firebase_notification($fields);
    }

    /**
     * Send Android notification.
     *
     * @param array $tokens Device tokens.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array|false Array of results or false on failure.
     */
    private function send_android_notification($tokens, $title, $message, $data = array(), $type = '') {
        // Add notification data to data payload for Android
        $data['title'] = $title;
        $data['body'] = $message;
        $data['sound'] = 'default';
        $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        
        // Add notification type to data
        if (!empty($type)) {
            $data['notification_type'] = $type;
        }
        
        // Prepare payload
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
            'priority' => 'high',
        );
        
        // Send notification
        return $this->send_firebase_notification($fields);
    }

    /**
     * Send web notification.
     *
     * @param array $tokens Device tokens.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @return array|false Array of results or false on failure.
     */
    private function send_web_notification($tokens, $title, $message, $data = array(), $type = '') {
        // Prepare notification data
        $notification = array(
            'title' => $title,
            'body' => $message,
            'icon' => get_site_icon_url(),
            'click_action' => site_url(),
        );
        
        // Add notification type to data
        if (!empty($type)) {
            $data['notification_type'] = $type;
        }
        
        // Prepare payload
        $fields = array(
            'registration_ids' => $tokens,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        );
        
        // Send notification
        return $this->send_firebase_notification($fields);
    }

    /**
     * Send Firebase notification.
     *
     * @param array $fields Notification fields.
     * @return array|false Array of results or false on failure.
     */
    private function send_firebase_notification($fields) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Prepare headers
        $headers = array(
            'Authorization: key=' . $this->firebase_api_key,
            'Content-Type: application/json',
        );
        
        // Prepare request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->firebase_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        // Execute request
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for errors
        if ($result === false) {
            return false;
        }
        
        // Parse result
        $result = json_decode($result, true);
        
        // Add request info
        $result['request'] = $fields;
        $result['info'] = $info;
        $result['error'] = $error;
        
        return $result;
    }

    /**
     * Get user device tokens.
     *
     * @param int $user_id User ID.
     * @return array Array of device tokens.
     */
    private function get_user_device_tokens($user_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        $tokens = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d",
                $user_id
            )
        );
        
        return $tokens;
    }

    /**
     * Log notification.
     *
     * @param int $user_id User ID.
     * @param string $title Notification title.
     * @param string $message Notification message.
     * @param array $data Additional data.
     * @param string $type Notification type.
     * @param array $result Notification result.
     * @param string $topic Topic name.
     * @return void
     */
    private function log_notification($user_id, $title, $message, $data, $type, $result, $topic = '') {
        // Create log entry
        $log = array(
            'time' => current_time('mysql'),
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'type' => $type,
            'result' => $result,
            'topic' => $topic,
        );
        
        // Get existing logs
        $logs = get_option('aqualuxe_push_notification_logs', array());
        
        // Add new log
        array_unshift($logs, $log);
        
        // Keep only the last 1000 logs
        if (count($logs) > 1000) {
            $logs = array_slice($logs, 0, 1000);
        }
        
        // Save logs
        update_option('aqualuxe_push_notification_logs', $logs);
    }

    /**
     * Get notification logs.
     *
     * @param int $limit Number of logs to get.
     * @param int $offset Offset.
     * @return array Array of logs.
     */
    public function get_logs($limit = 100, $offset = 0) {
        $logs = get_option('aqualuxe_push_notification_logs', array());
        
        return array_slice($logs, $offset, $limit);
    }

    /**
     * Get notification logs count.
     *
     * @return int Number of logs.
     */
    public function get_logs_count() {
        $logs = get_option('aqualuxe_push_notification_logs', array());
        
        return count($logs);
    }

    /**
     * Clear notification logs.
     *
     * @return void
     */
    public function clear_logs() {
        update_option('aqualuxe_push_notification_logs', array());
    }

    /**
     * Get Firebase API key.
     *
     * @return string Firebase API key.
     */
    public function get_firebase_api_key() {
        return $this->firebase_api_key;
    }

    /**
     * Set Firebase API key.
     *
     * @param string $api_key Firebase API key.
     * @return void
     */
    public function set_firebase_api_key($api_key) {
        $this->firebase_api_key = $api_key;
        update_option('aqualuxe_firebase_api_key', $api_key);
    }

    /**
     * Subscribe user to topic.
     *
     * @param int $user_id User ID.
     * @param string $topic Topic name.
     * @return bool True on success, false on failure.
     */
    public function subscribe_user_to_topic($user_id, $topic) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Get user device tokens
        $device_tokens = $this->get_user_device_tokens($user_id);
        
        if (empty($device_tokens)) {
            return false;
        }
        
        $tokens = array();
        
        foreach ($device_tokens as $token) {
            $tokens[] = $token->device_token;
        }
        
        // Subscribe tokens to topic
        return $this->subscribe_tokens_to_topic($tokens, $topic);
    }

    /**
     * Unsubscribe user from topic.
     *
     * @param int $user_id User ID.
     * @param string $topic Topic name.
     * @return bool True on success, false on failure.
     */
    public function unsubscribe_user_from_topic($user_id, $topic) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Get user device tokens
        $device_tokens = $this->get_user_device_tokens($user_id);
        
        if (empty($device_tokens)) {
            return false;
        }
        
        $tokens = array();
        
        foreach ($device_tokens as $token) {
            $tokens[] = $token->device_token;
        }
        
        // Unsubscribe tokens from topic
        return $this->unsubscribe_tokens_from_topic($tokens, $topic);
    }

    /**
     * Subscribe tokens to topic.
     *
     * @param array $tokens Device tokens.
     * @param string $topic Topic name.
     * @return bool True on success, false on failure.
     */
    public function subscribe_tokens_to_topic($tokens, $topic) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Prepare request
        $url = 'https://iid.googleapis.com/iid/v1:batchAdd';
        $headers = array(
            'Authorization: key=' . $this->firebase_api_key,
            'Content-Type: application/json',
        );
        $fields = array(
            'to' => '/topics/' . $topic,
            'registration_tokens' => $tokens,
        );
        
        // Send request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        // Check result
        if ($result === false) {
            return false;
        }
        
        $result = json_decode($result, true);
        
        return isset($result['results']) && count($result['results']) === count($tokens);
    }

    /**
     * Unsubscribe tokens from topic.
     *
     * @param array $tokens Device tokens.
     * @param string $topic Topic name.
     * @return bool True on success, false on failure.
     */
    public function unsubscribe_tokens_from_topic($tokens, $topic) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Prepare request
        $url = 'https://iid.googleapis.com/iid/v1:batchRemove';
        $headers = array(
            'Authorization: key=' . $this->firebase_api_key,
            'Content-Type: application/json',
        );
        $fields = array(
            'to' => '/topics/' . $topic,
            'registration_tokens' => $tokens,
        );
        
        // Send request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        // Check result
        if ($result === false) {
            return false;
        }
        
        $result = json_decode($result, true);
        
        return isset($result['results']) && count($result['results']) === count($tokens);
    }

    /**
     * Get token info.
     *
     * @param string $token Device token.
     * @return array|false Token info or false on failure.
     */
    public function get_token_info($token) {
        // Check if Firebase API key is set
        if (empty($this->firebase_api_key)) {
            return false;
        }
        
        // Prepare request
        $url = 'https://iid.googleapis.com/iid/info/' . $token . '?details=true';
        $headers = array(
            'Authorization: key=' . $this->firebase_api_key,
        );
        
        // Send request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        // Check result
        if ($result === false) {
            return false;
        }
        
        return json_decode($result, true);
    }

    /**
     * Clean up invalid tokens.
     *
     * @return int Number of deleted tokens.
     */
    public function clean_up_tokens() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        // Get all tokens
        $tokens = $wpdb->get_results("SELECT * FROM $table_name");
        
        $deleted = 0;
        
        foreach ($tokens as $token) {
            // Check token info
            $info = $this->get_token_info($token->device_token);
            
            // Delete token if invalid
            if ($info === false || isset($info['error'])) {
                $wpdb->delete(
                    $table_name,
                    array(
                        'id' => $token->id,
                    ),
                    array(
                        '%d',
                    )
                );
                
                $deleted++;
            }
        }
        
        return $deleted;
    }
}