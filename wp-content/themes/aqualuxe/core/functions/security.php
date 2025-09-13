<?php
/**
 * Security Functions
 *
 * Security-related functions and hardening
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove WordPress version from head and feeds
 */
function aqualuxe_remove_version() {
    return '';
}
add_filter('the_generator', 'aqualuxe_remove_version');

/**
 * Hide WordPress version in scripts and styles
 * Note: This is handled in performance-functions.php to avoid duplication
 */

/**
 * Remove unnecessary header information
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove X-Pingback header
 */
function aqualuxe_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'aqualuxe_remove_x_pingback');

/**
 * Sanitize file uploads
 */
function aqualuxe_sanitize_file_name($filename) {
    $sanitized_filename = remove_accents($filename);
    $invalid = array(
        ' ' => '-',
        '%20' => '-',
        '_' => '-',
    );
    $sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);
    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename);
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename);
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename);
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename);
    return $sanitized_filename;
}
add_filter('sanitize_file_name', 'aqualuxe_sanitize_file_name', 10);

/**
 * Restrict file upload types
 */
function aqualuxe_restrict_upload_mimes($existing_mimes) {
    // Remove potentially dangerous file types
    unset($existing_mimes['swf']);
    unset($existing_mimes['exe']);
    unset($existing_mimes['flv']);
    
    // Add safe file types if needed
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['webp'] = 'image/webp';
    
    return $existing_mimes;
}
add_filter('upload_mimes', 'aqualuxe_restrict_upload_mimes');



/**
 * Sanitize user input
 */
function aqualuxe_sanitize_input($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = aqualuxe_sanitize_input($value);
        }
    } else {
        $input = htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    return $input;
}

/**
 * Validate email addresses
 */
function aqualuxe_validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate secure nonce
 */
function aqualuxe_create_nonce($action) {
    return wp_create_nonce('aqualuxe_' . $action);
}

/**
 * Verify nonce
 */
function aqualuxe_verify_nonce($nonce, $action) {
    return wp_verify_nonce($nonce, 'aqualuxe_' . $action);
}

/**
 * Rate limiting for AJAX requests
 */
function aqualuxe_rate_limit_check($action, $limit = 10, $window = 60) {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'aqualuxe_rate_limit_' . $action . '_' . md5($user_ip);
    
    $requests = get_transient($transient_key);
    
    if ($requests === false) {
        set_transient($transient_key, 1, $window);
        return true;
    }
    
    if ($requests >= $limit) {
        return false;
    }
    
    set_transient($transient_key, $requests + 1, $window);
    return true;
}

/**
 * Escape output functions
 */
function aqualuxe_esc_html($text) {
    return esc_html($text);
}

function aqualuxe_esc_attr($text) {
    return esc_attr($text);
}

function aqualuxe_esc_url($url) {
    return esc_url($url);
}

function aqualuxe_esc_js($text) {
    return esc_js($text);
}

/**
 * Validate and sanitize form data
 */
function aqualuxe_validate_form_data($data, $rules) {
    $validated = array();
    $errors = array();
    
    foreach ($rules as $field => $rule) {
        $value = isset($data[$field]) ? $data[$field] : '';
        
        // Required field check
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = sprintf(__('%s is required', 'aqualuxe'), $rule['label']);
            continue;
        }
        
        // Skip validation if field is empty and not required
        if (empty($value)) {
            $validated[$field] = '';
            continue;
        }
        
        // Type-specific validation
        switch ($rule['type']) {
            case 'email':
                if (!aqualuxe_validate_email($value)) {
                    $errors[$field] = sprintf(__('%s must be a valid email address', 'aqualuxe'), $rule['label']);
                } else {
                    $validated[$field] = sanitize_email($value);
                }
                break;
                
            case 'url':
                $validated[$field] = esc_url_raw($value);
                break;
                
            case 'text':
                $validated[$field] = sanitize_text_field($value);
                break;
                
            case 'textarea':
                $validated[$field] = sanitize_textarea_field($value);
                break;
                
            case 'html':
                $validated[$field] = wp_kses_post($value);
                break;
                
            case 'integer':
                $validated[$field] = intval($value);
                break;
                
            case 'float':
                $validated[$field] = floatval($value);
                break;
                
            default:
                $validated[$field] = aqualuxe_sanitize_input($value);
        }
        
        // Length validation
        if (isset($rule['min_length']) && strlen($validated[$field]) < $rule['min_length']) {
            $errors[$field] = sprintf(__('%s must be at least %d characters long', 'aqualuxe'), $rule['label'], $rule['min_length']);
        }
        
        if (isset($rule['max_length']) && strlen($validated[$field]) > $rule['max_length']) {
            $errors[$field] = sprintf(__('%s must be no more than %d characters long', 'aqualuxe'), $rule['label'], $rule['max_length']);
        }
    }
    
    return array(
        'data' => $validated,
        'errors' => $errors,
        'valid' => empty($errors)
    );
}

/**
 * Log security events with enhanced information
 */
function aqualuxe_log_security_event($event, $details = array()) {
    if (!function_exists('error_log')) {
        return;
    }
    
    // Get user IP safely
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'event' => sanitize_text_field($event),
        'ip' => sanitize_text_field($ip),
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : 'Unknown',
        'user_id' => get_current_user_id(),
        'request_uri' => isset($_SERVER['REQUEST_URI']) ? sanitize_text_field($_SERVER['REQUEST_URI']) : '',
        'details' => is_array($details) ? $details : array($details)
    );
    
    // Filter sensitive information
    if (isset($log_entry['details']['password'])) {
        $log_entry['details']['password'] = '[REDACTED]';
    }
    
    error_log('AquaLuxe Security: ' . wp_json_encode($log_entry));
    
    // Store in database for admin review (optional)
    if (get_option('aqualuxe_log_security_events', false)) {
        $logs = get_option('aqualuxe_security_logs', array());
        $logs[] = $log_entry;
        
        // Keep only last 100 entries
        if (count($logs) > 100) {
            $logs = array_slice($logs, -100);
        }
        
        update_option('aqualuxe_security_logs', $logs);
    }
}

/**
 * Enhanced Content Security Policy
 */
function aqualuxe_content_security_policy() {
    // Base CSP
    $csp_directives = array(
        'default-src' => "'self'",
        'script-src' => "'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://maps.googleapis.com",
        'style-src' => "'self' 'unsafe-inline' https://fonts.googleapis.com",
        'font-src' => "'self' https://fonts.gstatic.com data:",
        'img-src' => "'self' data: https: http:",
        'connect-src' => "'self' https://www.google-analytics.com",
        'media-src' => "'self'",
        'object-src' => "'none'",
        'frame-src' => "'self' https://www.youtube.com https://player.vimeo.com https://www.google.com",
        'frame-ancestors' => "'self'",
        'form-action' => "'self'",
        'base-uri' => "'self'",
        'worker-src' => "'self'",
        'manifest-src' => "'self'",
    );
    
    // Allow customization via filter
    $csp_directives = apply_filters('aqualuxe_csp_directives', $csp_directives);
    
    // Build CSP string
    $csp = '';
    foreach ($csp_directives as $directive => $sources) {
        $csp .= $directive . ' ' . $sources . '; ';
    }
    
    // Apply final filter
    $csp = apply_filters('aqualuxe_content_security_policy', $csp);
    
    header("Content-Security-Policy: " . trim($csp));
}

/**
 * Enhanced security headers
 */
function aqualuxe_add_security_headers() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Feature Policy (Permissions Policy)
    $permissions = array(
        'camera' => '()',
        'microphone' => '()',
        'geolocation' => '(self)',
        'payment' => '(self)',
        'usb' => '()',
        'magnetometer' => '()',
        'gyroscope' => '()',
        'fullscreen' => '(self)',
        'autoplay' => '(self)',
    );
    
    $permissions = apply_filters('aqualuxe_permissions_policy', $permissions);
    
    $permissions_policy = '';
    foreach ($permissions as $feature => $allowlist) {
        $permissions_policy .= $feature . '=' . $allowlist . ', ';
    }
    
    header('Permissions-Policy: ' . rtrim($permissions_policy, ', '));
    
    // HSTS (HTTP Strict Transport Security) - only on HTTPS
    if (is_ssl()) {
        $hsts_max_age = apply_filters('aqualuxe_hsts_max_age', 31536000); // 1 year
        header('Strict-Transport-Security: max-age=' . $hsts_max_age . '; includeSubDomains; preload');
    }
    
    // Cross-Origin policies
    header('Cross-Origin-Embedder-Policy: unsafe-none');
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Resource-Policy: same-site');
}

// Enable security headers
add_action('send_headers', 'aqualuxe_add_security_headers', 1);

// Enable CSP if not in development
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    add_action('send_headers', 'aqualuxe_content_security_policy', 2);
}

/**
 * Enhanced login security
 */
function aqualuxe_enhanced_login_security() {
    // Limit login attempts
    add_action('wp_login_failed', 'aqualuxe_login_failed');
    add_action('wp_authenticate_user', 'aqualuxe_authenticate_user', 10, 2);
    add_filter('authenticate', 'aqualuxe_authenticate_username_case', 20, 3);
}
add_action('init', 'aqualuxe_enhanced_login_security');

/**
 * Handle failed login attempts
 */
function aqualuxe_login_failed($username) {
    $ip = aqualuxe_get_client_ip();
    $attempts_key = 'aqualuxe_login_attempts_' . md5($ip);
    $lockout_key = 'aqualuxe_login_lockout_' . md5($ip);
    
    // Check if already locked out
    if (get_transient($lockout_key)) {
        return;
    }
    
    $attempts = get_transient($attempts_key) ?: 0;
    $attempts++;
    
    $max_attempts = apply_filters('aqualuxe_max_login_attempts', 5);
    $lockout_duration = apply_filters('aqualuxe_login_lockout_duration', 15 * MINUTE_IN_SECONDS);
    
    if ($attempts >= $max_attempts) {
        set_transient($lockout_key, true, $lockout_duration);
        aqualuxe_log_security_event('login_lockout', array(
            'username' => $username,
            'ip' => $ip,
            'attempts' => $attempts
        ));
        
        // Send notification email to admin
        if (apply_filters('aqualuxe_notify_login_lockout', true)) {
            $admin_email = get_option('admin_email');
            $subject = sprintf(__('[%s] Login Lockout Notification', 'aqualuxe'), get_bloginfo('name'));
            $message = sprintf(
                __("A login lockout has been triggered.\n\nIP Address: %s\nUsername: %s\nAttempts: %d\nTime: %s", 'aqualuxe'),
                $ip,
                $username,
                $attempts,
                current_time('mysql')
            );
            wp_mail($admin_email, $subject, $message);
        }
    } else {
        set_transient($attempts_key, $attempts, 1 * HOUR_IN_SECONDS);
    }
    
    aqualuxe_log_security_event('login_failed', array(
        'username' => $username,
        'ip' => $ip,
        'attempt' => $attempts
    ));
}

/**
 * Check for login lockout
 */
function aqualuxe_authenticate_user($user, $password) {
    if (is_wp_error($user)) {
        return $user;
    }
    
    $ip = aqualuxe_get_client_ip();
    $lockout_key = 'aqualuxe_login_lockout_' . md5($ip);
    
    if (get_transient($lockout_key)) {
        $error = new WP_Error('login_locked', __('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        return $error;
    }
    
    // Clear attempts on successful login
    $attempts_key = 'aqualuxe_login_attempts_' . md5($ip);
    delete_transient($attempts_key);
    
    return $user;
}

/**
 * Prevent username enumeration
 */
function aqualuxe_authenticate_username_case($user, $username, $password) {
    if (is_wp_error($user) || empty($username) || empty($password)) {
        return $user;
    }
    
    // Log suspicious activity if someone tries to enumerate users
    if (!empty($username) && empty($password)) {
        aqualuxe_log_security_event('username_enumeration_attempt', array(
            'username' => $username,
            'ip' => aqualuxe_get_client_ip()
        ));
    }
    
    return $user;
}

/**
 * Get client IP address safely
 */
function aqualuxe_get_client_ip() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Validate IP
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return $ip;
    }
    
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
}

/**
 * Hide WordPress version in feeds
 */
function aqualuxe_hide_wp_version_rss() {
    return '';
}
add_filter('the_generator', 'aqualuxe_hide_wp_version_rss');

/**
 * Disable file editing in admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Remove WordPress version from CSS and JS files
 */
function aqualuxe_remove_wp_version_strings($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'aqualuxe_remove_wp_version_strings', 15, 1);
add_filter('style_loader_src', 'aqualuxe_remove_wp_version_strings', 15, 1);

/**
 * Disable directory browsing
 */
function aqualuxe_disable_directory_browsing() {
    if (!is_admin()) {
        $request = $_SERVER['REQUEST_URI'];
        if (preg_match('/\/$/', $request) && !is_front_page() && !is_home()) {
            $path = ABSPATH . ltrim($request, '/');
            if (is_dir($path) && !file_exists($path . 'index.php') && !file_exists($path . 'index.html')) {
                wp_redirect(home_url(), 301);
                exit;
            }
        }
    }
}
add_action('init', 'aqualuxe_disable_directory_browsing');

/**
 * Enhanced file upload security
 */
function aqualuxe_enhanced_upload_security($file) {
    // Check file size
    $max_size = apply_filters('aqualuxe_max_upload_size', 5 * MB_IN_BYTES);
    if ($file['size'] > $max_size) {
        $file['error'] = sprintf(__('File size exceeds maximum allowed size of %s.', 'aqualuxe'), size_format($max_size));
        return $file;
    }
    
    // Check file extension
    $allowed_extensions = apply_filters('aqualuxe_allowed_upload_extensions', array(
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        'pdf', 'doc', 'docx', 'txt', 'rtf',
        'mp3', 'wav', 'mp4', 'mov', 'avi',
        'zip', 'tar', 'gz'
    ));
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        $file['error'] = __('File type not allowed.', 'aqualuxe');
        return $file;
    }
    
    // Check for malicious content in uploads
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $dangerous_types = array(
            'application/x-executable',
            'application/x-dosexec',
            'application/x-msdownload',
            'application/x-shockwave-flash'
        );
        
        if (in_array($mime_type, $dangerous_types)) {
            $file['error'] = __('File contains potentially harmful content.', 'aqualuxe');
            aqualuxe_log_security_event('malicious_upload_attempt', array(
                'filename' => $file['name'],
                'mime_type' => $mime_type,
                'ip' => aqualuxe_get_client_ip()
            ));
            return $file;
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'aqualuxe_enhanced_upload_security');

/**
 * Enhanced input validation for common data types
 */
function aqualuxe_validate_input($input, $type = 'text') {
    switch ($type) {
        case 'email':
            return sanitize_email($input);
        case 'url':
            return esc_url_raw($input);
        case 'integer':
            return absint($input);
        case 'float':
            return floatval($input);
        case 'boolean':
            return (bool) $input;
        case 'textarea':
            return wp_kses_post($input);
        case 'slug':
            return sanitize_title($input);
        case 'key':
            return sanitize_key($input);
        case 'text':
        default:
            return sanitize_text_field($input);
    }
}

/**
 * Secure AJAX handler wrapper with enhanced security
 */
function aqualuxe_secure_ajax_handler($action, $callback, $public = false, $logged_in = true) {
    $ajax_action = 'wp_ajax_aqualuxe_' . $action;
    
    if ($public) {
        $ajax_action_nopriv = 'wp_ajax_nopriv_aqualuxe_' . $action;
        add_action($ajax_action_nopriv, function() use ($callback, $action) {
            aqualuxe_ajax_security_check($action);
            call_user_func($callback);
        });
    }
    
    if ($logged_in) {
        add_action($ajax_action, function() use ($callback, $action) {
            aqualuxe_ajax_security_check($action);
            call_user_func($callback);
        });
    }
}

/**
 * AJAX security check
 */
function aqualuxe_ajax_security_check($action) {
    // Verify nonce
    if (!aqualuxe_verify_nonce($_POST['nonce'] ?? '', $action)) {
        wp_die(__('Security check failed.', 'aqualuxe'), __('Security Error', 'aqualuxe'), array('response' => 403));
    }
    
    // Rate limiting
    if (!aqualuxe_rate_limit_check($action)) {
        wp_die(__('Too many requests. Please try again later.', 'aqualuxe'), __('Rate Limit Exceeded', 'aqualuxe'), array('response' => 429));
    }
    
    // Check user capabilities for admin actions
    if (strpos($action, 'admin_') === 0 && !current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions.', 'aqualuxe'), __('Permission Error', 'aqualuxe'), array('response' => 403));
    }
}