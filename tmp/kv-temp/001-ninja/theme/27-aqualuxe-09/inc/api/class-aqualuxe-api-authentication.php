<?php
/**
 * AquaLuxe API Authentication
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Authentication Class
 *
 * Handles authentication for the API
 */
class AquaLuxe_API_Authentication {

    /**
     * The namespace for this API.
     *
     * @var string
     */
    private $namespace;

    /**
     * JWT secret key.
     *
     * @var string
     */
    private $jwt_secret;

    /**
     * Token expiration time in seconds.
     *
     * @var int
     */
    private $token_expiration;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $api = AquaLuxe_API::get_instance();
        $this->namespace = $api->get_namespace();
        
        // Get JWT secret from options or generate a new one if it doesn't exist
        $this->jwt_secret = get_option('aqualuxe_jwt_secret');
        if (!$this->jwt_secret) {
            $this->jwt_secret = wp_generate_password(64, true, true);
            update_option('aqualuxe_jwt_secret', $this->jwt_secret);
        }
        
        // Set token expiration (default: 7 days)
        $this->token_expiration = apply_filters('aqualuxe_jwt_expiration', 7 * DAY_IN_SECONDS);
        
        // Add authentication hooks
        add_filter('determine_current_user', array($this, 'authenticate_request'), 20);
        add_filter('rest_authentication_errors', array($this, 'check_authentication_error'), 15);
    }

    /**
     * Register REST API routes.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route($this->namespace, '/auth/login', array(
            'methods' => 'POST',
            'callback' => array($this, 'login'),
            'permission_callback' => '__return_true',
            'args' => array(
                'username' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'password' => array(
                    'required' => true,
                    'type' => 'string',
                ),
            ),
        ));
        
        register_rest_route($this->namespace, '/auth/refresh', array(
            'methods' => 'POST',
            'callback' => array($this, 'refresh_token'),
            'permission_callback' => '__return_true',
            'args' => array(
                'refresh_token' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ));
        
        register_rest_route($this->namespace, '/auth/validate', array(
            'methods' => 'POST',
            'callback' => array($this, 'validate_token'),
            'permission_callback' => '__return_true',
        ));
        
        register_rest_route($this->namespace, '/auth/revoke', array(
            'methods' => 'POST',
            'callback' => array($this, 'revoke_token'),
            'permission_callback' => array($this, 'permission_callback'),
        ));
        
        register_rest_route($this->namespace, '/auth/register', array(
            'methods' => 'POST',
            'callback' => array($this, 'register_user'),
            'permission_callback' => '__return_true',
            'args' => array(
                'username' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_user',
                ),
                'email' => array(
                    'required' => true,
                    'type' => 'string',
                    'format' => 'email',
                    'sanitize_callback' => 'sanitize_email',
                ),
                'password' => array(
                    'required' => true,
                    'type' => 'string',
                    'minLength' => 8,
                ),
                'first_name' => array(
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'last_name' => array(
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ));
        
        register_rest_route($this->namespace, '/auth/forgot-password', array(
            'methods' => 'POST',
            'callback' => array($this, 'forgot_password'),
            'permission_callback' => '__return_true',
            'args' => array(
                'email' => array(
                    'required' => true,
                    'type' => 'string',
                    'format' => 'email',
                    'sanitize_callback' => 'sanitize_email',
                ),
            ),
        ));
        
        register_rest_route($this->namespace, '/auth/reset-password', array(
            'methods' => 'POST',
            'callback' => array($this, 'reset_password'),
            'permission_callback' => '__return_true',
            'args' => array(
                'key' => array(
                    'required' => true,
                    'type' => 'string',
                ),
                'login' => array(
                    'required' => true,
                    'type' => 'string',
                ),
                'password' => array(
                    'required' => true,
                    'type' => 'string',
                    'minLength' => 8,
                ),
            ),
        ));
    }

    /**
     * Login user and generate JWT token.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function login($request) {
        $username = $request->get_param('username');
        $password = $request->get_param('password');
        
        // Authenticate user
        $user = wp_authenticate($username, $password);
        
        if (is_wp_error($user)) {
            return new WP_Error(
                'invalid_credentials',
                'Invalid username or password',
                array('status' => 401)
            );
        }
        
        // Generate tokens
        $tokens = $this->generate_tokens($user);
        
        // Get user data
        $user_data = $this->get_user_data($user);
        
        // Log the login
        $this->log_authentication('login', $user->ID);
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'user' => $user_data,
            'token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->token_expiration,
        ));
    }

    /**
     * Refresh JWT token.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function refresh_token($request) {
        $refresh_token = $request->get_param('refresh_token');
        
        // Validate refresh token
        $token_data = $this->validate_jwt_token($refresh_token, true);
        
        if (is_wp_error($token_data)) {
            return $token_data;
        }
        
        // Get user
        $user = get_user_by('id', $token_data->data->user_id);
        
        if (!$user) {
            return new WP_Error(
                'invalid_user',
                'User not found',
                array('status' => 401)
            );
        }
        
        // Generate new tokens
        $tokens = $this->generate_tokens($user);
        
        // Log the refresh
        $this->log_authentication('refresh', $user->ID);
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->token_expiration,
        ));
    }

    /**
     * Validate JWT token.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function validate_token($request) {
        $token = $this->get_auth_token();
        
        if (!$token) {
            return new WP_Error(
                'invalid_token',
                'Token not provided',
                array('status' => 401)
            );
        }
        
        // Validate token
        $token_data = $this->validate_jwt_token($token);
        
        if (is_wp_error($token_data)) {
            return $token_data;
        }
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'valid' => true,
            'user_id' => $token_data->data->user_id,
            'expires_at' => $token_data->exp,
        ));
    }

    /**
     * Revoke JWT token.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function revoke_token($request) {
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            return new WP_Error(
                'not_authenticated',
                'User not authenticated',
                array('status' => 401)
            );
        }
        
        // Add token to blacklist
        $token = $this->get_auth_token();
        $this->blacklist_token($token);
        
        // Log the revocation
        $this->log_authentication('revoke', $user_id);
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Token revoked successfully',
        ));
    }

    /**
     * Register a new user.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function register_user($request) {
        // Check if registration is allowed
        if (!get_option('users_can_register')) {
            return new WP_Error(
                'registration_disabled',
                'User registration is disabled',
                array('status' => 403)
            );
        }
        
        $username = $request->get_param('username');
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        
        // Check if username exists
        if (username_exists($username)) {
            return new WP_Error(
                'username_exists',
                'Username already exists',
                array('status' => 400)
            );
        }
        
        // Check if email exists
        if (email_exists($email)) {
            return new WP_Error(
                'email_exists',
                'Email already exists',
                array('status' => 400)
            );
        }
        
        // Create user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            return $user_id;
        }
        
        // Set user role
        $user = new WP_User($user_id);
        $user->set_role('customer');
        
        // Update user meta
        if ($first_name) {
            update_user_meta($user_id, 'first_name', $first_name);
        }
        
        if ($last_name) {
            update_user_meta($user_id, 'last_name', $last_name);
        }
        
        // Generate tokens
        $tokens = $this->generate_tokens($user);
        
        // Get user data
        $user_data = $this->get_user_data($user);
        
        // Log the registration
        $this->log_authentication('register', $user_id);
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'user' => $user_data,
            'token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->token_expiration,
        ));
    }

    /**
     * Send password reset email.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function forgot_password($request) {
        $email = $request->get_param('email');
        
        // Get user by email
        $user = get_user_by('email', $email);
        
        if (!$user) {
            return new WP_Error(
                'invalid_email',
                'No user found with this email address',
                array('status' => 404)
            );
        }
        
        // Generate reset key
        $key = get_password_reset_key($user);
        
        if (is_wp_error($key)) {
            return $key;
        }
        
        // Send email
        $subject = sprintf(__('[%s] Password Reset'), get_bloginfo('name'));
        $message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
        $message .= sprintf(__('Site Name: %s'), get_bloginfo('name')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
        $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
        $message .= __('Or use these details in the mobile app:') . "\r\n\r\n";
        $message .= __('Key: ') . $key . "\r\n";
        $message .= __('Login: ') . $user->user_login . "\r\n\r\n";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        $mail_sent = wp_mail($user->user_email, $subject, $message, $headers);
        
        if (!$mail_sent) {
            return new WP_Error(
                'mail_error',
                'Error sending email',
                array('status' => 500)
            );
        }
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Password reset email sent',
        ));
    }

    /**
     * Reset user password.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function reset_password($request) {
        $key = $request->get_param('key');
        $login = $request->get_param('login');
        $password = $request->get_param('password');
        
        // Get user
        $user = check_password_reset_key($key, $login);
        
        if (is_wp_error($user)) {
            return $user;
        }
        
        // Reset password
        reset_password($user, $password);
        
        // Generate tokens
        $tokens = $this->generate_tokens($user);
        
        // Get user data
        $user_data = $this->get_user_data($user);
        
        // Log the password reset
        $this->log_authentication('reset_password', $user->ID);
        
        // Return response
        return rest_ensure_response(array(
            'success' => true,
            'user' => $user_data,
            'token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->token_expiration,
        ));
    }

    /**
     * Authenticate request using JWT token.
     *
     * @param int|bool $user_id Current user ID or false.
     * @return int|bool
     */
    public function authenticate_request($user_id) {
        // Skip if already authenticated
        if ($user_id) {
            return $user_id;
        }
        
        // Skip if not a REST API request
        if (!defined('REST_REQUEST') || !REST_REQUEST) {
            return $user_id;
        }
        
        // Get token
        $token = $this->get_auth_token();
        
        if (!$token) {
            return $user_id;
        }
        
        // Validate token
        $token_data = $this->validate_jwt_token($token);
        
        if (is_wp_error($token_data)) {
            return $user_id;
        }
        
        // Return user ID
        return $token_data->data->user_id;
    }

    /**
     * Check for authentication errors.
     *
     * @param WP_Error|null|bool $error Error from another authentication handler, null if no handler has been applied, true if authentication succeeded.
     * @return WP_Error|null|bool
     */
    public function check_authentication_error($error) {
        // Skip if already authenticated
        if (true === $error || is_wp_error($error)) {
            return $error;
        }
        
        // Skip if not a protected endpoint
        if (!$this->is_protected_endpoint()) {
            return $error;
        }
        
        // Get token
        $token = $this->get_auth_token();
        
        if (!$token) {
            return new WP_Error(
                'authentication_required',
                'Authentication is required',
                array('status' => 401)
            );
        }
        
        // Validate token
        $token_data = $this->validate_jwt_token($token);
        
        if (is_wp_error($token_data)) {
            return $token_data;
        }
        
        return $error;
    }

    /**
     * Check if current endpoint requires authentication.
     *
     * @return bool
     */
    private function is_protected_endpoint() {
        // Get current route
        $route = $GLOBALS['wp']->query_vars['rest_route'];
        
        if (empty($route)) {
            return false;
        }
        
        // Public endpoints
        $public_endpoints = apply_filters('aqualuxe_api_public_endpoints', array(
            '/' . $this->namespace . '/auth/login',
            '/' . $this->namespace . '/auth/register',
            '/' . $this->namespace . '/auth/forgot-password',
            '/' . $this->namespace . '/auth/reset-password',
            '/' . $this->namespace . '/auth/validate',
        ));
        
        // Check if current route is public
        foreach ($public_endpoints as $endpoint) {
            if (strpos($route, $endpoint) === 0) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Permission callback for protected endpoints.
     *
     * @return bool
     */
    public function permission_callback() {
        return is_user_logged_in();
    }

    /**
     * Generate JWT tokens for user.
     *
     * @param WP_User $user User object.
     * @return array
     */
    private function generate_tokens($user) {
        // Access token payload
        $access_token_payload = array(
            'iss' => get_bloginfo('url'),
            'iat' => time(),
            'exp' => time() + $this->token_expiration,
            'data' => array(
                'user_id' => $user->ID,
            ),
        );
        
        // Refresh token payload (longer expiration)
        $refresh_token_payload = array(
            'iss' => get_bloginfo('url'),
            'iat' => time(),
            'exp' => time() + (30 * DAY_IN_SECONDS),
            'data' => array(
                'user_id' => $user->ID,
                'is_refresh' => true,
            ),
        );
        
        // Generate tokens
        $access_token = $this->generate_jwt_token($access_token_payload);
        $refresh_token = $this->generate_jwt_token($refresh_token_payload);
        
        return array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        );
    }

    /**
     * Generate JWT token.
     *
     * @param array $payload Token payload.
     * @return string
     */
    private function generate_jwt_token($payload) {
        // Header
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT',
        );
        
        // Encode header
        $header_encoded = $this->base64url_encode(json_encode($header));
        
        // Encode payload
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        
        // Create signature
        $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $this->jwt_secret, true);
        $signature_encoded = $this->base64url_encode($signature);
        
        // Create token
        $token = "$header_encoded.$payload_encoded.$signature_encoded";
        
        return $token;
    }

    /**
     * Validate JWT token.
     *
     * @param string $token JWT token.
     * @param bool $is_refresh Whether this is a refresh token.
     * @return object|WP_Error
     */
    private function validate_jwt_token($token, $is_refresh = false) {
        // Check if token is blacklisted
        if ($this->is_token_blacklisted($token)) {
            return new WP_Error(
                'invalid_token',
                'Token has been revoked',
                array('status' => 401)
            );
        }
        
        // Split token
        $token_parts = explode('.', $token);
        
        if (count($token_parts) !== 3) {
            return new WP_Error(
                'invalid_token',
                'Invalid token format',
                array('status' => 401)
            );
        }
        
        // Get header and payload
        $header_encoded = $token_parts[0];
        $payload_encoded = $token_parts[1];
        $signature_encoded = $token_parts[2];
        
        // Verify signature
        $signature = $this->base64url_decode($signature_encoded);
        $expected_signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $this->jwt_secret, true);
        
        if (!hash_equals($signature, $expected_signature)) {
            return new WP_Error(
                'invalid_token',
                'Invalid token signature',
                array('status' => 401)
            );
        }
        
        // Decode payload
        $payload = json_decode($this->base64url_decode($payload_encoded));
        
        if (!$payload) {
            return new WP_Error(
                'invalid_token',
                'Invalid token payload',
                array('status' => 401)
            );
        }
        
        // Check expiration
        if (isset($payload->exp) && time() > $payload->exp) {
            return new WP_Error(
                'expired_token',
                'Token has expired',
                array('status' => 401)
            );
        }
        
        // Check issuer
        if (!isset($payload->iss) || $payload->iss !== get_bloginfo('url')) {
            return new WP_Error(
                'invalid_token',
                'Invalid token issuer',
                array('status' => 401)
            );
        }
        
        // Check if refresh token
        if ($is_refresh && (!isset($payload->data->is_refresh) || !$payload->data->is_refresh)) {
            return new WP_Error(
                'invalid_token',
                'Not a refresh token',
                array('status' => 401)
            );
        }
        
        // Check if access token
        if (!$is_refresh && isset($payload->data->is_refresh) && $payload->data->is_refresh) {
            return new WP_Error(
                'invalid_token',
                'Not an access token',
                array('status' => 401)
            );
        }
        
        return $payload;
    }

    /**
     * Get authentication token from request.
     *
     * @return string|false
     */
    private function get_auth_token() {
        // Check Authorization header
        $auth_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;
        
        if ($auth_header) {
            // Bearer token
            if (preg_match('/Bearer\s(\S+)/i', $auth_header, $matches)) {
                return $matches[1];
            }
        }
        
        // Check query parameter
        if (isset($_GET['token'])) {
            return sanitize_text_field($_GET['token']);
        }
        
        return false;
    }

    /**
     * Base64URL encode.
     *
     * @param string $data Data to encode.
     * @return string
     */
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64URL decode.
     *
     * @param string $data Data to decode.
     * @return string
     */
    private function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Add token to blacklist.
     *
     * @param string $token JWT token.
     * @return void
     */
    private function blacklist_token($token) {
        $blacklist = get_option('aqualuxe_token_blacklist', array());
        
        // Get token payload
        $token_parts = explode('.', $token);
        $payload = json_decode($this->base64url_decode($token_parts[1]));
        
        // Add token to blacklist with expiration
        $blacklist[$token] = isset($payload->exp) ? $payload->exp : (time() + $this->token_expiration);
        
        // Clean up expired tokens
        foreach ($blacklist as $t => $exp) {
            if (time() > $exp) {
                unset($blacklist[$t]);
            }
        }
        
        update_option('aqualuxe_token_blacklist', $blacklist);
    }

    /**
     * Check if token is blacklisted.
     *
     * @param string $token JWT token.
     * @return bool
     */
    private function is_token_blacklisted($token) {
        $blacklist = get_option('aqualuxe_token_blacklist', array());
        return isset($blacklist[$token]);
    }

    /**
     * Get user data.
     *
     * @param WP_User $user User object.
     * @return array
     */
    private function get_user_data($user) {
        return array(
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'display_name' => $user->display_name,
            'roles' => $user->roles,
            'avatar' => get_avatar_url($user->ID),
        );
    }

    /**
     * Log authentication event.
     *
     * @param string $action Authentication action.
     * @param int $user_id User ID.
     * @return void
     */
    private function log_authentication($action, $user_id) {
        $log = array(
            'time' => current_time('mysql'),
            'action' => $action,
            'user_id' => $user_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        );
        
        $logs = get_option('aqualuxe_auth_logs', array());
        array_unshift($logs, $log);
        
        // Keep only the last 1000 logs
        if (count($logs) > 1000) {
            $logs = array_slice($logs, 0, 1000);
        }
        
        update_option('aqualuxe_auth_logs', $logs);
    }
}